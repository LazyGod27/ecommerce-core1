<?php

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
}