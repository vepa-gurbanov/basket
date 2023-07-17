<div>
    <div class="fs-4 mb-0 text-gray-800">
        {{ __('Update Password') }}
    </div>
    <p class="mt-1 small text-gray-600">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </p>
</div>

<form method="post" action="{{ route('admin.password.update') }}" class="mt-4 needs-validation" novalidate>
    @csrf
    @method('put')

    <!-- Current Password -->
    <div class="mb-3">
        <label class="form-label fw-bold" for="current_password">{{ __('Current Password') }} <span class="text-danger">*</span></label>
        <input type="password"
               class="form-control w-50 @error('current_password') is-invalid @enderror"
               id="current_password"
               name="current_password"
               placeholder="Current password..."
               required
               autocomplete="current-password" />
        @error('current_password')
        <div class="invalid-feedback">
            {{ $errors->updatePassword->get('current_password') }}
        </div>
        @enderror
    </div>

    <!-- New Password -->
    <div class="mb-3">
        <label class="form-label fw-bold" for="password">{{ __('New Password') }} <span class="text-danger">*</span></label>
        <input type="password"
               class="form-control w-50 @error('password') is-invalid @enderror"
               id="password"
               name="password"
               placeholder="New password..."
               required
               autocomplete="new-password" />
        @error('password')
        <div class="invalid-feedback">
            {{ $errors->updatePassword->get('password') }}
        </div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label class="form-label fw-bold" for="password_confirmation">{{ __('Confirm  Password') }} <span class="text-danger">*</span></label>
        <input type="password"
               class="form-control w-50 @error('password_confirmation') is-invalid @enderror"
               id="password_confirmation"
               name="password_confirmation"
               placeholder="New password..."
               required
               autocomplete="new-password" />
        @error('password_confirmation')
        <div class="invalid-feedback">
            {{ $errors->updatePassword->get('password_confirmation') }}
        </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-50">
        {{ __('Save') }}
    </button>

</form>
