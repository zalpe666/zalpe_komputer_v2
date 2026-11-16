<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>404 - Page Not Found | Zalpe Komputer</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: #0f172a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, sans-serif;
            overflow: hidden;
        }

        .error-container {
            max-width: 650px;
            position: relative;
            z-index: 2;
        }

        .error-code {
            font-size: clamp(110px, 22vw, 190px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -12px;
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: float 3s ease-in-out infinite;
        }

        .error-icon {
            width: 85px;
            height: 85px;
            background: rgba(59, 130, 246, .12);
            border: 1px solid rgba(96, 165, 250, .25);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 38px;
            color: #60a5fa;
        }

        .btn-home {
            background: #2563eb;
            border: none;
            color: white;
            padding: 13px 25px;
            border-radius: 12px;
            font-weight: 600;
            transition: .3s;
        }

        .btn-home:hover {
            background: #1d4ed8;
            color: white;
            transform: translateY(-3px);
        }

        .btn-back {
            border: 1px solid #334155;
            color: #cbd5e1;
            padding: 13px 25px;
            border-radius: 12px;
            transition: .3s;
        }

        .btn-back:hover {
            background: #1e293b;
            color: white;
        }

        .decoration {
            position: absolute;
            width: 350px;
            height: 350px;
            background: #2563eb;
            filter: blur(150px);
            opacity: .12;
            border-radius: 50%;
        }

        .decoration.one {
            top: -150px;
            left: -150px;
        }

        .decoration.two {
            bottom: -150px;
            right: -150px;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }
    </style>
</head>

<body>

    <div class="decoration one"></div>
    <div class="decoration two"></div>

    <main class="container text-center error-container">

        <div class="error-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>

        <div class="error-code">404</div>

        <h1 class="fw-bold mt-4 mb-3">
            Oops! Halaman Tidak Ditemukan
        </h1>

        <p class="text-secondary fs-6 mb-4 mx-auto" style="max-width: 480px;">
            Sepertinya halaman yang kamu cari tidak tersedia.
            Mungkin halaman telah dipindahkan, dihapus,
            atau URL yang kamu masukkan salah.
        </p>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">

            <a href="{{ route('customer.home.index') }}" class="btn btn-home">
                <i class="bi bi-house-door me-2"></i>
                Kembali ke Beranda
            </a>

            <button onclick="history.back()" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i>
                Halaman Sebelumnya
            </button>

        </div>

        <div class="mt-5 pt-3">
            <p class="text-secondary small mb-0">
                &copy; {{ date('Y') }} <span class="text-white fw-semibold">Zalpe Komputer</span>.
                All Rights Reserved.
            </p>
        </div>

    </main>

</body>

</html>
