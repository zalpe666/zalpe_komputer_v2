<section class="mb-4">

    <div class="card border-danger shadow-sm">
        <div class="card-body">

            <!-- Header -->
            <h5 class="card-title text-danger fw-bold">
                Delete Account
            </h5>

            <p class="text-muted small">
                Once your account is deleted, all of its resources and data will be permanently deleted.
                Before deleting your account, please download any data or information that you wish to retain.
            </p>

            <!-- Button Trigger Modal -->
            <button class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal">
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
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm.
                        </p>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
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
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
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