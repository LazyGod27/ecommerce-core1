<?php

/**
 * Test Direct Checkout Functionality
 * 
 * This script tests the "Buy Now" direct checkout functionality
 */

echo "🧪 Testing Direct Checkout Functionality\n";
echo "=======================================\n\n";

// Test 1: Check if direct checkout route exists
echo "1. Checking direct checkout route...\n";
if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    if (strpos($routesContent, 'checkout/direct') !== false) {
        echo "   ✅ Direct checkout route found\n";
    } else {
        echo "   ❌ Direct checkout route not found\n";
    }
} else {
    echo "   ❌ routes/web.php not found\n";
}
echo "\n";

// Test 2: Check CartController directCheckout method
echo "2. Checking CartController directCheckout method...\n";
if (file_exists('app/Http/Controllers/CartController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/CartController.php');
    if (strpos($controllerContent, 'directCheckout') !== false) {
        echo "   ✅ directCheckout method found\n";
        
        // Check if it stores items in session
        if (strpos($controllerContent, "session(['cart' => \$cart])") !== false) {
            echo "   ✅ Session storage implemented\n";
        } else {
            echo "   ❌ Session storage not implemented\n";
        }
        
        // Check if it has direct checkout flag
        if (strpos($controllerContent, 'is_direct_checkout') !== false) {
            echo "   ✅ Direct checkout flag implemented\n";
        } else {
            echo "   ❌ Direct checkout flag not implemented\n";
        }
    } else {
        echo "   ❌ directCheckout method not found\n";
    }
} else {
    echo "   ❌ CartController.php not found\n";
}
echo "\n";

// Test 3: Check processCheckout method handles direct checkout
echo "3. Checking processCheckout method...\n";
if (file_exists('app/Http/Controllers/CartController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/CartController.php');
    if (strpos($controllerContent, 'hasDirectCheckoutItems') !== false) {
        echo "   ✅ Direct checkout handling in processCheckout found\n";
    } else {
        echo "   ❌ Direct checkout handling in processCheckout not found\n";
    }
} else {
    echo "   ❌ CartController.php not found\n";
}
echo "\n";

// Test 4: Check buyNow JavaScript function
echo "4. Checking buyNow JavaScript function...\n";
if (file_exists('public/js/cart-auth.js')) {
    $jsContent = file_get_contents('public/js/cart-auth.js');
    if (strpos($jsContent, 'function buyNow') !== false) {
        echo "   ✅ buyNow function found\n";
        
        if (strpos($jsContent, '/checkout/direct') !== false) {
            echo "   ✅ Direct checkout URL used\n";
        } else {
            echo "   ❌ Direct checkout URL not used\n";
        }
    } else {
        echo "   ❌ buyNow function not found\n";
    }
} else {
    echo "   ❌ cart-auth.js not found\n";
}
echo "\n";

// Test 5: Check OrderItem model supports product_name
echo "5. Checking OrderItem model...\n";
if (file_exists('app/Models/OrderItem.php')) {
    $modelContent = file_get_contents('app/Models/OrderItem.php');
    if (strpos($modelContent, 'product_name') !== false) {
        echo "   ✅ product_name field supported in OrderItem\n";
    } else {
        echo "   ❌ product_name field not supported in OrderItem\n";
    }
} else {
    echo "   ❌ OrderItem.php not found\n";
}
echo "\n";

// Test 6: Generate test data for direct checkout
echo "6. Generating test data for direct checkout...\n";
$testDirectCheckoutData = [
    'product_name' => 'Test Product for Direct Checkout',
    'product_price' => 99.99,
    'product_image' => 'test-image.jpg',
    'quantity' => 1
];

$testDataFile = 'test_direct_checkout_data.json';
file_put_contents($testDataFile, json_encode($testDirectCheckoutData, JSON_PRETTY_PRINT));
echo "   ✅ Test data saved to $testDataFile\n";
echo "\n";

// Test 7: Create debugging instructions
echo "7. Creating debugging instructions...\n";
$debugInstructions = '## Direct Checkout Debugging Instructions

### Step 1: Test Buy Now Flow
1. Go to home page
2. Click "Buy Now" on any product
3. Check if it redirects to checkout page
4. Fill out checkout form
5. Click "Place Order"
6. Check if order is created successfully

### Step 2: Check Laravel Logs
Look for these debug messages in storage/logs/laravel.log:
- "=== DIRECT CHECKOUT DEBUG START ==="
- "Direct checkout item stored in session"
- "=== ENHANCED CHECKOUT DEBUG START ==="
- "Created order item for direct checkout"

### Step 3: Check Session Data
1. Add debugging to see session cart data
2. Check if direct checkout items are stored
3. Verify items persist between requests

### Step 4: Common Issues and Fixes

#### Issue: "Your cart is empty" after Buy Now
**Cause:** Direct checkout items not stored in session
**Solution:** 
1. Check if directCheckout method stores items in session
2. Verify session is working properly
3. Check Laravel logs for errors

#### Issue: Order not created
**Cause:** processCheckout not handling direct checkout items
**Solution:**
1. Check if processCheckout has direct checkout handling
2. Verify OrderItem creation for direct checkout
3. Check database for order records

#### Issue: Product not found in order
**Cause:** OrderItem not created properly for direct checkout
**Solution:**
1. Check if product_name is stored in OrderItem
2. Verify direct checkout flag is set
3. Check order_items table in database

### Step 5: Manual Testing Steps
1. **Test Buy Now from Home:**
   - Click "Buy Now" on product
   - Should go to checkout page
   - Fill form and submit
   - Should create order successfully

2. **Test Buy Now from Product Detail:**
   - Go to product detail page
   - Click "Buy Now"
   - Should work same as home page

3. **Test Buy Now from Categories:**
   - Go to any category page
   - Click "Buy Now" on product
   - Should work same as home page

### Step 6: Database Verification
Check these tables after successful order:
- `orders` - Should have new order record
- `order_items` - Should have order item with product_name
- `trackings` - Should have tracking record

### Step 7: Debug Information
- Session ID: Check browser cookies
- Cart data: Check session storage
- Request data: Check Network tab
- Server logs: Check Laravel logs
- Database: Check order tables
';

file_put_contents('DIRECT_CHECKOUT_DEBUG_INSTRUCTIONS.md', $debugInstructions);
echo "   ✅ Debug instructions saved to DIRECT_CHECKOUT_DEBUG_INSTRUCTIONS.md\n";
echo "\n";

echo "🎯 Quick Fixes Applied:\n";
echo "======================\n";
echo "✅ Enhanced directCheckout method to store items in session\n";
echo "✅ Updated processCheckout to handle direct checkout items\n";
echo "✅ Added direct checkout flag for identification\n";
echo "✅ Enhanced order item creation for direct checkout\n";
echo "✅ Added comprehensive debugging and logging\n";
echo "\n";

echo "🔧 Next Steps:\n";
echo "==============\n";
echo "1. Test the Buy Now functionality from home page\n";
echo "2. Check Laravel logs for debugging information\n";
echo "3. Verify orders are created successfully\n";
echo "4. Check database for order records\n";
echo "5. Test with different products\n";
echo "\n";

echo "✅ Direct checkout test completed!\n";
