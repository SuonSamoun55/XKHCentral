<?php

namespace App\Http\Controllers\Api\POS\User\Products;

use App\Http\Controllers\Controller;
use App\Models\POS\Cart;
use App\Models\POS\Favorite;
use App\Models\POS\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ItemListController extends Controller
{
    public function getItems()
    {
        $user = Auth::user();

        $items = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->orderBy('display_name')
            ->get();

        $items->transform(function (Item $item) {
            return $this->decorateItem($item);
        });

        $favoriteIds = Favorite::where('user_id', $user->id)
            ->pluck('item_id')
            ->toArray();

        $cartCount = 0;
        if ($user) {
            $activeCart = Cart::where('user_id', $user->id)
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
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
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
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->where('item_category_code', $categoryCode)
            ->orderBy('display_name')
            ->get();

        $items->transform(function (Item $item) {
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
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->orderBy('display_name')
            ->get();

        $items->transform(function (Item $item) {
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
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
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

        $items = Item::query()
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

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'active'
        ]);

        $cartItem = $cart->items()->where('item_id', $request->item_id)->first();

        if ($cartItem) {
            $cartItem->increment('qty', 1);
        } else {
            $cart->items()->create([
                'item_id' => $request->item_id,
                'qty' => 1
            ]);
        }

        $count = (int) $cart->items()->sum('qty');

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    public function detail($id)
    {
        $user = Auth::user();

        $item = Item::query()
            ->where('id', $id)
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
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
                ->where('status', 'active')
                ->first();

            if ($activeCart) {
                $cartCount = (int) $activeCart->items()->sum('qty');
            }
        }

        return view('POSViews.POSUserViews.Products.show', compact(
            'item',
            'discountPercent',
            'finalPrice',
            'unitPrice',
            'cartCount'
        ));
    }

    /**
     * Apply discount/price + resolved image_url to a single item.
     * Prefers custom_image_url (manually uploaded) over image_url (synced from BC),
     * so a custom photo is never hidden by the default BC photo.
     */
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

    /**
     * Make sure image_url is always a full, browser-loadable URL,
     * whether it's stored as a full URL, a public disk path, or empty.
     */
    private function resolveImageUrl(?string $rawPath): string
    {
        if (!$rawPath) {
            return asset('images/no-image.png');
        }

        // Already a full URL (http/https)
        if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
            return $rawPath;
        }

        // Already an absolute app path like /storage/... or /images/...
        if (str_starts_with($rawPath, '/')) {
            return asset(ltrim($rawPath, '/'));
        }

        // Relative path stored, e.g. "items/photo1.jpg" saved via Storage::disk('public')
        if (Storage::disk('public')->exists($rawPath)) {
            return asset('storage/' . $rawPath);
        }

        // Fallback: just asset() it directly
        return asset($rawPath);
    }
}
