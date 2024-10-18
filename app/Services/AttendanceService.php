<?php

namespace App\Services;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class AttendanceService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getAllAttendances(string $userId = '', ?string $search = null)
    {
        // Build the query to retrieve user attendances
        $query = User::find($userId)->attendances();

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('activity', 'like', "%{$search}%")
                    ->orWhere('attendance', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Sort attendances by date
        $query->orderBy('date');

        // Get all attendances after pagination
        $allAttendances = $query->get()->transform(function ($attendance) {
            $attendance->duration = self::calculateDuration($attendance->time_start, $attendance->time_finish);
            return $attendance;
        });

        return $allAttendances ?? null;
    }

    /**
     * Get user attendances with related attendances, paginated and searchable.
     *
     * @param string $userId
     * @param int $perPage
     * @param string|null $search
     * @return \Illuminate\Pagination\LengthAwarePaginator|null
     */
    public static function getPaginatedAttendances(string $userId = '', ?string $search = null, int $perPage = 20)
    {
        // Build the query to retrieve user attendances
        $query = User::find($userId)->attendances();

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('activity', 'like', "%{$search}%")
                    ->orWhere('attendance', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Sort attendances by date
        $query->orderBy('date');

        // Paginate the attendances
        $paginatedAttendances = $query->paginate($perPage);

        // Map through the attendances to add duration
        $paginatedAttendances->getCollection()->transform(function ($journal) {
            $journal->duration = self::calculateDuration($journal->time_start, $journal->time_finish);
            return $journal;
        });

        return $paginatedAttendances ?? null;
    }

    /**
     * Calculate the duration between two times.
     *
     * @param string|null $startTime
     * @param string|null $finishTime
     * @return string
     */
    private static function calculateDuration(?string $startTime, ?string $finishTime): string
    {
        if (!$startTime || !$finishTime) {
            return 'N/A'; // Handle cases where times are not available
        }

        $start = \Carbon\Carbon::parse($startTime);
        $finish = \Carbon\Carbon::parse($finishTime);

        // Calculate difference in hours and minutes
        $hours = $start->diffInHours($finish);
        $minutes = $start->diffInMinutes($finish) % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
