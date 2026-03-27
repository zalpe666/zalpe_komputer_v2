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
            ->paginate(5);

        return view('admin.transaction.index', compact('transactions'));
    }

    public function today()
    {
        $transactions = Transaction::with(['user', 'address'])
            ->whereDate('created_at', Carbon::today()) // filter hanya transaksi hari ini
            ->orderBy('created_at', 'desc')
            ->paginate(5);

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
            'details.product' // ambil product di detail
        ])->findOrFail($id);

        return view('admin.transaction.show', compact('transaction'));
    }
}
