@extends('layouts.app')

@section('title', 'Admin Panel')
@section('content')
    <div class="container mt-4">
    <div class="row">

        {{-- 🛒 CART LIST --}}
        <div class="col-md-8">
            <h4 class="mb-3">Shopping Cart</h4>

            @forelse($carts as $cart)
            <div class="card mb-3">
                <div class="card-body d-flex align-items-center">

                    <img src="{{ $cart->product->image}}"
                        width="80" class="me-3">

                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $cart->product->name }}</h6>

                        <small class="text-muted">
                            Rp {{ number_format($cart->product->final_price) }}
                        </small>

                        {{-- QTY --}}
                        <div class="d-flex align-items-center mt-2">

                            {{-- MINUS --}}
                            <form method="POST" action="{{ route('customer.cart.update', $cart->id) }}">
                                @csrf
                                <input type="hidden" name="action" value="minus">
                                <button class="btn btn-sm btn-outline-secondary">-</button>
                            </form>

                            <span class="mx-3">{{ $cart->pcs }}</span>

                            {{-- PLUS --}}
                            <form method="POST" action="{{ route('customer.cart.update', $cart->id) }}">
                                @csrf
                                <input type="hidden" name="action" value="plus">
                                <button class="btn btn-sm btn-outline-success">+</button>
                            </form>

                        </div>
                    </div>

                    {{-- SUBTOTAL ITEM --}}
                    <div class="text-end">
                        <div class="fw-bold text-success">
                            Rp {{ number_format($cart->product->final_price * $cart->pcs) }}
                        </div>

                        {{-- DELETE --}}
                        <form method="POST" action="{{ route('customer.cart.remove', $cart->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger mt-2">
                                Remove
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @empty
                <div class="alert alert-info">
                    Cart masih kosong 🛒
                </div>
            @endforelse

        </div>

        {{-- 💰 SUMMARY --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">

                    <h5 class="mb-3">Summary</h5>

                    @php
                        $subtotal = 0;
                        foreach ($carts as $cart) {
                            $subtotal += $cart->product->final_price * $cart->pcs;
                        }
                    @endphp

                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <strong>Rp {{ number_format($subtotal) }}</strong>
                    </div>

                    <hr>

                    <button class="btn btn-success w-100">
                        Checkout
                    </button>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
