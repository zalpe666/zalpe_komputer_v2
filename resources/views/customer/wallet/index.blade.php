@extends('layouts.app')

@section('content')
    <div class="container">

        <h2>Zalpe Pay 💰</h2>

        {{-- STATUS --}}
        <p>
            Saldo: <b>Rp {{ number_format($wallet->balance) }}</b><br>
            KYC:
            @if ($user->ktp_verified_at)
                <span style="color:green">Verified</span>
            @else
                <span style="color:red">Belum Verified</span>
            @endif
        </p>

        {{-- ALERT --}}
        @if (session('success'))
            <div style="color:green">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div style="color:red">{{ session('error') }}</div>
        @endif

        <hr>

        {{-- KYC --}}
        <h4>Verifikasi KTP (Dummy)</h4>
        <form method="POST" action="{{ route('customer.kyc.verify') }}">
            @csrf
            <input type="text" name="ktp_number" placeholder="Nomor KTP">
            <button type="submit">Verifikasi</button>
        </form>

        <hr>

        {{-- TOPUP --}}
        <h4>Topup</h4>
        <form method="POST" action="{{ route('customer.wallet.topup') }}">
            @csrf
            <input type="number" name="amount" placeholder="Jumlah">
            <button type="submit">Topup</button>
        </form>

        {{-- PAY --}}
        <h4>Bayar Pakai Zalpe Pay</h4>
        <form method="POST" action="{{ route('customer.wallet.pay') }}">
            @csrf
            <input type="number" name="amount" placeholder="Jumlah">
            <button type="submit">Bayar</button>
        </form>

        {{-- WITHDRAW --}}
        <h4>Withdraw</h4>
        <form method="POST" action="{{ route('customer.wallet.withdraw') }}">
            @csrf
            <input type="number" name="amount" placeholder="Jumlah">
            <button type="submit">Withdraw</button>
        </form>

        <hr>

        {{-- HISTORY --}}
        <h4>Riwayat Transaksi</h4>
        <ul>
            @foreach ($transactions as $trx)
                <li>
                    [{{ $trx->type }}] Rp {{ number_format($trx->amount) }}
                    - {{ $trx->description }}
                </li>
            @endforeach
        </ul>

    </div>
@endsection
