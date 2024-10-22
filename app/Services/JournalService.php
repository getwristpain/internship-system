<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Str;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * Get all journals for a user, optionally searchable.
     *
     * @param string $userId
     * @param string|null $search
     * @return Collection
     */
    public static function getAllJournals(string $userId = '', ?string $search = null): Collection
    {
        // Temukan pengguna dan pastikan pengguna ada
        $user = User::find($userId);
        if (!$user) {
            return collect(); // Mengembalikan koleksi kosong jika pengguna tidak ditemukan
        }

        // Membangun query untuk mengambil jurnal pengguna
        $query = $user->journals();

        // Terapkan filter pencarian jika ada
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('activity', 'like', "%{$search}%")
                    ->orWhere('attendance', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Urutkan jurnal berdasarkan tanggal
        $query->orderBy('date');

        // Ambil semua jurnal dan transformasi
        return $query->get()->transform(function ($journal) {
            $journal->attendance = self::getAttendanceStatus($journal->attendance);
            $journal->duration = self::calculateDuration($journal->time_start, $journal->time_finish);
            return $journal;
        });
    }

    /**
     * Get user journals with pagination and optional search.
     *
     * @param string $userId
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getPaginatedJournals(string $userId = '', ?string $search = null, int $perPage = 20)
    {
        // Temukan pengguna dan pastikan pengguna ada
        $user = User::find($userId);
        if (!$user) {
            return collect(); // Mengembalikan koleksi kosong jika pengguna tidak ditemukan
        }

        // Membangun query untuk mengambil jurnal pengguna
        $query = $user->journals();

        // Terapkan filter pencarian jika ada
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('activity', 'like', "%{$search}%")
                    ->orWhere('attendance', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Urutkan jurnal berdasarkan tanggal
        $query->orderBy('date');

        // Paginasikan jurnal
        $paginatedJournals = $query->paginate($perPage);

        // Transformasi koleksi jurnal yang dipaginasikan
        $paginatedJournals->getCollection()->transform(function ($journal) {
            $journal->attendance = self::getAttendanceStatus($journal->attendance);
            $journal->date = Carbon::parse($journal->date)->translatedFormat('d F Y');
            $journal->duration = self::calculateDuration($journal->time_start, $journal->time_finish);
            return $journal;
        });

        return $paginatedJournals;
    }

    /**
     * Get the attendance status by slug.
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
        return null; // Kembalikan null jika status tidak ada
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
            return 'N/A'; // Menangani kasus di mana waktu tidak tersedia
        }

        $start = Carbon::parse($startTime);
        $finish = Carbon::parse($finishTime);

        // Hitung selisih dalam jam dan menit
        $hours = $start->diffInHours($finish);
        $minutes = $start->diffInMinutes($finish) % 60;

        return sprintf('%d jam %d menit', $hours, $minutes);
    }

    /**
     * Get attendance statuses, excluding certain statuses.
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        try {
            $excludesStatus = [
                'attendance-status-excused',
                'attendance-status-vacation',
            ];

            $statuses = Status::where('type', 'attendance-status')
                ->whereNotIn('slug', $excludesStatus)
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
            report($e); // Log the error
            return self::defaultStatus(); // Mengembalikan status default
        } catch (\Exception $e) {
            report($e); // Log error lain
            return self::defaultErrorStatus(); // Mengembalikan status error
        }
    }

    /**
     * Default statuses to return when no statuses are found.
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
