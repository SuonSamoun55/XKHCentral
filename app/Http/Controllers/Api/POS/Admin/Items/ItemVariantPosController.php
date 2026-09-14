<?php

namespace App\Http\Controllers\Api\POS\Admin\Items;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\ItemVariant;
use App\Models\POS\ItemSetupStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemVariantPosController extends Controller
{
    public function index($itemId)
    {
        $item = Item::where('id', $itemId)
            ->where('company_id', session('selected_company_id'))
            ->firstOrFail();

        $variants = ItemVariant::where('item_id', $item->id)->get();

        return response()->json($variants);
    }
    public function uploadImage(Request $request, $variantId)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $variant = ItemVariant::whereHas('item', function ($q) {
            $q->where('company_id', session('selected_company_id'));
        })->findOrFail($variantId);

        $path = $request->file('image')->store('item-variants', 'public');

        $variant->image_url = Storage::url($path);
        $variant->save();

        // Check if ALL variants for this item now have images
        $totalVariants = ItemVariant::where('item_id', $variant->item_id)->count();
        $variantsWithImage = ItemVariant::where('item_id', $variant->item_id)
            ->whereNotNull('image_url')
            ->where('image_url', '!=', '')
            ->count();

        $allDone = $totalVariants > 0 && $totalVariants === $variantsWithImage;

        $status = ItemSetupStatus::firstOrNew(['item_id' => $variant->item_id]);
        $status->variants_done = $allDone;
        $status->save();

        return response()->json([
            'success' => true,
            'image_url' => $variant->image_url,
        ]);
    }

    public function manage()
    {
        $companyId = session('selected_company_id');

        $allVariants = ItemVariant::whereHas('item', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->get();

        $variantsByItem = $allVariants->groupBy('item_id');

        $itemIds = $variantsByItem->keys();
        $items = Item::whereIn('id', $itemIds)->where('company_id', $companyId)->orderBy('display_name')->get();
        foreach ($items as $item) {
            $item->variantList = $variantsByItem[$item->id] ?? collect();
        }

        return view('POSViews.POSAdminViews.Items.variants-manage', compact('items'));
    }
}
