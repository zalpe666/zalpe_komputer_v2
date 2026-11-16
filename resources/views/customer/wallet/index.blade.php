@extends('layouts.app')

@section('content')
<style>
    /* Sedikit kustomisasi untuk tampilan yang lebih premium */
    .wallet-card {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
    }
    .icon-circle {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .card-custom {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<div class="container">
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-0">Zalpe Pay</h3>
            <p class="text-muted mb-0">Kelola keuangan Anda dengan mudah</p>
        </div>
    </div>

    {{-- ALERT MESSAGES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        {{-- KOLOM KIRI: Kartu Saldo --}}
        <div class="col-lg-6">
            <div class="card card-custom wallet-card h-100 p-2">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="mb-1 text-white-50">Total Saldo Aktif</p>
                            <h1 class="fw-bold mb-0">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</h1>
                        </div>
                        <i class="bi bi-sim card-custom fs-1 text-white-50 opacity-50"></i>
                    </div>
                    
                    <div class="mt-auto">
                        <hr class="border-light opacity-25">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-white-50"><i class="bi bi-shield-check me-1"></i> Status Akun</span>
                            @if ($user->ktp_verified_at)
                                <span class="badge bg-light text-success rounded-pill px-3 py-2 fw-bold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Verified
                                </span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold shadow-sm">
                                    <i class="bi bi-x-circle-fill me-1"></i> Belum Verified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Aksi Topup & KYC --}}
        <div class="col-lg-6 d-flex flex-column gap-3">
            
            {{-- KYC ALERT CARD (Hanya tampil jika belum diverifikasi) --}}
            @if (!$user->ktp_verified_at)
                <div class="card card-custom border-start border-warning border-4 bg-warning bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person-vcard fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Verifikasi Identitas (KYC)</h6>
                                <small class="text-muted">Verifikasi KTP untuk amankan akun Anda.</small>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('customer.kyc.verify') }}">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="ktp_number" class="form-control" placeholder="Masukkan 16 digit NIK" required>
                                <button type="submit" class="btn btn-warning fw-bold text-dark">Verifikasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- TOPUP CARD --}}
            <div class="card card-custom flex-grow-1">
                <div class="card-body">
                    <h6 class="fw-bold mb-1"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Topup Saldo</h6>
                    <p class="text-muted small mb-3">Tambahkan saldo untuk mulai bertransaksi.</p>
                    
                    <form method="POST" action="{{ route('customer.wallet.topup') }}">
                        @csrf
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0">Rp</span>
                            <input type="number" name="amount" class="form-control border-start-0 ps-0" placeholder="0" required>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Topup</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- RIWAYAT TRANSAKSI --}}
    <h5 class="fw-bold mb-3 mt-5">Riwayat Transaksi Terakhir</h5>
    <div class="card card-custom overflow-hidden">
        <ul class="list-group list-group-flush">
            @forelse ($transactions as $trx)
                <li class="list-group-item p-4 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center">
                        {{-- Logika Ikon Dinamis berdasarkan tipe transaksi --}}
                        @if(strtolower($trx->type) == 'topup')
                            <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-arrow-down-left fs-5"></i>
                            </div>
                        @else
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-arrow-up-right fs-5"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h6 class="mb-1 fw-bold text-capitalize">{{ $trx->type }}</h6>
                            <small class="text-muted">{{ $trx->description }}</small>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        {{-- Logika warna dinamis --}}
                        @if(strtolower($trx->type) == 'topup')
                            <h6 class="fw-bold text-success mb-0">+ Rp {{ number_format($trx->amount, 0, ',', '.') }}</h6>
                        @else
                            <h6 class="fw-bold mb-0">Rp {{ number_format($trx->amount, 0, ',', '.') }}</h6>
                        @endif
                    </div>
                </li>
            @empty
                <li class="list-group-item p-5 text-center text-muted">
                    <i class="bi bi-receipt fs-1 text-light mb-3 d-block"></i>
                    <h6 class="fw-bold">Belum Ada Transaksi</h6>
                    <p class="small mb-0">Riwayat topup dan pembayaran Anda akan muncul di sini.</p>
                </li>
            @endforelse
        </ul>
    </div>

</div>
@endsection