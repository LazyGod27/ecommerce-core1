@extends('layouts.frontend')

@section('title', 'iMarket - Payment Successful')

@section('styles')
<style>
    .success-animation {
        animation: successPulse 2s ease-in-out;
    }
    
    @keyframes successPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .checkmark {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #4CAF50;
        stroke-miterlimit: 10;
        margin: 10% auto;
        box-shadow: inset 0px 0px 0px #4CAF50;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    
    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4CAF50;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    
    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    
    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }
    
    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }
    
    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px #4CAF50;
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <!-- Success Animation -->
        <div class="success-animation mb-6">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-green-600 mb-4">Payment Successful!</h1>
        <p class="text-gray-600 mb-8">Your order has been confirmed and payment has been processed successfully.</p>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Order Details</h2>
            <div class="grid md:grid-cols-2 gap-4 text-left">
                <div>
                    <p class="text-gray-600">Order Number:</p>
                    <p class="font-semibold">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Order Date:</p>
                    <p class="font-semibold">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Payment Method:</p>
                    <p class="font-semibold">{{ $order->payment_method }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Contact Number:</p>
                    <p class="font-semibold">{{ $order->contact_number ?? $order->user->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Email:</p>
                    <p class="font-semibold">{{ $order->email ?? $order->user->email ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Total Amount:</p>
                    <p class="font-semibold text-green-600">₱{{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4">Order Items</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-12 h-12 rounded-md object-cover">
                        <div>
                            <p class="font-semibold">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    <p class="font-semibold">₱{{ number_format($item->total, 2) }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
            <div class="text-left">
                <p class="text-gray-600 mb-2">Shipping Address:</p>
                <p class="font-semibold">{{ $order->shipping_address }}</p>
                <p class="text-gray-600 mt-2">Contact: {{ $order->contact_number }}</p>
                <p class="text-gray-600">Email: {{ $order->email }}</p>
            </div>
        </div>

        <!-- Tracking Information -->
        @if($order->tracking)
        <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4 text-blue-800">Tracking Information</h3>
            <div class="text-left">
                <p class="text-gray-600 mb-2">Tracking Number:</p>
                <p class="font-semibold text-blue-600">{{ $order->tracking->tracking_number }}</p>
                <p class="text-gray-600 mt-2">Estimated Delivery:</p>
                <p class="font-semibold">{{ $order->tracking->estimated_delivery->format('M d, Y') }}</p>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('order.confirmation', $order->id) }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors">
                View Order Details
            </a>
            <a href="{{ route('home') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition-colors">
                Continue Shopping
            </a>
            <a href="{{ route('tracking') }}" 
               class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition-colors">
                View All Orders
            </a>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 p-4 bg-yellow-50 rounded-lg">
            <h4 class="font-semibold text-yellow-800 mb-2">What's Next?</h4>
            <ul class="text-sm text-yellow-700 space-y-1">
                <li>• You will receive an email confirmation shortly</li>
                <li>• Your order will be processed within 24 hours</li>
                <li>• You can track your order status anytime</li>
                <li>• For any questions, contact our customer service</li>
            </ul>
        </div>
    </div>
</div>
@endsection
