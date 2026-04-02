@extends('layouts.customer-layout')

@section('content')
<div class="container py-4">

    {{-- 🔹 STATUS SUMMARY --}}
    <div class="row rounded-4 shadow-sm">
        @php
            $icon = [
                'Waiting Payment' => 'bi bi-hourglass-split',
                'Packing' => 'bi bi-box-seam',
                'Sending' => 'bi bi-truck',
                'Completed' => 'bi bi-check2-circle',
            ];
        @endphp

        @foreach ($transactionStats as $status => $count)
        <div class="col-md-3">
            <div class="card text-center border-0">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi {{ $icon[$status] }} fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ $count }}</h5>
                    <p class="text-muted mb-0">{{ $status }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4 mt-4">

        {{-- 🔹 LEFT PANEL --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Profil Saya</h5>

                    <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p>
                    <p class="text-muted small">{{ auth()->user()->email }}</p>

                    <hr>

                    <div class="mb-2">
                        <small class="text-muted">Saldo Wallet</small>
                        <h5 class="text-success">
                            Rp {{ number_format(auth()->user()->wallet->balance ?? 0) }}
                        </h5>
                    </div>

                    <hr>

                    <a href="{{ route('customer.transaction.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        Lihat Transaksi
                    </a>
                    <a href="{{ route('customer.address.index') }}" class="btn btn-outline-secondary w-100">
                        Kelola Alamat
                    </a>
                </div>
            </div>
        </div>

        {{-- 🔹 RIGHT PANEL --}}
        <div class="col-md-8 d-flex flex-column gap-4">

            {{-- Statistik Card --}}
            <div class="card shadow-sm border-0 rounded-4 flex-fill">
                <div class="card-body">
                    <h5 class="mb-3">Statistik</h5>

                    <div class="row text-center">
                        <div class="col-4">
                            <h6>{{ $totalTransactions ?? 0 }}</h6>
                            <small class="text-muted">Transaction</small>
                        </div>
                        <div class="col-4">
                            <h6>Rp {{ number_format($totalSpent ?? 0) }}</h6>
                            <small class="text-muted">Total Spends</small>
                        </div>
                        <div class="col-4">
                            <h6>{{ $totalItems ?? 0 }}</h6>
                            <small class="text-muted">Product</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transaksi Terbaru Card --}}
            <div class="card shadow-sm border-0 rounded-4 flex-fill">
                <div class="card-body">
                    <h5 class="mb-3">Transaksi Terbaru</h5>

                    {{-- Scrollable Wrapper --}}
                    <div style="height: 290px; overflow-y: auto;" class="pe-2">
                        @forelse($recentTransactions as $trx)
                            @php
                                $statusColor = match ($trx->transaction_status) {
                                    'Waiting Payment' => 'warning',
                                    'Paid' => 'info',
                                    'Processed' => 'primary',
                                    'Shipped' => 'secondary',
                                    'Delivered', 'Completed' => 'success',
                                    'Cancelled' => 'danger',
                                    default => 'dark',
                                };
                            @endphp

                            <div class="trx-item d-flex justify-content-between align-items-center mb-3 p-3 rounded-4 shadow-sm">

                                {{-- LEFT --}}
                                <div class="trx-left">
                                    <div class="fw-semibold mb-1">{{ $trx->transaction_type }}</div>
                                    <div class="text-success fw-bold">Rp {{ number_format($trx->subtotal) }}</div>
                                    <small class="text-muted">{{ $trx->created_at->format('d M Y') }}</small>
                                    <div>
                                        <a href="{{ route('customer.transaction.show', $trx->id) }}" class="text-primary small text-decoration-none">
                                            Lihat Detail →
                                        </a>
                                    </div>
                                </div>

                                {{-- RIGHT --}}
                                <div class="text-end">
                                    <span class="badge bg-{{ $statusColor }} px-3 py-2">
                                        {{ $trx->transaction_status }}
                                    </span>
                                </div>

                            </div>

                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-receipt fs-2 mb-2"></i>
                                <div>Belum ada transaksi</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection