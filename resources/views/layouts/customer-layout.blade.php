<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Zalpe Komputer</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <x-navbar />

    <div class="container">
        <div class="row g-4 align-items-stretch my-sm-0">
            {{-- Samakan breakpoint ke lg agar sinkron dengan offcanvas-lg & d-lg-none --}}
            <div class="col-lg-3">
                <div class="offcanvas-lg offcanvas-start h-100" tabindex="-1" id="customerOffcanvas"
                    aria-labelledby="customerOffcanvasLabel">

                    {{-- Header Offcanvas (Hanya muncul di layar < 992px) --}}
                    <div class="offcanvas-header border-bottom">
                        <h5 class="offcanvas-title fw-bold" id="customerOffcanvasLabel">Menu Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            data-bs-target="#customerOffcanvas" aria-label="Close"></button>
                    </div>

                    {{-- Hapus h-100 & overflow-hidden pada .card agar offcanvas-body bisa scroll bebas di mobile --}}
                    <div class="offcanvas-body p-0">
                        <div class="card border-0 rounded-0 rounded-lg-4 shadow-sm w-100">

                            {{-- Header Profile --}}
                            <div class="bg-success bg-gradient p-4 text-white text-center rounded-top-4">
                                <div class="rounded-circle bg-white bg-opacity-25 border border-2 border-white border-opacity-50 d-flex align-items-center justify-content-center mx-auto mb-3 fs-4 fw-bold"
                                    style="width: 60px; height: 60px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                                <small class="opacity-75 d-block text-truncate" style="font-size: 11px;">
                                    {{ auth()->user()->email }}
                                </small>
                            </div>

                            {{-- Menu Navigasi --}}
                            <div class="card-body p-3">
                                <div class="nav nav-pills flex-column gap-1">

                                    <small class="text-uppercase fw-bold text-secondary px-3 mt-2 mb-1"
                                        style="font-size: 11px; letter-spacing: 1px;">
                                        Menu Utama
                                    </small>
                                    <a href="{{ route('customer.dashboard.index') }}"
                                        class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium {{ Request::routeIs('customer.dashboard.index') ? 'active bg-success text-white shadow-sm' : 'text-dark' }}">
                                        <i data-lucide="layout-grid" style="width: 18px; height: 18px;"></i> Dashboard
                                    </a>
                                    <a href="{{ route('customer.profile.edit') }}"
                                        class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium {{ Request::routeIs('customer.profile.*') ? 'active bg-success text-white shadow-sm' : 'text-dark' }}">
                                        <i data-lucide="user" style="width: 18px; height: 18px;"></i> Profil Saya
                                    </a>

                                    <small class="text-uppercase fw-bold text-secondary px-3 mt-3 mb-1"
                                        style="font-size: 11px; letter-spacing: 1px;">
                                        Aktivitas Belanja
                                    </small>
                                    <a href="{{ route('customer.transaction.index') }}"
                                        class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium {{ Request::routeIs('customer.transaction.*') ? 'active bg-success text-white shadow-sm' : 'text-dark' }}">
                                        <i data-lucide="shopping-bag" style="width: 18px; height: 18px;"></i> Riwayat
                                        Pesanan
                                    </a>
                                    <a href="{{ route('customer.rating.index') }}"
                                        class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium {{ Request::routeIs('customer.rating.*') ? 'active bg-success text-white shadow-sm' : 'text-dark' }}">
                                        <i data-lucide="star" style="width: 18px; height: 18px;"></i> Beri Ulasan
                                    </a>
                                    <a href="#"
                                        class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium text-dark">
                                        <i data-lucide="heart" style="width: 18px; height: 18px;"></i> Wishlist
                                    </a>

                                    <small class="text-uppercase fw-bold text-secondary px-3 mt-3 mb-1"
                                        style="font-size: 11px; letter-spacing: 1px;">
                                        Pengaturan
                                    </small>
                                    <a href="{{ route('customer.address.index') }}"
                                        class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium {{ Request::routeIs('customer.address.*') ? 'active bg-success text-white shadow-sm' : 'text-dark' }}">
                                        <i data-lucide="map-pin" style="width: 18px; height: 18px;"></i> Daftar Alamat
                                    </a>
                                    <a href="{{ route('customer.dashboard.setting') }}"
                                        class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium {{ Request::routeIs('customer.dashboard.setting') ? 'active bg-success text-white shadow-sm' : 'text-dark' }}">
                                        <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i> Keamanan
                                    </a>

                                    {{-- LOGOUT --}}
                                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-outline-danger w-100 rounded-3 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                                            <i data-lucide="log-out" style="width: 18px; height: 18px;"></i> Keluar Akun
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Samakan ke col-lg-9 --}}
            <div class="col-lg-9">
                <div class="card border-0 rounded-4 shadow-sm min-vh-100 h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Judul Halaman --}}
                        <h4 class="fw-bold text-dark mb-0">
                            @yield('page_title')
                        </h4>

                        {{-- Tombol Menu Mobile --}}
                        <div class="d-lg-none">
                            <button class="btn btn-light rounded-3" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#customerOffcanvas" aria-controls="customerOffcanvas">
                                <i data-lucide="panel-right-open"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stack('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        </script>
    @endif

</body>

</html>
