@extends('layouts.app')

@section('title', 'Katalog Produk - Zalpe Komputer')

@section('content')
<style>
    body { background-color: #f8f9fa; }
    
    /* Sidebar Filter Styling */
    .filter-sidebar {
        position: sticky;
        top: 100px;
        z-index: 10;
    }
    
    .filter-group-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #adb5bd;
        margin-bottom: 15px;
        display: block;
    }

    .form-check-label {
        font-size: 0.95rem;
        color: #495057;
        cursor: pointer;
        transition: color 0.2s;
    }

    .form-check-input:checked + .form-check-label {
        color: #198754;
        font-weight: 600;
    }

    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    /* Sort Select Styling */
    .sort-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 8px 15px;
        font-size: 0.9rem;
        cursor: pointer;
        background-color: #fff;
    }

    /* Badge Search */
    .search-badge {
        background-color: #e8f5e9;
        color: #198754;
        border: 1px solid #c8e6c9;
        padding: 5px 15px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Pagination Styling */
    .pagination {
        margin-top: 50px;
        justify-content: center;
    }
    .page-link {
        border-radius: 10px !important;
        margin: 0 3px;
        border: none;
        color: #495057;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .page-item.active .page-link {
        background-color: #198754;
        color: white;
    }
</style>

<div class="container py-5">
    <div class="row g-4">
        
        {{-- 🔹 LEFT SIDE: FILTERS --}}
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold m-0 text-dark">Filter</h5>
                        <i class="bi bi-sliders2 text-success"></i>
                    </div>

                    <form method="GET" action="{{ route('customer.product.index') }}">
                        {{-- Simpan pencarian jika ada --}}
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- CATEGORY FILTER --}}
                        @if ($categories->count())
                            <div class="mb-4">
                                <span class="filter-group-title">Kategori</span>
                                <div class="filter-scroll pe-2" style="max-height: 250px; overflow-y: auto;">
                                    @foreach ($categories as $category)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="category[]"
                                                id="cat-{{ $category->id }}" value="{{ $category->id }}"
                                                {{ in_array($category->id, request('category', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat-{{ $category->id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <hr class="my-4 opacity-25">

                        {{-- BRAND FILTER --}}
                        @if ($brands->count())
                            <div class="mb-4">
                                <span class="filter-group-title">Brand</span>
                                <div class="filter-scroll pe-2" style="max-height: 250px; overflow-y: auto;">
                                    @foreach ($brands as $brand)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="brand[]"
                                                id="brand-{{ $brand->id }}" value="{{ $brand->id }}"
                                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success rounded-pill fw-bold py-2 shadow-sm">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('customer.product.index') }}" class="btn btn-light rounded-pill fw-bold py-2 text-muted">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 🔹 RIGHT SIDE: PRODUCT GRID --}}
        <div class="col-lg-9">
            
            {{-- Header List --}}
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        @if (request('search'))
                            <div class="search-badge">
                                <i class="bi bi-search"></i>
                                <span>Hasil: <b>"{{ request('search') }}"</b></span>
                            </div>
                        @else
                            <h4 class="fw-bold text-dark mb-0">Semua Produk</h4>
                            <p class="text-muted small mb-0">{{ $products->total() }} Produk ditemukan</p>
                        @endif
                    </div>
                    
                    <div class="col-md-6 mt-3 mt-md-0 text-md-end">
                        <form method="GET" action="{{ route('customer.product.index') }}" class="d-inline-block">
                            {{-- Pertahankan filter saat sorting --}}
                            @foreach (request()->except('sort') as $key => $value)
                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                            <div class="d-flex align-items-center gap-2">
                                <label class="small text-muted text-nowrap">Urutkan:</label>
                                <select name="sort" class="form-select sort-select shadow-none" onchange="this.form.submit()">
                                    <option value="">Default</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama: Z-A</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Grid Produk --}}
            <div class="row g-4">
                @forelse ($products as $product)
                    <div class="col-6 col-md-4 col-xl-3">
                        <x-product-card :product="$product" />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="bi bi-search text-muted display-1 opacity-25"></i>
                            <h5 class="mt-4 fw-bold text-dark">Yah, produk tidak ditemukan</h5>
                            <p class="text-muted">Coba gunakan filter lain atau cari kata kunci yang berbeda.</p>
                            <div class="mt-2">
                                <a href="{{ route('customer.product.index') }}" class="btn btn-success rounded-pill px-4">Lihat Semua Produk</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection