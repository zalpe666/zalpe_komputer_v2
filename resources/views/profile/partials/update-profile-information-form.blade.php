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
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $user->name) }}"
                           required
                           autofocus
                           autocomplete="name">

                    @error('name')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}"
                           required
                           autocomplete="username">

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Email Verification -->
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">

                            <div class="alert alert-warning py-2">
                                Your email address is unverified.
                            </div>

                            <button form="send-verification"
                                    class="btn btn-sm btn-outline-primary">
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