@extends('admin.auth.app')
@section('title')
    __{{ 'Confirm password' }}
@endsection
@section('content')
    <small class="mb-3">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </small>

    <form method="POST" action="{{ route('admin.password.confirm') }}" class="needs-validation" novalidate>
        @csrf
        @honeypot

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   placeholder="Your password..."
                   required
                   autocomplete="current-password" />
            @error('password')
            <div class="invalid-feedback">
                {{ $errors->get('password') }}
            </div>
            @enderror
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">
            {{ __('Confirm') }}
        </button>
    </form>
@endsection
