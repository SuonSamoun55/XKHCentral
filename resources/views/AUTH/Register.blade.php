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

    <style>
        /* Root Variables */
        :root {
            --primary-color: #ff85a2;
            --primary-light: #ffedf1;
            --primary-dark: #e06b85;
            --text-color: #333;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary-light) 0%, #fff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .register-left {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .register-left i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .register-left h1 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .register-left p {
            font-size: 1rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .register-right {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-header {
            margin-bottom: 30px;
        }

        .register-header h2 {
            font-size: 1.8rem;
            color: var(--text-color);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .register-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 133, 162, 0.1);
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        .terms-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: var(--primary-color);
            flex-shrink: 0;
        }

        .terms-checkbox label {
            cursor: pointer;
            line-height: 1.5;
        }

        .terms-checkbox a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .terms-checkbox a:hover {
            color: var(--primary-dark);
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(255, 133, 162, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, var(--primary-dark), #c55570);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 133, 162, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            background: linear-gradient(135deg, #ccc, #999);
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #94a3b8;
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
        }

        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .social-btn {
            padding: 12px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .social-btn:hover {
            border-color: var(--primary-color);
            background: var(--primary-light);
        }

        .social-btn i {
            font-size: 1.2rem;
        }

        .login-link {
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition);
        }

        .login-link a:hover {
            color: var(--primary-dark);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: none;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
            display: block;
        }

        .alert.success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
            display: block;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.8rem;
            display: none;
        }

        .password-strength.show {
            display: block;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            margin-bottom: 5px;
            overflow: hidden;
        }

        .strength-bar-fill {
            height: 100%;
            transition: var(--transition);
            width: 0%;
        }

        .strength-weak .strength-bar-fill {
            width: 33%;
            background: #ef4444;
        }

        .strength-medium .strength-bar-fill {
            width: 66%;
            background: #f59e0b;
        }

        .strength-strong .strength-bar-fill {
            width: 100%;
            background: #22c55e;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .register-container {
                grid-template-columns: 1fr;
            }

            .register-left {
                padding: 40px 30px;
            }

            .register-left i {
                font-size: 3rem;
            }

            .register-left h1 {
                font-size: 1.5rem;
            }

            .register-right {
                padding: 40px 30px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .social-login {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .register-right {
                padding: 30px 20px;
            }

            .register-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
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
