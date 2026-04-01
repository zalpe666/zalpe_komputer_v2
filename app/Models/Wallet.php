<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke WalletTransaction
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Hitung saldo terbaru dari wallet_transactions verified
     */
    public function calculateBalance()
    {
        $income = $this->walletTransactions()->where('type', 'income')->where('status', 'verified')->sum('amount');
        $expense = $this->walletTransactions()->where('type', 'expense')->sum('amount');
        return $income - $expense;
    }
}
