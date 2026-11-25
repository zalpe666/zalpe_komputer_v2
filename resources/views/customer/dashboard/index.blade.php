@extends('layouts.customer-layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="row g-2 mb-2">
        @php
            $stats = [
                'Waiting Payment' => ['icon' => 'wallet', 'color' => 'text-warning', 'bg' => 'bg-warning-subtle'],
                'Packing' => ['icon' => 'package', 'color' => 'text-primary', 'bg' => 'bg-primary-subtle'],
                'Sending' => ['icon' => 'truck', 'color' => 'text-info', 'bg' => 'bg-info-subtle'],
                'Completed' => ['icon' => 'check-circle-2', 'color' => 'text-success', 'bg' => 'bg-success-subtle'],
            ];
        @endphp

        @foreach ($transactionStats as $status => $count)
            <div class="col-6 col-md-3">
                <div class="card h-100 shadow-sm rounded-4 border-light-subtle text-center p-3">
                    <div
                        class="mx-auto d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3 {{ $stats[$status]['bg'] ?? 'bg-light' }} {{ $stats[$status]['color'] ?? 'text-dark' }}">
                        <i data-lucide="{{ $stats[$status]['icon'] ?? 'shopping-bag' }}"></i>
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
            <div
                class="card bg-success bg-gradient text-white border-0 rounded-4 shadow mb-4 overflow-hidden position-relative">
                <div
                    class="position-absolute top-0 end-0 translate-middle-y bg-white bg-opacity-10 rounded-circle p-5 mt-3 me-n4">
                </div>

                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="mb-1 opacity-75 small">Saldo ZalpePay</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format(auth()->user()->wallet->balance ?? 0) }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-3 p-2">
                            <i data-lucide="cpu"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-success">Top Up</button>
                        <button
                            class="btn btn-link text-white text-decoration-none btn-sm d-inline-flex align-items-center gap-1">
                            Riwayat <i data-lucide="arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="card border-0 shadow-sm rounded-4 mb-2">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Akses Cepat</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.profile.edit') }}"
                            class="btn btn-light text-start rounded-3 border-0 py-2 px-3 d-flex align-items-center gap-2">
                            <i data-lucide="user-circle" class="text-success"></i>
                            <span>Edit Profil</span>
                        </a>
                        <a href="{{ route('customer.address.index') }}"
                            class="btn btn-light text-start rounded-3 border-0 py-2 px-3 d-flex align-items-center gap-2">
                            <i data-lucide="map-pin" class="text-success"></i>
                            <span>Kelola Alamat</span>
                        </a>
                        <a href="{{ route('customer.transaction.index') }}"
                            class="btn btn-light text-start rounded-3 border-0 py-2 px-3 d-flex align-items-center gap-2">
                            <i data-lucide="receipt" class="text-success"></i>
                            <span>Riwayat Transaksi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔹 RIGHT PANEL: STATS & RECENT --}}
        <div class="col-lg-8">
            {{-- Stats Summary --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-4">Statistik Belanja</h6>
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="bg-light rounded-4 p-3 text-center h-100">
                                <i data-lucide="shopping-cart" class="text-success mb-2"></i>
                                <h5 class="fw-bold mb-0">{{ $totalTransactions ?? 0 }}</h5>
                                <small class="text-muted">Pesanan</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="bg-light rounded-4 p-3 text-center h-100 text-nowrap">
                                <i data-lucide="wallet" class="text-success mb-2"></i>
                                <h5 class="fw-bold mb-0">Rp {{ number_format(($totalSpent ?? 0) / 1000) }}k</h5>
                                <small class="text-muted">Pengeluaran</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="bg-light rounded-4 p-3 text-center h-100">
                                <i data-lucide="monitor" class="text-success mb-2"></i>
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
                        <a href="{{ route('customer.transaction.index') }}"
                            class="small text-success fw-bold text-decoration-none">Lihat Semua</a>
                    </div>

                    <div class="vstack gap-2">
                        @forelse($recentTransactions as $trx)
                            @php
                                $statusBadge = match ($trx->transaction_status) {
                                    'Waiting Payment' => 'bg-warning-subtle text-warning',
                                    'Delivered', 'Completed' => 'bg-success-subtle text-success',
                                    'Cancelled' => 'bg-danger-subtle text-danger',
                                    default => 'bg-primary-subtle text-primary',
                                };
                            @endphp

                            <div
                                class="d-flex justify-content-between align-items-center p-3 rounded-4 border border-light-subtle bg-light bg-opacity-50">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm p-2 flex-shrink-0">
                                        <i data-lucide="shopping-bag" class="text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small mb-0">{{ $trx->transaction_type }}</div>
                                        <small class="text-muted">{{ $trx->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark small mb-1">Rp {{ number_format($trx->subtotal) }}</div>
                                    <span
                                        class="badge rounded-pill {{ $statusBadge }}">{{ $trx->transaction_status }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i data-lucide="receipt" class="text-muted opacity-25"></i>
                                <p class="text-muted small mb-0 mt-2">Belum ada transaksi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
