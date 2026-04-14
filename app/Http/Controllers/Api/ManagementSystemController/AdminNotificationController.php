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
        $tab = $request->get('tab', 'all');

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
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('date')) {
            $notificationQuery->whereDate('created_at', $request->date);
        }

        if ($tab === 'new') {
            $notificationQuery->where('is_read', false);
        } elseif ($tab === 'user_contact') {
            $notificationQuery->where(function ($q) {
                $q->where('type', 'user_contact')
                    ->orWhere('title', 'like', '%contact%')
                    ->orWhere('message', 'like', '%contact%');
            });
        } elseif ($tab === 'out_of_stock') {
            $notificationQuery->where(function ($q) {
                $q->where('type', 'out_of_stock')
                    ->orWhere('title', 'like', '%out of stock%')
                    ->orWhere('message', 'like', '%out of stock%');
            });
        }

        $notifications = $notificationQuery
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        // Set profile_image_display for users in notifications
        $notifications->getCollection()->transform(function ($notification) {
            if ($notification->user) {
                $notification->user->profile_image_display = $this->getCustomerImageDisplay($notification->user);
            }
            return $notification;
        });

        $allCount = Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->count();

        $newCount = Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->where('is_read', false)
            ->count();

        $userContactCount = Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->where(function ($q) {
                $q->where('type', 'user_contact')
                    ->orWhere('title', 'like', '%contact%')
                    ->orWhere('message', 'like', '%contact%');
            })
            ->count();

        $outOfStockCount = Notification::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->where(function ($q) {
                $q->where('type', 'out_of_stock')
                    ->orWhere('title', 'like', '%out of stock%')
                    ->orWhere('message', 'like', '%out of stock%');
            })
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
            compact(
                'notifications',
                'customers',
                'allCount',
                'newCount',
                'userContactCount',
                'outOfStockCount',
                'tab'
            )
        );
    }

    public function show($id)
    {
        $selectedCompanyId = session('selected_company_id');

        $notification = Notification::with('user')
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                });
            })
            ->findOrFail($id);

        // Set profile_image_display for the user
        if ($notification->user) {
            $notification->user->profile_image_display = $this->getCustomerImageDisplay($notification->user);
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
            ]);
        }

        return view(
            'ManagementSystemViews.AdminViews.Layouts.Notifications.NotificationsViews',
            compact('notification')
        );
    }
public function searchCustomers(Request $request)
{
    $selectedCompanyId = session('selected_company_id');
    $keyword = trim((string) $request->get('q', ''));

    $customers = User::query()
        ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
            $query->where('company_id', $selectedCompanyId);
        })
        ->where('status', true)
        ->where('role', '!=', 'admin')
        ->when($keyword !== '', function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhere('bc_customer_no', 'like', '%' . $keyword . '%');
            });
        })
        ->orderBy('name')
        ->limit(15)
        ->get([
            'id',
            'name',
            'email',
            'phone',
            'bc_customer_no',
            'profile_image',
            'profile_image_url',
        ]);

    $mapped = $customers->map(function ($customer) {
        $avatar = asset('images/default-avatar.png');

        if (!empty($customer->profile_image)) {
            $avatar = asset('storage/' . ltrim($customer->profile_image, '/'));
        } elseif (!empty($customer->profile_image_url)) {
            $avatar = $customer->profile_image_url;
        } elseif (!empty($customer->bc_customer_no)) {
            $avatar = route('users.bc.image', $customer->bc_customer_no);
        }

        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email ?? 'No Email',
            'phone' => $customer->phone ?? '',
            'customer_no' => $customer->bc_customer_no ?? 'No Customer No',
            'avatar' => $avatar,
        ];
    })->values();

    return response()->json([
        'success' => true,
        'data' => $mapped,
    ]);
}

public function latestNotifications(Request $request)
{
    $selectedCompanyId = session('selected_company_id');
    $lastId = (int) $request->get('last_id', 0);

    $notifications = Notification::with('user')
        ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
            $query->whereHas('user', function ($q) use ($selectedCompanyId) {
                $q->where('company_id', $selectedCompanyId);
            });
        })
        ->where('id', '>', $lastId)
        ->latest('id')
        ->limit(20)
        ->get();

    $mapped = $notifications->map(function ($notification) {
        $user = $notification->user;
        $avatar = asset('images/default-avatar.png');

        if ($user) {
            if (!empty($user->profile_image)) {
                $avatar = asset('storage/' . ltrim($user->profile_image, '/'));
            } elseif (!empty($user->profile_image_url)) {
                $avatar = $user->profile_image_url;
            } elseif (!empty($user->bc_customer_no)) {
                $avatar = route('users.bc.image', $user->bc_customer_no);
            }
        }

        return [
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => strip_tags($notification->message ?? ''),
            'type' => $notification->type,
            'is_read' => (bool) $notification->is_read,
            'time' => optional($notification->created_at)->format('H:i'),
            'created_at' => optional($notification->created_at)->toDateTimeString(),
            'show_url' => route('admin.notifications.show', $notification->id),
            'user_name' => optional($user)->name ?? 'Unknown User',
            'avatar' => $avatar,
        ];
    })->values();

    return response()->json([
        'success' => true,
        'data' => $mapped,
        'last_id' => $notifications->max('id') ?? $lastId,
    ]);
}
  public function store(Request $request)
{
    $selectedCompanyId = session('selected_company_id');

    $request->validate([
        'send_type'   => 'required|in:all,specific,multiple',
        'user_id'     => 'nullable|exists:users,id',
        'user_ids'    => 'nullable|array',
        'user_ids.*'  => 'nullable|exists:users,id',
        'type'        => 'required|string|max:100',
        'title'       => 'required|string|max:255',
        'message'     => 'required|string',
    ]);

    $allowedHtml = '<b><strong><i><em><u><ul><ol><li><br><p><div>';
    $cleanMessage = trim(strip_tags($request->message, $allowedHtml));

    if ($cleanMessage === '') {
        return back()->withInput()->with('error', 'Message cannot be empty.');
    }

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
                'user_id'  => $customer->id,
                'order_id' => null,
                'item_id'  => null,
                'type'     => $request->type,
                'title'    => $request->title,
                'message'  => $cleanMessage,
                'is_read'  => false,
            ]);
        }

        return back()->with('success', 'Notification sent to all customers successfully.');
    }

    if ($request->send_type === 'specific') {
        if (empty($request->user_id)) {
            return back()->withInput()->with('error', 'Please select a customer.');
        }

        $customer = (clone $customerQuery)->where('id', $request->user_id)->first();

        if (!$customer) {
            return back()->withInput()->with('error', 'Selected customer not found.');
        }

        Notification::create([
            'user_id'  => $customer->id,
            'order_id' => null,
            'item_id'  => null,
            'type'     => $request->type,
            'title'    => $request->title,
            'message'  => $cleanMessage,
            'is_read'  => false,
        ]);

        return back()->with('success', 'Notification sent successfully.');
    }

    $selectedIds = collect($request->user_ids ?? [])
        ->filter()
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values();

    if ($selectedIds->isEmpty()) {
        return back()->withInput()->with('error', 'Please select at least one customer.');
    }

    $customers = (clone $customerQuery)
        ->whereIn('id', $selectedIds->all())
        ->get();

    if ($customers->isEmpty()) {
        return back()->withInput()->with('error', 'Selected customers not found.');
    }

    foreach ($customers as $customer) {
        Notification::create([
            'user_id'  => $customer->id,
            'order_id' => null,
            'item_id'  => null,
            'type'     => $request->type,
            'title'    => $request->title,
            'message'  => $cleanMessage,
            'is_read'  => false,
        ]);
    }

    return back()->with('success', 'Notification sent to selected customers successfully.');
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

    protected function getCustomerImageDisplay($user)
    {
        if (!empty($user->profile_image)) {
            return asset('storage/' . $user->profile_image);
        }

        if (!empty($user->profile_image_url)) {
            return $user->profile_image_url;
        }

        if (!empty($user->bc_id)) {
            return route('users.bc-image', ['bcId' => $user->bc_id]);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=17bfd0&color=fff&size=128';
    }
}
