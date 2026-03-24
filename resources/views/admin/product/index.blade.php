@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb custom-breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}" class="breadcrumb-link">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Product
                </li>
            </ol>
        </nav>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h3 class="fw-semibold mb-1">Product List</h3>
                        <p class="text-muted">View, add, edit, or delete products</p>
                    </div>
                    <a href="{{ route('admin.product.create') }}" class="btn btn-primary rounded-4">
                        <i class="bi bi-plus-lg"></i> Add Product
                    </a>
                </div>
                <form id="filter-form" method="GET" action="{{ route('admin.product.index') }}"
                    class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">

                    <!-- Search Name (kiri) -->
                    <div class="position-relative flex-grow-0" style="flex-basis: 250px;">
                        <input type="text" name="search" class="form-control rounded-4 ps-4 pe-4"
                            placeholder="Search product..." value="{{ request('search') }}"
                            onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">

                        <i class="bi bi-search position-absolute fs-7"
                            style="top:50%; left:6px; transform:translateY(-50%); pointer-events:none;"></i>
                    </div>

                    <!-- Filters (kanan) -->
                    <div class="d-flex gap-2 flex-grow-1 justify-content-end flex-wrap">
                        <!-- Category -->
                        <div class="position-relative flex-grow-1" style="max-width: 150px;">
                            <select name="category_id" class="form-control rounded-4 pe-4"
                                onchange="document.getElementById('filter-form').submit();">
                                <option value="">All Category</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="bi bi-caret-down-fill position-absolute"
                                style="top:50%; right:12px; transform:translateY(-50%); pointer-events:none;"></i>
                        </div>

                        <!-- Brand -->
                        <div class="position-relative flex-grow-1" style="max-width: 150px;">
                            <select name="brand_id" class="form-control rounded-4 pe-4"
                                onchange="document.getElementById('filter-form').submit();">
                                <option value="">All Brand</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="bi bi-caret-down-fill position-absolute"
                                style="top:50%; right:12px; transform:translateY(-50%); pointer-events:none;"></i>
                        </div>

                        <!-- Type -->
                        <div class="position-relative flex-grow-1" style="max-width: 150px;">
                            <select name="type" class="form-control rounded-4 pe-4"
                                onchange="document.getElementById('filter-form').submit();">
                                <option value="">All Type</option>
                                <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Product
                                </option>
                                <option value="games" {{ request('type') == 'games' ? 'selected' : '' }}>Games</option>
                                <option value="digital" {{ request('type') == 'digital' ? 'selected' : '' }}>Digital
                                </option>
                                <option value="steam wallet" {{ request('type') == 'steam_wallet' ? 'selected' : '' }}>
                                    Steam Wallet</option>
                            </select>
                            <i class="bi bi-caret-down-fill position-absolute"
                                style="top:50%; right:12px; transform:translateY(-50%); pointer-events:none;"></i>
                        </div>
                        <div class="position-relative flex-grow-1" style="max-width: 80px;">
                            <a href="{{ route('admin.product.index') }}" class="btn btn-danger rounded-4 w-100">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
                <table class="table table-striped table-borderless p-4">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td
                                    style="max-width: 350px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $product->name }}
                                </td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ $product->brand->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'Product' => 'bg-primary', // biru
                                            'Games' => 'bg-success', // hijau
                                            'Digital' => 'bg-warning text-dark', // kuning
                                            'Steam Wallet' => 'bg-info text-dark', // cyan
                                        ];
                                        $colorClass = $typeColors[$product->type] ?? 'bg-secondary';
                                    @endphp

                                    <span class="badge {{ $colorClass }}">
                                        {{ $product->type ?? '-' }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($product->final_price) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td class="d-flex gap-1">

                                    <!-- Detail -->
                                    <a href="#" class="btn  btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Detail">
                                        <i class="bi fs-7 bi-eye-fill"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="#" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Edit">
                                        <i class="bi fs-7 bi-pencil-fill"></i>
                                    </a>

                                    <!-- Delete -->
                                    <button type="button" class="btn btn-sm btn-delete" data-id="{{ $product->id }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                        <i class="bi fs-7 bi-trash-fill"></i>
                                    </button>

                                    <!-- Form Hapus -->
                                    <form id="delete-form-{{ $product->id }}"
                                        action="{{ route('admin.product.delete', $product->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center text-muted">
                                        <i class="bi bi-box-seam" style="font-size: 40px;"></i>
                                        <h6 class="mt-3 mb-1">Belum ada produk</h6>
                                        <small>Silakan tambah produk terlebih dahulu</small>
                                        <a href="{{ route('admin.product.create') }}" class="btn btn-primary btn-sm mt-3">
                                            + Tambah Produk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>


    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Yakin hapus?',
                        text: "Data tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ff003c',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    });
                });
            });

        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        </script>
    @endif
@endpush
