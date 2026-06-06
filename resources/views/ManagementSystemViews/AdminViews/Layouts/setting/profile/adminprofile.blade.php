@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Profile Information')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/setting/profile/adminprofile.css') }}">
@endpush

@section('content')
<div class="main-wrapper">

        {{-- Sidebar --}}
        

    <div class="content-area">
            <div class="container mt-4">

                <div class="profile-card">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Header --}}
                    <h4 class="profile-title">Admin Profile Info</h4>
                    <p class="profile-subtitle">
                        Update your personal information and contact details
                    </p>

                    {{-- Form --}}
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Avatar + Upload --}}
                        <div class="d-flex align-items-center gap-4 mb-4">

                            @php
                            $avatarUrl = auth()->user()->profile_image_display ?? 'https://via.placeholder.com/80';
                            @endphp
                            <img src="{{ $avatarUrl }}" class="profile-avatar" id="previewImage">

                            <div>
                                <label class="btn btn-light border">
                                    Change Photo
                                    <input type="file" name="avatar" hidden onchange="previewFile(event)">
                                </label>

                                <div class="text-muted small mt-1">
                                    JPG, PNG or GIF. Max size 2MB
                                </div>
                                @error('avatar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', auth()->user()->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email + Phone --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', auth()->user()->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+855..."
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror"
                                value="{{ old('dob', auth()->user()->dob ?? '') }}">
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Location --}}
                        <div class="mb-4">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location', auth()->user()->location ?? '') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-center gap-3">

                            <a href="{{ route('admin.profile') }}" class="btn btn-cancel btn-custom">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-save btn-custom">
                                Save
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>

    {{-- Preview Image Script --}}
@endsection

@push('scripts')
<script>
        function previewFile(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('previewImage').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
