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
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 500;
        color: #333;
        flex: 1;
    }

    .item-price {
        color: #666;
        font-weight: 500;
    }

    .item-quantity {
        color: #666;
        margin: 0 15px;
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
                                    <div class="detail-value">₱{{ number_format($order->total_amount ?? 0, 2) }}</div>
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

                            @if($order->orderItems && $order->orderItems->count() > 0)
                                <div class="order-items">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-3">Order Items</h4>
                                    @foreach($order->orderItems as $item)
                                        <div class="item-row">
                                            <div class="item-name">{{ $item->product_name ?? 'Product' }}</div>
                                            <div class="item-quantity">Qty: {{ $item->quantity }}</div>
                                            <div class="item-price">₱{{ number_format($item->price ?? 0, 2) }}</div>
                                        </div>
                                    @endforeach
                                    <div class="order-total">
                                        Total: ₱{{ number_format($order->total_amount ?? 0, 2) }}
                                    </div>
                                </div>
                            @endif

                            <div class="btn-container">
                                <a href="{{ route('tracking') }}?order={{ $order->id }}" class="btn btn-secondary">
                                    <i class="fas fa-truck mr-2"></i>
                                    Track Order
                                </a>
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
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Orders Yet</h3>
                        <p class="text-gray-600 mb-6">You haven't placed any orders yet. Start shopping to see your order history here!</p>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Start Shopping
                        </a>
                    </div>
                @endif

                <div class="btn-container">
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
