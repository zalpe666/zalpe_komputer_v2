@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detail Transaksi</h3>

    <div class="card mb-3">
        <div class="card-body">
            <p><b>Invoice:</b> {{ $transaction->invoice }}</p>
            <p><b>Status:</b> {{ $transaction->status }}</p>
            <p><b>Kurir:</b> {{ $transaction->courier_name }} - {{ $transaction->courier_service }}</p>
            <p><b>Estimasi:</b> {{ $transaction->estimated_delivery }}</p>
        </div>
    </div>

    <h5>Produk</h5>

    @foreach ($transaction->transactionDetails as $item)
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <h6>{{ $item->product->name }}</h6>
                    <small>{{ $item->qty }} x Rp {{ number_format($item->price) }}</small>
                </div>
                <div>
                    Rp {{ number_format($item->total) }}
                </div>
            </div>
        </div>
    @endforeach

    <hr>

    <h5>Ringkasan</h5>
    <p>Subtotal: Rp {{ number_format($transaction->subtotal) }}</p>
    <p>Ongkir: Rp {{ number_format($transaction->shipping_cost) }}</p>
    <h5>Total: Rp {{ number_format($transaction->total) }}</h5>

    <a href="{{ route('customer.transaction.index') }}" class="btn btn-secondary mt-3">
        Kembali
    </a>
</div>
@endsection