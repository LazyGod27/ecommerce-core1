@extends('ssa.categories.layout')

@section('title', 'Toys & Games - iMarket PH')

@section('content')
<section class="hero">
    <div class="hero-content">
        <h1>Toys & Games</h1>
        <p>Discover fun toys and games for all ages.</p>
    </div>
</section>

<section class="product">
    <div class="middle-text">
        <h2>Toys & <span>Games</span></h2>
    </div>
    <div class="feature-content">
        <div class="product-card" onclick="viewProduct('Gaming Controller', 7999, '{{ asset('ssa/controller.jpg') }}', 'Professional gaming controller for PS5 with customizable buttons and triggers.')">
            <div class="product-img">
                <img src="{{ asset('ssa/controller.jpg') }}" alt="Gaming Controller">
                <span class="discount">NEW</span>
            </div>
            <div class="product-info">
                <h6>Sony</h6>
                <h3>DualSense Edge</h3>
                <p class="buyers"><i class="fas fa-bolt"></i> Latest Release</p>
                <div class="actions">
                    <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Gaming Controller', 7999, '{{ asset('ssa/controller.jpg') }}')">Buy Now</a>
                    <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Gaming Controller', 7999, '{{ asset('ssa/controller.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function addToCart(productName, price, image) {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const form = document.getElementById('add-to-cart-form');
        const syntheticId = Date.now();
        form.setAttribute('action', `${'{{ url('/') }}'}/cart/add/${syntheticId}`);
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrf}">
            <input type="hidden" name="product_name" value="${productName}">
            <input type="hidden" name="product_price" value="${price}">
            <input type="hidden" name="product_image" value="${image}">
            <input type="hidden" name="quantity" value="1">
        `;
        form.submit();
    }

    function buyNow(productName, price, image) {
        addToCart(productName, price, image);
        setTimeout(() => {
            window.location.href = '{{ route("checkout") }}';
        }, 500);
    }

    function viewProduct(productName, price, image, description) {
        // Create and show product detail modal
        const modal = document.createElement('div');
        modal.className = 'product-modal';
        modal.innerHTML = `
            <div class="product-modal-content">
                <div class="product-modal-header">
                    <h3>${productName}</h3>
                    <button class="product-modal-close" onclick="closeProductModal()">&times;</button>
                </div>
                <div class="product-modal-body">
                    <div class="product-modal-image">
                        <img src="${image}" alt="${productName}">
                    </div>
                    <div class="product-modal-info">
                        <h4>₱${price.toLocaleString()}</h4>
                        <p class="product-description">${description}</p>
                        <div class="product-modal-actions">
                            <button class="btn buy" onclick="buyNow('${productName}', ${price}, '${image}'); closeProductModal();">Buy Now</button>
                            <button class="btn cart" onclick="addToCart('${productName}', ${price}, '${image}'); closeProductModal();">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal styles
        const style = document.createElement('style');
        style.textContent = `
            .product-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .product-modal.show {
                opacity: 1;
            }
            .product-modal-content {
                background: white;
                border-radius: 12px;
                max-width: 600px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                transform: scale(0.8);
                transition: transform 0.3s ease;
                display: flex;
                flex-direction: column;
            }
            .product-modal.show .product-modal-content {
                transform: scale(1);
            }
            .product-modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px;
                border-bottom: 1px solid #eee;
            }
            .product-modal-header h3 {
                margin: 0;
                color: #333;
            }
            .product-modal-close {
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #999;
            }
            .product-modal-close:hover {
                color: #333;
            }
            .product-modal-body {
                display: flex;
                padding: 20px;
                gap: 20px;
                flex: 1;
                overflow-y: auto;
            }
            .product-modal-image {
                flex: 1;
            }
            .product-modal-image img {
                width: 100%;
                height: 200px;
                object-fit: cover;
                border-radius: 8px;
            }
            .product-modal-info {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .product-modal-info h4 {
                font-size: 24px;
                color: #e74c3c;
                margin: 0 0 15px 0;
            }
            .product-description {
                color: #666;
                line-height: 1.6;
                margin-bottom: 20px;
            }
            .product-modal-actions {
                display: flex;
                gap: 10px;
                margin-top: auto;
                padding-top: 20px;
                flex-shrink: 0;
            }
            .product-modal-actions .btn {
                padding: 12px 20px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s ease;
                flex: 1;
                text-align: center;
                white-space: nowrap;
                font-size: 14px;
            }
            .product-modal-actions .btn.buy {
                background: #e74c3c;
                color: white;
            }
            .product-modal-actions .btn.buy:hover {
                background: #c0392b;
            }
            .product-modal-actions .btn.cart {
                background: #3498db;
                color: white;
            }
            .product-modal-actions .btn.cart:hover {
                background: #2980b9;
            }
            @media (max-width: 768px) {
                .product-modal-body {
                    flex-direction: column;
                    padding: 15px;
                }
                .product-modal-actions {
                    flex-direction: column;
                    gap: 8px;
                }
                .product-modal-actions .btn {
                    padding: 14px 20px;
                    font-size: 16px;
                }
                .product-modal-content {
                    max-height: 95vh;
                    width: 95%;
                }
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(modal);
        
        // Show modal with animation
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeProductModal();
            }
        });
    }

    function closeProductModal() {
        const modal = document.querySelector('.product-modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    }
</script>
@endsection
