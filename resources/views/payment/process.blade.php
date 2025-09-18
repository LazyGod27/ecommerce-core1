@extends('layouts.frontend')

@section('title', 'Payment Processing - iMarket')

@section('styles')
<style>
    .main-container {
        padding-top: 100px;
        min-height: 100vh;
        background: var(--bg-color);
    }
    
    .payment-section {
        padding: 40px 20px;
        background: white;
        margin: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .payment-icon {
        font-size: 4rem;
        color: #f59e0b;
        margin-bottom: 20px;
    }
    
    .payment-title {
        font-size: 2rem;
        color: var(--text-color);
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .payment-subtitle {
        font-size: 1.1rem;
        color: #6b7280;
        margin-bottom: 30px;
    }
    
    .order-summary {
        background: #f8fafc;
        padding: 30px;
        border-radius: 12px;
        margin: 30px 0;
        text-align: left;
    }
    
    .order-summary h3 {
        color: var(--text-color);
        margin-bottom: 20px;
        font-size: 1.5rem;
        text-align: center;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .summary-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--main-color);
    }
    
    .summary-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .summary-value {
        color: var(--text-color);
        font-weight: 600;
    }
    
    .payment-options {
        margin: 30px 0;
    }
    
    .payment-option {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin: 15px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: left;
    }
    
    .payment-option:hover {
        border-color: var(--main-color);
        background: #f0f9ff;
    }
    
    .payment-option.selected {
        border-color: var(--main-color);
        background: #f0f9ff;
    }
    
    .payment-option-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }
    
    .payment-option img {
        width: 40px;
        height: 30px;
        object-fit: contain;
    }
    
    .payment-option-title {
        font-weight: 600;
        color: var(--text-color);
        font-size: 1.1rem;
    }
    
    .payment-option-description {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .proceed-button {
        width: 100%;
        padding: 15px;
        background: var(--main-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }
    
    .proceed-button:hover:not(:disabled) {
        background: #1e40af;
        transform: translateY(-2px);
    }
    
    .proceed-button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
    
    .loading {
        display: none;
        margin-top: 20px;
    }
    
    .loading-spinner {
        border: 4px solid #f3f4f6;
        border-top: 4px solid var(--main-color);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .payment-title {
            font-size: 1.5rem;
        }
        
        .order-summary {
            padding: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="main-container">
    <div class="payment-section">
        <div class="payment-icon">
            <i class="ri-credit-card-line"></i>
        </div>
        
        <h1 class="payment-title">Complete Your Payment</h1>
        <p class="payment-subtitle">Choose your preferred payment method to complete your order</p>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            
            <div class="summary-row">
                <span class="summary-label">Order Number:</span>
                <span class="summary-value">{{ $order->order_number }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Payment Method:</span>
                <span class="summary-value">{{ $order->payment_method }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Subtotal:</span>
                <span class="summary-value">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Shipping:</span>
                <span class="summary-value">₱{{ number_format($order->shipping_cost, 2) }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Tax:</span>
                <span class="summary-value">₱{{ number_format($order->tax, 2) }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Total Amount:</span>
                <span class="summary-value">₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
        
        <div class="payment-options">
            <div class="payment-option selected" data-method="cod">
                <div class="payment-option-header">
                    <i class="ri-money-dollar-circle-line" style="font-size: 2rem; color: #10b981;"></i>
                    <div>
                        <div class="payment-option-title">Cash on Delivery (COD)</div>
                        <div class="payment-option-description">Pay when you receive your order</div>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="proceed-button" onclick="proceedWithPayment()">
            Complete Order
        </button>
        
        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Processing your order...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function proceedWithPayment() {
        const button = document.querySelector('.proceed-button');
        const loading = document.getElementById('loading');
        
        // Show loading
        button.disabled = true;
        loading.style.display = 'block';
        
        // Simulate payment processing
        setTimeout(() => {
            // Redirect to order confirmation
            window.location.href = '{{ route("order.confirmation", $order->id) }}';
        }, 2000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Payment process page loaded');
    });
</script>
@endsection