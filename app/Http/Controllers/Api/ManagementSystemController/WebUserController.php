<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\BcCustomer;
use App\Models\MagamentSystemModel\User;

class WebUserController extends Controller
{
    public function index()
    {
        $customers = BcCustomer::orderBy('id', 'desc')->get();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserList',
            compact('customers')
        );
    }

    public function syncBCCustomers()
    {
        $token = $this->getToken();

        if (!$token) {
            return redirect()->route('users.index')
                ->with('error', 'Business Central authentication failed.');
        }

        $url = $this->bcUrl("customers?\$select=id,number,displayName,email,phoneNumber");

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($url);

        if (!$response->successful()) {
            return redirect()->route('users.index')
                ->with('error', 'Failed to fetch customers from Business Central.');
        }

        $customers = $response->json()['value'] ?? [];

        foreach ($customers as $customer) {
            $customerNo = $customer['number'] ?? null;

            if (!$customerNo) {
                continue;
            }

            $isConnected = User::where('bc_customer_no', $customerNo)->exists();

            BcCustomer::updateOrCreate(
                ['bc_customer_no' => $customerNo],
                [
                    'name' => $customer['displayName'] ?? '',
                    'email' => $customer['email'] ?? null,
                    'phone' => $customer['phoneNumber'] ?? null,
                    'address' => null,
                    'connect_status' => $isConnected ? 'connected' : 'not_connected',
                    'last_synced_at' => now(),
                ]
            );
        }

        return redirect()->route('users.index')
            ->with('success', 'BC customers synced successfully.');
    }

    public function create($id)
    {
        $customer = BcCustomer::findOrFail($id);

        if ($customer->connect_status === 'connected') {
            return redirect()->route('users.index')
                ->with('error', 'This customer is already connected.');
        }

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.create',
            compact('customer')
        );
    }

    public function store(Request $request, $id)
    {
        $customer = BcCustomer::findOrFail($id);

        if ($customer->connect_status === 'connected') {
            return redirect()->route('users.index')
                ->with('error', 'This customer is already connected.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:customer,admin'],
        ]);

        User::create([
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'bc_customer_no' => $customer->bc_customer_no,
            'status' => true,
            'linked_at' => now(),
        ]);

        $customer->update([
            'connect_status' => 'connected',
            'last_synced_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Customer connected successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('ManagementSystemViews.AdminViews.Layouts.UserinfoView.show',
            compact('user')
        );
    }
}
