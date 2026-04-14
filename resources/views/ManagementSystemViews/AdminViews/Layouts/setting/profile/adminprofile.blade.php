<!DOCTYPE html>
<html>

<head>
    <title>Profile Information</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/adminSidbar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .main-wrapper {
            display: flex;
            gap: 10px;
            min-height: 100vh;
        }

        .content-area {
            flex: 1;
            width: 100%;
        }

        .profile-card {
            background: var(--card);
            border-radius: 20px;
            padding: 30px;
        }

        .mt-4 {
            margin-top: 0 !important;
        }

        .profile-title {
            color: #00a8a8;
            font-weight: 600;
        }

        .profile-subtitle {
            color: #888;
            font-size: 12px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn-custom {
            min-width: 120px;
        }

        .btn-save {
            background: #00a8a8;
            color: white;
        }

        .btn-save:hover {
            background: #009090;
        }

        .btn-cancel {
            border: 1px solid #00a8a8;
            color: #00a8a8;
        }

        .btn-cancel:hover {
            background: #e6f7f7;
        }

        .form-label {
            font-size: 13px;
        }

        .form-control {
            font-size: 13px;
        }

        .border {
            font-size: 13px;
        }

        .text-muted {
            font-size: 11px;
        }

        /* Custom Alert Styles */
        .alert-success {
            
            width: 30%;
            background: #ffffff !important;
            color: #334155 !important;
            border: none !important;
            border-left: 4px solid #10b981 !important;
            border-radius: 8px !important;
            padding: 16px 20px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
             position: fixed;
            top: 5px;
            right: 30px;
            z-index: 9999;
        }

        .alert-success .btn-close {
            color: #334155;
        }
    </style>
</head>

<body>

<div class="main-wrapper">

        {{-- Sidebar --}}
        @include('ManagementSystemViews.AdminViews.Layouts.aside')

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
    <script>
        function previewFile(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('previewImage').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>

</html>
