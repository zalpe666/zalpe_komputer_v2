<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand text-success fw-bold" href="#">Zalpe Komputer</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
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
                            <a class="nav-link" href="#">Cart</a>
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
                                    <a class="dropdown-item" href="#">Profile</a>
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
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                @endauth

            </div>
        </div>
    </nav>
    <div class="container py-4">
        @yield('content')
    </div>

</body>

</html>
