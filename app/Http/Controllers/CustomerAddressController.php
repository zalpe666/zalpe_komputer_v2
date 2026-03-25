<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Province;
use App\Models\City;
use App\Models\District;

class CustomerAddressController extends Controller
{
    public function index()
    {
        $addresses = Address::with(['province', 'city', 'district'])
            ->where('user_id', auth()->id())
            ->get();

        return view('customer.address.index', compact('addresses'));
    }

    public function create()
    {
        $provinces = Province::all();
        return view('customer.address.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'address' => 'required',
        ]);
        if ($request->has('is_default')) {
            Address::where('user_id', auth()->id())
                ->update(['is_default' => 0]);
        }
        Address::create([
            'user_id' => auth()->id(),
            ...$request->all()

        ]);

        return redirect()->route('customer.address.index');
    }
    public function getCities($provinceId)
    {
        return City::where('province_id', $provinceId)->get();
    }

    public function getDistricts($cityId)
    {
        return District::where('city_id', $cityId)->get();
    }
}
