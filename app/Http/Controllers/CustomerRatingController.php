<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class CustomerRatingController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Ambil semua transactionDetails user yang status transaksinya Completed
        $transactionDetails = \App\Models\TransactionDetail::with('product', 'transaction')
            ->whereHas('transaction', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->where('transaction_status', 'Completed');
            })
            ->get();

        // Pisahkan Ongoing & Completed rating
        $ongoing = $transactionDetails->filter(fn($item) => is_null($item->rating) || $item->rating == 0);
        $completed = $transactionDetails->filter(fn($item) => $item->rating > 0);

        return view('customer.rating.index', compact('ongoing', 'completed'));
    }
    public function create($transactionId, $productId)
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
    public function store(Request $request, $transactionId, $productId)
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
