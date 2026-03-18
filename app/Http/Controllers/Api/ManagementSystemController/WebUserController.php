<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\MagamentSystemModel\User;
use Illuminate\Support\Facades\Http;

class WebUserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');

    //     $this->middleware(function ($request, $next) {
    //         if (Auth::user()->role !== 'admin') {
    //             abort(403, 'Only admin can access this page.');
    //         }

    //         return $next($request);
    //     });
    // }

    public function index()
    {
        $users = User::all();

        return view('ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserList', compact('users'));
    }

    public function create()
    {
        return view('ManagementSystemViews.AdminViews.Layouts.UserinfoView.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:customer,admin'],
            'BCcustomer_no' => ['required', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'BCcustomer_no' => $data['BCcustomer_no'],
        ]);

        return redirect()->route('users.index');
    }

    public function getBCCustomers()
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json([
                'error' => 'Business Central authentication failed'
            ], 500);
        }

        $url = $this->bcUrl(
            "customers?\$select=id,number,displayName,email,phoneNumber,currencyCode,balanceDue"
        );

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($url);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to fetch customers',
                'details' => $response->body()
            ], 500);
        }

        $customers = $response->json()['value'] ?? [];

        return response()->json($customers);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $customer = null;

        if (!empty($user->BCcustomer_no) && !empty($user->email)) {
            $token = $this->getToken();

            if ($token) {
                $customerNo = trim($user->BCcustomer_no);
                $email = trim($user->email);

                $url = $this->bcUrl(
                    "customers?\$select=id,number,displayName,type,addressLine1,addressLine2,city,state,country,postalCode,phoneNumber,email,currencyCode,balanceDue,taxRegistrationNumber,lastModifiedDateTime&\$filter=number eq '{$customerNo}' and email eq '{$email}'"
                );

                $response = Http::withoutVerifying()
                    ->withToken($token)
                    ->get($url);

                if ($response->successful()) {
                    $customer = $response->json()['value'][0] ?? null;
                }
            }
        }

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.show',
            compact('user', 'customer')
        );
    }
}
