<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Earning;
use App\Models\Subscription;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminService
{
    /**
     * Create a new admin account
     */
    public function createAdmin(array $data): Admin
    {
        return DB::transaction(function () use ($data) {
            $admin = Admin::create($data);
            
            Log::info('New admin created', ['admin_id' => $admin->id, 'user_id' => $admin->user_id, 'role' => $admin->role]);
            
            return $admin;
        });
    }

    /**
     * Update admin information
     */
    public function updateAdmin(Admin $admin, array $data): Admin
    {
        return DB::transaction(function () use ($admin, $data) {
            $admin->update($data);
            
            Log::info('Admin updated', ['admin_id' => $admin->id, 'updated_fields' => array_keys($data)]);
            
            return $admin;
        });
    }

    /**
     * Get platform dashboard statistics
     */
    public function getPlatformStats(): array
    {
        $stats = [
            'total_sellers' => Seller::count(),
            'active_sellers' => Seller::where('status', 'active')->count(),
            'pending_sellers' => Seller::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'total_commission' => Earning::where('status', 'paid')->sum('commission_amount'),
            'total_platform_fee' => Earning::where('status', 'paid')->sum('platform_fee'),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'expired_subscriptions' => Subscription::where('status', 'expired')->count()
        ];

        return $stats;
    }

    /**
     * Get platform performance metrics
     */
    public function getPlatformMetrics($period = '30'): array
    {
        $startDate = now()->subDays($period);
        
        $metrics = [
            'period' => $period . ' days',
            'new_sellers' => Seller::where('created_at', '>=', $startDate)->count(),
            'new_products' => Product::where('created_at', '>=', $startDate)->count(),
            'new_orders' => Order::where('created_at', '>=', $startDate)->count(),
            'revenue' => Order::where('created_at', '>=', $startDate)->where('status', 'completed')->sum('total'),
            'commission_earned' => Earning::where('created_at', '>=', $startDate)->where('status', 'paid')->sum('commission_amount'),
            'platform_fee_earned' => Earning::where('created_at', '>=', $startDate)->where('status', 'paid')->sum('platform_fee'),
            'new_subscriptions' => Subscription::where('created_at', '>=', $startDate)->count(),
            'cancelled_subscriptions' => Subscription::where('cancelled_at', '>=', $startDate)->count(),
            'average_order_value' => $this->calculateAverageOrderValue($startDate),
            'seller_conversion_rate' => $this->calculateSellerConversionRate($startDate)
        ];

        return $metrics;
    }

    /**
     * Approve seller application
     */
    public function approveSeller(Seller $seller, Admin $admin): Seller
    {
        return DB::transaction(function () use ($seller, $admin) {
            $seller->update([
                'status' => 'active',
                'verification_status' => 'verified',
                'is_verified' => true
            ]);

            // Activate seller's shops
            $seller->shops()->update(['status' => 'active']);

            Log::info('Seller approved', [
                'seller_id' => $seller->id,
                'admin_id' => $admin->id,
                'approved_by' => $admin->user->name
            ]);

            return $seller;
        });
    }

    /**
     * Suspend seller account
     */
    public function suspendSeller(Seller $seller, Admin $admin, string $reason = null): Seller
    {
        return DB::transaction(function () use ($seller, $admin, $reason) {
            $seller->update([
                'status' => 'suspended'
            ]);

            // Suspend seller's shops and products
            $seller->shops()->update(['status' => 'suspended']);
            $seller->products()->update(['status' => 'suspended']);

            Log::info('Seller suspended', [
                'seller_id' => $seller->id,
                'admin_id' => $admin->id,
                'reason' => $reason,
                'suspended_by' => $admin->user->name
            ]);

            return $seller;
        });
    }

    /**
     * Process payout request
     */
    public function processPayoutRequest($payoutRequest, Admin $admin): void
    {
        DB::transaction(function () use ($payoutRequest, $admin) {
            $payoutRequest->update([
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now()
            ]);

            // Update earnings status
            $payoutRequest->earnings()->update(['status' => 'paid']);

            Log::info('Payout request approved', [
                'payout_request_id' => $payoutRequest->id,
                'seller_id' => $payoutRequest->seller_id,
                'amount' => $payoutRequest->amount,
                'admin_id' => $admin->id,
                'approved_by' => $admin->user->name
            ]);
        });
    }

    /**
     * Update platform settings
     */
    public function updatePlatformSettings(array $settings, Admin $admin): void
    {
        DB::transaction(function () use ($settings, $admin) {
            foreach ($settings as $key => $value) {
                PlatformSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'updated_by' => $admin->id
                    ]
                );
            }

            Log::info('Platform settings updated', [
                'admin_id' => $admin->id,
                'updated_by' => $admin->user->name,
                'settings' => array_keys($settings)
            ]);
        });
    }

    /**
     * Generate platform report
     */
    public function generateReport(string $type, array $filters = []): array
    {
        $report = [
            'type' => $type,
            'generated_at' => now(),
            'filters' => $filters
        ];

        switch ($type) {
            case 'sales':
                $report['data'] = $this->generateSalesReport($filters);
                break;
            case 'sellers':
                $report['data'] = $this->generateSellersReport($filters);
                break;
            case 'products':
                $report['data'] = $this->generateProductsReport($filters);
                break;
            case 'earnings':
                $report['data'] = $this->generateEarningsReport($filters);
                break;
            default:
                throw new \InvalidArgumentException('Invalid report type');
        }

        return $report;
    }

    /**
     * Calculate average order value
     */
    private function calculateAverageOrderValue($startDate): float
    {
        $orders = Order::where('created_at', '>=', $startDate)->get();
        
        return $orders->count() > 0 ? $orders->avg('total') : 0;
    }

    /**
     * Calculate seller conversion rate
     */
    private function calculateSellerConversionRate($startDate): float
    {
        $totalSellers = Seller::where('created_at', '>=', $startDate)->count();
        $activeSellers = Seller::where('created_at', '>=', $startDate)->where('status', 'active')->count();
        
        return $totalSellers > 0 ? ($activeSellers / $totalSellers) * 100 : 0;
    }

    /**
     * Generate sales report
     */
    private function generateSalesReport(array $filters): array
    {
        $query = Order::query();
        
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        return [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total'),
            'average_order_value' => $query->avg('total'),
            'orders_by_status' => $query->groupBy('status')->selectRaw('status, count(*) as count')->get()
        ];
    }

    /**
     * Generate sellers report
     */
    private function generateSellersReport(array $filters): array
    {
        $query = Seller::query();
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return [
            'total_sellers' => $query->count(),
            'active_sellers' => $query->where('status', 'active')->count(),
            'sellers_by_status' => $query->groupBy('status')->selectRaw('status, count(*) as count')->get(),
            'top_sellers' => $query->orderBy('total_earnings', 'desc')->limit(10)->get()
        ];
    }

    /**
     * Generate products report
     */
    private function generateProductsReport(array $filters): array
    {
        $query = Product::query();
        
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return [
            'total_products' => $query->count(),
            'active_products' => $query->where('status', 'active')->count(),
            'products_by_category' => $query->groupBy('category')->selectRaw('category, count(*) as count')->get(),
            'top_products' => $query->orderBy('review_count', 'desc')->limit(10)->get()
        ];
    }

    /**
     * Generate earnings report
     */
    private function generateEarningsReport(array $filters): array
    {
        $query = Earning::query();
        
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        return [
            'total_earnings' => $query->sum('amount'),
            'total_commission' => $query->sum('commission_amount'),
            'total_platform_fee' => $query->sum('platform_fee'),
            'earnings_by_status' => $query->groupBy('status')->selectRaw('status, sum(net_amount) as total')->get()
        ];
    }
}











