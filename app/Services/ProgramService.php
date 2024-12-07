<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Program;
use Illuminate\Support\Facades\Log;

class ProgramService
{
    public static function getAllPrograms()
    {
        $programs = Program::with(['status'])->orderBy('year', 'desc')->get();

        if ($programs->isEmpty()) {
            Log::info('No programs found.');
            return collect();
        }

        return $programs;
    }

    public static function getLatestProgram(?int $programId = null)
    {
        if (!$programId) {
            return Program::where('year', Carbon::now()->format('Y'))->orderBy('updated_at', 'desc')->first() ?? null;
        }

        return Program::find($programId) ?? null;
    }
}
