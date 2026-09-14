<?php

namespace App\Http\Controllers\Api\POS\User\Products;

use App\Http\Controllers\Concerns\ResolvesImageUrl;
use App\Http\Controllers\Controller;
use App\Models\POS\Cart;
use App\Models\POS\Favorite;
use App\Models\POS\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\POS\ItemVariant;

class ItemListController extends Controller
{
    use ResolvesImageUrl;

    public function getItems()
    {
        $user = Auth::user();
        $companyId = session('selected_company_id') ?? $user->company_id;

        $items = Item::query()
            ->when($companyId, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            // A null is_visible means the item was just synced and hasn't been
            // reviewed by the admin yet — keep it hidden from customers until decided.
            ->where('is_visible', true)
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->orderBy('display_name')
            ->get();
        $itemIds = $items->pluck('id');

        $variantsByItem = ItemVariant::whereIn('item_id', $itemIds)
            ->get()
            ->groupBy('item_id');

        $items->transform(function (Item $item) use ($variantsByItem) {
            $item->setRelation(
                'variants',
                $variantsByItem->get($item->id, collect())
            );

            return $this->decorateItem($item);
        });

        $favoriteIds = Favorite::where('user_id', $user->id)
            ->pluck('item_id')
            ->toArray();

        $cartCount = 0;
        if ($user) {
            $activeCart = Cart::where('user_id', $user->id)
                ->where('company_id', $companyId)
                ->where('status', 'active')
                ->first();

            if ($activeCart) {
                $cartCount = (int) $activeCart->items()->sum('qty');
            }
        }

        return view('POSViews.POSUserViews.Products.index', compact('items', 'favoriteIds', 'cartCount'));
    }

    public function mobileCategories()
    {
        $categories = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            // A null is_visible means the item was just synced and hasn't been
            // reviewed by the admin yet — keep it hidden from customers until decided.
            ->where('is_visible', true)
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->whereNotNull('item_category_code')
            ->where('item_category_code', '<>', '')
            ->selectRaw('item_category_code as code, count(*) as count, max(image_url) as image_url')
            ->groupBy('item_category_code')
            ->orderBy('item_category_code')
            ->get()
            ->map(function ($category) {
                return [
                    'code' => $category->code,
                    'title' => ucwords(str_replace(['-', '_'], [' ', ' '], $category->code)),
                    'count' => (int) $category->count,
                    'image' => $this->resolveImageUrl($category->image_url),
                ];
            });

        return view('POSViews.POSUserViews.mobile.POSitemCategoriesMobileView', compact('categories'));
    }

    public function mobileCategoryProducts($category)
    {
        $categoryCode = $category;
        $categoryTitle = ucwords(str_replace(['-', '_'], [' ', ' '], $categoryCode));

        $items = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            // A null is_visible means the item was just synced and hasn't been
            // reviewed by the admin yet — keep it hidden from customers until decided.
            ->where('is_visible', true)
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->where('item_category_code', $categoryCode)
            ->orderBy('display_name')
            ->get();

        // Same batch-load-and-attach pattern as getItems(), so the popup
        // also works from category-filtered mobile listings.
        $itemIds = $items->pluck('id');

        $variantsByItem = ItemVariant::whereIn('item_id', $itemIds)
            ->get()
            ->groupBy('item_id');

        $items->transform(function (Item $item) use ($variantsByItem) {
            $item->setRelation(
                'variants',
                $variantsByItem->get($item->id, collect())
            );

            return $this->decorateItem($item);
        });

        return view('POSViews.POSUserViews.mobile.POSitemCategoryProductsMobileView', compact('items', 'categoryTitle', 'categoryCode'));
    }

    public function showProduct($id)
    {
        return $this->detail($id);
    }

    public function mobileProducts()
    {
        $user = Auth::user();

        // ✅ PRODUCTS
        $items = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            // A null is_visible means the item was just synced and hasn't been
            // reviewed by the admin yet — keep it hidden from customers until decided.
            ->where('is_visible', true)
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->orderBy('display_name')
            ->get();

        // Same batch-load-and-attach pattern, so the mobile popup also works.
        $itemIds = $items->pluck('id');

        $variantsByItem = ItemVariant::whereIn('item_id', $itemIds)
            ->get()
            ->groupBy('item_id');

        $items->transform(function (Item $item) use ($variantsByItem) {
            $item->setRelation(
                'variants',
                $variantsByItem->get($item->id, collect())
            );

            return $this->decorateItem($item);
        });

        // ✅ FAVORITES (for ❤️ state)
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = Favorite::where('user_id', $user->id)
                ->pluck('item_id')
                ->toArray();
        }

        // ✅ REAL CATEGORIES (same logic as category pages)
        $categories = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            // A null is_visible means the item was just synced and hasn't been
            // reviewed by the admin yet — keep it hidden from customers until decided.
            ->where('is_visible', true)
            ->whereNotNull('item_category_code')
            ->where('item_category_code', '!=', '')
            ->selectRaw('item_category_code as code, COUNT(*) as count')
            ->groupBy('item_category_code')
            ->orderBy('item_category_code')
            ->get()
            ->map(function ($cat) {
                return [
                    'code' => $cat->code,
                    'title' => ucwords(str_replace(['_', '-'], ' ', $cat->code)),
                    'count' => (int) $cat->count,
                ];
            });

        return view(
            'POSViews.POSUserViews.mobile.POSItem_mobile',
            compact('items', 'categories', 'favoriteIds')
        );
    }

    public function filter(Request $request)
    {
        $categoryCode = $request->category;
        $user = Auth::user();
        $companyId = session('selected_company_id') ?? $user->company_id;

        $items = Item::query()
            ->when($companyId, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->when($categoryCode, fn ($q) =>
                $q->whereHas('category', fn ($c) =>
                    $c->where('code', $categoryCode)
                )
            )
            ->get();

        $items->transform(function (Item $item) {
            return $this->decorateItem($item);
        });

        return response()->json([
            'count' => $items->count(),
            'html' => view(
                'ManagementSystemViews.UserViews.partials.product-cards',
                compact('items')
            )->render()
        ]);
    }

    public function index()
    {
        $items = Item::select(
                'id',
                'display_name',
                'image_url',
                'custom_image_url',
                'final_price',
                'unit_price',
                'category_code'
            )
            ->where('is_active', 1)
            ->get();

        $items->transform(function (Item $item) {
            return $this->decorateItem($item);
        });

        $categories = Category::select('code', 'title')
            ->withCount('items')
            ->get()
            ->map(function ($cat) {
                return [
                    'code'  => $cat->code,
                    'title' => $cat->title,
                    'count' => $cat->items_count,
                ];
            });

        return view('POSViews.POSUserViews.mobile.POSItem_mobile', compact(
            'items',
            'categories'
        ));
    }

    public function add(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'variant_id' => ['nullable', 'integer'],
            'variant_ids' => ['nullable', 'array'],
            'variant_ids.*' => ['integer'],
            'qty' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        $qty = round((float) ($validated['qty'] ?? 1), 2);
        // A product can expose multiple option groups (Size, Beef Type, ...),
        // but a cart line only tracks one variant, so the first selection wins.
        $variantId = $validated['variant_id'] ?? ($validated['variant_ids'][0] ?? null);
        $companyId = session('selected_company_id') ?? $user->company_id;

        $count = DB::transaction(function () use ($user, $validated, $variantId, $qty, $companyId) {
            $cart = Cart::firstOrCreate([
                'user_id' => $user->id,
                'company_id' => $companyId,
                'status' => 'active',
            ]);

            $cartItem = $cart->items()
                ->where('item_id', $validated['item_id'])
                ->where('item_variant_id', $variantId)
                ->lockForUpdate()
                ->first();

            if ($cartItem) {
                $cartItem->increment('qty', $qty);
            } else {
                $cart->items()->create([
                    'item_id' => $validated['item_id'],
                    'item_variant_id' => $variantId,
                    'qty' => $qty,
                ]);
            }

            return (int) $cart->items()->sum('qty');
        });

        return response()->json([
            'success' => true,
            'count' => $count,
            'cartCount' => $count,
            'message' => 'Added to cart successfully.',
        ]);
    }

    public function detail($id)
    {
        $user = Auth::user();
        $companyId = session('selected_company_id') ?? $user->company_id;

        $item = Item::query()
            ->where('id', $id)
            ->when($companyId, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            // A null is_visible means the item was just synced and hasn't been
            // reviewed by the admin yet — keep it hidden from customers until decided.
            ->where('is_visible', true)
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->firstOrFail();

        $item = $this->decorateItem($item);

        $discountPercent = $item->effective_discount_percent;
        $finalPrice = $item->final_price;
        $unitPrice = (float) ($item->unit_price ?? 0);
        $cartCount = 0;

        if ($user) {
            $activeCart = Cart::where('user_id', $user->id)
                ->where('company_id', $companyId)
                ->where('status', 'active')
                ->first();

            if ($activeCart) {
                $cartCount = (int) $activeCart->items()->sum('qty');
            }
        }

        // ✅ FIX: this was missing entirely — the blade view reads
        // $favoriteIds to decide whether to render the heart as filled on
        // load, but it was never fetched or passed here, so the heart
        // always reset to "not favorited" on every page refresh.
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = Favorite::where('user_id', $user->id)
                ->pluck('item_id')
                ->toArray();
        }

        // Pulled directly from item_variants, same pattern as ItemVariantPosController::index()
        $variants = ItemVariant::where('item_id', $item->id)
            ->where('blocked', false)
            ->get();

        // ✅ Related products: up to 10 other items from the SAME category
        // (item_category_code), same visibility rules as the main listing,
        // current product excluded.
        $relatedItems = Item::query()
            ->where('id', '!=', $item->id)
            ->when($companyId, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->when($item->item_category_code, function ($q) use ($item) {
                $q->where('item_category_code', $item->item_category_code);
            }, function ($q) {
                // current product has no category — don't show unrelated items
                $q->whereRaw('1 = 0');
            })
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            // A null is_visible means the item was just synced and hasn't been
            // reviewed by the admin yet — keep it hidden from customers until decided.
            ->where('is_visible', true)
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->orderBy('display_name')
            ->limit(10)
            ->get();

        // Same batch-load-and-attach pattern as getItems(), so the
        // quick-add "+" button on each related card can also open the
        // variant popup instead of always adding straight to cart.
        $relatedItemIds = $relatedItems->pluck('id');

        $relatedVariantsByItem = ItemVariant::whereIn('item_id', $relatedItemIds)
            ->get()
            ->groupBy('item_id');

        $relatedItems->transform(function (Item $relatedItem) use ($relatedVariantsByItem) {
            $relatedItem->setRelation(
                'variants',
                $relatedVariantsByItem->get($relatedItem->id, collect())
            );

            return $this->decorateItem($relatedItem);
        });

        return view('POSViews.POSUserViews.Products.show', compact(
            'item', 'discountPercent', 'finalPrice', 'unitPrice', 'cartCount', 'variants', 'favoriteIds', 'relatedItems'
        ));
    }

    /**
     * Toggle favorite status for the given item for the current user.
     * Returns { success: true, favorited: bool } so the frontend can
     * reliably read the new state under the "favorited" key.
     */
    public function toggleFavorite(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $itemId = $request->input('item_id');

        if (!$itemId) {
            return response()->json([
                'success' => false,
                'message' => 'item_id is required'
            ], 422);
        }

        $existing = Favorite::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $itemId,
            ]);
            $favorited = true;
        }

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'message' => $favorited ? 'Added to favorites.' : 'Removed from favorites.',
        ]);
    }

    private function decorateItem(Item $item): Item
    {
        $discountPercent = $this->resolveDiscountPercent($item);
        $unitPrice = (float) ($item->unit_price ?? 0);
        $finalPrice = round(max(0, $unitPrice * (1 - ($discountPercent / 100))), 2);

        $item->setAttribute('effective_discount_percent', round($discountPercent, 2));
        $item->setAttribute('final_price', $finalPrice);
        $item->setAttribute('image_url', $this->resolveImageUrl($item->custom_image_url ?: $item->image_url));

        return $item;
    }

    private function resolveDiscountPercent(Item $item): float
    {
        $discount = max(0, (float) ($item->discount_amount ?? 0));
        if ($discount <= 0) {
            return 0.0;
        }

        $today = Carbon::today();
        $start = $item->discount_start_date ? Carbon::parse($item->discount_start_date)->startOfDay() : null;
        $end = $item->discount_end_date ? Carbon::parse($item->discount_end_date)->endOfDay() : null;

        if ($start && $today->lt($start)) {
            return 0.0;
        }

        if ($end && $today->gt($end)) {
            return 0.0;
        }

        return min(100, $discount);
    }

}
