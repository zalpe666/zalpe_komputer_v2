<style>
    .product-card {
        transition: all 0.25s ease-in-out;
        border: 1px solid transparent;
        border-radius: 12px;
        /* default radius */
    }

    .product-card:hover {
        transform: translateY(-5px);
        border-color: #198754;
        /* bootstrap success */
        box-shadow: 0 0 15px rgba(25, 135, 84, 0.4);
        border-radius: 12px;
        /* tetap melengkung saat hover */
    }
</style>
<a href="{{ route('customer.product.show', $product->slug) }}" class="text-decoration-none d-block">

    <div
        class="card h-100 position-relative product-card my-2
        @if ($product->brand_id == 30) bg-dark text-danger border border-danger
        @elseif($product->type == 'Steam Wallet')
            bg-info text-white rounded-4 @endif
    ">

        {{-- BADGE DISKON --}}
        @if ($product->discount > 0)
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                -{{ $product->discount }}%
            </span>
        @endif

        {{-- IMAGE --}}
        <img src="{{ $product->image }}" class="card-img-top" style="object-fit:cover;"
            onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">

        <div class="card-body d-flex flex-column">

            {{-- NAME --}}
            <h6 class="fw-bold text-truncate
                {{ $product->brand_id == 30 ? 'text-danger' : '' }}">
                {{ $product->name }}
            </h6>

            {{-- BRAND --}}
            <span
                style="
                font-size: 10px;
                background: {{ $product->brand_id == 30 ? '#ff0000' : '#333' }};
                color: white;
                padding: 2px 6px;
                border-radius: 4px;
                display: inline-block;
                width: fit-content;
            ">
                {{ strtoupper($product->brand->name ?? '-') }}
            </span>

            {{-- RATING & SOLD --}}
            <small class="{{ $product->brand_id == 30 ? 'text-white' : 'text-muted' }}">
                {{ $product->rating ?? '-' }}
                <i class="bi bi-star-fill text-warning"></i>
                •
                {{ number_format($product->sold ?? 0) }} sold
            </small>

            {{-- PRICE --}}
            <div class="mt-2 mb-3">

                @if ($product->discount > 0)
                    <small
                        class="text-decoration-line-through
                        {{ $product->brand_id == 30 ? 'text-danger' : 'text-muted' }}"
                        style="font-size: 12px">
                        Rp {{ number_format($product->default_price) }}
                    </small>
                @endif

                <span
                    class="fw-bold
                    @if ($product->brand_id == 30) text-danger
                    @elseif($product->type !== 'Product')
                        text-white
                    @else
                        text-success @endif
                ">
                    Rp {{ number_format($product->final_price) }}
                </span>

            </div>

            {{-- STOCK --}}
            @if ($product->is_out_of_stock)
                <span class="badge bg-danger mb-2">Out of Stock</span>
            @endif

        </div>
    </div>

</a>
