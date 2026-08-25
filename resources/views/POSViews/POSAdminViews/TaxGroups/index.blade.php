@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/page_list.css') }}">
@endpush

@section('title', 'Tax Groups')

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

    <div class="page-head">
        <div>
            <h1>Tax Groups</h1>
            <p>The percentage each Business Central tax group code (like <code>GST15</code>) actually means. Items synced from BC carry a code — this table is what turns that code into a real rate for checkout.</p>
        </div>
        <a href="{{ route('tax-groups.create') }}" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
            Add Tax Group
        </a>
    </div>

    @if ($unconfiguredCodes->isNotEmpty())
        <div class="error-banner" style="display:flex;align-items:flex-start;gap:10px;background:#FCF3E1;border:1px solid #F2D08F;color:#C9860C;border-radius:9px;padding:12px 14px;margin-bottom:18px;font-size:13px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><path d="M12 8v5"/><path d="M12 16h.01"/></svg>
            <div>
                Synced items are using a code with no rate configured yet, so they're currently charging <b>0%</b> tax:
                <div class="url-list" style="margin-top:6px;">
                    @foreach ($unconfiguredCodes as $code)
                        <span class="url-chip mono">{{ $code }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="table-card">
        <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Display Name</th>
                    <th>Percent</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($taxGroups as $taxGroup)
                    <tr>
                        <td class="id-cell" data-label="ID">{{ $taxGroup->id }}</td>
                        <td class="key-cell" data-label="Code"><code>{{ $taxGroup->code }}</code></td>
                        <td class="label-cell" data-label="Display Name">{{ $taxGroup->display_name ?? '—' }}</td>
                        <td class="url-cell" data-label="Percent">
                            <div class="url-list"><span class="url-chip mono">{{ rtrim(rtrim(number_format($taxGroup->percent, 2), '0'), '.') }}%</span></div>
                        </td>
                        <td class="actions-cell" data-label="Action">
                            <div class="action-group">
                                <a href="{{ route('tax-groups.edit', $taxGroup->id) }}" class="pill-btn edit">Edit</a>
                                <form action="{{ route('tax-groups.destroy', $taxGroup->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this tax group? Items already tagged with this code will keep showing 0% until you add it again.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-btn delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="5">No tax groups yet. Add one for each code Business Central sends (e.g. <code>GST15</code>).</td>
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
