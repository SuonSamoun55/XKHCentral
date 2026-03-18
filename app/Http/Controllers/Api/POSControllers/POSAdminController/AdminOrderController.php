<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Order;
use App\Models\MagamentSystemModel\OrderAction;
use App\Models\MagamentSystemModel\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminOrderController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        $orders = Order::with(['user', 'items'])
            ->latest()
            ->get();

        return view('POSViews.POSAdminViews.OrderList', compact('orders'));
    }

    public function confirm($id)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            return back()->with('error', 'Unauthorized.');
        }

        $order = Order::with(['user', 'items'])->find($id);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be confirmed.');
        }

        if (!$order->user) {
            return back()->with('error', 'Customer not found.');
        }

        if (empty($order->user->BCcustomer_no)) {
            return back()->with('error', 'Customer BC number not found.');
        }

        if (!$order->items()->exists()) {
            return back()->with('error', 'Order has no items.');
        }

        DB::beginTransaction();

        try {
            $token = $this->getAccessToken();

            if (!$token) {
                throw new \Exception('Failed to get Business Central access token.');
            }

            $companyId   = env('BC_COMPANY_ID', 'd295785a-4a3b-ef11-8409-002248951b0d');
            $environment = env('BC_ENVIRONMENT', 'SandboxKH');

            $baseUrl = "https://api.businesscentral.dynamics.com/v2.0/{$environment}/api/v2.0/companies({$companyId})";

            // 1. Create Sales Invoice Header in Business Central
            $invoiceResponse = Http::withToken($token)
                ->acceptJson()
                ->post("{$baseUrl}/salesInvoices", [
                    'customerNumber' => $order->user->BCcustomer_no,
                    // 'externalDocumentNumber' => $order->order_no,
                ]);

            if (!$invoiceResponse->successful()) {
                throw new \Exception('Create BC sales invoice failed: ' . $invoiceResponse->body());
            }

            $invoiceData = $invoiceResponse->json();
            $invoiceId   = $invoiceData['id'] ?? null;
            $invoiceNo   = $invoiceData['number'] ?? null;

            if (!$invoiceId) {
                throw new \Exception('BC invoice ID not returned.');
            }

            // 2. Create Sales Invoice Lines
            foreach ($order->items as $item) {
                $lineResponse = Http::withToken($token)
                    ->acceptJson()
                    ->post("{$baseUrl}/salesInvoices({$invoiceId})/salesInvoiceLines", [
                        'lineType'         => 'Item',
                        'lineObjectNumber' => $item->item_no,
                        'quantity'         => (int) $item->qty,
                    ]);

                if (!$lineResponse->successful()) {
                    throw new \Exception(
                        'Create BC invoice line failed for item [' . $item->item_no . ']: ' . $lineResponse->body()
                    );
                }
            }

            // 3. Update local order
            $order->update([
                'status'         => 'confirmed',
                'sync_status'    => 'synced',
                'bc_document_no' => $invoiceNo ?: $invoiceId,
            ]);

            // 4. Save action history
            OrderAction::create([
                'order_id'    => $order->id,
                'user_id'     => $order->user_id,
                'action_by'   => $admin->id,
                'action_type' => 'confirmed',
                'status'      => 'confirmed',
                'note'        => 'Order confirmed by admin and stored in Business Central Sales Invoice.',
            ]);

            // 5. Notify user
            Notification::create([
                'user_id'  => $order->user_id,
                'order_id' => $order->id,
                'item_id'  => null,
                'type'     => 'order',
                'title'    => 'Order Confirmed',
                'message'  => 'Your order ' . $order->order_no . ' has been confirmed and stored in Sales Invoice.',
                'is_read'  => false,
            ]);

            DB::commit();

            return back()->with('success', 'Order confirmed and stored in BC Sales Invoice successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Confirm failed: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, $id)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            return back()->with('error', 'Unauthorized.');
        }

        $order = Order::with(['user', 'items'])->find($id);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        DB::beginTransaction();

        try {
            $order->update([
                'status' => 'cancelled',
            ]);

            OrderAction::create([
                'order_id'    => $order->id,
                'user_id'     => $order->user_id,
                'action_by'   => $admin->id,
                'action_type' => 'cancelled',
                'status'      => 'cancelled',
                'note'        => $request->note ?? 'Order cancelled by admin.',
            ]);

            Notification::create([
                'user_id'  => $order->user_id,
                'order_id' => $order->id,
                'item_id'  => null,
                'type'     => 'order',
                'title'    => 'Order Cancelled',
                'message'  => 'Your order ' . $order->order_no . ' has been cancelled by admin.',
                'is_read'  => false,
            ]);

            DB::commit();

            return back()->with('success', 'Order cancelled successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Cancel failed: ' . $e->getMessage());
        }
    }

    public function actionHistory()
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        $actions = OrderAction::with(['order', 'user', 'actionBy'])
            ->latest()
            ->get();

        return view('POSViews.POSAdminViews.OrderActionHistory', compact('actions'));
    }

private function getAccessToken()
{
    $tenantId = env('BC_TENANT_ID');
    $clientId = env('BC_CLIENT_ID');
    $clientSecret = env('BC_CLIENT_SECRET');

    $response = \Illuminate\Support\Facades\Http::asForm()->post(
        "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token",
        [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://api.businesscentral.dynamics.com/.default',
        ]
    );

    if (!$response->successful()) {
        throw new \Exception('Token request failed: ' . $response->status() . ' - ' . $response->body());
    }

    return $response->json()['access_token'] ?? null;
}
}
