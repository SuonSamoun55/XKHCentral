@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Products')

@push('styles')
    <style>
        .sidebar-wrap,
        aside {
            display: none !important;
        }

        .page-wrap {
            min-height: 100vh;
            padding-bottom: 90px;
            overflow-y: auto;
        }

        .content-area {
            max-width: 430px;
            margin: 0 auto;
            padding: 10px !important;
        }

        .products-page {
            background: #fff;
            padding: 16px;
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* TOP BAR */
        .top-bar {
            display: grid;
            grid-template-columns: 40px 1fr 40px;
            align-items: center;
        }

        .icon-btn,
        .cart-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: inherit;
        }

        /* SEARCH */
        .search-box {
            display: flex;
            align-items: center;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 10px 14px;
            margin-top: 16px;
        }

        .search-box i {
            color: #9ca3af;
            margin-right: 8px;
        }

        /* CATEGORY */
        .category-list {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            overflow-x: auto;
        }

        .category-list a {
            padding: 8px 14px;
            border-radius: 20px;
            background: #f1f5f9;
            font-size: 12px;
            text-decoration: none;
            color: #000;
            white-space: nowrap;
        }

        .category-list a.active {
            background: #10b8c3;
            color: #fff;
        }

        /* GRID */
        .products-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-top: 16px;
        }

        /* CARD */
        .product-card {
            background: #fff;
            border-radius: 18px;
            padding: 12px;
            border: 1px solid #e5edff;
            position: relative;
            cursor: pointer;
        }

        .product-thumb {
            height: 120px;
            background: #f1f5f9;
            /* placeholder ទឹកភ្នែកស្ងប់ */
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }



        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #f1f5f9;
            display: block;

            /* ❌ បំបាត់ animation / flash */
            transition: none !important;
        }


        .product-info {
            margin-top: 10px;
        }

        .product-title {
            font-size: 14px;
            font-weight: 700;
        }

        .product-price {
            font-size: 14px;
            font-weight: 800;
            margin-top: 4px;
        }

        .product-add {
            position: absolute;
            right: 12px;
            bottom: 12px;
            width: 32px;
            height: 32px;
            background: #10b8c3;
            color: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* FAVORITE */
        .fav-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .fav-btn i {
            pointer-events: none;
        }

        .search-box input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            background: transparent;
        }

        /* ✅ Sticky header container */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #ffffff;
            padding-bottom: 8px;
        }

        /* ✅ Prevent content jump */
        .sticky-header::after {
            content: '';
            display: block;
            height: 1px;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrap">
        <main class="content-area">
            <div class="products-page">

                {{-- TOP BAR --}}
                <div class="sticky-header">
                    <div class="top-bar">
                        <a href="{{ route('user.pos.favorites') }}" class="icon-btn">
                            <i class="bi bi-arrow-left"></i>
                        </a>

                        <div style="text-align:center;font-weight:700">Products</div>

                        <a href="{{ route('user.pos.cart') }}" class="cart-btn">
                            <i class="bi bi-cart3"></i>
                        </a>
                    </div>

                    {{-- SEARCH --}}
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="search-input" placeholder="Search products..." autocomplete="off" />
                    </div>

                    {{-- CATEGORIES --}}
                    <div class="category-list">

                        <a href="#" class="category-filter active" data-category="">
                            All
                        </a>

                        @foreach ($categories as $category)
                            <a href="#" class="category-filter" data-category="{{ $category['code'] }}">
                                {{ $category['title'] }} ({{ $category['count'] }})
                            </a>
                        @endforeach

                    </div>
                </div>
                {{-- PRODUCT COUNT --}}
                <div id="product-count" style="margin-top:12px;font-weight:700;">
                    {{ $items->count() }} products
                </div>

                {{-- PRODUCTS --}}
                <div class="products-list">
                    @foreach ($items as $item)
                        <div class="product-card" data-category="{{ $item->item_category_code }}"
                            data-detail-url="{{ route('user.pos.product.detail', $item->id) }}">

                            {{-- IMAGE --}}
                            <div class="product-thumb">
                                <img src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                    alt="{{ $item->display_name }}"
                                    onerror="this.src='{{ asset('images/no-image.png') }}'">
                            </div>

                            {{-- FAVORITE --}}
                            <button class="fav-btn" data-item-id="{{ $item->id }}">
                                <i
                                    class="bi {{ in_array($item->id, $favoriteIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                            </button>

                            {{-- INFO --}}
                            <div class="product-info">
                                <div class="product-title">
                                    {{ $item->display_name ?: 'No Name' }}
                                </div>
                                <div class="product-price">
                                    ${{ number_format((float) ($item->final_price ?? ($item->unit_price ?? 0)), 0) }}
                                </div>
                            </div>

                            {{-- ADD --}}
                            <div class="product-add">
                                <i class="bi bi-plus"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            const searchInput = document.getElementById('search-input');
            const filters = document.querySelectorAll('.category-filter');
            const products = document.querySelectorAll('.product-card');
            const productCount = document.getElementById('product-count');

            let activeCategory = "";

            function applyFilters() {
                const keyword = searchInput.value.toLowerCase().trim();
                let visible = 0;

                products.forEach(product => {
                    const title =
                        product.querySelector('.product-title')?.innerText.toLowerCase() || "";

                    const category = product.dataset.category;

                    const matchCategory = !activeCategory || category === activeCategory;
                    const matchSearch = !keyword || title.includes(keyword);

                    if (matchCategory && matchSearch) {
                        product.style.display = "block";
                        visible++;
                    } else {
                        product.style.display = "none";
                    }
                });

                productCount.innerText = `${visible} products`;
            }

            // ✅ CATEGORY FILTER
            filters.forEach(filter => {
                filter.addEventListener('click', e => {
                    e.preventDefault();

                    filters.forEach(f => f.classList.remove('active'));
                    filter.classList.add('active');

                    activeCategory = filter.dataset.category || "";
                    applyFilters();
                });
            });

            // ✅ SEARCH FILTER
            searchInput.addEventListener('input', () => {
                applyFilters();
            });

            // ✅ CARD CLICK → PRODUCT DETAIL (event delegation)
            document.addEventListener('click', e => {
                const card = e.target.closest('.product-card');
                if (!card) return;

                // ignore favorite & add buttons
                if (
                    e.target.closest('.fav-btn') ||
                    e.target.closest('.product-add')
                ) {
                    return;
                }

                const url = card.dataset.detailUrl;
                if (url) {
                    window.location.href = url;
                }
            });

            // ✅ FAVORITE TOGGLE
            document.addEventListener('click', async e => {
                const btn = e.target.closest('.fav-btn');
                if (!btn) return;

                e.stopPropagation();

                const icon = btn.querySelector('i');
                const itemId = btn.dataset.itemId;

                try {
                    const res = await fetch("{{ route('user.pos.favorite.toggle') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrf
                        },
                        body: JSON.stringify({
                            item_id: itemId
                        })
                    });

                    await res.json();

                    icon.classList.toggle('bi-heart');
                    icon.classList.toggle('bi-heart-fill');
                    icon.classList.toggle('text-danger');

                } catch (err) {
                    console.error("Favorite toggle failed", err);
                }
            });

        });
    </script>
