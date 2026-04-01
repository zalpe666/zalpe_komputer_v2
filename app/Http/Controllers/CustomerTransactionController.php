<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Midtrans\Config;
use App\Models\WalletTransaction;
use App\Models\Wallet;

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
    public function markAsCompleted($id)
    {
        $transaction = Transaction::with('transactionDetails')->findOrFail($id);

        // cuma bisa update kalau status sekarang Delivered
        if ($transaction->transaction_status != 'Delivered') {
            return back()->with('error', 'Transaksi tidak bisa diupdate.');
        }

        // Update status transaksi
        $transaction->update([
            'transaction_status' => 'Completed'
        ]);
        $transaction->transactionDetails()->update([
            'status' => 'Completed'
        ]);


        return back()->with('success', 'Paket berhasil diterima. Sekarang bisa beri rating produk.');
    }
    public function cancel(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // hanya bisa cancel Packing / Waiting Payment
        if (!in_array($transaction->transaction_status, ['Packing', 'Waiting Payment'])) {
            return back()->with('error', 'Transaksi tidak bisa dibatalkan');
        }

        $user = $transaction->user;

        // update status transaksi
        $transaction->transaction_status = 'Cancelled';
        $transaction->payment_status = $transaction->payment_method === 'cod' ? 'Unpaid' : 'Refunded';
        $transaction->save();

        // 🔥 handle refund ke wallet
        if (in_array($transaction->payment_method, ['midtrans', 'wallet'])) {
            // ambil wallet
            $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

            WalletTransaction::create([
                'wallet_id'   => $wallet->id,
                'type'        => 'income',
                'amount'      => $transaction->total,
                'description' => 'Refund dari transaksi ' . $transaction->invoice,
                'status'      => $user->ktp_verified_at ? 'verified' : 'pending', // pending kalau belum KTP verified
            ]);

            // auto update balance kalau sudah verified
            if ($user->ktp_verified_at) {
                $wallet->balance = $wallet->walletTransactions()
                    ->where('status', 'verified')
                    ->sum('amount')
                    - $wallet->walletTransactions()->where('type', 'expense')->sum('amount');
                $wallet->save();
            }
        }

        return back()->with('success', 'Transaksi berhasil dibatalkan');
    }
    public function showRateForm($transactionId, $productId)
    {
        $transaction = Transaction::with('transactionDetails')->findOrFail($transactionId);
        $product = Product::findOrFail($productId);

        // Validasi: transaction milik user & product ada di transaction
        if (
            $transaction->user_id != auth()->id() ||
            !$transaction->transactionDetails->where('product_id', $productId)->first() ||
            $transaction->transaction_status != 'Completed'
        ) {
            abort(403, 'Akses ditolak');
        }

        return view('customer.transaction.rate', compact('transaction', 'product'));
    }

    public function submitRate(Request $request, $transactionId, $productId)
    {
        $transaction = Transaction::with('transactionDetails')->findOrFail($transactionId);

        // Validasi user & transaction
        $detail = $transaction->transactionDetails
            ->where('product_id', $productId)
            ->firstOrFail();

        if ($transaction->user_id != auth()->id() || $transaction->transaction_status != 'Completed') {
            abort(403, 'Akses ditolak');
        }

        // Cek apakah sudah dirating
        if (!is_null($detail->rating)) {
            return back()->with('error', 'Anda sudah memberikan rating untuk produk ini.');
        }

        // Validasi input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        // Update rating & review di transaction_detail
        $detail->update([
            'rating' => $request->rating,
            'review' => $request->review,
            // 'status' tetap 'Completed'
        ]);

        // Hitung rata-rata rating product dari semua transaction_details dengan status 'Completed'
        $avgRating = TransactionDetail::where('product_id', $productId)
            ->where('status', 'Completed')
            ->whereNotNull('rating') // hanya yang sudah dirating
            ->avg('rating');

        // Update rating di product
        Product::find($productId)->update([
            'rating' => $avgRating ?? 0,
        ]);

        return redirect()->route('customer.transaction.index')
            ->with('success', 'Terima kasih, rating berhasil dikirim!');
    }
}
