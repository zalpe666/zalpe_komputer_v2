<style>
    .profile-toggle {
        transition: all 0.25s ease;
    }

    .profile-toggle:hover {
        background-color: #f8f9fa !important;
        border-color: #198754 !important;
        transform: translateY(-1px);
    }

    .dropdown-item {
        font-size: 0.9rem;
        color: #4b5563;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f0fdf4;
        color: #198754;
        transform: translateX(5px);
    }

    .dropdown-item i {
        font-size: 1.1rem;
    }

    .nav-link.dropdown-toggle::after {
        display: none;
    }

    .dropdown-menu {
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .bg-light-success {
        background: #e6f4ea;
    }

    .notification-dropdown {
        width: 350px;
        max-width: calc(100vw - 30px);
        /* Typo diperbaiki */
        border-radius: 12px;
    }

    .notification-body {
        max-height: 400px;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    @media (max-width: 991.98px) {
        .navbar-nav .nav-link {
            padding: 10px 0;
        }

        .navbar-nav .dropdown-menu {
            margin-top: 0 !important;
        }

        .notification-dropdown {
            width: min(350px, calc(100vw - 30px));
            /* Typo diperbaiki */
        }

        .profile-toggle {
            border: 0 !important;
            box-shadow: none !important;
            padding: 8px 0 !important;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top pt-3">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('customer.home.index') }}">
            <span class="fw-bold text-success">Zalpe Komputer</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            {{-- LEFT MENU --}}
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
                                <div class="col-12">
                                    <a class="dropdown-item rounded"
                                        href="{{ route('customer.product.index', ['category' => [$category->id]]) }}">
                                        Semua Kategori
                                    </a>
                                </div>
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

                {{-- WALLET --}}
                <li class="nav-item">
                    <a class="nav-link fw-medium {{ request()->routeIs('customer.wallet.*') ? 'active text-success fw-bold' : '' }}"
                        href="{{ route('customer.wallet.index') }}">
                        Wallet
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link fw-medium" data-bs-toggle="modal" data-bs-target="#searchModal">
                        Search
                    </a>
                </li>
                @endauth

            </ul>


            {{-- RIGHT MENU --}}
            <ul class="navbar-nav flex-column flex-lg-row align-items-lg-center gap-lg-2">

                @auth
                {{-- KERANJANG --}}
                <li class="nav-item">
                    <a href="{{ route('customer.cart.index') }}"
                        class="nav-link fw-medium d-flex align-items-center gap-2">

                        <i class="bi bi-cart3 d-none d-lg-inline"></i>

                        <span>Keranjang</span>

                        @if (isset($cartCount) && $cartCount > 0)
                        <span class="badge bg-danger rounded-pill">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </li>

                {{-- NOTIFIKASI --}}
                <li class="nav-item dropdown">
                    <a href="#" id="notifDropdown"
                        class="nav-link dropdown-toggle fw-medium d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown" aria-expanded="false">

                        <i class="bi bi-bell d-none d-lg-inline"></i>

                        <span>Notifikasi</span>

                        @if ($unreadCount > 0)
                        <span id="unread-badge" class="badge bg-danger rounded-pill">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end p-0 shadow border-0 mt-3 notification-dropdown">

                        <div class="px-3 py-2 bg-light border-bottom d-flex justify-content-between">
                            <span class="fw-bold small">Notifikasi</span>

                            @if ($unreadCount > 0)
                            <button id="markReadAll" type="button" class="btn btn-sm text-success p-0">
                                Tandai semua
                            </button>
                            @endif
                        </div>

                        <div class="notification-body">
                            @forelse ($notifications as $notif)
                            @php
                            $icon = match ($notif->type) {
                            'Shopping' => 'bi-bag-check-fill text-success',
                            'Payment' => 'bi-credit-card-fill text-primary',
                            default => 'bi-info-circle-fill text-secondary',
                            };
                            @endphp

                            <a href="{{ $notif->link ?? '#' }}"
                                class="dropdown-item p-3 border-bottom d-flex align-items-start gap-3 notification-item {{ $notif->is_read ? 'opacity-75' : 'bg-light-success' }}">

                                <i class="bi {{ $icon }} fs-4"></i>

                                <div class="flex-grow-1">
                                    <p class="mb-0 fw-semibold small text-dark">
                                        {{ $notif->title }}
                                    </p>

                                    <p class="mb-1 text-muted small text-wrap" style="line-height: 1.4;">
                                        {{ $notif->message }}
                                    </p>

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
                            <a href="#" class="small text-success fw-bold">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </li>

                {{-- PROFIL --}}
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link d-flex align-items-center gap-3 bg-white border rounded-pill px-2 py-1 shadow-sm profile-toggle"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 32px; height: 32px;">

                            <span class="fw-bold small">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>

                        <div class="d-none d-lg-block me-2">
                            <p class="fw-bold text-dark mb-0" style="font-size: 0.85rem; line-height: 1.2;">
                                {{ auth()->user()->name }}
                            </p>
                        </div>

                        <i class="bi bi-chevron-down small text-muted d-none d-lg-inline me-1"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3 p-2"
                        style="min-width: 240px;">

                        <li class="px-3 py-3 border-bottom mb-2 d-lg-none">
                            <p class="fw-bold mb-0">
                                {{ auth()->user()->name }}
                            </p>
                            <small class="text-muted">
                                {{ auth()->user()->email }}
                            </small>
                        </li>

                        <li>
                            <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.transaction.index') }}">
                                <i class="bi bi-bag-check me-2 text-success"></i>
                                Pesanan Saya
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.profile.edit') }}">
                                <i class="bi bi-person-gear me-2 text-success"></i>
                                Pengaturan Profil
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.address.index') }}">
                                <i class="bi bi-geo-alt me-2 text-success"></i>
                                Daftar Alamat
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider mx-2 opacity-50">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger fw-medium">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endauth

                @guest
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">
                        Masuk
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('register') }}" class="btn btn-success rounded-pill">
                        Daftar
                    </a>
                </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>

{{-- SEARCH MODAL DIPINDAHKAN KE LUAR TAG </nav> --}}
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="searchModalLabel">
                    <i class="bi bi-search text-success me-2"></i>
                    Cari Produk
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body p-4">

                <form method="GET" action="{{ route('customer.product.index') }}">

                    <div class="input-group">
                        <input type="search" id="productSearch" name="search"
                            class="form-control bg-light border-0 rounded-start-pill ps-4"
                            placeholder="Cari laptop, VGA, RAM..." value="{{ request('search') }}"
                            autocomplete="off" required>

                        <button type="submit" class="btn btn-success rounded-end-pill px-4">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>
                    </div>

                    <div class="mt-3 text-muted small">
                        Masukkan nama produk yang ingin kamu cari.
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const markBtn = document.getElementById('markReadAll');

        if (markBtn) {
            markBtn.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                markBtn.disabled = true;

                fetch("{{ route('customer.notifications.readAll') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal memperbarui notifikasi');
                        }

                        const badge = document.getElementById('unread-badge');

                        if (badge) {
                            badge.remove();
                        }

                        document.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.remove('bg-light-success', 'opacity-75');
                            item.classList.add('opacity-75');
                        });

                        markBtn.remove();
                    })
                    .catch(error => {
                        console.error(error);
                        markBtn.disabled = false;
                    });
            });
        }

        // Gabungkan listener searchModal di sini agar lebih rapi
        const searchModal = document.getElementById('searchModal');
        if (searchModal) {
            searchModal.addEventListener('shown.bs.modal', function() {
                document.getElementById('productSearch').focus();
            });
        }
    });
</script>
@endpush