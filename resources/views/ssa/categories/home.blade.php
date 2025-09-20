@extends('ssa.categories.layout')

@section('title', 'Home & Living - iMarket PH')

@section('content')
<section class="hero">
    <div class="hero-slides">
        <img class="slide active" src="https://i.pinimg.com/736x/a2/4a/b5/a24ab53b4bf89b079978fad2e813e6d8.jpg" alt="Slide 1">
        <img class="slide" src="https://i.pinimg.com/1200x/95/62/40/9562401b17aa8803c45564fa87a7523a.jpg" alt="Slide 2">
        <img class="slide" src="https://i.pinimg.com/1200x/3f/29/e2/3f29e2a0f203313db21fa45734f73b29.jpg" alt="Slide 3">
        <img class="slide" src="https://i.pinimg.com/736x/fd/a6/8c/fda68c5189f7cd37f044e201a77acc1e.jpg" alt="Slide 4">
        <img class="slide" src="https://i.pinimg.com/736x/5c/b4/98/5cb49830b398136f9c2aa341f847958d.jpg" alt="Slide 5">
    </div>
    <div class="hero-content">
        <h1>HOME & LIVING</h1>
    </div>
    <div class="hero-dots">
        <span class="dot active" data-index="0"></span>
        <span class="dot" data-index="1"></span>
        <span class="dot" data-index="2"></span>
        <span class="dot" data-index="3"></span>
        <span class="dot" data-index="4"></span>
    </div>
</section>

<section class="feature">
    <div class="feature-content">
        <div class="product-card" 
            data-name="Modern Ceramic Vase" 
            data-sizes="small,medium" 
            data-colors="white,gray" 
            data-price="850" 
            data-orig-price="1060" 
            data-img-white="https://m.media-amazon.com/images/I/718Btp+-iCL._AC_.jpg" 
            data-img-gray="https://infinitebasics.com/cdn/shop/files/main_71e69d6a-e0b0-4af1-b272-c75b8353b0b2.jpg?v=1730917133">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/718Btp+-iCL._AC_.jpg" alt="Modern Ceramic Vase">
                <span class="discount">-20%</span>
            </div>
            <div class="product-info">
                <h6>Modern Ceramic Vase</h6>
                <h3>₱850 <del>₱1,060</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,500 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Soft Chenille Throw Blanket" 
            data-sizes="N/A" 
            data-colors="navy,ivory" 
            data-price="1200" 
            data-orig-price="1500"
            data-img-navy="https://resource.astrogaming.com/d_transparent.gif/content/dam/astro/en/products/a30/pdp-gallery-a30-white-01.png"
            data-img-ivory="https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://resource.astrogaming.com/d_transparent.gif/content/dam/astro/en/products/a30/pdp-gallery-a30-white-01.png" alt="Soft Chenille Throw Blanket">
                <span class="discount">-20%</span>
            </div>
            <div class="product-info">
                <h6>Soft Chenille Throw Blanket</h6>
                <h3>₱1,200 <del>₱1,500</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 800 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Scented Soy Candle" 
            data-sizes="100g,250g" 
            data-colors="N/A" 
            data-price="500" 
            data-orig-price="850"
            data-img-default="https://images-na.ssl-images-amazon.com/images/I/61wk1z9PrZL.jpg">
            <div class="product-img">
                <img src="https://images-na.ssl-images-amazon.com/images/I/61wk1z9PrZL.jpg" alt="Scented Soy Candle">
                <span class="discount">-41%</span>
            </div>
            <div class="product-info">
                <h6>Scented Soy Candle</h6>
                <h3>₱500 <del>₱850</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 650 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Decorative Wall Mirror" 
            data-sizes="N/A" 
            data-colors="gold,silver" 
            data-price="2800" 
            data-orig-price="3500"
            data-img-gold="https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg"
            data-img-silver="https://i.pinimg.com/736x/87/1b/30/871b30f5d475c4d3d2a02b66236b9e15.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg" alt="Decorative Wall Mirror">
                <span class="discount">-20%</span>
            </div>
            <div class="product-info">
                <h6>Decorative Wall Mirror</h6>
                <h3>₱2,800 <del>₱3,500</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 320 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Bamboo Kitchen Utensil Set" 
            data-sizes="N/A" 
            data-colors="N/A" 
            data-price="450" 
            data-orig-price="600"
            data-img-default="https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg" alt="Bamboo Kitchen Utensil Set">
                <span class="discount">-25%</span>
            </div>
            <div class="product-info">
                <h6>Bamboo Kitchen Utensil Set</h6>
                <h3>₱450 <del>₱600</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 890 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Indoor Plant Pot Set" 
            data-sizes="small,medium,large" 
            data-colors="terracotta,white" 
            data-price="750" 
            data-orig-price="950"
            data-img-terracotta="https://i5.walmartimages.com/asr/b30bd280-e303-4d72-802e-1098752e3741_1.f164d3532b3ba852a89e92972f4d4754.jpeg"
            data-img-white="https://m.media-amazon.com/images/I/61b7U+0dD8L._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://i5.walmartimages.com/asr/b30bd280-e303-4d72-802e-1098752e3741_1.f164d3532b3ba852a89e92972f4d4754.jpeg" alt="Indoor Plant Pot Set">
                <span class="discount">-21%</span>
            </div>
            <div class="product-info">
                <h6>Indoor Plant Pot Set</h6>
                <h3>₱750 <del>₱950</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,200 sold</p>
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
