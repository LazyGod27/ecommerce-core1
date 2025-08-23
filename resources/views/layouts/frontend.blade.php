<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'iMarket')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    @yield('styles')
</head>
<body class="bg-[#e6e6fa] text-[#353c61] min-w-[320px] min-h-screen flex flex-col">
    <header class="header flex items-center justify-between p-4 bg-[#353c61] text-white text-sm sticky top-0 z-20 flex-wrap">
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="logo p-2 rounded-md hover:border-white border border-transparent">
                <span class="font-bold text-lg">iMarket</span>
            </a>
            <div class="hidden sm:flex items-end gap-1 p-2 rounded-md cursor-pointer hover:border-white border border-transparent">
                <i class="fas fa-map-marker-alt text-xl"></i>
                <a href="{{ route('tracking') }}" class="flex flex-col leading-none">
                    <span class="text-xs">Track Your Order(s)</span>
                </a>
            </div>
        </div>
        
        <div class="search-bar flex flex-grow h-10 rounded-md overflow-hidden mx-2 sm:mx-4 mt-2 sm:mt-0 order-3 sm:order-none focus-within:ring-2 focus-within:ring-[#4bc5ec]">
            <div class="search-dropdown bg-[#bdccdc] text-[#353c61] px-2 flex items-center gap-1 border-r border-gray-300 cursor-pointer text-xs">
                <span>All</span>
                <i class="fas fa-caret-down text-gray-500"></i>
            </div>
            <form action="{{ route('products.search') }}" method="GET" class="flex flex-grow">
                <input type="text" name="q" placeholder="Search iMarket" class="w-full px-3 text-base outline-none border-none" value="{{ request('q') }}">
                <button type="submit" class="search-button bg-[#4bc5ec] text-[#2c3c8c] px-4 cursor-pointer text-lg hover:bg-[#2c3c8c] hover:text-white transition-colors duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="header-right flex items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
            <div class="language-selector hidden sm:flex items-end gap-1 p-2 rounded-md cursor-pointer hover:border-white border border-transparent">
                <i class="fas fa-globe text-lg"></i>
                <span class="font-bold">EN</span>
                <i class="fas fa-caret-down text-sm"></i>
            </div>
            <div class="account-lists p-2 rounded-md cursor-pointer hover:border-white border border-transparent whitespace-nowrap">
                @auth
                    <a href="{{ route('account') }}">
                        <span class="text-xs">Welcome</span>
                        <br>
                        <span class="font-bold">{{ auth()->user()->name }} <i class="fas fa-caret-down text-sm"></i></span>
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <span class="text-xs">Sign in</span>
                        <br>
                        <span class="font-bold">Account <i class="fas fa-caret-down text-sm"></i></span>
                    </a>
                @endauth
            </div>
            <div class="returns-orders hidden sm:block p-2 rounded-md cursor-pointer hover:border-white border border-transparent whitespace-nowrap">
                <a href="#">
                    <span class="text-xs">Returns</span>
                    <br>
                    <span class="font-bold">& Orders</span>
                </a>
            </div>
            <div class="cart flex items-end gap-1 font-bold p-2 rounded-md cursor-pointer hover:border-white border border-transparent">
                <a href="{{ route('cart') }}" class="flex items-center gap-1">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                    <span class="text-xs sm:text-base">Cart</span>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">{{ count(session('cart')) }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>
    
    <nav class="sub-nav bg-[#2c3c8c] text-white p-2 sm:px-4 text-sm flex items-center z-10">
        <ul class="flex flex-wrap gap-x-4 sm:gap-x-6 list-none m-0 p-0">
            <li><a href="#" class="nav-item text-white p-1 rounded-md border border-transparent hover:border-white transition-all duration-100 ease-in-out">Feedback</a></li>
            <li><a href="{{ route('customer-service') }}" class="nav-item text-white p-1 rounded-md border border-transparent hover:border-white transition-all duration-100 ease-in-out">Customer Service</a></li>
            <li><a href="#" class="nav-item text-white p-1 rounded-md border border-transparent hover:border-white transition-all duration-100 ease-in-out">Sell</a></li>
        </ul>
    </nav>
    
    <main class="main-content max-w-7xl mx-auto p-5 flex flex-col gap-5 flex-grow">
        @yield('content')
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
