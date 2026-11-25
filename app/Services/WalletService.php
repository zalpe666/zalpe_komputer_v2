<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Services\WalletService;

use Exception;

class WalletService
{
    /**
     * Tambah saldo (income)
     */
    public static function add($userId, $amount, $description = null)
    {
        if ($amount <= 0) {
            throw new Exception('Amount tidak valid');
        }

        return DB::transaction(function () use ($userId, $amount, $description) {

            $wallet = Wallet::lockForUpdate()
                ->firstOrCreate(['user_id' => $userId], ['balance' => 0]);

            // 1. catat transaksi
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'income',
                'amount' => $amount,
                'description' => $description
            ]);

            // 2. update saldo
            $wallet->increment('balance', $amount);

            return $wallet->fresh();
        });
    }

    /**
     * Kurangi saldo (expense)
     */
    public static function deduct($userId, $amount, $description = null)
    {
        if ($amount <= 0) {
            throw new Exception('Amount tidak valid');
        }

        return DB::transaction(function () use ($userId, $amount, $description) {

            $wallet = Wallet::lockForUpdate()
                ->where('user_id', $userId)
                ->firstOrFail();

            // cek saldo
            if ($wallet->balance < $amount) {
                throw new Exception('Saldo tidak cukup');
            }

            // 1. catat transaksi
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'expense',
                'amount' => $amount,
                'description' => $description
            ]);

            // 2. update saldo
            $wallet->decrement('balance', $amount);

            return $wallet->fresh();
        });
    }
    public static function refund($userId, $amount, $description = null)
    {
        return self::add(
            $userId,
            $amount,
            $description ?? 'Refund saldo'
        );
    }
    /**
     * Ambil saldo user
     */
    public static function getBalance($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        return $wallet ? $wallet->balance : 0;
    }
}
