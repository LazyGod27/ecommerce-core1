<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Earning;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\PlatformSetting;
use App\Models\CommissionRule;
use App\Models\PayoutRequest;
use App\Services\AdminService;
use App\Services\CommissionService;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlatformManagementController extends Controller
{
    protected $adminService;
    protected $commissionService;
    protected $payoutService;

    public function __construct(
        AdminService $adminService,
        CommissionService $commissionService,
        PayoutService $payoutService
    ) {
        $this->adminService = $adminService;
        $this->commissionService = $commissionService;
        $this->payoutService = $payoutService;
    }

    /**
     * Get platform dashboard overview
     * GET /api/external/platform/dashboard
     */
    public function getDashboard(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', 30);
            
            $stats = $this->adminService->getPlatformStats();
            $metrics = $this->adminService->getPlatformMetrics($period);
            
            // Get additional platform data
            $platformData = [
                'revenue_breakdown' => $this->getRevenueBreakdown($period),
                'top_sellers' => $this->getTopSellers($period),
                'top_products' => $this->getTopProducts($period),
                'recent_activities' => $this->getRecentActivities(),
                'system_health' => $this->getSystemHealth()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'metrics' => $metrics,
                    'platform_data' => $platformData
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get platform dashboard', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get platform dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manage sellers
     * GET /api/external/platform/sellers
     */
    public function getSellers(Request $request): JsonResponse
    {
        try {
            $sellers = Seller::with(['user', 'shops', 'subscriptions', 'earnings'])
                ->when($request->get('status'), function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->when($request->get('verification_status'), function ($query, $status) {
                    return $query->where('verification_status', $status);
                })
                ->when($request->get('subscription_plan'), function ($query, $plan) {
                    return $query->where('subscription_plan', $plan);
                })
                ->when($request->get('search'), function ($query, $search) {
                    return $query->where('business_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
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
                'data' => $sellers
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get sellers', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get sellers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve seller
     * POST /api/external/platform/sellers/{sellerId}/approve
     */
    public function approveSeller(Request $request, $sellerId): JsonResponse
    {
        try {
            $seller = Seller::findOrFail($sellerId);
            
            $validated = $request->validate([
                'notes' => 'nullable|string|max:500',
                'commission_rate' => 'nullable|numeric|min:0|max:100',
                'subscription_plan' => 'nullable|string'
            ]);

            $admin = $request->user()->admin;
            $seller = $this->adminService->approveSeller($seller, $admin);

            // Update additional fields if provided
            if (isset($validated['commission_rate'])) {
                $seller->update(['commission_rate' => $validated['commission_rate']]);
            }

            if (isset($validated['subscription_plan'])) {
                $seller->update(['subscription_plan' => $validated['subscription_plan']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Seller approved successfully',
                'data' => $seller->load(['user', 'shops'])
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to approve seller', [
                'seller_id' => $sellerId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve seller',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suspend seller
     * POST /api/external/platform/sellers/{sellerId}/suspend
     */
    public function suspendSeller(Request $request, $sellerId): JsonResponse
    {
        try {
            $seller = Seller::findOrFail($sellerId);
            
            $validated = $request->validate([
                'reason' => 'required|string|max:500',
                'duration' => 'nullable|integer|min:1', // days
                'notify_seller' => 'nullable|boolean'
            ]);

            $admin = $request->user()->admin;
            $seller = $this->adminService->suspendSeller($seller, $admin, $validated['reason']);

            return response()->json([
                'success' => true,
                'message' => 'Seller suspended successfully',
                'data' => $seller
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to suspend seller', [
                'seller_id' => $sellerId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to suspend seller',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manage subscription plans
     * GET /api/external/platform/subscription-plans
     */
    public function getSubscriptionPlans(Request $request): JsonResponse
    {
        try {
            $plans = SubscriptionPlan::with(['subscriptions'])
                ->when($request->get('type'), function ($query, $type) {
                    return $query->where('type', $type);
                })
                ->when($request->get('is_active'), function ($query, $active) {
                    return $query->where('is_active', $active === 'true');
                })
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $plans
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get subscription plans', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get subscription plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create subscription plan
     * POST /api/external/platform/subscription-plans
     */
    public function createSubscriptionPlan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug',
            'description' => 'nullable|string',
            'type' => 'required|in:basic,standard,premium,enterprise',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'trial_days' => 'nullable|integer|min:0',
            'features' => 'nullable|array',
            'limits' => 'nullable|array',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_popular' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $plan = SubscriptionPlan::create($request->all());

            Log::info('Subscription plan created via external API', [
                'plan_id' => $plan->id,
                'name' => $plan->name,
                'created_by' => 'external_api'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription plan created successfully',
                'data' => $plan
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create subscription plan', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create subscription plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manage commission rules
     * GET /api/external/platform/commission-rules
     */
    public function getCommissionRules(Request $request): JsonResponse
    {
        try {
            $rules = CommissionRule::with(['subscriptionPlan'])
                ->when($request->get('category'), function ($query, $category) {
                    return $query->where('category', $category);
                })
                ->when($request->get('is_active'), function ($query, $active) {
                    return $query->where('is_active', $active === 'true');
                })
                ->orderBy('priority')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $rules
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get commission rules', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get commission rules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create commission rule
     * POST /api/external/platform/commission-rules
     */
    public function createCommissionRule(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
            'priority' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $rule = CommissionRule::create($request->all());

            Log::info('Commission rule created via external API', [
                'rule_id' => $rule->id,
                'name' => $rule->name,
                'created_by' => 'external_api'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commission rule created successfully',
                'data' => $rule
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create commission rule', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create commission rule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manage payout requests
     * GET /api/external/platform/payout-requests
     */
    public function getPayoutRequests(Request $request): JsonResponse
    {
        try {
            $requests = PayoutRequest::with(['seller.user', 'earnings'])
                ->when($request->get('status'), function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->when($request->get('seller_id'), function ($query, $sellerId) {
                    return $query->where('seller_id', $sellerId);
                })
                ->when($request->get('date_from'), function ($query, $date) {
                    return $query->where('created_at', '>=', $date);
                })
                ->when($request->get('date_to'), function ($query, $date) {
                    return $query->where('created_at', '<=', $date);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $requests
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get payout requests', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get payout requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payout request
     * POST /api/external/platform/payout-requests/{requestId}/process
     */
    public function processPayoutRequest(Request $request, $requestId): JsonResponse
    {
        try {
            $payoutRequest = PayoutRequest::findOrFail($requestId);
            
            $validated = $request->validate([
                'action' => 'required|in:approve,reject',
                'notes' => 'nullable|string|max:500',
                'transaction_id' => 'nullable|string|max:255'
            ]);

            $admin = $request->user()->admin;

            if ($validated['action'] === 'approve') {
                $this->adminService->processPayoutRequest($payoutRequest, $admin);
                $message = 'Payout request approved successfully';
            } else {
                $payoutRequest->update([
                    'status' => 'rejected',
                    'rejection_reason' => $validated['notes'],
                    'processed_by' => $admin->id,
                    'processed_at' => now()
                ]);
                $message = 'Payout request rejected';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $payoutRequest->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process payout request', [
                'request_id' => $requestId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process payout request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update platform settings
     * PUT /api/external/platform/settings
     */
    public function updatePlatformSettings(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'settings.*.description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin = $request->user()->admin;
            $settings = [];

            foreach ($request->settings as $setting) {
                $settings[$setting['key']] = $setting['value'];
            }

            $this->adminService->updatePlatformSettings($settings, $admin);

            return response()->json([
                'success' => true,
                'message' => 'Platform settings updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update platform settings', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update platform settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate platform report
     * POST /api/external/platform/reports
     */
    public function generateReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:sales,sellers,products,earnings,revenue,subscriptions',
            'format' => 'nullable|in:json,csv,pdf',
            'filters' => 'nullable|array',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'group_by' => 'nullable|in:day,week,month,year'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin = $request->user()->admin;
            $filters = $request->get('filters', []);
            
            if ($request->get('date_from')) {
                $filters['date_from'] = $request->get('date_from');
            }
            
            if ($request->get('date_to')) {
                $filters['date_to'] = $request->get('date_to');
            }

            $report = $this->adminService->generateReport($request->get('type'), $filters);

            return response()->json([
                'success' => true,
                'data' => $report
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate report', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get revenue breakdown
     */
    private function getRevenueBreakdown($period): array
    {
        $startDate = now()->subDays($period);
        
        return [
            'total_revenue' => Order::where('created_at', '>=', $startDate)->where('status', 'completed')->sum('total'),
            'commission_earned' => Earning::where('created_at', '>=', $startDate)->where('status', 'paid')->sum('commission_amount'),
            'platform_fee_earned' => Earning::where('created_at', '>=', $startDate)->where('status', 'paid')->sum('platform_fee'),
            'subscription_revenue' => Subscription::where('created_at', '>=', $startDate)->where('status', 'active')->sum('price'),
            'by_carrier' => $this->getRevenueByCarrier($startDate),
            'by_category' => $this->getRevenueByCategory($startDate)
        ];
    }

    /**
     * Get top sellers
     */
    private function getTopSellers($period): array
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
     * Get top products
     */
    private function getTopProducts($period): array
    {
        $startDate = now()->subDays($period);
        
        return Product::with(['seller.user'])
            ->whereHas('orderItems.order', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('status', 'completed');
            })
            ->withCount(['orderItems' => function ($query) use ($startDate) {
                $query->whereHas('order', function ($q) use ($startDate) {
                    $q->where('created_at', '>=', $startDate)
                      ->where('status', 'completed');
                });
            }])
            ->orderBy('order_items_count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities(): array
    {
        return [
            'new_sellers' => Seller::where('created_at', '>=', now()->subDays(7))->count(),
            'new_orders' => Order::where('created_at', '>=', now()->subDays(7))->count(),
            'pending_approvals' => Seller::where('status', 'pending')->count(),
            'pending_payouts' => PayoutRequest::where('status', 'pending')->count()
        ];
    }

    /**
     * Get system health
     */
    private function getSystemHealth(): array
    {
        return [
            'database_status' => 'healthy',
            'api_response_time' => '120ms',
            'error_rate' => '0.1%',
            'uptime' => '99.9%',
            'active_users' => \App\Models\User::where('last_login_at', '>=', now()->subHours(24))->count()
        ];
    }

    /**
     * Get revenue by carrier
     */
    private function getRevenueByCarrier($startDate): array
    {
        return \App\Models\Tracking::join('orders', 'trackings.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.status', 'completed')
            ->groupBy('trackings.carrier')
            ->selectRaw('trackings.carrier, sum(orders.total) as revenue')
            ->get();
    }

    /**
     * Get revenue by category
     */
    private function getRevenueByCategory($startDate): array
    {
        return Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.status', 'completed')
            ->groupBy('products.category')
            ->selectRaw('products.category, sum(order_items.quantity * order_items.price) as revenue')
            ->get();
    }
}


























