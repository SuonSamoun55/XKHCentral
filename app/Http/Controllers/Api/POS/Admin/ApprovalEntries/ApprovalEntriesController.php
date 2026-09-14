<?php

namespace App\Http\Controllers\Api\POS\Admin\ApprovalEntries;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\OrderAction;
use Illuminate\Http\Request;

class ApprovalEntriesController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('companies.index')
                ->with('error', 'Select a company first to view its approval entries.');
        }

        $entries = OrderAction::with(['order', 'actionBy'])
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
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('POSViews.POSAdminViews.ApprovalEntries.index', compact('entries'));
    }
}
