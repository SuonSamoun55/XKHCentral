<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\CompanyConnection;
use App\Models\ManagementSystem\User;
use App\Models\POS\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    protected ?string $baseUrl = null;
    protected ?string $companyId = null;
    protected ?CompanyConnection $connection = null;

    protected function loadCompanyConnection(): void
    {
        if ($this->connection) {
            return;
        }

        $selectedCompanyId = session('selected_company_id');
        $company = null;

        if ($selectedCompanyId) {
            $company = Company::with('companyConnection')->find($selectedCompanyId);
        }

        if (!$company) {
            $company = Company::with('companyConnection')->first();
        }

        /** @var CompanyConnection|null $connection */
        $connection = $company?->companyConnection;

        if (!$connection || !$connection->status) {
            return;
        }

        $this->connection = $connection;
        $this->baseUrl = $connection->base_url;
        $this->companyId = $connection->company_bc_id;
    }

    protected function bcUrl(string $path): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->baseUrl || !$this->companyId) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $baseUrl = rtrim($this->baseUrl, '/');
        $resource = ltrim($path, '/');

        $isImagePath = Str::contains($resource, ['/picture', 'getImage']);

        if ($isImagePath) {
            $baseUrl = preg_replace('#/api/[^/]+/[^/]+/v[\d.]+$#i', '/api/v2.0', $baseUrl);
        }

        return $baseUrl . '/companies(' . $this->companyId . ')/' . $resource;
    }

    protected function bcEndpoint(string $field, string $defaultTemplate, array $replacements = []): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->connection) {
            return null;
        }

        $template = (string) ($this->connection->{$field} ?? '');
        if ($template === '') {
            $template = $defaultTemplate;
        }

        $pairs = [
            '{companyId}' => $this->companyId ?? '',
        ];

        foreach ($replacements as $key => $value) {
            $pairs['{' . $key . '}'] = (string) $value;
        }

        $resolved = strtr($template, $pairs);

        if (Str::startsWith($resolved, ['http://', 'https://'])) {
            return $resolved;
        }

        return $this->bcUrl($resolved);
    }

    protected function getToken(): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->connection) {
            return null;
        }
        return Cache::remember('bc_token_' . $this->connection->id, 3300, function () {
            $response = Http::withoutVerifying()->asForm()->timeout(15)->post($this->connection->token_url, [
                'grant_type' => 'client_credentials',
                'client_id' => trim($this->connection->client_id),
                'client_secret' => trim($this->connection->client_secret),
                'scope' => trim($this->connection->api_scope ?: 'https://api.businesscentral.dynamics.com/.default'),
            ]);
            if (!$response->successful()) {
                logger()->error('BC token failed from base controller', [
                    'company_id' => $this->connection->company_id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
            return $response->json()['access_token'] ?? null;
        });
    }
    protected function buildOrderStats(?User $user): array
    {
        $stats = [
            'pending_count' => 0,
            'confirmed_count' => 0,
            'cancelled_count' => 0,
            'pending_amount' => 0,
            'confirmed_amount' => 0,
            'cancelled_amount' => 0,
            'last_order_at' => null,
        ];

        if (!$user) {
            return $stats;
        }
        $cancelledStatuses = ['cancelled', 'canceled'];
        $confirmedStatuses = ['confirmed', 'delivery', 'on-the-way', 'delivered'];
        $stats['pending_count'] = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $stats['confirmed_count'] = Order::where('user_id', $user->id)->whereIn('status', $confirmedStatuses)->count();
        $stats['cancelled_count'] = Order::where('user_id', $user->id)->whereIn('status', $cancelledStatuses)->count();
        $stats['pending_amount'] = Order::where('user_id', $user->id)->where('status', 'pending')->sum('total_amount');
        $stats['confirmed_amount'] = Order::where('user_id', $user->id)->whereIn('status', $confirmedStatuses)->sum('total_amount');
        $stats['cancelled_amount'] = Order::where('user_id', $user->id)->whereIn('status', $cancelledStatuses)->sum('total_amount');
        $stats['last_order_at'] = Order::where('user_id', $user->id)->max('created_at');
        return $stats;
    }
    protected function bc(string $token)
    {
        return Http::withToken($token)->acceptJson();
    }

    protected function valueFrom(array $row, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                return $row[$key];
            }
        }

        return $default;
    }

    protected function toBool(mixed $value, bool $default = false): bool
    {
        return $value === null ? $default : filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

}
