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
    <title>iMarket PH</title>
    <style>
        .image-scan-fab{position:fixed;bottom:20px;left:20px;width:50px;height:50px;border-radius:50%;background-color:#007bff;color:#fff;border:none;display:flex;justify-content:center;align-items:center;font-size:1.5rem;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,.15);z-index:999;transition:transform .3s ease}.image-scan-fab:hover{transform:scale(1.1)}.voice-toggle-btn{position:fixed;bottom:20px;right:20px;width:180px;height:50px;border-radius:50px;background-color:#007bff;color:#000;border:none;display:flex;justify-content:center;align-items:center;font-size:.9rem;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,.15);z-index:1000;transition:transform .3s ease}.voice-toggle-btn:hover{transform:scale(1.05)}.voice-chat-container{position:fixed;bottom:-100%;right:20px;width:350px;height:450px;background-color:#fff;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,.2);display:flex;flex-direction:column;z-index:1001;transition:bottom .5s cubic-bezier(.68,-.55,.27,1.55)}.voice-chat-container.open{bottom:90px}.voice-chat-header{display:flex;justify-content:space-between;align-items:center;padding:15px 20px;background-color:var(--primary-color);color:#00008a;border-top-left-radius:12px;border-top-right-radius:12px;font-weight:600;font-size:1.1rem}.voice-chat-close-btn{background:none;border:none;color:#ffffff;font-size:1.5rem;cursor:pointer;line-height:1}.voice-chat-body{flex-grow:1;padding:20px;overflow-y:auto;background-color:#4f549c;display:flex;flex-direction:column;gap:10px}.voice-message{padding:10px 15px;border-radius:15px;max-width:80%;word-wrap:break-word}.voice-bot-message{background-color:#e0e0e0;align-self:flex-start}.voice-user-message{background-color:var(--primary-color);color:#fff;align-self:flex-end}.voice-chat-input-area{display:flex;justify-content:center;align-items:center;padding:15px;border-top:1px solid #000}#voice-mic-btn{background-color:rgb(125,125,241);color:#000;border:none;border-radius:50%;width:50px;height:50px;font-size:1.5rem;cursor:pointer;transition:background-color .3s ease,transform .3s ease}#voice-mic-btn.listening{background-color:#dc3545;animation:pulse 1s infinite}@keyframes pulse{0%{transform:scale(1)}50%{transform:scale(1.1)}100%{transform:scale(1)}}
        
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
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .user-dropdown-menu a:last-child {
            border-bottom: none;
        }
        
        .user-dropdown-menu a:hover {
            background-color: #f8f9fa;
        }
        
        .user-dropdown-menu a i {
            width: 16px;
            text-align: center;
        }
        
        /* Product Card Clickable Styles */
        .product-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .product-card:active {
            transform: translateY(-2px);
        }
        
        /* Error and Success Message Styles */
        .error-message {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
        }
        
        .success-message {
            background: #efe;
            color: #363;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #cfc;
        }
        
        .error-message ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .error-message li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="login-modal" id="loginModal">
        <div class="login-content">
            <span class="close-btn" id="closeLogin">&times;</span>
            <h2>Login</h2>
            
            @if ($errors->any())
                <div class="error-message">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label for="login_email">Email</label>
                <input type="email" id="login_email" name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                <label for="login_password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="login_password" name="password" placeholder="Enter password" required>
                    <i class="ri-eye-line" id="togglePassword"></i>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <p class="signup-text">Don't have an account yet? <a href="#" id="openSignup">Sign up here</a></p>
        </div>
    </div>

    <div class="signup-modal" id="signupModal">
        <div class="signup-content">
            <span class="close-btn" id="closeSignup">&times;</span>
            <h2>Sign Up</h2>
            
            @if ($errors->any())
                <div class="error-message">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <label for="register_name">Full Name</label>
                <input type="text" id="register_name" name="name" placeholder="Enter full name" value="{{ old('name') }}" required>
                <label for="register_email">Email</label>
                <input type="email" id="register_email" name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                <label for="register_password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="register_password" name="password" placeholder="Enter password" required>
                    <i class="ri-eye-line toggleSignupPassword"></i>
                </div>
                <label for="register_password_confirmation">Confirm Password</label>
                <input type="password" id="register_password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                <button type="submit" class="login-btn">Create Account</button>
            </form>
            <div class="divider"><span>OR</span></div>
            <div class="social-login">
                <a href="{{ route('auth.google') }}" class="google-btn">
                    <img src="{{ asset('ssa/google.png') }}" alt="Google Logo">
                    Sign up with Google
                </a>
                <a href="{{ route('auth.facebook') }}" class="facebook-btn">
                    <i class="ri-facebook-fill"></i> Sign up with Facebook
                </a>
            </div>
            <p class="signup-text">Already have an account? <a href="#" id="openLoginFromSignup">Login here</a></p>
        </div>
    </div>

    <header>
        <div class="logo">
            <img src="{{ asset('ssa/logo.png') }}" alt="IMARKET PH Logo">
        </div>
        <ul class="navbar" id="navbar">
            <li><a href="#" class="active"><i class="ri-home-line"></i> Home</a></li>
            <li><a href="#"><i class="ri-store-line"></i> Mall</a></li>
            <li><a href="#"><i class="ri-percent-line"></i> Flash Deals</a></li>
            <li class="dropdown">
                <a href="#"><i class="ri-list-unordered"></i> Categories <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('products') }}"><i class="ri-fire-line"></i> Best Selling</a></li>
                    <li><a href="{{ route('products') }}"><i class="ri-star-smile-line"></i> New Arrivals</a></li>
                    <li><a href="{{ route('products.category', ['category' => 'electronics']) }}"><i class="ri-computer-line"></i> Electronics</a></li>
                    <li><a href="{{ route('products.category', ['category' => 'fashion']) }}"><i class="ri-t-shirt-line"></i> Fashion & Apparel</a></li>
                    <li><a href="{{ route('products.category', ['category' => 'home']) }}"><i class="ri-home-4-line"></i> Home & Living</a></li>
                    <li><a href="{{ route('products.category', ['category' => 'beauty']) }}"><i class="ri-heart-line"></i> Beauty & Health</a></li>
                    <li><a href="{{ route('products.category', ['category' => 'sports']) }}"><i class="ri-football-line"></i> Sports & Outdoor</a></li>
                    <li><a href="{{ route('products.category', ['category' => 'toys']) }}"><i class="ri-gamepad-line"></i> Toys & Games</a></li>
                    <li><a href="{{ route('products.category', ['category' => 'groceries']) }}"><i class="ri-shopping-basket-line"></i> Groceries</a></li>
                </ul>
            </li>
        </ul>
        <div class="search-bar">
            <form action="{{ route('products.search') }}" method="GET" class="flex flex-grow">
                <input type="text" name="q" placeholder="Search for products, brands and more..." class="w-full px-3 text-base outline-none border-none" value="{{ request('q') }}" />
                <button type="submit" class="search-btn"><i class="ri-search-line"></i></button>
            </form>
        </div>
        <div class="icons">
            <a href="{{ route('cart') }}"><i class="ri-shopping-cart-line"></i></a>
            @auth
                <div class="user-dropdown">
                    <a href="#" class="user-link">
                        <i class="ri-user-line"></i>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <div class="user-dropdown-menu">
                        <a href="{{ route('profile.index') }}"><i class="ri-user-line"></i> My Profile</a>
                        <a href="{{ route('profile.orders') }}"><i class="ri-shopping-bag-line"></i> My Orders</a>
                        <a href="{{ route('tracking') }}"><i class="ri-truck-line"></i> Track Orders</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ri-logout-box-line"></i> Logout</a>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="#" id="openLogin"><i class="ri-user-line"></i></a>
            @endauth
            <div class="bx bx-menu" id="menu-icon"></div>
        </div>
    </header>

    <div class="overlay" id="overlay"></div>

    <section class="hero">
        <div class="hero-slides">
            <img class="slide active" src="{{ asset('ssa/clothes.webp') }}" alt="Slide 1">
            <img class="slide" src="{{ asset('ssa/soap.jpg') }}" alt="Slide 2">
            <img class="slide" src="{{ asset('ssa/pc.webp') }}" alt="Slide 3">
            <img class="slide" src="{{ asset('ssa/home.jpg') }}" alt="Slide 4">
            <img class="slide" src="{{ asset('ssa/school.webp') }}" alt="Slide 5">
        </div>
        <div class="hero-content">
            <h1>IMARKET PH</h1>
            <p>Discover amazing deals and best-selling products.</p>
            <a href="{{ route('products') }}" class="hero-btn">Shop Now</a>
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
        <div class="middle-text">
            <h2>Discover <span>Quality Products</span></h2>
        </div>
        <div class="feature-content">
            <div class="product-card" onclick="viewProduct('Men\'s Sneakers', 899, '{{ asset('ssa/sneakers.webp') }}', 'High-quality men\'s sneakers perfect for daily wear and sports activities.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/sneakers.webp') }}" alt="Men's Sneakers">
                    <span class="discount">-35%</span>
                </div>
                <div class="product-info">
                    <h6>Men's Sneakers</h6>
                    <h3>₱899 <del>₱1,399</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,245 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Men\'s Sneakers', 899, '{{ asset('ssa/sneakers.webp') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Men\'s Sneakers', 899, '{{ asset('ssa/sneakers.webp') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Gaming Headset', 499, '{{ asset('ssa/headset.jpg') }}', 'Professional gaming headset with surround sound and noise cancellation.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/headset.jpg') }}" alt="Gaming Headset">
                    <span class="discount">-50%</span>
                </div>
                <div class="product-info">
                    <h6>Gaming Headset</h6>
                    <h3>₱499 <del>₱999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 980 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Gaming Headset', 499, '{{ asset('ssa/headset.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Gaming Headset', 499, '{{ asset('ssa/headset.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Casual Backpack', 599, '{{ asset('ssa/back.jpg') }}', 'Durable and stylish backpack perfect for daily use and travel.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/back.jpg') }}" alt="Casual Backpack">
                    <span class="discount">-40%</span>
                </div>
                <div class="product-info">
                    <h6>Casual Backpack</h6>
                    <h3>₱599 <del>₱999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 740 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Casual Backpack', 599, '{{ asset('ssa/back.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Casual Backpack', 599, '{{ asset('ssa/back.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Smart Watch', 799, '{{ asset('ssa/relo.jpg') }}', 'Advanced smartwatch with health monitoring and fitness tracking features.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/relo.jpg') }}" alt="Smart Watch">
                    <span class="discount">-60%</span>
                </div>
                <div class="product-info">
                    <h6>Smart Watch</h6>
                    <h3>₱799 <del>₱1,999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,560 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Smart Watch', 799, '{{ asset('ssa/relo.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Smart Watch', 799, '{{ asset('ssa/relo.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('LED Desk Lamp', 299, '{{ asset('ssa/lamp.jpg') }}', 'Adjustable LED desk lamp with multiple brightness levels and USB charging port.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/lamp.jpg') }}" alt="LED Desk Lamp">
                    <span class="discount">-25%</span>
                </div>
                <div class="product-info">
                    <h6>LED Desk Lamp</h6>
                    <h3>₱299 <del>₱399</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 430 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('LED Desk Lamp', 299, '{{ asset('ssa/lamp.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('LED Desk Lamp', 299, '{{ asset('ssa/lamp.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Wireless Earbuds', 699, '{{ asset('ssa/earbuds.jpg') }}', 'High-quality wireless earbuds with noise cancellation and long battery life.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/earbuds.jpg') }}" alt="Wireless Earbuds">
                    <span class="discount">-30%</span>
                </div>
                <div class="product-info">
                    <h6>Wireless Earbuds</h6>
                    <h3>₱699 <del>₱999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 860 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Wireless Earbuds', 699, '{{ asset('ssa/earbuds.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Wireless Earbuds', 699, '{{ asset('ssa/earbuds.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Gaming Mouse', 329, '{{ asset('ssa/mouse.jpg') }}', 'Precision gaming mouse with customizable RGB lighting and high DPI sensor.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/mouse.jpg') }}" alt="Gaming Mouse">
                    <span class="discount">-45%</span>
                </div>
                <div class="product-info">
                    <h6>Gaming Mouse</h6>
                    <h3>₱329 <del>₱599</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,120 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Gaming Mouse', 329, '{{ asset('ssa/mouse.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Gaming Mouse', 329, '{{ asset('ssa/mouse.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Hooded Jacket', 1299, '{{ asset('ssa/hoodie.jpg') }}', 'Comfortable and stylish hooded jacket perfect for casual wear and outdoor activities.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/hoodie.jpg') }}" alt="Hooded Jacket">
                    <span class="discount">-35%</span>
                </div>
                <div class="product-info">
                    <h6>Hooded Jacket</h6>
                    <h3>₱1,299 <del>₱1,999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 560 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Hooded Jacket', 1299, '{{ asset('ssa/hoodie.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Hooded Jacket', 1299, '{{ asset('ssa/hoodie.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Mechanical Keyboard', 1499, '{{ asset('ssa/keyboard.jpg') }}', 'Professional mechanical keyboard with RGB backlighting and tactile switches.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/keyboard.jpg') }}" alt="Mechanical Keyboard">
                    <span class="discount">-55%</span>
                </div>
                <div class="product-info">
                    <h6>Mechanical Keyboard</h6>
                    <h3>₱1,499 <del>₱3,299</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 2,010 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Mechanical Keyboard', 1499, '{{ asset('ssa/keyboard.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Mechanical Keyboard', 1499, '{{ asset('ssa/keyboard.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Insulated Water Bottle', 399, '{{ asset('ssa/water.jpg') }}', 'High-quality insulated water bottle that keeps drinks cold for 24 hours and hot for 12 hours.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/water.jpg') }}" alt="Insulated Water Bottle">
                    <span class="discount">-20%</span>
                </div>
                <div class="product-info">
                    <h6>Insulated Water Bottle</h6>
                    <h3>₱399 <del>₱499</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 740 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Insulated Water Bottle', 399, '{{ asset('ssa/water.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Insulated Water Bottle', 399, '{{ asset('ssa/water.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product">
        <div class="middle-text">
            <h2>New <span>Arrival</span></h2>
        </div>
        <div class="feature-content">
            <div class="product-card" onclick="viewProduct('AirPods Pro 2', 8999, '{{ asset('ssa/airpods.jpg') }}', 'Latest Apple AirPods Pro with active noise cancellation and spatial audio.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/airpods.jpg') }}" alt="AirPods Pro 2">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Apple</h6>
                    <h3>AirPods Pro 2</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Just Arrived</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('AirPods Pro 2', 8999, '{{ asset('ssa/airpods.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('AirPods Pro 2', 8999, '{{ asset('ssa/airpods.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Nike Dunk Low Panda', 5999, '{{ asset('ssa/shoes.jpg') }}', 'Classic Nike Dunk Low in black and white colorway, perfect for street style.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/shoes.jpg') }}" alt="Nike Dunk Low">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Nike</h6>
                    <h3>Dunk Low Panda</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Fresh Drop</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Nike Dunk Low Panda', 5999, '{{ asset('ssa/shoes.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Nike Dunk Low Panda', 5999, '{{ asset('ssa/shoes.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Sony DualSense Edge', 7999, '{{ asset('ssa/controller.jpg') }}', 'Professional gaming controller for PS5 with customizable buttons and triggers.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/controller.jpg') }}" alt="PS5 DualSense Edge">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Sony</h6>
                    <h3>DualSense Edge</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Latest Release</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Sony DualSense Edge', 7999, '{{ asset('ssa/controller.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Sony DualSense Edge', 7999, '{{ asset('ssa/controller.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Smart Watch Ultra Gen 2', 12999, '{{ asset('ssa/ultra.jpg') }}', 'Advanced smartwatch with health monitoring, GPS, and extended battery life.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/ultra.jpg') }}" alt="Smart Watch Ultra">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Smart Tech</h6>
                    <h3>Watch Ultra Gen 2</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Brand New</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Smart Watch Ultra Gen 2', 12999, '{{ asset('ssa/ultra.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Smart Watch Ultra Gen 2', 12999, '{{ asset('ssa/ultra.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('JBL Go 4 Portable Speaker', 1999, '{{ asset('ssa/jbl.png') }}', 'Compact portable speaker with powerful sound and waterproof design.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/jbl.png') }}" alt="JBL Go 4 Speaker">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>JBL</h6>
                    <h3>Go 4 Portable Speaker</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> New Arrival</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('JBL Go 4 Portable Speaker', 1999, '{{ asset('ssa/jbl.png') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('JBL Go 4 Portable Speaker', 1999, '{{ asset('ssa/jbl.png') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="voice-chat-container" id="voice-chat-container">
        <div class="voice-chat-header">
            <span class="voice-chat-title">Voice Command AI</span>
            <button class="voice-chat-close-btn" id="voice-close-chat-btn">&times;</button>
        </div>
        <div class="voice-chat-body" id="voice-chat-body">
            <div class="voice-message voice-bot-message">Hello! Tap the microphone to start talking.</div>
        </div>
        <div class="voice-chat-input-area">
            <button id="voice-mic-btn"><i class="ri-mic-fill"></i></button>
        </div>
    </div>

    <button class="voice-toggle-btn" id="voice-toggle-btn">Voice Command AI</button>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h5>Customer Care</h5>
                <ul>
                    <li><a href="{{ route('customer-service') }}">Customer Service</a></li>
                    <li><a href="#">How to Buy</a></li>
                    <li><a href="#">Shipping & Delivery</a></li>
                    <li><a href="#">Return & Refund</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>About ImarketPH</h5>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Payment Methods</h5>
                <div class="footer-logos">
                    <img src="{{ asset('ssa/visa.png') }}" alt="Visa">
                    <img src="{{ asset('ssa/mastercard.png') }}" alt="Mastercard">
                    <img src="{{ asset('ssa/gcash.png') }}" alt="GCash">
                    <img src="{{ asset('ssa/maya.png') }}" alt="Paymaya">
                </div>
            </div>
            <div class="footer-section">
                <h5>Delivery Services</h5>
                <div class="footer-logos">
                    <img src="{{ asset('ssa/jnt.png') }}" alt="J&T Express">
                    <img src="{{ asset('ssa/ninjavan.jpg') }}" alt="Ninja Van">
                    <img src="{{ asset('ssa/lbc.png') }}" alt="LBC Express">
                    <img src="{{ asset('ssa/flash.png') }}" alt="Flash Express">
                </div>
            </div>
            <div class="footer-section">
                <h5>Follow Us</h5>
                <div class="footer-socials">
                    <a href="#"><img src="{{ asset('ssa/facebook.png') }}" alt="Facebook"></a>
                    <a href="#"><img src="{{ asset('ssa/instagram.jpg') }}" alt="Instagram"></a>
                    <a href="#"><img src="{{ asset('ssa/twitter.png') }}" alt="Twitter"></a>
                    <a href="#"><img src="{{ asset('ssa/youtube.png') }}" alt="YouTube"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 All Rights Reserved by ImarketPH</p>
        </div>
    </footer>

    <button class="image-scan-fab" onclick="window.open('https://images.google.com/', '_blank')" title="Image Scan">
        <i class="ri-camera-line"></i>
    </button>

    <form id="add-to-cart-form" method="POST" style="display:none;"></form>
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
                            
                            <!-- Size Selection -->
                            <div class="product-options">
                                <div class="size-selector">
                                    <label>Size:</label>
                                    <div class="size-options">
                                        <input type="radio" id="size-s" name="size" value="S" checked>
                                        <label for="size-s" class="size-option">S</label>
                                        <input type="radio" id="size-m" name="size" value="M">
                                        <label for="size-m" class="size-option">M</label>
                                        <input type="radio" id="size-l" name="size" value="L">
                                        <label for="size-l" class="size-option">L</label>
                                        <input type="radio" id="size-xl" name="size" value="XL">
                                        <label for="size-xl" class="size-option">XL</label>
                                        <input type="radio" id="size-xxl" name="size" value="XXL">
                                        <label for="size-xxl" class="size-option">XXL</label>
                                    </div>
                                </div>
                                
                                <!-- Quantity Selector -->
                                <div class="quantity-selector">
                                    <label>Quantity:</label>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                                        <input type="number" id="product-quantity" value="1" min="1" max="10" class="quantity-input">
                                        <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="product-modal-actions">
                                <button class="btn buy" onclick="buyNowWithOptions('${productName}', ${price}, '${image}'); closeProductModal();">Buy Now</button>
                                <button class="btn cart" onclick="addToCartWithOptions('${productName}', ${price}, '${image}'); closeProductModal();">
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
                .product-options {
                    margin-bottom: 20px;
                    flex: 1;
                }
                .size-selector, .quantity-selector {
                    margin-bottom: 15px;
                }
                .size-selector label, .quantity-selector label {
                    display: block;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 8px;
                }
                .size-options {
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }
                .size-options input[type="radio"] {
                    display: none;
                }
                .size-option {
                    display: inline-block;
                    padding: 8px 16px;
                    border: 2px solid #ddd;
                    border-radius: 6px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    background: white;
                    font-weight: 500;
                }
                .size-option:hover {
                    border-color: #e74c3c;
                    color: #e74c3c;
                }
                .size-options input[type="radio"]:checked + .size-option {
                    background: #e74c3c;
                    color: white;
                    border-color: #e74c3c;
                }
                .quantity-controls {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .quantity-btn {
                    width: 32px;
                    height: 32px;
                    border: 2px solid #ddd;
                    background: white;
                    border-radius: 6px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    font-size: 16px;
                    transition: all 0.3s ease;
                }
                .quantity-btn:hover {
                    border-color: #e74c3c;
                    color: #e74c3c;
                }
                .quantity-input {
                    width: 60px;
                    height: 32px;
                    text-align: center;
                    border: 2px solid #ddd;
                    border-radius: 6px;
                    font-weight: 600;
                }
                .quantity-input:focus {
                    outline: none;
                    border-color: #e74c3c;
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

        function increaseQuantity() {
            const quantityInput = document.getElementById('product-quantity');
            if (quantityInput) {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue < 10) {
                    quantityInput.value = currentValue + 1;
                }
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('product-quantity');
            if (quantityInput) {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            }
        }

        function getSelectedSize() {
            const sizeInputs = document.querySelectorAll('input[name="size"]');
            for (let input of sizeInputs) {
                if (input.checked) {
                    return input.value;
                }
            }
            return 'S'; // Default size
        }

        function getSelectedQuantity() {
            const quantityInput = document.getElementById('product-quantity');
            return quantityInput ? parseInt(quantityInput.value) : 1;
        }

        function addToCartWithOptions(productName, price, image) {
            const size = getSelectedSize();
            const quantity = getSelectedQuantity();
            
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const form = document.getElementById('add-to-cart-form');
            const syntheticId = Date.now();
            form.setAttribute('action', `${'{{ url('/') }}'}/cart/add/${syntheticId}`);
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrf}">
                <input type="hidden" name="product_name" value="${productName} (Size: ${size})">
                <input type="hidden" name="product_price" value="${price}">
                <input type="hidden" name="product_image" value="${image}">
                <input type="hidden" name="quantity" value="${quantity}">
            `;
            form.submit();
        }

        function buyNowWithOptions(productName, price, image) {
            const size = getSelectedSize();
            const quantity = getSelectedQuantity();
            
            // Add to cart with options and redirect to checkout
            addToCartWithOptions(productName, price, image);
            // Redirect to checkout after a short delay
            setTimeout(() => {
                window.location.href = '{{ route("checkout") }}';
            }, 500);
        }

        function buyNow(productName, price, image) {
            // Add to cart and redirect to checkout
            addToCart(productName, price, image);
            // Redirect to checkout after a short delay
            setTimeout(() => {
                window.location.href = '{{ route("checkout") }}';
            }, 500);
        }

        const voiceToggleBtn=document.getElementById('voice-toggle-btn');
        const voiceChatContainer=document.getElementById('voice-chat-container');
        const voiceCloseChatBtn=document.getElementById('voice-close-chat-btn');
        const voiceMicBtn=document.getElementById('voice-mic-btn');
        const voiceChatBody=document.getElementById('voice-chat-body');
        if(voiceToggleBtn&&voiceChatContainer&&voiceCloseChatBtn){
            voiceToggleBtn.addEventListener('click',()=>{voiceChatContainer.classList.toggle('open')});
            voiceCloseChatBtn.addEventListener('click',()=>{voiceChatContainer.classList.remove('open')});
        }
        if('SpeechRecognition'in window||'webkitSpeechRecognition'in window){
            const SpeechRecognition=window.SpeechRecognition||window.webkitSpeechRecognition;const recognition=new SpeechRecognition();
            recognition.continuous=false;recognition.interimResults=false;recognition.lang='en-US';
            voiceMicBtn.addEventListener('click',()=>{voiceMicBtn.classList.add('listening');recognition.start()});
            recognition.onresult=(event)=>{const transcript=event.results[0][0].transcript;const userMessageDiv=document.createElement('div');userMessageDiv.className='voice-message voice-user-message';userMessageDiv.textContent=transcript;voiceChatBody.appendChild(userMessageDiv);voiceChatBody.scrollTop=voiceChatBody.scrollHeight;setTimeout(()=>{const botResponseDiv=document.createElement('div');botResponseDiv.className='voice-message voice-bot-message';botResponseDiv.textContent=`You said: "${transcript}". This is a simulated AI response.`;voiceChatBody.appendChild(botResponseDiv);voiceChatBody.scrollTop=voiceChatBody.scrollHeight},1000)};
            recognition.onend=()=>{voiceMicBtn.classList.remove('listening')};
            recognition.onerror=(event)=>{console.error('Speech recognition error:',event.error);voiceMicBtn.classList.remove('listening');const errorDiv=document.createElement('div');errorDiv.className='voice-message voice-bot-message';errorDiv.textContent='Sorry, there was an error with voice recognition. Please try again.';voiceChatBody.appendChild(errorDiv)};
        }else{voiceMicBtn.disabled=true;voiceMicBtn.textContent='Not Supported';voiceMicBtn.title='Your browser does not support the Web Speech API.';const fallbackMessage=document.createElement('div');fallbackMessage.className='voice-message voice-bot-message';fallbackMessage.textContent='Voice commands are not supported on this browser. Please use a modern browser like Chrome or Edge.';voiceChatBody.appendChild(fallbackMessage)}
        window.onload=()=>{startHeroSlider&&startHeroSlider()};
        document.addEventListener('DOMContentLoaded',()=>{
            // Login modal functionality
            const loginModal = document.getElementById('loginModal');
            const signupModal = document.getElementById('signupModal');
            const openLoginBtn = document.getElementById('openLogin');
            const openSignupBtn = document.getElementById('openSignup');
            const openLoginFromSignupBtn = document.getElementById('openLoginFromSignup');
            const closeLoginBtn = document.getElementById('closeLogin');
            const closeSignupBtn = document.getElementById('closeSignup');
            const overlay = document.getElementById('overlay');

            // Open login modal
            if (openLoginBtn) {
                openLoginBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    loginModal.style.display = 'flex';
                    setTimeout(() => loginModal.classList.add('show'), 10);
                });
            }

            // Open signup modal
            if (openSignupBtn) {
                openSignupBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    signupModal.style.display = 'flex';
                    setTimeout(() => signupModal.classList.add('show'), 10);
                });
            }

            // Switch from signup to login
            if (openLoginFromSignupBtn) {
                openLoginFromSignupBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    signupModal.classList.remove('show');
                    setTimeout(() => {
                        signupModal.style.display = 'none';
                        loginModal.style.display = 'flex';
                        setTimeout(() => loginModal.classList.add('show'), 10);
                    }, 400);
                });
            }

            // Close modals
            if (closeLoginBtn) {
                closeLoginBtn.addEventListener('click', () => {
                    loginModal.classList.remove('show');
                    setTimeout(() => loginModal.style.display = 'none', 400);
                });
            }

            if (closeSignupBtn) {
                closeSignupBtn.addEventListener('click', () => {
                    signupModal.classList.remove('show');
                    setTimeout(() => signupModal.style.display = 'none', 400);
                });
            }

            // Close on overlay click
            if (overlay) {
                overlay.addEventListener('click', () => {
                    loginModal.classList.remove('show');
                    signupModal.classList.remove('show');
                    setTimeout(() => {
                        loginModal.style.display = 'none';
                        signupModal.style.display = 'none';
                    }, 400);
                });
            }

            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const toggleSignupPassword = document.querySelector('.toggleSignupPassword');
            const loginPassword = document.getElementById('login_password');
            const signupPassword = document.getElementById('register_password');

            if (togglePassword && loginPassword) {
                togglePassword.addEventListener('click', () => {
                    const type = loginPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    loginPassword.setAttribute('type', type);
                    togglePassword.classList.toggle('ri-eye-line');
                    togglePassword.classList.toggle('ri-eye-off-line');
                });
            }

            if (toggleSignupPassword && signupPassword) {
                toggleSignupPassword.addEventListener('click', () => {
                    const type = signupPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    signupPassword.setAttribute('type', type);
                    toggleSignupPassword.classList.toggle('ri-eye-line');
                    toggleSignupPassword.classList.toggle('ri-eye-off-line');
                });
            }
        });
    </script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="{{ asset('ssa/script.js') }}"></script>
</body>
</html>


