<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SearchController;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'productSearch']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add/{product}', [CartController::class, 'add']);
    Route::put('/cart/update/{rowId}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    
    Route::post('/checkout', [CheckoutController::class, 'store']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/payment/intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook']);

Route::post('/payments/gcash/initiate', [PaymentController::class, 'initiateGcash']);
Route::post('/payments/gcash/webhook', [PaymentController::class, 'gcashWebhook']);

Route::post('/search/voice', [SearchController::class, 'voiceSearch']);
Route::post('/search/image', [App\Http\Controllers\Api\ImageSearchController::class, 'searchByImage']);

Route::post('/payments/process', [PaymentController::class, 'process']);
Route::post('/payments/refund', [PaymentController::class, 'refund']);

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

// Shipping Provider Webhooks
Route::post('/webhooks/shipping/jnt', [\App\Http\Controllers\Api\ShippingWebhookController::class, 'handleJntWebhook']);
Route::post('/webhooks/shipping/ninjavan', [\App\Http\Controllers\Api\ShippingWebhookController::class, 'handleNinjaVanWebhook']);
Route::post('/webhooks/shipping/lbc', [\App\Http\Controllers\Api\ShippingWebhookController::class, 'handleLbcWebhook']);
Route::post('/webhooks/shipping/flash', [\App\Http\Controllers\Api\ShippingWebhookController::class, 'handleFlashWebhook']);

Route::middleware('throttle:60,1')->group(function () {
    Route::post('/payments/gcash/webhook', [PaymentController::class, 'gcashWebhook']);
});

// Seller API Routes
Route::middleware(['auth:sanctum', 'seller'])->prefix('seller')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\SellerApiController::class, 'getDashboard']);
    Route::get('/products', [\App\Http\Controllers\Api\SellerApiController::class, 'getProducts']);
    Route::get('/orders', [\App\Http\Controllers\Api\SellerApiController::class, 'getOrders']);
    Route::get('/earnings', [\App\Http\Controllers\Api\SellerApiController::class, 'getEarnings']);
    Route::get('/shops', [\App\Http\Controllers\Api\SellerApiController::class, 'getShops']);
    Route::put('/profile', [\App\Http\Controllers\Api\SellerApiController::class, 'updateProfile']);
    Route::get('/analytics', [\App\Http\Controllers\Api\SellerApiController::class, 'getAnalytics']);
});

// Admin API Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\AdminApiController::class, 'getDashboard']);
    Route::get('/sellers', [\App\Http\Controllers\Api\AdminApiController::class, 'getSellers']);
    Route::get('/sellers/{seller}', [\App\Http\Controllers\Api\AdminApiController::class, 'getSeller']);
    Route::post('/sellers/{seller}/approve', [\App\Http\Controllers\Api\AdminApiController::class, 'approveSeller']);
    Route::post('/sellers/{seller}/suspend', [\App\Http\Controllers\Api\AdminApiController::class, 'suspendSeller']);
    Route::get('/products', [\App\Http\Controllers\Api\AdminApiController::class, 'getProducts']);
    Route::get('/orders', [\App\Http\Controllers\Api\AdminApiController::class, 'getOrders']);
    Route::get('/analytics', [\App\Http\Controllers\Api\AdminApiController::class, 'getAnalytics']);
    Route::post('/reports', [\App\Http\Controllers\Api\AdminApiController::class, 'generateReport']);
    Route::put('/settings', [\App\Http\Controllers\Api\AdminApiController::class, 'updateSettings']);
});

// External API Routes (for Core Transaction 2 team)
Route::middleware(['api.key:product_management'])->prefix('external')->group(function () {
    // Product Management
    Route::post('/products', [\App\Http\Controllers\Api\ProductManagementController::class, 'createProduct']);
    Route::put('/products/{id}', [\App\Http\Controllers\Api\ProductManagementController::class, 'updateProduct']);
    Route::patch('/products/{id}/stock', [\App\Http\Controllers\Api\ProductManagementController::class, 'updateStock']);
    Route::delete('/products/{id}', [\App\Http\Controllers\Api\ProductManagementController::class, 'deleteProduct']);
    Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductManagementController::class, 'getProduct']);
    Route::post('/products/bulk', [\App\Http\Controllers\Api\ProductManagementController::class, 'bulkCreateProducts']);
    Route::get('/sellers/{sellerId}/products', [\App\Http\Controllers\Api\ProductManagementController::class, 'getSellerProducts']);
    
    // Order Tracking Management
    Route::post('/orders/{orderId}/tracking', [\App\Http\Controllers\Api\OrderTrackingController::class, 'createTracking']);
    Route::patch('/tracking/{trackingId}/status', [\App\Http\Controllers\Api\OrderTrackingController::class, 'updateTrackingStatus']);
    Route::post('/tracking/{trackingId}/history', [\App\Http\Controllers\Api\OrderTrackingController::class, 'addTrackingHistory']);
    Route::get('/tracking/{trackingId}', [\App\Http\Controllers\Api\OrderTrackingController::class, 'getTracking']);
    Route::get('/tracking/number/{trackingNumber}', [\App\Http\Controllers\Api\OrderTrackingController::class, 'getTrackingByNumber']);
    Route::get('/orders/{orderId}/tracking', [\App\Http\Controllers\Api\OrderTrackingController::class, 'getOrderTracking']);
    Route::post('/tracking/bulk-update', [\App\Http\Controllers\Api\OrderTrackingController::class, 'bulkUpdateTracking']);
    Route::get('/tracking/stats', [\App\Http\Controllers\Api\OrderTrackingController::class, 'getTrackingStats']);
});

// Platform Management API Routes (for Core Transaction 3 team)
Route::middleware(['api.key:platform_management'])->prefix('platform')->group(function () {
    // Platform Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Api\PlatformManagementController::class, 'getDashboard']);
    
    // Seller Management
    Route::get('/sellers', [\App\Http\Controllers\Api\PlatformManagementController::class, 'getSellers']);
    Route::post('/sellers/{sellerId}/approve', [\App\Http\Controllers\Api\PlatformManagementController::class, 'approveSeller']);
    Route::post('/sellers/{sellerId}/suspend', [\App\Http\Controllers\Api\PlatformManagementController::class, 'suspendSeller']);
    
    // Subscription Plans
    Route::get('/subscription-plans', [\App\Http\Controllers\Api\PlatformManagementController::class, 'getSubscriptionPlans']);
    Route::post('/subscription-plans', [\App\Http\Controllers\Api\PlatformManagementController::class, 'createSubscriptionPlan']);
    
    // Commission Management
    Route::get('/commission-rules', [\App\Http\Controllers\Api\PlatformManagementController::class, 'getCommissionRules']);
    Route::post('/commission-rules', [\App\Http\Controllers\Api\PlatformManagementController::class, 'createCommissionRule']);
    
    // Payout Management
    Route::get('/payout-requests', [\App\Http\Controllers\Api\PlatformManagementController::class, 'getPayoutRequests']);
    Route::post('/payout-requests/{requestId}/process', [\App\Http\Controllers\Api\PlatformManagementController::class, 'processPayoutRequest']);
    
    // Platform Settings
    Route::put('/settings', [\App\Http\Controllers\Api\PlatformManagementController::class, 'updatePlatformSettings']);
    
    // Reports
    Route::post('/reports', [\App\Http\Controllers\Api\PlatformManagementController::class, 'generateReport']);
});

// Public API Routes (for inter-system communication)
Route::prefix('public')->group(function () {
    Route::get('/sellers/{seller}/stats', function ($sellerId) {
        $seller = \App\Models\Seller::findOrFail($sellerId);
        $service = app(\App\Services\SellerService::class);
        return response()->json([
            'success' => true,
            'data' => $service->getDashboardStats($seller)
        ]);
    });
    
    Route::get('/platform/stats', function () {
        $service = app(\App\Services\AdminService::class);
        return response()->json([
            'success' => true,
            'data' => $service->getPlatformStats()
        ]);
    });
});

// Shared Data API Routes (for Core Transaction 2 & 3 integration)
Route::middleware(['api.key:shared_data'])->prefix('shared')->group(function () {
    // Data retrieval endpoints
    Route::get('/data', [\App\Http\Controllers\Api\SharedDataController::class, 'getSharedData']);
    Route::get('/realtime-updates', [\App\Http\Controllers\Api\SharedDataController::class, 'getRealtimeUpdates']);
    
    // Data sync endpoints
    Route::post('/sellers/{sellerId}/sync', [\App\Http\Controllers\Api\SharedDataController::class, 'syncSellerData']);
    Route::post('/products/{productId}/sync', [\App\Http\Controllers\Api\SharedDataController::class, 'syncProductData']);
    Route::post('/orders/{orderId}/sync', [\App\Http\Controllers\Api\SharedDataController::class, 'syncOrderData']);
    Route::post('/sellers/{sellerId}/earnings/sync', [\App\Http\Controllers\Api\SharedDataController::class, 'syncEarningsData']);
    Route::post('/platform/settings/sync', [\App\Http\Controllers\Api\SharedDataController::class, 'syncPlatformSettings']);
    
    // Webhook management
    Route::post('/webhooks/register', [\App\Http\Controllers\Api\SharedDataController::class, 'registerWebhook']);
    Route::post('/webhooks/{webhookId}/test', [\App\Http\Controllers\Api\SharedDataController::class, 'testWebhook']);
});

// Core Transaction 1 Data Export API Routes (for sharing data with Core Transaction 2 & 3)
Route::middleware(['api.key:core_transaction_1_export'])->prefix('core-transaction-1')->group(function () {
    // Customer data export
    Route::get('/customers', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportCustomers']);
    Route::get('/customers/{customerId}', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportCustomer']);
    
    // Order data export
    Route::get('/orders', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportOrders']);
    Route::get('/orders/{orderId}', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportOrder']);
    
    // Product data export
    Route::get('/products', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportProducts']);
    Route::get('/products/{productId}', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportProduct']);
    
    // Review data export
    Route::get('/reviews', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportReviews']);
    
    // Analytics export
    Route::get('/analytics', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'exportAnalytics']);
    
    // Real-time events
    Route::get('/events', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'getRealTimeEvents']);
    
    // Webhook management
    Route::post('/webhooks/test', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'testWebhooks']);
    Route::get('/webhooks/status', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'getWebhookStatus']);
    Route::post('/webhooks/send', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'sendWebhookEvent']);
    
    // Statistics
    Route::get('/stats', [\App\Http\Controllers\Api\CoreTransaction1DataController::class, 'getExportStats']);
});

