@extends('layouts.frontend')

@section('title', 'Shopping Cart - iMarket')

@section('content')
<style>
    /* Blue Color Scheme */
    :root {
        --primary-blue: #2563eb;
        --secondary-blue: #3b82f6;
        --light-blue: #dbeafe;
        --dark-blue: #1e40af;
        --accent-blue: #60a5fa;
        --success-green: #10b981;
        --warning-orange: #f59e0b;
        --danger-red: #ef4444;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: var(--gray-50);
        margin: 0;
        padding: 0;
        padding-bottom: 120px !important; /* Space for fixed cart summary */
    }

    .cart-page {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--light-blue) 0%, #ffffff 100%);
        padding-top: 80px;
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .cart-header {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--primary-blue);
    }

    .cart-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cart-title::before {
        content: "🛒";
        font-size: 32px;
    }

    .cart-stats {
        display: flex;
        gap: 24px;
        align-items: center;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray-600);
        font-size: 14px;
    }

    .stat-number {
        font-weight: 600;
        color: var(--primary-blue);
    }

    .cart-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .cart-table-header {
        background: var(--gray-50);
        padding: 16px 24px;
        border-bottom: 1px solid var(--gray-200);
        display: grid;
        grid-template-columns: 50px 1fr 120px 120px 120px 100px;
        gap: 16px;
        align-items: center;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 14px;
    }

    .cart-items {
        min-height: 200px;
    }

    .seller-group {
        border-bottom: 1px solid var(--gray-200);
    }

    .seller-header {
        background: var(--light-blue);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid var(--gray-200);
    }

    .seller-checkbox {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-blue);
    }

    .seller-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .seller-name {
        font-weight: 600;
        color: var(--gray-800);
        font-size: 16px;
    }

    .seller-count {
        background: var(--primary-blue);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .chat-btn {
        background: var(--secondary-blue);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .chat-btn:hover {
        background: var(--dark-blue);
    }

    .cart-item {
        padding: 20px 24px;
        display: grid;
        grid-template-columns: 50px 1fr 120px 120px 120px 100px;
        gap: 16px;
        align-items: center;
        border-bottom: 1px solid var(--gray-100);
        transition: background-color 0.2s;
    }

    .cart-item:hover {
        background: var(--gray-50);
    }

    .item-checkbox {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-blue);
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .product-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid var(--gray-200);
    }

    .product-details {
        flex: 1;
    }

    .product-name {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 8px;
        font-size: 16px;
        line-height: 1.4;
    }

    .product-badges {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge.blue {
        background: var(--light-blue);
        color: var(--primary-blue);
    }

    .badge.green {
        background: #dcfce7;
        color: var(--success-green);
    }

    .badge.orange {
        background: #fef3c7;
        color: var(--warning-orange);
    }

    .product-variations {
        margin-top: 8px;
    }

    .variation-select {
        padding: 6px 12px;
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        background: white;
        font-size: 14px;
        color: var(--gray-700);
    }

    .price-column {
        text-align: right;
    }

    .price-display {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
    }

    .current-price {
        font-weight: 700;
        color: var(--primary-blue);
        font-size: 18px;
    }

    .original-price {
        font-size: 14px;
        color: var(--gray-400);
        text-decoration: line-through;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .quantity-btn {
        width: 32px;
        height: 32px;
        border: 1px solid var(--gray-300);
        background: white;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 18px;
        color: var(--gray-600);
    }

    .quantity-btn:hover {
        background: var(--gray-50);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .quantity-display {
        min-width: 50px;
        text-align: center;
        font-weight: 600;
        color: var(--gray-800);
    }

    .total-price {
        font-weight: 700;
        color: var(--primary-blue);
        font-size: 18px;
        text-align: right;
    }

    .actions-column {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }

    .action-link {
        color: var(--primary-blue);
        text-decoration: none;
        font-size: 14px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .action-link:hover {
        background: var(--light-blue);
    }

    .cart-summary {
        background: white;
        padding: 24px;
        border-top: 1px solid var(--gray-200);
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 0 0 12px 12px;
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 1000 !important;
        transition: all 0.3s ease;
        width: 100% !important;
        min-height: 80px !important;
    }

    .cart-summary.stuck-to-content {
        position: relative !important;
        bottom: auto !important;
        left: auto !important;
        right: auto !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-top: 0 !important;
        /* Keep all other properties consistent */
        background: white !important;
        padding: 24px !important;
        border-top: 1px solid var(--gray-200) !important;
        border-radius: 0 0 12px 12px !important;
        width: 100% !important;
        min-height: 80px !important;
        transition: all 0.3s ease !important;
    }

    .summary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .summary-left {
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .select-all {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        color: var(--gray-700);
    }

    .select-all input {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-blue);
    }

    .bulk-actions {
        display: flex;
        gap: 12px;
    }

    .bulk-btn {
        padding: 8px 16px;
        border: 1px solid var(--gray-300);
        background: white;
        border-radius: 6px;
        color: var(--gray-600);
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .bulk-btn:hover {
        background: var(--gray-50);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .summary-right {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .total-info {
        text-align: right;
    }

    .total-label {
        font-size: 14px;
        color: var(--gray-600);
        margin-bottom: 4px;
    }

    .total-amount {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-blue);
    }

    .checkout-btn {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .checkout-btn:disabled {
        background: var(--gray-300);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray-500);
    }

    .empty-cart-icon {
        font-size: 64px;
        margin-bottom: 16px;
    }

    .empty-cart h3 {
        font-size: 24px;
        margin-bottom: 8px;
        color: var(--gray-700);
    }

    .empty-cart p {
        font-size: 16px;
        margin-bottom: 24px;
    }

    .continue-shopping {
        background: var(--primary-blue);
        color: white;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }

    .continue-shopping:hover {
        background: var(--dark-blue);
    }

    /* You May Also Like Section */
    .recommendations-section {
        margin-top: 20px;
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .recommendations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--gray-200);
    }

    .recommendations-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0;
    }

    .see-all-link {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
    }

    .see-all-link:hover {
        text-decoration: underline;
    }

    .recommendations-carousel {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .recommendations-carousel .product-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 12px;
        position: relative;
        transition: all 0.2s;
        min-width: 200px;
    }

    .recommendations-carousel .product-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .recommendations-carousel .product-badges {
        position: absolute;
        top: 8px;
        left: 8px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        z-index: 2;
    }

    .recommendations-carousel .badge {
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .recommendations-carousel .badge.mall {
        background: #ff6b35;
        color: white;
    }

    .recommendations-carousel .badge.preferred {
        background: #10b981;
        color: white;
    }

    .recommendations-carousel .badge.official {
        background: var(--primary-blue);
        color: white;
    }

    .recommendations-carousel .badge.fulfilled {
        background: #f59e0b;
        color: white;
    }

    .recommendations-carousel .badge.deal {
        background: #ef4444;
        color: white;
    }

    .recommendations-carousel .product-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .recommendations-carousel .product-name {
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-800);
        margin-bottom: 4px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .recommendations-carousel .product-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }

    .recommendations-carousel .rating {
        font-size: 11px;
        color: var(--warning-orange);
        font-weight: 600;
    }

    .recommendations-carousel .sold {
        font-size: 10px;
        color: var(--gray-500);
    }

    .recommendations-carousel .product-price {
        margin-bottom: 6px;
    }

    .recommendations-carousel .current-price {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-blue);
    }

    .recommendations-carousel .discount {
        font-size: 10px;
        color: var(--danger-red);
        margin-right: 4px;
    }

    .recommendations-carousel .product-badges-bottom {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .recommendations-carousel .badge.spaylater {
        background: var(--light-blue);
        color: var(--primary-blue);
        font-size: 9px;
    }

    .recommendations-carousel .badge.interest {
        background: #dcfce7;
        color: var(--success-green);
        font-size: 9px;
    }

    .recommendations-carousel .badge.shipping {
        background: #fef3c7;
        color: var(--warning-orange);
        font-size: 9px;
    }

    .recommendations-carousel .badge.mega {
        background: #fecaca;
        color: var(--danger-red);
        font-size: 9px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .cart-container {
            padding: 10px;
        }

        .cart-table-header {
            display: none;
        }

        .cart-item {
            display: block;
            padding: 16px;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .product-info {
            margin-bottom: 16px;
        }

        .product-image {
            width: 60px;
            height: 60px;
        }

        .price-column,
        .quantity-column,
        .total-column,
        .actions-column {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .summary-header {
            flex-direction: column;
            gap: 16px;
            align-items: stretch;
        }

        .summary-right {
            justify-content: space-between;
        }

        .checkout-btn {
            width: 100%;
        }

        .recommendations-carousel {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .recommendations-carousel .product-card {
            min-width: 150px;
            padding: 8px;
        }

        .recommendations-carousel .product-image {
            height: 100px;
        }

        .recommendations-carousel .product-name {
            font-size: 11px;
        }

        .recommendations-carousel .current-price {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .cart-title {
            font-size: 24px;
        }

        .product-name {
            font-size: 14px;
        }

        .current-price,
        .total-price {
            font-size: 16px;
        }
    }
</style>

<div class="cart-page">
    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">Shopping Cart</h1>
            <div class="cart-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ count($cart) }}</span>
                    <span>Items</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">₱{{ number_format($subtotal ?? 0, 0) }}</span>
                    <span>Total</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ count(array_unique(array_column($cart ?? [], 'seller'))) }}</span>
                    <span>Stores</span>
                </div>
            </div>
        </div>

        <div class="cart-content">
            <div class="cart-table-header">
                <div></div>
                <div>Product</div>
                <div>Unit Price</div>
                <div>Quantity</div>
                <div>Total Price</div>
                <div>Actions</div>
            </div>

            <div class="cart-items">
                @if(empty($cart) || count($cart) == 0)
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <h3>Your cart is empty</h3>
                        <p>Looks like you haven't added any items to your cart yet.</p>
                        <a href="{{ route('products') }}" class="continue-shopping">Continue Shopping</a>
                    </div>
                @else
                    @php
                        // Group cart items by seller/store
                        $groupedItems = [];
                        foreach($cart as $rowId => $item) {
                            $seller = $item['seller'] ?? 'Default Store';
                            if (!isset($groupedItems[$seller])) {
                                $groupedItems[$seller] = [];
                            }
                            $groupedItems[$seller][] = ['rowId' => $rowId, 'item' => $item];
                        }
                    @endphp

                    @foreach($groupedItems as $seller => $items)
                        <div class="seller-group">
                            <div class="seller-header">
                                <input type="checkbox" class="seller-checkbox" checked>
                                <div class="seller-info">
                                    <span class="seller-name">{{ $seller }}</span>
                                    <span class="seller-count">{{ count($items) }} {{ count($items) == 1 ? 'item' : 'items' }}</span>
                                </div>
                                <button class="chat-btn">Chat</button>
                            </div>

                            @foreach($items as $cartItem)
                                @php
                                    $rowId = $cartItem['rowId'];
                                    $item = $cartItem['item'];
                                    $itemTotal = $item['price'] * $item['quantity'];
                                @endphp
                                <div class="cart-item" data-row-id="{{ $rowId }}">
                                    <input type="checkbox" class="item-checkbox" checked>
                                    <div class="product-info">
                                        <img src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=150&h=150&fit=crop' }}" alt="{{ $item['name'] }}" class="product-image">
                                        <div class="product-details">
                                            <h3 class="product-name">{{ $item['name'] }}</h3>
                                            <div class="product-badges">
                                                <span class="badge blue">FREE SHIPPING</span>
                                                <span class="badge green">0% INTEREST</span>
                                            </div>
                                            <div class="product-variations">
                                                <select class="variation-select">
                                                    <option>Color: Black</option>
                                                    <option>Color: White</option>
                                                    <option>Color: Blue</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="price-column">
                                        <div class="price-display">
                                            <span class="current-price">₱{{ number_format($item['price'], 0) }}</span>
                                            @if(isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                <span class="original-price">₱{{ number_format($item['original_price'], 0) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="quantity-column">
                                        <div class="quantity-controls">
                                            <button class="quantity-btn" data-action="decrease" data-row-id="{{ $rowId }}">-</button>
                                            <span class="quantity-display">{{ $item['quantity'] }}</span>
                                            <button class="quantity-btn" data-action="increase" data-row-id="{{ $rowId }}">+</button>
                                        </div>
                                    </div>
                                    <div class="total-column">
                                        <span class="total-price">₱{{ number_format($itemTotal, 0) }}</span>
                                    </div>
                                    <div class="actions-column">
                                        <a href="#" class="action-link remove-item" data-row-id="{{ $rowId }}">Remove</a>
                                        <a href="#" class="action-link">Save for Later</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="cart-summary">
                <div class="summary-header">
                    <div class="summary-left">
                        <label class="select-all">
                            <input type="checkbox" checked>
                            Select All Items ({{ count($cart) }})
                        </label>
                        <div class="bulk-actions">
                            <button class="bulk-btn">Delete</button>
                            <button class="bulk-btn">Move to My Likes</button>
                        </div>
                    </div>
                    <div class="summary-right">
                        <div class="total-info">
                            <div class="total-label">Total ({{ count($cart) }} items)</div>
                            <div class="total-amount">₱{{ number_format($subtotal ?? 0, 0) }}</div>
                        </div>
                        <a href="{{ route('checkout') }}" class="checkout-btn">Check Out</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- You May Also Like Section -->
        <div class="recommendations-section">
            <div class="recommendations-header">
                <h2>You May Also Like</h2>
                <a href="{{ route('products') }}" class="see-all-link">See All ></a>
            </div>
            
            <div class="recommendations-carousel">
                <div class="product-card">
                    <div class="product-badges">
                        <span class="badge mall">Mall</span>
                        <span class="badge deal">Sulit Deal</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=200&h=200&fit=crop" alt="Gaming Mouse" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Fantech VX9 Kanata Wired Gaming Mouse</h3>
                        <div class="product-rating">
                            <span class="rating">4.9</span>
                            <span class="sold">2K+ sold</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">₱289</span>
                        </div>
                        <div class="product-badges-bottom">
                            <span class="badge spaylater">SPayLater</span>
                            <span class="badge interest">0% INTEREST</span>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-badges">
                        <span class="badge preferred">Preferred</span>
                        <span class="badge deal">Sulit Deal</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1484704849700-f032a568e944?w=200&h=200&fit=crop" alt="Gaming Headset" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Leyoya G9 Gaming Earphones with Mic</h3>
                        <div class="product-rating">
                            <span class="rating">4.6</span>
                            <span class="sold">10K+ sold</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">₱138</span>
                        </div>
                        <div class="product-badges-bottom">
                            <span class="badge shipping">UNLI FREE SHIPPING</span>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-badges">
                        <span class="badge official">Official Store</span>
                        <span class="badge deal">Sulit Deal</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=200&h=200&fit=crop" alt="Gaming Mouse" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Attack Shark X11 SE Gaming Mouse</h3>
                        <div class="product-rating">
                            <span class="rating">4.9</span>
                            <span class="sold">10K+ sold</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">₱1,099</span>
                        </div>
                        <div class="product-badges-bottom">
                            <span class="badge mega">MEGA DISCOUNT</span>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-badges">
                        <span class="badge fulfilled">Fulfilled by Shopee</span>
                        <span class="badge deal">Sulit Deal</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1484704849700-f032a568e944?w=200&h=200&fit=crop" alt="Gaming Headset" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Onikuma X15 PRO Gaming Headset</h3>
                        <div class="product-rating">
                            <span class="sold">6K+ sold</span>
                        </div>
                        <div class="product-price">
                            <span class="discount">P20 off</span>
                            <span class="current-price">₱669</span>
                        </div>
                        <div class="product-badges-bottom">
                            <span class="badge spaylater">SPayLater</span>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-badges">
                        <span class="badge mall">Mall</span>
                        <span class="badge deal">Sulit Deal</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=200&h=200&fit=crop" alt="Gaming Mouse" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Fantech VX9S Kanata S Gaming Mouse</h3>
                        <div class="product-rating">
                            <span class="sold">919 sold</span>
                        </div>
                        <div class="product-price">
                            <span class="discount">P50 off</span>
                            <span class="current-price">₱556</span>
                        </div>
                        <div class="product-badges-bottom">
                            <span class="badge interest">0% INTEREST</span>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-badges">
                        <span class="badge deal">Sulit Deal</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=200&h=200&fit=crop" alt="Gaming PC" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">RYZEN 5 5600GT PC PACKAGE</h3>
                        <div class="product-rating">
                            <span class="rating">5.0</span>
                            <span class="sold">16 sold</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">₱18,895</span>
                        </div>
                        <div class="product-badges-bottom">
                            <span class="badge shipping">FREE SHIPPING</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartSummary = document.querySelector('.cart-summary');
    const footer = document.querySelector('footer');
    
    console.log('Cart summary found:', cartSummary);
    console.log('Footer found:', footer);
    
    // Quantity controls
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const rowId = this.getAttribute('data-row-id');
            const quantityDisplay = this.parentElement.querySelector('.quantity-display');
            const currentQuantity = parseInt(quantityDisplay.textContent);
            
            if (action === 'increase') {
                // Update quantity via AJAX
                updateQuantity(rowId, currentQuantity + 1);
            } else if (action === 'decrease' && currentQuantity > 1) {
                // Update quantity via AJAX
                updateQuantity(rowId, currentQuantity - 1);
            }
        });
    });

    // Remove item functionality
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const rowId = this.getAttribute('data-row-id');
            
            // Remove item directly without confirmation
            removeItem(rowId);
        });
    });

    // Checkbox controls
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSellerCheckbox(this);
            updateSelectAll();
        });
    });

    document.querySelectorAll('.seller-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const sellerGroup = this.closest('.seller-group');
            const itemCheckboxes = sellerGroup.querySelectorAll('.item-checkbox');
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSelectAll();
        });
    });

    const selectAllCheckbox = document.querySelector('.select-all input');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.item-checkbox, .seller-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    }

    // Fixed positioning behavior - switch to relative when past last item
    function handleSticky() {
        if (!cartSummary) {
            console.log('Cart summary not found');
            return;
        }
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        
        // Find the last cart item
        const lastCartItem = document.querySelector('.cart-item:last-child');
        if (!lastCartItem) {
            console.log('No cart items found');
            return;
        }
        
        // Get the position of the last cart item
        const lastItemRect = lastCartItem.getBoundingClientRect();
        const lastItemBottom = lastItemRect.bottom + scrollTop;
        
        // Switch to content mode when we're past the last item
        const buffer = 100; // Buffer to make transition smooth
        const shouldStopSticky = lastItemBottom < scrollTop + windowHeight - buffer;
        
        console.log('Scroll:', scrollTop, 'Last item bottom:', lastItemBottom, 'Window bottom:', scrollTop + windowHeight, 'Should stop sticky:', shouldStopSticky);
        
        if (shouldStopSticky) {
            cartSummary.classList.add('stuck-to-content');
            console.log('Summary stuck to content');
        } else {
            cartSummary.classList.remove('stuck-to-content');
            console.log('Summary fixed at bottom');
        }
    }

    // Throttle scroll events for better performance
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        scrollTimeout = setTimeout(handleSticky, 50); // Less frequent updates
    });

    // Debug: Log elements
    console.log('Cart summary element:', cartSummary);
    console.log('Last cart item element:', document.querySelector('.cart-item:last-child'));
    
    // Initial check
    handleSticky();

    // Checkout button functionality
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            // Check if there are any selected items
            const selectedItems = document.querySelectorAll('.item-checkbox:checked');
            if (selectedItems.length === 0) {
                e.preventDefault();
                alert('Please select at least one item to checkout.');
                return false;
            }
            
            // If items are selected, allow navigation to checkout
            console.log('Proceeding to checkout with', selectedItems.length, 'selected items');
        });
    }

    function updateSellerCheckbox(itemCheckbox) {
        const sellerGroup = itemCheckbox.closest('.seller-group');
        const sellerCheckbox = sellerGroup.querySelector('.seller-checkbox');
        const itemCheckboxes = sellerGroup.querySelectorAll('.item-checkbox');
        const checkedItems = sellerGroup.querySelectorAll('.item-checkbox:checked');
        
        sellerCheckbox.checked = checkedItems.length === itemCheckboxes.length;
    }

    function updateSelectAll() {
        const allItemCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedItems.length === allItemCheckboxes.length;
        }
    }

    function updateTotal() {
        // This would calculate the total based on selected items
        // For now, we'll keep the static total
    }

    // AJAX function to update quantity
    function updateQuantity(rowId, newQuantity) {
        console.log('Updating quantity for rowId:', rowId, 'to:', newQuantity);
        
        fetch(`/cart/update/${rowId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        })
        .then(response => {
            console.log('Update response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Update response data:', data);
            if (data.success) {
                // Reload the page to show updated cart
                location.reload();
            } else {
                alert('Error updating quantity: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error updating quantity:', error);
            alert('Error updating quantity: ' + error.message + '. Please try again.');
        });
    }

    // AJAX function to remove item
    function removeItem(rowId) {
        console.log('Removing item with rowId:', rowId);
        
        fetch(`/cart/remove/${rowId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Reload the page to show updated cart
                location.reload();
            } else {
                alert('Error removing item: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error removing item:', error);
            alert('Error removing item: ' + error.message + '. Please try again.');
        });
    }
});
</script>
@endsection