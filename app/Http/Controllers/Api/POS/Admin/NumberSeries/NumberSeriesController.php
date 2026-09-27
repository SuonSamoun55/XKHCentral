<?php

namespace App\Http\Controllers\Api\POS\Admin\NumberSeries;

use App\Http\Controllers\Controller;
use App\Models\POS\NumberSeries;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NumberSeriesController extends Controller
{
    // Minimum Digits offers 1..10 (was hard-locked to 3/4/5 — too narrow for
    // e.g. a 2-digit internal code or an 8-digit invoice number). 12-digit
    // cap on Starting/Ending No. keeps the field from accepting an
    // arbitrarily long number while staying well above any padding choice,
    // matching the "grows naturally past the padding" behavior in the hint
    // text. Both are shared with the create/edit views so the HTML `max`/
    // dropdown range and this validation never drift apart.
    public const MAX_DIGIT_COUNT = 10;
    public const MAX_SERIES_NUMBER = 999999999999;

    public static array $purposes = [
        'CUSTOMER' => 'Customer',
        'ORDER' => 'Order',
        'ENTRY' => 'Entry',
        'STAFF' => 'Staff',
        'ITEM' => 'Item',
    ];

    public function index()
    {
        $companyId = session('selected_company_id');
        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its number series.');
        }
        $series = NumberSeries::where('company_id', $companyId)
            ->orderBy('code')
            ->get();
        $purposes = self::$purposes;
        return view('POSViews.POSAdminViews.NumberSeries.index', compact('series', 'purposes'));
    }
    private function availablePurposes(int $companyId, ?int $ignoreId = null): array
    {
        $usedCodes = NumberSeries::where('company_id', $companyId)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->pluck('code')
            ->all();
        return array_diff_key(self::$purposes, array_flip($usedCodes));
    }
    public function create()
    {
        $companyId = session('selected_company_id');
        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its number series.');
        }
        $availablePurposes = $this->availablePurposes($companyId);
        $maxDigitCount = self::MAX_DIGIT_COUNT;
        $maxSeriesNumber = self::MAX_SERIES_NUMBER;
        return view('POSViews.POSAdminViews.NumberSeries.create', compact('availablePurposes', 'maxDigitCount', 'maxSeriesNumber'));
    }

    public function store(Request $request)
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its number series.');
        }

        $availablePurposes = $this->availablePurposes($companyId);

        $validated = $request->validate([
            'code' => [
                'required',
                Rule::in(array_keys($availablePurposes)),
                Rule::unique('number_series')->where(fn($q) => $q->where('company_id', $companyId)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'prefix' => ['required', 'string', 'max:10'],
            'padding' => ['required', 'integer', 'between:1,' . self::MAX_DIGIT_COUNT],
            'start_no' => ['required', 'integer', 'min:1', 'max:' . self::MAX_SERIES_NUMBER],
            'end_no' => ['required', 'integer', 'gt:start_no', 'max:' . self::MAX_SERIES_NUMBER],
            'is_active' => ['nullable', 'boolean'],
        ]);

        NumberSeries::create([
            'company_id' => $companyId,
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'prefix' => $validated['prefix'],
            'padding' => $validated['padding'],
            'start_no' => $validated['start_no'],
            'end_no' => $validated['end_no'],
            'last_no' => null,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return redirect()->route('number-series.index')->with('success', 'Number series created successfully.');
    }
    public function edit($id)
    {
        $companyId = session('selected_company_id');
        $series = NumberSeries::where('company_id', $companyId)->findOrFail($id);
        $availablePurposes = $this->availablePurposes($companyId, $series->id);
        $availablePurposes[$series->code] = self::$purposes[$series->code] ?? $series->code;
        $maxDigitCount = self::MAX_DIGIT_COUNT;
        $maxSeriesNumber = self::MAX_SERIES_NUMBER;
        return view('POSViews.POSAdminViews.NumberSeries.edit', compact('series', 'availablePurposes', 'maxDigitCount', 'maxSeriesNumber'));
    }
    public function update(Request $request, $id)
    {
        $companyId = session('selected_company_id');
        $series = NumberSeries::where('company_id', $companyId)->findOrFail($id);
        $locked = $series->last_no !== null;
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'end_no' => [
                'required',
                'integer',
                'gt:start_no',
                'max:' . self::MAX_SERIES_NUMBER,
                function ($attribute, $value, $fail) use ($series) {
                    if ($series->last_no !== null && $value < $series->last_no) {
                        $fail("End No. cannot be less than the last issued number ({$series->last_no}).");
                    }
                },
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
        if (!$locked) {
            $availablePurposes = $this->availablePurposes($companyId, $series->id);
            $availablePurposes[$series->code] = self::$purposes[$series->code] ?? $series->code;
            $rules['code'] = [
                'required',
                Rule::in(array_keys($availablePurposes)),
                Rule::unique('number_series')->where(fn($q) => $q->where('company_id', $companyId))->ignore($series->id),
            ];
            $rules['prefix'] = ['required', 'string', 'max:10'];
            $rules['padding'] = ['required', 'integer', 'between:1,' . self::MAX_DIGIT_COUNT];
            $rules['start_no'] = ['required', 'integer', 'min:1', 'max:' . self::MAX_SERIES_NUMBER];
        }
        $validated = $request->validate($rules);

        $series->name = $validated['name'];
        $series->end_no = $validated['end_no'];
        $series->is_active = $request->boolean('is_active', true);

        if (!$locked) {
            $series->code = strtoupper($validated['code']);
            $series->prefix = $validated['prefix'];
            $series->padding = $validated['padding'];
            $series->start_no = $validated['start_no'];
        }
        $series->save();
        return redirect()->route('number-series.index')->with('success', 'Number series updated successfully.');
    }
    public function destroy($id)
    {
        $companyId = session('selected_company_id');
        $series = NumberSeries::where('company_id', $companyId)->findOrFail($id);
        $series->delete();

        return redirect()->route('number-series.index')->with('success', 'Number series deleted successfully.');
    }
}
