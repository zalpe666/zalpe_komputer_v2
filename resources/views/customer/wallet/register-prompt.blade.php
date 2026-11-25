@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-7">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    <div class="p-5 text-center">

                        <div class="mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                                style="width: 110px; height: 110px;">

                                <i class="bi bi-wallet2 text-primary" style="font-size: 50px;"></i>

                            </div>
                        </div>

                        <h3 class="fw-bold mb-2">
                            Selamat Datang di Zalpe Pay
                        </h3>

                        <p class="text-muted mb-4">
                            Nikmati kemudahan transaksi menggunakan saldo
                            digital Zalpe Pay. Daftarkan Wallet Anda terlebih
                            dahulu untuk mulai menggunakan layanan ini.
                        </p>

                        <div class="bg-light rounded-4 p-4 text-start mb-4">

                            <h6 class="fw-bold mb-3">
                                Keuntungan Zalpe Pay
                            </h6>

                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-lightning-charge-fill text-primary me-3 fs-5"></i>
                                <span>Transaksi lebih praktis dan cepat</span>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-shield-check text-primary me-3 fs-5"></i>
                                <span>Keamanan menggunakan PIN Wallet</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history text-primary me-3 fs-5"></i>
                                <span>Pantau riwayat transaksi Anda</span>
                            </div>

                        </div>

                        <a href="{{ route('customer.wallet.register') }}"
                            class="btn btn-primary btn-lg rounded-3 px-5 fw-semibold">

                            <i class="bi bi-wallet2 me-2"></i>
                            Daftar Zalpe Pay

                        </a>

                        <p class="text-muted small mt-3 mb-0">
                            Anda belum memiliki akun Wallet Zalpe Pay.
                        </p>

                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
