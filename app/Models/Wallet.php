<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'nik',
        'full_name',
        'birth_date',
        'pin',
        'balance',
        'status',
    ];

    protected $hidden = [
        'pin',
    ];
    
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'balance' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
