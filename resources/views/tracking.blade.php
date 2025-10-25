@extends('layouts.frontend')

@section('title', 'iMarket - Track Your Orders')

@section('styles')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        margin: 0;
        padding-top: 100px;
        min-height: 100vh;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        gap: 20px;
    }
    
    .left-panel {
        flex: 2;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
        min-height: 600px;
    }
    
    .right-panel {
        flex: 1;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
        height: fit-content;
        position: sticky;
        top: 120px;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .container {
            flex-direction: column;
            gap: 15px;
        }
        
        .right-panel {
            position: static;
            order: -1;
        }
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 15px;
            gap: 10px;
        }
        
        .left-panel, .right-panel {
            padding: 20px;
        }
        
        .header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .header h1 {
            font-size: 1.3rem;
        }
    }
    
    @media (max-width: 480px) {
        .container {
            padding: 10px;
        }
        
        .left-panel, .right-panel {
            padding: 15px;
        }
        
        .progress-container {
            margin: 20px 0;
            padding: 15px 0;
        }
        
        .progress-step {
            flex: 1;
            min-width: 0;
        }
        
        .progress-text {
            font-size: 0.8rem;
        }
        
        .progress-circle {
            width: 35px;
            height: 35px;
        }
    }
    
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
    }
    
    .header a {
        color: var(--main-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }
        
        .cancel-btn {
            font-size: 0.8rem;
            padding: 6px 12px;
        }
    }
    
    .cancel-btn {
        background: #dc2626;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .cancel-btn:hover {
        background: #b91c1c;
    }
    
    .progress-container {
        position: relative;
        margin: 30px 0;
        padding: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        background: var(--main-color);
        z-index: 2;
        transition: width 0.5s ease;
    }
    
    .progress-step {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 3;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .progress-step:hover {
        transform: translateY(-2px);
    }
    
    .progress-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .progress-step.active .progress-circle {
        background: var(--main-color);
        color: white;
    }
    
    .progress-step.completed .progress-circle {
        background: #10b981;
        color: white;
    }
    
    .progress-step.completed .progress-circle::after {
        content: '✓';
        font-weight: bold;
    }
    
    .progress-text {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6b7280;
        text-align: center;
    }
    
    .progress-step.active .progress-text {
        color: var(--main-color);
        font-weight: 600;
    }
    
    .progress-step.completed .progress-text {
        color: #10b981;
        font-weight: 600;
    }
    
    .product-list-section h2 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--text-color);
    }
    
    .product-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 15px;
        background: #f9fafb;
        transition: all 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .product-card {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .product-image {
            width: 100px;
            height: 100px;
        }
    }
    
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .product-details h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 5px;
    }
    
    .product-details p {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .product-price {
        color: var(--main-color);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .info-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .info-section:last-child {
        border-bottom: none;
    }
    
    .info-section h2 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--text-color);
    }
    
    .suggested-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    
    @media (max-width: 768px) {
        .suggested-products-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
        }
    }
    
    @media (max-width: 480px) {
        .suggested-products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
    }
    
    .suggested-product-card {
        text-align: center;
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    
    .suggested-product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .suggested-product-card img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 10px;
    }
    
    .suggested-product-card span {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-color);
    }
    
    .empty-state {
        padding: 2rem;
    }
    
    .loading-spinner {
        padding: 2rem;
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    @media (max-width: 768px) {
        .modal-content {
            padding: 20px;
            width: 95%;
            margin: 10px;
        }
    }
    
    @media (max-width: 480px) {
        .modal-content {
            padding: 15px;
            width: 98%;
            margin: 5px;
        }
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .modal-header h3 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-color);
    }
    
    .modal-header button {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
    }
    
    .modal-header button:hover {
        color: var(--text-color);
    }
    
    .reason-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .reason-item:last-child {
        border-bottom: none;
    }
    
    .reason-item input[type="radio"] {
        accent-color: var(--main-color);
    }
    
    .reason-item label {
        cursor: pointer;
        font-weight: 500;
        color: var(--text-color);
    }
    
    .hidden {
        display: none !important;
    }
    
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }
        
        .left-panel, .right-panel {
            flex: none;
        }
        
        .progress-step {
            margin-bottom: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Left Panel for Status and Products -->
    <div class="left-panel">
        <div class="header">
            <a href="{{ route('products') }}" class="text-gray-500 hover:text-gray-800">
                <i class="ri-arrow-left-line"></i>
            </a>
            <h1>Your Orders</h1>
            <div class="header-actions">
                <a href="{{ route('tracking') }}" class="text-blue-500 hover:underline text-sm font-medium">View All ></a>
                <button class="cancel-btn" id="cancel-order-btn">Cancel Order</button>
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
            <h2>Items</h2>
            <div id="product-list">
                <!-- Product cards will be dynamically inserted here -->
            </div>
            <div id="no-products-message" class="hidden text-center text-gray-500 mt-8">
                <div class="empty-state">
                    <i class="ri-shopping-bag-line" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                    <p>No products found in this category.</p>
                    <a href="{{ route('products') }}" class="text-blue-500 hover:underline mt-2 inline-block">Browse Products</a>
                </div>
            </div>
            <div id="loading-state" class="hidden text-center text-gray-500 mt-8">
                <div class="loading-spinner">
                    <i class="ri-loader-4-line animate-spin" style="font-size: 2rem; color: var(--main-color);"></i>
                    <p class="mt-2">Loading products...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel for Address and Summary -->
    <div class="right-panel">
        <!-- Delivery Address Section -->
        <div class="info-section">
            <h2>Delivery Address</h2>
            <div style="background: #f3f4f6; padding: 20px; border-radius: 8px;">
                @if($user && $user->address_line1)
                    <p style="font-size: 0.9rem; color: #6b7280;">
                        {{ $user->address_line1 }}<br>
                        @if($user->address_line2){{ $user->address_line2 }}<br>@endif
                        {{ $user->city }}, {{ $user->state }} {{ $user->postal_code }}<br>
                        {{ $user->country }}
                    </p>
                @else
                    <p style="font-size: 0.9rem; color: #6b7280;">No address found</p>
                @endif
            </div>
        </div>

        <!-- Order Summary Section -->
        <div class="info-section">
            <h2>Order Summary</h2>
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span>Order Total</span>
                    <span style="font-weight: 600;">₱ 0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Shipping Fee</span>
                    <span style="font-weight: 600;">₱ 0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1rem; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <span>Total Payment</span>
                    <span style="color: #f97316;">₱ 0.00</span>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h2>Suggested Products</h2>
            <div class="suggested-products-grid">
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/keyboard.jpg') }}" alt="Suggested Product A">
                    <span>Mechanical Keyboard</span>
                </a>
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/shoes.jpg') }}" alt="Suggested Product B">
                    <span>Nike Sneakers</span>
                </a>
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/headset.jpg') }}" alt="Suggested Product C">
                    <span>Gaming Headset</span>
                </a>
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/ultra.jpg') }}" alt="Suggested Product D">
                    <span>Smart Watch</span>
                </a>
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/jbl.png') }}" alt="Suggested Product E">
                    <span>JBL Speaker</span>
                </a>
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/water.jpg') }}" alt="Suggested Product F">
                    <span>Water Bottle</span>
                </a>
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/hoodie.jpg') }}" alt="Suggested Product G">
                    <span>Hoodie</span>
                </a>
                <a href="#" class="suggested-product-card">
                    <img src="{{ asset('ssa/mouse.jpg') }}" alt="Suggested Product H">
                    <span>Gaming Mouse</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div id="cancellation-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Cancel Order</h3>
            <button id="close-modal-btn">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 20px;">Please select the reason for canceling your order:</p>
            <form id="cancellation-form">
                <div class="reason-item">
                    <input type="radio" id="reason1" name="cancel-reason" value="I no longer want the product">
                    <label for="reason1">I no longer want the product</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason2" name="cancel-reason" value="Ordered by mistake">
                    <label for="reason2">Ordered by mistake</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason3" name="cancel-reason" value="Found a cheaper price elsewhere">
                    <label for="reason3">Found a cheaper price elsewhere</label>
                </div>
                <div class="reason-item">
                    <input type="radio" id="reason4" name="cancel-reason" value="Others">
                    <label for="reason4">Others</label>
                </div>
                <textarea style="margin-top: 20px; padding: 12px; width: 100%; border: 1px solid #d1d5db; border-radius: 6px; resize: vertical; min-height: 80px;" rows="3" placeholder="You may provide additional details..."></textarea>
                <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px;">
                    <button type="button" style="background: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;" id="cancel-modal-btn">Cancel</button>
                    <button type="submit" style="background: var(--main-color); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ordersData = {
        'to-pay': [],
        'to-ship': [],
        'to-receive': [],
        'to-review': [],
        'returns': []
    };

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
                        <p style="color: #6b7280; font-size: 0.9rem;">${product.details}</p>
                        <p class="product-price" style="font-weight: 600; margin-top: 8px;">${product.price}</p>
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
        // Handle cancellation logic here
    });

    // Initialize
    updateActiveStatus(progressSteps[0]);
    renderProducts('to-pay');
</script>
@endsection