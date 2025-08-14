<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products/search', function () {
    return view('products.search');
});

Route::post('stripe/webhook', '\App\Http\Controllers\Api\PaymentController@handleWebhook')
     ->name('stripe.webhook');