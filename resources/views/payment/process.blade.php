@extends('layouts.frontend')

@section('title', 'iMarket - Payment')

@section('styles')
<style>
    .payment-method-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .payment-method-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .payment-method-card.selected {
        border-color: #2c3c8c;
        background-color: #f8fafc;
    }
    .stripe-form {
        max-width: 400px;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Complete Your Payment</h1>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number:</span>
                            <span class="font-semibold">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax:</span>
                            <span>₱{{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping:</span>
                            <span>₱{{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600">₱{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Shipping Address</h3>
                    <p class="text-gray-600">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <!-- Payment Methods -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Choose Payment Method</h2>
                
                <div class="space-y-4">
                    <!-- GCash -->
                    <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4" data-method="gcash">
                        <div class="flex items-center space-x-3">
                            <img src="https://logodix.com/logo/2206207.png" alt="GCash" class="w-8 h-8">
                            <div class="flex-1">
                                <h3 class="font-semibold">GCash</h3>
                                <p class="text-sm text-gray-600">Pay using your GCash wallet</p>
                            </div>
                            <input type="radio" name="payment_method" value="gcash" class="text-blue-600">
                        </div>
                    </div>

                    <!-- PayMaya -->
                    <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4" data-method="paymaya">
                        <div class="flex items-center space-x-3">
                            <img src="https://business.inquirer.net/wp-content/blogs.dir/5/files/2020/11/PayMaya-Logo_Vertical.png" alt="PayMaya" class="w-8 h-8">
                            <div class="flex-1">
                                <h3 class="font-semibold">PayMaya</h3>
                                <p class="text-sm text-gray-600">Pay using your PayMaya wallet</p>
                            </div>
                            <input type="radio" name="payment_method" value="paymaya" class="text-blue-600">
                        </div>
                    </div>

                    <!-- Credit/Debit Card -->
                    <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4" data-method="stripe">
                        <div class="flex items-center space-x-3">
                            <img src="https://up.yimg.com/ib/th/id/OIP.Tm8I5fC59eVVStXCIObmMQHaE1?pid=Api&rs=1&c=1&qlt=95&w=106&h=69" alt="Card" class="w-8 h-8">
                            <div class="flex-1">
                                <h3 class="font-semibold">Credit/Debit Card</h3>
                                <p class="text-sm text-gray-600">Pay using your card securely</p>
                            </div>
                            <input type="radio" name="payment_method" value="stripe" class="text-blue-600">
                        </div>
                    </div>

                    <!-- Cash on Delivery -->
                    <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4" data-method="cod">
                        <div class="flex items-center space-x-3">
                            <img src="https://static.vecteezy.com/system/resources/previews/006/566/274/non_2x/cash-on-delivery-icon-design-illustration-in-flat-design-free-vector.jpg" alt="COD" class="w-8 h-8">
                            <div class="flex-1">
                                <h3 class="font-semibold">Cash on Delivery</h3>
                                <p class="text-sm text-gray-600">Pay when you receive your order</p>
                            </div>
                            <input type="radio" name="payment_method" value="cod" class="text-blue-600">
                        </div>
                    </div>
                </div>

                <!-- Payment Forms -->
                <div id="payment-forms" class="mt-6">
                    <!-- Stripe Form -->
                    <div id="stripe-form" class="hidden">
                        <form id="stripe-payment-form" class="stripe-form">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Card Information</label>
                                <div id="card-element" class="border border-gray-300 rounded-md p-3"></div>
                                <div id="card-errors" class="text-red-600 text-sm mt-2"></div>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                Pay ₱{{ number_format($order->total, 2) }}
                            </button>
                        </form>
                    </div>

                    <!-- E-wallet Buttons -->
                    <div id="ewallet-buttons" class="hidden space-y-3">
                        <form action="{{ route('payment.gcash') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition-colors">
                                Pay with GCash
                            </button>
                        </form>
                        
                        <form action="{{ route('payment.paymaya') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-md hover:bg-purple-700 transition-colors">
                                Pay with PayMaya
                            </button>
                        </form>
                    </div>

                    <!-- COD Button -->
                    <div id="cod-button" class="hidden">
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="Cash on Delivery">
                            <input type="hidden" name="shipping_address" value="{{ $order->shipping_address }}">
                            <input type="hidden" name="contact_number" value="{{ $order->contact_number }}">
                            <input type="hidden" name="email" value="{{ $order->email }}">
                            <button type="submit" class="w-full bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 transition-colors">
                                Confirm Cash on Delivery Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentCards = document.querySelectorAll('.payment-method-card');
    const paymentForms = document.getElementById('payment-forms');
    const stripeForm = document.getElementById('stripe-form');
    const ewalletButtons = document.getElementById('ewallet-buttons');
    const codButton = document.getElementById('cod-button');

    // Initialize Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    // Handle payment method selection
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            paymentCards.forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Show appropriate payment form
            const method = this.dataset.method;
            showPaymentForm(method);
        });
    });

    function showPaymentForm(method) {
        // Hide all forms
        stripeForm.classList.add('hidden');
        ewalletButtons.classList.add('hidden');
        codButton.classList.add('hidden');
        
        // Show appropriate form
        switch(method) {
            case 'stripe':
                stripeForm.classList.remove('hidden');
                break;
            case 'gcash':
            case 'paymaya':
                ewalletButtons.classList.remove('hidden');
                break;
            case 'cod':
                codButton.classList.remove('hidden');
                break;
        }
    }

    // Handle Stripe form submission
    const stripeFormElement = document.getElementById('stripe-payment-form');
    stripeFormElement.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const submitButton = stripeFormElement.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';
        
        try {
            const {paymentIntent, error} = await stripe.confirmCardPayment(
                '{{ $stripeClientSecret ?? "" }}',
                {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: '{{ $order->user->name }}',
                            email: '{{ $order->email }}'
                        }
                    }
                }
            );
            
            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Pay ₱{{ number_format($order->total, 2) }}';
            } else {
                // Payment successful
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("payment.stripe") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const orderId = document.createElement('input');
                orderId.type = 'hidden';
                orderId.name = 'order_id';
                orderId.value = '{{ $order->id }}';
                
                const stripeToken = document.createElement('input');
                stripeToken.type = 'hidden';
                stripeToken.name = 'stripe_token';
                stripeToken.value = paymentIntent.id;
                
                form.appendChild(csrfToken);
                form.appendChild(orderId);
                form.appendChild(stripeToken);
                document.body.appendChild(form);
                form.submit();
            }
        } catch (error) {
            document.getElementById('card-errors').textContent = 'An error occurred. Please try again.';
            submitButton.disabled = false;
            submitButton.textContent = 'Pay ₱{{ number_format($order->total, 2) }}';
        }
    });
});
</script>
@endsection
