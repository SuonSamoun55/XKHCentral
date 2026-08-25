<?php
namespace App\Http\Controllers\Api\POS\Admin\Profile;
use App\Http\Controllers\Controller;
use App\Models\POS\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyId = session('selected_company_id');

        // Same "approved" definition used on the order detail pages
        // (order->status / action_type of confirmed|approved).
        $approvedActionTypes = ['confirmed', 'approved'];

        // Scoped to the currently selected company — an order approved while
        // viewing Company 1 must not still show up on this profile once the
        // admin switches to Company 2.
        $approvedOrders = Order::with('user')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereHas('actions', function ($q) use ($user, $approvedActionTypes) {
                $q->where('action_by', $user->id)
                    ->whereIn('action_type', $approvedActionTypes);
            })
            ->get()
            ->sortByDesc(fn ($order) => $order->checked_out_at ?? $order->created_at)
            ->values();

        $approvedOrdersCount = $approvedOrders->count();

        $approvedCustomerRows = $approvedOrders
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->map(fn ($orders) => [
                'user_id' => $orders->first()->user_id,
                'customer_name' => $orders->first()->user->name ?? 'Unknown',
                'orders_count' => $orders->count(),
            ])
            ->sortByDesc('orders_count')
            ->values();

        $approvedCustomersCount = $approvedCustomerRows->count();

        return view('ManagementSystemViews.AdminViews.Layouts.setting.profile.adminprofile', compact(
            'user',
            'approvedOrders',
            'approvedOrdersCount',
            'approvedCustomerRows',
            'approvedCustomersCount'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'dob' => 'nullable|date',
            'location' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['profile_image'] = $path; // Store just the path, not 'storage/' prefix
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('ManagementSystemViews.AdminViews.Layouts.setting.profile.adminchangepassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = auth()->user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()
            ->with('success', 'Password updated successfully.')
            ->with('new_password', $request->password);
    }
}