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
                <a href="{{ url()->previous() }}" class="nav-btn" id="pdBackBtn" title="Back"
                    onclick="event.preventDefault(); (window.history.length > 1) ? window.history.back() : (window.location.href = this.href);">
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

                        {{-- data-favorited holds the server-rendered initial state as a
                             reliable source of truth ("1" / "0"), independent of whatever
                             key name the AJAX toggle endpoint happens to return. The
                             is-favorited class drives the red heart color via CSS. --}}
                        <button type="button"
                            class="wishlist-btn fav-btn {{ $isFavorited ? 'is-favorited' : '' }}"
                            id="pdFavBtn"
                            data-item-id="{{ $item->id }}"
                            data-favorited="{{ $isFavorited ? '1' : '0' }}"
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

            {{-- Related products — same category, current item excluded --}}
            @if (isset($relatedItems) && $relatedItems->isNotEmpty())
                <div class="related-section">
                    <h2 class="related-title">More in this category</h2>

                    <div class="related-grid">
                        @foreach ($relatedItems as $related)
                            @php
                                $relatedOldPrice = $related->effective_discount_percent > 0
                                    ? (float) $related->unit_price
                                    : 0;

                                // Same variant-group source/caveat as the main item-list page:
                                // 'group' decides which option-row a variant renders under
                                // (e.g. "Size" vs "Beef Type"). Adjust $v->variant_group to
                                // whatever column on ItemVariant actually stores that grouping.
                                $relatedVariants = collect($related->variants ?? [])->map(fn ($v) => [
                                    'id'      => $v->id,
                                    'group'   => $v->variant_group ?? 'Options', // <-- adjust field name
                                    'label'   => $v->description ?? $v->code,
                                    'image'   => $v->image_url ?: ($related->image_url ?: asset('images/no-image.png')),
                                    'blocked' => (bool) ($v->sales_blocked ?? false),
                                ])->values();
                            @endphp

                            {{--
                                NOTE: adjust the route name below to match whatever
                                your product-detail route is actually called
                                (e.g. user.pos.product.detail / user.pos.products.show).
                                It should resolve to ItemListController@detail.

                                Structure is flattened (image/title/price/button as
                                direct children of .related-card) so the phone media
                                query below can lay them out with CSS grid-template-areas,
                                the same pattern used for .pl-product-info on the main
                                product list. On desktop it just stacks normally.
                            --}}
                            <div class="related-card"
                                data-id="{{ $related->id }}"
                                data-price="{{ number_format($related->final_price, 2, '.', '') }}"
                                data-old-price="{{ $relatedOldPrice > $related->final_price ? number_format($relatedOldPrice, 2, '.', '') : '' }}"
                                data-image="{{ $related->image_url ?: asset('images/no-image.png') }}"
                                data-variants="{{ $relatedVariants->toJson() }}">

                                <a href="{{ route('user.pos.product.detail', $related->id) }}" class="related-card-image-link">
                                    <div class="related-card-image">
                                        @if ($related->effective_discount_percent > 0)
                                            <div class="related-badge">SAVE {{ round($related->effective_discount_percent) }}%</div>
                                        @endif
                                        <img src="{{ $related->image_url }}"
                                            alt="{{ $related->display_name }}"
                                            onerror="this.onerror=null;this.classList.add('is-fallback');this.src='{{ asset('images/no-image.png') }}';">
                                    </div>
                                </a>

                                <a href="{{ route('user.pos.product.detail', $related->id) }}" class="related-card-title">
                                    {{ $related->display_name }}
                                </a>

                                <div class="related-card-price">
                                    @if ($related->effective_discount_percent > 0)
                                        <span class="related-price-old">${{ number_format($related->unit_price, 2) }}</span>
                                    @endif
                                    <span class="related-price-new">${{ number_format($related->final_price, 2) }}</span>
                                </div>

                                {{-- phone-only quick add button (squircle "+"), hidden on desktop.
                                     If the related item has variants, this opens the popup;
                                     otherwise it adds straight to cart. --}}
                                <button type="button" class="related-add-btn" data-id="{{ $related->id }}" title="Add to cart">
                                    <span class="related-add-text">Add to cart</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ===== VARIANT SELECTION POPUP (related-products quick-add) ===== --}}
        <div class="pd-variant-modal-overlay" id="pdVariantModalOverlay">
            <div class="pd-variant-modal" role="dialog" aria-modal="true" aria-labelledby="pdVariantModalTitle">
                <button type="button" class="pd-variant-modal-close" id="pdVariantModalClose" title="Close">
                    <i class="bi bi-x-lg"></i>
                </button>

                <div class="pd-variant-modal-body">
                    <div class="pd-variant-modal-image-col">
                        <img id="pdVariantModalImage" src="" alt="">
                    </div>

                    <div class="pd-variant-modal-info">
                        <h3 id="pdVariantModalTitle"></h3>

                        <div class="pd-variant-modal-price-row">
                            <span class="pd-variant-modal-old-price" id="pdVariantModalOldPrice"></span>
                            <div class="pd-variant-modal-price" id="pdVariantModalPrice"></div>
                        </div>

                        {{-- option groups (Size, Beef Type, etc.) injected here dynamically,
                             one label + one button-row per group. --}}
                        <div id="pdVariantModalOptions"></div>

                        <div class="pd-variant-modal-qty">
                            <div class="qty-box">
                                <button type="button" class="qty-btn" id="pdVariantModalQtyMinus">−</button>
                                <span class="pd-qty" id="pdVariantModalQty">1</span>
                                <button type="button" class="qty-btn" id="pdVariantModalQtyPlus">+</button>
                            </div>
                        </div>

                        <button type="button" class="add-to-cart-btn pd-variant-modal-confirm" id="pdVariantModalConfirm">
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

        /**
         * Reads the "favorited" state out of the toggle endpoint's JSON
         * response, regardless of which key name the backend uses.
         * Returns true/false, or null if no recognizable flag was present
         * (in which case the caller falls back to flipping the current
         * button state, so the UI still updates instead of silently
         * doing nothing).
         */
        function pdExtractFavoritedFlag(data) {
            const candidates = [
                'favorited', 'is_favorited', 'isFavorited',
                'favorite', 'is_favorite', 'isFavorite',
                'status', 'value', 'liked'
            ];

            for (const key of candidates) {
                if (Object.prototype.hasOwnProperty.call(data, key)) {
                    const v = data[key];
                    if (typeof v === 'boolean') return v;
                    if (typeof v === 'number') return v === 1;
                    if (typeof v === 'string') return v === '1' || v.toLowerCase() === 'true' || v.toLowerCase() === 'added';
                }
            }
            return null;
        }

        function pdApplyFavoriteState(btn, icon, isFavorited) {
            btn.classList.toggle('is-favorited', isFavorited);
            btn.dataset.favorited = isFavorited ? '1' : '0';

            if (isFavorited) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill', 'text-danger');
                btn.title = 'Remove from favorites';
            } else {
                icon.classList.remove('bi-heart-fill', 'text-danger');
                icon.classList.add('bi-heart');
                btn.title = 'Save to favorites';
            }
        }

        function pdBindFavoriteButton() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const btn = document.getElementById('pdFavBtn');
            if (!btn) return;

            btn.addEventListener('click', async function () {
                const itemId = this.dataset.itemId;
                const icon = this.querySelector('i');
                if (!itemId || !icon) return;

                // Prevent double-clicks while the request is in flight.
                if (this.disabled) return;
                this.disabled = true;

                // Current state before the request, used as a fallback if the
                // server response doesn't contain a clear favorited flag.
                const wasFavorited = this.dataset.favorited === '1';

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

                    if (!response.ok) {
                        throw new Error(`Request failed with status ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.success === false) {
                        pdShowToast('error', data.message || 'Favorite update failed.');
                        return;
                    }

                    const flag = pdExtractFavoritedFlag(data);
                    // If the backend didn't return a recognizable flag,
                    // assume the toggle succeeded and flip the previous state.
                    const isFavorited = flag === null ? !wasFavorited : flag;

                    pdApplyFavoriteState(this, icon, isFavorited);
                } catch (error) {
                    console.error(error);
                    pdShowToast('error', 'Favorite update failed.');
                } finally {
                    this.disabled = false;
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

        /* ─────────────────────────────────────────────────────────────
           Related-products quick-add ("+" button under each of the
           related cards) — same "with variants -> popup, without ->
           add directly" behavior as the main product list page.
           ───────────────────────────────────────────────────────────── */

        function getRelatedCardData(card) {
            return {
                id:          card.dataset.id || "",
                displayName: card.querySelector(".related-card-title")?.textContent?.trim() || "No Name",
                price:       card.dataset.price || "0.00",
                oldPrice:    card.dataset.oldPrice || "",
                image:       card.dataset.image || card.querySelector("img")?.src || "",
                variants:    (() => {
                    try { return JSON.parse(card.dataset.variants || "[]"); }
                    catch (e) { return []; }
                })()
            };
        }

        function pdGroupVariants(variants) {
            const groups = {};
            variants.forEach(v => {
                const groupName = v.group || "Options";
                if (!groups[groupName]) groups[groupName] = [];
                groups[groupName].push(v);
            });
            return groups;
        }

        const pdVariantEls = {
            overlay:  document.getElementById("pdVariantModalOverlay"),
            close:    document.getElementById("pdVariantModalClose"),
            image:    document.getElementById("pdVariantModalImage"),
            title:    document.getElementById("pdVariantModalTitle"),
            oldPrice: document.getElementById("pdVariantModalOldPrice"),
            price:    document.getElementById("pdVariantModalPrice"),
            options:  document.getElementById("pdVariantModalOptions"),
            qty:      document.getElementById("pdVariantModalQty"),
            qtyMinus: document.getElementById("pdVariantModalQtyMinus"),
            qtyPlus:  document.getElementById("pdVariantModalQtyPlus"),
            confirm:  document.getElementById("pdVariantModalConfirm"),
        };

        let pdActiveVariantCard = null;
        let pdActiveVariantSelections = {}; // { "Size": variantId, "Beef Type": variantId, ... }
        let pdActiveVariantQty = 1;

        function pdRenderVariantModal(card) {
            const data = getRelatedCardData(card);
            pdActiveVariantQty = 1;
            pdActiveVariantSelections = {};

            pdVariantEls.image.src = data.image;
            pdVariantEls.image.alt = data.displayName;
            pdVariantEls.title.textContent = data.displayName;
            pdVariantEls.price.textContent = `$${data.price}`;
            pdVariantEls.qty.textContent = "1";

            if (data.oldPrice && parseFloat(data.oldPrice) > parseFloat(data.price)) {
                pdVariantEls.oldPrice.textContent = `$${data.oldPrice}`;
                pdVariantEls.oldPrice.style.display = "";
            } else {
                pdVariantEls.oldPrice.style.display = "none";
            }

            pdVariantEls.options.innerHTML = "";

            const groups = pdGroupVariants(data.variants);
            let firstGroupImage = null;

            Object.keys(groups).forEach(groupName => {
                const groupList = groups[groupName];
                const firstAvailable = groupList.find(v => !v.blocked) || groupList[0];
                pdActiveVariantSelections[groupName] = firstAvailable.id;
                if (!firstGroupImage && firstAvailable.image) firstGroupImage = firstAvailable.image;

                const label = document.createElement("div");
                label.className = "variant-label";
                label.textContent = groupName;

                const optionsRow = document.createElement("div");
                optionsRow.className = "variant-options";

                groupList.forEach(v => {
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.className = "variant-btn" +
                        (v.id === pdActiveVariantSelections[groupName] ? " active" : "") +
                        (v.blocked ? " disabled" : "");
                    btn.textContent = v.label;
                    btn.dataset.variantId = v.id;
                    if (v.image) btn.dataset.image = v.image;
                    if (v.blocked) btn.disabled = true;

                    btn.addEventListener("click", () => {
                        if (btn.disabled) return;
                        pdActiveVariantSelections[groupName] = v.id;
                        if (v.image) pdVariantEls.image.src = v.image;
                        optionsRow.querySelectorAll(".variant-btn").forEach(b => b.classList.remove("active"));
                        btn.classList.add("active");
                    });

                    optionsRow.appendChild(btn);
                });

                pdVariantEls.options.appendChild(label);
                pdVariantEls.options.appendChild(optionsRow);
            });

            if (firstGroupImage) pdVariantEls.image.src = firstGroupImage;
        }

        function pdOpenVariantModal(card) {
            pdActiveVariantCard = card;
            pdRenderVariantModal(card);
            pdVariantEls.overlay.classList.add("show");
        }

        function pdCloseVariantModal() {
            pdVariantEls.overlay.classList.remove("show");
            pdActiveVariantCard = null;
            pdActiveVariantSelections = {};
        }

        function pdBindVariantModal() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            pdVariantEls.close?.addEventListener("click", pdCloseVariantModal);
            pdVariantEls.overlay?.addEventListener("click", (e) => {
                if (e.target === pdVariantEls.overlay) pdCloseVariantModal();
            });
            document.addEventListener("keydown", (e) => {
                if (pdVariantEls.overlay?.classList.contains("show") && e.key === "Escape") pdCloseVariantModal();
            });

            pdVariantEls.qtyMinus?.addEventListener("click", () => {
                pdActiveVariantQty = Math.max(1, pdActiveVariantQty - 1);
                pdVariantEls.qty.textContent = pdActiveVariantQty;
            });
            pdVariantEls.qtyPlus?.addEventListener("click", () => {
                pdActiveVariantQty += 1;
                pdVariantEls.qty.textContent = pdActiveVariantQty;
            });

            pdVariantEls.confirm?.addEventListener("click", async function () {
                if (!pdActiveVariantCard) return;
                const data = getRelatedCardData(pdActiveVariantCard);
                if (!data.id) { pdShowToast("error", "Item ID not found."); return; }

                const selectedIds = Object.values(pdActiveVariantSelections);
                // Backward-compatible single id when there's only one option group.
                const singleVariantId = selectedIds.length === 1 ? selectedIds[0] : null;

                this.disabled = true;
                const textEl = this.querySelector(".add-to-cart-text");
                if (textEl) textEl.textContent = "Adding...";

                try {
                    const response = await fetch('{{ route("user.pos.cart.add") }}', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            item_id: data.id,
                            variant_id: singleVariantId,
                            variant_ids: selectedIds.length > 1 ? selectedIds : null,
                            qty: pdActiveVariantQty
                        })
                    });
                    const result = await response.json();

                    if (result.success) {
                        const newCount = result.cartCount ?? result.count;
                        const badge = document.getElementById('pdCartCount');
                        if (badge && newCount !== undefined) {
                            badge.textContent = newCount;
                            badge.classList.toggle('is-empty', newCount <= 0);
                        }
                        pdShowToast('success', result.message || 'Added to cart successfully.');
                        pdCloseVariantModal();
                    } else {
                        pdShowToast('error', result.message || 'Failed to add to cart.');
                    }
                } catch (error) {
                    console.error(error);
                    pdShowToast('error', 'Something went wrong.');
                } finally {
                    this.disabled = false;
                    if (textEl) textEl.textContent = 'Add to cart';
                }
            });
        }

        function pdBindRelatedAddButtons() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            document.querySelectorAll('#pos-product-detail-scope .related-add-btn').forEach(btn => {
                btn.addEventListener('click', async function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const card = this.closest('.related-card');
                    const data = getRelatedCardData(card);
                    if (!data.id) { pdShowToast('error', 'Item ID not found.'); return; }

                    // Products WITH variants -> open the popup instead of adding directly.
                    if (data.variants && data.variants.length > 0) {
                        pdOpenVariantModal(card);
                        return;
                    }

                    if (this.disabled) return;
                    this.disabled = true;

                    try {
                        const response = await fetch('{{ route("user.pos.cart.add") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ item_id: data.id, variant_id: null, qty: 1 })
                        });
                        const result = await response.json();

                        if (result.success) {
                            const newCount = result.cartCount ?? result.count;
                            const badge = document.getElementById('pdCartCount');
                            if (badge && newCount !== undefined) {
                                badge.textContent = newCount;
                                badge.classList.toggle('is-empty', newCount <= 0);
                            }
                            pdShowToast('success', result.message || 'Added to cart successfully.');
                        } else {
                            pdShowToast('error', result.message || 'Failed to add to cart.');
                        }
                    } catch (error) {
                        console.error(error);
                        pdShowToast('error', 'Something went wrong.');
                    } finally {
                        this.disabled = false;
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            pdBindFavoriteButton();
            pdBindRelatedAddButtons();
            pdBindVariantModal();
        });
    </script>
@endsection