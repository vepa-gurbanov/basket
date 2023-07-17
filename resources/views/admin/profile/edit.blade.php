@extends('admin.layouts.app')
@section('title')
    {{ __('Profile') }}
@endsection
@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Profile') }}</h1>
    </div>

    <div class="mx-3">

        <div class="mb-5">
            @include('admin.profile.partials.update-profile-information-form')
        </div>

        <div class="mb-5">
            @include('admin.profile.partials.update-password-form')
        </div>

        <div class="mb-5">
            @include('admin.profile.partials.delete-user-form')
        </div>

    </div>

@endsection
