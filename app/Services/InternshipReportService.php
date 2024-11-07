<?php

namespace App\Services;

use App\Models\User;

class InternshipReportService
{
    public static function getReports(string $userId = '')
    {
        $user = User::with(['internshipReports'])->find($userId);

        if (!$user) {
            return [];
        }

        return $user->internshipReports;
    }
}
