<?php

namespace App\Http\Controllers;

use App\Models\CourierRate;
use Illuminate\Http\Request;

class CustomerCourierController extends Controller
{
    public function getByDistrict($district_id)
    {
        $couriers = CourierRate::where('district_id', $district_id)->get();

        return response()->json($couriers);
    }
}
