<style>
    .user-detail-wrapper {
        padding: 0;
        margin: 0;
    }

    .user-detail-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e8eef4;
    }

    .user-avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #eef2f7;
        border: 2px solid #d6dde5;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar-fallback {
        font-size: 32px;
        font-weight: 700;
        color: #475569;
    }

    .user-info-section {
        margin-bottom: 20px;
    }

    .user-info-row {
        display: flex;
        gap: 20px;
        margin-bottom: 12px;
    }

    .user-info-item {
        flex: 1;
    }

    .user-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    .section-divider {
        margin: 20px 0;
        border: none;
        border-top: 1px solid #e8eef4;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #18bfd0;
        margin-bottom: 16px;
        margin-top: 8px;
    }

    .alert-no-connection {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        color: #92400e;
        border-radius: 6px;
        padding: 12px;
        font-size: 13px;
        margin: 0;
    }
</style>

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
                <img src="{{ $profileImage }}" alt="User Avatar" onerror="this.style.display='none';">
            @endif
            
            @if(!$profileImage || true)
                <span class="user-avatar-fallback" id="avatarFallback">{{ $firstLetter }}</span>
            @endif
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
            </div>
        </div>
    @else
        <div class="alert-no-connection">
            ⚠️ This customer is not connected to any user account yet.
        </div>
    @endif
</div>