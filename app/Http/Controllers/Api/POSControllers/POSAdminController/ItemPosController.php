<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;
use App\Models\MagamentSystemModel\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ItemPosController extends Controller
{
    public function index()
    {
        $token = $this->getToken();
        $url = $this->bcEndpoint('items_endpoint', "items?\$filter=blocked eq false");

        if (!$token) {
            return response()->json([
                'error' => 'Business Central authentication failed',
            ], 401);
        }

        if (!$url) {
            return response()->json([
                'error' => 'Business Central URL could not be built',
            ], 422);
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($url);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch items from API',
                'details' => $response->body()
            ], 500);
        }

        $items = $response->json()['value'] ?? [];

        $localItems = Item::select(
                'bc_id',
                'default_location_code',
                'base_unit_of_measure_code',
                'vat_percent',
                'tax_amount',
                'discount_amount',
                'discount_start_date',
                'discount_end_date'
            )
            ->get()
            ->keyBy('bc_id');

        foreach ($items as &$item) {
            $localItem = $localItems[$item['id']] ?? null;

            $item['defaultLocationCode'] = $item['defaultLocationCode']
                ?? $item['locationCode']
                ?? ($localItem->default_location_code ?? null);

            $item['baseUnitOfMeasureCode'] = $item['baseUnitOfMeasureCode']
                ?? ($localItem->base_unit_of_measure_code ?? null)
                ?? 'PCS';

            $item['vatPercent'] = $item['vatPercent']
                ?? $item['vat_percentage']
                ?? $item['vatpercent']
                ?? ($localItem->vat_percent ?? 0);

            $item['taxAmount'] = $item['taxAmount']
                ?? $item['tax_amount']
                ?? $item['taxamount']
                ?? ($localItem->tax_amount ?? 0);

            $item['discountAmount'] = $item['discountAmount']
                ?? $item['discount_amount']
                ?? $item['discountamount']
                ?? ($localItem->discount_amount ?? 0);

            $item['discountStartDate'] = $item['discountStartDate']
                ?? $item['discount_start_date']
                ?? $item['discountstartdate']
                ?? optional($localItem->discount_start_date)->format('Y-m-d H:i:s');

            $item['discountEndDate'] = $item['discountEndDate']
                ?? $item['discount_end_date']
                ?? $item['discountenddate']
                ?? optional($localItem->discount_end_date)->format('Y-m-d H:i:s');
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

        $item['baseUnitOfMeasureCode'] = $item['baseUnitOfMeasureCode']
            ?? ($localItem->base_unit_of_measure_code ?? null)
            ?? 'PCS';

        $item['vatPercent'] = $item['vatPercent']
            ?? $item['vat_percentage']
            ?? $item['vatpercent']
            ?? ($localItem->vat_percent ?? 0);

        $item['taxAmount'] = $item['taxAmount']
            ?? $item['tax_amount']
            ?? $item['taxamount']
            ?? ($localItem->tax_amount ?? 0);

        $item['discountAmount'] = $item['discountAmount']
            ?? $item['discount_amount']
            ?? $item['discountamount']
            ?? ($localItem->discount_amount ?? 0);

        $item['discountStartDate'] = $item['discountStartDate']
            ?? $item['discount_start_date']
            ?? $item['discountstartdate']
            ?? optional($localItem->discount_start_date)->format('Y-m-d H:i:s');

        $item['discountEndDate'] = $item['discountEndDate']
            ?? $item['discount_end_date']
            ?? $item['discountenddate']
            ?? optional($localItem->discount_end_date)->format('Y-m-d H:i:s');

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
        $companyId = Company::value('id');

        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'No company found.',
            ], 422);
        }

        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'string'],
            'items.*.number' => ['required', 'string'],
            'items.*.displayName' => ['nullable', 'string'],
            'items.*.unitPrice' => ['nullable', 'numeric'],

            'items.*.vatPercent' => ['nullable', 'numeric'],
            'items.*.taxAmount' => ['nullable', 'numeric'],
            'items.*.discountAmount' => ['nullable', 'numeric'],
            'items.*.discountStartDate' => ['nullable', 'date'],
            'items.*.discountEndDate' => ['nullable', 'date'],

            'items.*.inventory' => ['nullable', 'numeric'],
            'items.*.blocked' => ['nullable'],
            'items.*.itemCategoryCode' => ['nullable', 'string'],
            'items.*.baseUnitOfMeasureCode' => ['nullable', 'string'],
            'items.*.priceIncludesTax' => ['nullable'],
            'items.*.imageUrl' => ['nullable', 'string'],
            'items.*.defaultLocationCode' => ['nullable', 'string'],
        ]);

        foreach ($validated['items'] as $item) {
            Item::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'bc_id' => $item['id'],
                ],
                [
                    'number' => $item['number'],
                    'display_name' => $item['displayName'] ?? null,
                    'unit_price' => $item['unitPrice'] ?? 0,

                    'vat_percent' => $item['vatPercent'] ?? 0,
                    'tax_amount' => $item['taxAmount'] ?? 0,
                    'discount_amount' => $item['discountAmount'] ?? 0,
                    'discount_start_date' => $item['discountStartDate'] ?? null,
                    'discount_end_date' => $item['discountEndDate'] ?? null,

                    'inventory' => (int) ($item['inventory'] ?? 0),
                    'blocked' => filter_var($item['blocked'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'item_category_code' => $item['itemCategoryCode'] ?? null,
                    'base_unit_of_measure_code' => $item['baseUnitOfMeasureCode'] ?? null,
                    'price_includes_tax' => filter_var($item['priceIncludesTax'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'image_url' => $item['imageUrl'] ?? null,
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

    public function detail(string $id)
    {
        $token = $this->getToken();

        if (!$token) {
            return redirect()->back()->with('error', 'Business Central authentication failed.');
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($this->bcUrl("items({$id})"));

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Failed to fetch item detail.');
        }

        $item = $response->json();

        $localItem = Item::where('bc_id', $id)->first();

        $item['defaultLocationCode'] = $item['defaultLocationCode']
            ?? $item['locationCode']
            ?? ($localItem->default_location_code ?? null);

        $item['baseUnitOfMeasureCode'] = $item['baseUnitOfMeasureCode']
            ?? ($localItem->base_unit_of_measure_code ?? null)
            ?? 'PCS';

        $item['vatPercent'] = $item['vatPercent']
            ?? $item['vat_percentage']
            ?? $item['vatpercent']
            ?? ($localItem->vat_percent ?? 0);

        $item['taxAmount'] = $item['taxAmount']
            ?? $item['tax_amount']
            ?? $item['taxamount']
            ?? ($localItem->tax_amount ?? 0);

        $item['discountAmount'] = $item['discountAmount']
            ?? $item['discount_amount']
            ?? $item['discountamount']
            ?? ($localItem->discount_amount ?? 0);

        $item['discountStartDate'] = $item['discountStartDate']
            ?? $item['discount_start_date']
            ?? $item['discountstartdate']
            ?? optional($localItem->discount_start_date)->format('Y-m-d H:i:s');

        $item['discountEndDate'] = $item['discountEndDate']
            ?? $item['discount_end_date']
            ?? $item['discountenddate']
            ?? optional($localItem->discount_end_date)->format('Y-m-d H:i:s');

        return view('POSViews.POSAdminViews.ItemDetail', compact('item'));
    }
}
    
