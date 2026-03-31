<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use App\Models\MagamentSystemModel\Notification;
use App\Models\MagamentSystemModel\User;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $tab = $request->get('tab', 'inbox');

        $notificationQuery = Notification::with('user')
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            });

        if ($request->filled('search')) {
            $search = trim($request->search);

            $notificationQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        if ($tab === 'unread') {
            $notificationQuery->where('is_read', false);
        }

        if ($request->filled('date')) {
            $notificationQuery->whereDate('created_at', $request->date);
        }

        $notifications = $notificationQuery
            ->latest()
            ->paginate(10);

        $notifications->appends($request->query());

        $allCount = Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->count();

        $unreadCount = Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->where('is_read', false)
            ->count();

        $customers = User::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->where('status', true)
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.Notifications.AdminNotificationViews',
            compact('notifications', 'customers', 'allCount', 'unreadCount', 'tab')
        );
    }

    public function store(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');

        $request->validate([
            'send_type' => 'required|in:all,specific',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $customerQuery = User::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->where('status', true)
            ->where('role', '!=', 'admin');

        if ($request->send_type === 'all') {
            $customers = $customerQuery->get();

            if ($customers->isEmpty()) {
                return back()->withInput()->with('error', 'No active customers found.');
            }

            foreach ($customers as $customer) {
                Notification::create([
                    'user_id' => $customer->id,
                    'order_id' => null,
                    'item_id' => null,
                    'type' => $request->type,
                    'title' => $request->title,
                    'message' => $request->message,
                    'is_read' => false,
                ]);
            }

            return back()->with('success', 'Notification sent to all customers successfully.');
        }

        if (empty($request->user_id)) {
            return back()->withInput()->with('error', 'Please select a customer.');
        }

        $customer = $customerQuery->where('id', $request->user_id)->first();

        if (!$customer) {
            return back()->withInput()->with('error', 'Selected customer not found.');
        }

        Notification::create([
            'user_id' => $customer->id,
            'order_id' => null,
            'item_id' => null,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return back()->with('success', 'Notification sent successfully.');
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $selectedCompanyId = session('selected_company_id');

        Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function deleteSelected(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $ids = $request->input('notification_ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'No notifications selected.');
        }

        Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('success', 'Selected notifications deleted.');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }
}
