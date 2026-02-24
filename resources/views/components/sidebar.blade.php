@php
    $user = auth()->user();
@endphp
@if ($user)
    {{-- ROLE: ADMIN --}}
    @if ($user->role === 'admin')
        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span class="nav-text ms-2">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.user.*') ? 'active' : '' }}"
                href="{{ route('admin.user.index') }}">
                <i class="bi bi-people"></i>
                <span class="nav-text ms-2">User</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.course.management') ? 'active' : '' }}"
                href="{{ route('admin.course.management') }}">
                <i class="bi bi-kanban"></i>
                <span class="nav-text ms-2">Management</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.modul.*') ? 'active' : '' }}"
                href="{{ route('admin.modul.index') }}">
                <i class="bi bi-journal-richtext"></i>
                <span class="nav-text ms-2">Modul</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.resource.*') ? 'active' : '' }}"
                href="{{ route('admin.resource.index') }}">
                <i class="bi bi-folder2-open"></i>
                <span class="nav-text ms-2">Resource</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.responden.*') ? 'active' : '' }}"
                href="{{ route('admin.responden.index') }}">
                <i class="bi bi-clipboard-data"></i>
                <span class="nav-text ms-2">Responden</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold {{ request()->routeIs('admin.setting.*') ? 'active' : '' }}"
                href="{{ route('admin.setting.edit') }}">
                <i class="bi bi-clipboard-data"></i>
                <span class="nav-text ms-2">Setting</span>
            </a>
        </li>
    @endif
    @if ($user->role === 'master')
        <li class="nav-item">
            <a class="nav-link fw-bold active"
                href="#">
                <i class="bi bi-clipboard-data"></i>
                <span class="nav-text ms-2">Setting</span>
            </a>
        </li>
    @endif



@endif
