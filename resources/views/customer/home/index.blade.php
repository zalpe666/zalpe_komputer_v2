@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')


    <div class="container">

        {{-- BANNER --}}
        <div id="carouselExampleIndicators" class="carousel slide mb-4">
            <div class="carousel-indicators">
                @foreach ($banners as $key => $banner)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}">
                    </button>
                @endforeach
            </div>

            <div class="carousel-inner rounded-4 overflow-hidden">
                @foreach ($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image }}" class="d-block w-100" style="height: 350px; object-fit: cover;">
                    </div>
                @endforeach
            </div>

        </div>

        {{-- HEADER SLIDER --}}
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h3 class="m-0 p-0">Latest Product</h3>
                    <p class="text-muted">Discover our newest arrivals</p>
                </div>
                <div>
                    <button class="btn btn-outline-success btn-prev rounded-4 me-2">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <button class="btn btn-outline-success btn-next rounded-4 me-2">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- SLIDER --}}
            <div class="product-slider">
                @foreach ($productsLatest as $product)
                    <div class="px-2">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
        {{-- <div class="product-slider">
            @foreach ($productsDiscount as $product)
                <div class="px-2">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div> --}}

    </div>

@endsection
@push('script')
    <script>
        $(document).ready(function() {

            $('.product-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 2,
                arrows: false, // ❗ penting: matikan default arrow
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
@endpush
