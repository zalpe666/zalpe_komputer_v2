@php
    $isGaming = $product->brand_id == 30;
    $isSteam = $product->type == 'Steam Wallet';
    $isDarkTheme = $isGaming || $isSteam;

    $cardTheme = match (true) {
        $isGaming => 'theme-gaming',
        $isSteam => 'theme-steam',
        default => '',
    };

    $textColor = $isDarkTheme ? 'text-white' : 'text-dark';
    $mutedColor = $isDarkTheme ? 'text-light' : 'text-muted';

    $priceColor = match (true) {
        $isGaming => 'text-danger',
        $isSteam => 'text-info',
        default => 'text-success',
    };

@endphp

<style>
    .product-card {
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 15px;
        overflow: hidden;
        transition: border-color 0.25s ease;
    }

    .product-link:hover .product-card {
        border-color: #198754;
    }

    .img-container {
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #f8f9fa;
    }

    .product-card img {
        width: 100%;
        height: 100%;
        padding: 10px;
        object-fit: contain;
    }

    .theme-gaming {
        background: linear-gradient(145deg, #1a1a1a, #000) !important;
        border-color: transparent !important;
    }

    .product-link:hover .theme-gaming {
        border-color: #dc3545 !important;
    }

    .theme-steam {
        background: linear-gradient(135deg, #171a21, #2a475e) !important;
        color: #fff !important;
    }

    @media (max-width: 575.98px) {
        .product-card {
            height: auto !important;
        }

        .product-card .card-body {
            padding: 10px !important;
        }

        .product-card .card-body>.mt-auto {
            margin-top: 8px !important;
        }

        .product-card .card-title {
            font-size: 0.85rem;
        }

        .product-card .fs-5 {
            font-size: 0.95rem !important;
        }

        .product-card .img-container {
            aspect-ratio: 1 / 1;
        }
    }
</style>

<a href="{{ $product->is_games
    ? route('customer.game.show', $product->slug)
    : route('customer.product.show', $product->slug) }}"
    class="product-link d-block text-decoration-none">

    <div class="card h-100 product-card shadow-sm {{ $cardTheme }}">

        @if ($product->discount > 0)
            <span class="badge bg-danger position-absolute top-0 start-0 m-3 shadow-sm"
                style="z-index: 2; border-radius: 8px;">
                <i class="bi bi-lightning-fill"></i>
                {{ $product->discount }}% OFF
            </span>
        @endif

        <div class="img-container position-relative">
            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">

            @if ($product->is_out_of_stock)
                <div class="position-absolute top-0 start-0 w-100 h-100
                    d-flex align-items-center justify-content-center"
                    style="background: rgba(255,255,255,0.7); backdrop-filter: blur(2px);">

                    <span class="badge bg-dark px-3 py-2">
                        Habis Terjual
                    </span>
                </div>
            @endif
        </div>

        <div class="card-body d-flex flex-column p-3">

            <div class="mb-2">
                <span class="badge {{ $isGaming ? 'bg-danger' : 'bg-light text-dark border' }}"
                    style="font-size: 0.65rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    {{ $product->brand->name ?? $product->publishers }}
                </span>
            </div>

            <h6 class="card-title fw-bold mb-1 text-truncate {{ $textColor }}" title="{{ $product->name }}">
                {{ $product->name }}
            </h6>

            <div class="d-flex align-items-center gap-2 mb-3" style="font-size: 0.75rem;">

                <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <span class="{{ $textColor }}">
                        {{ $product->rating ? number_format($product->rating, 1) : '5.0' }}
                    </span>
                </div>

                <span class="{{ $mutedColor }}">|</span>

                <span class="{{ $mutedColor }}">
                    Terjual {{ number_format($product->sold ?? 0) }}
                </span>

            </div>

            <div class="mt-auto">

                @if ($product->discount > 0)
                    <div class="text-decoration-line-through {{ $mutedColor }} small mb-0"
                        style="font-size: 0.7rem;">
                        Rp {{ number_format($product->default_price, 0, ',', '.') }}
                    </div>
                @endif

                <div class="fs-5 fw-bold {{ $priceColor }}">
                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                </div>

            </div>

        </div>
    </div>
</a>
