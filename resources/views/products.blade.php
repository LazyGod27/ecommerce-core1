@extends('layouts.frontend')

@section('title', 'Shop - iMarket')

@section('styles')
<style>
    .main-container {
        padding-top: 100px;
        min-height: 100vh;
        background: var(--bg-color);
    }
    
    /* Ensure all buttons are visible */
    button, .btn, .option {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-block !important;
    }
    
    /* Override any conflicting styles */
    .add-to-cart-btn, .checkout-btn, .option {
        background-color: initial !important;
        color: initial !important;
    }
    
    .hero {
        position: relative;
        height: 60vh;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero-slides {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }
    
    .slide.active {
        opacity: 1;
    }
    
    .hero-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        text-align: center;
        color: white;
        width: 100%;
        pointer-events: none;
    }
    
    .hero-content h1 {
        font-size: 4rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin: 0;
        padding: 0;
    }
    
    .hero-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 2;
    }
    
    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: background 0.3s ease;
    }
    
    .dot.active {
        background: white;
    }
    
    .feature {
        padding: 60px 20px;
        background: white;
    }
    
    .feature-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .product-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        animation: rumble 0.3s ease-in-out;
    }
    
    @keyframes rumble {
        0% { transform: translateY(-10px) rotate(0deg); }
        25% { transform: translateY(-10px) rotate(1deg); }
        50% { transform: translateY(-10px) rotate(0deg); }
        75% { transform: translateY(-10px) rotate(-1deg); }
        100% { transform: translateY(-10px) rotate(0deg); }
    }
    
    .product-card {
        animation: subtleFloat 3s ease-in-out infinite;
    }
    
    @keyframes subtleFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-2px); }
    }
    
    .product-card:nth-child(odd) {
        animation-delay: 0.5s;
    }
    
    .product-card:nth-child(even) {
        animation-delay: 1s;
    }
    
    .product-card:nth-child(3n) {
        animation-delay: 1.5s;
    }
    
    .product-card:hover .product-img img {
        transform: scale(1.05) rotate(2deg);
        transition: transform 0.3s ease;
    }
    
    .product-card:active {
        animation: clickRumble 0.2s ease-in-out;
    }
    
    @keyframes clickRumble {
        0% { transform: scale(1); }
        50% { transform: scale(0.95) rotate(1deg); }
        100% { transform: scale(1); }
    }
    
    
    .product-img {
        position: relative;
        height: 250px;
        overflow: hidden;
    }
    
    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-img img {
        transform: scale(1.05);
    }
    
    .discount {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #e74c3c;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-info h6 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
        line-height: 1.4;
    }
    
    .product-info h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--main-color);
        margin-bottom: 8px;
    }
    
    .product-info h3 del {
        color: #999;
        font-weight: 400;
        margin-left: 8px;
    }
    
    .buyers {
        color: #666;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .buyers i {
        color: #10b981;
    }
    
    .product-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: white;
        border-radius: 15px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        display: flex;
        gap: 20px;
    }
    
    .close-modal {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 2rem;
        cursor: pointer;
        color: #999;
        z-index: 1;
    }
    
    .close-modal:hover {
        color: #333;
    }
    
    .modal-img {
        width: 50%;
        height: 300px;
        object-fit: cover;
        border-radius: 15px 0 0 15px;
    }
    
    .modal-details {
        padding: 30px;
        flex: 1;
    }
    
    .modal-details h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 15px;
    }
    
    .modal-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--main-color);
        margin-bottom: 20px;
    }
    
    .modal-price del {
        color: #999;
        font-weight: 400;
        margin-left: 10px;
    }
    
    .modal-option-group {
        margin-bottom: 20px;
    }
    
    .modal-option-group h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }
    
    .option-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .option {
        padding: 8px 16px;
        border: 2px solid #ddd;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 500;
        background: white;
        color: #333;
        opacity: 1;
        visibility: visible;
        display: inline-block;
    }
    
    .option:hover {
        border-color: #2c3c8c;
        color: #2c3c8c;
        background: #f0f9ff;
    }
    
    .option.selected {
        background: #2c3c8c !important;
        color: white !important;
        border-color: #2c3c8c !important;
    }
    
    .modal-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    .add-to-cart-btn, .checkout-btn {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid transparent;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        opacity: 1;
        visibility: visible;
        min-height: 44px;
    }
    
    .add-to-cart-btn {
        background: #3498db !important;
        color: white !important;
        border-color: #2980b9;
    }
    
    .add-to-cart-btn:hover {
        background: #2980b9 !important;
        transform: translateY(-2px);
        border-color: #1f5f8b;
    }
    
    .checkout-btn {
        background: #2c3c8c !important;
        color: white !important;
        border-color: #1e40af;
    }
    
    .checkout-btn:hover {
        background: #1e40af !important;
        transform: translateY(-2px);
        border-color: #1e3a8a;
    }
    
    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }
        
        .hero {
            height: 50vh;
        }
        
        .modal-content {
            flex-direction: column;
            width: 95%;
        }
        
        .modal-img {
            width: 100%;
            height: 200px;
            border-radius: 15px 15px 0 0;
        }
        
        .modal-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="main-container">
    <section class="feature">
        <!-- Hero Section at top of products -->
        <div class="hero" style="margin-bottom: 40px;">
        <div class="hero-slides">
                @forelse($heroProducts as $index => $product)
                    <img class="slide {{ $index === 0 ? 'active' : '' }}" 
                         src="{{ $product->image }}" 
                         alt="{{ $product->name }}"
                         onerror="this.src='{{ asset('ssa/logo.png') }}'">
                @empty
                    <!-- Fallback images if no products -->
            <img class="slide active" src="https://i.pinimg.com/736x/a2/4a/b5/a24ab53b4bf89b079978fad2e813e6d8.jpg" alt="Slide 1">
            <img class="slide" src="https://i.pinimg.com/1200x/95/62/40/9562401b17aa8803c45564fa87a7523a.jpg" alt="Slide 2">
            <img class="slide" src="https://i.pinimg.com/1200x/3f/29/e2/3f29e2a0f203313db21fa45734f73b29.jpg" alt="Slide 3">
            <img class="slide" src="https://i.pinimg.com/736x/fd/a6/8c/fda68c5189f7cd37f044e201a77acc1e.jpg" alt="Slide 4">
            <img class="slide" src="https://i.pinimg.com/736x/5c/b4/98/5cb49830b398136f9c2aa341f847958d.jpg" alt="Slide 5">
                @endforelse
        </div>
        <div class="hero-content">
                <h1>SHOP ALL PRODUCTS</h1>
        </div>
        <div class="hero-dots">
                @forelse($heroProducts as $index => $product)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                @empty
                    <!-- Fallback dots -->
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
            <span class="dot" data-index="3"></span>
            <span class="dot" data-index="4"></span>
                @endforelse
                </div>
            </div>
        <div class="feature-content" id="productsContainer">
            @forelse($products as $product)
                @php
                    $discount = $product->original_price ? round((($product->original_price - $product->price) / $product->original_price) * 100) : 0;
                    $soldCount = $product->review_count ? $product->review_count * 10 : rand(50, 2000);
                @endphp
            <div class="product-card" 
                    data-name="{{ $product->name }}" 
                    data-id="{{ $product->id }}"
                    data-sizes="Standard, Large" 
                    data-colors="Multi-Color" 
                    data-price="{{ $product->price }}" 
                    data-orig-price="{{ $product->original_price ?? $product->price }}"
                    data-img-red="{{ $product->image }}"
                    data-category="{{ $product->category }}">
                <div class="product-img">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('ssa/logo.png') }}'">
                        @if($discount > 0)
                            <span class="discount">-{{ $discount }}%</span>
                        @endif
                </div>
                <div class="product-info">
                        <h6>{{ $product->name }}</h6>
                        <h3>₱{{ number_format($product->price, 2) }} 
                            @if($product->original_price && $product->original_price > $product->price)
                                <del>₱{{ number_format($product->original_price, 2) }}</del>
                            @endif
                        </h3>
                        <p class="buyers">
                            <i class="fas fa-user-check"></i> {{ $soldCount }} sold
                            @if($product->average_rating)
                                | <i class="fas fa-star" style="color: #ffc107;"></i> {{ $product->average_rating }}
                            @endif
                        </p>
                        @php
                            $categoryColors = [
                                'electronics' => '#e74c3c',
                                'fashion' => '#9b59b6', 
                                'beauty' => '#e91e63',
                                'sports' => '#27ae60',
                                'toys' => '#f39c12',
                                'home' => '#3498db',
                                'groceries' => '#2ecc71',
                                'new' => '#e67e22'
                            ];
                            $categoryIcons = [
                                'electronics' => '⚡',
                                'fashion' => '👕',
                                'beauty' => '💄',
                                'sports' => '⚽',
                                'toys' => '🎮',
                                'home' => '🏠',
                                'groceries' => '🛒',
                                'new' => '✨'
                            ];
                            $color = $categoryColors[$product->category] ?? '#95a5a6';
                            $icon = $categoryIcons[$product->category] ?? '🏷️';
                        @endphp
                        <p class="category-badge" style="font-size: 0.8rem; color: {{ $color }}; margin-top: 5px; font-weight: 600; background: {{ $color }}20; padding: 4px 8px; border-radius: 12px; display: inline-block;">
                            {{ $icon }} {{ ucfirst($product->category) }}
                        </p>
                        
                        <!-- Direct Add to Cart Button -->
                        <div style="margin-top: 15px;">
                            <form action="{{ route('cart.add', $product) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 0.9rem; font-weight: 600; transition: background 0.3s ease; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                </div>
            </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                    <h3 style="color: #666; margin-bottom: 20px;">No products available</h3>
                    <p style="color: #999;">Please run the database seeder to populate products.</p>
                    <a href="{{ route('home') }}" style="display: inline-block; margin-top: 20px; padding: 12px 24px; background: #2c3c8c; color: white; text-decoration: none; border-radius: 8px;">Go Home</a>
                </div>
            @endforelse
        </div>
    </section>
</div>


<!-- Product Modal -->
<div class="product-modal" id="productModal">
    <div class="modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <img src="" alt="" class="modal-img" id="modalImg">
        <div class="modal-details">
            <h2 id="modalName">Product Name</h2>
            <p class="modal-price"><span id="modalPrice">₱1,234</span> <del id="modalOrigPrice">₱5,678</del></p>
            
            <div class="modal-option-group" id="sizesGroup">
                <h4>Sizes:</h4>
                <div class="option-list" id="modalSizes"></div>
            </div>
            
            <div class="modal-option-group" id="colorsGroup">
                <h4>Colors:</h4>
                <div class="option-list" id="modalColors"></div>
    </div>
    
            <div class="modal-actions">
                <button class="add-to-cart-btn" id="addToCartBtn"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                <button class="checkout-btn" id="checkoutBtn"><i class="fas fa-credit-card"></i> Checkout</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Hero slider functionality
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        // Auto-advance slides every 4 seconds
        setInterval(nextSlide, 4000);

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        // Product modal functionality
        const productCards = document.querySelectorAll('.product-card');
        const productModal = document.getElementById('productModal');
        const closeModalBtn = document.getElementById('closeModal');
        const modalImg = document.getElementById('modalImg');
        const modalName = document.getElementById('modalName');
        const modalPrice = document.getElementById('modalPrice');
        const modalOrigPrice = document.getElementById('modalOrigPrice');
        const modalSizes = document.getElementById('modalSizes');
        const modalColors = document.getElementById('modalColors');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const checkoutBtn = document.getElementById('checkoutBtn');

        let selectedSize = '';
        let selectedColor = '';
        let currentProductData = {};

        const openModal = (productData) => {
            currentProductData = productData;
            
            modalImg.src = productData.imgSrc;
            modalImg.alt = productData.name;
            
            modalName.textContent = productData.name;
            modalPrice.textContent = `₱${productData.price.toLocaleString()}`;
            modalOrigPrice.textContent = `₱${productData.origPrice.toLocaleString()}`;
            
            modalSizes.innerHTML = '';
            modalColors.innerHTML = '';

            // Handle sizes
            if (productData.sizes && productData.sizes !== 'N/A') {
                const sizesArray = productData.sizes.split(',');
                sizesArray.forEach((size, index) => {
                    const sizeOption = document.createElement('span');
                    sizeOption.classList.add('option');
                    sizeOption.textContent = size.trim();
                    modalSizes.appendChild(sizeOption);
                    
                    // Auto-select first size as default
                    if (index === 0) {
                        sizeOption.classList.add('selected');
                        selectedSize = size.trim();
                    }
                });
                document.getElementById('sizesGroup').style.display = 'block';
            } else {
                document.getElementById('sizesGroup').style.display = 'none';
            }

            // Handle colors
            if (productData.colors && productData.colors !== 'N/A') {
                const colorsArray = productData.colors.split(',');
                colorsArray.forEach((color, index) => {
                    const colorOption = document.createElement('span');
                    colorOption.classList.add('option');
                    colorOption.textContent = color.trim();
                    colorOption.setAttribute('data-color', color.trim().toLowerCase().replace(/ /g, ''));
                    modalColors.appendChild(colorOption);
                    
                    // Auto-select first color as default
                    if (index === 0) {
                        colorOption.classList.add('selected');
                        selectedColor = color.trim();
                        // Update image based on selected color
                        colorChangeHandler(colorOption.getAttribute('data-color'));
                    }
                });
                document.getElementById('colorsGroup').style.display = 'block';
            } else {
                document.getElementById('colorsGroup').style.display = 'none';
            }

            productModal.style.display = 'flex';
        };

        const closeModal = () => {
            productModal.style.display = 'none';
        };

        productCards.forEach(card => {
            card.addEventListener('click', (e) => {
                // Don't open modal if clicking on the Add to Cart button
                if (e.target.closest('form') || e.target.closest('button')) {
                    return;
                }
                
                const productData = {
                    id: card.getAttribute('data-id'),
                    name: card.getAttribute('data-name'),
                    sizes: card.getAttribute('data-sizes'),
                    colors: card.getAttribute('data-colors'),
                    price: parseFloat(card.getAttribute('data-price')),
                    origPrice: parseFloat(card.getAttribute('data-orig-price')),
                    imgSrc: card.querySelector('.product-img img').src,
                    colorImages: {}
                };

                const dataAttributes = card.attributes;
                for (let i = 0; i < dataAttributes.length; i++) {
                    const attr = dataAttributes[i];
                    if (attr.name.startsWith('data-img-')) {
                        const colorName = attr.name.replace('data-img-', '');
                        productData.colorImages[colorName] = attr.value;
                    }
                }
                
                openModal(productData);
            });
        });

        closeModalBtn.addEventListener('click', closeModal);

        window.addEventListener('click', (event) => {
            if (event.target === productModal) {
                closeModal();
            }
        });

        const handleOptionSelection = (containerId, optionType) => {
            const container = document.getElementById(containerId);
            container.addEventListener('click', (e) => {
                if (e.target.classList.contains('option')) {
                    container.querySelectorAll('.option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    e.target.classList.add('selected');

                    if (optionType === 'size') {
                        selectedSize = e.target.textContent;
                    } else if (optionType === 'color') {
                        selectedColor = e.target.textContent;
                        colorChangeHandler(e.target.getAttribute('data-color'));
                    }
                }
            });
        };

        handleOptionSelection('modalSizes', 'size');
        handleOptionSelection('modalColors', 'color');
        
        const colorChangeHandler = (color) => {
            const newImageSrc = currentProductData.colorImages[color];
            if (newImageSrc) {
                modalImg.src = newImageSrc;
            } else {
                console.warn(`Image not found for color: ${color}`);
            }
        };
        
        addToCartBtn.addEventListener('click', () => {
            const productName = modalName.textContent;
            const productPrice = parseFloat(modalPrice.textContent.replace('₱', '').replace(/,/g, ''));
            
            // Create a form to submit to cart
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cart/add/${currentProductData.id || 'demo'}`;
            
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
            quantityInput.value = '1';
            form.appendChild(quantityInput);
            
            // Add product details for demo products
            if (!currentProductData.id || currentProductData.id === 'demo') {
                const productNameInput = document.createElement('input');
                productNameInput.type = 'hidden';
                productNameInput.name = 'product_name';
                productNameInput.value = productName;
                form.appendChild(productNameInput);
                
                const productPriceInput = document.createElement('input');
                productPriceInput.type = 'hidden';
                productPriceInput.name = 'product_price';
                productPriceInput.value = productPrice;
                form.appendChild(productPriceInput);
                
                const productImageInput = document.createElement('input');
                productImageInput.type = 'hidden';
                productImageInput.name = 'product_image';
                productImageInput.value = modalImg.src;
                form.appendChild(productImageInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        });

        checkoutBtn.addEventListener('click', () => {
            const productName = modalName.textContent;
            const productPrice = parseFloat(modalPrice.textContent.replace('₱', '').replace(/,/g, ''));
            
            // First add to cart, then redirect to checkout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cart/add/${currentProductData.id || 'demo'}`;
            
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
            quantityInput.value = '1';
            form.appendChild(quantityInput);
            
            // Add product details for demo products
            if (!currentProductData.id || currentProductData.id === 'demo') {
                const productNameInput = document.createElement('input');
                productNameInput.type = 'hidden';
                productNameInput.name = 'product_name';
                productNameInput.value = productName;
                form.appendChild(productNameInput);
                
                const productPriceInput = document.createElement('input');
                productPriceInput.type = 'hidden';
                productPriceInput.name = 'product_price';
                productPriceInput.value = productPrice;
                form.appendChild(productPriceInput);
                
                const productImageInput = document.createElement('input');
                productImageInput.type = 'hidden';
                productImageInput.name = 'product_image';
                productImageInput.value = modalImg.src;
                form.appendChild(productImageInput);
            }
            
            // Add redirect parameter
            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect_to_checkout';
            redirectInput.value = '1';
            form.appendChild(redirectInput);
            
            document.body.appendChild(form);
            form.submit();
        });

        // Enhanced rumble effect on product card click
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('click', () => {
                // Add extra rumble effect
                card.style.animation = 'clickRumble 0.3s ease-in-out';
                setTimeout(() => {
                    card.style.animation = 'subtleFloat 3s ease-in-out infinite';
                }, 300);
            });
        });
    });
</script>
@endsection