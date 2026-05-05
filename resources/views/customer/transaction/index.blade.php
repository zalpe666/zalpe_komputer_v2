@extends('layouts.customer-layout')

@section('title', 'Daftar Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<style>
    /* Filter Styling */
    .filter-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 15px;
    }

    /* Transaction Card Styling */
    .trx-card {
        border: 1px solid #edf2f7;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .trx-card:hover {
        border-color: #198754;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .trx-header {
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 12px;
    }

    .product-img {
        width: 70px;
        height: 70px;
        object-fit: contain;
        background: #f8f9fa;
        border-radius: 12px;
    }

    /* Soft Badges */
    .badge-soft {
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
    }
    .badge-soft-success { background: #dcfce7; color: #15803d; }
    .badge-soft-warning { background: #fef9c3; color: #a16207; }
    .badge-soft-danger { background: #fee2e2; color: #b91c1c; }
    .badge-soft-primary { background: #dbeafe; color: #1d4ed8; }
    .badge-soft-info { background: #ecfeff; color: #0e7490; }

    .btn-action {
        border-radius: 10px;
        font-weight: 600;
        padding: 8px 20px;
    }
</style>

<div class="transaction-container">
    
    {{-- 🔍 FILTER SECTION --}}
    <div class="card filter-card border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" id="filterForm" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="status" class="form-select border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach (['Pending', 'Waiting Payment', 'Packing', 'Sending', 'Delivered', 'Completed', 'Cancelled'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        @foreach ($transactionTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <a href="{{ route('customer.transaction.index') }}" class="btn btn-outline-secondary w-100 rounded-3 border-0 shadow-sm bg-white">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- 📑 TRANSACTION LIST --}}
    @forelse ($transactions as $trx)
        @php 
            $totalItems = $trx->transactionDetails->count(); 
            
            // Logic mapping for UI
            $typeIcons = [
                'Shopping' => 'bi-bag-check-fill',
                'Games' => 'bi-controller',
                'Top-Up' => 'bi-cash-stack',
                'Phone Credit' => 'bi-phone-fill',
            ];

            $statusClasses = [
                'Paid' => 'badge-soft-primary',
                'Waiting Payment' => 'badge-soft-warning',
                'Pending' => 'badge-soft-warning',
                'Cancelled' => 'badge-soft-danger',
                'Delivered' => 'badge-soft-info',
                'Completed' => 'badge-soft-success',
                'Packing' => 'badge-soft-primary',
                'Sending' => 'badge-soft-info',
            ];
        @endphp

        <div class="card trx-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                
                {{-- Card Header --}}
                <div class="trx-header d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 p-2 rounded-3">
                            <i class="bi {{ $typeIcons[$trx->transaction_type] ?? 'bi-receipt' }} text-success fs-5"></i>
                        </div>
                        <div>
                            <span class="fw-bold d-block text-dark">{{ $trx->transaction_type }}</span>
                            <small class="text-muted">{{ $trx->invoice }} • {{ $trx->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                    <span class="badge-soft {{ $statusClasses[$trx->transaction_status] ?? 'bg-light text-dark' }}">
                        {{ $trx->transaction_status }}
                    </span>
                </div>

                {{-- Products List --}}
                <div class="row g-4 align-items-center">
                    <div class="col-lg-9">
                        <div class="product-wrapper">
                            @foreach ($trx->transactionDetails as $key => $item)
                                <div class="trx-product mb-3 {{ $key > 0 ? 'd-none' : '' }}">
                                    <div class="d-flex gap-3">
                                        <img src="{{ $item->product->image }}" class="product-img" alt="Product">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1 text-dark">{{ $item->product->name }}</h6>
                                            <p class="text-muted small mb-1">{{ $item->qty }} barang x Rp {{ number_format($item->price) }}</p>
                                            
                                            @if (!is_null($item->code))
                                                <button class="btn btn-sm btn-light py-0 px-2 border text-success fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#code-{{ $item->id }}">
                                                    <i class="bi bi-upc-scan me-1"></i> Lihat Kode
                                                </button>
                                                <div class="collapse mt-2" id="code-{{ $item->id }}">
                                                    <div class="p-2 bg-light rounded border border-dashed text-center fw-mono">{{ $item->code }}</div>
                                                </div>
                                            @endif

                                            @if ($trx->transaction_status == 'Completed' && is_null($item->rating))
                                                <a href="{{ route('customer.rating.rate.form', [$trx->id, $item->product->id]) }}" class="btn btn-sm btn-outline-success py-0 px-2 mt-1">
                                                    <i class="bi bi-star me-1"></i> Beri Ulasan
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if ($totalItems > 1)
                                <button class="btn btn-sm text-success fw-bold p-0 mt-1" type="button" onclick="toggleProducts(this)">
                                    <i class="bi bi-chevron-down me-1"></i> Lihat {{ $totalItems - 1 }} produk lainnya
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Price Summary --}}
                    <div class="col-lg-3 text-lg-end border-start">
                        <p class="text-muted small mb-1">Total Belanja</p>
                        <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($trx->total) }}</h5>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('customer.transaction.show', $trx->id) }}" class="btn btn-light btn-action text-dark">
                        Detail
                    </a>

                    @if ($trx->transaction_status == 'Delivered')
                        <form action="{{ route('customer.transaction.complete', $trx->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="btn btn-success btn-action shadow-sm">Selesaikan Pesanan</button>
                        </form>
                    @endif

                    @if (in_array($trx->transaction_status, ['Packing', 'Waiting Payment']))
                        <form action="{{ route('customer.transaction.cancel', $trx->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="btn btn-outline-danger btn-action">Batalkan</button>
                        </form>
                    @endif

                    @if ($trx->transaction_status == 'Waiting Payment' || $trx->transaction_status == 'Pending')
                        <a href="{{ route('customer.transaction.payment', $trx->id) }}" class="btn btn-success btn-action shadow-sm px-4">
                            Bayar Sekarang
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-receipt-cutoff text-muted display-1 opacity-25"></i>
            <h5 class="mt-3 text-muted">Belum ada transaksi ditemukan</h5>
            <a href="{{ route('customer.product.index') }}" class="btn btn-success rounded-pill mt-2 px-4">Mulai Belanja</a>
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('script')
    <script>
        function toggleProducts(button) {
            const wrapper = button.closest('.product-wrapper');
            const hiddenProducts = wrapper.querySelectorAll('.trx-product:not(:first-child)');
            const isHidden = hiddenProducts[0].classList.contains('d-none');

            if (isHidden) {
                hiddenProducts.forEach(el => el.classList.remove('d-none'));
                button.innerHTML = '<i class="bi bi-chevron-up me-1"></i> Tampilkan lebih sedikit';
            } else {
                hiddenProducts.forEach(el => el.classList.add('d-none'));
                button.innerHTML = `<i class="bi bi-chevron-down me-1"></i> Lihat ${hiddenProducts.length} produk lainnya`;
            }
        }
    </script>
@endpush