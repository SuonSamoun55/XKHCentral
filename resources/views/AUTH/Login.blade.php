@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Login - POS')
@section('content')
    <link rel="stylesheet" href="{{ asset('ManagementSystemCss/Login.css') }}">

    <div class="onboarding-overlay" id="onboardingOverlay">
        <div class="onboarding-progress-container">
            <div class="progress-step"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
        </div>
        <div class="onboarding-slide slide-1 active">
            <div class="onboarding-card">
                <div class="onboarding-badge"></div>
                <img src="{{ asset('images/pos/xtricate.png') }}" alt="xtricate logo" class="onboarding-logo">
                <img src="{{ asset('images/pos/image 11.png') }}" alt="Welcome to Xtricate" class="onboarding-image1">
            </div>
        </div>
        <div class="onboarding-slide slide-2">
            <div class="onboarding-card">
                <img src="{{ asset('images/pos/login.png') }}" alt="login" class="onboarding-image">
                <img src="{{ asset('images/pos/Fader.png') }}" alt="fader" class="onboarding-fader">
                <h5>Tons of furniture collections</h5>
                <p>Experience the future of POS with our innovative system.</p>
                <button type="button" class="btn onboarding-btn next-btn">Next</button>
            </div>
        </div>
        <div class="onboarding-slide slide-3">
            <div class="onboarding-card">
                <img src="{{ asset('images/pos/image3.png') }}" alt="login" class="onboarding-image">
                <img src="{{ asset('images/pos/Fader.png') }}" alt="fader" class="onboarding-fader">
                <h5>Fast Deliveries to your doorstep</h5>
                <p>Intuitive interface designed for seamless user experience.</p>
                <button type="button" class="btn onboarding-btn next-btn">Next</button>
            </div>
        </div>
        <div class="onboarding-slide slide-4">
            <div class="onboarding-card">
                <img src="{{ asset('images/pos/image4.png') }}" alt="login" class="onboarding-image">
                <img src="{{ asset('images/pos/Fader.png') }}" alt="fader" class="onboarding-fader">
                <h5>Bring aesthetics to your home</h5>
                <p>Your data is protected with top-tier security measures.</p>
                <button type="button" class="btn onboarding-btn next-btn" id="finalNextBtn">Next</button>
            </div>
        </div>
    </div>

    <div class="login-page" id="loginPage">
        <div class="login-shell">
            {{-- 1. Left Side (Top on Mobile) --}}
            <div class="login-left">
                <video autoplay muted loop playsinline>
                    <source src="{{ asset('/videos/grokvideo.mp4') }}" type="video/mp4">
                </video>
            </div>

            {{-- 2. Right Side (Bottom on Mobile) --}}



            <div class="login-right">
                <div class="login-form-box">
                    <form method="POST" action="{{ route('logout') }}" class="logout-link">
    @csrf
    <button class="logout-btn" type="submit" title="Logout">
        <span class="nav-icon">
            <img src="{{ asset('images/pos/Vector.png') }}" alt="Logout Icon">
        </span>
    </button>
</form>
                    <img src="{{ asset('images/pos/image 14.png') }}" alt="second login image" class="login-form-image">
                    <h1 class="login-title">Account Login</h1>
                    <p class="login-subtitle">
                        If you are already a member you can login with your email address and password.
                    </p>

                    {{-- Alerts --}}
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="login-label" for="email">Email address</label>
                            <input id="email" type="email" name="email" class="form-control login-input"
                                value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-2">
                            <label class="login-label" for="password">Password</label>
                            <input id="password" type="password" name="password" class="form-control login-input" required>
                        </div>

                        <div class="login-check-row">
                            <input type="checkbox" id="rememberMe" name="remember">
                            <label for="rememberMe">Remember me</label>
                        </div>

                        <button type="submit" class="btn login-btn">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var overlay = document.getElementById('onboardingOverlay');
            var slides = document.querySelectorAll('.onboarding-slide');
            var nextBtns = document.querySelectorAll('.next-btn');
            var finalNextBtn = document.getElementById('finalNextBtn');
            var progressSteps = document.querySelectorAll('.progress-step');
            var currentSlide = 0;

            var progressContainer = document.querySelector('.onboarding-progress-container');

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });

                // Update progress bar - only current step active
                progressSteps.forEach((step, i) => {
                    step.classList.remove('active');
                });

                // Show progress from slide 2 onwards, activate only current step
                if (index >= 1) {
                    progressContainer.classList.add('visible');
                    const progressIndex = index - 1; // slide-2 = step 0, slide-3 = step 1, slide-4 = step 2
                    if (progressSteps[progressIndex]) {
                        progressSteps[progressIndex].classList.add('active');
                    }
                } else {
                    progressContainer.classList.remove('visible');
                }

                currentSlide = index;
            }

            // Auto transition from slide 1 to slide 2 after 1 second
            setTimeout(function() {
                if (currentSlide === 0) {
                    showSlide(1);
                }
            }, 1000);

            // Handle next button clicks
            nextBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentSlide < slides.length - 1) {
                        showSlide(currentSlide + 1);
                    }
                });
            });

            // Final next button hides overlay
            if (finalNextBtn) {
                finalNextBtn.addEventListener('click', function() {
                    overlay.style.display = 'none';
                });
            }

            // Handle back button
            var backBtn = document.getElementById('backBtn');
            if (backBtn) {
                backBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.history.back();
                });
            }
        });
    </script>
@endsection
