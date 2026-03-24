@extends('layouts.dashboard')

@section('title', 'Admin Panel')

@section('content')

    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb custom-breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}" class="breadcrumb-link">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Profile
                </li>
            </ol>
        </nav>
        <h3 class="mb-4 fw-bold">Profile</h3>
        <div class="row g-4">
            <!-- Update Profile -->
            <div class="col-12">
                @include('profile.partials.update-profile-information-form')
            </div>
            <!-- Update Password -->
            <div class="col-12">
                @include('profile.partials.update-password-form')
            </div>
            <!-- Delete User -->
            <div class="col-12">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>


@endsection
