<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;
use App\Models\MagamentSystemModel\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('selected_company_id') ?: Company::value('id');

        $query = Item::query()
            ->where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereNotNull('discount_amount')
                  ->orWhereNotNull('discount_start_date')
                  ->orWhereNotNull('discount_end_date');
            })
            ->orderByDesc('updated_at');

        $items = $query->get()->map(function ($item) {
            $today = Carbon::today();
            $start = $item->discount_start_date ? Carbon::parse($item->discount_start_date) : null;
            $end   = $item->discount_end_date ? Carbon::parse($item->discount_end_date) : null;

            $status = 'inactive';

            if (($item->discount_amount ?? 0) > 0) {
                if ($start && $today->lt($start)) {
                    $status = 'scheduled';
                } elseif ($start && $end && $today->between($start, $end)) {
                    $status = 'active';
                } elseif ($end && $today->gt($end)) {
                    $status = 'expired';
                } elseif (!$start && !$end) {
                    $status = 'active';
                }
            }

            $item->discount_status = $status;
            return $item;
        });

        return view('POSViews.POSAdminViews.Discount.index', compact('items'));
    }

 public function create()
{
    $companyId = session('selected_company_id') ?: Company::value('id');

    $items = Item::where('company_id', $companyId)
        ->where('blocked', false)
        ->orderBy('display_name')
        ->get();

    $categories = Item::where('company_id', $companyId)
        ->where('blocked', false)
        ->whereNotNull('item_category_code')
        ->where('item_category_code', '!=', '')
        ->select('item_category_code')
        ->distinct()
        ->orderBy('item_category_code')
        ->pluck('item_category_code');

    return view('POSViews.POSAdminViews.discount.create', compact('items', 'categories'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'discount_type'        => ['required', 'in:item,category'],
        'item_id'              => ['nullable', 'exists:items,id'],
        'category_code'        => ['nullable', 'string'],
        'discount_amount'      => ['required', 'numeric', 'min:0', 'max:100'],
        'discount_start_date'  => ['nullable', 'date'],
        'discount_end_date'    => ['nullable', 'date', 'after_or_equal:discount_start_date'],
    ]);

    $companyId = session('selected_company_id') ?: Company::value('id');
    if ($validated['discount_type'] === 'item') {
        if (empty($validated['item_id'])) {
            return back()->withErrors([
                'item_id' => 'Please select an item.'
            ])->withInput();
        }

        $item = Item::where('company_id', $companyId)->findOrFail($validated['item_id']);
        $this->applyDiscountToItem($item, $validated);

        return redirect()->route('discounts.index')->with('success', 'Item discount percentage added successfully.');
    }

    if (empty($validated['category_code'])) {
        return back()->withErrors([
            'category_code' => 'Please select a category.'
        ])->withInput();
    }

    $items = Item::where('company_id', $companyId)
        ->where('item_category_code', $validated['category_code'])
        ->get();

    foreach ($items as $item) {
        $this->applyDiscountToItem($item, $validated);
    }

    $updatedCount = $items->count();

    return redirect()->route('discounts.index')->with('success', "Category discount percentage added successfully to {$updatedCount} item(s).");
}
    public function edit($id)
    {
        $item = Item::findOrFail($id);

        $companyId = session('selected_company_id') ?: Company::value('id');

        $items = Item::where('company_id', $companyId)
            ->where('blocked', false)
            ->orderBy('display_name')
            ->get();

        $categories = Item::where('company_id', $companyId)
            ->where('blocked', false)
            ->whereNotNull('item_category_code')
            ->where('item_category_code', '!=', '')
            ->select('item_category_code')
            ->distinct()
            ->orderBy('item_category_code')
            ->pluck('item_category_code');

        return view('POSViews.POSAdminViews.Discount.edit', compact('item', 'items', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'discount_amount'      => ['required', 'numeric', 'min:0', 'max:100'],
            'discount_start_date'  => ['nullable', 'date'],
            'discount_end_date'    => ['nullable', 'date', 'after_or_equal:discount_start_date'],
        ]);

        $companyId = session('selected_company_id') ?: Company::value('id');
        $item = Item::where('company_id', $companyId)->findOrFail($id);
        $this->applyDiscountToItem($item, $validated);

        return redirect()
            ->route('discounts.index')
            ->with('success', 'Discount updated successfully.');
    }

    public function destroy($id)
    {
        $companyId = session('selected_company_id') ?: Company::value('id');
        $item = Item::where('company_id', $companyId)->findOrFail($id);
        $this->applyDiscountToItem($item, [
            'discount_amount' => 0,
            'discount_start_date' => null,
            'discount_end_date' => null,
        ]);

        return redirect()
            ->route('discounts.index')
            ->with('success', 'Discount removed successfully.');
    }

    private function applyDiscountToItem(Item $item, array $payload): void
    {
        $discountPercent = round((float) ($payload['discount_amount'] ?? 0), 2);
        $startDate = $payload['discount_start_date'] ?? null;
        $endDate = $payload['discount_end_date'] ?? null;

        $item->discount_amount = $discountPercent;
        $item->discount_start_date = $startDate;
        $item->discount_end_date = $endDate;
        $item->save();
    }
}
