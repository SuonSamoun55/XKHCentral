@extends('POSViews.POSAdminViews.app')

@section('title', 'Item Detail')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Items/show.css') }}">
@endpush

@section('content')
<main class="detail-content">
        <div class="detail-card">
            <a href="{{ url()->previous() }}" class="back-btn text-decoration-none">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="detail-grid">
                <div>
                    <div class="main-image">
                        <img
                            src="{{ url('/item-image/' . $item['id']) }}"
                            alt="{{ $item['displayName'] ?? 'Item Image' }}"
                            onerror="this.src='https://placehold.co/800x600/e5e7eb/94a3b8?text=No+Photo'">
                    </div>

                    <div class="thumb-row">
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="item-title">{{ $item['displayName'] ?? 'No Name' }}</div>
                    <div class="item-price">${{ number_format((float)($item['unitPrice'] ?? 0), 2) }}</div>

                    <div class="item-desc">
                        {{ $item['description'] ?? 'No description available for this item.' }}
                    </div>

                    <div class="section-title">Available Size / Unit of Measure</div>

                    <div class="size-row">
                        <div class="size-chip">
                            {{ $item['baseUnitOfMeasureCode'] ?? 'PCS' }}
                        </div>
                    </div>

                    <div class="stock-box">
                        <i class="bi bi-arrow-right-circle text-info"></i>
                        Stock : <span>{{ (int)($item['inventory'] ?? 0) }} items left</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

