<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS User Item List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #222;
        }

        a {
            text-decoration: none;
        }

        button,
        input {
            font-family: inherit;
        }

        .page-wrap {
            display: flex;
            min-height: 100vh;
            gap: 16px;
            padding: 10px;
        }

        .sidebar {
            width: 285px;
            background: #fff;
            border-radius: 18px;
            padding: 22px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-shrink: 0;
            box-shadow: 0 4px 18px rgba(0,0,0,0.05);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        .brand-dot {
            width: 14px;
            height: 14px;
            background: orange;
            border-radius: 50%;
            display: inline-block;
        }

        .search-box {
            position: relative;
            margin-bottom: 20px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8f8f8f;
            font-size: 14px;
        }

        .search-box input {
            width: 100%;
            height: 42px;
            border: 1px solid #e6e6e6;
            border-radius: 999px;
            padding: 0 14px 0 40px;
            outline: none;
            font-size: 14px;
            background: #fff;
        }

        .search-box input:focus {
            border-color: #19bcc5;
        }

        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 14px;
            border-radius: 14px;
            color: #222;
            font-size: 16px;
            font-weight: 500;
            transition: 0.2s ease;
        }

        .menu-item i {
            font-size: 18px;
        }

        .menu-item:hover {
            background: #f1f5f9;
        }

        .menu-item.active {
            background: #19bcc5;
            color: #fff;
        }

        .sidebar-bottom {
            padding: 10px 4px 0;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .profile-card img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }

        .profile-name {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .profile-role {
            display: inline-block;
            margin-top: 4px;
            padding: 2px 8px;
            border-radius: 999px;
            background: #19bcc5;
            color: #fff;
            font-size: 11px;
        }

        .bottom-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #444;
            font-size: 15px;
            margin-bottom: 16px;
            padding: 8px 0;
        }

        .bottom-link-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ef4444;
            font-size: 15px;
        }

        .content-area {
            flex: 1;
            background: #f8f8f8;
            border-radius: 18px;
            padding: 24px 18px;
            min-height: calc(100vh - 20px);
            box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #19bcc5;
        }

        .cart-box {
            position: relative;
            color: #19bcc5;
            font-size: 28px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #19bcc5;
            color: #fff;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            font-weight: bold;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 16px;
        }

        .product-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            border: 1px solid #e7e7e7;
            transition: 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.10);
        }

        .product-image {
            width: 100%;
            height: 150px;
            background: #fff;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .product-info {
            padding: 12px;
        }

        .product-name {
            font-size: 14px;
            font-weight: 700;
            color: #222;
            margin-bottom: 4px;
            min-height: 36px;
            line-height: 1.3;
        }

        .product-uom {
            font-size: 13px;
            color: #888;
            margin-bottom: 10px;
        }

        .product-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .product-price {
            font-size: 16px;
            font-weight: 700;
            color: #111;
        }

        .add-btn {
            min-width: 34px;
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 50%;
            background: #19bcc5;
            color: #fff;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s ease;
        }

        .add-btn:hover {
            background: #1199a1;
        }

        .add-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .empty-box {
            background: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 12px;
            color: #666;
            border: 1px solid #e5e7eb;
        }

        .message-box {
            display: none;
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .message-box.success {
            display: block;
            background: #ecfeff;
            color: #0f766e;
            border: 1px solid #a5f3fc;
        }

        .message-box.error {
            display: block;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        @media (max-width: 991px) {
            .page-wrap {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .content-area {
                padding: 18px 14px;
            }

            .product-image {
                height: 130px;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrap">

        <aside class="sidebar">
            <div>
                <div class="brand">
                    <span class="brand-dot"></span>
                    <span>Orange</span>
                </div>

                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search products">
                </div>

                <nav class="menu-list">
                    <a href="{{ route('user.posinterface') }}" class="menu-item active">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('user.pos.cart') }}" class="menu-item">
                        <i class="bi bi-cart"></i>
                        <span>Cart</span>
                    </a>

                    <a href="javascript:void(0)" class="menu-item">
                        <i class="bi bi-star"></i>
                        <span>Favorite</span>
                    </a>

                    <a href="javascript:void(0)" class="menu-item">
                        <i class="bi bi-bag"></i>
                        <span>Order History</span>
                    </a>

                    <a href="javascript:void(0)" class="menu-item">
                        <i class="bi bi-chat-left"></i>
                        <span>Notification</span>
                    </a>
                </nav>
            </div>

            <div class="sidebar-bottom">
                <div class="profile-card">
                    <img src="{{ asset('images/no-image.png') }}" alt="User">
                    <div>
                        <div class="profile-name">{{ auth()->user()->name ?? 'Guest User' }}</div>
                        <span class="profile-role">{{ ucfirst(auth()->user()->role ?? 'customer') }}</span>
                    </div>
                </div>

                <div class="bottom-link">
                    <span class="bottom-link-left">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </span>
                    <i class="bi bi-chevron-down"></i>
                </div>

                <a href="{{ route('logout') }}"
                   class="logout-link"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Log out</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </aside>

        <main class="content-area">
            <div class="top-bar">
                <h1 class="page-title">Product</h1>

                <a href="{{ route('user.pos.cart') }}" class="cart-box">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </a>
            </div>

            <div id="messageBox" class="message-box"></div>

            @if($items->isEmpty())
                <div class="empty-box">No items found.</div>
            @else
                <div class="products-grid" id="productsGrid">
                    @foreach($items as $item)
                        <div class="product-card product-item"
                             data-name="{{ strtolower($item->display_name ?? '') }}"
                             data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}">
                            <div class="product-image">
                                <img
                                    src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                    alt="{{ $item->display_name ?? 'No Name' }}"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';"
                                >
                            </div>

                            <div class="product-info">
                                <div class="product-name">{{ $item->display_name ?: 'No Name' }}</div>
                                <div class="product-uom">{{ $item->base_unit_of_measure_code ?: 'PCS' }}</div>

                                <div class="product-bottom">
                                    <div class="product-price">${{ number_format($item->unit_price ?? 0, 2) }}</div>
                                    <button
                                        class="add-btn add-to-cart-btn"
                                        type="button"
                                        data-id="{{ $item->id }}"
                                        title="Add to cart"
                                    >+</button>
                                </div>
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

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const cartCountEl = document.getElementById('cartCount');
        const messageBox = document.getElementById('messageBox');
        const searchInput = document.getElementById('searchInput');
        const productItems = document.querySelectorAll('.product-item');
        const noSearchResult = document.getElementById('noSearchResult');

        function showMessage(message, type = 'success') {
            messageBox.className = 'message-box ' + type;
            messageBox.textContent = message;

            setTimeout(() => {
                messageBox.className = 'message-box';
                messageBox.textContent = '';
            }, 2500);
        }

        async function loadCartCount() {
            try {
                const response = await fetch("{{ route('user.pos.cart.data') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    cartCountEl.textContent = data.item_count ?? 0;
                }
            } catch (error) {
                console.error('Failed to load cart count:', error);
            }
        }

        async function addToCart(itemId, button) {
            try {
                button.disabled = true;
                button.textContent = '...';

                const response = await fetch("{{ route('user.pos.cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        qty: 1
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showMessage(data.message || 'Item added to cart successfully.', 'success');
                    loadCartCount();
                } else {
                    showMessage(data.message || 'Failed to add item to cart.', 'error');
                }
            } catch (error) {
                console.error(error);
                showMessage('Something went wrong while adding item to cart.', 'error');
            } finally {
                button.disabled = false;
                button.textContent = '+';
            }
        }

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.getAttribute('data-id');
                addToCart(itemId, this);
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const keyword = this.value.toLowerCase().trim();
                let visibleCount = 0;

                productItems.forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const uom = card.getAttribute('data-uom') || '';
                    const matched = name.includes(keyword) || uom.includes(keyword);

                    card.style.display = matched ? '' : 'none';

                    if (matched) {
                        visibleCount++;
                    }
                });

                if (noSearchResult) {
                    noSearchResult.style.display = visibleCount === 0 ? 'block' : 'none';
                }
            });
        }

        loadCartCount();
    </script>
</body>
</html>
