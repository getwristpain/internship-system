<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Str;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JournalService
{
    /**
     * Get all journals for a user, optionally searchable.
     *
     * @param string $userId
     * @param string|null $search
     * @return Collection
     */
    public static function getAllJournals(string $userId = '', ?string $search = null): Collection
    {
        if (empty($userId)) {
            return collect(); // Return empty collection if userId is empty
        }

        $user = User::find($userId);
        if (!$user) {
            return collect(); // Return empty collection if user not found
        }

        // Build journal query with optional search filter
        $query = $user->journals();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('activity', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Order journals by date and transform results
        $query->orderBy('date');

        $allJournals = $query->get()->transform(function ($journal) {
            return self::transformJournal($journal, 'all');
        });

        return $allJournals->isEmpty() ? collect() : $allJournals;
    }

    /**
     * Get paginated journals for a user with optional search.
     *
     * @param string $userId
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public static function getPaginatedJournals(string $userId = '', ?string $search = null, int $perPage = 20): LengthAwarePaginator
    {
        if (empty($userId)) {
            return new LengthAwarePaginator(collect(), 0, $perPage);
        }

        $user = User::find($userId);
        if (!$user) {
            return new LengthAwarePaginator(collect(), 0, $perPage);
        }

        // Build journal query with optional search filter
        $query = $user->journals();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('activity', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Order and paginate journals, then transform results
        $paginatedJournals = $query->orderBy('date')->paginate($perPage);

        $paginatedJournals->getCollection()->transform(function ($journal) {
            return self::transformJournal($journal, 'paginated');
        });

        return $paginatedJournals;
    }

    /**
     * Transform individual journal based on context (all or paginated).
     *
     * @param $journal
     * @param string $context
     * @return mixed
     */
    private static function transformJournal($journal, string $context)
    {
        // Set the date format based on the context
        $dateFormat = $context === 'paginated' ? 'F d, Y' : 'd-m-Y';

        $journal->date = Carbon::parse($journal->date)->translatedFormat($dateFormat);
        $journal->attendance = self::getAttendanceStatus($journal->attendance);
        $journal->duration = self::calculateDuration($journal->time_start, $journal->time_finish);

        return $journal;
    }

    /**
     * Get attendance status name by slug.
     *
     * @param string|null $status
     * @return string|null
     */
    public static function getAttendanceStatus(?string $status): ?string
    {
        if ($status) {
            $status = Status::where('slug', $status)->first();
            return $status ? $status->name : null;
        }
        return null;
    }

    /**
     * Calculate duration between start and finish times.
     *
     * @param string|null $startTime
     * @param string|null $finishTime
     * @return string
     */
    private static function calculateDuration(?string $startTime, ?string $finishTime): string
    {
        if (!$startTime || !$finishTime) {
            return 'N/A';
        }

        $start = Carbon::parse($startTime);
        $finish = Carbon::parse($finishTime);
        $hours = $start->diffInHours($finish);
        $minutes = $start->diffInMinutes($finish) % 60;

        return sprintf('%d jam %d menit', $hours, $minutes);
    }

    /**
     * Get attendance statuses excluding specific statuses.
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        try {
            $excludedStatuses = ['attendance-status-excused', 'attendance-status-vacation'];

            $statuses = Status::where('type', 'attendance-status')
                ->whereNotIn('slug', $excludedStatuses)
                ->get();

            if ($statuses->isEmpty()) {
                throw new ModelNotFoundException('No statuses found.');
            }

            return $statuses->map(function ($status) {
                return [
                    'value' => $status->slug,
                    'text' => Str::title(__('attendance.' . $status->name)),
                    'description' => $status->description ?? 'Deskripsi tidak tersedia',
                    'badgeClass' => StatusBadgeMapper::getStatusBadgeClass($status->name),
                ];
            })->toArray();
        } catch (ModelNotFoundException $e) {
            report($e);
            return self::defaultStatus();
        } catch (\Exception $e) {
            report($e);
            return self::defaultErrorStatus();
        }
    }

    /**
     * Default status to return when no statuses found.
     *
     * @return array
     */
    private static function defaultStatus(): array
    {
        return [
            [
                'value' => 'unknown',
                'text' => 'Unknown',
                'description' => 'No status available',
                'badgeClass' => 'badge badge-neutral',
            ]
        ];
    }

    /**
     * Default error status to return on exception.
     *
     * @return array
     */
    private static function defaultErrorStatus(): array
    {
        return [
            [
                'value' => 'error',
                'text' => 'Error',
                'description' => 'Something went wrong while retrieving statuses.',
                'badgeClass' => 'badge badge-error',
            ]
        ];
    }
}
