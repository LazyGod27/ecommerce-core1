@extends('layouts.frontend')

@section('title', 'iMarket - Checkout')

@section('styles')
<style>
    :root {
        --blue-dark: #2c3c8c;
        --blue-darker: #353c61;
        --blue-light: #4bc5ec;
        --gray-blue: #5c6c9c;
        --light-gray-blue: #bdcodc;
        --gray-light: #94dcf4;
    }
    .bg-blue-dark { background-color: var(--blue-dark); }
    .bg-blue-darker { background-color: var(--blue-darker); }
    .bg-blue-light { background-color: var(--blue-light); }
    .text-blue-dark { color: var(--blue-dark); }
    .text-blue-light { color: var(--blue-light); }
    .text-gray-blue { color: var(--gray-blue); }
    .border-gray-blue { border-color: var(--gray-blue); }
    .hover\:bg-gray-blue:hover { background-color: var(--gray-blue); }
    .header-bg { background-color: #353c61; }
    .header-bottom-bg { background-color: #2c3c8c; }
    .home-button { background-color: #2c3c8c; }
    .search-button { background-color: #4bc5ec; }
    #message-box {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
    }
    #message-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card.selected {
        border: 3px solid #10b981; 
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
        background-color: #ecfdf5; 
    }
    @media (min-width: 1024px) {
        .lg\:landscape-flex {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
        }
        .lg\:landscape-flex-1 {
            flex: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Checkout</h1>
    <div class="lg:flex lg:space-x-8">
        <div class="flex-1">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Your Cart</h2>
                <div id="cart-items" class="space-y-4">
                    @if(session('cart') && count(session('cart')) > 0)
                        @foreach(session('cart') as $item)
                        <div class="flex items-center space-x-4 border-b pb-4">
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-md object-cover">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $item['name'] }}</h3>
                                <p class="text-gray-600">₱{{ number_format($item['price'], 2) }}</p>
                            </div>
                            <form action="{{ route('cart.remove', $item['rowId']) }}" method="POST" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    @else
                        <p class="text-center text-gray-500">Your cart is empty.</p>
                    @endif
                </div>
            </div>
            

            <div class="bg-white rounded-lg shadow-md p-6 mb-8 lg:landscape-flex-1">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Voucher Code</h2>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <input type="text" id="voucher-input" placeholder="Enter voucher code" class="flex-1 p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-light">
                    <button id="apply-voucher-btn" class="bg-blue-dark hover:bg-blue-darker text-white font-bold py-3 px-6 rounded-md transition-colors duration-200">
                        Apply Voucher
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Choose a Payment Method</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    <div id="gcash" class="card p-6 border-2 border-gray-200 rounded-xl cursor-pointer flex flex-col items-center text-center hover:bg-gray-50" data-method="GCash">
                        <img src="https://logodix.com/logo/2206207.png" class="w-12 h-12 text-green-600 mb-2" alt="GCash logo">
                        <h3 class="text-xl font-semibold text-gray-800">GCash</h3>
                        <p class="text-sm text-gray-500">E-wallet</p>
                    </div>
                    <div id="paymaya" class="card p-6 border-2 border-gray-200 rounded-xl cursor-pointer flex flex-col items-center text-center hover:bg-gray-50" data-method="PayMaya">
                        <img src="https://business.inquirer.net/wp-content/blogs.dir/5/files/2020/11/PayMaya-Logo_Vertical.png" class="w-12 h-12 text-pink-600 mb-2" alt="PayMaya logo">
                        <h3 class="text-xl font-semibold text-gray-800">PayMaya</h3>
                        <p class="text-sm text-gray-500">E-wallet</p>
                    </div>
                    <div id="card" class="card p-6 border-2 border-gray-200 rounded-xl cursor-pointer flex flex-col items-center text-center hover:bg-gray-50" data-method="Card">
                        <img src="https://up.yimg.com/ib/th/id/OIP.Tm8I5fC59eVVStXCIObmMQHaE1?pid=Api&rs=1&c=1&qlt=95&w=106&h=69" class="w-12 h-12 text-purple-600 mb-2" alt="Credit/Debit card icon">
                        <h3 class="text-xl font-semibold text-gray-800">Card</h3>
                        <p class="text-sm text-gray-500">Credit/Debit Card</p>
                    </div>
                    <div id="cod" class="card p-6 border-2 border-gray-200 rounded-xl cursor-pointer flex flex-col items-center text-center hover:bg-gray-50" data-method="Cash on Delivery">
                        <img src="https://static.vecteezy.com/system/resources/previews/006/566/274/non_2x/cash-on-delivery-icon-design-illustration-in-flat-design-free-vector.jpg" class="w-12 h-12 text-green-600 mb-2" alt="Cash on Delivery icon">
                        <h3 class="text-xl font-semibold text-gray-800">COD</h3>
                        <p class="text-sm text-gray-500">Cash on Delivery</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="lg:w-1/3 mt-8 lg:mt-0">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Order Summary</h2>
                <div class="space-y-2">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span id="subtotal">₱{{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-red-600 font-semibold" id="discount-row">
                        <span>Discount:</span>
                        <span id="discount-amount">-₱0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Shipping:</span>
                        <span id="shipping-cost">₱50.00</span>
                    </div>
                    <hr class="my-2 border-gray-200">
                    <div class="flex justify-between font-bold text-lg text-gray-800">
                        <span>Total:</span>
                        <span id="total">₱{{ number_format(($subtotal ?? 0) + 50, 2) }}</span>
                    </div>
                </div>
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    <input type="hidden" name="payment_method" id="payment_method">
                    <button type="submit" id="place-order-btn" class="mt-6 w-full bg-blue-dark hover:bg-blue-darker text-white font-bold py-3 px-4 rounded-md transition-colors duration-200" disabled>
                        Place Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="message-backdrop" class="hidden"></div>
<div id="message-box" class="hidden bg-white p-8 rounded-lg shadow-lg max-w-sm text-center">
    <p id="message-text" class="text-lg font-semibold text-gray-800 mb-4"></p>
    <button id="close-message-btn" class="bg-blue-dark hover:bg-blue-darker text-white font-bold py-2 px-4 rounded-md">OK</button>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const subtotalElement = document.getElementById('subtotal');
        const shippingCostElement = document.getElementById('shipping-cost');
        const discountAmountElement = document.getElementById('discount-amount');
        const totalElement = document.getElementById('total');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const messageBox = document.getElementById('message-box');
        const messageBackdrop = document.getElementById('message-backdrop');
        const messageText = document.getElementById('message-text');
        const closeMessageBtn = document.getElementById('close-message-btn');
        const voucherInput = document.getElementById('voucher-input');
        const applyVoucherBtn = document.getElementById('apply-voucher-btn');
        const paymentCards = document.querySelectorAll('.card');
        const paymentMethodInput = document.getElementById('payment_method');

        let selectedMethod = null;
        let appliedDiscount = 0;
        let isVoucherApplied = false;

        const vouchers = {
            'IMARKET10': { type: 'percent', value: 0.10, minPurchase: 100 },
            'FREESHIP': { type: 'shipping', value: 50.00, minPurchase: 500 },
            'SAVE50': { type: 'fixed', value: 50.00, minPurchase: 250 }
        };

        const shippingCost = 50.00;
        const subtotal = {{ $subtotal ?? 0 }};

        function showMessage(text, callback) {
            messageText.textContent = text;
            messageBox.classList.remove('hidden');
            messageBackdrop.classList.remove('hidden');
            closeMessageBtn.onclick = () => {
                messageBox.classList.add('hidden');
                messageBackdrop.classList.add('hidden');
                if (callback) callback();
            };
        }

        function updateOrderSummary(subtotal, discount) {
            let currentShippingCost = shippingCost;
            if (isVoucherApplied && voucherInput.value.toUpperCase() === 'FREESHIP') {
                currentShippingCost = 0;
            }

            const total = subtotal - discount + currentShippingCost;
            subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
            discountAmountElement.textContent = `-₱${discount.toFixed(2)}`;
            shippingCostElement.textContent = `₱${currentShippingCost.toFixed(2)}`;
            totalElement.textContent = `₱${total.toFixed(2)}`;
        }

        applyVoucherBtn.addEventListener('click', () => {
            if (subtotal === 0) {
                showMessage('Your cart is empty. Cannot apply a voucher.');
                return;
            }
            if (isVoucherApplied) {
                showMessage('A voucher has already been applied.');
                return;
            }

            const voucherCode = voucherInput.value.toUpperCase();
            const voucher = vouchers[voucherCode];

            if (!voucher) {
                showMessage('Invalid voucher code. Please try again.');
                voucherInput.value = '';
                return;
            }

            if (subtotal < voucher.minPurchase) {
                showMessage(`This voucher requires a minimum purchase of ₱${voucher.minPurchase.toFixed(2)}.`);
                return;
            }

            let discount = 0;
            if (voucher.type === 'percent') {
                discount = subtotal * voucher.value;
            } else if (voucher.type === 'fixed') {
                discount = voucher.value;
            } else if (voucher.type === 'shipping') {
                discount = 0;
            }
            
            appliedDiscount = discount;
            isVoucherApplied = true;
            updateOrderSummary(subtotal, appliedDiscount);
            showMessage(`Voucher "${voucherCode}" applied successfully!`);
        });

        paymentCards.forEach(card => {
            card.addEventListener('click', () => {
                paymentCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');

                selectedMethod = card.dataset.method;
                paymentMethodInput.value = selectedMethod;
                placeOrderBtn.disabled = false;
            });
        });
        
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            if (!selectedMethod) {
                e.preventDefault();
                showMessage('Please select a payment method.');
                return;
            }
        });
    });
</script>
@endsection