@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    body { background-color: #f8f9fa; }
    
    /* Image Gallery Styling */
    .product-gallery {
        position: sticky;
        top: 100px;
    }
    .zoom-container {
        overflow: hidden;
        border-radius: 20px;
        background: #fff;
        cursor: zoom-in;
        border: 1px solid #f1f5f9;
    }
    .zoom-container img {
        width: 100%;
        transition: transform 0.3s ease;
    }

    /* Badge Styling */
    .badge-soft {
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
    }
    .badge-soft-success { background: #dcfce7; color: #15803d; }
    .badge-soft-secondary { background: #f1f5f9; color: #475569; }

    /* Sticky Sidebar for Action */
    .action-sidebar {
        position: sticky;
        top: 100px;
    }

    /* Qty Input */
    .qty-control {
        border: 2px solid #f1f5f9;
        border-radius: 50px;
        overflow: hidden;
        background: #fff;
    }
    .qty-control input {
        border: none;
        font-weight: 700;
        width: 50px;
        text-align: center;
    }

    /* Review Progress */
    .progress {
        height: 10px;
        border-radius: 10px;
        background-color: #f1f5f9;
    }

    /* Description Styling */
    .product-description {
        line-height: 1.8;
        color: #4b5563;
    }
</style>

<div class="container py-5">
    <div class="row g-5">
        
        {{-- 📸 LEFT SIDE: IMAGE GALLERY --}}
        <div class="col-lg-5">
            <div class="product-gallery">
                <div class="card border-0 shadow-sm zoom-container" id="zoomContainer">
                    <img id="zoomImage" src="{{ $product->image }}" 
                         onerror="this.src='https://via.placeholder.com/600x600?text=No+Image'">
                </div>
                <div class="mt-3 d-flex gap-2">
                    {{-- Opsional: Tambahkan thumbnail jika ada multiple images --}}
                    <div class="border rounded-3 p-1 bg-white shadow-sm" style="width: 70px; height: 70px;">
                        <img src="{{ $product->image }}" class="img-fluid rounded-2">
                    </div>
                </div>
            </div>
        </div>

        {{-- 📝 MIDDLE SIDE: PRODUCT INFO --}}
        <div class="col-lg-4">
            {{-- Category & Brand --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-success small fw-bold">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">{{ $product->brand->name }}</li>
                </ol>
            </nav>

            <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="text-warning">
                    @for($i=1; $i<=5; $i++)
                        <i class="bi bi-star{{ $i <= $product->rating ? '-fill' : '' }}"></i>
                    @endfor
                    <span class="text-dark fw-bold ms-1">{{ number_format($product->rating, 1) }}</span>
                </div>
                <div class="text-muted small">
                    <span class="fw-bold text-dark">{{ number_format($product->sold) }}</span> Terjual • 
                    <span class="fw-bold text-dark">{{ number_format($product->view) }}</span> Dilihat
                </div>
            </div>

            <hr class="opacity-50">

            {{-- Price Section --}}
            <div class="my-4">
                @if ($product->discount > 0)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 small">{{ $product->discount }}% OFF</span>
                        <span class="text-muted text-decoration-line-through small">Rp {{ number_format($product->default_price) }}</span>
                    </div>
                @endif
                <h1 class="fw-bold text-success mb-0">Rp {{ number_format($product->final_price) }}</h1>
            </div>

            <hr class="opacity-50">

            {{-- Description --}}
            <div class="mt-4">
                <h6 class="fw-bold text-dark mb-3">Detail Produk</h6>
                <div class="product-description small">
                    {!! $product->description ?? 'Tidak ada deskripsi tersedia.' !!}
                </div>
            </div>
        </div>

        {{-- 🛒 RIGHT SIDE: ACTION CARD --}}
        <div class="col-lg-3">
            <div class="action-sidebar">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h6 class="fw-bold mb-4 text-dark">Atur Jumlah</h6>
                    
                    @auth
                        {{-- Qty Control --}}
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="qty-control d-flex align-items-center shadow-sm">
                                <button class="btn btn-link text-success p-2" onclick="decreaseQty()"><i class="bi bi-dash-lg"></i></button>
                                <input type="text" id="qty" value="1" readonly>
                                <button class="btn btn-link text-success p-2" onclick="increaseQty()"><i class="bi bi-plus-lg"></i></button>
                            </div>
                            <small class="text-muted">Stok: <b>{{ $product->stock }}</b></small>
                        </div>

                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <span class="text-muted small">Subtotal</span>
                            <h5 class="fw-bold text-dark mb-0" id="totalPrice">Rp {{ number_format($product->final_price) }}</h5>
                        </div>

                        @if ($product->type !== 'Product')
                            <a href="{{ route('customer.checkout.now.index', $product->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold py-3 mb-2 shadow-sm">⚡ Beli Sekarang</a>
                        @else
                            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="pcs" id="pcsInput" value="1">
                                <button class="btn btn-success w-100 rounded-pill fw-bold py-3 mb-2 shadow-sm" {{ $product->is_out_of_stock ? 'disabled' : '' }}>
                                    <i class="bi bi-cart-plus me-2"></i> Keranjang
                                </button>
                            </form>
                        @endif
                        
                        <button class="btn btn-outline-secondary w-100 rounded-pill py-2 border-0 shadow-none small">
                            <i class="bi bi-heart me-1"></i> Wishlist
                        </button>
                    @endauth

                    @guest
                        <div class="bg-light p-3 rounded-4 text-center mb-3">
                            <p class="small text-muted mb-3">Login untuk mulai belanja produk ini</p>
                            <a href="{{ route('login') }}" class="btn btn-success w-100 rounded-pill fw-bold">Masuk</a>
                        </div>
                    @endguest
                </div>

                <div class="mt-4 px-2">
                    <div class="d-flex align-items-center gap-2 small text-muted mb-2">
                        <i class="bi bi-patch-check text-success"></i> Original & Bergaransi
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-muted">
                        <i class="bi bi-shield-check text-success"></i> Keamanan Zalpe Komputer
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ⭐ REVIEWS SECTION --}}
    <div class="mt-5 pt-5 border-top">
        <div class="row g-5">
            <div class="col-lg-4">
                <h5 class="fw-bold text-dark mb-4">Ulasan Pembeli</h5>
                <div class="card border-0 bg-white shadow-sm rounded-4 p-4 text-center">
                    <h1 class="display-4 fw-bold text-dark mb-0">{{ number_format($avgRating, 1) }}<span class="fs-4 text-muted">/5.0</span></h1>
                    <div class="text-warning mb-2 fs-5">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <p class="text-muted small">{{ number_format($totalRating) }} Rating • {{ number_format($totalReview) }} Ulasan</p>
                    
                    <div class="mt-3">
                        @for ($i = 5; $i >= 1; $i--)
                            @php
                                $count = $ratingCounts[$i] ?? 0;
                                $percent = $totalRating > 0 ? ($count / $totalRating) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="small text-muted" style="width: 20px;">{{ $i }}</span>
                                <i class="bi bi-star-fill text-warning small"></i>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="small text-muted" style="width: 40px;">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <h5 class="fw-bold text-dark mb-4">Ulasan Terbaru</h5>
                @forelse ($reviews as $review)
                    <div class="card border-0 bg-white shadow-sm rounded-4 p-4 mb-3">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($review->transaction->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $review->transaction->user->name ?? 'Zalpe User' }}</h6>
                                    <div class="text-warning small">
                                        @for($j=1; $j<=5; $j++)
                                            <i class="bi bi-star{{ $j <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                        <span class="text-muted ms-2">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small">{{ $review->review ?? 'Pembeli tidak memberikan komentar.' }}</p>
                    </div>
                @empty
                    <div class="bg-light p-5 rounded-4 text-center">
                        <i class="bi bi-chat-dots-fill text-muted fs-1 opacity-25"></i>
                        <p class="text-muted mt-3">Belum ada ulasan untuk produk ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        // Zoom Effect
        const container = document.getElementById('zoomContainer');
        const image = document.getElementById('zoomImage');

        container.addEventListener('mousemove', function(e) {
            const rect = container.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            image.style.transformOrigin = `${x}% ${y}%`;
            image.style.transform = "scale(2.5)";
        });

        container.addEventListener('mouseleave', function() {
            image.style.transform = "scale(1)";
        });

        // Qty Logic
        let qty = 1;
        const price = {{ $product->final_price }};
        const stock = {{ $product->stock }};

        function updateTotal() {
            document.getElementById('qty').value = qty;
            document.getElementById('pcsInput').value = qty;
            document.getElementById('totalPrice').innerText = 'Rp ' + (qty * price).toLocaleString('id-ID');
        }

        function increaseQty() {
            if(qty < stock) {
                qty++;
                updateTotal();
            }
        }

        function decreaseQty() {
            if (qty > 1) {
                qty--;
                updateTotal();
            }
        }
    </script>
@endpush