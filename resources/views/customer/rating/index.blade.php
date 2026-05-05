@extends('layouts.customer-layout')

@section('title', 'Rate Product')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-12">
            
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                <i class="bi bi-star-half fs-2 text-warning me-3"></i>
                <h3 class="mb-0 fw-bold text-dark">Rating Produk</h3>
            </div>

            {{-- Nav Pills (Lebih modern dari Tabs biasa) --}}
            <ul class="nav nav-pills mb-4 p-2 bg-white rounded-4 shadow-sm border" id="ratingTab" role="tablist">
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link active w-100 rounded-3 fw-semibold py-2" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing"
                        type="button" role="tab" aria-controls="ongoing" aria-selected="true">
                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Rating 
                        <span class="badge bg-white text-primary ms-2 rounded-pill border">{{ $ongoing->count() }}</span>
                    </button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link w-100 rounded-3 fw-semibold py-2" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed"
                        type="button" role="tab" aria-controls="completed" aria-selected="false">
                        <i class="bi bi-check2-circle me-1"></i> Selesai
                        <span class="badge bg-white text-success ms-2 rounded-pill border">{{ $completed->count() }}</span>
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="ratingTabContent">

                {{-- Ongoing Rating --}}
                <div class="tab-pane fade show active" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
                    @if($ongoing->isEmpty())
                        <div class="text-center py-5 bg-light rounded-4 border">
                            <i class="bi bi-box2-heart fs-1 text-muted mb-2 d-block"></i>
                            <h5 class="text-muted fw-bold">Belum Ada Produk</h5>
                            <p class="text-muted mb-0">Tidak ada produk yang menunggu untuk diberikan rating saat ini.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($ongoing as $item)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3">
                                            <div>
                                                <h5 class="fw-bold mb-1 text-dark">{{ $item->product->name }}</h5>
                                                <span class="badge bg-light text-secondary border">
                                                    {{ $item->qty }} Item x Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                Belum Dinilai
                                            </span>
                                        </div>
                                        
                                        {{-- Form Rating dengan Grid System agar rapi di Mobile --}}
                                        <form action="" method="POST" class="bg-light p-3 rounded-3 border">
                                            @csrf
                                            <div class="row g-2 align-items-center">
                                                <div class="col-md-3">
                                                    <select name="rating" class="form-select border-warning text-dark fw-semibold" required>
                                                        <option value="">Pilih Rating ⭐</option>
                                                        @for($i=5; $i>=1; $i--)
                                                            <option value="{{ $i }}">{{ $i }} Bintang ({{ str_repeat('⭐', $i) }})</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="review" class="form-control" placeholder="Tuliskan ulasan pengalaman Anda (Opsional)...">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                                                        <i class="bi bi-send me-1"></i> Kirim
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Completed Rating --}}
                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    @if($completed->isEmpty())
                        <div class="text-center py-5 bg-light rounded-4 border">
                            <i class="bi bi-star fs-1 text-muted mb-2 d-block"></i>
                            <h5 class="text-muted fw-bold">Belum Ada Ulasan</h5>
                            <p class="text-muted mb-0">Kamu belum memberikan rating untuk produk apapun.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($completed as $item)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h5 class="fw-bold mb-1 text-dark">{{ $item->product->name }}</h5>
                                                <span class="text-muted small">
                                                    Deli: {{ $item->qty }} Item x Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-1 d-inline-block">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Selesai Dinilai
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Kotak Ulasan --}}
                                        <div class="bg-light p-3 rounded-3 border-start border-warning border-4">
                                            <div class="mb-2 text-warning">
                                                @for($i=1; $i<=5; $i++)
                                                    @if($i <= $item->rating)
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="text-dark fw-bold ms-2">{{ $item->rating }} / 5</span>
                                            </div>
                                            
                                            @if($item->review)
                                                <p class="mb-0 text-secondary fst-italic">"{{ $item->review }}"</p>
                                            @else
                                                <p class="mb-0 text-muted fst-italic small">Tidak ada ulasan tertulis.</p>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection