@extends('layouts.dashboard')


@section('content')
    <div class="container">
        <h3>Detail Produk</h3>

        <p>Nama: {{ $product->name }}</p>
        <p>Harga: {{ $product->price }}</p>
        <p>Deskripsi: {{ $product->description }}</p>

        <a href="{{ route('admin.product.index') }}">
            Kembali
        </a>
    </div>
@endsection
