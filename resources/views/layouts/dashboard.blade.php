<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
            height: 100%;
            font-family: 'Poppins', sans-serif !important;
        }

        /* Wrapper */
        .wrapper {
            display: flex;
            height: 100vh;
            ß
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 240px;
            background: #A0DAA9;
            color: #4D4D4D;
            transition: width 0.3s;
            z-index: 1030;
        }

        .sidebar-offcanvas {
            background: #D8F8BC;
            color: #4D4D4D;
            transition: width 0.3s;
            z-index: 1030;
            min-height: 100vh;
            background: #D8F8BC;
        }

        .sidebar-offcanvas .nav-link {
            color: #4D4D4D;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-left: 5px
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .nav-text {
            transition: opacity .2s ease, width .2s ease;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Header sidebar */
        .sidebar-header {
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Nav link */
        .sidebar .nav-link {
            color: #4D4D4D;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-left: 5px
        }

        .sidebar .nav-link {
            transition:
                background-color 0.25s ease,
                color 0.25s ease,
                border-radius 0.25s ease,
                padding-left 0.25s ease;
        }

        .sidebar .nav-link:hover {
            background: #f8f9fa;
            color: #4D4D4D;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            padding-left: 20px;
            /* geser dikit biar hidup */
        }

        .nav-link-custom {
            color: #575857;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
        }

        .nav-link-custom:hover {
            background-color: #f1f1f1;
            color: #000;
        }

        .nav-link-custom.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
        }

        /* Hide text */
        .sidebar .text {
            transition: opacity 0.2s, width 0.2s;
        }

        .sidebar.collapsed .text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
        }

        /* Header text & icon */
        .sidebar-text {
            font-size: 0.9rem;
        }

        .sidebar-icon {
            display: none;
            font-size: 1.4rem;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar.collapsed .sidebar-icon {
            display: inline-block;
        }

        /* MAIN AREA */
        .main {
            margin-left: 240px;
            width: calc(100% - 240px);
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8f9fa;
            transition: margin-left 0.3s, width 0.3s;
        }

        .sidebar.collapsed~.main {
            margin-left: 56px;
            width: calc(100% - 56px);
        }

        /* TOPBAR */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            height: 56px;
            background: #fff;
            border-bottom: 1px dashed #dee2e6;
            display: flex;
            align-items: center;
            padding: 0 16px;
        }

        .main {
            margin-left: 240px;
            width: calc(100% - 240px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8f9fa;
        }

        /* CONTENT SCROLL */
        .main-content {
            flex: 1;
            padding: 16px;
        }

        .sidebar-offcanvas .nav-link.active {
            background: #f8f9fa;
            color: #4D4D4D;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            margin-left: 5px;
        }

        .sidebar .nav-link.active {
            background: #f8f9fa;
            color: #4D4D4D;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            margin-left: 5px;
        }

        .sidebar .nav-link.active i {
            color: #4D4D4D;
        }

        @media (max-width: 768px) {

            /* Sembunyikan sidebar */
            .sidebar {
                display: none !important;
            }

            /* Main full width */
            .main {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Topbar tetap normal */
            .topbar {
                left: 0;
                width: 100%;
            }

            /* Wrapper tidak pakai flex sidebar */
            .wrapper {
                display: block;
                height: auto;
            }

            /* Padding konten diperkecil biar nyaman di HP */
            .main-content {
                padding: 12px;
            }

            .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }
    </style>
</head>

<body>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header bg-green">
            <h5 class="offcanvas-title fw-bold text-">
                <i class="bi bi-cpu"></i> Dashboard Panel
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="sidebar-offcanvas nav flex-column">
                <x-sidebar />
            </ul>
        </div>
    </div>

    <body class="vh-100">
        <div class="wrapper">

            <aside id="sidebar" class="sidebar">
                <div class="sidebar-header">
                    <span class="sidebar-text fw-bold">
                        <i class="bi bi-pc-display-horizontal"></i> ZK Komputer
                    </span>
                    <span class="sidebar-icon">
                        <i class="bi bi-pc-display-horizontal"></i>
                    </span>
                </div>

                <ul class="nav flex-column mt-2">
                    <x-sidebar />
                </ul>

            </aside>

            <div class="main">
                <div class="topbar d-flex justify-content-between align-items-center py-2">
                    <!-- LEFT -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Mobile Offcanvas Button -->
                        <button class="btn btn-light btn-sm d-md-none" data-bs-toggle="offcanvas"
                            data-bs-target="#mobileSidebar">
                            <i class="bi bi-list"></i>
                        </button>

                        <!-- Desktop Toggle -->
                        <button id="toggleSidebar" class="btn btn-light btn-sm d-none d-md-inline">
                            <i class="bi bi-list"></i>
                        </button>
                        <div id="clock" class="fw-semibold text-dark small"></div>
                    </div>
                    <!-- RIGHT -->
                    <div class="d-flex align-items-center justify-content-between px-3 py-2">

                        <!-- Left: Menu -->
                        <ul class="nav align-items-center gap-3 mb-0">
                            @if (auth()->check() && auth()->user()->role === 'user')
                                <li class="nav-item">
                                    <a class="nav-link nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                        href="{{ route('admin.dashboard') }}">
                                        Beranda
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link nav-link-custom me-3 {{ request()->routeIs('courses.*') ? 'active' : '' }}"
                                        href="#">
                                        Course Saya
                                    </a>
                                </li>
                            @endif

                        </ul>

                        <!-- Right: Actions -->
                        <div class="d-flex align-items-center gap-3 border-0">
                            <!-- Notification -->
                            <div class="dropdown">
                                <button class="btn rounded-circle btn-outline-light btn-sm border-1 position-relative"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-bell text-black"></i>
                                    {{-- @if ($unreadCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadCount }}
                                    </span>
                                @endif --}}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="width: 520px">
                                    <li class="dropdown-header small">Notifications</li>

                                    {{-- @forelse ($notifications as $notif)
                                    <li class="dropdown-item small {{ !$notif->is_read ? 'fw-bold' : '' }}">
                                        <div>{{ $notif->title }}</div>
                                        <div class="text-muted small">{{ $notif->message }}</div>
                                    </li>
                                @empty
                                    <li class="dropdown-item text-center text-muted">
                                        Tidak ada notifikasi
                                    </li>
                                @endforelse --}}
                                    <li class="dropdown-item text-center text-muted">
                                        Tidak ada notifikasi
                                    </li>
                                </ul>
                            </div>
                            <!-- Profile -->
                            <div class="dropdown">
                                <button
                                    class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-1 text-black"
                                    data-bs-toggle="dropdown">

                                    <i class="bi bi-person-circle fs-6"></i>
                                    <span class="small d-none d-md-inline">
                                        {{ auth()->user()->name }}
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item small" href="{{ route('profile.edit') }}">Profile</a>
                                    </li>
                                    <li><a class="dropdown-item small" href="#">Settings</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item small text-danger">
                                                Log Out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="main-content bg-light">
                    <div class="container-fluid px-5">
                        @yield('content')
                    </div>
                </div>

                {{-- <x-footer /> --}}
            </div>

        </div>
        @stack('scripts')
    </body>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');

        // Load sidebar state saat halaman dibuka
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');

            // Simpan state ke localStorage
            localStorage.setItem(
                'sidebar-collapsed',
                sidebar.classList.contains('collapsed')
            );
        });
        const btnFullscreen = document.getElementById('btnFullscreen');

        btnFullscreen.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                // Masuk fullscreen
                document.documentElement.requestFullscreen().catch((err) => {
                    console.error(`Error attempting to enable full-screen mode: ${err.message}`);
                });
            } else {
                // Keluar fullscreen
                document.exitFullscreen();
            }
        });
    </script>
    <script>
        function updateClock() {
            const now = new Date();

            const options = {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            };

            const time = new Intl.DateTimeFormat('id-ID', options).format(now);
            const date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            });

            const clockEl = document.getElementById('clock');
            if (clockEl) {
                clockEl.innerHTML = `
            <div>${date}</div>
            <div class="text-muted">${time} WIB</div>
        `;
            }
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>

</html>
