<header>
    <div class="logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('ssa/logo.png') }}" alt="IMARKET PH Logo">
        </a>
    </div>
    <ul class="navbar" id="navbar">
        <li><a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i class="ri-home-line"></i> Home</li>
        <li><a href="{{ route('store') }}" class="{{ request()->is('store') ? 'active' : '' }}"><i class="ri-store-line"></i> Mall</a></li>
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
    @include('components.search-bar')
    <div class="icons">
        <a href="#" onclick="goToCart()"><i class="ri-shopping-cart-line"></i></a>
        @auth
            <div class="user-dropdown">
                <a href="{{ route('profile.index') }}" class="user-link">
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
