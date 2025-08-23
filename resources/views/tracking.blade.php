@extends('layouts.frontend')

@section('title', 'iMarket - Order Tracking')

@section('styles')
<style>
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .progress-step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 1rem;
        margin-bottom: 8px;
        transition: background-color 0.3s ease;
    }

    .progress-step-text {
        font-size: 0.875rem;
        color: #888;
        transition: color 0.3s ease;
        white-space: nowrap;
    }

    .progress-line {
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: #e5e7eb;
        z-index: 0;
    }
    
    .progress-line-fill {
        height: 100%;
        background-color: #2c3c8c;
        transition: width 0.5s ease-in-out;
    }

    .message-box {
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        animation: fadeInOut 3s forwards;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translate(-50%, -20px); }
        10% { opacity: 1; transform: translate(-50%, 0); }
        90% { opacity: 1; transform: translate(-50%, 0); }
        100% { opacity: 0; transform: translate(-50%, -20px); }
    }

    .tracking-search {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
    }

    .tracking-input {
        border: none;
        border-radius: 10px;
        padding: 15px 20px;
        font-size: 16px;
        width: 100%;
        margin-bottom: 15px;
    }

    .tracking-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .tracking-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .order-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-placed { background: #e3f2fd; color: #1976d2; }
    .status-shipped { background: #fff3e0; color: #f57c00; }
    .status-transit { background: #e8f5e8; color: #388e3c; }
    .status-delivered { background: #e8f5e8; color: #2e7d32; }
    .status-cancelled { background: #ffebee; color: #d32f2f; }
    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-processing { background: #e3f2fd; color: #1976d2; }
    .status-completed { background: #e8f5e8; color: #2e7d32; }

    .recent-orders {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    .order-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 15px 0;
        transition: background-color 0.3s ease;
    }

    .order-item:hover {
        background-color: #f8f9fa;
    }

    .order-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4 text-center">Order Tracking</h1>
        <p class="text-gray-600 text-center">Track your orders and stay updated on delivery status</p>
    </div>

    <!-- Message box for user feedback -->
    <div id="message-box" class="message-box fixed p-4 rounded-md shadow-lg text-white font-semibold transition-all duration-300 opacity-0 z-50"></div>

    <!-- Tracking Search Section -->
    <div class="tracking-search">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold mb-2">Track Your Order</h2>
            <p class="opacity-90">Enter your order ID to get real-time updates</p>
        </div>
        
        <div class="max-w-md mx-auto">
            <input type="text" id="order-id-input" placeholder="Enter Order ID (e.g., odr1, odr2, odr3, odr4)" 
                   class="tracking-input text-gray-800">
            <button id="search-order-btn" class="tracking-btn w-full">
                <i class="fas fa-search mr-2"></i>
                Track Order
            </button>
        </div>
    </div>

    <!-- Recent Orders Section (for logged-in users) -->
    @auth
    <div class="recent-orders mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-clock mr-2"></i>
            Recent Orders
        </h3>
        <div id="recent-orders-list">
            @if($orders && count($orders) > 0)
                @foreach($orders as $order)
                <div class="order-item">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h4 class="font-semibold text-gray-800">Order #{{ $order->order_number }}</h4>
                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->items->count() }} item(s) - ₱{{ number_format($order->total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="status-badge status-{{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <a href="{{ route('tracking.show', $order->id) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                Track Order
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <i class="fas fa-shopping-bag text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">No orders found. Start shopping to see your orders here!</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endauth

    <!-- Order Details Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="order-details-section" style="display: none;">
        
        <!-- Order Progress -->
        <div class="lg:col-span-2 order-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-700">Order: <span id="order-number"></span></h2>
                <span id="order-status-badge" class="status-badge"></span>
            </div>
            
            <div class="relative flex justify-between items-start pt-8 pb-4">
                <!-- Progress line and fill -->
                <div class="progress-line absolute top-1/2 left-0 w-full h-1 bg-gray-200 -z-10">
                    <div id="progress-line-fill" class="progress-line-fill"></div>
                </div>
                
                <div id="step-order-placed" class="progress-step">
                    <div class="progress-step-icon bg-gray-400">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <span class="progress-step-text">Order Placed</span>
                </div>
                
                <div id="step-shipped" class="progress-step">
                    <div class="progress-step-icon bg-gray-400">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <span class="progress-step-text">Shipped</span>
                </div>
                
                <div id="step-in-transit" class="progress-step">
                    <div class="progress-step-icon bg-gray-400">
                        <i class="fas fa-truck"></i>
                    </div>
                    <span class="progress-step-text">In Transit</span>
                </div>
                
                <div id="step-delivered" class="progress-step">
                    <div class="progress-step-icon bg-gray-400">
                        <i class="fas fa-home"></i>
                    </div>
                    <span class="progress-step-text">Delivered</span>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-card p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Order Summary</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Order Date:</span>
                    <span id="order-date" class="font-semibold text-gray-800"></span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Estimated Delivery:</span>
                    <span id="estimated-delivery" class="font-semibold text-gray-800"></span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Shipped To:</span>
                    <span id="shipping-address" class="text-right"></span>
                </div>
                
                <hr class="my-4 border-gray-200">
                
                <div class="flex justify-between items-center text-sm text-gray-800">
                    <span class="font-semibold">Subtotal:</span>
                    <span id="subtotal"></span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-800">
                    <span class="font-semibold">Shipping:</span>
                    <span id="shipping-cost"></span>
                </div>
                <div class="flex justify-between items-center text-lg font-bold text-gray-900 mt-4 pt-2 border-t border-gray-200">
                    <span>Total:</span>
                    <span id="total-cost"></span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="lg:col-span-3 order-card p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Order Items</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-table-body" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sample Orders for Demo -->
    <div class="mt-8 text-center">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Try these sample order IDs:</h3>
        <div class="flex flex-wrap justify-center gap-2">
            <button onclick="trackOrder('odr1')" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">odr1 (Order Placed)</button>
            <button onclick="trackOrder('odr2')" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">odr2 (Shipped)</button>
            <button onclick="trackOrder('odr3')" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">odr3 (In Transit)</button>
            <button onclick="trackOrder('odr4')" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">odr4 (Delivered)</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sampleOrderDatabase = {
            'odr1': {
                orderId: 'odr1',
                status: 'Order Placed',
                orderDate: 'Aug 15, 2024',
                estimatedDelivery: 'Aug 20, 2024',
                shippingAddress: '123 E-Commerce Way, Suite 400, Shopping City, SC 12345',
                subtotal: 149.98,
                shipping: 10.00,
                total: 159.98,
                items: [
                    { name: 'Wireless Bluetooth Headphones', price: 79.99, quantity: 1 },
                    { name: 'Ergonomic Gaming Mouse', price: 69.99, quantity: 1 },
                ]
            },
            'odr2': {
                orderId: 'odr2',
                status: 'Shipped',
                orderDate: 'Aug 14, 2024',
                estimatedDelivery: 'Aug 19, 2024',
                shippingAddress: '456 Tech Park Ave, Innovation Hub, CA 90210',
                subtotal: 49.99,
                shipping: 5.00,
                total: 54.99,
                items: [
                    { name: 'USB-C Fast Charger', price: 29.99, quantity: 1 },
                    { name: 'Braided USB-C Cable', price: 20.00, quantity: 1 },
                ]
            },
            'odr3': {
                orderId: 'odr3',
                status: 'In Transit',
                orderDate: 'Aug 12, 2024',
                estimatedDelivery: 'Aug 17, 2024',
                shippingAddress: '789 Business Blvd, Business Town, TX 75001',
                subtotal: 249.50,
                shipping: 15.50,
                total: 265.00,
                items: [
                    { name: '4K Smart LED TV (55 inch)', price: 200.00, quantity: 1 },
                    { name: 'HDMI 2.1 Cable', price: 19.50, quantity: 2 },
                    { name: 'Wall Mount Bracket', price: 10.50, quantity: 1 }
                ]
            },
            'odr4': {
                orderId: 'odr4',
                status: 'Delivered',
                orderDate: 'Aug 05, 2024',
                estimatedDelivery: 'Aug 10, 2024',
                shippingAddress: '987 Main Street, Apt 5B, New York, NY 10001',
                subtotal: 9.99,
                shipping: 0.00,
                total: 9.99,
                items: [
                    { name: 'Phone Case (iPhone 14)', price: 9.99, quantity: 1 },
                ]
            }
        };

        const orderIdInput = document.getElementById('order-id-input');
        const searchOrderBtn = document.getElementById('search-order-btn');
        const messageBox = document.getElementById('message-box');
        const orderDetailsSection = document.getElementById('order-details-section');

        function renderOrderDetails(order) {
            const orderNumberEl = document.getElementById('order-number');
            const statusBadgeEl = document.getElementById('order-status-badge');
            const orderDateEl = document.getElementById('order-date');
            const estimatedDeliveryEl = document.getElementById('estimated-delivery');
            const shippingAddressEl = document.getElementById('shipping-address');
            const subtotalEl = document.getElementById('subtotal');
            const shippingCostEl = document.getElementById('shipping-cost');
            const totalCostEl = document.getElementById('total-cost');
            const itemsTableBodyEl = document.getElementById('order-items-table-body');
            const progressLineFillEl = document.getElementById('progress-line-fill');
            
            if (!order) {
                showTemporaryMessage("Order not found. Please try a different ID.", 'bg-red-500');
                orderDetailsSection.style.display = 'none';
                return;
            }

            orderDetailsSection.style.display = 'grid';
            orderNumberEl.textContent = order.orderId;

            // Set status badge
            statusBadgeEl.textContent = order.status;
            statusBadgeEl.className = 'status-badge';
            
            switch(order.status) {
                case 'Order Placed':
                    statusBadgeEl.classList.add('status-placed');
                    break;
                case 'Shipped':
                    statusBadgeEl.classList.add('status-shipped');
                    break;
                case 'In Transit':
                    statusBadgeEl.classList.add('status-transit');
                    break;
                case 'Delivered':
                    statusBadgeEl.classList.add('status-delivered');
                    break;
                default:
                    statusBadgeEl.classList.add('status-cancelled');
            }

            // Update progress bar
            const statusSteps = ['Order Placed', 'Shipped', 'In Transit', 'Delivered'];
            const currentStatusIndex = statusSteps.indexOf(order.status);
            const totalSteps = statusSteps.length;
            const progressPercentage = (currentStatusIndex / (totalSteps - 1)) * 100;
            progressLineFillEl.style.width = `${progressPercentage}%`;

            // Update progress steps
            statusSteps.forEach((status, index) => {
                const stepEl = document.querySelector(`#step-${status.toLowerCase().replace(/ /g, '-')}`);
                if (stepEl) {
                    const iconDiv = stepEl.querySelector('.progress-step-icon');
                    const textSpan = stepEl.querySelector('.progress-step-text');
                    
                    iconDiv.className = 'progress-step-icon bg-gray-400';
                    textSpan.className = 'progress-step-text text-gray-500';

                    if (index <= currentStatusIndex) {
                        iconDiv.classList.remove('bg-gray-400');
                        if (status === 'Delivered') {
                            iconDiv.classList.add('bg-green-500');
                            textSpan.classList.add('text-green-500', 'font-semibold');
                        } else if (status === 'In Transit') {
                            iconDiv.classList.add('bg-blue-500');
                            textSpan.classList.add('text-blue-500', 'font-semibold');
                        } else {
                            iconDiv.classList.add('bg-blue-600');
                            textSpan.classList.add('text-blue-600', 'font-semibold');
                        }
                    }
                }
            });

            // Update order details
            orderDateEl.textContent = order.orderDate;
            estimatedDeliveryEl.textContent = order.estimatedDelivery;
            shippingAddressEl.textContent = order.shippingAddress;
            subtotalEl.textContent = `₱${order.subtotal.toFixed(2)}`;
            shippingCostEl.textContent = `₱${order.shipping.toFixed(2)}`;
            totalCostEl.textContent = `₱${order.total.toFixed(2)}`;

            // Update order items table
            itemsTableBodyEl.innerHTML = '';
            order.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${item.name}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱${item.price.toFixed(2)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.quantity}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱${(item.price * item.quantity).toFixed(2)}</td>
                `;
                itemsTableBodyEl.appendChild(row);
            });
        }

        function showTemporaryMessage(message, colorClass) {
            messageBox.textContent = message;
            messageBox.className = `message-box fixed p-4 rounded-md shadow-lg font-semibold transition-all duration-300 ${colorClass} text-white opacity-100 z-50`;
            
            messageBox.style.animation = 'none';
            void messageBox.offsetWidth; 
            messageBox.style.animation = null; 
        }

        function handleSearch() {
            const orderId = orderIdInput.value.trim().toLowerCase();
            if (orderId) {
                const orderData = sampleOrderDatabase[orderId];
                renderOrderDetails(orderData);
                if (orderData) {
                    showTemporaryMessage(`Order ${orderData.orderId} found.`, 'bg-green-500');
                }
            } else {
                showTemporaryMessage("Please enter an Order ID.", 'bg-red-500');
                orderDetailsSection.style.display = 'none';
            }
        }

        // Global function for demo buttons
        window.trackOrder = function(orderId) {
            orderIdInput.value = orderId;
            handleSearch();
        };

        if (searchOrderBtn) {
            searchOrderBtn.addEventListener('click', handleSearch);
        }

        if (orderIdInput) {
            orderIdInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    handleSearch();
                }
            });
        }

        // Show default order on page load
        renderOrderDetails(sampleOrderDatabase['odr3']);
    });
</script>
@endsection
