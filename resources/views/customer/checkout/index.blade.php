@extends('layouts.app')
@section('title', 'Selesaikan Pembayaran - Zalpe Komputer')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .checkout-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Custom Radio Card */
        .selectable-card {
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #e9ecef;
            position: relative;
        }

        .selectable-card:hover {
            border-color: #198754;
            background-color: #f0fdf4;
        }

        .form-check-input-custom {
            position: absolute;
            opacity: 0;
        }

        .form-check-input-custom:checked+.selectable-card {
            border-color: #198754;
            background-color: #f0fdf4;
        }

        .form-check-input-custom:checked+.selectable-card .bi-check-circle-fill {
            display: block !important;
        }

        .product-img-mini {
            width: 70px;
            height: 70px;
            object-fit: contain;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
    <style>
        /* Custom Styling untuk Logo dan Kartu Bank */
        .bank-card {
            border-width: 2px;
            transition: all 0.2s ease-in-out;
        }

        .bank-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Ukuran logo agar seragam */
        .bank-logo {
            height: 30px;
            object-fit: contain;
        }

        /* Efek ketika radio button dipilih */
        .btn-check:checked+.bank-card {
            border-color: #000;
            background-color: #f0fdf4;
        }
    </style>

    <div class="container py-5">
        <form action="{{ route('customer.checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="carts" value="{{ request()->carts }}">

            <div class="row g-4">
                {{-- 🛒 SISI KIRI: FORM CHECKOUT --}}
                <div class="col-lg-8">
                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
                    @endif

                    <h3 class="fw-bold mb-4">Checkout</h3>

                    {{-- 📍 SECTION 1: ALAMAT PENGIRIMAN --}}
                    <div class="card checkout-card mb-4">
                        <div class="card-body p-4">
                            <div class="section-title text-success">
                                <i class="bi bi-geo-alt-fill"></i> Alamat Pengiriman
                            </div>

                            <div class="row g-3">
                                @foreach ($addresses as $address)
                                    <div class="col-12">
                                        <input type="radio" name="address_id" value="{{ $address->id }}"
                                            id="address-{{ $address->id }}" class="form-check-input-custom"
                                            data-district="{{ $address->district_id }}" required>
                                        <label for="address-{{ $address->id }}"
                                            class="selectable-card w-100 p-3 rounded-4 d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="badge bg-success mb-2">{{ $address->name }}</span>
                                                <p class="mb-1 fw-bold text-dark">{{ auth()->user()->name }}</p>
                                                <p class="mb-0 text-muted small">{{ $address->address }}</p>
                                            </div>
                                            <i class="bi bi-check-circle-fill text-success fs-4 d-none"></i>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('customer.address.index') }}"
                                class="btn btn-link btn-sm text-success fw-bold p-0 mt-3">+ Tambah Alamat Baru</a>
                        </div>
                    </div>

                    {{-- 📦 SECTION 2: ITEM PRODUK --}}
                    <div class="card checkout-card mb-4">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="bi bi-box-seam"></i> Pesanan Anda
                            </div>
                            @foreach ($carts as $cart)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <img src="{{ $cart->product->image }}" class="product-img-mini me-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold text-dark text-truncate" style="max-width: 250px;">
                                            {{ $cart->product->name }}</h6>
                                        <small class="text-muted">{{ $cart->pcs }} unit x Rp
                                            {{ number_format($cart->product->final_price) }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-dark">
                                            Rp {{ number_format($cart->product->final_price * $cart->pcs) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 🚚 SECTION 3: JASA PENGIRIMAN --}}
                    <div class="card checkout-card mb-4">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="bi bi-truck"></i> Pilihan Pengiriman
                            </div>
                            <div id="courier-list" class="row g-3">
                                <div class="col-12 text-center py-4">
                                    <i class="bi bi-geo text-muted fs-1 opacity-25"></i>
                                    <p class="text-muted mt-2 small">Silakan pilih alamat terlebih dahulu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 💳 SECTION 4: METODE PEMBAYARAN --}}
                    <div class="card checkout-card mb-4">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="bi bi-wallet2"></i> Metode Pembayaran
                            </div>
                            <div class="row g-3">
                                {{-- 💳 TRANSFER BANK --}}
                                <div class="col-md-4">
                                    <input type="radio" name="payment_method" value="midtrans" id="pay_bank"
                                        class="form-check-input-custom" required>
                                    <label for="pay_bank" class="selectable-card w-100 p-3 rounded-4 text-center h-100">
                                        <i class="bi bi-bank fs-2 mb-2 d-block"></i>
                                        <span class="fw-bold d-block small">Transfer Bank / VA</span>
                                        <i
                                            class="bi bi-check-circle-fill text-success position-absolute top-0 end-0 p-2 d-none"></i>
                                    </label>
                                </div>

                                {{-- 📱 E-WALLET --}}
                                @php
                                    $isWalletEnough = $walletBalance >= $subtotal;
                                @endphp

                                <div class="col-md-4">
                                    <input type="radio" name="payment_method" value="wallet" id="pay_wallet"
                                        class="form-check-input-custom" {{ !$isWalletEnough ? 'disabled' : '' }} required>

                                    <label for="pay_wallet"
                                        class="selectable-card w-100 p-3 rounded-4 text-center h-100 {{ !$isWalletEnough ? 'opacity-50' : '' }}">

                                        <i class="bi bi-wallet2 fs-2 mb-2 d-block"></i>

                                        <span class="fw-bold d-block small">
                                            Zalpe Pay
                                        </span>

                                        {{-- 💰 SALDO --}}
                                        <small class="d-block mt-1 text-muted">
                                            Saldo: Rp {{ number_format($walletBalance) }}
                                        </small>

                                        {{-- ⚠️ STATUS --}}
                                        @if (!$isWalletEnough)
                                            <small class="text-danger d-block mt-1">
                                                Saldo tidak cukup
                                            </small>
                                        @else
                                            <small class="text-success d-block mt-1">
                                                Bisa digunakan
                                            </small>
                                        @endif

                                        <i
                                            class="bi bi-check-circle-fill text-success position-absolute top-0 end-0 p-2 d-none"></i>
                                    </label>
                                </div>

                                {{-- 🚚 COD --}}
                                <div class="col-md-4">
                                    <input type="radio" name="payment_method" value="cod" id="pay_cod"
                                        class="form-check-input-custom" required>
                                    <label for="pay_cod" class="selectable-card w-100 p-3 rounded-4 text-center h-100">
                                        <i class="bi bi-hand-index-thumb fs-2 mb-2 d-block"></i>
                                        <span class="fw-bold d-block small">COD (Bayar di Tempat)</span>
                                        <i
                                            class="bi bi-check-circle-fill text-success position-absolute top-0 end-0 p-2 d-none"></i>
                                    </label>
                                </div>
                            </div>

                            {{-- 🔥 PILIH BANK (MUNCUL KALAU PILIH VA) --}}
                            <div id="bank-options" class="mt-4 d-none">
                                <label class="fw-bold mb-3 text-dark">Pilih Bank Pembayaran</label>

                                <div class="row g-3">
                                    {{-- Bank BCA --}}
                                    <div class="col-12 col-sm-4">
                                        <input type="radio" name="bank" value="bca" id="bank_bca"
                                            class="btn-check" autocomplete="off">
                                        <label
                                            class="btn btn-outline-light text-dark w-100 p-3 bank-card border rounded-4 d-flex flex-column align-items-center justify-content-center h-100"
                                            for="bank_bca">
                                            <!-- Ganti src dengan path logo di folder public Laravel Anda -->
                                            <img src="https://simulator.sandbox.midtrans.com/assets/images/payment_partners/bank_transfer/bca_va.png"
                                                alt="BCA" class="bank-logo mb-2">
                                            <span class="fw-semibold small">BCA</span>
                                        </label>
                                    </div>

                                    {{-- Bank BNI --}}
                                    <div class="col-12 col-sm-4">
                                        <input type="radio" name="bank" value="bni" id="bank_bni"
                                            class="btn-check" autocomplete="off">
                                        <label
                                            class="btn btn-outline-light text-dark w-100 p-3 bank-card border rounded-4 d-flex flex-column align-items-center justify-content-center h-100"
                                            for="bank_bni">
                                            <!-- Ganti src dengan path logo di folder public Laravel Anda -->
                                            <img src="https://simulator.sandbox.midtrans.com/assets/images/payment_partners/bank_transfer/bni_va.png"
                                                alt="BNI" class="bank-logo mb-2">
                                            <span class="fw-semibold small">BNI</span>
                                        </label>
                                    </div>

                                    {{-- Bank Mandiri --}}
                                    <div class="col-12 col-sm-4">
                                        <input type="radio" name="bank" value="mandiri" id="bank_mandiri"
                                            class="btn-check" autocomplete="off">
                                        <label
                                            class="btn btn-outline-light text-dark w-100 p-3 bank-card border rounded-4 d-flex flex-column align-items-center justify-content-center h-100"
                                            for="bank_mandiri">
                                            <!-- Ganti src dengan path logo di folder public Laravel Anda -->
                                            <img src="https://simulator.sandbox.midtrans.com/assets/images/payment_partners/bank_transfer/mandiri_bill.png"
                                                alt="Mandiri" class="bank-logo mb-2">
                                            <span class="fw-semibold small">Mandiri</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 💰 SISI KANAN: ORDER SUMMARY --}}
                <div class="col-lg-4">
                    <div class="card checkout-card sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                            @php
                                $subtotal = $carts->sum(
                                    fn($c) => ($c->product->price ?? $c->product->default_price) * $c->pcs,
                                );
                                $discount_by_merchant = $carts->sum(
                                    fn($c) => (($c->product->price ?? $c->product->default_price) -
                                        $c->product->final_price) *
                                        $c->pcs,
                                );
                                $subtotal_final = $subtotal - $discount_by_merchant;
                            @endphp

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Harga</span>
                                <span class="fw-medium">Rp {{ number_format($subtotal) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-danger">
                                <span class="small">Total Diskon</span>
                                <span class="small fw-bold">- Rp {{ number_format($discount_by_merchant) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Ongkos Kirim</span>
                                <span class="fw-bold" id="ongkir">Rp 0</span>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Total Tagihan</h5>
                                <h4 class="fw-bold text-success mb-0" id="total">Rp
                                    {{ number_format($subtotal_final) }}</h4>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm">
                                Selesaikan Pembayaran
                            </button>

                            <div class="mt-3 text-center">
                                <small class="text-muted"><i class="bi bi-shield-lock-fill me-1"></i> Pembayaran Aman &
                                    Terenkripsi</small>
                            </div>
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

            // 📌 Ambil data kurir saat alamat dipilih
            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    let districtId = this.dataset.district;

                    // Loading UI
                    courierList.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <div class="spinner-border text-success" role="status"></div>
                        <p class="text-muted mt-2">Mencari layanan kurir...</p>
                    </div>
                `;
                    ongkirText.innerText = 'Rp 0';
                    totalText.innerText = formatRupiah(subtotalFinal);

                    fetch(`/home/couriers/${districtId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (!data.length) {
                                courierList.innerHTML =
                                    `<div class="col-12 text-center py-4 text-danger">Layanan tidak tersedia.</div>`;
                                return;
                            }

                            let html = '';
                            data.forEach((courier, index) => {
                                let valueJson = JSON.stringify({
                                    name: courier.name,
                                    service: courier.service,
                                    price_per_kg: courier.price_per_kg,
                                    estimated_delivery_time: courier
                                        .estimated_delivery_time
                                });

                                html += `
                                <div class="col-md-6">
                                    <input type="radio" name="courier" value='${valueJson}' id="courier-${index}" 
                                        class="form-check-input-custom" data-price="${courier.price_per_kg}" required>
                                    <label for="courier-${index}" class="selectable-card w-100 p-3 rounded-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-dark">${courier.name}</span>
                                            <span class="text-success fw-bold small">${formatRupiah(courier.price_per_kg)}/kg</span>
                                        </div>
                                        <small class="text-muted d-block mt-1">${courier.service} (${courier.estimated_delivery_time})</small>
                                        <i class="bi bi-check-circle-fill text-success position-absolute top-0 end-0 p-2 d-none"></i>
                                    </label>
                                </div>
                            `;
                            });
                            courierList.innerHTML = html;
                        })
                        .catch(err => {
                            courierList.innerHTML =
                                `<div class="col-12 text-center py-4 text-danger">Gagal memuat data kurir.</div>`;
                        });
                });
            });

            // 📌 Hitung total ongkir saat kurir dipilih
            document.addEventListener('change', function(e) {
                if (e.target.name === 'courier') {
                    let pricePerKg = parseInt(e.target.dataset.price);
                    let ongkir = Math.ceil(totalWeight / 1000) * pricePerKg;

                    ongkirText.innerText = formatRupiah(ongkir);
                    totalText.innerText = formatRupiah(subtotalFinal + ongkir);
                }
            });

            // 📌 Validasi COD
            checkoutForm.addEventListener('submit', function(e) {
                let paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
                if (paymentMethod === 'cod' && subtotalFinal > 1000000) {
                    e.preventDefault();
                    alert("Ups! COD hanya tersedia untuk pesanan di bawah Rp 1.000.000");
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const payBank = document.getElementById("pay_bank");
            const payWallet = document.getElementById("pay_wallet");
            const payCod = document.getElementById("pay_cod");
            const bankOptions = document.getElementById("bank-options");

            function toggleBank() {
                if (payBank.checked) {
                    bankOptions.classList.remove("d-none");
                } else {
                    bankOptions.classList.add("d-none");

                    // reset pilihan bank
                    document.querySelectorAll('input[name="bank"]').forEach(el => el.checked = false);
                }
            }

            payBank.addEventListener("change", toggleBank);
            payWallet.addEventListener("change", toggleBank);
            payCod.addEventListener("change", toggleBank);
        });
    </script>
@endpush
