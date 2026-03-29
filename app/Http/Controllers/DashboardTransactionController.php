<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;

class DashboardTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'address'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.transaction.index', compact('transactions'));
    }

    public function today()
    {
        $transactions = Transaction::with(['user', 'address'])
            ->whereDate('created_at', Carbon::today()) // filter hanya transaksi hari ini
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.transaction.today', compact('transactions'));
    }

    /**
     * Tampilkan detail transaksi tertentu
     */
    public function show($id)
    {
        $transaction = Transaction::with([
            'user',
            'address',
            'transactionDetails.product' // ambil product di detail
        ])->findOrFail($id);

        return view('admin.transaction.show', compact('transaction'));
    }
    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $status = $request->status;

        // validasi biar aman
        if (!in_array($status, ['Packing', 'Sending', 'Delivered'])) {
            return back()->with('error', 'Status tidak valid');
        }

        $transaction->update([
            'transaction_status' => $status
        ]);

        return back()->with('success', 'Status berhasil diupdate');
    }
}
