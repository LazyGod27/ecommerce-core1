<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\Earning;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SellerService
{
    /**
     * Create a new seller account
     */
    public function createSeller(array $data): Seller
    {
        return DB::transaction(function () use ($data) {
            $seller = Seller::create($data);
            
            // Create default shop
            $this->createDefaultShop($seller);
            
            // Log the creation
            Log::info('New seller created', ['seller_id' => $seller->id, 'user_id' => $seller->user_id]);
            
            return $seller;
        });
    }

    /**
     * Create default shop for seller
     */
    public function createDefaultShop(Seller $seller): Shop
    {
        $shopData = [
            'seller_id' => $seller->id,
            'name' => $seller->business_name,
            'slug' => $this->generateUniqueSlug($seller->business_name),
            'description' => $seller->description,
            'category' => 'general',
            'status' => 'draft'
        ];

        return Shop::create($shopData);
    }

    /**
     * Update seller information
     */
    public function updateSeller(Seller $seller, array $data): Seller
    {
        return DB::transaction(function () use ($seller, $data) {
            $seller->update($data);
            
            Log::info('Seller updated', ['seller_id' => $seller->id, 'updated_fields' => array_keys($data)]);
            
            return $seller;
        });
    }

    /**
     * Calculate seller earnings for an order
     */
    public function calculateEarnings(Order $order): array
    {
        $earnings = [];
        $totalCommission = 0;
        $totalPlatformFee = 0;

        foreach ($order->items as $item) {
            $product = $item->product;
            $seller = $product->seller;
            
            if (!$seller) continue;

            $itemTotal = $item->quantity * $item->price;
            $commissionRate = $seller->commission_rate / 100;
            $commissionAmount = $itemTotal * $commissionRate;
            $platformFee = $itemTotal * 0.05; // 5% platform fee
            $netAmount = $itemTotal - $commissionAmount - $platformFee;

            $earnings[] = [
                'seller_id' => $seller->id,
                'order_id' => $order->id,
                'product_id' => $product->id,
                'amount' => $itemTotal,
                'commission_rate' => $seller->commission_rate,
                'commission_amount' => $commissionAmount,
                'platform_fee' => $platformFee,
                'net_amount' => $netAmount,
                'currency' => 'PHP',
                'status' => 'pending'
            ];

            $totalCommission += $commissionAmount;
            $totalPlatformFee += $platformFee;
        }

        return [
            'earnings' => $earnings,
            'total_commission' => $totalCommission,
            'total_platform_fee' => $totalPlatformFee
        ];
    }

    /**
     * Process seller earnings for an order
     */
    public function processEarnings(Order $order): void
    {
        $earningsData = $this->calculateEarnings($order);
        
        foreach ($earningsData['earnings'] as $earningData) {
            Earning::create($earningData);
        }

        Log::info('Earnings processed for order', [
            'order_id' => $order->id,
            'total_commission' => $earningsData['total_commission'],
            'total_platform_fee' => $earningsData['total_platform_fee']
        ]);
    }

    /**
     * Get seller dashboard statistics
     */
    public function getDashboardStats(Seller $seller): array
    {
        $stats = [
            'total_products' => $seller->products()->count(),
            'active_products' => $seller->products()->where('status', 'active')->count(),
            'total_orders' => $seller->orders()->count(),
            'pending_orders' => $seller->orders()->where('status', 'pending')->count(),
            'completed_orders' => $seller->orders()->where('status', 'completed')->count(),
            'total_earnings' => $seller->earnings()->where('status', 'paid')->sum('net_amount'),
            'pending_earnings' => $seller->earnings()->where('status', 'pending')->sum('net_amount'),
            'total_sales' => $seller->orders()->where('status', 'completed')->sum('total'),
            'average_rating' => $seller->reviews()->avg('rating') ?? 0,
            'total_reviews' => $seller->reviews()->count(),
            'shops_count' => $seller->shops()->count(),
            'active_shops' => $seller->shops()->where('status', 'active')->count()
        ];

        return $stats;
    }

    /**
     * Get seller performance metrics
     */
    public function getPerformanceMetrics(Seller $seller, $period = '30'): array
    {
        $startDate = now()->subDays($period);
        
        $metrics = [
            'period' => $period . ' days',
            'orders_count' => $seller->orders()->where('created_at', '>=', $startDate)->count(),
            'revenue' => $seller->orders()->where('created_at', '>=', $startDate)->sum('total'),
            'earnings' => $seller->earnings()->where('created_at', '>=', $startDate)->sum('net_amount'),
            'new_products' => $seller->products()->where('created_at', '>=', $startDate)->count(),
            'new_reviews' => $seller->reviews()->where('created_at', '>=', $startDate)->count(),
            'conversion_rate' => $this->calculateConversionRate($seller, $startDate),
            'average_order_value' => $this->calculateAverageOrderValue($seller, $startDate)
        ];

        return $metrics;
    }

    /**
     * Generate unique slug for shop
     */
    private function generateUniqueSlug(string $name): string
    {
        $slug = \Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Shop::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Calculate conversion rate
     */
    private function calculateConversionRate(Seller $seller, $startDate): float
    {
        $totalViews = $seller->products()->sum('view_count') ?? 0;
        $totalOrders = $seller->orders()->where('created_at', '>=', $startDate)->count();
        
        return $totalViews > 0 ? ($totalOrders / $totalViews) * 100 : 0;
    }

    /**
     * Calculate average order value
     */
    private function calculateAverageOrderValue(Seller $seller, $startDate): float
    {
        $orders = $seller->orders()->where('created_at', '>=', $startDate)->get();
        
        return $orders->count() > 0 ? $orders->avg('total') : 0;
    }
}











