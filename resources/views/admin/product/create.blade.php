@extends('layouts.dashboard')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb custom-breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}" class="breadcrumb-link">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.product.index') }}" class="breadcrumb-link">
                        Product
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Create
                </li>
            </ol>
        </nav>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-semibold mb-0">Product List</h5>
                    <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Product
                    </a>
                </div>
            </div>
            <div class="px-4">
                <form method="POST"
                    action="{{ isset($product) ? route('admin.product.update', $product->id) : route('admin.product.store') }}">
                    @csrf

                    <div class="row">
                        <!-- LEFT -->
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <img id="preview-image" src="{{ $product->image ?? 'https://via.placeholder.com/150' }}"
                                    class="img-thumbnail" style="max-height:150px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="text" name="image" id="image-input" class="form-control"
                                    placeholder="https://example.com/image.jpg" value="{{ $product->image ?? '' }}"
                                    oninput="updatePreview()">
                            </div>
                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-8">

                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $product->name ?? '' }}">
                            </div>

                            <div class="row">
                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-control">
                                        <option disabled selected>Pilih Category</option>
                                        @foreach ($categories as $c)
                                            <option value="{{ $c->id }}"
                                                {{ isset($product) && $product->category_id == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Brand -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-control">
                                        <option disabled selected>Pilih Brand</option>
                                        @foreach ($brands as $b)
                                            <option value="{{ $b->id }}"
                                                {{ isset($product) && $product->brand_id == $b->id ? 'selected' : '' }}>
                                                {{ $b->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Price -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga</label>
                                    <input type="text" id="price-display" class="form-control"
                                        placeholder="Masukkan harga"
                                        value="{{ isset($product) ? number_format($product->default_price, 0, ',', '.') : '' }}">

                                    <!-- hidden input (yang dikirim ke backend) -->
                                    <input type="hidden" name="default_price" id="price">
                                </div>

                                <!-- Stock -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control"
                                        value="{{ $product->stock ?? '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Type</label>
                                <select name="type" class="form-control">
                                    <option disabled {{ !isset($product) ? 'selected' : '' }}>Pilih Type</option>

                                    <option value="Product"
                                        {{ isset($product) && $product->type == 'Product' ? 'selected' : '' }}>
                                        Product
                                    </option>

                                    <option value="Games"
                                        {{ isset($product) && $product->type == 'Games' ? 'selected' : '' }}>
                                        Games
                                    </option>

                                    <option value="Digital"
                                        {{ isset($product) && $product->type == 'Digital' ? 'selected' : '' }}>
                                        Digital
                                    </option>

                                    <option value="Steam Wallet"
                                        {{ isset($product) && $product->type == 'Steam Wallet' ? 'selected' : '' }}>
                                        Steam Wallet
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="form-check form-switch d-flex align-items-center gap-2"> <input type="hidden"
                                        name="active" value="0"> <input class="form-check-input" type="checkbox"
                                        name="active" value="1"
                                        {{ isset($product) ? ($product->active ? 'checked' : '') : 'checked' }}> <span
                                        class="small text-muted">Active</span>
                                </div>
                            </div>

                            <button class="btn btn-success">
                                {{ isset($product) ? 'Update' : 'Simpan' }}
                            </button>

                        </div>
                    </div>
                </form>
            </div>

        </div>


    </div>

    <!-- JS Preview -->
    <script>
        function updatePreview() {
            const input = document.getElementById('image-input').value;
            const preview = document.getElementById('preview-image');

            if (input) {
                preview.src = input;
            } else {
                preview.src = 'https://via.placeholder.com/150';
            }
        }
    </script>
    <script>
        function updatePreview() {
            let input = document.getElementById('image-input').value;
            document.getElementById('preview-image').src = input;
        }

        // FORMAT HARGA REALTIME
        const display = document.getElementById('price-display');
        const hidden = document.getElementById('price');

        display.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, ''); // ambil angka saja
            hidden.value = value; // simpan tanpa koma

            if (value) {
                this.value = new Intl.NumberFormat('id-ID').format(value);
            } else {
                this.value = '';
            }
        });

        // INIT VALUE SAAT EDIT
        window.addEventListener('load', () => {
            let val = display.value.replace(/\D/g, '');
            hidden.value = val;
        });
    </script>
@endsection
