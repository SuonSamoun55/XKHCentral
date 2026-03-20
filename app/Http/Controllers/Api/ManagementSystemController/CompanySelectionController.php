<?php

// namespace App\Http\Controllers\Api\ManagementSystemController;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\MagamentSystemModel\Company;
// use App\Models\MagamentSystemModel\CompanyConnection;

// class CompanySelectionController extends Controller
// {
//     public function select(Request $request)
//     {
//         $request->validate([
//             'company_id' => ['required', 'exists:companies,id'],
//         ]);

//         $company = Company::with('connection')->findOrFail($request->company_id);

//         /** @var CompanyConnection|null $connection */
//         $connection = $company->connection;

//         if (!$connection || !$connection->status) {
//             return redirect()->back()->with('error', 'This company connection is not active.');
//         }

//         session([
//             'selected_company_id' => $company->id,
//             'selected_company_name' => $company->name,
//         ]);

//         return redirect()->back()->with('success', 'Company selected successfully.');
//     }
// }

