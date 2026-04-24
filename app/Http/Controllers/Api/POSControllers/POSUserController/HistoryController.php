<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class HistoryController extends Controller
{
   public function history(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
$perPage = $request->get('limit', 10);
        $orders = Order::where('user_id', $user->id)
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('order_no', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('customer_no', 'like', "%{$search}%");
                });
            })
            ->when($request->status && strtolower($request->status) !== 'all', function ($query) use ($request) {
                $status = strtolower(str_replace(' ', '-', $request->status));
                return $query->where('status', $status);
            })
            ->when($request->date, function ($query) use ($request) {
                return $query->whereDate('created_at', $request->date);
            })
            ->with('items')
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        return view('POSViews.POSUserViews.POSHistoryView', compact('orders'));
    }
    public function downloadInvoice($id)
{
    $order = Order::with('items')->findOrFail($id);

    // Verify the order belongs to the logged-in user
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $token = $this->getToken();

    if (!$token) {
        return back()->with('error', 'Failed to authenticate with Business Central.');
    }

    $bcOrderId = $this->resolveBusinessCentralOrderId($order, $token);

    if (!$bcOrderId) {
        return back()->with('error', 'Invoice PDF is available only after admin confirms and syncs this order to Business Central.');
    }

    $endpoint = $this->bcEndpoint(
        'sales_order_pdf_endpoint',
        'salesOrders({salesOrderId})/pdfDocument/pdfDocumentContent',
        ['salesOrderId' => $bcOrderId]
    );

    if (!$endpoint) {
        return back()->with('error', 'Business Central URL is not configured.');
    }

    $response = Http::withoutVerifying()
        ->withToken($token)
        ->withHeaders([
            'Accept' => 'application/pdf',
        ])
        ->get($endpoint);

    if (!$response->successful()) {
        return back()->with('error', 'Failed to download invoice PDF from Business Central.');
    }

    $safeOrderNo = preg_replace('/[^A-Za-z0-9\-_]/', '-', (string) $order->order_no);
    $fileName = 'invoice-' . ($safeOrderNo ?: $order->id) . '.pdf';

    return response($response->body())
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
}

private function resolveBusinessCentralOrderId(Order $order, string $token): ?string
{
    if ($order->status !== 'confirmed' || empty($order->bc_document_no)) {
        return null;
    }

    $documentNo = str_replace("'", "''", (string) $order->bc_document_no);
    $searchUrl = $this->bcEndpoint(
        'sales_orders_by_number_endpoint',
        "salesOrders?\$filter=number eq '{documentNo}'&\$top=1",
        ['documentNo' => $documentNo]
    );

    if (!$searchUrl) {
        return null;
    }

    $response = Http::withoutVerifying()
        ->withToken($token)
        ->acceptJson()
        ->get($searchUrl);

    if (!$response->successful()) {
        return null;
    }

    $rows = $response->json('value') ?? [];
    if (!is_array($rows) || empty($rows[0]['id'])) {
        return null;
    }

    return (string) $rows[0]['id'];
}
}
