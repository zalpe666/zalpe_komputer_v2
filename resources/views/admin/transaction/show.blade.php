@extends('layouts.dashboard')

@section('content')
   <h3>Transaction Detail: {{ $transaction->invoice }}</h3>
<p>User: {{ $transaction->user->name }}</p>
<p>Address: {{ $transaction->address->address }}, {{ $transaction->address->city->name ?? '' }}</p>
<p>Total: Rp {{ number_format($transaction->total) }}</p>
<p>Status: {{ $transaction->status }}</p>

<h4>Products</h4>
<table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaction->details as $d)
        <tr>
            <td>{{ $d->product->name }}</td>
            <td>{{ $d->qty }}</td>
            <td>Rp {{ number_format($d->price) }}</td>
            <td>Rp {{ number_format($d->total) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection