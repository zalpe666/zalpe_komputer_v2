<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ZK Admin Panel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #696CFF;
            --primary-bg: #E7E7FE;
            --text-dark: #434A54;
            --text-muted: #8A92A6;
            --bg-body: #F4F6F9;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 64px;
        }

        html,
        body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            margin: 0;
            overflow-x: hidden;
        }

        /* WRAPPER */
        .wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.03);
            transition: width 0.3s ease;
            z-index: 1030;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Header & RGB Animation */
        .sidebar-header {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #fff;
            overflow: hidden;
        }

        .sidebar-header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #00ffff, #0000ff, #8b00ff, #ff0000);
            background-size: 300% 100%;
            animation: rgbFlow 6s linear infinite;
        }

        @keyframes rgbFlow {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 300% 50%;
            }
        }

        .sidebar-text {
            font-size: 1.1rem;
            color: var(--text-dark);
            white-space: nowrap;
            transition: opacity 0.3s ease;
        }

        .sidebar-icon {
            display: none;
            font-size: 1.4rem;
            color: var(--primary-color);
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar.collapsed .sidebar-icon {
            display: inline-block;
        }

        /* Sidebar Navigation Items */
        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #e0e0e0;
            border-radius: 5px;
        }

        .sidebar .nav-link {
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 20px;
            margin: 4px 12px;
            border-radius: 8px;
            transition: all 0.25s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: #f8f9fa;
            color: var(--text-dark);
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-bg);
            color: var(--primary-color);
            font-weight: 600;
            border-right: 4px solid var(--primary-color);
            border-radius: 8px 0 0 8px;
            /* Menempel ke kanan */
            margin-right: 0;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
        }

        .sidebar .nav-text {
            transition: opacity 0.2s ease, width 0.2s ease;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
            margin: 4px 10px;
            border-radius: 8px;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            display: none;
        }

        .sidebar.collapsed .nav-link.active {
            border-right: none;
            border-radius: 8px;
            margin-right: 10px;
        }

        /* ================= OFFCANVAS (MOBILE) ================= */
        .offcanvas-start {
            background: #ffffff;
            border-right: none;
        }

        .offcanvas-header.bg-green {
            background: #D8F8BC;
        }

        /* ================= MAIN CONTENT ================= */
        .main {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease, width 0.3s ease;
            height: 100vh;
        }

        .sidebar.collapsed~.main {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            z-index: 1020;
        }

        .nav-link-custom {
            color: var(--text-muted);
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link-custom:hover {
            background-color: #f8f9fa;
            color: var(--text-dark);
        }

        .nav-link-custom.active {
            background-color: var(--primary-bg);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Dropdowns */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 10px 0;
        }

        .dropdown-item {
            padding: 8px 20px;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: var(--primary-bg);
            color: var(--primary-color);
        }

        /* Content Area */
        .main-content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            background-color: var(--bg-body);
        }

        /* Breadcrumb Custom */
        .custom-breadcrumb {
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .breadcrumb-link {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
        }

        .breadcrumb-link:hover {
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: var(--text-dark);
            font-weight: 600;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .sidebar {
                display: none !important;
            }

            .main {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .topbar {
                padding: 0 16px;
            }

            .main-content {
                padding: 16px;
            }

            .dropdown-menu-end {
                width: 300px !important;
            }

            /* Fix overflow notif mobile */
        }
    </style>
</head>

<body>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header bg-green">
            <h5 class="offcanvas-title fw-bold">
                <i class="bi bi-pc-display-horizontal me-2 text-primary"></i> ZK Admin Panel
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="sidebar-menu nav flex-column">
                <!-- Nantinya diisi oleh file sidebar Anda -->
                <x-sidebar />
            </ul>
        </div>
    </div>

    <!-- Layout Wrapper -->
    <div class="wrapper">

        <!-- Desktop Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <span class="sidebar-text fw-bold">
                    <i class="bi bi-pc-display-horizontal me-1"></i> ZK Admin Panel
                </span>
                <span class="sidebar-icon">
                    <i class="bi bi-pc-display-horizontal"></i>
                </span>
            </div>

            <ul class="sidebar-menu nav flex-column">
                <!-- Nantinya diisi oleh file sidebar Anda -->
                <x-sidebar />
            </ul>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="main">
            <!-- Topbar -->
            <nav class="topbar">
                <!-- Left: Toggles & Menu -->
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light btn-sm d-md-none rounded-circle border-0 shadow-sm"
                        data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <button id="toggleSidebar"
                        class="btn btn-light btn-sm d-none d-md-inline flex-shrink-0 rounded-circle border-0 shadow-sm">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <!-- Top Menu -->
                    <ul class="nav align-items-center gap-2 mb-0 d-none d-sm-flex">
                        @if (auth()->check() && auth()->user()->role === 'user')
                            <li class="nav-item">
                                <a class="nav-link nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('admin.dashboard') }}">
                                    Beranda
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-custom {{ request()->routeIs('courses.*') ? 'active' : '' }}"
                                    href="#">
                                    Course Saya
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Right: Notifications & Profile -->
                <div class="d-flex align-items-center gap-3">

                    <!-- Notification -->
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle btn-sm border-0 shadow-sm position-relative p-2"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-6"></i>
                            {{-- @if ($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                                    {{ $unreadCount }}
                                </span>
                            @endif --}}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-width: 90vw;">
                            <li class="dropdown-header fw-bold text-dark fs-6 pb-2 border-bottom">Notifikasi</li>
                            <li class="dropdown-item text-center text-muted py-4">
                                <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                                Tidak ada notifikasi
                            </li>
                        </ul>
                    </div>

                    <!-- Profile -->
                    <div class="dropdown">
                        <button
                            class="btn btn-light rounded-pill btn-sm border-0 shadow-sm d-flex align-items-center gap-2 px-3 py-2"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 text-primary"></i>
                            <span class="small fw-semibold d-none d-md-inline">
                                {{ auth()->check() ? auth()->user()->name : 'Guest User' }}
                            </span>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <i class="bi bi-gear"></i> Pengaturan
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>

            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            {{-- <x-footer /> --}}
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');

        // Load sidebar state saat halaman dibuka
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        });
    </script>
    @stack('scripts')
</body>

</html>
