@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/TaxGroup/TaxList.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/NumberSeries/index.css') }}">
@endpush

@section('title', 'Number Series')

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
    <h1>Number Series</h1>
    <div class="page-head">
        <button type="button" class="export-btn" id="exportSeriesBtn" data-bs-toggle="modal" data-bs-target="#exportSeriesModal">
            <i class="bi bi-download"></i> Download as Excel
        </button>
        <a href="{{ route('number-series.create') }}" class="btn btn-primary">
            Add Number Series
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>

    <div class="table-card">
        <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Description</th>
                    <th>Starting No.</th>
                    <th>Ending No.</th>
                    <th>Last Date Used</th>
                    <th>Last No. Used</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($series as $s)
                    <tr>
                        <td class="series-page-cell" data-label="Page">{{ $purposes[$s->code] ?? $s->code }}</td>
                        <td class="label-cell" data-label="Description">{{ $s->name }}</td>
                        <td class="range-cell" data-label="Starting No.">{{ $s->start_formatted }}</td>
                        <td class="range-cell" data-label="Ending No.">{{ $s->end_formatted }}</td>
                        <td class="meta-cell" data-label="Last Date Used">{{ $s->last_used_at?->format('n/j/Y') ?? '—' }}</td>
                        <td class="meta-cell" data-label="Last No. Used">{{ $s->last_no ? $s->formatNumber($s->last_no) : '—' }}</td>
                        <td class="actions-cell" data-label="Action">
                            <div class="action-group">
                                <a href="{{ route('number-series.edit', $s->id) }}" class="pill-btn edit">Edit</a>
                                <form action="{{ route('number-series.destroy', $s->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this number series? Anything still calling code &quot;{{ $s->code }}&quot; will start failing.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-btn delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">No number series yet. Add one to give Orders (or a future feature) an auto-numbered ID range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="modal fade export-columns-modal" id="exportSeriesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content export-columns-content">
                <div class="modal-body">
                    <h5 class="export-columns-title">Choose columns to export</h5>
                    <p class="export-columns-sub">Downloads every number series listed below.</p>

                    <label class="export-column-row export-column-all">
                        <input type="checkbox" id="exportSeriesSelectAll" checked>
                        <span>Select all</span>
                    </label>

                    <div class="export-column-list" id="exportSeriesColumnList">
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="Page" checked>
                            <span>Page</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="Description" checked>
                            <span>Description</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="Starting No." checked>
                            <span>Starting No.</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="Ending No." checked>
                            <span>Ending No.</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="Last Date Used" checked>
                            <span>Last Date Used</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="Last No. Used" checked>
                            <span>Last No. Used</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer export-columns-footer">
                    <button type="button" class="export-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="export-confirm-btn" id="exportSeriesConfirmBtn">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
            </div>
        </div>
    </div>

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

            /* ── Export to CSV/Excel ── */
            const exportSelectAll = document.getElementById('exportSeriesSelectAll');
            const exportCheckboxes = Array.from(document.querySelectorAll('#exportSeriesColumnList .export-column-checkbox'));

            exportSelectAll?.addEventListener('change', function () {
                exportCheckboxes.forEach(cb => cb.checked = this.checked);
            });

            exportCheckboxes.forEach(cb => cb.addEventListener('change', function () {
                exportSelectAll.checked = exportCheckboxes.every(c => c.checked);
                exportSelectAll.indeterminate = !exportSelectAll.checked && exportCheckboxes.some(c => c.checked);
            }));

            function csvCell(value) {
                const s = String(value ?? '');
                return /[",\n]/.test(s) ? '"' + s.replace(/"/g, '""') + '"' : s;
            }

            document.getElementById('exportSeriesConfirmBtn')?.addEventListener('click', function () {
                const selected = exportCheckboxes.filter(cb => cb.checked).map(cb => cb.value);
                if (!selected.length) {
                    alert('Please select at least one column to export.');
                    return;
                }

                const rows = Array.from(document.querySelectorAll('.pagelist-page table tbody tr:not(.empty-row)')).map(row => {
                    const cellsByLabel = {};
                    row.querySelectorAll('td[data-label]').forEach(td => {
                        cellsByLabel[td.dataset.label] = td.textContent.trim();
                    });
                    return selected.map(col => csvCell(cellsByLabel[col] ?? '-'));
                });

                const header = selected.map(col => csvCell(col));
                const csv = '﻿' + [header, ...rows].map(r => r.join(',')).join('\r\n');

                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `number-series-${new Date().toISOString().slice(0, 10)}.csv`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);

                bootstrap.Modal.getOrCreateInstance(document.getElementById('exportSeriesModal')).hide();
            });
        });
    </script>
@endpush
