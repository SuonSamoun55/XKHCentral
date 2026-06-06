@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Change Password')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/setting/profile/adminchangepassword.css') }}">
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

                    {{-- Error Message --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Header --}}
                    <h4 class="profile-title">Change Password</h4>
                    <p class="profile-subtitle">
                        Update your password to keep your account secure
                    </p>

                    {{-- Form --}}
                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Enter your current password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your new password" required>
                            <div id="password-strength" class="password-strength"></div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm New Password --}}
                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Confirm your new password" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-centers gap-3">

                            <a href="{{ route('admin.profile') }}" class="btn btn-cancel btn-custom">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-save btn-custom">
                                Update Password
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>

    {{-- Password Strength Script --}}
@endsection

@push('scripts')
<script>
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.getElementById('password-strength');

            if (password.length === 0) {
                strengthIndicator.textContent = '';
                strengthIndicator.className = 'password-strength';
                return;
            }

            let strength = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) {
                strength += 1;
            } else {
                feedback.push('At least 8 characters');
            }

            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength += 1;
            } else {
                feedback.push('One uppercase letter');
            }

            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength += 1;
            } else {
                feedback.push('One lowercase letter');
            }

            // Number check
            if (/\d/.test(password)) {
                strength += 1;
            } else {
                feedback.push('One number');
            }

            // Special character check
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                strength += 1;
            } else {
                feedback.push('One special character');
            }

            // Update indicator
            if (strength <= 2) {
                strengthIndicator.textContent = 'Weak password: ' + feedback.join(', ');
                strengthIndicator.className = 'password-strength weak';
            } else if (strength <= 4) {
                strengthIndicator.textContent = 'Medium strength password';
                strengthIndicator.className = 'password-strength medium';
            } else {
                strengthIndicator.textContent = 'Strong password';
                strengthIndicator.className = 'password-strength strong';
            }
        });

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;

            if (confirmation && password !== confirmation) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
@endpush
