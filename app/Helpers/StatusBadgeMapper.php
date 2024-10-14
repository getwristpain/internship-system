<?php

namespace App\Helpers;

class StatusBadgeMapper
{
    public static function getStatusBadgeClass(string $statusName): string
    {
        return match ($statusName) {
            'verified' => 'badge badge-info',
            'active' => 'badge badge-success text-white',
            'pending' => 'badge badge-warning',
            'blocked' => 'badge badge-error',
            'suspended' => 'badge badge-warning',
            'deactivated' => 'badge badge-ghost',
            default => 'badge badge-outline badge-neutral',
        };
    }
}
