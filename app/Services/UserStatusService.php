<?php

namespace App\Services;

use App\Models\Status;
use Illuminate\Support\Str;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserStatusService
{
    public static function getStatuses(): array
    {
        try {
            $statuses = Status::where('type', 'user-status')->get();

            if ($statuses->isEmpty()) {
                throw new ModelNotFoundException('No statuses found.');
            }

            return $statuses
                ->map(
                    fn($status) => [
                        'value' => $status->slug,
                        'text' => Str::title($status->name),
                        'description' => $status->description ?? 'Deskripsi tidak tersedia',
                        'badgeClass' => StatusBadgeMapper::getStatusBadgeClass($status->name),
                    ]
                )
                ->toArray();
        } catch (ModelNotFoundException $e) {
            // Log the error or handle it accordingly
            report($e);
            return [
                [
                    'value' => 'unknown',
                    'text' => 'Unknown',
                    'description' => 'No status available',
                    'badgeClass' => 'badge badge-neutral',
                ]
            ];
        } catch (\Exception $e) {
            // Handle any other exceptions that might occur
            report($e);
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

    protected static function getBadgeClass(string $statusName): string
    {
        return match ($statusName) {
            'active' => 'badge badge-success',
            'pending' => 'badge badge-warning',
            'blocked' => 'badge badge-error',
            'suspended' => 'badge badge-warning',
            'deactivated' => 'badge badge-ghost',
            default => 'badge badge-outline badge-neutral',
        };
    }
}
