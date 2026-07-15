<?php

namespace App\Http\Controllers\Api\POS\Admin\Items;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\ItemVariant;
use App\Models\POS\InventoryMovement;
use App\Models\ManagementSystem\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ItemPosController extends Controller
{
    public function index()
    {
        $token = $this->getToken();
        $url = $this->bcEndpoint('items_endpoint', 'items');

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
                'discount_end_date',
                'is_visible',
                'custom_image_url'
            )
            ->get()
            ->keyBy('bc_id');

        foreach ($items as $index => &$item) {
            $item['id'] = $item['id'] ?? $item['systemId'] ?? $item['SystemId'] ?? null;

            if (empty($item['id'])) {
                unset($items[$index]);
                continue;
            }

            $localItem = $localItems[$item['id']] ?? null;
            $blocked = filter_var(
                $item['blocked'] ?? $item['Blocked'] ?? $item['isBlocked'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            );

            if ($blocked) {
                unset($items[$index]);
                continue;
            }

            // Hide products marked inactive from Store Management.
            if ($localItem && !$localItem->is_visible) {
                unset($items[$index]);
                continue;
            }

            $item['number'] = $item['number']
                ?? $item['no']
                ?? $item['No']
                ?? $item['itemNo']
                ?? $item['itemNumber']
                ?? null;

            $item['displayName'] = $item['displayName']
                ?? $item['display_name']
                ?? $item['description']
                ?? $item['Description']
                ?? $item['name']
                ?? null;

            $item['unitPrice'] = $item['unitPrice']
                ?? $item['unit_price']
                ?? $item['price']
                ?? $item['UnitPrice']
                ?? 0;

            $item['inventory'] = $item['inventory']
                ?? $item['Inventory']
                ?? $item['quantityOnHand']
                ?? $item['qtyOnHand']
                ?? 0;

            $item['blocked'] = $blocked;

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

          $item['discountStartDate'] =
    $item['discountStartDate']
    ?? $item['discount_start_date']
    ?? $item['discountstartdate']
    ?? ($localItem?->discount_start_date?->format('Y-m-d H:i:s'));

$item['discountEndDate'] =
    $item['discountEndDate']
    ?? $item['discount_end_date']
    ?? $item['discountenddate']
    ?? ($localItem?->discount_end_date?->format('Y-m-d H:i:s'));

            // Custom uploaded photo, if any (protected from BC sync overwrites)
            $item['customImageUrl'] = $localItem->custom_image_url ?? null;
        }

        $items = array_values($items);

        return view('POSViews.POSAdminViews.Items.index', compact('items'));
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
            ?? optional(optional($localItem)->discount_start_date)->format('Y-m-d H:i:s');

        $item['discountEndDate'] = $item['discountEndDate']
            ?? $item['discount_end_date']
            ?? $item['discountenddate']
            ?? optional(optional($localItem)->discount_end_date)->format('Y-m-d H:i:s');

        // Custom uploaded photo, if any (protected from BC sync overwrites)
        $item['customImageUrl'] = $localItem->custom_image_url ?? null;

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
        $actorId = Auth::id();

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

        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $item) {
                $incomingInventory = (int) ($item['inventory'] ?? 0);

                $existing = Item::where('company_id', $companyId)
                    ->where('bc_id', $item['id'])
                    ->first();

                $oldInventory = (int) ($existing->inventory ?? 0);

                $saved = Item::updateOrCreate(
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

                        'inventory' => $incomingInventory,
                        'blocked' => filter_var($item['blocked'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'item_category_code' => $item['itemCategoryCode'] ?? null,
                        'base_unit_of_measure_code' => $item['baseUnitOfMeasureCode'] ?? null,
                        'price_includes_tax' => filter_var($item['priceIncludesTax'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'image_url' => $item['imageUrl'] ?? null,
                        'default_location_code' => $item['defaultLocationCode'] ?? null,
                    ]
                );

                $change = $incomingInventory - $oldInventory;
                if ($change !== 0 || !$existing) {
                    $delta = $change !== 0 ? $change : $incomingInventory;
                    $today = now()->toDateString();

                    // Merge same-day sync logs per item so tracking is cleaner:
                    // one row per item per day, while still keeping pull date visibility.
                    $sameDaySync = InventoryMovement::query()
                        ->where('company_id', $companyId)
                        ->where('item_id', $saved->id)
                        ->where('source', 'sync')
                        ->whereNull('order_id')
                        ->whereDate('happened_at', $today)
                        ->latest('id')
                        ->first();

                    if ($sameDaySync) {
                        $sameDaySync->quantity_change = (int) $sameDaySync->quantity_change + (int) $delta;
                        $sameDaySync->new_inventory = $incomingInventory;
                        $sameDaySync->happened_at = now();
                        $sameDaySync->actor_user_id = $actorId;
                        $sameDaySync->reference_no = $saved->number;
                        $sameDaySync->note = 'Inventory synced from BC (merged same-day pull).';
                        $sameDaySync->save();
                    } else {
                        InventoryMovement::create([
                            'company_id' => $companyId,
                            'item_id' => $saved->id,
                            'order_id' => null,
                            'actor_user_id' => $actorId,
                            'buyer_user_id' => null,
                            'source' => 'sync',
                            'quantity_change' => $delta,
                            'old_inventory' => $oldInventory,
                            'new_inventory' => $incomingInventory,
                            'happened_at' => now(),
                            'reference_no' => $saved->number,
                            'note' => $existing
                                ? 'Inventory updated from BC sync.'
                                : 'New item created from BC sync.',
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
            ], 500);
        }

        // Also sync variants right after items, so one click does both
        $variantResult = $this->syncVariantsFromBc();

        return response()->json([
            'success' => true,
            'message' => 'Items and variants synced successfully.',
            'count' => count($validated['items']),
            'variantsSaved' => $variantResult['saved'],
            'variantsSkipped' => $variantResult['skipped'],
        ]);
    }

    // Pulls item variants from Business Central and saves them locally.
    // Called automatically at the end of syncFromAl(), so one button click does both.
    private function syncVariantsFromBc()
    {
        $token = $this->getToken();

        if (!$token) {
            return ['saved' => 0, 'skipped' => 0];
        }

        $url = $this->bcEndpoint('item_variants_endpoint', 'itemVariants');

        if (!$url) {
            return ['saved' => 0, 'skipped' => 0];
        }

        $response = Http::withoutVerifying()->withToken($token)->get($url);

        if (!$response->successful()) {
            return ['saved' => 0, 'skipped' => 0];
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

        return ['saved' => $savedCount, 'skipped' => $skippedCount];
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

        // Custom uploaded photo, if any (protected from BC sync overwrites)
        $item['customImageUrl'] = $localItem->custom_image_url ?? null;

        return view('POSViews.POSAdminViews.Items.show', compact('item'));
    }
}
