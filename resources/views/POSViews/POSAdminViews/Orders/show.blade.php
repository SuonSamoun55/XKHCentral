@extends('Layout.POSAdmin.app')
@section('title', 'Review Order')
@section('backUrl', route('admin.orders.index', ['tab' => 'new']))
@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Orders/show.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSAdminViews/Orders/show.css')) }}">
@endpush


@section('content')
<div class="approval-page-clean">
    <div class="approval-page-inner">

        
        <div class="header-title">
            <button class="back-btn-top" onclick="window.history.back()" aria-label="Go back">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h1 class="approval-title">Approval Order Details</h1>
        </div>

        <div class="alert-container" id="alertContainer"></div>

        @php
            $subtotal = $order->subtotal ?? ($order->items->sum('line_total') ?? 0);
            $shipping = $order->shipping_amount ?? 25;
            $taxes = $order->tax_amount ?? 0;
            $discount = $order->discount_amount ?? 0;
            $totalUsd = $order->total_amount ?? (($subtotal + $shipping + $taxes) - $discount);
        @endphp

        <div class="info-cards-row">
            <div class="info-card">
                <div class="info-card-text">
                    <div class="info-card-label">Order ID</div>
                    <div class="info-card-value">{{ $order->order_no ?? '#98090' }}</div>
                </div>
                <div class="info-card-icon">
                    <img src="{{ asset('/images/AdminPOS/checkout.png') }}" alt="">
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-text">
                    <div class="info-card-label">Customer</div>
                    <div class="info-card-value">{{ $order->user->name ?? 'N/A' }}</div>
                </div>
                <div class="info-card-icon">
                    <img src="{{ asset('/images/AdminPOS/client.png') }}" alt="">
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-text">
                    <div class="info-card-label">Placed on</div>
                    <div class="info-card-value">{{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('d-m-y') }}</div>
                </div>
                <div class="info-card-icon">
                    <img src="{{ asset('/images/AdminPOS/calendar.png') }}" alt="">
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-text">
                    <div class="info-card-label">Total items</div>
                    <div class="info-card-value">{{ $order->items->count() }}</div>
                </div>
                <div class="info-card-icon">
                    <img src="{{ asset('/images/AdminPOS/delivery.png') }}" alt="">
                </div>
            </div>
        </div>

        <div class="total-orders-banner">
            <span class="total-orders-icon"><i class="bi bi-currency-dollar"></i></span>
            <span class="total-orders-label">Total orders</span>
            <span class="total-orders-value">${{ number_format($totalUsd, 2) }}</span>
        </div>

        <div class="items-table-wrap">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th class="col-center">Qty</th>
                        <th class="col-center">Price</th>
                        <th class="col-center">Discount</th>
                        <th class="col-center">VAT</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                        @php
                            $itemModel = $item->item ?? null;

                            $productImage = $itemModel && $itemModel->image_url
                                ? $itemModel->image_url
                                : 'https://via.placeholder.com/86x86?text=Item';

                            $productName = $item->item_name ?? 'Item Name';
                            $productDesc = $itemModel->description ?? null;
                            $variantLabel = optional($item->itemVariant)->description
                                ?? $item->variant_description
                                ?? null;

                            $discountPercent = (float) ($item->discount_percent ?? 0);
                            $discountAmount = (float) ($item->discount_amount ?? 0);
                            $vatAmount = (float) ($item->tax_amount ?? 0);
                        @endphp

                        <tr>
                            <td data-label="Product Name">
                                <div class="desc-cell">
                                    <img class="desc-image" src="{{ $productImage }}" alt="{{ $productName }}">
                                    <div class="desc-text-wrap">
                                        <div class="desc-text">{{ $productName }}</div>
                                        @if($variantLabel)
                                            <div class="desc-sub">Variant: {{ $variantLabel }}</div>
                                        @endif
                                        @if($productDesc)
                                            <div class="desc-sub">{{ $productDesc }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td data-label="Qty" class="col-center">{{ (int) ($item->qty ?? 1) }}</td>

                            <td data-label="Price" class="col-center">${{ number_format($item->unit_price ?? 0, 2) }}</td>

                            <td data-label="Discount" class="col-center">
                                @if($discountPercent > 0 || $discountAmount > 0)
                                    <span class="discount-badge">
                                        {{ $discountPercent > 0 ? rtrim(rtrim(number_format($discountPercent, 1), '0'), '.') . '%' : '$' . number_format($discountAmount, 2) }}
                                    </span>
                                @else
                                    &mdash;
                                @endif
                            </td>

                            <td data-label="VAT" class="col-center">
                                @if($vatAmount > 0)
                                    ${{ number_format($vatAmount, 2) }}
                                @else
                                    &mdash;
                                @endif
                            </td>

                            <td data-label="Subtotal"><strong>${{ number_format($item->line_total ?? 0, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-products">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mini-summary">
            <div class="mini-summary-row">
                <span>Subtotal</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="mini-summary-row">
                <span>Tax</span>
                <span>${{ number_format($taxes, 2) }}</span>
            </div>
            <div class="mini-summary-row mini-summary-total">
                <span>Total in USD</span>
                <span>${{ number_format($totalUsd, 2) }}</span>
            </div>
        </div>
        <div class="approval-footer">
            @if(($order->status ?? '') === 'pending')
                <button type="button"
                        class="reject-link-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal">
                    Reject Order
                </button>

                <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" class="complete-approval-form js-approve-order-form">
                    @csrf
                    <button type="button" class="complete-approval-btn js-approve-order-btn">
                        <i class="bi bi-check-circle-fill"></i> Complete Approval
                    </button>
                </form>
            @else
                <div class="status-view">
                    {{ ucfirst($order->status) }}
                </div>
            @endif
        </div>

        <!-- ===================== MOBILE ONLY ===================== -->
        <div class="mobile-order-detail">
            <div class="mobile-customer-card">
                <div class="mobile-customer-top">
                    <img
                        class="mobile-customer-avatar"
                        src="{{ $order->user->profile_image_display ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name ?? 'User') . '&background=17bfd0&color=fff' }}"
                        alt="{{ $order->user->name ?? 'User' }}"
                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=17bfd0&color=fff';"
                    >
                    <div>
                        <div class="mobile-customer-name">{{ $order->user->name ?? 'N/A' }}</div>
                        <div class="mobile-customer-order-id">Order ID #{{ $order->order_no }}</div>
                    </div>
                </div>

                @if(($order->user->phone ?? null) || ($order->user->location ?? null))
                    <div class="mobile-customer-contact">
                        @if($order->user->phone ?? null)
                            <a href="tel:{{ $order->user->phone }}" class="mobile-contact-pill">
                                <i class="bi bi-telephone-fill"></i> {{ $order->user->phone }}
                            </a>
                        @endif
                        @if($order->user->location ?? null)
                            <span class="mobile-contact-pill">
                                <i class="bi bi-geo-alt-fill"></i> {{ $order->user->location }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="mobile-section-card">
                <div class="mobile-section-header">
                    <span><i class="bi bi-file-earmark-text-fill"></i> Order Summary</span>
                    <span class="mobile-section-count">{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('item', $order->items->count()) }}</span>
                </div>

                <div class="mobile-order-items-list">
                    @forelse($order->items as $item)
                        @php
                            $mobileItemModel = $item->item ?? null;
                            $mobileProductImage = $mobileItemModel && $mobileItemModel->image_url
                                ? $mobileItemModel->image_url
                                : 'https://via.placeholder.com/86x86?text=Item';
                            $mobileProductName = $item->item_name ?? 'Item Name';
                            $mobileVariantLabel = optional($item->itemVariant)->description
                                ?? $item->variant_description
                                ?? null;
                        @endphp
                        <div class="mobile-order-item-row">
                            <img class="mobile-order-item-thumb" src="{{ $mobileProductImage }}" alt="{{ $mobileProductName }}">
                            <div class="mobile-order-item-info">
                                <div class="mobile-order-item-name">{{ $mobileProductName }}</div>
                                @if($mobileVariantLabel)
                                    <div class="mobile-order-item-variant">{{ $mobileVariantLabel }}</div>
                                @endif
                            </div>
                            <div class="mobile-order-item-price-wrap">
                                <div class="mobile-order-item-price">${{ number_format($item->unit_price ?? 0, 2) }}</div>
                                <div class="mobile-order-item-qty">&times;{{ (int) ($item->qty ?? 1) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-products">No items found.</div>
                    @endforelse
                </div>
            </div>

            <div class="mobile-section-card">
                <div class="mobile-cost-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="mobile-cost-row">
                    <span>Discount</span>
                    <span class="mobile-cost-positive">-${{ number_format($discount, 2) }}</span>
                </div>
                <div class="mobile-cost-row">
                    <span>Delivery Fee</span>
                    <span class="mobile-cost-positive">{{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free' }}</span>
                </div>
                <div class="mobile-cost-row">
                    <span>VAT</span>
                    <span class="mobile-cost-positive">{{ $taxes > 0 ? '$' . number_format($taxes, 2) : 'Free' }}</span>
                </div>

                <div class="mobile-cost-divider"></div>

                <div class="mobile-cost-row mobile-cost-total">
                    <span>Total in USD</span>
                    <span>${{ number_format($totalUsd, 2) }}</span>
                </div>
            </div>

            @if(($order->status ?? '') === 'pending')
                <div class="mobile-review-banner">
                    <i class="bi bi-info-circle-fill"></i>
                    Please review the order details before approval.
                </div>

                <div class="mobile-approval-actions">
                    <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" class="mobile-approve-order-form js-approve-order-form">
                        @csrf
                        <button type="button" class="mobile-approve-order-btn js-approve-order-btn">
                            <i class="bi bi-check-circle-fill"></i> Approve Order
                        </button>
                    </form>

                    <button type="button"
                            class="mobile-reject-order-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle"></i> Reject Order
                    </button>
                </div>
            @else
                <div class="mobile-status-view">{{ ucfirst($order->status) }}</div>
            @endif
        </div>
        <!-- =================== END MOBILE ONLY =================== -->

    </div>
</div>

@if(($order->status ?? '') === 'pending')
<div class="modal fade confirm-action-modal" id="confirmApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content confirm-action-content">
            <div class="modal-body confirm-action-body">
                <h5 class="confirm-action-title">Approve this order?</h5>
                <p class="confirm-action-message">Once approved, this order will be confirmed and stored in Business Central as a Sales Order. This action cannot be undone.</p>
            </div>
            <div class="modal-footer confirm-action-footer">
                <button type="button" class="confirm-action-delete-btn confirm-action-approve-btn" id="confirmApproveBtn">Approve</button>
                <button type="button" class="confirm-action-cancel-btn" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade remark-modal" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content remark-modal-content">
            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                @csrf

                <div class="modal-header remark-modal-header">
                    <h5 class="remark-modal-title">Remark</h5>
                    <button type="button" class="remark-modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-body remark-modal-body">
                    <textarea
                        name="note"
                        class="remark-textarea"
                        required>{{ old('note') }}</textarea>
                    <div class="remark-hint">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        Please provide a reason you reject the items
                    </div>
                </div>

                <div class="modal-footer remark-modal-footer">
                    <button type="button" class="remark-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="remark-save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const alertContainer = document.getElementById('alertContainer');

    function showAlert(message, type = 'success') {
        if (!alertContainer) return;
        const el = document.createElement('div');
        el.className = `custom-alert alert-${type}`;
        el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i><span>${message}</span>`;
        alertContainer.appendChild(el);
        setTimeout(() => {
            el.classList.add('fade-out');
            setTimeout(() => el.remove(), 300);
        }, 4000);
    }

    @if (session('success'))
        showAlert(@json(session('success')), 'success');
    @endif

    @if (session('error'))
        showAlert(@json(session('error')), 'danger');
    @endif

    @if ($errors->any())
        showAlert(@json($errors->first()), 'danger');
    @endif

    // Approve requires confirming in a modal first, instead of submitting
    // the form the instant the button is clicked.
    let bsApproveModal = null;
    let pendingApproveForm = null;
    const approveModalEl = document.getElementById('confirmApproveModal');
    if (approveModalEl) {
        bsApproveModal = new bootstrap.Modal(approveModalEl);
    }

    document.querySelectorAll('.js-approve-order-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            pendingApproveForm = btn.closest('.js-approve-order-form');
            bsApproveModal?.show();
        });
    });

    document.getElementById('confirmApproveBtn')?.addEventListener('click', function () {
        bsApproveModal?.hide();
        pendingApproveForm?.submit();
    });
});
</script>
@endpush
@endsection
