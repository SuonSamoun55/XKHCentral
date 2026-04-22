<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;
use App\Models\POSModel\InventoryMovement;
use App\Models\POSModel\OrderItem;
use App\Models\MagamentSystemModel\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreManagementController extends Controller
{
    public function index(Request $request)
    {
        $companyId = Company::value('id');

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
            ]);
        }

        return view('POSViews.POSAdminViews.StoreManagement.index', compact(
            'products',
            'categories',
            'productCount',
            'categoryCount'
        ));
    }

    public function toggleProduct(Request $request, $id)
    {
        $companyId = Company::value('id');

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
        $companyId = Company::value('id');

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
        $companyId = Company::value('id');
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
        $companyId = Company::value('id');
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
        $companyId = Company::value('id');

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
        $companyId = Company::value('id');

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

        $buyersQuery = OrderItem::query()
            ->from('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
            ->where('oi.company_id', $companyId)
            ->where('oi.item_id', $item->id)
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
            'unique_buyers' => (int) OrderItem::query()
                ->from('order_items as oi')
                ->join('orders as o', 'o.id', '=', 'oi.order_id')
                ->where('oi.company_id', $companyId)
                ->where('oi.item_id', $item->id)
                ->distinct('o.user_id')
                ->count('o.user_id'),
            'total_sold_qty' => (int) OrderItem::query()
                ->where('company_id', $companyId)
                ->where('item_id', $item->id)
                ->sum('qty'),
            'total_revenue' => (float) OrderItem::query()
                ->where('company_id', $companyId)
                ->where('item_id', $item->id)
                ->sum('line_total'),
        ];

        return view('POSViews.POSAdminViews.StoreManagement.product-detail', [
            'item' => $item,
            'buyerRows' => $buyerRows,
            'buyerStats' => $buyerStats,
            'buyerSearch' => $buyerSearch,
            'buyerFilter' => $buyerFilter,
        ]);
    }
}
