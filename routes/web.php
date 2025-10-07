<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('ssa.index');
})->name('home');
Route::get('/products', function () {
    // Get products from all categories and shuffle them to create a "rumble" effect
    $products = \App\Models\Product::with(['reviews'])->inRandomOrder()->get();
    // Get some featured products for hero section
    $heroProducts = \App\Models\Product::inRandomOrder()->take(5)->get();
    return view('products', compact('products', 'heroProducts'));
})->name('products');

// Store routes
Route::get('/store', function () {
    return view('store');
})->name('store');

Route::get('/product/{id}', function ($id) {
    return view('product-detail', compact('id'));
})->name('product.detail');

// Category routes
Route::get('/categories/best', function () {
    return view('ssa.categories.best');
})->name('categories.best');

Route::get('/categories/new', function () {
    return view('ssa.categories.new');
})->name('categories.new');

Route::get('/categories/electronics', function () {
    return view('ssa.categories.electronics');
})->name('categories.electronics');

Route::get('/categories/fashion', function () {
    return view('ssa.categories.fashion');
})->name('categories.fashion');

Route::get('/categories/home', function () {
    return view('ssa.categories.home');
})->name('categories.home');

Route::get('/categories/beauty', function () {
    return view('ssa.categories.beauty');
})->name('categories.beauty');

Route::get('/categories/sports', function () {
    return view('ssa.categories.sports');
})->name('categories.sports');

Route::get('/categories/toys', function () {
    return view('ssa.categories.toys');
})->name('categories.toys');

Route::get('/categories/groceries', function () {
    return view('ssa.categories.groceries');
})->name('categories.groceries');

// Customer Service route
Route::get('/customer-service', function () {
    return view('ssa.customer-service');
})->name('customer-service');

// Order Tracking route (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/track-order', function () {
        $orderId = request('order');
        $order = null;
        
        if ($orderId) {
            $order = \App\Models\Order::with(['items.product', 'tracking'])
                ->where('id', $orderId)
                ->where('user_id', auth()->id())
                ->first();
        }
        
        return view('ssa.track-order', compact('order'));
    })->name('track-order');
});
// Search routes
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/trending', [SearchController::class, 'trending'])->name('search.trending');
Route::get('/search/popular', [SearchController::class, 'popular'])->name('search.popular');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/voice-search', function () {
    return view('voice-search');
})->name('voice-search');

Route::get('/test-voice', function () {
    return view('test-voice');
})->name('test-voice');

Route::get('/demo', function () {
    return view('demo');
})->name('demo');

Route::get('/debug', function () {
    return view('debug');
})->name('debug');

// Cart routes (available for both guests and authenticated users)
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

Route::middleware('auth')->group(function () {
    Route::post('/cart/save-for-later/{rowId}', [CartController::class, 'saveForLater'])->name('cart.save-for-later');
    Route::post('/checkout/direct', [CartController::class, 'directCheckout'])->name('checkout.direct');
});

// Authentication routes
Route::get('/login', function () {
    return view('ssa.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return redirect('/ssa/register.html');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Social Login Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);

// Checkout routes
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::middleware('auth')->group(function () {
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    // Removed duplicate route - using OrderController::confirmation instead
    Route::get('/api/similar-products/{order}', [CartController::class, 'getSimilarProducts'])->name('api.similar-products');
});

// Profile routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::put('/profile/update-address', [ProfileController::class, 'updateAddress'])->name('profile.update-address');
    Route::get('/profile/preferences', [ProfileController::class, 'preferences'])->name('profile.preferences');
    Route::put('/profile/update-preferences', [ProfileController::class, 'updatePreferences'])->name('profile.update-preferences');
    Route::get('/profile/delete-account', [ProfileController::class, 'deleteAccount'])->name('profile.delete-account');
    Route::delete('/profile/delete-account', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Account route (legacy - redirects to new profile)
Route::get('/account', function () {
    return redirect()->route('profile.index');
})->name('account');

// Payment routes
Route::middleware('auth')->group(function () {
    Route::get('/payment/process/{orderId}', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/gcash', [PaymentController::class, 'gcash'])->name('payment.gcash');
    Route::post('/payment/paymaya', [PaymentController::class, 'paymaya'])->name('payment.paymaya');
    Route::post('/payment/stripe', [PaymentController::class, 'stripe'])->name('payment.stripe');
    Route::get('/payment/success/{orderId}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{orderId}', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::post('/payment/refund/{orderId}', [PaymentController::class, 'refund'])->name('payment.refund');
});

// Payment callbacks (public routes for webhooks)
Route::get('/payment/callback/{method}/{orderId}', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook/{method}', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Tracking routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
    Route::get('/tracking/{orderId}', [TrackingController::class, 'show'])->name('tracking.show');
    Route::post('/tracking/search', [TrackingController::class, 'track'])->name('tracking.search');
    Route::post('/tracking/order', [TrackingController::class, 'trackByOrderNumber'])->name('tracking.order');
    Route::get('/tracking/updates/{trackingId}', [TrackingController::class, 'getTrackingUpdates'])->name('tracking.updates');
    Route::get('/tracking/delivery/{trackingId}', [TrackingController::class, 'getEstimatedDelivery'])->name('tracking.delivery');
    Route::get('/tracking/history', [TrackingController::class, 'getOrderHistory'])->name('tracking.history');
    Route::get('/tracking/invoice/{orderId}', [TrackingController::class, 'downloadInvoice'])->name('tracking.invoice');
});

// Order management routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/order/confirmation/{orderId}', [OrderController::class, 'confirmation'])->name('order.confirmation');
    Route::post('/order/cancel/{orderId}', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::put('/order/edit/{orderId}', [OrderController::class, 'edit'])->name('order.edit');
    
    // Order response routes
    Route::get('/orders-waiting-response', [App\Http\Controllers\CustomerOrderController::class, 'waitingForResponse'])->name('orders.waiting-response');
    Route::get('/orders/{order}/response', [App\Http\Controllers\CustomerOrderController::class, 'showResponseForm'])->name('orders.response-form');
    Route::post('/orders/{order}/confirm-received', [App\Http\Controllers\CustomerOrderController::class, 'confirmReceived'])->name('orders.confirm-received');
    Route::get('/orders/{order}/request-return', [App\Http\Controllers\CustomerOrderController::class, 'showReturnForm'])->name('orders.request-return');
    Route::post('/orders/{order}/request-return', [App\Http\Controllers\CustomerOrderController::class, 'requestReturn'])->name('orders.request-return.submit');
    Route::get('/orders-return-requests', [App\Http\Controllers\CustomerOrderController::class, 'returnRequests'])->name('orders.return-requests');
    Route::get('/orders/{order}/status', [App\Http\Controllers\CustomerOrderController::class, 'getOrderStatus'])->name('orders.status');
    Route::get('/orders/{order}/return-details', [App\Http\Controllers\CustomerOrderController::class, 'getReturnDetails'])->name('orders.return-details');
});

// Admin tracking management (protected)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/tracking/update/{trackingId}', [TrackingController::class, 'updateTrackingStatus'])->name('tracking.update');
});

// Customer service route

// API routes for AJAX requests
Route::get('/api/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('api.payment.methods');
Route::get('/api/user', function () {
    if (Auth::check()) {
        return response()->json(['authenticated' => true, 'user' => Auth::user()]);
    }
    return response()->json(['authenticated' => false], 401);
})->name('api.user');