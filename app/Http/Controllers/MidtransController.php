<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $payload = $request->all();

        \Log::info('MIDTRANS CALLBACK', $payload);

        // 🔥 Ambil transaksi
        $transaction = Transaction::where('invoice', $payload['order_id'])->first();

        // 🔥 Kalau tidak ditemukan → jangan error
        if (!$transaction) {
            \Log::warning('Transaction not found', $payload);
            return response()->json(['message' => 'OK'], 200);
        }

        // 🔥 Handle status
        switch ($payload['transaction_status']) {
            case 'capture':
            case 'settlement':
                $transaction->transaction_status = 'Paid';
                break;

            case 'pending':
                $transaction->transaction_status = 'Pending';
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $transaction->transaction_status = 'Cancelled';
                break;
        }

        $transaction->save();

        return response()->json(['message' => 'OK'], 200);
    }
}
