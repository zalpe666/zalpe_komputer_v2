@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 h-100 bg-primary opacity-75">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Commodi, saepe
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                eius dignissimos aperiam necessitatibus quaerat nisi debitis fugit eligendi nemo odit magnam ratione nobis
                repudiandae odio! Consectetur molestiae repudiandae laudantium!</div>
            <div class="col-md-8">
                <h3 class="mb-4">Riwayat Transaksi</h3>

                @foreach ($transactions as $trx)
                    @php
                        $firstItem = $trx->transactionDetails->first();
                        $totalItems = $trx->transactionDetails->count();
                    @endphp

                    <div class="card mb-3">
                        <div class="card-body">

                            <h6 class="mb-1">{{ $trx->invoice }}</h6>

                            <span
                                class="badge 
                @if ($trx->transaction_status == 'Paid') bg-success
                @elseif($trx->transaction_status == 'Pending') bg-warning
                @elseif($trx->transaction_status == 'Cancelled') bg-danger
                @else bg-secondary @endif">
                                {{ $trx->transaction_status }}
                            </span>

                            <hr>

                            {{-- ✅ TAMPILKAN 1 PRODUK --}}
                            @if ($firstItem)
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <img src="{{ $firstItem->product->image }}" width="100px" alt="">
                                        <strong>{{ $firstItem->product->name }}</strong><br>
                                        <small>{{ $firstItem->qty }} x Rp {{ number_format($firstItem->price) }}</small>

                                        {{-- ✅ JIKA LEBIH DARI 1 --}}
                                        @if ($totalItems > 1)
                                            <div class="text-muted">
                                                +{{ $totalItems - 1 }} produk lainnya
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        Rp {{ number_format($firstItem->total) }}
                                    </div>
                                </div>
                            @endif
                            @if ($trx->transaction_status == 'Pending')
                                <a href="{{ route('customer.transaction.payment', $trx->id) }}"
                                    class="btn btn-success btn-sm">
                                    Bayar
                                </a>
                            @endif
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Total: Rp {{ number_format($trx->total) }}</strong>

                                <a href="{{ route('customer.transaction.show', $trx->id) }}" class="btn btn-sm btn-dark">
                                    Detail
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach

                @if ($transactions->isEmpty())
                    <div class="alert alert-info">Belum ada transaksi</div>
                @endif
            </div>
        </div>
    </div>
@endsection
