@extends('layouts.dashboard')

@section('title', 'Admin Panel')

@section('content')

    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb custom-breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}" class="breadcrumb-link">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Brand
                </li>
            </ol>
        </nav>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h3 class="fw-semibold mb-1">Brand List</h3>
                        <p class="text-muted">Manage all available brands in your store</p>
                    </div>
                    <a href="#" class="btn btn-primary rounded-4">
                        <i class="bi bi-plus-lg"></i> Add Brand
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px">#</th>
                                <th style="width: 120px">Icon</th>
                                <th>Nama brand</th>
                                <th style="width: 150px">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($brands as $index => $item)
                                <tr>
                                    <td>
                                        {{ $brands->firstItem() + $index }}
                                    </td>

                                    <td class="text-center">
                                        @if ($item->photo_url)
                                            <img src="{{ $item->photo_url }}" alt="{{ $item->name }}"
                                                style="height:40px; object-fit:contain;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $item->name }}
                                    </td>

                                    <td>
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Tidak ada data brand
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $brands->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>



    </div>
@endsection
