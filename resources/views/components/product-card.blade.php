<style>
    /* Styling Dasar Kartu */
    .product-card {
        border: 1px solid transparent;
        /* Border transparan default */
        border-radius: 15px;
        overflow: hidden;
        background-color: #fff;

        /* Animasi durasi 0.25 detik */
        transition: border-color 0.25s ease;
    }

    /* Efek Hover */
    .product-link:hover .product-card {
        border-color: #198754;
        /* Border hijau muncul dengan transisi */
    }

    .img-container {
        overflow: hidden;
        aspect-ratio: 1/1;
        background: #f8f9fa;
    }

    .product-card img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }

    /* Tema Khusus Gaming */
    .theme-gaming {
        background: linear-gradient(145deg, #1a1a1a, #000) !important;
        border-color: transparent !important;
    }

    .product-link:hover .theme-gaming {
        border-color: #dc3545 !important;
        /* Border merah muncul dengan transisi */
    }

    /* Tema Steam */
    .theme-steam {
        background: linear-gradient(135deg, #171a21, #2a475e) !important;
        color: white !important;
    }
</style>

<a href="{{ route('customer.product.show', $product->slug) }}" class="text-decoration-none d-block product-link">
    <div
        class="card h-100 product-card shadow-sm 
        @if ($product->brand_id == 30) theme-gaming
        @elseif($product->type == 'Steam Wallet') theme-steam @endif">

        {{-- Isinya tetap sama seperti sebelumnya --}}
        @if ($product->discount > 0)
            <span class="badge bg-danger position-absolute top-0 start-0 m-3 shadow-sm"
                style="z-index: 2; border-radius: 8px;">
                <i class="bi bi-lightning-fill"></i> {{ $product->discount }}% OFF
            </span>
        @endif

        <div class="img-container position-relative">
            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">

            @if ($product->is_out_of_stock)
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                    style="background: rgba(255,255,255,0.7); backdrop-filter: blur(2px);">
                    <span class="badge bg-dark px-3 py-2">Habis Terjual</span>
                </div>
            @endif
        </div>

        <div class="card-body d-flex flex-column p-3">
            <div class="mb-2">
                <span class="badge {{ $product->brand_id == 30 ? 'bg-danger' : 'bg-light text-dark border' }}"
                    style="font-size: 0.65rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    {{ $product->brand->name ?? 'Generic' }}
                </span>
            </div>

            <h6 class="card-title fw-bold mb-1 text-truncate {{ $product->brand_id == 30 || $product->type == 'Steam Wallet' ? 'text-white' : 'text-dark' }}"
                title="{{ $product->name }}">
                {{ $product->name }}
            </h6>

            <div class="d-flex align-items-center gap-2 mb-3" style="font-size: 0.75rem;">
                <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <span
                        class="{{ $product->brand_id == 30 || $product->type == 'Steam Wallet' ? 'text-light' : 'text-dark' }}">
                        {{ $product->rating ? number_format($product->rating, 1) : '5.0' }}
                    </span>
                </div>
                <span class="text-muted">|</span>
                <span
                    class="{{ $product->brand_id == 30 || $product->type == 'Steam Wallet' ? 'text-light' : 'text-muted' }}">
                    Terjual {{ number_format($product->sold ?? 0) }}
                </span>
            </div>

            <div class="mt-auto">
                @if ($product->discount > 0)
                    <div class="text-decoration-line-through text-muted small mb-0" style="font-size: 0.7rem;">
                        Rp {{ number_format($product->default_price, 0, ',', '.') }}
                    </div>
                @endif

                <div
                    class="fs-5 fw-bold {{ $product->brand_id == 30 ? 'text-danger' : ($product->type == 'Steam Wallet' ? 'text-info' : 'text-success') }}">
                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</a>
