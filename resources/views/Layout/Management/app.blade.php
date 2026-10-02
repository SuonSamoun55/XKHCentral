<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User App')</title>
    <link rel="icon" type="image/png" href="{{ $activeFaviconUrl ?? asset('images/pos/xtricate.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('/css/views/shared/admin-variables.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Layout/admin-sidebar.css') }}?v={{ filemtime(public_path('css/views/Management/Layout/admin-sidebar.css')) }}">

    <style>
        .company-ticket-wrap {
            position: fixed;
            bottom: 45px;
            right: 10px;
            z-index: 200;
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

        /* Test company (cloned from a live one) */
        .company-ticket-wrap.is-test .company-ticket-body { background: #E3F6F7; }
        .company-ticket-wrap.is-test .company-ticket-text { color: #0EA8B2; }
        .company-ticket-wrap.is-test .company-ticket-tab,
        .company-ticket-wrap.is-test .company-live-badge { background: #0EA8B2; }

        @keyframes companyLivePulse {
            0%   { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.5); opacity: 0; }
        }
            @media (max-width: 640px) {
            .company-ticket-wrap {
                bottom: calc(100px + env(safe-area-inset-bottom));
            }
        }
    </style>

    @stack('styles')

</head>

<body>

    @if ($companyName ?? null)
        <div class="company-ticket-wrap {{ ($companyIsTest ?? false) ? 'is-test' : '' }}" id="companyTicketWrap">
            <div class="company-ticket-body" id="companyTicketBody">
                <span class="company-ticket-text">{{ $companyName }}</span>
            </div>
            <div class="company-ticket-tab">
                <div class="company-live-badge" id="companyLiveBadge">
                    <span class="company-live-ring"></span>
                    <span class="company-live-text">{{ ($companyIsTest ?? false) ? 'TEST' : 'LIVE' }}</span>
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

    <div class="app-shell" id="managementShell">
        <script>
            try {
                if (localStorage.getItem('managementSidebarCollapsed') === 'true') {
                    document.getElementById('managementShell').classList.add('collapsed');
                }
            } catch (_) {}
        </script>
        @include('Layout/Management.aside')

        <div class="app-content">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
