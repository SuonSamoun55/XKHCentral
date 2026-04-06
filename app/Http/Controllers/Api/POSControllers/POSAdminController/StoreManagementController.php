<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;
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
}
