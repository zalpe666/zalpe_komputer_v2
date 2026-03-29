<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class CustomerTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with([
            'transactionDetails.product'
        ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.transaction.index', compact('transactions'));
    }

    // 📌 Detail transaksi
    public function show($id)
    {
        $transaction = Transaction::with(['transactionDetails.product', 'address'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.transaction.show', compact('transaction'));
    }
    public function payment($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())
            ->findOrFail($id);

        // dd($transaction->snap_token); // 🔥 sekarang ini baru jalan

        if (empty($transaction->snap_token)) {
            $this->createPayment($transaction);
        }

        return view('customer.transaction.payment', compact('transaction'));
    }
    private function createPayment($transaction)
    {
        // dd('masuk createPayment');
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->invoice,
                'gross_amount' => $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // DEBUG
        // dd($snapToken);

        $transaction->update([
            'snap_token' => $snapToken
        ]);
    }
}
