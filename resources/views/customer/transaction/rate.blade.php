@extends('layouts.app')

@section('title', 'Rate Product')

@section('content')
<div class="container">
    <h3>Rating Produk: {{ $product->name }}</h3>

    <form action="{{ route('customer.transaction.rate.submit', [$transaction->id, $product->id]) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">-- Pilih --</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="review" class="form-label">Ulasan (opsional)</label>
            <textarea name="review" id="review" class="form-control" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Kirim Rating</button>
        <a href="{{ route('customer.transaction.show', $transaction->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection