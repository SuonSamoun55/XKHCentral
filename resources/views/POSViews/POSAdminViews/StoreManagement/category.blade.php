{{-- <div class="store-header-row">
    <h2 class="store-page-title">Manage Store</h2>
</div>

<div class="store-tab-switcher">
    <button
        type="button"
        class="store-tab-btn js-store-tab {{ $tab === 'products' ? 'active' : '' }}"
        data-url="{{ route('store.management.index', ['tab' => 'products', 'search' => $tab === 'products' ? $search : '']) }}"
    >
        <i class="bi bi-box-seam"></i>
        <span>Product ({{ $productCount }})</span>
    </button>

    <button
        type="button"
        class="store-tab-btn js-store-tab {{ $tab === 'categories' ? 'active' : '' }}"
        data-url="{{ route('store.management.index', ['tab' => 'categories', 'search' => $tab === 'categories' ? $search : '']) }}"
    >
        <i class="bi bi-grid-3x3-gap"></i>
        <span>Categories ({{ $categoryCount }})</span>
    </button>
</div>

<div class="store-top-tools">
    <div class="store-search-form">
        <div class="search-input-box">
            <i class="bi bi-search"></i>
            <input
                type="text"
                id="storeSearchInput"
                data-tab="{{ $tab }}"
                value="{{ $search }}"
                placeholder="Search {{ $tab === 'products' ? 'product' : 'category' }}..."
                autocomplete="off"
            >
        </div>

        <button type="button" class="small-btn btn-search-custom" disabled>
            Search
        </button>
    </div>
</div>

@if($tab === 'products')
    <div class="action-bar">
        <div class="action-left">
            <div class="selected-box">
                Selected
                <span id="selectedProductCount">0</span>
            </div>
        </div>

        <div class="action-right">
            <button
                type="button"
                id="bulkProductActivate"
                class="small-btn btn-active-custom"
                data-url="{{ route('store.management.products.bulkUpdate') }}"
            >
                Activate Selected
            </button>

            <button
                type="button"
                id="bulkProductDeactivate"
                class="small-btn btn-inactive-custom"
                data-url="{{ route('store.management.products.bulkUpdate') }}"
            >
                Deactivate Selected
            </button>
        </div>
    </div>

    <div class="table-scroll-wrap">
        <table class="manage-store-table">
            <thead>
                <tr>
                    <th class="col-check">
                        <input type="checkbox" id="selectAllProducts">
                    </th>
                    <th>Product</th>
                    <th>Item No</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $item)
                    <tr>
                        <td class="col-check">
                            <input type="checkbox" value="{{ $item->id }}" class="product-checkbox">
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
                                    <div class="product-main-name">
                                        {{ $item->display_name ?: 'No Name' }}
                                    </div>
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
                    <tr>
                        <td colspan="7">
                            <div class="empty-state-box">No products found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($products, 'links'))
        <div class="custom-pagination-wrap">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endif

@if($tab === 'categories')
    <div class="action-bar">
        <div class="action-left">
            <div class="selected-box">
                Selected
                <span id="selectedCategoryCount">0</span>
            </div>
        </div>

        <div class="action-right">
            <button
                type="button"
                id="bulkCategoryActivate"
                class="small-btn btn-active-custom"
                data-url="{{ route('store.management.categories.bulkUpdate') }}"
            >
                Activate Selected
            </button>

            <button
                type="button"
                id="bulkCategoryDeactivate"
                class="small-btn btn-inactive-custom"
                data-url="{{ route('store.management.categories.bulkUpdate') }}"
            >
                Deactivate Selected
            </button>
        </div>
    </div>

    <div class="category-list-grid">
        @forelse($categories as $category)
            <div class="category-item-card">
                <div class="category-left-wrap">
                    <input
                        type="checkbox"
                        value="{{ $category->item_category_code }}"
                        class="category-checkbox"
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
            <div class="empty-state-box">No categories found.</div>
        @endforelse
    </div>

    @if(method_exists($categories, 'links'))
        <div class="custom-pagination-wrap">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endif

<script>
document.querySelectorAll('.custom-pagination-wrap .page-link').forEach(function(link){
    link.classList.add('js-page-link');
});
</script> --}}
