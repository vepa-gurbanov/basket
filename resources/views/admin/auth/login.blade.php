@extends('admin.auth.app')
@section('title')
    __{{ 'Login' }}
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.login') }}" class="needs-validation" novalidate>
        @csrf
        @honeypot
        <h1 class="h3 mb-3 fw-normal text-center">{{ __('Welcome back!') }}</h1>

        <!-- Email Address -->
        <div class="mb-3">
            <label class="form-label fw-bold" for="email">{{ __('Email address') }} <span class="text-danger">*</span></label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
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
                   autocomplete="current-password" />
            @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-check text-start my-3">
            <input class="form-check-input"
                   type="checkbox"
                   id="remember"
                   name="remember" />
            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">
            {{ __('Login') }}
        </button>

        @if (Route::has('admin.password.request'))
            <a href="{{ route('admin.password.request') }}" class="small">
                {{ __('Forgot your password?') }}
            </a>
        @endif
    </form>
@endsection
