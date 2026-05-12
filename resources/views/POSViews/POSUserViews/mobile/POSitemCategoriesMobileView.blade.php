@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Categories')

@push('styles')
    <style>
        :root {
            --bg: #f7fafc;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #6b7280;
            --primary: #10b8c3;
            --border: #e2e8f0;
            --radius: 20px;
            --shadow: 0 14px 38px rgba(15, 23, 42, 0.08);
        }

        body {
            background: var(--bg);
        }

        .app-shell {
            min-height: 100vh;
            background: var(--bg);
        }

        .sidebar-wrap {
            display: none !important;
        }

        .page-wrap {
            min-height: 100vh;
            padding-bottom: 110px;
            display: flex;
            justify-content: flex-start;
            align-items: stretch;
            background: transparent;
        }

        .category-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 22px;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .category-title {
            position: fixed;
            top: 130px;
            /* header (70px) + search (~60px) */
            left: 0;
            width: 98%;
            z-index: 30;
            padding: 10px !important;
            margin-left: 10px;

            font-size: 18px;
            font-weight: 100;
            background: #fff;
            color: #9ca3af;
        }

        .back-btn {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            border: 1px solid rgba(16, 184, 195, 0.16);
            background: #ffffff;
            color: var(--primary);
            text-decoration: none;
            box-shadow: 0 12px 20px rgba(16, 184, 195, 0.12);
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;

            height: 44px;
            /* 🔑 lock height */
            padding: 0 12px;
            border-radius: 12px;

            max-width: 280px;
            /* ✅ not full width */
            box-sizing: border-box;
        }



        .search-box i {
            color: var(--muted);
            font-size: 16px;
        }

        .search-box input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 15px;
            color: var(--text);

        }

        .categories-grid {
            margin-top: 80px;
            display: grid;
            gap: 14px;
        }

        .category-card {
            display: flex;
            align-items: center;
            margin-top: -10px;
            gap: 14px;
            padding: 16px;
            border-radius: 18px;
            height: 120px;
            background: #e9edf4;
            text-decoration: none;
            color: inherit;
            border: 1px solid #dbe7ff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .category-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.12);
        }

        .category-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: rgba(16, 184, 195, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
            flex-shrink: 0;
        }

        .category-card-body {
            min-width: 0;
        }

        .category-card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--text);
        }

        .category-card-sub {
            font-size: 13px;
            color: var(--muted);
        }

        .category-empty {
            padding: 28px 16px;
            border-radius: 18px;
            background: var(--card);
            color: var(--muted);
            text-align: center;
            box-shadow: var(--shadow);
        }
        /* body does NOT scroll */
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        /* page-wrap stays layout-only */
        .page-wrap {
            flex: 1;
            height: 100%;
            max-height: 100vh;
            overflow: visible;
            background-color: white;
            border-radius: 12px;
            padding: 0px 12px;
        }

        /* ✅ ONLY content-area scrolls */
        .content-area {
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;

            /* cart-box (56px) + top bar (56px) */

            /* smooth mobile scroll */
        }

        .header-top-bar {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
        }

        .header-top-bar::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -16px;
            height: 16px;
            background: #ffffff;
            pointer-events: none;
        }

        .sticky-wrap {
            position: sticky;
            top: 68px;
            z-index: 1000;
            background: #f8f8f8;
        }

        .header-top-bar {
            position: relative;
            /* not sticky */
        }

        /* ✅ sticky header stays locked */
    </style>
@endpush

@section('content')
    <div class="page-wrap">
        <main class="content-area">
            @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
                        @include('ManagementSystemViews.UserViews.Layouts.footer')

            <div class="sticky-wrap">
                <div class="header-top-bar">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="search" id="categorySearch" placeholder="Search categories" autocomplete="off">
                    </div>
                    <p class="category-title">Categories</p>
                </div>
            </div>
            <div class="categories-grid" id="categoriesGrid">
                @forelse ($categories as $category)
                    <a href="{{ route('user.pos.categories.products', ['category' => $category['code']]) }}"
                        class="category-card" data-title="{{ strtolower($category['title']) }}">
                        <div class="category-icon">
                            <i class="bi bi-grid-1x2-fill"></i>
                        </div>
                        <div class="category-card-body">
                            <div class="category-card-title">{{ $category['title'] }}</div>
                            <div class="category-card-sub">{{ $category['count'] }}
                                product{{ $category['count'] !== 1 ? 's' : '' }}</div>
                        </div>
                    </a>
                @empty
                    <div class="category-empty">No categories available.</div>
                @endforelse
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('categorySearch');
            const cards = Array.from(document.querySelectorAll('.category-card'));

            if (!searchInput || !cards.length) {
                return;
            }

            searchInput.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();
                cards.forEach(card => {
                    const title = card.dataset.title || '';
                    card.style.display = title.includes(query) ? 'flex' : 'none';
                });
            });
        });
    </script>
@endpush
