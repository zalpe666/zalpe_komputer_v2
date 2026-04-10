@extends('layouts.app')

@section('title', 'Admin Panel')
@section('content')
    <div class="container">
        <h1>Ini Adalah User</h1>
        <div class="container mt-4">
            <div class="row">

                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 position-relative">

                            {{-- BADGE DISKON --}}
                            @if ($product->discount > 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    -{{ $product->discount }}%
                                </span>
                            @endif

                            {{-- IMAGE --}}
                            <img src="{{ $product->image }}" class="card-img-top"; object-fit:cover;"
                                onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">

                            <div class="card-body d-flex flex-column">

                                {{-- NAME --}}
                                <h6 class="fw-bold text-truncate">{{ $product->name }}</h6>

                                {{-- BRAND --}}
                                <small class="text-muted">
                                    {{ $product->brand->name ?? '-' }}
                                </small>

                                {{-- RATING --}}
                                <small class="text-muted">
                                    {{ $product->rating ?? '-' }} <i class="bi bi-star-fill text-warning"></i>
                                </small>

                                {{-- PRICE --}}
                                <div class="mt-2 mb-3">
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($product->final_price) }}
                                    </span>

                                    @if ($product->discount > 0)
                                        <small class="text-decoration-line-through text-muted ms-1">
                                            Rp {{ number_format($product->default_price) }}
                                        </small>
                                    @endif
                                </div>

                                {{-- STOCK --}}
                                @if ($product->is_out_of_stock)
                                    <span class="badge bg-danger mb-2">Out of Stock</span>
                                @endif

                                {{-- BUTTON --}}
                                <div class="mt-auto">
                                    @auth
                                        @if ($product->type !== 'Product')
                                            <a href="{{ route('customer.checkout.now.index', $product->id) }}"
                                                class="btn btn-primary w-100 {{ $product->is_out_of_stock ? 'disabled' : '' }}">
                                                Buy Now
                                            </a>
                                        @else
                                            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-success w-100"
                                                    {{ $product->is_out_of_stock ? 'disabled' : '' }}>
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @endif
                                    @endauth

                                    @guest
                                        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
                                            Login to Buy
                                        </a>
                                    @endguest
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
