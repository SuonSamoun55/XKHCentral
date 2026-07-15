<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            /** @var \App\Models\ManagementSystem\User $user */
            $user = Auth::user();

            return $this->redirectUser($user);
        }

        return view('AUTH.Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['email'] = strtolower(trim($credentials['email']));

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ]);
        }

        $request->session()->regenerate();

        /** @var \App\Models\ManagementSystem\User $user */
        $user = Auth::user();

        $user->last_seen_at = now();
        $user->save();

        return $this->redirectUser($user);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\ManagementSystem\User|null $user */
        $user = Auth::user();

        if ($user) {
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

        $credentials['email'] = strtolower(trim($credentials['email']));

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        /** @var \App\Models\ManagementSystem\User $user */
        $user = Auth::user();

        $user->last_seen_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'token' => $user->createToken('pos-token')->plainTextToken,
            'user' => $user,
        ]);
    }

    public function apiLogout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->last_seen_at = now();
            $user->save();

            optional($user->currentAccessToken())->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    private function redirectUser($user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('pos.index'),
            'customer' => redirect()->route('user.index'),
            default => redirect()->route('login'),
        };
    }
}