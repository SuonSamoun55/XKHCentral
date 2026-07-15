<?php

namespace App\Http\Controllers\Api\POS\Admin\Items;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\ItemVariant;
use App\Models\POS\ItemSetupStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ItemVariantPosController extends Controller
{
    // Show all variants for one item (used by the item detail page)
    public function index($itemId)
    {
        $variants = ItemVariant::where('item_id', $itemId)->get();

        return response()->json($variants);
    }

    // Get variants from Business Central and save them (also called automatically
    // from ItemPosController::syncFromAl, kept here too in case you want a
    // standalone variant-only sync button somewhere)
    public function syncFromBc()
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Login to Business Central failed'], 401);
        }

        $url = $this->bcEndpoint('item_variants_endpoint', 'itemVariants');

        if (!$url) {
            return response()->json(['error' => 'Could not build BC URL'], 422);
        }

        $response = Http::withoutVerifying()->withToken($token)->get($url);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get variants from BC',
            ], 500);
        }

        $variants = $response->json()['value'] ?? [];

        $savedCount = 0;
        $skippedCount = 0;

        foreach ($variants as $variant) {

            $itemNumber = $variant['itemNo'] ?? null;
            $bcId = $variant['id'] ?? null;
            $code = $variant['code'] ?? null;

            if (!$itemNumber || !$bcId || !$code) {
                $skippedCount = $skippedCount + 1;
                continue;
            }

            $localItem = Item::where('number', $itemNumber)->first();

            if (!$localItem) {
                $skippedCount = $skippedCount + 1;
                continue;
            }

            $existingVariant = ItemVariant::where('bc_id', $bcId)->first();

            if ($existingVariant) {
                $existingVariant->item_id = $localItem->id;
                $existingVariant->item_number = $itemNumber;
                $existingVariant->code = $code;
                $existingVariant->description = $variant['description'] ?? null;
                $existingVariant->description2 = $variant['description2'] ?? null;
                $existingVariant->blocked = $variant['blocked'] ?? false;
                $existingVariant->sales_blocked = $variant['salesBlocked'] ?? false;
                $existingVariant->purchasing_blocked = $variant['purchasingBlocked'] ?? false;
                $existingVariant->save();
            } else {
                $newVariant = new ItemVariant();
                $newVariant->item_id = $localItem->id;
                $newVariant->bc_id = $bcId;
                $newVariant->item_number = $itemNumber;
                $newVariant->code = $code;
                $newVariant->description = $variant['description'] ?? null;
                $newVariant->description2 = $variant['description2'] ?? null;
                $newVariant->blocked = $variant['blocked'] ?? false;
                $newVariant->sales_blocked = $variant['salesBlocked'] ?? false;
                $newVariant->purchasing_blocked = $variant['purchasingBlocked'] ?? false;
                $newVariant->save();
            }

            $savedCount = $savedCount + 1;
        }

        return response()->json([
            'success' => true,
            'message' => 'Variants synced',
            'saved' => $savedCount,
            'skipped' => $skippedCount,
        ]);
    }

    // Upload an image for one variant
    public function uploadImage(Request $request, $variantId)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $variant = ItemVariant::findOrFail($variantId);

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

    // Admin page: list every item that has variants, so images can be uploaded
    public function manage()
    {
        // Get all variants first
        $allVariants = ItemVariant::all();

        // Group variants by item_id
        $variantsByItem = $allVariants->groupBy('item_id');

        // Get only the items that actually have variants
        $itemIds = $variantsByItem->keys();
        $items = Item::whereIn('id', $itemIds)->orderBy('display_name')->get();

        // Attach the variants manually to each item
        foreach ($items as $item) {
            $item->variantList = $variantsByItem[$item->id] ?? collect();
        }

        return view('POSViews.POSAdminViews.Items.variants-manage', compact('items'));
    }
}