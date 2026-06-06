<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/UserinfoView/UserShow.css') }}">

<div class="user-detail-wrapper">
    <!-- Customer Info Header with Avatar -->
    <div class="user-detail-header">
        <div class="user-avatar-circle">
            @php
                $profileImage = $customer->profile_image_display ?? $customer->profile_image ?? null;
                $displayName = $customer->local_name ?? $customer->name ?? 'User';
                $firstLetter = strtoupper(mb_substr(trim($displayName), 0, 1)) ?: 'U';
            @endphp
            
            @if($profileImage)
                <img src="{{ $profileImage }}" alt="User Avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            @endif
            
            <span class="user-avatar-fallback" id="avatarFallback" style="display: {{ $profileImage ? 'none' : 'flex' }};">{{ $firstLetter }}</span>
        </div>

        <div style="flex: 1;">
            <div class="user-value" style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                {{ $displayName }}
            </div>
            <div class="user-label" style="color: #18bfd0;">Customer</div>
        </div>
    </div>

    <!-- Customer BC Information -->
    <div class="user-info-section">
        <div class="user-info-row">
            <div class="user-info-item">
                <div class="user-label">Customer BC ID</div>
                <div class="user-value">{{ $customer->bc_customer_no ?? '-' }}</div>
            </div>
            <div class="user-info-item">
                <div class="user-label">Email</div>
                <div class="user-value">{{ $customer->local_email ?? $customer->email ?? '-' }}</div>
            </div>
        </div>
        <div class="user-info-row">
            <div class="user-info-item">
                <div class="user-label">Phone</div>
                <div class="user-value">{{ $customer->local_phone ?? $customer->phone ?? '-' }}</div>
            </div>
            <div class="user-info-item">
                <div class="user-label">Address</div>
                <div class="user-value">{{ $customer->address ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Connected User Information -->
    <hr class="section-divider">
    <div class="section-title">Connected User Account</div>

    @if($user)
        <div class="user-info-section">
            <div class="user-info-row">
                <div class="user-info-item">
                    <div class="user-label">Name</div>
                    <div class="user-value">{{ $user->name ?? '-' }}</div>
                </div>
                <div class="user-info-item">
                    <div class="user-label">Email</div>
                    <div class="user-value">{{ $user->email ?? '-' }}</div>
                </div>
            </div>
            <div class="user-info-row">
                <div class="user-info-item">
                    <div class="user-label">Phone</div>
                    <div class="user-value">{{ $user->phone ?? '-' }}</div>
                </div>
                <div class="user-info-item">
                    <div class="user-label">Role</div>
                    <div class="user-value" style="text-transform: capitalize;">{{ $user->role ?? '-' }}</div>
                </div>
                {{-- <div>
                    <div class="user-label">Password</div>
                    <div class="user-value">{{ $user->password ?? '-' }}</div>
                </div> --}}
            </div>
        </div>
    @else
        <div class="alert-no-connection">
            ⚠️ This customer is not connected to any user account yet.
        </div>
    @endif
</div>