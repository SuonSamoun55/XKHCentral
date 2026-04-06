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

                <select id="storeStatusFilter" class="store-select-control">
                    <option value="all">All Status</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>

                <div class="stock-filter-wrap">
                    <select id="storeStockFilter" class="store-select-control">
                        <option value="all">All Stock</option>
                        <option value="in">In Stock</option>
                        <option value="out">Out of Stock</option>
                    </select>
                </div>

                <button
                    type="button"
                    id="bulkProductActivate"
                    class="store-action-btn btn-active-custom products-only-btn"
                    data-url="{{ route('store.management.products.bulkUpdate') }}"
                >
                    <i class="bi bi-check2-circle"></i>
                    Activate
                </button>

                <button
                    type="button"
                    id="bulkProductDeactivate"
                    class="store-action-btn btn-inactive-custom products-only-btn"
                    data-url="{{ route('store.management.products.bulkUpdate') }}"
                >
                    <i class="bi bi-x-circle"></i>
                    Deactivate
                </button>

                <button
                    type="button"
                    id="bulkCategoryActivate"
                    class="store-action-btn btn-active-custom categories-only-btn d-none"
                    data-url="{{ route('store.management.categories.bulkUpdate') }}"
                >
                    <i class="bi bi-check2-circle"></i>
                    Activate
                </button>

                <button
                    type="button"
                    id="bulkCategoryDeactivate"
                    class="store-action-btn btn-inactive-custom categories-only-btn d-none"
                    data-url="{{ route('store.management.categories.bulkUpdate') }}"
                >
                    <i class="bi bi-x-circle"></i>
                    Deactivate
                </button>
            </div>
        </div>
    </div>

    <div id="productsTabContent" class="store-tab-content">
        <div class="table-scroll-wrap store-table-scroll">
            <table class="manage-store-table">
                <thead>
                    <tr>
                        <th class="col-check">
                            <input type="checkbox" id="selectAllProducts" class="row-check-input">
                        </th>
                        <th>Product</th>
                        <th>Item No</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @forelse($products as $item)
                        <tr
                            class="product-row"
                            data-name="{{ strtolower($item->display_name ?? '') }}"
                            data-number="{{ strtolower($item->number ?? '') }}"
                            data-category="{{ strtolower($item->item_category_code ?? '') }}"
                            data-status="{{ $item->is_visible ? 'active' : 'inactive' }}"
                            data-stock="{{ (int) $item->inventory > 0 ? 'in' : 'out' }}"
                        >
                            <td class="col-check">
                                <input type="checkbox" value="{{ $item->id }}" class="product-checkbox row-check-input">
                            </td>

                            <td>
                                <div class="product-cell">
                                    <div class="product-thumb-box">
                                        @if(!empty($item->image_url))
                                            <img
                                                src="{{ $item->image_url }}"
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
                                    </div>
                                </div>
                            </td>

                            <td>{{ $item->number ?: '-' }}</td>
                            <td>{{ $item->item_category_code ?: '-' }}</td>
                            <td>${{ number_format((float) $item->unit_price, 2) }}</td>
                            <td>{{ (int) $item->inventory }}</td>

                            <td>
                                <div class="status-action-wrap">
                                    <span class="status-chip {{ $item->is_visible ? 'active' : 'inactive' }}">
                                        {{ $item->is_visible ? 'ACTIVE' : 'INACTIVE' }}
                                    </span>

                                    <button
                                        type="button"
                                        class="toggle-switch js-toggle-product {{ $item->is_visible ? 'on' : 'off' }}"
                                        data-url="{{ route('store.management.products.toggle', $item->id) }}"
                                        title="Toggle product visibility"
                                    >
                                        <span class="toggle-dot"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noProductRow">
                            <td colspan="7">
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
                    <span id="selectedProductCount">0</span>
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
                        <span class="status-chip {{ $category->category_visible ? 'active' : 'inactive' }}">
                            {{ $category->category_visible ? 'ACTIVE' : 'INACTIVE' }}
                        </span>

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
                    <span id="selectedCategoryCount">0</span>
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
