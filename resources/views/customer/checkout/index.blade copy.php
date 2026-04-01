@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container mt-4">
        <div class="row">

            {{-- 🛒 LIST PRODUK --}}
            <div class="col-md-8">
                <h4 class="mb-3">Checkout</h4>

                @foreach ($carts as $cart)
                    <div class="card mb-3">
                        <div class="card-body d-flex align-items-center">

                            <img src="{{ $cart->product->image }}" width="80" class="me-3">

                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $cart->product->name }}</h6>

                                <small class="text-muted">
                                    Rp {{ number_format($cart->product->final_price) }}
                                </small>

                                <div class="mt-2">
                                    Qty: <strong>{{ $cart->pcs }}</strong>
                                </div>
                            </div>

                            {{-- SUBTOTAL --}}
                            <div class="text-end">
                                <div class="fw-bold text-success">
                                    Rp {{ number_format($cart->product->final_price * $cart->pcs) }}
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

                {{-- 📍 PILIH ALAMAT --}}
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Shipping Address</h5>

                        @forelse($addresses as $address)
                            <div class="border p-3 mb-2 rounded">
                                <input type="radio" name="address_id" value="{{ $address->id }}"
                                    data-district="{{ $address->district_id }}">

                                <strong>{{ $address->name }}</strong> ({{ $address->phone }}) <br>
                                {{ $address->address }} <br>
                                {{ $address->city->name ?? '' }}, {{ $address->province->name ?? '' }}
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                Belum ada alamat
                            </div>
                        @endforelse
                        <div id="courier-list">
                            <small class="text-muted">Pilih alamat dulu</small>
                        </div>
                    </div>
                </div>
                {{-- 📍 PILIH PAYMENT METHOD --}}
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Payment Method</h5>

                        {{-- Midtrans Bank Transfer --}}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer"
                                value="midtrans">
                            <label class="form-check-label" for="payment_transfer">
                                Bank Transfer (BCA, Mandiri, BNI)
                            </label>
                        </div>

                        {{-- Midtrans QRIS --}}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_qris"
                                value="qris">
                            <label class="form-check-label" for="payment_qris">
                                QRIS
                            </label>
                        </div>

                        {{-- Midtrans E-Wallet --}}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_ewallet"
                                value="ewallet">
                            <label class="form-check-label" for="payment_ewallet">
                                E-Wallet
                            </label>
                        </div>

                        {{-- Wallet --}}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_wallet"
                                value="wallet">
                            <label class="form-check-label" for="payment_wallet">
                                Wallet (Saldo: Rp {{ number_format(auth()->user()->wallet->balance ?? 0, 0, ',', '.') }})
                            </label>
                        </div>

                        {{-- COD --}}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cod"
                                value="cod">
                            <label class="form-check-label" for="payment_cod">
                                COD (Cash on Delivery, max Rp 1.000.000)
                            </label>
                        </div>
                    </div>
                </div>

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

                        <div class="d-flex justify-content-between mt-2">
                            <span>Ongkir</span>
                            <strong id="ongkir">Rp 0</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <strong id="total">Rp {{ number_format($subtotal) }}</strong>
                        </div>

                        <button id="pay-btn" class="btn btn-success w-100 mt-3">
                            Bayar Sekarang
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('script')
    <script>
        let selectedOngkir = 0;
        document.addEventListener("DOMContentLoaded", function() {

            const addressRadios = document.querySelectorAll('input[name="address_id"]');
            const courierList = document.getElementById('courier-list');
            const ongkirText = document.getElementById('ongkir');
            const totalText = document.getElementById('total');

            let subtotal = {{ $carts->sum(fn($c) => $c->product->final_price * $c->pcs) }};
            let totalWeight = {{ $carts->sum(fn($c) => ($c->product->weight ?? 1000 ?: 1000) * $c->pcs) }};

            function formatRupiah(number) {
                return 'Rp ' + number.toLocaleString('id-ID');
            }

            function updateTotal() {
                totalText.innerText = formatRupiah(subtotal + selectedOngkir);
            }

            // 🔥 PILIH ALAMAT → LOAD COURIER
            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {

                    let districtId = this.dataset.district;

                    fetch(`/home/couriers/${districtId}`)
                        .then(res => res.json())
                        .then(data => {

                            if (data.length === 0) {
                                courierList.innerHTML =
                                    `<div class="text-danger">Tidak ada kurir</div>`;
                                return;
                            }

                            let html = '';

                            data.forEach(courier => {
                                html += `
                                    <div class="border p-2 mb-2 rounded">
                                        <input type="radio" name="courier"
                                            data-price="${courier.price_per_kg}"
                                            data-name="${courier.name}" 
                                            data-service="${courier.service}" 
                                            data-estimate="${courier.estimated_delivery_time}">
                                        <strong>${courier.name} (${courier.service})</strong><br>
                                        ${formatRupiah(courier.price_per_kg)} / kg <br>
                                        Estimasi: ${courier.estimated_delivery_time}
                                    </div>
                                `;
                            });

                            courierList.innerHTML = html;

                        })
                        .catch(err => {
                            console.error(err);
                            courierList.innerHTML =
                                `<div class="text-danger">Error ambil ongkir</div>`;
                        });

                });
            });

            // 🔥 FIX UTAMA: EVENT DELEGATION (INI YANG BIKIN DULU GAGAL)
            courierList.addEventListener('change', function(e) {

                console.log("EVENT KE TRIGGER"); // 👈 cek ini

                if (e.target.name === 'courier') {

                    console.log("PRICE:", e.target.dataset.price); // 👈 cek ini

                    let price = parseInt(e.target.dataset.price);

                    let ongkir = Math.ceil(totalWeight / 1000) * price;
                    selectedOngkir = ongkir;
                    console.log("ONGKIR:", ongkir); // 👈 cek ini

                    ongkirText.innerText = formatRupiah(ongkir);

                    updateTotal();
                }
            });

        });

        const payBtn = document.getElementById('pay-btn');

        payBtn.addEventListener('click', function() {


            // 🔥 ambil address
            let address = document.querySelector('input[name="address_id"]:checked');
            if (!address) {
                alert("Pilih alamat dulu");
                return;
            }

            // 🔥 ambil courier
            let courier = document.querySelector('input[name="courier"]:checked');
            if (!courier) {
                alert("Pilih courier dulu");
                return;
            }

            // 🔥 ambil payment method
            let payment_method = document.querySelector('input[name="payment_method"]:checked')?.value;


            if (!payment_method) {
                alert("Pilih metode pembayaran dulu");
                return;
            }
            let subtotal = {{ $carts->sum(fn($c) => $c->product->final_price * $c->pcs) }};

            if (payment_method === 'cod' && subtotal > 1000000) {
                alert("COD hanya bisa untuk subtotal maksimal Rp 1.000.000");
                return;
            }

            // 🔥 ambil data
            let address_id = address.value;
            let shipping_cost = selectedOngkir;

            let courier_name = courier.dataset.name; // "JNE"
            let courier_service = courier.dataset.service; // "Reguler"
            let estimated_delivery = courier.dataset.estimate; // "1-3 Days"

            // 🔥 ambil cart dari URL
            let params = new URLSearchParams(window.location.search);
            let carts = params.get('carts'); // contoh: "3,4"

            fetch("{{ route('customer.checkout.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        address_id,
                        carts,
                        shipping_cost,
                        courier_name,
                        courier_service,
                        estimated_delivery,
                        payment_method
                    })
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res);

                    if (res.success) {
                        window.location.href = res.redirect;
                    }
                })
                .catch(err => console.log(err));

        });
    </script>
@endpush
