@extends('ssa.categories.layout')

@section('title', 'Sports & Outdoor - iMarket PH')

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
        <h1>SPORTS & OUTDOOR</h1>
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
            data-name="Durable Waterproof Hiking Boots"
            data-sizes="36,37,38,39,40,41,42,43,44,45"
            data-colors="Brown,Black,Green,Gray"
            data-price="4800"
            data-orig-price="6000"
            data-img-brown="https://cdn.shopify.com/s/files/1/0312/2921/products/1_eb6ef6cc-dd53-4348-a019-fa625878165a.jpg?v=1571265412"
            data-img-black="https://elliottsboots.sirv.com/Images/KEENOutdoor/KE1022512.jpg"
            data-img-green="https://www.bootbarn.com/dw/image/v2/BCCF_PRD/on/demandware.static/-/Sites-master-product-catalog-shp/default/dwc61b7e8a/images/625/2000337625_300_P1.JPG?sw=980&sh=980&sm=fit"
            data-img-gray="https://cdn.idealo.com/folder/Product/200523/6/200523639/s1_produktbild_max_4/keen-targhee-iii-wp-women-bleacher-duck-green.jpg">
            <div class="product-img">
                <img src="https://www.tripsavvy.com/thmb/_AoGMpzCqj-2t4BGOZf2o-3gfDY=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/Keen_Targhee_III_04-17c091d491ec4c299688c05740376cac.jpg" alt="Durable Waterproof Hiking Boots">
                <span class="discount">-20%</span>
            </div>
            <div class="product-info">
                <h6>KEEN Targhee III</h6>
                <h3>₱4,800 <del>₱6,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 4,200 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Training Shorts with Pockets"
            data-sizes="S,M,L,XL,XXL"
            data-colors="Black,Gray"
            data-price="300"
            data-orig-price="350"
            data-img-black="https://i.pinimg.com/originals/36/4f/1b/364f1b92496d49adf3b7b5ba95bd2586.jpg"
            data-img-gray="https://img.kwcdn.com/product/Fancyalgo/VirtualModelMatting/5decce1c6dae25868c879abaec31624f.jpg?imageView2/2/w/800/q/70/format/webp">
            <div class="product-img">
                <img src="https://i5.walmartimages.com/asr/b2fdf73a-4594-4902-9b0d-1a5114d923a4.9e96017fc8990f2a03dfe6d196e5c19d.jpeg?odnWidth=1000&odnHeight=1000&odnBg=ffffff" alt="Training Shorts with Pockets">
                <span class="discount">-14%</span>
            </div>
            <div class="product-info">
                <h6>Training Shorts with Pockets</h6>
                <h3>₱300 <del>₱350</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 7,800 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Hydration Pack for Cycling"
            data-sizes="Default"
            data-colors="Black,Blue,Red"
            data-price="1200"
            data-orig-price="1600"
            data-img-black="https://www.starbike.com/images/2022/uswe-mtb-hydro-backpack-12l-4-hires.jpg"
            data-img-blue="https://p.vitalmtb.com/styles/full_size_1600/s3/photos/products/41156/photos/2058236/mtb-hydro-3-black-blue-uswe-hydr.png?VersionId=dLxKAJGKTlbDyWW6LNRmm6OcNjPARm2h&itok=Bm_VX_4d"
            data-img-red="https://m.media-amazon.com/images/I/9160jxgQRPL.jpg">
            <div class="product-img">
                <img src="https://www.starbike.com/images/2022/uswe-mtb-hydro-backpack-12l-4-hires.jpg" alt="Hydration Pack for Cycling">
                <span class="discount">-25%</span>
            </div>
            <div class="product-info">
                <h6>HYDRO MTB</h6>
                <h3>₱1,200 <del>₱1,600</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 5,300 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Fleece Pullover Jacket"
            data-sizes="S,M,L,XL"
            data-colors="Brown"
            data-price="1500"
            data-orig-price="2000"
            data-img-brown="https://cdn.shopify.com/s/files/1/0114/4022/6366/products/fleece-coyote-1_1600x.jpg?v=1582183985">
            <div class="product-img">
                <img src="https://cdn.shopify.com/s/files/1/0114/4022/6366/products/fleece-coyote-1_1600x.jpg?v=1582183985" alt="Fleece Pullover Jacket">
                <span class="discount">-25%</span>
            </div>
            <div class="product-info">
                <h6>Fleece Pullover Jacket</h6>
                <h3>₱1,500 <del>₱2,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 3,400 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Rainproof Shell Jacket"
            data-sizes="S,M,L,XL"
            data-colors="Black,Red,Orange"
            data-price="2500"
            data-orig-price="3500"
            data-img-black="https://www.cimalp.co.uk/21075-new_fiche_produit/technical-ultrashell-jacket.jpg"
            data-img-navy="https://www.cimalp.co.uk/12516-new_fiche_produit/reinforced-ultrashell-jacket.jpg"
            data-img-Green="https://static.cimalp.fr/33723-large_default/superlight-windproof-jacket.jpg">
            <div class="product-img">
                <img src="https://static.cimalp.fr/30028-large_default/ultra-light-windproof-jacket.jpg" alt="Rainproof Shell Jacket">
                <span class="discount">-28%</span>
            </div>
            <div class="product-info">
                <h6>CIMALP Rainproof Jacket</h6>
                <h3>₱2,500 <del>₱3,500</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 2,900 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Long-Sleeve Compression Top"
            data-sizes="S,M,L,XL"
            data-colors="Black,WHite"
            data-price="450"
            data-orig-price="600"
            data-img-black="https://i.pinimg.com/originals/36/4f/1b/364f1b92496d49adf3b7b5ba95bd2586.jpg"
            data-img-white="https://img.kwcdn.com/product/Fancyalgo/VirtualModelMatting/5decce1c6dae25868c879abaec31624f.jpg?imageView2/2/w/800/q/70/format/webp">
            <div class="product-img">
                <img src="https://i5.walmartimages.com/asr/b2fdf73a-4594-4902-9b0d-1a5114d923a4.9e96017fc8990f2a03dfe6d196e5c19d.jpeg?odnWidth=1000&odnHeight=1000&odnBg=ffffff" alt="Long-Sleeve Compression Top">
                <span class="discount">-25%</span>
            </div>
            <div class="product-info">
                <h6>Long-Sleeve Compression Top</h6>
                <h3>₱450 <del>₱600</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 2,100 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Outdoor LED Headlamp"
            data-sizes="Default"
            data-colors="Black"
            data-price="850"
            data-orig-price="1000"
            data-img-black="https://m.media-amazon.com/images/I/71DxWxvCwlL._AC_SX679_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/71DxWxvCwlL._AC_SX679_.jpg" alt="Outdoor LED Headlamp">
                <span class="discount">-15%</span>
            </div>
            <div class="product-info">
                <h6>Outdoor LED Headlamp</h6>
                <h3>₱850 <del>₱1,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,800 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Yoga Mat with Carrying Strap"
            data-sizes="Default"
            data-colors="red"
            data-price="900"
            data-orig-price="1100"
            data-img-red="https://m.media-amazon.com/images/I/61btRqmjd-L._AC_SX569_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/61btRqmjd-L._AC_SX569_.jpg" alt="Yoga Mat">
                <span class="discount">-18%</span>
            </div>
            <div class="product-info">
                <h6>Yoga Mat with Carrying Strap</h6>
                <h3>₱900 <del>₱1,100</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 3,400 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Heavy-Duty Climbing Rope"
            data-sizes="Default"
            data-colors="Red"
            data-price="2800"
            data-orig-price="4000"
            data-img-red="https://m.media-amazon.com/images/I/81NCbSVPUkL._AC_SX679_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/81NCbSVPUkL._AC_SX679_.jpg" alt="Heavy-Duty Climbing Rope">
                <span class="discount">-30%</span>
            </div>
            <div class="product-info">
                <h6>150Ft 1/2 Inch Heavy Duty Climbing Rope</h6>
                <h3>₱2,800 <del>₱4,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,500 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="High-Performance Running Shoes"
            data-sizes="39,40,41,42,43,44,45"
            data-colors="Navy-Blue,Black,White"
            data-price="3500"
            data-orig-price="4200"
            data-img-White="https://m.media-amazon.com/images/I/71UmBKxMJqL._AC_SY535_.jpg"
            data-img-Navy-Blue="https://m.media-amazon.com/images/I/712Kt28IRUL._AC_SY535_.jpg"
            data-img-Black="https://m.media-amazon.com/images/I/81BctOe-TCL._AC_SY535_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/81BctOe-TCL._AC_SY535_.jpg" alt="High-Performance Running Shoes">
                <span class="discount">-17%</span>
            </div>
            <div class="product-info">
                <h6>Men's PG7 Running Shoes</h6>
                <h3>₱3,500 <del>₱4,200</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 8,200 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Trail Running Backpack"
            data-sizes="S,M,L"
            data-colors="White,Black,Green,White"
            data-price="1800"
            data-orig-price="2000"
            data-img-White="https://m.media-amazon.com/images/I/81-vTl43CoL._AC_SX569_.jpg"
            data-img-Black="https://m.media-amazon.com/images/I/91lLB7-eo6L._AC_SX569_.jpg"
            data-img-green="https://m.media-amazon.com/images/I/71Ut28BQ-eL._AC_SX569_.jpg"
            data-img-white="https://m.media-amazon.com/images/I/81zgaXyGt-L._AC_SX569_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/91lLB7-eo6L.__AC_SX300_SY300_QL70_FMwebp_.jpg" alt="Trail Running Backpack">
                <span class="discount">-10%</span>
            </div>
            <div class="product-info">
                <h6>MVRK Water Resistant Chest Pack</h6>
                <h3>₱1,800 <del>₱2,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 5,100 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="All-Weather Windbreaker"
            data-sizes="S,M,L,XL"
            data-colors="Green,Gray,Blue,Black"
            data-price="2100"
            data-orig-price="2500"
            data-img-green="https://m.media-amazon.com/images/I/81jBFrdbrEL._AC_SY550_.jpg"
            data-img-gray="https://m.media-amazon.com/images/I/81lMtViGxYL._AC_SY550_.jpg"
            data-img-blue="https://m.media-amazon.com/images/I/81t2H20E7KL._AC_SY550_.jpg"
            data-img-black="https://m.media-amazon.com/images/I/814N257zllL._AC_SY550_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/81jBFrdbrEL._AC_SY550_.jpg" alt="All-Weather Windbreaker">
                <span class="discount">-16%</span>
            </div>
            <div class="product-info">
                <h6>Mens Rain Jacket Windbreaker</h6>
                <h3>₱2,100 <del>₱2,500</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 4,500 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Lightweight Camping Tent"
            data-sizes="2-Person,4-Person"
            data-colors="Blue,Pink,Red,Yellow,Orange"
            data-price="3800"
            data-orig-price="5000"
            data-img-Blue="https://m.media-amazon.com/images/I/81DaQhY+yRL._AC_SX569_.jpg"
            data-img-pink="https://m.media-amazon.com/images/I/81GYG5c0DML._AC_SX569_.jpg"
            data-img-red="https://m.media-amazon.com/images/I/81HKefqkHRL._AC_SX569_.jpg"
            data-img-yellow="https://m.media-amazon.com/images/I/81gUSGUOehL._AC_SX569_.jpg"
            data-img-orange="https://m.media-amazon.com/images/I/81Ce6HzyJTL._AC_SX569_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/81DaQhY+yRL._AC_SX569_.jpg" alt="Lightweight Camping Tent">
                <span class="discount">-24%</span>
            </div>
            <div class="product-info">
                <h6>2-Person Dome Tent</h6>
                <h3>₱3,800 <del>₱5,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 2,500 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="Multi-tool Knife"
            data-sizes="90LBS"
            data-colors="Default"
            data-price="1050"
            data-orig-price="1200"
            data-img-90LB="https://m.media-amazon.com/images/I/71BE37ZZfLL._AC_SX569_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/71BE37ZZfLL._AC_SX569_.jpg" alt="Multi-tool Knife">
                <span class="discount">-12%</span>
            </div>
            <div class="product-info">
                <h6>FEIERDUN Adjustable Dumbbells</h6>
                <h3>₱1,050 <del>₱1,200</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 4,100 sold</p>
            </div>
        </div>

        <div class="product-card"
            data-name="UV Protection Sunglasses"
            data-sizes="Default"
            data-colors="Default"
            data-price="1500"
            data-orig-price="1800"
            data-img-Default="https://m.media-amazon.com/images/I/71jbvJKvSgL.__AC_SX300_SY300_QL70_FMwebp_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/71jbvJKvSgL.__AC_SX300_SY300_QL70_FMwebp_.jpg" alt="UV Protection Sunglasses">
                <span class="discount">-17%</span>
            </div>
            <div class="product-info">
                <h6>Push Up Board Home Gym</h6>
                <h3>₱1,500 <del>₱1,800</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 5,600 sold</p>
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
