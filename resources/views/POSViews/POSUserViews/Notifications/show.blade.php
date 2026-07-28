@extends('ManagementSystemViews.UserViews.Layouts.app')
@section('title', 'Notification Detail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Notifications/notification-detail.css') }}" />
@endpush

@section('content')

    <div class="page">
        <div class="Head">
            <div class="header">
            <div class="cart-nav">
                <a href="{{ route('user.notifications') }}" class="back-arrow icon-btn" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <h1>Notification Detail</h1>
        </div>
            <a href="{{ route('user.pos.cart') }}" class="cart-btn" title="Cart">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </a>
        </div>
        <div class="sender-row">
            <div class="avatar">
                @if ($ndSenderImage)
                    <img src="{{ $ndSenderImage }}"
                        alt="{{ $ndSenderDisplayName }}"
                        class="avatar-img"
                        onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
                @endif
                <span class="avatar-fallback" style="{{ $ndSenderImage ? 'display:none;' : 'display:flex;' }}">
                    @if ($notification->type === 'admin_message' || $notification->type === 'global_message' || $ndActionAdmin)
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    @endif
                </span>
            </div>
            <div class="sender-info">
                <div class="name">{{ $ndSenderDisplayName }}</div>
                <div class="sendto">
                    <span>
                        {{ $ndSenderContact }}
                    </span>
                </div>
            </div>
        </div>
        <div class="order-meta-row">
            <div class="order-meta">
                @if ($isOrderNotification && $notification->order_id)
                    <div class="row">
                        <span class="label">Action Date: </span>
                        <span class="value">
                            {{ $notification->created_at->format('D d/m/Y h:i A') }}
                        </span>
                    </div>
                @endif
                <div class="row">
                    <span class="label">Type</span>
                    <span class="value">
                        @if ($notification->type === 'admin_message')
                            Admin Message
                        @elseif ($notification->type === 'global_message')
                            Global Message
                        @else
                            Order
                        @endif
                    </span>
                </div>
                @if ($ndActionAdmin)
                    <div class="row">
                        <span class="label">{{ $ndSenderLabel }}</span>
                        <span class="value">{{ $ndSenderDisplayName }}</span>
                    </div>
                @endif
            </div>

            <div class="actions{{ $ndStatusKey ? ' actions--'.$ndStatusKey : '' }}">
                <span class="cancel-icon" title="Delete notification">
                    @if ($ndStatusKey === 'message')
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16v16H4z"></path>
                            <path d="M22 6l-10 7L2 6"></path>
                        </svg>
                    @elseif ($ndStatusKey === 'approved')
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            <path d="M12 11l2 2 4-4"></path>
                        </svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            <path d="M13 9l4 4m0-4l-4 4"></path>
                        </svg>
                    @endif
                </span>
                <span class="cancel-btn">{{ $ndStatusLabel ?? 'Delete Notification' }}</span>
            </div>
        </div>

        {{-- Title --}}
        <div class="message-label">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            {{ $notification->title }}
        </div>
        <div class="message-box">{{ $notification->message }}</div>
        @if ($isOrderNotification)
            <h2 class="section-title">Order Detail</h2>
            <div class="item-count">{{ $orderItems->count() }} {{ Str::plural('item', $orderItems->count()) }}</div>

            @if ($orderItems->isNotEmpty())
                <div class="content-grid">
                    <div class="items-col">
                        <div class="items-list">
                            @foreach ($orderItems as $orderItem)
                                <div class="item-row">
                                    <img class="item-img"
                                        src="{{ $orderItem->display_image ?? asset('images/no-image.png') }}"
                                        alt="{{ $orderItem->item_name ?? 'Item' }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">

                                    <div class="item-info">
                                        <div class="item-name">{{ $orderItem->item_name ?? $orderItem->item_no }}</div>
                                        <div class="item-sub">
                                            @if ($orderItem->variant_description)
                                                {{ $orderItem->variant_description }}<br>
                                            @endif
                                            VAT {{ rtrim(rtrim(number_format($orderItem->display_vat_percent, 2), '0'), '.') }}%: ${{ number_format($orderItem->display_vat_amount, 2) }}
                                            @if ($orderItem->display_discount_percent > 0)
                                                <br>Discount : {{ rtrim(rtrim(number_format($orderItem->display_discount_percent, 2), '0'), '.') }}% (-${{ number_format($orderItem->display_vat_amount, 2) }})
                                            @endif
                                        </div>
                                    </div>

                                    <div class="item-qty">x{{ $orderItem->display_qty }}</div>
                                    <div class="item-price">${{ number_format($orderItem->display_net_unit_price, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="summary-col">
                        <div class="summary-title">Order Summary</div>

                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span class="val">${{ number_format($ndGrossSubtotal, 2) }}</span>
                        </div>

                        <div class="summary-line summary-line-discount">
                            <span>Discount</span>
                            <span class="val">-${{ number_format($ndDiscountTotal, 2) }}</span>
                        </div>

                        {{-- <div class="summary-line">
                            <span>Delivery</span>
                            <span class="val">${{ number_format($ndDeliveryFee, 2) }}</span>
                        </div> --}}

                        <div class="summary-line">
                            <span>
                                VAT

                            </span>
                            <span class="val">${{ number_format($ndTax, 2) }}</span>
                        </div>

                        <hr class="summary-divider">

                        <div class="summary-total">
                            <span class="label">Order total in USD</span>
                            <span class="val">${{ number_format($ndTotal, 2) }}</span>
                        </div>
                        <div class="summary-total summary-total-secondary">
                            <span class="label">Order total in Khmer Riel</span>
                            <span class="val">Riel {{ number_format($ndTotalRiel, 0) }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-items">No items found for this order.</div>
            @endif
        @endif
    </div>
@endsection
