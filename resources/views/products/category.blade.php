@extends('layouts.frontend')

@section('title', 'iMarket - ' . $categoryName)

@section('styles')
<style>
    .badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 2px 8px;
        font-size: 0.75rem;
        font-weight: bold;
        border-radius: 9999px; 
    }
    .sale-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 2px 8px;
        font-size: 0.75rem;
        font-weight: bold;
        color: white;
        border-radius: 9999px;
    }

    .modal {
        background-color: rgba(0, 0, 0, 0.5);
        transition: opacity 0.3s ease-in-out;
    }

    .modal-content {
        transform: translateY(-20px);
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }
    .modal.hidden {
        opacity: 0;
        pointer-events: none;
    }
    .modal.hidden .modal-content {
        transform: translateY(-20px);
        opacity: 0;
    }

    .message-box {
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        animation: fadeInOut 3s forwards;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translate(-50%, -20px); }
        10% { opacity: 1; transform: translate(-50%, 0); }
        90% { opacity: 1; transform: translate(-50%, 0); }
        100% { opacity: 0; transform: translate(-50%, -20px); }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">{{ $categoryName }}</h1>
        <p class="text-gray-600 text-center">Browse our collection of {{ strtolower($categoryName) }} products</p>
    </div>

    <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <!-- Products will be generated dynamically via JavaScript -->
    </div>
</div>

<!-- Product Modal -->
<div id="product-modal" class="modal fixed inset-0 flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300 ease-in-out">
    <div class="modal-content bg-white p-6 rounded-lg shadow-xl w-full max-w-lg relative">
        <button id="close-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-300">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <div class="flex flex-col md:flex-row items-center gap-6">
            <img id="modal-product-image" src="" alt="Product Image" class="w-48 h-48 rounded-md object-cover">
            <div class="flex-grow">
                <h2 id="modal-product-name" class="text-2xl font-bold text-gray-800 mb-2"></h2>
                <p id="modal-product-price" class="text-3xl font-bold text-blue-600 mb-4"></p>
                <div class="flex flex-col space-y-3">
                    <button id="modal-add-to-cart" class="bg-blue-600 text-white font-semibold p-3 rounded-md hover:bg-blue-700 transition-colors duration-300">
                        Add to Cart
                    </button>
                    <a href="{{ route('cart') }}" id="modal-proceed-to-checkout" class="block text-center bg-blue-400 text-white font-semibold p-3 rounded-md hover:bg-blue-500 transition-colors duration-300">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Box -->
<div id="message-box" class="message-box fixed bg-green-500 text-white px-6 py-3 rounded-md shadow-lg hidden">
    <p id="message-text"></p>
</div>
@endsection

@section('scripts')
<script>
    const colors = [
        { bg: '94dcf4', text: '2c3c8c' }
    ];

    function getRandom(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    const productGrid = document.getElementById('product-grid');
    const productModal = document.getElementById('product-modal');
    const closeModalButton = document.getElementById('close-modal');
    const modalProductName = document.getElementById('modal-product-name');
    const modalProductPrice = document.getElementById('modal-product-price');
    const modalProductImage = document.getElementById('modal-product-image');
    const modalAddToCartButton = document.getElementById('modal-add-to-cart');
    const modalProceedToCheckoutLink = document.getElementById('modal-proceed-to-checkout');
    const messageBox = document.getElementById('message-box');
    const messageText = document.getElementById('message-text');

    // Category-specific product names
    const categoryProducts = {
        'gaming': [
            'Gaming Mouse RGB', 'Mechanical Keyboard', 'Gaming Headset', 'Gaming Chair', 'Gaming Monitor',
            'Gaming Laptop', 'Gaming Controller', 'Gaming Mousepad', 'Gaming Microphone', 'Gaming Webcam'
        ],
        'accessories': [
            'Wireless Earbuds', 'Smart Watch', 'Phone Case', 'Power Bank', 'Bluetooth Speaker',
            'USB Cable', 'Phone Stand', 'Car Charger', 'Screen Protector', 'Phone Holder'
        ],
        'clothing': [
            'Classic Denim Jeans', 'Slim Fit Jeans', 'Skinny Jeans', 'Bootcut Jeans', 'High Waist Jeans',
            'Distressed Jeans', 'Black Jeans', 'Blue Jeans', 'Wide Leg Jeans', 'Mom Jeans'
        ],
        'beauty': [
            'Matte Lipstick', 'Foundation', 'Mascara', 'Eyeshadow Palette', 'Blush',
            'Concealer', 'Setting Powder', 'Lip Gloss', 'Eyeliner', 'Highlighter'
        ]
    };

    const showMessage = (message) => {
        messageText.textContent = message;
        messageBox.classList.remove('hidden');
        setTimeout(() => {
            messageBox.classList.add('hidden');
        }, 3000); 
    };

    const openProductModal = (product) => {
        modalProductName.textContent = product.name;
        modalProductPrice.textContent = `₱${product.price.toFixed(2)}`;
        modalProductImage.src = product.image;
        modalProductImage.alt = product.name;

        modalAddToCartButton.dataset.productId = product.id;
        modalAddToCartButton.dataset.productName = product.name;
        modalAddToCartButton.dataset.productPrice = product.price;
        modalAddToCartButton.dataset.productImage = product.image;

        modalProceedToCheckoutLink.dataset.productId = product.id;
        modalProceedToCheckoutLink.dataset.productName = product.name;
        modalProceedToCheckoutLink.dataset.productPrice = product.price;
        modalProceedToCheckoutLink.dataset.productImage = product.image;

        productModal.classList.remove('hidden');
    };

    const closeModal = () => {
        productModal.classList.add('hidden');
    };

    const addToCart = (product) => {
        // Create a form and submit it to add to cart
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("cart.add", ":product") }}'.replace(':product', product.id);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity';
        quantityInput.value = '1';
        
        // Add product data for demo products
        const productNameInput = document.createElement('input');
        productNameInput.type = 'hidden';
        productNameInput.name = 'product_name';
        productNameInput.value = product.name;
        
        const productPriceInput = document.createElement('input');
        productPriceInput.type = 'hidden';
        productPriceInput.name = 'product_price';
        productPriceInput.value = product.price;
        
        const productImageInput = document.createElement('input');
        productImageInput.type = 'hidden';
        productImageInput.name = 'product_image';
        productImageInput.value = product.image;
        
        form.appendChild(csrfToken);
        form.appendChild(quantityInput);
        form.appendChild(productNameInput);
        form.appendChild(productPriceInput);
        form.appendChild(productImageInput);
        document.body.appendChild(form);
        form.submit();
    };

    // Generate products based on category
    const category = '{{ $category }}';
    const productNames = categoryProducts[category] || ['Product'];
    
    for (let i = 1; i <= 20; i++) {
        const randomColor = colors[0];
        const isFlashSale = getRandom(0, 1) === 1;
        const hasExtra = getRandom(0, 1) === 1;
        const hasFreeShipping = getRandom(0, 1) === 1;
        const hasCOD = getRandom(0, 1) === 1;
        const productName = productNames[getRandom(0, productNames.length - 1)] + ` ${i}`;

        const originalPrice = getRandom(500, 2000);
        const discountPercentage = isFlashSale ? getRandom(20, 80) : 0;
        const salePrice = isFlashSale ? (originalPrice - (originalPrice * discountPercentage / 100)).toFixed(2) : originalPrice.toFixed(2);
        
        const rating = (getRandom(30, 50) / 10).toFixed(1);
        const soldCount = `${getRandom(10, 500)} sold`;
        
        const productHtml = `
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden relative transition-transform duration-300 hover:scale-105 cursor-pointer"
                 onclick="openProductModal({ id: '${i}', name: '${productName}', price: ${salePrice}, image: 'https://placehold.co/400x400/${randomColor.bg}/${randomColor.text}?text=${encodeURIComponent(productName)}' })">
                <img src="https://placehold.co/400x400/${randomColor.bg}/${randomColor.text}?text=${encodeURIComponent(productName)}" alt="Product Image" class="w-full h-auto">
                
                ${hasExtra ? `<span class="absolute top-2 left-2 bg-blue-400 text-white text-xs font-semibold px-2 py-1 rounded-full">XTRA</span>` : ''}
                ${isFlashSale ? `<span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">-${discountPercentage}%</span>` : ''}
                
                <div class="p-3">
                    <h3 class="font-semibold text-gray-800 text-sm mb-1 truncate">${productName}</h3>
                    
                    <div class="flex items-center text-xs text-gray-600 mb-2">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                        <span class="mr-2">${rating}</span>
                        <span>(${soldCount})</span>
                    </div>

                    <div class="flex items-center text-xs text-gray-600 space-x-1 mb-2">
                        ${hasFreeShipping ? `<span class="bg-gray-200 text-gray-700 px-1 py-0.5 rounded-full">Free Shipping</span>` : ''}
                        ${hasCOD ? `<span class="bg-gray-200 text-gray-700 px-1 py-0.5 rounded-full">COD</span>` : ''}
                    </div>
                    
                    <div class="flex items-baseline justify-between text-base mb-2">
                        <span class="font-bold text-blue-600">₱${salePrice}</span>
                        ${isFlashSale ? `<span class="text-xs text-gray-500 line-through">₱${originalPrice.toFixed(2)}</span>` : ''}
                    </div>
                </div>
            </div>
        `;
        productGrid.innerHTML += productHtml;
    }

    modalAddToCartButton.addEventListener('click', (event) => {
        const product = {
            id: event.target.dataset.productId,
            name: event.target.dataset.productName,
            price: parseFloat(event.target.dataset.productPrice),
            image: event.target.dataset.productImage,
        };
        addToCart(product);
        closeModal();
    });

    modalProceedToCheckoutLink.addEventListener('click', (event) => {
        const product = {
            id: event.target.dataset.productId,
            name: event.target.dataset.productName,
            price: parseFloat(event.target.dataset.productPrice),
            image: event.target.dataset.productImage,
        };
        addToCart(product);
    });

    closeModalButton.addEventListener('click', closeModal);
    productModal.addEventListener('click', (event) => {
        if (event.target === productModal) {
            closeModal();
        }
    });
</script>
@endsection
