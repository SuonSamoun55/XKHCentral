<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>POS Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/dashboard.css') }}" />

</head>
<body>
    <div class="app-shell" id="appShell">



        @include('ManagementSystemViews.UserViews.Layouts.aside')

        <main class="content-scroll">
            <div class="dashboard-grid">

                <section class="card hero-card">
                    <div class="hero-title">Product</div>
                    <a href="/pos-system">

                        <div class="pos-product">
                            <img src="{{ asset('images/pos/product 1.png') }}" alt="Product">
                        </div>
                    </a>
                </section>

                <section class="card hero-card">
                    <div class="hero-title">Orders</div>
                    <div class="Orders">
                        <img src="{{ asset('images/pos/product 2.png') }}" alt="Orders">
                    </div>
                </section>

                <section class="card hero-card">
                    <div class="hero-title">POS System</div>
                    <div class="soft-card bg-pos" data-href="/pos/interface">

                        <div class="pos-system">
                            <img src="{{ asset('images/pos/product 3.png') }}" alt="POS">
                        </div>
                </section>

                <section class="card recent-card">
                    <div class="card-head">
                        <h3>Recent Order</h3>
                        <div class="small-text">Sort by Newest ⌄</div>
                    </div>

                    <div class="order-list">
                        <div class="order-item">
                            <div class="order-left">
                                <img src="https://i.pravatar.cc/80?img=15" alt="">
                                <div>
                                    <div class="order-name">Chris Friedkly</div>
                                    <div class="order-sub">Supermarket Villanova</div>
                                </div>
                            </div>
                        </div>

                        <div class="order-item active">
                            <div class="order-left">
                                <img src="https://i.pravatar.cc/80?img=32" alt="">
                                <div>
                                    <div class="order-name">Maggie Johnson</div>
                                    <div class="order-sub">Oasis Organic Inc.</div>
                                </div>
                            </div>

                            <div class="order-actions">
                                <span>◌</span>
                                <span>☆</span>
                                <span>✎</span>
                                <span>⋮</span>
                            </div>
                        </div>

                        <div class="order-item">
                            <div class="order-left">
                                <img src="https://i.pravatar.cc/80?img=47" alt="">
                                <div>
                                    <div class="order-name">Gael Harry</div>
                                    <div class="order-sub">New York Finest Fruits</div>
                                </div>
                            </div>
                        </div>

                        <div class="order-item">
                            <div class="order-left">
                                <img src="https://i.pravatar.cc/80?img=49" alt="">
                                <div>
                                    <div class="order-name">Jenna Sullivan</div>
                                    <div class="order-sub">Walmart</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="gold-link">summary orders →</a>
                </section>

                <section class="card report-card">
                    <div class="card-head">
                        <h3 class="report-title">Report</h3>
                        <div class="small-text">Yearly ⌄</div>
                    </div>

                    <div class="chart-box">
                        <svg viewBox="0 0 100 40" preserveAspectRatio="none">
                            <polyline fill="none" stroke="#67cd63" stroke-width="1.7" stroke-dasharray="2 2"
                                points="0,33 13,30 26,18 38,13 52,31 66,26 82,10 100,2" />
                        </svg>
                    </div>

                    <div class="chart-years">
                        <span>2016</span>
                        <span>2017</span>
                        <span>2018</span>
                        <span>2019</span>
                        <span>2020</span>
                        <span>2021</span>
                        <span>2022</span>
                        <span>2023</span>
                    </div>
                </section>

                <section class="mini-stats">
                    <div class="card mini-card">
                        <div class="mini-label">Top month</div>
                        <div class="mini-value">November</div>
                        <div class="mini-sub accent">2019</div>
                    </div>

                    <div class="card mini-card">
                        <div class="mini-label">Top year</div>
                        <div class="mini-value">2023</div>
                        <div class="mini-sub">96K sold so far</div>
                    </div>

                    <div class="card mini-card">
                        <div class="mini-label">Top buyer</div>
                        <div class="top-buyer">
                            <img src="https://i.pravatar.cc/80?img=32" alt="">
                            <div>
                                <div class="buyer-name">Maggie Johnson</div>
                                <div class="buyer-sub">Oasis Organic Inc.</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card notification-card">
                    <h3>Notification</h3>
                    <div class="small-sub">2 unread messages</div>

                    <div class="avatar-row">
                        <img src="https://i.pravatar.cc/80?img=32" alt="">
                        <img src="https://i.pravatar.cc/80?img=15" alt="">
                        <img src="https://i.pravatar.cc/80?img=59" alt="">
                        <img src="https://i.pravatar.cc/80?img=47" alt="">
                    </div>

                    <a href="#" class="gold-link">All messages →</a>
                </section>

                <section class="card states-card">
                    <h3>Top states</h3>

                    <div class="state-list">
                        <div class="state-item">
                            <span class="state-code">NY</span>
                            <div class="state-bar">
                                <div class="state-fill" style="width:100%">120K</div>
                            </div>
                        </div>

                        <div class="state-item">
                            <span class="state-code">MA</span>
                            <div class="state-bar">
                                <div class="state-fill" style="width:82%">80K</div>
                            </div>
                        </div>

                        <div class="state-item">
                            <span class="state-code">NH</span>
                            <div class="state-bar">
                                <div class="state-fill" style="width:70%">70K</div>
                            </div>
                        </div>

                        <div class="state-item">
                            <span class="state-code">OR</span>
                            <div class="state-bar">
                                <div class="state-fill" style="width:54%">50K</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card deals-card">
                    <h3>New deals</h3>

                    <div class="deal-grid">
                        <div class="deal-item"><span class="deal-plus">+</span><span>Fruit2Go</span></div>
                        <div class="deal-item"><span class="deal-plus">+</span><span>Marshall's MKT</span></div>
                        <div class="deal-item"><span class="deal-plus">+</span><span>CCNT</span></div>
                        <div class="deal-item"><span class="deal-plus">+</span><span>Joana Mini-market</span></div>
                        <div class="deal-item"><span class="deal-plus">+</span><span>Little Brazil Vegan</span></div>
                        <div class="deal-item"><span class="deal-plus">+</span><span>Target</span></div>
                        <div class="deal-item"><span class="deal-plus">+</span><span>Organic Place</span></div>
                        <div class="deal-item"><span class="deal-plus">+</span><span>Morello's</span></div>
                    </div>
                </section>

            </div>
        </main>
    </div>
    <script>
        < script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" >
    </script>

    <script>
        document.querySelectorAll('.soft-card').forEach(card => {
            card.addEventListener('click', (e) => {
                // don't trigger when clicking the button/link
                if (e.target.closest('a')) return;
                const href = card.getAttribute('data-href');
                if (href) window.location.href = href;
            });
        });
    </script>

</body>

</html>
