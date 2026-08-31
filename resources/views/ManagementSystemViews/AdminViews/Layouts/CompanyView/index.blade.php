@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/company_list.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Password/adminchangepassword.css') }}">
@endpush

@section('title', 'Companies')

@section('content')
    <div class="companies-page">

        <div class="alert-container">
            @if (session('success'))
                <div class="custom-alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="custom-alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <div class="page-head">
            <div>
                <h1>Companies</h1>
                <p>{{ $companies->count() }} {{ Str::plural('company', $companies->count()) }} total</p>
            </div>
            <div class="head-actions">
                @if ($selectedCompanyId)
                    <form action="{{ route('companies.clearSelection') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-ghost">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            View all
                        </button>
                    </form>
                @endif
                <a href="{{ route('companies.create') }}" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Create company
                </a>
            </div>
        </div>

        <div class="toolbar">
            <div class="search">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="11" cy="11" r="7" />
                    <path d="M21 21l-4.3-4.3" />
                </svg>
                <input type="text" id="companySearch" placeholder="Search companies…" autocomplete="off">
            </div>
            <span class="count-pill" id="companyCount">{{ $companies->count() }} of {{ $companies->count() }} shown</span>
        </div>

        <div class="company-list" id="companyList">
            @forelse ($companies as $company)
                @php
                    $isSelected = $selectedCompanyId == $company->id;
                    $isConnected = $company->companyConnection && $company->companyConnection->status;
                    $initials = strtoupper(
                        substr(preg_replace('/\s+/', '', $company->display_name ?? $company->name), 0, 2),
                    );
                    $logoVariant = ['', 'alt', 'alt2'][$loop->index % 3];
                @endphp
                <div class="company-card {{ $isSelected ? 'is-selected' : '' }}"
                    data-company-name="{{ strtolower(($company->display_name ?? $company->name) . ' ' . $company->name) }}">
                    <div class="logo-mark {{ $logoVariant }}">
                        @if (!empty($company->logo) && file_exists(public_path('storage/' . $company->logo)))
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="">
                        @else
                            {{ $initials }}
                        @endif
                    </div>

                    <div class="info">
                        <div class="info-name-row">
                            <span class="info-name">{{ $company->display_name ?? $company->name }}</span>
                            @if ($isSelected)
                                <span class="selected-tag">CURRENT</span>
                            @endif
                        </div>
                        <div class="info-sub">
                            {{ $company->email ?? '—' }}
                            <span class="dot-sep">·</span>
                            <b>{{ optional($company->companyConnection)->company_bc_id ?? 'BC not set' }}</b>
                        </div>
                    </div>

                    <div class="meta">
                        <div class="meta-item">
                            <div class="meta-label">Users</div>
                            <div class="meta-value">{{ $company->users_count }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Status</div>
                            @if ($company->is_active && $isConnected)
                                <span class="status-pill"><svg viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="4" fill="currentColor" />
                                    </svg>Active · Connected</span>
                            @elseif ($company->is_active)
                                <span class="status-pill warning"><svg viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="4" fill="currentColor" />
                                    </svg>Active · Not connected</span>
                            @else
                                <span class="status-pill inactive"><svg viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="4" fill="currentColor" />
                                    </svg>Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="actions">
                        @if ($isSelected)
                            <button type="button" class="btn-manage is-current">Current</button>
                        @else
                            <form action="{{ route('companies.select', $company->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-manage">Manage</button>
                            </form>
                        @endif
                        <a href="{{ route('companies.edit', $company->id) }}" class="icon-btn" title="Edit">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                            </svg>
                        </a>
                        <a href="{{ route('companies.api.setup', $company->id) }}" class="icon-btn" title="API Setup">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z" />
                            </svg>
                        </a>
                        <button type="button" class="icon-btn danger open-delete-confirm" title="Delete"
                            data-url="{{ route('companies.destroy', $company->id) }}"
                            data-label="{{ $company->display_name ?? $company->name }}">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18" />
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">No companies yet. Create the first one.</div>
            @endforelse
        </div>

    </div>

    {{-- Delete confirmation overlay (same reused pattern as the Staff list) --}}
    <div class="pw-confirm-overlay" id="deleteConfirmOverlay">
        <div class="pw-confirm-box">
            <div class="pw-confirm-icon"><i class="bi bi-trash3-fill"></i></div>
            <h3 class="pw-confirm-title" id="deleteConfirmTitle">Delete this company?</h3>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.companies-page .custom-alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.animation = 'companyListFadeOut 0.5s ease-in forwards';
                    alert.addEventListener('animationend', function() {
                        alert.remove();
                    });
                }, 4000);
            });

            const searchInput = document.getElementById('companySearch');
            const cards = Array.from(document.querySelectorAll('#companyList .company-card'));
            const countPill = document.getElementById('companyCount');
            const total = cards.length;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const term = searchInput.value.trim().toLowerCase();
                    let shown = 0;

                    cards.forEach(function(card) {
                        const match = card.dataset.companyName.includes(term);
                        card.style.display = match ? '' : 'none';
                        if (match) shown++;
                    });

                    if (countPill) {
                        countPill.textContent = shown + ' of ' + total + ' shown';
                    }
                });
            }

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
                            'Delete this company?';
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
        });
    </script>
@endpush
