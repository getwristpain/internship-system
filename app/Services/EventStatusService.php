<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Status;
use Illuminate\Support\Facades\Log;

class EventStatusService
{
    public static function getStatus(string $name = '')
    {
        $slug = 'event-status-' . $name;

        $status = Status::where('slug', $slug)->first();

        if (!$status) {
            Log::error('Status not found!');
            return null;
        }

        return $status;
    }

    public static function setStatus(Carbon $date_start, Carbon $date_finish)
    {
        $today = Carbon::today();

        if ($date_finish->lt($today)) {
            // Jika $date_finish < hari ini
            return self::getStatus('stopped');
        }

        if ($date_start->lte($today) && $date_finish->gte($today)) {
            // Jika $date_start <= hari ini dan $date_finish >= hari ini
            return self::getStatus('running');
        }

        if ($date_start->gte($today) && $date_finish->gte($today)) {
            // Jika $date_start >= hari ini dan $date_finish >= hari ini
            return self::getStatus('not-started');
        }

        // Jika tidak keduanya
        return self::getStatus('pending');
    }
}
