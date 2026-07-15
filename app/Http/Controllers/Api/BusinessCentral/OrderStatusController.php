<?php

namespace App\Http\Controllers\Api\BusinessCentral;

use App\Http\Controllers\Controller;
use App\Models\POS\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class OrderStatusController extends Controller
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

        if (empty($order->bc_document_no) && empty($order->bc_order_id)) {
            return $this->ok('Order has not been synced yet', $payload);
        }

        $token = $this->getToken();

        if (!$token) {
            return $this->fail('Failed to authenticate with Business Central', 502, $payload);
        }

        $lookupDocumentNo = $order->bc_document_no ?: $order->order_no;

        $endpoint = $this->salesOrderLookupEndpoint($order, $lookupDocumentNo);

        if (!$endpoint) {
            return $this->fail('Business Central URL is not configured', 422, $payload);
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->acceptJson()
            ->get($endpoint);

        if (!$response->successful()) {
            logger()->warning('BC order lookup failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);
        }

        $bcOrder = data_get($response->json(), 'value.0');

        if (is_array($bcOrder)) {
            $payload['bc_status'] = $bcOrder['status'] ?? null;

            if (!empty($bcOrder['id'])) {
                $order->forceFill(['bc_order_id' => $bcOrder['id']])->save();
                $payload['bc_order_id'] = $bcOrder['id'];
            }

            if (!empty($bcOrder['number'])) {
                $order->forceFill(['bc_document_no' => $bcOrder['number']])->save();
                $payload['bc_document_no'] = $bcOrder['number'];
            }
        }

        $invoice = $this->findPostedSalesInvoice($token, $lookupDocumentNo);

        if ($invoice) {
            $invoiceNo = $invoice['number'] ?? null;

            $payload['posted_sales_invoice_found'] = true;
            $payload['posted_sales_invoice_status'] = 'Posted';
            $payload['posted_sales_invoice'] = $invoice;
            $payload['tracking_status'] = 'delivery';

            $this->syncPostedInvoiceToLocalOrder($order, $invoiceNo);

            return $this->ok('Invoice found', $payload);
        }

        $shipment = $this->findPostedSalesShipment($token, $lookupDocumentNo);

        if ($shipment) {
            $shipmentNo = $shipment['number'] ?? null;

            $payload['posted_sales_shipment_found'] = true;
            $payload['posted_sales_shipment_status'] = 'Posted';
            $payload['posted_sales_shipment'] = $shipment;
            $payload['tracking_status'] = 'on-the-way';

            $this->syncPostedShipmentToLocalOrder($order, $shipmentNo);

            return $this->ok('Shipment found', $payload);
        }

        return $this->ok('No updates found', $payload);
    }

    /*
    |--------------------------------------------------------------------------
    | FIXED BC QUERY (NO $filter VARIABLE ERROR)
    |--------------------------------------------------------------------------
    */

    private function salesOrderLookupEndpoint(Order $order, string $documentNo): ?string
    {
        if (!empty($order->bc_order_id)) {
            return $this->bcUrl('salesOrders(' . $order->bc_order_id . ')');
        }

        $escaped = str_replace("'", "''", $documentNo);

        $endpoint = $this->bcEndpoint(
            'sales_orders_by_number_endpoint',
            "salesOrders?\$filter=number eq '{documentNo}'&\$top=1",
            ['documentNo' => $escaped]
        );

        if (!$endpoint) {
            return null;
        }

        return str_replace('{documentNo}', rawurlencode($escaped), $endpoint);
    }

    /*
    |--------------------------------------------------------------------------
    | POSTED INVOICE SEARCH (FIXED QUERY BUILD)
    |--------------------------------------------------------------------------
    */

    private function findPostedSalesInvoice(string $token, string $doc): ?array
    {
        $url = $this->bcUrl('postedSalesInvoices');

        if (!$url) {
            return null;
        }

        // ✅ FIX: NO "$filter variable error"
        $response = Http::withoutVerifying()
            ->withToken($token)
            ->acceptJson()
            ->get($url, [
                '$filter' => "orderNumber eq '{$doc}'",
                '$top' => 1,
            ]);

        return data_get($response->json(), 'value.0');
    }


    private function findPostedSalesShipment(string $token, string $doc): ?array
    {
        $url = $this->bcUrl('postedSalesShipments');

        if (!$url) {
            return null;
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->acceptJson()
            ->get($url, [
                '$filter' => "orderNumber eq '{$doc}'",
                '$top' => 1,
            ]);

        return data_get($response->json(), 'value.0');
    }

    private function ok($message, $data, $code = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => $code,
        ];
    }

    private function fail($message, $code = 400, $data = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
            'status_code' => $code,
        ];
    }

    private function syncPostedInvoiceToLocalOrder(Order $order, ?string $invoiceNo): void
    {
        $order->update([
            'status' => 'delivery',
            'bc_invoice_no' => $invoiceNo,
        ]);
    }

    private function syncPostedShipmentToLocalOrder(Order $order, ?string $shipmentNo): void
    {
        $order->update([
            'status' => 'on-the-way',
            'bc_shipment_no' => $shipmentNo,
        ]);
    }
}