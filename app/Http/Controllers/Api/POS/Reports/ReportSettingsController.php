<?php

namespace App\Http\Controllers\Api\POS\Reports;

use App\Http\Controllers\Controller;
use App\Models\POS\ReportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportSettingsController extends Controller
{
    public function index()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to configure its report layout.');
        }

        $settings = ReportSetting::forCompany($companyId);

        return view('POSViews.POSAdminViews.ReportSettings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return back()->with('error', 'Select a company first.');
        }

        $validated = $request->validate([
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'show_logo' => ['nullable'],
            'show_company_name' => ['nullable'],
            'show_address' => ['nullable'],
            'show_tax_number' => ['nullable'],
            'show_phone' => ['nullable'],
            'show_email' => ['nullable'],
            'show_discount_column' => ['nullable'],
            'show_vat_column' => ['nullable'],
            'show_unit_column' => ['nullable'],
            'show_item_image' => ['nullable'],
            'show_signature' => ['nullable'],
            'spacing' => ['required', 'in:compact,normal,spacious'],
            'footer_note' => ['nullable', 'string', 'max:1000'],
            'signature_labels' => ['nullable', 'array'],
            'signature_labels.*' => ['nullable', 'string', 'max:100'],
        ]);

        $signatureLabels = collect($validated['signature_labels'] ?? [])
            ->map(fn ($label) => trim((string) $label))
            ->filter()
            ->values()
            ->all();

        $existingSettings = ReportSetting::where('company_id', $companyId)->first();
        $logoPath = $existingSettings->logo ?? null;

        if ($request->hasFile('logo')) {
            // Its own storage folder (report_logos), deliberately separate
            // from company_logos (companies.logo) — this must never touch
            // the sidebar/header logo.
            if (!empty($logoPath) && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            $logoPath = $request->file('logo')->store('report_logos', 'public');
        }

        ReportSetting::updateOrCreate(
            ['company_id' => $companyId],
            [
                'logo' => $logoPath,
                'show_logo' => $request->boolean('show_logo'),
                'show_company_name' => $request->boolean('show_company_name'),
                'show_address' => $request->boolean('show_address'),
                'show_tax_number' => $request->boolean('show_tax_number'),
                'show_phone' => $request->boolean('show_phone'),
                'show_email' => $request->boolean('show_email'),
                'show_discount_column' => $request->boolean('show_discount_column'),
                'show_vat_column' => $request->boolean('show_vat_column'),
                'show_unit_column' => $request->boolean('show_unit_column'),
                'show_item_image' => $request->boolean('show_item_image'),
                'spacing' => $validated['spacing'],
                'show_signature' => $request->boolean('show_signature'),
                'signature_labels' => $signatureLabels ?: null,
                'footer_note' => $validated['footer_note'] ?? null,
            ]
        );

        return back()->with('success', 'Report layout saved.');
    }
}
