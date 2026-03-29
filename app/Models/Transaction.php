<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice',
        'user_id',
        'address_id',
        'subtotal',
        'shipping_cost',
        'total',
        'courier_name',
        'courier_service',
        'estimated_delivery',
        'payment_method',
        'payment_status',
        'transaction_status',
        'notes',
        'snap_token',
        'transaction_type'
    ];

    // 🔥 RELATION

    // ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ke address
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // ke detail (items)
    public function transactionDetails()
    {
        return $this->hasMany(\App\Models\TransactionDetail::class);
    }
    protected $casts = [
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'total' => 'integer',
    ];
}
