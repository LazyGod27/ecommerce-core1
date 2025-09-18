<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('ssa.index');
})->name('home');
Route::get('/products', function () {
    return view('products');
})->name('products');
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

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
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
});

// Admin tracking management (protected)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/tracking/update/{trackingId}', [TrackingController::class, 'updateTrackingStatus'])->name('tracking.update');
});

// Customer service route
Route::get('/customer-service', function () {
    return view('customer-service');
})->name('customer-service');

// API routes for AJAX requests
Route::get('/api/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('api.payment.methods');