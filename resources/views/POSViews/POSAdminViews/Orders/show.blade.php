@extends('POSViews.POSAdminViews.app')

@section('title', 'Approval Order')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Orders/show.css') }}">
@endpush

@section('content')
<div class="approval-page-clean">
    <div class="approval-page-inner">

        <div class="top-back-wrap">
            <a href="{{ route('admin.orders.index', ['tab' => 'new']) }}" class="back-btn-top">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

        <h1 class="approval-title">Approval Order</h1>

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger py-2 px-3 mb-3">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="approval-head">
            <div class="order-no-text">
                Order Number ({{ $order->order_no ?? '#98090' }})
            </div>

            <div class="order-date-text">
                {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('M d Y') }}<br>
                {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('g:ia') }}
            </div>

            <div class="customer-head">
                <img
                    class="customer-avatar"
                    src="{{ $order->user->profile_image_display ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name ?? 'User') . '&background=17bfd0&color=fff&size=128' }}"
                    alt="{{ $order->user->name ?? 'Customer' }}"
                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=17bfd0&color=fff&size=128';"
                >
                <div class="customer-name">{{ $order->user->name ?? 'N/A' }}</div>
            </div>
        </div>

        @php
            $subtotal = $order->subtotal ?? ($order->items->sum('line_total') ?? 0);
            $shipping = $order->shipping_amount ?? 25;
            $taxes = $order->tax_amount ?? 0;
            $discount = $order->discount_amount ?? 0;
            $totalUsd = $order->total_amount ?? (($subtotal + $shipping + $taxes) - $discount);
            $rielRate = $order->riel_rate ?? 4100;
            $totalKhr = $totalUsd * $rielRate;
        @endphp

        <div class="detail-main">
            <div class="items-column">
                <div class="products-scroll">
                    @forelse($order->items as $item)
                        @php
                            $itemModel = $item->item ?? null;

                            $productImage = $itemModel && $itemModel->image_url
                                ? $itemModel->image_url
                                : 'https://via.placeholder.com/86x86?text=Item';

                            $productName = $item->item_name ?? 'Item Name';

                            $parts = array_values(array_filter([
                                $itemModel->display_name ?? null,
                                $itemModel->description ?? null,
                                $itemModel->item_category_code ?? null,
                                $itemModel->base_unit_of_measure_code ?? null,
                            ]));
                        @endphp

                        <div class="product-row">
                            <div class="product-left">
                                <img class="product-image" src="{{ $productImage }}" alt="{{ $productName }}">

                                <div class="product-text">
                                    <div class="product-name">{{ $productName }}</div>

                                    <div class="product-meta-line">
                                        @foreach($parts as $part)
                                            <span class="meta-part">{{ $part }}</span>
                                            @if(!$loop->last)
                                                <span class="meta-dot">•</span>
                                            @endif
                                        @endforeach

                                        @if(count($parts) > 0)
                                            <span class="meta-dot">•</span>
                                        @endif

                                        <span class="stock-inline">
                                            <span class="check">✓</span>
                                            <span>In stock</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="qty-area">
                                <select class="qty-select" disabled>
                                    <option selected>{{ (int) ($item->qty ?? 1) }}</option>
                                </select>
                            </div>

                            <div class="price-area">
                                ${{ number_format($item->unit_price ?? $item->line_total ?? 0, 2) }}
                            </div>

                            <div class="trash-area">
                                <button type="button" class="trash-btn" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-products">No items found.</div>
                    @endforelse
                </div>
            </div>

            <div class="summary-column">
                <div class="summary-card">
                    <div class="summary-row">
                        <div class="summary-label">Subtotal</div>
                        <div class="summary-value">${{ number_format($subtotal, 2) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Shipping</div>
                        <div class="summary-value">${{ number_format($shipping, 2) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Taxes</div>
                        <div class="summary-value">${{ number_format($taxes, 2) }}</div>
                    </div>

                    @if($discount > 0)
                        <div class="summary-row">
                            <div class="summary-label">Discount</div>
                            <div class="summary-value">- ${{ number_format($discount, 2) }}</div>
                        </div>
                    @endif

                    <div class="summary-space"></div>

                    <div class="summary-row">
                        <div class="summary-label">Total in USD</div>
                        <div class="summary-value">${{ number_format($totalUsd, 2) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Total in Khmer Riel</div>
                        <div class="summary-value">៛{{ number_format($totalKhr, 0) }}</div>
                    </div>

                    @if(($order->status ?? '') === 'pending')
                        <div class="summary-actions">
                            <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="approve-btn">Approve</button>
                            </form>

                            <button type="button"
                                    class="reject-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                Reject
                            </button>
                        </div>
                    @else
                        <div class="status-view">
                            {{ ucfirst($order->status) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@if(($order->status ?? '') === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                @csrf

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title reject-modal-title">Reject Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-3">
                    <label class="reject-modal-label">Reason for rejection</label>
                    <textarea
                        name="note"
                        class="reject-textarea"
                        placeholder="Please write the reason for rejecting this order..."
                        required>{{ old('note') }}</textarea>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
