@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', $item->display_name ?? 'Product Detail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Products/show.css') }}">
@endpush

@section('content')
    @php
        // $variants is passed in from the controller (queried directly from item_variants)

        $baseImage = $item->custom_image_url ?? $item->image_url ?? asset('images/no-image.png');

        // One gallery entry per variant (falls back to the base product photo if that
        // variant has no image of its own), plus the base product photo itself first.
        $gallery = collect([
            ['image' => $baseImage, 'variant_id' => null, 'label' => $item->display_name ?? 'Product'],
        ])->concat(
            $variants->map(fn ($v) => [
                'image' => $v->image_url ?: $baseImage,
                'variant_id' => $v->id,
                'label' => $v->description ?? $v->code,
            ])
        );

        $mainImage = optional($variants->first())->image_url ?: $baseImage;

        // Use description2 as the group label ("Beef Type") if any variant has one
        $variantGroupLabel = $variants->pluck('description2')->filter()->first() ?? 'Options';

        $inStock = (int) ($item->inventory ?? 0) > 0;

        // $favoriteIds is passed in from the controller (same array used on the
        // item-list index page) — array of item IDs the current user has favorited.
        $favoriteIds = $favoriteIds ?? [];
        $isFavorited = in_array($item->id, $favoriteIds);
    @endphp

    <div id="pos-product-detail-scope">
        <div id="pdToast" class="pd-toast" aria-live="polite" aria-atomic="true"></div>

        <div class="detail-wrap">
            {{-- Top nav --}}
            <div class="top-nav">
                <a href="{{ url()->previous() }}" class="nav-btn" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="top-nav-actions">
                    <a href="{{ route('user.pos.cart') }}" class="cart-box" title="Cart">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-count {{ $cartCount > 0 ? '' : 'is-empty' }}" id="pdCartCount">{{ $cartCount }}</span>
                    </a>
                </div>
            </div>

            <div class="detail-grid">
                {{-- Left: main image + one thumbnail per variant --}}
                <div class="gallery-col">
                    <div class="image-container">
                        @if ($discountPercent > 0)
                            <div class="discount-badge">SAVE {{ round($discountPercent) }}%</div>
                        @endif

                        <button type="button"
                            class="wishlist-btn fav-btn"
                            id="pdFavBtn"
                            data-item-id="{{ $item->id }}"
                            title="{{ $isFavorited ? 'Remove from favorites' : 'Save to favorites' }}">
                            <i class="bi {{ $isFavorited ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                        </button>

                        <img id="pdMainImage"
                            src="{{ $mainImage }}"
                            alt="{{ $item->display_name ?? 'Product' }}"
                            onerror="this.onerror=null;this.classList.add('is-fallback');this.src='{{ asset('images/no-image.png') }}';">
                    </div>

                    @if ($gallery->count() > 1)
                        <div class="thumb-strip">
                            <button type="button" class="thumb-arrow thumb-arrow-left" onclick="pdScrollThumbs(-1)" title="Previous">
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <div class="thumb-row" id="pdThumbRow">
                                @foreach ($gallery as $index => $g)
                                    <button type="button"
                                        class="thumb-btn {{ $index === 0 ? 'active' : '' }}"
                                        data-image="{{ $g['image'] }}"
                                        data-variant-id="{{ $g['variant_id'] }}"
                                        title="{{ $g['label'] }}"
                                        onclick="pdSelectThumb(this)">
                                        <img src="{{ $g['image'] }}" alt="{{ $g['label'] }}">
                                    </button>
                                @endforeach
                            </div>

                            <button type="button" class="thumb-arrow thumb-arrow-right" onclick="pdScrollThumbs(1)" title="Next">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Right: info --}}
                <div class="info-col">
                    <h1 class="product-title">{{ $item->display_name ?? 'Unnamed Product' }}</h1>

                    @if ($discountPercent > 0)
                        <div class="price-old">${{ number_format($unitPrice, 2) }}</div>
                    @endif
                    <div class="price-new">${{ number_format($finalPrice, 2) }}</div>

                    @if (!empty($item->description))
                        <div class="product-desc">{{ $item->description }}</div>
                    @endif

                    @if ($variants->isNotEmpty())
                        <div class="variant-section">
                            <div class="variant-label">{{ $variantGroupLabel }}</div>
                            <div class="variant-options">
                                @foreach ($variants as $variant)
                                    <button type="button"
                                        class="variant-btn {{ $loop->first ? 'active' : '' }} {{ $variant->sales_blocked ? 'disabled' : '' }}"
                                        data-variant-id="{{ $variant->id }}"
                                        data-image="{{ $variant->image_url ?: $baseImage }}"
                                        {{ $variant->sales_blocked ? 'disabled' : '' }}
                                        onclick="pdSelectVariant(this)">
                                        {{ $variant->description ?? $variant->code }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="product-meta">
                        <span><strong>Item No:</strong> {{ $item->number }}</span>
                        @if (!empty($item->base_unit_of_measure_code))
                            <span><strong>Unit:</strong> {{ $item->base_unit_of_measure_code }}</span>
                        @endif
                        <span><strong>Availability:</strong> {{ $inStock ? 'In Stock' : 'Out of Stock' }}</span>
                    </div>

                    <div id="pdAddToCartForm">
                        <input type="hidden" id="pdItemId" value="{{ $item->id }}">
                        <input type="hidden" name="variant_id" id="pdSelectedVariantId"
                            value="{{ optional($variants->first())->id }}">

                        <div class="quantity-wrapper">
                            <div class="qty-box">
                                <button type="button" class="qty-btn" onclick="pdChangeQty(-1)">−</button>
                                <input type="number" name="qty" id="pdQtyInput" class="qty-input" value="1" min="1" readonly>
                                <button type="button" class="qty-btn" onclick="pdChangeQty(1)">+</button>
                            </div>
                        </div>

                        <button type="button" class="add-to-cart-btn" id="pdAddToCartBtn" onclick="pdAddToCart(this)">
                            <span class="add-to-cart-text">Add to cart</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pdShowToast(type, text) {
            const toastEl = document.getElementById('pdToast');
            if (!toastEl) return;
            toastEl.textContent = text;
            toastEl.className = `pd-toast show ${type}`;
            setTimeout(() => { toastEl.className = 'pd-toast'; }, 2500);
        }

        function pdAddToCart(btn) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const itemId    = document.getElementById('pdItemId').value;
            const variantId = document.getElementById('pdSelectedVariantId').value;
            const qty       = parseInt(document.getElementById('pdQtyInput').value || '1', 10) || 1;

            if (!itemId) {
                pdShowToast('error', 'Item ID not found.');
                return;
            }

            const textEl = btn.querySelector('.add-to-cart-text');
            const originalText = textEl ? textEl.textContent : btn.textContent;
            btn.disabled = true;
            if (textEl) { textEl.textContent = 'Adding...'; } else { btn.textContent = 'Adding...'; }

            fetch('{{ route("user.pos.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ item_id: itemId, variant_id: variantId || null, qty: qty })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const newCount = data.cartCount ?? data.count;
                        const badge = document.getElementById('pdCartCount');
                        if (badge && newCount !== undefined) {
                            badge.textContent = newCount;
                            badge.classList.toggle('is-empty', newCount <= 0);
                        }
                        pdShowToast('success', data.message || 'Added to cart successfully.');
                    } else {
                        pdShowToast('error', data.message || 'Failed to add to cart.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    pdShowToast('error', 'Something went wrong.');
                })
                .finally(() => {
                    btn.disabled = false;
                    if (textEl) { textEl.textContent = originalText || 'Add to cart'; }
                    else { btn.textContent = originalText || 'Add to cart'; }
                });
        }

        function pdBindFavoriteButton() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const btn = document.getElementById('pdFavBtn');
            if (!btn) return;

            btn.addEventListener('click', async function () {
                const itemId = this.dataset.itemId;
                const icon = this.querySelector('i');
                if (!itemId) return;

                try {
                    const response = await fetch('{{ route("user.pos.favorite.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ item_id: itemId })
                    });
                    const data = await response.json();
                    if (!icon) return;

                    if (data.favorited) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill', 'text-danger');
                        this.title = 'Remove from favorites';
                    } else {
                        icon.classList.remove('bi-heart-fill', 'text-danger');
                        icon.classList.add('bi-heart');
                        this.title = 'Save to favorites';
                    }
                } catch (error) {
                    console.error(error);
                    pdShowToast('error', 'Favorite update failed.');
                }
            });
        }

        function pdSyncSelection(variantId, image) {
            document.getElementById('pdMainImage').src = image;
            document.getElementById('pdSelectedVariantId').value = variantId || '';

            document.querySelectorAll('#pos-product-detail-scope .thumb-btn').forEach(t => {
                t.classList.toggle('active', t.dataset.variantId === String(variantId ?? ''));
            });

            document.querySelectorAll('#pos-product-detail-scope .variant-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.variantId === String(variantId ?? ''));
            });
        }

        function pdSelectThumb(btn) {
            pdSyncSelection(btn.dataset.variantId, btn.dataset.image);
        }

        function pdSelectVariant(btn) {
            if (btn.disabled) return;
            pdSyncSelection(btn.dataset.variantId, btn.dataset.image);
        }

        function pdChangeQty(delta) {
            const input = document.getElementById('pdQtyInput');
            const next = Math.max(1, parseInt(input.value || '1', 10) + delta);
            input.value = next;
        }

        function pdScrollThumbs(direction) {
            const row = document.getElementById('pdThumbRow');
            if (!row) return;
            row.scrollBy({ left: direction * 90, behavior: 'smooth' });
        }

        document.addEventListener('DOMContentLoaded', pdBindFavoriteButton);
    </script>
@endsection