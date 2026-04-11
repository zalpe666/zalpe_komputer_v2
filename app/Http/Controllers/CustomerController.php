<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;

class CustomerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();

        $productsLatest = Product::where('is_active', true)
            ->latest()
            ->limit(10)
            ->get();
        $productsDiscount = Product::where('is_active', true)
            ->where('discount', '>', 0)
            ->latest()
            ->limit(10)
            ->get();
        $rogProducts = Product::where('is_active', true)
            ->where('brand_id', 30)
            ->latest()
            ->limit(10)
            ->get();
        $categories = Category::limit(8)->get();

        $brands = Brand::limit(10)->get();

        return view('customer.home.index', compact(
            'productsLatest',
            'productsDiscount',
            'banners',
            'rogProducts',
            'categories',
            'brands'
        ));
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
