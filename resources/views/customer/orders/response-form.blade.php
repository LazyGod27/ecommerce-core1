@extends('layouts.app')

@section('title', 'Order Response - ' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Response Required</h1>
                <p class="text-gray-600">Please confirm receipt or request a return for your order</p>
            </div>

            <!-- Order Information -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order #{{ $order->order_number }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Order Details</h3>
                        <p class="text-sm text-gray-600">Order Date: {{ $order->created_at->format('M j, Y g:i A') }}</p>
                        <p class="text-sm text-gray-600">Delivered: {{ $order->delivered_at->format('M j, Y g:i A') }}</p>
                        <p class="text-sm text-gray-600">Total: ${{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Response Deadline</h3>
                        <p class="text-sm text-red-600 font-medium">
                            {{ $order->delivery_confirmation_deadline->format('M j, Y g:i A') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            ({{ $order->delivery_confirmation_deadline->diffForHumans() }})
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                            @if($item->product->image)
                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-600">Price: ${{ number_format($item->price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">${{ number_format($item->quantity * $item->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">What would you like to do?</h3>
                <p class="text-gray-600 mb-6">Please choose one of the following options:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Confirm Received -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="text-center">
                            <div class="text-green-600 text-4xl mb-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Confirm Item Received</h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Click this if you have received your order and everything is satisfactory.
                            </p>
                            <button onclick="confirmReceived({{ $order->id }})" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                ✅ Confirm Received
                            </button>
                        </div>
                    </div>

                    <!-- Request Return -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="text-center">
                            <div class="text-yellow-600 text-4xl mb-3">
                                <i class="fas fa-undo"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Request Return/Refund</h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Click this if you want to return the item or request a refund.
                            </p>
                            <a href="{{ route('orders.request-return', $order) }}" 
                               class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center">
                                🔄 Request Return
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="text-yellow-600 mr-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Important Notice</h4>
                        <p class="text-sm text-gray-700">
                            If you don't respond within the deadline, your order will be automatically confirmed as received. 
                            This is our standard policy to ensure smooth order processing.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Back to Orders -->
            <div class="mt-6 text-center">
                <a href="{{ route('orders.index') }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Back to All Orders
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="text-center">
                <div class="text-green-600 text-4xl mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Order Received</h3>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to confirm that you have received this order? 
                    This action cannot be undone.
                </p>
                <div class="flex space-x-3">
                    <button onclick="closeModal()" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button onclick="processConfirmation()" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Yes, Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;

function confirmReceived(orderId) {
    currentOrderId = orderId;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    currentOrderId = null;
}

function processConfirmation() {
    if (!currentOrderId) return;
    
    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Processing...';
    button.disabled = true;
    
    // Make AJAX request
    fetch(`/orders/${currentOrderId}/confirm-received`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Order confirmed as received successfully!');
            // Redirect to orders page
            window.location.href = '/orders';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled = false;
        closeModal();
    });
}
</script>
@endsection
