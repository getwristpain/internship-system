<?php

namespace App\Services;

use App\Models\Internship;

class InternshipService
{
    public static function getAllInternships(?int $programId)
    {
        if (!$programId) {
            return Internship::all();
        }

        return Internship::where('program_id', $programId)->get();
    }
}
