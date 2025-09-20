@extends('ssa.categories.layout')

@section('title', 'Fashion & Apparel - iMarket PH')

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
        <h1>WEAR</h1>
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
            data-name="Vintage Low-Top Sneakers" 
            data-sizes="36,37,38,39,40,41,42,43,44,45" 
            data-colors="White,Black-Leather,Green,Dark-Gray" 
            data-price="8100" 
            data-orig-price="18000" 
            data-img-white="https://cdn.clothbase.com/uploads/bc3a8178-7fcc-4bb2-b9cc-b027e2ebd494/image.jpg"
            data-img-black-Leather="https://www.mrporter.com/variants/images/1647597294521561/cu/w2000_q60.jpg"
            data-img-green="https://www.mytheresa.com/media/1094/1238/100/4e/P01001476_d2.jpg"
            data-img-dark-gray="https://www.mrporter.com/variants/images/1647597294521641/cu/w2000_q60.jpg"
            onclick="viewProduct('Vintage Low-Top Sneakers', 8100, 'https://media-assets.grailed.com/prd/listing/47294752/da782adc9b964af09f259b6546d20c0a?h=700&fit=clip&auto=format', 'Classic vintage low-top sneakers with premium materials and timeless design.')">
            <div class="product-img">
                <img src="https://media-assets.grailed.com/prd/listing/47294752/da782adc9b964af09f259b6546d20c0a?h=700&fit=clip&auto=format" alt="Vintage Low-Top Sneakers">
                <span class="discount">-55%</span>
            </div>
            <div class="product-info">
                <h6>Vintage Low-Top Sneakers</h6>
                <h3>₱8,100 <del>₱18,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 5,200 sold</p>
                <div class="actions">
                    <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Vintage Low-Top Sneakers', 8100, 'https://media-assets.grailed.com/prd/listing/47294752/da782adc9b964af09f259b6546d20c0a?h=700&fit=clip&auto=format')">Buy Now</a>
                    <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Vintage Low-Top Sneakers', 8100, 'https://media-assets.grailed.com/prd/listing/47294752/da782adc9b964af09f259b6546d20c0a?h=700&fit=clip&auto=format')"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>

        <div class="product-card" 
            data-name="Oversized Streetwear Joggers" 
            data-sizes="S,M,L,XL,XXL,XXXL" 
            data-colors="Black,Gray,khaki" 
            data-price="300" 
            data-orig-price="350"
            data-img-black="https://media-assets.grailed.com/prd/listing/temp/e6abf8c9d40347b1a37d6640b76fcff5?w=1000"
            data-img-gray="https://media-assets.grailed.com/prd/listing/temp/d8d385ab19bf4f9b891e6326fa393321?"
            data-img-khaki="https://media-assets.grailed.com/prd/listing/temp/1015765063c74069a0694ed26bedce5c?w=1000"
            onclick="viewProduct('Oversized Streetwear Joggers', 300, 'https://media-assets.grailed.com/prd/listing/temp/d8d385ab19bf4f9b891e6326fa393321?', 'Comfortable oversized streetwear joggers perfect for casual wear.')">
            <div class="product-img">
                <img src="https://media-assets.grailed.com/prd/listing/temp/d8d385ab19bf4f9b891e6326fa393321?" alt="Oversized Streetwear Joggers">
                <span class="discount">-5%</span>
            </div>
            <div class="product-info">
                <h6>Baggy Sweat Pants</h6>
                <h3>₱300 <del>₱350</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 8,980 sold</p>
                <div class="actions">
                    <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Oversized Streetwear Joggers', 300, 'https://media-assets.grailed.com/prd/listing/temp/d8d385ab19bf4f9b891e6326fa393321?')">Buy Now</a>
                    <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Oversized Streetwear Joggers', 300, 'https://media-assets.grailed.com/prd/listing/temp/d8d385ab19bf4f9b891e6326fa393321?')"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>

        <div class="product-card" 
            data-name="Minimalist Everyday Backpack" 
            data-sizes="Default" 
            data-colors="Black,white,green,orange" 
            data-price="750" 
            data-orig-price="1500"
            data-img-black="https://www.uoozee.com/cdn/shop/files/6b1dd51f7df9c0a35230343ffc21cc3c.jpg?v=1748435092&width=960"
            data-img-white="https://www.uoozee.com/cdn/shop/files/56f34dd51dd524e6bb28f1e248c3b004.jpg?v=1748435092&width=960"
            data-img-green="https://www.uoozee.com/cdn/shop/files/18717bc5a61294a1f6ecd6d895065cfd.jpg?v=1748435092&width=960"
            data-img-orange="https://www.uoozee.com/cdn/shop/files/5eeee630eb846468568a690c1171233f.jpg?v=1748435092&width=960">
            <div class="product-img">
                <img src="https://www.uoozee.com/cdn/shop/files/751ae0bcc7281673f383d0d20578e0d1.jpg?v=1748435092&width=1000" alt="Casual Backpack">
                <span class="discount">-50%</span>
            </div>
            <div class="product-info">
                <h6>Korean Aesthethic Shoulder Bag</h6>
                <h3>₱750 <del>₱1,500</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,200 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Oversized 'Vibe Check' Hoodie" 
            data-sizes="S,M,L,XL" 
            data-colors="Black,White,Pink,Blue" 
            data-price="750" 
            data-orig-price="1500"
            data-img-black="https://i.pinimg.com/1200x/92/69/8d/92698d58e905b49c91b1c8f81c2b7dcc.jpg"
            data-img-white="https://i.pinimg.com/736x/96/46/d8/9646d859d461d17aa30e592bece4fe31.jpg"
            data-img-pink="https://i.pinimg.com/1200x/b9/1f/c8/b91fc82d03f19466295a0d7a4efb423f.jpg"
            data-img-blue="https://i.pinimg.com/736x/6d/e5/55/6de5555c1347e3eec9f8c966bc588835.jpg">
            <div class="product-img">
                <img src="https://i.pinimg.com/1200x/7c/5c/51/7c5c51e042c735453478a3328643c6f5.jpg" alt="Oversized Hoodie">
                <span class="discount">-50%</span>
            </div>
            <div class="product-info">
                <h6>F1 Racing Oversize Hoodie</h6>
                <h3>₱750 <del>₱1,500</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 3,200 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Light-Wash Unisex Denim Jacket" 
            data-sizes="S,M,L,XL" 
            data-colors="Light Blue,Dark Blue" 
            data-price="1399" 
            data-orig-price="2798"
            data-img-lightblue="https://i.pinimg.com/736x/00/6b/e5/006be503a29755633eab2e80622501b6.jpg"
            data-img-darkblue="https://i.pinimg.com/1200x/ef/7b/45/ef7b458dbce1a80ecc03c1205388d5b9.jpg">
            <div class="product-img">
                <img src="https://i.pinimg.com/736x/f7/e7/e7/f7e7e739c2b6012c731d1dd0e4b31d95.jpg" alt="Denim Jacket">
                <span class="discount">-50%</span>
            </div>
            <div class="product-info">
                <h6>Light-Wash Unisex Denim Jacket</h6>
                <h3>₱1,399 <del>₱2,798</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 2,430 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Ribbed Knit Cropped Top" 
            data-sizes="S,M,L,XL" 
            data-colors="Black" 
            data-price="350" 
            data-orig-price="450"
            data-img-black="https://i.pinimg.com/1200x/48/e9/32/48e932a07da8b48ac272a24b06051be7.jpg">
            <div class="product-img">
                <img src="https://i.pinimg.com/1200x/48/e9/32/48e932a07da8b48ac272a24b06051be7.jpg" alt="Cropped Top">
                <span class="discount">-10%</span>
            </div>
            <div class="product-info">
                <h6>Ribbed Knit Cropped Top</h6>
                <h3>₱350 <del>₱450</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 5,860 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Adjustable Strapback Cap" 
            data-sizes="OS" 
            data-colors="Black,Pink,Gray,Blue" 
            data-price="995" 
            data-orig-price="1195"
            data-img-black="https://i.pinimg.com/1200x/d6/33/3a/d6333a2fedc7e198dcbd9c607f5a3f31.jpg"
            data-img-pink="https://i.pinimg.com/1200x/33/fa/bf/33fabf61e711219bf23e75c3838f1cf0.jpg"
            data-img-Gray="https://i.pinimg.com/736x/c0/df/bd/c0dfbd1a31f36736781173181e4057b5.jpg"
            data-img-blue="https://i.pinimg.com/736x/a4/ab/a4/a4aba41f4bf2761849597347e8a28ef9.jpg">
            <div class="product-img">
                <img src="https://i.pinimg.com/1200x/d6/33/3a/d6333a2fedc7e198dcbd9c607f5a3f31.jpg" alt="Streetwear Cap">
                <span class="discount">-17%</span>
            </div>
            <div class="product-info">
                <h6>New York Yankees</h6>
                <h3>₱995 <del>₱1,195</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,800 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Unisex Crewneck Sweater" 
            data-sizes="S,M,L,XL" 
            data-colors="Dark-Gray,Gray,White,black,yellow" 
            data-price="1072" 
            data-orig-price="1599"
            data-img-dark-gray="https://i.pinimg.com/1200x/0f/04/f6/0f04f674c9ae4430fc826e069c679804.jpg"
            data-img-gray="https://i.pinimg.com/736x/34/f6/93/34f69334ae9678325b0f2e81120417e9.jpg"
            data-img-white="https://i.pinimg.com/1200x/c5/17/89/c51789d9599e3f25fa3c9f5e9e56e97d.jpg"
            data-img-Black="https://i.pinimg.com/736x/2c/df/c6/2cdfc6dc530ce0f7a65f49ec05d6b987.jpg"
            data-img-yellow="https://i.pinimg.com/736x/7b/52/3e/7b523ed6e065e78fdf998d07efb747da.jpg">
            <div class="product-img">
                <img src="https://i.pinimg.com/736x/7b/52/3e/7b523ed6e065e78fdf998d07efb747da.jpg" alt="Crewneck Sweater">
                <span class="discount">-35%</span>
            </div>
            <div class="product-info">
                <h6>Standard Sweatshirt</h6>
                <h3>₱1,072 <del>₱1,599</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 4,200 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Unisex Graphic T-Shirt" 
            data-sizes="S,M,L,XL,2XL,3XL" 
            data-colors="Black" 
            data-price="1499" 
            data-orig-price="3299"
            data-img-white="https://i.pinimg.com/1200x/d9/4f/7b/d94f7b2222d32a52842369633787ef2d.jpg">
            <div class="product-img">
                <img src="https://i.pinimg.com/1200x/d9/4f/7b/d94f7b2222d32a52842369633787ef2d.jpg" alt="Unisex Graphic T-Shirt">
                <span class="discount">-55%</span>
            </div>
            <div class="product-info">
                <h6>Men Cotton Plant & Slogan Tee</h6>
                <h3>₱1,499 <del>₱3,299</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 6,010 sold</p>
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
