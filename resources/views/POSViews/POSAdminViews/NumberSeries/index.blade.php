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
        });
    </script>
@endpush
