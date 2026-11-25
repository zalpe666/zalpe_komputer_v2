@extends('layouts.app')

@section('title', 'Checkout Now')

@section('content')
    <div class="container py-4">

        <h3 class="mb-4">Checkout Now</h3>

        <div class="card shadow-sm">
            <div class="row g-0">

                <!-- IMAGE -->
                <div class="col-md-4">
                    <img src="{{ $product->image }}" class="img-fluid rounded-start" alt="{{ $product->name }}">
                </div>

                <!-- DETAIL -->
                <div class="col-md-8">
                    <div class="card-body d-flex flex-column h-100">

                        <h5 class="card-title">{{ $product->name }}</h5>

                        <p class="text-muted mb-2">
                            Type: {{ $product->type }}
                        </p>

                        <h4 class="text-success mb-3">
                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                        </h4>

                        @if ($product->is_out_of_stock)
                            <span class="badge bg-danger mb-3">Out of Stock</span>
                        @endif

                        <p class="card-text">
                            {{ $product->description ?? 'No description available' }}
                        </p>
                        <form action="{{ route('customer.checkout.now.process') }}" method="POST" class="mt-auto">
                            @csrf

                            <input type="hidden" name="product_id" value="{{ $product->id }}">


                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="mb-3">Payment Method</h5>

                                    {{-- BCA --}}
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="bank" id="bankBca"
                                            value="bca" data-payment="midtrans" required>
                                        <label class="form-check-label" for="bankBca">
                                            Bank Transfer (BCA)
                                        </label>
                                    </div>

                                    {{-- MANDIRI --}}
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="bank" id="bankMandiri"
                                            value="mandiri" data-payment="midtrans">
                                        <label class="form-check-label" for="bankMandiri">
                                            Bank Transfer (Mandiri)
                                        </label>
                                    </div>

                                    {{-- BNI --}}
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="bank" id="bankBni"
                                            value="bni" data-payment="midtrans">
                                        <label class="form-check-label" for="bankBni">
                                            Bank Transfer (BNI)
                                        </label>
                                    </div>

                                    <hr>

                                    {{-- WALLET --}}
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="bank" id="paymentWallet"
                                            value="wallet" data-payment="wallet" {{ !$walletAvailable ? 'disabled' : '' }}>

                                        <label class="form-check-label {{ !$walletAvailable ? 'text-muted' : '' }}"
                                            for="paymentWallet"
                                            style="{{ !$walletAvailable ? 'cursor: not-allowed; pointer-events: none;' : '' }}">

                                            Wallet (Saldo:
                                            Rp{{ number_format($wallet->balance ?? 0, 0, ',', '.') }})

                                            @if (!$wallet)
                                                <span class="text-danger small">(Wallet belum tersedia)</span>
                                            @elseif (!$walletAvailable)
                                                <span class="text-danger small">(Saldo tidak mencukupi)</span>
                                            @endif

                                        </label>
                                    </div>
                                    {{-- Hidden payment method --}}
                                    <input type="hidden" name="payment_method" id="paymentMethod" value="">

                                </div>
                            </div>


                            <button class="btn btn-primary w-100 mt-3" {{ $product->is_out_of_stock ? 'disabled' : '' }}>
                                Proceed to Payment
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
