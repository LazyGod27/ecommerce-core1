@extends('layouts.frontend')

@section('title', 'iMarket - My Account')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">My Account</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Information</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Name</label>
                        <p class="text-gray-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-800">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Member Since</label>
                        <p class="text-gray-800">{{ auth()->user()->created_at->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('tracking') }}" class="block w-full text-left p-3 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors duration-200">
                        <i class="fas fa-truck mr-2"></i>
                        Track My Orders
                    </a>
                    <a href="#" onclick="goToCart()" class="block w-full text-left p-3 bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition-colors duration-200">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        View Cart
                    </a>
                    <a href="{{ route('customer-service') }}" class="block w-full text-left p-3 bg-purple-50 text-purple-700 rounded-md hover:bg-purple-100 transition-colors duration-200">
                        <i class="fas fa-headset mr-2"></i>
                        Customer Service
                    </a>
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Actions</h2>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
