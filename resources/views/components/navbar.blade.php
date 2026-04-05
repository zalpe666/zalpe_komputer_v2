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
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.cart.index') }}">Cart</a>
                    </li>
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
                <div class="dropdown me-3">
                    <button id="notifDropdown" class="btn btn-light position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>

                        @if ($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-2"
                        style="width: 300px; max-height: 400px; overflow-y:auto;">
                        @forelse($notifications as $notif)
                            <a href="{{ $notif->link ?? '#' }}" class="text-decoration-none text-dark">

                                <div class="mb-2 p-2 rounded {{ $notif->is_read ? '' : 'bg-light' }}">
                                    <div class="fw-bold">{{ $notif->title }}</div>
                                    <small>{{ $notif->message }}</small><br>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>

                            </a>
                        @empty
                            <div class="text-center text-muted">Tidak ada notifikasi</div>
                        @endforelse
                    </div>
                </div>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search">
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
