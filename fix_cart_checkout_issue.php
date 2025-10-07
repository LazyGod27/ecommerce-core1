<?php

/**
 * Fix Cart Checkout Issue
 * 
 * This script fixes the common cart empty issue during checkout
 */

echo "🔧 Fixing Cart Checkout Issue\n";
echo "=============================\n\n";

// 1. Clear Laravel caches
echo "1. Clearing Laravel caches...\n";
$commands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan view:clear'
];

foreach ($commands as $command) {
    echo "   Running: $command\n";
    $output = [];
    $returnCode = 0;
    exec($command . ' 2>&1', $output, $returnCode);
    if ($returnCode === 0) {
        echo "   ✅ Success\n";
    } else {
        echo "   ⚠️  Warning: " . implode("\n", $output) . "\n";
    }
}
echo "\n";

// 2. Check and fix session storage
echo "2. Checking session storage...\n";
$sessionPath = 'storage/framework/sessions';
if (!is_dir($sessionPath)) {
    echo "   Creating session directory...\n";
    if (mkdir($sessionPath, 0755, true)) {
        echo "   ✅ Session directory created\n";
    } else {
        echo "   ❌ Failed to create session directory\n";
    }
} else {
    echo "   ✅ Session directory exists\n";
}

// Clear session files
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "   ✅ Cleared existing session files\n";
}
echo "\n";

// 3. Create enhanced CartController fix
echo "3. Creating enhanced CartController fix...\n";

$enhancedCartController = '<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // ... existing methods ...

    public function processCheckout(Request $request)
    {
        // Enhanced debugging and validation
        Log::info("=== ENHANCED CHECKOUT DEBUG START ===");
        Log::info("Session ID: " . session()->getId());
        Log::info("User ID: " . (Auth::id() ?? "null"));
        Log::info("Request URL: " . $request->fullUrl());
        Log::info("Request method: " . $request->method());
        Log::info("Request data: " . json_encode($request->all()));
        
        // Get cart from session with fallback
        $cart = session("cart", []);
        Log::info("Cart from session: " . json_encode($cart));
        Log::info("Cart count: " . count($cart));
        
        // Enhanced cart validation
        if (empty($cart)) {
            Log::error("Cart is empty in processCheckout");
            
            // Try to get cart from alternative sources
            $cartFromRequest = $request->get("cart", []);
            if (!empty($cartFromRequest)) {
                Log::info("Found cart in request data: " . json_encode($cartFromRequest));
                $cart = $cartFromRequest;
            } else {
                return redirect()->route("cart")->with("error", "Your cart is empty! Please add items to your cart before checkout.");
            }
        }
        
        // Get selected items with enhanced handling
        $selectedItems = $request->get("selected_items", []);
        Log::info("Raw selected items: " . json_encode($selectedItems));
        
        // Handle different formats of selected items
        if (is_string($selectedItems)) {
            $selectedItems = json_decode($selectedItems, true) ?? [];
            Log::info("Selected items after JSON decode: " . json_encode($selectedItems));
        }
        
        // If selected_items is not an array, try to convert it
        if (!is_array($selectedItems)) {
            $selectedItems = [];
        }
        
        // If no selected items provided, use all cart items
        if (empty($selectedItems)) {
            $selectedItems = array_keys($cart);
            Log::info("Using all cart items as selected: " . json_encode($selectedItems));
        }
        
        // Filter cart to only include selected items
        $selectedCart = array_filter($cart, function($key) use ($selectedItems) {
            return in_array($key, $selectedItems);
        }, ARRAY_FILTER_USE_KEY);
        
        Log::info("Selected cart after filtering: " . json_encode($selectedCart));
        Log::info("Selected cart count: " . count($selectedCart));
        
        if (empty($selectedCart)) {
            Log::error("No items selected for checkout");
            return redirect()->route("cart")->with("error", "No items selected for checkout! Please select items and try again.");
        }
        
        // Validate request data
        try {
            $request->validate([
                "payment_method" => "required|string|in:GCash,PayMaya,Card,Cash on Delivery,COD",
                "shipping_address" => "required|string|max:500",
                "shipping_phone" => "required|string|max:20",
                "shipping_name" => "required|string|max:255",
                "shipping_city" => "required|string|max:100",
                "shipping_postal" => "required|string|max:20"
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation failed", [
                "user_id" => Auth::id(),
                "errors" => $e->errors(),
                "request_data" => $request->all()
            ]);
            return redirect()->route("checkout")->withErrors($e->errors())->withInput();
        }
        
        // Validate stock availability for selected items
        foreach ($selectedCart as $item) {
            if (isset($item["id"]) && is_numeric($item["id"])) {
                $product = Product::find($item["id"]);
                if ($product) {
                    if ($product->stock < $item["quantity"]) {
                        return redirect()->route("cart")->with("error", "Insufficient stock for {$product->name}!");
                    }
                }
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate totals
            $subtotal = collect($selectedCart)->sum(function($item) {
                return $item["price"];
            });
            $shippingCost = $this->calculateShippingCost($subtotal);
            $tax = $this->calculateTax($subtotal);
            $total = $subtotal + $shippingCost + $tax;
            
            // Create order
            $order = Order::create([
                "user_id" => Auth::id(),
                "order_number" => "ORD-" . strtoupper(Str::random(8)),
                "status" => "pending",
                "shipping_address" => $request->shipping_address,
                "contact_number" => $request->shipping_phone,
                "email" => $request->shipping_email ?? Auth::user()->email,
                "payment_method" => $request->payment_method,
                "payment_status" => "pending",
                "subtotal" => $subtotal,
                "tax" => $tax,
                "shipping_cost" => $shippingCost,
                "total" => $total,
                "notes" => $request->notes ?? ""
            ]);
            
            // Create order items
            foreach ($selectedCart as $item) {
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $item["id"] ?? null,
                    "product_name" => $item["name"],
                    "quantity" => $item["quantity"],
                    "price" => $item["price"] / $item["quantity"] // Convert back to unit price
                ]);
            }
            
            // Remove selected items from cart
            $remainingCart = array_diff_key($cart, $selectedCart);
            session(["cart" => $remainingCart]);
            
            DB::commit();
            
            Log::info("Order created successfully", [
                "order_id" => $order->id,
                "order_number" => $order->order_number,
                "total" => $total
            ]);
            
            return redirect()->route("order.confirmation", $order->id)
                ->with("success", "Order placed successfully! Order #" . $order->order_number);
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Order creation failed", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
            return redirect()->route("checkout")->with("error", "Failed to place order. Please try again.");
        }
    }
    
    // ... rest of existing methods ...
}';

file_put_contents('enhanced_cart_controller.php', $enhancedCartController);
echo "   ✅ Created enhanced_cart_controller.php\n";

// 4. Create checkout form fix
echo "4. Creating checkout form fix...\n";

$checkoutFormFix = '<!-- Add this to your checkout.blade.php form -->
<form method="POST" action="{{ route("checkout.process") }}" id="checkout-form">
    @csrf
    <input type="hidden" name="payment_method" id="payment_method">
    <input type="hidden" name="selected_items" id="selected_items" value="{{ json_encode(array_keys($selectedCart)) }}">
    
    <!-- Add debugging information -->
    <input type="hidden" name="debug_cart" value="{{ json_encode($selectedCart) }}">
    <input type="hidden" name="debug_session_id" value="{{ session()->getId() }}">
    
    <!-- Rest of your form fields -->
</form>

<script>
// Enhanced form submission with debugging
document.getElementById("checkout-form").addEventListener("submit", function(e) {
    console.log("=== CHECKOUT FORM DEBUG ===");
    console.log("Form submission started");
    console.log("Payment method:", document.getElementById("payment_method").value);
    console.log("Selected items:", document.getElementById("selected_items").value);
    console.log("Debug cart:", document.querySelector("[name=debug_cart]").value);
    console.log("Session ID:", document.querySelector("[name=debug_session_id]").value);
    
    // Validate payment method
    if (!document.getElementById("payment_method").value) {
        e.preventDefault();
        alert("Please select a payment method before placing your order.");
        return false;
    }
    
    // Validate selected items
    const selectedItems = JSON.parse(document.getElementById("selected_items").value);
    if (!selectedItems || selectedItems.length === 0) {
        e.preventDefault();
        alert("No items selected for checkout. Please go back to cart and select items.");
        return false;
    }
    
    console.log("Form validation passed, submitting...");
    return true;
});
</script>';

file_put_contents('checkout_form_fix.html', $checkoutFormFix);
echo "   ✅ Created checkout_form_fix.html\n";

// 5. Create cart JavaScript fix
echo "5. Creating cart JavaScript fix...\n";

$cartJsFix = '// Enhanced cart JavaScript with debugging
function proceedToCheckout() {
    console.log("=== CART CHECKOUT DEBUG ===");
    
    const selectedItems = [];
    const checkboxes = document.querySelectorAll(".item-checkbox:checked");
    
    console.log("Found checkboxes:", checkboxes.length);
    
    if (checkboxes.length === 0) {
        alert("Please select at least one item to proceed to checkout.");
        return;
    }
    
    checkboxes.forEach(checkbox => {
        const cartItem = checkbox.closest(".cart-item");
        const rowId = cartItem.getAttribute("data-row-id");
        console.log("Adding item to checkout:", rowId);
        selectedItems.push(rowId);
    });
    
    console.log("Selected items for checkout:", selectedItems);
    
    // Create form with enhanced debugging
    const form = document.createElement("form");
    form.method = "GET";
    form.action = "{{ route("checkout") }}";
    
    // Add selected items
    selectedItems.forEach((item, index) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = `selected_items[${index}]`;
        input.value = item;
        form.appendChild(input);
    });
    
    // Add debugging information
    const debugInput = document.createElement("input");
    debugInput.type = "hidden";
    debugInput.name = "debug_selected_count";
    debugInput.value = selectedItems.length;
    form.appendChild(debugInput);
    
    console.log("Submitting form to checkout...");
    document.body.appendChild(form);
    form.submit();
}

// Enhanced cart update function
function updateCart() {
    console.log("Updating cart display...");
    
    const cartItems = document.querySelectorAll(".cart-item");
    let selectedCount = 0;
    
    cartItems.forEach(item => {
        const checkbox = item.querySelector(".item-checkbox");
        if (checkbox && checkbox.checked) {
            selectedCount++;
        }
    });
    
    console.log("Selected items count:", selectedCount);
    
    // Update UI
    const countElement = document.getElementById("checkout-items-count");
    if (countElement) {
        countElement.textContent = selectedCount;
    }
    
    // Update checkout button
    const checkoutButton = document.getElementById("checkout-button");
    if (checkoutButton) {
        checkoutButton.disabled = selectedCount === 0;
        if (selectedCount === 0) {
            checkoutButton.style.opacity = "0.5";
            checkoutButton.style.cursor = "not-allowed";
        } else {
            checkoutButton.style.opacity = "1";
            checkoutButton.style.cursor = "pointer";
        }
    }
}';

file_put_contents('cart_js_fix.js', $cartJsFix);
echo "   ✅ Created cart_js_fix.js\n";

echo "\n";
echo "🎯 Quick Fixes Applied:\n";
echo "======================\n";
echo "✅ Cleared Laravel caches\n";
echo "✅ Cleared session files\n";
echo "✅ Created enhanced debugging code\n";
echo "✅ Created form fixes\n";
echo "✅ Created JavaScript fixes\n";
echo "\n";
echo "📋 Next Steps:\n";
echo "==============\n";
echo "1. Replace your CartController processCheckout method with the enhanced version\n";
echo "2. Update your checkout.blade.php form with the debugging code\n";
echo "3. Update your cart JavaScript with the enhanced version\n";
echo "4. Test the checkout process\n";
echo "5. Check browser console for any JavaScript errors\n";
echo "6. Check Laravel logs for debugging information\n";
echo "\n";
echo "🔧 Additional Troubleshooting:\n";
echo "==============================\n";
echo "1. Check if JavaScript is enabled in your browser\n";
echo "2. Clear browser cache and cookies\n";
echo "3. Check browser developer console for errors\n";
echo "4. Ensure session storage is working\n";
echo "5. Check if CSRF token is being sent correctly\n";
echo "\n";
echo "✅ Fix script completed!\n";
