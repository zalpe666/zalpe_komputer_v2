<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-navbar />
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    {{-- Dashboard / Home --}}
                    <a href="#"
                        class="list-group-item list-group-item-action {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    {{-- Profile --}}
                    <a href="#"
                        class="list-group-item list-group-item-action {{ Request::routeIs('profile') ? 'active' : '' }}">
                        Profile
                    </a>

                    {{-- Orders / Transactions --}}
                    <a href="{{ route('customer.transaction.index') }}"
                        class="list-group-item list-group-item-action {{ Request::routeIs('customer.transaction.index') ? 'active' : '' }}">
                        Riwayat Transaksi
                    </a>

                    {{-- Wishlist / Dummy --}}
                    <a href="#"
                        class="list-group-item list-group-item-action {{ Request::routeIs('wishlist') ? 'active' : '' }}">
                        Wishlist
                    </a>

                    {{-- Settings / Dummy --}}
                    <a href="#"
                        class="list-group-item list-group-item-action {{ Request::routeIs('settings') ? 'active' : '' }}">
                        Pengaturan
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                @yield('content')
            </div>
        </div>

    </div>
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
