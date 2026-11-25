<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class CustomerKycController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'ktp_number' => 'required'
        ]);

        $user = auth()->user();

        DB::transaction(function () use ($user, $request) {

            // ✅ update KYC
            $user->update([
                'ktp_number' => $request->ktp_number,
                'ktp_verified_at' => now(),
            ]);

            // ✅ buat wallet kalau belum ada
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            // ❗ cek biar ga double bonus
            if ($wallet->balance == 0) {

                // ✅ tambah saldo 1000
                $wallet->increment('balance', 1000);

                // ✅ log transaksi
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'income',
                    'amount' => 1000,
                    'description' => '🎉 Selamat! Anda mendapatkan saldo awal Zalpe Pay sebesar Rp1.000'
                ]);
                NotificationService::create(
                    $user->id,
                    'KYC Berhasil!',
                    'Selamat! Verifikasi KTP kamu berhasil. Kamu mendapatkan bonus saldo Zalpe Pay sebesar Rp1.000.',
                    'Wallet',
                    route('customer.wallet.index')
                );
            }
        });

        return back()->with('success', 'KYC berhasil & saldo bonus diberikan');
    }
}
