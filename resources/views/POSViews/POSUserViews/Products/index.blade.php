@extends('ManagementSystemViews.UserViews.Layouts.app')

    @section('title', 'POS User Item List')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Products/item-list.css') }}">
    @endpush

    @section('content')
        @php
            $categoryOptions = $items
                ->pluck('item_category_code')
                ->filter(fn ($category) => filled($category))
                ->unique()
                ->sort()
                ->values();
        @endphp

        <div class="pl-page-wrap">
           
            <main class="pl-content-area">
            @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            @include('ManagementSystemViews.UserViews.Layouts.footer')
                {{-- ===== MOBILE FILTERS (phone only) ===== --}}
                <div class="pl-mobile-product-filters" id="mobileProductFilters">
                    <div class="pl-mobile-search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="mobileSearchInput" placeholder="Search products ...">
                    </div>
                    <div class="pl-mobile-category-row" aria-label="Product categories">
                        <button type="button" class="pl-category-filter-btn active" data-category="">
                            All categories
                        </button>
                        @foreach ($categoryOptions as $category)
                            <button type="button" class="pl-category-filter-btn"
                                data-category="{{ strtolower($category) }}">
                                {{ ucwords(str_replace(['_', '-'], ' ', $category)) }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="pl-header">
                    <div class="pl-topbar">

                        {{-- Title + Cart --}}
                        <div class="pl-top">
                            <h1 class="pl-title">Products</h1>
                            <a href="{{ route('user.pos.cart') }}" class="pl-cart-box">
                                <i class="bi bi-cart3"></i>
                                <span class="pl-cart-count" id="desktopCartCount">{{ (int) ($cartCount ?? 0) }}</span>
                            </a>
                        </div>

                        {{-- Search row: filter btn + pill search --}}
                        <div class="pl-desktop-search-row">

                            {{-- Teal filter toggle button --}}
                            <button type="button" class="pl-desktop-filter-btn" id="desktopFilterBtn"
                                title="Filter by category">
                                <i class="bi bi-sliders2"></i>
                            </button>

                            {{-- Pill search with teal send button --}}
                            <div class="pl-search-area">
                                <div class="pl-search-wrapper">
                                    <div class="pl-search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="searchInput" placeholder="Search products...">
                                    </div>

                                    {{-- Search dropdown --}}
                                    <div class="pl-search-dropdown" id="searchDropdown">
                                        <div class="pl-search-dropdown-left">
                                            <div class="pl-search-section-title">Your Searches</div>
                                            <div id="searchSuggestions"></div>
                                        </div>
                                        <div class="pl-search-dropdown-right">
                                            <div class="pl-search-section-title">Products</div>
                                            <div id="searchPreviewProducts" class="pl-search-preview-products"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Desktop category pills (toggled by filter btn) --}}
                        <div class="pl-desktop-category-row" id="desktopCategoryRow">
                            <button type="button" class="pl-category-filter-btn active" data-category="">
                                All
                            </button>
                            @foreach ($categoryOptions as $category)
                                <button type="button" class="pl-category-filter-btn"
                                    data-category="{{ strtolower($category) }}">
                                    {{ ucwords(str_replace(['_', '-'], ' ', $category)) }}
                                </button>
                            @endforeach
                        </div>

                    </div>
                </div>

                <div id="messageBox" class="pl-message-box"></div>
                <div id="toast" class="pl-toast" aria-live="polite" aria-atomic="true"></div>

                @if ($items->isEmpty())
                    <div class="pl-empty-box">No items found.</div>
                @else
                    <div class="pl-products-grid" id="productsGrid">
                        @foreach ($items as $item)
                            @php
                                $normalPrice     = (float) ($item->unit_price ?? 0);
                                $discountPercent = (float) ($item->effective_discount_percent ?? 0);
                                $salePrice       = (float) ($item->final_price ?? $normalPrice);
                                $oldPrice        = $discountPercent > 0 ? $normalPrice : 0;
                                $descText        = $item->short_description ?? '';
                                $sizeLabel       = strtoupper($item->base_unit_of_measure_code ?? 'S');

                                // Variants relationship — same source the product detail page queries
                                // from item_variants. Embedded as JSON so the popup doesn't need an
                                // extra AJAX call when "Add to cart" is clicked.
                                //
                                // ADJUST THIS: 'group' below decides which option-row a variant
                                // renders under (e.g. "Size" vs "Beef Type" in your screenshot).
                                // Replace $v->variant_group with whatever column on your
                                // ItemVariant model actually stores that grouping
                                // (e.g. $v->attribute_name, $v->type, $v->option_group, etc).
                                // If your variants table has no such column yet, you'll need to
                                // add one — otherwise every variant falls into a single "Options" row.
                                $itemVariants = collect($item->variants ?? [])->map(fn ($v) => [
                                    'id'      => $v->id,
                                    'group'   => $v->variant_group ?? 'Options', // <-- adjust field name
                                    'label'   => $v->description ?? $v->code,
                                    'image'   => $v->image_url ?: ($item->image_url ?: asset('images/no-image.png')),
                                    'blocked' => (bool) ($v->sales_blocked ?? false),
                                ])->values();
                            @endphp

                            <div class="pl-product-card pl-product-item"
                                data-id="{{ $item->id }}"
                                data-detail-url="{{ route('user.pos.product.detail', $item->id) }}"
                                data-name="{{ strtolower($item->display_name ?? '') }}"
                                data-display-name="{{ $item->display_name ?? '' }}"
                                data-desc="{{ strtolower($descText) }}"
                                data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}"
                                data-category="{{ strtolower($item->item_category_code ?? '') }}"
                                data-price="{{ number_format($salePrice, 2, '.', '') }}"
                                data-old-price="{{ $oldPrice > $salePrice ? number_format($oldPrice, 2, '.', '') : '' }}"
                                data-image="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                data-variants="{{ $itemVariants->toJson() }}">

                                <div class="pl-product-img-box">
                                    @if ($discountPercent > 0)
                                        <div class="pl-discount-badge">
                                            SAVE {{ rtrim(rtrim(number_format($discountPercent, 2), '0'), '.') }} %
                                        </div>
                                    @endif

                                    <button class="pl-fav-btn" data-item-id="{{ $item->id }}">
                                        <i class="bi {{ in_array($item->id, $favoriteIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                    </button>

                                    <img src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                        alt="{{ $item->display_name ?? 'No Name' }}" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                </div>

                                <div class="pl-product-info">
                                    <div class="pl-product-title">{{ $item->display_name ?: 'No Name' }}</div>
                                    {{-- <div class="pl-product-subtitle">Size {{ $sizeLabel }}</div> --}}

                                    <div class="pl-price-row {{ $oldPrice > $salePrice ? 'has-discount' : 'no-discount' }}">
                                        <div class="pl-old-price">
                                            @if ($oldPrice > $salePrice)
                                                ${{ number_format($oldPrice, 2) }}
                                            @endif
                                        </div>
                                        <div class="pl-new-price">${{ number_format($salePrice, 2) }}</div>
                                    </div>

                                    <div class="pl-qty-section">
                                        <span class="pl-qty-label">Quantity:</span>
                                        <div class="pl-qty-box">
                                            <button type="button" class="pl-qty-btn minus">−</button>
                                            <span class="pl-qty">1</span>
                                            <button type="button" class="pl-qty-btn plus">+</button>
                                        </div>
                                    </div>

                                    <button type="button" class="pl-add-cart-btn mobile-action"
                                        data-id="{{ $item->id }}">
                                        <span class="pl-add-cart-text">Add to cart</span>
                                    </button>

                                    <a href="{{ route('user.pos.product.detail', $item->id) }}" class="pl-view-detail-btn">
                                        View detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="noSearchResult" class="pl-empty-box" style="display:none; margin-top:16px;">
                        No matching products found.
                    </div>
                @endif

            </main>
        </div>

        {{-- ===== VARIANT SELECTION POPUP ===== --}}
        <div class="pl-variant-modal-overlay" id="variantModalOverlay">
            <div class="pl-variant-modal" role="dialog" aria-modal="true" aria-labelledby="variantModalTitle">
                <button type="button" class="pl-variant-modal-close" id="variantModalClose" title="Close">
                    <i class="bi bi-x-lg"></i>
                </button>

                <div class="pl-variant-modal-body">
                    <div class="pl-variant-modal-image-col">
                        <img id="variantModalImage" src="" alt="">
                    </div>

                    <div class="pl-variant-modal-info">
                        <h3 id="variantModalTitle"></h3>

                        <div class="pl-variant-modal-price-row">
                            <span class="pl-variant-modal-old-price" id="variantModalOldPrice"></span>
                            <div class="pl-variant-modal-price" id="variantModalPrice"></div>
                        </div>

                        {{-- Option groups (Size, Beef Type, etc.) are injected here dynamically,
                             one label + one button-row per group, mirroring the screenshot. --}}
                        <div id="variantModalOptions"></div>

                        <div class="pl-variant-modal-qty">
                            <div class="pl-qty-box">
                                <button type="button" class="pl-qty-btn" id="variantModalQtyMinus">−</button>
                                <span class="pl-qty" id="variantModalQty">1</span>
                                <button type="button" class="pl-qty-btn" id="variantModalQtyPlus">+</button>
                            </div>
                        </div>

                        <button type="button" class="pl-add-cart-btn pl-variant-modal-confirm" id="variantModalConfirm">
                            <span class="pl-add-cart-text">Add to cart</span>
                        </button>

                        <a href="#" class="pl-view-detail-btn pl-variant-modal-view-detail" id="variantModalViewDetail">
                            View detail
                        </a>
                    </div>
                </div>
            </div>

            {{-- floating prev/next pill — switches between currently visible products --}}
            <div class="pl-variant-modal-nav" id="variantModalNav">
                <button type="button" class="pl-variant-modal-nav-btn" id="variantModalPrev" title="Previous product">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <span class="pl-variant-modal-nav-divider"></span>
                <button type="button" class="pl-variant-modal-nav-btn" id="variantModalNext" title="Next product">
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
            appShell:              document.getElementById("appShell"),

            cartCount:             document.getElementById("desktopCartCount"),
            mobileCartCount:       document.getElementById("cartCount"),
            asideCartCount:        document.getElementById("asideCartCount"),
            messageBox:            document.getElementById("messageBox"),

            searchInput:           document.getElementById("searchInput"),
            searchDropdown:        document.getElementById("searchDropdown"),
            searchSuggestions:     document.getElementById("searchSuggestions"),
            searchPreviewProducts: document.getElementById("searchPreviewProducts"),
            searchWrapper:         document.querySelector(".pl-search-wrapper"),

            mobileProductFilters:  document.getElementById("mobileProductFilters"),
            mobileSearchInput:     document.getElementById("mobileSearchInput"),
            mobileCategoryRow:     document.querySelector(".pl-mobile-category-row"),

            desktopFilterBtn:      document.getElementById("desktopFilterBtn"),
            desktopCategoryRow:    document.getElementById("desktopCategoryRow"),

            categoryButtons:       [...document.querySelectorAll(".pl-category-filter-btn")],
            noSearchResult:        document.getElementById("noSearchResult"),
            productsGrid:          document.getElementById("productsGrid"),

            productCards:          [...document.querySelectorAll(".pl-product-card")],
            favButtons:            [...document.querySelectorAll(".pl-fav-btn")],

            // variant modal
            variantModalOverlay:   document.getElementById("variantModalOverlay"),
            variantModalClose:     document.getElementById("variantModalClose"),
            variantModalImage:     document.getElementById("variantModalImage"),
            variantModalTitle:     document.getElementById("variantModalTitle"),
            variantModalOldPrice:  document.getElementById("variantModalOldPrice"),
            variantModalPrice:     document.getElementById("variantModalPrice"),
            variantModalOptions:   document.getElementById("variantModalOptions"),
            variantModalQty:       document.getElementById("variantModalQty"),
            variantModalQtyMinus:  document.getElementById("variantModalQtyMinus"),
            variantModalQtyPlus:   document.getElementById("variantModalQtyPlus"),
            variantModalConfirm:   document.getElementById("variantModalConfirm"),
            variantModalViewDetail: document.getElementById("variantModalViewDetail"),
            variantModalNav:       document.getElementById("variantModalNav"),
            variantModalPrev:      document.getElementById("variantModalPrev"),
            variantModalNext:      document.getElementById("variantModalNext")
        };

        let selectedCategory = "";
        let lastScrollY = window.scrollY;

        let recentSearches = JSON.parse(localStorage.getItem("pos_recent_searches")) || [
            "premium beef", "beef steak", "meat"
        ];

        function saveSearchHistory() {
            localStorage.setItem("pos_recent_searches", JSON.stringify(recentSearches));
        }

        function showMessage(type, text) {
            if (!els.messageBox) return;
            const iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-octagon-fill';
            const title     = type === 'success' ? 'Success!' : 'Error!';
            els.messageBox.innerHTML = `
                <i class="bi ${iconClass} pl-main-icon"></i>
                <div class="pl-message-content"><strong>${title}</strong> ${text}</div>
                <button type="button" class="pl-close-alert-btn"
                    onclick="this.parentElement.classList.remove('show')">
                    <i class="bi bi-x"></i>
                </button>`;
            els.messageBox.className = `pl-message-box ${type} show`;
            setTimeout(() => els.messageBox.classList.remove('show'), 4000);
        }

        function showToast(type, text) {
            const toastEl = document.getElementById('toast');
            if (!toastEl) return;
            toastEl.textContent = text;
            toastEl.className = `pl-toast show ${type}`;
            setTimeout(() => { toastEl.className = 'pl-toast'; }, 2500);
        }

        function escapeHtml(text = "") {
            const div = document.createElement("div");
            div.textContent = text;
            return div.innerHTML;
        }

        function getCardData(card) {
            return {
                id:          card.dataset.id || "",
                name:        (card.dataset.name || "").toLowerCase(),
                displayName: card.dataset.displayName ||
                            card.querySelector(".pl-product-title")?.textContent?.trim() || "No Name",
                desc:        (card.dataset.desc || "").toLowerCase(),
                category:    (card.dataset.category || "").toLowerCase(),
                uom:         (card.dataset.uom || "").toLowerCase(),
                price:       card.dataset.price || "0.00",
                image:       card.dataset.image || card.querySelector("img")?.src || "",
                variants:    (() => {
                    try { return JSON.parse(card.dataset.variants || "[]"); }
                    catch (e) { return []; }
                })()
            };
        }

        function normalizeCategory(value = "") { return value.trim().toLowerCase(); }

        function currentSearchValue() {
            return (els.mobileSearchInput?.value || els.searchInput?.value || "").trim();
        }

        function syncSearchInputs(value) {
            if (els.searchInput && els.searchInput.value !== value) els.searchInput.value = value;
            if (els.mobileSearchInput && els.mobileSearchInput.value !== value) els.mobileSearchInput.value = value;
        }

        function matchCard(card, keyword, category = selectedCategory) {
            const text = keyword.trim().toLowerCase();
            const data = getCardData(card);
            if (category && data.category !== category) return false;
            if (!text) return true;
            return [data.name, data.displayName.toLowerCase(), data.desc, data.category, data.uom]
                .some(v => v.includes(text));
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
            if (els.noSearchResult)
                els.noSearchResult.style.display = visibleCount ? "none" : "block";
        }

        function getMatchedCards(keyword) {
            if (!keyword.trim()) return [];
            return els.productCards.filter(card => matchCard(card, keyword));
        }

        function addRecentSearch(keyword) {
            const value = keyword.trim().toLowerCase();
            if (!value) return;
            recentSearches = recentSearches.filter(i => i !== value);
            recentSearches.unshift(value);
            recentSearches = recentSearches.slice(0, 8);
            saveSearchHistory();
        }

        function closeSearchDropdown() { els.searchDropdown?.classList.remove("show"); }
        function openSearchDropdown()  { els.searchDropdown?.classList.add("show"); }

        function removeRecentSearch(keyword) {
            recentSearches = recentSearches.filter(i => i !== keyword);
            localStorage.setItem("pos_recent_searches", JSON.stringify(recentSearches));
            renderSearchPanel(els.searchInput.value);
        }

        function renderSuggestions(keyword) {
            if (!els.searchSuggestions) return;
            const text = keyword.trim().toLowerCase();
            let suggestions = text ? recentSearches.filter(i => i.includes(text)) : recentSearches;
            if (!suggestions.length) {
                els.searchSuggestions.innerHTML = `<div class="pl-search-empty">No search history</div>`;
                return;
            }
            els.searchSuggestions.innerHTML = suggestions.map(item => `
                <div class="pl-search-suggestion-item" data-value="${escapeHtml(item)}">
                    <div class="pl-search-suggestion-left">
                        <i class="bi bi-clock-history"></i>
                        <span>${escapeHtml(item)}</span>
                    </div>
                    <div class="pl-search-item-actions">
                        <button type="button" class="pl-delete-history-btn" data-value="${escapeHtml(item)}">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>`).join("");

            els.searchSuggestions.querySelectorAll(".pl-search-suggestion-item").forEach(item => {
                item.addEventListener("click", (e) => {
                    if (e.target.closest('.pl-delete-history-btn')) return;
                    const value = item.dataset.value || "";
                    syncSearchInputs(value);
                    addRecentSearch(value);
                    filterProducts(value);
                    renderSearchPanel(value);
                });
            });
            els.searchSuggestions.querySelectorAll(".pl-delete-history-btn").forEach(btn => {
                btn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    removeRecentSearch(btn.dataset.value);
                });
            });
        }

        function renderPreviewProducts(keyword) {
            if (!els.searchPreviewProducts) return;
            const matchedCards = getMatchedCards(keyword);
            if (!matchedCards.length) {
                els.searchPreviewProducts.innerHTML = `<div class="pl-search-empty">No product found</div>`;
                return;
            }
            els.searchPreviewProducts.innerHTML = matchedCards.slice(0, 3).map(card => {
                const data = getCardData(card);
                return `
                    <div class="pl-search-preview-card" data-id="${escapeHtml(data.id)}">
                        <img src="${escapeHtml(data.image)}" alt="${escapeHtml(data.displayName)}">
                        <div class="pl-search-preview-info">
                            <div class="pl-search-preview-name">${escapeHtml(data.displayName)}</div>
                            <div class="pl-search-preview-price">$${escapeHtml(data.price)}</div>
                            <button type="button" class="pl-search-preview-btn">View</button>
                        </div>
                    </div>`;
            }).join("");

            els.searchPreviewProducts.querySelectorAll(".pl-search-preview-card").forEach(preview => {
                preview.querySelector(".pl-search-preview-btn")?.addEventListener("click", () => {
                    const card = els.productCards.find(c => c.dataset.id === preview.dataset.id);
                    if (!card) return;
                    filterProducts(keyword);
                    closeSearchDropdown();
                    card.scrollIntoView({ behavior: "smooth", block: "center" });
                    card.classList.add("pl-highlight-product");
                    setTimeout(() => card.classList.remove("pl-highlight-product"), 1500);
                });
            });
        }

        function renderSearchPanel(keyword) {
            const value = keyword.trim();
            renderSuggestions(value);
            if (value) {
                renderPreviewProducts(value);
                filterProducts(value);
            } else {
                if (els.searchPreviewProducts)
                    els.searchPreviewProducts.innerHTML =
                        `<div class="pl-search-empty">Start typing to find products...</div>`;
                filterProducts("");
            }
            openSearchDropdown();
        }

        /* ── desktop filter btn toggle ── */
        function bindDesktopFilterBtn() {
            if (!els.desktopFilterBtn || !els.desktopCategoryRow) return;
            els.desktopFilterBtn.addEventListener("click", () => {
                const open = els.desktopCategoryRow.classList.toggle("open");
                els.desktopFilterBtn.classList.toggle("active", open);
            });
        }

        // Sidebar settings toggle + nav active-state (Dashboard/Cart/Favorite/etc.)
        // are already handled by aside.blade.php's own inline script — that
        // partial is included once per page and owns #settingsBtn, #settingsBox,
        // and the nav buttons. Re-binding those same IDs here previously caused
        // TWO click listeners to fire on one click, so the settings menu would
        // open then immediately toggle closed again. Left as a no-op so nothing
        // else in this file that calls bindSidebar() needs to change.
        function bindSidebar() {}

        function bindQuantityButtons() {
            els.productCards.forEach(card => {
                const plusBtn = card.querySelector(".plus");
                const minusBtn = card.querySelector(".minus");
                const qtyEl   = card.querySelector(".pl-qty");
                plusBtn?.addEventListener("click",  () => {
                    if (qtyEl) qtyEl.textContent = parseInt(qtyEl.textContent || "0", 10) + 1;
                });
                minusBtn?.addEventListener("click", () => {
                    const cur = parseInt(qtyEl?.textContent || "0", 10);
                    if (cur > 1 && qtyEl) qtyEl.textContent = cur - 1;
                });
            });
        }

        /* ── shared cart-add call, used by both direct add and the variant popup ── */
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
                    variant_id: variantId || null,     // kept for backward compatibility (single-variant case)
                    variant_ids: variantIds || null,    // populated when a product has multiple option groups
                    qty: qty
                })
            });
            const data = await response.json();

            if (data.success && data.cartCount !== undefined) {
                if (els.cartCount)       els.cartCount.textContent = data.cartCount;
                if (els.mobileCartCount) els.mobileCartCount.textContent = data.cartCount;
                if (els.asideCartCount) {
                    els.asideCartCount.textContent = data.cartCount;
                    els.asideCartCount.classList.toggle("is-empty", data.cartCount <= 0);
                }
            }
            return data;
        }

        /* ─────────────────────────────────────────────────────────────
           Products WITH variants  -> open the popup (grouped options).
           Products WITHOUT variants -> add straight to cart, no popup,
           using the qty already set on the card itself.
           ───────────────────────────────────────────────────────────── */
        function bindAddToCart() {
            els.productCards.forEach(card => {
                const addBtn = card.querySelector(".pl-add-cart-btn");
                const qtyEl  = card.querySelector(".pl-qty");
                addBtn?.addEventListener("click", async function () {
                    const data = getCardData(card);
                    if (!data.id) { showMessage("error", "Item ID not found."); return; }

                    if (data.variants && data.variants.length > 0) {
                        openVariantModal(card);
                        return;
                    }

                    const qty = parseInt(qtyEl?.textContent || "1", 10);
                    this.disabled = true;
                    const textEl = this.querySelector(".pl-add-cart-text");
                    if (textEl) textEl.textContent = "Adding..."; else this.textContent = "Adding...";
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
                        showMessage("error", "Something went wrong.");
                    } finally {
                        this.disabled = false;
                        if (textEl) textEl.textContent = "Add to cart"; else this.textContent = "Add to cart";
                    }
                });
            });
        }

        /* ── variant selection popup ── */
        let activeVariantCard = null;
        let activeVariantSelections = {}; // { "Size": variantId, "Beef Type": variantId, ... }
        let activeVariantQty  = 1;

        function visibleCards() {
            // Only cards currently shown (respects active search/category filter)
            return els.productCards.filter(card => card.style.display !== "none");
        }

        // groups a flat variants array into { "Size": [...], "Beef Type": [...] }
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
            els.variantModalViewDetail.href = card.dataset.detailUrl || "#";

            const oldPriceAttr = card.dataset.oldPrice;
            if (oldPriceAttr && parseFloat(oldPriceAttr) > parseFloat(data.price)) {
                els.variantModalOldPrice.textContent = `$${oldPriceAttr}`;
                els.variantModalOldPrice.style.display = "";
            } else {
                els.variantModalOldPrice.style.display = "none";
            }

            els.variantModalOptions.innerHTML = "";
            // reset to block layout so groups stack vertically (Size above Beef Type),
            // instead of the flex/wrap row styling meant for a single option row.
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
                    label.className = "pl-variant-modal-label";
                    label.textContent = groupName;

                    const optionsRow = document.createElement("div");
                    optionsRow.className = "pl-variant-modal-options"; // reuse existing flex-row styling

                    groupList.forEach(v => {
                        const btn = document.createElement("button");
                        btn.type = "button";
                        btn.className = "pl-variant-btn" +
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
                            optionsRow.querySelectorAll(".pl-variant-btn").forEach(b => b.classList.remove("active"));
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
                // Backward-compatible single id when there's only one option group.
                const singleVariantId = selectedIds.length === 1 ? selectedIds[0] : null;

                this.disabled = true;
                const textEl = this.querySelector(".pl-add-cart-text");
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
                    const icon   = this.querySelector("i");
                    if (!itemId) return;
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
                        if (!icon) return;
                        if (data.favorited) {
                            icon.classList.remove("bi-heart");
                            icon.classList.add("bi-heart-fill", "text-danger");
                        } else {
                            icon.classList.remove("bi-heart-fill", "text-danger");
                            icon.classList.add("bi-heart");
                        }
                    } catch (error) {
                        console.error(error);
                        showMessage("error", "Favorite update failed.");
                    }
                });
            });
        }

        function bindSearch() {
            if (els.mobileSearchInput) {
                els.mobileSearchInput.addEventListener("input",  e => { filterProducts(e.target.value); closeSearchDropdown(); });
                els.mobileSearchInput.addEventListener("keydown", e => {
                    if (e.key === "Enter") {
                        const v = e.target.value.trim();
                        if (v) addRecentSearch(v);
                        els.mobileSearchInput.blur();
                    }
                });
            }

            if (!els.searchInput) return;

            els.searchInput.addEventListener("focus",  ()  => renderSearchPanel(els.searchInput.value));
            els.searchInput.addEventListener("input",  e   => renderSearchPanel(e.target.value));
            els.searchInput.addEventListener("keydown", e  => {
                if (e.key === "Enter") {
                    const v = e.target.value.trim();
                    if (v) { addRecentSearch(v); closeSearchDropdown(); }
                }
            });

            document.getElementById("searchSubmitBtn")?.addEventListener("click", () => {
                const value = els.searchInput.value.trim();
                if (value) { addRecentSearch(value); filterProducts(value); closeSearchDropdown(); }
            });

            document.addEventListener("click", e => {
                if (!els.searchWrapper?.contains(e.target)) closeSearchDropdown();
            });
        }

        function bindCategoryFilters() {
            els.categoryButtons.forEach(button => {
                button.addEventListener("click", () => {
                    selectedCategory = normalizeCategory(button.dataset.category || "");
                    const parent = button.closest(".pl-desktop-category-row, .pl-mobile-category-row");
                    if (parent) parent.querySelectorAll(".pl-category-filter-btn").forEach(b => b.classList.remove("active"));
                    button.classList.add("active");
                    filterProducts();
                    closeSearchDropdown();
                });
            });
        }

        function bindMobileFilterScroll() {
            if (!els.mobileCategoryRow) return;
            const isMobile = () => window.matchMedia("(max-width: 767px)").matches;
            const updateCategoryVisibility = (currentY) => {
                if (!isMobile()) { els.mobileCategoryRow.classList.remove("is-hidden"); return; }
                const scrollingDown = currentY > lastScrollY;
                els.mobileCategoryRow.classList.toggle("is-hidden", scrollingDown && currentY > 24);
                lastScrollY = Math.max(currentY, 0);
            };
            window.addEventListener("scroll",  () => updateCategoryVisibility(window.scrollY), { passive: true });
            els.productsGrid?.addEventListener("scroll", () => updateCategoryVisibility(els.productsGrid.scrollTop), { passive: true });
        }

        function bindProductDetailNavigation() {
            els.productCards.forEach(card => {
                card.addEventListener("click", (e) => {
                    if (e.target.closest(".pl-qty-btn, .pl-add-cart-btn, .pl-fav-btn, .pl-search-preview-btn, .pl-view-detail-btn")) return;
                    const isMobile = window.matchMedia("(max-width: 768px)").matches;
                    if (!isMobile) return;
                    const detailUrl = card.dataset.detailUrl;
                    if (detailUrl) window.location.href = detailUrl;
                });
            });
        }

        bindDesktopFilterBtn();
        bindSidebar();
        bindQuantityButtons();
        bindAddToCart();
        bindVariantModal();
        bindFavoriteButtons();
        bindSearch();
        bindCategoryFilters();
        bindMobileFilterScroll();
        bindProductDetailNavigation();
    });
    </script>
    @endpush