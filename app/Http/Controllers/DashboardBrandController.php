<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class DashboardBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::paginate(10);
        return view('admin.brand.index', compact('brands'));
    }
}
