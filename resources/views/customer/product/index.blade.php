@extends('layouts.app')

@section('title', 'Products')

@section('content')

    <div class="container py-4">

        {{-- PRODUCT LIST --}}
        <div class="row">
            <div class="col-md-3 ">
                <div class="card bg-white border-1 rounded-4 shadow-sm p-3">
                    <form method="GET" action="{{ route('customer.product.index') }}">
                        {{-- CATEGORY --}}
                        @if ($categories->count())
                            <div class="mb-4">
                                <h6 class="fw-bold">Categories</h6>

                                @foreach ($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]"
                                            value="{{ $category->id }}"
                                            {{ in_array($category->id, request('category', [])) ? 'checked' : '' }}>

                                        <label class="form-check-label">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        {{-- BRAND --}}
                        @if ($brands->count())
                            <div class="mb-4">
                                <h6 class="fw-bold">Brand</h6>

                                @foreach ($brands as $brand)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="brand[]"
                                            value="{{ $brand->id }}"
                                            {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>

                                        <label class="form-check-label">
                                            {{ $brand->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- BUTTON --}}
                        <button class="btn btn-primary w-100 mb-2">Apply Filter</button>

                        <a href="{{ route('customer.product.index') }}" class="btn btn-outline-secondary w-100">
                            Reset
                        </a>

                    </form>
                </div>


            </div>
            <div class="col-md-9">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h3 class="fw-bold mb-0">Products</h3>

                        <form method="GET" action="{{ route('customer.product.index') }}">
                            {{-- Biar filter lain tidak hilang --}}
                            @foreach (request()->except('sort') as $key => $value)
                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="">Sort By</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                    Price: Low → High
                                </option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                    Price: High → Low
                                </option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                    Name: A → Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                    Name: Z → A
                                </option>
                            </select>
                        </form>

                    </div>
                    @if (request('search'))
                        <small class="text-muted">
                            Search result for: <b>"{{ request('search') }}"</b>
                        </small>
                    @endif
                </div>
                <div class="row g-3">

                    @forelse ($products as $product)
                        <div class="col-md-3">

                            <x-product-card :product="$product" />

                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Product not found</p>
                        </div>
                    @endforelse

                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

@endsection
