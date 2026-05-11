@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Privacy Policy')

@push('styles')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        /* =========================
       PAGE BASE
    ========================= */
        body {
            background: #ffffff;
            margin: 0;
        }

        .sidebar,
        .sidebar-wrap {
            display: none;
        }

        /* =========================
       MOBILE CONTAINER
    ========================= */
        .mobile-policy {
            max-width: 430px;
            margin: auto;
            min-height: 100vh;
            background: #ffffff;
            padding: 20px 18px 90px;
        }

        /* =========================
       HEADER
    ========================= */

        .mobile-back-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: none;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #0f172a;
            text-decoration: none;
        }

        .mobile-policy-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .mobile-cart-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: none;
            background: #f1f5f9;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-cart-count {
            position: absolute;
            top: 6px;
            right: 6px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* =========================
       CONTENT
    ========================= */
        .policy-section {
            margin-bottom: 22px;
        }

        .policy-section h4 {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 30px;
        }

        .policy-text {
            font-size: 13px;
            line-height: 1.6;
            color: #334155;
        }

        /* =========================
       BOTTOM NAV
    ========================= */

        .cart-boxM {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding-bottom: 16px;
            margin-top: -10px !important;
            margin-bottom: 10px !important;
            padding-left: 6px;
            padding-right: 6px;
            background: white;
        }
    </style>
@endpush

@section('content')

    <div class="mobile-policy">

        @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
            @include('ManagementSystemViews.UserViews.Layouts.footer')

        {{-- SECTION 1 --}}
        <div class="policy-section">
            <h4>1. Types data we collect</h4>
            <div class="policy-text">
                Xtricate eCommerce App, operated by Xtricate Cambodia, respects your privacy
                and is committed to protecting your personal information.
                This app allows users to place orders
                and connects with Microsoft Dynamics 365 Business Central to manage orders,
                inventory, invoices, and customer records.
            </div>
        </div>

        {{-- SECTION 2 --}}
        <div class="policy-section">
            <h4>2. Use of your personal data</h4>
            <div class="policy-text">
                We may collect information such as your name, company name,
                email address, phone number, delivery address,
                account details, order history, payment status,
                device information, and app usage data.
                <br><br>
                This information is used to provide our services,
                process orders, improve app performance,
                offer customer support, prevent fraud,
                and comply with legal obligations.
                <br><br>
                This Privacy Policy may be updated from time to time.
                Continued use of the app means you accept any updated version.
                <br><br>
                If you have any questions, please contact
                <strong>Xtricate Cambodia</strong> at
                <strong>info@xtricate.com</strong>.
            </div>
        </div>

    </div>

    {{-- BOTTOM NAV --}}
@endsection
