@extends('layouts.app')

@section('title', 'Keranjang Belanja - Zalpe Komputer')

@section('content')
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Inter', sans-serif;
        }

        /* Card Styling */
        .cart-card {
            transition: all 0.3s ease;
            border: 1px solid #edf2f7;
        }

        .cart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08) !important;
        }

        /* Product Image Container */
        .img-container {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Qty Control */
        .qty-input-group {
            border: 1.5px solid #e2e8f0;
            border-radius: 50px;
            overflow: hidden;
            width: fit-content;
        }

        .qty-text {
            min-width: 45px;
            text-align: center;
            font-weight: 700;
            color: #334155;
        }

        /* Sticky Sidebar */
        .sticky-summary {
            top: 100px;
            z-index: 10;
        }

        /* Custom Checkbox */
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .form-check-input {
            cursor: pointer;
            width: 1.25rem;
            height: 1.25rem;
        }

        .btn-checkout {
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-checkout:not(:disabled):hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
        }
    </style>

    <div class="container py-5">
        <div class="row g-4">

            {{-- 🛒 SISI KIRI: DAFTAR KERANJANG --}}
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h3 class="fw-bold text-dark m-0">Keranjang Belanja</h3>
                        <p class="text-muted mb-0">Kamu punya <span class="fw-bold text-success">{{ count($carts) }}
                                barang</span> di keranjang</p>
                    </div>
                </div>

                @if (count($carts) > 0)
                    {{-- Action Bar: Select All --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
                            <div class="form-check d-flex align-items-center">
                                <input type="checkbox" id="select-all" class="form-check-input shadow-none me-2">
                                <label class="form-check-label fw-bold text-dark" for="select-all">Pilih Semua</label>
                            </div>
                            <button class="btn btn-sm text-danger fw-bold text-decoration-none p-0">Hapus Pilihan</button>
                        </div>
                    </div>

                    {{-- Cart Items Loop --}}
                    @foreach ($carts as $cart)
                        <div class="card border-0 shadow-sm rounded-4 mb-3 cart-card">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    {{-- Checkbox --}}
                                    <div class="col-auto">
                                        <input type="checkbox" class="form-check-input cart-checkbox shadow-none"
                                            data-id="{{ $cart->id }}" data-price="{{ $cart->product->final_price }}"
                                            data-original-price="{{ $cart->product->price ?? $cart->product->default_price }}"
                                            data-qty="{{ $cart->pcs }}" id="checkbox-{{ $cart->id }}">
                                    </div>

                                    {{-- Image --}}
                                    <div class="col-auto">
                                        <div class="img-container">
                                            <img src="{{ $cart->product->image }}" width="90" height="90"
                                                style="object-fit: contain;" alt="Product">
                                        </div>
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="col ms-2">
                                        <h6 class="fw-bold text-dark mb-1">{{ $cart->product->name }}</h6>
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <span class="fw-bold text-success">Rp
                                                {{ number_format($cart->product->final_price, 0, ',', '.') }}</span>
                                            @if ($cart->product->discount > 0)
                                                <span class="text-muted text-decoration-line-through small">Rp
                                                    {{ number_format($cart->product->price ?? $cart->product->default_price, 0, ',', '.') }}</span>
                                                <span
                                                    class="badge bg-danger-subtle text-danger small">{{ $cart->product->discount }}%
                                                    OFF</span>
                                            @endif
                                        </div>

                                        {{-- Qty Controls --}}
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="qty-input-group d-flex align-items-center bg-white shadow-sm">
                                                <button class="btn btn-link text-success p-2 px-3 btn-qty"
                                                    data-id="{{ $cart->id }}" data-action="minus"><i
                                                        class="bi bi-dash-lg"></i></button>
                                                <span class="qty-text"
                                                    id="qty-{{ $cart->id }}">{{ $cart->pcs }}</span>
                                                <button class="btn btn-link text-success p-2 px-3 btn-qty"
                                                    data-id="{{ $cart->id }}" data-action="plus"><i
                                                        class="bi bi-plus-lg"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Subtotal Item & Delete --}}
                                    <div class="col-auto text-end">
                                        <p class="text-muted small mb-1">Subtotal</p>
                                        <h5 class="fw-bold text-dark mb-3" id="subtotal-item-{{ $cart->id }}">
                                            Rp {{ number_format($cart->product->final_price * $cart->pcs, 0, ',', '.') }}
                                        </h5>
                                        <form method="POST" action="{{ route('customer.cart.remove', $cart->id) }}">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="bi bi-trash3 me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Empty State --}}
                    <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
                        <div class="card-body">
                            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="180"
                                class="mb-4 opacity-50">
                            <h4 class="fw-bold">Keranjangmu masih kosong</h4>
                            <p class="text-muted">Yuk, cari komponen PC impianmu sekarang!</p>
                            <a href="{{ route('customer.product.index') }}"
                                class="btn btn-success rounded-pill px-5 py-2 fw-bold mt-2">Mulai Belanja</a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 💰 SISI KANAN: RINGKASAN PEMBAYARAN --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow rounded-4 sticky-top sticky-summary">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-4">Ringkasan Belanja</h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Harga (<span id="checked-count" class="fw-bold">0</span>
                                barang)</span>
                            <span class="text-dark fw-bold" id="total-harga-asli">Rp 0</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Diskon</span>
                            <span class="text-danger fw-bold" id="discount-text">- Rp 0</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Pajak (PPN 11%)</span>
                            <span class="text-dark fw-medium">Termasuk</span>
                        </div>

                        <hr class="my-4" style="border-style: dashed;">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold text-dark mb-0">Total Tagihan</h6>
                            <h4 class="fw-bold text-success mb-0" id="total-tagihan">Rp 0</h4>
                        </div>

                        <button id="checkout-btn"
                            class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm mb-3 btn-checkout" disabled>
                            Checkout Sekarang (<span id="btn-count">0</span>)
                        </button>

                        <div class="bg-light rounded-3 p-3 text-center">
                            <p class="small text-muted mb-0">
                                <i class="bi bi-patch-check-fill text-success me-1"></i>
                                Jaminan Produk Original & Garansi Zalpe
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('script')
        <script>
            const updateCartUrl = "{{ route('customer.cart.update', ':id') }}";

            document.addEventListener("DOMContentLoaded", function() {
                const checkboxes = document.querySelectorAll('.cart-checkbox');
                const selectAll = document.getElementById('select-all');
                const originalPriceText = document.getElementById('total-harga-asli');
                const discountText = document.getElementById('discount-text');
                const totalTagihanText = document.getElementById('total-tagihan');
                const checkoutBtn = document.getElementById('checkout-btn');
                const checkedCountText = document.getElementById('checked-count');
                const btnCountText = document.getElementById('btn-count');
                const qtyButtons = document.querySelectorAll('.btn-qty');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                function formatRupiah(number) {
                    return 'Rp ' + number.toLocaleString('id-ID');
                }

                function calculateCart() {
                    let totalAsli = 0;
                    let totalFinal = 0;
                    let checkedCount = 0;

                    checkboxes.forEach(cb => {
                        if (cb.checked) {
                            let originalPrice = parseInt(cb.dataset.originalPrice);
                            let finalPrice = parseInt(cb.dataset.price);
                            let qty = parseInt(cb.dataset.qty);

                            totalAsli += originalPrice * qty;
                            totalFinal += finalPrice * qty;
                            checkedCount++;
                        }
                    });

                    let diskon = totalAsli - totalFinal;

                    // Update UI
                    originalPriceText.innerText = formatRupiah(totalAsli);
                    discountText.innerText = '- ' + formatRupiah(diskon);
                    totalTagihanText.innerText = formatRupiah(totalFinal);
                    checkedCountText.innerText = checkedCount;
                    btnCountText.innerText = checkedCount;

                    checkoutBtn.disabled = checkedCount === 0;
                    if (selectAll) selectAll.checked = (checkedCount === checkboxes.length && checkboxes.length > 0);
                }

                // Checkbox Event
                checkboxes.forEach(cb => {
                    cb.addEventListener('change', calculateCart);
                });

                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        checkboxes.forEach(cb => cb.checked = selectAll.checked);
                        calculateCart();
                    });
                }

                // Qty Update (AJAX)
                qtyButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.dataset.id;
                        const action = this.dataset.action;
                        const url = updateCartUrl.replace(':id', id);

                        // Animasi Loading pada item
                        const card = this.closest('.cart-card');
                        card.style.opacity = "0.6";

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    action: action
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                card.style.opacity = "1";

                                // Update Qty Teks
                                document.getElementById(`qty-${id}`).innerText = data.qty;

                                // Update Dataset di Checkbox
                                const cb = document.getElementById(`checkbox-${id}`);
                                cb.dataset.qty = data.qty;

                                // Update Subtotal Item
                                const subtotal = parseInt(cb.dataset.price) * data.qty;
                                document.getElementById(`subtotal-item-${id}`).innerText =
                                    formatRupiah(subtotal);

                                calculateCart();
                            });
                    });
                });

                // Checkout Action
                checkoutBtn.addEventListener('click', function() {
                    let selectedIds = [];
                    checkboxes.forEach(cb => {
                        if (cb.checked) selectedIds.push(cb.dataset.id);
                    });
                    window.location.href = `/home/checkout?carts=${selectedIds.join(',')}`;
                });
            });
        </script>
    @endpush
@endsection
