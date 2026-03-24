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
                    <input type="password"
                           name="current_password"
                           class="form-control"
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
                    <input type="password"
                           name="password"
                           class="form-control"
                           autocomplete="new-password">

                    @error('password', 'updatePassword')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
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