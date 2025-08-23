<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');

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
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

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
Route::get('/payment/gcash', [PaymentController::class, 'gcash'])->name('payment.gcash');
Route::post('/payment/gcash/process', [PaymentController::class, 'processGcash'])->name('payment.gcash.process');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

// Tracking route
Route::get('/tracking', function () {
    return view('tracking');
})->name('tracking');

// Customer service route
Route::get('/customer-service', function () {
    return view('customer-service');
})->name('customer-service');

// Stripe webhook
Route::post('stripe/webhook', [\App\Http\Controllers\Api\PaymentController::class, 'handleWebhook'])
     ->name('stripe.webhook');