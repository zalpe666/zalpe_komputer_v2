<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'price',
        'qty',
        'total'
    ];

    // 🔥 RELATION

    // ke transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ke product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
