<?php

namespace App\Http\Controllers\Api\POS\Reports;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Company;
use App\Models\POS\Order;
use App\Models\POS\ReportSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// use function abort_unless;
class OrderReportController extends Controller
{
    /**
     * Renders and caches this order's PDF ahead of time, so the first
     * person to actually open the preview/download gets the cached copy
     * (~0.04s) instead of paying dompdf's ~2s render cost right when they're
     * waiting on it. Meant to be called right after whatever event makes an
     * order "final enough" to have a report worth viewing (currently: admin
     * confirmation) — never lets a PDF failure break that calling action,
     * since warming the cache is a pure optimization, not a requirement.
     */
    public function warmCache(Order $order): void
    {
        try {
            $this->pdfBytes($order);
        } catch (\Throwable $e) {
            Log::warning('Order report PDF cache warm-up failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function preview(string $id)
    {
        $order = $this->authorizedOrder($id);

        /** @var \App\Models\ManagementSystem\User|null $authUser */
        $authUser = Auth::user();
        $isAdmin = $authUser && ($authUser->isAdmin() || $authUser->hasPermission('orders'));

        return view('Reports.OrderInvoicePreview', [
            'order' => $order,
            'streamUrl' => route('orders.report.stream', $order->id),
            'downloadUrl' => route('orders.report.download', $order->id),
            // Same sidebar the rest of that side's pages use — an admin/staff
            // member sees the POS admin shell, the order's own customer sees
            // their regular account shell, never the other one's.
            'layout' => $isAdmin ? 'Layout.POSAdmin.app' : 'Layout.POSUser.app',
        ]);
    }

    /**
     * The bare HTML report document — kept for any consumer that needs raw
     * HTML rather than a PDF; the preview page itself now embeds stream()'s
     * PDF instead, since only the PDF is actually paginated.
     */
    public function raw(string $id)
    {
        $order = $this->authorizedOrder($id);

        return view('Reports.OrderInvoice', $this->reportData($order, forPdf: false));
    }

    /**
     * Same PDF as download(), but sent inline for the preview page's PDF.js
     * viewer instead of forced as an attachment.
     */
    public function stream(string $id)
    {
        $order = $this->authorizedOrder($id);
        $filename = "order-{$this->safeOrderNo($order)}.pdf";

        return response($this->pdfBytes($order), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"{$filename}\"");
    }

    public function download(string $id)
    {
        $order = $this->authorizedOrder($id);
        $filename = "order-{$this->safeOrderNo($order)}.pdf";

        return response($this->pdfBytes($order), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * dompdf is genuinely slow (layout + image loading + PDF encoding), and
     * an order's invoice never changes once confirmed — yet stream() and
     * download() each used to run it fresh, so opening the preview and then
     * clicking Download regenerated the exact same PDF twice, and simply
     * reopening the preview regenerated it again every time. Cache the
     * rendered bytes on the private "local" disk (never publicly exposed —
     * this always goes through the auth check in authorizedOrder() first)
     * keyed by a version stamp that changes whenever anything the report
     * actually renders could have changed, so a stale cache is never served.
     */
    private function pdfBytes(Order $order): string
    {
        $path = $this->pdfCachePath($order);

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->get($path);
        }

        // setOptions() fully replaces dompdf's Options object — calling it
        // AFTER loadView() (as this used to) leaves the frame tree dompdf
        // already built while loading the HTML pointed at the old Options,
        // so render()/output() then has to redo that work under the new
        // ones. Measured: ~8.5s that way vs ~4.5s calling it first, for the
        // exact same, byte-identical PDF — pure wasted duplicate work, not
        // a caching or fidelity difference.
        $bytes = Pdf::setOptions([
                'isRemoteEnabled' => true,
                // Package config sets this to base_path() too, but the
                // underlying Dompdf instance doesn't reliably pick that up —
                // it was defaulting to vendor/dompdf/dompdf, which silently
                // blocks every local image (logo, item thumbnails) as
                // outside its sandbox. Set it directly here instead.
                'chroot' => base_path(),
                // Also set explicitly for the same reason: replacing dompdf's
                // Options wipes the package config's font_dir/font_cache
                // along with it, silently falling back to dompdf's own
                // bundled vendor/dompdf/dompdf/lib/fonts as the cache
                // location. That's fragile on real hosting — many deploy
                // pipelines treat vendor/ as read-only or replace it wholesale
                // on every deploy, which would silently lose font-metric
                // caching (and the render-time cost that comes with rebuilding
                // it) on every single deploy. storage/fonts is owned by the
                // app and persists across deploys.
                'fontDir' => storage_path('fonts'),
                'fontCache' => storage_path('fonts'),
            ])
            ->loadView('Reports.OrderInvoice', $this->reportData($order, forPdf: true))
            ->setPaper('a4')
            ->output();

        Storage::disk('local')->put($path, $bytes);

        return $bytes;
    }

    private function pdfCachePath(Order $order): string
    {
        $settings = ReportSetting::forCompany($order->company_id);

        $lastChangedAt = collect([
            $order->updated_at,
            optional($order->actions)->max('updated_at'),
            $settings->updated_at,
        ])->filter()->max();

        $version = $lastChangedAt ? $lastChangedAt->timestamp : 'na';

        return "reports_cache/order-{$order->id}-{$version}.pdf";
    }

    private function safeOrderNo(Order $order): string
    {
        return preg_replace('/[^A-Za-z0-9_-]/', '-', $order->order_no ?: $order->id);
    }

    private function reportData(Order $order, bool $forPdf): array
    {
        $company = $order->company ?: new Company();
        $settings = ReportSetting::forCompany($order->company_id);

        // Prefer a logo uploaded specifically for reports (report_settings.logo);
        // fall back to the company's sidebar logo only for display, never write
        // back to it — a report-only logo must never affect the sidebar/header.
        $reportLogo = $settings->logo ?: $company->logo;

        // The admin who confirmed the order in AdminOrderController — the
        // most recent 'confirmed' action, since an order can be re-confirmed
        // after a 'confirm_failed' retry.
        $approvedBy = optional(
            $order->actions
                ->where('action_type', 'confirmed')
                ->sortByDesc('created_at')
                ->first()
        )->actionBy;

        return [
            'order' => $order,
            'company' => $company,
            'settings' => $settings,
            'companyLogoUrl' => $settings->show_logo ? $this->resolveLogoUrl($reportLogo, $forPdf) : null,
            'totalTax' => (float) $order->items->sum('tax_amount'),
            'approvedByName' => optional($approvedBy)->name,
            // dompdf needs a local filesystem path (no HTTP round-trip — see
            // below); a real browser rendering the same view for the iframe
            // preview needs an ordinary /storage URL instead, since it can't
            // load a Windows filesystem path as an <img src>.
            'forPdf' => $forPdf,
        ];
    }

    /**
     * For PDF generation this returns a local filesystem path, not an
     * asset() URL — dompdf otherwise has to fetch its own asset() URL back
     * over HTTP (isRemoteEnabled), which can hang or fail: APP_URL often
     * doesn't match the host actually serving the app in dev, and even when
     * it does, a single-worker dev server can deadlock fetching itself
     * mid-request. For the browser preview it returns a normal public URL,
     * since a real browser can't load a filesystem path as an <img src>.
     */
    private function resolveLogoUrl(?string $logo, bool $forPdf): ?string
    {
        if (!$logo) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $logo)) {
            return $logo;
        }

        if (!Storage::disk('public')->exists($logo)) {
            return null;
        }

        if (!$forPdf) {
            // Root-relative, not Storage::url()/asset() — those build an
            // absolute URL against config('app.url'), which routinely
            // doesn't match the host actually serving the page in dev
            // (e.g. APP_URL=http://xkhcentral.test while browsing via
            // 127.0.0.1:8000), leaving the <img> pointed at a host the
            // browser can't load it from.
            return '/storage/' . $logo;
        }

        // On Windows path() returns backslashes (C:\Users\...), which is not
        // a valid <img src> — normalize to forward slashes.
        return str_replace('\\', '/', Storage::disk('public')->path($logo));
    }

    /**
     * Same access rule as the old BC-backed invoice download: the order's
     * own customer, or an admin/staff member with the 'orders' permission.
     */
    private function authorizedOrder(string $id): Order
    {
        /** @var \App\Models\ManagementSystem\User|null $authUser */
        $authUser = Auth::user();

        $order = Order::with(['items.item', 'items.itemVariant', 'user.bcCustomer', 'company', 'actions.actionBy'])->findOrFail($id);

        $isOwner = $authUser && (int) $order->user_id === (int) $authUser->id;
        $isAdmin = $authUser && ($authUser->isAdmin() || $authUser->hasPermission('orders'));

        abort_unless($isOwner || $isAdmin, 403, 'You do not have access to this report.');

        return $order;
    }
}
