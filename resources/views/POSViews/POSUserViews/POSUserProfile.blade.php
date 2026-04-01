<!DOCTYPE html>
<html>

<head>
    <title>Profile Information</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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
    </style>
</head>

<body>

    <div class="app-shell" id="appShell">

        {{-- Sidebar --}}
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        <div class="page-wrap">
            <div class="container mt-4">

                <div class="profile-card">

                    {{-- Header --}}
                    <h4 class="profile-title">Profile Information</h4>
                    <p class="profile-subtitle">
                        Update your personal information and contact details
                    </p>

                    {{-- Form --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Avatar + Upload --}}
                        <div class="d-flex align-items-center gap-4 mb-4">

                            @php
                            $avatar = auth()->user()->avatar ?? null;
                            $avatarUrl = $avatar
                                ? (preg_match('/^https?:\/\//i', $avatar) ? $avatar : asset($avatar))
                                : 'https://via.placeholder.com/80';
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
                            </div>

                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}">
                        </div>

                        {{-- Email + Phone --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', auth()->user()->email) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="+855..."
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}">
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control"
                                value="{{ old('dob', auth()->user()->dob ?? '') }}">
                        </div>

                        {{-- Location --}}
                        <div class="mb-4">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control"
                                value="{{ old('location', auth()->user()->location ?? '') }}">
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-center gap-3">

                            <a href="{{ url()->previous() }}" class="btn btn-cancel btn-custom">
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
