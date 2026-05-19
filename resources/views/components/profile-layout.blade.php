@if($isAdmin)
    @extends('layouts.admin')
    @section('content')
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{ $slot }}
        </div>
    @endsection
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profile') }}
            </h2>
        </x-slot>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{ $slot }}
        </div>
    </x-app-layout>
@endif