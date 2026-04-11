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

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>

        </div>
        {{-- Latest Products --}}
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h3 class="m-0 p-0">Latest Product</h3>
                    <p class="text-muted">Discover our newest arrivals</p>
                </div>
                <div>
                    <button class="btn btn-outline-success btn-prev rounded-3 me-2">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <button class="btn btn-outline-success btn-next rounded-3 me-2">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="product-slider container">
                @foreach ($productsLatest as $product)
                    <div class="pe-2">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Shop by Categories --}}
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h3 class="m-0 p-0">Shop by Categories</h3>
                    <p class="text-muted">Pick a category and start exploring</p>
                </div>
                <div>
                    <a href="" class="text-muted">Show More <i class="bi bi-arrow-right"></i> </a>
                </div>
            </div>
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-3 mb-3">
                        <a href="" class="text-decoration-none text-dark">
                            <div class="card h-100 border-1 rounded-4 shadow-sm text-center">
                                <div class="p-3">
                                    <a href="{{ route('customer.product.index', ['category[]' => $category->id]) }}" class="text-decoration-none text-black"> <img src="{{ $category->photo_url }}" alt="{{ $category->name }}"
                                            class="img-fluid mb-3" style="max-height: 120px; object-fit: contain;">
                                        <h6 class="fw-semibold mb-1">
                                            {{ $category->name }}
                                    </a>
                                    </h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="container mt-4">
            <div class="row">
                <!-- Banner 1 -->
                <div class="col-12 col-md-6">
                    <div class="card text-black border-0" style="min-height: 200px;">
                        <div class="card-img-overlay d-flex flex-column justify-content-center p-4"
                            style="background: url('https://freshcart.codescandy.com/assets/images/banner/grocery-banner.png') center/cover no-repeat; border-radius: .5rem; min-height: 200px;">

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

                <!-- Banner 2 -->
                <div class="col-12 col-md-6">
                    <div class="card text-black border-0" style="min-height: 200px;">
                        <div class="card-img-overlay d-flex flex-column justify-content-center p-4"
                            style="background: url('https://freshcart.codescandy.com/assets/images/banner/grocery-banner-2.jpg') center/cover no-repeat; border-radius: .5rem; min-height: 200px;">

                            <h4 class="fw-bold mb-2">Steam Wallet</h4>

                            <p class="mb-3">
                                Get up to <span class="fw-bold">25%</span> off
                            </p>

                            <div>
                                <a href="#" class="btn btn-dark btn-sm">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rog-row text-white d-flex align-items-center mt-4">
            <div class="container py-3">
                <h1 class="text-danger fw-bold">Republic Of Gamers</h1>
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
                            slidesToShow: 1
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
                            slidesToShow: 1
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
