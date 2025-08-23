@extends('layouts.frontend')

@section('title', 'iMarket - Search Results')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Search Results</h1>
        <p class="text-gray-600">Showing results for: "<span class="font-semibold">{{ $query }}</span>"</p>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <img src="{{ asset('storage/' . ($product->image ?? 'default.jpg')) }}" 
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
