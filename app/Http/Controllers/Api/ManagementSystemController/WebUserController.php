<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\BcCustomer;
use App\Models\MagamentSystemModel\User;
use App\Models\MagamentSystemModel\Company;
use Carbon\Carbon;

class WebUserController extends Controller
{
    public function index()
    {
        $companyId = Company::value('id');

        $customers = $this->buildCustomerCollection($companyId);

        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserList',
            compact('customers')
        );
    }

   public function getUsers()
{
    $companyId = Company::value('id');

    $customers = $this->buildCustomerCollection($companyId);

    $data = $customers->map(function ($customer) {
        $displayBcNo = $customer->bc_customer_no ?? '-';
        $displayName = $customer->local_name ?? $customer->name ?? '-';
        $displayEmail = $customer->local_email ?? $customer->email ?? '-';
        $displayPhone = $customer->local_phone ?? $customer->phone ?? '-';
        $displayRole = $customer->role ?? '-';

        $activityStatus = ($customer->connect_status === 'connected' && ($customer->is_online ?? false))
            ? 'online'
            : 'offline';

        $lastSeenText = $customer->last_seen_at
            ? \Carbon\Carbon::parse($customer->last_seen_at)->format('Y-m-d h:i A')
            : '-';

        return [
            'id' => $customer->id,
            'bc_customer_no' => $displayBcNo,
            'name' => $displayName,
            'email' => $displayEmail,
            'phone' => $displayPhone,
            'role' => $displayRole,
            'connect_status' => $customer->connect_status ?? 'not_connected',
            'activity_status' => $activityStatus,
            'is_online' => (bool) ($customer->is_online ?? false),
            'last_seen_at' => $lastSeenText,
            'offline_duration' => $customer->offline_duration ?? '-',
            'profile_image_display' => $customer->profile_image_display ?? null,
            'profile_image_url' => $customer->profile_image_url ?? '',
            'show_url' => route('users.show', $customer->id),
            'destroy_url' => route('users.destroy', $customer->id),
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $data,
        'total' => $data->count(),
    ]);
}
protected function getCustomerImageDisplay($customer, $linkedUser = null)
{
    if ($linkedUser && !empty($linkedUser->profile_image)) {
        return asset('storage/' . $linkedUser->profile_image);
    }

    if ($linkedUser && !empty($linkedUser->profile_image_url)) {
        return $linkedUser->profile_image_url;
    }

    if (!empty($customer->bc_id)) {
        return route('users.bc-image', ['bcId' => $customer->bc_id]);
    }

    if (!empty($customer->profile_image_url)) {
        return $customer->profile_image_url;
    }

    return $this->defaultImageUrl();
}
    protected function buildCustomerCollection($companyId)
{
    $customers = BcCustomer::where('company_id', $companyId)
        ->orderBy('id', 'desc')
        ->get();

    $userMap = User::where('company_id', $companyId)
        ->get()
        ->keyBy('bc_customer_no');

    foreach ($customers as $customer) {
        $linkedUser = $userMap->get($customer->bc_customer_no);

        $bcName = $customer->display_name ?? $customer->name ?? '-';
        $bcEmail = $customer->email ?? '-';
        $bcPhone = $customer->phone_number ?? '-';

        if ($linkedUser) {
            $customer->connect_status = 'connected';
            $customer->role = $linkedUser->role ?? 'user';
            $customer->local_user_id = $linkedUser->id;

            $customer->local_name = !empty($linkedUser->name) ? $linkedUser->name : $bcName;
            $customer->local_email = !empty($linkedUser->email) ? $linkedUser->email : $bcEmail;
            $customer->local_phone = !empty($linkedUser->phone) ? $linkedUser->phone : $bcPhone;

            $customer->profile_image = $linkedUser->profile_image ?? null;
            $customer->profile_image_url = $linkedUser->profile_image_url ?? null;
            $customer->profile_image_display = $this->getCustomerImageDisplay($customer, $linkedUser);

            $customer->last_seen_at = $linkedUser->last_seen_at;
            $customer->is_online = $linkedUser->is_online;
            $customer->offline_duration = $linkedUser->is_online
                ? 'Online now'
                : $linkedUser->offline_duration;
        } else {
            $customer->connect_status = 'not_connected';
            $customer->role = '-';
            $customer->local_user_id = null;

            $customer->local_name = $bcName;
            $customer->local_email = $bcEmail;
            $customer->local_phone = $bcPhone;

            $customer->profile_image = null;
            $customer->profile_image_url = !empty($customer->bc_id)
                ? route('users.bc-image', ['bcId' => $customer->bc_id])
                : ($customer->profile_image_url ?? null);

            $customer->profile_image_display = $this->getCustomerImageDisplay($customer, null);

            $customer->last_seen_at = null;
            $customer->is_online = false;
            $customer->offline_duration = 'Not connected';
        }

        $customer->name = $bcName;
        $customer->email = $bcEmail;
        $customer->phone = $bcPhone;
    }

    return $customers;
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

        try {
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->timeout(60)
                ->get($url);

            if (!$response->successful()) {
                Log::error('BC sync failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $url,
                ]);

                return redirect()->route('users.index')
                    ->with('error', 'Failed to fetch BC customers.');
            }

            $data = $response->json('value', []);

            foreach ($data as $row) {
                $displayName = trim($row['displayName'] ?? '');
                $customerNo = $row['number'] ?? null;
                $bcId = $row['id'] ?? null;

                if (!$customerNo) {
                    continue;
                }

                $phoneNumber = $row['phoneNumber'] ?? null;

                $bcImageUrl = null;
                if (!empty($bcId)) {
                    $bcImageUrl = route('users.bc-image', ['bcId' => $bcId]);
                }

                BcCustomer::updateOrCreate(
                    [
                        'company_id' => $companyId,
                        'bc_customer_no' => $customerNo,
                    ],
                    [
                        'bc_id' => $bcId,
                        'name' => $displayName !== '' ? $displayName : 'Unknown',
                        'display_name' => $displayName !== '' ? $displayName : 'Unknown',
                        'email' => $row['email'] ?? null,
                        'phone_number' => $phoneNumber,
                        'profile_image_url' => $bcImageUrl,
                    ]
                );

                User::where('company_id', $companyId)
                    ->where('bc_customer_no', $customerNo)
                    ->update([
                        'name' => $displayName !== '' ? $displayName : 'Unknown',
                        'email' => $row['email'] ?? null,
                        'phone' => $phoneNumber,
                        'profile_image_url' => $bcImageUrl,
                    ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'BC customers synced successfully.');
        } catch (\Throwable $e) {
            Log::error('BC sync exception', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Error while syncing BC customers: ' . $e->getMessage());
        }
    }

    public function getBCImage($bcId)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Auth failed'], 401);
        }

        $contentUrl = $this->bcUrl("customers({$bcId})/picture/pictureContent");

        $imageResponse = Http::withoutVerifying()
            ->withToken($token)
            ->withHeaders([
                'Accept' => 'image/jpeg, image/png, image/*',
            ])
            ->get($contentUrl);

        if (!$imageResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customer image',
                'details' => $imageResponse->body(),
                'url' => $contentUrl,
            ], 500);
        }

        $contentType = $imageResponse->header('Content-Type') ?: 'image/jpeg';
        $contentType = explode(';', $contentType)[0];

        return response($imageResponse->body())
            ->header('Content-Type', $contentType)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    protected function defaultImageUrl()
    {
        $fallbackPath = public_path('images/default-user.png');

        if (file_exists($fallbackPath)) {
            return asset('images/default-user.png');
        }

        return '';
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
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'profile_image_url' => 'nullable|string|max:1000',
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

       $uploadedImagePath = null;

if ($request->hasFile('profile_image')) {
    $uploadedImagePath = $request->file('profile_image')->store('users/profile_images', 'public');
}

$finalImageUrl = !empty($customer->bc_id)
    ? route('users.bc-image', ['bcId' => $customer->bc_id])
    : ($customer->profile_image_url ?? null);

User::create([
    'company_id' => $companyId,
    'bc_customer_no' => $bcCustomerNo,
    'name' => $customer->display_name ?? $customer->name ?? '-',
    'email' => $customer->email ?? null,
    'phone' => $customer->phone_number ?? null,
    'profile_image' => $uploadedImagePath,
    'profile_image_url' => $finalImageUrl,
    'password' => Hash::make($request->password),
    'role' => $request->role,
    'status' => true,
    'linked_at' => now(),
    'last_seen_at' => null,
]);
        return redirect()->route('users.index')
            ->with('success', 'User connected successfully.');
    }

    public function show($id)
    {
        $customer = BcCustomer::findOrFail($id);
        $user = User::where('bc_customer_no', $customer->bc_customer_no)->first();

        if ($user) {
            $user->profile_image_display = $user->profile_image_display;
        }

        $customer->profile_image_display = !empty($customer->profile_image_url)
            ? $customer->profile_image_url
            : $this->defaultImageUrl();

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
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'profile_image_url' => 'nullable|string|max:1000',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->route('users.index')
                ->with('error', 'Old password is incorrect.');
        }

     $data = [
    'role' => $request->role,
    'name' => $customer->display_name ?? $customer->name ?? $user->name,
    'email' => $customer->email ?? $user->email,
    'phone' => $customer->phone_number ?? $user->phone,
];

if ($request->hasFile('profile_image')) {
    if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
        Storage::disk('public')->delete($user->profile_image);
    }

    $data['profile_image'] = $request->file('profile_image')->store('users/profile_images', 'public');
} else {
    $data['profile_image'] = $user->profile_image;
}

$data['profile_image_url'] = !empty($customer->bc_id)
    ? route('users.bc-image', ['bcId' => $customer->bc_id])
    : ($customer->profile_image_url ?? $user->profile_image_url);

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
        $user = User::where('bc_customer_no', $customer->bc_customer_no)->first();

        if ($user) {
            if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->delete();
        }

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
            $user = User::where('bc_customer_no', $customer->bc_customer_no)->first();

            if ($user) {
                if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $user->delete();
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'Selected users deleted successfully.');
    }
}
