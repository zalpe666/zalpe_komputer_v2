@extends('layouts.app')

@section('title', 'Admin Panel')
@section('content')
    <div class="container mt-4">
        <div class="row">

            {{-- 🛒 CART LIST --}}
            <div class="col-md-8">
                <h4 class="mb-3">Shopping Cart</h4>
                <div class="mb-2">
                    <input type="checkbox" id="select-all" class="form-check-input">
                    <label for="select-all">Pilih Semua</label>
                </div>
                @forelse($carts as $cart)
                    <div class="card mb-3">
                        <div class="card-body d-flex align-items-center">

                            {{-- ✅ CHECKBOX --}}
                            <input type="checkbox" class="form-check-input me-3 cart-checkbox" data-id="{{ $cart->id }}"
                                data-price="{{ $cart->product->final_price }}" data-qty="{{ $cart->pcs }}"
                                id="checkbox-{{ $cart->id }}">

                            <img src="{{ $cart->product->image }}" width="80" class="me-3">

                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $cart->product->name }}</h6>

                                <small class="text-muted">
                                    Rp {{ number_format($cart->product->final_price) }}
                                </small>

                                {{-- QTY --}}
                                <div class="d-flex align-items-center mt-2">

                                    {{-- MINUS --}}
                                    <button class="btn btn-sm btn-outline-secondary btn-qty" data-id="{{ $cart->id }}"
                                        data-action="minus">
                                        -
                                    </button>

                                    <span class="mx-3 qty-text" id="qty-{{ $cart->id }}">
                                        {{ $cart->pcs }}
                                    </span>

                                    {{-- PLUS --}}
                                    <button class="btn btn-sm btn-outline-success btn-qty" data-id="{{ $cart->id }}"
                                        data-action="plus">
                                        +
                                    </button>

                                </div>
                            </div>

                            {{-- SUBTOTAL ITEM --}}
                            <div class="text-end">
                                <div class="fw-bold text-success" id="subtotal-item-{{ $cart->id }}">
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
                            <strong id="subtotal-text">Rp 0</strong>
                        </div>

                        <hr>

                        <button id="checkout-btn" class="btn btn-success w-100" disabled>
                            Checkout
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('script')
    <script>
        const updateCartUrl = "{{ route('customer.cart.update', ':id') }}";
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const checkboxes = document.querySelectorAll('.cart-checkbox');
            const selectAll = document.getElementById('select-all');
            const subtotalText = document.getElementById('subtotal-text');
            const checkoutBtn = document.getElementById('checkout-btn');
            const qtyButtons = document.querySelectorAll('.btn-qty');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function formatRupiah(number) {
                return 'Rp ' + number.toLocaleString('id-ID');
            }

            function calculateSubtotal() {
                let subtotal = 0;
                let checkedCount = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        let price = parseInt(cb.dataset.price);
                        let qty = parseInt(cb.dataset.qty);
                        subtotal += price * qty;
                        checkedCount++;
                    }
                });

                subtotalText.innerText = formatRupiah(subtotal);
                checkoutBtn.disabled = subtotal === 0;
                selectAll.checked = checkedCount === checkboxes.length;
            }

            // ✅ HANDLE CHECKBOX
            checkboxes.forEach(cb => {
                cb.addEventListener('change', calculateSubtotal);
            });

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                calculateSubtotal();
            });

            // 🔥 AJAX QTY UPDATE
            qtyButtons.forEach(btn => {
                btn.addEventListener('click', function() {

                    let id = this.dataset.id;
                    let action = this.dataset.action;

                    let url = updateCartUrl.replace(':id', id);

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
                        .then(response => {
                            // console.log(response); // 🔥 debug dulu

                            if (!response || !response.ok) {
                                throw new Error('Response error');
                            }

                            return response.json();
                        })
                        .then(data => {

                            // update qty text
                            document.getElementById(`qty-${id}`).innerText = data.qty;

                            let checkbox = document.getElementById(`checkbox-${id}`);

                            // update qty di checkbox
                            checkbox.dataset.qty = data.qty;

                            let price = parseInt(checkbox.dataset.price);

                            // 🔥 update subtotal per item
                            document.getElementById(`subtotal-item-${id}`).innerText =
                                formatRupiah(price * data.qty);

                            calculateSubtotal();
                        })
                        .catch(err => {
                            console.error('ERROR:', err);
                        });
                });
            });

            // ✅ CHECKOUT
            checkoutBtn.addEventListener('click', function() {
                let selectedIds = [];

                checkboxes.forEach(cb => {
                    if (cb.checked) selectedIds.push(cb.dataset.id);
                });

                if (selectedIds.length === 0) return;

                window.location.href = `/home/checkout?carts=${selectedIds.join(',')}`;
            });

        });
    </script>
@endpush
