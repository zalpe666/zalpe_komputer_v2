<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Zalpe Komputer</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        /* 🔹 SIDEBAR CUSTOM */
        .sidebar-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            position: sticky;
            top: 100px;
        }

        .user-mini-profile {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            padding: 25px 20px;
            color: white;
            text-align: center;
        }

        .avatar-circle {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 24px;
            font-weight: bold;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #adb5bd;
            padding: 10px 15px;
            margin-top: 15px;
        }

        .list-group-item {
            border: none;
            padding: 12px 20px;
            margin: 2px 10px;
            border-radius: 12px !important;
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            transition: all 0.3s ease;
        }

        .list-group-item i {
            font-size: 18px;
            width: 25px;
        }

        .list-group-item:hover {
            background-color: #f0fdf4;
            color: #198754;
            transform: translateX(5px);
        }

        .list-group-item.active {
            background-color: #198754 !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.25);
        }

        /* 🔹 CONTENT CARD */
        .main-content-card {
            border: none;
            border-radius: 20px;
            min-height: 80vh;
        }

        .btn-logout {
            margin: 20px 10px;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            background-color: #fff5f5;
            color: #dc3545;
            border: 1px solid #fee2e2;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>

    <x-navbar />

    <div class="container py-5">
        <div class="row g-4">

            {{-- 🔹 SIDEBAR --}}
            <div class="col-lg-3">
                <div class="card sidebar-card shadow-sm">
                    {{-- Header Profile --}}
                    <div class="user-mini-profile">
                        <div class="avatar-circle">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                        <small class="opacity-75" style="font-size: 11px;">{{ auth()->user()->email }}</small>
                    </div>

                    <div class="card-body p-2">
                        <div class="list-group list-group-flush">

                            <div class="sidebar-title">Menu Utama</div>
                            <a href="{{ route('customer.dashboard.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.dashboard.index') ? 'active' : '' }}">
                                <i class="bi bi-grid-1x2-fill"></i> Dashboard
                            </a>
                            <a href="{{ route('customer.profile.edit') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.profile.*') ? 'active' : '' }}">
                                <i class="bi bi-person-fill"></i> Profil Saya
                            </a>

                            <div class="sidebar-title">Aktivitas Belanja</div>
                            <a href="{{ route('customer.transaction.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.transaction.*') ? 'active' : '' }}">
                                <i class="bi bi-bag-check-fill"></i> Riwayat Pesanan
                            </a>
                            <a href="{{ route('customer.rating.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.rating.*') ? 'active' : '' }}">
                                <i class="bi bi-star-fill"></i> Beri Ulasan
                            </a>
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                                <i class="bi bi-heart-fill"></i> Wishlist
                            </a>

                            <div class="sidebar-title">Pengaturan</div>
                            <a href="{{ route('customer.address.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.address.*') ? 'active' : '' }}">
                                <i class="bi bi-geo-alt-fill"></i> Daftar Alamat
                            </a>
                            <a href="{{ route('customer.dashboard.setting') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.dashboard.setting') ? 'active' : '' }}">
                                <i class="bi bi-shield-lock-fill"></i> Keamanan
                            </a>

                            {{-- LOGOUT --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-logout d-flex align-items-center justify-content-center gap-2" style="width: 90%">
                                    <i class="bi bi-box-arrow-right"></i> Keluar Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 🔹 CONTENT --}}
            <div class="col-lg-9">
                <div class="card main-content-card shadow-sm border-0 p-4">
                    {{-- Judul Halaman Dinamis --}}
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark">@yield('page_title')</h4>
                        <hr class="opacity-25">
                    </div>
                    
                    @yield('content')
                </div>
            </div>

        </div>
    </div>

    {{-- 🔥 SCRIPT --}}
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