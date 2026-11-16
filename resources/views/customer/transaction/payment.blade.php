@extends('layouts.customer-layout')
@section('title', 'Pembayaran')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12">

                @if ($transaction->payment_status == 'Paid')
                    {{-- ✅ SUKSES --}}
                    <div class="card border-0 rounded-4 shadow-sm text-center overflow-hidden">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
                            </div>

                            <h3 class="fw-bolder text-success mb-2">Pembayaran Berhasil!</h3>
                            <p class="text-muted mb-4">
                                Terima kasih! Pembayaran untuk pesanan Anda telah kami terima.
                            </p>

                            <div class="bg-light p-3 rounded-3 mb-4 text-center">
                                <span class="d-block text-muted mb-1">Nomor Invoice</span>
                                <span class="fw-bold text-dark fs-5">{{ $transaction->invoice }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 mb-4">
                                <span class="text-muted">Total Pembayaran</span>
                                <span class="fw-bold fs-5">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                            </div>

                            <a href="{{ route('customer.transaction.index') }}"
                                class="btn btn-success btn-lg w-100 rounded-pill shadow-sm">
                                <i class="bi bi-bag-check me-2"></i> Lihat Pesanan
                            </a>
                        </div>
                    </div>
                @else
                    {{-- ⏳ MENUNGGU PEMBAYARAN --}}
                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                        {{-- Header Card --}}
                        <div class="card-header bg-primary text-white text-center py-4 border-0">
                            <h4 class="fw-bold mb-1">Selesaikan Pembayaran</h4>
                            <p class="mb-0 text-white-50">Silakan bayar sebelum waktu habis</p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            {{-- Batas Waktu --}}
                            <div class="text-center mb-4">
                                <p class="text-muted mb-1">Berlaku sampai:</p>
                                <h5 class="text-danger fw-bold">
                                    <i class="bi bi-clock-history me-1"></i> {{ $transaction->expired_at }}
                                </h5>
                            </div>

                            <hr class="text-secondary">

                            {{-- Detail Transfer --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Bank Tujuan</span>
                                <span class="fw-bold fs-5 text-uppercase text-primary">{{ $transaction->bank }}</span>
                            </div>

                            @if ($transaction->bank == 'mandiri')
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">Biller Code</span>
                                    <span class="fw-bold">{{ $transaction->biller_code }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted d-block mb-2">Bill Key</label>
                                    <span class="fw-bold">{{ $transaction->bill_key }}</span>
                                </div>
                            @else
                                <div class="mb-3">
                                    <label class="text-muted d-block mb-2">Nomor Virtual Account</label>
                            @endif
                            {{-- Input Group Copy VA --}}
                            <div class="input-group input-group-lg shadow-sm">
                                <input type="text" id="va" class="form-control fw-bold text-primary bg-white"
                                    value="{{ $transaction->va_number }}" readonly>
                                <button onclick="copyVA()" class="btn btn-primary" type="button" id="copyBtn">
                                    <i class="bi bi-clipboard me-1"></i> Salin
                                </button>
                            </div>
                        </div>

                        {{-- Total Bayar --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light border rounded-3">
                            <span class="text-muted">Total Tagihan</span>
                            <span class="fw-bold fs-4 text-primary">Rp
                                {{ number_format($transaction->total, 0, ',', '.') }}</span>
                        </div>

                        {{-- Peringatan --}}
                        <div class="alert alert-warning mt-4 d-flex align-items-start shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning me-3"></i>
                            <div>
                                <strong>Perhatian!</strong> Transfer tepat sesuai nominal agar pembayaran otomatis
                                terverifikasi oleh sistem.
                            </div>
                        </div>

                        {{-- Tombol Refresh --}}
                        <button onclick="refreshPage()"
                            class="btn btn-outline-primary btn-lg w-100 rounded-pill shadow-sm mt-3">
                            <i class="bi bi-arrow-clockwise me-2"></i> Cek Status Pembayaran
                        </button>

                    </div>
            </div>
            @endif

        </div>
    </div>
    </div>

    <script>
        function copyVA() {
            // Karena ID va sekarang adalah input, kita pakai .value bukan .innerText
            let vaInput = document.getElementById("va");
            vaInput.select();
            vaInput.setSelectionRange(0, 99999); // Untuk mobile
            navigator.clipboard.writeText(vaInput.value);

            // Ubah teks tombol sementara untuk feedback UX yang lebih baik
            let btn = document.getElementById("copyBtn");
            let originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2-all me-1"></i> Disalin!';
            btn.classList.replace('btn-primary', 'btn-success');

            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.replace('btn-success', 'btn-primary');
            }, 2000);
        }

        function refreshPage() {
            // Bisa tambahkan animasi loading di sini jika diperlukan
            location.reload();
        }
    </script>
@endsection
