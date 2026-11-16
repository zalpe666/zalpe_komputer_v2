@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid px-0">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb custom-breadcrumb mb-0 shadow-sm w-auto d-inline-flex">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}" class="breadcrumb-link d-flex align-items-center gap-1">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
                    Product
                </li>
            </ol>
        </nav>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">

                <!-- Header Card -->
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-dark);">Product List</h4>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Manage your products, pricing, and stock</p>
                    </div>
                    <a href="{{ route('admin.product.create') }}"
                        class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium">
                        <i class="bi bi-plus-lg me-1"></i> Add Product
                    </a>
                </div>

                <!-- Filter Form -->
                <div class="bg-light p-3 rounded-4 mb-4">
                    <form id="filter-form" method="GET" action="{{ route('admin.product.index') }}"
                        class="row g-2 align-items-center">

                        <!-- Search Name -->
                        <div class="col-12 col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 rounded-end-3"
                                    placeholder="Search product..." value="{{ request('search') }}"
                                    onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-6 col-md-2">
                            <select name="category_id" class="form-select rounded-3 text-muted"
                                onchange="document.getElementById('filter-form').submit();">
                                <option value="">All Category</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="col-6 col-md-2">
                            <select name="brand_id" class="form-select rounded-3 text-muted"
                                onchange="document.getElementById('filter-form').submit();">
                                <option value="">All Brand</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="col-8 col-md-3">
                            <select name="type" class="form-select rounded-3 text-muted"
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
                        </div>

                        <!-- Reset -->
                        <div class="col-4 col-md-2">
                            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary w-100 rounded-3">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table Area -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"
                            style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            <tr>
                                <th class="text-secondary fw-semibold py-3 rounded-start-3">Product Name</th>
                                <th class="text-secondary fw-semibold py-3">Category</th>
                                <th class="text-secondary fw-semibold py-3">Brand</th>
                                <th class="text-secondary fw-semibold py-3">Type</th>
                                <th class="text-secondary fw-semibold py-3">Price</th>
                                <th class="text-secondary fw-semibold py-3 text-center">Stock</th>
                                <th class="text-secondary fw-semibold py-3 rounded-end-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="fw-medium text-dark"
                                        style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $product->name }}
                                    </td>
                                    <td class="text-muted">{{ $product->category->name ?? '-' }}</td>
                                    <td class="text-muted">{{ $product->brand->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $type = strtolower($product->type);
                                            $typeColors = [
                                                'product' =>
                                                    'bg-primary-subtle text-primary border border-primary-subtle',
                                                'games' =>
                                                    'bg-success-subtle text-success border border-success-subtle',
                                                'digital' =>
                                                    'bg-warning-subtle text-warning border border-warning-subtle',
                                                'steam_wallet' => 'bg-info-subtle text-info border border-info-subtle',
                                                'steam wallet' => 'bg-info-subtle text-info border border-info-subtle',
                                            ];
                                            $colorClass = $typeColors[$type] ?? 'bg-secondary-subtle text-secondary';
                                        @endphp

                                        <span class="badge rounded-pill px-3 py-2 {{ $colorClass }}">
                                            {{ ucfirst($product->type) ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($product->final_price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $product->stock > 10 ? 'bg-success' : 'bg-danger' }} rounded-circle p-2"
                                            data-bs-toggle="tooltip" title="{{ $product->stock }} items left">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <!-- Detail -->
                                            <a href="{{ route('admin.product.show', $product->id) }}"
                                                class="btn btn-light btn-sm text-info rounded-3 action-btn"
                                                data-bs-toggle="tooltip" title="View Detail">
                                                <i class="bi fs-6 bi-eye"></i>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.product.edit', $product->id) }}"
                                                class="btn btn-light btn-sm text-warning rounded-3 action-btn"
                                                data-bs-toggle="tooltip" title="Edit Product">
                                                <i class="bi fs-6 bi-pencil-square"></i>
                                            </a>

                                            <!-- Delete -->
                                            <button type="button"
                                                class="btn btn-light btn-sm text-danger rounded-3 action-btn btn-delete"
                                                data-id="{{ $product->id }}" data-bs-toggle="tooltip"
                                                title="Delete Product">
                                                <i class="bi fs-6 bi-trash"></i>
                                            </button>

                                            <!-- Form Hapus -->
                                            <form id="delete-form-{{ $product->id }}"
                                                action="{{ route('admin.product.delete', $product->id) }}" method="POST"
                                                style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 border-0">
                                        <div class="d-flex flex-column align-items-center text-muted">
                                            <div class="bg-light rounded-circle p-4 mb-3">
                                                <i class="bi bi-box-seam text-secondary" style="font-size: 48px;"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-1">No products found</h5>
                                            <p class="text-muted mb-4">You haven't added any products yet, or no products
                                                match your filter.</p>
                                            <a href="{{ route('admin.product.create') }}"
                                                class="btn btn-primary rounded-pill px-4">
                                                <i class="bi bi-plus-lg me-1"></i> Add New Product
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Tambahan Style Khusus Hover Action Button -->
    <style>
        .action-btn {
            transition: all 0.2s ease-in-out;
        }

        .action-btn:hover {
            transform: scale(1.1);
            background-color: #fff !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Initialize Bootstrap Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            // SweetAlert Delete Confirmation
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This product will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        background: '#ffffff',
                        borderRadius: '16px'
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
                timer: 2500,
                timerProgressBar: true,
                customClass: {
                    popup: 'colored-toast'
                }
            });
        </script>
    @endif
@endpush
