<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public static function getAllCompanies()
    {
        return Company::all();
    }
}
