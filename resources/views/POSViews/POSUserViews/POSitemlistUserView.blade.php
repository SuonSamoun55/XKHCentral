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

                <div class="top-bar">

                    <h1 class="page-title">Product</h1>

                    <a href="{{ route('user.pos.cart') }}" class="cart-box">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </a>
                </div>
                <div class="search-area">

                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search products...">
                    </div>

                    <div class="category-buttons">
                        <button class="cat-btn active">All Products</button>
                        <button class="cat-btn">🥩 Meat</button>
                        <button class="cat-btn">🥬 Vegetables</button>
                    </div>

                </div>

                <h2 class="page-title">All Products</h2>

                <div id="messageBox" class="message-box"></div>

                @if ($items->isEmpty())
                    <div class="empty-box">No items found.</div>
                @else
                    <div class="products-grid" id="productsGrid">
                        @foreach ($items as $item)
                            <div class="product-card product-item"
                                data-name="{{ strtolower($item->display_name ?? '') }}"
                                data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}">

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

                                    <div class="product-bottom">

                                        <div class="price">
                                            ${{ number_format($item->unit_price ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="qty-section">
                                        <span>Quantity:</span>
                                        <div class="qty-box">

                                            <button class="qty-btn minus">-</button>

                                            <span class="qty">0</span>

                                            <button class="qty-btn plus add-to-cart-btn"
                                                data-id="{{ $item->id }}">+</button>

                                        </div>


                                    </div>
                                    <button class="add-cart-btn" data-id="{{ $item->id }}">
                                        <i class="bi bi-cart"></i>
                                        Add to Cart
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
        document.addEventListener("DOMContentLoaded", function() {
            const appShell = document.querySelector('.app-shell');
            const collapseHandle = document.querySelector('.collapse-handle');
            const settingsBox = document.getElementById('settingsBox');


            if (collapseHandle && appShell) {
                collapseHandle.addEventListener('click', () => {
                    appShell.classList.toggle('collapsed');
                    // close settings menu if sidebar collapsed
                    if (appShell.classList.contains('collapsed')) {
                        settingsBox?.classList.remove('open');
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const appShell = document.getElementById('appShell');
            const collapseHandle = document.getElementById('collapseHandle');
            const settingsBtn = document.getElementById('settingsBtn');
            const settingsBox = document.getElementById('settingsBox');
            const navButtons = document.querySelectorAll('.nav-btn');

            if (collapseHandle && appShell) {
                collapseHandle.addEventListener('click', () => {
                    appShell.classList.toggle('collapsed');
                    if (appShell.classList.contains('collapsed')) {
                        settingsBox?.classList.remove('open');
                    }
                });
            }

            if (settingsBtn) {
                settingsBtn.addEventListener('click', () => {
                    if (appShell?.classList.contains('collapsed')) return;
                    settingsBox?.classList.toggle('open');
                });
            }

            navButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    navButtons.forEach((item) => item.classList.remove('active'));
                    btn.classList.add('active');
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const cartCount = document.getElementById("cartCount");

            document.querySelectorAll(".product-card").forEach(card => {

                const plusBtn = card.querySelector(".plus");
                const minusBtn = card.querySelector(".minus");
                const qtySpan = card.querySelector(".qty");

                // ADD ITEM
                plusBtn.addEventListener("click", function() {

                    let qty = parseInt(qtySpan.innerText);
                    qty++;

                    // update number on item card
                    qtySpan.innerText = qty;

                    const itemId = this.dataset.id;

                    fetch("{{ route('user.pos.cart.add') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                item_id: itemId,
                                qty: 1
                            })
                        })
                        .then(response => response.json())
                        .then(data => {

                            if (data.success) {

                                // update cart icon number
                                cartCount.innerText = data.cartCount;

                            }

                        })
                        .catch(error => console.error(error));

                });


                // REMOVE ITEM (only UI for now)
                minusBtn.addEventListener("click", function() {

                    let qty = parseInt(qtySpan.innerText);

                    if (qty > 0) {
                        qty--;
                        qtySpan.innerText = qty;
                    }

                });

            });

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll(".fav-btn").forEach(button => {

                button.addEventListener("click", function() {

                    const itemId = this.dataset.itemId;
                    const icon = this.querySelector("i");

                    fetch("{{ route('user.pos.favorite.toggle') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                item_id: itemId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {

                            if (data.favorited) {
                                icon.classList.remove("bi-heart");
                                icon.classList.add("bi-heart-fill", "text-danger");
                            } else {
                                icon.classList.remove("bi-heart-fill", "text-danger");
                                icon.classList.add("bi-heart");
                            }

                        });

                });

            });

        });
    </script>

</body>

</html>
