@extends('layouts.customer-layout')

@section('title', 'Detail Transaksi')
@section('page_title', 'Detail Transaksi')

@section('content')
    {{-- 🔹 HEADER / INVOICE INFO --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="text-muted small">Invoice:</span>
            <span class="fw-bold text-dark">{{ $transaction->invoice }}</span>
            @php
                $badgeClass = match ($transaction->transaction_status) {
                    'Pending', 'Waiting Payment' => 'bg-warning-subtle text-warning',
                    'Packing' => 'bg-primary-subtle text-primary',
                    'Sending', 'Delivered' => 'bg-info-subtle text-info',
                    'Completed' => 'bg-success-subtle text-success',
                    'Cancelled' => 'bg-danger-subtle text-danger',
                    default => 'bg-secondary-subtle text-secondary',
                };
            @endphp
            <span class="badge rounded-pill px-3 py-2 fw-semibold {{ $badgeClass }}">
                {{ $transaction->transaction_status }}
            </span>
        </div>

        <a href="{{ route('customer.transaction.index') }}"
            class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1">
            <i data-lucide="arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- 🔹 ORDER TRACKING UI (100% Pure Bootstrap Stepper) --}}
    <div class="card bg-light border-light-subtle rounded-4 mb-4">
        <div class="card-body p-4">
            @php
                $isCancelled = $transaction->transaction_status === 'Cancelled';
                $statuses = [
                    ['name' => 'Pending', 'icon' => 'receipt', 'date' => $transaction->created_at],
                    ['name' => 'Waiting Payment', 'icon' => 'wallet', 'date' => $transaction->created_at],
                    ['name' => 'Packing', 'icon' => 'package', 'date' => $transaction->payment_date],
                    ['name' => 'Sending', 'icon' => 'truck', 'date' => $transaction->sending_date],
                    ['name' => 'Delivered', 'icon' => 'home', 'date' => $transaction->delivered_date],
                    ['name' => 'Completed', 'icon' => 'check-circle-2', 'date' => $transaction->completed_date],
                ];
                $statusNames = array_column($statuses, 'name');
                $currentIndex = array_search($transaction->transaction_status, $statusNames);
            @endphp

            @if ($isCancelled)
                <div class="text-center py-3">
                    <div
                        class="bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3">
                        <i data-lucide="x-circle"></i>
                    </div>
                    <h6 class="text-danger fw-bold mb-1">Pesanan Dibatalkan</h6>
                    <p class="text-muted small mb-0">
                        Pada {{ $transaction->canceled_date ? $transaction->canceled_date->format('d M Y, H:i') : '-' }}
                    </p>
                </div>
            @else
                <style>
                    .order-stepper-wrapper {
                        width: 100%;
                        overflow-x: auto;
                        overflow-y: hidden;
                        scrollbar-width: thin;
                        scrollbar-color: #adb5bd transparent;
                        padding-bottom: 10px;
                    }

                    .order-stepper {
                        display: flex;
                        min-width: 650px;
                        justify-content: space-between;
                        position: relative;
                    }

                    .order-step {
                        flex: 1;
                        position: relative;
                        text-align: center;
                        min-width: 0;
                    }

                    .order-step:not(:last-child)::after {
                        content: '';
                        position: absolute;
                        top: 22px;
                        left: calc(50% + 23px);
                        width: calc(100% - 46px);
                        height: 3px;
                        background: #dee2e6;
                        z-index: 0;
                    }

                    .order-step.completed:not(:last-child)::after {
                        background: #198754;
                    }

                    .order-step-icon {
                        width: 46px;
                        height: 46px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 10px;
                        position: relative;
                        z-index: 1;
                        background: #fff;
                        color: #adb5bd;
                        border: 2px solid #dee2e6;
                    }

                    .order-step.completed .order-step-icon {
                        background: #d1e7dd;
                        color: #198754;
                        border-color: #198754;
                    }

                    .order-step.active .order-step-icon {
                        background: #198754;
                        color: #fff;
                        border-color: #198754;
                        box-shadow: 0 0 0 5px rgba(25, 135, 84, .12);
                    }

                    .order-step-label {
                        font-size: 12px;
                        font-weight: 600;
                        color: #adb5bd;
                        white-space: nowrap;
                    }

                    .order-step.completed .order-step-label,
                    .order-step.active .order-step-label {
                        color: #212529;
                    }

                    .order-step-date {
                        display: block;
                        font-size: 10px;
                        color: #6c757d;
                        margin-top: 4px;
                        white-space: nowrap;
                    }

                    @media (max-width: 575.98px) {
                        .order-stepper {
                            min-width: 650px;
                        }
                    }
                </style>

                <div class="order-stepper-wrapper">
                    <div class="order-stepper">
                        @foreach ($statuses as $index => $status)
                            @php
                                $isCompleted = $currentIndex !== false && $index < $currentIndex;
                                $isActive = $currentIndex !== false && $index === $currentIndex;

                                $stepClass = $isCompleted ? 'completed' : ($isActive ? 'active' : '');
                            @endphp

                            <div class="order-step {{ $stepClass }}">
                                <div class="order-step-icon">
                                    <i data-lucide="{{ $status['icon'] }}" style="width: 20px; height: 20px;"></i>
                                </div>

                                <div class="order-step-label">
                                    {{ $status['name'] }}
                                </div>

                                <small class="order-step-date">
                                    {{ $status['date'] ? $status['date']->format('d M, H:i') : '-' }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


        </div>
    </div>

    {{-- 🔹 MAIN CONTENT --}}
    <div class="row g-4">

        {{-- LEFT PANEL: PRODUCT LIST & RINGKASAN BIAYA --}}
        <div class="col-xl-7 col-12">
            <div class="card border-light-subtle rounded-4 shadow-sm h-100">
                <div class="card-body p-3 p-md-4 d-flex flex-column">
                    <h6 class="fw-bold mb-3 pb-3 border-bottom d-flex align-items-center gap-2">
                        <i data-lucide="shopping-cart" class="text-success"></i>
                        <span>Detail Produk</span>
                    </h6>

                    {{-- Responsive Product List --}}
                    <div class="vstack gap-3 mb-4">
                        @foreach ($transaction->transactionDetails as $item)
                            <div
                                class="d-flex align-items-center justify-content-between gap-3 pb-3 border-bottom border-light">
                                <div class="d-flex align-items-center gap-3 overflow-hidden">
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}"
                                        class="rounded-3 bg-light border object-fit-contain flex-shrink-0" width="60"
                                        height="60">
                                    <div class="overflow-hidden">
                                        <h6 class="mb-1 text-dark fw-semibold small text-break">
                                            {{ $item->product->name }}
                                        </h6>
                                        <small class="text-muted d-block">
                                            {{ $item->qty }} &times; Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="fw-bold text-dark small">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- RINGKASAN BIAYA --}}
                    <div class="bg-light rounded-4 p-3 mt-auto">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Subtotal Produk</span>
                            <span class="fw-medium text-dark">
                                Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Diskon Merchant</span>
                            <span class="text-danger fw-medium">
                                - Rp {{ number_format($transaction->discount_by_merchant, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom small">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="fw-medium text-dark">
                                Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Total Belanja</span>
                            <span class="fw-bold fs-6 text-success">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: INFO PENGIRIMAN & PEMBAYARAN --}}
        <div class="col-xl-5 col-12 d-flex flex-column gap-3">

            {{-- Delivery Address --}}
            <div class="card border-light-subtle rounded-4 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="map-pin" class="text-success"></i>
                        <span>Alamat Pengiriman</span>
                    </h6>
                    <div class="fw-bold text-dark small mb-1">{{ $transaction->address->name }}</div>
                    <div class="text-muted small mb-2 d-flex align-items-center gap-1">
                        <i data-lucide="phone"></i>
                        <span>{{ $transaction->address->phone ?? '-' }}</span>
                    </div>

                    <div class="bg-light p-3 rounded-3 small text-muted">
                        <p class="mb-1 text-dark fw-medium">{{ $transaction->address->address }}</p>
                        @if ($transaction->address->detail_address)
                            <p class="mb-1">{{ $transaction->address->detail_address }}</p>
                        @endif
                        <p class="mb-1">
                            {{ $transaction->address->district->name ?? '-' }},
                            {{ $transaction->address->city->name ?? '-' }},
                            {{ $transaction->address->province->name ?? '-' }}
                        </p>
                        <p class="mb-0">Indonesia ({{ $transaction->address->postal_code ?? '-' }})</p>
                    </div>
                </div>
            </div>

            {{-- Delivery Detail --}}
            <div class="card border-light-subtle rounded-4 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="truck" class="text-success"></i>
                        <span>Pengiriman</span>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-muted">Kurir</span>
                        <span class="fw-medium text-dark">{{ $transaction->courier_name }}
                            ({{ $transaction->courier_service }})</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-muted">Berat</span>
                        <span class="fw-medium text-dark">{{ number_format($transaction->total_weight ?? 0) }} gr</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-muted">Estimasi</span>
                        <span class="fw-medium text-dark">{{ $transaction->estimated_delivery }}</span>
                    </div>
                    <div class="bg-light p-3 rounded-3 small mt-3">
                        <span class="text-muted d-block mb-1">Catatan:</span>
                        <span class="fst-italic text-dark">{{ $transaction->notes ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Detail --}}
            <div class="card border-light-subtle rounded-4 shadow-sm flex-grow-1">
                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="credit-card" class="text-success"></i>
                        <span>Pembayaran</span>
                    </h6>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Metode</span>
                        <span class="text-capitalize fw-medium text-dark">{{ $transaction->payment_method ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Status</span>
                        <span
                            class="text-capitalize fw-semibold text-{{ $transaction->payment_status === 'paid' ? 'success' : 'warning' }}">
                            {{ $transaction->payment_status ?? '-' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Tanggal</span>
                        <span class="fw-medium text-dark text-end">
                            {{ $transaction->payment_date ? $transaction->payment_date->format('d M Y, H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
