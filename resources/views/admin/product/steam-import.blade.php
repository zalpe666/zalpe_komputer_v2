@extends('layouts.dashboard')
    

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>Import Game dari Steam</h4>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.product.steam-import.store') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            Steam App ID
                        </label>

                        <input type="number" name="app_id" class="form-control" placeholder="Contoh: 1174180"
                            value="{{ old('app_id') }}" required>

                        <small class="text-muted">
                            Masukkan Steam App ID game yang ingin diimport.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Import Game
                    </button>

                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            </div>
        </div>
    </div>
@endsection
