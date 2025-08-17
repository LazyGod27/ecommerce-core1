<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with(['items.product'])->where('user_id', $user->id)->first();
        
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }
        
        return new CartResource($cart);
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if product already in cart
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        $cart->calculateTotal();
        $cart->load('items.product');

        return new CartResource($cart);
    }

    public function remove($rowId)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        
        $cart->items()->where('id', $rowId)->delete();
        $cart->calculateTotal();
        $cart->load('items.product');
        
        return new CartResource($cart);
    }

    public function update(Request $request, $rowId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        
        $item = $cart->items()->where('id', $rowId)->firstOrFail();
        $item->update(['quantity' => $request->quantity]);
        
        $cart->calculateTotal();
        $cart->load('items.product');
        
        return new CartResource($cart);
    }

    public function clear()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        
        $cart->items()->delete();
        $cart->update(['total' => 0]);
        
        return response()->json(['message' => 'Cart cleared successfully']);
    }
}
