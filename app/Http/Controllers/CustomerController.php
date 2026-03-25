<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CustomerController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->get();

        return view('customer.home.index', compact('products'));
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
}
