@extends('layouts.frontend')

@section('title', 'iMarket - Order History')

@section('styles')
<style>
    .orders-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .orders-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .orders-header {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .orders-content {
        padding: 40px;
    }

    .order-item {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        border-left: 4px solid #4bc5ec;
        transition: all 0.3s ease;
    }

    .order-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-number {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }

    .order-date {
        color: #666;
        font-size: 0.9rem;
    }

    .order-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-placed { background: #e3f2fd; color: #1976d2; }
    .status-shipped { background: #fff3e0; color: #f57c00; }
    .status-transit { background: #e8f5e8; color: #388e3c; }
    .status-delivered { background: #e8f5e8; color: #2e7d32; }
    .status-cancelled { background: #ffebee; color: #d32f2f; }

    .order-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .detail-item {
        background: white;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 1rem;
        color: #333;
        font-weight: 500;
    }

    .order-items {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-top: 15px;
    }

    .item-row {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
        gap: 15px;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-image {
        flex-shrink: 0;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .item-description {
        color: #666;
        line-height: 1.4;
        font-size: 0.875rem;
    }

    .item-quantity {
        color: #666;
        font-weight: 500;
        min-width: 60px;
        text-align: center;
    }

    .item-price {
        color: #4bc5ec;
        font-weight: 600;
        min-width: 80px;
        text-align: right;
    }

    .order-total {
        background: #4bc5ec;
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 15px;
        text-align: right;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-icon {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 20px;
    }

    .btn-container {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #333;
        border: 2px solid #e9ecef;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .pagination .page-link {
        padding: 10px 15px;
        margin: 0 5px;
        border-radius: 8px;
        text-decoration: none;
        color: #4bc5ec;
        border: 2px solid #4bc5ec;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover,
    .pagination .page-item.active .page-link {
        background: #4bc5ec;
        color: white;
    }

    @media (max-width: 768px) {
        .orders-content {
            padding: 30px 20px;
        }

        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-details {
            grid-template-columns: 1fr;
        }

        .item-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .btn-container {
            flex-direction: column;
        }
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .modal-body p {
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .reason-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .reason-item input[type="radio"] {
        accent-color: #ef4444;
    }

    .reason-item label {
        cursor: pointer;
        color: #374151;
    }

    .modal-body textarea {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.75rem;
        width: 100%;
        resize: vertical;
        font-family: inherit;
    }

    .modal-body button {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-body button[type="button"] {
        background: #d1d5db;
        color: #374151;
        border: none;
        margin-right: 0.5rem;
    }

    .modal-body button[type="submit"] {
        background: #ef4444;
        color: white;
        border: none;
    }

    .modal-body button:hover {
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="orders-container">
    <div class="container mx-auto px-4">
        <div class="orders-card">
            <div class="orders-header">
                <h1 class="text-3xl font-bold mb-2">Order History</h1>
                <p class="text-lg opacity-90">Track your past orders and their current status</p>
            </div>

            <div class="orders-content">
                @if($orders->count() > 0)
                    @foreach($orders as $order)
                        <div class="order-item">
                            <div class="order-header">
                                <div>
                                    <div class="order-number">Order #{{ $order->id }}</div>
                                    <div class="order-date">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</div>
                                </div>
                                <span class="order-status status-{{ strtolower(str_replace(' ', '-', $order->status ?? 'placed')) }}">
                                    {{ $order->status ?? 'Order Placed' }}
                                </span>
                            </div>

                            <div class="order-details">
                                <div class="detail-item">
                                    <div class="detail-label">Order Date</div>
                                    <div class="detail-value">{{ $order->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Total Amount</div>
                                    <div class="detail-value">₱{{ number_format($order->total ?? 0, 2) }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Payment Method</div>
                                    <div class="detail-value">{{ $order->payment_method ?? 'Not specified' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Payment Status</div>
                                    <div class="detail-value">{{ $order->payment_status ?? 'Pending' }}</div>
                                </div>
                            </div>

                            @if($order->items && $order->items->count() > 0)
                                <div class="order-items">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-3">Order Items</h4>
                                    @foreach($order->items as $item)
                                        <div class="item-row">
                                            <div class="item-image">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="item-details">
                                                <div class="item-name">{{ $item->product->name ?? 'Product' }}</div>
                                                @if($item->product && $item->product->description)
                                                    <div class="item-description text-sm text-gray-500">{{ Str::limit($item->product->description, 50) }}</div>
                                                @endif
                                            </div>
                                            <div class="item-quantity">Qty: {{ $item->quantity }}</div>
                                            <div class="item-price">₱{{ number_format($item->price ?? 0, 2) }}</div>
                                        </div>
                                    @endforeach
                                    <div class="order-total">
                                        Total: ₱{{ number_format($order->total ?? 0, 2) }}
                                    </div>
                                </div>
                            @endif

                            <div class="btn-container">
                                <a href="{{ route('track-order') }}?order={{ $order->id }}" class="btn btn-secondary">
                                    <i class="fas fa-truck mr-2"></i>
                                    Track Order
                                </a>
                                @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                                    <a href="#" class="btn btn-danger ml-2" onclick="cancelOrder({{ $order->id }})">
                                        <i class="fas fa-times mr-2"></i>
                                        Cancel Order
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="pagination">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Orders Found</h3>
                        <p class="text-gray-500 mb-6">You haven't placed any orders yet.</p>
                        <a href="{{ route('products') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Order Cancellation Modal -->
<div id="cancellation-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold">Cancel Order</h3>
            <button id="close-modal-btn" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p class="mb-4">Please select the reason for canceling your order:</p>
            <form id="cancellation-form">
                @csrf
                <div class="reason-item">
                    <input type="radio" id="reason1" name="cancel-reason" value="I no longer want the product">
                    <label for="reason1">I no longer want the product</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason2" name="cancel-reason" value="Ordered by mistake">
                    <label for="reason2">Ordered by mistake</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason3" name="cancel-reason" value="Found a cheaper price elsewhere">
                    <label for="reason3">Found a cheaper price elsewhere</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason4" name="cancel-reason" value="Others">
                    <label for="reason4">Others</label>
                </div>
                <textarea class="mt-4 p-2 w-full border rounded-md" rows="3" placeholder="You may provide additional details..."></textarea>
                <div class="flex justify-end mt-4">
                    <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mr-2" id="cancel-modal-btn">Cancel</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Submit Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentOrderId = null;

    function cancelOrder(orderId) {
        currentOrderId = orderId;
        document.getElementById('cancellation-modal').style.display = 'flex';
    }

    // Modal handling
    document.getElementById('close-modal-btn').addEventListener('click', function() {
        document.getElementById('cancellation-modal').style.display = 'none';
    });

    document.getElementById('cancel-modal-btn').addEventListener('click', function() {
        document.getElementById('cancellation-modal').style.display = 'none';
    });

    document.getElementById('cancellation-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Here you would typically send the cancellation request to the server
        alert('Order cancellation request submitted successfully!');
        document.getElementById('cancellation-modal').style.display = 'none';
        
        // Reload the page to update the order status
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    });
</script>
@endsection

