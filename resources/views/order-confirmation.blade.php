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
        position: relative;
        overflow: hidden;
    }
    
    .confirmation-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6);
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
        text-align: center;
        margin-bottom: 30px;
        color: var(--text-color);
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 8px;
    }
    
    .product-price {
        color: var(--main-color);
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 15px;
    }
    
    .stars {
        color: #fbbf24;
    }
    
    .rating-text {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .add-to-cart-btn {
        width: 100%;
        padding: 12px;
        background: var(--main-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .add-to-cart-btn:hover {
        background: #1e40af;
        transform: translateY(-2px);
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
            @if($order->tracking->estimated_delivery)
            <p><strong>Estimated Delivery:</strong> {{ $order->tracking->estimated_delivery->format('M d, Y') }}</p>
            @endif
        </div>
        @endif
        
        <div class="order-items">
            <h3>Order Items</h3>
            @foreach($order->items as $item)
            <div class="order-item">
                <img src="{{ str_starts_with($item->product->image ?? 'ssa/default.jpg', 'http') ? $item->product->image : asset($item->product->image ?? 'ssa/default.jpg') }}" 
                     alt="{{ $item->product->name ?? 'Product' }}">
                <div class="item-info">
                    <div class="item-name">{{ $item->product->name ?? 'Product' }}</div>
                    <div class="item-quantity">Quantity: {{ $item->quantity }}</div>
                </div>
                <div class="item-price">₱{{ number_format($item->quantity * ($item->price ?? $item->product->price ?? 0), 2) }}</div>
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
        <h3><i class="ri-heart-line"></i> You Might Also Like</h3>
        <div class="products-grid" id="similar-products-grid">
            <!-- Similar products will be loaded dynamically -->
            <div class="product-card" onclick="addToCart('Wireless Earbuds Pro', 149.99, '{{ asset('ssa/earbuds.jpg') }}')">
                <img src="{{ asset('ssa/earbuds.jpg') }}" alt="Wireless Earbuds Pro">
                <div class="product-info">
                    <div class="product-name">Wireless Earbuds Pro</div>
                    <div class="product-price">₱149.99</div>
                    <div class="product-rating">
                        <div class="stars">★★★★★</div>
                        <span class="rating-text">(42 reviews)</span>
                    </div>
                    <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart('Wireless Earbuds Pro', 149.99, '{{ asset('ssa/earbuds.jpg') }}')">
                        <i class="ri-shopping-cart-line"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <div class="product-card" onclick="addToCart('Smart Watch Series 8', 399.99, '{{ asset('ssa/watch.jpg') }}')">
                <img src="{{ asset('ssa/watch.jpg') }}" alt="Smart Watch Series 8">
                <div class="product-info">
                    <div class="product-name">Smart Watch Series 8</div>
                    <div class="product-price">₱399.99</div>
                    <div class="product-rating">
                        <div class="stars">★★★★★</div>
                        <span class="rating-text">(45 reviews)</span>
                    </div>
                    <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart('Smart Watch Series 8', 399.99, '{{ asset('ssa/watch.jpg') }}')">
                        <i class="ri-shopping-cart-line"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <div class="product-card" onclick="addToCart('Gaming Laptop RTX 4070', 1299.99, '{{ asset('ssa/rtx.jpg') }}')">
                <img src="{{ asset('ssa/rtx.jpg') }}" alt="Gaming Laptop RTX 4070">
                <div class="product-info">
                    <div class="product-name">Gaming Laptop RTX 4070</div>
                    <div class="product-price">₱1,299.99</div>
                    <div class="product-rating">
                        <div class="stars">★★★★★</div>
                        <span class="rating-text">(12 reviews)</span>
                    </div>
                    <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart('Gaming Laptop RTX 4070', 1299.99, '{{ asset('ssa/rtx.jpg') }}')">
                        <i class="ri-shopping-cart-line"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <div class="product-card" onclick="addToCart('Bluetooth Speaker', 49.99, '{{ asset('ssa/jbl.jpg') }}')">
                <img src="{{ asset('ssa/jbl.jpg') }}" alt="Bluetooth Speaker">
                <div class="product-info">
                    <div class="product-name">Bluetooth Speaker</div>
                    <div class="product-price">₱49.99</div>
                    <div class="product-rating">
                        <div class="stars">★★★★☆</div>
                        <span class="rating-text">(29 reviews)</span>
                    </div>
                    <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart('Bluetooth Speaker', 49.99, '{{ asset('ssa/jbl.jpg') }}')">
                        <i class="ri-shopping-cart-line"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any additional JavaScript functionality here
    document.addEventListener('DOMContentLoaded', function() {
        // Confetti animation for success
        if (typeof confetti !== 'undefined') {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        }
        
        // Load similar products dynamically
        loadSimilarProducts();
        
        // Add smooth animations to product cards
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        console.log('Order confirmation page loaded');
    });
    
    function addToCart(productName, price, image) {
        // Create a form to add item to cart
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/cart/add/${Date.now()}`; // Use timestamp as synthetic ID
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Add product details
        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'product_name';
        nameInput.value = productName;
        form.appendChild(nameInput);
        
        const priceInput = document.createElement('input');
        priceInput.type = 'hidden';
        priceInput.name = 'product_price';
        priceInput.value = price;
        form.appendChild(priceInput);
        
        const imageInput = document.createElement('input');
        imageInput.type = 'hidden';
        imageInput.name = 'product_image';
        imageInput.value = image;
        form.appendChild(imageInput);
        
        const quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity';
        quantityInput.value = '1';
        form.appendChild(quantityInput);
        
        // Add to DOM and submit
        document.body.appendChild(form);
        form.submit();
    }
    
    // Add hover effects to product cards
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    async function loadSimilarProducts() {
        try {
            const orderId = window.location.pathname.split('/').pop();
            const response = await fetch(`/api/similar-products/${orderId}`);
            const data = await response.json();
            
            if (data.products && data.products.length > 0) {
                const grid = document.getElementById('similar-products-grid');
                grid.innerHTML = '';
                
                data.products.forEach((product, index) => {
                    const productCard = createProductCard(product, index);
                    grid.appendChild(productCard);
                });
            }
        } catch (error) {
            console.error('Error loading similar products:', error);
        }
    }
    
    function createProductCard(product, index) {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        const stars = '★'.repeat(product.rating) + '☆'.repeat(5 - product.rating);
        
        card.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <div class="product-info">
                <div class="product-name">${product.name}</div>
                <div class="product-price">₱${product.price.toFixed(2)}</div>
                <div class="product-rating">
                    <div class="stars">${stars}</div>
                    <span class="rating-text">(${product.reviews_count} reviews)</span>
                </div>
                <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart('${product.name}', ${product.price}, '${product.image}')">
                    <i class="ri-shopping-cart-line"></i> Add to Cart
                </button>
            </div>
        `;
        
        // Add click handler
        card.onclick = () => addToCart(product.name, product.price, product.image);
        
        // Add hover effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        // Animate in
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
        
        return card;
    }
</script>
@endsection