<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::get('/products', [ProductController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', 'Api\CheckoutController@store');
});
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::middleware('auth:sanctum')->post('/logout', 'Api\AuthController@logout');

Route::post('/payment/intent', 'Api\PaymentController@createPaymentIntent');
Route::post('/payment/webhook', 'Api\PaymentController@handleWebhook');