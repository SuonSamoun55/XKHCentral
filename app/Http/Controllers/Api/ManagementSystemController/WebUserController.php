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

        $userMap = User::where('company_id', $companyId)
            ->get()
            ->keyBy('bc_customer_no');

        foreach ($customers as $customer) {
            $linkedUser = $userMap->get($customer->bc_customer_no);

            if ($linkedUser) {
                $customer->connect_status = 'connected';
                $customer->role = $linkedUser->role ?? 'user';
                $customer->local_user_id = $linkedUser->id;
                $customer->local_name = $linkedUser->name ?? ($customer->display_name ?? $customer->name ?? '-');
                $customer->local_email = $linkedUser->email ?? ($customer->email ?? '-');
                $customer->local_phone = $linkedUser->phone ?? ($customer->phone_number ?? '-');
            } else {
                $customer->connect_status = 'not_connected';
                $customer->role = '-';
                $customer->local_user_id = null;
                $customer->local_name = $customer->display_name ?? $customer->name ?? '-';
                $customer->local_email = $customer->email ?? '-';
                $customer->local_phone = $customer->phone_number ?? '-';
            }

            $customer->name = $customer->display_name ?? $customer->name ?? '-';
            $customer->email = $customer->email ?? '-';
            $customer->phone = $customer->phone_number ?? '-';
        }

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserList',
            compact('customers')
        );
    }

    public function syncBCCustomers()
    {
        $companyId = Company::value('id');

        if (!$companyId) {
            return redirect()->route('users.index')
                ->with('error', 'No company found.');
        }

        $token = $this->getToken();

        if (!$token) {
            return redirect()->route('users.index')
                ->with('error', 'Business Central authentication failed.');
        }

        $url = $this->bcUrl("customers?\$select=id,number,displayName,email,phoneNumber");

        if (!$url) {
            return redirect()->route('users.index')
                ->with('error', 'Unable to build Business Central URL.');
        }

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->get($url);

        if (!$response->successful()) {
            return redirect()->route('users.index')
                ->with('error', 'Failed to fetch BC customers.');
        }

        $data = $response->json('value', []);

        foreach ($data as $row) {
            $displayName = trim($row['displayName'] ?? '');
            $customerNo = $row['number'] ?? null;

            if (!$customerNo) {
                continue;
            }

            BcCustomer::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'bc_customer_no' => $customerNo,
                ],
                [
                    'bc_id' => $row['id'] ?? null,
                    'name' => $displayName !== '' ? $displayName : 'Unknown',
                    'display_name' => $displayName !== '' ? $displayName : 'Unknown',
                    'email' => $row['email'] ?? null,
                    'phone_number' => $row['phoneNumber'] ?? null,
                ]
            );
        }

        return redirect()->route('users.index')
            ->with('success', 'BC customers synced successfully.');
    }

    public function create($id)
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request, $id)
    {
        $customer = BcCustomer::findOrFail($id);
        $companyId = Company::value('id');

        $request->validate([
            'role' => 'required|string|max:50',
            'password' => 'required|min:6|confirmed',
        ]);

        $bcCustomerNo = $customer->bc_customer_no ?? null;

        if (!$bcCustomerNo) {
            return redirect()->route('users.index')
                ->with('error', 'This BC customer has no customer number.');
        }

        if (User::where('company_id', $companyId)
            ->where('bc_customer_no', $bcCustomerNo)
            ->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'This customer is already connected.');
        }

        User::create([
            'company_id' => $companyId,
            'bc_customer_no' => $bcCustomerNo,
            'name' => $customer->display_name ?? $customer->name ?? '-',
            'email' => $customer->email ?? null,
            'phone' => $customer->phone_number ?? null,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => true,
            'linked_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User connected successfully.');
    }

    public function show($id)
    {
        $customer = BcCustomer::findOrFail($id);

        $user = User::where('bc_customer_no', $customer->bc_customer_no)->first();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserShow',
            compact('customer', 'user')
        );
    }

    public function edit($id)
    {
        return redirect()->route('users.index');
    }

    public function update(Request $request, $id)
    {
        $customer = BcCustomer::findOrFail($id);

        $user = User::where('bc_customer_no', $customer->bc_customer_no)->firstOrFail();

        $request->validate([
            'role' => 'required|string|max:50',
            'old_password' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->route('users.index')
                ->with('error', 'Old password is incorrect.');
        }

        $data = [
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $customer = BcCustomer::findOrFail($id);

        User::where('bc_customer_no', $customer->bc_customer_no)->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->route('users.index')
                ->with('error', 'Please select at least one user.');
        }

        $customers = BcCustomer::whereIn('id', $ids)->get();

        foreach ($customers as $customer) {
            User::where('bc_customer_no', $customer->bc_customer_no)->delete();
        }

        return redirect()->route('users.index')
            ->with('success', 'Selected users deleted successfully.');
    }
}
