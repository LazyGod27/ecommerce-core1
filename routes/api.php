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

Route::middleware('throttle:60,1')->group(function () {
    Route::post('/payments/gcash/webhook', [PaymentController::class, 'gcashWebhook']);
});

