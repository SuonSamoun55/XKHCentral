<link rel="stylesheet" href="{{ asset('css/POSsystem/pagination.css') }}">

@if ($paginator->hasPages())
<div class="pagination-container">

    {{-- SHOW ITEMS --}}
    <div class="show-items-wrapper">
        <span>Show</span>
        <select onchange="window.location.href=this.value">
            @foreach([10, 20, 50, 100] as $limit)
                <option value="{{ request()->fullUrlWithQuery(['limit' => $limit]) }}"
                    {{ $paginator->perPage() == $limit ? 'selected' : '' }}>
                    {{ $limit }}
                </option>
            @endforeach
        </select>
        <span>items</span>
    </div>

    <ul class="pagination-nav">

        {{-- PREVIOUS --}}
        <li>
            @if ($paginator->onFirstPage())
                <span class="disabled-arrow">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="arrow-icon">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif
        </li>

        {{-- PAGE NUMBER WINDOW (MAX 5) --}}
        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();

            $start = max(1, $current - 2);
            $end   = min($last, $current + 2);

            if ($current <= 3) {
                $start = 1;
                $end = min(5, $last);
            }

            if ($current >= $last - 2) {
                $start = max(1, $last - 4);
                $end = $last;
            }
        @endphp

        @for ($page = $start; $page <= $end; $page++)
            <li>
                @if ($page == $current)
                    <span class="active-page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
                @endif
            </li>
        @endfor

        {{-- NEXT --}}
        <li>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="arrow-icon">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="disabled-arrow">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </li>

    </ul>
</div>
@endif
