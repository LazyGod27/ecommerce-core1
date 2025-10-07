@extends('layouts.frontend')

@section('title', 'iMarket - Search Results')

@section('styles')
<style>
    /* Custom animations for no products found page */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    .bounce-animation {
        animation: bounce 2s infinite;
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .search-tip-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .search-tip-card:hover {
        border-left-color: #3b82f6;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        transform: translateX(8px);
    }
    
    .category-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }
    
    .category-card:hover::before {
        left: 100%;
    }
    
    .trending-tag {
        position: relative;
        overflow: hidden;
    }
    
    .trending-tag::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s;
    }
    
    .trending-tag:hover::before {
        left: 100%;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Search Results</h1>
        <p class="text-gray-600">Showing results for: "<span class="font-semibold">{{ $query }}</span>"</p>
        
        @if(isset($categorySuggestions) && count($categorySuggestions) > 0)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">You might also be interested in:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categorySuggestions as $category)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center mb-2">
                        <i class="{{ $category['icon'] }} text-2xl text-blue-600 mr-3"></i>
                        <h4 class="text-lg font-semibold text-gray-800">{{ $category['title'] }}</h4>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($category['suggestions'] as $suggestion)
                        <a href="{{ route('search', ['q' => $suggestion]) }}" 
                           class="bg-white text-blue-600 px-3 py-1 rounded-full text-sm hover:bg-blue-100 transition-colors duration-200">
                            {{ $suggestion }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <img src="{{ asset($product->image ?? 'ssa/default.jpg') }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 100) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold text-blue-600">₱{{ number_format($product->price, 2) }}</span>
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <!-- Enhanced No Products Found Section -->
        <div class="max-w-4xl mx-auto">
            <!-- Main No Results Card -->
            <div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 rounded-2xl shadow-xl border border-blue-100 p-8 mb-8 hover-lift">
                <div class="text-center">
                    <!-- Animated Search Icon -->
                    <div class="relative mb-6">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-4 float-animation">
                            <i class="ri-search-line text-4xl text-blue-600"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center bounce-animation">
                            <i class="ri-close-line text-red-500 text-lg"></i>
                        </div>
                    </div>
                    
                    <!-- Main Message -->
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">Oops! No products found</h2>
                    <p class="text-lg text-gray-600 mb-2">We couldn't find any products matching</p>
                    <p class="text-xl font-semibold text-blue-600 mb-6">"{{ $query }}"</p>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                        <a href="{{ route('home') }}" 
                           class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-full hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="ri-home-line mr-2"></i>Continue Shopping
                        </a>
                        <button onclick="document.getElementById('searchInput').focus()" 
                                class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-full hover:bg-blue-50 transition-all duration-300 transform hover:scale-105">
                            <i class="ri-search-line mr-2"></i>Try Different Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Tips Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="ri-lightbulb-line text-yellow-500 mr-2"></i>
                    Search Tips
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start space-x-3 search-tip-card p-3 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-bold text-sm">1</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Check your spelling</h4>
                            <p class="text-gray-600 text-sm">Make sure all words are spelled correctly</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3 search-tip-card p-3 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-bold text-sm">2</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Try different keywords</h4>
                            <p class="text-gray-600 text-sm">Use more general or different terms</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3 search-tip-card p-3 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-bold text-sm">3</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Use fewer keywords</h4>
                            <p class="text-gray-600 text-sm">Try searching with just one or two words</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3 search-tip-card p-3 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-bold text-sm">4</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Browse categories</h4>
                            <p class="text-gray-600 text-sm">Explore our product categories below</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Categories Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="ri-fire-line text-orange-500 mr-2"></i>
                    Popular Categories
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('categories.electronics') }}" 
                       class="group category-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-smartphone-line text-3xl text-blue-600 mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                        <h4 class="font-semibold text-gray-800">Electronics</h4>
                    </a>
                    <a href="{{ route('categories.fashion') }}" 
                       class="group category-card bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4 text-center hover:from-pink-100 hover:to-pink-200 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-shirt-line text-3xl text-pink-600 mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                        <h4 class="font-semibold text-gray-800">Fashion</h4>
                    </a>
                    <a href="{{ route('categories.home') }}" 
                       class="group category-card bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 text-center hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-home-line text-3xl text-green-600 mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                        <h4 class="font-semibold text-gray-800">Home & Living</h4>
                    </a>
                    <a href="{{ route('categories.beauty') }}" 
                       class="group category-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 text-center hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-heart-line text-3xl text-purple-600 mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                        <h4 class="font-semibold text-gray-800">Beauty & Health</h4>
                    </a>
                </div>
            </div>

            <!-- Trending Searches Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="ri-trending-up-line text-green-500 mr-2"></i>
                    Trending Searches
                </h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('search', ['q' => 'gaming mouse']) }}" 
                       class="trending-tag bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-4 py-2 rounded-full hover:from-blue-100 hover:to-indigo-100 hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-mouse-line mr-1"></i>Gaming Mouse
                    </a>
                    <a href="{{ route('search', ['q' => 'wireless headphones']) }}" 
                       class="trending-tag bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-4 py-2 rounded-full hover:from-blue-100 hover:to-indigo-100 hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-headphone-line mr-1"></i>Wireless Headphones
                    </a>
                    <a href="{{ route('search', ['q' => 'smartphone case']) }}" 
                       class="trending-tag bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-4 py-2 rounded-full hover:from-blue-100 hover:to-indigo-100 hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-smartphone-line mr-1"></i>Smartphone Case
                    </a>
                    <a href="{{ route('search', ['q' => 'running shoes']) }}" 
                       class="trending-tag bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-4 py-2 rounded-full hover:from-blue-100 hover:to-indigo-100 hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-run-line mr-1"></i>Running Shoes
                    </a>
                    <a href="{{ route('search', ['q' => 'laptop bag']) }}" 
                       class="trending-tag bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-4 py-2 rounded-full hover:from-blue-100 hover:to-indigo-100 hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-suitcase-line mr-1"></i>Laptop Bag
                    </a>
                    <a href="{{ route('search', ['q' => 'makeup set']) }}" 
                       class="trending-tag bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-4 py-2 rounded-full hover:from-blue-100 hover:to-indigo-100 hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                        <i class="ri-makeup-line mr-1"></i>Makeup Set
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
