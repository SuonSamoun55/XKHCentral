@extends('POSViews.POSAdminViews.app')

@section('title', 'Approval Order')

@push('styles')
<style>
    .content-area{
        width: 100%;
    }

    .approval-page-clean{
        height: 100%;
    }

    .approval-page-inner{
        background: #fff;
        min-height: calc(100vh - 0px);
        padding: 28px 40px 24px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
    }

    .back-btn-top{
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        transition: .2s ease;
    }

    .back-btn-top:hover{
        color: #19b2c1;
    }

    .approval-title{
        font-size: 24px;
        font-weight: 800;
        color: #39b1bc;
        margin: 0 0 22px;
        line-height: 1.2;
    }

    .approval-head{
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        gap: 18px;
        align-items: center;
        margin-bottom: 22px;
        flex-shrink: 0;
    }

    .order-no-text{
        font-size: 15px;
        color: #2f2f2f;
        font-weight: 500;
    }

    .order-date-text{
        font-size: 12px;
        color: #8a9099;
        line-height: 1.55;
    }

    .customer-head{
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: flex-start;
    }

    .customer-avatar{
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #edf1f4;
        flex-shrink: 0;
        background: #f8fafc;
    }

    .customer-name{
        font-size: 14px;
        font-weight: 500;
        color: #363636;
    }

    .detail-main{
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 28px;
        align-items: start;
        flex: 1 1 auto;
        min-height: 0;
        height: 100%;
    }

    .items-column{
        min-width: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .products-scroll{
        width: 100%;
        flex: 1;
        min-height: 0;
        max-height: 90%;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .products-scroll::-webkit-scrollbar{
        display: none;
        width: 0;
        height: 0;
    }

    .product-row{
        display: grid;
        grid-template-columns: minmax(0, 1fr) 56px 100px 24px;
        align-items: center;
        gap: 14px;
        padding: 10px 0;
    }

    .product-left{
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .product-image{
        width: 54px;
        height: 54px;
        border-radius: 8px;
        object-fit: cover;
        background: #f7f8fa;
        border: 1px solid #eef2f5;
        flex-shrink: 0;
    }

    .product-text{
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-top: 0;
    }

    .product-name{
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.25;
        margin: 0 0 4px;
        word-break: break-word;
    }

    .product-meta-line{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: #556274;
        line-height: 1.35;
    }

    .qty-select{
        width: 48px;
        height: 34px;
        border: 1px solid #d8dde5;
        border-radius: 8px;
        background: #fff;
        color: #6b7280;
        font-size: 13px;
        outline: none;
        padding: 0 6px;
    }

    .price-area{
        text-align: right;
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        white-space: nowrap;
    }

    .trash-btn{
        border: none;
        background: transparent;
        color: #a6adb7;
        font-size: 16px;
        padding: 0;
        cursor: default;
        line-height: 1;
    }

    .summary-column{
        min-width: 0;
        position: sticky;
        top: 20px;
        align-self: start;
    }

    .summary-card{
        background: #fff;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        padding: 18px 18px 16px;
    }

    .summary-row{
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        font-size: 15px;
        margin-bottom: 12px;
    }

    .summary-label{
        color: #2f2f2f;
        font-weight: 500;
    }

    .summary-value{
        color: #2f2f2f;
        font-weight: 700;
        min-width: 110px;
        text-align: right;
    }

    .summary-space{
        height: 10px;
    }

    .summary-actions{
        margin-top: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .summary-actions form{
        margin: 0;
        width: 100%;
    }

    .approve-btn{
        width: 100%;
        min-width: 140px;
        height: 42px;
        border: none;
        border-radius: 8px;
        background: #22c55e;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .approve-btn:hover{
        background: #16a34a;
    }

    .reject-btn{
        width: 100%;
        min-width: 140px;
        height: 42px;
        border: 1px solid #ef4444;
        border-radius: 8px;
        background: #fff;
        color: #ef4444;
        font-size: 14px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .reject-btn:hover{
        background: #fee2e2;
    }

    .status-view{
        width: 100%;
        min-width: 180px;
        height: 40px;
        border-radius: 8px;
        background: #eaf8fa;
        color: #149eac;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        margin-top: 16px;
    }

    .empty-products{
        text-align: center;
        padding: 24px 0;
        font-size: 14px;
        color: #7b8794;
    }

    .reject-modal-title{
        font-weight: 700;
        color: #1f2937;
    }

    .reject-modal-label{
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .reject-textarea{
        width: 100%;
        min-height: 120px;
        border: 1px solid #d9dde4;
        border-radius: 8px;
        background: #fff;
        outline: none;
        padding: 12px 14px;
        font-size: 13px;
        color: #374151;
        resize: vertical;
    }

    .reject-textarea:focus{
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.10);
    }

    @media (max-width: 1100px){
        .detail-main{
            grid-template-columns: 1fr;
        }

        .summary-column{
            position: static;
        }

        .products-scroll{
            max-height: 420px;
        }
    }

    @media (max-width: 900px){
        .approval-page-inner{
            padding: 22px 16px 16px;
        }

        .approval-head{
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .products-scroll{
            max-width: 100%;
            max-height: 360px;
            padding-right: 0;
        }

        .product-row{
            grid-template-columns: 1fr;
            gap: 10px;
            padding: 14px 0;
        }

        .product-image{
            width: 72px;
            height: 72px;
        }

        .product-name{
            font-size: 16px;
        }

        .product-meta-line{
            font-size: 14px;
        }

        .qty-area,
        .price-area,
        .trash-area{
            justify-content: flex-start;
            text-align: left;
        }

        .price-area{
            font-size: 16px;
        }
    }
</style>
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
