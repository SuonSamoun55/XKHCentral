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
    public function index($itemId)
    {
        $item = Item::where('id', $itemId)
            ->where('company_id', session('selected_company_id'))
            ->firstOrFail();

        $variants = ItemVariant::where('item_id', $item->id)->get();

        return response()->json($variants);
    }
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

    // Admin page: list every item that has variants, so images can be uploaded
    public function manage()
    {
        $companyId = session('selected_company_id');

        // Get all variants for this company's items first
        $allVariants = ItemVariant::whereHas('item', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->get();

        // Group variants by item_id
        $variantsByItem = $allVariants->groupBy('item_id');

        // Get only the items that actually have variants
        $itemIds = $variantsByItem->keys();
        $items = Item::whereIn('id', $itemIds)->where('company_id', $companyId)->orderBy('display_name')->get();

        // Attach the variants manually to each item
        foreach ($items as $item) {
            $item->variantList = $variantsByItem[$item->id] ?? collect();
        }

        return view('POSViews.POSAdminViews.Items.variants-manage', compact('items'));
    }
}
