<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class BusinessCentralOrderStatusController extends Controller
{
    public function show(string $order): JsonResponse
    {
        $user = auth()->user();

        $order = Order::query()
            ->where('id', $order)
            ->orWhere('order_no', $order)
            ->orWhere('bc_document_no', $order)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order was not found.',
            ], 404);
        }

        if ($user && (int) $order->user_id !== (int) $user->id && ($user->role ?? null) !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to view this order.',
            ], 403);
        }

        $result = $this->syncOrderStatus($order);
        $statusCode = $result['status_code'];
        unset($result['status_code']);

        return response()
            ->json($result, $statusCode)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function syncOrderStatus(Order $order): array
    {
        $payload = [
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'local_status' => $order->status,
            'sync_status' => $order->sync_status,
            'bc_order_id' => $order->bc_order_id,
            'bc_document_no' => $order->bc_document_no,
            'bc_status' => null,
            'bc_order' => null,
            'posted_sales_invoice_found' => false,
            'posted_sales_invoice_status' => null,
            'posted_sales_invoice' => null,
            'posted_sales_shipment_found' => false,
            'posted_sales_shipment_status' => null,
            'posted_sales_shipment' => null,
            'tracking_status' => $order->status,
        ];

        if (empty($order->bc_document_no)) {
            return [
                'success' => true,
                'message' => 'Order has not been synced to Business Central yet.',
                'data' => $payload,
                'status_code' => 200,
            ];
        }

        $token = $this->getToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with Business Central.',
                'data' => $payload,
                'status_code' => 502,
            ];
        }

        $endpoint = $this->salesOrderLookupEndpoint((string) $order->bc_document_no);

        if (!$endpoint) {
            return [
                'success' => false,
                'message' => 'Business Central URL is not configured.',
                'data' => $payload,
                'status_code' => 422,
            ];
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->acceptJson()
            ->get($endpoint);

        $bcOrderLookupFailed = false;

        if (!$response->successful()) {
            logger()->warning('BC order status lookup failed', [
                'order_id' => $order->id,
                'bc_document_no' => $order->bc_document_no,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $bcOrderLookupFailed = true;
        }

        $bcOrder = $bcOrderLookupFailed ? null : $response->json('value.0');

        if (is_array($bcOrder)) {
            $bcOrderId = $bcOrder['id'] ?? null;

            $payload['bc_status'] = $bcOrder['status'] ?? null;
            $payload['bc_order'] = [
                'id' => $bcOrderId,
                'number' => $bcOrder['number'] ?? null,
                'status' => $bcOrder['status'] ?? null,
                'last_modified_date_time' => $bcOrder['lastModifiedDateTime'] ?? null,
            ];

            if ($bcOrderId && empty($order->bc_order_id) && $this->orderHasColumn('bc_order_id')) {
                $order->forceFill(['bc_order_id' => $bcOrderId])->save();
                $payload['bc_order_id'] = $bcOrderId;
            }
        }

        $postedInvoice = $this->findPostedSalesInvoice($token, (string) $order->bc_document_no);

        if ($postedInvoice) {
            $invoiceNumber = $this->valueFrom($postedInvoice, ['number', 'no']);

            $payload['posted_sales_invoice_found'] = true;
            $payload['posted_sales_invoice_status'] = 'Posted';
            $payload['posted_sales_invoice'] = [
                'id' => $this->valueFrom($postedInvoice, ['id', 'systemId']),
                'number' => $invoiceNumber,
                'order_number' => $this->valueFrom($postedInvoice, ['orderNumber', 'orderNo']),
                'invoice_date' => $this->valueFrom($postedInvoice, ['invoiceDate']),
                'posting_date' => $this->valueFrom($postedInvoice, ['postingDate']),
                'customer_number' => $this->valueFrom($postedInvoice, ['customerNumber', 'sellToCustomerNo']),
                'customer_name' => $this->valueFrom($postedInvoice, ['customerName', 'sellToCustomerName']),
                'total_amount_including_tax' => $this->valueFrom($postedInvoice, ['totalAmountIncludingTax']),
                'last_modified_date_time' => $this->valueFrom($postedInvoice, ['lastModifiedDateTime']),
            ];
            $payload['tracking_status'] = 'delivery';

            $this->syncPostedInvoiceToLocalOrder($order, $invoiceNumber);
            $payload['local_status'] = 'delivery';
            $payload['bc_invoice_no'] = $invoiceNumber;
            $payload['bc_status'] = 'Posted';

            return [
                'success' => true,
                'message' => 'Posted sales invoice found. Order tracking status is delivery.',
                'data' => $payload,
                'status_code' => 200,
            ];
        }

        $postedShipment = $this->findPostedSalesShipment($token, (string) $order->bc_document_no);

        if ($postedShipment) {
            $shipmentNumber = $this->valueFrom($postedShipment, ['number', 'no']);

            $payload['posted_sales_shipment_found'] = true;
            $payload['posted_sales_shipment_status'] = 'Posted';
            $payload['posted_sales_shipment'] = [
                'id' => $this->valueFrom($postedShipment, ['id', 'systemId']),
                'number' => $shipmentNumber,
                'order_number' => $this->valueFrom($postedShipment, ['orderNumber', 'orderNo']),
                'shipment_date' => $this->valueFrom($postedShipment, ['shipmentDate']),
                'posting_date' => $this->valueFrom($postedShipment, ['postingDate']),
                'customer_number' => $this->valueFrom($postedShipment, ['customerNumber', 'sellToCustomerNo']),
                'customer_name' => $this->valueFrom($postedShipment, ['customerName', 'sellToCustomerName']),
                'last_modified_date_time' => $this->valueFrom($postedShipment, ['lastModifiedDateTime']),
            ];
            $payload['tracking_status'] = 'on-the-way';

            $this->syncPostedShipmentToLocalOrder($order, $shipmentNumber);
            $payload['local_status'] = 'on-the-way';
            $payload['bc_shipment_no'] = $shipmentNumber;
            $payload['bc_status'] = 'Shipment Posted';

            return [
                'success' => true,
                'message' => 'Posted sales shipment found. Order tracking status is on the way.',
                'data' => $payload,
                'status_code' => 200,
            ];
        }

        if ($bcOrderLookupFailed) {
            return [
                'success' => false,
                'message' => 'Failed to get order status from Business Central.',
                'data' => $payload,
                'status_code' => 502,
            ];
        }

        if (!is_array($bcOrder)) {
            return [
                'success' => false,
                'message' => 'Order was not found in Sales Orders, Posted Sales Shipments, or Posted Sales Invoices in Business Central.',
                'data' => $payload,
                'status_code' => 404,
            ];
        }

        return [
            'success' => true,
            'message' => 'Order status loaded from Business Central.',
            'data' => $payload,
            'status_code' => 200,
        ];
    }

    private function salesOrderLookupEndpoint(string $documentNo): ?string
    {
        $escapedDocumentNo = str_replace("'", "''", $documentNo);
        $endpoint = $this->bcEndpoint(
            'sales_orders_by_number_endpoint',
            "salesOrders?\$filter=number eq '{documentNo}'&\$top=1",
            ['documentNo' => $escapedDocumentNo]
        );

        if (!$endpoint) {
            return null;
        }

        if (str_contains($endpoint, '{documentNo}')) {
            $endpoint = str_replace('{documentNo}', rawurlencode($escapedDocumentNo), $endpoint);
        }

        if (str_contains($endpoint, '$filter=') || str_contains($endpoint, '%24filter=')) {
            return $endpoint;
        }

        $separator = str_contains($endpoint, '?') ? '&' : '?';

        return $endpoint . $separator . '$filter=' . rawurlencode("number eq '" . $escapedDocumentNo . "'") . '&$top=1';
    }

    private function findPostedSalesInvoice(string $token, string $orderNumber): ?array
    {
        $lookups = [
            $this->collectionLookupEndpoint('salesInvoices', 'orderNumber', $orderNumber),
            $this->collectionLookupEndpoint('postedSalesInvoices', 'orderNumber', $orderNumber),
            $this->collectionLookupEndpoint('postedSalesInvoices', 'orderNo', $orderNumber),
            $this->customApiLookupEndpoint('postedSalesInvoices', 'orderNumber', $orderNumber),
            $this->customApiLookupEndpoint('postedSalesInvoices', 'orderNo', $orderNumber),
        ];

        foreach (array_filter($lookups) as $endpoint) {
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->acceptJson()
                ->get($endpoint);

            if (!$response->successful()) {
                logger()->info('BC posted invoice lookup skipped', [
                    'endpoint' => $endpoint,
                    'order_number' => $orderNumber,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                continue;
            }

            $invoice = $response->json('value.0');

            if (is_array($invoice)) {
                return $invoice;
            }
        }

        return null;
    }

    private function findPostedSalesShipment(string $token, string $orderNumber): ?array
    {
        $lookups = [
            $this->collectionLookupEndpoint('salesShipments', 'orderNumber', $orderNumber),
            $this->collectionLookupEndpoint('salesShipments', 'orderNo', $orderNumber),
            $this->collectionLookupEndpoint('postedSalesShipments', 'orderNumber', $orderNumber),
            $this->collectionLookupEndpoint('postedSalesShipments', 'orderNo', $orderNumber),
            $this->customApiLookupEndpoint('postedSalesShipments', 'orderNumber', $orderNumber),
            $this->customApiLookupEndpoint('postedSalesShipments', 'orderNo', $orderNumber),
        ];

        foreach (array_filter($lookups) as $endpoint) {
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->acceptJson()
                ->get($endpoint);

            if (!$response->successful()) {
                logger()->info('BC posted shipment lookup skipped', [
                    'endpoint' => $endpoint,
                    'order_number' => $orderNumber,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                continue;
            }

            $shipment = $response->json('value.0');

            if (is_array($shipment)) {
                return $shipment;
            }
        }

        return null;
    }

    private function collectionLookupEndpoint(string $collection, string $field, string $value): ?string
    {
        $endpoint = $this->bcUrl($collection);

        if (!$endpoint) {
            return null;
        }

        $escapedValue = str_replace("'", "''", $value);
        $separator = str_contains($endpoint, '?') ? '&' : '?';

        return $endpoint . $separator . '$filter=' . rawurlencode($field . " eq '" . $escapedValue . "'") . '&$top=1';
    }

    private function customApiLookupEndpoint(string $collection, string $field, string $value): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->connection || !$this->companyId) {
            return null;
        }

        $baseUrl = rtrim((string) $this->connection->base_url, '/');
        $customBaseUrl = preg_replace('#/api/v2\.0$#', '/api/xkh/integration/v1.0', $baseUrl);

        if (!$customBaseUrl) {
            return null;
        }

        $escapedValue = str_replace("'", "''", $value);
        $endpoint = $customBaseUrl . '/companies(' . $this->companyId . ')/' . $collection;

        return $endpoint . '?$filter=' . rawurlencode($field . " eq '" . $escapedValue . "'") . '&$top=1';
    }

    private function valueFrom(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                return $row[$key];
            }
        }

        return null;
    }

    private function syncPostedInvoiceToLocalOrder(Order $order, ?string $invoiceNumber): void
    {
        $updates = ['status' => 'delivery'];

        if ($invoiceNumber && $this->orderHasColumn('bc_invoice_no')) {
            $updates['bc_invoice_no'] = $invoiceNumber;
        }

        if ($this->orderHasColumn('bc_status')) {
            $updates['bc_status'] = 'Posted';
        }

        if ($this->orderHasColumn('bc_last_synced_at')) {
            $updates['bc_last_synced_at'] = now();
        } elseif ($this->orderHasColumn('last_synced_at')) {
            $updates['last_synced_at'] = now();
        }

        $order->forceFill($updates)->save();
    }

    private function syncPostedShipmentToLocalOrder(Order $order, ?string $shipmentNumber): void
    {
        if (in_array($order->status, ['delivery', 'delivered'], true)) {
            return;
        }

        $updates = ['status' => 'on-the-way'];

        if ($shipmentNumber && $this->orderHasColumn('bc_shipment_no')) {
            $updates['bc_shipment_no'] = $shipmentNumber;
        }

        if ($this->orderHasColumn('bc_status')) {
            $updates['bc_status'] = 'Shipment Posted';
        }

        if ($this->orderHasColumn('bc_last_synced_at')) {
            $updates['bc_last_synced_at'] = now();
        } elseif ($this->orderHasColumn('last_synced_at')) {
            $updates['last_synced_at'] = now();
        }

        $order->forceFill($updates)->save();
    }

    private function orderHasColumn(string $column): bool
    {
        static $columns = null;

        if ($columns === null) {
            $columns = array_flip(Schema::getColumnListing('orders'));
        }

        return isset($columns[$column]);
    }
}
