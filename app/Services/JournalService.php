<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class JournalService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get user journals with related attendances, paginated and searchable.
     *
     * @param string $userId
     * @param int $perPage
     * @param string|null $search
     * @return \Illuminate\Pagination\LengthAwarePaginator|null
     */
    public static function getPaginatedJournals(string $userId, int $perPage = 20, ?string $search = null)
    {
        // Build the query to retrieve user journals
        $query = User::find($userId)->journals();

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('activity', 'like', "%{$search}%")
                    ->orWhere('attendance', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Sort journals by date
        $query->orderBy('date');

        // Paginate the journals
        $paginatedJournals = $query->paginate($perPage);

        // Map through the journals to add duration
        $paginatedJournals->getCollection()->transform(function ($journal) {
            $journal->duration = self::calculateDuration($journal->time_start, $journal->time_finish);
            return $journal;
        });

        // Optional: Get all journals after pagination
        $allJournals = $query->get()->transform(function ($journal) {
            $journal->duration = self::calculateDuration($journal->time_start, $journal->time_finish);
            return $journal;
        });

        return [
            'paginated' => $paginatedJournals,
            'all' => $allJournals
        ]; // Return both paginated journals and all journals
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
