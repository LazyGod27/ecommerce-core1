@extends('layouts.app')

@section('title', 'Request Return - ' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Request Return/Refund</h1>
                <p class="text-gray-600">Please provide a reason for your return request</p>
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

            <!-- Return Request Form -->
            <form id="returnForm" class="space-y-6">
                @csrf
                
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Return/Refund <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="reason" 
                        name="reason" 
                        rows="6" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Please provide a detailed reason for your return request. This will help us process your request faster."
                        required
                        minlength="10"
                        maxlength="1000"
                    ></textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Minimum 10 characters, maximum 1000 characters
                    </p>
                </div>

                <!-- Common Return Reasons -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Common Reasons (Click to select)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <button type="button" onclick="selectReason('Item damaged during shipping')" 
                                class="text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-sm">
                            📦 Item damaged during shipping
                        </button>
                        <button type="button" onclick="selectReason('Wrong item received')" 
                                class="text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-sm">
                            ❌ Wrong item received
                        </button>
                        <button type="button" onclick="selectReason('Item not as described')" 
                                class="text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-sm">
                            📝 Item not as described
                        </button>
                        <button type="button" onclick="selectReason('Changed my mind')" 
                                class="text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-sm">
                            🤔 Changed my mind
                        </button>
                        <button type="button" onclick="selectReason('Item defective')" 
                                class="text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-sm">
                            ⚠️ Item defective
                        </button>
                        <button type="button" onclick="selectReason('Size/color not suitable')" 
                                class="text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-sm">
                            👕 Size/color not suitable
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('orders.response-form', $order) }}" 
                       class="text-gray-600 hover:text-gray-800 font-medium">
                        ← Back to Order Response
                    </a>
                    
                    <div class="flex space-x-3">
                        <button type="button" onclick="goBack()" 
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium transition-colors">
                            🔄 Submit Return Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selectReason(reason) {
    document.getElementById('reason').value = reason;
}

function goBack() {
    window.history.back();
}

document.getElementById('returnForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const reason = document.getElementById('reason').value.trim();
    
    if (reason.length < 10) {
        alert('Please provide a more detailed reason (at least 10 characters).');
        return;
    }
    
    if (reason.length > 1000) {
        alert('Reason is too long (maximum 1000 characters).');
        return;
    }
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Submitting...';
    submitBtn.disabled = true;
    
    // Make AJAX request
    fetch(`/orders/{{ $order->id }}/request-return`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Return request submitted successfully! We will review your request and get back to you soon.');
            window.location.href = '/orders-waiting-response';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@endsection
