@php
    $user = auth()->user();
@endphp

@if ($user && ($user->role === 'master' || $user->role === 'admin'))
    <li class="nav-item">
        <a class="nav-link fw-bold {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}"
            href="{{ route('admin.dashboard.index') }}">
            <i class="bi bi-house-door-fill"></i>
            <span class="nav-text ms-2">Home</span>
        </a>
    </li>
    <hr class="my-2">

    <li class="nav-item">
        <a class="nav-link fw-bold {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
            href="{{ route('admin.categories.index') }}">
            <i class="bi bi-tags-fill"></i>
            <span class="nav-text ms-2">Categories</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold {{ request()->routeIs('admin.brand.index') ? 'active' : '' }}"
            href="{{ route('admin.brand.index') }}">
            <i class="bi bi-box-seam"></i>
            <span class="nav-text ms-2">Brand</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold {{ request()->routeIs('admin.product.*') ? 'active' : '' }}"
            href="{{ route('admin.product.index') }}">
            <i class="bi bi-bag-fill"></i>
            <span class="nav-text ms-2">Product</span>
        </a>
    </li>
    <hr class="my-2">

    {{-- Transaction with Collapse --}}
    <li class="nav-item">
        <a class="nav-link fw-bold d-flex justify-content-between align-items-center"
            data-bs-toggle="collapse" href="#transactionMenu" role="button" aria-expanded="false"
            aria-controls="transactionMenu">
            <div>
                <i class="bi bi-bag-fill"></i>
                <span class="nav-text ms-2">Transaction</span>
            </div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse" id="transactionMenu">
            <ul class="nav flex-column ms-3 mt-2">
                <li class="nav-item">
                    <a class="nav-link fw-normal {{ request()->routeIs('admin.transaction.today') ? 'active' : '' }}"
                        href="{{ route('admin.transaction.today') }}">
                        Today
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-normal {{ request()->routeIs('admin.transaction.index') ? 'active' : '' }}"
                        href="{{ route('admin.transaction.index')}}">
                        All Transactions
                    </a>
                </li>
            </ul>
        </div>
    </li>
@endif