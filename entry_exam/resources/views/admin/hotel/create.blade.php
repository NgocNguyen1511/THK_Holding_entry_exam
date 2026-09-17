@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
<div class="create-hotel-wrapper">
    <div class="create-hotel-content">
        <div class="page-header">
            <h1 class="page-title">Create New Hotel</h1>
            <p class="page-desc">Fill in the hotel details below to add a new property to the directory.</p>
        </div>

        @if(session('success'))
            <x-alert type="success" title="Registration Completed">
                <span>{{ session('success') }}</span>
                <a href="{{ route('adminHotelSearchPage') }}" class="alert-link">Back to Hotel Search &rarr;</a>
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger" title="Error">
                <span>{{ session('error') }}</span>
            </x-alert>
        @elseif($errors->has('error'))
            <x-alert type="danger" title="Error">
                <span>{{ $errors->first('error') }}</span>
            </x-alert>
        @endif

        <form action="{{ route('adminHotelCreateProcess') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <x-admin.create-hotel-form :prefectures="$prefectures" />

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Create Hotel
                </button>
                <a href="{{ route('adminHotelSearchPage') }}" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection