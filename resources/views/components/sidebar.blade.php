<style>
    /* ================= SIDEBAR SUBMENU & UTILITIES ================= */

    /* Label Section (seperti judul "INVENTORY") */
    .section-label {
        font-size: 0.7rem;
        letter-spacing: 1px;
        color: #a1a5b7;
        display: block;
    }

    /* Animasi Chevron saat Dropdown dibuka */
    .chevron-icon {
        font-size: 0.8rem;
        transition: transform 0.3s ease;
    }

    a[aria-expanded="true"] .chevron-icon {
        transform: rotate(90deg);
    }

    /* Desain Submenu */
    .submenu-list {
        margin-left: 10px;
        /* Menjorok ke dalam menyesuaikan ikon bapaknya */
        border-left: 1px dashed #d1d5db;
        /* Garis vertikal penghubung */
        padding-left: 0;
        margin-top: 4px;
    }

    /* Link di dalam Submenu */
    .sidebar .submenu-link {
        padding: 8px 16px;
        margin: 4px 0 4px 12px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Modifikasi bulat kecil untuk submenu */
    .submenu-icon {
        font-size: 0.4rem !important;
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    /* Jika submenu active, bulatan ikut terang */
    .sidebar .submenu-link.active .submenu-icon {
        opacity: 1;
        color: var(--primary-color);
    }
</style>
@php
    $user = auth()->user();
@endphp

@if ($user && ($user->role === 'master' || $user->role === 'admin'))
    <!-- Dashboard -->
    <li class="nav-item mt-2">
        <a class="nav-link {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}"
            href="{{ request()->routeIs('admin.dashboard.index') ? '#' : route('admin.dashboard.index') }}">
            <i class="bi bi-house-door"></i>
            <span class="nav-text">Home</span>
        </a>
    </li>

    <!-- Section: INVENTORY -->
    <li class="nav-item mt-4 mb-2">
        <span class="nav-text text-uppercase fw-bold px-3 section-label">
            Inventory
        </span>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
            href="{{ route('admin.categories.index') }}">
            <i class="bi bi-tags"></i>
            <span class="nav-text">Categories</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.brand.index') ? 'active' : '' }}"
            href="{{ route('admin.brand.index') }}">
            <i class="bi bi-box-seam"></i>
            <span class="nav-text">Brand</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}"
            href="{{ route('admin.product.index') }}">
            <i class="bi bi-box"></i>
            <span class="nav-text">Product</span>
        </a>
    </li>

    <!-- Section: TRANSACTIONS -->
    <li class="nav-item mt-4 mb-2">
        <span class="nav-text text-uppercase fw-bold px-3 section-label">
            Sales & Orders
        </span>
    </li>

    {{-- Transaction with Collapse --}}
    @php
        // Cek apakah salah satu rute submenu sedang aktif
        $isTransactionActive =
            request()->routeIs('admin.transaction.today') || request()->routeIs('admin.transaction.index');
    @endphp

    <li class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center {{ $isTransactionActive ? 'text-primary fw-bold' : '' }}"
            data-bs-toggle="collapse" href="#transactionMenu" role="button"
            aria-expanded="{{ $isTransactionActive ? 'true' : 'false' }}" aria-controls="transactionMenu">

            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-cart3 {{ $isTransactionActive ? 'text-primary' : '' }}"></i>
                <span class="nav-text">Transaction</span>
            </div>
            <!-- Ikon Chevron untuk animasi -->

        </a>

        <!-- Collapse Area (otomatis "show" jika submenunya aktif) -->
        <div class="collapse {{ $isTransactionActive ? 'show' : '' }}" id="transactionMenu">
            <ul class="nav flex-column submenu-list">
                <li class="nav-item">
                    <a class="nav-link submenu-link {{ request()->routeIs('admin.transaction.today') ? 'active' : '' }}"
                        href="{{ route('admin.transaction.today') }}">
                        <i class="bi bi-circle-fill submenu-icon"></i>
                        <span class="nav-text">Today</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link submenu-link {{ request()->routeIs('admin.transaction.index') ? 'active' : '' }}"
                        href="{{ route('admin.transaction.index') }}">
                        <i class="bi bi-circle-fill submenu-icon"></i>
                        <span class="nav-text">All Transactions</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
@endif
