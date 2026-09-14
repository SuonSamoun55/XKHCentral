<?php

namespace App\Http\Controllers\Api\POS\User\Orders;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\ManagementSystem\OrderAction;
use App\Models\POS\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $orders = $this->filteredOrders($request, ['items.item']);
        $companyImage = $this->companyImage();
        return view(
            'POSViews.POSUserViews.Orders.history',
            compact('orders', 'companyImage')
        );
    }

    public function show($id)
    {
        $order = $this->orders()
            ->with(['items.item', 'items.itemVariant', 'actions.actionBy'])
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
        $companyId = session('selected_company_id') ?? auth()->user()->company_id;

        return Order::where('user_id', auth()->id())
            ->when($companyId, fn ($query) => $query->where('company_id', $companyId));
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
        // actually stored in the orders table, and the "Cancel"
        // tab label maps to the "cancelled"/"canceled" statuses.
        $statusAliases = [
            'delivered' => ['delivered', 'delivery'],
            'cancel'    => ['cancelled', 'canceled'],
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
}
