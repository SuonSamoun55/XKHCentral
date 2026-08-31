<?php

namespace App\Http\Controllers\Api\POS\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = $user->bcCustomer;
        $orderStats = $this->buildOrderStats($user);

        return view('POSViews.POSUserViews.Profile.show', compact('user', 'customer', 'orderStats'));
    }
    public function edit()
    {
        $user = Auth::user();
        return view('POSViews.POSUserViews.Profile.edit', compact('user'));
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

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('POSViews.POSUserViews.Profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()
            ->with('success', 'Password updated successfully.')
            ->with('new_password', $request->password);
    }

    public function index_mobile()
    {
        $user = Auth::user(); // optional, ready for later use

        return view('POSViews.POSUserViews.mobile.POSprofile_mobile', compact('user'));
    }
}
