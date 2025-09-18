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
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
    }
    
    .hero-content h1 {
        font-size: 4rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin-bottom: 20px;
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
    <section class="hero">
        <div class="hero-slides">
            <img class="slide active" src="https://i.pinimg.com/736x/a2/4a/b5/a24ab53b4bf89b079978fad2e813e6d8.jpg" alt="Slide 1">
            <img class="slide" src="https://i.pinimg.com/1200x/95/62/40/9562401b17aa8803c45564fa87a7523a.jpg" alt="Slide 2">
            <img class="slide" src="https://i.pinimg.com/1200x/3f/29/e2/3f29e2a0f203313db21fa45734f73b29.jpg" alt="Slide 3">
            <img class="slide" src="https://i.pinimg.com/736x/fd/a6/8c/fda68c5189f7cd37f044e201a77acc1e.jpg" alt="Slide 4">
            <img class="slide" src="https://i.pinimg.com/736x/5c/b4/98/5cb49830b398136f9c2aa341f847958d.jpg" alt="Slide 5">
        </div>
        <div class="hero-content">
            <h1>SHOP</h1>
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
                data-name="Family Adventure Board Game" 
                data-sizes="Standard" 
                data-colors="Multi-Color" 
                data-price="950" 
                data-orig-price="1200" 
                data-img-red="https://m.media-amazon.com/images/I/91+0y-6N+KL.jpg">
                <div class="product-img">
                    <img src="https://m.media-amazon.com/images/I/91+0y-6N+KL.jpg" alt="Family Adventure Board Game">
                    <span class="discount">-21%</span>
                </div>
                <div class="product-info">
                    <h6>Family Adventure Board Game</h6>
                    <h3>₱950 <del>₱1,200</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,500 sold</p>
                </div>
            </div>

            <div class="product-card" 
                data-name="Galactic Starship LEGO Set" 
                data-sizes="Large" 
                data-colors="Blue, Gray" 
                data-price="1750" 
                data-orig-price="2200"
                data-img-red="https://www.lego.com/cdn/product-assets/product.img.toy.bricks.jpeg/40555.jpeg">
                <div class="product-img">
                    <img src="https://www.lego.com/cdn/product-assets/product.img.toy.bricks.jpeg/40555.jpeg" alt="Galactic Starship LEGO Set">
                    <span class="discount">-21%</span>
                </div>
                <div class="product-info">
                    <h6>Galactic Starship LEGO Set</h6>
                    <h3>₱1,750 <del>₱2,200</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 800 sold</p>
                </div>
            </div>

            <div class="product-card" 
                data-name="Compact RC Racing Drone" 
                data-sizes="Small, Medium" 
                data-colors="Black, White, Red" 
                data-price="4000" 
                data-orig-price="6500"
                data-img-black="https://i.pinimg.com/originals/e8/34/00/e83400a40236a282f6f36599f57424ed.jpg">
                <div class="product-img">
                    <img src="https://i.pinimg.com/originals/e8/34/00/e83400a40236a282f6f36599f57424ed.jpg" alt="Compact RC Racing Drone">
                    <span class="discount">-38%</span>
                </div>
                <div class="product-info">
                    <h6>Compact RC Racing Drone</h6>
                    <h3>₱4,000 <del>₱6,500</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 650 sold</p>
                </div>
            </div>

            <div class="product-card" 
                data-name="Superhero Action Figure" 
                data-sizes="6 inch, 12 inch" 
                data-colors="Blue, Red, Black" 
                data-price="1890" 
                data-orig-price="2100"
                data-img-black="https://m.media-amazon.com/images/I/81M+2dI9YhL._AC_UF890,1000_QL80_.jpg">
                <div class="product-img">
                    <img src="https://m.media-amazon.com/images/I/81M+2dI9YhL._AC_UF890,1000_QL80_.jpg" alt="Superhero Action Figure">
                    <span class="discount">-10%</span>
                </div>
                <div class="product-info">
                    <h6>Superhero Action Figure</h6>
                    <h3>₱1,890 <del>₱2,100</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 300 sold</p>
                </div>
            </div>

            <div class="product-card" 
                data-name="1000-Piece Landscape Puzzle" 
                data-sizes="Standard" 
                data-colors="Nature, City, Ocean" 
                data-price="395" 
                data-orig-price="465"
                data-img-black="https://m.media-amazon.com/images/I/91eKz+72SjL._AC_SX425_.jpg">
                <div class="product-img">
                    <img src="https://m.media-amazon.com/images/I/91eKz+72SjL._AC_SX425_.jpg" alt="1000-Piece Landscape Puzzle">
                    <span class="discount">-15%</span>
                </div>
                <div class="product-info">
                    <h6>1000-Piece Landscape Puzzle</h6>
                    <h3>₱395 <del>₱465</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 900 sold</p>
                </div>
            </div>

            <div class="product-card" 
                data-name="Creative Clay Set" 
                data-sizes="Small, Large" 
                data-colors="Red, Blue, Yellow, Green" 
                data-price="180" 
                data-orig-price="240"
                data-img-gray="https://images.thdstatic.com/productImages/ecb1814e-913a-446a-93be-6d9b9a528c11/svn/play-doh-play-doh-sets-a1780f01-64_600.jpg">
                <div class="product-img">
                    <img src="https://images.thdstatic.com/productImages/ecb1814e-913a-446a-93be-6d9b9a528c11/svn/play-doh-play-doh-sets-a1780f01-64_600.jpg" alt="Creative Clay Set">
                    <span class="discount">-25%</span>
                </div>
                <div class="product-info">
                    <h6>Creative Clay Set</h6>
                    <h3>₱180 <del>₱240</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,100 sold</p>
                </div>
            </div>
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

        // Auto-advance slides
        setInterval(nextSlide, 5000);

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
            card.addEventListener('click', () => {
                const productData = {
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
            if (selectedSize && selectedColor) {
                alert(`Added ${productName} (Size: ${selectedSize}, Color: ${selectedColor}) to cart!`);
                closeModal();
            } else {
                alert('Please select a size and color.');
            }
        });

        checkoutBtn.addEventListener('click', () => {
            const productName = modalName.textContent;
            if (selectedSize && selectedColor) {
                alert(`Proceeding to checkout for ${productName} (Size: ${selectedSize}, Color: ${selectedColor}).`);
                closeModal();
            } else {
                alert('Please select a size and color before checking out.');
            }
        });
    });
</script>
@endsection