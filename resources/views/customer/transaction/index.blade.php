@extends('layouts.customer-layout')

@section('title', 'Daftar Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
    {{-- 🔍 FILTER SECTION --}}
    <div class="card bg-light border-light-subtle rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" id="filterForm" class="row g-2 align-items-center">
                <div class="col-12 col-sm-6 col-lg-3">
                    <select name="status" class="form-select border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach (['Pending', 'Waiting Payment', 'Packing', 'Sending', 'Delivered', 'Completed', 'Cancelled'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <select name="type" class="form-select border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        @foreach ($transactionTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-4 d-flex gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="form-control border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="form-control border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                </div>
                <div class="col-12 col-lg-2">
                    <a href="{{ route('customer.transaction.index') }}"
                        class="btn btn-outline-secondary w-100 rounded-3 border-0 shadow-sm bg-white d-inline-flex align-items-center justify-content-center gap-1">
                        <i data-lucide="rotate-ccw"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 📑 TRANSACTION LIST --}}
    <div class="vstack gap-3">
        @forelse ($transactions as $trx)
            @php
                $totalItems = $trx->transactionDetails->count();

                $typeIcons = [
                    'Shopping' => 'shopping-bag',
                    'Games' => 'gamepad-2',
                    'Top-Up' => 'coins',
                    'Phone Credit' => 'smartphone',
                ];

                $statusClasses = [
                    'Paid' => 'bg-primary-subtle text-primary',
                    'Waiting Payment' => 'bg-warning-subtle text-warning',
                    'Pending' => 'bg-warning-subtle text-warning',
                    'Cancelled' => 'bg-danger-subtle text-danger',
                    'Delivered' => 'bg-info-subtle text-info',
                    'Completed' => 'bg-success-subtle text-success',
                    'Packing' => 'bg-primary-subtle text-primary',
                    'Sending' => 'bg-info-subtle text-info',
                ];
            @endphp

            <div class="card border-light-subtle rounded-4 shadow-sm">
                <div class="card-body p-3 p-md-4">

                    {{-- Card Header --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded-3 flex-shrink-0">
                                <i data-lucide="{{ $typeIcons[$trx->transaction_type] ?? 'receipt' }}"></i>
                            </div>
                            <div>
                                <span class="fw-bold d-block text-dark">{{ $trx->transaction_type }}</span>
                                <small class="text-muted">{{ $trx->invoice }} &bull;
                                    {{ $trx->created_at->format('d M Y') }}</small>
                            </div>
                        </div>
                        <span
                            class="badge rounded-pill px-3 py-2 fw-semibold {{ $statusClasses[$trx->transaction_status] ?? 'bg-light text-dark' }}">
                            {{ $trx->transaction_status }}
                        </span>
                    </div>

                    {{-- Products & Price Summary --}}
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-lg-8">
                            <div class="product-wrapper vstack gap-3">
                                @foreach ($trx->transactionDetails as $key => $item)
                                    <div class="trx-product {{ $key > 0 ? 'd-none' : '' }}">
                                        <div class="d-flex align-items-start gap-3">
                                            @if ($trx->transaction_type == 'Top-Up')
                                                <div class="rounded-3 bg-success bg-opacity-10 border flex-shrink-0 d-flex align-items-center justify-content-center"
                                                    style="width: 68px; height: 68px;">
                                                    <i data-lucide="coins" class="text-success"
                                                        style="width: 32px; height: 32px;"></i>
                                                </div>
                                            @else
                                                <img src="{{ $item->product->image ?? '' }}"
                                                    class="rounded-3 bg-light border object-fit-contain flex-shrink-0"
                                                    width="68" height="68"
                                                    alt="{{ $item->product->name ?? 'Product Image' }}">
                                            @endif
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h6 class="fw-bold mb-1 text-dark text-truncate">
                                                    {{ $trx->transaction_type == 'Top-Up' ? 'Top-Up Zalpe Pay' : $item->product->name ?? 'Product Name' }}
                                                </h6>
                                                <p class="text-muted small mb-2">{{ $item->qty }} barang &times; Rp
                                                    {{ number_format($item->price) }}</p>

                                                <div class="d-flex flex-wrap gap-2">
                                                    @if (!is_null($item->code))
                                                        <button
                                                            class="btn btn-sm btn-light border text-success fw-semibold rounded-3 d-inline-flex align-items-center gap-1"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#code-{{ $item->id }}">
                                                            <i data-lucide="qr-code"></i>
                                                            <span>Lihat Kode</span>
                                                        </button>
                                                    @endif

                                                    @if ($trx->transaction_status == 'Completed' && $trx->transaction_type != 'Top-Up' && is_null($item->rating))
                                                        <a href="{{ route('customer.rating.rate.form', [$trx->id, $item->product->id]) }}"
                                                            class="btn btn-sm btn-outline-success rounded-3 d-inline-flex align-items-center gap-1">
                                                            <i data-lucide="star"></i>
                                                            <span>Beri Ulasan</span>
                                                        </a>
                                                    @endif

                                                    @if ($trx->transaction_status == 'Completed' && $trx->transaction_type == 'Games')
                                                        <a href="#"
                                                            class="btn btn-sm btn-outline-success rounded-3 d-inline-flex align-items-center gap-1">
                                                            <i data-lucide="star"></i>
                                                            <span>Launch</span>
                                                        </a>
                                                    @endif
                                                </div>

                                                @if (!is_null($item->code))
                                                    <div class="collapse mt-2" id="code-{{ $item->id }}">
                                                        <div
                                                            class="p-2 bg-light rounded-3 border text-center font-monospace fw-bold text-dark user-select-all">
                                                            {{ $item->code }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($totalItems > 1)
                                    <div>
                                        <button
                                            class="btn btn-sm btn-link text-success fw-semibold text-decoration-none p-0 d-inline-flex align-items-center gap-1"
                                            type="button" onclick="toggleProducts(this)"
                                            data-remaining="{{ $totalItems - 1 }}">
                                            <i data-lucide="chevron-down"></i>
                                            <span>Lihat {{ $totalItems - 1 }} produk lainnya</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Price Summary (Border atas di mobile, border kiri di desktop) --}}
                        <div class="col-12 col-lg-4 border-lg-top-0 border-lg-start pt-3 pt-lg-0 ps-lg-4">
                            <div class="d-flex d-lg-block justify-content-between align-items-center text-lg-end">
                                <span class="text-muted small d-block mb-lg-1">Total Belanja</span>
                                <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($trx->total) }}</h5>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('customer.transaction.show', $trx->id) }}"
                            class="btn btn-light rounded-3 px-3 fw-semibold text-dark d-inline-flex align-items-center gap-1">
                            <i data-lucide="eye"></i>
                            <span>Detail</span>
                        </a>

                        @if ($trx->transaction_status == 'Delivered')
                            <form action="{{ route('customer.transaction.complete', $trx->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn btn-success rounded-3 px-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1">
                                    <i data-lucide="check-circle-2"></i>
                                    <span>Selesaikan Pesanan</span>
                                </button>
                            </form>
                        @endif

                        @if (in_array($trx->transaction_status, ['Packing', 'Waiting Payment', 'Pending']))
                            <form action="{{ route('customer.transaction.cancel', $trx->id) }}" method="POST"
                                class="form-cancel">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn btn-outline-danger rounded-3 px-3 fw-semibold d-inline-flex align-items-center gap-1">
                                    <i data-lucide="x-circle"></i>
                                    <span>Batalkan</span>
                                </button>
                            </form>
                        @endif

                        @if ($trx->transaction_status == 'Waiting Payment' || $trx->transaction_status == 'Pending')
                            <a href="{{ route('customer.transaction.payment', $trx->id) }}"
                                class="btn btn-success rounded-3 px-4 fw-semibold shadow-sm d-inline-flex align-items-center gap-1">
                                <i data-lucide="credit-card"></i>
                                <span>Bayar Sekarang</span>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="card border-light-subtle rounded-4 bg-light bg-opacity-50 text-center py-5 px-3">
                <div class="card-body">
                    <div
                        class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center p-3 mb-3 text-secondary">
                        <i data-lucide="receipt"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Belum Ada Transaksi Ditemukan</h6>
                    <p class="text-muted small mb-3">Riwayat belanja atau top-up Anda akan muncul di sini.</p>
                    <a href="{{ route('customer.product.index') }}"
                        class="btn btn-success rounded-pill px-4 fw-semibold d-inline-flex align-items-center gap-2">
                        <i data-lucide="shopping-bag"></i>
                        <span>Mulai Belanja</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class=" mt-4">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
@endsection

@push('script')
    <script>
        function toggleProducts(button) {
            const wrapper = button.closest('.product-wrapper');
            const hiddenProducts = wrapper.querySelectorAll('.trx-product:not(:first-child)');
            const isHidden = hiddenProducts[0].classList.contains('d-none');
            const count = button.getAttribute('data-remaining');

            if (isHidden) {
                hiddenProducts.forEach(el => el.classList.remove('d-none'));
                button.innerHTML = '<i data-lucide="chevron-up"></i><span>Tampilkan lebih sedikit</span>';
            } else {
                hiddenProducts.forEach(el => el.classList.add('d-none'));
                button.innerHTML = `<i data-lucide="chevron-down"></i><span>Lihat ${count} produk lainnya</span>`;
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        document.querySelectorAll('.form-cancel').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Batalkan transaksi?',
                    text: "Pesanan yang dibatalkan tidak dapat dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, batalkan!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
