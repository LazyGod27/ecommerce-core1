<?php

/**
 * Debug Cart Issue Script
 * 
 * This script helps debug the cart empty issue during checkout
 */

echo "🔍 Debugging Cart Issue\n";
echo "======================\n\n";

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: This doesn't appear to be a Laravel project.\n";
    echo "Please run this script from your Laravel project root directory.\n";
    exit(1);
}

echo "📋 Cart Issue Diagnosis\n";
echo "=======================\n\n";

// 1. Check session configuration
echo "1. Checking session configuration...\n";
$sessionConfig = include 'config/session.php';
echo "   Session Driver: " . $sessionConfig['driver'] . "\n";
echo "   Session Lifetime: " . $sessionConfig['lifetime'] . " minutes\n";
echo "   Session Cookie: " . $sessionConfig['cookie'] . "\n";
echo "   Session Path: " . $sessionConfig['path'] . "\n";
echo "   Session Domain: " . ($sessionConfig['domain'] ?? 'null') . "\n";
echo "   Session Secure: " . ($sessionConfig['secure'] ? 'true' : 'false') . "\n";
echo "   Session HTTP Only: " . ($sessionConfig['http_only'] ? 'true' : 'false') . "\n";
echo "   Session Same Site: " . ($sessionConfig['same_site'] ?? 'null') . "\n\n";

// 2. Check if .env file has session configuration
echo "2. Checking .env file for session configuration...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    $sessionLines = [];
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        if (strpos($line, 'SESSION_') === 0 || strpos($line, 'CACHE_') === 0) {
            $sessionLines[] = $line;
        }
    }
    
    if (empty($sessionLines)) {
        echo "   ⚠️  No session configuration found in .env file\n";
        echo "   Adding recommended session configuration...\n";
        
        $recommendedConfig = [
            "",
            "# Session Configuration",
            "SESSION_DRIVER=file",
            "SESSION_LIFETIME=120",
            "SESSION_ENCRYPT=false",
            "SESSION_PATH=/",
            "SESSION_DOMAIN=null",
            "SESSION_SECURE_COOKIE=false",
            "SESSION_HTTP_ONLY=true",
            "SESSION_SAME_SITE=lax",
            "",
            "# Cache Configuration", 
            "CACHE_DRIVER=file",
            "CACHE_PREFIX="
        ];
        
        $envContent .= implode("\n", $recommendedConfig);
        file_put_contents('.env', $envContent);
        echo "   ✅ Added session configuration to .env file\n";
    } else {
        echo "   ✅ Session configuration found in .env file:\n";
        foreach ($sessionLines as $line) {
            echo "      " . $line . "\n";
        }
    }
} else {
    echo "   ❌ .env file not found\n";
}
echo "\n";

// 3. Check storage permissions
echo "3. Checking storage permissions...\n";
$storagePath = 'storage/framework/sessions';
if (is_dir($storagePath)) {
    echo "   ✅ Sessions directory exists: $storagePath\n";
    if (is_writable($storagePath)) {
        echo "   ✅ Sessions directory is writable\n";
    } else {
        echo "   ❌ Sessions directory is not writable\n";
        echo "   Fix: chmod -R 775 storage/\n";
    }
} else {
    echo "   ❌ Sessions directory does not exist: $storagePath\n";
    echo "   Creating sessions directory...\n";
    if (mkdir($storagePath, 0755, true)) {
        echo "   ✅ Created sessions directory\n";
    } else {
        echo "   ❌ Failed to create sessions directory\n";
    }
}
echo "\n";

// 4. Check for common issues
echo "4. Checking for common issues...\n";

// Check if there are any JavaScript errors that might prevent form submission
echo "   Checking checkout form JavaScript...\n";
if (file_exists('resources/views/checkout.blade.php')) {
    $checkoutContent = file_get_contents('resources/views/checkout.blade.php');
    if (strpos($checkoutContent, 'selected_items') !== false) {
        echo "   ✅ selected_items field found in checkout form\n";
    } else {
        echo "   ❌ selected_items field not found in checkout form\n";
    }
    
    if (strpos($checkoutContent, 'checkout-form') !== false) {
        echo "   ✅ checkout-form ID found in checkout form\n";
    } else {
        echo "   ❌ checkout-form ID not found in checkout form\n";
    }
} else {
    echo "   ❌ checkout.blade.php not found\n";
}

// Check cart controller
echo "   Checking cart controller...\n";
if (file_exists('app/Http/Controllers/CartController.php')) {
    $cartController = file_get_contents('app/Http/Controllers/CartController.php');
    if (strpos($cartController, 'processCheckout') !== false) {
        echo "   ✅ processCheckout method found in CartController\n";
    } else {
        echo "   ❌ processCheckout method not found in CartController\n";
    }
    
    if (strpos($cartController, 'session(\'cart\'') !== false) {
        echo "   ✅ Session cart handling found in CartController\n";
    } else {
        echo "   ❌ Session cart handling not found in CartController\n";
    }
} else {
    echo "   ❌ CartController.php not found\n";
}

echo "\n";

// 5. Generate debugging code
echo "5. Generating debugging code...\n";
$debugCode = '<?php
// Add this to your CartController processCheckout method for debugging
Log::info("=== CART DEBUG START ===");
Log::info("Session ID: " . session()->getId());
Log::info("Session data: " . json_encode(session()->all()));
Log::info("Cart from session: " . json_encode(session("cart", [])));
Log::info("Request data: " . json_encode($request->all()));
Log::info("Selected items: " . json_encode($request->get("selected_items", [])));
Log::info("=== CART DEBUG END ===");
';

file_put_contents('cart_debug_code.php', $debugCode);
echo "   ✅ Created cart_debug_code.php with debugging code\n";

// 6. Generate fix
echo "6. Generating potential fixes...\n";
$fixCode = '<?php
// Add this to your CartController processCheckout method

// Enhanced cart validation and debugging
public function processCheckout(Request $request)
{
    // Debug logging
    Log::info("=== CHECKOUT DEBUG START ===");
    Log::info("Session ID: " . session()->getId());
    Log::info("User ID: " . Auth::id());
    Log::info("Request data: " . json_encode($request->all()));
    
    // Get cart from session
    $cart = session("cart", []);
    Log::info("Cart from session: " . json_encode($cart));
    Log::info("Cart count: " . count($cart));
    
    // Check if cart is empty
    if (empty($cart)) {
        Log::error("Cart is empty in processCheckout");
        return redirect()->route("cart")->with("error", "Your cart is empty! Please add items to your cart before checkout.");
    }
    
    // Get selected items
    $selectedItems = $request->get("selected_items", []);
    Log::info("Selected items from request: " . json_encode($selectedItems));
    
    // Handle JSON string
    if (is_string($selectedItems)) {
        $selectedItems = json_decode($selectedItems, true) ?? [];
        Log::info("Selected items after JSON decode: " . json_encode($selectedItems));
    }
    
    // If no selected items, use all cart items
    if (empty($selectedItems)) {
        $selectedItems = array_keys($cart);
        Log::info("Using all cart items as selected: " . json_encode($selectedItems));
    }
    
    // Filter cart
    $selectedCart = array_filter($cart, function($key) use ($selectedItems) {
        return in_array($key, $selectedItems);
    }, ARRAY_FILTER_USE_KEY);
    
    Log::info("Selected cart after filtering: " . json_encode($selectedCart));
    Log::info("Selected cart count: " . count($selectedCart));
    
    if (empty($selectedCart)) {
        Log::error("No items selected for checkout");
        return redirect()->route("cart")->with("error", "No items selected for checkout!");
    }
    
    // Continue with rest of checkout process...
    Log::info("=== CHECKOUT DEBUG END ===");
}
';

file_put_contents('cart_fix_code.php', $fixCode);
echo "   ✅ Created cart_fix_code.php with enhanced debugging\n";

echo "\n";
echo "🎯 Recommended Actions:\n";
echo "======================\n";
echo "1. Check the generated debug files: cart_debug_code.php and cart_fix_code.php\n";
echo "2. Add the debugging code to your CartController\n";
echo "3. Test the checkout process and check the logs\n";
echo "4. Clear your browser cache and cookies\n";
echo "5. Check browser developer console for JavaScript errors\n";
echo "6. Ensure session storage is working properly\n";
echo "\n";
echo "🔧 Quick Fixes to Try:\n";
echo "======================\n";
echo "1. Clear Laravel cache: php artisan cache:clear\n";
echo "2. Clear config cache: php artisan config:clear\n";
echo "3. Clear session files: rm -rf storage/framework/sessions/*\n";
echo "4. Restart your web server\n";
echo "5. Check if JavaScript is enabled in your browser\n";
echo "\n";
echo "✅ Debug script completed!\n";
