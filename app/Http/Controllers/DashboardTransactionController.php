<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use App\Models\Notification;

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

        if (!in_array($status, ['Packing', 'Sending', 'Delivered'])) {
            return back()->with('error', 'Status tidak valid');
        }

        $transaction->transaction_status = $status;

        // ⬇️ isi tanggal sesuai status
        if ($status === 'Sending') {
            if (!$transaction->sending_date) {
                $transaction->sending_date = now();
            }
        }

        if ($status === 'Delivered') {
            if (!$transaction->delivered_date) {
                $transaction->delivered_date = now();
            }
        }

        $transaction->save();

        // 🔔 INSERT NOTIFICATION
        Notification::create([
            'user_id' => $transaction->user_id,
            'title'   => 'Update Status Pesanan',
            'message' => "Pesanan kamu dengan ID #{$transaction->id} sekarang statusnya: {$status}",
            "link"    => route('customer.transaction.show', $transaction->id),
        ]);

        return back()->with('success', 'Status berhasil diupdate');
    }
}
