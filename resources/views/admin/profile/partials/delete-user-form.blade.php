<div>
    <div class="fs-4 mb-0 text-gray-900">
        {{ __('Delete Account') }}
    </div>
    <p class="mt-1 small text-gray-600">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>
</div>

<div class="row-cols-auto">

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
        {{ __('Delete Account') }}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteUserModalLabel">{{ __('Delete Account') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="post" action="{{ route('admin.profile.destroy') }}" id="userDeletionForm" class="mt-4 needs-validation" novalidate>
                        @csrf
                        @method('delete')

                        <div class="h4 fw-bold text-gray-900">
                            {{ __('Are you sure you want to delete your account?') }}
                        </div>

                        <p class="mt-1 small text-gray-600">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="current_password">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control user-delete @error('password') is-invalid @enderror"
                                   style="border-color: red"
                                   id="password"
                                   name="password"
                                   placeholder="Password..."
                                   required />
                            @error('current_password')
                            <div class="invalid-feedback">
                                {{ $errors->userDeletion->get('password') }}
                            </div>
                            @enderror
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger w-100" onclick="$('form#userDeletionForm').submit();">{{ __('Delete Account') }}</button>
                </div>
            </div>
        </div>
    </div>

</div>
