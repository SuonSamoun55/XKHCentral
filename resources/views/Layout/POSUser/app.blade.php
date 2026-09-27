<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User App')</title>
    <link rel="icon" type="image/png" href="{{ $activeFaviconUrl ?? asset('images/pos/xtricate.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet"
        href="{{ asset('css/views/POSViews/POSUserViews/Layout/aside.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Layout/aside.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('/css/views/POSViews/POSUserViews/Layout/footer.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Layout/footer.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('/css/views/POSViews/POSUserViews/Layout/header_mobile.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Layout/header_mobile.css')) }}">

    <style>
        .company-ticket-wrap {
            position: fixed;
            bottom: 45px;
            right: 10px;

            z-index: 5;
            display: flex;
            align-items: stretch;
            height: 34px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            border-radius: 999px;
            overflow: hidden;
        }

        .company-ticket-body {
            width: 0;
            overflow: hidden;
            background: #E9F8FB;
            display: flex;
            align-items: center;
            white-space: nowrap;
            transition: width 0.45s cubic-bezier(.2, .8, .3, 1);
        }

        .company-ticket-text {
            padding: 0 12px;
            font-weight: 600;
            font-size: 12px;
            font-family: Arial, sans-serif;
            color: #09bfd3;
            display: inline-block;
        }

        .company-ticket-tab {
            width: 34px;
            background: #09bfd3;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        .company-live-badge {
            position: relative;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            background: #09bfd3;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .company-live-text {
            font-size: 6px;
            font-weight: 700;
            letter-spacing: 0.4px;
            color: #fff;
        }

        .company-live-ring {
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            animation: companyLivePulse 1.6s ease-out infinite;
        }

        @keyframes companyLivePulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {


            .company-ticket-wrap {
                bottom: calc(100px + env(safe-area-inset-bottom));
            }
        }

        body.kb-open .company-ticket-wrap {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>

<body>

    @if ($companyName ?? null)
        <div class="company-ticket-wrap" id="companyTicketWrap">
            <div class="company-ticket-body" id="companyTicketBody">
                <span class="company-ticket-text">{{ $companyName }}</span>
            </div>
            <div class="company-ticket-tab">
                <div class="company-live-badge" id="companyLiveBadge">
                    <span class="company-live-ring"></span>
                    <span class="company-live-text">LIVE</span>
                </div>
            </div>
        </div>
        <script>
            (function() {
                const wrap = document.getElementById('companyTicketWrap');
                const body = document.getElementById('companyTicketBody');
                const text = body?.querySelector('.company-ticket-text');
                document.getElementById('companyLiveBadge')?.addEventListener('click', function() {
                    const opening = !wrap.classList.contains('open');
                    wrap.classList.toggle('open', opening);
                    if (opening && text) {
                        const maxWidth = window.innerWidth * 0.6;
                        const needed = text.scrollWidth + 1;
                        body.style.width = Math.min(needed, maxWidth) + 'px';
                    } else {
                        body.style.width = '0px';
                    }
                });
            })();
        </script>
    @endif

    <div class="app-shell" id="appShell">
        <script>
            try {
                if (localStorage.getItem('posUserSidebarCollapsed') === 'true') {
                    document.getElementById('appShell').classList.add('collapsed');
                }
            } catch (_) {}
        </script>
        @include('Layout.POSUser.aside')
        @yield('content')



    </div>

    <script>
        (function() {
            document.addEventListener('focusin', function(e) {
                if (e.target.matches('input, textarea, select')) {
                    document.body.classList.add('kb-open');
                }
            });
            document.addEventListener('focusout', function(e) {
                if (e.target.matches('input, textarea, select')) {
                    document.body.classList.remove('kb-open');
                }
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>
