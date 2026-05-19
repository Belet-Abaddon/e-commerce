@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

@if($isAdmin)
    @extends('layouts.admin')
    
    @section('header', 'My Profile')

    @section('content')
        {{ $slot }}
    @endsection
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profile') }}
            </h2>
        </x-slot>

        {{ $slot }}
    </x-app-layout>
@endif