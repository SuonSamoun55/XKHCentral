<?php

namespace App\Http\Controllers\Api\POS\Admin\Tax;

use App\Http\Controllers\Controller;
use App\Models\POS\VatPostingSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class VatPostingSetupController extends Controller
{
    public function index()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its VAT posting setup.');
        }

        $setups = VatPostingSetup::where('company_id', $companyId)
            ->orderBy('vat_prod_posting_group')
            ->orderBy('vat_bus_posting_group')
            ->get();

        return view('POSViews.POSAdminViews.Tax.VatPostingSetup.index', compact('setups'));
    }

    public function syncFromBc(Request $request)
    {
        $companyId = session('selected_company_id');

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
        $url = $this->bcUrl('vatPostingSetups');

        if (!$url) {
            return response()->json([
                'success' => false,
                'message' => 'Business Central URL could not be built.',
            ], 422);
        }

        $response = Http::withoutVerifying()->withToken($token)->acceptJson()->get($url);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch VAT Posting Setup from Business Central.',
                'details' => $response->body(),
            ], 500);
        }

        $rows = $response->json()['value'] ?? [];

        $syncedCount = 0;

        DB::transaction(function () use ($rows, $companyId, &$syncedCount) {
            foreach ($rows as $row) {
                $bcId = $this->valueFrom($row, ['id', 'systemId', 'SystemId']);

                if (!$bcId) {
                    continue;
                }

                VatPostingSetup::updateOrCreate(
                    [
                        'company_id' => $companyId,
                        'bc_id' => $bcId,
                    ],
                    [
                        'vat_bus_posting_group' => $this->valueFrom($row, ['vatBusPostingGroup']),
                        'vat_prod_posting_group' => $this->valueFrom($row, ['vatProdPostingGroup']),
                        'description' => $this->valueFrom($row, ['description']),
                        'blocked' => $this->toBool($this->valueFrom($row, ['blocked'])),
                        'vat_identifier' => $this->valueFrom($row, ['vatIdentifier']),
                        'vat_pct' => $this->valueFrom($row, ['vatPct'], 0),
                        'vat_calculation_type' => $this->decodeXmlEscapes($this->valueFrom($row, ['vatCalculationType'])),
                        'unrealized_vat_type' => $this->decodeXmlEscapes($this->valueFrom($row, ['unrealizedVATType'])),
                        'adjust_for_payment_discount' => $this->toBool($this->valueFrom($row, ['adjustForPaymentDiscount'])),
                        'sales_vat_account' => $this->valueFrom($row, ['salesVATAccount']),
                        'sales_vat_unreal_account' => $this->valueFrom($row, ['salesVATUnrealAccount']),
                        'purchase_vat_account' => $this->valueFrom($row, ['purchaseVATAccount']),
                        'purch_vat_unreal_account' => $this->valueFrom($row, ['purchVATUnrealAccount']),
                        'reverse_chrg_vat_acc' => $this->valueFrom($row, ['reverseChrgVATAcc']),
                        'reverse_chrg_vat_unreal_acc' => $this->valueFrom($row, ['reverseChrgVATUnrealAcc']),
                        'vat_clause_code' => $this->valueFrom($row, ['vatClauseCode']),
                        'eu_service' => $this->toBool($this->valueFrom($row, ['euService'])),
                        'certificate_of_supply_required' => $this->toBool($this->valueFrom($row, ['certificateOfSupplyRequired'])),
                        'tax_category' => $this->valueFrom($row, ['taxCategory']),
                    ]
                );

                $syncedCount++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'VAT Posting Setup synced successfully.',
            'count' => $syncedCount,
        ]);
    }
    private function decodeXmlEscapes(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return preg_replace_callback(
            '/_x([0-9A-Fa-f]{4})_/',
            fn($m) => mb_chr((int) hexdec($m[1])),
            $value
        );
    }
}
