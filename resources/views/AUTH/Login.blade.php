<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - POS</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pos/xtricate.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('/css/views/AUTH/login.css') }}">
</head>

<body>

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
                    <img src="{{ asset('images/pos/image 14.png') }}" alt="second login image"
                        class="login-form-image">
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
                                value="{{ old('email') }}" placeholder="Enter your email address" required>
                        </div>

                        <div class="mb-2">
                            <label class="login-label" for="password">Password</label>
                            <div class="password-field">
                                <input id="password" type="password" name="password" class="form-control login-input"
                                    placeholder="Enter your password" required>
                                <button type="button" class="password-toggle-btn" id="passwordToggleBtn"
                                    aria-label="Show password" aria-pressed="false">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
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
            }, 2000);

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
            // On phones, the on-screen keyboard eats a large chunk of the
            // viewport. Centering the field (old behavior) fought with the
            // browser's own keyboard-open scroll and often left the field
            // hidden behind the keyboard or off-screen above it. Instead,
            // once the keyboard has finished opening, scroll so the field
            // sits just below the top of whatever viewport space is left —
            // easy to see and type into no matter how tall the keyboard is.
            var isMobileViewport = window.matchMedia('(max-width: 768px)').matches;

            function scrollFieldNearTop(input) {
                var topOffset = 16;
                var currentTop = input.getBoundingClientRect().top;
                window.scrollBy({
                    top: currentTop - topOffset,
                    behavior: 'smooth'
                });
            }

            document.querySelectorAll('.login-input').forEach(function(input) {
                input.addEventListener('focus', function() {
                    if (!isMobileViewport) return;

                    if (window.visualViewport) {
                        var onViewportResize = function() {
                            window.visualViewport.removeEventListener('resize', onViewportResize);
                            scrollFieldNearTop(input);
                        };
                        window.visualViewport.addEventListener('resize', onViewportResize);

                        // Fallback in case the keyboard doesn't trigger a
                        // visualViewport resize on this browser.
                        setTimeout(function() {
                            window.visualViewport.removeEventListener('resize', onViewportResize);
                            scrollFieldNearTop(input);
                        }, 400);
                    } else {
                        setTimeout(function() {
                            scrollFieldNearTop(input);
                        }, 400);
                    }
                });
            });

            var passwordInput = document.getElementById('password');
            var passwordToggleBtn = document.getElementById('passwordToggleBtn');
            if (passwordInput && passwordToggleBtn) {
                passwordToggleBtn.addEventListener('click', function() {
                    var icon = passwordToggleBtn.querySelector('i');
                    var willShow = passwordInput.type === 'password';

                    passwordInput.type = willShow ? 'text' : 'password';
                    icon.classList.toggle('bi-eye', !willShow);
                    icon.classList.toggle('bi-eye-slash', willShow);
                    passwordToggleBtn.setAttribute('aria-label', willShow ? 'Hide password' : 'Show password');
                    passwordToggleBtn.setAttribute('aria-pressed', willShow ? 'true' : 'false');
                    passwordInput.focus();
                });
            }
            var loginForm = document.querySelector('.login-form-box form');
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    var submitBtn = loginForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Logging in...';
                    }
                });
            }
        });
    </script>
</body>

</html>
