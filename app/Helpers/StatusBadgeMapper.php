<?php

namespace App\Helpers;

class StatusBadgeMapper
{
    public static function getStatusBadgeClass(string $statusName): string
    {
        return match ($statusName) {
            'verified' => 'badge badge-info',
            'active', 'present', 'excused' => 'badge badge-success text-white',
            'pending', 'suspended', 'late', 'sick' => 'badge badge-warning',
            'blocked', 'absent' => 'badge badge-error text-white',
            'leave', 'holiday', 'vacation' => 'badge badge-info',
            'deactivated', => 'badge badge-ghost',
            default => 'badge badge-outline badge-neutral',
        };
    }
}
