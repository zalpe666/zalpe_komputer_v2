<style>
    /* Efek hover pada tombol profil */
    .profile-toggle:hover {
        background-color: #f8f9fa !important;
        border-color: #198754 !important;
        transform: translateY(-1px);
    }

    /* Styling item dropdown */
    .dropdown-item {
        font-size: 0.9rem;
        color: #4b5563;
        transition: all 0.2s;
    }

    /* .dropdown-item:hover {
        background-color: #f0fdf4;
        /* Hijau sangat muda */
        color: #198754;
        transform: translateX(5px);
    } */

    .dropdown-item i {
        font-size: 1.1rem;
    }

    /* Menghilangkan panah default bootstrap jika ingin pakai chevron custom */
    .nav-link.dropdown-toggle::after {
        display: none;
    }
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        {{-- LOGO --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('customer.home.index') }}">
            <span class="fw-bold text-success">Zalpe Komputer</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            {{-- MENU --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                @auth
                    <li class="nav-item">
                        <a class="nav-link fw-medium {{ request()->routeIs('customer.product.*') ? 'active text-success fw-bold' : '' }}"
                            href="{{ route('customer.product.index') }}">
                            Produk
                        </a>
                    </li>

                    {{-- KATEGORI --}}
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" data-bs-toggle="dropdown">
                            Kategori
                        </a>
                        <div class="dropdown-menu w-100 shadow border-0 mt-0">
                            <div class="container py-3">
                                <div class="row g-2">
                                    @foreach ($categories as $category)
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <a class="dropdown-item rounded"
                                                href="{{ route('customer.product.index', ['category' => [$category->id]]) }}">
                                                {{ $category->name }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- BRAND --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" data-bs-toggle="dropdown">
                            Brand
                        </a>
                        <ul class="dropdown-menu shadow border-0">
                            @foreach ($brands as $brand)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('customer.product.index', ['brand' => [$brand->id]]) }}">
                                        {{ $brand->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endauth
            </ul>

            {{-- SEARCH --}}
            @auth
                <form class="d-flex mx-lg-4 my-3 my-lg-0 flex-grow-1 justify-content-center" style="max-width: 500px;"
                    method="GET" action="{{ route('customer.product.index') }}">
                    <div class="input-group">
                        <input class="form-control bg-light border-0 rounded-start-pill ps-4" type="search" name="search"
                            placeholder="Cari laptop, VGA, RAM...">
                        <button class="btn btn-success rounded-end-pill px-4">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            @endauth

            {{-- RIGHT MENU --}}
            <ul class="navbar-nav align-items-lg-center gap-2">

                @auth
                    {{-- CART --}}
                    <li class="nav-item">
                        <a href="{{ route('customer.cart.index') }}"
                            class="btn btn-light rounded-circle position-relative shadow-sm d-flex align-items-center justify-content-center"
                            style="width:42px;height:42px;">
                            <i class="bi bi-cart3"></i>

                            @if (isset($cartCount) && $cartCount > 0)
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle small">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- NOTIF --}}
                    <li class="nav-item dropdown">
                        <button id="notifDropdown"
                            class="btn btn-light rounded-circle position-relative shadow-sm d-flex align-items-center justify-content-center"
                            data-bs-toggle="dropdown" style="width:42px;height:42px;">
                            <i class="bi bi-bell"></i>

                            @if ($unreadCount > 0)
                                <span id="unread-badge"
                                    class="badge bg-danger position-absolute top-0 start-100 translate-middle small">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <div class="dropdown-menu dropdown-menu-end p-0 shadow border-0 mt-3"
                            style="width:350px;border-radius:12px;">

                            {{-- HEADER --}}
                            <div class="px-3 py-2 bg-light border-bottom d-flex justify-content-between">
                                <span class="fw-bold small">Notifikasi</span>

                                @if ($unreadCount > 0)
                                    <button id="markReadAll" class="btn btn-sm text-success p-0">
                                        Tandai semua
                                    </button>
                                @endif
                            </div>

                            {{-- BODY --}}
                            <div class="notification-body" style="max-height: 400px;">
                                @forelse($notifications as $notif)
                                    <a href="{{ $notif->link ?? '#' }}"
                                        class="dropdown-item p-3 border-bottom d-flex align-items-start gap-3 notification-item {{ $notif->is_read ? 'opacity-75' : 'bg-light-success' }}">
                                        <div class="flex-shrink-0">
                                            @php
                                                $icon = match ($notif->type) {
                                                    'Shopping' => 'bi-bag-check-fill text-success',
                                                    'Payment' => 'bi-credit-card-fill text-primary',
                                                    default => 'bi-info-circle-fill text-secondary',
                                                };
                                            @endphp
                                            <i class="bi {{ $icon }} fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0 fw-semibold small text-wrap text-dark">{{ $notif->title }}</p>
                                            <p class="mb-1 text-muted small text-wrap" style="line-height: 1.4;">
                                                {{ $notif->message }}</p>
                                            <small class="text-secondary" style="font-size: 0.7rem;">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        Tidak ada notifikasi
                                    </div>
                                @endforelse
                            </div>

                            <div class="text-center py-2 border-top">
                                <a href="#" class="small text-success fw-bold">Lihat Semua</a>
                            </div>
                        </div>
                    </li>

                    {{-- USER --}}
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link d-flex align-items-center gap-3 bg-white border rounded-pill px-2 py-1 shadow-sm profile-toggle"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="transition: all 0.3s ease;">

                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 32px; height: 32px;">
                                {{-- Mengambil inisial nama depan --}}
                                <span class="fw-bold small">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>

                            <div class="d-none d-lg-block me-2">
                                <p class="fw-bold text-dark mb-0" style="font-size: 0.85rem; line-height: 1.2;">
                                    {{ auth()->user()->name }}</p>
                            </div>

                            <i class="bi bi-chevron-down small text-muted d-none d-lg-inline me-1"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3 p-2"
                            style="min-width: 240px;">
                            {{-- Header Info Singkat --}}
                            <li class="px-3 py-3 border-bottom mb-2 d-lg-none">
                                <p class="fw-bold mb-0">{{ auth()->user()->name }}</p>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </li>

                            <li>
                                <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.transaction.index') }}">
                                    <i class="bi bi-bag-check me-2 text-success"></i> Pesanan Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.profile.edit') }}">
                                    <i class="bi bi-person-gear me-2 text-success"></i> Pengaturan Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.address.index') }}">
                                    <i class="bi bi-geo-alt me-2 text-success"></i> Daftar Alamat
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider mx-2 opacity-50">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 py-2 text-danger fw-medium">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <li><a href="{{ route('login') }}" class="nav-link">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-success rounded-pill">Daftar</a></li>
                @endguest

            </ul>
        </div>
    </div>
</nav>

{{-- STYLE --}}
<style>
    .dropdown-menu {
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all .25s ease;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .bg-light-success {
        background: #e6f4ea;
    }

    .notification-body {
        max-height: 400px;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
</style>

{{-- SCRIPT --}}
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const markBtn = document.getElementById("markReadAll");

            if (markBtn) {
                markBtn.addEventListener("click", function() {

                    fetch("{{ route('customer.notifications.readAll') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(() => {
                            const badge = document.getElementById("unread-badge");
                            if (badge) badge.remove();

                            document.querySelectorAll(".notification-item")
                                .forEach(el => el.classList.remove("bg-light-success"));
                        })
                        .catch(err => console.error(err));

                });
            }

        });
    </script>
@endpush
