<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'province_id',
        'city_id',
        'district_id',
        'address',
        'postal_code',
        'is_default'
    ];

    // 🔗 RELATIONS

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    protected $casts = [
        'is_default' => 'boolean',
    ];
}
