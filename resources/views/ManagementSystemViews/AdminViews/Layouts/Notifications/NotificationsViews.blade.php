@extends('Layout.POSAdmin.app')
@section('title', 'Notification Detail')
@section('backUrl', route('admin.notifications.index'))

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('/css/views/POSViews/POSAdminViews/AdminNotification/NotificationsViews.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSAdminViews/AdminNotification/NotificationsViews.css')) }}">
@endpush
@section('content')
    @php
        $user = $notification->user;
        $sender = $notification->sender;
        $isUserContact = $notification->type === 'user_contact';
        $contactUser = $isUserContact ? ($sender ?: $user) : ($user ?: $sender);

        $hasRealAvatar =
            ($contactUser && !empty($contactUser->profile_image_display)) ||
            !empty($notification->sender_profile_image);
        $avatarSrc = $contactUser->profile_image_display ?? ($notification->sender_profile_image ?? '');

        $displayName =
            optional($contactUser)->name ?? (($notification->sender_name ?: optional($sender)->name) ?? 'System');
        $avatarInitial = mb_strtoupper(mb_substr($displayName, 0, 1));

        $recipientName = optional($user)->name ?? (optional($sender)->name ?? 'N/A');
        $recipientEmail = optional($user)->email ?? optional($sender)->email;
        $typeLabel = str($notification->type ?? 'notification')
            ->replace('_', ' ')
            ->title();
        $cleanTitle = trim(
            (string) preg_replace('/\s*\(Sent to \d+ users\)\s*/i', '', (string) ($notification->title ?? '')),
        );
        $messageDate = optional($notification->updated_at)->format('D d/m/Y h:i A');
        $rawMessage = (string) ($notification->message ?? '');
        $sentToNames = $recipientEmail ?: $recipientName;
        $sentToHtml = e($recipientEmail ?: $recipientName);
        $messageTextForScan = html_entity_decode(
        strip_tags(preg_replace('/<br\s*\/@endphp/i', "\n", $rawMessage)),
    ENT_QUOTES | ENT_HTML5,
    'UTF-8',
);

if (preg_match('/Recipients:\s*(.+)/i', $messageTextForScan, $matches)) {
    $extractedRecipients = trim(preg_replace('/\s+/', ' ', (string) $matches[1]));
    if ($extractedRecipients !== '') {
        $sentToNames = $extractedRecipients;
        $sentToHtml = e($extractedRecipients);
    }
}

if (preg_match('/Recipients:\s*(.+?)(?:<\/p>|<\/div>|<br\s*\/?>|\r?\n|$)/is', $rawMessage, $htmlMatches)) {
    $extractedRecipientsHtml = trim(strip_tags((string) $htmlMatches[1]));
    if ($extractedRecipientsHtml !== '') {
        $sentToHtml = e($extractedRecipientsHtml);
    }
}

$cleanMessage = preg_replace('/<p[^>]*>\s*Recipients:\s*.*?<\/p>/is', '', $rawMessage);
$cleanMessage = preg_replace('/<div[^>]*>\s*Recipients:\s*.*?<\/div>/is', '', $cleanMessage);
$cleanMessage = preg_replace('/<span[^>]*>\s*Recipients:\s*.*?<\/span>/is', '', $cleanMessage);
$cleanMessage = preg_replace('/Recipients:\s*.*?(?:<br\s*\/?>|\r?\n|$)/is', '', $cleanMessage);
if ($sentToNames !== $recipientName) {
    $cleanMessage = str_ireplace('Recipients: ' . $sentToNames, '', $cleanMessage);
}
$cleanMessage = trim((string) $cleanMessage);

$order = $notification->order;
$isOrderNotification = $notification->type === 'order' && $order;
$isStockNotification = $notification->type === 'out_of_stock' && $stockItem;
$isGlobalMessageNotification = $notification->type === 'global_message';

if ($isOrderNotification) {
    $shipping = (float) ($order->shipping_amount ?? 0);
    $orderDiscount = (float) ($order->discount_amount ?? 0);
    $orderTotalUsd = (float) $order->total_amount + $shipping;
    $orderPlacedAt = optional($order->checked_out_at ?? $order->created_at)->format('d M Y, h:i A');
    $orderNotifiedAt = optional($notification->updated_at)->format('d M Y, h:i A');

    $orderStatusMap = [
        'cancelled' => [
            'class' => 'cancelled',
            'pillIcon' => 'bi-x-circle-fill',
            'alertIcon' => 'bi-cart-x-fill',
            'label' => 'Cancelled',
            'note' => 'Order cancelled',
        ],
        'pending' => [
            'class' => 'pending',
            'pillIcon' => 'bi-hourglass-split',
            'alertIcon' => 'bi-cart-x-fill',
            'label' => 'Pending',
            'note' => 'Order pending',
        ],
    ];
    $orderStatusInfo = $orderStatusMap[$order->status] ?? [
        'class' => 'confirmed',
        'pillIcon' => 'bi-check-circle-fill',
        'alertIcon' => 'bi-check-circle-fill',
        'label' => ucfirst($order->status),
        'note' => 'Order ' . $order->status,
    ];
}
?>

    <div class="notification-detail-page {{ $isOrderNotification ? 'has-mobile-receipt' : '' }}">
        <div class="alert-container" id="alertContainer"></div>

        <div class="image-viewer" id="detailImageViewer">
            <button type="button" class="image-viewer-close" id="detailImageViewerClose"
                aria-label="Close image viewer">&times;</button>
            <div class="image-viewer-stage">
                <button type="button" class="image-viewer-btn" id="detailImagePrev"
                    aria-label="Previous image">&lt;</button>
                <img src="" alt="Full preview" class="image-viewer-img" id="detailImageViewerImg">
                <button type="button" class="image-viewer-btn" id="detailImageNext" aria-label="Next image">&gt;</button>
            </div>
            <div class="image-viewer-count" id="detailImageViewerCount">1 / 1</div>
        </div>

        <div class="page-wrap">
            <div class="detail-wrapper">
                <div class="detail-header">
                    <div class="header-title-group">
                        <a href="{{ route('admin.notifications.index') }}" class="header-back-arrow" aria-label="Back"
                            id="detailBackArrow">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <h2 class="page-title">Notification Detail</h2>
                    </div>

                </div>
                @unless ($isStockNotification || $isGlobalMessageNotification)
                    <div class="detail-card {{ $isOrderNotification ? 'order-info-row' : '' }}">
                        <div class="top-user-box">
                            <div class="avatar-box large">
                                @if ($hasRealAvatar)
                                    <img src="{{ $avatarSrc }}" alt="{{ $displayName }}"
                                        onerror="this.onerror=null;this.parentElement.innerHTML='{{ $avatarInitial }}';this.parentElement.classList.add('letter-avatar');">
                                @else
                                    <span class="letter-avatar">{{ $avatarInitial }}</span>
                                @endif
                            </div>

                            <div class="user-info-box">
                                <h3>{{ $displayName }}</h3>
                                <p class="sent-to-line">Sent to {!! $sentToHtml !!}</p>
                                @unless ($isOrderNotification)
                                    <p class="time-ago-line">{{ optional($notification->updated_at)->diffForHumans() }}</p>
                                @endunless
                            </div>
                        </div>

                        @if ($isOrderNotification)
                            <div class="order-meta-item">
                                <label>Order ID</label>
                                <div class="plain-value">{{ $order->order_no }}</div>
                            </div>

                            <div class="order-meta-item">
                                <label>Placed on</label>
                                <div class="plain-value">{{ $orderPlacedAt }}</div>
                            </div>

                            <div class="order-status-group">
                                <span class="order-status-chip {{ $orderStatusInfo['class'] }}">
                                    <i class="bi {{ $orderStatusInfo['pillIcon'] }}"></i>
                                    {{ $orderStatusInfo['label'] }}
                                </span>
                                <span class="order-status-timestamp">{{ $orderNotifiedAt }}</span>
                            </div>
                        @else
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Type</label>
                                    <div class="plain-value">{{ $notification->type ?: 'N/A' }}</div>
                                </div>

                                <div class="detail-item">
                                    <label>Date</label>
                                    <div class="plain-value">{{ $messageDate ?: 'N/A' }}</div>
                                </div>

                                <div class="detail-item full">
                                    <label class="message-label"><i class="bi bi-pencil-square"></i> Message</label>
                                    <div class="content-card">
                                        {{-- <h4 class="content-title">{{ $cleanTitle !== '' ? $cleanTitle : 'Notification Message' }}</h4> --}}
                                        <div class="content-divider"></div>
                                        <div class="rendered-message">
                                            @if (filled($cleanMessage))
                                                {!! $cleanMessage !!}
                                            @else
                                                <p>No message content available.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endunless
                @if ($isOrderNotification && filled($cleanMessage))
                    <div class="order-alert-banner {{ $orderStatusInfo['class'] }}">
                        <span class="alert-icon-badge {{ $orderStatusInfo['class'] }}">
                            <i class="bi {{ $orderStatusInfo['alertIcon'] }}"></i>
                        </span>
                        <div class="rendered-message">{!! $cleanMessage !!}</div>
                    </div>
                @endif

                @if ($isOrderNotification)
                    <h3 class="order-detail-title">Order details</h3>
                    <div class="order-detail-grid">

                        <div class="order-items-card">
                            <div class="order-items-count">{{ $orderItemsTotal }} items</div>

                            <div class="order-items-table-wrap">
                                <table class="order-items-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Unit price</th>
                                            <th>Discount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderItems as $line)
                                            @php
                                                $lineImage =
                                                    optional($line->itemVariant)->image_url ??
                                                    (optional($line->item)->custom_image_url ??
                                                        optional($line->item)->image_url);
                                                $lineImage = $lineImage
                                                    ? (str_starts_with($lineImage, 'http')
                                                        ? $lineImage
                                                        : asset($lineImage))
                                                    : asset('images/no-image.png');
                                                $lineVatPercent = !empty(optional($line->item)->price_includes_tax)
                                                    ? 0
                                                    : max(
                                                        0,
                                                        (float) (optional($line->item)->resolved_vat_percent ?? 0),
                                                    );
                                                $lineDiscountPercent = (float) ($line->discount_percent ?? 0);
                                            @endphp
                                            <tr>
                                                <td class="oi-item-cell">
                                                    <img class="order-line-img" src="{{ $lineImage }}"
                                                        alt="{{ $line->item_name }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                                    <div class="order-line-info">
                                                        <div class="order-line-name">{{ $line->item_name }}</div>
                                                        <div class="order-line-meta">
                                                            VAT {{ $lineVatPercent }}%
                                                            &middot; SKU: {{ $line->item_no ?: 'N/A' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="qty-stepper" role="group"
                                                        aria-label="Quantity {{ (int) $line->qty }}">
                                                        <span class="qty-btn" aria-hidden="true">&minus;</span>
                                                        <span class="qty-value">{{ (int) $line->qty }}</span>
                                                        <span class="qty-btn" aria-hidden="true">+</span>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($line->unit_price ?? 0, 2) }}</td>
                                                <td>{{ $lineDiscountPercent > 0 ? rtrim(rtrim(number_format($lineDiscountPercent, 2), '0'), '.') . '%' : '—' }}
                                                </td>
                                                <td class="oi-total">${{ number_format($line->line_total ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($orderItems->hasPages())
                                <div class="order-items-pagination">
                                    <a class="page-arrow {{ $orderItems->onFirstPage() ? 'disabled' : '' }}"
                                        href="{{ $orderItems->onFirstPage() ? '#' : $orderItems->previousPageUrl() }}"
                                        aria-label="Previous page"><i class="bi bi-chevron-left"></i></a>

                                    @for ($p = 1; $p <= $orderItems->lastPage(); $p++)
                                        <a class="page-num {{ $p === $orderItems->currentPage() ? 'active' : '' }}"
                                            href="{{ $orderItems->url($p) }}">{{ $p }}</a>
                                    @endfor

                                    <a class="page-arrow {{ $orderItems->hasMorePages() ? '' : 'disabled' }}"
                                        href="{{ $orderItems->hasMorePages() ? $orderItems->nextPageUrl() : '#' }}"
                                        aria-label="Next page"><i class="bi bi-chevron-right"></i></a>
                                </div>
                            @endif
                        </div>

                        <div class="sumary">
                            <div class="order-summary-card">
                                <h4 class="order-summary-title">Order summary</h4>
                                <div class="order-items-count">{{ $orderItemsTotal }} items</div>
                                <div class="order-summary-align-spacer"></div>
                                <div class="order-summary-row">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
                                </div>
                                @if ($shipping > 0)
                                    <div class="order-summary-row">
                                        <span>Delivery</span>
                                        <span>${{ number_format($shipping, 2) }}</span>
                                    </div>
                                @endif
                                <div class="order-summary-row">
                                    <span>VAT</span>
                                    <span>${{ number_format($orderVat, 2) }}</span>
                                </div>
                                @if ($orderDiscount > 0)
                                    <div class="order-summary-row discount">
                                        <span>Discount</span>
                                        <span>-${{ number_format($orderDiscount, 2) }}</span>
                                    </div>
                                @endif

                                <div class="order-summary-divider"></div>

                                <div class="order-summary-row total">
                                    <span>Total in USD</span>
                                    <span>${{ number_format($orderTotalUsd, 2) }}</span>
                                </div>

                                <a href="{{ route('admin.orders.invoice', $order->id) }}" class="download-invoice-btn">
                                    <i class="bi bi-download"></i> Download invoice
                                </a>
                            </div>
                        </div>

                    </div>
                @elseif ($isStockNotification)
                    <div class="order-detail-grid stock-detail-grid">
                        <div class="stock-left-col">
                            <div class="detail-card order-info-row stock-profile-card">
                                <div class="stock-profile-user">
                                    <div class="avatar-box large">
                                        @if ($hasRealAvatar)
                                            <img src="{{ $avatarSrc }}" alt="{{ $displayName }}"
                                                onerror="this.onerror=null;this.parentElement.innerHTML='{{ $avatarInitial }}';this.parentElement.classList.add('letter-avatar');">
                                        @else
                                            <span class="letter-avatar">{{ $avatarInitial }}</span>
                                        @endif
                                    </div>

                                    <div class="user-info-box">
                                        <h3>{{ $displayName }}</h3>
                                        <p class="sent-to-line">Sent to {!! $sentToHtml !!}</p>
                                    </div>
                                </div>

                                <div class="stock-profile-status">
                                    <span class="order-status-chip {{ $stockLevel['class'] }}">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        {{ $stockLevel['label'] }}
                                    </span>
                                    <span class="order-status-timestamp">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $messageDate }}
                                    </span>
                                </div>
                            </div>

                            <div class="stock-alert-card">
                                <div class="stock-alert-icon {{ $stockLevel['class'] }}">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                                <span class="stock-card-label">INVENTORY ALERT</span>
                                <h4 class="stock-alert-title">{{ $cleanTitle !== '' ? $cleanTitle : 'Stock alert' }}</h4>
                                <p class="stock-alert-message">
                                    {{ trim($cleanMessage) !== '' ? trim($cleanMessage) : 'No further details available.' }}
                                </p>

                                <div class="stock-alert-divider"></div>
                                <div class="stock-alert-footer {{ $stockOrder ? '' : 'single' }}">
                                    @if ($stockOrder)
                                        <a href="{{ route('admin.orders.show', $stockOrder->id) }}"
                                            class="stock-order-link">
                                            Order #{{ $stockOrder->order_no }} <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('store.management.products.detail', $stockItem->id) }}"
                                        class="stock-view-order-btn">
                                        View product detail <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="stock-side-col">
                            <div class="affected-product-card">
                                <span class="stock-card-label">AFFECTED PRODUCT</span>
                                @php
                                    $stockItemImage = $stockItem->image_url
                                        ? (str_starts_with($stockItem->image_url, 'http')
                                            ? $stockItem->image_url
                                            : asset($stockItem->image_url))
                                        : asset('images/no-image.png');
                                @endphp
                                <a href="{{ route('store.management.products.detail', $stockItem->id) }}"
                                    class="affected-product-row">
                                    <img class="affected-product-img" src="{{ $stockItemImage }}"
                                        alt="{{ $stockItem->display_name }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                    <div class="affected-product-info">
                                        <div class="affected-product-name">{{ $stockItem->display_name }}</div>
                                        <div class="affected-product-meta">SKU:
                                            <strong>{{ $stockItem->number ?: 'N/A' }}</strong></div>
                                        @if ($stockItem->item_category_code)
                                            <div class="affected-product-meta">Category:
                                                <strong>{{ $stockItem->item_category_code }}</strong></div>
                                        @endif
                                    </div>
                                </a>
                            </div>

                            <div class="stock-summary-card">
                                <span class="stock-card-label">STOCK SUMMARY</span>
                                <div class="stock-stat-row">
                                    <div class="stock-stat-box current">
                                        <span class="stock-stat-label">Current Stock</span>
                                        <span class="stock-stat-value">{{ $stockCurrent }}</span>
                                    </div>
                                    <div class="stock-stat-box reserved">
                                        <span class="stock-stat-label">Reserved</span>
                                        <span class="stock-stat-value">{{ $stockReserved }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($isGlobalMessageNotification)
                    <div class="detail-card global-meta-card">
                        <div class="global-meta-header">
                            <span class="global-subject-pill">
                                <span class="global-subject-dot"></span>
                                Subject: {{ $cleanTitle !== '' ? $cleanTitle : 'N/A' }}
                            </span>
                        </div>

                        <div class="global-meta-row">
                            <div class="global-meta-item">
                                <span class="global-meta-label"><i class="bi bi-person-fill"></i> SENDER</span>
                                <div class="global-meta-value">{{ $notification->sender_name ?: 'System' }}</div>
                                @if (optional($sender)->email)
                                    <div class="global-meta-sub">{{ $sender->email }}</div>
                                @endif
                            </div>

                            <div class="global-meta-item">
                                <span class="global-meta-label"><i class="bi bi-envelope-fill"></i> RECIPIENT</span>
                                <div class="global-meta-value">{!! $sentToHtml !!}</div>
                            </div>

                            <div class="global-meta-item">
                                <span class="global-meta-label"><i class="bi bi-broadcast"></i> TYPE</span>
                                <div class="global-meta-value">Global Message</div>
                            </div>

                            <div class="global-meta-item">
                                <span class="global-meta-label"><i class="bi bi-clock-fill"></i> SENT TIME</span>
                                <div class="global-meta-value">{{ $messageDate }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card global-message-card">
                        <div class="top-user-box">
                            <div class="avatar-box large">
                                @if ($hasRealAvatar)
                                    <img src="{{ $avatarSrc }}" alt="{{ $displayName }}"
                                        onerror="this.onerror=null;this.parentElement.innerHTML='{{ $avatarInitial }}';this.parentElement.classList.add('letter-avatar');">
                                @else
                                    <span class="letter-avatar">{{ $avatarInitial }}</span>
                                @endif
                            </div>

                            <div class="user-info-box">
                                <h3>{{ $displayName }}</h3>
                                @if (optional($sender)->email)
                                    <p class="sent-to-line">{{ $sender->email }}</p>
                                @endif
                            </div>

                            <span class="global-message-timestamp">{{ $messageDate }}</span>
                        </div>

                        <div class="content-card global-message-body">
                            {{-- <h4 class="content-title">{{ $cleanTitle !== '' ? $cleanTitle : 'Message' }}</h4> --}}
                            <div class="content-divider"></div>
                            <div class="rendered-message">
                                @if (filled($cleanMessage))
                                    {!! $cleanMessage !!}
                                @else
                                    <p>No message content available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if ($isOrderNotification)
                    {{-- Phone-only receipt-style layout — the desktop info
                     card / alert banner / order-detail-grid above are
                     hidden below 768px (see .has-mobile-receipt in the
                     CSS) and this replaces them with a single scrolling
                     receipt matching the reference mockup. --}}
                    <div class="mobile-order-receipt">
                        <div class="receipt-row">
                            <span class="receipt-label">Order ID</span>
                            <span class="receipt-value">{{ $order->order_no }}</span>
                        </div>

                        <div class="receipt-sender">
                            <div class="avatar-box large">
                                @if ($hasRealAvatar)
                                    <img src="{{ $avatarSrc }}" alt="{{ $displayName }}"
                                        onerror="this.onerror=null;this.parentElement.innerHTML='{{ $avatarInitial }}';this.parentElement.classList.add('letter-avatar');">
                                @else
                                    <span class="letter-avatar">{{ $avatarInitial }}</span>
                                @endif
                            </div>
                            <div class="receipt-sender-name">{{ $displayName }}</div>
                        </div>

                        <div class="receipt-row">
                            <span class="receipt-label">Approved by</span>
                            <span class="receipt-value strong">{{ $displayName }}</span>
                        </div>
                        <div class="receipt-row">
                            <span class="receipt-label">Order date</span>
                            <span class="receipt-value muted">{{ $orderPlacedAt }}</span>
                        </div>

                        @if (filled($cleanMessage))
                            <div class="receipt-note-label">Note from admin</div>
                            <div class="receipt-note-card">
                                <i class="bi bi-sticky-fill"></i>
                                <div class="rendered-message">{!! $cleanMessage !!}</div>
                            </div>
                        @endif

                        <div class="receipt-summary-head">
                            <span>Order Summary</span>
                            <span class="receipt-item-count">{{ $orderItemsTotal }} Items</span>
                        </div>

                        <div class="receipt-items">
                            @foreach ($allOrderItems as $line)
                                @php
                                    $lineImage =
                                        optional($line->itemVariant)->image_url ??
                                        (optional($line->item)->custom_image_url ?? optional($line->item)->image_url);
                                    $lineImage = $lineImage
                                        ? (str_starts_with($lineImage, 'http')
                                            ? $lineImage
                                            : asset($lineImage))
                                        : asset('images/no-image.png');
                                    $lineVatPercent = !empty(optional($line->item)->price_includes_tax)
                                        ? 0
                                        : max(0, (float) (optional($line->item)->resolved_vat_percent ?? 0));
                                    $lineDiscountPercent = (float) ($line->discount_percent ?? 0);
                                @endphp
                                <div class="receipt-item">
                                    <img class="receipt-item-img" src="{{ $lineImage }}"
                                        alt="{{ $line->item_name }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                    <div class="receipt-item-info">
                                        <div class="receipt-item-name">
                                            {{ $line->item_name }}
                                            @if ($line->variant_description)
                                                <span class="receipt-item-size">&middot;
                                                    {{ $line->variant_description }}</span>
                                            @endif
                                        </div>
                                        <div class="receipt-item-meta-row">
                                            <span>VAT: {{ $lineVatPercent }}%</span>
                                            <span>Discount:
                                                {{ $lineDiscountPercent > 0 ? rtrim(rtrim(number_format($lineDiscountPercent, 2), '0'), '.') . '%' : '0' }}</span>
                                            <span>${{ number_format($line->unit_price ?? 0, 2) }}</span>
                                            <span>Qty: {{ (int) $line->qty }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="receipt-payment-head">Payment</div>
                        <div class="receipt-payment">
                            <div class="receipt-row">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
                            </div>
                            @if ($orderDiscount > 0)
                                <div class="receipt-row">
                                    <span>Discount</span>
                                    <span>-${{ number_format($orderDiscount, 2) }}</span>
                                </div>
                            @endif
                            <div class="receipt-row">
                                <span>Delivery Fee</span>
                                <span>${{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="receipt-row">
                                <span>Estimated Tax</span>
                                <span>${{ number_format($orderVat, 2) }}</span>
                            </div>

                            <div class="receipt-divider"></div>

                            <div class="receipt-row total">
                                <span>Total in USD</span>
                                <span>${{ number_format($orderTotalUsd, 2) }}</span>
                            </div>
                        </div>

                        <button type="button" class="receipt-trash-btn" id="mobileReceiptDeleteBtn">
                            <i class="bi bi-trash"></i> Trash
                        </button>
                    </div>
                @endif

                <div class="bottom-actions">
                    <a href="{{ route('admin.notifications.index') }}" class="back-btn secondary"
                        id="detailBackToList">Back to List</a>

                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST"
                        class="js-detail-delete-form" id="detailDeleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-btn" id="detailDeleteBtn">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade confirm-action-modal" id="confirmActionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content confirm-action-content">
                    <div class="modal-body confirm-action-body">
                        <h5 class="confirm-action-title">Are you sure?</h5>
                        <p class="confirm-action-message" id="confirmActionMessage"></p>
                    </div>
                    <div class="modal-footer confirm-action-footer">
                        <button type="button" class="confirm-action-delete-btn"
                            id="confirmActionConfirmBtn">Delete</button>
                        <button type="button" class="confirm-action-cancel-btn" data-bs-dismiss="modal">Cancel
                            Request</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const messageRoot = document.querySelector('.notification-detail-page .rendered-message');
            const viewer = document.getElementById('detailImageViewer');
            const viewerImg = document.getElementById('detailImageViewerImg');
            const viewerCount = document.getElementById('detailImageViewerCount');
            const closeBtn = document.getElementById('detailImageViewerClose');
            const prevBtn = document.getElementById('detailImagePrev');
            const nextBtn = document.getElementById('detailImageNext');

            if (!messageRoot || !viewer || !viewerImg) return;

            const images = Array.from(messageRoot.querySelectorAll('img'));
            if (!images.length) return;

            let activeIndex = 0;

            function renderViewer() {
                const current = images[activeIndex];
                if (!current) return;

                viewerImg.src = current.currentSrc || current.src;
                viewerImg.alt = current.alt || 'Full preview';
                if (viewerCount) {
                    viewerCount.textContent = `${activeIndex + 1} / ${images.length}`;
                }
                if (prevBtn) prevBtn.style.visibility = images.length > 1 ? 'visible' : 'hidden';
                if (nextBtn) nextBtn.style.visibility = images.length > 1 ? 'visible' : 'hidden';
            }

            function openViewer(index) {
                activeIndex = index;
                renderViewer();
                viewer.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeViewer() {
                viewer.classList.remove('show');
                document.body.style.overflow = '';
            }

            function stepViewer(direction) {
                if (images.length < 2) return;
                activeIndex = (activeIndex + direction + images.length) % images.length;
                renderViewer();
            }

            images.forEach(function(img, index) {
                img.addEventListener('click', function() {
                    openViewer(index);
                });
            });

            closeBtn?.addEventListener('click', closeViewer);
            prevBtn?.addEventListener('click', function() {
                stepViewer(-1);
            });
            nextBtn?.addEventListener('click', function() {
                stepViewer(1);
            });

            viewer.addEventListener('click', function(event) {
                if (event.target === viewer) closeViewer();
            });

            document.addEventListener('keydown', function(event) {
                if (!viewer.classList.contains('show')) return;
                if (event.key === 'Escape') closeViewer();
                if (event.key === 'ArrowLeft') stepViewer(-1);
                if (event.key === 'ArrowRight') stepViewer(1);
            });
        })();
    </script>
    <script>
        (function() {
            function showAlert(message, type) {
                type = type || 'success';
                var container = document.getElementById('alertContainer');
                if (!container || !message) return;
                var el = document.createElement('div');
                el.className = 'custom-alert alert-' + type;
                el.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle-fill' :
                    'exclamation-triangle-fill') + '"></i><span></span>';
                el.querySelector('span').textContent = message;
                container.appendChild(el);
                setTimeout(function() {
                    el.classList.add('fade-out');
                    setTimeout(function() {
                        el.remove();
                    }, 300);
                }, 4000);
            }

            var bsConfirmModal = null;
            var pendingConfirmCallback = null;
            var confirmModalEl = document.getElementById('confirmActionModal');

            if (confirmModalEl && window.bootstrap) {
                bsConfirmModal = new bootstrap.Modal(confirmModalEl);
            }

            document.getElementById('confirmActionConfirmBtn')?.addEventListener('click', function() {
                bsConfirmModal?.hide();
                var callback = pendingConfirmCallback;
                pendingConfirmCallback = null;
                callback?.();
            });

            function showConfirmModal(message, onConfirm) {
                var messageEl = document.getElementById('confirmActionMessage');
                if (messageEl) messageEl.textContent = message;
                pendingConfirmCallback = onConfirm;
                bsConfirmModal?.show();
            }

            var deleteForm = document.getElementById('detailDeleteForm');
            var deleteBtns = [
                document.getElementById('detailDeleteBtn'),
                document.getElementById('mobileReceiptDeleteBtn'),
            ].filter(Boolean);
            if (!deleteBtns.length || !deleteForm) return;

            function triggerDelete() {
                showConfirmModal('This action is permanent and cannot be undone. This notification will be deleted.',
                    function() {
                        deleteBtns.forEach(function(btn) {
                            btn.disabled = true;
                        });
                        var token = deleteForm.querySelector('input[name="_token"]')?.value || '';

                        fetch(deleteForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-HTTP-Method-Override': 'DELETE',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                            })
                            .then(function(res) {
                                return res.json().then(function(data) {
                                    return {
                                        ok: res.ok,
                                        data: data
                                    };
                                });
                            })
                            .then(function(result) {
                                if (!result.ok || result.data.success === false) {
                                    throw new Error(result.data.message || 'Failed to delete notification.');
                                }
                                showAlert(result.data.message || 'Notification deleted successfully.');
                                setTimeout(function() {
                                    window.location.href = @json(route('admin.notifications.index'));
                                }, 900);
                            })
                            .catch(function(err) {
                                deleteBtns.forEach(function(btn) {
                                    btn.disabled = false;
                                });
                                showAlert(err.message, 'error');
                            });
                    });
            }

            deleteBtns.forEach(function(btn) {
                btn.addEventListener('click', triggerDelete);
            });
        })();
    </script>

    <script>
        (function() {
            var cameFromSameOrigin = document.referrer && document.referrer.indexOf(window.location.origin) === 0;
            if (!cameFromSameOrigin || window.history.length <= 1) return;

            ['detailBackArrow', 'detailBackToList'].forEach(function(id) {
                var el = document.getElementById(id);
                if (!el) return;
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.history.back();
                });
            });
        })();
    </script>
@endpush
