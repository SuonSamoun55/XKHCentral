<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS User Item List</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link rel="stylesheet" href="{{ asset('css/POSsystem/itemlist.css') }}">

</head>

<body>

    <div class="app-shell" id="appShell">

        {{-- Sidebar --}}
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        {{-- Page Content --}}
        <div class="page-wrap">
            <main class="content-area">
                <div class="header">

                    <div class="top">

                        <h1 class="title">
                            @include('ManagementSystemViews.UserViews.Layouts.header', [
                                'title' => 'Products',
                            ])
                        </h1>

                        <a href="{{ route('user.pos.cart') }}" class="cart-box">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-count" id="cartCount">0</span>
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

                <div id="messageBox" class="message-box"></div>

                @if ($items->isEmpty())
                    <div class="empty-box">No items found.</div>
                @else
                    <div class="products-grid" id="productsGrid">
                        @foreach ($items as $item)
                            <div class="product-card product-item" data-id="{{ $item->id }}"
                                data-name="{{ strtolower($item->display_name ?? '') }}"
                                data-display-name="{{ $item->display_name ?? '' }}"
                                data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}"
                                data-category="{{ strtolower($item->item_category_code ?? '') }}"
                                data-price="{{ number_format($item->unit_price ?? 0, 2, '.', '') }}"
                                data-image="{{ $item->image_url ?: asset('images/no-image.png') }}">

                                <div class="product-img-box">
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
                    {{ $item->descriptio    n ?: ($item->base_unit_of_measure_code ?: 'No description') }}
                </div> --}}

                                    <div class="price">
                                        ${{ number_format($item->unit_price ?? 0, 2) }}
                                    </div>

                                    <div class="qty-section">
                                        <span>Quantity:</span>
                                        <div class="qty-box">
                                            <button type="button" class="qty-btn minus">−</button>
                                            <span class="qty">1</span>
                                            <button type="button" class="qty-btn plus">+</button>
                                        </div>
                                    </div>

                                    <button type="button" class="add-cart-btn" data-id="{{ $item->id }}">
                                        Add to cart
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

    </div>
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

            // Replace the old hardcoded recentSearches with this logic:
            let recentSearches = JSON.parse(localStorage.getItem("pos_recent_searches")) || [
                "premium beef", "beef steak", "meat" // Default suggestions if history is empty
            ];

            // Helper function to save history to the browser
            function saveSearchHistory() {
                localStorage.setItem("pos_recent_searches", JSON.stringify(recentSearches));
            }

            function showMessage(type, text) {
                if (!els.messageBox) return;

                // Choose icon based on type
                const iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-octagon-fill';
                const title = type === 'success' ? 'Success!' : 'Error!';

                // Set the HTML structure
                els.messageBox.innerHTML = `
        <i class="bi ${iconClass} main-icon"></i>
        <div class="message-content">
            <strong>${title}</strong> ${text}
        </div>
        <button type="button" class="close-alert-btn" onclick="this.parentElement.classList.remove('show')">
            <i class="bi bi-x"></i>
        </button>
    `;

                // Apply classes and show
                els.messageBox.className = `message-box ${type} show`;

                // Auto-hide after 4 seconds
                setTimeout(() => {
                    els.messageBox.classList.remove('show');
                }, 4000);
            }

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

                // Remove the word if it already exists (to move it to the top)
                recentSearches = recentSearches.filter(item => item !== value);

                // Add to the beginning of the array
                recentSearches.unshift(value);

                // Keep only the last 8 searches
                recentSearches = recentSearches.slice(0, 8);

                saveSearchHistory(); // Commit to localStorage
            }

            function closeSearchDropdown() {
                els.searchDropdown?.classList.remove("show");
            }

            function openSearchDropdown() {
                els.searchDropdown?.classList.add("show");
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

                // Event: Click on the text to SEARCH
                els.searchSuggestions.querySelectorAll(".search-suggestion-item").forEach(item => {
                    item.addEventListener("click", (e) => {
                        // Important: Don't search if they clicked the delete button!
                        if (e.target.closest('.delete-history-btn')) return;

                        const value = item.dataset.value || "";
                        els.searchInput.value = value;
                        addRecentSearch(value);
                        filterProducts(value);
                        renderSearchPanel(value);
                    });
                });

                // Event: Click on the "X" to DELETE
                els.searchSuggestions.querySelectorAll(".delete-history-btn").forEach(btn => {
                    btn.addEventListener("click", (e) => {
                        e.stopPropagation(); // Prevents the search from triggering
                        const valueToDelete = btn.dataset.value;
                        removeRecentSearch(valueToDelete);
                    });
                });
            }

            function removeRecentSearch(keyword) {
                // Filter out the item
                recentSearches = recentSearches.filter(item => item !== keyword);

                // Save the new shorter list to localStorage
                localStorage.setItem("pos_recent_searches", JSON.stringify(recentSearches));

                // Re-render the panel immediately so the item disappears
                renderSearchPanel(els.searchInput.value);
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

                // Always render suggestions (History)
                renderSuggestions(value);

                // Only show product previews if the user has actually typed something
                if (value) {
                    renderPreviewProducts(value);
                    filterProducts(value);
                } else {
                    if (els.searchPreviewProducts) {
                        els.searchPreviewProducts.innerHTML =
                            `<div class="search-empty">Start typing to find products...</div>`;
                    }
                    filterProducts(""); // Show all products if search is cleared
                }

                openSearchDropdown(); // Keep it open to show history
            }

            function bindSidebar() {
                try {
                    // Note: The collapse button is already handled by aside.blade.php script
                    // We don't re-bind it here to avoid conflicts

                    // Only bind the settings if needed
                    if (els.settingsBtn && els.settingsBox) {
                        els.settingsBtn.addEventListener("click", (e) => {
                            e.preventDefault();
                            if (els.appShell?.classList.contains("collapsed")) return;
                            els.settingsBox.classList.toggle("open");
                        });
                    }

                    // Nav Buttons
                    if (els.navButtons && els.navButtons.length > 0) {
                        els.navButtons.forEach(button => {
                            button.addEventListener("click", () => {
                                els.navButtons.forEach(btn => btn.classList.remove("active"));
                                button.classList.add("active");
                            });
                        });
                    }

                    console.log("Sidebar elements ready.");
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
                        const qty = parseInt(qtyEl?.textContent || "1", 10) + 1;
                        if (qtyEl) qtyEl.textContent = qty;
                    });

                    minusBtn?.addEventListener("click", () => {
                        const currentQty = parseInt(qtyEl?.textContent || "1", 10);
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
                        const qty = parseInt(qtyEl?.textContent || "1", 10);

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

                // 1. Show history immediately when the user clicks into the search box
                els.searchInput.addEventListener("focus", () => {
                    renderSearchPanel(els.searchInput.value);
                });

                // 2. Update as they type
                els.searchInput.addEventListener("input", e => {
                    renderSearchPanel(e.target.value);
                });

                // 3. Handle the "Enter" key
                els.searchInput.addEventListener("keydown", e => {
                    if (e.key === "Enter") {
                        const value = e.target.value.trim();
                        if (value) {
                            addRecentSearch(value);
                            closeSearchDropdown(); // Close after searching
                        }
                    }
                });

                // 4. Handle the "Send" button click (the icon next to the input)
                document.getElementById("searchSubmitBtn")?.addEventListener("click", () => {
                    const value = els.searchInput.value.trim();
                    if (value) {
                        addRecentSearch(value);
                        filterProducts(value);
                        closeSearchDropdown();
                    }
                });

                // 5. Close dropdown if clicking outside (but ignore sidebar/collapse buttons)
                document.addEventListener("click", e => {
                    // Don't interfere with sidebar collapse button or its children
                    if (els.collapseHandle?.contains(e.target)) return;

                    if (!els.searchWrapper?.contains(e.target)) {
                        closeSearchDropdown();
                    }
                });
            }

            bindSidebar();
            bindQuantityButtons();
            bindAddToCart();
            bindFavoriteButtons();
            bindSearch();
        });
    </script>

</body>

</html>
