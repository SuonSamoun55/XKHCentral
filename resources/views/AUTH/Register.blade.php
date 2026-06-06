<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - POS Portal</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/views/AUTH/Register.css') }}">
</head>
<body>
    <div class="register-container">
        <!-- Left Side - Branding -->
        <div class="register-left">
            <i class="bi bi-shop"></i>
            <h1>POS PORTAL</h1>
            <p>Join us today! Create your account and start managing your business with our powerful point of sale system.</p>
        </div>

        <!-- Right Side - Register Form -->
        <div class="register-right">
            <div class="register-header">
                <h2>Create Account</h2>
                <p>Sign up to get started</p>
            </div>

            <!-- Alert Messages -->
            <div id="alertBox" class="alert"></div>

            <form action="/register" method="POST" id="registerForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person"></i>
                            <input type="text" id="first_name" name="first_name" class="form-control" placeholder="John" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person"></i>
                            <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Doe" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="john.doe@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Create a strong password" required>
                    </div>
                    <div id="passwordStrength" class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-bar-fill"></div>
                        </div>
                        <span id="strengthText"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-enter your password" required>
                    </div>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn-register">
                    <i class="bi bi-person-plus"></i> Create Account
                </button>
            </form>

            <div class="divider">
                <span>OR</span>
            </div>

            <div class="social-login">
                <button class="social-btn" onclick="alert('Google signup coming soon!')">
                    <i class="bi bi-google" style="color: #ea4335;"></i>
                    Google
                </button>
                <button class="social-btn" onclick="alert('Facebook signup coming soon!')">
                    <i class="bi bi-facebook" style="color: #1877f2;"></i>
                    Facebook
                </button>
            </div>

            <div class="login-link">
                Already have an account? <a href="/login">Sign In</a>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const password = this.value;

            if (password.length === 0) {
                strengthDiv.classList.remove('show');
                return;
            }

            strengthDiv.classList.add('show');

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthDiv.className = 'password-strength show';

            if (strength <= 1) {
                strengthDiv.classList.add('strength-weak');
                strengthText.textContent = 'Weak password';
            } else if (strength <= 2) {
                strengthDiv.classList.add('strength-medium');
                strengthText.textContent = 'Medium password';
            } else {
                strengthDiv.classList.add('strength-strong');
                strengthText.textContent = 'Strong password';
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;
            const alertBox = document.getElementById('alertBox');

            // Validation checks
            if (!firstName || !lastName || !email || !password || !passwordConfirm) {
                e.preventDefault();
                alertBox.className = 'alert error';
                alertBox.textContent = 'Please fill in all fields.';
                return;
            }

            if (!email.includes('@')) {
                e.preventDefault();
                alertBox.className = 'alert error';
                alertBox.textContent = 'Please enter a valid email address.';
                return;
            }

            if (password.length < 8) {
                e.preventDefault();
                alertBox.className = 'alert error';
                alertBox.textContent = 'Password must be at least 8 characters.';
                return;
            }

            if (password !== passwordConfirm) {
                e.preventDefault();
                alertBox.className = 'alert error';
                alertBox.textContent = 'Passwords do not match.';
                return;
            }

            if (!terms) {
                e.preventDefault();
                alertBox.className = 'alert error';
                alertBox.textContent = 'You must agree to the Terms of Service and Privacy Policy.';
                return;
            }
        });

        @if(session('error'))
            document.getElementById('alertBox').className = 'alert error';
            document.getElementById('alertBox').textContent = '{{ session("error") }}';
        @endif

        @if(session('success'))
            document.getElementById('alertBox').className = 'alert success';
            document.getElementById('alertBox').textContent = '{{ session("success") }}';
        @endif
    </script>
</body>
</html>
