<?php

namespace App\Services;

use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\CompanyConnection;

class BusinessCentralService
{
    protected ?CompanyConnection $connection = null;

    public function __construct()
    {
        $company = Company::with('companyConnection')->first();

        if ($company && $company->companyConnection && $company->companyConnection->status) {
            $this->connection = $company->companyConnection;
        }
    }

    public function getConnection(): ?CompanyConnection
    {
        return $this->connection;
    }
}
