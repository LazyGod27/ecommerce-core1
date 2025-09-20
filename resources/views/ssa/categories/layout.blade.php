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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>@yield('title', 'iMarket PH')</title>
    <style>
        .image-scan-fab {
            position: fixed;
            bottom: 20px;
            left: 20px; 
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #007bff;
            color: #fff;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 999;
            transition: transform 0.3s ease;
        }
        .image-scan-fab:hover {
            transform: scale(1.1);
        }
        .voice-toggle-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 180px;
            height: 50px;
            border-radius: 50px;
            background-color: #007bff;
            color: #000000;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .voice-toggle-btn:hover {
            transform: scale(1.05);
        }
        .voice-chat-container {
            position: fixed;
            bottom: -100%;
            right: 20px;
            width: 350px;
            height: 450px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            z-index: 1001;
            transition: bottom 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }
        .voice-chat-container.open {
            bottom: 90px;
        }
        .voice-chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: var(--primary-color);
            color: #00008a;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .voice-chat-close-btn {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 1.5rem;
            cursor: pointer;
            line-height: 1;
        }
        .voice-chat-body {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #4f549c;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .voice-message {
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 80%;
            word-wrap: break-word;
        }
        .voice-bot-message {
            background-color: #e0e0e0;
            align-self: flex-start;
        }
        .voice-user-message {
            background-color: var(--primary-color);
            color: #fff;
            align-self: flex-end;
        }
        .voice-chat-input-area {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            border-top: 1px solid #000;
        }
        #voice-mic-btn {
            background-color: rgb(125, 125, 241);
            color: #000;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        #voice-mic-btn.listening {
            background-color: #dc3545;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
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
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('ssa/logo.png') }}" alt="IMARKET PH Logo">
        </div>
        <ul class="navbar" id="navbar">
            <li><a href="{{ route('home') }}"><i class="ri-home-line"></i> Home</a></li>
            <li><a href="#"><i class="ri-store-line"></i> Mall</a></li>
            <li><a href="#"><i class="ri-percent-line"></i> Flash Deals</a></li>
            <li class="dropdown">
                <a href="#"><i class="ri-list-unordered"></i> Categories <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('categories.best') }}"><i class="ri-fire-line"></i> Best Selling</a></li>
                    <li><a href="{{ route('categories.new') }}"><i class="ri-star-smile-line"></i> New Arrivals</a></li>
                    <li><a href="{{ route('categories.electronics') }}"><i class="ri-computer-line"></i> Electronics</a></li>
                    <li><a href="{{ route('categories.fashion') }}"><i class="ri-t-shirt-line"></i> Fashion & Apparel</a></li>
                    <li><a href="{{ route('categories.home') }}"><i class="ri-home-4-line"></i> Home & Living</a></li>
                    <li><a href="{{ route('categories.beauty') }}"><i class="ri-heart-line"></i> Beauty & Health</a></li>
                    <li><a href="{{ route('categories.sports') }}"><i class="ri-football-line"></i> Sports & Outdoor</a></li>
                    <li><a href="{{ route('categories.toys') }}"><i class="ri-gamepad-line"></i> Toys & Games</a></li>
                    <li><a href="{{ route('categories.groceries') }}"><i class="ri-shopping-basket-line"></i> Groceries</a></li>
                </ul>
            </li>
        </ul>
        <div class="search-bar">
            <form action="{{ route('search') }}" method="GET" class="flex flex-grow relative" id="searchForm">
                <input type="text" 
                       name="q" 
                       id="searchInput"
                       placeholder="Search for products, brands and more..." 
                       class="w-full px-3 text-base outline-none border-none" 
                       value="{{ request('q') }}" 
                       autocomplete="off" />
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
                        <a href="{{ route('track-order') }}"><i class="ri-truck-line"></i> Track Orders</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ri-logout-box-line"></i> Logout</a>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}"><i class="ri-user-line"></i></a>
            @endauth
            <div class="bx bx-menu" id="menu-icon"></div>
        </div>
    </header>

    @yield('content')
    
    <script>
        // Hero slides functionality
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
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
        });
    </script>

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
    
    @yield('scripts')
    
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="{{ asset('ssa/script.js') }}"></script>
</body>
</html>
