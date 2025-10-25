<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminApiController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Get platform dashboard data
     */
    public function getDashboard(Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $stats = $this->adminService->getPlatformStats();
        $metrics = $this->adminService->getPlatformMetrics($request->get('period', 30));

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'metrics' => $metrics,
                'admin' => $admin
            ]
        ]);
    }

    /**
     * Get all sellers
     */
    public function getSellers(Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $sellers = Seller::with(['user', 'shops', 'subscriptions'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('verification_status'), function ($query, $status) {
                return $query->where('verification_status', $status);
            })
            ->when($request->get('search'), function ($query, $search) {
                return $query->where('business_name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $sellers
        ]);
    }

    /**
     * Get seller details
     */
    public function getSeller(Seller $seller): JsonResponse
    {
        $seller->load(['user', 'shops.products', 'subscriptions', 'earnings', 'reviews']);

        return response()->json([
            'success' => true,
            'data' => $seller
        ]);
    }

    /**
     * Approve seller
     */
    public function approveSeller(Seller $seller, Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $seller = $this->adminService->approveSeller($seller, $admin);

        return response()->json([
            'success' => true,
            'message' => 'Seller approved successfully',
            'data' => $seller
        ]);
    }

    /**
     * Suspend seller
     */
    public function suspendSeller(Seller $seller, Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $seller = $this->adminService->suspendSeller($seller, $admin, $validated['reason']);

        return response()->json([
            'success' => true,
            'message' => 'Seller suspended successfully',
            'data' => $seller
        ]);
    }

    /**
     * Get all products
     */
    public function getProducts(Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $products = Product::with(['seller.user', 'shop', 'reviews'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('category'), function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->get('seller_id'), function ($query, $sellerId) {
                return $query->where('seller_id', $sellerId);
            })
            ->when($request->get('search'), function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get all orders
     */
    public function getOrders(Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $orders = Order::with(['user', 'items.product.seller', 'tracking'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('date_from'), function ($query, $date) {
                return $query->where('created_at', '>=', $date);
            })
            ->when($request->get('date_to'), function ($query, $date) {
                return $query->where('created_at', '<=', $date);
            })
            ->when($request->get('search'), function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                           ->orWhereHas('user', function ($q) use ($search) {
                               $q->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                           });
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Get platform analytics
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $period = $request->get('period', 30);
        $metrics = $this->adminService->getPlatformMetrics($period);

        // Get additional analytics data
        $analytics = [
            'sales_chart' => $this->getSalesChartData($period),
            'seller_growth' => $this->getSellerGrowthData($period),
            'top_sellers' => $this->getTopSellers($period),
            'category_performance' => $this->getCategoryPerformance($period),
            'revenue_breakdown' => $this->getRevenueBreakdown($period)
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'metrics' => $metrics,
                'analytics' => $analytics
            ]
        ]);
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $validated = $request->validate([
            'type' => 'required|in:sales,sellers,products,earnings',
            'filters' => 'sometimes|array'
        ]);

        $report = $this->adminService->generateReport($validated['type'], $validated['filters'] ?? []);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Update platform settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $admin = $request->user()->admin;
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $validated = $request->validate([
            'settings' => 'required|array'
        ]);

        $this->adminService->updatePlatformSettings($validated['settings'], $admin);

        return response()->json([
            'success' => true,
            'message' => 'Platform settings updated successfully'
        ]);
    }

    /**
     * Get sales chart data
     */
    private function getSalesChartData(int $period): array
    {
        $data = [];
        $startDate = now()->subDays($period);

        for ($i = $period; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sales = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total');
            
            $data[] = [
                'date' => $date,
                'sales' => $sales
            ];
        }

        return $data;
    }

    /**
     * Get seller growth data
     */
    private function getSellerGrowthData(int $period): array
    {
        $data = [];
        $startDate = now()->subDays($period);

        for ($i = $period; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $newSellers = Seller::whereDate('created_at', $date)->count();
            
            $data[] = [
                'date' => $date,
                'new_sellers' => $newSellers
            ];
        }

        return $data;
    }

    /**
     * Get top sellers
     */
    private function getTopSellers(int $period): array
    {
        $startDate = now()->subDays($period);
        
        return Seller::with(['user'])
            ->whereHas('orders', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('status', 'completed');
            })
            ->withCount(['orders' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('status', 'completed');
            }])
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get category performance
     */
    private function getCategoryPerformance(int $period): array
    {
        $startDate = now()->subDays($period);
        
        return Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.status', 'completed')
            ->groupBy('products.category')
            ->selectRaw('products.category, count(*) as orders, sum(order_items.quantity * order_items.price) as revenue')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    /**
     * Get revenue breakdown
     */
    private function getRevenueBreakdown(int $period): array
    {
        $startDate = now()->subDays($period);
        
        return [
            'total_revenue' => Order::where('created_at', '>=', $startDate)->where('status', 'completed')->sum('total'),
            'commission_earned' => \App\Models\Earning::where('created_at', '>=', $startDate)->where('status', 'paid')->sum('commission_amount'),
            'platform_fee_earned' => \App\Models\Earning::where('created_at', '>=', $startDate)->where('status', 'paid')->sum('platform_fee'),
            'subscription_revenue' => \App\Models\Subscription::where('created_at', '>=', $startDate)->where('status', 'active')->sum('price')
        ];
    }
}


























