@extends('Layout.Management.app')
@section('title', 'Profile Information')
@section('backUrl', url('/admin'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/Management/AdminProfile/adminprofile.css') }}?v={{ filemtime(public_path('css/views/Management/AdminProfile/adminprofile.css')) }}">
@endpush
@section('content')
@php
    $user = auth()->user();
    $firstLetter = strtoupper(mb_substr(trim($user->name ?? 'A'), 0, 1)) ?: 'A';
    $field = fn ($value) => $value !== null && trim((string) $value) !== '' ? $value : null;
@endphp

<div class="admin-profile-page">
    <div class="page-top-row">
        <div class="header-title-group">
            <a href="{{ url('/admin') }}" class="header-back-arrow" aria-label="Back">
                <i class="bi bi-chevron-left"></i>
            </a>
            <h2 class="page-title">Profile Information</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="ap-alert ap-alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="ap-alert ap-alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="ap-alert ap-alert-error">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="ap-header">
        <div class="ap-header-left">
            <div class="avatar-wrap">
                <div class="ap-avatar-circle">
                    @if($user->profile_image_display)
                        <img src="{{ $user->profile_image_display }}" alt="" id="apAvatarPreview" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @else
                        <img src="" alt="" id="apAvatarPreview" style="display:none;">
                    @endif
                    <span class="ap-avatar-fallback" id="apAvatarFallback" style="display: {{ $user->profile_image_display ? 'none' : 'flex' }};">{{ $firstLetter }}</span>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="apAvatarForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                    <input type="hidden" name="dob" value="{{ $user->dob }}">
                    <input type="hidden" name="location" value="{{ $user->location }}">
                    <label class="ap-avatar-edit-btn" for="apAvatarInput" aria-label="Change profile photo">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                    <input type="file" name="avatar" id="apAvatarInput" accept="image/*" hidden>
                </form>
            </div>
            <div class="ap-header-text">
                <h1>{{ $user->name }}</h1>
                <span class="ap-role-badge">{{ ucfirst($user->role ?? 'admin') }}</span>
                <div class="ap-email">{{ $user->email }}</div>
            </div>
        </div>
        <a href="{{ route('admin.password.change') }}" class="ap-header-btn">
            <i class="bi bi-key-fill"></i> Change Password
        </a>
    </div>

    <div class="ap-card ap-stats-card">
        <h2><i class="bi bi-graph-up"></i> Approval Activity</h2>
        <div class="ap-stats-grid">
            <button type="button" class="ap-stat" id="apOrdersStatBtn">
                <div class="ap-stat-value">{{ $approvedOrdersCount }}</div>
                <div class="ap-stat-label">{{ \Illuminate\Support\Str::plural('Order', $approvedOrdersCount) }} Approved</div>
            </button>
            <button type="button" class="ap-stat" id="apCustomersStatBtn">
                <div class="ap-stat-value">{{ $approvedCustomersCount }}</div>
                <div class="ap-stat-label">{{ \Illuminate\Support\Str::plural('Customer', $approvedCustomersCount) }} Served</div>
            </button>
        </div>
    </div>

    <div class="ap-card">
        <h2><i class="bi bi-person-vcard"></i> Contact &amp; Account</h2>
        <dl class="ap-field-list">
            <dt>Email</dt><dd class="{{ $field($user->email) ? '' : 'empty' }}">{{ $field($user->email) ?? '—' }}</dd>
            <dt>Phone</dt><dd class="{{ $field($user->phone) ? '' : 'empty' }}">{{ $field($user->phone) ?? '—' }}</dd>
            <dt>Date of Birth</dt><dd class="{{ $field($user->dob) ? '' : 'empty' }}">{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('M d, Y') : '—' }}</dd>
            <dt>Location</dt><dd class="{{ $field($user->location) ? '' : 'empty' }}">{{ $field($user->location) ?? '—' }}</dd>
            <dt>Role</dt><dd>{{ ucfirst($user->role ?? 'admin') }}</dd>
        </dl>
    </div>
</div>

<div class="ap-list-overlay" id="apOrdersListOverlay">
    <div class="ap-list-box">
        <div class="ap-list-head">
            <h3>Orders Approved <span class="ap-list-count">({{ $approvedOrdersCount }})</span></h3>
            <button type="button" class="ap-list-close" id="apOrdersListClose" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="ap-list-body">
            @forelse($approvedOrders as $order)
                <a href="{{ route('admin.orders.show', $order->id) }}" class="ap-list-row">
                    <div class="ap-list-row-main">
                        <div class="ap-list-row-title">{{ $order->order_no }}</div>
                        <div class="ap-list-row-sub">{{ $order->user->name ?? $order->customer_no ?? 'Unknown customer' }}</div>
                    </div>
                    <div class="ap-list-row-end">
                        <div class="ap-list-row-amount">${{ number_format((float) ($order->total_amount ?? 0), 2) }}</div>
                        <div class="ap-list-row-date">{{ optional($order->checked_out_at ?? $order->created_at)->format('M d, Y') }}</div>
                    </div>
                </a>
            @empty
                <div class="ap-list-empty">No orders approved yet.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="ap-list-overlay" id="apCustomersListOverlay">
    <div class="ap-list-box">
        <div class="ap-list-head">
            <h3>Customers Served <span class="ap-list-count">({{ $approvedCustomersCount }})</span></h3>
            <button type="button" class="ap-list-close" id="apCustomersListClose" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="ap-list-body">
            @forelse($approvedCustomerRows as $row)
                <a href="{{ route('admin.orders.index', ['tab' => 'approved', 'customer_id' => $row['user_id'], 'approved_by' => $user->id]) }}" class="ap-list-row">
                    <div class="ap-list-row-main">
                        <div class="ap-list-row-title">{{ $row['customer_name'] }}</div>
                        <div class="ap-list-row-sub">View orders you approved for this customer</div>
                    </div>
                    <div class="ap-list-row-end">
                        <div class="ap-list-row-amount">{{ $row['orders_count'] }} {{ \Illuminate\Support\Str::plural('order', $row['orders_count']) }}</div>
                    </div>
                </a>
            @empty
                <div class="ap-list-empty">No customers served yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('apAvatarInput');
    var form = document.getElementById('apAvatarForm');
    if (!input || !form) return;

    input.addEventListener('change', function () {
        if (!input.files || !input.files[0]) return;

        var preview = document.getElementById('apAvatarPreview');
        var fallback = document.getElementById('apAvatarFallback');
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

function apBindListModal(btnId, overlayId, closeId) {
    var btn = document.getElementById(btnId);
    var overlay = document.getElementById(overlayId);
    var closeBtn = document.getElementById(closeId);
    if (!btn || !overlay) return;

    function open() { overlay.classList.add('show'); }
    function close() { overlay.classList.remove('show'); }

    btn.addEventListener('click', open);
    closeBtn?.addEventListener('click', close);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) close();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('show')) close();
    });
}

apBindListModal('apOrdersStatBtn', 'apOrdersListOverlay', 'apOrdersListClose');
apBindListModal('apCustomersStatBtn', 'apCustomersListOverlay', 'apCustomersListClose');
</script>
@endpush
