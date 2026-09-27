<?php

namespace App\Http\Controllers\Api\POS\Admin\ApprovalEntries;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\OrderAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApprovalEntriesController extends Controller
{
    // Keyed so the export column picker and the CSV header row always agree
    // on both the machine key and the human label.
    public const EXPORT_COLUMNS = [
        'entry_no' => 'Entry No.',
        'approval_type' => 'Approval Type',
        'order_no' => 'To Approve',
        'details' => 'Details',
        'status' => 'Status',
        'sender' => 'Sender ID',
        'date' => 'Date',
    ];

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

    public function export(Request $request): StreamedResponse
    {
        $companyId = session('selected_company_id');

        abort_unless($companyId, 422, 'Select a company first to export approval entries.');

        $columns = array_values(array_intersect(
            $request->input('columns', array_keys(self::EXPORT_COLUMNS)),
            array_keys(self::EXPORT_COLUMNS)
        ));

        if (empty($columns)) {
            $columns = array_keys(self::EXPORT_COLUMNS);
        }

        $entries = $this->baseQuery($request, $companyId)->get();

        $filename = 'approval-entries-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($entries, $columns) {
            $out = fopen('php://output', 'w');
            // Byte-order mark so Excel opens the UTF-8 file without mangling accents.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, array_map(fn ($key) => self::EXPORT_COLUMNS[$key], $columns));

            foreach ($entries as $entry) {
                $statusLabel = match ($entry->status) {
                    'confirmed' => 'Approved',
                    'cancelled' => 'Rejected',
                    default => ucfirst($entry->status ?? '—'),
                };

                $row = [
                    'entry_no' => $entry->entry_no ?? '—',
                    'approval_type' => $entry->action_type === 'cancelled' ? 'Order Rejection' : 'Order to BC',
                    'order_no' => $entry->order->order_no ?? '—',
                    'details' => $entry->note ?? '—',
                    'status' => $statusLabel,
                    'sender' => $entry->actionBy->name ?? '—',
                    'date' => optional($entry->created_at)->format('n/j/Y g:i A') ?? '—',
                ];

                fputcsv($out, array_map(fn ($key) => $row[$key], $columns));
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
