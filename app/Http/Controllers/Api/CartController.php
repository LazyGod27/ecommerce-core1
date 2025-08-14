<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['items.product'])
            ->where('user_id', auth()->id())
            ->firstOrCreate(['user_id' => auth()->id()]);

        return new CartResource($cart);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = $cart->items()
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'message' => 'Item added to cart',
            'cart' => new CartResource($cart->fresh())
        ]);
    }

    public function removeItem($itemId)
    {
        $cart = Cart::where('user_id', auth()->id())->firstOrFail();
        $cart->items()->where('id', $itemId)->delete();

        return response()->json([
            'message' => 'Item removed from cart',
            'cart' => new CartResource($cart->fresh())
        ]);
    }
}
