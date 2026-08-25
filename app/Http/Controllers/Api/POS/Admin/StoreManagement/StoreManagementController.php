<?php

namespace App\Http\Controllers\Api\POS\Admin\StoreManagement;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\InventoryMovement;
use App\Models\POS\OrderItem;
use App\Models\POS\ItemVariant;
use App\Models\POS\ItemSetupStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoreManagementController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its store.');
        }

        $productCount = Item::where('company_id', $companyId)->count();

        $categoryCount = Item::where('company_id', $companyId)
            ->whereNotNull('item_category_code')
            ->where('item_category_code', '!=', '')
            ->distinct('item_category_code')
            ->count('item_category_code');

        $products = Item::query()
            ->where('company_id', $companyId)
            ->orderBy('display_name')
            ->get();

        // Pull setup status for all items at once and attach to each product
        $statuses = ItemSetupStatus::all()->keyBy('item_id');

        foreach ($products as $item) {
            $status = $statuses[$item->id] ?? null;
            $item->main_image_done = $status->main_image_done ?? false;
            $item->variants_done = $status->variants_done ?? false;
        }

        $categories = Item::query()
            ->select(
                'item_category_code',
                DB::raw('COUNT(*) as total_items'),
                DB::raw('MAX(CASE WHEN is_visible = 1 THEN 1 ELSE 0 END) as category_visible')
            )
            ->where('company_id', $companyId)
            ->whereNotNull('item_category_code')
            ->where('item_category_code', '!=', '')
            ->groupBy('item_category_code')
            ->orderBy('item_category_code')
            ->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('POSViews.POSAdminViews.StoreManagement.content', compact(
                    'products',
                    'categories',
                    'productCount',
                    'categoryCount'
                ))->render()
            ])
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        return response(view('POSViews.POSAdminViews.StoreManagement.index', compact(
            'products',
            'categories',
            'productCount',
            'categoryCount'
        )))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function toggleProduct(Request $request, $id)
    {
        $companyId = session('selected_company_id');

        $item = Item::where('company_id', $companyId)->findOrFail($id);
        $item->is_visible = !$item->is_visible;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'id' => $item->id,
            'is_visible' => (bool) $item->is_visible,
            'label' => $item->is_visible ? 'ACTIVE' : 'INACTIVE',
        ]);
    }

    public function toggleCategory(Request $request, $code)
    {
        $companyId = session('selected_company_id');

        $items = Item::where('company_id', $companyId)
            ->where('item_category_code', $code)
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }

        $newStatus = !$items->first()->is_visible;

        Item::where('company_id', $companyId)
            ->where('item_category_code', $code)
            ->update(['is_visible' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'code' => $code,
            'is_visible' => (bool) $newStatus,
            'label' => $newStatus ? 'ACTIVE' : 'INACTIVE',
        ]);
    }

    public function bulkUpdateProducts(Request $request)
    {
        $companyId = session('selected_company_id');
        $ids = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one product.'
            ], 422);
        }

        $status = $action === 'activate';

        Item::where('company_id', $companyId)
            ->whereIn('id', $ids)
            ->update(['is_visible' => $status]);

        return response()->json([
            'success' => true,
            'message' => $status
                ? 'Selected products activated successfully.'
                : 'Selected products deactivated successfully.'
        ]);
    }

    public function bulkUpdateCategories(Request $request)
    {
        $companyId = session('selected_company_id');
        $codes = $request->input('codes', []);
        $action = $request->input('action');

        if (empty($codes)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one category.'
            ], 422);
        }

        $status = $action === 'activate';

        Item::where('company_id', $companyId)
            ->whereIn('item_category_code', $codes)
            ->update(['is_visible' => $status]);

        return response()->json([
            'success' => true,
            'message' => $status
                ? 'Selected categories activated successfully.'
                : 'Selected categories deactivated successfully.'
        ]);
    }

    public function tracking(Request $request)
    {
        $companyId = session('selected_company_id');

        $search = trim((string) $request->get('search', ''));
        $source = $request->get('source', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $tableView = $request->get('table_view', 'summary');
        if (!in_array($tableView, ['summary', 'details'], true)) {
            $tableView = 'summary';
        }
        $perPage = (int) $request->get('per_page', 20);
        $perPage = $perPage > 0 ? min($perPage, 200) : 20;

        $summaryQuery = InventoryMovement::query()
            ->with('item:id,display_name,number')
            ->where('company_id', $companyId);

        if ($search !== '') {
            $summaryQuery->whereHas('item', function ($q) use ($search) {
                $q->where('display_name', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%");
            });
        }

        if ($dateFrom) {
            $summaryQuery->whereDate('happened_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $summaryQuery->whereDate('happened_at', '<=', $dateTo);
        }

        $rawSummary = $summaryQuery->get()
            ->groupBy('item_id')
            ->map(function ($rows) {
                $item = optional($rows->first())->item;

                return [
                    'item' => $item,
                    'added_qty' => (int) $rows->where('source', 'sync')->where('quantity_change', '>', 0)->sum('quantity_change'),
                    'reduced_qty' => (int) abs($rows->where('source', 'sync')->where('quantity_change', '<', 0)->sum('quantity_change')),
                    'sold_qty' => (int) abs($rows->where('source', 'sale')->sum('quantity_change')),
                    'last_activity' => optional($rows->sortByDesc('happened_at')->first())->happened_at,
                ];
            })
            ->sortByDesc(function ($row) {
                return ($row['sold_qty'] ?? 0) + ($row['added_qty'] ?? 0) + ($row['reduced_qty'] ?? 0);
            })
            ->values();

        $movements = InventoryMovement::query()
            ->with([
                'item:id,display_name,number',
                'buyer:id,name,role',
                'actor:id,name',
                'order:id,order_no',
            ])
            ->where('company_id', $companyId)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->whereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where('display_name', 'like', "%{$search}%")
                            ->orWhere('number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('buyer', function ($buyerQuery) use ($search) {
                        $buyerQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_no', 'like', "%{$search}%");
                    })
                    ->orWhere('reference_no', 'like', "%{$search}%");
                });
            })
            ->when(in_array($source, ['sync', 'sale'], true), function ($q) use ($source) {
                $q->where('source', $source);
            })
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->whereDate('happened_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                $q->whereDate('happened_at', '<=', $dateTo);
            })
            ->orderByDesc('happened_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('POSViews.POSAdminViews.StoreManagement.tracking', [
            'movements' => $movements,
            'summaryRows' => $rawSummary,
            'search' => $search,
            'source' => $source,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'perPage' => $perPage,
            'tableView' => $tableView,
        ]);
    }

    public function productDetail(Request $request, int $id)
    {
        $companyId = session('selected_company_id');

        $item = Item::query()
            ->where('company_id', $companyId)
            ->findOrFail($id);

        $buyerSearch = trim((string) $request->get('buyer_search', ''));
        $buyerFilter = $request->get('buyer_filter', 'all');
        $topLimit = match ($buyerFilter) {
            'top5' => 5,
            'top10' => 10,
            default => null,
        };

        // "Sold" only counts orders the admin has actually confirmed (and
        // posted to BC) — pending and cancelled orders are not real sales.
        $soldStatuses = ['confirmed', 'on-the-way', 'delivered', 'delivery'];

        $buyersQuery = $this->orderItemsForItem($companyId, $item->id)
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
            ->whereIn('o.status', $soldStatuses)
            ->when($buyerSearch !== '', function ($q) use ($buyerSearch) {
                $q->where('u.name', 'like', "%{$buyerSearch}%");
            })
            ->groupBy('u.id', 'u.name')
            ->orderByDesc(DB::raw('SUM(oi.qty)'))
            ->orderByDesc(DB::raw('SUM(oi.line_total)'))
            ->selectRaw('
                u.id as buyer_id,
                COALESCE(u.name, \'Unknown Buyer\') as buyer_name,
                COUNT(DISTINCT o.id) as total_orders,
                COALESCE(SUM(oi.qty), 0) as total_qty,
                COALESCE(SUM(oi.line_total), 0) as total_spent,
                MAX(o.checked_out_at) as last_bought_at
            ');

        if ($topLimit) {
            $buyersQuery->limit($topLimit);
        }

        $buyerRows = $buyersQuery->get();

        $buyerStats = [
            'unique_buyers' => (int) $this->orderItemsForItem($companyId, $item->id)
                ->whereIn('o.status', $soldStatuses)
                ->distinct('o.user_id')
                ->count('o.user_id'),
            'total_sold_qty' => (int) $this->orderItemsForItem($companyId, $item->id)
                ->whereIn('o.status', $soldStatuses)
                ->sum('oi.qty'),
            'total_revenue' => (float) $this->orderItemsForItem($companyId, $item->id)
                ->whereIn('o.status', $soldStatuses)
                ->sum('oi.line_total'),
        ];

        $statusCounts = $this->orderItemsForItem($companyId, $item->id)
            ->groupBy('o.status')
            ->selectRaw('o.status as status, COUNT(DISTINCT o.id) as total')
            ->pluck('total', 'status');

        $orderStats = [
            'pending'    => (int) ($statusCounts['pending'] ?? 0),
            'confirmed'  => (int) ($statusCounts['confirmed'] ?? 0),
            'on_the_way' => (int) ($statusCounts['on-the-way'] ?? 0),
            'delivered'  => (int) (($statusCounts['delivered'] ?? 0) + ($statusCounts['delivery'] ?? 0)),
            'cancelled'  => (int) (($statusCounts['cancelled'] ?? 0) + ($statusCounts['canceled'] ?? 0)),
        ];

        // Who's waiting on this item right now, broken out per status tab —
        // customer, qty, amount — so admin doesn't have to go hunting through
        // the full order list to see what a status count is made of.
        $statusGroups = [
            'pending'    => ['pending'],
            'confirmed'  => ['confirmed'],
            'on_the_way' => ['on-the-way'],
            'delivered'  => ['delivered', 'delivery'],
            'cancelled'  => ['cancelled', 'canceled'],
        ];

        $orderRows = $this->orderItemsForItem($companyId, $item->id)
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
            ->whereIn('o.status', array_merge(...array_values($statusGroups)))
            ->groupBy('o.id', 'o.order_no', 'o.created_at', 'u.name', 'o.status')
            ->orderByDesc('o.created_at')
            ->selectRaw('
                o.status as status,
                COALESCE(u.name, \'Unknown Buyer\') as buyer_name,
                o.order_no,
                SUM(oi.qty) as qty,
                SUM(oi.line_total) as line_total,
                o.created_at
            ')
            ->get();

        $statusRows = [];
        foreach ($statusGroups as $key => $rawStatuses) {
            $statusRows[$key] = $orderRows->whereIn('status', $rawStatuses)->values();
        }

        $pendingRows = $statusRows['pending'];

        // Stock is only decremented when an order is confirmed, so a pile of
        // *pending* qty against current stock is invisible unless we surface
        // it here — warn once pending demand reaches 80% of what's on hand,
        // and escalate to critical once it would oversell the item outright.
        $pendingQtyTotal = (int) $pendingRows->sum('qty');
        $currentStock = (int) $item->inventory;
        $stockRiskLevel = null;

        if ($pendingQtyTotal > 0) {
            if ($pendingQtyTotal >= $currentStock) {
                $stockRiskLevel = 'critical';
            } elseif ($pendingQtyTotal >= 0.8 * $currentStock) {
                $stockRiskLevel = 'warning';
            }
        }

        $stockRisk = [
            'level' => $stockRiskLevel,
            'pending_qty' => $pendingQtyTotal,
            'stock' => $currentStock,
            'remaining_if_confirmed' => $currentStock - $pendingQtyTotal,
        ];

        return view('POSViews.POSAdminViews.StoreManagement.product-detail', [
            'item' => $item,
            'buyerRows' => $buyerRows,
            'buyerStats' => $buyerStats,
            'buyerSearch' => $buyerSearch,
            'buyerFilter' => $buyerFilter,
            'orderStats' => $orderStats,
            'pendingRows' => $pendingRows,
            'statusRows' => $statusRows,
            'stockRisk' => $stockRisk,
        ]);
    }

    // Show the "Update Images" page for one item (main photo + all variant photos)
    public function editImages(int $id)
    {
        $companyId = session('selected_company_id');

        $item = Item::where('company_id', $companyId)->findOrFail($id);

        $variants = ItemVariant::where('item_id', $item->id)->get();

        $status = ItemSetupStatus::where('item_id', $item->id)->first();
        $isUpdated = $status && $status->main_image_done && $status->variants_done;

        return view('POSViews.POSAdminViews.StoreManagement.product-images', compact('item', 'variants', 'isUpdated'));
    }

    // Upload / replace the main photo for one item.
    // Saved to custom_image_url (NOT image_url) so it survives future BC syncs,
    // since syncFromAl() always overwrites image_url with the BC photo.
    public function uploadMainImage(Request $request, int $id)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $companyId = session('selected_company_id');

        $item = Item::where('company_id', $companyId)->findOrFail($id);

        $path = $request->file('image')->store('item-main-images', 'public');

        $item->custom_image_url = Storage::url($path);
        $item->save();

        // Mark that the main image has been set up for this item
        $status = ItemSetupStatus::firstOrNew(['item_id' => $item->id]);
        $status->main_image_done = true;
        $status->save();

        return response()->json([
            'success' => true,
            'image_url' => $item->custom_image_url,
        ]);
    }

    // Toggle an item's image setup (main photo + variants) between done/not
    // done, without requiring a fresh upload. Used by the "Mark as Updated"
    // button on the product-images page.
    public function markUpdated(int $id)
    {
        $companyId = session('selected_company_id');

        $item = Item::where('company_id', $companyId)->findOrFail($id);

        $status = ItemSetupStatus::firstOrNew(['item_id' => $item->id]);
        $isCurrentlyDone = $status->exists && $status->main_image_done && $status->variants_done;

        $newState = !$isCurrentlyDone;
        $status->main_image_done = $newState;
        $status->variants_done = $newState;
        $status->save();

        return response()->json([
            'success' => true,
            'is_updated' => $newState,
        ]);
    }

    /**
     * Base query for "order_items belonging to this item, for this company"
     * — every stat on the Product Detail page (buyer list, totals, status
     * counts, per-status order rows) starts from exactly this same join, so
     * it lives in one place instead of six near-identical copies. Callers
     * add their own whereIn('o.status', ...), select, group, etc. on top.
     */
    private function orderItemsForItem(int $companyId, int $itemId)
    {
        return OrderItem::query()
            ->from('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->where('oi.company_id', $companyId)
            ->where('oi.item_id', $itemId);
    }
}
