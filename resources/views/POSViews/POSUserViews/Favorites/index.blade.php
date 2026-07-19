@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS Favorites')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Favorites/index.css') }}">
@endpush

@section('content')

    <div class="page-wrap">
        <main class="content-area">
            @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            @include('ManagementSystemViews.UserViews.Layouts.footer')

            <div class="header">
                <div class="topbar">
                    <div class="top">
                        <h1 class="title">Favorites</h1>
                        <a href="{{ route('user.pos.cart') }}" class="cart-box">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-count" id="desktopCartCount">{{ (int) ($cartCount ?? 0) }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <div id="messageBox" class="message-box"></div>
            <div id="toast" class="toast" aria-live="polite" aria-atomic="true" role="status"></div>

            @if ($favorites->isEmpty())
                {{-- ===== EMPTY STATE (unchanged, favorite-specific) ===== --}}
                <div class="wishlist-page">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search your wishlist ..." />
                    </div>

                    <div class="product-count">0 products</div>

                    <div class="empty-state">
                        <div class="image-placeholder">
                            <img src="{{ asset('images/pos/no wishlist 1.png') }}" alt="Empty Wishlist">
                        </div>
                        <h2>Your wishlist is empty</h2>
                        <p>Looks like you haven't added anything<br>to your wishlist yet</p>
                        <a href="{{ route('user.posinterface') }}" class="primary-btn">
                            Explore now
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>

                    <div class="bottom-nav">
                        <div class="nav-item"><i class="bi bi-house"></i><span>home</span></div>
                        <div class="nav-item"><i class="bi bi-box"></i><span>products</span></div>
                        <div class="nav-item active"><i class="bi bi-heart-fill"></i><span>favorite</span></div>
                        <div class="nav-item"><i class="bi bi-person"></i><span>user</span></div>
                    </div>
                </div>
                <div class="empty-box">No favorite items found.</div>
            @else
                {{-- ===== PRODUCT GRID — mirrors item-list card markup ===== --}}
                <div class="products-grid" id="productsGrid">
                    @foreach ($favorites as $item)
                        @php
                            $normalPrice     = (float) ($item->unit_price ?? 0);
                            $discountPercent = (float) ($item->effective_discount_percent ?? 0);
                            $salePrice       = (float) ($item->final_price ?? $normalPrice);
                            $oldPrice        = $discountPercent > 0 ? $normalPrice : 0;

                            // Same variant-embedding pattern as item-list. Requires the
                            // FavoritesController to attach a `variants` relation to each
                            // $item the same way ItemListController::getItems() does
                            // (batch-load ItemVariant::whereIn('item_id', ...) and
                            // ->setRelation('variants', ...)). See note below.
                            $itemVariants = collect($item->variants ?? [])->map(fn ($v) => [
                                'id'      => $v->id,
                                'group'   => $v->variant_group ?? 'Options',
                                'label'   => $v->description ?? $v->code,
                                'image'   => $v->image_url ?: ($item->image_url ?: asset('images/no-image.png')),
                                'blocked' => (bool) ($v->sales_blocked ?? false),
                            ])->values();
                        @endphp

                        <div class="product-card product-item"
                            data-id="{{ $item->id }}"
                            data-detail-url="{{ route('user.pos.product.detail', $item->id) }}"
                            data-display-name="{{ $item->display_name ?? '' }}"
                            data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}"
                            data-price="{{ number_format($salePrice, 2, '.', '') }}"
                            data-old-price="{{ $oldPrice > $salePrice ? number_format($oldPrice, 2, '.', '') : '' }}"
                            data-image="{{ $item->image_url ?: asset('images/no-image.png') }}"
                            data-variants="{{ $itemVariants->toJson() }}">

                            <div class="product-img-box">
                                @if ($discountPercent > 0)
                                    <div class="discount-badge">
                                        SAVE {{ rtrim(rtrim(number_format($discountPercent, 2), '0'), '.') }}%
                                    </div>
                                @endif

                                <button type="button" class="fav-btn" data-item-id="{{ $item->id }}">
                                    <i class="bi bi-heart-fill text-danger"></i>
                                </button>

                                <img src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                    alt="{{ $item->display_name ?? 'No Name' }}" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                            </div>

                            <div class="product-info">
                                <div class="product-title">{{ $item->display_name ?: 'No Name' }}</div>

                                <div class="price-row {{ $oldPrice > $salePrice ? 'has-discount' : 'no-discount' }}">
                                    <div class="old-price">
                                        @if ($oldPrice > $salePrice)
                                            ${{ number_format($oldPrice, 2) }}
                                        @endif
                                    </div>
                                    <div class="new-price">${{ number_format($salePrice, 2) }}</div>
                                </div>

                                <div class="qty-section">
                                    <span class="qty-label">Quantity:</span>
                                    <div class="qty-box">
                                        <button type="button" class="qty-btn minus">−</button>
                                        <span class="qty">1</span>
                                        <button type="button" class="qty-btn plus">+</button>
                                    </div>
                                </div>

                                <button type="button" class="add-cart-btn mobile-action" data-id="{{ $item->id }}">
                                    <span class="add-cart-text">Add to cart</span>
                                </button>

                                <a href="{{ route('user.pos.product.detail', $item->id) }}" class="view-detail-btn">
                                    View detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    {{-- ===== VARIANT SELECTION POPUP (copied from item-list) ===== --}}
    <div class="variant-modal-overlay" id="variantModalOverlay">
        <div class="variant-modal" role="dialog" aria-modal="true" aria-labelledby="variantModalTitle">
            <button type="button" class="variant-modal-close" id="variantModalClose" title="Close">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="variant-modal-body">
                <div class="variant-modal-image-col">
                    <img id="variantModalImage" src="" alt="">
                </div>

                <div class="variant-modal-info">
                    <h3 id="variantModalTitle"></h3>

                    <div class="variant-modal-price-row">
                        <span class="variant-modal-old-price" id="variantModalOldPrice"></span>
                        <div class="variant-modal-price" id="variantModalPrice"></div>
                    </div>

                    <div id="variantModalOptions"></div>

                    <div class="variant-modal-qty">
                        <div class="qty-box">
                            <button type="button" class="qty-btn" id="variantModalQtyMinus">−</button>
                            <span class="qty" id="variantModalQty">1</span>
                            <button type="button" class="qty-btn" id="variantModalQtyPlus">+</button>
                        </div>
                    </div>

                    <button type="button" class="add-cart-btn variant-modal-confirm" id="variantModalConfirm">
                        <span class="add-cart-text">Add to cart</span>
                    </button>

                    <a href="#" class="view-detail-btn variant-modal-view-detail" id="variantModalViewDetail">
                        View detail
                    </a>
                </div>
            </div>
        </div>

        <div class="variant-modal-nav" id="variantModalNav">
            <button type="button" class="variant-modal-nav-btn" id="variantModalPrev" title="Previous product">
                <i class="bi bi-chevron-left"></i>
            </button>
            <span class="variant-modal-nav-divider"></span>
            <button type="button" class="variant-modal-nav-btn" id="variantModalNext" title="Next product">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const els = {
        cartCount:        document.getElementById("desktopCartCount"),
        asideCartCount:    document.getElementById("asideCartCount"),
        messageBox:        document.getElementById("messageBox"),
        toast:              document.getElementById("toast"),
        productsGrid:       document.getElementById("productsGrid"),
        productCards:       [...document.querySelectorAll(".product-card")],
        favButtons:         [...document.querySelectorAll(".fav-btn")],

        variantModalOverlay:    document.getElementById("variantModalOverlay"),
        variantModalClose:      document.getElementById("variantModalClose"),
        variantModalImage:      document.getElementById("variantModalImage"),
        variantModalTitle:      document.getElementById("variantModalTitle"),
        variantModalOldPrice:   document.getElementById("variantModalOldPrice"),
        variantModalPrice:      document.getElementById("variantModalPrice"),
        variantModalOptions:    document.getElementById("variantModalOptions"),
        variantModalQty:        document.getElementById("variantModalQty"),
        variantModalQtyMinus:   document.getElementById("variantModalQtyMinus"),
        variantModalQtyPlus:    document.getElementById("variantModalQtyPlus"),
        variantModalConfirm:    document.getElementById("variantModalConfirm"),
        variantModalViewDetail: document.getElementById("variantModalViewDetail"),
        variantModalNav:        document.getElementById("variantModalNav"),
        variantModalPrev:       document.getElementById("variantModalPrev"),
        variantModalNext:       document.getElementById("variantModalNext"),
    };

    function showToast(type, text) {
        if (!els.toast) return;
        els.toast.textContent = text;
        els.toast.className = `toast show ${type}`;
        clearTimeout(els.toast._hideTimeout);
        els.toast._hideTimeout = setTimeout(() => { els.toast.className = 'toast'; }, 2800);
    }

    function getCardData(card) {
        return {
            id:          card.dataset.id || "",
            displayName: card.dataset.displayName ||
                         card.querySelector(".product-title")?.textContent?.trim() || "No Name",
            price:       card.dataset.price || "0.00",
            image:       card.dataset.image || card.querySelector("img")?.src || "",
            detailUrl:   card.dataset.detailUrl || "#",
            variants:    (() => {
                try { return JSON.parse(card.dataset.variants || "[]"); }
                catch (e) { return []; }
            })()
        };
    }

    function ensureEmptyState() {
        if (!els.productsGrid) return;
        if (els.productsGrid.querySelectorAll(".product-card").length > 0) return;
        els.productsGrid.remove();
        if (!document.querySelector(".empty-box")) {
            const empty = document.createElement("div");
            empty.className = "empty-box";
            empty.textContent = "No favorite items found.";
            els.messageBox?.insertAdjacentElement("afterend", empty);
        }
    }

    /* ── shared add-to-cart call ── */
    async function sendAddToCart({ itemId, variantId, variantIds, qty }) {
        const response = await fetch("{{ route('user.pos.cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({
                item_id: itemId,
                variant_id: variantId || null,
                variant_ids: variantIds || null,
                qty: qty
            })
        });
        const data = await response.json();

        if (data.success && data.cartCount !== undefined) {
            if (els.cartCount) els.cartCount.textContent = data.cartCount;
            if (els.asideCartCount) {
                els.asideCartCount.textContent = data.cartCount;
                els.asideCartCount.classList.toggle("is-empty", data.cartCount <= 0);
            }
        }
        return data;
    }

    function bindQuantityButtons() {
        els.productCards.forEach(card => {
            const plusBtn  = card.querySelector(".plus");
            const minusBtn = card.querySelector(".minus");
            const qtyEl    = card.querySelector(".qty");
            plusBtn?.addEventListener("click", () => {
                if (qtyEl) qtyEl.textContent = parseInt(qtyEl.textContent || "0", 10) + 1;
            });
            minusBtn?.addEventListener("click", () => {
                const cur = parseInt(qtyEl?.textContent || "0", 10);
                if (cur > 1 && qtyEl) qtyEl.textContent = cur - 1;
            });
        });
    }

    /* products WITH variants -> popup; WITHOUT variants -> add straight to cart */
    function bindAddToCart() {
        els.productCards.forEach(card => {
            const addBtn = card.querySelector(".add-cart-btn");
            const qtyEl  = card.querySelector(".qty");
            addBtn?.addEventListener("click", async function () {
                const data = getCardData(card);
                if (!data.id) { showToast("error", "Item ID not found."); return; }

                if (data.variants && data.variants.length > 0) {
                    openVariantModal(card);
                    return;
                }

                const qty = parseInt(qtyEl?.textContent || "1", 10);
                this.disabled = true;
                const textEl = this.querySelector(".add-cart-text");
                if (textEl) textEl.textContent = "Adding...";
                try {
                    const result = await sendAddToCart({ itemId: data.id, variantId: null, variantIds: null, qty });
                    if (result.success) {
                        if (qtyEl) qtyEl.textContent = "1";
                        showToast("success", result.message || "Added to cart successfully.");
                    } else {
                        showToast("error", result.message || "Failed to add to cart.");
                    }
                } catch (error) {
                    console.error(error);
                    showToast("error", "Something went wrong.");
                } finally {
                    this.disabled = false;
                    if (textEl) textEl.textContent = "Add to cart";
                }
            });
        });
    }

    /* ── variant selection popup (copied from item-list) ── */
    let activeVariantCard = null;
    let activeVariantSelections = {};
    let activeVariantQty = 1;

    function visibleCards() {
        return els.productCards.filter(card => card.style.display !== "none" && document.body.contains(card));
    }

    function groupVariants(variants) {
        const groups = {};
        variants.forEach(v => {
            const groupName = v.group || "Options";
            if (!groups[groupName]) groups[groupName] = [];
            groups[groupName].push(v);
        });
        return groups;
    }

    function renderVariantModal(card) {
        const data = getCardData(card);
        activeVariantQty = 1;
        activeVariantSelections = {};

        const hasVariants = data.variants && data.variants.length > 0;

        els.variantModalImage.src = data.image;
        els.variantModalImage.alt = data.displayName;
        els.variantModalTitle.textContent = data.displayName;
        els.variantModalPrice.textContent = `$${data.price}`;
        els.variantModalQty.textContent = "1";
        els.variantModalViewDetail.href = data.detailUrl;

        const oldPriceAttr = card.dataset.oldPrice;
        if (oldPriceAttr && parseFloat(oldPriceAttr) > parseFloat(data.price)) {
            els.variantModalOldPrice.textContent = `$${oldPriceAttr}`;
            els.variantModalOldPrice.style.display = "";
        } else {
            els.variantModalOldPrice.style.display = "none";
        }

        els.variantModalOptions.innerHTML = "";
        els.variantModalOptions.style.display = "block";

        if (hasVariants) {
            const groups = groupVariants(data.variants);
            let firstGroupImage = null;

            Object.keys(groups).forEach(groupName => {
                const groupList = groups[groupName];
                const firstAvailable = groupList.find(v => !v.blocked) || groupList[0];
                activeVariantSelections[groupName] = firstAvailable.id;
                if (!firstGroupImage && firstAvailable.image) firstGroupImage = firstAvailable.image;

                const label = document.createElement("div");
                label.className = "variant-modal-label";
                label.textContent = groupName;

                const optionsRow = document.createElement("div");
                optionsRow.className = "variant-modal-options";

                groupList.forEach(v => {
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.className = "variant-btn" +
                        (v.id === activeVariantSelections[groupName] ? " active" : "") +
                        (v.blocked ? " disabled" : "");
                    btn.textContent = v.label;
                    btn.dataset.variantId = v.id;
                    btn.dataset.group = groupName;
                    if (v.image) btn.dataset.image = v.image;
                    if (v.blocked) btn.disabled = true;

                    btn.addEventListener("click", () => {
                        if (btn.disabled) return;
                        activeVariantSelections[groupName] = v.id;
                        if (v.image) els.variantModalImage.src = v.image;
                        optionsRow.querySelectorAll(".variant-btn").forEach(b => b.classList.remove("active"));
                        btn.classList.add("active");
                    });

                    optionsRow.appendChild(btn);
                });

                els.variantModalOptions.appendChild(label);
                els.variantModalOptions.appendChild(optionsRow);
            });

            if (firstGroupImage) els.variantModalImage.src = firstGroupImage;
        }

        updateNavState();
    }

    function updateNavState() {
        const cards = visibleCards();
        const index = cards.indexOf(activeVariantCard);
        const multiple = cards.length > 1;
        els.variantModalNav.style.display = multiple ? "flex" : "none";
        if (!multiple) return;
        els.variantModalPrev.disabled = index <= 0;
        els.variantModalNext.disabled = index === -1 || index >= cards.length - 1;
    }

    function openVariantModal(card) {
        activeVariantCard = card;
        renderVariantModal(card);
        els.variantModalOverlay.classList.add("show");
    }

    function closeVariantModal() {
        els.variantModalOverlay.classList.remove("show");
        activeVariantCard = null;
        activeVariantSelections = {};
    }

    function goToAdjacentProduct(direction) {
        const cards = visibleCards();
        const index = cards.indexOf(activeVariantCard);
        if (index === -1) return;
        const nextIndex = index + direction;
        if (nextIndex < 0 || nextIndex >= cards.length) return;
        activeVariantCard = cards[nextIndex];
        renderVariantModal(activeVariantCard);
    }

    function bindVariantModal() {
        els.variantModalClose?.addEventListener("click", closeVariantModal);
        els.variantModalOverlay?.addEventListener("click", (e) => {
            if (e.target === els.variantModalOverlay) closeVariantModal();
        });
        document.addEventListener("keydown", (e) => {
            if (!els.variantModalOverlay?.classList.contains("show")) return;
            if (e.key === "Escape") closeVariantModal();
            if (e.key === "ArrowLeft") goToAdjacentProduct(-1);
            if (e.key === "ArrowRight") goToAdjacentProduct(1);
        });

        els.variantModalPrev?.addEventListener("click", () => goToAdjacentProduct(-1));
        els.variantModalNext?.addEventListener("click", () => goToAdjacentProduct(1));

        els.variantModalQtyMinus?.addEventListener("click", () => {
            activeVariantQty = Math.max(1, activeVariantQty - 1);
            els.variantModalQty.textContent = activeVariantQty;
        });
        els.variantModalQtyPlus?.addEventListener("click", () => {
            activeVariantQty += 1;
            els.variantModalQty.textContent = activeVariantQty;
        });

        els.variantModalConfirm?.addEventListener("click", async function () {
            if (!activeVariantCard) return;
            const data = getCardData(activeVariantCard);
            if (!data.id) { showToast("error", "Item ID not found."); return; }

            const selectedIds = Object.values(activeVariantSelections);
            const singleVariantId = selectedIds.length === 1 ? selectedIds[0] : null;

            this.disabled = true;
            const textEl = this.querySelector(".add-cart-text");
            if (textEl) textEl.textContent = "Adding...";

            try {
                const result = await sendAddToCart({
                    itemId: data.id,
                    variantId: singleVariantId,
                    variantIds: selectedIds.length > 1 ? selectedIds : null,
                    qty: activeVariantQty
                });
                if (result.success) {
                    showToast("success", result.message || "Added to cart successfully.");
                    closeVariantModal();
                } else {
                    showToast("error", result.message || "Failed to add to cart.");
                }
            } catch (error) {
                console.error(error);
                showToast("error", "Something went wrong.");
            } finally {
                this.disabled = false;
                if (textEl) textEl.textContent = "Add to cart";
            }
        });
    }

    function bindFavoriteButtons() {
        els.favButtons.forEach(button => {
            button.addEventListener("click", async function () {
                const itemId = this.dataset.itemId;
                if (!itemId || this.disabled) return;
                this.disabled = true;

                try {
                    const response = await fetch("{{ route('user.pos.favorite.toggle') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({ item_id: itemId })
                    });
                    const data = await response.json();

                    if (!data.favorited) {
                        button.closest(".product-card")?.remove();
                        ensureEmptyState();
                        showToast("success", "Removed from favorites.");
                    }
                } catch (error) {
                    console.error(error);
                    showToast("error", "Failed to update favorite.");
                } finally {
                    this.disabled = false;
                }
            });
        });
    }

    function bindProductDetailNavigation() {
        els.productCards.forEach(card => {
            card.addEventListener("click", (e) => {
                if (e.target.closest(".qty-btn, .add-cart-btn, .fav-btn, .view-detail-btn")) return;
                const isMobile = window.matchMedia("(max-width: 768px)").matches;
                if (!isMobile) return;
                const detailUrl = card.dataset.detailUrl;
                if (detailUrl) window.location.href = detailUrl;
            });
        });
    }

    bindQuantityButtons();
    bindAddToCart();
    bindVariantModal();
    bindFavoriteButtons();
    bindProductDetailNavigation();
});
</script>
@endpush
