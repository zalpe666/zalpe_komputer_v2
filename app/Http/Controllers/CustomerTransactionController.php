<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Midtrans\Config;
use App\Models\WalletTransaction;
use App\Models\Wallet;
use Midtrans\CoreApi;
use App\Services\WalletService;
use App\Services\NotificationService;

class CustomerTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactionTypes = ['Shopping', 'Games', 'Top-Up', 'Phone Credit','Digital'];

        $query = Transaction::with(['transactionDetails.product'])
            ->where('user_id', auth()->id())
            ->latest(); // 🔥 default urut terbaru

        // 🔹 Filter status
        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        // 🔹 Filter tipe transaksi
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // 🔹 Filter tanggal (range)
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } else {
            // 🔥 Optional: default tampil 90 hari terakhir (biar ringan)
            if (!$request->hasAny(['start_date', 'end_date'])) {
                $query->where('created_at', '>=', now()->subDays(90));
            }
        }

        // 🔹 Pagination
        $transactions = $query->paginate(5)->withQueryString();

        return view('customer.transaction.index', [
            'transactions' => $transactions,
            'transactionTypes' => $transactionTypes,
            'filters' => $request->only(['status', 'type', 'start_date', 'end_date'])
        ]);
    }

    // 📌 Detail transaksi
    public function show($id)
    {
        $transaction = Transaction::with([
            'transactionDetails.product',
            'address.province',
            'address.city',
            'address.district'
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.transaction.show', compact('transaction'));
    }
    public function payment($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.transaction.payment', compact('transaction'));
    }
    private function createPayment($transaction)
    {
        if ($transaction->snap_token) {
            return; // 🔥 jangan generate lagi
        }

        Config::$serverKey = config('midtrans.serverKey');

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->invoice,
                'gross_amount' => (int)$transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

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
            'transaction_status' => 'Completed',
            'completed_date' => now(),
        ]);

        // Update semua detail jadi completed
        $transaction->transactionDetails()->update([
            'status' => 'Completed'
        ]);

        // 🔥 Ambil semua detail yang completed
        $details = $transaction->transactionDetails;

        // 🔥 Group by product_id dan hitung qty
        $grouped = $details->groupBy('product_id');

        foreach ($grouped as $productId => $items) {
            $totalQty = $items->sum('qty'); // atau 'quantity' sesuaikan field kamu

            // 🔥 Update sold di product (increment biar aman)
            Product::where('id', $productId)->increment('sold', $totalQty);
        }
        $cashbackAmount = min($transaction->total * 0.05, 50000); // max 50rb

        if ($transaction->payment_method == 'wallet') {
            WalletService::add(
                auth()->id(),
                $cashbackAmount,
                'Cashback order #' . $transaction->invoice
            );
            NotificationService::create(
                auth()->id(),
                'Cashback Received',
                'You received a cashback of Rp ' . number_format($cashbackAmount, 0, ',', '.') . ' for your order #' . $transaction->invoice,
                'Shopping',
                '/home/wallet',
            );
        }
        return back()->with('success', 'Paket berhasil diterima. Sekarang bisa beri rating produk.');
    }
    public function cancel(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if (!in_array($transaction->transaction_status, ['Packing', 'Waiting Payment', 'Pending'])) {
            return back()->with('error', 'Transaksi tidak bisa dibatalkan');
        }

        $user = $transaction->user;

        // cek apakah transaksi sudah dibayar
        $isPaid = in_array($transaction->payment_status, ['Paid', 'Success']);

        // update status transaksi
        $transaction->transaction_status = 'Cancelled';
        $transaction->payment_status = $isPaid
            ? 'Refunded'
            : ($transaction->payment_method === 'cod' ? 'Unpaid' : 'Cancelled');

        $transaction->canceled_date = now();
        $transaction->save();

        // refund hanya jika SUDAH dibayar
        if (
            $isPaid &&
            in_array($transaction->payment_method, ['midtrans', 'wallet'])
        ) {

            WalletService::refund(
                $transaction->user_id,
                $transaction->total,
                'Refund for order #' . $transaction->invoice
            );

            NotificationService::create(
                $transaction->user_id,
                'Refund Successful',
                'Refund for order #' . $transaction->invoice . ' has been successfully added to your Zalpe Pay balance',
                'Shopping',
                '/home/transaction/' . $transaction->id,
            );
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
