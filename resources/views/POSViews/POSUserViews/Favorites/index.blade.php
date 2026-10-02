@extends('Layout.POSUser.app')

@section('title', 'POS Favorites')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Favorites/index.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Favorites/index.css')) }}">
@endpush

@section('content')
    @php
        $categoryOptions = $favorites
            ->pluck('item_category_code')
            ->filter(fn ($category) => filled($category))
            ->unique()
            ->sort()
            ->values();
    @endphp

    <div class="page-wrap">
        <main class="content-area">
            @include('Layout.POSUser.header_mobile')
            @include('Layout.POSUser.footer')

            @if ($favorites->isNotEmpty())
                {{-- ===== MOBILE FILTERS (phone only) ===== --}}
                <div class="mobile-product-filters" id="mobileProductFilters">
                    <div class="mobile-filter-row">
                        <div class="mobile-search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="mobileSearchInput" placeholder="Search wishlist ...">
                        </div>

                        <button type="button" class="mobile-filter-btn" id="mobileFilterBtn"
                            aria-haspopup="dialog" aria-expanded="false" aria-controls="mobileFilterSheet">
                            <img src="{{ asset('images/AdminPOS/filter (1).png') }}" alt="Filter" class="mobile-filter-icon">
                            <span class="mobile-filter-count" id="mobileFilterCount" hidden>0</span>
                        </button>
                    </div>
                </div>

                {{-- Category filter — bottom sheet with checkboxes (phone only) --}}
                <div class="filter-sheet-overlay" id="mobileFilterOverlay">
                    <div class="filter-sheet" id="mobileFilterSheet" role="dialog" aria-modal="true"
                        aria-labelledby="mobileFilterTitle">
                        <div class="filter-sheet-handle"></div>
                        <div class="filter-sheet-header">
                            <h3 id="mobileFilterTitle">Filter by category</h3>
                            <button type="button" class="filter-sheet-close" id="mobileFilterClose"
                                aria-label="Close filter">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        @if ($categoryOptions->isNotEmpty())
                            <div class="filter-sheet-search">
                                <i class="bi bi-search"></i>
                                <input type="text" id="mobileFilterSearchInput" placeholder="Search category ...">
                            </div>
                        @endif
                        <div class="filter-sheet-body" id="mobileFilterBody">
                            @forelse ($categoryOptions as $category)
                                <label class="filter-checkbox-row">
                                    <input type="checkbox" class="filter-checkbox"
                                        value="{{ strtolower($category) }}">
                                    <span class="filter-checkbox-box"><i class="bi bi-check"></i></span>
                                    <span class="filter-checkbox-label">
                                        {{ ucwords(str_replace(['_', '-'], ' ', $category)) }}
                                    </span>
                                </label>
                            @empty
                                <div class="filter-empty">No categories available.</div>
                            @endforelse
                            <div class="filter-empty" id="mobileFilterNoMatch" hidden>No matching categories.</div>
                        </div>
                        <div class="filter-sheet-footer">
                            <button type="button" class="filter-clear-btn" id="mobileFilterClear">Clear all</button>
                            <button type="button" class="filter-apply-btn" id="mobileFilterApply">Apply</button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="header">
                <div class="topbar">
                    <div class="top">
                        <h1 class="title">Favorites</h1>
                        <a href="{{ route('user.pos.cart') }}" class="cart-box">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-count" id="desktopCartCount">{{ (int) ($cartCount ?? 0) }}</span>
                        </a>
                    </div>

                    @if ($favorites->isNotEmpty())
                        {{-- Search row: filter btn + pill search --}}
                        <div class="desktop-search-row">
                            <button type="button" class="desktop-filter-btn" id="desktopFilterBtn"
                                title="Filter by category">
                                <img src="{{ asset('images/AdminPOS/filter (1).png') }}" alt="Filter" class="desktop-filter-icon">
                            </button>

                            <div class="search-area">
                                <div class="search-wrapper">
                                    <div class="search-box-desktop">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="searchInput" placeholder="Search your wishlist...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Desktop category pills (toggled by filter btn) --}}
                        <div class="desktop-category-row" id="desktopCategoryRow">
                            <button type="button" class="category-filter-btn active" data-category="">
                                All
                            </button>
                            @foreach ($categoryOptions as $category)
                                <button type="button" class="category-filter-btn"
                                    data-category="{{ strtolower($category) }}">
                                    {{ ucwords(str_replace(['_', '-'], ' ', $category)) }}
                                </button>
                            @endforeach
                        </div>
                    @endif
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
                <div class="empty-box">
                    <div class="image-placeholder-desk">
                            <img src="{{ asset('images/pos/no wishlist 1.png') }}" alt="Empty Wishlist">
                            <h2>Your wishlist is empty</h2>
                        <p>Looks like you haven't added anything<br>to your wishlist yet</p>
                        <a href="{{ route('user.posinterface') }}" class="primary-btn">
                            Explore now
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        </div>
                </div>
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
                            $itemVariants = collect($item->variants ?? [])->map(function ($v) use ($item, $discountPercent) {
                                // Each variant's own price (with the product's discount applied)
                                $regular = $item->unitPriceFor($v);
                                $sale = round(max(0, $regular * (1 - ($discountPercent / 100))), 2);

                                return [
                                    'id'        => $v->id,
                                    'group'     => $v->variant_group ?? 'Options',
                                    'label'     => $v->description ?? $v->code,
                                    'image'     => $v->image_url ?: ($item->image_url ?: asset('images/no-image.png')),
                                    'blocked'   => (bool) ($v->sales_blocked ?? false),
                                    'price'     => number_format($sale, 2, '.', ''),
                                    'old_price' => $regular > $sale ? number_format($regular, 2, '.', '') : '',
                                ];
                            })->values();
                        @endphp

                        <div class="product-card product-item"
                            data-id="{{ $item->id }}"
                            data-detail-url="{{ route('user.pos.product.detail', $item->id) }}"
                            data-name="{{ strtolower($item->display_name ?? '') }}"
                            data-display-name="{{ $item->display_name ?? '' }}"
                            data-category="{{ strtolower($item->item_category_code ?? '') }}"
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
                                @if (!empty($item->base_unit_of_measure_code))
                                    <div class="product-subtitle">Unit: {{ strtoupper($item->base_unit_of_measure_code) }}</div>
                                @endif
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

                <div id="noSearchResult" class="empty-box" style="display:none;">
                    No matching products found.
                </div>
            @endif
        </main>
    </div>
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
        mobileCartCount:   document.getElementById("cartCount"),
        asideCartCount:    document.getElementById("asideCartCount"),
        mobileCartDot:     document.getElementById("mobileCartDot"),
        messageBox:        document.getElementById("messageBox"),
        toast:              document.getElementById("toast"),
        productsGrid:       document.getElementById("productsGrid"),
        productCards:       [...document.querySelectorAll(".product-card")],
        favButtons:         [...document.querySelectorAll(".fav-btn")],
        noSearchResult:     document.getElementById("noSearchResult"),

        searchInput:        document.getElementById("searchInput"),
        mobileSearchInput:  document.getElementById("mobileSearchInput"),

        desktopFilterBtn:   document.getElementById("desktopFilterBtn"),
        desktopCategoryRow: document.getElementById("desktopCategoryRow"),
        categoryButtons:    [...document.querySelectorAll(".category-filter-btn")],

        mobileFilterBtn:       document.getElementById("mobileFilterBtn"),
        mobileFilterCount:     document.getElementById("mobileFilterCount"),
        mobileFilterOverlay:   document.getElementById("mobileFilterOverlay"),
        mobileFilterClose:     document.getElementById("mobileFilterClose"),
        mobileFilterApply:     document.getElementById("mobileFilterApply"),
        mobileFilterClear:     document.getElementById("mobileFilterClear"),
        mobileFilterSearchInput: document.getElementById("mobileFilterSearchInput"),
        mobileFilterBody:      document.getElementById("mobileFilterBody"),
        mobileFilterNoMatch:   document.getElementById("mobileFilterNoMatch"),
        filterCheckboxes:      [...document.querySelectorAll(".filter-checkbox")],

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
        // NOT ".empty-box" — #noSearchResult ("No matching products found")
        // also carries that class and stays in the DOM (just hidden) even
        // when the wishlist isn't empty, so that check was always true and
        // this function silently did nothing after the very first removal.
        if (document.getElementById("jsEmptyWishlist")) return;

        // Same markup the server renders on a fresh page load (mobile
        // .wishlist-page + desktop .empty-box) — keeps the real "empty
        // wishlist" image/illustration instead of a bare text placeholder.
        const emptyHtml = `
            <div id="jsEmptyWishlist">
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
            <div class="empty-box">
                <div class="image-placeholder-desk">
                    <img src="{{ asset('images/pos/no wishlist 1.png') }}" alt="Empty Wishlist">
                    <h2>Your wishlist is empty</h2>
                    <p>Looks like you haven't added anything<br>to your wishlist yet</p>
                    <a href="{{ route('user.posinterface') }}" class="primary-btn">
                        Explore now
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
            </div>
        `;

        els.messageBox?.insertAdjacentHTML("afterend", emptyHtml);

        // A fresh page load only renders the search/filter bar when there
        // are favorites to search — match that once the last one is gone.
        document.getElementById("mobileProductFilters")?.remove();
        document.querySelector(".desktop-search-row")?.remove();
        document.querySelector(".desktop-category-row")?.remove();
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
            if (els.mobileCartCount) {
                els.mobileCartCount.textContent = data.cartCount;
                els.mobileCartCount.classList.toggle("is-empty", data.cartCount <= 0);
            }
            if (els.asideCartCount) {
                els.asideCartCount.classList.toggle("show", data.cartCount > 0);
            }
            if (els.mobileCartDot) {
                els.mobileCartDot.classList.toggle("show", data.cartCount > 0);
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

    // Show a price (and struck-through old price) in the popup — the
    // picked variant's own price, or the product's when none is given.
    function setVariantModalPrice(price, oldPrice) {
        els.variantModalPrice.textContent = `$${price}`;
        if (oldPrice && parseFloat(oldPrice) > parseFloat(price)) {
            els.variantModalOldPrice.textContent = `$${oldPrice}`;
            els.variantModalOldPrice.style.display = "";
        } else {
            els.variantModalOldPrice.style.display = "none";
        }
    }

    function renderVariantModal(card) {
        const data = getCardData(card);
        activeVariantQty = 1;
        activeVariantSelections = {};

        const hasVariants = data.variants && data.variants.length > 0;

        els.variantModalImage.src = data.image;
        els.variantModalImage.alt = data.displayName;
        els.variantModalTitle.textContent = data.displayName;
        els.variantModalQty.textContent = "1";
        els.variantModalViewDetail.href = data.detailUrl;
        setVariantModalPrice(data.price, card.dataset.oldPrice);

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
                if (firstAvailable.price) setVariantModalPrice(firstAvailable.price, firstAvailable.old_price);

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
                        if (v.price) setVariantModalPrice(v.price, v.old_price);
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

                    if (!data.success) {
                        showToast("error", data.message || "Failed to update favorite.");
                        return;
                    }

                    if (!data.favorited) {
                        button.closest(".product-card")?.remove();
                        ensureEmptyState();
                        showToast("success", data.message || "Removed from favorites.");
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

    /* ── search + category filter (same pattern as the Products page) ── */
    let activeCategories = new Set();

    function normalizeCategory(value = "") { return value.trim().toLowerCase(); }

    function currentSearchValue() {
        return (els.mobileSearchInput?.value || els.searchInput?.value || "").trim();
    }

    function syncSearchInputs(value) {
        if (els.searchInput && els.searchInput.value !== value) els.searchInput.value = value;
        if (els.mobileSearchInput && els.mobileSearchInput.value !== value) els.mobileSearchInput.value = value;
    }

    function matchCard(card, keyword) {
        const text = keyword.trim().toLowerCase();
        const name = card.dataset.name || "";
        const category = (card.dataset.category || "").toLowerCase();
        if (activeCategories.size > 0 && !activeCategories.has(category)) return false;
        if (!text) return true;
        return name.includes(text);
    }

    function filterProducts(keyword = currentSearchValue()) {
        let visibleCount = 0;
        const value = keyword.trim();
        syncSearchInputs(value);
        els.productCards.forEach(card => {
            const matched = matchCard(card, value);
            card.style.display = matched ? "" : "none";
            if (matched) visibleCount++;
        });
        if (els.noSearchResult) els.noSearchResult.style.display = visibleCount ? "none" : "block";
        updateNavState();
    }

    function bindSearch() {
        els.searchInput?.addEventListener("input", e => filterProducts(e.target.value));
        els.mobileSearchInput?.addEventListener("input", e => filterProducts(e.target.value));
    }

    function bindDesktopFilterBtn() {
        if (!els.desktopFilterBtn || !els.desktopCategoryRow) return;
        els.desktopFilterBtn.addEventListener("click", () => {
            const open = els.desktopCategoryRow.classList.toggle("open");
            els.desktopFilterBtn.classList.toggle("active", open);
        });
    }

    function syncFilterUI() {
        els.categoryButtons.forEach(btn => {
            const btnCategory = normalizeCategory(btn.dataset.category || "");
            const isAllBtn = btnCategory === "";
            btn.classList.toggle("active", isAllBtn ? activeCategories.size === 0 : activeCategories.has(btnCategory));
        });

        els.filterCheckboxes.forEach(cb => {
            cb.checked = activeCategories.has(cb.value);
        });

        const count = activeCategories.size;
        if (els.mobileFilterCount) {
            els.mobileFilterCount.textContent = count;
            els.mobileFilterCount.hidden = count === 0;
        }
        els.mobileFilterBtn?.classList.toggle("active", count > 0);
    }

    function bindCategoryFilters() {
        els.categoryButtons.forEach(button => {
            button.addEventListener("click", () => {
                const category = normalizeCategory(button.dataset.category || "");
                if (category) {
                    activeCategories = new Set([category]);
                } else {
                    activeCategories.clear();
                }
                syncFilterUI();
                filterProducts();
            });
        });
    }

    function bindMobileFilterSheet() {
        if (!els.mobileFilterBtn || !els.mobileFilterOverlay) return;

        function openSheet() {
            els.mobileFilterOverlay.classList.add("show");
            els.mobileFilterBtn.setAttribute("aria-expanded", "true");
            document.body.classList.add("filter-sheet-open");
        }
        function closeSheet() {
            els.mobileFilterOverlay.classList.remove("show");
            els.mobileFilterBtn.setAttribute("aria-expanded", "false");
            document.body.classList.remove("filter-sheet-open");

            if (els.mobileFilterSearchInput) els.mobileFilterSearchInput.value = "";
            els.mobileFilterBody?.querySelectorAll(".filter-checkbox-row").forEach(row => {
                row.hidden = false;
            });
            if (els.mobileFilterNoMatch) els.mobileFilterNoMatch.hidden = true;
        }

        els.mobileFilterBtn.addEventListener("click", openSheet);
        els.mobileFilterClose?.addEventListener("click", closeSheet);
        els.mobileFilterApply?.addEventListener("click", closeSheet);
        els.mobileFilterOverlay.addEventListener("click", (e) => {
            if (e.target === els.mobileFilterOverlay) closeSheet();
        });

        els.filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", () => {
                if (checkbox.checked) activeCategories.add(checkbox.value);
                else activeCategories.delete(checkbox.value);
                syncFilterUI();
                filterProducts();
            });
        });

        els.mobileFilterClear?.addEventListener("click", () => {
            activeCategories.clear();
            syncFilterUI();
            filterProducts();
        });

        els.mobileFilterSearchInput?.addEventListener("input", () => {
            const text = els.mobileFilterSearchInput.value.trim().toLowerCase();
            let visibleCount = 0;
            els.mobileFilterBody?.querySelectorAll(".filter-checkbox-row").forEach(row => {
                const label = row.querySelector(".filter-checkbox-label")?.textContent?.trim().toLowerCase() || "";
                const matched = !text || label.includes(text);
                row.hidden = !matched;
                if (matched) visibleCount++;
            });
            if (els.mobileFilterNoMatch) els.mobileFilterNoMatch.hidden = visibleCount > 0;
        });
    }

    bindQuantityButtons();
    bindAddToCart();
    bindVariantModal();
    bindFavoriteButtons();
    bindSearch();
    bindDesktopFilterBtn();
    bindCategoryFilters();
    bindMobileFilterSheet();
    bindProductDetailNavigation();
});
</script>
@endpush
