@extends('layouts.admin')

@section('header', 'Edit Admin Profile')

@section('content')
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Update Profile Information Form --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
            <div class="max-w-3xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password Form --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
            <div class="max-w-3xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete User Form --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
            <div class="max-w-3xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
@endsection