<link rel="stylesheet" href="{{ asset('css/POSsystem/pagination.css') }}">
<div class="pagination-container">
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
        {{-- Previous --}}
        <li>
            @if ($paginator->onFirstPage())
                <span class="disabled-arrow"><i class="bi bi-chevron-left"></i></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="arrow-icon"><i class="bi bi-chevron-left"></i></a>
            @endif
        </li>

        {{-- Numbers --}}
        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li>
                        @if ($page == $paginator->currentPage())
                            <span class="active-page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    </li>
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        <li>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="arrow-icon"><i class="bi bi-chevron-right"></i></a>
            @else
                <span class="disabled-arrow"><i class="bi bi-chevron-right"></i></span>
            @endif
        </li>
    </ul>
</div>
