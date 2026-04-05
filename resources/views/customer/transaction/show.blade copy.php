@extends('layouts.customer-layout')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="mb-4">
            <h4 class="fw-semibold mb-1">Detail Transaksi</h4>
            <span class="text-muted">{{ $transaction->invoice }}</span>
        </div>
        <div class="row">
            <div class="col-md-6">

                @if ($transaction->address)
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body">

                            <h6 class="mb-3">
                                <i class="bi bi-geo-alt"></i> Alamat Pengiriman
                            </h6>

                            {{-- Nama --}}
                            <div class="fw-bold">
                                {{ $transaction->address->name }}
                            </div>

                            {{-- Nomor HP --}}
                            <div class="text-muted mb-2">
                                {{ $transaction->address->phone ?? '-' }}
                            </div>

                            {{-- Alamat utama --}}
                            <div>
                                {{ $transaction->address->address }}
                            </div>

                            {{-- Detail alamat --}}
                            @if ($transaction->address->detail_address)
                                <div>
                                    {{ $transaction->address->detail_address }}
                                </div>
                            @endif

                            {{-- Wilayah --}}
                            <div class="text-muted mt-2">
                                {{ $transaction->address->district->name ?? '-' }},
                                {{ $transaction->address->city->name ?? '-' }},
                                {{ $transaction->address->province->name ?? '-' }}
                            </div>

                            {{-- Negara + Kode Pos --}}
                            <div class="text-muted">
                                Indonesia ({{ $transaction->address->postal_code ?? '' }})
                            </div>
                        @else
                            <div class="text-muted">Alamat tidak tersedia</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">

                <h6 class="mb-3">
                    <i class="bi bi-receipt"></i> Order Detail
                </h6>

                {{-- Order ID --}}
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Order ID</span>
                    <span class="fw-semibold">#{{ $transaction->id ?? '-' }}</span>
                </div>

                {{-- Tanggal --}}
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tanggal</span>
                    <span>
                        {{ $transaction->created_at ? $transaction->created_at->format('d M Y H:i') : '-' }}
                    </span>
                </div>

                {{-- Total --}}
                <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                    <span class="fw-semibold">Total</span>
                    <span class="fw-bold text-success">
                        Rp {{ number_format($transaction->total ?? 0, 0, ',', '.') }}
                    </span>
                </div>

            </div>
        </div>
    </div>
    </div>
    <div class="card mb-3 shadow-sm rounded-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Informasi Transaksi</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <b>Invoice:</b>
                    <div>{{ $transaction->invoice }}</div>
                </div>

                <div class="col-md-6">
                    <b>Status Transaksi:</b>
                    <div>
                        <span
                            class="badge {{ match ($transaction->transaction_status) {
                                'Pending' => 'bg-warning',
                                'Waiting Payment' => 'bg-secondary',
                                'Packing' => 'bg-info',
                                'Sending' => 'bg-primary',
                                'Delivered' => 'bg-info',
                                'Completed' => 'bg-success',
                                'Cancelled' => 'bg-danger',
                                default => 'bg-secondary',
                            } }}">
                            {{ $transaction->transaction_status }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <b>Tipe Transaksi:</b>
                    <div>{{ $transaction->transaction_type }}</div>
                </div>

                <div class="col-md-6">
                    <b>Kurir & Layanan:</b>
                    <div>{{ $transaction->courier_name }} - {{ $transaction->courier_service }}</div>
                </div>

                <div class="col-md-6">
                    <b>Estimasi:</b>
                    <div>{{ $transaction->estimated_delivery }}</div>
                </div>

                <div class="col-md-6">
                    <b>Metode Pembayaran:</b>
                    <div>{{ $transaction->payment_method ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <b>Status Pembayaran:</b>
                    <div>{{ $transaction->payment_status }}</div>
                </div>

                @if ($transaction->payment_date)
                    <div class="col-md-6">
                        <b>Tanggal Bayar:</b>
                        <div>{{ $transaction->payment_date->format('d M Y H:i') }}</div>
                    </div>
                @endif

                @if ($transaction->sending_date)
                    <div class="col-md-6">
                        <b>Dikirim:</b>
                        <div>{{ $transaction->sending_date->format('d M Y H:i') }}</div>
                    </div>
                @endif

                @if ($transaction->delivered_date)
                    <div class="col-md-6">
                        <b>Diterima:</b>
                        <div>{{ $transaction->delivered_date->format('d M Y H:i') }}</div>
                    </div>
                @endif

                @if ($transaction->canceled_date)
                    <div class="col-md-6">
                        <b>Dibatalkan:</b>
                        <div>{{ $transaction->canceled_date->format('d M Y H:i') }}</div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Produk --}}
    <h5 class="mb-3">Produk</h5>
    @foreach ($transaction->transactionDetails as $item)
        <div class="card mb-2 shadow-sm rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                    <small class="text-muted">
                        {{ $item->qty }} x Rp {{ number_format($item->price) }}
                    </small>
                </div>
                <div class="fw-semibold">
                    Rp {{ number_format($item->total) }}
                </div>
            </div>
        </div>
    @endforeach

    {{-- Ringkasan Harga --}}
    <div class="card mt-4 shadow-sm rounded-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Ringkasan Harga</h5>

            <div class="d-flex justify-content-between mb-1">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaction->subtotal) }}</span>
            </div>

            @if ($transaction->discount_by_merchant > 0)
                <div class="d-flex justify-content-between mb-1">
                    <span>Diskon Merchant</span>
                    <span class="text-success">- Rp {{ number_format($transaction->discount_by_merchant) }}</span>
                </div>
            @endif

            @if ($transaction->discount_by_voucher > 0)
                <div class="d-flex justify-content-between mb-1">
                    <span>Diskon Voucher</span>
                    <span class="text-success">- Rp {{ number_format($transaction->discount_by_voucher) }}</span>
                </div>
            @endif

            <div class="d-flex justify-content-between mb-1">
                <span>Ongkir</span>
                <span>Rp {{ number_format($transaction->shipping_cost) }}</span>
            </div>

            <hr>

            <div class="d-flex justify-content-between fw-semibold fs-5">
                <span>Total</span>
                <span>Rp {{ number_format($transaction->total) }}</span>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if ($transaction->notes)
        <div class="mt-3">
            <h6>Catatan</h6>
            <p class="text-muted">{{ $transaction->notes }}</p>
        </div>
    @endif

    {{-- Back Button --}}
    <div class="mt-4">
        <a href="{{ route('customer.transaction.index') }}" class="btn btn-secondary px-4">
            Kembali
        </a>
    </div>

    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: "{{ session('success') }}",
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}",
                });
            @endif
        });
    </script>
@endpush
