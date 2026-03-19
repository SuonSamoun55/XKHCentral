<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ItemPosController extends Controller
{
    public function index()
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Business Central authentication failed'], 401);
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($this->bcUrl("items?\$filter=blocked eq false"));
    
        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch items from API',
                'details' => $response->body()
            ], 500);
        }

        $items = $response->json()['value'] ?? [];

        // attach local saved location if available
        $localItems = Item::select('bc_id', 'default_location_code')->get()->keyBy('bc_id');

        foreach ($items as &$item) {
            $item['defaultLocationCode'] = $item['defaultLocationCode']
                ?? $item['locationCode']
                ?? ($localItems[$item['id']]->default_location_code ?? null);
        }

        return view('POSViews.POSAdminViews.ItemList', compact('items'));
    }

    public function showItem(string $id)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Auth failed'], 401);
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($this->bcUrl("items({$id})"));

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch item',
                'details' => $response->body()
            ], 500);
        }

        $item = $response->json();

        $localItem = Item::where('bc_id', $id)->first();

        $item['defaultLocationCode'] = $item['defaultLocationCode']
            ?? $item['locationCode']
            ?? ($localItem->default_location_code ?? null);

        return response()->json($item);
    }

    public function getItemImage(string $itemId)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Auth failed'], 401);
        }

        $contentUrl = $this->bcUrl("items({$itemId})/picture/pictureContent");

        $imageResponse = Http::withoutVerifying()
            ->withToken($token)
            ->withHeaders([
                'Accept' => 'image/jpeg, image/png, image/*'
            ])
            ->get($contentUrl);

        if (!$imageResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch image',
                'details' => $imageResponse->body()
            ], 500);
        }

        $contentType = $imageResponse->header('Content-Type') ?: 'image/jpeg';
        $contentType = explode(';', $contentType)[0];

        return response($imageResponse->body())
            ->header('Content-Type', $contentType)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    public function syncFromAl(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'string'],
            'items.*.number' => ['required', 'string'],
            'items.*.displayName' => ['nullable', 'string'],
            'items.*.unitPrice' => ['nullable', 'numeric'],
            'items.*.inventory' => ['nullable', 'numeric'],
            'items.*.blocked' => ['nullable'],
            'items.*.itemCategoryCode' => ['nullable', 'string'],
            'items.*.baseUnitOfMeasureCode' => ['nullable', 'string'],
            'items.*.priceIncludesTax' => ['nullable'],
            'items.*.imageUrl' => ['nullable', 'string'],

            // auto location from BC
            'items.*.defaultLocationCode' => ['nullable', 'string'],
        ]);

        foreach ($validated['items'] as $item) {
            $imageUrl = !empty($item['imageUrl']) ? $item['imageUrl'] : null;

            Item::updateOrCreate(
                ['bc_id' => $item['id']],
                [
                    'number' => $item['number'],
                    'display_name' => $item['displayName'] ?? null,
                    'unit_price' => $item['unitPrice'] ?? 0,
                    'inventory' => (int) ($item['inventory'] ?? 0),
                    'blocked' => filter_var($item['blocked'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'item_category_code' => $item['itemCategoryCode'] ?? null,
                    'base_unit_of_measure_code' => $item['baseUnitOfMeasureCode'] ?? null,
                    'price_includes_tax' => filter_var($item['priceIncludesTax'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'image_url' => $imageUrl,
                    'default_location_code' => $item['defaultLocationCode'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Items synced successfully.',
            'count' => count($validated['items']),
        ]);
    }
}
