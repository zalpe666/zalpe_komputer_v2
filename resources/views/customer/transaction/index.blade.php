@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-4 h-100 bg-primary opacity-75">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, saepe
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione
                nobis...
            </div>

            {{-- Riwayat Transaksi --}}
            <div class="col-md-8">
                <h3 class="mb-4">Riwayat Transaksi</h3>

                @forelse ($transactions as $trx)
                    @php
                        $totalItems = $trx->transactionDetails->count();
                    @endphp

                    <div class="card mb-3">
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <h6 class="mb-1">{{ $trx->invoice }}</h6>
                            <span
                                class="badge 
                            @if ($trx->transaction_status == 'Paid') bg-primary
                            @elseif($trx->transaction_status == 'Pending') bg-warning
                            @elseif($trx->transaction_status == 'Cancelled') bg-danger
                            @elseif($trx->transaction_status == 'Delivered') bg-info
                            @elseif($trx->transaction_status == 'Completed') bg-success
                            @else bg-secondary @endif">
                                {{ $trx->transaction_status }}
                            </span>

                            <hr>

                            {{-- Loop semua produk --}}
                            @foreach ($trx->transactionDetails as $key => $item)
                                <div class="trx-product mb-2 @if ($key > 0) d-none @endif">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <img src="{{ $item->product->image }}" width="100px" alt="">
                                            <strong>{{ $item->product->name }}</strong><br>
                                            <small>{{ $item->qty }} x Rp {{ number_format($item->price) }}</small>
                                        </div>
                                        <div>
                                            Rp {{ number_format($item->total) }}
                                        </div>
                                    </div>

                                    {{-- 🔹 Tombol Rate muncul jika transaction Completed & belum dinilai --}}
                                    @if ($trx->transaction_status == 'Completed')
                                        @if ($item->status != 'rated' && is_null($item->rating))
                                            <div class="mt-2">
                                                <a href="{{ route('customer.transaction.rate.form', [$trx->id, $item->product->id]) }}"
                                                    class="btn btn-sm btn-warning">
                                                    Rate Produk
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <span class="fw-bold">Rating:</span>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $item->rating)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-secondary"></i>
                                                    @endif
                                                @endfor
                                                @if ($item->review)
                                                    <div class="mt-1"><em>"{{ $item->review }}"</em></div>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach

                            {{-- Tombol lihat semua produk --}}
                            @if ($totalItems > 1)
                                <button class="btn btn-link p-0" type="button" onclick="showAllProducts(this)">
                                    Lihat semua produk ({{ $totalItems }})
                                </button>
                            @endif

                            <hr>

                            {{-- Action buttons --}}
                            <div class="mb-2">
                                @if ($trx->transaction_status == 'Delivered')
                                    <form action="{{ route('customer.transaction.complete', $trx->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm">Terima Paket</button>
                                    </form>
                                @endif

                                @if (in_array($trx->transaction_status, ['Packing', 'Waiting Payment']))
                                    <form action="{{ route('customer.transaction.cancel', $trx->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                @endif

                                @if ($trx->transaction_status == 'Waiting Payment')
                                    <a href="{{ route('customer.transaction.payment', $trx->id) }}"
                                        class="btn btn-success btn-sm">Bayar</a>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Total: Rp {{ number_format($trx->total) }}</strong>
                                <a href="{{ route('customer.transaction.show', $trx->id) }}"
                                    class="btn btn-sm btn-dark">Detail</a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Belum ada transaksi</div>
                @endforelse

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function showAllProducts(button) {
            const cardBody = button.closest('.card-body');
            const hiddenProducts = cardBody.querySelectorAll('.trx-product.d-none');

            hiddenProducts.forEach(el => el.classList.remove('d-none'));
            button.style.display = 'none'; // hide tombol setelah diklik
        }
    </script>
@endpush
