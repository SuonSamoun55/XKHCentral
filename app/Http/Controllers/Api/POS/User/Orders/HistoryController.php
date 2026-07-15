<?php

namespace App\Http\Controllers\Api\POS\User\Orders;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\ManagementSystem\OrderAction;
use App\Models\POS\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
 use App\Models\ManagementSystem\Company;
 use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function history(Request $request)
    {
        $orders = $this->filteredOrders($request, ['items']);
        $companyImage = $this->companyImage();

        return view(
            'POSViews.POSUserViews.Orders.history',
            compact('orders', 'companyImage')
        );
    }

    public function historyMobile(Request $request)
    {
        $orders = $this->filteredOrders($request, ['items.item']);

        $counts = $this->orders()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $allCount       = $counts->sum();
        $pendingCount   = $counts['pending'] ?? 0;
        $deliveredCount = $counts['delivered'] ?? 0;
        $cancelledCount = $counts['cancelled'] ?? 0;
        $onTheWayCount  = $counts['on-the-way'] ?? 0;
        $companyImage   = $this->companyImage();

        return view(
            'POSViews.POSUserViews.mobile.POSHistoryMobileView',
            compact(
                'orders',
                'allCount',
                'pendingCount',
                'deliveredCount',
                'cancelledCount',
                'onTheWayCount',
                'companyImage'
            )
        );
    }

    public function show($id)
    {
        $order = $this->orders()
            ->with(['items.item', 'actions.actionBy'])
            ->findOrFail($id);

        return view('POSViews.POSUserViews.Orders.show', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        $order = $this->orders()->findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending orders can be cancelled.'
            );
        }

        $userId = auth()->id();
        $note = trim($request->input(
            'note',
            'Cancelled directly by customer.'
        ));

        try {
            DB::transaction(function () use ($order, $userId, $note) {
                $order->update([
                    'status'      => 'cancelled',
                    'sync_status' => 'cancelled',
                ]);

                OrderAction::create([
                    'order_id'    => $order->id,
                    'user_id'     => $userId,
                    'action_by'   => $userId,
                    'action_type' => 'cancelled',
                    'status'      => 'cancelled',
                    'note'        => $note,
                ]);

                Notification::create([
                    'user_id'  => $userId,
                    'order_id' => $order->id,
                    'item_id'  => null,
                    'type'     => 'order',
                    'title'    => 'Order Cancelled',
                    'message'  => "Your order {$order->order_no} has been cancelled.",
                    'is_read'  => false,
                ]);
            });

            return to_route('user.pos.order.show', $order->id)
                ->with('success', 'Order cancelled successfully.');
        } catch (\Throwable $e) {
            Log::error('Order cancellation failed', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to cancel order.');
        }
    }

    public function downloadInvoice($id)
    {
        $order = $this->orders()->with('items')->findOrFail($id);
        $token = $this->getToken();

        if (!$token) {
            return back()->with(
                'error',
                'Failed to authenticate with Business Central.'
            );
        }

        $isInvoice = in_array(
            $order->status,
            ['delivery', 'delivered'],
            true
        );

        $bcId = $isInvoice
            ? $this->resolvePostedInvoiceId($order, $token)
            : $this->resolveSalesOrderId($order, $token);

        if (!$bcId) {
            $message = match (true) {
                $isInvoice =>
                    'Posted sales invoice was not found in Business Central yet.',

                $order->status === 'on-the-way' =>
                    'Sales order was not found in Business Central yet.',

                default =>
                    'Invoice PDF is available only after this order is synced to Business Central.',
            };

            return back()->with('error', $message);
        }

        $type = $isInvoice ? 'invoice' : 'order';

        $page = $isInvoice
            ? "postedSaleInvoicePdf({$bcId})"
            : "salesOrderPdf({$bcId})";

        return $this->streamPdf(
            $this->bcUrl("{$page}/Microsoft.NAV.GetPDF"),
            $token,
            $order,
            $type
        );
    }

    private function streamPdf(
        ?string $endpoint,
        string $token,
        Order $order,
        string $type
    ) {
        if (!$endpoint) {
            return back()->with(
                'error',
                'Business Central URL is not configured.'
            );
        }

        $response = $this->bc($token)->post($endpoint, (object) []);

        if (!$response->successful()) {
            Log::error('BC PDF download failed', [
                'endpoint' => $endpoint,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);

            return back()->with(
                'error',
                'Failed to download PDF from Business Central.'
            );
        }

        $pdf = base64_decode($response->json('value', ''), true);

        if ($pdf === false) {
            return back()->with(
                'error',
                'PDF was not returned by Business Central.'
            );
        }

        $orderNo = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '-',
            $order->order_no ?: $order->id
        );

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>
                "attachment; filename=\"{$type}-{$orderNo}.pdf\"",
        ]);
    }

    private function resolveSalesOrderId(
        Order $order,
        string $token
    ): ?string {
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

        return $url
            ? $this->firstId($this->bc($token)->get($url))
            : null;
    }

    private function resolvePostedInvoiceId(
        Order $order,
        string $token
    ): ?string {
        if ($order->bc_invoice_no) {
            $id = $this->postedInvoiceId(
                $token,
                'number',
                $order->bc_invoice_no
            );

            if ($id) {
                return $id;
            }
        }

        return $this->postedInvoiceId(
            $token,
            'orderNumber',
            $order->bc_document_no ?: $order->order_no
        );
    }

    private function postedInvoiceId(
        string $token,
        string $field,
        string $value
    ): ?string {
        $url = $this->bcUrl('postedSalesInvoices');

        if (!$url) {
            return null;
        }

        $value = str_replace("'", "''", $value);
        $filter = rawurlencode("{$field} eq '{$value}'");

        return $this->firstId(
            $this->bc($token)->get(
                "{$url}?\$filter={$filter}&\$top=1"
            )
        );
    }

    private function firstId($response): ?string
    {
        if (!$response->successful()) {
            return null;
        }

        $id = data_get($response->json(), 'value.0.id');

        return $id ? (string) $id : null;
    }

    public function deleteMultiple(Request $request)
    {
        $ids = array_filter(
            (array) $request->input('ids', []),
            'is_numeric'
        );

        $deleted = $this->orders()
            ->whereIn('id', $ids)
            ->delete();

        return back()->with(
            'success',
            "{$deleted} order(s) deleted successfully."
        );
    }

    private function orders()
    {
        return Order::where('user_id', auth()->id());
    }

    private function filteredOrders(
        Request $request,
        array $relations = []
    ) {
        $search = trim((string) $request->search);
        $status = strtolower(
            str_replace(' ', '-', (string) $request->status)
        );

        // The "Delivered" tab must match every status spelling
        // actually stored in the orders table.
        $statusAliases = [
            'delivered' => ['delivered', 'delivery'],
        ];
        $statusValues = $statusAliases[$status] ?? [$status];

        return $this->orders()
            ->with($relations)
            ->when($search, fn ($query) =>
                $query->where(fn ($query) =>
                    $query->where('order_no', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('customer_no', 'like', "%{$search}%")
                )
            )
            ->when(
                $status && $status !== 'all',
                fn ($query) => $query->whereIn('status', $statusValues)
            )
            ->when(
                $request->date,
                fn ($query, $date) =>
                    $query->whereDate('created_at', $date)
            )
            ->latest()
            ->paginate(max(1, (int) $request->get('limit', 10)))
            ->withQueryString();
    }
    private function companyImage(): ?string
    {
        $company = null;

        if (session('selected_company_id')) {
            $company = Company::find(session('selected_company_id'));
        }

        if (!$company) {
            $company = Company::first();
        }

        if (!$company || empty($company->logo)) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $company->logo)) {
            return $company->logo;
        }

        return Storage::url($company->logo);
    }
    protected function bc(string $token)
{
    return Http::withToken($token)->acceptJson();
}
}
