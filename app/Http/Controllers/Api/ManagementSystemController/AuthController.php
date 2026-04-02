<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagamentSystemModel\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('pos.index');
            }

            if ($user->role === 'customer') {
                return redirect()->route('user.index');
            }
        }

        return view('AUTH.Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            if ($user) {
                $user->last_seen_at = now();
                $user->save();
            }

            if ($user->role === 'admin') {
                return redirect()->route('pos.index');
            }

            if ($user->role === 'customer') {
                return redirect()->route('user.index');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
$user->last_seen_at = now();
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        $user->last_seen_at = now();
        $user->save();

        $token = $user->createToken('pos-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
        ]);
    }

   public function apiLogout(Request $request)
{
    /** @var \App\Models\MagamentSystemModel\User|null $user */
    $user = $request->user();

    if ($user) {
        $user->last_seen_at = now();
        $user->save();

        if (method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $accessToken = $user->currentAccessToken();

            if ($accessToken instanceof \Laravel\Sanctum\PersonalAccessToken) {
                $accessToken->delete();
            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Logout successful.',
    ]);
}
}
