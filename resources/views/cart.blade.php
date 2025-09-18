@extends('layouts.frontend')

@section('title', 'iMarket - Shopping Cart')

@section('styles')
<style>
    .main-container {
        padding-top: 100px;
        min-height: 100vh;
        background: var(--bg-color);
    }
    
    .product-grid-section {
        padding: 20px;
        background: white;
        margin: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .cart-section {
        padding: 20px;
        background: white;
        margin: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .cart-items {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .cart-item {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        background: #f9fafb;
    }
    
    .cart-item-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .item-checkbox {
        width: 18px;
        height: 18px;
        accent-color: var(--main-color);
    }
    
    .cart-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .item-details {
        flex: 1;
    }
    
    .cart-item-name {
        font-weight: 600;
        color: var(--text-color);
        display: block;
        margin-bottom: 5px;
    }
    
    .cart-item-price {
        color: var(--main-color);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .quantity-and-notes {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }
    
    .item-actions {
        display: flex;
        gap: 10px;
    }
    
    .remove-btn {
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .remove-btn:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        border-radius: 8px;
        padding: 5px;
        border: 1px solid #d1d5db;
    }
    
    .quantity-controls button {
        width: 30px;
        height: 30px;
        border: none;
        background: var(--main-color);
        color: white;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .quantity-controls button:hover {
        background: #1e40af;
    }
    
    .quantity-controls span {
        min-width: 30px;
        text-align: center;
        font-weight: 600;
    }
    
    .item-notes {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        resize: vertical;
        min-height: 40px;
        font-family: inherit;
    }
    
    .checkout-details {
        margin: 20px 0;
        padding: 20px;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .checkout-details h3 {
        margin-bottom: 15px;
        color: var(--text-color);
        font-weight: 600;
    }
    
    .voucher-input input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
    
    .payment-methods label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        background: white;
        transition: all 0.3s ease;
    }
    
    .payment-methods label:hover {
        border-color: var(--main-color);
        background: #f0f9ff;
    }
    
    .payment-methods input[type="radio"] {
        accent-color: var(--main-color);
    }
    
    .payment-methods img {
        width: 30px;
        height: 20px;
        object-fit: contain;
    }
    
    .cart-summary {
        background: #f8fafc;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .summary-total {
        color: var(--main-color);
        font-size: 1.3rem;
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
    
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: white;
        margin: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
    }
    
    .product-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .product-card h3 {
        padding: 15px;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
    }
    
    .product-card .price {
        padding: 0 15px 15px;
        color: var(--main-color);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .add-to-cart-button {
        width: 100%;
        padding: 10px;
        background: var(--main-color);
        color: white;
        border: none;
        border-radius: 0 0 12px 12px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s ease;
    }
    
    .add-to-cart-button:hover {
        background: #1e40af;
    }
</style>
@endsection

@section('content')
<div class="main-container">
    <section class="product-grid-section">
        <h2>Popular Products</h2>
        <div class="product-grid" id="product-list">
            <!-- Popular products will be loaded here -->
        </div>
    </section>
    
    <section class="cart-section">
        <h2>Shopping Cart</h2>
        
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
            <ul class="cart-items" id="cart-items-list">
                @foreach(session('cart') as $item)
                    <li class="cart-item" data-row-id="{{ $item['rowId'] }}">
                        <div class="cart-item-header">
                            <input type="checkbox" class="item-checkbox" checked onchange="updateCart()">
                            <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}" 
                                 alt="{{ $item['name'] }}">
                            <div class="item-details">
                                <span class="cart-item-name">{{ $item['name'] }}</span>
                                <span class="cart-item-price">₱{{ number_format($item['price'], 2) }}</span>
                            </div>
                        </div>
                        <div class="quantity-and-notes">
                            <div class="quantity-controls">
                                <button onclick="changeQuantity('{{ $item['rowId'] }}', -1)">-</button>
                                <span>{{ $item['quantity'] }}</span>
                                <button onclick="changeQuantity('{{ $item['rowId'] }}', 1)">+</button>
                            </div>
                            <div class="item-actions">
                                <button onclick="removeItem('{{ $item['rowId'] }}')" class="remove-btn" title="Remove item">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                            <textarea class="item-notes" placeholder="Add a note (e.g., 'no box')"></textarea>
                        </div>
                    </li>
                @endforeach
            </ul>
            
            <div class="checkout-details">
                <h3>Voucher</h3>
                <div class="voucher-input">
                    <input type="text" placeholder="Enter voucher code">
                </div>
                
                <h3>Payment Method</h3>
                <div class="payment-methods">
                    <label>
                        <input type="radio" name="payment" value="gcash" checked>
                        <img src="{{ asset('ssa/gcash.png') }}" alt="GCash Logo"> 
                        GCash
                    </label>
                    <label>
                        <input type="radio" name="payment" value="paymaya">
                        <img src="{{ asset('ssa/maya.png') }}" alt="PayMaya Logo"> 
                        PayMaya
                    </label>
                    <label>
                        <input type="radio" name="payment" value="card">
                        <img src="{{ asset('ssa/visa.png') }}" alt="Card Logo"> 
                        Credit/Debit Card
                    </label>
                    <label>
                        <input type="radio" name="payment" value="cod">
                        <i class="ri-money-dollar-circle-line"></i> 
                        Cash on Delivery (COD)
                    </label>
                </div>
            </div>
            
            <div class="cart-summary">
                <div class="summary-row">
                    <span class="summary-label">Selected Items (<span id="total-items">{{ count(session('cart')) }}</span>)</span>
                    <span class="summary-total" id="cart-total">₱{{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
            </div>
            
            @auth
                <form method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    <button type="submit" class="checkout-button" id="checkout-button">
                        Checkout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="checkout-button" style="text-decoration: none; display: block; text-align: center;">
                    Login to Checkout
                </a>
            @endauth
        @else
            <div class="empty-cart">
                <i class="ri-shopping-cart-line"></i>
                <h2>Your cart is empty</h2>
                <p>Add some items to get started!</p>
                <a href="{{ route('products') }}" class="continue-shopping-btn">Continue Shopping</a>
            </div>
        @endif
    </section>
</div>
@endsection

@section('scripts')
<script>
    function updateCart() {
        // Update cart totals based on checked items
        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
        const totalItems = Array.from(checkedItems).reduce((sum, checkbox) => {
            const quantitySpan = checkbox.closest('.cart-item').querySelector('.quantity-controls span');
            return sum + parseInt(quantitySpan.textContent);
        }, 0);
        
        document.getElementById('total-items').textContent = totalItems;
        
        // Calculate total price
        let totalPrice = 0;
        checkedItems.forEach(checkbox => {
            const cartItem = checkbox.closest('.cart-item');
            const priceText = cartItem.querySelector('.cart-item-price').textContent;
            const price = parseFloat(priceText.replace('₱', '').replace(',', ''));
            const quantity = parseInt(cartItem.querySelector('.quantity-controls span').textContent);
            totalPrice += price * quantity;
        });
        
        document.getElementById('cart-total').textContent = `₱${totalPrice.toFixed(2)}`;
        
        // Enable/disable checkout button
        const checkoutButton = document.getElementById('checkout-button');
        if (checkoutButton) {
            checkoutButton.disabled = totalItems === 0;
        }
    }
    
    function changeQuantity(rowId, change) {
        const quantitySpan = event.target.parentElement.querySelector('span');
        let quantity = parseInt(quantitySpan.textContent);
        quantity += change;
        
        if (quantity <= 0) {
            // Remove item from cart
            removeItem(rowId);
        } else {
            // Update quantity via form submission
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cart/update/${rowId}`;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            // Add quantity
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = quantity;
            form.appendChild(quantityInput);
            
            // Add to DOM and submit
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function removeItem(rowId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cart/remove/${rowId}`;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            // Add to DOM and submit
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Load popular products
    function loadPopularProducts() {
        const productList = document.getElementById('product-list');
        // This would typically fetch from an API
        // For now, we'll show a placeholder
        productList.innerHTML = '<p class="text-center text-gray-500">Popular products will be loaded here</p>';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        loadPopularProducts();
        updateCart();
    });
</script>
@endsection