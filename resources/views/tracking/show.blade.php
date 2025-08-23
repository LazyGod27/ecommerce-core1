@extends('layouts.frontend')

@section('title', 'iMarket - Order Tracking')

@section('styles')
<style>
    .tracking-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    
    .tracking-step {
        position: relative;
        margin-bottom: 30px;
    }
    
    .tracking-step::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e5e7eb;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e5e7eb;
    }
    
    .tracking-step.completed::before {
        background: #10b981;
        box-shadow: 0 0 0 2px #10b981;
    }
    
    .tracking-step.current::before {
        background: #3b82f6;
        box-shadow: 0 0 0 2px #3b82f6;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-pending { background-color: #fef3c7; color: #92400e; }
    .status-processing { background-color: #dbeafe; color: #1e40af; }
    .status-shipped { background-color: #e0e7ff; color: #3730a3; }
    .status-delivered { background-color: #d1fae5; color: #065f46; }
    .status-cancelled { background-color: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Tracking Timeline -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Order Progress</h2>
                
                @if($order->tracking)
                <div class="tracking-timeline">
                    @php
                        $trackingDetails = $order->tracking->tracking_details ?? [];
                        $currentStatus = $order->tracking->status;
                    @endphp
                    
                    @foreach($trackingDetails as $index => $detail)
                    <div class="tracking-step {{ $index === count($trackingDetails) - 1 ? 'current' : 'completed' }}">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-800">{{ $detail['status'] }}</h3>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($detail['timestamp'])->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <p class="text-gray-600 mb-2">{{ $detail['description'] }}</p>
                            @if(isset($detail['location']))
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $detail['location'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-truck text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Tracking information will be available once your order is processed.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Details Sidebar -->
        <div class="lg:col-span-1">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span>₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax:</span>
                        <span>₱{{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping:</span>
                        <span>₱{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <hr class="my-3">
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Total:</span>
                        <span class="text-blue-600">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
                <div class="space-y-2">
                    <p class="text-gray-600 text-sm">Address:</p>
                    <p class="font-medium">{{ $order->shipping_address }}</p>
                    <p class="text-gray-600 text-sm mt-3">Contact:</p>
                    <p class="font-medium">{{ $order->contact_number }}</p>
                    <p class="text-gray-600 text-sm mt-3">Email:</p>
                    <p class="font-medium">{{ $order->email }}</p>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Method:</span>
                        <span class="font-medium">{{ $order->payment_method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="status-badge status-{{ $order->payment_status }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paid:</span>
                        <span class="font-medium">{{ $order->paid_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tracking Information -->
            @if($order->tracking)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Tracking Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tracking #:</span>
                        <span class="font-medium text-blue-600">{{ $order->tracking->tracking_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Carrier:</span>
                        <span class="font-medium">{{ $order->tracking->carrier }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="status-badge status-{{ $order->tracking->status }}">
                            {{ ucfirst($order->tracking->status) }}
                        </span>
                    </div>
                    @if($order->tracking->estimated_delivery)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Est. Delivery:</span>
                        <span class="font-medium">{{ $order->tracking->estimated_delivery->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($order->tracking->current_location)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="font-medium">{{ $order->tracking->current_location }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('tracking.invoice', $order->id) }}" 
                       class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-center block">
                        Download Invoice
                    </a>
                    @if($order->canBeRefunded())
                    <button onclick="showRefundModal()" 
                            class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                        Request Refund
                    </button>
                    @endif
                    <a href="{{ route('profile.orders') }}" 
                       class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors text-center block">
                        View All Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-xl font-semibold mb-4">Order Items</h2>
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                         alt="{{ $item->product->name }}" 
                         class="w-16 h-16 rounded-md object-cover">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                        <p class="text-sm text-gray-600">Unit Price: ₱{{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-800">₱{{ number_format($item->total, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Refund Modal -->
@if($order->canBeRefunded())
<div id="refundModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Request Refund</h3>
            <form action="{{ route('payment.refund', $order->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Refund</label>
                    <textarea name="reason" rows="4" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                        Submit Request
                    </button>
                    <button type="button" onclick="hideRefundModal()" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
function showRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
}

function hideRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}

// Auto-refresh tracking updates every 30 seconds
setInterval(function() {
    fetch('{{ route("tracking.updates", $order->tracking->id ?? 0) }}')
        .then(response => response.json())
        .then(data => {
            if (data.tracking) {
                // Update tracking information if needed
                console.log('Tracking updated:', data);
            }
        })
        .catch(error => console.error('Error fetching tracking updates:', error));
}, 30000);
</script>
@endsection
