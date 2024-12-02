<?php

namespace App\Services;

use App\Models\Program;
use Illuminate\Support\Facades\Log;

class ProgramService
{
    public static function getPrograms()
    {
        $programs = Program::with(['status'])->orderBy('year', 'desc')->get();

        if ($programs->isEmpty()) {
            Log::info('No programs found.');
            return collect();
        }

        return $programs;
    }
}
