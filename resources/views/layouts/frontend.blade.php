<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'iMarket')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Override Tailwind's default styles for logos
        tailwind.config = {
            theme: {
                extend: {}
            }
        }
    </script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('ssa/style.css') }}?v={{ time() }}">
    <style>
        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .user-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 500;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
            padding: 8px 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: all .18s ease;
            z-index: 40;
        }
        
        .user-dropdown:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        
        .user-dropdown-menu a:hover {
            background: #f9fafb;
            color: #111827;
        }
        
        /* Force logo size consistency - override any conflicting styles including Tailwind */
        .logo img,
        header .logo img,
        .logo a img,
        header .logo a img,
        img[alt="IMARKET PH Logo"],
        img[src*="logo.png"] {
            max-height: 80px !important;
            height: auto !important;
            width: auto !important;
            display: block !important;
            margin-top: 6px !important;
            margin-left: -30px !important;
        }
        
        /* Override any Tailwind height classes that might be applied to logos */
        .logo .h-8,
        .logo .h-10,
        .logo .h-12,
        .logo .h-16,
        .logo .h-20,
        header .logo .h-8,
        header .logo .h-10,
        header .logo .h-12,
        header .logo .h-16,
        header .logo .h-20 {
            max-height: 80px !important;
            height: auto !important;
        }
    </style>
    @yield('styles')
    
    <!-- Final override to ensure logo consistency across all pages -->
    <style>
        /* Maximum specificity CSS to override everything */
        html body header .logo a img,
        html body .logo img,
        html body header .logo img,
        html body .logo a img,
        html body header .logo a img,
        html body img[alt="IMARKET PH Logo"],
        html body img[src*="logo.png"],
        html body img[alt*="IMARKET"],
        html body img[alt*="iMarket"] {
            max-height: 80px !important;
            height: auto !important;
            width: auto !important;
            display: block !important;
            margin-top: 6px !important;
            margin-left: -30px !important;
        }
        
        /* Even more specific selectors */
        body header div.logo a img,
        body div.logo img,
        body header div.logo img,
        body div.logo a img {
            max-height: 80px !important;
            height: auto !important;
            width: auto !important;
            display: block !important;
            margin-top: 6px !important;
            margin-left: -30px !important;
        }
    </style>
    
    <!-- Aggressive JavaScript to force logo size -->
    <script>
        // Force logo size immediately and repeatedly
        function forceLogoSize() {
            const logos = document.querySelectorAll('.logo img, header .logo img, img[alt="IMARKET PH Logo"], img[src*="logo.png"], img[alt*="IMARKET"], img[alt*="iMarket"]');
            logos.forEach(function(logo) {
                logo.style.setProperty('max-height', '80px', 'important');
                logo.style.setProperty('height', 'auto', 'important');
                logo.style.setProperty('width', 'auto', 'important');
                logo.style.setProperty('display', 'block', 'important');
                logo.style.setProperty('margin-top', '6px', 'important');
                logo.style.setProperty('margin-left', '-30px', 'important');
            });
        }
        
        // Run immediately
        forceLogoSize();
        
        // Run when DOM is ready
        document.addEventListener('DOMContentLoaded', forceLogoSize);
        
        // Run after a short delay to catch any dynamic content
        setTimeout(forceLogoSize, 100);
        setTimeout(forceLogoSize, 500);
        setTimeout(forceLogoSize, 1000);
        
        // Run on window load
        window.addEventListener('load', forceLogoSize);
    </script>
</head>
<body class="min-w-[320px] min-h-screen flex flex-col">
    @include('components.homepage-header')
    
    <main class="flex-grow">
        <div class="page-container">
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h5>Customer Care</h5>
                <ul>
                    <li><a href="{{ route('customer-service') }}">Customer Service</a></li>
                    <li><a href="#">How to Buy</a></li>
                    <li><a href="#">Shipping & Delivery</a></li>
                    <li><a href="#">Return & Refund</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>About ImarketPH</h5>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Payment Methods</h5>
                <div class="footer-logos">
                    <img src="{{ asset('ssa/visa.png') }}" alt="Visa">
                    <img src="{{ asset('ssa/mastercard.png') }}" alt="Mastercard">
                    <img src="{{ asset('ssa/gcash.png') }}" alt="GCash">
                    <img src="{{ asset('ssa/maya.png') }}" alt="Paymaya">
                </div>
            </div>
            <div class="footer-section">
                <h5>Delivery Services</h5>
                <div class="footer-logos">
                    <img src="{{ asset('ssa/jnt.png') }}" alt="J&T Express">
                    <img src="{{ asset('ssa/ninjavan.jpg') }}" alt="Ninja Van">
                    <img src="{{ asset('ssa/lbc.png') }}" alt="LBC Express">
                    <img src="{{ asset('ssa/flash.png') }}" alt="Flash Express">
                </div>
            </div>
            <div class="footer-section">
                <h5>Follow Us</h5>
                <div class="footer-socials">
                    <a href="#"><img src="{{ asset('ssa/facebook.png') }}" alt="Facebook"></a>
                    <a href="#"><img src="{{ asset('ssa/instagram.jpg') }}" alt="Instagram"></a>
                    <a href="#"><img src="{{ asset('ssa/twitter.png') }}" alt="Twitter"></a>
                    <a href="#"><img src="{{ asset('ssa/youtube.png') }}" alt="YouTube"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 All Rights Reserved by ImarketPH</p>
        </div>
    </footer>

    <script src="{{ asset('js/cart-auth.js') }}"></script>
    @yield('scripts')
</body>
</html>
