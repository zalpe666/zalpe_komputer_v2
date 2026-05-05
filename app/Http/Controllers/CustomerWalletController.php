<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\WalletService;

class CustomerWalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->latest()
            ->take(10)
            ->get();

        return view('customer.wallet.index', compact('wallet', 'transactions', 'user'));
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        WalletService::add(auth()->id(), (int)$request->amount, 'Topup manual');

        return back()->with('success', 'Topup berhasil');
    }

    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        WalletService::deduct(auth()->id(), (int)$request->amount, 'Bayar order');

        return back()->with('success', 'Pembayaran berhasil');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        $user = auth()->user();

        // 🔒 KYC check
        if (!$user->ktp_verified_at) {
            return back()->with('error', 'Verifikasi KTP dulu');
        }

        WalletService::deduct($user->id, (int)$request->amount, 'Withdraw');

        return back()->with('success', 'Withdraw request berhasil');
    }
}
