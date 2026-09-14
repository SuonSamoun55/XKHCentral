@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/PermissionPage/PermissionPage_List.css') }}">
@endpush

@section('title', 'Permission Page')

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
                <h1>Permission Page</h1>
            </div>
        </div>

        <div class="tab-row">
            <button type="button" class="tab-btn active" data-tab="admin">Admin Side</button>
            <button type="button" class="tab-btn" data-tab="user">User Side</button>
        </div>

        @foreach (['admin' => 'admin', 'customer' => 'user'] as $groupKey => $tabKey)
            <div class="table-card" data-panel="{{ $tabKey }}"
                @if ($tabKey !== 'admin') style="display:none;" @endif>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Page Key</th>
                                <th>Page Label</th>
                                <th>URL(s)</th>
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
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="4">No pages found.</td>
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
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.pagelist-page .custom-alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.animation = 'pageListFadeOut 0.5s ease-in forwards';
                    alert.addEventListener('animationend', function() {
                        alert.remove();
                    });
                }, 4000);
            });

            const tabBtns = document.querySelectorAll('.pagelist-page .tab-btn');
            const panels = document.querySelectorAll('.pagelist-page [data-panel]');
            tabBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const target = btn.getAttribute('data-tab');
                    tabBtns.forEach(function(b) {
                        b.classList.toggle('active', b === btn);
                    });
                    panels.forEach(function(p) {
                        p.style.display = (p.getAttribute('data-panel') === target) ? '' :
                            'none';
                    });
                    checkOverflow();
                });
            });

            // scroll-affordance: show a right-edge fade when the table has more to scroll
            function checkOverflow() {
                document.querySelectorAll('.pagelist-page .table-card').forEach(function(card) {
                    if (card.style.display === 'none') return;
                    const scroller = card.querySelector('.table-scroll');
                    if (!scroller) return;
                    const hasMore = scroller.scrollWidth > scroller.clientWidth + 2 &&
                        (scroller.scrollLeft + scroller.clientWidth) < scroller.scrollWidth - 2;
                    card.classList.toggle('has-overflow', hasMore);
                });
            }
            document.querySelectorAll('.pagelist-page .table-scroll').forEach(function(scroller) {
                scroller.addEventListener('scroll', checkOverflow);
            });
            window.addEventListener('resize', checkOverflow);
            checkOverflow();

            // preserve full text access on truncated URL chips via native tooltip
            document.querySelectorAll('.pagelist-page .url-chip').forEach(function(chip) {
                chip.setAttribute('title', chip.textContent.trim());
            });
        });
    </script>
@endpush
