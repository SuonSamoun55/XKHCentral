<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Cart;
use App\Models\POSModel\Order;
use App\Models\POSModel\OrderItem;
use App\Models\MagamentSystemModel\Notification;
use App\Models\MagamentSystemModel\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class HistoryController extends Controller
{
   public function history(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', $user->id)
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('order_no', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('customer_no', 'like', "%{$search}%");
                });
            })
            ->when($request->status && strtolower($request->status) !== 'all', function ($query) use ($request) {
                $status = strtolower(str_replace(' ', '-', $request->status));
                return $query->where('status', $status);
            })
            ->when($request->date, function ($query) use ($request) {
                return $query->whereDate('created_at', $request->date);
            })
            ->with('items')
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('POSViews.POSUserViews.POSHistoryView', compact('orders'));
    }
    public function downloadInvoice($id)
{
    $order = Order::with('items')->findOrFail($id);

    // Verify the order belongs to the logged-in user
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // Example using DomPDF (Standard Laravel way)
    // $pdf = Pdf::loadView('POSViews.Invoices.Template', compact('order'));
    // return $pdf->download('Invoice-'.$order->order_no.'.pdf');

    // Simple Test: Just download a text file to make sure the button works
    $content = "Order: #{$order->order_no}\nTotal: {$order->total_amount}\nStatus: {$order->status}";
    return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename=order-{$order->order_no}.txt");
}
}