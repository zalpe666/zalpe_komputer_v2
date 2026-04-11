<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class CustomerProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // CATEGORY
        if ($request->category) {
            $query->whereIn('category_id', $request->category);
        }

        // BRAND
        if ($request->brand) {
            $query->whereIn('brand_id', $request->brand);
        }

        // SORTING
        // SORTING
        if ($request->sort) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderByRaw('(default_price - (default_price * discount / 100)) ASC');
                    break;

                case 'price_desc':
                    $query->orderByRaw('(default_price - (default_price * discount / 100)) DESC');
                    break;

                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;

                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;

                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        $categories = Category::whereHas('products', function ($q) {
            $q->where('is_active', true);
        })->get();

        $brands = Brand::whereHas('products', function ($q) {
            $q->where('is_active', true);
        })->get();

        return view('customer.product.index', compact('products', 'categories', 'brands'));
    }
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('customer.product.show', compact('product'));
    }
}
