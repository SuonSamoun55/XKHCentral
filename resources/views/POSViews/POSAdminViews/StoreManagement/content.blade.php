<div class="store-header-row">
        <h2 class="store-page-title">Manage Store</h2>
    </div>

    <div class="store-top-tools">
        <div class="store-toolbar-row">

            <div class="store-toolbar-left">
                <div class="store-tab-switcher-inline">
                    <button
                        type="button"
                        class="store-tab-btn js-store-tab active"
                        data-tab="products"
                    >
                        Product ({{ $productCount }})
                    </button>

                    <button
                        type="button"
                        class="store-tab-btn js-store-tab"
                        data-tab="categories"
                    >
                        Categories ({{ $categoryCount }})
                    </button>
                </div>
            </div>

            <div class="store-toolbar-right">
                <div class="search-input-box">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        id="storeSearchInput"
                        placeholder="Search product or category..."
                        autocomplete="off"
                    >
                </div>

                <div class="store-status-menu-group">
                    <select id="storeStatusFilter" class="store-select-control status-filter-select">
                        <option value="all">All Status</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Inactive Only</option>
                        <option value="not_setup">Not Setup</option>
                    </select>

                    <select id="storeSellingLocation" class="store-select-control" title="Selling location — only this location's stock is shown and sold to customers">
                        <option value="">— Not set (using total stock) —</option>
                        @foreach ($sellingLocations as $loc)
                            <option value="{{ $loc->location_code }}" {{ optional($storeSetting)->selling_location_code === $loc->location_code ? 'selected' : '' }}>
                                {{ $loc->location_name ?: 'Unassigned' }}{{ $loc->location_code ? ' (' . $loc->location_code . ')' : '' }}
                            </option>
                        @endforeach
                    </select>

                    <div class="store-menu-wrap" id="storeMenuWrap">
                        <button type="button" class="store-menu-trigger" id="storeMenuTrigger" title="Open menu">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>

                        <div class="store-menu-panel d-none" id="storeMenuPanel">
                        <div class="store-menu-item stock-filter-wrap">
                            <label for="storeStockFilter" class="store-menu-label">All Stock</label>
                            <select id="storeStockFilter" class="store-select-control">
                                <option value="all">All Stock</option>
                                <option value="in">In Stock</option>
                                <option value="out">Out of Stock</option>
                            </select>
                        </div>

                        <div class="store-menu-item setup-filter-wrap">
                            <label for="storeSetupFilter" class="store-menu-label">All Setup</label>
                            <select id="storeSetupFilter" class="store-select-control">
                                <option value="all">All Setup</option>
                                <option value="complete">Image Updated</option>
                                <option value="incomplete">Not Updated Yet</option>
                            </select>
                        </div>

                        <a
                            href="{{ route('store.management.tracking') }}"
                            class="store-action-btn btn-active-custom store-menu-btn"
                            style="text-decoration:none;"
                        >
                            <i class="bi bi-activity"></i>
                            Track Stock
                        </a>

                        <button
                            type="button"
                            class="store-action-btn btn-active-custom products-only-btn store-menu-btn js-bulk-action"
                            data-scope="product"
                            data-action="activate"
                            data-url="{{ route('store.management.products.bulkUpdate') }}"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Activate
                        </button>

                        <button
                            type="button"
                            class="store-action-btn btn-inactive-custom products-only-btn store-menu-btn js-bulk-action"
                            data-scope="product"
                            data-action="deactivate"
                            data-url="{{ route('store.management.products.bulkUpdate') }}"
                        >
                            <i class="bi bi-x-circle"></i>
                            Deactivate
                        </button>

                        <button
                            type="button"
                            class="store-action-btn btn-active-custom categories-only-btn store-menu-btn js-bulk-action d-none"
                            data-scope="category"
                            data-action="activate"
                            data-url="{{ route('store.management.categories.bulkUpdate') }}"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Activate
                        </button>

                        <button
                            type="button"
                            class="store-action-btn btn-inactive-custom categories-only-btn store-menu-btn js-bulk-action d-none"
                            data-scope="category"
                            data-action="deactivate"
                            data-url="{{ route('store.management.categories.bulkUpdate') }}"
                        >
                            <i class="bi bi-x-circle"></i>
                            Deactivate
                        </button>
                        </div>
                    </div>
                </div>

                <label class="store-select-all-inline">
                    <input type="checkbox" class="js-select-all-products row-check-input">
                    <span>Select all</span>
                </label>
            </div>
        </div>
    </div>

    <div id="productsTabContent" class="store-tab-content">
        <div class="table-scroll-wrap store-table-scroll">
            <table class="manage-store-table">
                <thead>
                    <tr>
                        <th class="col-check">
                            <input type="checkbox" class="js-select-all-products row-check-input">
                        </th>
                        <th>Product</th>
                        <th>Item No</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @forelse($products as $item)
                        @php
                            // Null is_visible = just synced, not reviewed yet.
                            $visibilityStatus = is_null($item->is_visible)
                                ? 'not_setup'
                                : ($item->is_visible ? 'active' : 'inactive');
                        @endphp
                        <tr
                            class="product-row"
                            data-name="{{ strtolower($item->display_name ?? '') }}"
                            data-number="{{ strtolower($item->number ?? '') }}"
                            data-category="{{ strtolower($item->item_category_code ?? '') }}"
                            data-status="{{ $visibilityStatus }}"
                            data-stock="{{ (int) $item->sellable_inventory > 0 ? 'in' : 'out' }}"
                            data-setup="{{ ($item->main_image_done && $item->variants_done) ? 'complete' : 'incomplete' }}"
                            data-href="{{ route('store.management.products.detail', $item->id) }}"
                        >
                            <td class="col-check">
                                <input type="checkbox" value="{{ $item->id }}" class="product-checkbox row-check-input">
                            </td>

                            <td>
                                <div class="product-cell">
                                    <div class="product-thumb-box">
                                        @if(!empty($item->custom_image_url) || !empty($item->image_url))
                                            <img
                                                src="{{ $item->resolved_image_url }}"
                                                alt="{{ $item->display_name }}"
                                                onerror="this.onerror=null;this.parentElement.innerHTML='<div class=&quot;thumb-placeholder&quot;><i class=&quot;bi bi-image&quot;></i></div>';"
                                            >
                                        @else
                                            <div class="thumb-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-text-box">
                                        <div class="product-main-name">{{ $item->display_name ?: 'No Name' }}</div>
                                        <div class="product-sub-line">{{ $item->number ?: '-' }}</div>

                                        <div class="product-mobile-meta">
                                            ${{ number_format((float) $item->unit_price, 2) }} &bull; {{ (int) $item->sellable_inventory }} in stock
                                        </div>

                                        <div class="product-mobile-status {{ $visibilityStatus }}">
                                            {{ is_null($item->is_visible) ? 'Not Setup' : ($item->is_visible ? 'Active' : 'Inactive') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $item->number ?: '-' }}</td>
                            <td>{{ $item->item_category_code ?: '-' }}</td>
                            <td>${{ number_format((float) $item->unit_price, 2) }}</td>
                            <td>{{ (int) $item->sellable_inventory }}</td>
                            <td>
                                @if (optional($storeSetting)->selling_location_code)
                                    <div style="font-size:12px; white-space:nowrap;">{{ $storeSetting->selling_location_name ?: $storeSetting->selling_location_code }}</div>
                                @else
                                    <span style="color:#9ca3af;">No location selected</span>
                                @endif
                            </td>

                            <td>
                                <div class="status-action-wrap">
                                    <button
                                        type="button"
                                        class="toggle-switch js-toggle-product {{ is_null($item->is_visible) ? 'not-setup' : ($item->is_visible ? 'on' : 'off') }}"
                                        data-url="{{ route('store.management.products.toggle', $item->id) }}"
                                        title="{{ is_null($item->is_visible) ? 'Not set up yet — click to activate' : 'Toggle product visibility' }}"
                                    >
                                        <span class="toggle-dot"></span>
                                    </button>

                                    <a href="{{ route('store.management.products.detail', $item->id) }}" class="store-action-btn btn-active-custom" style="height:30px; padding:0 10px; font-size:12px; text-decoration:none;">
                                        <i class="bi bi-eye"></i> <span class="btn-text">View Detail</span>
                                    </a>

                                    <a href="{{ route('store.management.product.images', $item->id) }}" class="store-action-btn btn-active-custom" style="height:30px; padding:0 10px; font-size:12px; text-decoration:none;">
                                        <i class="bi bi-image"></i> <span class="btn-text">Update</span>
                                    </a>
                                </div>

                                <div class="setup-badges-row" style="display:flex; gap:4px; margin-top:6px; flex-wrap:wrap;">
                                    @if($item->main_image_done)
                                        <span style="font-size:10px; font-weight:700; padding:2px 6px; border-radius:4px; background:#d1fae5; color:#065f46;">
                                            <i class="bi bi-check-circle"></i> Image Setup
                                        </span>
                                    @endif

                                    @if($item->variants_done)
                                        <span style="font-size:10px; font-weight:700; padding:2px 6px; border-radius:4px; background:#d1fae5; color:#065f46;">
                                            <i class="bi bi-check-circle"></i> Variants Setup
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noProductRow">
                            <td colspan="8">
                                <div class="empty-state-box">No products found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="store-footer-bar">
            <div class="store-footer-left">
                <div class="selected-box">
                    Selected
                    <span class="js-selected-product-count">0</span>
                </div>

                <div class="footer-show-box">
                    <label for="storePerPage">Show</label>
                    <select id="storePerPage" class="store-footer-select">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="99999">All</option>
                    </select>
                    <span>items</span>
                </div>
            </div>

            <div class="store-footer-center">
                <div id="productPagination" class="custom-pagination-wrap"></div>
            </div>

            <div class="store-footer-right">
                <span class="footer-result-text" id="productShowingText">Showing 0 items</span>
            </div>
        </div>
    </div>

    <div id="categoriesTabContent" class="store-tab-content d-none">
        <div class="category-list-grid" id="categoryListGrid">
            @forelse($categories as $category)
                <div
                    class="category-item-card category-card"
                    data-name="{{ strtolower($category->item_category_code ?? '') }}"
                    data-status="{{ $category->category_visible ? 'active' : 'inactive' }}"
                >
                    <div class="category-left-wrap">
                        <input
                            type="checkbox"
                            value="{{ $category->item_category_code }}"
                            class="category-checkbox row-check-input"
                        >

                        <div class="category-text-wrap">
                            <div class="category-title-text">{{ $category->item_category_code }}</div>
                            <div class="category-sub-text">{{ $category->total_items }} item(s)</div>
                        </div>
                    </div>

                    <div class="category-right-wrap">
                        <button
                            type="button"
                            class="toggle-switch js-toggle-category {{ $category->category_visible ? 'on' : 'off' }}"
                            data-url="{{ route('store.management.categories.toggle', $category->item_category_code) }}"
                            title="Toggle category visibility"
                        >
                            <span class="toggle-dot"></span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state-box" id="noCategoryCard">No categories found.</div>
            @endforelse
        </div>

        <div class="store-footer-bar">
            <div class="store-footer-left">
                <div class="selected-box">
                    Selected
                    <span class="js-selected-category-count">0</span>
                </div>

                <div class="footer-show-box">
                    <label for="storePerPageCategory">Show</label>
                    <select id="storePerPageCategory" class="store-footer-select">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="99999">All</option>
                    </select>
                    <span>items</span>
                </div>
            </div>

            <div class="store-footer-center">
                <div id="categoryPagination" class="custom-pagination-wrap"></div>
            </div>

            <div class="store-footer-right">
                <span class="footer-result-text" id="categoryShowingText">Showing 0 items</span>
            </div>
        </div>
    </div>


