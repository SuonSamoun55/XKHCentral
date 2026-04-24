@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Change Password')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-area { width: 100%; }
        .profile-card { background: var(--card); border-radius: 20px; padding: 30px; margin: 10px auto 0; }
        .profile-title { color: #00a8a8; font-weight: 600; }
        .profile-subtitle { color: #888; font-size: 12px; }
        .btn-custom { min-width: 120px; }
        .btn-save { background: #00a8a8; color: white; }
        .btn-save:hover { background: #009090; }
        .btn-cancel { border: 1px solid #00a8a8; color: #00a8a8; }
        .btn-cancel:hover { background: #e6f7f7; }
        .password-strength { font-size: 12px; margin-top: 5px; }
        .password-strength.weak { color: #dc2626; }
        .password-strength.medium { color: #f59e0b; }
        .password-strength.strong { color: #10b981; }
    </style>
@endpush

@section('content')
    <div class="content-area">
        <div class="container mt-4">
            <div class="profile-card">
                @if(session('success'))
                    <script>window.addEventListener('DOMContentLoaded',()=>window.showAppToast && window.showAppToast(@json(session('success')),'success'));</script>
                @endif
                @if(session('error'))
                    <script>window.addEventListener('DOMContentLoaded',()=>window.showAppToast && window.showAppToast(@json(session('error')),'error'));</script>
                @endif

                <h4 class="profile-title">Change Password</h4>
                <p class="profile-subtitle">Update your password to keep your account secure</p>

                <form action="{{ route('user.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter your current password" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your new password" required>
                        <div id="password-strength" class="password-strength"></div>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm your new password" required>
                        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
@endsection

@push('scripts')
<script>
    document.getElementById('password')?.addEventListener('input', function() {
        const password = this.value;
        const strengthIndicator = document.getElementById('password-strength');

        if (password.length === 0) {
            strengthIndicator.textContent = '';
            strengthIndicator.className = 'password-strength';
            return;
        }

        let strength = 0;
        let feedback = [];

        if (password.length >= 8) strength += 1; else feedback.push('At least 8 characters');
        if (/[A-Z]/.test(password)) strength += 1; else feedback.push('One uppercase letter');
        if (/[a-z]/.test(password)) strength += 1; else feedback.push('One lowercase letter');
        if (/\d/.test(password)) strength += 1; else feedback.push('One number');
        if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength += 1; else feedback.push('One special character');

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

    document.getElementById('password_confirmation')?.addEventListener('input', function() {
        const password = document.getElementById('password')?.value || '';
        const confirmation = this.value;
        this.setCustomValidity(confirmation && password !== confirmation ? 'Passwords do not match' : '');
    });
</script>
@endpush
