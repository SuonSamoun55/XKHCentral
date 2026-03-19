<?php

namespace App\Services;

use App\Models\MagamentSystemModel\Company;
use App\Models\MagamentSystemModel\CompanyConnection;

class BusinessCentralService
{
    protected ?CompanyConnection $connection = null;

    public function __construct()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return;
        }

        $company = Company::with('connection')->find($companyId);

        /** @var CompanyConnection|null $connection */
        $connection = $company?->connection;

        if ($connection && $connection->status) {
            $this->connection = $connection;
        }
    }

    public function getConnection(): ?CompanyConnection
    {
        return $this->connection;
    }
}
