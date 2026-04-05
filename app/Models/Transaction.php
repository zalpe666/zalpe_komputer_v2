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
        'sending_date',
        'payment_date',
        'delivered_date',
        'canceled_date',
        'snap_token',
        'transaction_type',
        'discount_by_merchant',
        'discount_by_voucher',
        'total_weight',
        'completed_date',
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
        'payment_date' => 'datetime',
        'sending_date' => 'datetime',
        'delivered_date' => 'datetime',
        'canceled_date' => 'datetime',
        'completed_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
