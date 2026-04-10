<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Zalpe Komputer') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Zalpe Komputer</a>

            <div class="ms-auto">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-success">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container text-center py-5">
        <h1 class="display-4 fw-bold">Welcome to Zalpe Komputer 🚀</h1>
        <p class="lead">Toko komputer terbaik untuk kebutuhan gaming & produktivitas</p>

        <a href="#" class="btn btn-primary btn-lg mt-3">Shop Now</a>
    </div>

    <!-- Features -->
    <div class="container py-5">
        <div class="row text-center">

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">🔥 Produk Gaming</h5>
                        <p class="card-text">PC, Laptop, dan aksesoris gaming terbaik.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">⚡ Harga Terbaik</h5>
                        <p class="card-text">Harga kompetitif dengan kualitas terbaik.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">🚚 Pengiriman Cepat</h5>
                        <p class="card-text">Pengiriman cepat dan aman ke seluruh Indonesia.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">© {{ date('Y') }} Zalpe Komputer. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>