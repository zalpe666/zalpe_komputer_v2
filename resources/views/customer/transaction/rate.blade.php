@extends('layouts.customer-layout')

@section('title', 'Rate Product')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            {{-- Header --}}
            <div class="mb-4">
                <h4 class="fw-semibold mb-1">Beri Rating</h4>
                <p class="text-muted mb-0">
                    {{ $product->name }}
                </p>
            </div>

            <form action="{{ route('customer.rating.rate.submit', [$transaction->id, $product->id]) }}" method="POST">
                @csrf

                {{-- Star Rating --}}
                <div class="mb-4 text-center">
                    <label class="form-label d-block mb-2">Pilih Rating</label>

                    <div id="starRating" class="d-flex justify-content-center gap-2 fs-3">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star text-muted star text-warningeb" data-value="{{ $i }}"></i>
                        @endfor
                    </div>

                    <input type="hidden" name="rating" id="ratingInput" required>
                </div>

                {{-- Review --}}
                <div class="mb-4">
                    <label for="review" class="form-label">Ulasan (opsional)</label>
                    <textarea 
                        name="review" 
                        id="review" 
                        class="form-control rounded-3" 
                        rows="4"
                        placeholder="Bagikan pengalaman kamu..."
                    ></textarea>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('customer.transaction.show', $transaction->id) }}" 
                       class="btn btn-light border px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        Kirim Rating
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('script')
<script>
const stars = document.querySelectorAll('.star');
const input = document.getElementById('ratingInput');

stars.forEach((star, index) => {
    star.addEventListener('click', () => {
        let rating = star.getAttribute('data-value');
        input.value = rating;

        stars.forEach((s, i) => {
            if (i < rating) {
                s.classList.remove('bi-star');
                s.classList.add('bi-star-fill', 'text-warning');
            } else {
                s.classList.remove('bi-star-fill', 'text-warning');
                s.classList.add('bi-star');
            }
        });
    });
});
</script>
@endpush