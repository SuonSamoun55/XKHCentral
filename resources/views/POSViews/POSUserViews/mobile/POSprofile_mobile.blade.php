@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Profile')

@section('content')

    <div class="mobile-profile-page">
        @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            @include('ManagementSystemViews.UserViews.Layouts.footer')

        {{-- PROFILE AVATAR --}}
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

        {{-- PROFILE ACTIONS --}}
        <div class="profile-actions">

            {{-- Edit Profile --}}
            <a href="{{ route('profile') }}" class="profile-card">
                <i class="bi bi-pencil"></i>
                <span>Edit Profile</span>
            </a>


            {{-- Privacy Policy --}}
            <a href="{{ route('privacy_policy_mobile') }}" class="profile-card">
                <i class="bi bi-shield-lock"></i>
                <span>Privacy Policy</span>
            </a>

            {{-- Sign Out --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="profile-card danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </button>
            </form>

        </div>

    </div>

@endsection


@push('styles')
    <style>
        /* ===============================
       MOBILE PROFILE – IMAGE MATCH
    =============================== */
        .sidebar,
        .sidebar-wrap {
            display: none;
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

        .mobile-profile-page {
            background: #f8fafc;
            padding: 0px 6px;
            min-height: 100vh;
            min-width: 100%;
            padding-bottom: 80px;
        }

        /* Top Profile Section */
        .profile-top {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 28px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }

        .profile-name {
            margin-top: 14px;
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
        }

        /* Menu Cards */
        .profile-actions {
            margin-top: 28px;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 26px;
            border-radius: 14px;
            background: #ffffff;
            text-decoration: none;
            color: #0f172a;
            border: none;
            width: 100%;
            text-align: left;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);
        }

        .profile-card i {
            font-size: 20px;
            color: #0ea5e9;
        }

        .profile-card span {
            font-size: 14px;
            font-weight: 500;
        }

        /* Danger (Sign Out) */
        .profile-card.danger i {
            color: #ef4444;
        }

        .profile-card.danger span {
            color: #ef4444;
        }

        /* Button reset for form */
        .profile-actions form {
            margin: 0;
        }

        .profile-actions button {
            cursor: pointer;
        }
    </style>
@endpush
