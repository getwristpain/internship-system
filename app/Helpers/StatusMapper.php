<?php

namespace App\Helpers;

class StatusMapper
{
    public static function getStatusClass(string $name = '')
    {
        return match ($name) {
            'pending' => 'text-yellow-500',
            'running' => 'text-blue-500',
            'stopped' => 'text-red-500',
            'finished' => 'text-green-500',
            'not started', 'archived' => 'text-gray-500',
        };
    }
}
