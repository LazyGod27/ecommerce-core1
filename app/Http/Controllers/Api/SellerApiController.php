<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SellerApiController extends Controller
{
    protected $sellerService;

    public function __construct(SellerService $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    /**
     * Get seller dashboard data
     */
    public function getDashboard(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        $stats = $this->sellerService->getDashboardStats($seller);
        $metrics = $this->sellerService->getPerformanceMetrics($seller, $request->get('period', 30));

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'metrics' => $metrics,
                'seller' => $seller
            ]
        ]);
    }

    /**
     * Get seller products
     */
    public function getProducts(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        $products = $seller->products()
            ->with(['reviews', 'shop'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('category'), function ($query, $category) {
                return $query->where('category', $category);
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get seller orders
     */
    public function getOrders(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        $orders = $seller->orders()
            ->with(['user', 'items.product'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('date_from'), function ($query, $date) {
                return $query->where('created_at', '>=', $date);
            })
            ->when($request->get('date_to'), function ($query, $date) {
                return $query->where('created_at', '<=', $date);
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Get seller earnings
     */
    public function getEarnings(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        $earnings = $seller->earnings()
            ->with(['order', 'product'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('date_from'), function ($query, $date) {
                return $query->where('created_at', '>=', $date);
            })
            ->when($request->get('date_to'), function ($query, $date) {
                return $query->where('created_at', '<=', $date);
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $earnings
        ]);
    }

    /**
     * Get seller shops
     */
    public function getShops(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        $shops = $seller->shops()
            ->with(['products'])
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $shops
        ]);
    }

    /**
     * Update seller profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        $validated = $request->validate([
            'business_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'phone' => 'sometimes|string|max:20',
            'website' => 'sometimes|url|max:255',
            'business_address' => 'sometimes|array',
            'shipping_policies' => 'sometimes|array',
            'return_policies' => 'sometimes|array'
        ]);

        $seller = $this->sellerService->updateSeller($seller, $validated);

        return response()->json([
            'success' => true,
            'data' => $seller
        ]);
    }

    /**
     * Get seller analytics
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        $period = $request->get('period', 30);
        $metrics = $this->sellerService->getPerformanceMetrics($seller, $period);

        // Get additional analytics data
        $analytics = [
            'sales_chart' => $this->getSalesChartData($seller, $period),
            'top_products' => $this->getTopProducts($seller, $period),
            'revenue_by_category' => $this->getRevenueByCategory($seller, $period),
            'order_status_distribution' => $this->getOrderStatusDistribution($seller, $period)
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
     * Get sales chart data
     */
    private function getSalesChartData(Seller $seller, int $period): array
    {
        $data = [];
        $startDate = now()->subDays($period);

        for ($i = $period; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sales = $seller->orders()
                ->whereDate('created_at', $date)
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
     * Get top products
     */
    private function getTopProducts(Seller $seller, int $period): array
    {
        $startDate = now()->subDays($period);
        
        return $seller->products()
            ->withCount(['orders' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('status', 'completed');
            }])
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get revenue by category
     */
    private function getRevenueByCategory(Seller $seller, int $period): array
    {
        $startDate = now()->subDays($period);
        
        return $seller->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.status', 'completed')
            ->groupBy('products.category')
            ->selectRaw('products.category, sum(order_items.quantity * order_items.price) as revenue')
            ->get();
    }

    /**
     * Get order status distribution
     */
    private function getOrderStatusDistribution(Seller $seller, int $period): array
    {
        $startDate = now()->subDays($period);
        
        return $seller->orders()
            ->where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->get();
    }
}











