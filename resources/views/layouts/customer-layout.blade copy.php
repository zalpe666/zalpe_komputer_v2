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
        <div class="row g-4">

            <div class="col-lg-3">
                
            </div>

            {{-- 🔹 CONTENT --}}
            <div class="col-lg-9">
                <div class="card border-0 rounded-4 shadow-sm min-vh-100">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        {{-- Judul Halaman --}}
                        <h4 class="fw-bold text-dark mb-0">
                            @yield('page_title')
                        </h4>

                        {{-- Tombol Menu Mobile --}}
                        <div class="d-lg-none">
                            <button class="btn btn-success rounded-3" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#customerOffcanvas" aria-controls="customerOffcanvas">
                                <i class="bi bi-list me-2"></i>
                                Menu Akun
                            </button>
                        </div>
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
