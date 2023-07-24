@extends('admin.auth.app')
@section('title')
    {{ __('Reset password') }}
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.password.store') }}" class="needs-validation" novalidate>
        @csrf
        @honeypot

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label class="form-label fw-bold" for="email">{{ __('Email') }} <span class="text-danger">*</span></label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email'), $request->email }}"
                   placeholder="Your email..."
                   required
                   autofocus
                   autocomplete="username" />
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="form-label fw-bold" for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   placeholder="Your password..."
                   required
                   autocomplete="new-password" />
            @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label class="form-label fw-bold" for="password_confirmation">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
            <input type="password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   id="password_confirmation"
                   name="password_confirmation"
                   placeholder="Your password..."
                   required
                   autocomplete="new-password" />
            @error('password_confirmation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">
            {{ __('Reset Password') }}
        </button>
    </form>
@endsection
