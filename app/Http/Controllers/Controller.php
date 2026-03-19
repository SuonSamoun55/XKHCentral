<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\MagamentSystemModel\Company;
use App\Models\MagamentSystemModel\CompanyConnection;
use Illuminate\Support\Facades\Http;

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

        if (!$selectedCompanyId) {
            return;
        }

        $company = Company::with('connection')->find($selectedCompanyId);

        /** @var CompanyConnection|null $connection */
        $connection = $company?->connection;

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

        return rtrim($this->baseUrl, '/') . '/companies(' . $this->companyId . ')/' . ltrim($path, '/');
    }

    protected function getToken(): ?string
    {
        $this->loadCompanyConnection();

        if (!$this->connection) {
            return null;
        }

        $response = Http::withoutVerifying()->asForm()->post($this->connection->token_url, [
            'grant_type' => 'client_credentials',
            'client_id' => trim($this->connection->client_id),
            'client_secret' => trim($this->connection->client_secret),
            'scope' => 'https://api.businesscentral.dynamics.com/.default',
        ]);

        if (!$response->successful()) {
            logger()->error('BC token failed from base controller', [
                'selected_company_id' => session('selected_company_id'),
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->json()['access_token'] ?? null;
    }
}
