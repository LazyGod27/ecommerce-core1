@extends('layouts.frontend')

@section('title', 'iMarket - Checkout')

@section('styles')
<style>
    .checkout-container {
        padding-top: 100px;
        min-height: 100vh;
        background: var(--bg-color);
    }
    
    .checkout-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    
    .checkout-main {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .checkout-sidebar {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
        height: fit-content;
        position: sticky;
        top: 120px;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--main-color);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-color);
        font-weight: 500;
    }
    
    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }
    
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
        outline: none;
        border-color: var(--main-color);
        box-shadow: 0 0 0 3px rgba(44, 60, 140, 0.1);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }
    
    .payment-method {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    
    .payment-method:hover {
        border-color: var(--main-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .payment-method.selected {
        border-color: var(--main-color);
        background: #f0f9ff;
    }
    
    .payment-method img {
        width: 50px;
        height: 30px;
        object-fit: contain;
        margin-bottom: 10px;
    }
    
    .payment-method h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 5px;
    }
    
    .payment-method p {
        font-size: 0.9rem;
        color: #6b7280;
    }
    
    .order-summary {
        background: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .summary-item:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-color);
    }
    
    .summary-total {
        color: var(--main-color);
        font-size: 1.3rem;
        font-weight: 700;
    }
    
    .checkout-button {
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
    }
    
    .checkout-button:hover:not(:disabled) {
        background: #1e40af;
        transform: translateY(-2px);
    }
    
    .checkout-button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
    
    .cart-items {
        margin-bottom: 30px;
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 10px;
        background: #f9fafb;
    }
    
    .cart-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
    }
    
    .cart-item-details {
        flex: 1;
    }
    
    .cart-item-name {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 5px;
    }
    
    .cart-item-price {
        color: var(--main-color);
        font-weight: 600;
    }
    
    .cart-item-quantity {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .voucher-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .voucher-input {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .voucher-input input {
        flex: 1;
    }
    
    .voucher-button {
        padding: 12px 20px;
        background: var(--main-color);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s ease;
    }
    
    .voucher-button:hover {
        background: #1e40af;
    }
    
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-cart i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 20px;
    }
    
    .empty-cart h2 {
        color: var(--text-color);
        margin-bottom: 10px;
    }
    
    .empty-cart p {
        color: #6b7280;
        margin-bottom: 30px;
    }
    
    .continue-shopping-btn {
        display: inline-block;
        padding: 12px 24px;
        background: var(--main-color);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .continue-shopping-btn:hover {
        background: #1e40af;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .checkout-content {
            grid-template-columns: 1fr;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .payment-methods {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="checkout-container">
    <div class="checkout-content">
        <div class="checkout-main">
            <h1 class="section-title">Checkout</h1>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('cart') && count(session('cart')) > 0)
                <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
                    @csrf
                    <input type="hidden" name="payment_method" id="payment_method">
                    
                    <!-- Cart Items -->
                    <div class="cart-items">
                        <h2 class="section-title">Your Cart</h2>
                        @foreach(session('cart') as $item)
                            <div class="cart-item">
                                <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}">
                                <div class="cart-item-details">
                                    <div class="cart-item-name">{{ $item['name'] }}</div>
                                    <div class="cart-item-price">₱{{ number_format($item['price'], 2) }}</div>
                                    <div class="cart-item-quantity">Qty: {{ $item['quantity'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Voucher Section -->
                    <div class="voucher-section">
                        <h3>Voucher Code</h3>
                        <div class="voucher-input">
                            <input type="text" id="voucher-code" placeholder="Enter voucher code">
                            <button type="button" class="voucher-button" id="apply-voucher">Apply</button>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <h2 class="section-title">Shipping Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping_name">Full Name *</label>
                            <input type="text" id="shipping_name" name="shipping_name" value="{{ $user->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="shipping_phone">Phone Number *</label>
                            <input type="tel" id="shipping_phone" name="shipping_phone" value="{{ $user->phone ?? '' }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address *</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required>{{ $user->address_line1 ?? '' }}</textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping_city">City *</label>
                            <input type="text" id="shipping_city" name="shipping_city" value="{{ $user->city ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="shipping_postal">Postal Code *</label>
                            <input type="text" id="shipping_postal" name="shipping_postal" value="{{ $user->postal_code ?? '' }}" required>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <h2 class="section-title">Payment Method</h2>
                    <div class="payment-methods">
                        <div class="payment-method" data-method="GCash">
                            <img src="{{ asset('ssa/gcash.png') }}" alt="GCash">
                            <h3>GCash</h3>
                            <p>E-wallet</p>
                        </div>
                        <div class="payment-method" data-method="PayMaya">
                            <img src="{{ asset('ssa/maya.png') }}" alt="PayMaya">
                            <h3>PayMaya</h3>
                            <p>E-wallet</p>
                        </div>
                        <div class="payment-method" data-method="Card">
                            <img src="{{ asset('ssa/visa.png') }}" alt="Card">
                            <h3>Credit/Debit Card</h3>
                            <p>Visa, Mastercard</p>
                        </div>
                        <div class="payment-method" data-method="COD">
                            <i class="ri-money-dollar-circle-line" style="font-size: 2rem; color: var(--main-color);"></i>
                            <h3>Cash on Delivery</h3>
                            <p>Pay when delivered</p>
                        </div>
                    </div>
                </form>
            @else
                <div class="empty-cart">
                    <i class="ri-shopping-cart-line"></i>
                    <h2>Your cart is empty</h2>
                    <p>Add some items to get started!</p>
                    <a href="{{ route('products') }}" class="continue-shopping-btn">Continue Shopping</a>
                </div>
            @endif
        </div>

        <div class="checkout-sidebar">
            <h2 class="section-title">Order Summary</h2>
            
            <div class="order-summary">
                <div class="summary-item">
                    <span>Subtotal</span>
                    <span id="subtotal">₱{{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span>Shipping</span>
                    <span id="shipping">₱{{ number_format($shipping ?? 50, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span>Tax (12% VAT)</span>
                    <span id="tax">₱{{ number_format($tax ?? 0, 2) }}</span>
                </div>
                <div class="summary-item" id="discount-row" style="display: none;">
                    <span>Discount</span>
                    <span id="discount">-₱0.00</span>
                </div>
                <div class="summary-item">
                    <span>Total</span>
                    <span class="summary-total" id="total">₱{{ number_format($total ?? (($subtotal ?? 0) + ($tax ?? 0) + ($shipping ?? 50)), 2) }}</span>
                </div>
            </div>

            @if(session('cart') && count(session('cart')) > 0)
                @auth
                    <button type="submit" form="checkout-form" class="checkout-button" id="place-order-btn" disabled>
                        Place Order
                    </button>
                @else
                    <a href="{{ route('login') }}" class="checkout-button" style="text-decoration: none; display: block; text-align: center;">
                        Login to Place Order
                    </a>
                @endauth
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const paymentMethods = document.querySelectorAll('.payment-method');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const paymentMethodInput = document.getElementById('payment_method');
        const voucherCodeInput = document.getElementById('voucher-code');
        const applyVoucherBtn = document.getElementById('apply-voucher');
        const subtotalElement = document.getElementById('subtotal');
        const shippingElement = document.getElementById('shipping');
        const taxElement = document.getElementById('tax');
        const discountElement = document.getElementById('discount');
        const discountRow = document.getElementById('discount-row');
        const totalElement = document.getElementById('total');

        let selectedMethod = null;
        let appliedDiscount = 0;
        let isVoucherApplied = false;

        const vouchers = {
            'IMARKET10': { type: 'percent', value: 0.10, minPurchase: 100 },
            'FREESHIP': { type: 'shipping', value: 50.00, minPurchase: 500 },
            'SAVE50': { type: 'fixed', value: 50.00, minPurchase: 250 }
        };

        const subtotal = {{ $subtotal ?? 0 }};
        const shipping = {{ $shipping ?? 50 }};
        const tax = {{ $tax ?? 0 }};

        function updateOrderSummary() {
            let currentShipping = shipping;
            if (isVoucherApplied && voucherCodeInput.value.toUpperCase() === 'FREESHIP') {
                currentShipping = 0;
            }

            const total = subtotal - appliedDiscount + currentShipping + tax;
            
            subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
            shippingElement.textContent = `₱${currentShipping.toFixed(2)}`;
            taxElement.textContent = `₱${tax.toFixed(2)}`;
            discountElement.textContent = `-₱${appliedDiscount.toFixed(2)}`;
            totalElement.textContent = `₱${total.toFixed(2)}`;
        }

        // Payment method selection
        paymentMethods.forEach(method => {
            method.addEventListener('click', () => {
                paymentMethods.forEach(m => m.classList.remove('selected'));
                method.classList.add('selected');
                
                selectedMethod = method.dataset.method;
                paymentMethodInput.value = selectedMethod;
                placeOrderBtn.disabled = false;
            });
        });

        // Voucher application
        applyVoucherBtn.addEventListener('click', () => {
            if (subtotal === 0) {
                alert('Your cart is empty. Cannot apply a voucher.');
                return;
            }
            if (isVoucherApplied) {
                alert('A voucher has already been applied.');
                return;
            }

            const voucherCode = voucherCodeInput.value.toUpperCase();
            const voucher = vouchers[voucherCode];

            if (!voucher) {
                alert('Invalid voucher code. Please try again.');
                voucherCodeInput.value = '';
                return;
            }

            if (subtotal < voucher.minPurchase) {
                alert(`This voucher requires a minimum purchase of ₱${voucher.minPurchase.toFixed(2)}.`);
                return;
            }

            let discount = 0;
            if (voucher.type === 'percent') {
                discount = subtotal * voucher.value;
            } else if (voucher.type === 'fixed') {
                discount = voucher.value;
            } else if (voucher.type === 'shipping') {
                discount = 0;
            }
            
            appliedDiscount = discount;
            isVoucherApplied = true;
            discountRow.style.display = 'flex';
            updateOrderSummary();
            alert(`Voucher "${voucherCode}" applied successfully!`);
        });

        // Form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            if (!selectedMethod) {
                e.preventDefault();
                alert('Please select a payment method.');
                return;
            }
            
            // Show loading state
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Processing...';
        });

        // Initialize
        updateOrderSummary();
    });
</script>
@endsection
