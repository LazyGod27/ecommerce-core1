@extends('layouts.frontend')

@section('title', 'Track Order - iMarket PH')

@section('content')
<style>
    :root {
        --primary-color: #3b82f6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        animation: gradientShift 15s ease infinite;
    }
    
    @keyframes gradientShift {
        0% { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        25% { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        50% { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        75% { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        100% { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    }

    .main-container {
        padding-top: 100px;
        min-height: 100vh;
        background: transparent;
    }

    .container {
        display: flex;
        gap: 2rem;
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    
    .left-panel {
        flex: 2;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        margin-bottom: 3rem;
        padding: 2rem 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: slideInUp 0.8s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .progress-line {
        position: absolute;
        top: 50%;
        left: 5%;
        right: 5%;
        height: 4px;
        background: linear-gradient(90deg, #e2e8f0 0%, #cbd5e1 100%);
        z-index: 1;
        border-radius: 2px;
    }
    
    .progress-bar-fill {
        position: absolute;
        top: 50%;
        left: 5%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--success-color) 100%);
        z-index: 2;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 3;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 1rem;
        border-radius: 12px;
        animation: fadeInScale 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .progress-step:nth-child(1) { animation-delay: 0.1s; }
    .progress-step:nth-child(2) { animation-delay: 0.2s; }
    .progress-step:nth-child(3) { animation-delay: 0.3s; }
    .progress-step:nth-child(4) { animation-delay: 0.4s; }
    .progress-step:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .progress-step:hover {
        background: rgba(59, 130, 246, 0.08);
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
    }
    
    .progress-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--gray-200);
        border: 3px solid var(--gray-200);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-500);
    }
    
    .progress-step.active .progress-circle {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        animation: pulse 2s infinite;
    }
    
    .progress-step.completed .progress-circle {
        background: var(--success-color);
        border-color: var(--success-color);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .progress-step.completed .progress-circle::before {
        content: '✓';
        font-size: 16px;
        font-weight: bold;
    }
    
    .progress-text {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        color: var(--gray-500);
        text-align: center;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .progress-step.active .progress-text {
        color: var(--primary-color);
        font-weight: 600;
        transform: scale(1.05);
    }
    
    .progress-step.completed .progress-text {
        color: var(--success-color);
        font-weight: 600;
    }

    @keyframes pulse {
        0% { box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        50% { box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6); }
        100% { box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
    }
    
    .product-list-section h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }
    
    .product-card {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        background: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        animation: slideInLeft 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .product-card:nth-child(1) { animation-delay: 0.1s; }
    .product-card:nth-child(2) { animation-delay: 0.2s; }
    .product-card:nth-child(3) { animation-delay: 0.3s; }
    .product-card:nth-child(4) { animation-delay: 0.4s; }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .product-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    
    .product-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .product-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .product-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
    }
    
    .product-price {
        color: var(--danger-color);
        font-size: 1.25rem;
        font-weight: 700;
    }

    .tracking-timeline {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-100);
        position: relative;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-200);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        font-weight: 600;
        flex-shrink: 0;
        z-index: 2;
        position: relative;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
    }

    .timeline-description {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .timeline-time {
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 500;
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
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        animation: slideInRight 0.8s ease-out;
        animation-fill-mode: both;
    }
    
    .info-section:nth-child(1) { animation-delay: 0.2s; }
    .info-section:nth-child(2) { animation-delay: 0.4s; }
    .info-section:nth-child(3) { animation-delay: 0.6s; }
    .info-section:nth-child(4) { animation-delay: 0.8s; }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .info-section:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        padding: 1rem;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .suggested-product-card:nth-child(1) { animation-delay: 0.1s; }
    .suggested-product-card:nth-child(2) { animation-delay: 0.2s; }
    .suggested-product-card:nth-child(3) { animation-delay: 0.3s; }
    .suggested-product-card:nth-child(4) { animation-delay: 0.4s; }
    .suggested-product-card:nth-child(5) { animation-delay: 0.5s; }
    .suggested-product-card:nth-child(6) { animation-delay: 0.6s; }
    .suggested-product-card:nth-child(7) { animation-delay: 0.7s; }
    .suggested-product-card:nth-child(8) { animation-delay: 0.8s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .suggested-product-card:hover {
        transform: translateY(-6px) scale(1.05);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
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
        background: rgba(0, 0, 0, 0.6);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.8) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
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
    
    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(59, 130, 246, 0.3);
        border-radius: 50%;
        border-top-color: #3b82f6;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Enhanced Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        animation: pulse 2s infinite;
    }
    
    .status-badge.pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #f59e0b;
    }
    
    .status-badge.processing {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 1px solid #3b82f6;
    }
    
    .status-badge.shipped {
        background: linear-gradient(135deg, #e9d5ff 0%, #ddd6fe 100%);
        color: #6b21a8;
        border: 1px solid #8b5cf6;
    }
    
    .status-badge.delivered {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #10b981;
    }
    
    .status-badge.cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #ef4444;
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
        
        <!-- Tracking Timeline Section -->
        @if($order && $order->tracking)
        <div class="tracking-timeline">
            <h2 class="font-semibold text-lg mb-6 flex items-center">
                <i class="ri-truck-line mr-2 text-blue-600"></i>
                Tracking Timeline
            </h2>
            <div class="timeline-item">
                <div class="timeline-icon">📦</div>
                <div class="timeline-content">
                    <div class="timeline-title">Order Placed</div>
                    <div class="timeline-description">Your order has been received and is being processed</div>
                    <div class="timeline-time">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</div>
                </div>
            </div>
            @if($order->status === 'processing' || $order->status === 'shipped' || $order->status === 'delivered' || $order->status === 'completed')
            <div class="timeline-item">
                <div class="timeline-icon">⚙️</div>
                <div class="timeline-content">
                    <div class="timeline-title">Processing</div>
                    <div class="timeline-description">Your order is being prepared for shipment</div>
                    <div class="timeline-time">{{ $order->created_at->addHours(2)->format('M d, Y \a\t g:i A') }}</div>
                </div>
            </div>
            @endif
            @if($order->status === 'shipped' || $order->status === 'delivered' || $order->status === 'completed')
            <div class="timeline-item">
                <div class="timeline-icon">🚚</div>
                <div class="timeline-content">
                    <div class="timeline-title">Shipped</div>
                    <div class="timeline-description">Your order is on its way</div>
                    <div class="timeline-time">{{ $order->created_at->addDays(1)->format('M d, Y \a\t g:i A') }}</div>
                </div>
            </div>
            @endif
            @if($order->status === 'delivered' || $order->status === 'completed')
            <div class="timeline-item">
                <div class="timeline-icon">✅</div>
                <div class="timeline-content">
                    <div class="timeline-title">Delivered</div>
                    <div class="timeline-description">Your order has been delivered successfully</div>
                    <div class="timeline-time">{{ $order->delivered_at ? $order->delivered_at->format('M d, Y \a\t g:i A') : $order->created_at->addDays(3)->format('M d, Y \a\t g:i A') }}</div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Dynamic Product List Section -->
        <div class="product-list-section">
            <h2 class="font-semibold text-lg mb-4 flex items-center">
                <i class="ri-shopping-bag-line mr-2 text-green-600"></i>
                Order Items
            </h2>
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
            <h2 class="font-semibold text-lg mb-4 flex items-center">
                <i class="ri-map-pin-line mr-2 text-red-600"></i>
                Delivery Address
            </h2>
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-200">
                @if($order && $order->shipping_address)
                    <p class="text-sm text-gray-700 font-medium">{{ $order->shipping_address }}</p>
                    @if($order->contact_number)
                        <p class="text-sm text-gray-600 mt-2 flex items-center">
                            <i class="ri-phone-line mr-1"></i>
                            {{ $order->contact_number }}
                        </p>
                    @endif
                    @if($order->email)
                        <p class="text-sm text-gray-600 mt-1 flex items-center">
                            <i class="ri-mail-line mr-1"></i>
                            {{ $order->email }}
                        </p>
                    @endif
                @else
                    <p class="text-sm text-gray-600">No address found</p>
                @endif
            </div>
        </div>

        <!-- Delivery Estimate Section -->
        @if($order && $order->tracking)
        <div class="info-section">
            <h2 class="font-semibold text-lg mb-4 flex items-center">
                <i class="ri-time-line mr-2 text-orange-600"></i>
                Delivery Estimate
            </h2>
            <div class="bg-gradient-to-br from-orange-50 to-yellow-50 p-4 rounded-xl border border-orange-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Expected Delivery</span>
                    <span class="text-sm font-bold text-orange-600">
                        {{ $order->tracking->estimated_delivery ? $order->tracking->estimated_delivery->format('M d, Y') : 'TBD' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tracking Number</span>
                    <span class="text-sm font-mono bg-white px-2 py-1 rounded border">
                        {{ $order->tracking->tracking_number }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        <!-- Order Summary Section -->
        <div class="info-section">
            <h2 class="font-semibold text-lg mb-4 flex items-center">
                <i class="ri-receipt-line mr-2 text-green-600"></i>
                Order Summary
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Order Total</span>
                    <span class="font-semibold text-gray-800">
                        @if($order)
                            ₱{{ number_format($order->subtotal ?? 0, 2) }}
                        @else
                            ₱ 0.00
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Shipping Fee</span>
                    <span class="font-semibold text-gray-800">
                        @if($order)
                            ₱{{ number_format($order->shipping_cost ?? 0, 2) }}
                        @else
                            ₱ 0.00
                        @endif
                    </span>
                </div>
                @if($order && $order->tax)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Tax (12% VAT)</span>
                    <span class="font-semibold text-gray-800">₱{{ number_format($order->tax, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-3 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg px-3 mt-4">
                    <span class="font-bold text-gray-800">Total Payment</span>
                    <span class="font-bold text-xl text-green-600">
                        @if($order)
                            ₱{{ number_format($order->total ?? 0, 2) }}
                        @else
                            ₱ 0.00
                        @endif
                    </span>
                </div>
                @if($order)
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Order Status</span>
                            <span class="status-badge {{ $order->status ?? 'pending' }}">
                                {{ ucfirst($order->status ?? 'Pending') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Order Date</span>
                            <span class="font-semibold text-gray-800">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($order->delivered_at)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Delivered Date</span>
                                <span class="font-semibold text-gray-800">{{ $order->delivered_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
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
