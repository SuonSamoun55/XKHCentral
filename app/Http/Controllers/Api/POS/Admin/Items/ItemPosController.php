<?php

namespace App\Http\Controllers\Api\POS\Admin\Items;

use App\Http\Controllers\Concerns\ResolvesImageUrl;
use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\ItemVariant;
use App\Models\POS\ItemLocationInventory;
use App\Models\POS\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ItemPosController extends Controller
{
    use ResolvesImageUrl;

    public function index()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return response()->json([
                'error' => 'Select a company first (Companies list) before selling.',
            ], 422);
        }
        $items = Item::where('company_id', $companyId)
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->orderBy('display_name')
            ->get()
            ->map(fn(Item $item) => $this->toDisplayItem($item))
            ->values()
            ->all();

        return view('POSViews.POSAdminViews.Items.index', compact('items'));
    }

    protected function toDisplayItem(Item $item): array
    {
        return [
            'id' => $item->bc_id,
            'number' => $item->number,
            'displayName' => $item->display_name,
            'unitPrice' => (float) $item->unit_price,
            'inventory' => (float) $item->inventory,
            'blocked' => (bool) $item->blocked,
            'defaultLocationCode' => $item->default_location_code,
            'baseUnitOfMeasureCode' => $item->base_unit_of_measure_code ?? 'PCS',
            'itemCategoryCode' => $item->item_category_code,
            'taxGroupCode' => $item->tax_group_code,
            'vatPercent' => $item->resolved_vat_percent ?? 0,
            'taxAmount' => (float) $item->tax_amount,
            'discountAmount' => (float) $item->discount_amount,
            'discountStartDate' => optional($item->discount_start_date)->format('Y-m-d H:i:s'),
            'discountEndDate' => optional($item->discount_end_date)->format('Y-m-d H:i:s'),
            'localItemId' => $item->id,
            'customImageUrl' => $item->custom_image_url,
            'imageUrl' => $this->resolveImageUrl($item->custom_image_url ?: $item->image_url),
        ];
    }

    public function showItem(string $id)
    {
        $localItem = Item::where('bc_id', $id)
            ->where('company_id', session('selected_company_id'))
            ->first();

        if (!$localItem) {
            return response()->json(['error' => 'Item not found. Sync it from BC first.'], 404);
        }

        return response()->json($this->toDisplayItem($localItem));
    }

    public function syncFromAl(Request $request)
    {
        $companyId = session('selected_company_id');
        $actorId = Auth::id();

        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'Select a company first (Companies list) before syncing.',
            ], 422);
        }

        $token = $this->getToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Business Central authentication failed.',
            ], 401);
        }

        $url = $this->bcEndpoint('items_endpoint', 'items');

        if (!$url) {
            return response()->json([
                'success' => false,
                'message' => 'Business Central URL could not be built.',
            ], 422);
        }

        $response = Http::withoutVerifying()->withToken($token)->get($url);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch items from Business Central.',
                'details' => $response->body(),
            ], 500);
        }

        $rawItems = $response->json()['value'] ?? [];

        DB::beginTransaction();

        try {
            $syncedCount = 0;
            foreach ($rawItems as $item) {
                $bcId = $this->valueFrom($item, ['id', 'systemId', 'SystemId']);

                if (!$bcId) {
                    continue;
                }

                if (!$this->toBool($item['itemButtonAllowApi'] ?? null, true)) {
                    continue;
                }

                $syncedCount++;

                $fields = $this->extractBcItemFields($item, $bcId);
                $incomingInventory = $fields['inventory'];

                $existing = Item::where('company_id', $companyId)
                    ->where('bc_id', $fields['bc_id'])
                    ->first();
                $imagePath = $token
                    ? $this->downloadItemImage($fields['bc_id'], $token)
                    : null;

                $oldInventory = (float) ($existing->inventory ?? 0);
                $saved = Item::updateOrCreate(
                    [
                        'company_id' => $companyId,
                        'bc_id' => $fields['bc_id'],
                    ],
                    [
                        'number' => $fields['number'],
                        'display_name' => $fields['display_name'],
                        'unit_price' => $fields['unit_price'],

                        'tax_group_code' => $fields['tax_group_code'],
                        'tax_amount' => $fields['tax_amount'],
                        'discount_amount' => $fields['discount_amount'],
                        'discount_start_date' => $fields['discount_start_date'],
                        'discount_end_date' => $fields['discount_end_date'],

                        'inventory' => $incomingInventory,
                        'blocked' => $fields['blocked'],
                        'item_category_code' => $fields['item_category_code'],
                        'base_unit_of_measure_code' => $fields['base_unit_of_measure_code'],
                        'price_includes_tax' => $fields['price_includes_tax'],
                        'image_url' => $imagePath ?? $existing->image_url ?? null,
                        'default_location_code' => $fields['default_location_code'],
                    ]
                );

                if ($token) {
                    $this->syncItemLocationInventory($saved, $fields['bc_id'], $token, $companyId);
                }

                $change = $incomingInventory - $oldInventory;
                if ($change !== 0 || !$existing) {
                    $delta = $change !== 0 ? $change : $incomingInventory;
                    $today = now()->toDateString();

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

        try {
            $variantResult = $this->syncVariantsFromBc($companyId);
        } catch (\Throwable $e) {
            logger()->error('Item variant sync threw an exception', [
                'company_id' => $companyId,
                'message' => $e->getMessage(),
            ]);

            $variantResult = ['saved' => 0, 'skipped' => 0, 'error' => 'Unexpected error: ' . $e->getMessage()];
        }

        return response()->json([
            'success' => true,
            'message' => 'Items and variants synced successfully.',
            'count' => $syncedCount,
            'variantsSaved' => $variantResult['saved'],
            'variantsSkipped' => $variantResult['skipped'],
            'variantsError' => $variantResult['error'] ?? null,
        ]);
    }

    protected function extractBcItemFields(array $item, string $bcId): array
    {
        return [
            'bc_id' => $bcId,
            'number' => $this->valueFrom($item, ['number', 'no', 'No', 'itemNo', 'itemNumber']),
            'display_name' => $this->valueFrom($item, ['displayName', 'display_name', 'description', 'Description', 'name']),
            'unit_price' => $this->valueFrom($item, ['unitPrice', 'unit_price', 'price', 'UnitPrice'], 0),

            'tax_group_code' => $this->valueFrom($item, ['taxGroupCode', 'taxgroupcode', 'vatProdPostingGroup', 'vatprodpostinggroup']),
            'tax_amount' => $this->valueFrom($item, ['taxAmount', 'tax_amount', 'taxamount'], 0),
            'discount_amount' => $this->valueFrom($item, ['discountAmount', 'discount_amount', 'discountamount'], 0),
            'discount_start_date' => $this->valueFrom($item, ['discountStartDate', 'discount_start_date', 'discountstartdate']),
            'discount_end_date' => $this->valueFrom($item, ['discountEndDate', 'discount_end_date', 'discountenddate']),

            'inventory' => (float) $this->valueFrom($item, ['inventory', 'Inventory', 'quantityOnHand', 'qtyOnHand'], 0),
            'blocked' => $this->toBool($this->valueFrom($item, ['blocked', 'Blocked', 'isBlocked'])),
            'item_category_code' => $this->valueFrom($item, ['itemCategoryCode', 'item_category_code', 'categoryCode', 'CategoryCode']),
            'base_unit_of_measure_code' => $this->valueFrom($item, ['baseUnitOfMeasure', 'baseUnitOfMeasureCode', 'base_unit_of_measure_code', 'unitOfMeasureCode']),
            'price_includes_tax' => $this->toBool($this->valueFrom($item, ['priceIncludesTax', 'price_includes_tax'])),
            'default_location_code' => $this->valueFrom($item, ['defaultLocationCode', 'locationCode']),
        ];
    }

    /**
     * Pulls the per-location breakdown from BC's custom getInventoryByLocation
     * bound action, so stock can be shown per warehouse instead of only the
     * single flat total the items list endpoint returns. Rows for locations
     * BC no longer reports for this item are removed (stock moved/zeroed
     * out there), everything else is upserted.
     */
    protected function syncItemLocationInventory(Item $savedItem, string $bcId, string $token, int $companyId): void
    {
        $response = Http::withoutVerifying()
            ->withToken($token)
            ->acceptJson()
            ->post($this->bcUrl("items({$bcId})/Microsoft.NAV.getInventoryByLocation"), (object) []);

        if (!$response->successful()) {
            return;
        }

        // The bound action returns Edm.String — its "value" is a JSON string
        // that itself needs decoding, e.g. {"itemNo":"1000","locations":[...]}
        // — not a plain array, despite the endpoint's name.
        $payload = $response->json('value') ?? $response->json();

        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        $rows = $payload['locations'] ?? (is_array($payload) ? $payload : []);

        if (!is_array($rows)) {
            return;
        }

        $seenCodes = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $locationCode = (string) $this->valueFrom($row, ['locationCode', 'location_code'], '');
            $seenCodes[] = $locationCode;

            ItemLocationInventory::updateOrCreate(
                [
                    'item_id' => $savedItem->id,
                    'location_code' => $locationCode,
                ],
                [
                    'company_id' => $companyId,
                    'location_name' => $this->valueFrom($row, ['locationName', 'location_name']),
                    'inventory' => (float) $this->valueFrom($row, ['inventory', 'Inventory'], 0),
                ]
            );
        }

        ItemLocationInventory::where('item_id', $savedItem->id)
            ->whereNotIn('location_code', $seenCodes)
            ->delete();
    }

    protected function downloadItemImage(string $bcId, string $token): ?string
    {
        $path = "items/{$bcId}.jpg";

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        $imageResponse = Http::withoutVerifying()
            ->withToken($token)
            ->withHeaders(['Accept' => 'image/jpeg, image/png, image/*'])
            ->get($this->bcUrl("items({$bcId})/picture/pictureContent"));

        if (!$imageResponse->successful() || $imageResponse->body() === '') {
            return null;
        }

        Storage::disk('public')->put($path, $imageResponse->body());

        return $path;
    }

    private function syncVariantsFromBc($companyId)
    {
        $token = $this->getToken();

        if (!$token) {
            logger()->warning('Item variant sync skipped: BC authentication failed', [
                'company_id' => $companyId,
            ]);

            return ['saved' => 0, 'skipped' => 0, 'error' => 'Business Central authentication failed.'];
        }

        $url = $this->bcEndpoint('item_variants_endpoint', 'itemVariants');

        if (!$url) {
            logger()->warning('Item variant sync skipped: could not build BC URL', [
                'company_id' => $companyId,
            ]);

            return ['saved' => 0, 'skipped' => 0, 'error' => 'Business Central URL could not be built.'];
        }

        $response = Http::withoutVerifying()->withToken($token)->get($url);

        if (!$response->successful()) {
            logger()->warning('Item variant sync failed: BC request unsuccessful', [
                'company_id' => $companyId,
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'saved' => 0,
                'skipped' => 0,
                'error' => 'Business Central rejected the item variants request (HTTP ' . $response->status() . '). Check storage/logs/laravel.log for details, or verify the Item Variants endpoint under Companies > API Setup.',
            ];
        }

        $variants = $response->json()['value'] ?? [];

        $savedCount = 0;
        $skippedCount = 0;

        foreach ($variants as $variant) {
            $itemNumber = $variant['itemNo'] ?? $variant['itemNumber'] ?? null;
            $itemBcId = $variant['itemId'] ?? null;
            $bcId = $variant['id'] ?? null;
            $code = $variant['code'] ?? null;

            if ((!$itemNumber && !$itemBcId) || !$bcId || !$code) {
                $skippedCount = $skippedCount + 1;
                continue;
            }

            $localItem = Item::where('company_id', $companyId)
                ->when(
                    $itemBcId,
                    fn($q) => $q->where('bc_id', $itemBcId),
                    fn($q) => $q->where('number', $itemNumber)
                )
                ->first();

            if (!$localItem) {
                $skippedCount = $skippedCount + 1;
                continue;
            }
            $existingVariant = ItemVariant::where('bc_id', $bcId)
                ->whereHas('item', fn($q) => $q->where('company_id', $companyId))
                ->first();

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
        $localItem = Item::where('bc_id', $id)
            ->where('company_id', session('selected_company_id'))
            ->first();

        if (!$localItem) {
            return redirect()->back()->with('error', 'Item not found. Sync it from BC first.');
        }

        $item = $this->toDisplayItem($localItem);

        return view('POSViews.POSAdminViews.Items.show', compact('item'));
    }
}
