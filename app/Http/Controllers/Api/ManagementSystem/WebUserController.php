<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\BcCustomer;
use App\Models\ManagementSystem\User;
use App\Models\POS\NumberSeries;
use App\Models\Role;
use Carbon\Carbon;

class WebUserController extends Controller
{
    protected function assertCustomerInScope(BcCustomer $customer): void
    {
        $companyId = session('selected_company_id');

        if ($companyId && (int) $customer->company_id !== (int) $companyId) {
            abort(403, 'You do not have access to this customer.');
        }
    }

    public function index()
    {
        $companyId = session('selected_company_id');

        $customers = $this->buildCustomerCollection($companyId);

        $roles = Role::when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->get();
        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserList',
            compact('customers', 'roles')
        );
    }

    public function getUsers()
    {
        $companyId = session('selected_company_id');

        $customers = $this->buildCustomerCollection($companyId);

        $data = $customers->map(function ($customer) {
            $displayBcNo = $customer->bc_customer_no ?? '-';
            $displayName = $customer->local_name ?? $customer->name ?? '-';
            $rawEmail = trim((string) ($customer->local_email ?? $customer->email ?? ''));
            $displayEmail = in_array($rawEmail, ['', '.', '-'], true) ? '' : $rawEmail;
            $displayPhone = $customer->local_phone ?? $customer->phone ?? '-';
            $displayRole = $customer->role ?? '-';

            $activityStatus = ($customer->connect_status === 'connected' && ($customer->is_online ?? false))
                ? 'online'
                : 'offline';

            $lastSeenText = $customer->last_seen_at
                ? Carbon::parse($customer->last_seen_at)->format('Y-m-d h:i A')
                : '-';

            return [
                'id' => $customer->id,
                'bc_customer_no' => $displayBcNo,
                'local_customer_no' => $customer->local_customer_no ?? '-',
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
        // Only the columns this method (and its callers, index()/getUsers())
        // actually read — trims what's pulled off disk and hydrated into
        // models, since this runs on every page load and every 15s poll.
        $customers = BcCustomer::select([
                'id', 'company_id', 'bc_customer_no', 'bc_id', 'display_name',
                'name', 'email', 'phone_number', 'profile_image_url', 'local_customer_no',
            ])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderBy('id', 'desc')
            ->get();
        $userMap = User::select([
                'id', 'company_id', 'bc_customer_no', 'status', 'role', 'name',
                'email', 'phone', 'profile_image', 'profile_image_url', 'last_seen_at',
            ])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->get()
            ->keyBy(fn($u) => $u->company_id . '|' . $u->bc_customer_no);

        foreach ($customers as $customer) {
            $linkedUser = $userMap->get($customer->company_id . '|' . $customer->bc_customer_no);

            $bcName = $customer->display_name ?? $customer->name ?? '-';
            $bcEmail = $customer->email ?? '-';
            $bcPhone = $customer->phone_number ?? '-';

            if ($linkedUser && $linkedUser->status) {
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
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('users.index')
                ->with('error', 'Select a company first (Companies list) before syncing BC customers.');
        }

        $token = $this->getToken();

        if (!$token) {
            return redirect()->route('users.index')
                ->with('error', 'Business Central authentication failed.');
        }

        $url = $this->bcEndpoint(
            'customers_endpoint',
            'Customers'
        );

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
                
                
                $fields = $this->extractBcCustomerFields($row);

                if (!$this->toBool($fields['allow_api'], true)) {
                    continue;
                }
                if (!$fields['customer_no']) {
                    continue;
                }

                $bcImageUrl = null;
                if (!empty($fields['bc_id'])) {
                    $bcImageUrl = route('users.bc-image', ['bcId' => $fields['bc_id']]);
                }

                $customer = BcCustomer::updateOrCreate(
                    [
                        'company_id' => $companyId,
                        'bc_customer_no' => $fields['customer_no'],
                    ],
                    $this->filterCustomerDataByExistingColumns(array_merge(
                        $this->bcFieldsForStorage($fields),
                        [
                            'bc_id' => $fields['bc_id'],
                            'profile_image_url' => $bcImageUrl,
                            'last_synced_at' => now(),
                        ]
                    ))
                );

                $this->assignLocalCustomerNo($customer, $companyId);

                User::where('company_id', $companyId)
                    ->where('bc_customer_no', $fields['customer_no'])
                    ->update([
                        'name' => $fields['name'],
                        'email' => $fields['email'],
                        'phone' => $fields['phone'],
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

    protected function extractBcCustomerFields(array $row): array
    {
        $displayName = trim((string) $this->valueFrom($row, [
            'displayName',
            'display_name',
            'name',
            'Name',
            'customerName',
        ], ''));

        return [
            'bc_id' => $this->valueFrom($row, ['id', 'systemId', 'SystemId']),
            'customer_no' => $this->valueFrom($row, [
                'number',
                'no',
                'No',
                'customerNo',
                'customerNumber',
            ]),
            'name' => $displayName !== '' ? $displayName : 'Unknown',
            'email' => $this->valueFrom($row, ['email', 'Email', 'emailAddress']),
            'phone' => $this->valueFrom($row, ['phoneNo', 'phoneNumber', 'phone_number', 'phone', 'Phone']),
            'mobile_phone_no' => $this->valueFrom($row, ['mobilePhoneNo', 'mobile_phone_no', 'mobilePhone']),
            'address' => $this->valueFrom($row, ['address', 'Address']),
            'city' => $this->valueFrom($row, ['city', 'City']),
            'payment_terms_code' => $this->valueFrom($row, ['paymentTermsCode', 'payment_terms_code']),
            'allow_api' => $this->valueFrom($row, ['CustomerButtonAllowAPI', 'customerButtonAllowApi']),
            'customer_price_group' => $this->valueFrom($row, ['customerPriceGroup', 'customer_price_group']),
            'location_code' => $this->valueFrom($row, ['locationCode', 'location_code']),
            'ship_to_code' => $this->valueFrom($row, ['shipToCode', 'ship_to_code']),
            'blocked' => $this->valueFrom($row, ['blocked', 'Blocked']),
            'balance' => $this->valueFrom($row, ['balance', 'Balance'], 0),
            'balance_due' => $this->valueFrom($row, ['balanceDue', 'balance_due'], 0),
            'credit_limit' => $this->valueFrom($row, ['creditLimit', 'credit_limit'], 0),
        ];
    }

    protected function bcFieldsForStorage(array $fields): array
    {
        return [
            'name' => $fields['name'],
            'display_name' => $fields['name'],
            'email' => $fields['email'],
            'phone' => $fields['phone'],
            'phone_number' => $fields['phone'],
            'mobile_phone_no' => $fields['mobile_phone_no'],
            'address' => $fields['address'],
            'city' => $fields['city'],
            'payment_terms_code' => $fields['payment_terms_code'],
            'customer_price_group' => $fields['customer_price_group'],
            'location_code' => $fields['location_code'],
            'ship_to_code' => $fields['ship_to_code'],
            'blocked' => $fields['blocked'],
            'balance' => $fields['balance'],
            'balance_due' => $fields['balance_due'],
            'credit_limit' => $fields['credit_limit'],
        ];
    }
    public function syncSingleCustomer($id)
    {
        $customer = BcCustomer::findOrFail($id);
        $this->assertCustomerInScope($customer);
        $authUser = auth()->user();
        $isOwner = $authUser && $authUser->bc_customer_no && $authUser->bc_customer_no === $customer->bc_customer_no;
        $isAdmin = $authUser && strtolower($authUser->role ?? '') === 'admin';

        if (!$isOwner && !$isAdmin) {
            abort(403);
        }

        if (empty($customer->bc_id)) {
            return response()->json([
                'success' => false,
                'message' => 'This customer has no Business Central ID to sync from.',
            ], 422);
        }

        $token = $this->getToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Business Central authentication failed.',
            ], 502);
        }

        $url = $this->bcUrl("customers({$customer->bc_id})");

        try {
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->timeout(30)
                ->get($url);

            if (!$response->successful()) {
                Log::error('BC single customer sync failed', [
                    'id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch this customer from Business Central.',
                ], 502);
            }

            $fields = $this->extractBcCustomerFields($response->json() ?? []);

            $customer->fill($this->filterCustomerDataByExistingColumns(array_merge(
                $this->bcFieldsForStorage($fields),
                ['last_synced_at' => now()]
            )));
            $customer->save();

            $this->assignLocalCustomerNo($customer, $customer->company_id);

            return response()->json([
                'success' => true,
                'balance' => number_format((float) $customer->balance, 2),
                'balance_due' => number_format((float) $customer->balance_due, 2),
                'credit_limit' => number_format((float) $customer->credit_limit, 2),
                'synced_at' => $customer->last_synced_at->diffForHumans(),
            ]);
        } catch (\Throwable $e) {
            Log::error('BC single customer sync exception', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error syncing this customer: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Gives a synced BC customer its own Laravel-generated number (from the
     * "CUSTOMER" number series, Management > Number Series) the first time
     * it's seen — leaves it alone on every later re-sync. If no CUSTOMER
     * series is configured yet, the customer is simply left without one
     * rather than blocking the sync.
     */
    protected function assignLocalCustomerNo(BcCustomer $customer, ?int $companyId): void
    {
        if (!$companyId || !empty($customer->local_customer_no)) {
            return;
        }

        try {
            $customer->local_customer_no = DB::transaction(fn () => NumberSeries::issue($companyId, 'CUSTOMER'));
            $customer->save();
        } catch (\Throwable $e) {
            Log::warning('Could not assign local customer number', [
                'company_id' => $companyId,
                'bc_customer_no' => $customer->bc_customer_no,
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function filterCustomerDataByExistingColumns(array $data): array
    {
        static $columns = null;

        if ($columns === null) {
            $columns = array_flip(Schema::getColumnListing('bc_customers'));
        }

        return array_intersect_key($data, $columns);
    }

    public function getBCImage($bcId)
    {
        $disk = Storage::disk('public');

        foreach (['jpg', 'png'] as $ext) {
            $cachePath = "bc-images/{$bcId}.{$ext}";
            if ($disk->exists($cachePath)) {
                return response($disk->get($cachePath))
                    ->header('Content-Type', $ext === 'png' ? 'image/png' : 'image/jpeg')
                    ->header('Cache-Control', 'public, max-age=86400');
            }
        }

        // A customer/product with no BC picture (or one BC is timing out on)
        // was re-triggering a full remote HTTP round-trip on every single
        // dashboard load — this negative cache short-circuits that for 10
        // minutes instead, which is what the repeated slow/red "failed"
        // requests in the network waterfall were.
        $failureCacheKey = "bc-image-failed:{$bcId}";
        if (\Illuminate\Support\Facades\Cache::has($failureCacheKey)) {
            return response()->file(public_path('images/default-user.png'));
        }

        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Auth failed'], 401);
        }

        $contentUrl = $this->bcUrl("customers({$bcId})/picture/pictureContent");

        try {
            $imageResponse = Http::withoutVerifying()
                ->withToken($token)
                ->timeout(15)
                ->withHeaders([
                    'Accept' => 'image/jpeg, image/png, image/*',
                ])
                ->get($contentUrl);
        } catch (\Throwable $e) {
            Log::warning('BC customer image fetch failed', [
                'bc_id' => $bcId,
                'url' => $contentUrl,
                'message' => $e->getMessage(),
            ]);

            \Illuminate\Support\Facades\Cache::put($failureCacheKey, true, now()->addMinutes(10));

            return response()->file(public_path('images/default-user.png'));
        }

        if (!$imageResponse->successful()) {
            \Illuminate\Support\Facades\Cache::put($failureCacheKey, true, now()->addMinutes(10));

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customer image',
                'details' => $imageResponse->body(),
                'url' => $contentUrl,
            ], 500);
        }

        $contentType = $imageResponse->header('Content-Type') ?: 'image/jpeg';
        $contentType = explode(';', $contentType)[0];
        $extension   = $contentType === 'image/png' ? 'png' : 'jpg';

        $disk->put("bc-images/{$bcId}.{$extension}", $imageResponse->body());

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
        $this->assertCustomerInScope($customer);
        $companyId = $customer->company_id;

        $request->validate([
            'role' => 'required|string|max:50|exists:roles,name',
            'password' => 'required|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'profile_image_url' => 'nullable|string|max:1000',
        ]);

        $bcCustomerNo = $customer->bc_customer_no ?? null;

        if (!$bcCustomerNo) {
            return redirect()->route('users.index')
                ->with('error', 'This BC customer has no customer number.');
        }
        $existingUser = User::where('company_id', $companyId)
            ->where('bc_customer_no', $bcCustomerNo)
            ->first();

        if ($existingUser && $existingUser->status) {
            return redirect()->route('users.index')
                ->with('error', 'This customer is already connected.');
        }
        $uploadedImagePath = $existingUser?->profile_image;
        if ($request->hasFile('profile_image')) {
            $uploadedImagePath = $request->file('profile_image')->store('users/profile_images', 'public');
        }

        $finalImageUrl = !empty($customer->bc_id)
            ? route('users.bc-image', ['bcId' => $customer->bc_id])
            : ($customer->profile_image_url ?? null);

        $data = [
            'name' => $customer->display_name ?? $customer->name ?? '-',
            'email' => $customer->email ?? null,
            'phone' => $customer->phone_number ?? null,
            'profile_image' => $uploadedImagePath,
            'profile_image_url' => $finalImageUrl,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'role_id' => Role::where('name', $request->role)->where('company_id', $companyId)->value('id'),
            'status' => true,
            'linked_at' => now(),
            'last_seen_at' => null,
        ];

        if ($existingUser) {
            $existingUser->update($data);
        } else {
            User::create(array_merge($data, [
                'company_id' => $companyId,
                'bc_customer_no' => $bcCustomerNo,
            ]));
        }

        return redirect()->route('users.index')
            ->with('success', 'User connected successfully.');
    }

    public function show($id)
    {
        $customer = BcCustomer::findOrFail($id);
        $this->assertCustomerInScope($customer);
        $user = User::where('company_id', $customer->company_id)
            ->where('bc_customer_no', $customer->bc_customer_no)
            ->first();
        $customer->profile_image_display = !empty($customer->profile_image_url)
            ? $customer->profile_image_url
            : $this->defaultImageUrl();

        $orderStats = $this->buildOrderStats($user);
        return view(
            'ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserShow',
            compact('customer', 'user', 'orderStats')
        );
    }
    public function edit($id)
    {
        return redirect()->route('users.index');
    }
    public function update(Request $request, $id)
    {
        $customer = BcCustomer::findOrFail($id);
        $this->assertCustomerInScope($customer);
        $user = User::where('company_id', $customer->company_id)
            ->where('bc_customer_no', $customer->bc_customer_no)
            ->firstOrFail();
        $request->validate([
            'role' => 'required|string|max:50|exists:roles,name',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'profile_image_url' => 'nullable|string|max:1000',
        ]);

        $data = [
            'role' => $request->role,
            'role_id' => Role::where('name', $request->role)->where('company_id', $customer->company_id)->value('id'),
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

    /**
     * Edits the local bc_customers snapshot only (Contact + Fulfillment &
     * Payment cards on the customer detail page). Business Central is the
     * source of truth — the next sync overwrites these fields, so this is a
     * stopgap for correcting a value between syncs, not a permanent override.
     */
    public function updateContactDetails(Request $request, $id)
    {
        $customer = BcCustomer::findOrFail($id);
        $this->assertCustomerInScope($customer);

        $validated = $request->validate([
            'phone' => 'nullable|string|max:50',
            'mobile_phone_no' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'location_code' => 'nullable|string|max:50',
            'ship_to_code' => 'nullable|string|max:50',
            'payment_terms_code' => 'nullable|string|max:50',
            'customer_price_group' => 'nullable|string|max:50',
        ]);

        $customer->update($validated);

        return redirect()->route('users.show', $customer->id)
            ->with('success', 'Customer details updated successfully.');
    }

    /**
     * Disconnects the customer's portal login (deactivates the linked User
     * — randomizes their password and flips status off) without touching
     * the BcCustomer record itself. Previously this deleted the BcCustomer
     * row outright, which just made a real Business Central customer
     * vanish from the list instead of cleanly showing as "Not Connect" —
     * the row now stays, connect_status recomputes to not_connected, and
     * they can be reconnected via the same "Connect" flow at any time.
     */
    public function destroy($id)
    {
        $customer = BcCustomer::findOrFail($id);
        $this->assertCustomerInScope($customer);
        $user = User::where('company_id', $customer->company_id)
            ->where('bc_customer_no', $customer->bc_customer_no)
            ->first();

        if ($user) {
            $user->update([
                'password' => Hash::make(Str::random(40)),
                'status' => false,
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Customer disconnected successfully.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->route('users.index')
                ->with('error', 'Please select at least one user.');
        }

        $companyId = session('selected_company_id');

        $customers = BcCustomer::whereIn('id', $ids)
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->get();

        foreach ($customers as $customer) {
            $user = User::where('company_id', $customer->company_id)
                ->where('bc_customer_no', $customer->bc_customer_no)
                ->first();

            if ($user) {
                $user->update([
                    'password' => Hash::make(Str::random(40)),
                    'status' => false,
                ]);
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'Selected customers disconnected successfully.');
    }
}
