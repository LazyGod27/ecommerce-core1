<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('ssa/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Store - iMarket PH</title>
    <style>
        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .user-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 500;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
            padding: 8px 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: all .18s ease;
            z-index: 40;
        }
        
        .user-dropdown:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        
        .user-dropdown-menu a:hover {
            background: #f9fafb;
            color: #111827;
        }
    </style>
    
    <!-- Aggressive JavaScript to force logo size -->
    <script>
        function forceLogoSize() {
            const logos = document.querySelectorAll('.logo img, header .logo img, img[alt="IMARKET PH Logo"], img[src*="logo.png"], img[alt*="IMARKET"], img[alt*="iMarket"]');
            logos.forEach(function(logo) {
                logo.style.setProperty('max-height', '80px', 'important');
                logo.style.setProperty('height', 'auto', 'important');
                logo.style.setProperty('width', 'auto', 'important');
                logo.style.setProperty('display', 'block', 'important');
                logo.style.setProperty('margin-top', '6px', 'important');
                logo.style.setProperty('margin-left', '-30px', 'important');
            });
        }
        
        forceLogoSize();
        document.addEventListener('DOMContentLoaded', forceLogoSize);
        setTimeout(forceLogoSize, 100);
        setTimeout(forceLogoSize, 500);
        setTimeout(forceLogoSize, 1000);
        window.addEventListener('load', forceLogoSize);
    </script>
</head>
<body>
    @include('components.homepage-header')

@section('styles')
<style>
    .store-container {
        padding-top: 100px;
        min-height: 100vh;
        background: #f7f8fc;
    }
    
    .store-header {
        background: white;
        padding: 40px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .store-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }
    
    .store-subtitle {
        font-size: 1.1rem;
        color: #6b7280;
        margin-bottom: 30px;
    }
    
    .seller-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        max-width: 400px;
        margin: 0 auto;
    }
    
    .seller-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .seller-details h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 5px 0;
    }
    
    .seller-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #fbbf24;
        font-size: 0.9rem;
    }
    
    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .filter-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    
    .filter-tab {
        padding: 10px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 25px;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .filter-tab.active {
        background: #4bc5ec;
        color: white;
        border-color: #4bc5ec;
    }
    
    .filter-tab:hover {
        border-color: #4bc5ec;
        color: #4bc5ec;
    }
    
    .search-filter {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-input {
        flex: 1;
        min-width: 250px;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.3s ease;
    }
    
    .search-input:focus {
        border-color: #4bc5ec;
    }
    
    .sort-select {
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        background: white;
        font-size: 1rem;
        outline: none;
        cursor: pointer;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f3f4f6;
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 8px;
    }
    
    .stars {
        color: #fbbf24;
        font-size: 0.9rem;
    }
    
    .rating-text {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .product-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #059669;
        margin-bottom: 8px;
    }
    
    .product-sold {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }
    
    .product-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-add-cart {
        flex: 1;
        padding: 10px;
        background: #4bc5ec;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    
    .btn-add-cart:hover {
        background: #3a9bc1;
    }
    
    .btn-buy-now {
        flex: 1;
        padding: 10px;
        background: #059669;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    
    .btn-buy-now:hover {
        background: #047857;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 40px;
    }
    
    .pagination button {
        padding: 10px 15px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .pagination button:hover {
        border-color: #4bc5ec;
        color: #4bc5ec;
    }
    
    .pagination button.active {
        background: #4bc5ec;
        color: white;
        border-color: #4bc5ec;
    }
    
    .no-products {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }
    
    .no-products h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    
    @media (max-width: 768px) {
        .store-title {
            font-size: 2rem;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .search-filter {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-input {
            min-width: auto;
        }
    }
</style>
@endsection

@section('content')
<div class="store-container">
    <!-- Store Header -->
    <div class="store-header">
        <h1 class="store-title">ACE Store</h1>
        <p class="store-subtitle">Discover amazing products from our trusted seller</p>
        
        <!-- Seller Info -->
        <div class="seller-info">
            <div class="seller-avatar">A</div>
            <div class="seller-details">
                <h3>ACE Electronics</h3>
                <div class="seller-rating">
                    <span class="stars">★★★★★</span>
                    <span>4.9 (2,847 reviews)</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mx-auto px-4">
        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filter-tabs">
                <div class="filter-tab active" data-category="all">All Products</div>
                <div class="filter-tab" data-category="electronics">Electronics</div>
                <div class="filter-tab" data-category="fashion">Fashion</div>
                <div class="filter-tab" data-category="home">Home & Garden</div>
                <div class="filter-tab" data-category="sports">Sports</div>
                <div class="filter-tab" data-category="beauty">Beauty</div>
                <div class="filter-tab" data-category="toys">Toys</div>
            </div>
            
            <div class="search-filter">
                <input type="text" class="search-input" placeholder="Search products..." id="searchInput">
                <select class="sort-select" id="sortSelect">
                    <option value="newest">Newest First</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="products-grid" id="productsGrid">
            <!-- Products will be loaded here -->
        </div>
        
        <!-- No Products Message -->
        <div class="no-products hidden" id="noProducts">
            <h3>No products found</h3>
            <p>Try adjusting your search or filter criteria</p>
        </div>
        
        <!-- Pagination -->
        <div class="pagination" id="pagination">
            <!-- Pagination will be generated here -->
        </div>
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-2xl font-bold text-gray-800" id="modalProductName">Product Name</h2>
                    <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <img id="modalProductImage" src="" alt="Product" class="w-full h-64 object-cover rounded-lg">
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="stars text-yellow-400">★★★★★</div>
                            <span class="text-gray-600" id="modalProductRating">4.9 (2,847 reviews)</span>
                        </div>
                        <div class="text-3xl font-bold text-green-600 mb-2" id="modalProductPrice">₱999.00</div>
                        <div class="text-gray-600 mb-4" id="modalProductSold">2,847 sold</div>
                        <div class="flex gap-3 mb-4">
                            <button onclick="addToCartFromModal()" class="btn-add-cart flex-1">Add to Cart</button>
                            <button onclick="buyNowFromModal()" class="btn-buy-now flex-1">Buy Now</button>
                        </div>
                        <div class="text-sm text-gray-500">
                            <strong>Seller:</strong> ACE Electronics
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button onclick="viewFullProduct()" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        View Full Product Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</body>
</html>

<script>
    // Sample products data
    const products = [
        {
            id: 1,
            name: "Wireless Bluetooth Headphones",
            price: 1299,
            image: "{{ asset('ssa/headset.jpg') }}",
            category: "electronics",
            rating: 4.8,
            reviews: 1247,
            sold: 2847,
            description: "High-quality wireless headphones with noise cancellation"
        },
        {
            id: 2,
            name: "Smart Fitness Watch",
            price: 1899,
            image: "{{ asset('ssa/relo.jpg') }}",
            category: "electronics",
            rating: 4.9,
            reviews: 892,
            sold: 1523,
            description: "Advanced fitness tracking with heart rate monitor"
        },
        {
            id: 3,
            name: "Gaming Mechanical Keyboard",
            price: 1499,
            image: "{{ asset('ssa/keyboard.jpg') }}",
            category: "electronics",
            rating: 4.7,
            reviews: 634,
            sold: 987,
            description: "RGB mechanical keyboard for gaming enthusiasts"
        },
        {
            id: 4,
            name: "Premium Gaming Mouse",
            price: 799,
            image: "{{ asset('ssa/mouse.jpg') }}",
            category: "electronics",
            rating: 4.6,
            reviews: 445,
            sold: 723,
            description: "High-precision gaming mouse with customizable buttons"
        },
        {
            id: 5,
            name: "Wireless Earbuds Pro",
            price: 2499,
            image: "{{ asset('ssa/earbuds.jpg') }}",
            category: "electronics",
            rating: 4.9,
            reviews: 1567,
            sold: 3245,
            description: "Premium wireless earbuds with active noise cancellation"
        },
        {
            id: 6,
            name: "LED Desk Lamp",
            price: 399,
            image: "{{ asset('ssa/lamp.jpg') }}",
            category: "home",
            rating: 4.5,
            reviews: 234,
            sold: 456,
            description: "Adjustable LED desk lamp with multiple brightness levels"
        },
        {
            id: 7,
            name: "Insulated Water Bottle",
            price: 299,
            image: "{{ asset('ssa/water.jpg') }}",
            category: "home",
            rating: 4.4,
            reviews: 178,
            sold: 312,
            description: "Stainless steel insulated water bottle, keeps drinks cold for 24 hours"
        },
        {
            id: 8,
            name: "Hooded Jacket",
            price: 1299,
            image: "{{ asset('ssa/hoodie.jpg') }}",
            category: "fashion",
            rating: 4.6,
            reviews: 567,
            sold: 891,
            description: "Comfortable hooded jacket perfect for casual wear"
        },
        {
            id: 9,
            name: "Nike Sneakers",
            price: 5999,
            image: "{{ asset('ssa/shoes.jpg') }}",
            category: "fashion",
            rating: 4.8,
            reviews: 1234,
            sold: 2134,
            description: "Classic Nike sneakers with modern comfort technology"
        },
        {
            id: 10,
            name: "Gaming Controller",
            price: 7999,
            image: "{{ asset('ssa/controller.jpg') }}",
            category: "toys",
            rating: 4.7,
            reviews: 445,
            sold: 678,
            description: "Professional gaming controller for console and PC"
        }
    ];
    
    let currentCategory = 'all';
    let currentSort = 'newest';
    let currentSearch = '';
    let currentPage = 1;
    const productsPerPage = 9;
    
    // Initialize store
    document.addEventListener('DOMContentLoaded', function() {
        loadProducts();
        setupEventListeners();
    });
    
    function setupEventListeners() {
        // Category filters
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.dataset.category;
                currentPage = 1;
                loadProducts();
            });
        });
        
        // Search input
        document.getElementById('searchInput').addEventListener('input', function() {
            currentSearch = this.value.toLowerCase();
            currentPage = 1;
            loadProducts();
        });
        
        // Sort select
        document.getElementById('sortSelect').addEventListener('change', function() {
            currentSort = this.value;
            currentPage = 1;
            loadProducts();
        });
    }
    
    function loadProducts() {
        let filteredProducts = products.filter(product => {
            const matchesCategory = currentCategory === 'all' || product.category === currentCategory;
            const matchesSearch = product.name.toLowerCase().includes(currentSearch);
            return matchesCategory && matchesSearch;
        });
        
        // Sort products
        filteredProducts.sort((a, b) => {
            switch(currentSort) {
                case 'price-low':
                    return a.price - b.price;
                case 'price-high':
                    return b.price - a.price;
                case 'rating':
                    return b.rating - a.rating;
                case 'popular':
                    return b.sold - a.sold;
                default: // newest
                    return b.id - a.id;
            }
        });
        
        // Pagination
        const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
        const startIndex = (currentPage - 1) * productsPerPage;
        const endIndex = startIndex + productsPerPage;
        const paginatedProducts = filteredProducts.slice(startIndex, endIndex);
        
        displayProducts(paginatedProducts);
        displayPagination(totalPages);
        
        // Show/hide no products message
        const noProductsDiv = document.getElementById('noProducts');
        if (paginatedProducts.length === 0) {
            noProductsDiv.classList.remove('hidden');
        } else {
            noProductsDiv.classList.add('hidden');
        }
    }
    
    function displayProducts(products) {
        const grid = document.getElementById('productsGrid');
        grid.innerHTML = '';
        
        products.forEach(product => {
            const productCard = createProductCard(product);
            grid.appendChild(productCard);
        });
    }
    
    function createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.onclick = () => showProductModal(product);
        
        card.innerHTML = `
            <img src="${product.image}" alt="${product.name}" class="product-image">
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <div class="product-rating">
                    <span class="stars">${'★'.repeat(Math.floor(product.rating))}</span>
                    <span class="rating-text">${product.rating} (${product.reviews})</span>
                </div>
                <div class="product-price">₱${product.price.toLocaleString()}</div>
                <div class="product-sold">${product.sold} sold</div>
                <div class="product-actions">
                    <button class="btn-add-cart" onclick="event.stopPropagation(); addToCart('${product.name}', ${product.price}, '${product.image}')">
                        Add to Cart
                    </button>
                    <button class="btn-buy-now" onclick="event.stopPropagation(); buyNow('${product.name}', ${product.price}, '${product.image}')">
                        Buy Now
                    </button>
                </div>
            </div>
        `;
        
        return card;
    }
    
    function displayPagination(totalPages) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';
        
        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.className = i === currentPage ? 'active' : '';
            button.onclick = () => {
                currentPage = i;
                loadProducts();
            };
            pagination.appendChild(button);
        }
    }
    
    function showProductModal(product) {
        document.getElementById('modalProductName').textContent = product.name;
        document.getElementById('modalProductImage').src = product.image;
        document.getElementById('modalProductRating').textContent = `${product.rating} (${product.reviews} reviews)`;
        document.getElementById('modalProductPrice').textContent = `₱${product.price.toLocaleString()}`;
        document.getElementById('modalProductSold').textContent = `${product.sold} sold`;
        
        document.getElementById('productModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    function addToCartFromModal() {
        const productName = document.getElementById('modalProductName').textContent;
        const productPrice = document.getElementById('modalProductPrice').textContent.replace('₱', '').replace(/,/g, '');
        const productImage = document.getElementById('modalProductImage').src;
        
        addToCart(productName, productPrice, productImage);
        closeProductModal();
    }
    
    function buyNowFromModal() {
        const productName = document.getElementById('modalProductName').textContent;
        const productPrice = document.getElementById('modalProductPrice').textContent.replace('₱', '').replace(/,/g, '');
        const productImage = document.getElementById('modalProductImage').src;
        
        buyNow(productName, productPrice, productImage);
        closeProductModal();
    }
    
    function viewFullProduct() {
        const productName = document.getElementById('modalProductName').textContent;
        // Find the product ID from the current product data
        const currentProduct = products.find(p => p.name === productName);
        if (currentProduct) {
            window.location.href = `/product/${currentProduct.id}`;
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProductModal();
        }
    });
</script>
@endsection
