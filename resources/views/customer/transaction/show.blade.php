@extends('layouts.customer-layout')

{{-- Mengisi Judul Sesuai Parent --}}
@section('page_title', 'Detail Transaksi')

@section('content')
<style>
    /* CUSTOM CSS FOR SATISFYING ORDER TRACKING UI */
    .tracking-wrapper {
        padding: 1.5rem 0;
    }
    .stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 10px;
        padding-top: 5px;
    }
    .stepper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: #e9ecef;
        z-index: 1;
        border-radius: 4px;
    }
    .stepper-progress {
        position: absolute;
        top: 25px;
        left: 0;
        height: 4px;
        background-color: #0d6efd;
        z-index: 2;
        transition: width 0.5s ease;
        border-radius: 4px;
    }
    .stepper-item {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
    }
    .stepper-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 8px;
        border: 4px solid #fff;
        transition: all 0.3s ease;
    }
    .stepper-item.completed .stepper-icon,
    .stepper-item.active .stepper-icon {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
    }
    .stepper-item.active .stepper-icon {
        animation: pulse 2s infinite;
    }
    .stepper-item.cancelled .stepper-icon {
        background-color: #dc3545;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.2);
    }
    .stepper-label {
        font-weight: 600;
        font-size: 0.8rem;
        color: #6c757d;
        line-height: 1.2;
    }
    .stepper-item.completed .stepper-label,
    .stepper-item.active .stepper-label {
        color: #212529;
    }
    .stepper-date {
        font-size: 0.7rem;
        color: #adb5bd;
        margin-top: 4px;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(13, 110, 253, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }
    
    /* UTILITIES */
    .bg-light-soft { background-color: #f8f9fa; }
    .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; }
    .table-cart td { vertical-align: middle; }
</style>

<div>
    {{-- 🔹 HEADER / INVOICE INFO --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <span class="text-muted d-flex align-items-center gap-2">
                Invoice: <strong class="text-dark">{{ $transaction->invoice }}</strong>
                <span class="badge {{ match ($transaction->transaction_status) {
                    'Pending' => 'bg-warning text-dark',
                    'Waiting Payment' => 'bg-secondary',
                    'Packing' => 'bg-info',
                    'Sending' => 'bg-primary',
                    'Delivered' => 'bg-info',
                    'Completed' => 'bg-success',
                    'Cancelled' => 'bg-danger',
                    default => 'bg-secondary',
                } }} rounded-pill px-3 py-2 ms-2">
                    {{ $transaction->transaction_status }}
                </span>
            </span>
        </div>
        <a href="{{ route('customer.transaction.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    {{-- 🔹 ORDER TRACKING UI --}}
    <div class="border border-opacity-50 rounded-4 mb-4 p-4 tracking-wrapper bg-light-soft">
        @php
            $isCancelled = $transaction->transaction_status === 'Cancelled';
            $statuses = [
                ['name' => 'Pending', 'icon' => 'bi-receipt', 'date' => $transaction->created_at],
                ['name' => 'Waiting Payment', 'icon' => 'bi-wallet2', 'date' => $transaction->created_at], 
                ['name' => 'Packing', 'icon' => 'bi-box-seam', 'date' => $transaction->payment_date],
                ['name' => 'Sending', 'icon' => 'bi-truck', 'date' => $transaction->sending_date],
                ['name' => 'Delivered', 'icon' => 'bi-house-check', 'date' => $transaction->delivered_date],
                ['name' => 'Completed', 'icon' => 'bi-check-circle-fill', 'date' => $transaction->completed_date],
            ];
            $statusNames = array_column($statuses, 'name');
            $currentIndex = array_search($transaction->transaction_status, $statusNames);
            
            $progressWidth = 0;
            if ($currentIndex !== false && !$isCancelled) {
                $progressWidth = ($currentIndex / (count($statuses) - 1)) * 100;
            } elseif ($isCancelled) {
                $progressWidth = 100;
            }
        @endphp

        @if($isCancelled)
            <div class="text-center py-3">
                <i class="bi bi-x-circle-fill text-danger" style="font-size: 2.5rem;"></i>
                <h6 class="text-danger mt-3 fw-bold">Pesanan Dibatalkan</h6>
                <p class="text-muted small mb-0">Pada {{ $transaction->canceled_date ? $transaction->canceled_date->format('d M Y H:i') : '-' }}</p>
            </div>
        @else
            <div class="stepper overflow-x-auto overflow-y-hidden pb-2">
                <div class="stepper-progress" style="width: {{ $progressWidth }}%;"></div>
                @foreach($statuses as $index => $status)
                    @php
                        $stateClass = '';
                        if ($index < $currentIndex) $stateClass = 'completed';
                        elseif ($index === $currentIndex) $stateClass = 'active';
                    @endphp
                    <div class="stepper-item {{ $stateClass }}" style="min-width: 90px;">
                        <div class="stepper-icon">
                            <i class="bi {{ $status['icon'] }}"></i>
                        </div>
                        <div class="stepper-label">{{ $status['name'] }}</div>
                        <div class="stepper-date">
                            {{ $status['date'] ? $status['date']->format('d M, H:i') : '-' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 🔹 MAIN CONTENT --}}
    <div class="row g-4">
        
        {{-- LEFT PANEL: PRODUCT LIST --}}
        {{-- Menggunakan col-xl-7 agar tidak terlalu sempit di layar laptop standar --}}
        <div class="col-xl-7 col-12 d-flex flex-column gap-4">
            <div class="border border-opacity-50 rounded-4 p-4 h-100">
                <h6 class="fw-bold mb-3 border-bottom pb-3"><i class="bi bi-cart3 me-2"></i> Detail Produk</h6>
                
                <div class="table-responsive">
                    <table class="table table-borderless table-cart mb-4">
                        <thead class="text-muted border-bottom text-uppercase fs-8">
                            <tr>
                                <th scope="col" class="pb-2">Produk</th>
                                <th scope="col" class="pb-2 text-center">Harga</th>
                                <th scope="col" class="pb-2 text-center">Qty</th>
                                <th scope="col" class="pb-2 text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->transactionDetails as $item)
                                <tr class="border-bottom border-light">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="product-img border">
                                            <div>
                                                <h6 class="mb-0 text-dark fw-semibold fs-7">{{ $item->product->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center fs-7">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-3 text-center fs-7">{{ $item->qty }}</td>
                                    <td class="py-3 text-end fw-semibold text-dark fs-7">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- RINGKASAN BIAYA --}}
                <div class="bg-light-soft rounded-4 p-3 mt-auto">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal Produk</span>
                        <span class="fw-medium">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Diskon Merchant</span>
                        <span class="text-danger fw-medium">- Rp {{ number_format($transaction->discount_by_merchant, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3 small">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span class="fw-medium">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Belanja</span>
                        <span class="fw-bold fs-6">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: INFO PENGIRIMAN & PEMBAYARAN --}}
        <div class="col-xl-5 col-12 d-flex flex-column gap-3">
            
            {{-- Delivery Address --}}
            <div class="border border-opacity-50 rounded-4 p-3">
                <h6 class="fw-bold mb-3 fs-7">
                    <i class="bi bi-geo-alt-fill me-2"></i> Alamat Pengiriman
                </h6>
                <div class="fw-bold text-dark fs-7 mb-1">{{ $transaction->address->name }}</div>
                <div class="text-muted fs-8 mb-2"><i class="bi bi-telephone-fill me-1"></i> {{ $transaction->address->phone ?? '-' }}</div>
                
                <div class="bg-light-soft p-2 rounded-3 fs-8 text-muted">
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

            {{-- Delivery Detail --}}
            <div class="border border-opacity-50 rounded-4 p-3">
                <h6 class="fw-bold mb-3 fs-7">
                    <i class="bi bi-truck me-2"></i> Pengiriman
                </h6>
                <div class="d-flex justify-content-between align-items-center mb-2 fs-8">
                    <span class="text-muted">Kurir</span>
                    <span class="fw-medium">{{ $transaction->courier_name }} ({{ $transaction->courier_service }})</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 fs-8">
                    <span class="text-muted">Berat</span>
                    <span class="fw-medium">{{ number_format($transaction->total_weight ?? 0) }} gr</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 fs-8">
                    <span class="text-muted">Estimasi</span>
                    <span class="fw-medium">{{ $transaction->estimated_delivery }}</span>
                </div>
                <div class="d-flex flex-column mt-2 bg-light-soft p-2 rounded-3 fs-8">
                    <span class="text-muted mb-1">Catatan:</span>
                    <span class="fst-italic text-dark">{{ $transaction->notes ?? '-' }}</span>
                </div>
            </div>

            {{-- Payment Detail --}}
            <div class="border border-opacity-50 rounded-4 p-3 h-100">
                <h6 class="fw-bold mb-3 fs-7">
                    <i class="bi bi-credit-card-fill me-2"></i> Pembayaran
                </h6>
                <div class="d-flex justify-content-between mb-2 fs-8">
                    <span class="text-muted">Metode</span>
                    <span class="text-capitalize fw-medium">{{ $transaction->payment_method ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 fs-8">
                    <span class="text-muted">Status</span>
                    <span class="text-capitalize fw-medium text-{{ $transaction->payment_status === 'paid' ? 'success' : 'warning' }}">
                        {{ $transaction->payment_status ?? '-' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between fs-8">
                    <span class="text-muted">Tanggal</span>
                    <span class="fw-medium text-end">
                        {{ $transaction->payment_date ? $transaction->payment_date->format('d M Y, H:i') : '-' }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection