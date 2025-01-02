<?php

namespace App\Services;

use App\Models\System;

class SystemService
{
    /**
     * Check if the application is installed.
     *
     * @return bool
     */
    public static function isInstalled(): bool
    {
        $system = System::first();

        if ($system && $system->is_installed) {
            return true;
        }

        // Redirect to the installation route if not installed
        return false;
    }
}
