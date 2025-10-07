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
    <title>Product Details - iMarket PH</title>
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
    .product-detail-container {
        padding-top: 100px;
        min-height: 100vh;
        background: #f7f8fc;
    }
    
    .product-main {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .product-gallery {
        position: relative;
        height: 500px;
        background: #f3f4f6;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-info {
        padding: 40px;
    }
    
    .product-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 15px;
        line-height: 1.2;
    }
    
    .product-rating-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .rating-stars {
        color: #fbbf24;
        font-size: 1.2rem;
    }
    
    .rating-text {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .product-price {
        font-size: 3rem;
        font-weight: 700;
        color: #059669;
        margin-bottom: 10px;
    }
    
    .product-sold {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 30px;
    }
    
    .product-actions {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .btn-add-cart {
        flex: 1;
        padding: 15px 30px;
        background: #4bc5ec;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    
    .btn-add-cart:hover {
        background: #3a9bc1;
    }
    
    .btn-buy-now {
        flex: 1;
        padding: 15px 30px;
        background: #059669;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    
    .btn-buy-now:hover {
        background: #047857;
    }
    
    .seller-section {
        background: #f8fafc;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    
    .seller-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
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
    
    .seller-info h3 {
        font-size: 1.3rem;
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
    
    .seller-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .stat-item {
        text-align: center;
        padding: 10px;
        background: white;
        border-radius: 8px;
    }
    
    .stat-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #6b7280;
    }
    
    .specifications-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .section-header {
        background: #f8fafc;
        padding: 20px 30px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    
    .section-content {
        padding: 30px;
    }
    
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .spec-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .spec-label {
        font-weight: 600;
        color: #6b7280;
    }
    
    .spec-value {
        color: #1f2937;
    }
    
    .description-text {
        color: #4b5563;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .reviews-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .reviews-summary {
        display: flex;
        align-items: center;
        gap: 30px;
        padding: 30px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .rating-overview {
        text-align: center;
    }
    
    .rating-score {
        font-size: 3rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 5px;
    }
    
    .rating-stars-large {
        color: #fbbf24;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    
    .rating-count {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .rating-breakdown {
        flex: 1;
    }
    
    .rating-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    
    .rating-label {
        width: 20px;
        font-size: 0.9rem;
        color: #6b7280;
    }
    
    .rating-progress {
        flex: 1;
        height: 8px;
        background: #f3f4f6;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .rating-fill {
        height: 100%;
        background: #fbbf24;
        border-radius: 4px;
    }
    
    .rating-percentage {
        width: 30px;
        font-size: 0.9rem;
        color: #6b7280;
        text-align: right;
    }
    
    .reviews-list {
        padding: 30px;
    }
    
    .review-item {
        padding: 20px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .review-item:last-child {
        border-bottom: none;
    }
    
    .review-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }
    
    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-weight: 600;
    }
    
    .reviewer-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 5px 0;
    }
    
    .review-rating {
        color: #fbbf24;
        font-size: 0.9rem;
    }
    
    .review-date {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .review-text {
        color: #4b5563;
        line-height: 1.5;
        margin-top: 10px;
    }
    
    .recommendations-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .recommendations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 30px;
    }
    
    .recommendation-card {
        background: #f8fafc;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .recommendation-card:hover {
        transform: translateY(-2px);
    }
    
    .recommendation-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .recommendation-info {
        padding: 15px;
    }
    
    .recommendation-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .recommendation-price {
        font-size: 1rem;
        font-weight: 700;
        color: #059669;
    }
    
    @media (max-width: 768px) {
        .product-info {
            padding: 20px;
        }
        
        .product-title {
            font-size: 2rem;
        }
        
        .product-price {
            font-size: 2.5rem;
        }
        
        .product-actions {
            flex-direction: column;
        }
        
        .reviews-summary {
            flex-direction: column;
            text-align: center;
        }
        
        .specs-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="product-detail-container">
    <div class="container mx-auto px-4">
        <!-- Product Main Section -->
        <div class="product-main">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Product Image -->
                <div class="product-gallery">
                    <img id="productImage" src="" alt="Product" class="product-image">
                </div>
                
                <!-- Product Info -->
                <div class="product-info">
                    <h1 id="productTitle" class="product-title">Product Name</h1>
                    
                    <div class="product-rating-section">
                        <div class="rating-stars" id="productRatingStars">★★★★★</div>
                        <span class="rating-text" id="productRatingText">4.9 (2,847 reviews)</span>
                    </div>
                    
                    <div class="product-price" id="productPrice">₱999.00</div>
                    <div class="product-sold" id="productSold">2,847 sold</div>
                    
                    <div class="product-actions">
                        <button class="btn-add-cart" onclick="addToCartFromDetail()">Add to Cart</button>
                        <button class="btn-buy-now" onclick="buyNowFromDetail()">Buy Now</button>
                    </div>
                    
                    <!-- Seller Section -->
                    <div class="seller-section">
                        <div class="seller-header">
                            <div class="seller-avatar">A</div>
                            <div class="seller-info">
                                <h3>ACE Electronics</h3>
                                <div class="seller-rating">
                                    <span class="stars">★★★★★</span>
                                    <span>4.9 (2,847 reviews)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="seller-stats">
                            <div class="stat-item">
                                <div class="stat-value">2,847</div>
                                <div class="stat-label">Products Sold</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">4.9</div>
                                <div class="stat-label">Rating</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">98%</div>
                                <div class="stat-label">Positive Reviews</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">24h</div>
                                <div class="stat-label">Response Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Specifications Section -->
        <div class="specifications-section">
            <div class="section-header">
                <h2 class="section-title">Product Specifications</h2>
            </div>
            <div class="section-content">
                <div class="specs-grid" id="specificationsGrid">
                    <!-- Specifications will be loaded here -->
                </div>
            </div>
        </div>
        
        <!-- Description Section -->
        <div class="specifications-section">
            <div class="section-header">
                <h2 class="section-title">Product Description</h2>
            </div>
            <div class="section-content">
                <div class="description-text" id="productDescription">
                    <!-- Product description will be loaded here -->
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="reviews-section">
            <div class="section-header">
                <h2 class="section-title">Customer Reviews</h2>
            </div>
            <div class="reviews-summary">
                <div class="rating-overview">
                    <div class="rating-score" id="overallRating">4.9</div>
                    <div class="rating-stars-large">★★★★★</div>
                    <div class="rating-count" id="totalReviews">2,847 reviews</div>
                </div>
                <div class="rating-breakdown">
                    <div class="rating-bar">
                        <span class="rating-label">5★</span>
                        <div class="rating-progress">
                            <div class="rating-fill" style="width: 85%"></div>
                        </div>
                        <span class="rating-percentage">85%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">4★</span>
                        <div class="rating-progress">
                            <div class="rating-fill" style="width: 12%"></div>
                        </div>
                        <span class="rating-percentage">12%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">3★</span>
                        <div class="rating-progress">
                            <div class="rating-fill" style="width: 2%"></div>
                        </div>
                        <span class="rating-percentage">2%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">2★</span>
                        <div class="rating-progress">
                            <div class="rating-fill" style="width: 1%"></div>
                        </div>
                        <span class="rating-percentage">1%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">1★</span>
                        <div class="rating-progress">
                            <div class="rating-fill" style="width: 0%"></div>
                        </div>
                        <span class="rating-percentage">0%</span>
                    </div>
                </div>
            </div>
            <div class="reviews-list" id="reviewsList">
                <!-- Reviews will be loaded here -->
            </div>
        </div>
        
        <!-- Recommendations Section -->
        <div class="recommendations-section">
            <div class="section-header">
                <h2 class="section-title">You May Also Like</h2>
            </div>
            <div class="recommendations-grid" id="recommendationsGrid">
                <!-- Recommendations will be loaded here -->
            </div>
        </div>
    </div>
</div>
    </div>
</body>
</html>

<script>
    // Sample products database
    const productsDatabase = {
        1: {
            id: 1,
            name: "Wireless Bluetooth Headphones",
            price: 1299,
            image: "{{ asset('ssa/headset.jpg') }}",
            rating: 4.8,
            reviews: 1247,
            sold: 2847,
            description: "Experience premium sound quality with our Wireless Bluetooth Headphones. Featuring advanced noise cancellation technology, these headphones deliver crystal-clear audio for music, calls, and entertainment. With up to 30 hours of battery life and quick charge capability, you can enjoy uninterrupted listening all day long. The ergonomic design ensures comfort during extended use, while the wireless connectivity provides freedom of movement. Perfect for professionals, students, and music enthusiasts who demand the best audio experience.",
            specifications: {
                "Brand": "ACE Electronics",
                "Model": "AE-WH-2024",
                "Connectivity": "Bluetooth 5.0",
                "Battery Life": "30 hours",
                "Charging Time": "2 hours",
                "Range": "10 meters",
                "Weight": "250g",
                "Color": "Black",
                "Warranty": "2 years",
                "Noise Cancellation": "Active",
                "Microphone": "Built-in",
                "Compatibility": "iOS, Android, PC"
            },
            reviews: [
                {
                    id: 1,
                    name: "Sarah Johnson",
                    rating: 5,
                    date: "2024-01-15",
                    text: "Amazing sound quality! The noise cancellation works perfectly and the battery life is incredible. Highly recommend!"
                },
                {
                    id: 2,
                    name: "Mike Chen",
                    rating: 5,
                    date: "2024-01-12",
                    text: "Great headphones for the price. Comfortable to wear for long periods and the sound is crisp and clear."
                },
                {
                    id: 3,
                    name: "Emily Davis",
                    rating: 4,
                    date: "2024-01-10",
                    text: "Good quality headphones, though the bass could be a bit stronger. Overall satisfied with the purchase."
                },
                {
                    id: 4,
                    name: "David Wilson",
                    rating: 5,
                    date: "2024-01-08",
                    text: "Excellent product! Fast delivery and great customer service. The headphones exceeded my expectations."
                },
                {
                    id: 5,
                    name: "Lisa Brown",
                    rating: 4,
                    date: "2024-01-05",
                    text: "Very comfortable and good sound quality. The only minor issue is the charging cable could be longer."
                }
            ],
            recommendations: [
                {
                    id: 2,
                    name: "Smart Fitness Watch",
                    price: 1899,
                    image: "{{ asset('ssa/relo.jpg') }}"
                },
                {
                    id: 3,
                    name: "Gaming Mechanical Keyboard",
                    price: 1499,
                    image: "{{ asset('ssa/keyboard.jpg') }}"
                },
                {
                    id: 4,
                    name: "Premium Gaming Mouse",
                    price: 799,
                    image: "{{ asset('ssa/mouse.jpg') }}"
                },
                {
                    id: 5,
                    name: "Wireless Earbuds Pro",
                    price: 2499,
                    image: "{{ asset('ssa/earbuds.jpg') }}"
                }
            ]
        },
        2: {
            id: 2,
            name: "Smart Fitness Watch",
            price: 1899,
            image: "{{ asset('ssa/relo.jpg') }}",
            rating: 4.9,
            reviews: 892,
            sold: 1523,
            description: "Track your fitness goals with our advanced Smart Fitness Watch. Monitor heart rate, steps, sleep patterns, and more with precision. Water-resistant design perfect for workouts and daily activities.",
            specifications: {
                "Brand": "ACE Electronics",
                "Model": "AE-SW-2024",
                "Display": "1.4 inch AMOLED",
                "Battery Life": "7 days",
                "Water Resistance": "5ATM",
                "Sensors": "Heart Rate, GPS, Accelerometer",
                "Compatibility": "iOS, Android",
                "Weight": "45g",
                "Color": "Black",
                "Warranty": "2 years"
            },
            reviews: [
                {
                    id: 1,
                    name: "John Smith",
                    rating: 5,
                    date: "2024-01-14",
                    text: "Excellent fitness tracker! Accurate heart rate monitoring and great battery life."
                },
                {
                    id: 2,
                    name: "Maria Garcia",
                    rating: 4,
                    date: "2024-01-11",
                    text: "Good watch for the price. The sleep tracking is very helpful."
                }
            ],
            recommendations: [
                { id: 1, name: "Wireless Bluetooth Headphones", price: 1299, image: "{{ asset('ssa/headset.jpg') }}" },
                { id: 3, name: "Gaming Mechanical Keyboard", price: 1499, image: "{{ asset('ssa/keyboard.jpg') }}" }
            ]
        }
    };
    
    // Get product ID from URL
    const productId = {{ $id }};
    const productData = productsDatabase[productId] || productsDatabase[1];
    
    // Initialize product detail page
    document.addEventListener('DOMContentLoaded', function() {
        loadProductData();
        loadSpecifications();
        loadDescription();
        loadReviews();
        loadRecommendations();
    });
    
    function loadProductData() {
        document.getElementById('productTitle').textContent = productData.name;
        document.getElementById('productImage').src = productData.image;
        document.getElementById('productPrice').textContent = `₱${productData.price.toLocaleString()}`;
        document.getElementById('productSold').textContent = `${productData.sold} sold`;
        
        // Rating stars
        const stars = '★'.repeat(Math.floor(productData.rating)) + '☆'.repeat(5 - Math.floor(productData.rating));
        document.getElementById('productRatingStars').textContent = stars;
        document.getElementById('productRatingText').textContent = `${productData.rating} (${productData.reviews} reviews)`;
        document.getElementById('overallRating').textContent = productData.rating;
        document.getElementById('totalReviews').textContent = `${productData.reviews} reviews`;
    }
    
    function loadSpecifications() {
        const grid = document.getElementById('specificationsGrid');
        grid.innerHTML = '';
        
        Object.entries(productData.specifications).forEach(([key, value]) => {
            const specItem = document.createElement('div');
            specItem.className = 'spec-item';
            specItem.innerHTML = `
                <span class="spec-label">${key}</span>
                <span class="spec-value">${value}</span>
            `;
            grid.appendChild(specItem);
        });
    }
    
    function loadDescription() {
        document.getElementById('productDescription').textContent = productData.description;
    }
    
    function loadReviews() {
        const reviewsList = document.getElementById('reviewsList');
        reviewsList.innerHTML = '';
        
        productData.reviews.forEach(review => {
            const reviewItem = document.createElement('div');
            reviewItem.className = 'review-item';
            
            const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
            const initials = review.name.split(' ').map(n => n[0]).join('');
            
            reviewItem.innerHTML = `
                <div class="review-header">
                    <div class="reviewer-avatar">${initials}</div>
                    <div class="reviewer-info">
                        <h4>${review.name}</h4>
                        <div class="review-rating">${stars}</div>
                    </div>
                    <div class="review-date">${new Date(review.date).toLocaleDateString()}</div>
                </div>
                <div class="review-text">${review.text}</div>
            `;
            
            reviewsList.appendChild(reviewItem);
        });
    }
    
    function loadRecommendations() {
        const grid = document.getElementById('recommendationsGrid');
        grid.innerHTML = '';
        
        productData.recommendations.forEach(product => {
            const card = document.createElement('div');
            card.className = 'recommendation-card';
            card.onclick = () => window.location.href = `/product/${product.id}`;
            
            card.innerHTML = `
                <img src="${product.image}" alt="${product.name}" class="recommendation-image">
                <div class="recommendation-info">
                    <div class="recommendation-name">${product.name}</div>
                    <div class="recommendation-price">₱${product.price.toLocaleString()}</div>
                </div>
            `;
            
            grid.appendChild(card);
        });
    }
    
    function addToCartFromDetail() {
        addToCart(productData.name, productData.price, productData.image);
    }
    
    function buyNowFromDetail() {
        buyNow(productData.name, productData.price, productData.image);
    }
</script>
@endsection
