@php
    $user = auth()->user();
@endphp
@if ($user)
    {{-- ROLE: ADMIN --}}
    {{-- @if ($user->role === 'admin')
       
    @endif --}}
    @if ($user->role === 'master' || $user->role === 'admin')
        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}"
                href="{{ route('admin.dashboard.index') }}">
                <i class="bi bi-house-door"></i>
                <span class="nav-text ms-2">Home</span>
            </a>
        </li>
        <hr class="my-2"> 
        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
                href="{{ route('admin.categories.index') }}">
                <i class="bi bi-tags"></i>
                <span class="nav-text ms-2">Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.brand.index') ? 'active' : '' }}"
                href="{{ route('admin.brand.index') }}">
                <i class="bi bi-tags"></i>
                <span class="nav-text ms-2">Brand</span>
            </a>
        </li>
    @endif


@endif
