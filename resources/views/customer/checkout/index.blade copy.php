@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container mt-4">
    <form action="{{ route('customer.checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <input type="hidden" name="carts" value="{{ request()->carts }}">

        <div class="row">
            {{-- 🛒 LIST PRODUK & OPTIONS --}}
            <div class="col-md-8">
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <h4 class="mb-3">Checkout</h4>

                @foreach ($carts as $cart)
                    <div class="card mb-3">
                        <div class="card-body d-flex align-items-center">
                            <img src="{{ $cart->product->image }}" width="80" class="me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $cart->product->name }}</h6>
                                <small class="text-muted">Rp {{ number_format($cart->product->final_price) }}</small>
                                <div class="mt-2">Qty: <strong>{{ $cart->pcs }}</strong></div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">
                                    Rp {{ number_format($cart->product->final_price * $cart->pcs) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- 📍 PILIH ALAMAT --}}
                <h5 class="mt-4">Shipping Address</h5>
                @foreach ($addresses as $address)
                    <div class="border p-3 mb-2 rounded cursor-pointer">
                        <label class="w-100 mb-0">
                            <input type="radio" name="address_id" value="{{ $address->id }}" data-district="{{ $address->district_id }}" required>
                            <strong>{{ $address->name }}</strong><br>
                            {{ $address->address }}
                        </label>
                    </div>
                @endforeach

                {{-- 🚚 PILIH KURIR (Digenarate JS) --}}
                <h5 class="mt-4">Courier</h5>
                <div id="courier-list" class="mb-4">
                    <div class="text-muted">Pilih alamat terlebih dahulu...</div>
                </div>

                {{-- 💳 PAYMENT METHOD --}}
                <h5 class="mt-4">Payment Method</h5>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="payment_method" value="midtrans" id="pay_bank" required>
                    <label class="form-check-label" for="pay_bank">Bank Transfer / Virtual Account</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="payment_method" value="wallet" id="pay_wallet" required>
                    <label class="form-check-label" for="pay_wallet">E-Wallet</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="payment_method" value="cod" id="pay_cod" required>
                    <label class="form-check-label" for="pay_cod">Cash on Delivery (COD)</label>
                </div>

            </div>

            {{-- 💰 SUMMARY --}}
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="mb-3">Summary</h5>

                        @php
                            $subtotal = $carts->sum(fn($c) => $c->product->default_price * $c->pcs);
                            $discount_by_merchant = $carts->sum(fn($c) => ($c->product->default_price - $c->product->final_price) * $c->pcs);
                            $subtotal_final = $subtotal - $discount_by_merchant;
                        @endphp

                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>Rp {{ number_format($subtotal) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between text-success">
                            <span>Discount</span>
                            <strong>- Rp {{ number_format($discount_by_merchant) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Ongkir</span>
                            <strong id="ongkir">Rp 0</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5">
                            <span>Total</span>
                            <strong id="total">Rp {{ number_format($subtotal_final) }}</strong>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-4 fs-5">
                            Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // 📌 Variabel dasar untuk render UI text
        let subtotalFinal = {{ $subtotal_final }};
        let totalWeight = {{ $totalWeight ?? 1000 }};
        
        const addressRadios = document.querySelectorAll('input[name="address_id"]');
        const courierList = document.getElementById('courier-list');
        const ongkirText = document.getElementById('ongkir');
        const totalText = document.getElementById('total');
        const checkoutForm = document.getElementById('checkout-form');

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        // 📌 1. Saat alamat berubah, ambil kurir 
        addressRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                let districtId = this.dataset.district;
                courierList.innerHTML = `<span class="text-muted">Loading kurir...</span>`;
                ongkirText.innerText = 'Rp 0';
                totalText.innerText = formatRupiah(subtotalFinal); // Reset total

                fetch(`/home/couriers/${districtId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.length) {
                            courierList.innerHTML = `<div class="text-danger">Tidak ada layanan kurir ke area ini.</div>`;
                            return;
                        }

                        let html = '';
                        data.forEach(courier => {
                            // Trik: Simpan data kurir sebagai JSON string di VALUE input
                            // Agar PHP tinggal json_decode() pas form di submit
                            let valueJson = JSON.stringify({
                                name: courier.name,
                                service: courier.service,
                                price_per_kg: courier.price_per_kg,
                                estimated_delivery_time: courier.estimated_delivery_time
                            });

                            html += `
                                <div class="border p-2 mb-2 rounded cursor-pointer label-radio">
                                    <label class="w-100 mb-0">
                                        <input type="radio" name="courier" value='${valueJson}' data-price="${courier.price_per_kg}" required>
                                        <strong>${courier.name} (${courier.service})</strong><br>
                                        ${formatRupiah(courier.price_per_kg)} / kg <br>
                                        <small class="text-muted">Estimasi: ${courier.estimated_delivery_time}</small>
                                    </label>
                                </div>
                            `;
                        });
                        courierList.innerHTML = html;
                    })
                    .catch(err => {
                        courierList.innerHTML = `<div class="text-danger">Gagal mengambil data kurir.</div>`;
                    });
            });
        });

        // 📌 2. Saat kurir dipilih, ubah teks Ongkir & Total di sebelah kanan
        document.addEventListener('change', function(e) {
            if (e.target.name === 'courier') {
                let pricePerKg = parseInt(e.target.dataset.price);
                let ongkir = Math.ceil(totalWeight / 1000) * pricePerKg;
                
                ongkirText.innerText = formatRupiah(ongkir);
                totalText.innerText = formatRupiah(subtotalFinal + ongkir);
            }
        });

        // 📌 3. Validasi COD ringan di JS biar user ngga perlu nunggu load page kalau salah
        checkoutForm.addEventListener('submit', function(e) {
            let paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
            if (paymentMethod === 'cod' && subtotalFinal > 1000000) {
                e.preventDefault(); // Cegah form dikirim
                alert("Metode pembayaran COD hanya berlaku untuk subtotal di bawah Rp 1.000.000");
            }
        });

    });
</script>
@endpush