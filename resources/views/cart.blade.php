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
    
    .cart-header {
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #e5e7eb;
    }
    
    .select-all-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .select-all-section input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--main-color);
    }
    
    .select-all-section label {
        font-weight: 600;
        color: var(--text-color);
        cursor: pointer;
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
        transition: all 0.3s ease;
        position: relative;
    }
    
    .cart-item:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .cart-item.removing {
        opacity: 0.5;
        transform: scale(0.95);
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
        gap: 8px;
        background: white;
        border-radius: 8px;
        padding: 4px;
        border: 1px solid #d1d5db;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .quantity-controls button {
        width: 32px;
        height: 32px;
        border: none;
        background: var(--main-color);
        color: white;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        transition: all 0.2s ease;
    }
    
    .quantity-controls button:hover {
        background: #1e40af;
        transform: scale(1.05);
    }
    
    .quantity-controls button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }
    
    .quantity-display {
        min-width: 40px;
        text-align: center;
        font-weight: 600;
        color: #374151;
    }
    
    .save-for-later-btn {
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }
    
    .save-for-later-btn:hover {
        background: #4b5563;
        transform: translateY(-1px);
    }
    
    .wishlist-btn {
        background: #ec4899;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }
    
    .wishlist-btn:hover {
        background: #db2777;
        transform: translateY(-1px);
    }
    
    .item-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
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
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-decoration: none !important;
    }
    
    .checkout-button:hover:not(:disabled) {
        background: var(--main-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        text-decoration: none !important;
        color: white !important;
    }
    
    .checkout-button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .checkout-button i {
        font-size: 1.2rem;
    }
    
    .cart-actions {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    
    .continue-shopping-btn {
        flex: 1;
        padding: 15px;
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
            display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .continue-shopping-btn:hover {
        background: var(--main-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        text-decoration: none;
        color: white;
    }
    
    .continue-shopping-btn i {
        font-size: 1.1rem;
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
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .cart-actions {
            flex-direction: column;
        }
        
        .continue-shopping-btn,
        .checkout-button {
            width: 100%;
        }
        
        .item-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .wishlist-btn,
        .save-for-later-btn,
        .remove-btn {
            width: 100%;
            justify-content: center;
        }
        
        .product-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .cart-item-header {
            flex-direction: column;
            text-align: center;
        }
        
        .quantity-and-notes {
            flex-direction: column;
            gap: 15px;
        }
    }
    
    @media (max-width: 480px) {
        .main-container {
            padding-top: 80px;
        }
        
        .cart-section,
        .product-grid-section {
            margin: 10px;
            padding: 15px;
        }
        
        .cart-item {
            padding: 15px;
        }
        
        .checkout-button,
        .continue-shopping-btn {
            padding: 12px;
            font-size: 1rem;
        }
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
            <div class="cart-header">
                <div class="select-all-section">
                    <input type="checkbox" id="select-all-checkbox" checked onchange="toggleAllItems()">
                    <label for="select-all-checkbox">Select All Items</label>
                </div>
            </div>
            
            <ul class="cart-items" id="cart-items-list">
                        @foreach(session('cart') as $item)
                    <li class="cart-item" data-row-id="{{ $item['rowId'] }}">
                        <div class="cart-item-header">
                            <input type="checkbox" class="item-checkbox" id="checkbox-{{ $item['rowId'] }}" checked onchange="updateSelectedItems()">
                            <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image']) }}" 
                                 alt="{{ $item['name'] }}">
                            <div class="item-details">
                                <span class="cart-item-name">{{ $item['name'] }}</span>
                                <span class="cart-item-price">₱{{ number_format($item['price'], 2) }}</span>
            </div>
                        </div>
                        <div class="quantity-and-notes">
                            <div class="quantity-controls">
                                <button onclick="changeQuantity('{{ $item['rowId'] }}', -1)" 
                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                <span class="quantity-display">{{ $item['quantity'] }}</span>
                                <button onclick="changeQuantity('{{ $item['rowId'] }}', 1)">+</button>
                            </div>
                            <div class="item-actions">
                                <button onclick="addToWishlist('{{ $item['rowId'] }}', '{{ $item['name'] }}', {{ $item['price'] }}, '{{ $item['image'] }}')" class="wishlist-btn" title="Add to wishlist">
                                    <i class="ri-heart-line"></i>
                                    Wishlist
                                </button>
                                <button onclick="saveForLater('{{ $item['rowId'] }}')" class="save-for-later-btn" title="Save for later">
                                    <i class="ri-bookmark-line"></i>
                                    Save for Later
                                </button>
                                <button onclick="removeItem('{{ $item['rowId'] }}')" class="remove-btn" title="Remove item">
                                    <i class="ri-delete-bin-line"></i>
                                    Remove
                                </button>
                            </div>
                            <textarea class="item-notes" placeholder="Add a note (e.g., 'no box')"></textarea>
                        </div>
                    </li>
                        @endforeach
            </ul>
            
            
            <div class="cart-summary">
                <div class="summary-row">
                    <span class="summary-label">Selected Items (<span id="selected-items-count">{{ count(session('cart')) }}</span>)</span>
                    <span class="summary-total" id="selected-items-total">₱{{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                <div class="summary-row" style="font-size: 0.9rem; color: #6b7280;">
                    <span>Total Items in Cart: <span id="total-cart-items">{{ count(session('cart')) }}</span></span>
            </div>
        </div>
        
            <div class="cart-actions">
                <a href="{{ route('products') }}" class="continue-shopping-btn">
                    <i class="ri-arrow-left-line"></i>
                    Continue Shopping
                </a>
                
                @auth
                    <button onclick="proceedToCheckout()" class="checkout-button" id="checkout-button">
                        <i class="ri-shopping-bag-line"></i>
                        Proceed to Checkout (<span id="checkout-items-count">{{ count(session('cart')) }}</span>)
                    </button>
                @else
                    <a href="{{ route('login') }}" class="checkout-button">
                        <i class="ri-login-circle-line"></i>
                        Login to Checkout
                    </a>
                @endauth
            </div>
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
            // Add visual feedback
            const cartItem = document.querySelector(`[data-row-id="${rowId}"]`);
            if (cartItem) {
                cartItem.classList.add('removing');
            }
            
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
    
    function saveForLater(rowId) {
        if (confirm('Save this item for later? It will be removed from your cart but saved in your wishlist.')) {
            // Add visual feedback
            const cartItem = document.querySelector(`[data-row-id="${rowId}"]`);
            if (cartItem) {
                cartItem.style.opacity = '0.5';
            }
            
            // Create a form to submit the save request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cart/save-for-later/${rowId}`;
            
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
    
    function updateQuantityButtons() {
        document.querySelectorAll('.quantity-controls').forEach(control => {
            const quantitySpan = control.querySelector('.quantity-display');
            const decreaseBtn = control.querySelector('button:first-child');
            const quantity = parseInt(quantitySpan.textContent);
            
            if (quantity <= 1) {
                decreaseBtn.disabled = true;
            } else {
                decreaseBtn.disabled = false;
            }
        });
    }
    
    function addToWishlist(rowId, productName, price, image) {
        // Get existing wishlist from localStorage
        let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        
        // Check if item already exists in wishlist
        const existingItem = wishlist.find(item => item.rowId === rowId);
        
        if (existingItem) {
            alert('This item is already in your wishlist!');
                return;
            }

        // Add to wishlist
        wishlist.push({
            rowId: rowId,
            name: productName,
            price: price,
            image: image,
            addedAt: new Date().toISOString()
        });
        
        // Save to localStorage
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        
        // Show success message
        alert('Item added to wishlist!');
        
        // Update wishlist count in header if it exists
        updateWishlistCount();
    }
    
    function updateWishlistCount() {
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const wishlistCount = document.getElementById('wishlist-count');
        if (wishlistCount) {
            wishlistCount.textContent = wishlist.length;
        }
    }
    
    function updateSelectedItems() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        let selectedCount = 0;
        let selectedTotal = 0;
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
                const cartItem = checkbox.closest('.cart-item');
                const priceElement = cartItem.querySelector('.cart-item-price');
                const quantityElement = cartItem.querySelector('.quantity-display');
                
                if (priceElement && quantityElement) {
                    const price = parseFloat(priceElement.textContent.replace('₱', '').replace(',', ''));
                    const quantity = parseInt(quantityElement.textContent);
                    selectedTotal += price * quantity;
                }
            }
        });
        
        // Update select all checkbox state
        if (selectedCount === checkboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (selectedCount === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
        
        // Update summary
        document.getElementById('selected-items-count').textContent = selectedCount;
        document.getElementById('selected-items-total').textContent = '₱' + selectedTotal.toFixed(2);
        document.getElementById('checkout-items-count').textContent = selectedCount;
        
        // Enable/disable checkout button
        const checkoutButton = document.getElementById('checkout-button');
        if (checkoutButton) {
            checkoutButton.disabled = selectedCount === 0;
            if (selectedCount === 0) {
                checkoutButton.style.opacity = '0.5';
                checkoutButton.style.cursor = 'not-allowed';
            } else {
                checkoutButton.style.opacity = '1';
                checkoutButton.style.cursor = 'pointer';
            }
        }
    }
    
    function toggleAllItems() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        
        updateSelectedItems();
    }
    
    function proceedToCheckout() {
        const selectedItems = [];
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        
        if (checkboxes.length === 0) {
            alert('Please select at least one item to proceed to checkout.');
                return;
            }
            
        checkboxes.forEach(checkbox => {
            const cartItem = checkbox.closest('.cart-item');
            const rowId = cartItem.getAttribute('data-row-id');
            selectedItems.push(rowId);
        });
        
        // Create a form to submit selected items to checkout
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("checkout") }}';
        
        // Add selected items as query parameters
        selectedItems.forEach((item, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `selected_items[${index}]`;
            input.value = item;
            form.appendChild(input);
        });
        
        // Add to DOM and submit
        document.body.appendChild(form);
        form.submit();
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