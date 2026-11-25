@extends('layouts.app')

@section('content')
    <div class="bg-white text-black min-vh-100 pb-5">

        {{-- 1. IMMERSIVE FULL-WIDTH HERO BACKDROP --}}
        <div class="position-relative bg-dark border-bottom border-secondary border-opacity-50 overflow-hidden">
            @if ($product->images_bg || $product->image)
                <img src="{{ $product->images_bg ?: $product->image }}"
                    class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-25"
                    alt="{{ $product->name }} ">
            @endif

            <div class="container position-relative py-5">
                <div class="row align-items-end g-4 pt-3">
                    {{-- Cover / Poster Utama di Hero --}}
                    <div class="col-md-4 col-lg-3">
                        <div class="card overflow-hidden">
                            <img src="{{ $product->image ?: $product->images_bg }}" class="w-100 h-100 object-fit-cover"
                                alt="{{ $product->name }}">
                        </div>
                    </div>

                    {{-- Judul & Identitas Utama Game --}}
                    <div class="col-md-8 col-lg-9">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge bg-info text-dark fw-bold text-uppercase px-3 py-2">
                                <i data-lucide="gamepad-2" class="me-1"></i>{{ $product->type }}
                            </span>

                            @if ($product->category)
                                <span class="badge bg-secondary bg-opacity-75 text-uppercase px-3 py-2">
                                    {{ $product->category->name }}
                                </span>
                            @endif

                            @if ($product->is_digital)
                                <span class="badge border border-info text-info text-uppercase px-3 py-2">
                                    Instant Digital Delivery
                                </span>
                            @endif
                        </div>

                        <h1 class="display-5 fw-bolder text-white mb-2">
                            {{ $product->name }}
                        </h1>

                        <div class="d-flex flex-wrap align-items-center gap-3 text-secondary small">
                            @if ($product->developers)
                                <span>
                                    Developer: <strong class="text-light">{{ $product->developers }}</strong>
                                </span>
                            @endif

                            @if ($product->developers && $product->publishers)
                                <span>&bull;</span>
                            @endif

                            @if ($product->publishers)
                                <span>
                                    Publisher: <strong class="text-light">{{ $product->publishers }}</strong>
                                </span>
                            @endif

                            @if ($product->steam_app_id)
                                <span>&bull;</span>
                                <span class="font-monospace text-info">
                                    AppID: {{ $product->steam_app_id }}
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- 2. MAIN STOREFRONT AREA --}}
        <div class="container mt-4">
            <div class="row g-4">

                {{-- KOLOM KIRI (8 KOLOM): MEDIA SHOWCASE, DESKRIPSI, TRAILER --}}
                <div class="col-lg-8">

                    {{-- MEDIA VIEWER & GALLERY STRIP --}}
                    <div class="card border-0 mb-4 overflow-hidden">
                        <div class="ratio ratio-16x9 bg-black">
                            <img id="mainGameImage" src="{{ $product->image ?: $product->image }}"
                                class="w-100 h-100 object-fit-contain" alt="{{ $product->name }}">
                        </div>

                        @if (!empty($product->images) || $product->images_bg)
                            <div class="card-footer bg-white px-2">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-bold text-uppercase text-black">
                                        Media & Screenshots
                                    </span>
                                    <span class="badge text-muted bg-light border border-secondary px-2 py-1"
                                        style="font-size: 0.65rem;">
                                        Klik untuk memperbesar
                                    </span>
                                </div>

                                <div class="row g-2">
                                    @if ($product->images_bg)
                                        <div class="col-4 col-sm-3 col-md-2">
                                            <button type="button"
                                                class="btn p-0 w-100 border border-2 border-info overflow-hidden gallery-thumbnail"
                                                data-image="{{ $product->images_bg }}" aria-label="Artwork utama">
                                                <div class="ratio ratio-16x9">
                                                    <img src="{{ $product->images_bg }}"
                                                        class="w-100 h-100 object-fit-cover" alt="Artwork">
                                                </div>
                                            </button>
                                        </div>
                                    @endif

                                    @foreach ($product->images ?? [] as $img)
                                        @php
                                            $fullImage = $img['path_full'] ?? ($img['path_thumbnail'] ?? null);
                                            $thumbnail = $img['path_thumbnail'] ?? $fullImage;
                                        @endphp

                                        @if ($fullImage && $thumbnail)
                                            <div class="col-4 col-sm-3 col-md-2">
                                                <button type="button"
                                                    class="btn p-0 w-100 border border-secondary overflow-hidden gallery-thumbnail"
                                                    data-image="{{ $fullImage }}"
                                                    aria-label="Screenshot {{ $loop->iteration }}">
                                                    <div class="ratio ratio-16x9">
                                                        <img src="{{ $thumbnail }}" class="w-100 h-100 object-fit-cover"
                                                            alt="Screenshot {{ $loop->iteration }}">
                                                    </div>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- TRAILER & VIDEO SECTION --}}
                    @if (!empty($product->movies))
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="bg-success px-1 py-3 rounded"></span>
                                <h4 class="fw-bold text-black text-uppercase mb-0">
                                    Official Trailers & Clips
                                </h4>
                            </div>

                            <div class="row g-3">
                                @foreach ($product->movies as $movie)
                                    <div class="col-md-6">
                                        <div class="card text-black h-100 overflow-hidden border-0">
                                            <div class="position-relative ratio ratio-16x9 bg-black">
                                                <img src="{{ $movie['thumbnail'] ?? '' }}"
                                                    class="w-100 h-100 object-fit-cover opacity-75"
                                                    alt="{{ $movie['name'] ?? 'Trailer' }}">
                                            </div>

                                            <div class="card-body d-flex flex-column justify-content-between">
                                                <div class="mb-3">
                                                    @if (!empty($movie['highlight']))
                                                        <span class="badge bg-danger text-white text-uppercase mb-2">
                                                            Featured Trailer
                                                        </span>
                                                    @endif

                                                    <h6 class="fw-bold text-black mb-0">
                                                        {{ $movie['name'] ?? 'Gameplay Trailer' }}
                                                    </h6>
                                                </div>

                                                @if (!empty($movie['hls_h264']))
                                                    <a href="{{ $movie['hls_h264'] }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="btn btn-outline-success btn-sm fw-bold text-uppercase w-100">
                                                        <i data-lucide="play-circle" class="me-1"></i>Play
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- DESKRIPSI GAME --}}


                    <div class="">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-success px-1 py-3 rounded"></span>
                            <h4 class="fw-bold text-black text-uppercase">
                                About This Game
                            </h4>
                        </div>

                        <div class="card bg-white text-black border-0">
                            <div class="card-body p-4">

                                <div id="gameDescription"
                                    style="max-height: 550px; overflow: hidden; transition: max-height 0.4s ease;">
                                    <div class="lh-lg">
                                        @if ($product->description)
                                            {!! $product->description !!}
                                        @else
                                            <p class="text-secondary fst-italic mb-0">
                                                Informasi deskripsi untuk judul game ini belum tersedia.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div id="descriptionButtonWrapper" class="text-center mt-3 d-none ">
                                    <button type="button" id="toggleDescription"
                                        class="btn btn-outline-dark btn-sm rounded-pill px-4 shadow">
                                        <span id="descriptionButtonText">Show More</span>
                                        <i data-lucide="chevron-down" id="descriptionButtonIcon" class="ms-1"
                                            style="width: 16px; height: 16px;"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (4 KOLOM): STICKY ACTION SIDEBAR & SPESIFIKASI --}}
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 85px; z-index: 10;">

                        {{-- BOX HARGA & CHECKOUT --}}
                        <div class="card mb-4 overflow-hidden">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-uppercase text-black small">
                                    Standar Edition
                                </span>
                                <span class="badge bg-secondary font-monospace">
                                    #{{ $product->id }}
                                </span>
                            </div>

                            <div class="card-body p-4">
                                {{-- Blok Harga --}}
                                <div class=rounded-3 p-3 border border-secondary border-opacity-50 mb-4">
                                    @if ($product->discount > 0)
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-danger fs-6 fw-bold px-2 py-1">
                                                HEMAT {{ $product->discount }}%
                                            </span>
                                            <span class="text-secondary text-decoration-line-through">
                                                Rp{{ number_format($product->default_price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-baseline justify-content-between">
                                        <span class="text-secondary small text-uppercase">Total Harga</span>
                                        <h2 class="fw-bolder text-success mb-0">
                                            Rp{{ number_format($product->final_price, 0, ',', '.') }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 mt-4">

                                    @auth
                                        @if ($isOwned)
                                            <button type="button"
                                                class="btn btn-secondary btn-lg fw-bold text-uppercase py-3" disabled>
                                                <i data-lucide="check-circle" class="me-2"></i>
                                                Already Owned
                                            </button>
                                        @else
                                            <a href="{{ route('customer.checkout.now.index', $product->id) }}"
                                                class="btn btn-outline-success btn-lg fw-bold text-uppercase py-3">
                                                <i data-lucide="gamepad-2" class="me-2"></i>
                                                Buy Now
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="btn btn-outline-success btn-lg fw-bold text-uppercase py-3">
                                            <i data-lucide="log-in" class="me-2"></i>
                                            Login to Buy
                                        </a>
                                    @endauth

                                </div>
                            </div>

                            {{-- Highlight Keunggulan --}}
                            <div class="card-footer bg-white border-top border-secondary border-opacity-50 p-3">
                                <div class="row g-2 text-center small text-secondary">
                                    <div class="col-6 border-end border-secondary border-opacity-50">
                                        <i data-lucide="shield-check" class="text-success d-block mx-auto mb-1"></i>
                                        100% Lisensi Resmi
                                    </div>
                                    <div class="col-6">
                                        <i data-lucide="bolt" class="text-success d-block mx-auto mb-1"></i>
                                        Aktivasi Instan
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL INFORMASI TEKNIS --}}
                        <div class="card text-black">
                            <div class="card-header py-3 bg-white">
                                <h6 class="fw-bold text-uppercase text-black mb-0">
                                    Informasi Produk
                                </h6>
                            </div>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between py-3">
                                    <span class="text-secondary">Genre / Kategori</span>
                                    <span class="fw-semibold text-end">{{ $product->category->name ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-3">
                                    <span class="text-secondary">Developer</span>
                                    <span class="fw-semibold text-end">{{ $product->developers ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-3">
                                    <span class="text-secondary">Publisher</span>
                                    <span class="fw-semibold text-end">{{ $product->publishers ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-3">
                                    <span class="text-secondary">Platform / Tipe</span>
                                    <span class="badge bg-secondary">{{ $product->type }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between py-3">
                                    <span class="text-secondary">Steam App ID</span>
                                    <span class="font-monospace text-info">{{ $product->steam_app_id ?? '-' }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="alert bg-info d-flex align-items-start gap-3 my-2" role="alert">
                            <i data-lucide="headphones" class="text-black flex-shrink-0 mt-1"></i>
                            <div class="small">
                                <strong class="d-block text-black mb-1">Pusat Bantuan Gamer</strong>
                                <span class="text-black">Mengalami kendala saat aktivasi kode atau transaksi? Hubungi
                                    tim support kami kapan saja.</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis nostrum architecto doloribus
                        corrupti, natus in incidunt quis delectus! Numquam dolorum optio obcaecati enim fugit adipisci
                        doloribus sapiente? Sint, iusto ab!</p>
                </div>

            </div>
        </div>

    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

    <script>
        document.addEventListener('click', function(event) {
            const thumbnail = event.target.closest('.gallery-thumbnail');
            if (!thumbnail) return;

            const imageUrl = thumbnail.dataset.image;
            const mainImage = document.getElementById('mainGameImage');
            if (!imageUrl || !mainImage) return;

            mainImage.src = imageUrl;

            document.querySelectorAll('.gallery-thumbnail').forEach(function(item) {
                item.classList.remove('border-2', 'border-info');
                item.classList.add('border-secondary');
            });

            thumbnail.classList.remove('border-secondary');
            thumbnail.classList.add('border-2', 'border-info');
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const description = document.getElementById('gameDescription');
            const buttonWrapper = document.getElementById('descriptionButtonWrapper');
            const toggleButton = document.getElementById('toggleDescription');
            const buttonText = document.getElementById('descriptionButtonText');
            const buttonIcon = document.getElementById('descriptionButtonIcon');

            if (!description || !toggleButton) return;

            const maxHeight = 300;

            // Cek apakah deskripsi melebihi 300px
            if (description.scrollHeight > maxHeight) {
                buttonWrapper.classList.remove('d-none');
            } else {
                description.style.maxHeight = 'none';
            }

            let expanded = false;

            toggleButton.addEventListener('click', function() {
                expanded = !expanded;

                if (expanded) {
                    description.style.maxHeight = description.scrollHeight + 'px';
                    buttonText.textContent = 'Show Less';
                    buttonIcon.setAttribute('data-lucide', 'chevron-up');
                } else {
                    description.style.maxHeight = maxHeight + 'px';
                    buttonText.textContent = 'Show More';
                    buttonIcon.setAttribute('data-lucide', 'chevron-down');
                }

                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        });
    </script>
@endsection
