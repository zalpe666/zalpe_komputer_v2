    @extends('layouts.app')

    @section('title', 'Admin Panel')
    @section('content')
        <style>
            .rog-row {
                background-image: url('https://rog.asus.com/_nuxt/img/rog_bg_pattern.2e3d71a.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;

                position: relative;
            }

            /* optional: biar teks lebih kebaca */
            .rog-row::before {
                content: "";
                position: absolute;
                inset: 0;
                /* gelapin sedikit */
            }

            .rog-row>.container {
                position: relative;
                z-index: 1;
            }

            .product-slider {
                height: 400px;
            }

            <style>

            /* Styling Dasar Kartu */
            .category-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid transparent !important;
            }

            /* Wrapper Gambar agar tetap konsisten ukurannya */
            .category-icon-wrapper {
                width: 80px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f8f9fa;
                border-radius: 50%;
                transition: all 0.3s ease;
            }

            .category-img {
                max-width: 50px;
                max-height: 50px;
                object-fit: contain;
                transition: transform 0.3s ease;
            }

            /* Efek Hover */
            .category-card:hover {
                transform: translateY(-3px);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
                border-color: #198754 !important;
                /* Border hijau saat hover */
            }

            .category-card:hover .category-icon-wrapper {
                background-color: #e8f5e9;
                /* Background icon jadi hijau muda */
            }

            .category-card:hover .category-img {
                transform: scale(1.1);
                /* Gambar sedikit membesar */
            }

            .category-card:hover .category-name {
                color: #198754 !important;
            }

            /* Perbaikan Responsif untuk Mobile */
            @media (max-width: 576px) {
                .category-icon-wrapper {
                    width: 60px;
                    height: 60px;
                }

                .category-img {
                    max-width: 35px;
                }

                .category-name {
                    font-size: 0.85rem;
                }
            }
        </style>


        <div class="">
            {{-- Banner Slider --}}
            <div class="container">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach ($banners as $key => $banner)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ $banner->image }}" class="d-block w-100" style="object-fit: cover;">
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
            {{-- Latest Products --}}
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="ps-3" style="border-left: 10px solid #dc3545;border-radius:10px">
                        <h3 class="m-0">Latest Product</h3>
                        <p class="text-muted mb-0">Discover our newest arrivals</p>
                    </div>

                    <div class="d-flex">
                        <button class="btn btn-outline-success btn-prev rounded-3 me-2">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <button class="btn btn-outline-success btn-next rounded-3">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="product-slider">
                    @forelse ($productsLatest as $product)
                        <div class="pe-2">
                            <x-product-card :product="$product" />
                        </div>
                    @empty
                        <p class="text-muted">No products available at the moment.</p>
                    @endforelse
                </div>
            </div>
            {{-- Shop by Categories --}}
            <div class="container mt-5">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Shop by Categories</h3>
                        <p class="text-muted mb-0">Temukan komponen terbaik berdasarkan kategorinya</p>
                    </div>
                    <a href="{{ route('customer.product.index') }}"
                        class="btn btn-outline-success btn-sm rounded-pill px-3">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-3 g-lg-4">
                    @foreach ($categories as $category)
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="{{ route('customer.product.index', ['category[]' => $category->id]) }}"
                                class="text-decoration-none group">
                                <div class="card h-100 border-0 rounded-4 shadow-sm category-card overflow-hidden">
                                    <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                                        {{-- Image Container --}}
                                        <div class="category-icon-wrapper mb-3">
                                            <img src="{{ $category->photo_url }}" alt="{{ $category->name }}"
                                                class="img-fluid category-img">
                                        </div>

                                        {{-- Category Name --}}
                                        <h6 class="fw-bold text-dark mb-0 text-center category-name">
                                            {{ $category->name }}
                                        </h6>

                                        {{-- Optional: Item Count (Hapus jika tidak ada data) --}}
                                        {{-- <small class="text-muted mt-1">120+ Produk</small> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr>
            <div class="container mt-4">
                <div class="row">
                    <!-- Banner 1 -->
                    <div class="col-12 col-md-6">
                        <div class="card text-black border-0" style="min-height: 200px;">
                            <div class="card-img-overlay d-flex flex-column justify-content-center p-4"
                                style="background: url('{{ asset('images/storage-banner.png') }}') center/cover no-repeat; border-radius: .5rem; min-height: 200px;">

                                <h4 class="fw-bold mb-2">SSD & HDD</h4>

                                <p class="mb-3">
                                    Get up to <span class="fw-bold">30%</span> off
                                </p>

                                <div>
                                    <a href="#" class="btn btn-dark btn-sm">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card text-white border-0" style="min-height: 200px;">
                            <div class="card-img-overlay d-flex flex-column justify-content-center p-4"
                                style="background: url('{{ asset('images/steam-banner.png') }}') center/cover no-repeat; border-radius: .5rem; min-height: 200px;">

                                <h4 class="fw-bold mb-2">Steam Wallet</h4>

                                <p class="mb-3">
                                    Get up to <span class="fw-bold">25%</span> off
                                </p>

                                <div>
                                    <a href="#" class="btn btn-light btn-sm">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rog-row text-white d-flex align-items-center mt-4">
                <div class="container py-3">
                    <h1 class="text-danger fw-bold">REPUBLIC OF GAMERS</h1>
                    <p class="text-white">Explore the latest high-performance gaming gear and components</p>
                    <div class="product-slider container">
                        @foreach ($rogProducts as $product)
                            <div class="pe-2">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div>
                <div class="container py-3">
                    <h1>Discounted Products</h1>
                    <p class="text-muted">Check out our latest discounted items</p>
                    <div class="product-slider container">
                        @foreach ($productsDiscount as $product)
                            <div class="pe-2">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @push('script')
        <script>
            $(document).ready(function() {

                $('.product-slider').slick({
                    slidesToShow: 5,
                    slidesToScroll: 2,
                    arrows: false,
                    dots: false,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    responsive: [{
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 2
                            }
                        }
                    ]
                });

                // NEXT
                $('.btn-next').click(function() {
                    $('.product-slider').slick('slickNext');
                });

                $('.btn-prev').click(function() {
                    $('.product-slider').slick('slickPrev');
                });

            });
        </script>
        <script>
            $(document).ready(function() {

                $('.rog-product-slider').slick({
                    slidesToShow: 5,
                    slidesToScroll: 2,
                    arrows: false,
                    dots: false,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    responsive: [{
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 2
                            }
                        }
                    ]
                });

                // NEXT
                $('.btn-next').click(function() {
                    $('.rog-product-slider').slick('slickNext');
                });

                $('.btn-prev').click(function() {
                    $('.rog-product-slider').slick('slickPrev');
                });

            });
        </script>
    @endpush
