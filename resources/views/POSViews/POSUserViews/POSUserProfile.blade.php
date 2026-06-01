@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Profile Information')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link rel="stylesheet" href="{{ asset('css/POSsystem/profile.css') }}">
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
    <div class="mobile-profile-menu" id="mobileProfileMenu">
        <div class="profile-top">
            @php
                $avatarUrl = auth()->user()->profile_image_display ?? 'https://via.placeholder.com/120';
            @endphp

            <img src="{{ $avatarUrl }}" alt="Profile Avatar" class="profile-avatar"
                onerror="this.src='https://via.placeholder.com/120'">

            <h3 class="profile-name">
                {{ auth()->user()->name ?? 'User' }}
            </h3>
        </div>

        <div class="profile-actions">
            <button type="button" class="profile-menu-card" id="openMobileEditProfile">
                <i class="bi bi-pencil"></i>
                <span>Edit Profile</span>
            </button>

            <a href="#mobilePrivacyPolicy" class="profile-menu-card">
                <i class="bi bi-shield-lock"></i>
                <span>Privacy Policy</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="profile-menu-card danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>

        <div class="mobile-policy-inline" id="mobilePrivacyPolicy">
            <h4>1. Types data we collect</h4>
            <p>
                Xtricate eCommerce App, operated by Xtricate Cambodia, respects your privacy
                and is committed to protecting your personal information. This app allows users
                to place orders and connects with Microsoft Dynamics 365 Business Central.
            </p>

            <h4>2. Use of your personal data</h4>
            <p>
                We may collect your name, company name, email address, phone number, delivery
                address, account details, order history, payment status, device information,
                and app usage data. This information is used to provide services, process
                orders, improve app performance, offer customer support, prevent fraud, and
                comply with legal obligations.
            </p>
        </div>
    </div>

    <div class="mobile-profile">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            {{-- Header --}}
            <div class="mobile-edit-header">
                <button type="button" class="mobile-edit-back" id="closeMobileEditProfile">
                    <i class="bi bi-arrow-left"></i>
                </button>
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
            const mobileMenu = document.getElementById('mobileProfileMenu');
            const mobileEdit = document.querySelector('.mobile-profile');
            const openMobileEdit = document.getElementById('openMobileEditProfile');
            const closeMobileEdit = document.getElementById('closeMobileEditProfile');

            openMobileEdit?.addEventListener('click', function() {
                mobileMenu?.classList.add('is-hidden');
                mobileEdit?.classList.add('is-open');
            });

            closeMobileEdit?.addEventListener('click', function() {
                mobileEdit?.classList.remove('is-open');
                mobileMenu?.classList.remove('is-hidden');
            });

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
