@extends('layouts.app')

@section('title', 'Admin Panel')
@section('content')
    <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">My Addresses</h4>
        <a href="{{ route('customer.address.create') }}" class="btn btn-primary">
            + Add Address
        </a>
    </div>

    @forelse ($addresses as $item)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">
                            {{ $item->name }} 
                            <span class="text-muted">({{ $item->phone }})</span>
                        </h6>

                        <small class="text-muted">
                            {{ $item->address }},
                            {{ $item->district->name }},
                            {{ $item->city->name }},
                            {{ $item->province->name }}
                        </small>
                    </div>

                    @if($item->is_default)
                        <span class="badge bg-success">Primary</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted">
            No address yet 🚫
        </div>
    @endforelse
</div>
@endsection