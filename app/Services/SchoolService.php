<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\Log;

class SchoolService
{
    public static function getSchool()
    {
        $school = School::first();
        if (!$school) {
            Log::error('School data not found.');
            return null;
        }

        return $school;
    }
}
