<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\CompanyConnection;
use App\Models\ManagementSystem\User;
use App\Models\POS\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    protected ?string $baseUrl = null;
    protected ?string $companyId = null;
    protected ?CompanyConnection $connection = null;

    protected function loadCompanyConnection(): void
    {
        if ($this->connection) {
            return;
        }

        $selectedCompanyId = session('selected_company_id');
        $company = null;

        if ($selectedCompanyId) {
            $company = Company::with('companyConnection')->find($selectedCompanyId);
        }

        if (!$company) {
            $company = Company::with('companyConnection')->first();
        }

        /** @var CompanyConnection|null $connection */
        $connection = $company?->companyConnection;

        if (!$connection || !$connection->status) {
            return;
        }

        $this->connection = $connection;
        $this->baseUrl = $connection->base_url;
        $this->companyId = $connection->company_bc_id;
    }

    protected function bcUrl(string $path): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->baseUrl || !$this->companyId) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $baseUrl = rtrim($this->baseUrl, '/');
        $resource = ltrim($path, '/');

        $isImagePath = Str::contains($resource, ['/picture', 'getImage']);

        if ($isImagePath) {
            $baseUrl = preg_replace('#/api/[^/]+/[^/]+/v[\d.]+$#i', '/api/v2.0', $baseUrl);
        }

        return $baseUrl . '/companies(' . $this->companyId . ')/' . $resource;
    }

    protected function bcEndpoint(string $field, string $defaultTemplate, array $replacements = []): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->connection) {
            return null;
        }

        $template = (string) ($this->connection->{$field} ?? '');
        if ($template === '') {
            $template = $defaultTemplate;
        }

        $pairs = [
            '{companyId}' => $this->companyId ?? '',
        ];

        foreach ($replacements as $key => $value) {
            $pairs['{' . $key . '}'] = (string) $value;
        }

        $resolved = strtr($template, $pairs);

        if (Str::startsWith($resolved, ['http://', 'https://'])) {
            return $resolved;
        }

        return $this->bcUrl($resolved);
    }

    protected function getToken(): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->connection) {
            return null;
        }

        return Cache::remember('bc_token_' . $this->connection->id, 3300, function () {
            $response = Http::withoutVerifying()->asForm()->timeout(15)->post($this->connection->token_url, [
                'grant_type' => 'client_credentials',
                'client_id' => trim($this->connection->client_id),
                'client_secret' => trim($this->connection->client_secret),
                'scope' => trim($this->connection->api_scope ?: 'https://api.businesscentral.dynamics.com/.default'),
            ]);

            if (!$response->successful()) {
                logger()->error('BC token failed from base controller', [
                    'company_id' => $this->connection->company_id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json()['access_token'] ?? null;
        });
    }

    /**
     * Laravel-side order stats for the customer detail page (WebUserController::show()).
     */
    protected function buildOrderStats(?User $user): array
    {
        $stats = [
            'pending_count' => 0,
            'confirmed_count' => 0,
            'cancelled_count' => 0,
            'pending_amount' => 0,
            'confirmed_amount' => 0,
            'cancelled_amount' => 0,
            'last_order_at' => null,
        ];

        if (!$user) {
            return $stats;
        }
        $cancelledStatuses = ['cancelled', 'canceled'];

        // "Confirmed" here means "approved and not cancelled" — it also
        // covers the later stages an order moves through once Business
        // Central posts its shipment/invoice (delivery, on-the-way,
        // delivered), so those orders don't silently drop out of every
        // bucket once OrderStatusController advances them past 'confirmed'.
        $confirmedStatuses = ['confirmed', 'delivery', 'on-the-way', 'delivered'];

        $stats['pending_count'] = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $stats['confirmed_count'] = Order::where('user_id', $user->id)->whereIn('status', $confirmedStatuses)->count();
        $stats['cancelled_count'] = Order::where('user_id', $user->id)->whereIn('status', $cancelledStatuses)->count();
        $stats['pending_amount'] = Order::where('user_id', $user->id)->where('status', 'pending')->sum('total_amount');
        $stats['confirmed_amount'] = Order::where('user_id', $user->id)->whereIn('status', $confirmedStatuses)->sum('total_amount');
        $stats['cancelled_amount'] = Order::where('user_id', $user->id)->whereIn('status', $cancelledStatuses)->sum('total_amount');
        $stats['last_order_at'] = Order::where('user_id', $user->id)->max('created_at');

        return $stats;
    }
    protected function bc(string $token)
    {
        return Http::withToken($token)->acceptJson();
    }

    protected function resolveSalesOrderId(Order $order, string $token): ?string
    {
        if ($order->bc_order_id) {
            return $order->bc_order_id;
        }

        if (!$order->bc_document_no) {
            return null;
        }

        $number = str_replace("'", "''", $order->bc_document_no);

        $url = $this->bcEndpoint(
            'sales_orders_by_number_endpoint',
            "salesOrders?\$filter=number eq '{number}'&\$top=1",
            ['number' => $number]
        );

        return $url ? $this->firstId($this->bc($token)->get($url)) : null;
    }

    protected function resolvePostedInvoiceId(Order $order, string $token): ?string
    {
        if ($order->bc_invoice_no) {
            $id = $this->postedInvoiceId($token, 'number', $order->bc_invoice_no);

            if ($id) {
                return $id;
            }
        }

        return $this->postedInvoiceId($token, 'orderNumber', $order->bc_document_no ?: $order->order_no);
    }

    protected function postedInvoiceId(string $token, string $field, string $value): ?string
    {
        $url = $this->bcUrl('postedSalesInvoices');

        if (!$url) {
            return null;
        }

        $value = str_replace("'", "''", $value);
        $filter = rawurlencode("{$field} eq '{$value}'");

        return $this->firstId($this->bc($token)->get("{$url}?\$filter={$filter}&\$top=1"));
    }

    protected function firstId($response): ?string
    {
        if (!$response->successful()) {
            return null;
        }

        $id = data_get($response->json(), 'value.0.id');

        return $id ? (string) $id : null;
    }

    protected function streamPdf(?string $endpoint, string $token, Order $order, string $type)
    {
        if (!$endpoint) {
            return back()->with('error', 'Business Central URL is not configured.');
        }

        $response = $this->bc($token)->post($endpoint, (object) []);

        if (!$response->successful()) {
            logger()->error('BC PDF download failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return back()->with('error', 'Failed to download PDF from Business Central.');
        }

        $pdf = base64_decode($response->json('value', ''), true);

        if ($pdf === false) {
            return back()->with('error', 'PDF was not returned by Business Central.');
        }

        $orderNo = preg_replace('/[^A-Za-z0-9_-]/', '-', $order->order_no ?: $order->id);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$type}-{$orderNo}.pdf\"",
        ]);
    }

    protected function downloadOrderInvoicePdf(Order $order)
    {
        $token = $this->getToken();

        if (!$token) {
            return back()->with('error', 'Failed to authenticate with Business Central.');
        }

        $isInvoice = in_array($order->status, ['delivery', 'delivered'], true);

        $bcId = $isInvoice
            ? $this->resolvePostedInvoiceId($order, $token)
            : $this->resolveSalesOrderId($order, $token);

        if (!$bcId) {
            $message = match (true) {
                $isInvoice => 'Posted sales invoice was not found in Business Central yet.',
                in_array($order->status, ['confirmed', 'on-the-way'], true) => 'Sales order was not found in Business Central yet.',
                default => 'Invoice PDF is available only after this order is synced to Business Central.',
            };

            return back()->with('error', $message);
        }

        $type = $isInvoice ? 'invoice' : 'order';
        $page = $isInvoice ? "postedSaleInvoicePdf({$bcId})" : "salesOrderPdf({$bcId})";

        return $this->streamPdf($this->bcUrl("{$page}/Microsoft.NAV.GetPDF"), $token, $order, $type);
    }
}
