@extends('Layout.Management.app')
@section('title', 'Customer Detail')
@section('backUrl', route('users.index'))

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/views/Management/userinfo/UserShow.css') }}?v={{ filemtime(public_path('css/views/Management/userinfo/UserShow.css')) }}">
@endpush

@section('content')
@php
    $profileImage = $customer->profile_image_display ?? $customer->profile_image ?? null;
    $displayName = $customer->local_name ?? $customer->display_name ?? $customer->name ?? 'User';
    $firstLetter = strtoupper(mb_substr(trim($displayName), 0, 1)) ?: 'U';

    $blockedRaw = trim((string) ($customer->blocked ?? ''));
    $isBlocked = $blockedRaw !== '' && $blockedRaw !== '_x0020_';

    $field = fn ($value) => $value !== null && trim((string) $value) !== '' ? $value : null;

    $address = $field(trim(implode(', ', array_filter([$customer->address ?? null, $customer->city ?? null]))));

    $money = fn ($value) => number_format((float) ($value ?? 0), 2);

    $syncedAt = $customer->last_synced_at ? \Carbon\Carbon::parse($customer->last_synced_at)->diffForHumans() : 'never';
    $lastOrderAt = $orderStats['last_order_at'] ?? null;
    $lastOrderText = $lastOrderAt ? \Carbon\Carbon::parse($lastOrderAt)->diffForHumans() : 'never';
@endphp

<div class="user-show-page">
    <div class="page-top-row">
        <div class="header-title-group">
            <a href="{{ route('users.index') }}" class="header-back-arrow" aria-label="Back" id="detailBackArrow">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="page-title">Customer View Detail</h2>
        </div>
    </div>

    <div class="user-detail-wrapper" data-customer-id="{{ $customer->id }}" data-csrf="{{ csrf_token() }}">

        <!-- Header -->
        <div class="user-detail-header">
            <div class="user-detail-header-left">
                <div class="user-avatar-circle">
                    @if($profileImage)
                        <img src="{{ $profileImage }}" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @endif
                    <span class="user-avatar-fallback" style="display: {{ $profileImage ? 'none' : 'flex' }};">{{ $firstLetter }}</span>
                </div>
                <div class="user-detail-header-text">
                    <div class="name-row">
                        <h1>{{ $displayName }}</h1>
                        @if($isBlocked)
                            <span class="detail-badge is-blocked">Blocked</span>
                        @endif
                        @if($user)
                            <span class="detail-badge is-connected">Connected &middot; {{ $user->role ?? 'user' }}</span>
                        @else
                            <span class="detail-badge is-offline">Not Connected</span>
                        @endif
                    </div>
                    <div class="cust-no">Customer No. <span>{{ $customer->bc_customer_no ?? '-' }}</span></div>
                </div>
            </div>
            <div class="user-detail-header-right">
                <span class="profile-status-pill {{ $isBlocked ? 'is-blocked' : 'is-active' }}">
                    <i class="bi {{ $isBlocked ? 'bi-slash-circle-fill' : 'bi-check-circle-fill' }}"></i>
                    Customer Profile {{ $isBlocked ? 'Blocked' : 'Active' }}
                </span>
            </div>
        </div>

        <!-- BC and Laravel source cards -->
        <div class="source-grid">

            <!-- BC card -->
            <div class="source-card">
                <div class="source-head">
                    <div class="source-tag bc"><span class="source-icon"><i class="bi bi-building"></i></span>Business Central</div>
                    <button type="button" class="source-action" id="syncBcBtn" {{ empty($customer->bc_id) ? 'disabled title="No Business Central ID on file"' : '' }}>
                        <i class="bi bi-arrow-repeat"></i> Sync now
                    </button>
                </div>

                <div class="primary-figure" id="bcBalance">{{ $money($customer->balance) }}</div>
                <div class="primary-figure-label">Balance (LCY)</div>

                <div class="mini-stats">
                    <div class="mini-stat">
                        <div class="mini-stat-label">Balance Due</div>
                        <div class="mini-stat-value" id="bcBalanceDue">{{ $money($customer->balance_due) }}</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-label">Credit Limit</div>
                        <div class="mini-stat-value" id="bcCreditLimit">{{ $money($customer->credit_limit) }}</div>
                    </div>
                </div>

                <div class="source-foot" id="bcSyncNote">Synced {{ $syncedAt }}</div>
            </div>

            <!-- Laravel POS card -->
            <div class="source-card">
                <div class="source-head">
                    <div class="source-tag lv"><span class="source-icon"><i class="bi bi-cart3"></i></span>Laravel POS</div>
                    @if($user)
                        <a class="source-action" href="{{ route('admin.orders.index', ['search' => $user->name]) }}">
                            <i class="bi bi-box-arrow-up-right"></i> View orders
                        </a>
                    @else
                        <button type="button" class="source-action" disabled title="No connected account, no orders">View orders</button>
                    @endif
                </div>

                <div class="primary-figure">{{ $money($orderStats['pending_amount']) }}</div>
                <div class="primary-figure-label">Pending amount (not yet synced)</div>

                <div class="order-counters">
                    <div class="counter pending">
                        <div class="counter-value">{{ $orderStats['pending_count'] }}</div>
                        <div class="counter-label">Pending</div>
                    </div>
                    <div class="counter confirmed">
                        <div class="counter-value">{{ $orderStats['confirmed_count'] }}</div>
                        <div class="counter-label">Confirmed</div>
                    </div>
                    <div class="counter cancelled">
                        <div class="counter-value">{{ $orderStats['cancelled_count'] }}</div>
                        <div class="counter-label">Cancelled</div>
                    </div>
                </div>

                <div class="source-foot">Last order {{ $lastOrderText }}</div>
            </div>

        </div>

        @unless($user)
            <div class="alert-no-connection">
                This customer is not connected to any user account yet — order stats will show once they are.
            </div>
        @endunless

        <!-- Info cards -->
        <div class="info-grid">
            <div class="detail-card">
                <h2><i class="bi bi-telephone"></i> Contact</h2>
                <dl class="field-list">
                    <dt>Phone</dt><dd class="{{ $field($customer->phone) ? '' : 'empty' }}">{{ $field($customer->phone) ?? '—' }}</dd>
                    <dt>Mobile</dt><dd class="{{ $field($customer->mobile_phone_no) ? '' : 'empty' }}">{{ $field($customer->mobile_phone_no) ?? '—' }}</dd>
                    <dt>Email</dt><dd class="{{ $field($customer->email) ? '' : 'empty' }}">{{ $field($customer->email) ?? '—' }}</dd>
                    <dt>Address</dt><dd class="{{ $address ? '' : 'empty' }}">{{ $address ?? '—' }}</dd>
                </dl>
            </div>

            <div class="detail-card">
                <h2><i class="bi bi-truck"></i> Fulfillment &amp; Payment</h2>
                <dl class="field-list">
                    <dt>Location</dt><dd class="{{ $field($customer->location_code) ? '' : 'empty' }}">{{ $field($customer->location_code) ?? '—' }}</dd>
                    <dt>Ship-to Code</dt><dd class="{{ $field($customer->ship_to_code) ? '' : 'empty' }}">{{ $field($customer->ship_to_code) ?? '—' }}</dd>
                    <dt>Payment Terms</dt><dd class="{{ $field($customer->payment_terms_code) ? '' : 'empty' }}">{{ $field($customer->payment_terms_code) ?? '—' }}</dd>
                    <dt>Price Group</dt><dd class="{{ $field($customer->customer_price_group) ? '' : 'empty' }}">{{ $field($customer->customer_price_group) ?? '—' }}</dd>
                </dl>
            </div>
        </div>

        <div class="footer-note">Last full sync {{ $syncedAt }}</div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var syncBtn = document.getElementById('syncBcBtn');
    var wrapper = document.querySelector('.user-detail-wrapper');
    if (!syncBtn || !wrapper) return;

    syncBtn.addEventListener('click', function () {
        if (syncBtn.disabled || syncBtn.classList.contains('is-syncing')) return;

        var customerId = wrapper.dataset.customerId;
        var token = wrapper.dataset.csrf;
        var originalText = syncBtn.textContent;

        syncBtn.classList.add('is-syncing');
        syncBtn.textContent = 'Syncing…';

        fetch('/users/' + customerId + '/sync-bc', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) {
                    document.getElementById('bcBalance').textContent = data.balance;
                    document.getElementById('bcBalanceDue').textContent = data.balance_due;
                    document.getElementById('bcCreditLimit').textContent = data.credit_limit;
                    document.getElementById('bcSyncNote').textContent = 'Synced ' + data.synced_at;
                } else {
                    alert(data.message || 'Sync failed.');
                }
            })
            .catch(function () {
                alert('Sync failed — could not reach the server.');
            })
            .finally(function () {
                syncBtn.classList.remove('is-syncing');
                syncBtn.textContent = originalText;
            });
    });
});

(function () {
    var cameFromSameOrigin = document.referrer && document.referrer.indexOf(window.location.origin) === 0;
    if (!cameFromSameOrigin || window.history.length <= 1) return;

    var backArrow = document.getElementById('detailBackArrow');
    if (!backArrow) return;

    backArrow.addEventListener('click', function (e) {
        e.preventDefault();
        window.history.back();
    });
})();
</script>
@endpush
