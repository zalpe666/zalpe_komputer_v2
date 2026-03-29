@extends('layouts.dashboard')

@section('content')
    <h3>Transaction Detail: {{ $transaction->invoice }}</h3>
    <p>User: {{ $transaction->user->name }}</p>
    <p>Address: {{ $transaction->address->address }}, {{ $transaction->address->city->name ?? '' }}</p>
    <p>Total: Rp {{ number_format($transaction->total) }}</p>
    <p>Status: {{ $transaction->transaction_status }}</p>

    <form action="{{ route('admin.transaction.updateStatus', $transaction->id) }}" method="POST" class="d-flex gap-2">
        @csrf
        @method('PUT')

        @if ($transaction->transaction_status == 'Pending')
            <button name="status" value="packing" class="btn btn-warning">
                Mark as Packing
            </button>
        @endif

        @if ($transaction->transaction_status == 'Paid')
            <button name="status" value="Packing" class="btn btn-primary">
                Mark as Packing
            </button>
        @endif
        @if ($transaction->transaction_status == 'Packing')
            <button name="status" value="Sending" class="btn btn-primary">
                Mark as Sending
            </button>
        @endif
        @if ($transaction->transaction_status == 'Sending')
            <button name="status" value="Delivered" class="btn btn-primary">
                Mark as Delivered
            </button>
        @endif
    </form>
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
            @foreach ($transaction->transactionDetails as $d)
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
