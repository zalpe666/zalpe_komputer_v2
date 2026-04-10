@extends('layouts.dashboard')

@section('content')
    <div class="container">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb custom-breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.product.index') }}">Product</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <h4 class="fw-semibold mb-3">Edit Product</h4>

                <form action="{{ route('admin.product.update', $product->id) }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control rounded-4"
                                value="{{ old('name', $product->name) }}" required>
                        </div>

                        <!-- Category -->
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control rounded-4">
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ $product->category_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="col-md-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-control rounded-4">
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ $product->brand_id == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control rounded-4">
                                <option value="Product" {{ $product->type == 'Product' ? 'selected' : '' }}>Product
                                </option>
                                <option value="Games" {{ $product->type == 'Games' ? 'selected' : '' }}>Games</option>
                                <option value="Digital" {{ $product->type == 'Digital' ? 'selected' : '' }}>Digital
                                </option>
                                <option value="Steam Wallet" {{ $product->type == 'Steam Wallet' ? 'selected' : '' }}>Steam
                                    Wallet</option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div class="col-md-3">
                            <label class="form-label">Default Price</label>
                            <input type="number" name="default_price" class="form-control rounded-4"
                                value="{{ old('default_price', $product->default_price) }}">
                        </div>

                        <!-- Discount -->
                        <div class="col-md-3">
                            <label class="form-label">Discount</label>
                            <input type="number" name="discount" class="form-control rounded-4"
                                value="{{ old('discount', $product->discount) }}">
                        </div>

                        <!-- Stock -->
                        <div class="col-md-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control rounded-4"
                                value="{{ old('stock', $product->stock) }}">
                        </div>

                        <!-- Weight -->
                        <div class="col-md-3">
                            <label class="form-label">Weight (gram)</label>
                            <input type="number" name="weight" class="form-control rounded-4"
                                value="{{ old('weight', $product->weight) }}">
                        </div>

                        <!-- Image -->
                        <div class="col-md-6">
                            <label class="form-label">Image URL</label>
                            <input type="text" name="image" id="imageInput" class="form-control rounded-4"
                                placeholder="https://example.com/image.jpg" value="{{ old('image', $product->image) }}">

                            <!-- Preview -->
                            <div class="mt-3">
                                <img id="imagePreview" src="{{ old('image', $product->image) }}" alt="Preview"
                                    class="img-fluid rounded-4 border" style="max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control rounded-4">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <!-- Active -->
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" name="active" class="form-check-input"
                                    {{ $product->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">Active Product</label>
                            </div>
                        </div>

                    </div>

                    <!-- Action -->
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary rounded-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-4">
                            Update Product
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('imagePreview');

            input.addEventListener('input', function() {
                let url = this.value;

                if (url) {
                    preview.src = url;
                } else {
                    preview.src = '';
                }
            });

            // fallback kalau gambar error
            preview.onerror = function() {
                this.src = 'https://via.placeholder.com/300x200?text=No+Image';
            };
        });
    </script>
@endpush
