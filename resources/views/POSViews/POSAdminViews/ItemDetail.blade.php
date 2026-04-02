<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Detail</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{
            --primary:#18b8c7;
            --primary-dark:#119aa7;
            --bg:#f5f6f8;
            --white:#ffffff;
            --text:#1f2937;
            --muted:#6b7280;
            --border:#e5e7eb;
        }

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            background:var(--bg);
            font-family:Arial, Helvetica, sans-serif;
            color:var(--text);
        }

        .page-layout{
            display:flex;
            min-height:100vh;
        }

        .sidebar-area{
            width:250px;
            flex-shrink:0;
            background:#fff;
            border-right:1px solid var(--border);
            height:100vh;
            overflow-y:auto;
        }

        .content-area{
            flex:1;
            padding:18px;
        }

        .detail-card{
            background:#fff;
            border-radius:18px;
            padding:26px;
            min-height:calc(100vh - 36px);
        }

        .back-btn{
            border:none;
            background:none;
            font-size:24px;
            color:#444;
            margin-bottom:20px;
        }

        .detail-grid{
            display:grid;
            grid-template-columns:1.05fr 1fr;
            gap:28px;
            align-items:start;
        }

        .main-image{
            width:100%;
            height:430px;
            border-radius:12px;
            overflow:hidden;
            background:#f1f5f9;
        }

        .main-image img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .thumb-row{
            display:flex;
            gap:10px;
            align-items:center;
            margin-top:12px;
        }

        .thumb{
            width:72px;
            height:42px;
            border-radius:8px;
            overflow:hidden;
            border:1px solid #ddd;
            background:#f8fafc;
        }

        .thumb img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .item-title{
            font-size:24px;
            font-weight:700;
            margin-bottom:6px;
        }

        .item-price{
            font-size:18px;
            font-weight:700;
            margin-bottom:18px;
        }

        .item-desc{
            font-size:13px;
            color:var(--muted);
            line-height:1.6;
            margin-bottom:24px;
        }

        .section-title{
            font-size:14px;
            font-weight:700;
            color:var(--primary);
            margin-bottom:14px;
        }

        .size-row{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            margin-bottom:24px;
        }

        .size-chip{
            min-width:88px;
            height:40px;
            padding:0 16px;
            border:1px solid #d1d5db;
            border-radius:8px;
            background:#fff;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-size:12px;
            font-weight:600;
            color:#374151;
        }

        .stock-box{
            margin-top:8px;
            font-size:16px;
            font-weight:700;
            color:#475569;
        }

        .stock-box span{
            color:var(--primary);
        }

        @media (max-width: 992px){
            .detail-grid{
                grid-template-columns:1fr;
            }

            .sidebar-area{
                display:none;
            }
        }
    </style>
</head>
<body>
<div class="page-layout">
    <aside class="sidebar-area">
        @include('POSViews.POSAdminViews.aside')
    </aside>

    <main class="content-area">
        <div class="detail-card">
            <a href="{{ url()->previous() }}" class="back-btn text-decoration-none">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="detail-grid">
                <div>
                    <div class="main-image">
                        <img
                            src="{{ url('/item-image/' . $item['id']) }}"
                            alt="{{ $item['displayName'] ?? 'Item Image' }}"
                            onerror="this.src='https://placehold.co/800x600/e5e7eb/94a3b8?text=No+Photo'">
                    </div>

                    <div class="thumb-row">
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                        <div class="thumb">
                            <img src="{{ url('/item-image/' . $item['id']) }}" alt="">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="item-title">{{ $item['displayName'] ?? 'No Name' }}</div>
                    <div class="item-price">${{ number_format((float)($item['unitPrice'] ?? 0), 2) }}</div>

                    <div class="item-desc">
                        {{ $item['description'] ?? 'No description available for this item.' }}
                    </div>

                    <div class="section-title">Available Size / Unit of Measure</div>

                    <div class="size-row">
                        <div class="size-chip">
                            {{ $item['baseUnitOfMeasureCode'] ?? 'PCS' }}
                        </div>
                    </div>

                    <div class="stock-box">
                        <i class="bi bi-arrow-right-circle text-info"></i>
                        Stock : <span>{{ (int)($item['inventory'] ?? 0) }} items left</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
