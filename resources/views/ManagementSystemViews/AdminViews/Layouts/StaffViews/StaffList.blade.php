@extends('Layout.Management.app')
@section('title', 'Staff Accounts')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/userinfo/UserList.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/userinfo/StaffList.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/userinfo/create.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Password/adminchangepassword.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/shared/toast.css') }}">
@endpush

@section('content')
    <div class="main-wrapper">
        <div class="content-areas">
            <div class="page-card">
                <div class="page-title">Staff Accounts</div>

                <div class="top-bar">
                    <div class="left-tools">
                        <div class="user-search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="staffSearch" class="user-search-input"
                                placeholder="Search by name or email">
                        </div>
                    </div>

                    <div class="right-tools-inline">
                        <a href="{{ route('users.index') }}" class="sync-btn">
                            <i class="bi bi-arrow-left"></i>
                            <span class="sync-btn-divider"></span>
                            <span class="sync-btn-text">Back to Customers</span>
                        </a>

                        <button type="button" class="sync-btn" data-bs-toggle="modal" data-bs-target="#staffCreateModal">
                            <img class="bi-person-badge-fill" src="/images/management/staff.png" alt="">
                            <span class="sync-btn-divider"></span>
                            <span class="sync-btn-text">Create Staff Account</span>
                        </button>
                    </div>
                </div>

                @include('partials.app-toast', ['suppressSuccessToast' => (bool) session('new_password')])

                <div class="table-container">
                    <div class="table-scroll">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Company</th>
                                    <th>Status</th>
                                    <th>Last Seen</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody id="staffTableBody">
                                @forelse($staff as $member)
                                    @php
                                        $firstLetter = strtoupper(mb_substr(trim($member->name), 0, 1)) ?: 'S';
                                        $lastSeenText = $member->last_seen_at
                                            ? \Carbon\Carbon::parse($member->last_seen_at)->format('Y-m-d h:i A')
                                            : '-';
                                    @endphp
                                    <tr class="staff-row" data-name="{{ strtolower($member->name) }}"
                                        data-email="{{ strtolower($member->email ?? '') }}">
                                        <td>
                                            <div class="avatar-cell">
                                                <div class="avatar-wrap">
                                                    @if ($member->profile_image_display)
                                                        <img src="{{ $member->profile_image_display }}" alt=""
                                                            class="avatar-image"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="avatar-fallback" style="display:none;">
                                                            {{ $firstLetter }}</div>
                                                    @else
                                                        <div class="avatar-fallback">{{ $firstLetter }}</div>
                                                    @endif
                                                </div>
                                                <div class="name-block">
                                                    <span class="name-text">{{ $member->name }}</span>
                                                    @if (!$member->company_id)
                                                        <span class="sub-text">Cross-tenant</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $member->email }}</td>
                                        <td><span class="badge bg-info text-dark">{{ ucfirst($member->role) }}</span></td>
                                        <td>{{ $member->company->display_name ?? ($member->company->name ?? '—') }}</td>
                                        <td>
                                            @if ($member->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td title="{{ $lastSeenText }}">{{ $lastSeenText }}</td>
                                        <td>
                                            <div class="action-icons">
                                                <button type="button" title="Edit" data-bs-toggle="modal"
                                                    data-bs-target="#staffEditModal" data-id="{{ $member->id }}"
                                                    data-name="{{ $member->name }}" data-email="{{ $member->email }}"
                                                    data-role="{{ $member->role }}"
                                                    data-company-id="{{ $member->company_id }}" class="open-staff-edit">
                                                    <i class="bi bi-pencil text-warning"></i>
                                                </button>

                                                <button type="button" title="Update Password" data-bs-toggle="modal"
                                                    data-bs-target="#staffPasswordModal" data-id="{{ $member->id }}"
                                                    data-name="{{ $member->name }}" class="open-staff-password">
                                                    <i class="bi bi-key-fill text-primary"></i>
                                                </button>

                                                <button type="button" title="Delete"
                                                    class="delete-icon open-delete-confirm"
                                                    data-url="{{ route('staff.destroy', $member->id) }}"
                                                    data-label="{{ $member->name }}">
                                                    <i class="bi bi-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noStaffRow">
                                        <td colspan="7" class="empty-text">No staff accounts yet. Create one to get
                                            started.</td>
                                    </tr>
                                @endforelse

                                <tr id="noStaffResultRow" style="display:none;">
                                    <td colspan="7" class="empty-text">No matching staff found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Create Staff Modal ===== --}}
    <div class="modal fade" id="staffCreateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-user-modal">
                <form method="POST" action="{{ route('staff.store') }}">
                    @csrf

                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title">Create Staff Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body custom-modal-body">
                        <p class="text-muted small">
                            A login not tied to a synced customer — for staff who need a role.
                            Give it no company to make it cross-tenant (can switch between companies
                            under Companies &rarr; Select); pick one company to lock it to just that company's data.
                        </p>

                        <div class="mb-3">
                            <label class="form-label custom-label">Name:</label>
                            <input type="text" name="name" class="form-control custom-input"
                                value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Email:</label>
                            <input type="email" name="email" class="form-control custom-input"
                                value="{{ old('email') }}" required>
                            <small class="form-text text-muted">Must not already be used by another account.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Role:</label>
                            <select name="role" class="form-select custom-input" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}">
                                        {{ $roleOption->display_name ?? ucfirst($roleOption->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (!auth()->user()->company_id)
                            <div class="mb-3">
                                <label class="form-label custom-label">Company:</label>
                                <select name="company_id" class="form-select custom-input">
                                    <option value="" {{ session('selected_company_id') ? '' : 'selected' }}>All
                                        Companies (cross-tenant)</option>
                                    @foreach ($companies as $companyOption)
                                        <option value="{{ $companyOption->id }}"
                                            {{ (int) session('selected_company_id') === $companyOption->id ? 'selected' : '' }}>
                                            {{ $companyOption->display_name ?? $companyOption->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted">Defaults to whichever company you're currently viewing —
                                    change it to create a cross-tenant account or lock it to a different company instead.
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <small class="form-text text-muted">This account will belong to your own company.</small>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label custom-label">Password:</label>
                            <input type="password" name="password" class="form-control custom-input" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Confirm Password:</label>
                            <input type="password" name="password_confirmation" class="form-control custom-input"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer custom-modal-footer">
                        <button type="button" class="btn modal-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn modal-save-btn">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Edit Staff Modal (info only — no password here) ===== --}}
    <div class="modal fade" id="staffEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-user-modal">
                <form method="POST" id="staffEditForm">
                    @csrf
                    @method('PUT')

                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title">Edit Staff Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body custom-modal-body">
                        <div class="mb-3">
                            <label class="form-label custom-label">Name:</label>
                            <input type="text" name="name" id="staffEditName" class="form-control custom-input"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Email:</label>
                            <input type="email" name="email" id="staffEditEmail" class="form-control custom-input"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Role:</label>
                            <select name="role" id="staffEditRole" class="form-select custom-input" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}">
                                        {{ $roleOption->display_name ?? ucfirst($roleOption->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (!auth()->user()->company_id)
                            <div class="mb-3">
                                <label class="form-label custom-label">Company:</label>
                                <select name="company_id" id="staffEditCompany" class="form-select custom-input">
                                    <option value="">All Companies (cross-tenant)</option>
                                    @foreach ($companies as $companyOption)
                                        <option value="{{ $companyOption->id }}">
                                            {{ $companyOption->display_name ?? $companyOption->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer custom-modal-footer">
                        <button type="button" class="btn modal-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn modal-save-btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Update Password Modal — requires YOUR OWN current password to authorize,
     same re-auth rule as Admin Profile > Change Password ===== --}}
    <div class="modal fade" id="staffPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-user-modal">
                <form method="POST" id="staffPasswordForm">
                    @csrf
                    @method('PUT')

                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title">Update Password for <span id="staffPasswordTargetName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body custom-modal-body">
                        <div class="mb-3">
                            <label class="form-label custom-label" for="staffNewPassword">New Password:</label>
                            <input type="password" name="password" id="staffNewPassword"
                                class="form-control custom-input" required>
                            <div id="staff-password-strength" class="password-strength"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label" for="staffNewPasswordConfirm">Confirm New
                                Password:</label>
                            <input type="password" name="password_confirmation" id="staffNewPasswordConfirm"
                                class="form-control custom-input" required>
                        </div>
                    </div>

                    <div class="modal-footer custom-modal-footer">
                        <button type="button" class="btn modal-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn modal-save-btn" id="staffPasswordSubmitBtn">Update
                            Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Confirm-before-submit overlay (reused pattern from Admin Change Password) ===== --}}
    <div class="pw-confirm-overlay" id="pwConfirmOverlay">
        <div class="pw-confirm-box">
            <div class="pw-confirm-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <h3 class="pw-confirm-title">Update password?</h3>
            <p class="pw-confirm-text" id="pwConfirmText">Are you sure you want to change this staff member's password?
            </p>
            <div class="pw-confirm-actions">
                <button type="button" class="pw-confirm-btn cancel" id="pwConfirmCancel">Cancel</button>
                <button type="button" class="pw-confirm-btn confirm" id="pwConfirmOk">Yes, Update</button>
            </div>
        </div>
    </div>

    {{-- ===== Delete confirmation overlay (same reused pattern, shared by every delete button) ===== --}}
    <div class="pw-confirm-overlay" id="deleteConfirmOverlay">
        <div class="pw-confirm-box">
            <div class="pw-confirm-icon"><i class="bi bi-trash3-fill"></i></div>
            <h3 class="pw-confirm-title" id="deleteConfirmTitle">Delete this staff account?</h3>
            <p class="pw-confirm-text">This action cannot be undone.</p>
            <div class="pw-confirm-actions">
                <button type="button" class="pw-confirm-btn cancel" id="deleteConfirmCancel">Cancel</button>
                <button type="button" class="pw-confirm-btn confirm" id="deleteConfirmOk">Yes, Delete</button>
            </div>
        </div>
    </div>
    <form method="POST" id="deleteConfirmForm" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- ===== Success overlay — shows the new plaintext password once, same as Admin Change Password ===== --}}
    @if (session('success') && session('new_password'))
        <div class="pw-success-overlay show" id="pwSuccessOverlay">
            <div class="pw-success-box">
                <div class="pw-success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <h3 class="pw-success-title">Password updated!</h3>
                <p class="pw-success-text">Share this with {{ session('password_updated_for', 'the staff member') }} — it
                    won't be shown again.</p>
                <div class="pw-new-password-box">
                    <span class="pw-new-password-label">New password</span>
                    <span class="pw-new-password-value">{{ session('new_password') }}</span>
                </div>
                <button type="button" class="pw-btn pw-btn-save" id="pwSuccessClose" style="width:100%;">Got
                    it</button>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Populate the edit modal from the clicked row's data-* attributes.
            document.querySelectorAll('.open-staff-edit').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.dataset.id;
                    document.getElementById('staffEditForm').action = '/staff/' + id;
                    document.getElementById('staffEditName').value = btn.dataset.name || '';
                    document.getElementById('staffEditEmail').value = btn.dataset.email || '';
                    document.getElementById('staffEditRole').value = btn.dataset.role || '';

                    const companySelect = document.getElementById('staffEditCompany');
                    if (companySelect) {
                        companySelect.value = btn.dataset.companyId || '';
                    }
                });
            });

            // Populate the update-password modal's target + form action.
            document.querySelectorAll('.open-staff-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.dataset.id;
                    document.getElementById('staffPasswordForm').action = '/staff/' + id +
                        '/password';
                    document.getElementById('staffPasswordTargetName').textContent = btn.dataset
                        .name || '';
                    document.getElementById('pwConfirmText').textContent =
                        'Are you sure you want to change the password for ' + (btn.dataset.name ||
                            'this staff member') + '?';
                });
            });

            // Password strength meter (same thresholds as Admin Change Password).
            document.getElementById('staffNewPassword')?.addEventListener('input', function() {
                const password = this.value;
                const strengthIndicator = document.getElementById('staff-password-strength');
                if (!strengthIndicator) return;

                if (password.length === 0) {
                    strengthIndicator.textContent = '';
                    strengthIndicator.className = 'password-strength';
                    return;
                }

                let strength = 0;
                let feedback = [];

                if (password.length >= 8) strength += 1;
                else feedback.push('At least 8 characters');
                if (/[A-Z]/.test(password)) strength += 1;
                else feedback.push('One uppercase letter');
                if (/[a-z]/.test(password)) strength += 1;
                else feedback.push('One lowercase letter');
                if (/\d/.test(password)) strength += 1;
                else feedback.push('One number');
                if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength += 1;
                else feedback.push('One special character');

                if (strength <= 2) {
                    strengthIndicator.textContent = 'Weak password: ' + feedback.join(', ');
                    strengthIndicator.className = 'password-strength weak';
                } else if (strength <= 4) {
                    strengthIndicator.textContent = 'Medium strength password';
                    strengthIndicator.className = 'password-strength medium';
                } else {
                    strengthIndicator.textContent = 'Strong password';
                    strengthIndicator.className = 'password-strength strong';
                }
            });

            document.getElementById('staffNewPasswordConfirm')?.addEventListener('input', function() {
                const password = document.getElementById('staffNewPassword')?.value || '';
                const confirmation = this.value;
                this.setCustomValidity(confirmation && password !== confirmation ?
                    'Passwords do not match' : '');
            });

            // Confirm-before-submit flow for the password form (mirrors Admin Change Password).
            (function() {
                const form = document.getElementById('staffPasswordForm');
                const submitBtn = document.getElementById('staffPasswordSubmitBtn');
                const overlay = document.getElementById('pwConfirmOverlay');
                const okBtn = document.getElementById('pwConfirmOk');
                const cancelBtn = document.getElementById('pwConfirmCancel');
                if (!form || !submitBtn || !overlay) return;

                function openModal() {
                    overlay.classList.add('show');
                }

                function closeModal() {
                    overlay.classList.remove('show');
                }

                submitBtn.addEventListener('click', function() {
                    if (!form.reportValidity()) return;
                    openModal();
                });

                okBtn?.addEventListener('click', function() {
                    closeModal();
                    form.submit();
                });
                cancelBtn?.addEventListener('click', closeModal);
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) closeModal();
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
                });
            })();

            // Success overlay dismiss.
            (function() {
                const overlay = document.getElementById('pwSuccessOverlay');
                const closeBtn = document.getElementById('pwSuccessClose');
                if (!overlay) return;

                function closeModal() {
                    overlay.classList.remove('show');
                }

                closeBtn?.addEventListener('click', closeModal);
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) closeModal();
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
                });
            })();

            // Delete confirmation — shared overlay + form for every delete button.
            (function() {
                const overlay = document.getElementById('deleteConfirmOverlay');
                const titleEl = document.getElementById('deleteConfirmTitle');
                const form = document.getElementById('deleteConfirmForm');
                const okBtn = document.getElementById('deleteConfirmOk');
                const cancelBtn = document.getElementById('deleteConfirmCancel');
                if (!overlay || !form) return;

                function openModal() {
                    overlay.classList.add('show');
                }

                function closeModal() {
                    overlay.classList.remove('show');
                }

                document.querySelectorAll('.open-delete-confirm').forEach(function(trigger) {
                    trigger.addEventListener('click', function() {
                        form.action = trigger.dataset.url;
                        titleEl.textContent = trigger.dataset.label ?
                            ('Delete ' + trigger.dataset.label + '?') :
                            'Delete this staff account?';
                        openModal();
                    });
                });

                okBtn?.addEventListener('click', function() {
                    form.submit();
                });
                cancelBtn?.addEventListener('click', closeModal);
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) closeModal();
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
                });
            })();

            // Simple client-side search by name/email.
            const searchInput = document.getElementById('staffSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const term = searchInput.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('.staff-row');
                    let visibleCount = 0;

                    rows.forEach(function(row) {
                        const matches = !term || row.dataset.name.includes(term) || row.dataset
                            .email.includes(term);
                        row.style.display = matches ? '' : 'none';
                        if (matches) visibleCount++;
                    });

                    const noResultRow = document.getElementById('noStaffResultRow');
                    if (noResultRow) {
                        noResultRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
                    }
                });
            }
        });
    </script>
@endpush
