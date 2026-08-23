@extends('Layout.Management.app')
@section('title', 'Change Password')
@section('backUrl', route('admin.profile'))

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/views/Management/Password/adminchangepassword.css') }}?v={{ filemtime(public_path('/css/views/Management/Password/adminchangepassword.css')) }}">
@endpush

@section('content')
<div class="pw-page">
    <div class="page-top-row">
        <a href="{{ route('admin.profile') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Profile
        </a>
    </div>

    @if(session('success') && !session('new_password'))
        <div class="pw-alert pw-alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="pw-alert pw-alert-error">{{ session('error') }}</div>
    @endif

    <div class="pw-card">
        <div class="pw-card-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <h1 class="pw-title">Change Password</h1>
        <p class="pw-subtitle">Update your password to keep your account secure</p>

        <form action="{{ route('admin.password.update') }}" method="POST" class="pw-form" id="pwForm">
            @csrf
            @method('PUT')

            <div class="pw-field">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="pw-input @error('current_password') is-invalid @enderror" placeholder="Enter your current password" required>
                @error('current_password')<div class="pw-error">{{ $message }}</div>@enderror
            </div>

            <div class="pw-field">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="pw-input @error('password') is-invalid @enderror" placeholder="Enter your new password" required>
                <div id="password-strength" class="password-strength"></div>
                @error('password')<div class="pw-error">{{ $message }}</div>@enderror
            </div>

            <div class="pw-field">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="pw-input @error('password_confirmation') is-invalid @enderror" placeholder="Confirm your new password" required>
                @error('password_confirmation')<div class="pw-error">{{ $message }}</div>@enderror
            </div>

            <div class="pw-actions">
                <a href="{{ route('admin.profile') }}" class="pw-btn pw-btn-cancel">Cancel</a>
                <button type="button" class="pw-btn pw-btn-save" id="pwSubmitBtn">Update Password</button>
            </div>
        </form>
    </div>

    @if(session('success') && session('new_password'))
        <div class="pw-success-overlay show" id="pwSuccessOverlay">
            <div class="pw-success-box">
                <div class="pw-success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <h3 class="pw-success-title">Password updated!</h3>
                <p class="pw-success-text">Please keep your new password safe — you'll need it next time you log in.</p>
                <div class="pw-new-password-box">
                    <span class="pw-new-password-label">New password</span>
                    <span class="pw-new-password-value">{{ session('new_password') }}</span>
                </div>
                <button type="button" class="pw-btn pw-btn-save" id="pwSuccessClose" style="width:100%;">Got it</button>
            </div>
        </div>
    @endif
</div>

<div class="pw-confirm-overlay" id="pwConfirmOverlay">
    <div class="pw-confirm-box">
        <div class="pw-confirm-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <h3 class="pw-confirm-title">Update password?</h3>
        <p class="pw-confirm-text">Are you sure you want to change your password? You'll need the new password next time you log in.</p>
        <div class="pw-confirm-actions">
            <button type="button" class="pw-confirm-btn cancel" id="pwConfirmCancel">Cancel</button>
            <button type="button" class="pw-confirm-btn confirm" id="pwConfirmOk">Yes, Update</button>
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

    (function () {
        const form = document.getElementById('pwForm');
        const submitBtn = document.getElementById('pwSubmitBtn');
        const overlay = document.getElementById('pwConfirmOverlay');
        const okBtn = document.getElementById('pwConfirmOk');
        const cancelBtn = document.getElementById('pwConfirmCancel');
        if (!form || !submitBtn || !overlay) return;

        function openModal() { overlay.classList.add('show'); }
        function closeModal() { overlay.classList.remove('show'); }

        submitBtn.addEventListener('click', function () {
            if (!form.reportValidity()) return;
            openModal();
        });

        okBtn?.addEventListener('click', function () {
            closeModal();
            form.submit();
        });
        cancelBtn?.addEventListener('click', closeModal);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
        });
    })();

    (function () {
        const overlay = document.getElementById('pwSuccessOverlay');
        const closeBtn = document.getElementById('pwSuccessClose');
        if (!overlay) return;

        function closeModal() { overlay.classList.remove('show'); }

        closeBtn?.addEventListener('click', closeModal);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
        });
    })();
</script>
@endpush
