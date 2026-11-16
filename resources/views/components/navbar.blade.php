<nav class="navbar navbar-expand-lg bg-white sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('customer.home.index') }}">
            <span class="fw-bold text-success"><i data-lucide="monitor-pc"></i> Zalpe Komputer</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            @auth
                {{-- Menu Kiri: Products, Categories, Brand, Profile --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- Products --}}
                    <li class="nav-item">
                        <a class="nav-link fw-medium {{ request()->routeIs('customer.product.*') ? 'active text-success fw-bold' : '' }}"
                            href="{{ route('customer.product.index') }}">
                            Products
                        </a>
                    </li>

                    {{-- Categories --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link fw-medium" href="#" data-bs-toggle="dropdown">
                            Categories
                            <i data-lucide="chevron-down" class="icon-sm"></i>
                        </a>

                        <div class="dropdown-menu p-3" style="min-width: 450px;">
                            <div class="row g-2">
                                @foreach ($categories as $category)
                                    <div class="col-4">
                                        <a class="dropdown-item rounded-3"
                                            href="{{ route('customer.product.index', ['category' => [$category->id]]) }}">
                                            <i data-lucide="{{ $category->icon }}" class="icon-sm me-1"></i>
                                            {{ $category->name }}
                                        </a>
                                    </div>
                                @endforeach

                                <div class="col-12">
                                    <a class="dropdown-item rounded-3" href="{{ route('customer.product.index') }}">
                                        <i data-lucide="layout-grid" class="icon-sm me-2"></i>
                                        All Categories
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- Brand --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                            Brand <i data-lucide="chevron-down" class="icon-sm"></i>
                        </a>

                        <div class="dropdown-menu p-3" style="min-width: 450px;">
                            <div class="row g-2">
                                @foreach ($brands as $brand)
                                    <div class="col-4">
                                        <a class="dropdown-item"
                                            href="{{ route('customer.product.index', ['brand' => [$brand->id]]) }}">
                                            {{ $brand->name }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-medium {{ request()->routeIs('customer.wallet.*') ? 'active text-success fw-bold' : '' }}"
                            href="{{ route('customer.wallet.index') }}">
                            Wallet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium {{ request()->routeIs('customer.wallet.*') ? 'active text-success fw-bold' : 'text-danger' }}"
                            href="{{ route('customer.wallet.index') }}">
                            Today Promo
                        </a>
                    </li>

                </ul>


                <ul class="navbar-nav me-lg-3 mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item me-lg-2">
                        <a class="nav-link d-flex align-items-center gap-2" href="{{ route('customer.cart.index') }}">
                            {{-- Tampilan Desktop: Ikon Keranjang + Badge Angka Merah --}}
                            <div class="position-relative d-none d-sm-inline-flex align-items-center">
                                <i data-lucide="shopping-cart" class="icon-lg"></i>

                                @if ($cartCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"
                                        style="font-size: 0.65rem; padding: 0.25em 0.45em;">
                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                        <span class="visually-hidden">item di keranjang</span>
                                    </span>
                                @endif
                            </div>

                            {{-- Tampilan Mobile: Teks + Badge Angka Merah --}}
                            <span class="d-inline d-sm-none">
                                Keranjang
                                @if ($cartCount > 0)
                                    <span class="badge rounded-pill bg-danger ms-1">{{ $cartCount }}</span>
                                @endif
                            </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown me-lg-2">
                        <a class="nav-link d-flex align-items-center gap-1" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="position-relative d-none d-sm-inline-flex align-items-center">
                                <i data-lucide="bell" class="icon-lg"></i>

                                @if ($notifications->count() > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"
                                        style="font-size: 0.65rem; padding: 0.25em 0.45em;">
                                        {{ $notifications->count() > 99 ? '99+' : $notifications->count() }}
                                        <span class="visually-hidden">notifikasi belum dibaca</span>
                                    </span>
                                @endif
                            </div>

                            {{-- Tampilan di layar mobile (HP) --}}
                            <span class="d-inline d-sm-none">
                                Notifikasi
                                @if ($notifications->count() > 0)
                                    <span class="badge rounded-pill bg-danger ms-1">{{ $notifications->count() }}</span>
                                @endif
                            </span>

                            <i data-lucide="chevron-down" class="icon-sm"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-0 overflow-hidden"
                            style="width: 340px;">

                            {{-- Header Notifikasi --}}
                            <div class="px-3 py-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-bold small text-dark">Notifikasi</span>

                                @if ($notifications->count() > 0)
                                    <span class="badge bg-success-subtle text-success rounded-pill">
                                        {{ $notifications->count() }}
                                    </span>
                                @endif
                            </div>

                            {{-- Daftar Notifikasi --}}
                            <div style="max-height: 320px; overflow-y: auto;">
                                @forelse ($notifications as $notif)
                                    <a href="{{ $notif->url ?? '#' }}"
                                        class="dropdown-item px-3 py-2 border-bottom text-wrap {{ !$notif->read_at ? 'bg-success bg-opacity-10' : '' }}">

                                        <p class="mb-1 fw-semibold small text-dark">
                                            {{ $notif->title }}
                                        </p>

                                        <p class="mb-1 text-muted small" style="line-height: 1.4;">
                                            {{ $notif->message }}
                                        </p>

                                        <small class="text-secondary d-flex align-items-center gap-1"
                                            style="font-size: 0.72rem;">
                                            <i data-lucide="clock" style="width: 12px; height: 12px;"></i>
                                            {{ $notif->created_at->diffForHumans() }}
                                        </small>
                                    </a>
                                @empty
                                    <div class="text-center py-5 px-3 text-muted">
                                        <i data-lucide="bell-off" class="mb-2 text-secondary"
                                            style="width: 28px; height: 28px;"></i>
                                        <p class="mb-0 small">Belum ada notifikasi</p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Footer --}}
                            @if ($notifications->count() > 0)
                                <div class="p-2 text-center bg-light border-top">
                                    <a href="#" class="text-decoration-none small fw-medium text-success">
                                        Tandai semua sudah dibaca
                                    </a>
                                </div>
                            @endif
                        </div>
                    </li>

                    {{-- Profile --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center gap-2" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">

                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                                style="width: 22px; height: 22px;">
                                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            {{ auth()->user()->name }}

                            <i data-lucide="chevron-down" class="icon-sm"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.transaction.index') }}">
                                    <i data-lucide="package" class="icon-sm"></i>
                                    Pesanan Saya
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.profile.edit') }}">
                                    <i data-lucide="settings" class="icon-sm"></i>
                                    Pengaturan Profil
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item rounded-3 py-2" href="{{ route('customer.address.index') }}">
                                    <i data-lucide="pin" class="icon-sm"></i>
                                    Daftar Alamat
                                </a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-medium">
                                        <i data-lucide="log-out" class="icon-sm me-2 "></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>

                <hr class="text-primary">
                {{-- Search --}}
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            @endauth

            @guest
                <ul class="navbar-nav ms-lg-auto">
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-success rounded-pill">Daftar</a>
                    </li>
                </ul>
            @endguest
        </div>
    </div>
</nav>
