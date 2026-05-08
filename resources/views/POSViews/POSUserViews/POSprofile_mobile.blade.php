@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Profile')

@section('content')

    <div class="mobile-profile-page">
        @include('ManagementSystemViews.UserViews.Layouts.header_mobile')


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
            <a href="#" class="profile-card">
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
    <div class="mobile-bottom-nav">

        {{-- HOME --}}
        <a href="{{ route('user.posinterface') }}" class="{{ request()->routeIs('user.posinterface') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>home</span>
        </a>

        {{-- PRODUCTS (categories + category products) --}}
        <a href="{{ route('user.pos.categories') }}"
            class="{{ request()->routeIs('user.pos.categories') || request()->routeIs('user.pos.categories.products') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>products</span>
        </a>

        {{-- WISHLIST --}}
        <a href="{{ route('user.pos.favorites') }}" class="{{ request()->routeIs('user.pos.favorites') ? 'active' : '' }}">
            <i class="bi bi-heart"></i>
            <span>wishlist</span>
        </a>


        {{-- USER --}}
        <a href="{{ route('profile_mobile') }}" class="{{ request()->routeIs('profile_mobile') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>user</span>
        </a>
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

        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-around;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            box-shadow: 0 -10px 30px rgba(15, 23, 42, 0.08);
            z-index: 1200;
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

        .mobile-bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            color: #64748b;
            font-size: 11px;
            text-decoration: none;
        }

        .mobile-bottom-nav a i {
            font-size: 20px;
            color: var(--primary);
        }

        .mobile-bottom-nav a.active {
            color: #0f172a;
        }

        .mobile-bottom-nav a.active i {
            color: var(--primary);
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
