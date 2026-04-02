@extends('layouts.customer-layout')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    
    <div class="text-center">
        
        {{-- ICON --}}
        <div class="mb-3">
            <i class="bi bi-tools display-1 text-primary"></i>
        </div>

        {{-- TITLE --}}
        <h3 class="fw-bold">Under Construction</h3>

        {{-- DESC --}}
        <p class="text-muted">
            Halaman ini sedang dalam pengembangan 🚧 <br>
            Silakan kembali lagi nanti.
        </p>

        {{-- BUTTON --}}
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-2">
            Kembali
        </a>
    </div>
</div>
@endsection