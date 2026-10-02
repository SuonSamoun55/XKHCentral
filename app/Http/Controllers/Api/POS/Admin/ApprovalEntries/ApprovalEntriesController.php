<?php

namespace App\Http\Controllers\Api\POS\Admin\ApprovalEntries;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\OrderAction;
use Illuminate\Http\Request;

class ApprovalEntriesController extends Controller
{
    private function baseQuery(Request $request, int $companyId)
    {
        return OrderAction::with(['order', 'actionBy'])
            ->where(function ($q) {
                // Approve.
                $q->where('action_type', 'confirmed')
                    // Reject — an admin cancelling a pending order. Excludes
                    // a customer cancelling their own order (same action_type,
                    // but there action_by is the customer themselves).
                    ->orWhere(function ($q2) {
                        $q2->where('action_type', 'cancelled')
                            ->whereColumn('action_by', '!=', 'user_id');
                    });
            })
            ->whereHas('order', fn ($q) => $q->where('company_id', $companyId))
            ->when($request->status && $request->status !== 'all', fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), function ($q) use ($request) {
                $keyword = trim($request->q);
                $q->where(function ($qq) use ($keyword) {
                    $qq->where('entry_no', 'like', "%{$keyword}%")
                        ->orWhere('note', 'like', "%{$keyword}%")
                        ->orWhereHas('order', fn ($oq) => $oq->where('order_no', 'like', "%{$keyword}%"))
                        ->orWhereHas('actionBy', fn ($aq) => $aq->where('name', 'like', "%{$keyword}%"));
                });
            })
            ->latest();
    }

    public function index(Request $request)
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to view its approval entries.');
        }

        $entries = $this->baseQuery($request, $companyId)
            ->paginate(25)
            ->withQueryString();

        return view('POSViews.POSAdminViews.ApprovalEntries.index', compact('entries'));
    }
}
