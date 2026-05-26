@extends('ManagementSystemViews.UserViews.Layouts.app')
@include('ManagementSystemViews.UserViews.Layouts.footer')
@section('title', 'Category Products')

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
            flex-direction: column;
            overflow-y: auto;
            /* 👈 important */
        }
      
        /* .content-area {
                        width: 100%;
                        max-width: 520px;
                        margin: 0 auto;
                        padding: 10px !important;
                        background: transparent;
                        box-shadow: none;
                        border-radius: 0;
                    } */

        /* .category-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
            position: sticky;
            top: 0;
            background: var(--bg);
            padding: 10px;
            z-index: 100;
            position: sticky;
            top: 0;
            padding: 10px;
            z-index: 100;

        }
         */
.category-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #F4F8FF ;
    padding: 12px;
}


        .category-title {
            font-size: 16px;
            font-weight: 700;
            color: rgb(35, 207, 230);
            margin: 0;
        }

        .category-subtitle {
            margin-top: 4px;
            font-size: 14px;
            color: var(--muted);
        }

        .back-btn {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid rgb(202, 195, 195);
            background: #f7fbff;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .products-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 👈 2 columns */
            gap: 14px;
        }

        .product-card {
            background: var(--card);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 12px;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            /* 👈 vertical layout */
            position: relative;
        }

        .product-thumb {
            width: 100%;
            height: 120px;
            border-radius: 14px;
            overflow: hidden;
            background: #eef2f7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-info {
            margin-top: 10px;
        }

        .product-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .product-price {
            font-size: 14px;
            font-weight: 800;
            margin-top: 4px;
        }

        /* ❤️ favorite icon */
        .product-fav {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #10b8c3;
            font-size: 16px;
        }

        /* ➕ add button */
        .product-add {
            position: absolute;
            bottom: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .product-meta {
            font-size: 13px;
            color: var(--muted);
        }

        .empty-box {
            padding: 26px 18px;
            border-radius: 18px;
            background: var(--card);
            text-align: center;
            color: var(--muted);
            box-shadow: var(--shadow);
        }

        /* .hero-product {

            width: 100%;
            height: 200px;
            border-radius: 20px;
            overflow: hidden;
            position: sticky;
            top: 0;
            z-index: 100;
            margin-bottom: 16px;
            background: #eef2f7;
        } */
        
.hero-product {
    position: sticky;
    top: 70px;  /* adjust based on header height */
    z-index: 90;
    width: 100%;
    height: 200px;
    overflow: hidden;
    margin-bottom: 16px;
    background: #eef2f7;
}


        .hero-product img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* overlay text (optional but matches UI) */
        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
        }

        .hero-title {
            color: #fff;
            font-size: 16px;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrap">
        <main class="content-area">

            <!-- ✅ Sticky Header -->
            <div class="category-header">
                <a href="{{ route('user.pos.categories') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>

            @if ($items->isEmpty())
                <div class="empty-box">There are no products in this category yet.</div>
            @else
                @if ($items->isNotEmpty())
                    @php $firstItem = $items->first(); @endphp

                    <!-- ✅ Sticky Hero (below header) -->
                    <div class="hero-product">
                        <img src="{{ $firstItem->image_url ?: asset('images/no-image.png') }}"
                             alt="{{ $firstItem->display_name }}">

                        <div class="hero-overlay">
                            <div>
                                <h4 class="category-title">{{ $categoryTitle }}</h4>
                                <div class="category-subtitle">
                                    {{ $items->count() }}
                                    product{{ $items->count() !== 1 ? 's' : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ✅ Products -->
                <div class="products-list">
                    @foreach ($items as $item)
                        <a href="{{ route('user.pos.product.detail', $item->id) }}" class="product-card">
                            <div class="product-thumb">
                                <img src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                     alt="{{ $item->display_name }}">
                            </div>

                            <!-- ❤️ favorite -->
                            <div class="product-fav">
                                <i class="bi bi-heart-fill"></i>
                            </div>

                            <div class="product-info">
                                <div class="product-title">
                                    {{ $item->display_name ?: 'No Name' }}
                                </div>
                                <div class="product-price">
                                    ${{ number_format((float) ($item->final_price ?? ($item->unit_price ?? 0)), 0) }}
                                </div>
                            </div>

                            <!-- ➕ add -->
                            <div class="product-add">
                                <i class="bi bi-plus"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Redirect to desktop POS page
            function checkDesktopScreen() {
                if (window.innerWidth >= 768) {
                    window.location.href = "/pos-system";
                }
            }

            // Check when page loads
            checkDesktopScreen();

            // Check when resizing screen
            window.addEventListener('resize', checkDesktopScreen);

        });
    </script>
@endpush
