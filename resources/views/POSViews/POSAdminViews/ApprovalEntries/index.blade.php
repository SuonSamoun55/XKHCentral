@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSAdminViews/ApprovalEntries/index.css') }}">
@endpush

@section('title', 'Approval Entries')

@section('content')
<div class="pagelist-page">

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
    <h1>Approval Entries</h1>

    <div class="tab-row">
        <a href="{{ route('approval-entries.index') }}" class="tab-btn {{ !request('status') || request('status') === 'all' ? 'active' : '' }}">All</a>
        <a href="{{ route('approval-entries.index', ['status' => 'confirmed']) }}" class="tab-btn {{ request('status') === 'confirmed' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('approval-entries.index', ['status' => 'cancelled']) }}" class="tab-btn {{ request('status') === 'cancelled' ? 'active' : '' }}">Rejected</a>
    </div>

    <div class="table-card">
        <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Entry No.</th>
                    <th>Approval Type</th>
                    <th>To Approve</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Sender ID</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entries as $entry)
                    @php
                        $statusClass = match ($entry->status) {
                            'confirmed' => 'status-approved',
                            'cancelled' => 'status-rejected',
                            default => 'status-pending',
                        };
                        $statusLabel = match ($entry->status) {
                            'confirmed' => 'Approved',
                            'cancelled' => 'Rejected',
                            default => ucfirst($entry->status ?? '—'),
                        };
                    @endphp
                    <tr class="clickable-row" data-href="{{ route('admin.orders.show', $entry->order_id) }}">
                        <td class="id-cell" data-label="Entry No.">{{ $entry->entry_no ?? '—' }}</td>
                        <td class="label-cell" data-label="Approval Type">{{ $entry->action_type === 'cancelled' ? 'Order Rejection' : 'Order to BC' }}</td>
                        <td class="approve-cell" data-label="To Approve">Order: {{ $entry->order->order_no ?? '—' }}</td>
                        <td class="detail-cell" data-label="Details">{{ \Illuminate\Support\Str::limit($entry->note, 80) ?: '—' }}</td>
                        <td class="status-cell" data-label="Status">
                            <div class="url-list">
                                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                        </td>
                        <td class="sender-cell" data-label="Sender ID">{{ $entry->actionBy->name ?? '—' }}</td>
                        <td class="date-cell" data-label="Date">{{ $entry->created_at->format('n/j/Y g:i A') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">No approval entries yet — they appear here as soon as an order is confirmed (or fails to confirm) toward Business Central.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if ($entries->hasPages())
        <div style="margin-top:14px;">
            {{ $entries->links() }}
        </div>
    @endif

</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.pagelist-page .custom-alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.animation = 'pageListFadeOut 0.5s ease-in forwards';
                    alert.addEventListener('animationend', function () { alert.remove(); });
                }, 4000);
            });

            document.querySelectorAll('.pagelist-page .clickable-row').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) {
                        return;
                    }
                    const href = this.getAttribute('data-href');
                    if (href) {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
@endpush
