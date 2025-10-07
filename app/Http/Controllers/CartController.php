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
        
        // Calculate subtotal properly (price * quantity for each item)
        $subtotal = collect($cart)->sum(function($item) {
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
        
        // Check if user wants to go directly to checkout
        if ($request->has('redirect_to_checkout')) {
            return redirect()->route('cart')->with('success', 'Product added to cart!')->with('checkout', true);
        }
        
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
        // Enhanced debugging for checkout
        Log::info('=== CHECKOUT PAGE DEBUG START ===');
        Log::info('Session ID: ' . session()->getId());
        Log::info('User ID: ' . (Auth::id() ?? 'null'));
        Log::info('Request data: ' . json_encode($request->all()));
        
        $cart = session('cart', []);
        Log::info('Cart from session: ' . json_encode($cart));
        Log::info('Cart count: ' . count($cart));
        
        if (empty($cart)) {
            Log::error('Cart is empty in checkout method');
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }
        
        // Get selected items from request or session storage
        $selectedItems = $request->get('selected_items', []);
        Log::info('Selected items from request: ' . json_encode($selectedItems));
        
        // If no selected items provided, use all cart items (fallback)
        if (empty($selectedItems)) {
            $selectedItems = array_keys($cart);
            Log::info('Using all cart items as selected: ' . json_encode($selectedItems));
        }
        
        // Filter cart to only include selected items
        $selectedCart = array_filter($cart, function($key) use ($selectedItems) {
            return in_array($key, $selectedItems);
        }, ARRAY_FILTER_USE_KEY);
        
        Log::info('Selected cart after filtering: ' . json_encode($selectedCart));
        Log::info('Selected cart count: ' . count($selectedCart));
        
        if (empty($selectedCart)) {
            Log::error('No items selected for checkout in checkout method');
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

    public function directCheckout(Request $request)
    {
        Log::info('=== DIRECT CHECKOUT DEBUG START ===');
        Log::info('Request data: ' . json_encode($request->all()));
        
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric|min:0',
            'product_image' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        // Create a temporary cart item for direct checkout
        $productId = 'direct_' . time();
        $directCartItem = [
            'rowId' => $productId,
            'id' => $productId,
            'name' => $request->product_name,
            'price' => $request->product_price * $request->quantity,
            'quantity' => $request->quantity,
            'image' => $request->product_image,
            'stock' => 999, // Set high stock for direct checkout
            'is_direct_checkout' => true // Flag to identify direct checkout items
        ];

        // Store the direct checkout item in session
        $cart = session('cart', []);
        $cart[$productId] = $directCartItem;
        session(['cart' => $cart]);
        
        Log::info('Direct checkout item stored in session: ' . json_encode($directCartItem));
        Log::info('Updated cart: ' . json_encode($cart));

        // Create selected cart for checkout view
        $selectedCart = [$productId => $directCartItem];

        $subtotal = $request->product_price * $request->quantity;
        $shippingCost = $this->calculateShippingCost($subtotal);
        $tax = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $tax;
        
        // Get user profile information for auto-filling
        $user = Auth::user();
        $userPhone = $user ? $user->phone : '';
        $userEmail = $user ? $user->email : '';
        $userAddress = $user ? ($user->address_line1 . ', ' . $user->city . ', ' . $user->state . ' ' . $user->postal_code) : '';
        
        Log::info('=== DIRECT CHECKOUT DEBUG END ===');
        
        return view('checkout', compact('selectedCart', 'subtotal', 'shippingCost', 'tax', 'total', 'userPhone', 'userEmail', 'userAddress', 'user'));
    }

    public function processCheckout(Request $request)
    {
        // Enhanced debugging and validation
        Log::info('=== ENHANCED CHECKOUT DEBUG START ===');
        Log::info('Session ID: ' . session()->getId());
        Log::info('User ID: ' . (Auth::id() ?? 'null'));
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request method: ' . $request->method());
        Log::info('Request data: ' . json_encode($request->all()));
        
        // Get cart from session with fallback
        $cart = session('cart', []);
        Log::info('Cart from session: ' . json_encode($cart));
        Log::info('Cart count: ' . count($cart));
        
        // Enhanced cart validation
        if (empty($cart)) {
            Log::error('Cart is empty in processCheckout');
            
            // Try to get cart from alternative sources
            $cartFromRequest = $request->get('cart', []);
            if (!empty($cartFromRequest)) {
                Log::info('Found cart in request data: ' . json_encode($cartFromRequest));
                $cart = $cartFromRequest;
            } else {
                return redirect()->route('cart')->with('error', 'Your cart is empty! Please add items to your cart before checkout.');
            }
        }
        
        // Get selected items with enhanced handling
        $selectedItems = $request->get('selected_items', []);
        Log::info('Raw selected items: ' . json_encode($selectedItems));
        
        // Handle different formats of selected items
        if (is_string($selectedItems)) {
            $selectedItems = json_decode($selectedItems, true) ?? [];
            Log::info('Selected items after JSON decode: ' . json_encode($selectedItems));
        }
        
        // If selected_items is not an array, try to convert it
        if (!is_array($selectedItems)) {
            $selectedItems = [];
        }
        
        // If no selected items provided, use all cart items
        if (empty($selectedItems)) {
            $selectedItems = array_keys($cart);
            Log::info('Using all cart items as selected: ' . json_encode($selectedItems));
        }
        
        // Filter cart to only include selected items
        $selectedCart = array_filter($cart, function($key) use ($selectedItems) {
            return in_array($key, $selectedItems);
        }, ARRAY_FILTER_USE_KEY);
        
        Log::info('Selected cart after filtering: ' . json_encode($selectedCart));
        Log::info('Selected cart count: ' . count($selectedCart));
        
        // Special handling for direct checkout items
        $hasDirectCheckoutItems = false;
        foreach ($selectedCart as $item) {
            if (isset($item['is_direct_checkout']) && $item['is_direct_checkout']) {
                $hasDirectCheckoutItems = true;
                break;
            }
        }
        
        if (empty($selectedCart)) {
            Log::error('No items selected for checkout');
            return redirect()->route('cart')->with('error', 'No items selected for checkout! Please select items and try again.');
        }
        
        // If we have direct checkout items, we don't need to validate stock
        if (!$hasDirectCheckoutItems) {
            // Validate stock availability for regular cart items only
            foreach ($selectedCart as $item) {
                if (isset($item['id']) && is_numeric($item['id'])) {
                    $product = Product::find($item['id']);
                    if ($product) {
                        if ($product->stock < $item['quantity']) {
                            return redirect()->route('cart')->with('error', "Insufficient stock for {$product->name}!");
                        }
                    }
                }
            }
        }

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
                'contact_number' => $request->shipping_phone,
                'email' => $request->shipping_email,
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
                // Handle direct checkout items differently
                if (isset($item['is_direct_checkout']) && $item['is_direct_checkout']) {
                    // For direct checkout items, create order item without product_id
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => null, // No real product for direct checkout
                        'product_name' => $item['name'], // Store product name directly
                        'quantity' => $item['quantity'],
                        'price' => $item['quantity'] > 0 ? ($item['price'] / $item['quantity']) : 0,
                    ]);
                    
                    Log::info('Created order item for direct checkout: ' . $item['name']);
                } else {
                    // Handle regular cart items
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
