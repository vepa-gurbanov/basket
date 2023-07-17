<div>
    <div class="fs-4 mb-0 text-gray-800">
        {{ __('Profile Information') }}
    </div>
    <p class="mt-1 small text-gray-600">
        {{ __("Update your account's profile information and email address.") }}
    </p>
</div>

<form method="post" action="{{ route('admin.profile.update') }}" class="mt-4 needs-validation" novalidate>
@csrf
@method('patch')

    <!-- Email Address -->
    <div class="mb-3">
        <label class="form-label fw-bold" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
        <input type="text"
               class="form-control w-50 @error('name') is-invalid @enderror"
               id="name"
               name="name"
               value="{{ old('name', $user->name) }}"
               placeholder="Your name..."
               required
               autofocus
               autocomplete="name" />
        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="mb-3">
        <label class="form-label fw-bold" for="email">{{ __('Email address') }} <span class="text-danger">*</span></label>
        <input type="email"
               class="form-control w-50 @error('email') is-invalid @enderror"
               id="email"
               name="email"
               value="{{ old('email', $user->email) }}"
               placeholder="Your email..."
               required
               autocomplete="username" />
        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-50">
        {{ __('Save') }}
    </button>

</form>
