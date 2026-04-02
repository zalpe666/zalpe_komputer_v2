@extends('layouts.customer-layout')

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
                <section class="mb-4">

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <!-- Header -->
                            <h5 class="card-title fw-bold">
                                Profile Information
                            </h5>

                            <p class="text-muted small">
                                Update your account's profile information and email address.
                            </p>

                            <!-- Form resend verification -->
                            <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <!-- Main Form -->
                            <form method="POST" action="{{ route('customer.profile.update') }}">
                                @csrf
                                @method('PATCH')

                                <!-- Name -->
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">

                                    @error('name')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required autocomplete="username">

                                    @error('email')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <!-- Email Verification -->
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                        <div class="mt-2">

                                            <div class="alert alert-warning py-2">
                                                Your email address is unverified.
                                            </div>

                                            <button form="send-verification" class="btn btn-sm btn-outline-primary">
                                                Re-send Verification Email
                                            </button>

                                            @if (session('status') === 'verification-link-sent')
                                                <div class="alert alert-success mt-2 py-2">
                                                    A new verification link has been sent to your email address.
                                                </div>
                                            @endif

                                        </div>
                                    @endif
                                </div>

                                <!-- Button -->
                                <div class="d-flex align-items-center gap-3">

                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>

                                    @if (session('status') === 'profile-updated')
                                        <div class="alert alert-success py-1 px-2 mb-0">
                                            Saved.
                                        </div>
                                    @endif

                                </div>

                            </form>

                        </div>
                    </div>

                </section>
            </div>
            <!-- Update Password -->
            <div class="col-12">
                <section class="mb-4">

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <!-- Header -->
                            <h5 class="card-title fw-bold">
                                Update Password
                            </h5>

                            <p class="text-muted small">
                                Ensure your account is using a long, random password to stay secure.
                            </p>

                            <!-- Form -->
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control"
                                        autocomplete="current-password">

                                    @error('current_password', 'updatePassword')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" autocomplete="new-password">

                                    @error('password', 'updatePassword')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        autocomplete="new-password">

                                    @error('password_confirmation', 'updatePassword')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Button + Status -->
                                <div class="d-flex align-items-center gap-3">

                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>

                                    @if (session('status') === 'password-updated')
                                        <div class="alert alert-success py-1 px-2 mb-0">
                                            Saved.
                                        </div>
                                    @endif

                                </div>

                            </form>

                        </div>
                    </div>

                </section>
            </div>
            <!-- Delete User -->
            <div class="col-12">
                <section class="mb-4">

                    <div class="card border-danger shadow-sm">
                        <div class="card-body">

                            <!-- Header -->
                            <h5 class="card-title text-danger fw-bold">
                                Delete Account
                            </h5>

                            <p class="text-muted small">
                                Once your account is deleted, all of its resources and data will be permanently deleted.
                                Before deleting your account, please download any data or information that you wish to
                                retain.
                            </p>

                            <!-- Button Trigger Modal -->
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                Delete Account
                            </button>

                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <form method="POST" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('DELETE')

                                    <!-- Header -->
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger">
                                            Confirm Delete Account
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- Body -->
                                    <div class="modal-body">

                                        <p class="small text-muted">
                                            Once your account is deleted, all of its resources and data will be permanently
                                            deleted.
                                            Please enter your password to confirm.
                                        </p>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Enter your password">

                                            @error('password', 'userDeletion')
                                                <div class="text-danger small mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>

                                    <!-- Footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="submit" class="btn btn-danger">
                                            Delete Account
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>


@endsection
