<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class DashboardProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Search name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter brand
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.product.index', compact('products', 'categories', 'brands'));
    }
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.product.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'type' => 'required|in:Product,Games,Digital,Steam Wallet',
            'default_price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'required',
        ]);

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'type' => $request->type,
            'default_price' => $request->default_price,
            'price' => $request->price,
            'discount' => $request->discount ?? 0,
            'image' => $request->image,
            'weight' => $request->weight ?? 0,
            'description' => $request->description,
            'stock' => $request->stock,
            'is_active' => $request->has('active'),
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Berhasil tambah produk');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.product.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'is_digital' => $request->has('is_digital'),
            'is_games' => $request->has('is_games'),
            'default_price' => $request->default_price,
            'price' => $request->price,
            'discount' => $request->discount,
            'image' => $request->image,
            'weight' => $request->weight,
            'description' => $request->description,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Berhasil update');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Berhasil delete');
    }
}
