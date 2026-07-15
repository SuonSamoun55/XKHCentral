@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS User Item List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pos/item-list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Products/index.css') }}">
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

    <div class="page-wrap">
        <main class="content-area">
            @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            @include('ManagementSystemViews.UserViews.Layouts.footer')

            {{-- ===== MOBILE FILTERS (phone only) ===== --}}
            <div class="mobile-product-filters" id="mobileProductFilters">
                <div class="mobile-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="mobileSearchInput" placeholder="Search products ...">
                </div>
                <div class="mobile-category-row" aria-label="Product categories">
                    <button type="button" class="category-filter-btn active" data-category="">
                        All categories
                    </button>
                    @foreach ($categoryOptions as $category)
                        <button type="button" class="category-filter-btn"
                            data-category="{{ strtolower($category) }}">
                            {{ ucwords(str_replace(['_', '-'], ' ', $category)) }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="header">
                <div class="topbar">

                    {{-- Title + Cart --}}
                    <div class="top">
                        <h1 class="title">Products</h1>
                        <a href="{{ route('user.pos.cart') }}" class="cart-box">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-count" id="desktopCartCount">{{ (int) ($cartCount ?? 0) }}</span>
                        </a>
                    </div>

                    {{-- Search row: filter btn + pill search --}}
                    <div class="desktop-search-row">

                        {{-- Teal filter toggle button --}}
                        <button type="button" class="desktop-filter-btn" id="desktopFilterBtn"
                            title="Filter by category">
                            <i class="bi bi-sliders2"></i>
                        </button>

                        {{-- Pill search with teal send button --}}
                        <div class="search-area">
                            <div class="search-wrapper">
                                <div class="search-box">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="searchInput" placeholder="Search products...">
                                    <button type="button" id="searchSubmitBtn" class="search-submit-btn"
                                        title="Search">
                                        <i class="bi bi-send-fill"></i>
                                    </button>
                                </div>

                                {{-- Search dropdown --}}
                                <div class="search-dropdown" id="searchDropdown">
                                    <div class="search-dropdown-left">
                                        <div class="search-section-title">Your Searches</div>
                                        <div id="searchSuggestions"></div>
                                    </div>
                                    <div class="search-dropdown-right">
                                        <div class="search-section-title">Products</div>
                                        <div id="searchPreviewProducts" class="search-preview-products"></div>
                                    </div>
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

                </div>
            </div>

            <div id="messageBox" class="message-box"></div>
            <div id="toast" class="toast" aria-live="polite" aria-atomic="true"></div>

            @if ($items->isEmpty())
                <div class="empty-box">No items found.</div>
            @else
                <div class="products-grid" id="productsGrid">
                    @foreach ($items as $item)
                        @php
                            $normalPrice     = (float) ($item->unit_price ?? 0);
                            $discountPercent = (float) ($item->effective_discount_percent ?? 0);
                            $salePrice       = (float) ($item->final_price ?? $normalPrice);
                            $oldPrice        = $discountPercent > 0 ? $normalPrice : 0;
                            $descText        = $item->short_description ?? '';
                        @endphp

                        <div class="product-card product-item"
                            data-id="{{ $item->id }}"
                            data-detail-url="{{ route('user.pos.product.detail', $item->id) }}"
                            data-name="{{ strtolower($item->display_name ?? '') }}"
                            data-display-name="{{ $item->display_name ?? '' }}"
                            data-desc="{{ strtolower($descText) }}"
                            data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}"
                            data-category="{{ strtolower($item->item_category_code ?? '') }}"
                            data-price="{{ number_format($salePrice, 2, '.', '') }}"
                            data-image="{{ $item->image_url ?: asset('images/no-image.png') }}">

                            <div class="product-img-box">
                                @if ($discountPercent > 0)
                                    <div class="discount-badge">
                                        SAVE {{ rtrim(rtrim(number_format($discountPercent, 2), '0'), '.') }} %
                                    </div>
                                @endif

                                <button class="fav-btn" data-item-id="{{ $item->id }}">
                                    <i class="bi {{ in_array($item->id, $favoriteIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
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

                                <button type="button" class="add-cart-btn mobile-action"
                                    data-id="{{ $item->id }}">
                                    <span class="add-cart-text">Add to cart</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="noSearchResult" class="empty-box" style="display:none; margin-top:16px;">
                    No matching products found.
                </div>
            @endif

        </main>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const els = {
        appShell:              document.getElementById("appShell"),
        collapseHandle:        document.getElementById("collapseHandle"),
        settingsBtn:           document.getElementById("settingsBtn"),
        settingsBox:           document.getElementById("settingsBox"),
        navButtons:            document.querySelectorAll(".nav-btn"),

        cartCount:             document.getElementById("desktopCartCount"),
        mobileCartCount:       document.getElementById("cartCount"),
        asideCartCount:        document.getElementById("asideCartCount"),
        messageBox:            document.getElementById("messageBox"),

        searchInput:           document.getElementById("searchInput"),
        searchDropdown:        document.getElementById("searchDropdown"),
        searchSuggestions:     document.getElementById("searchSuggestions"),
        searchPreviewProducts: document.getElementById("searchPreviewProducts"),
        searchWrapper:         document.querySelector(".search-wrapper"),

        mobileProductFilters:  document.getElementById("mobileProductFilters"),
        mobileSearchInput:     document.getElementById("mobileSearchInput"),
        mobileCategoryRow:     document.querySelector(".mobile-category-row"),

        desktopFilterBtn:      document.getElementById("desktopFilterBtn"),
        desktopCategoryRow:    document.getElementById("desktopCategoryRow"),

        categoryButtons:       [...document.querySelectorAll(".category-filter-btn")],
        noSearchResult:        document.getElementById("noSearchResult"),
        productsGrid:          document.getElementById("productsGrid"),

        productCards:          [...document.querySelectorAll(".product-card")],
        favButtons:            [...document.querySelectorAll(".fav-btn")]
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
            <i class="bi ${iconClass} main-icon"></i>
            <div class="message-content"><strong>${title}</strong> ${text}</div>
            <button type="button" class="close-alert-btn"
                onclick="this.parentElement.classList.remove('show')">
                <i class="bi bi-x"></i>
            </button>`;
        els.messageBox.className = `message-box ${type} show`;
        setTimeout(() => els.messageBox.classList.remove('show'), 4000);
    }

    function showToast(type, text) {
        const toastEl = document.getElementById('toast');
        if (!toastEl) return;
        toastEl.textContent = text;
        toastEl.className = `toast show ${type}`;
        setTimeout(() => { toastEl.className = 'toast'; }, 2500);
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
                         card.querySelector(".product-title")?.textContent?.trim() || "No Name",
            desc:        (card.dataset.desc || "").toLowerCase(),
            category:    (card.dataset.category || "").toLowerCase(),
            uom:         (card.dataset.uom || "").toLowerCase(),
            price:       card.dataset.price || "0.00",
            image:       card.dataset.image || card.querySelector("img")?.src || ""
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
            els.searchSuggestions.innerHTML = `<div class="search-empty">No search history</div>`;
            return;
        }
        els.searchSuggestions.innerHTML = suggestions.map(item => `
            <div class="search-suggestion-item" data-value="${escapeHtml(item)}">
                <div class="search-suggestion-left">
                    <i class="bi bi-clock-history"></i>
                    <span>${escapeHtml(item)}</span>
                </div>
                <div class="search-item-actions">
                    <button type="button" class="delete-history-btn" data-value="${escapeHtml(item)}">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>`).join("");

        els.searchSuggestions.querySelectorAll(".search-suggestion-item").forEach(item => {
            item.addEventListener("click", (e) => {
                if (e.target.closest('.delete-history-btn')) return;
                const value = item.dataset.value || "";
                syncSearchInputs(value);
                addRecentSearch(value);
                filterProducts(value);
                renderSearchPanel(value);
            });
        });
        els.searchSuggestions.querySelectorAll(".delete-history-btn").forEach(btn => {
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
            els.searchPreviewProducts.innerHTML = `<div class="search-empty">No product found</div>`;
            return;
        }
        els.searchPreviewProducts.innerHTML = matchedCards.slice(0, 3).map(card => {
            const data = getCardData(card);
            return `
                <div class="search-preview-card" data-id="${escapeHtml(data.id)}">
                    <img src="${escapeHtml(data.image)}" alt="${escapeHtml(data.displayName)}">
                    <div class="search-preview-info">
                        <div class="search-preview-name">${escapeHtml(data.displayName)}</div>
                        <div class="search-preview-price">$${escapeHtml(data.price)}</div>
                        <button type="button" class="search-preview-btn">View</button>
                    </div>
                </div>`;
        }).join("");

        els.searchPreviewProducts.querySelectorAll(".search-preview-card").forEach(preview => {
            preview.querySelector(".search-preview-btn")?.addEventListener("click", () => {
                const card = els.productCards.find(c => c.dataset.id === preview.dataset.id);
                if (!card) return;
                filterProducts(keyword);
                closeSearchDropdown();
                card.scrollIntoView({ behavior: "smooth", block: "center" });
                card.classList.add("highlight-product");
                setTimeout(() => card.classList.remove("highlight-product"), 1500);
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
                    `<div class="search-empty">Start typing to find products...</div>`;
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

    function bindSidebar() {
        try {
            if (els.settingsBtn && els.settingsBox) {
                els.settingsBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    if (els.appShell?.classList.contains("collapsed")) return;
                    els.settingsBox.classList.toggle("open");
                });
            }
            if (els.navButtons && els.navButtons.length > 0) {
                els.navButtons.forEach(button => {
                    button.addEventListener("click", () => {
                        els.navButtons.forEach(btn => btn.classList.remove("active"));
                        button.classList.add("active");
                    });
                });
            }
        } catch (error) { console.error("Sidebar Error:", error); }
    }

    function bindQuantityButtons() {
        els.productCards.forEach(card => {
            const plusBtn = card.querySelector(".plus");
            const minusBtn = card.querySelector(".minus");
            const qtyEl   = card.querySelector(".qty");
            plusBtn?.addEventListener("click",  () => {
                if (qtyEl) qtyEl.textContent = parseInt(qtyEl.textContent || "0", 10) + 1;
            });
            minusBtn?.addEventListener("click", () => {
                const cur = parseInt(qtyEl?.textContent || "0", 10);
                if (cur > 1 && qtyEl) qtyEl.textContent = cur - 1;
            });
        });
    }

    function bindAddToCart() {
        els.productCards.forEach(card => {
            const addBtn = card.querySelector(".add-cart-btn");
            const qtyEl  = card.querySelector(".qty");
            addBtn?.addEventListener("click", async function () {
                const itemId = this.dataset.id || card.dataset.id;
                const qty    = parseInt(qtyEl?.textContent || "0", 10);
                if (!itemId) { showMessage("error", "Item ID not found."); return; }
                this.disabled = true;
                this.textContent = "Adding...";
                try {
                    const response = await fetch("{{ route('user.pos.cart.add') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({ item_id: itemId, qty: qty })
                    });
                    const data = await response.json();
                    if (data.success) {
                        if (els.cartCount      && data.cartCount !== undefined) els.cartCount.textContent      = data.cartCount;
                        if (els.mobileCartCount && data.cartCount !== undefined) els.mobileCartCount.textContent = data.cartCount;
                        if (els.asideCartCount  && data.cartCount !== undefined) {
                            els.asideCartCount.textContent = data.cartCount;
                            els.asideCartCount.classList.toggle("is-empty", data.cartCount <= 0);
                        }
                        if (qtyEl) qtyEl.textContent = "1";
                        showToast("success", data.message || "Added to cart successfully.");
                    } else {
                        showToast("error", data.message || "Failed to add to cart.");
                    }
                } catch (error) {
                    console.error(error);
                    showMessage("error", "Something went wrong.");
                } finally {
                    this.disabled = false;
                    this.textContent = "Add to cart";
                }
            });
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
            if (els.collapseHandle?.contains(e.target)) return;
            if (!els.searchWrapper?.contains(e.target)) closeSearchDropdown();
        });
    }

    function bindCategoryFilters() {
        els.categoryButtons.forEach(button => {
            button.addEventListener("click", () => {
                selectedCategory = normalizeCategory(button.dataset.category || "");
                const parent = button.closest(".desktop-category-row, .mobile-category-row");
                if (parent) parent.querySelectorAll(".category-filter-btn").forEach(b => b.classList.remove("active"));
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
                if (e.target.closest(".qty-btn, .add-cart-btn, .fav-btn, .search-preview-btn")) return;
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
    bindFavoriteButtons();
    bindSearch();
    bindCategoryFilters();
    bindMobileFilterScroll();
    bindProductDetailNavigation();
});
</script>
@endpush