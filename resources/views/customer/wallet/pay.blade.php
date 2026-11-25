@extends('layouts.app')

@section('title', 'Wallet Payment')

@section('content')
    <div class="container py-4">

        <h3 class="mb-4">Wallet Payment</h3>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="row align-items-center g-4">

                    {{-- TOTAL PAYMENT --}}
                    <div class="col-md-6">

                        <h6 class="text-muted mb-2">
                            Total Payment
                        </h6>

                        <h2 class="text-success fw-bold">
                            Rp{{ number_format($transaction->total, 0, ',', '.') }}
                        </h2>

                        <p class="text-muted mb-2">
                            Invoice: {{ $transaction->invoice }}
                        </p>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Wallet Balance</span>
                            <strong>
                                Rp{{ number_format($wallet->balance, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Remaining Balance</span>
                            <strong class="text-success">
                                Rp{{ number_format($wallet->balance - $transaction->total, 0, ',', '.') }}
                            </strong>
                        </div>

                    </div>

                    {{-- WALLET PIN --}}
                    <div class="col-md-6">

                        <form action="{{ route('customer.wallet.pay.process', $transaction->id) }}" method="POST">

                            @csrf

                            <h5 class="mb-2">Enter Wallet PIN</h5>

                            <p class="text-muted small">
                                Masukkan 6 digit PIN Wallet untuk
                                menyelesaikan pembayaran.
                            </p>

                            <div class="d-flex gap-2 mb-3">

                                @for ($i = 0; $i < 6; $i++)
                                    <input type="password" name="wallet_pin[]" class="form-control text-center wallet-pin"
                                        maxlength="1" inputmode="numeric" autocomplete="off" required
                                        style="width: 45px; height: 50px; font-size: 20px;">
                                @endfor

                            </div>

                            @error('wallet_pin')
                                <div class="text-danger small mb-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            @error('wallet_pin.*')
                                <div class="text-danger small mb-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            <button type="submit" class="btn btn-primary w-100">
                                Pay Now
                            </button>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const inputs = document.querySelectorAll('.wallet-pin');

            inputs.forEach(function(input, index) {

                input.addEventListener('input', function() {

                    this.value = this.value.replace(/\D/g, '').slice(0, 1);

                    if (this.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                });

                input.addEventListener('keydown', function(event) {

                    if (event.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                    }

                });

                input.addEventListener('paste', function(event) {

                    event.preventDefault();

                    const pasted = (event.clipboardData || window.clipboardData)
                        .getData('text')
                        .replace(/\D/g, '')
                        .slice(0, 6);

                    pasted.split('').forEach((digit, i) => {
                        if (inputs[i]) {
                            inputs[i].value = digit;
                        }
                    });

                    inputs[Math.min(pasted.length, 5)].focus();

                });

            });

        });
    </script>
@endsection
