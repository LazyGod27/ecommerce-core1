<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // For the new frontend, we'll use session-based cart
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum('price');
        
        return view('cart', compact('cart', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $cart = session('cart', []);
        
        // Generate a unique row ID
        $rowId = uniqid();
        
        $cart[$rowId] = [
            'rowId' => $rowId,
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price * $request->quantity,
            'quantity' => $request->quantity,
            'image' => $product->image ?? 'default.jpg'
        ];
        
        session(['cart' => $cart]);
        
        return redirect()->route('cart')->with('success', 'Product added to cart!');
    }

    public function remove($rowId)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            session(['cart' => $cart]);
        }
        
        return redirect()->route('cart')->with('success', 'Item removed from cart!');
    }

    public function update(Request $request, $rowId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session('cart', []);
        
        if (isset($cart[$rowId])) {
            $product = Product::find($cart[$rowId]['id']);
            $cart[$rowId]['quantity'] = $request->quantity;
            $cart[$rowId]['price'] = $product->price * $request->quantity;
            session(['cart' => $cart]);
        }
        
        return redirect()->route('cart')->with('success', 'Cart updated!');
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string'
        ]);

        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        // Here you would typically:
        // 1. Create an order in the database
        // 2. Process payment based on the selected method
        // 3. Clear the cart
        // 4. Redirect to success page or tracking page

        // For now, we'll just clear the cart and redirect
        session()->forget('cart');
        
        return redirect()->route('tracking')->with('success', 'Order placed successfully!');
    }
}
