<?php

namespace App\Http\Controllers\Api\POS\Admin\TaxGroups;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\TaxGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxGroupController extends Controller
{
    public function index()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its tax groups.');
        }

        $taxGroups = TaxGroup::where('company_id', $companyId)
            ->orderBy('code')
            ->get();

        return view(
            'POSViews.POSAdminViews.TaxGroups.index',
            compact('taxGroups')
        );
    }

    /**
     * Codes Business Central has actually sent on this company's synced
     * items that don't have a tax group configured yet — deduplicated, so
     * the same code seen on 50 items still offers just one option. Mirrors
     * how the "Add Page" dropdown only offers real, not-yet-added keys.
     */
    private function availableCodes(int $companyId)
    {
        $configuredCodes = TaxGroup::where('company_id', $companyId)->pluck('code');

        return Item::where('company_id', $companyId)
            ->whereNotNull('tax_group_code')
            ->where('tax_group_code', '!=', '')
            ->whereNotIn('tax_group_code', $configuredCodes)
            ->distinct()
            ->orderBy('tax_group_code')
            ->pluck('tax_group_code');
    }

    public function create()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its tax groups.');
        }

        $availableCodes = $this->availableCodes($companyId);

        return view('POSViews.POSAdminViews.TaxGroups.create', compact('availableCodes'));
    }

    public function store(Request $request)
    {
        $companyId = session('selected_company_id');
        $availableCodes = $this->availableCodes($companyId);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::in($availableCodes)],
            'display_name' => ['nullable', 'string', 'max:255'],
            'percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        TaxGroup::create([
            'company_id' => $companyId,
            'code' => $validated['code'],
            'display_name' => $validated['display_name'] ?? null,
            'percent' => $validated['percent'],
        ]);

        return redirect()->route('tax-groups.index')->with('success', 'Tax group added successfully.');
    }

    public function edit($id)
    {
        $companyId = session('selected_company_id');
        $taxGroup = TaxGroup::where('company_id', $companyId)->findOrFail($id);

        return view('POSViews.POSAdminViews.TaxGroups.edit', compact('taxGroup'));
    }

    public function update(Request $request, $id)
    {
        $companyId = session('selected_company_id');
        $taxGroup = TaxGroup::where('company_id', $companyId)->findOrFail($id);

        $validated = $request->validate([
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('tax_groups')->where(fn ($q) => $q->where('company_id', $companyId))->ignore($taxGroup->id),
            ],
            'display_name' => ['nullable', 'string', 'max:255'],
            'percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $oldCode = $taxGroup->code;

        $taxGroup->update([
            'code' => $validated['code'],
            'display_name' => $validated['display_name'] ?? null,
            'percent' => $validated['percent'],
        ]);

        // If the code itself changed, re-point items that were tagged with
        // the old code so they don't silently keep pointing to nothing.
        if ($oldCode !== $taxGroup->code) {
            Item::where('company_id', $companyId)
                ->where('tax_group_code', $oldCode)
                ->update(['tax_group_code' => $taxGroup->code]);
        }

        return redirect()->route('tax-groups.index')->with('success', 'Tax group updated successfully.');
    }

    public function destroy($id)
    {
        $companyId = session('selected_company_id');
        $taxGroup = TaxGroup::where('company_id', $companyId)->findOrFail($id);
        $taxGroup->delete();

        return redirect()->route('tax-groups.index')->with('success', 'Tax group deleted successfully.');
    }
}
