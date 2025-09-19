@extends('layouts.frontend')

@section('title', 'iMarket - Search Results')

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
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-search"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">No products found</h2>
            <p class="text-gray-600 mb-6">We couldn't find any products matching your search.</p>
            <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-200">
                Continue Shopping
            </a>
        </div>
    @endif
</div>
@endsection
