<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',        // income / expense
        'amount',
        'description',
        'status',      // pending / verified
    ];

    /**
     * Relasi ke Wallet
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Helper: cek apakah sudah bisa dipakai (verified)
     */
    public function isVerified()
    {
        return $this->status === 'verified';
    }
}