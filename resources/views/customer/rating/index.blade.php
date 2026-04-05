@extends('layouts.customer-layout')

@section('title', 'Rate Product')

@section('content')
<div class="container py-4">

    <h3 class="mb-4">Rating Produk</h3>

    {{-- Nav Tabs --}}
    <ul class="nav nav-tabs mb-3" id="ratingTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing"
                type="button" role="tab" aria-controls="ongoing" aria-selected="true">
                Ongoing Rating <span class="badge bg-primary ms-1">{{ $ongoing->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed"
                type="button" role="tab" aria-controls="completed" aria-selected="false">
                Completed Rating <span class="badge bg-success ms-1">{{ $completed->count() }}</span>
            </button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="ratingTabContent">

        {{-- Ongoing Rating --}}
        <div class="tab-pane fade show active" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
            @if($ongoing->isEmpty())
                <p class="text-muted">Tidak ada produk yang menunggu rating.</p>
            @else
                @foreach($ongoing as $item)
                <div class="card mb-3 shadow-sm rounded-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">{{ $item->qty }} x Rp {{ number_format($item->price) }}</small>
                        </div>
                        <form action="" method="POST" class="d-flex gap-2 align-items-center">
                            @csrf
                            <select name="rating" class="form-select form-select-sm" required>
                                <option value="">Rate ⭐</option>
                                @for($i=1; $i<=5; $i++)
                                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                                @endfor
                            </select>
                            <input type="text" name="review" class="form-control form-control-sm" placeholder="Opsional">
                            <button class="btn btn-success px-3">Kirim</button>
                        </form>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        {{-- Completed Rating --}}
        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
            @if($completed->isEmpty())
                <p class="text-muted">Belum ada produk yang sudah di-rate.</p>
            @else
                @foreach($completed as $item)
                <div class="card mb-3 shadow-sm rounded-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">{{ $item->qty }} x Rp {{ number_format($item->price) }}</small>
                            <div>Rating: {{ $item->rating }} ⭐</div>
                            @if($item->review)
                                <small class="text-muted">"{{ $item->review }}"</small>
                            @endif
                        </div>
                        <span class="badge bg-success">Rated</span>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

    </div>

</div>
@endsection