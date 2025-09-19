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
    
    public function saveForLater($rowId)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$rowId])) {
            $item = $cart[$rowId];
            
            // Remove from cart
            unset($cart[$rowId]);
            session(['cart' => $cart]);
            
            // In a real application, you would save to a wishlist table
            // For now, we'll just show a success message
            return redirect()->route('cart')->with('success', 'Item saved for later!');
        }
        
        return redirect()->route('cart')->with('error', 'Item not found in cart.');
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }
        
        // Get selected items from request or session storage
        $selectedItems = $request->get('selected_items', []);
        
        // If no selected items provided, use all cart items (fallback)
        if (empty($selectedItems)) {
            $selectedItems = array_keys($cart);
        }
        
        // Filter cart to only include selected items
        $selectedCart = array_filter($cart, function($key) use ($selectedItems) {
            return in_array($key, $selectedItems);
        }, ARRAY_FILTER_USE_KEY);
        
        if (empty($selectedCart)) {
            return redirect()->route('cart')->with('error', 'No items selected for checkout!');
        }
        
        $subtotal = collect($selectedCart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        $shippingCost = $this->calculateShippingCost($subtotal);
        $tax = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $tax;
        
        // Get user profile information for auto-filling
        $user = Auth::user();
        $userPhone = $user ? $user->phone : '';
        $userEmail = $user ? $user->email : '';
        $userAddress = $user ? ($user->address_line1 . ', ' . $user->city . ', ' . $user->state . ' ' . $user->postal_code) : '';
        
        return view('checkout', compact('selectedCart', 'subtotal', 'shippingCost', 'tax', 'total', 'userPhone', 'userEmail', 'userAddress', 'user'));
    }

    public function processCheckout(Request $request)
    {
        Log::info('Checkout process started', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        try {
            $request->validate([
                'payment_method' => 'required|string|in:GCash,PayMaya,Card,Cash on Delivery,COD',
                'shipping_address' => 'required|string|max:500',
                'shipping_phone' => 'required|string|max:20',
                'shipping_name' => 'required|string|max:255',
                'shipping_city' => 'required|string|max:100',
                'shipping_postal' => 'required|string|max:20'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('checkout')->withErrors($e->errors())->withInput();
        }

        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        // Get selected items from the checkout form
        $selectedItems = $request->get('selected_items', []);
        
        Log::info('Selected items from request', [
            'raw_selected_items' => $selectedItems,
            'type' => gettype($selectedItems)
        ]);
        
        // If selected_items is a JSON string, decode it
        if (is_string($selectedItems)) {
            $selectedItems = json_decode($selectedItems, true) ?? [];
        }
        
        Log::info('Selected items after processing', [
            'selected_items' => $selectedItems,
            'cart_keys' => array_keys($cart)
        ]);
        
        // If no selected items provided, use all cart items (fallback)
        if (empty($selectedItems)) {
            $selectedItems = array_keys($cart);
        }
        
        // Filter cart to only include selected items
        $selectedCart = array_filter($cart, function($key) use ($selectedItems) {
            return in_array($key, $selectedItems);
        }, ARRAY_FILTER_USE_KEY);

        if (empty($selectedCart)) {
            return redirect()->route('cart')->with('error', 'No items selected for checkout!');
        }

        // Validate stock availability for selected items only
        foreach ($selectedCart as $item) {
            if (isset($item['id'])) {
                $product = Product::find($item['id']);
                if ($product) {
                    // Real product from database - check stock
                    if ($product->stock < $item['quantity']) {
                        return redirect()->route('cart')->with('error', "Insufficient stock for {$product->name}!");
                    }
                }
            }
            // Demo products (not in database) are assumed to have sufficient stock
        }

        try {
            DB::beginTransaction();

            // In session, item['price'] already stores the line total (unit_price * quantity)
            $subtotal = collect($selectedCart)->sum(function($item) {
                return $item['price'];
            });
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
            foreach ($selectedCart as $key => $item) {
                $product = isset($item['id']) ? Product::find($item['id']) : null;

                if (!$product) {
                    // Skip items that don't map to a real product record
                    Log::warning('Skipping cart item without valid product during checkout', [
                        'cart_key' => $key,
                        'item' => $item,
                    ]);
                    continue;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    // Store unit price in the order_items.price column
                    'price' => $item['quantity'] > 0 ? ($item['price'] / $item['quantity']) : 0,
                ]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            // Create tracking record (schema uses 'history' JSON column)
            Tracking::create([
                'order_id' => $order->id,
                'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
                'carrier' => 'Standard Shipping',
                'status' => 'pending',
                'estimated_delivery' => now()->addDays(3),
                'history' => [
                    [
                        'status' => 'Order Placed',
                        'location' => 'Processing Center',
                        'timestamp' => now()->toISOString(),
                        'description' => 'Your order has been received and is being processed.'
                    ]
                ]
            ]);

            DB::commit();

            // Remove only selected items from cart
            $remainingCart = array_diff_key($cart, $selectedCart);
            if (empty($remainingCart)) {
                session()->forget('cart');
            } else {
                session(['cart' => $remainingCart]);
            }

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total
            ]);

            // For now, always redirect to order confirmation
            // In a real application, you would handle different payment methods differently
            return redirect()->route('order.confirmation', $order->id)
                ->with('success', 'Order placed successfully! You will receive a confirmation email shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout')->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
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
    
    public function orderConfirmation($orderId)
    {
        $order = Order::with(['items.product', 'tracking'])->findOrFail($orderId);
        
        // Check if the order belongs to the authenticated user
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access to order details.');
        }
        
        return view('order-confirmation', compact('order'));
    }
    
    public function getSimilarProducts($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // Get categories from ordered products
        $categories = $order->items->pluck('product.category')->filter()->unique();
        
        // Get similar products based on categories
        $similarProducts = Product::whereIn('category', $categories)
            ->where('id', '!=', $order->items->pluck('product_id')->filter())
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        // If not enough products from same categories, get random popular products
        if ($similarProducts->count() < 4) {
            $additionalProducts = Product::whereNotIn('id', $similarProducts->pluck('id'))
                ->inRandomOrder()
                ->limit(4 - $similarProducts->count())
                ->get();
            
            $similarProducts = $similarProducts->merge($additionalProducts);
        }
        
        return response()->json([
            'products' => $similarProducts->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'rating' => rand(4, 5), // Mock rating
                    'reviews_count' => rand(10, 50), // Mock reviews count
                ];
            })
        ]);
    }
}
