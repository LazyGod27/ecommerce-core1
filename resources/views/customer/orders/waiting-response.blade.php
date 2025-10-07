@extends('layouts.app')

@section('title', 'Orders Waiting for Response')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Orders Waiting for Response</h1>
                <div class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-2"></i>
                    You have 7 days to confirm receipt or request a return
                </div>
            </div>

            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Order #{{ $order->order_number }}
                                        </h3>
                                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $order->getDeliveryStatusBadgeClass() }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Order Date</p>
                                            <p class="font-medium">{{ $order->created_at->format('M j, Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Delivered</p>
                                            <p class="font-medium">{{ $order->delivered_at->format('M j, Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Response Deadline</p>
                                            <p class="font-medium text-red-600">
                                                {{ $order->delivery_confirmation_deadline->format('M j, Y g:i A') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">Items:</p>
                                        <div class="space-y-2">
                                            @foreach($order->items as $item)
                                                <div class="flex items-center space-x-3">
                                                    @if($item->product->image)
                                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" 
                                                             class="w-12 h-12 object-cover rounded">
                                                    @endif
                                                    <div>
                                                        <p class="font-medium">{{ $item->product->name }}</p>
                                                        <p class="text-sm text-gray-600">
                                                            Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">
                                                Total: ${{ number_format($order->total, 2) }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-3">
                                            <a href="{{ route('orders.confirm-received', $order) }}" 
                                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                ✅ Confirm Received
                                            </a>
                                            <a href="{{ route('orders.request-return', $order) }}" 
                                               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                🔄 Request Return
                                            </a>
                                            <a href="{{ route('orders.show', $order) }}" 
                                               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                👁️ View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Orders Waiting for Response</h3>
                    <p class="text-gray-600">All your delivered orders have been confirmed or are not yet delivered.</p>
                    <a href="{{ route('orders.index') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        View All Orders
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
