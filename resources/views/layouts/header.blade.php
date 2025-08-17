<header class="bg-blue-darker text-white shadow-md">
    <div class="container mx-auto flex items-center justify-between p-4 flex-wrap">
        <div class="flex items-center space-x-6">
            <a href="{{ route('home') }}" class="text-2xl font-bold">iMarket</a>
            <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-blue-dark text-white font-bold rounded-full shadow-lg hover:bg-blue-darker transition-colors duration-300">
                <i class="fas fa-home mr-2"></i> Home
            </a>
            <a href="{{ route('tracking') }}" class="flex items-center text-sm hover:text-gray-300 transition-colors duration-300">
                <i class="fas fa-map-marker-alt mr-2"></i> Track Your Order(s)
            </a>
        </div>
        
        <div class="flex-grow max-w-xl mx-4 hidden md:flex items-stretch mt-2 md:mt-0">
            <select class="bg-gray-200 text-gray-800 rounded-l-md px-4 text-sm focus:outline-none">
                <option>All</option>
            </select>
            <input type="text" placeholder="Search iMarket" class="flex-grow p-2 text-sm text-gray-800 focus:outline-none">
            <button class="bg-blue-light text-blue-dark p-2 rounded-r-md hover:bg-gray-blue hover:text-white transition-colors duration-300">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="flex items-center space-x-6 mt-2 md:mt-0">
            @auth
                <a href="#" class="text-sm hidden md:block">
                    {{ Auth::user()->name }}
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm hidden md:block">
                    Sign in
                    <div class="text-xs text-gray-400">Account <i class="fas fa-chevron-down ml-1 text-xs"></i></div>
                </a>
            @endauth
            <a href="#" class="text-sm hidden md:block">
                Returns
                <div class="text-xs text-gray-400">& Orders</div>
            </a>
            <a href="{{ route('cart') }}" class="flex items-center text-sm font-bold">
                <i class="fas fa-shopping-cart text-xl mr-2"></i> Cart
                @if(Cart::count() > 0)
                    <span class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">
                        {{ Cart::count() }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <nav class="bg-blue-dark text-white p-2 sm:px-4 text-sm z-10">
        <div class="container mx-auto flex justify-start space-x-6">
            <a href="#" class="hover:text-gray-300 transition-colors duration-300">Today's Deals</a>
            <a href="#" class="hover:text-gray-300 transition-colors duration-300">Feedback</a>
            <a href="{{ route('customer-service') }}" class="hover:text-gray-300 transition-colors duration-300">Customer Service</a>
            <a href="#" class="hover:text-gray-300 transition-colors duration-300">Sell</a>
        </div>
    </nav>
</header>