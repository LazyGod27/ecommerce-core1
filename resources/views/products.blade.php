@extends('layouts.frontend')

@section('title', 'Products - iMarket')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">All Products</h1>
        <p class="text-gray-600">Browse our complete product catalog</p>
    </div>
    
    <div id="app">
        <product-search></product-search>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
@endpush