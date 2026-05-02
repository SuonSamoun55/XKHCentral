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

        .content-area {
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            padding-left: 10px !important;
            background: white;
            box-shadow: none;
            border-radius: 0;
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
    top: 130px; /* header (70px) + search (~60px) */
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
    position: fixed;
    left: 50%;
    transform: translateX(-50%); /* keep it centered */

    width: 96%;
    z-index: 50;

    display: flex;
    align-items: center;
    gap: 10px;

    padding: 16px 18px;
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid var(--border);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
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
            gap: 14px;
            padding: 16px;
            border-radius: 18px;
            height: 120px;
            background: #f5f9ff;
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

        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: #fff;
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.08);
            z-index: 999;
        }

        .mobile-bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 11px;
            color: #94a3b8;
            text-decoration: none;
        }

        .mobile-bottom-nav a i {
            font-size: 20px;
        }

        .mobile-bottom-nav a.active {
            color: var(--primary);
        }

        .header-top-bar {
            position: sticky;
            top: 0;
            
            z-index: 100;
            background: white;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
 padding-top: 20px;  
 padding-left: 10px;    
 padding-bottom: 40px;
  }

        .cart-box {
            width: 46px;
            height: 46px;
            border-radius: 18px;
            background: rgba(16, 184, 195, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .cart-count {
            position: absolute;
            top: 16px;
            right: 12px;
            min-width: 20px;
            height: 20px;
            padding-left: 5px;
            padding-top: 2px;
            height: 22px;
            background: var(--primary);
            color: #fff;
            border-radius: 999px;
        }
        /* ✅ FORCE PAGE SCROLL (minimal fix) */
/* ✅ FINAL SCROLL + STICKY FIX */

/* body does NOT scroll */
html, body {
    height: 100%;
    overflow: hidden;
}

/* page-wrap stays layout-only */
.page-wrap {
    overflow: hidden;
}

/* ✅ ONLY content-area scrolls */
.content-area {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch; /* smooth mobile scroll */
}

/* ✅ sticky header stays locked */
.h3 {
    position: sticky;
    top: 0;
    z-index: 100;
    background: white;
    
 margin-top: -10px;
    padding-top: 10px;
   

}



    </style>
@endpush

@section('content')
    <div class="page-wrap">
        <main class="content-area">
            <div class="header-top-bar">
                {{-- @include('ManagementSystemViews.UserViews.Layouts.header', ['title' => 'UMAH!']) --}}
                <h3>UMAH!</h3>
                @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            </div>
            {{-- <div class="category-header">
                <div>
                    <div class="brand-name">UMAH!</div>
                    
                </div>
                <a href="{{ route('user.posinterface') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div> --}}

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="search" id="categorySearch" placeholder="Search categories" autocomplete="off">
            </div>
            <p class="category-title">Categories</p>

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

        <div class="mobile-bottom-nav">

            {{-- HOME --}}
            <a href="{{ route('user.posinterface') }}"
                class="{{ request()->routeIs('user.posinterface') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i>
                <span>home</span>
            </a>

            {{-- PRODUCTS (categories + category products) --}}
            <a href="{{ route('user.pos.categories') }}"
                class="{{ request()->routeIs('user.pos.categories') || request()->routeIs('user.pos.categories.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>products</span>
            </a>

            {{-- WISHLIST --}}
            <a href="{{ route('user.pos.favorites') }}"
                class="{{ request()->routeIs('user.pos.favorites') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>wishlist</span>
            </a>

            {{-- USER --}}
            <a href="{{ route('user.notifications') }}"
                class="{{ request()->routeIs('user.notifications') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>user</span>
            </a>

        </div>
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
