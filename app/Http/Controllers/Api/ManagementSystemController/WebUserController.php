<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\BcCustomer;
use App\Models\MagamentSystemModel\User;
use App\Models\MagamentSystemModel\Company;

class WebUserController extends Controller
{
    public function index()
    {
        $companyId = Company::value('id');

        $customers = BcCustomer::where('company_id', $companyId)
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserList',
            compact('customers')
        );
    }

    public function syncBCCustomers()
    {
        $companyId = Company::value('id');

        $token = $this->getToken();

        if (!$token) {
            return redirect()->route('users.index')
                ->with('error', 'Business Central authentication failed.');
        }

        $url = $this->bcUrl("customers?\$select=id,number,displayName,email,phoneNumber");

        if (!$url) {
            return redirect()->route('users.index')
                ->with('error', 'Business Central URL could not be built.');
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($url);

        if (!$response->successful()) {
            return redirect()->route('users.index')
                ->with('error', 'Failed to fetch customers.');
        }

        $customers = $response->json()['value'] ?? [];

        foreach ($customers as $customer) {
            $customerNo = $customer['number'] ?? null;
            $bcId = $customer['id'] ?? null;

            if (!$customerNo || !$bcId) continue;

            $isConnected = User::where('bc_customer_no', $customerNo)
                ->where('company_id', $companyId)
                ->exists();

            BcCustomer::updateOrCreate(
                [
                    'bc_customer_no' => $customerNo,
                    'company_id' => $companyId,
                ],
                [
                    'bc_id' => $bcId,
                    'name' => $customer['displayName'] ?? '',
                    'email' => $customer['email'] ?? null,
                    'phone' => $customer['phoneNumber'] ?? null,
                    'connect_status' => $isConnected ? 'connected' : 'not_connected',
                    'last_synced_at' => now(),
                ]
            );
        }

        return redirect()->route('users.index')
            ->with('success', 'Customers synced successfully.');
    }

    public function create($id)
    {
        $companyId = Company::value('id');

        $customer = BcCustomer::where('company_id', $companyId)
            ->findOrFail($id);

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
        $companyId = Company::value('id');

        $customer = BcCustomer::where('company_id', $companyId)
            ->findOrFail($id);

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
            'company_id' => $companyId,
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
        $companyId = Company::value('id');

        $user = User::where('company_id', $companyId)
            ->findOrFail($id);

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.show',
            compact('user')
        );
    }
}
