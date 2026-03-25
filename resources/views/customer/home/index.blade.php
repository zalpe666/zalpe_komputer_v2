@extends('layouts.app')

@section('title', 'Admin Panel')
@section('content')
    <div class="container">
        <h1>Ini Adalah User</h1>
        <div class="container mt-4">
            <div class="row">

                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">

                            <img src="{{$product->image}}" class="card-img-top">

                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold">{{ $product->name }}</h6>

                                <small class="text-muted">
                                    {{ $product->brand->name ?? '-' }}
                                </small>

                                <div class="mt-2 mb-3">
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($product->final_price) }}
                                    </span>

                                    @if ($product->discount > 0)
                                        <small class="text-decoration-line-through text-muted">
                                            Rp {{ number_format($product->price) }}
                                        </small>
                                    @endif
                                </div>

                                {{-- STOCK --}}
                                @if ($product->is_out_of_stock)
                                    <span class="badge bg-danger mb-2">Out of Stock</span>
                                @endif

                                {{-- BUTTON --}}
                                @auth
                                    <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <button class="btn btn-success w-100" {{ $product->is_out_of_stock ? 'disabled' : '' }}>
                                            Add to Cart
                                        </button>
                                    </form>
                                @endauth

                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary mt-auto">
                                        Login to Buy
                                    </a>
                                @endguest

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
