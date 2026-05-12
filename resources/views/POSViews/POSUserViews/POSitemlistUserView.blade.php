@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS User Item List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/POSsystem/itemlist.css') }}">
@endpush

@section('content')
    <div class="page-wrap">
        <main class="content-area">
            @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            @include('ManagementSystemViews.UserViews.Layouts.footer')


            <div class="header">
                <div class="topbar">
                    <div class="top">
                        <h1 class="title">
                            Products
                        </h1>

                        <a href="{{ route('user.pos.cart') }}" class="cart-box">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-count" id="cartCount">{{ (int) ($cartCount ?? 0) }}</span>
                        </a>
                    </div>

                    <div class="search-area">
                        <div class="search-wrapper">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" id="searchInput" placeholder="Search Product">
                                <button type="button" id="searchSubmitBtn" class="search-submit-btn">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>

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

                <div class="header-actions">
                    <a href="{{ route('user.pos.order.history.mobile') }}" class="header-action-btn active">
                        <i class="bi bi-bag-check"></i>
                        <span>Order</span>
                    </a>
                    <a href="{{ route('user.pos.dashboard_mobile') }}" class="header-action-btn">
                        <i class="bi bi-display"></i>
                        <span>POS System</span>
                    </a>

                    <a href="{{ route('user.notifications') }}" class="header-action-btn">
                        <i class="bi bi-bell"></i>
                        <span>Notification</span>
                    </a>
                </div>

                <div class="hero-slider-wrapper">
                    <div class="hero-slider">

                        <div class="hero-card">
                            <div class="hero-title">New collections is available!</div>
                            <div class="hero-image">
                                <img src="{{ asset('images/pos/Image.png') }}" alt="sofa">
                            </div>
                        </div>

                        <div class="hero-card">
                            <div class="hero-title">New collections is available!</div>
                            <div class="hero-image">
                                <img src="{{ asset('images/pos/Image.png') }}" alt="table">
                            </div>
                        </div>

                        <div class="hero-card">
                            <div class="hero-title">New collections is available!</div>
                            <div class="hero-image">
                                <img src="{{ asset('images/pos/Image.png') }}" alt="sofa">
                            </div>
                        </div>

                    </div>
                </div>
                <a href="#" class="hero-link">
                    Learn more <i class="bi bi-arrow-right"></i>
                </a>
                <h4>Product</h4>




            </div>

            <div id="messageBox" class="message-box"></div>

            @if ($items->isEmpty())
                <div class="empty-box">No items found.</div>
            @else
                <div class="products-grid" id="productsGrid">
                    @foreach ($items as $item)
                        @php
                            $normalPrice = (float) ($item->unit_price ?? 0);
                            $discountPercent = (float) ($item->effective_discount_percent ?? 0);
                            $salePrice = (float) ($item->final_price ?? $normalPrice);
                            $oldPrice = $discountPercent > 0 ? $normalPrice : 0;
                            $descText = $item->short_description ?? '';
                        @endphp

                        <div class="product-card product-item" data-id="{{ $item->id }}"
                            data-detail-url="{{ route('user.pos.product.detail', $item->id) }}"
                            data-name="{{ strtolower($item->display_name ?? '') }}"
                            data-display-name="{{ $item->display_name ?? '' }}"
                            data-desc="{{ strtolower($descText ?? '') }}"
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
                                    <i
                                        class="bi {{ in_array($item->id, $favoriteIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                </button>

                                <img src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                    alt="{{ $item->display_name ?? 'No Name' }}" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                            </div>

                            <div class="product-info">
                                <div class="product-title">
                                    {{ $item->display_name ?: 'No Name' }}
                                </div>

                                {{-- <div class="product-desc">
                                        {{ $descText }}
                                    </div> --}}

                                <div class="price-row {{ $oldPrice > $salePrice ? 'has-discount' : 'no-discount' }}">
                                    <div class="old-price">
                                        @if ($oldPrice > $salePrice)
                                            ${{ number_format($oldPrice, 2) }}
                                        @endif
                                    </div>

                                    <div class="new-price">
                                        ${{ number_format($salePrice, 2) }}
                                    </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            const slider = document.querySelector(".hero-slider");
            const cards = document.querySelectorAll(".hero-card");

            let index = 0;

            setInterval(() => {
                index++;
                if (index >= cards.length) {
                    index = 0;
                }

                slider.style.transform = `translateX(-${index * 100}%)`;
            }, 3000);
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const els = {
                appShell: document.getElementById("appShell"),
                collapseHandle: document.getElementById("collapseHandle"),
                settingsBtn: document.getElementById("settingsBtn"),
                settingsBox: document.getElementById("settingsBox"),
                navButtons: document.querySelectorAll(".nav-btn"),

                cartCount: document.getElementById("cartCount"),
                messageBox: document.getElementById("messageBox"),

                searchInput: document.getElementById("searchInput"),
                searchDropdown: document.getElementById("searchDropdown"),
                searchSuggestions: document.getElementById("searchSuggestions"),
                searchPreviewProducts: document.getElementById("searchPreviewProducts"),
                searchWrapper: document.querySelector(".search-wrapper"),
                noSearchResult: document.getElementById("noSearchResult"),

                productCards: [...document.querySelectorAll(".product-card")],
                favButtons: [...document.querySelectorAll(".fav-btn")]
            };

            let recentSearches = JSON.parse(localStorage.getItem("pos_recent_searches")) || [
                "premium beef", "beef steak", "meat"
            ];

            function saveSearchHistory() {
                localStorage.setItem("pos_recent_searches", JSON.stringify(recentSearches));
            }

            // function showMessage(type, text) {
            //     if (!els.messageBox) return;

            //     const iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-octagon-fill';
            //     const title = type === 'success' ? 'Success!' : 'Error!';

            //     els.messageBox.innerHTML = `
            //         <i class="bi ${iconClass} main-icon"></i>
            //         <div class="message-content">
            //             <strong>${title}</strong> ${text}
            //         </div>
            //         <button type="button" class="close-alert-btn" onclick="this.parentElement.classList.remove('show')">
            //             <i class="bi bi-x"></i>
            //         </button>
            //     `;

            //     els.messageBox.className = `message-box ${type} show`;

            //     setTimeout(() => {
            //         els.messageBox.classList.remove('show');
            //     }, 4000);
            // }

            function escapeHtml(text = "") {
                const div = document.createElement("div");
                div.textContent = text;
                return div.innerHTML;
            }

            function getCardData(card) {
                return {
                    id: card.dataset.id || "",
                    name: (card.dataset.name || "").toLowerCase(),
                    displayName: card.dataset.displayName || card.querySelector(".product-title")?.textContent
                        ?.trim() || "No Name",
                    desc: (card.dataset.desc || "").toLowerCase(),
                    category: (card.dataset.category || "").toLowerCase(),
                    uom: (card.dataset.uom || "").toLowerCase(),
                    price: card.dataset.price || "0.00",
                    image: card.dataset.image || card.querySelector("img")?.src || ""
                };
            }

            function matchCard(card, keyword) {
                const text = keyword.trim().toLowerCase();
                if (!text) return true;

                const data = getCardData(card);

                return [
                    data.name,
                    data.displayName.toLowerCase(),
                    data.desc,
                    data.category,
                    data.uom
                ].some(value => value.includes(text));
            }

            function filterProducts(keyword = "") {
                let visibleCount = 0;

                els.productCards.forEach(card => {
                    const matched = matchCard(card, keyword);
                    card.style.display = matched ? "" : "none";
                    if (matched) visibleCount++;
                });

                if (els.noSearchResult) {
                    els.noSearchResult.style.display = visibleCount ? "none" : "block";
                }
            }

            function getMatchedCards(keyword) {
                if (!keyword.trim()) return [];
                return els.productCards.filter(card => matchCard(card, keyword));
            }

            function addRecentSearch(keyword) {
                const value = keyword.trim().toLowerCase();
                if (!value) return;

                recentSearches = recentSearches.filter(item => item !== value);
                recentSearches.unshift(value);
                recentSearches = recentSearches.slice(0, 8);

                saveSearchHistory();
            }

            function closeSearchDropdown() {
                els.searchDropdown?.classList.remove("show");
            }

            function openSearchDropdown() {
                els.searchDropdown?.classList.add("show");
            }

            function removeRecentSearch(keyword) {
                recentSearches = recentSearches.filter(item => item !== keyword);
                localStorage.setItem("pos_recent_searches", JSON.stringify(recentSearches));
                renderSearchPanel(els.searchInput.value);
            }

            function renderSuggestions(keyword) {
                if (!els.searchSuggestions) return;

                const text = keyword.trim().toLowerCase();
                let suggestions = text ?
                    recentSearches.filter(item => item.includes(text)) :
                    recentSearches;

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
                    </div>
                `).join("");

                els.searchSuggestions.querySelectorAll(".search-suggestion-item").forEach(item => {
                    item.addEventListener("click", (e) => {
                        if (e.target.closest('.delete-history-btn')) return;

                        const value = item.dataset.value || "";
                        els.searchInput.value = value;
                        addRecentSearch(value);
                        filterProducts(value);
                        renderSearchPanel(value);
                    });
                });

                els.searchSuggestions.querySelectorAll(".delete-history-btn").forEach(btn => {
                    btn.addEventListener("click", (e) => {
                        e.stopPropagation();
                        const valueToDelete = btn.dataset.value;
                        removeRecentSearch(valueToDelete);
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
                        </div>
                    `;
                }).join("");

                els.searchPreviewProducts.querySelectorAll(".search-preview-card").forEach(preview => {
                    preview.querySelector(".search-preview-btn")?.addEventListener("click", () => {
                        const id = preview.dataset.id;
                        const card = els.productCards.find(item => item.dataset.id === id);
                        if (!card) return;

                        filterProducts(keyword);
                        closeSearchDropdown();

                        card.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });

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
                    if (els.searchPreviewProducts) {
                        els.searchPreviewProducts.innerHTML =
                            `<div class="search-empty">Start typing to find products...</div>`;
                    }
                    filterProducts("");
                }

                openSearchDropdown();
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
                } catch (error) {
                    console.error("Sidebar Error:", error);
                }
            }

            function bindQuantityButtons() {
                els.productCards.forEach(card => {
                    const plusBtn = card.querySelector(".plus");
                    const minusBtn = card.querySelector(".minus");
                    const qtyEl = card.querySelector(".qty");

                    plusBtn?.addEventListener("click", () => {
                        const qty = parseInt(qtyEl?.textContent || "0", 10) + 1;
                        if (qtyEl) qtyEl.textContent = qty;
                    });

                    minusBtn?.addEventListener("click", () => {
                        const currentQty = parseInt(qtyEl?.textContent || "0", 10);
                        if (currentQty > 1 && qtyEl) {
                            qtyEl.textContent = currentQty - 1;
                        }
                    });
                });
            }

            function bindAddToCart() {
                els.productCards.forEach(card => {
                    const addBtn = card.querySelector(".add-cart-btn");
                    const qtyEl = card.querySelector(".qty");

                    addBtn?.addEventListener("click", async function() {
                        const itemId = this.dataset.id || card.dataset.id;
                        const qty = parseInt(qtyEl?.textContent || "0", 10);

                        if (!itemId) {
                            showMessage("error", "Item ID not found.");
                            return;
                        }

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
                                body: JSON.stringify({
                                    item_id: itemId,
                                    qty: qty
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                if (els.cartCount && data.cartCount !== undefined) {
                                    els.cartCount.textContent = data.cartCount;
                                }

                                if (qtyEl) qtyEl.textContent = "1";
                                showMessage("success", data.message ||
                                    "Added to cart successfully.");
                            } else {
                                showMessage("error", data.message || "Failed to add to cart.");
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
                    button.addEventListener("click", async function() {
                        const itemId = this.dataset.itemId;
                        const icon = this.querySelector("i");

                        if (!itemId) return;

                        try {
                            const response = await fetch(
                                "{{ route('user.pos.favorite.toggle') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": csrfToken,
                                        "Accept": "application/json"
                                    },
                                    body: JSON.stringify({
                                        item_id: itemId
                                    })
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
                if (!els.searchInput) return;

                els.searchInput.addEventListener("focus", () => {
                    renderSearchPanel(els.searchInput.value);
                });

                els.searchInput.addEventListener("input", e => {
                    renderSearchPanel(e.target.value);
                });

                els.searchInput.addEventListener("keydown", e => {
                    if (e.key === "Enter") {
                        const value = e.target.value.trim();
                        if (value) {
                            addRecentSearch(value);
                            closeSearchDropdown();
                        }
                    }
                });

                document.getElementById("searchSubmitBtn")?.addEventListener("click", () => {
                    const value = els.searchInput.value.trim();
                    if (value) {
                        addRecentSearch(value);
                        filterProducts(value);
                        closeSearchDropdown();
                    }
                });

                document.addEventListener("click", e => {
                    if (els.collapseHandle?.contains(e.target)) return;

                    if (!els.searchWrapper?.contains(e.target)) {
                        closeSearchDropdown();
                    }
                });
            }

            function bindProductDetailNavigation() {
                els.productCards.forEach(card => {

                    card.addEventListener("click", (e) => {

                        // ✅ Ignore inner buttons (keep existing behavior)
                        if (e.target.closest(
                                ".qty-btn, .add-cart-btn, .fav-btn, .search-preview-btn"
                            )) {
                            return;
                        }

                        // ✅ MOBILE ONLY
                        const isMobile = window.matchMedia("(max-width: 768px)").matches;

                        if (!isMobile) {
                            // ❌ Desktop / POS screen → do nothing
                            return;
                        }

                        const detailUrl = card.dataset.detailUrl;
                        if (!detailUrl) return;

                        window.location.href = detailUrl;
                    });
                });
            }
            bindSidebar();
            bindQuantityButtons();
            bindAddToCart();
            bindFavoriteButtons();
            bindSearch();
            bindProductDetailNavigation();
        });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const addBtn = document.getElementById("addToCartBtn");
        const cartCountEl = document.getElementById("cartCount");
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (!addBtn) return;

        addBtn.addEventListener("click", async function() {
            const itemId = this.dataset.id;

            if (!itemId) {
                alert("Item ID not found.");
                return;
            }

            this.disabled = true;
            this.querySelector(".add-cart-text").textContent = "Adding...";

            try {
                const response = await fetch("{{ route('user.pos.cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        qty: 1
                    })
                });

                const data = await response.json();

                if (data.success) {
                    if (cartCountEl && data.cartCount !== undefined) {
                        cartCountEl.textContent = data.cartCount;
                    }
                    showToast("success", data.message || "Added to cart successfully");
                } else {
                    showToast("error", data.message || "Failed to add to cart");
                }
            } catch (error) {
                console.error(error);
                showToast("error", "Something went wrong.");
            } finally {
                this.disabled = false;
                this.querySelector(".add-cart-text").textContent = "Add to cart";
            }
        });
    });
</script>
@endpush
