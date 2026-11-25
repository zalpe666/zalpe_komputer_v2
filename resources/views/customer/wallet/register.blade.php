@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 65px; height: 65px;">
                                <i class="bi bi-wallet2 text-primary fs-2"></i>
                            </div>

                            <h3 class="fw-bold mb-1">Daftar Zalpe Pay</h3>

                            <p class="text-muted small">
                                Lengkapi informasi untuk membuat akun Wallet Anda.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('customer.wallet.register.store') }}" method="POST">

                            @csrf

                            {{-- NIK --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nomor Induk Kependudukan (NIK)
                                </label>

                                <input type="text" name="nik" class="form-control" maxlength="16" inputmode="numeric"
                                    placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" required>
                            </div>

                            {{-- NAMA --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama Lengkap
                                </label>

                                <input type="text" name="full_name" class="form-control" placeholder="Sesuai identitas"
                                    value="{{ old('full_name') }}" required>
                            </div>

                            {{-- TANGGAL LAHIR --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Tanggal Lahir
                                </label>

                                <input type="date" name="birth_date" class="form-control"
                                    max="{{ date('Y-m-d', strtotime('-1 day')) }}" value="{{ old('birth_date') }}" required>
                            </div>

                            <hr class="my-4">

                            {{-- PIN --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    PIN Wallet
                                </label>

                                <input type="password" name="pin" class="form-control" maxlength="6"
                                    inputmode="numeric" autocomplete="new-password" placeholder="Masukkan 6 digit PIN"
                                    required>

                                <small class="text-muted">
                                    Gunakan PIN yang mudah diingat tetapi sulit ditebak.
                                </small>
                            </div>

                            {{-- KONFIRMASI PIN --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Konfirmasi PIN
                                </label>

                                <input type="password" name="pin_confirmation" class="form-control" maxlength="6"
                                    inputmode="numeric" autocomplete="new-password" placeholder="Masukkan ulang PIN"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3">

                                <i class="bi bi-wallet2 me-2"></i>
                                Daftar Zalpe Pay

                            </button>

                            <a href="{{ route('customer.wallet.index') }}" class="btn btn-light w-100 mt-2 rounded-3">

                                Kembali

                            </a>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
