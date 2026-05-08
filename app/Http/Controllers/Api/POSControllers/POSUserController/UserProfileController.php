<?php
namespace App\Http\Controllers\Api\POSControllers\POSUserController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('POSViews.POSUserViews.POSUserProfile', compact('user'));
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
        return view('POSViews.POSUserViews.POSUserChangePW');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
    
public function index_mobile()
    {
        $user = Auth::user(); // optional, ready for later use

        return view('POSViews.POSUserViews.POSprofile_mobile', compact('user'));
    }

}
