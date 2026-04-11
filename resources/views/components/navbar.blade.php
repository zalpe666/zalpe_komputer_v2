<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand text-success fw-bold" href="{{ route('customer.home.index') }}">Zalpe Komputer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            {{-- ✅ Menu kiri --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                @auth
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.cart.index') }}">Cart</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.product.index') }}">Products</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Categories
                        </a>

                        <div class="dropdown-menu p-3" style="min-width: 500px;">
                            <div class="row">

                                @foreach ($categories as $category)
                                    <div class="col-4">
                                        <a class="dropdown-item"
                                            href="{{ route('customer.product.index', ['category[]' => $category->id]) }}">
                                            {{ $category->name }}
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Brand
                        </a>

                        <div class="dropdown-menu p-3" style="min-width: 500px;">
                            <div class="row">

                                @foreach ($brands as $brand)
                                    <div class="col-4">
                                        <a class="dropdown-item"
                                            href="{{ route('customer.product.index', ['brand[]' => $brand->id]) }}">
                                            {{ $brand->name }}
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </li>

                    <!-- CATEGORY -->

                @endauth

                {{-- ✅ Dropdown user --}}
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.transaction.index') }}">Transaction</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.profile.edit') }}">Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.address.index') }}">Address</a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">Settings</a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                {{-- ✅ Guest --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-success ms-2" href="{{ route('register') }}">
                            Register
                        </a>
                    </li>
                @endguest

            </ul>

            {{-- ✅ Search hanya untuk login --}}
            @auth
                <div>
                    <a href="{{ route('customer.cart.index') }}" class="btn btn-light"><i class="bi bi-cart"></i></a>
                </div>
                <div class="dropdown me-3">
                    <button id="notifDropdown" class="btn btn-light position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>

                        @if ($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-0 shadow-sm" style="width: 400px;">
                        <div class="card border-0">

                            {{-- Header --}}
                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0 fw-semibold">Notifications</h6>
                                <a href="#" class="text-decoration-none small text-muted">
                                    See More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            {{-- Body --}}
                            <div class="card-body p-2" style="max-height: 400px; overflow-y:auto;">

                                @forelse($notifications as $notif)
                                    @php
                                        $icon = match ($notif->type) {
                                            'Shopping' => 'bi-bag text-primary',
                                            'Games' => 'bi-joystick text-success',
                                            'Top-Up' => 'bi-wallet2 text-warning',
                                            'Phone Credit' => 'bi-phone text-info',
                                            default => 'bi-bell text-secondary',
                                        };
                                    @endphp

                                    <a href="{{ $notif->link ?? '#' }}"
                                        class="d-flex align-items-start gap-3 text-decoration-none text-dark p-2 rounded notification-item">

                                        {{-- Icon --}}
                                        <div class="flex-shrink-0">
                                            <i class="bi {{ $icon }} fs-4 text-black"></i>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold mb-1">
                                                {{ $notif->title }}
                                            </div>

                                            <div class="text-muted small mb-1">
                                                {{ $notif->message }}
                                            </div>

                                            <small class="text-secondary d-flex align-items-center">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </a>

                                    @if (!$loop->last)
                                        <hr class="my-1">
                                    @endif

                                @empty
                                    <div class="text-center text-muted py-3">
                                        <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                                        Tidak ada notifikasi
                                    </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
                <form class="d-flex" role="search" method="GET" action="{{ route('customer.product.index') }}">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            @endauth

        </div>
    </div>
</nav>
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const notifBtn = document.getElementById("notifDropdown");

            if (notifBtn) {
                notifBtn.addEventListener("click", function() {

                    fetch("{{ route('customer.notifications.readAll') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    }).then(() => {
                        // 🔥 langsung hilangin badge tanpa reload
                        const badge = notifBtn.querySelector(".badge");
                        if (badge) badge.remove();
                    });

                });
            }
        });
    </script>
@endpush
