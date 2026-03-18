<?php

namespace App\Http\Controllers\Api\ManagementSystemController;
// use App\Http\Controllers\Api\ManagementSystemController\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
// use tok

class AuthController extends Controller
{
    // Show Login Page
    public function showLogin()
    {
        if (Auth::check()) {

            if (Auth::user()->role === 'admin') {
                return redirect()->route('pos.index');
            }

            if (Auth::user()->role === 'customer') {
                return redirect()->route('user.index');
            }
        }

        return view('AUTH.Login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('pos.index');
            }

            if (Auth::user()->role === 'customer') {
                return redirect()->route('user.index');
            }

        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
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

    /** @var \App\Models\MagamentSystemModel\User $user */
    $user = Auth::user();

    $token = $user->createToken('pos-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login successful.',
        'token' => $token,
        'user' => $user,
    ]);
}
}
