@extends('layouts.customer-layout')

@section('title', 'My Addresses')
@section('page_title', 'Daftar Alamat')

@section('content')
    {{-- Header & Tombol Tambah --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <p class="text-muted small mb-0">Kelola alamat pengiriman untuk memudahkan proses checkout Anda.</p>
        <a href="{{ route('customer.address.create') }}"
            class="btn btn-success rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
            <i data-lucide="plus"></i>
            <span>Tambah Alamat</span>
        </a>
    </div>

    {{-- Daftar Kartu Alamat --}}
    <div class="vstack gap-3">
        @forelse ($addresses as $item)
            <div
                class="card rounded-4 shadow-sm {{ $item->is_default ? 'border-success bg-success-subtle bg-opacity-10' : 'border-light-subtle' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="d-flex align-items-start gap-3">
                            {{-- Ikon Pin Lokasi --}}
                            <div
                                class="rounded-3 p-2 flex-shrink-0 {{ $item->is_default ? 'bg-success text-white' : 'bg-light text-success' }}">
                                <i data-lucide="map-pin"></i>
                            </div>

                            {{-- Detail Penerima & Alamat --}}
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <h6 class="fw-bold text-dark mb-0">{{ $item->name }}</h6>
                                    <span class="text-muted small d-inline-flex align-items-center gap-1">
                                        <i data-lucide="phone"></i>
                                        {{ $item->phone }}
                                    </span>
                                    @if ($item->is_default)
                                        <span class="badge bg-success rounded-pill d-inline-flex align-items-center gap-1">
                                            <i data-lucide="check-circle-2"></i>
                                            Utama
                                        </span>
                                    @endif
                                </div>

                                <p class="text-muted small mb-0">
                                    {{ $item->address }},
                                    {{ $item->district->name }},
                                    {{ $item->city->name }},
                                    {{ $item->province->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="card border-light-subtle rounded-4 bg-light bg-opacity-50 text-center py-5 px-3">
                <div class="card-body">
                    <div
                        class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center p-3 mb-3 text-secondary">
                        <i data-lucide="map-pin-off"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Belum Ada Alamat Tersimpan</h6>
                    <p class="text-muted small mb-3">Tambahkan alamat pengiriman pertama Anda sekarang.</p>
                    <a href="{{ route('customer.address.create') }}"
                        class="btn btn-outline-success rounded-3 px-4 fw-semibold d-inline-flex align-items-center gap-2">
                        <i data-lucide="plus"></i>
                        <span>Tambah Alamat Baru</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>
@endsection
