<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WalletService;
use App\Services\NotificationService;


class MidtransController extends Controller
{
    // ==========================================
    // GENERATE STEAM CODE
    // ==========================================
    function generateSteamCode()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < 16; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }

        return substr($code, 0, 4) . '-' .
            substr($code, 4, 4) . '-' .
            substr($code, 8, 4) . '-' .
            substr($code, 12, 4);
    }

    // ==========================================
    // MIDTRANS CALLBACK
    // ==========================================
    public function callback(Request $request)
    {
        $payload = $request->all();

        Log::info('MIDTRANS CALLBACK', $payload);

        // ==========================================
        // 1. VALIDASI ORDER ID
        // ==========================================
        $orderId = $payload['order_id'] ?? null;

        if (!$orderId) {
            Log::warning('Midtrans callback: Order ID missing');

            return response()->json([
                'message' => 'Order ID is required'
            ], 400);
        }

        // ==========================================
        // 2. HANDLE TEST NOTIFICATION
        // ==========================================
        if (str_starts_with($orderId, 'payment_notif_test_')) {

            Log::info('MIDTRANS TEST NOTIFICATION RECEIVED', [
                'order_id' => $orderId
            ]);

            return response()->json([
                'message' => 'Test notification received successfully'
            ], 200);
        }

        // ==========================================
        // 3. VALIDASI SIGNATURE MIDTRANS
        // ==========================================
        $serverKey = config('midtrans.serverKey');

        if (!$serverKey) {
            Log::error('Midtrans server key is not configured');

            return response()->json([
                'message' => 'Midtrans configuration error'
            ], 500);
        }

        $expectedSignature = hash(
            'sha512',
            $orderId .
                ($payload['status_code'] ?? '') .
                ($payload['gross_amount'] ?? '') .
                $serverKey
        );

        if (
            empty($payload['signature_key']) ||
            !hash_equals($expectedSignature, $payload['signature_key'])
        ) {
            Log::warning('Invalid Midtrans signature', [
                'order_id' => $orderId
            ]);

            return response()->json([
                'message' => 'Invalid signature'
            ], 403);
        }

        // ==========================================
        // 4. CARI TRANSAKSI
        // ==========================================
        $transaction = Transaction::where('invoice', $orderId)->first();

        if (!$transaction) {
            Log::warning('Transaction not found', [
                'order_id' => $orderId
            ]);

            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        // ==========================================
        // 5. HANDLE STATUS PEMBAYARAN
        // ==========================================
        try {

            switch ($payload['transaction_status'] ?? null) {

                case 'capture':
                case 'settlement':

                    DB::transaction(function () use ($transaction, $payload) {

                        // Lock transaksi agar callback bersamaan
                        // tidak memproses transaksi dua kali.
                        $trx = Transaction::where('id', $transaction->id)
                            ->lockForUpdate()
                            ->first();

                        if (!$trx) {
                            throw new \Exception('Transaction not found during processing.');
                        }

                        // Cegah pembayaran diproses berulang kali.
                        if ($trx->payment_status == 'Paid') {
                            Log::info('MIDTRANS CALLBACK ALREADY PROCESSED', [
                                'invoice' => $trx->invoice
                            ]);

                            return;
                        }

                        // Validasi nominal pembayaran.
                        if ((float) $payload['gross_amount'] != (float) $trx->total) {
                            throw new \Exception(
                                'Nominal pembayaran Midtrans tidak sesuai.'
                            );
                        }

                        // ==========================================
                        // 6. KHUSUS TOP-UP WALLET
                        // ==========================================
                        if ($trx->transaction_type == 'Top-Up') {

                            $wallet = Wallet::where('user_id', $trx->user_id)
                                ->first();

                            if (!$wallet) {
                                throw new \Exception(
                                    'Wallet pengguna tidak ditemukan.'
                                );
                            }

                            WalletService::add(
                                $trx->user_id,
                                $trx->total,
                                'Top-Up Zalpe Pay - Invoice ' . $trx->invoice
                            );
                            NotificationService::create(
                                $trx->user_id,
                                'Top-Up Successful',
                                'Your Zalpe Pay balance has been successfully topped up.',
                                'Top-Up',
                                '/home/wallet'
                            );
                            Log::info('WALLET TOPUP BERHASIL', [
                                'user_id' => $trx->user_id,
                                'wallet_id' => $wallet->id,
                                'amount' => $trx->total,
                                'balance' => WalletService::getBalance($trx->user_id)
                            ]);
                        }

                        // ==========================================
                        // 7. UPDATE STATUS TRANSAKSI
                        // ==========================================
                        if (!$trx->payment_date) {
                            $trx->payment_date = $payload['settlement_time'] ?? now();
                        }

                        if ($trx->transaction_type == 'Shopping') {

                            $trx->transaction_status = 'Packing';
                        } elseif (in_array($trx->transaction_type, [
                            'Games',
                            'Digital',
                            'Top-Up',
                            'Phone Credit'
                        ])) {

                            $trx->transaction_status = 'Completed';
                        }

                        $trx->payment_status = 'Paid';
                        $trx->save();

                        // ==========================================
                        // 8. GENERATE STEAM CODE
                        // ==========================================
                        if ($trx->transaction_type == 'Digital') {

                            $details = TransactionDetail::where(
                                'transaction_id',
                                $trx->id
                            )
                                ->whereHas('product', function ($q) {
                                    $q->where('type', '<>', 'products');
                                })
                                ->get();

                            foreach ($details as $detail) {

                                if (!$detail->code) {
                                    $detail->code = $this->generateSteamCode();
                                    $detail->save();
                                }
                            }
                        }

                        Log::info('MIDTRANS PAYMENT SUCCESS', [
                            'invoice' => $trx->invoice,
                            'transaction_type' => $trx->transaction_type,
                            'payment_status' => $trx->payment_status,
                            'transaction_status' => $trx->transaction_status
                        ]);
                    });

                    break;

                // ==========================================
                // 9. PENDING
                // ==========================================
                case 'pending':

                    if ($transaction->payment_status != 'Paid') {

                        $transaction->transaction_status = 'Pending';
                        $transaction->save();
                    }

                    break;

                // ==========================================
                // 10. FAILED / CANCELLED
                // ==========================================
                case 'deny':
                case 'expire':
                case 'cancel':

                    if ($transaction->payment_status != 'Paid') {

                        $transaction->transaction_status = 'Cancelled';
                        $transaction->payment_status = 'Failed';
                        $transaction->save();
                    }

                    break;

                default:

                    Log::info('MIDTRANS UNKNOWN TRANSACTION STATUS', [
                        'order_id' => $orderId,
                        'transaction_status' => $payload['transaction_status'] ?? null
                    ]);

                    break;
            }
        } catch (\Throwable $e) {

            Log::error('MIDTRANS CALLBACK PROCESSING FAILED', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Callback processing failed'
            ], 500);
        }

        return response()->json([
            'message' => 'OK'
        ], 200);
    }
}
