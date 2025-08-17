@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<main class="flex-grow container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Checkout</h1>
    
    <div class="lg:grid lg:grid-cols-3 lg:gap-8 max-w-5xl mx-auto">
        <!-- Left Column: Shopping Cart Items -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6 h-fit border border-gray-200 mb-8 lg:mb-0">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Shopping Cart</h2>
                <i class="fas fa-shopping-cart text-gray-600 text-2xl"></i>
            </div>
            <div id="cart-items" class="space-y-4">
                @if(Cart::count() > 0)
                    @foreach(Cart::content() as $item)
                    <div class="flex items-center justify-between border-b pb-4 last:border-b-0 last:pb-0">
                        <img src="{{ asset('storage/' . $item->model->image) }}" alt="{{ $item->model->name }}" class="w-16 h-16 rounded-md object-cover mr-4">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $item->model->name }}</p>
                            <p class="text-sm text-gray-500">₱{{ $item->model->price }} x {{ $item->qty }}</p>
                        </div>
                        <form action="{{ route('cart.remove', $item->rowId) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-300 font-semibold text-sm">
                                Remove
                            </button>
                        </form>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center">Your cart is empty.</p>
                @endif
            </div>
        </div>
        
        <!-- Right Column: Order Summary and Payment Methods -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6 h-fit border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Order Summary</h2>
                <i class="fas fa-receipt text-gray-600 text-2xl"></i>
            </div>

            <div id="checkout-items-summary" class="max-h-60 overflow-y-auto mb-4 border-b pb-4">
                @if(Cart::count() > 0)
                    @foreach(Cart::content() as $item)
                    <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                        <div class="flex items-center space-x-4">
                            <p class="font-medium text-gray-900">{{ $item->model->name }}</p>
                        </div>
                        <span class="font-semibold text-gray-800">₱{{ $item->subtotal }}</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center">Your cart is empty.</p>
                @endif
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Select Payment Method</h3>
                <div id="payment-methods-container" class="flex flex-col space-y-2">
                    <button class="payment-method-btn w-full px-4 py-2 rounded-md border border-gray-300 text-gray-800 hover:bg-gray-200 transition-colors duration-200 text-left" data-method="gcash">
                        GCash
                    </button>
                    <button class="payment-method-btn w-full px-4 py-2 rounded-md border border-gray-300 text-gray-800 hover:bg-gray-200 transition-colors duration-200 text-left" data-method="cod">
                        Cash on Delivery (COD)
                    </button>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t-2 border-gray-200">
                <div class="flex justify-between items-center font-bold text-xl text-gray-800">
                    <span>Total:</span>
                    <span id="cart-total">₱{{ Cart::total() }}</span>
                </div>
                <button id="place-order-button" class="mt-4 w-full bg-blue-dark text-white font-semibold p-3 rounded-md hover:bg-blue-darker transition-colors duration-300">
                    Place Order
                </button>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodBtns = document.querySelectorAll('.payment-method-btn');
        const placeOrderBtn = document.getElementById('place-order-button');
        let selectedMethod = null;

        paymentMethodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                paymentMethodBtns.forEach(b => {
                    b.classList.remove('bg-blue-dark', 'text-white', 'hover:bg-blue-darker');
                    b.classList.add('bg-white', 'text-gray-800', 'hover:bg-gray-200');
                });
                
                // Add active class to clicked button
                this.classList.remove('bg-white', 'text-gray-800', 'hover:bg-gray-200');
                this.classList.add('bg-blue-dark', 'text-white', 'hover:bg-blue-darker');
                
                selectedMethod = this.dataset.method;
            });
        });

        placeOrderBtn.addEventListener('click', function() {
            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }

            // Submit form based on selected method
            if (selectedMethod === 'gcash') {
                // Redirect to GCash payment
                window.location.href = "{{ route('payment.gcash') }}";
            } else {
                // Submit COD order
                document.getElementById('checkout-form').submit();
            }
        });
    });
</script>
@endpush