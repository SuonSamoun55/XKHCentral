<?php
namespace App\Http\Controllers\Api\POSControllers\POSUserController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $data['avatar'] = 'storage/' . $path;
    }

    $user->update($data);

    return back()->with('success', 'Profile updated successfully.');
}
}