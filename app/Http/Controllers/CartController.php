<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        
        
        $subtotal = collect($cart)->sum('price');
        $shippingCost = $this->calculateShippingCost($subtotal);
        $tax = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $tax;
        
        // Get user profile information for auto-filling
        $user = Auth::user();
        $userPhone = $user ? $user->phone : '';
        $userEmail = $user ? $user->email : '';
        $userAddress = $user ? ($user->address_line1 . ', ' . $user->city . ', ' . $user->state . ' ' . $user->postal_code) : '';
        
        return view('cart', compact('cart', 'subtotal', 'shippingCost', 'tax', 'total', 'userPhone', 'userEmail', 'userAddress'));
    }

    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Try to find the product in the database first
        $product = Product::find($productId);
        
        // If product exists in database, use it for validation
        if ($product) {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:' . $product->stock
            ]);
            
            $productName = $product->name;
            $productPrice = $product->price;
            $productImage = $product->image ?? 'default.jpg';
            $productStock = $product->stock;
        } else {
            // For demo products that don't exist in database, get data from request
            $productName = $request->input('product_name', 'Demo Product');
            $productPrice = $request->input('product_price', 100);
            $productImage = $request->input('product_image', 'default.jpg');
            $productStock = 999; // Set high stock for demo products
        }

        $cart = session('cart', []);
        
        // Check if product already exists in cart
        $existingItem = null;
        foreach ($cart as $rowId => $item) {
            if ($item['id'] == $productId) {
                $existingItem = $rowId;
                break;
            }
        }
        
        if ($existingItem) {
            // Update existing item quantity
            $newQuantity = $cart[$existingItem]['quantity'] + $request->quantity;
            if ($newQuantity > $productStock) {
                return redirect()->back()->with('error', 'Quantity exceeds available stock!');
            }
            $cart[$existingItem]['quantity'] = $newQuantity;
            $cart[$existingItem]['price'] = $productPrice * $newQuantity;
        } else {
            // Add new item
            $rowId = uniqid();
            $cart[$rowId] = [
                'rowId' => $rowId,
                'id' => $productId,
                'name' => $productName,
                'price' => $productPrice * $request->quantity,
                'quantity' => $request->quantity,
                'image' => $productImage,
                'stock' => $productStock
            ];
        }
        
        session(['cart' => $cart]);
        
        
        return redirect()->route('cart')->with('success', 'Product added to cart!');
    }

    public function remove($rowId)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            session(['cart' => $cart]);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Item removed from cart!']);
            }
            
            return redirect()->route('cart')->with('success', 'Item removed from cart!');
        }
        
        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart!'], 404);
        }
        
        return redirect()->route('cart')->with('error', 'Item not found in cart!');
    }

    public function update(Request $request, $rowId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session('cart', []);
        
        if (isset($cart[$rowId])) {
            $product = Product::find($cart[$rowId]['id']);
            
            if (!$product) {
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Product not found!'], 404);
                }
                return redirect()->route('cart')->with('error', 'Product not found!');
            }
            
            if ($request->quantity > $product->stock) {
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Quantity exceeds available stock!'], 400);
                }
                return redirect()->route('cart')->with('error', 'Quantity exceeds available stock!');
            }
            
            $cart[$rowId]['quantity'] = $request->quantity;
            $cart[$rowId]['price'] = $product->price * $request->quantity;
            session(['cart' => $cart]);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Cart updated!']);
            }
            
            return redirect()->route('cart')->with('success', 'Cart updated!');
        }
        
        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart!'], 404);
        }
        
        return redirect()->route('cart')->with('error', 'Item not found in cart!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart')->with('success', 'Cart cleared!');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }
        
        $subtotal = collect($cart)->sum('price');
        $shippingCost = $this->calculateShippingCost($subtotal);
        $tax = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $tax;
        
        // Get user profile information for auto-filling
        $user = Auth::user();
        $userPhone = $user ? $user->phone : '';
        $userEmail = $user ? $user->email : '';
        $userAddress = $user ? ($user->address_line1 . ', ' . $user->city . ', ' . $user->state . ' ' . $user->postal_code) : '';
        
        return view('checkout', compact('cart', 'subtotal', 'shippingCost', 'tax', 'total', 'userPhone', 'userEmail', 'userAddress', 'user'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:GCash,PayMaya,Card,Cash on Delivery',
            'shipping_address' => 'required|string|max:500',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email'
        ]);

        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        // Validate stock availability
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                // Real product from database - check stock
                if ($product->stock < $item['quantity']) {
                    return redirect()->route('cart')->with('error', "Insufficient stock for {$product->name}!");
                }
            }
            // Demo products (not in database) are assumed to have sufficient stock
        }

        try {
            DB::beginTransaction();

            $subtotal = collect($cart)->sum('price');
            $shippingCost = $this->calculateShippingCost($subtotal);
            $tax = $this->calculateTax($subtotal);
            $total = $subtotal + $shippingCost + $tax;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'total' => $total,
                'notes' => $request->notes ?? null
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] / $item['quantity'], // Unit price
                    'total' => $item['price']
                ]);

                // Update product stock (only for real products in database)
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Create tracking record
            Tracking::create([
                'order_id' => $order->id,
                'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
                'carrier' => 'Standard Shipping',
                'status' => 'pending',
                'estimated_delivery' => now()->addDays(3),
                'current_location' => 'Processing Center',
                'tracking_details' => [
                    [
                        'status' => 'Order Placed',
                        'location' => 'Processing Center',
                        'timestamp' => now()->toISOString(),
                        'description' => 'Your order has been received and is being processed.'
                    ]
                ]
            ]);

            DB::commit();

            // Clear cart
            session()->forget('cart');

            // For now, always redirect to order confirmation
            // In a real application, you would handle different payment methods differently
            return redirect()->route('order.confirmation', $order->id)
                ->with('success', 'Order placed successfully! You will receive a confirmation email shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('cart')->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    private function calculateShippingCost($subtotal)
    {
        // Free shipping for orders over ₱1000
        if ($subtotal >= 1000) {
            return 0;
        }
        return 50; // Standard shipping cost
    }

    private function calculateTax($subtotal)
    {
        return $subtotal * 0.12; // 12% VAT
    }

    public function getCartCount()
    {
        $cart = session('cart', []);
        $count = collect($cart)->sum('quantity');
        
        return response()->json(['count' => $count]);
    }
}
