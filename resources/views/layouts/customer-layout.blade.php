<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* 🔥 SIDEBAR */
        .sidebar-card {
            border-radius: 16px;
            border: 1px solid #eee;
        }

        .sidebar-title {
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .list-group-item {
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        /* Hover lebih soft */
        .list-group-item:hover {
            background-color: #f8f9fa;
            transform: translateX(3px);
        }

        /* Active modern */
        .list-group-item.active {
            background-color: #0d6efd;
            color: #fff;
            border: none;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }

        /* Icon spacing */
        .list-group-item i {
            font-size: 16px;
        }

        /* Logout style */
        .btn-logout {
            border-radius: 10px;
            transition: 0.2s;
        }

        .btn-logout:hover {
            background-color: #ffe5e5;
        }
    </style>
</head>

<body>

    <x-navbar />

    <div class="container py-4">
        <div class="row g-4">

            {{-- 🔹 SIDEBAR --}}
            <div class="col-md-3">
                <div class="card sidebar-card shadow-sm">
                    <div class="card-body p-3">

                        <div class="list-group list-group-flush">

                            {{-- MAIN --}}
                            <small class="text-muted sidebar-title px-2 mb-1">MAIN</small>

                            <a href="{{ route('customer.dashboard.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.dashboard.index') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>

                            <a href="{{ route('customer.profile.edit') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.profile.*') ? 'active' : '' }}">
                                <i class="bi bi-person"></i>
                                Profile
                            </a>

                            {{-- TRANSAKSI --}}
                            <small class="text-muted sidebar-title px-2 mt-3 mb-1">Transaction</small>

                            <a href="{{ route('customer.transaction.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.transaction.*') ? 'active' : '' }}">
                                <i class="bi bi-receipt"></i>
                                Transaction
                            </a>

                            <a href="#"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('wishlist') ? 'active' : '' }}">
                                <i class="bi bi-heart"></i>
                                Wishlist
                            </a>

                            {{-- ACCOUNT --}}
                            <small class="text-muted sidebar-title px-2 mt-3 mb-1">ACCOUNT</small>

                            <a href="{{ route('customer.address.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.address.*') ? 'active' : '' }}">
                                <i class="bi bi-geo-alt"></i>
                                Address
                            </a>

                            <a href="{{ route('customer.dashboard.setting') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ Request::routeIs('customer.dashboard.setting') ? 'active' : '' }}">
                                <i class="bi bi-gear"></i>
                                Setting
                            </a>

                            {{-- LOGOUT --}}
                            <div class="mt-3">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        class="btn btn-light w-100 text-danger d-flex align-items-center gap-2 btn-logout">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- 🔹 CONTENT --}}
            <div class="col-md-9">
                <div class="card shadow-sm border-0 rounded-4 p-3">
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
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        </script>
    @endif

</body>

</html>
