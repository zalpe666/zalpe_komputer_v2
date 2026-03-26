<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierRate extends Model
{
    protected $fillable = [
        'district_id',
        'name',
        'service',
        'price_per_kg',
        'estimated_delivery_time'
    ];

    // relasi ke district
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}