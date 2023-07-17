@extends('admin.auth.app')
@section('title')
__{{ 'Forgot password' }}
@endsection
@section('content')

        <div class="small mb-3 fw-normal text-start">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="alert alert-success text-success-emphasis mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            @honeypot

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
                       autofocus />
            </div>
            @error('email')
            <div class="mb-3 text-danger small">
                {{ $message }}
            </div>
            @enderror

            <button class="btn btn-primary w-100 py-2" type="submit">
                {{ __('Email Password Reset Link') }}
            </button>

        </form>


@endsection
