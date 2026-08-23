@extends('Layout.POSUser.app')
@section('title', 'Edit Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Profile/edit.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="profile-edit-page">
        <a href="{{ route('profile') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Profile
        </a>

        <div class="profile-card">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="profile-title">Edit Profile</h4>
                    <p class="profile-subtitle">Update your personal information and contact details</p>
                </div>
                <a href="{{ route('user.password.change') }}" class="btn btn-outline-secondary btn-sm">
                    Change Password
                </a>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="d-flex align-items-center gap-4 mb-4">
                    <img src="{{ auth()->user()->profile_image_display }}" class="profile-avatar" id="previewImage">
                    <div>
                        <label class="btn btn-light border">
                            Change Photo
                            <input type="file" name="avatar" hidden onchange="previewFile(event)">
                        </label>
                        <div class="text-muted small mt-1">JPG, PNG or GIF. Max size 2MB</div>
                        @error('avatar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', auth()->user()->dob) }}">
                    @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', auth()->user()->location) }}">
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('profile') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-save">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewFile(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const image = document.getElementById('previewImage');
                if (image) {
                    image.src = reader.result;
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
