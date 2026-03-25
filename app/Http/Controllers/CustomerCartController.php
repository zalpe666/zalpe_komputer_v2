<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CustomerCartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return view('customer.cart.index', compact('carts'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $cart->increment('pcs');
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $id,
                'pcs' => 1
            ]);
        }

        return back()->with('success', 'Added to cart!');
    }
    public function updateCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        if ($request->action == 'plus') {
            $cart->increment('pcs');
        } elseif ($request->action == 'minus') {
            if ($cart->pcs > 1) {
                $cart->decrement('pcs');
            }
        }

        return back();
    }

    public function removeCart($id)
    {
        Cart::findOrFail($id)->delete();
        return back();
    }
}
