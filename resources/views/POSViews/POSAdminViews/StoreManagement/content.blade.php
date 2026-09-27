<div class="store-header-row">
    <h2 class="store-page-title">Store Management</h2>
</div>

<div class="store-top-tools">
    <div class="store-toolbar-row">

        <div class="store-toolbar-left">
            <div class="store-tab-switcher-inline">
                <button type="button" class="store-tab-btn js-store-tab active" data-tab="products">
                    Product ({{ $productCount }})
                </button>

                <button type="button" class="store-tab-btn js-store-tab" data-tab="categories">
                    Categories ({{ $categoryCount }})
                </button>
            </div>
        </div>

        <div class="store-toolbar-right">
            <div class="search-input-box">
                <i class="bi bi-search"></i>
                <input type="text" id="storeSearchInput" placeholder="Search product or category"
                    autocomplete="off">
            </div>

            <div class="store-status-menu-group">
                <div class="store-menu-wrap" id="storeMenuWrap">
                    <button type="button" class="store-menu-trigger" id="storeMenuTrigger" title="Open menu">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>

                    <div class="store-menu-panel d-none" id="storeMenuPanel">
                        <div class="store-menu-item">
                            <label for="storeStatusFilter" class="store-menu-label">Status</label>
                            <select id="storeStatusFilter" class="store-select-control status-filter-select">
                                <option value="all">All Status</option>
                                <option value="active">Active Only</option>
                                <option value="inactive">Inactive Only</option>
                                <option value="not_setup">Not Reviewed</option>
                            </select>
                        </div>

                        <div class="store-menu-item products-only-btn">
                            <label for="storeSellingLocation" class="store-menu-label">Selling location</label>
                            <select id="storeSellingLocation"
                                class="store-select-control {{ $sellingLocations->isEmpty() ? 'no-options' : '' }}"
                                title="Selling location — only this location's stock is shown and sold to customers">
                                <option value="">— Not set (using total stock) —</option>
                                @foreach ($sellingLocations as $loc)
                                    <option value="{{ $loc->location_code }}"
                                        {{ optional($storeSetting)->selling_location_code === $loc->location_code ? 'selected' : '' }}>
                                        {{ $loc->location_name ?: 'Unassigned' }}{{ $loc->location_code ? ' (' . $loc->location_code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="store-menu-item" style="border-bottom:1px solid #e5e7eb; padding-bottom:8px; margin-bottom:4px;">
                            <button type="button" class="store-action-btn btn-active-custom store-menu-btn" id="storeExportBtn"
                                data-bs-toggle="modal" data-bs-target="#exportStoreModal">
                                <i class="bi bi-download"></i>
                                Download as Excel
                            </button>
                        </div>

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
                        <button type="button"
                            class="store-action-btn btn-active-custom products-only-btn store-menu-btn js-bulk-action"
                            data-scope="product" data-action="activate"
                            data-url="{{ route('store.management.products.bulkUpdate') }}">
                            <i class="bi bi-check2-circle"></i>
                            Activate
                        </button>

                        <button type="button"
                            class="store-action-btn btn-inactive-custom products-only-btn store-menu-btn js-bulk-action"
                            data-scope="product" data-action="deactivate"
                            data-url="{{ route('store.management.products.bulkUpdate') }}">
                            <i class="bi bi-x-circle"></i>
                            Deactivate
                        </button>

                        <button type="button"
                            class="store-action-btn btn-active-custom categories-only-btn store-menu-btn js-bulk-action d-none"
                            data-scope="category" data-action="activate"
                            data-url="{{ route('store.management.categories.bulkUpdate') }}">
                            <i class="bi bi-check2-circle"></i>
                            Activate
                        </button>

                        <button type="button"
                            class="store-action-btn btn-inactive-custom categories-only-btn store-menu-btn js-bulk-action d-none"
                            data-scope="category" data-action="deactivate"
                            data-url="{{ route('store.management.categories.bulkUpdate') }}">
                            <i class="bi bi-x-circle"></i>
                            Deactivate
                        </button>

                        <div class="store-menu-item products-only-btn"
                            style="border-top:1px solid #e5e7eb; padding-top:8px; margin-top:4px;">
                            <label class="store-menu-label">Oversell (out-of-stock products only)</label>
                            <button type="button"
                                class="store-action-btn btn-active-custom store-menu-btn js-bulk-oversell"
                                data-action="open"
                                data-url="{{ route('store.management.products.oversell.bulkOutOfStock') }}">
                                <i class="bi bi-unlock"></i>
                                Open All
                            </button>

                            <button type="button"
                                class="store-action-btn btn-inactive-custom store-menu-btn js-bulk-oversell"
                                data-action="close"
                                data-url="{{ route('store.management.products.oversell.bulkOutOfStock') }}">
                                <i class="bi bi-lock"></i>
                                Close All
                            </button>
                        </div>
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
                    <th class="col-th-series">Series</th>
                    <th class="col-th-product">Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Visible</th>
                    <th>Oversell</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                @forelse($products as $item)
                    @php
                        // Null is_visible = just synced, not reviewed yet.
                        $visibilityStatus = is_null($item->is_visible)
                            ? 'not_setup'
                            : ($item->is_visible
                                ? 'active'
                                : 'inactive');
                        $visState = is_null($item->is_visible) ? 'not-setup' : ($item->is_visible ? 'on' : 'off');
                        $locationLabel = optional($storeSetting)->selling_location_code
                            ? ($storeSetting->selling_location_name ?: $storeSetting->selling_location_code)
                            : 'No location selected';
                    @endphp
                    <tr class="product-row" data-name="{{ strtolower($item->display_name ?? '') }}"
                        data-number="{{ strtolower($item->number ?? '') }}"
                        data-category="{{ strtolower($item->item_category_code ?? '') }}"
                        data-status="{{ $visibilityStatus }}"
                        data-stock="{{ (int) $item->sellable_inventory > 0 ? 'in' : 'out' }}"
                        data-setup="{{ $item->main_image_done && $item->variants_done ? 'complete' : 'incomplete' }}"
                        data-series="{{ $item->number_series_id ? $item->series_number : '' }}"
                        data-location="{{ $locationLabel }}"
                        data-href="{{ route('store.management.products.detail', $item->id) }}">
                        <td class="col-check">
                            <input type="checkbox" value="{{ $item->id }}"
                                class="product-checkbox row-check-input">
                        </td>

                        <td class="col-series">
                            @if ($item->series_number)
                                <span class="series-number-badge">{{ $item->series_number }}</span>
                            @else
                                <span class="series-not-set">Not set up</span>
                            @endif
                        </td>

                        <td class="col-product">
                            <div class="product-cell">
                                <div class="product-thumb-box">
                                    @if (!empty($item->custom_image_url) || !empty($item->image_url))
                                        <img src="{{ $item->resolved_image_url }}" alt="{{ $item->display_name }}"
                                            onerror="this.onerror=null;this.parentElement.innerHTML='<div class=&quot;thumb-placeholder&quot;><i class=&quot;bi bi-image&quot;></i></div>';">
                                    @else
                                        <div class="thumb-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="product-text-box">
                                    <div class="product-main-name">{{ $item->display_name ?: 'No Name' }}</div>
                                    <div class="product-sub-line">
                                        {{ $item->number ?: '-' }}
                                        @if ($item->series_number)
                                            <span class="product-sub-series">&bull; {{ $item->series_number }}</span>
                                        @endif
                                    </div>

                                    <div class="product-mobile-meta">
                                        ${{ number_format((float) $item->unit_price, 2) }} &bull;
                                        {{ (int) $item->sellable_inventory }} in stock
                                        @if (optional($storeSetting)->selling_location_code)
                                            &bull; {{ $storeSetting->selling_location_name ?: $storeSetting->selling_location_code }}
                                        @endif
                                    </div>

                                    <div class="product-mobile-status {{ $visibilityStatus }}">
                                        {{ is_null($item->is_visible) ? 'Not Reviewed' : ($item->is_visible ? 'Active' : 'Inactive') }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="col-category">{{ $item->item_category_code ?: '-' }}</td>
                        <td class="col-price">${{ number_format((float) $item->unit_price, 2) }}</td>
                        <td class="col-stock">{{ (int) $item->sellable_inventory }}</td>
                        <td class="col-toggle col-visible">
                            <div class="pill-select visible-select" data-state="{{ $visState }}">
                                <span class="pill-dot"></span>
                                <select class="js-select-visible"
                                    data-url="{{ route('store.management.products.toggle', $item->id) }}"
                                    aria-label="Visibility">
                                    <option value="" {{ $visState === 'not-setup' ? 'selected' : '' }}>Not reviewed</option>
                                    <option value="1" {{ $visState === 'on' ? 'selected' : '' }}>Visible</option>
                                    <option value="0" {{ $visState === 'off' ? 'selected' : '' }}>Hidden</option>
                                </select>
                            </div>
                        </td>

                        <td class="col-toggle col-oversell">
                            <div class="pill-select oversell-select"
                                data-state="{{ $item->allow_oversell ? 'on' : 'off' }}">
                                <select class="js-select-oversell"
                                    data-url="{{ route('store.management.products.toggleOversell', $item->id) }}"
                                    aria-label="Oversell">
                                    <option value="1" {{ $item->allow_oversell ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ $item->allow_oversell ? '' : 'selected' }}>Disabled</option>
                                </select>
                            </div>
                        </td>

                        <td class="col-action">
                            <div class="row-actions">
                                <button type="button" class="row-actions-trigger js-row-actions-trigger"
                                    aria-haspopup="menu" title="Actions">
                                    <i class="bi bi-three-dots"></i>
                                </button>

                                <div class="row-actions-panel d-none" role="menu">
                                    {{-- Visible/Oversell already show as their own columns on
                                         desktop — these two only render there so phones (which
                                         hide those columns) can still reach them, via
                                         .row-actions-mobile-only in index.css. Same
                                         js-select-visible/js-select-oversell classes as the
                                         desktop dropdowns, so the shared change handler in
                                         index.blade.php saves them the same way. --}}
                                    <div class="row-actions-field row-actions-mobile-only">
                                        <span class="row-actions-field-label">Visible</span>
                                        <div class="pill-select visible-select" data-state="{{ $visState }}">
                                            <span class="pill-dot"></span>
                                            <select class="js-select-visible"
                                                data-url="{{ route('store.management.products.toggle', $item->id) }}"
                                                aria-label="Visibility">
                                                <option value="" {{ $visState === 'not-setup' ? 'selected' : '' }}>
                                                    Not reviewed</option>
                                                <option value="1" {{ $visState === 'on' ? 'selected' : '' }}>Visible
                                                </option>
                                                <option value="0" {{ $visState === 'off' ? 'selected' : '' }}>Hidden
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-actions-field row-actions-mobile-only">
                                        <span class="row-actions-field-label">Oversell</span>
                                        <div class="pill-select oversell-select"
                                            data-state="{{ $item->allow_oversell ? 'on' : 'off' }}">
                                            <select class="js-select-oversell"
                                                data-url="{{ route('store.management.products.toggleOversell', $item->id) }}"
                                                aria-label="Oversell">
                                                <option value="1" {{ $item->allow_oversell ? 'selected' : '' }}>
                                                    Enabled</option>
                                                <option value="0" {{ $item->allow_oversell ? '' : 'selected' }}>
                                                    Disabled</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-actions-divider row-actions-mobile-only"></div>

                                    <a href="{{ route('store.management.products.detail', $item->id) }}"
                                        class="row-actions-item" role="menuitem">
                                        <i class="bi bi-eye"></i> View detail
                                    </a>
                                    <a href="{{ route('store.management.product.images', $item->id) }}"
                                        class="row-actions-item" role="menuitem">
                                        <i class="bi bi-image"></i> Update image
                                    </a>

                                    <div class="row-actions-meta">
                                        <span class="{{ $item->main_image_done ? 'done' : '' }}">
                                            <i class="bi {{ $item->main_image_done ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                                            Image setup
                                        </span>
                                        <span class="{{ $item->variants_done ? 'done' : '' }}">
                                            <i class="bi {{ $item->variants_done ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                                            Variants setup
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="noProductRow">
                        <td colspan="9">
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
            <div class="category-item-card category-card"
                data-name="{{ strtolower($category->item_category_code ?? '') }}"
                data-status="{{ $category->category_visible ? 'active' : 'inactive' }}">
                <div class="category-left-wrap">
                    <input type="checkbox" value="{{ $category->item_category_code }}"
                        class="category-checkbox row-check-input">

                    <div class="category-text-wrap">
                        <div class="category-title-text">{{ $category->item_category_code }}</div>
                        <div class="category-sub-text">{{ $category->total_items }} item(s)</div>
                    </div>
                </div>

                <div class="category-right-wrap">
                    <button type="button"
                        class="toggle-switch js-toggle-category {{ $category->category_visible ? 'on' : 'off' }}"
                        data-url="{{ route('store.management.categories.toggle', $category->item_category_code) }}"
                        title="Toggle category visibility">
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
