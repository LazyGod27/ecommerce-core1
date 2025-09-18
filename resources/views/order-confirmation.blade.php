@extends('layouts.frontend')

@section('title', 'Order Confirmation - iMarket')

@section('styles')
<style>
    .main-container {
        padding-top: 100px;
        min-height: 100vh;
        background: var(--bg-color);
    }
    
    .confirmation-section {
        padding: 40px 20px;
        background: white;
        margin: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .success-icon {
        font-size: 4rem;
        color: #10b981;
        margin-bottom: 20px;
    }
    
    .confirmation-title {
        font-size: 2.5rem;
        color: var(--text-color);
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .confirmation-subtitle {
        font-size: 1.2rem;
        color: #6b7280;
        margin-bottom: 30px;
    }
    
    .order-details {
        background: #f8fafc;
        padding: 30px;
        border-radius: 12px;
        margin: 30px 0;
        text-align: left;
    }
    
    .order-details h3 {
        color: var(--text-color);
        margin-bottom: 20px;
        font-size: 1.5rem;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .detail-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--main-color);
    }
    
    .detail-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .detail-value {
        color: var(--text-color);
        font-weight: 600;
    }
    
    .order-items {
        margin-top: 20px;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .order-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .item-info {
        flex: 1;
    }
    
    .item-name {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 5px;
    }
    
    .item-quantity {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .item-price {
        color: var(--main-color);
        font-weight: 600;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background: var(--main-color);
        color: white;
    }
    
    .btn-primary:hover {
        background: #1e40af;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }
    
    .btn-outline {
        background: transparent;
        color: var(--main-color);
        border: 2px solid var(--main-color);
    }
    
    .btn-outline:hover {
        background: var(--main-color);
        color: white;
        transform: translateY(-2px);
    }
    
    .similar-products {
        margin-top: 40px;
        padding: 30px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .similar-products h3 {
        color: var(--text-color);
        margin-bottom: 20px;
        text-align: center;
        font-size: 1.5rem;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
    }
    
    .product-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .product-card h4 {
        padding: 15px;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
        margin: 0;
    }
    
    .product-card .price {
        padding: 0 15px 15px;
        color: var(--main-color);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .tracking-info {
        background: #f0f9ff;
        border: 1px solid #0ea5e9;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }
    
    .tracking-info h4 {
        color: #0c4a6e;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .tracking-number {
        font-family: monospace;
        background: white;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid #0ea5e9;
        color: #0c4a6e;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .confirmation-title {
            font-size: 2rem;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
        
        .detail-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
</style>
@endsection

@section('content')
<div class="main-container">
    <div class="confirmation-section">
        <div class="success-icon">
            <i class="ri-check-circle-fill"></i>
        </div>
        
        <h1 class="confirmation-title">Thank You for Your Purchase!</h1>
        <p class="confirmation-subtitle">Your order has been successfully placed and is being processed.</p>
        
        <div class="order-details">
            <h3>Order Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Order Number:</span>
                <span class="detail-value">{{ $order->order_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Order Date:</span>
                <span class="detail-value">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ $order->payment_method }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Shipping Address:</span>
                <span class="detail-value">{{ $order->shipping_address }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Contact Number:</span>
                <span class="detail-value">{{ $order->contact_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $order->email }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value">₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
        
        @if($order->tracking)
        <div class="tracking-info">
            <h4><i class="ri-truck-line"></i> Tracking Information</h4>
            <p>Your order is being prepared for shipment. You can track your order using the tracking number below:</p>
            <p><strong>Tracking Number:</strong> <span class="tracking-number">{{ $order->tracking->tracking_number }}</span></p>
            <p><strong>Estimated Delivery:</strong> {{ $order->tracking->estimated_delivery->format('M d, Y') }}</p>
        </div>
        @endif
        
        <div class="order-items">
            <h3>Order Items</h3>
            @foreach($order->items as $item)
            <div class="order-item">
                <img src="{{ str_starts_with($item->product->image ?? 'default.jpg', 'http') ? $item->product->image : asset('storage/' . ($item->product->image ?? 'default.jpg')) }}" 
                     alt="{{ $item->product->name ?? 'Product' }}">
                <div class="item-info">
                    <div class="item-name">{{ $item->product->name ?? 'Product' }}</div>
                    <div class="item-quantity">Quantity: {{ $item->quantity }}</div>
                </div>
                <div class="item-price">₱{{ number_format($item->total, 2) }}</div>
            </div>
            @endforeach
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('tracking', $order->id) }}" class="btn btn-primary">
                <i class="ri-truck-line"></i> Track Order
            </a>
            <a href="{{ route('profile.orders') }}" class="btn btn-outline">
                <i class="ri-list-check"></i> View All Orders
            </a>
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="ri-home-line"></i> Continue Shopping
            </a>
        </div>
    </div>
    
    <div class="similar-products">
        <h3>You Might Also Like</h3>
        <div class="product-grid">
            <!-- Similar products will be loaded here -->
            <div class="product-card" onclick="window.location.href='{{ route('home') }}'">
                <img src="{{ asset('ssa/headphones.jpg') }}" alt="Wireless Headphones">
                <h4>Wireless Headphones</h4>
                <div class="price">₱1,299</div>
            </div>
            <div class="product-card" onclick="window.location.href='{{ route('home') }}'">
                <img src="{{ asset('ssa/smartwatch.jpg') }}" alt="Smart Watch">
                <h4>Smart Watch</h4>
                <div class="price">₱2,999</div>
            </div>
            <div class="product-card" onclick="window.location.href='{{ route('home') }}'">
                <img src="{{ asset('ssa/laptop.jpg') }}" alt="Gaming Laptop">
                <h4>Gaming Laptop</h4>
                <div class="price">₱45,999</div>
            </div>
            <div class="product-card" onclick="window.location.href='{{ route('home') }}'">
                <img src="{{ asset('ssa/tablet.jpg') }}" alt="Tablet">
                <h4>Tablet</h4>
                <div class="price">₱8,999</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any additional JavaScript functionality here
    document.addEventListener('DOMContentLoaded', function() {
        // You can add animations or other interactive features
        console.log('Order confirmation page loaded');
    });
</script>
@endsection