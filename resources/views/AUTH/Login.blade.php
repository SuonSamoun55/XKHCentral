@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Login - POS')
@section('content')
<link rel="stylesheet" href="{{ asset('ManagementSystemCss/Login.css') }}">

<div class="login-page">
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
                <h1 class="login-title">Account Login</h1>
                <p class="login-subtitle">
                    If you are already a member you can login with your email address and password.
                </p>

                {{-- Alerts --}}
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="login-label" for="email">Email address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-control login-input"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="mb-2">
                        <label class="login-label" for="password">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control login-input"
                            required
                        >
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
@endsection