@extends('layouts.dashboard')

@section('content')
   <table class="table">
    <thead>
        <tr>
            <th>Invoice</th>
            <th>User</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $t)
        <tr>
            <td>{{ $t->invoice }}</td>
            <td>{{ $t->user->name }}</td>
            <td>Rp {{ number_format($t->total) }}</td>
            <td>{{ $t->transaction_status }}</td>
            <td>
                <a href="{{ route('admin.transaction.show', $t->id) }}" class="btn btn-sm btn-primary">Detail</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $transactions->links('pagination::bootstrap-5') }} {{-- pagination --}}
@endsection