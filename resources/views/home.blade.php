@extends('layouts.app')

@section('title', 'Home')

@section('content')
<main class="main-content max-w-7xl mx-auto p-5 flex flex-col gap-5 flex-grow">
    <section class="product-grid-section grid gap-5 justify-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        
        @foreach($categories as $category)
        <div class="product-card bg-[#add8e6] p-5 rounded-lg shadow-md flex flex-col items-start">
            <h2 class="text-xl font-bold mb-4 text-[#353c61]">{{ $category->name }}</h2>
            <div class="card-grid grid grid-cols-2 gap-4 w-full mb-4">
                @foreach($category->products->take(4) as $product)
                <div class="card-item text-center">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-24 rounded-md mb-2">
                    <p class="text-xs text-gray-500">{{ $product->name }}</p>
                </div>
                @endforeach
            </div>
            <a href="{{ route('category.show', $category->slug) }}" class="text-xs text-[#2c3c8c] font-bold hover:text-[#4bc5ec] hover:underline">See All</a>
        </div>
        @endforeach

    </section>
</main>
@endsection