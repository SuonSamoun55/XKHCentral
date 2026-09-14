<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order {{ $order->order_no }}</title>
    @php
        $spacingMap = [
            'compact' => ['page' => '18px 24px', 'row' => '4px 8px', 'section' => '10px'],
            'normal' => ['page' => '28px 34px', 'row' => '7px 8px', 'section' => '16px'],
            'spacious' => ['page' => '40px 48px', 'row' => '11px 10px', 'section' => '24px'],
        ];
        $sp = $spacingMap[$settings->spacing ?? 'normal'] ?? $spacingMap['normal'];
        $signatureLabels = $settings->show_signature
            ? ($settings->signature_labels ?: ['Customer Signature', 'Authorized By'])
            : [];

        $forPdf = $forPdf ?? true;
        $localImagePath = function (?string $path) use ($forPdf) {
            if (!$path) {
                return null;
            }
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            $relative = preg_replace('#^/?storage/#', '', ltrim($path, '/'));
            if (!$forPdf) {
                return file_exists(public_path('storage/' . $relative)) ? asset('storage/' . $relative) : null;
            }
            $absolute = public_path('storage/' . $relative);
            return file_exists($absolute) ? str_replace('\\', '/', $absolute) : null;
        };

        $noImageFallback = $forPdf
            ? str_replace('\\', '/', public_path('images/no-image.png'))
            : asset('images/no-image.png');

        $lineImage = fn($line) => $localImagePath(optional($line->itemVariant)->image_url) ??
            ($localImagePath(optional($line->item)->custom_image_url) ??
                ($localImagePath(optional($line->item)->image_url) ?? $noImageFallback));
    @endphp
    <style>
        @page {
            margin: {{ $forPdf ? $sp['page'] : '0' }};
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
        }

        body::-webkit-scrollbar {
            width: 0px;
        }

        .page-frame {
            max-width: 794px;
            margin: 0 auto;
            padding: {{ $forPdf ? '0' : $sp['page'] }};
            background: #fff;
        }

        .page-footer {
            position: fixed;
            bottom: 12px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }

        .page-footer .pagenum:after {
            content: counter(page);
        }

        .page-footer .pagecount:after {
            content: counter(pages);
        }

        .page-frame::-webkit-scrollbar {
            width: 0px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: {{ $sp['section'] }};
        }

        .header-col {
            display: table-cell;
            vertical-align: top;
        }

        .header-col.right {
            text-align: right;
        }

        .company-logo {
            max-height: 60px;
            max-width: 190px;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .company-meta {
            font-size: 10.5px;
            color: #6b7280;
            line-height: 1.5;
        }

        .doc-title {
            font-size: 22px;
            font-weight: 700;
            color: #0EA8B2;
            letter-spacing: 1px;
        }

        .doc-meta-table {
            margin-top: 6px;
            font-size: 10.5px;
            color: #374151;
        }

        .doc-meta-table td {
            padding: 1px 0;
        }

        .doc-meta-table td.label {
            color: #9ca3af;
            padding-right: 10px;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: {{ $sp['section'] }} 0;
        }

        .bill-to-box {
            display: table;
            width: 100%;
            table-layout: fixed;
            background: #eef7f7;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: {{ $sp['section'] }};
            page-break-inside: avoid;
        }

        .bill-to-col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding-right: 16px;
        }

        .location-col {
            display: table-cell;
            vertical-align: top;
            width: 50   %;
            padding-left: 16px;
            border-left: 1px solid #d7ece9;
        }

        .location-col-meta {
            font-size: 10.5px;
            color: #6b7280;
            line-height: 1.5;
        }

        .bill-to-top {
            margin-bottom: 6px;
        }

        .bill-to-label {
            vertical-align: middle;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0d8f97;
            font-weight: 700;
        }

        .bill-to-name {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .bill-to-meta {
            font-size: 10.5px;
            color: #6b7280;
            line-height: 1.5;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: {{ $sp['section'] }};
        }
        table.items tbody tr {
            page-break-inside: avoid;
        }

        table.items thead th {
            background: #0EA8B2;
            color: #fff;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: {{ $sp['row'] }};
            text-align: center;
        }

        table.items th.item-col,
        table.items td.item-col {
            text-align: left;
            border-radius: 5px 0px 0px 0px;
        }

        table.items th.line-total-col,
        table.items td.line-total-col {
            text-align: right;
            border-radius: 0px 5px 0px 0px;
        }

        table.items tbody td {
            padding: {{ $sp['row'] }};
            border-bottom: 1px solid #f0f0f0;
            font-size: 11px;
            text-align: center;
        }

        table.items tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .item-thumb {
            width: 50px;
            height: 32px;
            object-fit: cover;
            border-radius: 4px;
            vertical-align: middle;
            margin-right: 8px;
        }

        .item-name-text {
            vertical-align: middle;
        }

        .totals-table {
            width: 260px;
            margin-left: auto;
            margin-top: {{ $sp['section'] }};
            font-size: 11px;
            page-break-inside: avoid;
        }

        .totals-table td {
            padding: 4px 0;
        }

        .totals-table td.label {
            color: #6b7280;
        }

        .totals-table td.value {
            text-align: right;
            color: #111827;
        }

        .totals-table tr.grand td {
            border-top: 2px solid #0EA8B2;
            padding-top: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #0EA8B2;
        }

        .status-chip {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 999px;
            background: #e6f9fa;
            color: #0EA8B2;
        }

        .signature-row {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-top: {{ $sp['section'] }};
            page-break-inside: avoid;
        }

        .signature-col {
            display: table-cell;
        }

        .signature-line {
            border-top: 1px solid #9ca3af;
            margin-top: 10px;
            padding-top: 4px;
            font-size: 10px;
            width: 60%;
            color: #6b7280;
            text-align: center;
        }
        .signature-col:first-child .signature-line {
            margin-right: auto;
        }

        .signature-col:last-child .signature-line {
            margin-left: auto;
        }

        .footer-note {
            margin-top: {{ $sp['section'] }};
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>

<body>
    @if ($forPdf)
        <div class="page-footer">Page <span class="pagenum"></span> of <span class="pagecount"></span></div>
    @endif
    <div class="page-frame">

        <div class="header">
            <div class="header-col">
                @if ($settings->show_logo && $companyLogoUrl)
                    <img src="{{ $companyLogoUrl }}" class="company-logo" alt="">
                @endif
                @if ($settings->show_company_name)
                    <div class="company-name">{{ ucwords($company->display_name ?? $company->name) }}</div>
                @endif
                <div class="company-meta">
                    @if ($settings->show_address && $company->address)
                        Address: {{ $company->address }}<br>
                    @endif
                    @if ($settings->show_phone && $company->phone)
                        Tel: {{ $company->phone }}<br>
                    @endif
                    @if ($settings->show_email && $company->email)
                        Email: {{ $company->email }}<br>
                    @endif
                    @if ($settings->show_tax_number && $company->tax_number)
                        Tax No: {{ $company->tax_number }}
                    @endif
                </div>
            </div>
            <div class="header-col right">
                <div class="doc-title">ORDER RECEIPT</div>
                <table class="doc-meta-table" align="right">
                    <tr>
                        <td class="label">Order No.</td>
                        <td>{{ $order->order_no }}</td>
                    </tr>
                    @if ($order->bc_document_no)
                        <tr>
                            <td class="label">Document No.</td>
                            <td>{{ $order->bc_document_no }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="label">Date</td>
                        <td>{{ optional($order->checked_out_at ?? $order->created_at)->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td><span
                                class="status-chip">{{ ucfirst(str_replace('-', ' ', $order->status ?? 'pending')) }}</span>
                        </td>
                    </tr>
                    @if (!empty($approvedByName))
                        <tr>
                            <td class="label">Approved By</td>
                            <td>{{ $approvedByName }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="divider"></div>

        @php
            $billToCustomer = optional($order->user)->bcCustomer;
            $billToLocation = $order->location_code ?: optional($billToCustomer)->location_code;
            $billToAddress = trim(
                implode(', ', array_filter([optional($billToCustomer)->address, optional($billToCustomer)->city])),
            );
            $billToName = $order->user ? ucwords($order->user->name) : 'Customer';
        @endphp

        <div class="bill-to-box">
            <div class="bill-to-col">
                <div class="bill-to-top">
                    <span class="bill-to-label">Bill To</span>
                </div>
                <div class="bill-to-name">{{ $billToName }}</div>
                <div class="bill-to-meta">
                    @if (!empty($order->user->email))
                        Email: {{ $order->user->email }}<br>
                    @endif
                    @if (!empty($order->user->phone))
                        Phone: {{ $order->user->phone }}<br>
                    @endif
                    @if (!empty($order->customer_no))
                        Customer ID: {{ $order->customer_no }}
                    @endif
                </div>
            </div>

            @if (!empty($billToLocation) || !empty($billToAddress))
                <div class="location-col">
                    <div class="location-col-meta">
                        @if (!empty($billToLocation))
                            Location: {{ $billToLocation }}<br>
                        @endif
                        @if (!empty($billToAddress))
                            Address: {{ $billToAddress }}
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th class="item-col">Item</th>
                    <th>Variant</th>
                    @if ($settings->show_unit_column)
                        <th>Unit</th>
                    @endif
                    <th class="num">Qty</th>
                    <th class="num">Price</th>
                    @if ($settings->show_discount_column)
                        <th class="num">Discount</th>
                    @endif
                    @if ($settings->show_vat_column)
                        <th class="num">VAT %</th>
                    @endif
                    <th class="num line-total-col">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $line)
                    <tr>
                        <td class="item-col">
                            @if ($settings->show_item_image)
                                <img src="{{ $lineImage($line) }}" class="item-thumb" alt="">
                            @endif
                            <span class="item-name-text">
                                {{ $line->item_name ?? $line->item_no }}
                            </span>
                        </td>
                        <td>{{ $line->variant_description ?: '-' }}</td>
                        @if ($settings->show_unit_column)
                            <td>{{ strtoupper(optional($line->item)->base_unit_of_measure_code ?? '') ?: '-' }}</td>
                        @endif
                        <td class="num">{{ (int) $line->qty }}</td>
                        <td class="num">${{ number_format((float) $line->unit_price, 2) }}</td>
                        @if ($settings->show_discount_column)
                            <td class="num">${{ number_format((float) $line->discount_amount, 2) }}</td>
                        @endif
                        @if ($settings->show_vat_column)
                            @php
                                $lineVatPercent = max(0, (float) (optional($line->item)->resolved_vat_percent ?? 0));
                            @endphp
                            <td class="num">{{ rtrim(rtrim(number_format($lineVatPercent, 2), '0'), '.') }}%</td>
                        @endif
                        <td class="num line-total-col">${{ number_format((float) $line->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">${{ number_format((float) $order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Discount</td>
                <td class="value">-${{ number_format((float) $order->discount_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">VAT</td>
                <td class="value">${{ number_format($totalTax, 2) }}</td>
            </tr>
            <tr class="grand">
                <td class="label">Total ({{ $order->currency_code ?? 'USD' }})</td>
                <td class="value">${{ number_format((float) $order->total_amount, 2) }}</td>
            </tr>
        </table>

        @if (!empty($signatureLabels))
            <div class="signature-row">
                @foreach ($signatureLabels as $label)
                    <div class="signature-col">
                        <div class="signature-line">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="footer-note">
            {{ $settings->footer_note ?: 'Thank you for your order — generated by ' . ucwords($company->display_name ?? $company->name) . ' on ' . now()->format('M d, Y g:i A') . '.' }}
        </div>

    </div>
</body>

</html>
