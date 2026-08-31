@extends('Layout.POSUser.app')
@section('title', 'My Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Profile/show.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Profile/show.css')) }}">
@endpush

@section('content')
@php
    $firstLetter = strtoupper(mb_substr(trim($user->name ?? 'U'), 0, 1)) ?: 'U';

    $blockedRaw = trim((string) ($customer->blocked ?? ''));
    $isBlocked = $customer && $blockedRaw !== '' && $blockedRaw !== '_x0020_';

    $field = fn ($value) => $value !== null && trim((string) $value) !== '' ? $value : null;

    $address = $customer
        ? $field(trim(implode(', ', array_filter([$customer->address ?? null, $customer->city ?? null]))))
        : $field($user->location ?? null);

    $money = fn ($value) => number_format((float) ($value ?? 0), 2);

    $lastOrderAt = $orderStats['last_order_at'] ?? null;
    $lastOrderText = $lastOrderAt ? \Carbon\Carbon::parse($lastOrderAt)->diffForHumans() : 'never';
@endphp

@include('Layout.POSUser.header_mobile')
@include('Layout.POSUser.footer')
<div class="user-show-page">
    <div class="page-top-row">
        <a href="{{ route('user.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert-no-connection" style="background:#eaf3ee;border-color:#cfe3d7;color:#1f6f4a;">
            {{ session('success') }}
        </div>
    @endif

    <div class="user-detail-wrapper" data-customer-id="{{ $customer->id ?? '' }}" data-csrf="{{ csrf_token() }}">

        <!-- Header -->
        <div class="user-detail-header">
            <div class="user-detail-header-left">
                <div class="avatar-wrap">
                    <div class="user-avatar-circle">
                        @if($user->profile_image_display)
                            <img src="{{ $user->profile_image_display }}" alt="" id="avatarPreview" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @else
                            <img src="" alt="" id="avatarPreview" style="display:none;">
                        @endif
                        <span class="user-avatar-fallback" id="avatarFallback" style="display: {{ $user->profile_image_display ? 'none' : 'flex' }};">{{ $firstLetter }}</span>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="phone" value="{{ $user->phone }}">
                        <input type="hidden" name="dob" value="{{ $user->dob }}">
                        <input type="hidden" name="location" value="{{ $user->location }}">
                        <label class="avatar-edit-btn" for="avatarInput" aria-label="Change profile photo">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" hidden>
                    </form>
                </div>
                <div class="user-detail-header-text">
                    <div class="name-row">
                        <h1>{{ $user->name }}</h1>
                        <span class="detail-badge is-connected">{{ ucfirst($user->role ?? 'user') }}</span>
                        @if($isBlocked)
                            <span class="detail-badge is-blocked">Blocked</span>
                        @endif
                    </div>
                    <div class="cust-no">
                        @if($customer)
                            Customer No. <span>{{ $customer->bc_customer_no ?? '-' }}</span>
                        @else
                            Not linked to Business Central
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('user.password.change') }}" class="header-edit-btn">
                <i class="bi bi-key-fill"></i> Change Password
            </a>
        </div>

        <!-- Laravel POS source card -->
        <div class="source-grid single-col">
            <!-- Laravel POS card -->
            <div class="source-card">
                <div class="source-head">
                    <div class="source-tag lv"><span class="source-icon"><img src="{{ asset('images/management/management_POS_active.png') }}" class="icon-img" alt=""></span>Laravel POS</div>
                    <a class="source-action" href="{{ route('user.pos.order.history') }}">
                        <i class="bi bi-box-arrow-up-right"></i> View orders
                    </a>
                </div>

                <div class="primary-figure">{{ $money($orderStats['confirmed_amount']) }}</div>
                <div class="primary-figure-label">Total confirmed</div>

                <div class="order-counters">
                    <a href="{{ route('user.pos.order.history', ['status' => 'pending']) }}" class="counter pending">
                        <div class="counter-value">{{ $orderStats['pending_count'] }}</div>
                        <div class="counter-label">Pending</div>
                        @if ($orderStats['pending_amount'] > 0)
                            <div class="counter-amount">{{ $money($orderStats['pending_amount']) }}</div>
                        @endif
                    </a>
                    <a href="{{ route('user.pos.order.history', ['status' => 'confirmed']) }}" class="counter confirmed">
                        <div class="counter-value">{{ $orderStats['confirmed_count'] }}</div>
                        <div class="counter-label">Confirmed</div>
                    </a>
                    <a href="{{ route('user.pos.order.history', ['status' => 'cancel']) }}" class="counter cancelled">
                        <div class="counter-value">{{ $orderStats['cancelled_count'] }}</div>
                        <div class="counter-label">Cancelled</div>
                        @if ($orderStats['cancelled_amount'] > 0)
                            <div class="counter-amount">{{ $money($orderStats['cancelled_amount']) }}</div>
                        @endif
                    </a>
                </div>

                <div class="source-foot">Last order {{ $lastOrderText }}</div>
            </div>

        </div>

        @unless($customer)
            <div class="alert-no-connection">
                Your account isn't linked to a Business Central customer yet — contact an admin to connect it.
            </div>
        @endunless

        <!-- Info cards -->
        <div class="info-grid {{ $customer ? '' : 'single-col' }}">
            <div class="detail-card">
                <h2><img src="{{ asset('images/Profile/smartphone.png') }}" class="icon-img" alt=""> Contact</h2>
                <dl class="field-list">
                    <dt><img src="{{ asset('images/Profile/smartphone.png') }}" class="icon-img" alt=""> Phone</dt><dd class="{{ $field($customer->phone ?? null) ?? $field($user->phone) ? '' : 'empty' }}">{{ $field($customer->phone ?? null) ?? $field($user->phone) ?? '—' }}</dd>
                    <dt><img src="{{ asset('images/Profile/mobile.png') }}" class="icon-img" alt=""> Mobile</dt><dd class="{{ $customer && $field($customer->mobile_phone_no) ? '' : 'empty' }}">{{ ($customer ? $field($customer->mobile_phone_no) : null) ?? '—' }}</dd>
                    <dt><img src="{{ asset('images/Profile/email.png') }}" class="icon-img" alt=""> Email</dt><dd class="{{ $field($user->email) ? '' : 'empty' }}">{{ $field($user->email) ?? '—' }}</dd>
                    <dt><img src="{{ asset('images/Profile/gps.png') }}" class="icon-img" alt=""> Address</dt><dd class="{{ $address ? '' : 'empty' }}">{{ $address ?? '—' }}</dd>
                </dl>
            </div>

            @if($customer)
                <div class="detail-card">
                    <h2><i class="bi bi-truck"></i> Fulfillment &amp; Payment</h2>
                    <dl class="field-list">
                        <dt>Location</dt><dd class="{{ $field($customer->location_code) ? '' : 'empty' }}">{{ $field($customer->location_code) ?? '—' }}</dd>
                        <dt>Ship-to Code</dt><dd class="{{ $field($customer->ship_to_code) ? '' : 'empty' }}">{{ $field($customer->ship_to_code) ?? '—' }}</dd>
                        <dt>Payment Terms</dt><dd class="{{ $field($customer->payment_terms_code) ? '' : 'empty' }}">{{ $field($customer->payment_terms_code) ?? '—' }}</dd>
                        <dt>Price Group</dt><dd class="{{ $field($customer->customer_price_group) ? '' : 'empty' }}">{{ $field($customer->customer_price_group) ?? '—' }}</dd>
                    </dl>
                </div>
            @endif
        </div>

    </div>

    <details class="privacy-policy-note">
        <summary>Privacy Policy</summary>
        <p>
            Xtricate eCommerce App, operated by Xtricate Cambodia, respects your privacy and is committed to
            protecting your personal information. This app allows users to place orders and connects with
            Microsoft Dynamics 365 Business Central.
        </p>
        <p>
            We may collect your name, company name, email address, phone number, delivery address, account
            details, order history, payment status, device information, and app usage data. This information
            is used to provide services, process orders, improve app performance, offer customer support,
            prevent fraud, and comply with legal obligations.
        </p>
    </details>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('avatarInput');
    var form = document.getElementById('avatarForm');
    if (!input || !form) return;

    input.addEventListener('change', function () {
        if (!input.files || !input.files[0]) return;

        var preview = document.getElementById('avatarPreview');
        var fallback = document.getElementById('avatarFallback');
        var reader = new FileReader();
        reader.onload = function () {
            if (preview) {
                preview.src = reader.result;
                preview.style.display = 'block';
            }
            if (fallback) fallback.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);

        form.submit();
    });
});
</script>
@endpush
@endsection
