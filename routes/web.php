<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::put('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::post('/tracking', [TrackingController::class, 'track'])->name('tracking.track');
Route::get('/customer-service', [CustomerServiceController::class, 'index'])->name('customer-service');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Account route
Route::get('/account', function () {
    return view('account');
})->name('account');

// Checkout routes
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

// Payment routes
Route::post('/payment/gcash', [PaymentController::class, 'initiateGcash'])->name('payment.gcash');
Route::post('/payment/gcash/webhook', [PaymentController::class, 'gcashWebhook']);

// Stripe webhook
Route::post('stripe/webhook', '\App\Http\Controllers\Api\PaymentController@handleWebhook')
     ->name('stripe.webhook');