<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $transactionStats = Transaction::where('user_id', $userId)
            ->selectRaw('transaction_status, COUNT(*) as total')
            ->groupBy('transaction_status')
            ->pluck('total', 'transaction_status');

        // pastikan semua status ada meski 0
        $statuses = ['Waiting Payment', 'Packing', 'Sending', 'Completed'];

        // pastikan semua status ada
        foreach ($statuses as $status) {
            if (!isset($transactionStats[$status])) {
                $transactionStats[$status] = 0;
            }
        }

        // urutkan array sesuai $statuses
        $transactionStats = collect($statuses)
            ->mapWithKeys(fn($status) => [$status => $transactionStats[$status]]);
        // 🔹 Statistik
        $totalTransactions = Transaction::where('user_id', $userId)->count();
        $totalSpent = Transaction::where('user_id', $userId)
            ->where('payment_status', 'Paid')
            ->sum('total');

        $totalItems = TransactionDetail::whereHas('transaction', function ($q) use ($userId) {
            $q->where('user_id', $userId)
                ->where('transaction_status', 'Completed'); // filter status
        })->sum('qty');

        // 🔹 Recent transaksi (ambil 5 terbaru)
        $recentTransactions = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard.index', compact(
            'totalTransactions',
            'totalSpent',
            'totalItems',
            'recentTransactions',
            'transactionStats'
        ));
    }
    public function setting()
    {
        return view('customer.dashboard.setting');
    }
}
