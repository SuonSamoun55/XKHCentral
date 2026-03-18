<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    protected $baseUrl;
    protected $companyId;

    public function __construct()
    {
        $this->baseUrl   = env('BC_BASE_URL');
        $this->companyId = env('BC_COMPANY_ID');
    }

    protected function bcUrl(string $path): string
    {
        return rtrim($this->baseUrl, '/') . '/companies(' . $this->companyId . ')/' . ltrim($path, '/');
    }

protected function getToken(): ?string
{
    $loginUrl = env('BC_TOKEN_URL');

    if (!$loginUrl) {
        return null;
    }

    $response = Http::withoutVerifying()->asForm()->post($loginUrl, [
        'grant_type'    => 'client_credentials',
        'client_id'     => trim(env('BC_CLIENT_ID', '')),
        'client_secret' => trim(env('BC_CLIENT_SECRET', '')),
        'scope'         => 'https://api.businesscentral.dynamics.com/.default',
    ]);

    if (!$response->successful()) {
        logger()->error('BC token failed', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        return null;
    }

    return $response->json()['access_token'] ?? null;
}
}
