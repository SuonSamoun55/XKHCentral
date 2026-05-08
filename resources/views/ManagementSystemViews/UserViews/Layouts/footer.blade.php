@if ($paginator->hasPages())
<nav class="desktop-pagination">
    <ul class="pagination justify-content-center">

        {{-- Previous --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}">
                &lsaquo;
            </a>
        </li>

        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $start = max(1, $current - 2);
            $end = min($last, $current + 2);

            if ($current <= 3) {
                $start = 1;
                $end = min(5, $last);
            }

            if ($current > $last - 3) {
                $start = max(1, $last - 4);
                $end   = $last;
            }
        @endphp

        {{-- Page Numbers (limit 5) --}}
        @for ($page = $start; $page <= $end; $page++)
            <li class="page-item {{ $page == $current ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url($page) }}">
                    {{ $page }}
                </a>
            </li>
        @endfor

        {{-- Next --}}
        <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}">
                &rsaquo;
            </a>
        </li>

    </ul>
</nav>
@endif
<style>
    .desktop-pagination .pagination {
    gap: 6px;
}

.desktop-pagination .page-link {
    min-width: 36px;
    height: 36px;
    border-radius: 8px;
    text-align: center;
    font-weight: 500;
}

.desktop-pagination .page-item.active .page-link {
    background: #0ea5e9;
    border-color: #0ea5e9;
    color: #fff;
}

.desktop-pagination .page-item.disabled .page-link {
    opacity: 0.4;
}

</style>