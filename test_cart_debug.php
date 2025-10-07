<?php

/**
 * Test Cart Debug Script
 * 
 * This script helps test and debug the cart functionality
 */

echo "🧪 Testing Cart Functionality\n";
echo "=============================\n\n";

// Test 1: Check if we can access the cart route
echo "1. Testing cart route accessibility...\n";
$cartUrl = 'http://localhost/ecommerce-core1/cart';
$response = @file_get_contents($cartUrl);
if ($response !== false) {
    echo "   ✅ Cart page is accessible\n";
} else {
    echo "   ❌ Cart page is not accessible\n";
    echo "   Make sure your web server is running and the URL is correct\n";
}
echo "\n";

// Test 2: Check session configuration
echo "2. Checking session configuration...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'SESSION_DRIVER') !== false) {
        echo "   ✅ SESSION_DRIVER is configured\n";
    } else {
        echo "   ⚠️  SESSION_DRIVER not found in .env\n";
    }
    
    if (strpos($envContent, 'CACHE_DRIVER') !== false) {
        echo "   ✅ CACHE_DRIVER is configured\n";
    } else {
        echo "   ⚠️  CACHE_DRIVER not found in .env\n";
    }
} else {
    echo "   ❌ .env file not found\n";
}
echo "\n";

// Test 3: Check storage permissions
echo "3. Checking storage permissions...\n";
$storagePath = 'storage/framework/sessions';
if (is_dir($storagePath)) {
    if (is_writable($storagePath)) {
        echo "   ✅ Sessions directory is writable\n";
    } else {
        echo "   ❌ Sessions directory is not writable\n";
        echo "   Fix: chmod -R 775 storage/\n";
    }
} else {
    echo "   ❌ Sessions directory does not exist\n";
}
echo "\n";

// Test 4: Check cart JavaScript
echo "4. Checking cart JavaScript...\n";
if (file_exists('resources/views/cart.blade.php')) {
    $cartContent = file_get_contents('resources/views/cart.blade.php');
    if (strpos($cartContent, 'proceedToCheckout') !== false) {
        echo "   ✅ proceedToCheckout function found\n";
    } else {
        echo "   ❌ proceedToCheckout function not found\n";
    }
    
    if (strpos($cartContent, 'selected_items') !== false) {
        echo "   ✅ selected_items handling found\n";
    } else {
        echo "   ❌ selected_items handling not found\n";
    }
} else {
    echo "   ❌ cart.blade.php not found\n";
}
echo "\n";

// Test 5: Check checkout form
echo "5. Checking checkout form...\n";
if (file_exists('resources/views/checkout.blade.php')) {
    $checkoutContent = file_get_contents('resources/views/checkout.blade.php');
    if (strpos($checkoutContent, 'selected_items') !== false) {
        echo "   ✅ selected_items field found in checkout form\n";
    } else {
        echo "   ❌ selected_items field not found in checkout form\n";
    }
    
    if (strpos($checkoutContent, 'checkout-form') !== false) {
        echo "   ✅ checkout-form ID found\n";
    } else {
        echo "   ❌ checkout-form ID not found\n";
    }
} else {
    echo "   ❌ checkout.blade.php not found\n";
}
echo "\n";

// Test 6: Generate test data
echo "6. Generating test cart data...\n";
$testCartData = [
    'test_item_1' => [
        'rowId' => 'test_item_1',
        'id' => 1,
        'name' => 'Test Product 1',
        'price' => 100.00,
        'quantity' => 2,
        'image' => 'test-image.jpg',
        'stock' => 10
    ],
    'test_item_2' => [
        'rowId' => 'test_item_2',
        'id' => 2,
        'name' => 'Test Product 2',
        'price' => 50.00,
        'quantity' => 1,
        'image' => 'test-image2.jpg',
        'stock' => 5
    ]
];

$testDataFile = 'test_cart_data.json';
file_put_contents($testDataFile, json_encode($testCartData, JSON_PRETTY_PRINT));
echo "   ✅ Test cart data saved to $testDataFile\n";
echo "\n";

// Test 7: Create debugging instructions
echo "7. Creating debugging instructions...\n";
$debugInstructions = '## Cart Debugging Instructions

### Step 1: Check Browser Console
1. Open your browser\'s developer tools (F12)
2. Go to the Console tab
3. Try to add items to cart and proceed to checkout
4. Look for any JavaScript errors

### Step 2: Check Laravel Logs
1. Check storage/logs/laravel.log for any errors
2. Look for the debug messages we added to CartController

### Step 3: Test Cart Functionality
1. Add items to cart
2. Check if cart displays correctly
3. Try to proceed to checkout
4. Check if selected items are passed correctly

### Step 4: Common Issues and Fixes

#### Issue: Cart appears empty on checkout
**Possible Causes:**
- Session not working properly
- JavaScript errors preventing form submission
- CSRF token issues
- Cart data not being passed correctly

**Fixes:**
1. Clear browser cache and cookies
2. Check if JavaScript is enabled
3. Verify session configuration
4. Check Laravel logs for errors

#### Issue: Selected items not passed to checkout
**Possible Causes:**
- JavaScript not selecting items properly
- Form not submitting selected items
- Server not receiving selected items

**Fixes:**
1. Check cart.blade.php JavaScript
2. Verify checkout form has selected_items field
3. Check CartController debugging logs

### Step 5: Manual Testing
1. Add items to cart
2. Select items using checkboxes
3. Click "Proceed to Checkout"
4. Check if items appear on checkout page
5. Fill out checkout form and submit
6. Check if order is created successfully

### Step 6: Debug Information
- Session ID: Check browser cookies
- Cart data: Check browser developer tools > Application > Local Storage
- Request data: Check Network tab in developer tools
- Server logs: Check storage/logs/laravel.log
';

file_put_contents('CART_DEBUG_INSTRUCTIONS.md', $debugInstructions);
echo "   ✅ Debug instructions saved to CART_DEBUG_INSTRUCTIONS.md\n";
echo "\n";

echo "🎯 Quick Fixes to Try:\n";
echo "======================\n";
echo "1. Clear browser cache and cookies\n";
echo "2. Check browser developer console for JavaScript errors\n";
echo "3. Verify session storage is working\n";
echo "4. Check if CSRF token is being sent\n";
echo "5. Test with different browsers\n";
echo "6. Check Laravel logs for debugging information\n";
echo "\n";

echo "🔧 Next Steps:\n";
echo "==============\n";
echo "1. Test the cart functionality step by step\n";
echo "2. Check the generated debug files\n";
echo "3. Follow the debugging instructions\n";
echo "4. Check Laravel logs for detailed information\n";
echo "\n";

echo "✅ Test script completed!\n";
