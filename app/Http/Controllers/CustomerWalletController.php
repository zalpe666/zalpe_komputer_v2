<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\UserGame;
use App\Services\NotificationService;

class CustomerWalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return view('customer.wallet.register-prompt');
        }

        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->latest()
            ->take(10)
            ->get();

        return view('customer.wallet.index', compact(
            'wallet',
            'transactions',
            'user'
        ));
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:10000000',
            'bank' => 'required|in:bca,bni,mandiri',
        ]);

        $user = auth()->user();

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return back()->with('error', 'Anda belum memiliki Zalpe Pay.');
        }

        $amount = (int) $request->amount;

        // Generate invoice
        $invoice = 'ZK-TOPUP-' . date('YmdHis') . '-' . rand(100, 999);

        $transaction = null;

        try {

            // ==========================================
            // 1. BUAT TRANSAKSI DAN DETAIL
            // ==========================================

            DB::transaction(function () use (
                $user,
                $amount,
                $invoice,
                $request,
                &$transaction
            ) {

                $transaction = Transaction::create([
                    'invoice' => $invoice,
                    'transaction_type' => 'Top-Up',
                    'user_id' => $user->id,

                    'subtotal' => $amount,
                    'discount_by_merchant' => 0,
                    'shipping_cost' => 0,

                    'transaction_status' => 'Waiting Payment',
                    'total' => $amount,

                    'payment_method' => 'midtrans',
                    'bank' => $request->bank,
                    'payment_status' => 'Unpaid',
                ]);

                // Transaction Detail
                $transaction->transactionDetails()->create([
                    'product_id' => null,
                    'qty' => 1,
                    'price' => $amount,
                    'total' => $amount,
                ]);
            });

            // ==========================================
            // 2. KONFIGURASI MIDTRANS
            // ==========================================

            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = config('midtrans.isProduction');

            if (empty(Config::$serverKey)) {
                throw new \Exception('Midtrans Server Key belum dikonfigurasi.');
            }

            // ==========================================
            // 3. PARAMETER PEMBAYARAN
            // ==========================================

            if ($request->bank === 'mandiri') {

                $params = [
                    'transaction_details' => [
                        'order_id' => $invoice,
                        'gross_amount' => $amount,
                    ],

                    'payment_type' => 'echannel',

                    'echannel' => [
                        'bill_info1' => 'Payment:',
                        'bill_info2' => 'Zalpe Pay Top-Up',
                    ],
                ];
            } else {

                $params = [
                    'transaction_details' => [
                        'order_id' => $invoice,
                        'gross_amount' => $amount,
                    ],

                    'payment_type' => 'bank_transfer',

                    'bank_transfer' => [
                        'bank' => $request->bank,
                    ],
                ];
            }
            $response = CoreApi::charge($params);

            Log::info('WALLET TOPUP MIDTRANS RESPONSE', [
                'invoice' => $invoice,
                'response' => json_decode(json_encode($response), true),
            ]);

            // ==========================================
            // 5. AMBIL DATA PEMBAYARAN
            // ==========================================

            $vaNumber = null;
            $billerCode = null;
            $billKey = null;

            if (!empty($response->va_numbers)) {
                $vaNumber = $response->va_numbers[0]->va_number ?? null;
            }

            if (!empty($response->bill_key)) {
                $billKey = $response->bill_key;
                $billerCode = $response->biller_code ?? null;
            }

            // ==========================================
            // 6. UPDATE TRANSAKSI
            // ==========================================

            $transaction->update([
                'va_number' => $vaNumber,
                'biller_code' => $billerCode,
                'bill_key' => $billKey,
                'expired_at' => $response->expiry_time ?? null,
            ]);
            NotificationService::create(
                $user->id,
                'Waiting for Payment',
                "Please complete your top-up payment via " . strtoupper($request->bank) . ".",
                'Top-Up',
                '/home/transaction/' . $transaction->id . '/payment'
            );

            return back()->with(
                'success',
                'Invoice top-up berhasil dibuat. Silakan selesaikan pembayaran.'
            );
        } catch (\Throwable $e) {

            Log::error('WALLET TOPUP ERROR', [
                'invoice' => $invoice,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            if ($transaction) {
                $transaction->update([
                    'transaction_status' => 'Cancelled',
                    'payment_status' => 'Failed',
                ]);
            }

            return back()->with(
                'error',
                'Top-up gagal: ' . $e->getMessage()
            );
        }
    }
    public function pay($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('payment_method', 'wallet')
            ->firstOrFail();

        if ($transaction->payment_status == 'Paid') {
            return redirect()
                ->route('customer.transaction.index')
                ->with('info', 'Transaksi sudah dibayar.');
        }

        if ($transaction->payment_status != 'Unpaid') {
            return redirect()
                ->route('customer.transaction.index')
                ->with('error', 'Transaksi tidak dapat dibayar.');
        }

        $wallet = Wallet::where('user_id', auth()->id())
            ->where('status', 'verified')
            ->first();

        if (!$wallet) {
            return redirect()
                ->route('customer.transaction.index')
                ->with('error', 'Wallet belum tersedia atau belum terverifikasi.');
        }

        return view('customer.wallet.pay', compact(
            'transaction',
            'wallet'
        ));
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
    public function register()
    {
        $wallet = Wallet::where('user_id', auth()->id())->first();

        if ($wallet) {
            return redirect()->route('customer.wallet.index');
        }

        return view('customer.wallet.register');
    }
    public function storeRegister(Request $request)
    {
        $user = auth()->user();

        // Pastikan user belum memiliki Wallet
        if (Wallet::where('user_id', $user->id)->exists()) {
            return redirect()
                ->route('customer.wallet.index')
                ->with('error', 'Anda sudah memiliki Zalpe Pay.');
        }

        // Validasi data registrasi
        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:wallets,nik',
            'full_name' => 'required|string|max:150',
            'birth_date' => 'required|date|before:today',
            'pin' => 'required|digits:6|confirmed',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.before' => 'Tanggal lahir tidak valid.',
            'pin.required' => 'PIN Wallet wajib diisi.',
            'pin.digits' => 'PIN harus terdiri dari 6 digit.',
            'pin.confirmed' => 'Konfirmasi PIN tidak cocok.',
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'nik' => $validated['nik'],
            'full_name' => $validated['full_name'],
            'birth_date' => $validated['birth_date'],
            'pin' => Hash::make($validated['pin']),
            'balance' => 0,
            'status' => 'Verified',
        ]);
        NotificationService::create(
            $user->id,
            'KYC Berhasil!',
            'Selamat! Verifikasi KTP kamu berhasil.',
            'Top-Up',
            parse_url(route('customer.wallet.index'), PHP_URL_PATH)
        );

        return redirect()
            ->route('customer.wallet.index')
            ->with('success', 'Registrasi Zalpe Pay berhasil!');
    }
    public function processPay(Request $request, $id)
    {
        $request->validate([
            'wallet_pin' => 'required|array|size:6',
            'wallet_pin.*' => 'required|digits:1',
        ]);

        $pin = implode('', $request->wallet_pin);

        try {

            DB::transaction(function () use ($id, $pin) {

                // ==========================================
                // 1. VALIDASI TRANSAKSI DAN LOCK
                // ==========================================
                $transaction = Transaction::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->where('payment_method', 'wallet')
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($transaction->payment_status == 'Paid') {
                    throw new \Exception('Transaksi sudah dibayar.');
                }

                if ($transaction->payment_status != 'Unpaid') {
                    throw new \Exception('Transaksi tidak dapat dibayar.');
                }

                // ==========================================
                // 2. AMBIL WALLET DAN LOCK
                // ==========================================
                $wallet = Wallet::where('user_id', auth()->id())
                    ->where('status', 'verified')
                    ->lockForUpdate()
                    ->first();

                if (!$wallet) {
                    throw new \Exception('Wallet tidak tersedia.');
                }
                if ($transaction->transaction_type == 'Games') {

                    foreach ($transaction->transactionDetails as $detail) {

                        UserGame::firstOrCreate([
                            'user_id' => $transaction->user_id,
                            'product_id' => $detail->product_id,
                        ], [
                            'transaction_id' => $transaction->id,
                        ]);
                    }
                }

                // ==========================================
                // 3. VALIDASI PIN
                // ==========================================
                if (!Hash::check($pin, $wallet->pin)) {
                    throw new \Exception('PIN Wallet yang kamu masukkan salah.');
                }

                // ==========================================
                // 4. VALIDASI SALDO
                // ==========================================
                if ($wallet->balance < $transaction->total) {
                    throw new \Exception('Saldo Wallet tidak mencukupi.');
                }

                // ==========================================
                // 5. POTONG SALDO
                // ==========================================
                WalletService::deduct(
                    auth()->id(),
                    $transaction->total,
                    'Pembayaran order - Invoice ' . $transaction->invoice
                );

                // ==========================================
                // 6. UPDATE STATUS TRANSAKSI
                // ==========================================
                $transaction->payment_status = 'Paid';
                $transaction->payment_date = now();

                if ($transaction->transaction_type == 'Shopping') {

                    $transaction->transaction_status = 'Packing';
                } elseif (in_array($transaction->transaction_type, [
                    'Games',
                    'Digital',
                    'Top-Up',
                    'Phone Credit'
                ])) {

                    $transaction->transaction_status = 'Completed';
                }

                $transaction->save();

                Log::info('WALLET PAYMENT SUCCESS', [
                    'invoice' => $transaction->invoice,
                    'user_id' => auth()->id(),
                    'amount' => $transaction->total,
                    'transaction_type' => $transaction->transaction_type,
                ]);
            });

            return redirect()
                ->route('customer.transaction.index')
                ->with('success', 'Pembayaran berhasil!');
        } catch (\Throwable $e) {

            Log::error('WALLET PAYMENT FAILED', [
                'transaction_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
