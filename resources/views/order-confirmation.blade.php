@extends('layouts.frontend')

@section('title', 'iMarket - Order Confirmation')

@section('styles')
<style>
    .confirmation-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

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

    .action-btn {
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: #aaa;
    }

    .close:hover {
        color: #000;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Success Animation and Header -->
    <div class="confirmation-card p-8 mb-8 text-center">
        <div class="success-animation mb-6">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-green-600 mb-4">Order Placed Successfully!</h1>
        <p class="text-gray-600 mb-6">Your order has been confirmed and is being processed.</p>
        
        <div class="bg-blue-50 rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold text-blue-800 mb-2">Order #{{ $order->order_number }}</h2>
            <p class="text-blue-600">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Order Tracking Timeline -->
        <div class="lg:col-span-2">
            <div class="confirmation-card p-6">
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
            <div class="confirmation-card p-6 mb-6">
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
            <div class="confirmation-card p-6 mb-6">
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
            <div class="confirmation-card p-6 mb-6">
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
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="confirmation-card p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('tracking.show', $order->id) }}" 
                       class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors text-center block action-btn">
                        <i class="fas fa-truck mr-2"></i>
                        Track My Order
                    </a>
                    
                    @if($order->canBeCancelled())
                    <button onclick="showCancelModal()" 
                            class="w-full bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 transition-colors action-btn">
                        <i class="fas fa-times mr-2"></i>
                        Cancel Order
                    </button>
                    @endif
                    
                    @if($order->canBeEdited())
                    <button onclick="showEditModal()" 
                            class="w-full bg-yellow-600 text-white py-3 px-4 rounded-md hover:bg-yellow-700 transition-colors action-btn">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Order
                    </button>
                    @endif
                    
                    <a href="{{ route('home') }}" 
                       class="w-full bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 transition-colors text-center block action-btn">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="confirmation-card p-6 mt-8">
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

<!-- Cancel Order Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCancelModal()">&times;</span>
        <h3 class="text-lg font-semibold mb-4">Cancel Order</h3>
        <p class="text-gray-600 mb-4">Are you sure you want to cancel this order? This action cannot be undone.</p>
        <form action="{{ route('order.cancel', $order->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Cancellation</label>
                <textarea name="cancellation_reason" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-500" required placeholder="Please provide a reason for cancellation"></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                    Confirm Cancellation
                </button>
                <button type="button" onclick="closeCancelModal()" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                    Keep Order
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Order Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3 class="text-lg font-semibold mb-4">Edit Order</h3>
        <p class="text-gray-600 mb-4">You can modify your shipping information or contact details.</p>
        <form action="{{ route('order.edit', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                <textarea name="shipping_address" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ $order->shipping_address }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                <input type="tel" name="contact_number" value="{{ $order->contact_number }}" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ $order->email }}" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                    Update Order
                </button>
                <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showCancelModal() {
    document.getElementById('cancelModal').style.display = 'block';
}

function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
}

function showEditModal() {
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const cancelModal = document.getElementById('cancelModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === cancelModal) {
        closeCancelModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
}
</script>
@endsection
