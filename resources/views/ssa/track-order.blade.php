@extends('ssa.categories.layout')

@section('title', 'Track Order - iMarket PH')

@section('content')
<style>
    .container {
        display: flex;
        gap: 2rem;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    
    .left-panel {
        flex: 2;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }
    
    .right-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .flex {
        display: flex;
    }
    
    .items-center {
        align-items: center;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .text-gray-500 {
        color: #6b7280;
    }
    
    .text-blue-500 {
        color: #3b82f6;
    }
    
    .text-sm {
        font-size: 0.875rem;
    }
    
    .font-medium {
        font-weight: 500;
    }
    
    .font-semibold {
        font-weight: 600;
    }
    
    .text-lg {
        font-size: 1.125rem;
    }
    
    .hover\:text-gray-800:hover {
        color: #1f2937;
    }
    
    .hover\:underline:hover {
        text-decoration: underline;
    }
    
    .cancel-btn {
        background: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    
    .cancel-btn:hover {
        background: #dc2626;
    }
    
    .progress-container {
        position: relative;
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding: 1rem 0;
    }
    
    .progress-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #e5e7eb;
        z-index: 1;
    }
    
    .progress-bar-fill {
        position: absolute;
        top: 50%;
        left: 0;
        height: 2px;
        background: #3b82f6;
        z-index: 2;
        transition: width 0.3s ease;
    }
    
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 3;
        cursor: pointer;
    }
    
    .progress-circle {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #e5e7eb;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .progress-step.active .progress-circle {
        background: #3b82f6;
        border-color: #3b82f6;
    }
    
    .progress-step.completed .progress-circle {
        background: #10b981;
        border-color: #10b981;
    }
    
    .progress-text {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
        text-align: center;
    }
    
    .progress-step.active .progress-text {
        color: #3b82f6;
        font-weight: 600;
    }
    
    .progress-step.completed .progress-text {
        color: #10b981;
        font-weight: 600;
    }
    
    .product-list-section h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }
    
    .product-card {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 1rem;
        background: white;
    }
    
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }
    
    .product-details {
        flex: 1;
    }
    
    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin: 0 0 0.5rem 0;
    }
    
    .product-price {
        color: #ef4444;
        font-size: 1.125rem;
    }
    
    .hidden {
        display: none;
    }
    
    .text-center {
        text-align: center;
    }
    
    .mt-8 {
        margin-top: 2rem;
    }
    
    .info-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }
    
    .info-section h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }
    
    .bg-gray-100 {
        background-color: #f3f4f6;
    }
    
    .p-4 {
        padding: 1rem;
    }
    
    .rounded-lg {
        border-radius: 8px;
    }
    
    .space-y-2 > * + * {
        margin-top: 0.5rem;
    }
    
    .justify-between {
        justify-content: space-between;
    }
    
    .font-bold {
        font-weight: 700;
    }
    
    .text-base {
        font-size: 1rem;
    }
    
    .mt-4 {
        margin-top: 1rem;
    }
    
    .text-orange-500 {
        color: #f97316;
    }
    
    .suggested-products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .suggested-product-card {
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s ease;
    }
    
    .suggested-product-card:hover {
        transform: translateY(-2px);
    }
    
    .suggested-product-card img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }
    
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .modal-body p {
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    .reason-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .reason-item input[type="radio"] {
        accent-color: #f97316;
    }
    
    .reason-item label {
        cursor: pointer;
        color: #374151;
    }
    
    .modal-body textarea {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.75rem;
        width: 100%;
        resize: vertical;
        font-family: inherit;
    }
    
    .modal-body button {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .modal-body button[type="button"] {
        background: #d1d5db;
        color: #374151;
        border: none;
        margin-right: 0.5rem;
    }
    
    .modal-body button[type="submit"] {
        background: #f97316;
        color: white;
        border: none;
    }
    
    .modal-body button:hover {
        opacity: 0.9;
    }
    
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .left-panel, .info-section {
            padding: 1rem;
        }
        
        .suggested-products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .progress-container {
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .progress-step {
            flex: 1;
            min-width: 60px;
        }
    }
</style>

<div class="container">
    <!-- Left Panel for Status and Products -->
    <div class="left-panel">
        <div class="header">
            <a href="{{ route('profile.orders') }}" class="text-gray-500 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-semibold">
                @if($order)
                    Tracking Order #{{ $order->id }}
                @else
                    Your Orders
                @endif
            </h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.orders') }}" class="text-blue-500 hover:underline text-sm font-medium">View All Orders ></a>
                @if($order)
                    <a href="#" class="cancel-btn text-sm" id="cancel-order-btn">Cancel Order</a>
                @endif
            </div>
        </div>

        <!-- Progress bar Section -->
        <div class="progress-container">
            <div class="progress-line"></div>
            <div class="progress-bar-fill" id="progress-bar-fill"></div>
            
            <div class="progress-step active" data-status="to-pay">
                <div class="progress-circle"></div>
                <p class="progress-text">To Pay</p>
            </div>
            <div class="progress-step" data-status="to-ship">
                <div class="progress-circle"></div>
                <p class="progress-text">To Ship</p>
            </div>
            <div class="progress-step" data-status="to-receive">
                <div class="progress-circle"></div>
                <p class="progress-text">To Receive</p>
            </div>
            <div class="progress-step" data-status="to-review">
                <div class="progress-circle"></div>
                <p class="progress-text">To Review</p>
            </div>
            <div class="progress-step" data-status="returns">
                <div class="progress-circle"></div>
                <p class="progress-text">Returns</p>
            </div>
        </div>
        
        <!-- Dynamic Product List Section -->
        <div class="product-list-section">
            <h2 class="font-semibold text-lg mb-4">Items</h2>
            <div id="product-list">
                <!-- Product cards will be dynamically inserted here -->
            </div>
            <div id="no-products-message" class="hidden text-center text-gray-500 mt-8">
                <p>No products found in this category.</p>
            </div>
        </div>
    </div>

    <!-- Right Panel for Address and Summary -->
    <div class="right-panel">
        <!-- Delivery Address Section -->
        <div class="info-section">
            <h2 class="font-semibold text-lg mb-4">Delivery Address</h2>
            <div class="bg-gray-100 p-4 rounded-lg">
                @if($order && $order->shipping_address)
                    <p class="text-sm text-gray-700">{{ $order->shipping_address }}</p>
                    @if($order->contact_number)
                        <p class="text-sm text-gray-600 mt-2">Contact: {{ $order->contact_number }}</p>
                    @endif
                @else
                    <p class="text-sm text-gray-600">No address found</p>
                @endif
            </div>
        </div>

        <!-- Order Summary Section -->
        <div class="info-section">
            <h2 class="font-semibold text-lg mb-4">Order Summary</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Order Total</span>
                    <span class="font-semibold">
                        @if($order)
                            ₱{{ number_format($order->subtotal ?? 0, 2) }}
                        @else
                            ₱ 0.00
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span>Shipping Fee</span>
                    <span class="font-semibold">
                        @if($order)
                            ₱{{ number_format($order->shipping_cost ?? 0, 2) }}
                        @else
                            ₱ 0.00
                        @endif
                    </span>
                </div>
                <div class="flex justify-between font-bold text-base mt-4">
                    <span>Total Payment</span>
                    <span class="text-orange-500">
                        @if($order)
                            ₱{{ number_format($order->total ?? 0, 2) }}
                        @else
                            ₱ 0.00
                        @endif
                    </span>
                </div>
                @if($order)
                    <div class="flex justify-between text-sm mt-2">
                        <span>Order Status</span>
                        <span class="font-semibold text-blue-600">{{ ucfirst($order->status ?? 'Pending') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Order Date</span>
                        <span class="font-semibold">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($order->delivered_at)
                        <div class="flex justify-between text-sm">
                            <span>Delivered Date</span>
                            <span class="font-semibold">{{ $order->delivered_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        
        <div class="info-section">
            <h2 class="font-semibold text-lg mb-4">Suggested Products</h2>
            <div class="suggested-products-grid">
                <a href="#" class="suggested-product-card block">
                    <img src="https://dlcdnimgs.asus.com/websites/global/products/rq4syeyq3zyjvubn/img/new/01-bg_m.jpg" alt="Suggested Product A">
                    <span class="text-sm font-medium text-center">Asus Mechanical Keyboard</span>
                </a>
                <a href="#" class="suggested-product-card block">
                    <img src="https://3.kixify.com/sites/default/files/imagecache/product_full/product/2023/02/09/p_34475983_198712531_111814.jpg" alt="Suggested Product B">
                    <span class="text-sm font-medium text-center">New Balance 550</span>
                </a>
                <a href="#" class="suggested-product-card block">
                    <img src="https://cdn.vox-cdn.com/thumbor/jXZJmX2EJYsOydXhyVwUWiOTO50=/0x0:1238x825/1720x0/filters:focal(0x0:1238x825):no_upscale()/cdn.vox-cdn.com/uploads/chorus_asset/file/24878879/blacksharkv2inline.jpg" alt="Suggested Product C">
                    <span class="text-sm font-medium text-center">Razer Headset</span>
                </a>
                <a href="#" class="suggested-product-card block">
                    <img src="https://static-01.daraz.pk/p/91b1ca3d8371df14ef2e1512a1464d36.png" alt="Suggested Product D">
                    <span class="text-sm font-medium text-center">M90 Earbuds</span>
                </a>
                <a href="#" class="suggested-product-card block">
                    <img src="https://cf.shopee.ph/file/sg-11134201-22100-yteqt1thheiv6f" alt="Suggested Product E">
                    <span class="text-sm font-medium text-center">Aqua Flask</span>
                </a>
                <a href="#" class="suggested-product-card block">
                    <img src="https://img1.wushang.com/pn/wsec-img1/2020/7/5/5862eff1-31c8-4534-afc1-e77b800709d3.jpg?x-oss-process=image/resize,w_800,h_800" alt="Suggested Product F">
                    <span class="text-sm font-medium text-center">JBL Speaker</span>
                </a>
                <a href="#" class="suggested-product-card block">
                    <img src="https://down-ph.img.susercontent.com/file/ph-11134207-7r98r-lrmrz5wqmm0l1e" alt="Suggested Product G">
                    <span class="text-sm font-medium text-center">Grasya Hoodie</span>
                </a>
                <a href="#" class="suggested-product-card block">
                    <img src="https://www.techpowerup.com/img/15-03-24/Logitech_MX_Master_Wireless_Mouse.jpg" alt="Suggested Product H">
                    <span class="text-sm font-medium text-center">Logitech</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div id="cancellation-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold">Cancel Order</h3>
            <button id="close-modal-btn" class="text-gray-500 hover:text-gray-800 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <p class="mb-4">Please select the reason for canceling your order:</p>
            <form id="cancellation-form">
                @csrf
                <div class="reason-item">
                    <input type="radio" id="reason1" name="cancel-reason" value="I no longer want the product" class="text-main-orange focus:ring-main-orange">
                    <label for="reason1">I no longer want the product</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason2" name="cancel-reason" value="Ordered by mistake" class="text-main-orange focus:ring-main-orange">
                    <label for="reason2">Ordered by mistake</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason3" name="cancel-reason" value="Found a cheaper price elsewhere" class="text-main-orange focus:ring-main-orange">
                    <label for="reason3">Found a cheaper price elsewhere</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason4" name="cancel-reason" value="Others" class="text-main-orange focus:ring-main-orange">
                    <label for="reason4">Others</label>
                </div>
                <textarea class="mt-4 p-2 w-full border rounded-md" rows="3" placeholder="You may provide additional details..."></textarea>
                <div class="flex justify-end mt-4">
                    <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mr-2" id="cancel-modal-btn">Cancel</button>
                    <button type="submit" class="bg-main-orange text-white px-4 py-2 rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Order data from Laravel
    const orderData = @json($order);
    
    // Convert order items to tracking data based on order status
    const ordersData = {
        'to-pay': [],
        'to-ship': [],
        'to-receive': [],
        'to-review': [],
        'returns': []
    };
    
    if (orderData && orderData.items) {
        // Map order items to appropriate status based on order status
        const orderItems = orderData.items.map(item => ({
            name: item.product ? item.product.name : 'Product',
            details: `Order #${orderData.id} - ${getStatusDescription(orderData.status)}`,
            price: `₱${parseFloat(item.price).toLocaleString('en-PH', {minimumFractionDigits: 2})}`,
            img: item.product && item.product.image ? 
                `{{ asset('storage/') }}/${item.product.image}` : 
                'https://via.placeholder.com/80x80/e5e7eb/6b7280?text=No+Image'
        }));
        
        // Assign items to appropriate status based on order status
        switch(orderData.status) {
            case 'pending':
            case 'processing':
                ordersData['to-pay'] = orderItems;
                break;
            case 'shipped':
                ordersData['to-ship'] = orderItems;
                break;
            case 'in_transit':
                ordersData['to-receive'] = orderItems;
                break;
            case 'delivered':
            case 'completed':
                ordersData['to-review'] = orderItems;
                break;
            case 'cancelled':
            case 'refunded':
                ordersData['returns'] = orderItems;
                break;
            default:
                ordersData['to-pay'] = orderItems;
        }
    }
    
    function getStatusDescription(status) {
        const statusMap = {
            'pending': 'Pending Payment',
            'processing': 'Processing',
            'shipped': 'Shipped',
            'in_transit': 'In Transit',
            'delivered': 'Delivered',
            'completed': 'Completed',
            'cancelled': 'Cancelled',
            'refunded': 'Refunded'
        };
        return statusMap[status] || 'Pending';
    }

    const progressSteps = document.querySelectorAll('.progress-step');
    const productListContainer = document.getElementById('product-list');
    const progressBarFill = document.getElementById('progress-bar-fill');
    const noProductsMessage = document.getElementById('no-products-message');
    const cancelButton = document.getElementById('cancel-order-btn');
    const modal = document.getElementById('cancellation-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');
    const cancellationForm = document.getElementById('cancellation-form');

    function renderProducts(status) {
        productListContainer.innerHTML = '';
        
        const products = ordersData[status];
        if (products && products.length > 0) {
            noProductsMessage.classList.add('hidden');
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    <img src="${product.img}" alt="${product.name}" class="product-image">
                    <div class="product-details">
                        <h3 class="product-name">${product.name}</h3>
                        <p class="text-gray-500 text-sm">${product.details}</p>
                        <p class="product-price font-bold mt-2">${product.price}</p>
                    </div>
                `;
                productListContainer.appendChild(productCard);
            });
        } else {
            noProductsMessage.classList.remove('hidden');
        }
    }

    function updateActiveStatus(activeStep) {
        progressSteps.forEach(step => {
            step.classList.remove('active', 'completed');
        });
        let completedCount = 0;
        let foundActive = false;
        progressSteps.forEach(step => {
            if (step === activeStep) {
                step.classList.add('active');
                foundActive = true;
            } else if (!foundActive) {
                step.classList.add('completed');
                completedCount++;
            }
        });

        const progress = (completedCount / (progressSteps.length - 1)) * 100;
        progressBarFill.style.width = `${progress}%`;
    }

    progressSteps.forEach(step => {
        step.addEventListener('click', () => {
            const status = step.dataset.status;
            updateActiveStatus(step);
            renderProducts(status);
        });
    });

    cancelButton.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    cancelModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    cancellationForm.addEventListener('submit', (e) => {
        e.preventDefault();
        modal.style.display = 'none';
        // Here you would typically send the cancellation request to the server
        alert('Order cancellation request submitted successfully!');
    });

    // Initialize with correct step based on order status
    let initialStatus = 'to-pay';
    if (orderData) {
        switch(orderData.status) {
            case 'pending':
            case 'processing':
                initialStatus = 'to-pay';
                break;
            case 'shipped':
                initialStatus = 'to-ship';
                break;
            case 'in_transit':
                initialStatus = 'to-receive';
                break;
            case 'delivered':
            case 'completed':
                initialStatus = 'to-review';
                break;
            case 'cancelled':
            case 'refunded':
                initialStatus = 'returns';
                break;
        }
    }
    
    // Find and activate the correct progress step
    const initialStep = document.querySelector(`[data-status="${initialStatus}"]`);
    if (initialStep) {
        updateActiveStatus(initialStep);
    } else {
        updateActiveStatus(progressSteps[0]);
    }
    
    renderProducts(initialStatus);
</script>
@endsection
