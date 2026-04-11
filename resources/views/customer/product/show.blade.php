@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <div class="container py-4">

        <div class="row g-4">

            {{-- IMAGE --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <img src="{{ $product->image }}" class="w-100" style="object-fit: cover;"
                        onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
                </div>
            </div>

            {{-- INFO --}}
            <div class="col-md-7">

                {{-- NAME --}}
                <h3 class="fw-bold
                {{ $product->brand_id == 30 ? 'text-danger' : '' }}">
                    {{ $product->name }}
                </h3>

                {{-- BRAND --}}
                <span
                    style="
                font-size: 11px;
                background: {{ $product->brand_id == 30 ? '#ff0000' : '#333' }};
                color: white;
                padding: 3px 8px;
                border-radius: 5px;
                display: inline-block;
            ">
                    {{ strtoupper($product->brand->name ?? '-') }}
                </span>

                {{-- RATING --}}
                <div class="mt-2 text-muted">
                    <i class="bi bi-star-fill text-warning"></i>
                    {{ $product->rating ?? '-' }}
                    • {{ number_format($product->sold ?? 0) }} sold
                    • {{ number_format($product->view ?? 0) }} views
                </div>

                {{-- PRICE --}}
                <div class="mt-3">

                    @if ($product->discount > 0)
                        <div>
                            <small class="text-muted text-decoration-line-through">
                                Rp {{ number_format($product->default_price) }}
                            </small>
                        </div>
                    @endif

                    <h4
                        class="fw-bold
                    @if ($product->brand_id == 30) text-danger
                    @elseif($product->type !== 'Product')
                        text-primary
                    @else
                        text-success @endif
                ">
                        Rp {{ number_format($product->final_price) }}
                    </h4>

                    @if ($product->discount > 0)
                        <span class="badge bg-danger">
                            Save {{ $product->discount }}%
                        </span>
                    @endif
                </div>

                {{-- STOCK --}}
                <div class="mt-3">
                    @if ($product->is_out_of_stock)
                        <span class="badge bg-danger">Out of Stock</span>
                    @else
                        <span class="badge bg-success">In Stock</span>
                    @endif
                </div>

                {{-- DESCRIPTION --}}
                <div class="mt-4">
                    <h5 class="fw-bold">Description</h5>
                    <p class="text-muted">
                        {!! $product->description ?? 'No description available.' !!}
                    </p>
                </div>

                {{-- ACTION --}}
                <div class="mt-4">
                    @auth
                        @if ($product->type !== 'Product')
                            <a href="{{ route('customer.checkout.now.index', $product->id) }}"
                                class="btn btn-primary w-100
                           {{ $product->is_out_of_stock ? 'disabled' : '' }}">
                                Buy Now
                            </a>
                        @else
                            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-success w-100" {{ $product->is_out_of_stock ? 'disabled' : '' }}>
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

@endsection
