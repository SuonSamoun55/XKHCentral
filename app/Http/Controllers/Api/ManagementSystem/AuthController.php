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

        return response(view('AUTH.Login'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['email'] = strtolower(trim($credentials['email']));

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->onlyInput('email');
        };

        /** @var \App\Models\ManagementSystem\User $user */
        $user = Auth::user();
        if (!$user->status) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'This account is not active. Please contact an administrator.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user->last_seen_at = now();
        $user->save();
        if ($user->company_id) {
            session(['selected_company_id' => $user->company_id]);
        } else {
            session()->forget('selected_company_id');
        }

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

        if (!$user->status) {
            Auth::logout();

            return response()->json([
                'success' => false,
                'message' => 'This account is not active. Please contact an administrator.',
            ], 403);
        }

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
        if (strtolower((string) $user->role) === 'admin') {
            return redirect()->route('pos.index');
        }

        $permissionNames = $user->roleRelation
            ? $user->roleRelation->permissions->pluck('name')
            : collect();

        if ($permissionNames->contains('dashboard')) {
            return redirect()->route('pos.index');
        }

        if ($permissionNames->contains('home')) {
            return redirect()->route('user.index');
        }

        return back()->withErrors([
            'email' => 'Your role has no assigned home page yet. Ask an admin to grant it "Dashboard" (Admin Side) or "Home" (User Side) access under Roles.',
        ])->onlyInput('email');
    }
}
