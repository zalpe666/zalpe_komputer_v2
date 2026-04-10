@extends('layouts.customer-layout')

@section('content')
    <div>
        <h3 class="mb-3">Transactions List</h3>

        <form method="GET" id="filterForm" class="d-flex gap-2 mb-3">
            {{-- Filter Status --}}
            <select name="status" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                <option value="">All Status</option>
                @foreach (['Pending', 'Waiting Payment', 'Packing', 'Sending', 'Delivered', 'Completed', 'Cancelled'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Tipe Transaksi --}}
            <select name="type" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                <option value="">All Types</option>
                @foreach ($transactionTypes as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Tanggal --}}
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="form-control form-control-sm rounded-3" onchange="this.form.submit()">
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="form-control form-control-sm rounded-3" onchange="this.form.submit()">

            {{-- Reset Filter --}}
            <a href="{{ route('customer.transaction.index') }}" class="btn btn-sm btn-danger rounded-3">Reset</a>
        </form>

        {{-- ================== List Transaksi ================== --}}
        @forelse ($transactions as $trx)
            @php $totalItems = $trx->transactionDetails->count(); @endphp

            <div class="card mb-3">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $typeIcons = [
                                    'Shopping' => 'bi bi-bag',
                                    'Games' => 'bi bi-controller',
                                    'Top-Up' => 'bi bi-cash-stack',
                                    'Phone Credit' => 'bi bi-phone',
                                ];

                                $statusBadges = [
                                    'Paid' => 'bg-primary',
                                    'Pending' => 'bg-warning',
                                    'Cancelled' => 'bg-danger',
                                    'Delivered' => 'bg-info',
                                    'Completed' => 'bg-success',
                                ];
                            @endphp

                            {{-- Transaction Type --}}
                            <span class="d-flex align-items-center fw-bold">
                                <i class="{{ $typeIcons[$trx->transaction_type] ?? 'bi bi-question-circle' }} me-2"></i>
                                {{ $trx->transaction_type }}
                            </span>

                            {{-- Invoice --}}
                            <span class="text-muted">
                                {{ $trx->invoice }}
                            </span>

                            {{-- Status --}}
                            <span class="badge {{ $statusBadges[$trx->transaction_status] ?? 'bg-secondary' }}">
                                {{ $trx->transaction_status }}
                            </span>

                        </div>

                        {{-- Date --}}
                        <span class="text-muted small">
                            {{ $trx->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Produk --}}
                    <div class="row align-items-center">
                        <div class="col-md-10 border-end">
                            @foreach ($trx->transactionDetails as $key => $item)
                                <div class="trx-product mb-2 @if ($key > 0) d-none @endif">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item->product->image }}" width="80" alt="">
                                        <div>
                                            <strong>{{ $item->product->name }}</strong><br>
                                            <small>{{ $item->qty }} x Rp {{ number_format($item->price) }}</small>
                                            <br>

                                            @if (!is_null($item->code))
                                                {{-- Tombol toggle --}}
                                                <button class="btn btn-sm btn-outline-secondary mt-1" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#code-{{ $item->id }}"
                                                    aria-expanded="false" aria-controls="code-{{ $item->id }}">
                                                    Show Code
                                                </button>

                                                {{-- Kode yang bisa disembunyikan --}}
                                                <div class="collapse mt-1" id="code-{{ $item->id }}">
                                                    <span class="text-success">{{ $item->code }}</span>
                                                </div>
                                            @endif

                                            @if ($trx->transaction_status == 'Completed')
                                                <div class="mt-2">
                                                    @if (is_null($item->rating))
                                                        <a href="{{ route('customer.rating.rate.form', [$trx->id, $item->product->id]) }}"
                                                            class="text-muted">Rate</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Rate Produk --}}

                                </div>
                            @endforeach

                            {{-- Tombol Lihat Semua Produk --}}
                            @if ($totalItems > 1)
                                <div class="text-end">
                                    <button
                                        class="btn btn-sm btn-link d-inline-flex align-items-center gap-2 px-3 py-1  text-muted"
                                        type="button" onclick="toggleProducts(this)">
                                        <span>See More ({{ $totalItems }})</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-2 text-center">
                            <span>Total Belanja</span> <br>
                            <span class="text-success fw-bold">Rp {{ number_format($trx->total) }}</span>
                        </div>
                    </div>
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 mt-3">

                        @if ($trx->transaction_status == 'Delivered')
                            <form action="{{ route('customer.transaction.complete', $trx->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-success px-3">Terima Paket</button>
                            </form>
                        @endif

                        @if (in_array($trx->transaction_status, ['Packing', 'Waiting Payment']))
                            <form action="{{ route('customer.transaction.cancel', $trx->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-outline-danger px-3">Cancel</button>
                            </form>
                        @endif

                        @if ($trx->transaction_status == 'Waiting Payment')
                            <a href="{{ route('customer.transaction.payment', $trx->id) }}" class="btn btn-success px-3">
                                Bayar
                            </a>
                        @endif

                        <a href="{{ route('customer.transaction.show', $trx->id) }}" class="btn btn-dark px-3">
                            Detail
                        </a>

                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Belum ada transaksi</div>
        @endforelse

        <div class="mt-3">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

@push('script')
    <script>
        function toggleProducts(button) {
            const cardBody = button.closest('.card-body');
            const products = cardBody.querySelectorAll('.trx-product');

            const icon = button.querySelector('i');
            const text = button.querySelector('span');

            const isHidden = Array.from(products).some((el, i) => i > 0 && el.classList.contains('d-none'));

            if (isHidden) {
                // show all
                products.forEach(el => el.classList.remove('d-none'));

                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');

                text.innerText = 'See Less';
            } else {
                // hide except first
                products.forEach((el, i) => {
                    if (i > 0) el.classList.add('d-none');
                });

                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');

                text.innerText = `See Less (${products.length})`;
            }
        }
    </script>
@endpush
