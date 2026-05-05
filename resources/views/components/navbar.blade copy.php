<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('customer.home.index') }}">
            <i class="bi bi-cpu text-success fs-3 me-2"></i>
            <span class="fw-bold text-success tracking-tight">Zalpe Komputer</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                @auth
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('customer.product.index') }}">Produk</a>
                    </li>

                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside">
                            Kategori
                        </a>
                        <div class="dropdown-menu w-100 shadow-sm border-0 mt-0 rounded-0 rounded-bottom">
                            <div class="container py-3">
                                <div class="row g-3">
                                    @foreach ($categories as $category)
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <a class="dropdown-item rounded py-2"
                                                href="{{ route('customer.product.index', ['category[]' => $category->id]) }}">
                                                {{ $category->name }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" data-bs-toggle="dropdown">Brand</a>
                        <ul class="dropdown-menu border-0 shadow-sm">
                            @foreach ($brands as $brand)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('customer.product.index', ['brand[]' => $brand->id]) }}">
                                        {{ $brand->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endauth
            </ul>

            @auth
                <form class="d-flex mx-lg-4 my-3 my-lg-0 flex-grow-1 justify-content-center" style="max-width: 500px;"
                    role="search" method="GET" action="{{ route('customer.product.index') }}">
                    <div class="input-group">
                        <input class="form-control bg-light border-0 rounded-start-pill ps-4" type="search" name="search"
                            placeholder="Cari sparepart atau laptop...">
                        <button class="btn btn-success rounded-end-pill px-4" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            @endauth

            <ul class="navbar-nav align-items-lg-center gap-2">
                @auth
                    <li class="nav-item">
                        <a href="{{ route('customer.cart.index') }}"
                            class="btn btn-light border-0 rounded-circle d-flex align-items-center justify-content-center position-relative shadow-sm"
                            style="width: 42px; height: 42px;">
                            <i class="bi bi-cart3 fs-5"></i>
                            {{-- Badge (Opsional jika ada data) --}}
                            {{-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span> --}}
                        </a>
                    </li>

                    <li class="nav-item dropdown ms-2">
                        <button id="notifDropdown"
                            class="btn btn-light border-0 rounded-circle d-flex align-items-center justify-content-center position-relative shadow-sm"
                            data-bs-toggle="dropdown" style="width: 42px; height: 42px;">
                            <i class="bi bi-bell fs-5"></i>
                            @if ($unreadCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.6rem;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <div class="dropdown-menu dropdown-menu-end p-0 shadow border-0 overflow-hidden"
                            style="width: 350px;">
                        </div>
                    </li>
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link d-flex align-items-center gap-2 bg-light rounded-pill px-3 py-1" href="#"
                            data-bs-toggle="dropdown">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white"
                                style="width: 30px; height: 30px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-medium small d-none d-xl-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                            <li><a class="dropdown-item" href="{{ route('customer.transaction.index') }}"><i
                                        class="bi bi-receipt me-2"></i> Transaksi</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.profile.edit') }}"><i
                                        class="bi bi-person-gear me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.address.index') }}"><i
                                        class="bi bi-geo-alt me-2"></i> Alamat</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success rounded-pill px-4" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Tambahan CSS untuk memperhalus tampilan */
    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item:active {
        background-color: #198754;
    }

    .navbar-brand span {
        letter-spacing: -0.5px;
    }

    /* Animasi dropdown lembut */
    .dropdown-menu {
        display: block;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
</style>
