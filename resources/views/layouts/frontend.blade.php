<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'iMarket')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <style>
        /* Scoped layout polish */
        body { background: #f7f8fc; color: #353c61; }
        header { position: sticky; top: 0; z-index: 50; background: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,.06); padding: 10px 16px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .logo img { height: 34px; width: auto; display: block; }
        .navbar { list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 14px; flex: 1 1 auto; }
        .navbar > li { position: relative; }
        .navbar a { color: #1f2937; text-decoration: none; font-weight: 500; padding: 6px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; }
        .navbar a.active, .navbar a:hover { background: #f2f5fb; color: #111827; }
        .navbar .dropdown:hover .dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .navbar .dropdown-menu { position: absolute; top: 100%; left: 0; min-width: 220px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.08); padding: 8px 0; opacity: 0; visibility: hidden; transform: translateY(6px); transition: all .18s ease; z-index: 40; }
        .navbar .dropdown-menu a { display: flex; padding: 8px 12px; color: #374151; }
        .navbar .dropdown-menu a:hover { background: #f9fafb; color: #111827; }
        .search-bar { flex: 1 1 420px; max-width: 640px; margin: 0 12px; display: flex; align-items: center; gap: 8px; }
        .search-bar input { flex: 1; height: 38px; border: 1px solid #e5e7eb; border-radius: 10px; padding: 0 12px; outline: none; }
        .search-bar .search-btn, .search-bar button[type="submit"] { height: 38px; padding: 0 12px; background: #4bc5ec; color: #0b2857; border: none; border-radius: 10px; cursor: pointer; }
        .icons { display: flex; align-items: center; gap: 12px; margin-left: auto; }
        .icons a { color: #1f2937; text-decoration: none; font-size: 20px; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; }
        .icons a:hover { background: #f2f5fb; }
        .user-dropdown { position: relative; }
        .user-dropdown-menu { position: absolute; right: 0; top: 100%; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.08); min-width: 220px; padding: 8px 0; opacity: 0; visibility: hidden; transform: translateY(6px); transition: all .18s ease; z-index: 40; }
        .user-dropdown:hover .user-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .user-dropdown-menu a { display: flex; align-items: center; gap: 8px; padding: 8px 12px; color: #374151; text-decoration: none; }
        .user-dropdown-menu a:hover { background: #f9fafb; color: #111827; }
        .page-container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 16px; }
        .footer { background: #0b4d70; color: #e5f3fb; margin-top: 24px; }
        .footer .footer-content { max-width: 1200px; margin: 0 auto; padding: 24px 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .footer .footer-bottom { text-align: center; padding: 12px; background: rgba(0,0,0,.08); }
        @media (max-width: 640px) {
            .page-container { padding: 12px; }
            .search-bar { flex: 1 1 100%; order: 3; }
        }
    </style>
    @yield('styles')
</head>
<body class="min-w-[320px] min-h-screen flex flex-col">
    <header>
        <div class="logo">
            <img src="{{ asset('ssa/logo.png') }}" alt="IMARKET PH Logo">
        </div>
        <ul class="navbar" id="navbar">
            <li><a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i class="ri-home-line"></i> Home</a></li>
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
            <form action="{{ route('search') }}" method="GET" class="flex flex-grow relative" id="searchForm">
                <input type="text" 
                       name="q" 
                       id="searchInput"
                       placeholder="Search for products, brands and more..." 
                       class="w-full px-3 text-base outline-none border-none" 
                       value="{{ request('q') }}" 
                       autocomplete="off" />
                <button type="submit" class="search-btn"><i class="ri-search-line"></i></button>
                <div id="searchSuggestions" class="search-suggestions hidden" style="display: none;"></div>
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
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-layout').submit();"><i class="ri-logout-box-line"></i> Logout</a>
                    </div>
                </div>
                <form id="logout-form-layout" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}"><i class="ri-user-line"></i></a>
            @endauth
            <div class="bx bx-menu" id="menu-icon"></div>
        </div>
    </header>
    
    <main class="flex-grow">
        <div class="page-container">
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Get to Know Us</h3>
                <ul>
                    <li><a href="#">About iMarket</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Press Releases</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Make Money with Us</h3>
                <ul>
                    <li><a href="#">Sell products</a></li>
                    <li><a href="#">Become an Affiliate</a></li>
                    <li><a href="#">Advertise Your Products</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Payment Products</h3>
                <ul>
                    <li><a href="#">Business Card</a></li>
                    <li><a href="#">Shop with Points</a></li>
                    <li><a href="#">Reload Your Balance</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Let Us Help You</h3>
                <ul>
                    <li><a href="{{ route('customer-service') }}">Customer Service</a></li>
                    <li><a href="{{ route('tracking') }}">Track Your Order</a></li>
                    <li><a href="#">Returns & Replacements</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 iMarket. All rights reserved.</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
