@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/page_list.css') }}">
@endpush

@section('title', 'Page List')

@section('content')
<div class="pagelist-page">

    <div class="alert-container">
        @if (session('success'))
            <div class="custom-alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>

    <div class="page-head">
        <div>
            <h1>Page List</h1>
        </div>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
            Add Page
        </a>
    </div>

    <div class="tab-row">
        <button type="button" class="tab-btn active" data-tab="admin">Admin Side</button>
        <button type="button" class="tab-btn" data-tab="user">User Side</button>
    </div>

    @foreach (['admin' => 'admin', 'customer' => 'user'] as $groupKey => $tabKey)
        <div class="table-card" data-panel="{{ $tabKey }}" @if ($tabKey !== 'admin') style="display:none;" @endif>
            <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Page Key</th>
                        <th>Page Label</th>
                        <th>URL(s)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (($permissions[$groupKey] ?? []) as $permission)
                        <tr>
                            <td class="id-cell" data-label="No.">{{ $loop->iteration }}</td>
                            <td class="key-cell" data-label="Page Key"><code>{{ $permission->name }}</code></td>
                            <td class="label-cell" data-label="Page Label">{{ $permission->display_name }}</td>
                            <td class="url-cell" data-label="URL(s)">
                                <div class="url-list">
                                    @forelse (array_filter(array_map('trim', explode(',', $permission->urls ?? ''))) as $url)
                                        <span class="url-chip mono">{{ $url }}</span>
                                    @empty
                                        <span class="url-chip mono">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="actions-cell" data-label="Action">
                                <div class="action-group">
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="pill-btn edit">Edit</a>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this page? Any role assignments referencing it will be removed too.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pill-btn delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="5">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    @endforeach

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

            const tabBtns = document.querySelectorAll('.pagelist-page .tab-btn');
            const panels = document.querySelectorAll('.pagelist-page [data-panel]');
            tabBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const target = btn.getAttribute('data-tab');
                    tabBtns.forEach(function (b) { b.classList.toggle('active', b === btn); });
                    panels.forEach(function (p) {
                        p.style.display = (p.getAttribute('data-panel') === target) ? '' : 'none';
                    });
                    checkOverflow();
                });
            });

            // scroll-affordance: show a right-edge fade when the table has more to scroll
            function checkOverflow() {
                document.querySelectorAll('.pagelist-page .table-card').forEach(function (card) {
                    if (card.style.display === 'none') return;
                    const scroller = card.querySelector('.table-scroll');
                    if (!scroller) return;
                    const hasMore = scroller.scrollWidth > scroller.clientWidth + 2 &&
                        (scroller.scrollLeft + scroller.clientWidth) < scroller.scrollWidth - 2;
                    card.classList.toggle('has-overflow', hasMore);
                });
            }
            document.querySelectorAll('.pagelist-page .table-scroll').forEach(function (scroller) {
                scroller.addEventListener('scroll', checkOverflow);
            });
            window.addEventListener('resize', checkOverflow);
            checkOverflow();

            // preserve full text access on truncated URL chips via native tooltip
            document.querySelectorAll('.pagelist-page .url-chip').forEach(function (chip) {
                chip.setAttribute('title', chip.textContent.trim());
            });
        });
    </script>
@endpush
