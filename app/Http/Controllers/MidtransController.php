<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class MidtransController extends Controller
{
    function generateSteamCode()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < 16; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        // format XXXX-XXXX-XXXX-XXXX
        return substr($code, 0, 4) . '-' . substr($code, 4, 4) . '-' . substr($code, 8, 4) . '-' . substr($code, 12, 4);
    }
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
                if (!$transaction->payment_date) {
                    $transaction->payment_date = $payload['settlement_time'] ?? now();
                }

                if ($transaction->transaction_type == 'Shopping') {
                    $transaction->transaction_status = 'Packing';
                }else {
                    $transaction->transaction_type = 'Top-Up';
                    $transaction->transaction_status = 'Completed';
                }

                $transaction->payment_status = 'Paid';
                $transaction->save();

                // 🔥 UPDATE STEAM CODE hanya untuk transaksi Top-Up
                if ($transaction->transaction_type == 'Top-Up') {
                    $details = TransactionDetail::where('transaction_id', $transaction->id)
                        ->whereHas('product', function ($q) {
                            $q->where('type', '<>', 'products'); // Steam Wallet / digital
                        })
                        ->get();

                    foreach ($details as $detail) {
                        $detail->code = $this->generateSteamCode();
                        $detail->save();
                    }
                }

                break;
            case 'pending':
                $transaction->transaction_status = 'Pending';
                $transaction->save();
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $transaction->transaction_status = 'Cancelled';
                $transaction->save();
                break;
        }

        $transaction->save();

        return response()->json(['message' => 'OK'], 200);
    }
}
