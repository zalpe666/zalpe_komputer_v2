@extends('layouts.customer-layout')

@section('title', 'Dashboard')
@section('page_title')
    <span class="text-muted fw-light">Halo,</span> {{ auth()->user()->name }}! 👋
@endsection

@section('content')
<style>
    /* Status Card Animation */
    .status-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }
    .status-card:hover {
        transform: translateY(-5px);
        background-color: #f0fdf4;
        border-color: #198754;
    }
    .status-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 24px;
        margin-bottom: 15px;
    }

    /* Wallet Card Styling */
    .wallet-card {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        color: white;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .wallet-card::after {
        content: "";
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        top: -50px;
        right: -50px;
    }

    /* Recent Transaction Item */
    .trx-item {
        border-left: 4px solid transparent;
        transition: all 0.2s;
    }
    .trx-item:hover {
        background-color: #f8fafc;
        border-left-color: #198754;
    }

    .stat-box {
        padding: 20px;
        border-radius: 15px;
        background: #f8fafc;
    }
</style>

<div class="dashboard-wrapper">

    {{-- 🔹 TRACKING STATUS ORDER --}}
    <div class="row g-3 mb-4">
        @php
            $stats = [
                'Waiting Payment' => ['icon' => 'bi-wallet2', 'color' => 'text-warning', 'bg' => 'bg-warning-subtle'],
                'Packing' => ['icon' => 'bi-box-seam', 'color' => 'text-primary', 'bg' => 'bg-primary-subtle'],
                'Sending' => ['icon' => 'bi-truck', 'color' => 'text-info', 'bg' => 'bg-info-subtle'],
                'Completed' => ['icon' => 'bi-check2-circle', 'color' => 'text-success', 'bg' => 'bg-success-subtle'],
            ];
        @endphp

        @foreach ($transactionStats as $status => $count)
        <div class="col-6 col-md-3">
            <div class="card status-card h-100 shadow-sm rounded-4 border-0 text-center p-3">
                <div class="status-icon mx-auto {{ $stats[$status]['bg'] ?? 'bg-light' }} {{ $stats[$status]['color'] ?? 'text-dark' }}">
                    <i class="bi {{ $stats[$status]['icon'] ?? 'bi-bag' }}"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ $count }}</h4>
                <p class="text-muted small mb-0">{{ $status }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- 🔹 LEFT PANEL: PROFILE & WALLET --}}
        <div class="col-lg-4">
            {{-- Wallet Card --}}
            <div class="card wallet-card shadow mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="mb-0 opacity-75">Saldo ZalpePay</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format(auth()->user()->wallet->balance ?? 0) }}</h3>
                        </div>
                        <i class="bi bi-cpu-fill fs-1 opacity-25"></i>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold">Top Up</button>
                        <button class="btn btn-link text-white text-decoration-none btn-sm">Riwayat <i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Akses Cepat</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.profile.edit') }}" class="btn btn-light text-start rounded-3 border-0 py-2">
                            <i class="bi bi-person-circle me-2 text-success"></i> Edit Profil
                        </a>
                        <a href="{{ route('customer.address.index') }}" class="btn btn-light text-start rounded-3 border-0 py-2">
                            <i class="bi bi-geo-alt-fill me-2 text-success"></i> Kelola Alamat
                        </a>
                        <a href="{{ route('customer.transaction.index') }}" class="btn btn-light text-start rounded-3 border-0 py-2">
                            <i class="bi bi-receipt me-2 text-success"></i> Riwayat Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔹 RIGHT PANEL: STATS & RECENT --}}
        <div class="col-lg-8">
            {{-- Stats Summary --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Statistik Belanja</h6>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="stat-box text-center">
                                <i class="bi bi-cart-check text-success fs-4 d-block mb-1"></i>
                                <h5 class="fw-bold mb-0">{{ $totalTransactions ?? 0 }}</h5>
                                <small class="text-muted">Pesanan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box text-center text-nowrap">
                                <i class="bi bi-wallet-fill text-success fs-4 d-block mb-1"></i>
                                <h5 class="fw-bold mb-0">Rp {{ number_format(($totalSpent ?? 0) / 1000) }}k</h5>
                                <small class="text-muted">Pengeluaran</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box text-center">
                                <i class="bi bi-pc-display-horizontal text-success fs-4 d-block mb-1"></i>
                                <h5 class="fw-bold mb-0">{{ $totalItems ?? 0 }}</h5>
                                <small class="text-muted">Item</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0">Transaksi Terbaru</h6>
                        <a href="{{ route('customer.transaction.index') }}" class="small text-success fw-bold text-decoration-none">Lihat Semua</a>
                    </div>

                    <div class="recent-list" style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                        @forelse($recentTransactions as $trx)
                            @php
                                $statusBadge = match ($trx->transaction_status) {
                                    'Waiting Payment' => 'bg-warning-subtle text-warning',
                                    'Delivered', 'Completed' => 'bg-success-subtle text-success',
                                    'Cancelled' => 'bg-danger-subtle text-danger',
                                    default => 'bg-primary-subtle text-primary',
                                };
                            @endphp

                            <div class="trx-item d-flex justify-content-between align-items-center p-3 rounded-4 mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                        <i class="bi bi-bag-check text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small mb-0">{{ $trx->transaction_type }}</div>
                                        <small class="text-muted" style="font-size: 11px;">{{ $trx->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark small mb-1">Rp {{ number_format($trx->subtotal) }}</div>
                                    <span class="badge rounded-pill {{ $statusBadge }}" style="font-size: 10px;">{{ $trx->transaction_status }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-receipt-cutoff text-muted fs-1 opacity-25"></i>
                                <p class="text-muted small mb-0 mt-2">Belum ada transaksi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection