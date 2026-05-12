@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Profile Information')

@push('styles')

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')

    <div class="page-wrap">



        <div class="profile-card">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>
            @endif

            {{-- Header --}}
            <div class="d-flex align-items-center justify-content-between mb-4">

                <div>

                    <h4 class="profile-title">
                        Profile Information
                    </h4>

                    <p class="profile-subtitle">
                        Update your personal information and contact details
                    </p>

                </div>

                <a href="{{ route('user.password.change') }}" class="btn btn-outline-secondary btn-sm">
                    Change Password
                </a>

            </div>

            {{-- FORM --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Avatar --}}
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

                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', auth()->user()->phone) }}">

                    </div>

                </div>

                {{-- Date of Birth --}}
                <div class="mb-3">

                    <label class="form-label">
                        Date of Birth
                    </label>

                    <input type="date" name="dob" class="form-control" value="{{ old('dob', auth()->user()->dob) }}">

                </div>

                {{-- Location --}}
                <div class="mb-4">

                    <label class="form-label">
                        Location
                    </label>

                    <input type="text" name="location" class="form-control"
                        value="{{ old('location', auth()->user()->location) }}">

                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-center gap-3">

                    <a href="{{ url()->previous() }}" class="btn btn-cancel">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-save">
                        Save
                    </button>

                </div>

            </form>

        </div>



    </div>

    </div>

    @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            @include('ManagementSystemViews.UserViews.Layouts.footer')
    <div class="mobile-profile">


        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="mobile-edit-header">
                <h4>Edit Profile</h4>
            </div>

            {{-- Avatar --}}
            <div class="mobile-avatar-section">

                @php
                    $avatarUrl = auth()->user()->profile_image_display ?? 'https://via.placeholder.com/120';
                @endphp

                <img src="{{ $avatarUrl }}" class="mobile-avatar" id="mobilePreviewImage">

                <div class="mobile-photo-side">

                    <label class="mobile-change-photo">

                        Change Photo

                        <input type="file" name="avatar" hidden onchange="previewFile(event)">

                    </label>

                    <div class="mobile-avatar-hint">
                        JPG, PNG or GIF. Max size 2MB
                    </div>

                </div>

            </div>

            {{-- Form --}}
            <div class="mobile-form">

                <div class="mobile-input">

                    <label>Full name</label>

                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}">

                </div>

                <div class="mobile-input">

                    <label>Email</label>

                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">

                </div>

                <div class="mobile-input">

                    <label>Phone number</label>

                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">

                </div>

                <div class="mobile-input-row">

                    <div class="mobile-select-box">
                        <label>Country</label>

                        <select>
                            <option>United States</option>
                            <option>Cambodia</option>
                        </select>
                    </div>

                    <div class="mobile-select-box">
                        <label>Gender</label>

                        <select>
                            <option>Female</option>
                            <option>Male</option>
                        </select>
                    </div>

                </div>

                <div class="mobile-input">

                    <label>Address</label>

                    <input type="text" name="location" value="{{ old('location', auth()->user()->location) }}">

                </div>

                <button type="submit" class="mobile-save-btn">
                    SAVE
                </button>

            </div>

        </form>

    </div>



@endsection

@push('scripts')
    <script>
        function previewFile(event) {

            const reader = new FileReader();

            reader.onload = function() {

                const desktopImage = document.getElementById('previewImage');

                if (desktopImage) {
                    desktopImage.src = reader.result;
                }

                const mobileImage = document.getElementById('mobilePreviewImage');

                if (mobileImage) {
                    mobileImage.src = reader.result;
                }
            };

            reader.readAsDataURL(event.target.files[0]);
        }

        // Auto close alert
        document.addEventListener('DOMContentLoaded', function() {

            const alertElement = document.querySelector('.alert-success');

            if (alertElement) {

                setTimeout(function() {

                    alertElement.style.transition = 'opacity 0.35s ease';
                    alertElement.style.opacity = '0';

                    setTimeout(function() {

                        if (alertElement.parentNode) {
                            alertElement.parentNode.removeChild(alertElement);
                        }

                    }, 350);

                }, 4000);
            }
        });
    </script>
@endpush
@push('styles')
    <style>
        /* ===============================
       ACTION BUTTONS (MATCH IMAGE)
    ================================ */

        /* Cancel button */
        .btn.btn-cancel {
            min-width: 140px;
            height: 44px;
            border-radius: 10px;
            border: 1.5px solid #00a8a8;
            background: transparent;
            color: #00a8a8;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Save button */
        .btn.btn-save {
            min-width: 140px;
            height: 44px;
            border-radius: 10px;
            background: #00a8a8;
            border: none;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }

        /* =========================
               GLOBAL
            ========================= */
        body {
            background: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
            min-width: 100%;
        }

        .page-wrap {
            flex: 1;
            width: 100%;
            overflow-y: auto;
        }

        /* =========================
               DESKTOP PROFILE
            ========================= */
        .profile-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            overflow-y: auto;
        }

        .mt-4 {
            margin-top: 0 !important;
        }

        .profile-title {
            color: #00a8a8;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .profile-subtitle {
            color: #94a3b8;
            font-size: 12px;
        }

        .profile-avatar {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn-save {
            background: #00a8a8;
            color: white;
            border: none;
            min-width: 120px;
        }

        .btn-save:hover {
            background: #009090;
            color: white;
        }

        .btn-cancel {
            border: 1px solid #00a8a8;
            color: #00a8a8;
            min-width: 120px;
        }

        .btn-cancel:hover {
            background: #e6f7f7;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
        }

        .form-control {
            font-size: 13px;
        }

        /* =========================
               ALERT
            ========================= */
        .alert-success {
            width: 320px;
            background: #ffffff !important;
            color: #334155 !important;
            border: none !important;
            border-left: 4px solid #10b981 !important;
            border-radius: 10px !important;
            padding: 16px 20px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }


        /* =========================
               MOBILE PROFILE
            ========================= */
        .mobile-profile,
        .cart-boxM {
            display: none;
        }

        @media (max-width: 768px) {

            body {
                background: #ffffff;
            }

            /* Hide desktop */
            .app-shell {
                display: none !important;
            }

            .cart-boxM {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                padding-bottom: 16px;
                padding-top: 6px;
                padding-left: 6px;
                padding-right: 6px;
                background: white;
            }

            /* Show mobile */
            .mobile-profile {
                display: block !important;
                min-height: 100vh;
                background: #ffffff;
                max-width: 430px;
                margin: auto;
                padding: 18px;
            }

            /* Header */
            .mobile-edit-header {
                text-align: center;
                margin-bottom: 28px;
            }

            .mobile-edit-header h4 {
                font-size: 20px;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
            }

            /* Avatar */
            .mobile-avatar-section {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 28px;
            }

            .mobile-avatar {
                width: 82px;
                height: 82px;
                border-radius: 18px;
                object-fit: cover;
            }

            .mobile-photo-side {
                flex: 1;
            }

            .mobile-change-photo {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                height: 42px;
                padding: 0 18px;
                border: 1px solid #d1d5db;
                border-radius: 10px;
                background: white;
                font-size: 13px;
                cursor: pointer;
            }

            .mobile-avatar-hint {
                margin-top: 8px;
                font-size: 11px;
                color: #94a3b8;
            }

            /* Form */
            .mobile-form {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .mobile-input label {
                display: block;
                font-size: 11px;
                color: #94a3b8;
                margin-bottom: 6px;
                padding-left: 4px;
                position: relative;
                top: 24px;
                left: 8px;
            }

            .mobile-input input,
            .mobile-input-row select {
                width: 100%;
                height: 54px;
                border-radius: 12px;
                border: 1px solid #d1d5db;
                background: #f1f5f9;
                padding: 0 14px;
                font-size: 14px;
                outline: none;
            }

            .mobile-input-row {
                display: flex;
                gap: 12px;
            }

            .mobile-input-row select {
                flex: 1;
            }

            .mobile-save-btn {
                width: 100%;
                height: 54px;
                border: none;
                border-radius: 14px;
                background: #2bb0cc;
                color: white;
                font-size: 15px;
                font-weight: 600;
                margin-top: 10px;
            }

            .mobile-save-btn:hover {
                background: #2298b2;
            }

            .alert-success {
                width: calc(100% - 30px) !important;
                right: 15px !important;
                top: 15px !important;
            }

            .mobile-select-box {
                flex: 1;
            }

            .mobile-select-box label {
                display: block;
                font-size: 11px;
                color: #94a3b8;
                margin-bottom: 6px;
                padding-left: 4px;
                position: relative;
                top: 24px;
                left: 8px;
            }

            .mobile-status-select {
                height: 42px;
                border: none;
                border-radius: 12px;
                background: #fff;
                padding: 0 14px;
                font-size: 13px;
                font-weight: 500;
                color: #0f172a;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                outline: none;
                cursor: pointer;
            }
        }
    </style>
@endpush

<style>

</style>
