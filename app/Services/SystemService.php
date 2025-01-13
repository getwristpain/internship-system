<?php

namespace App\Services;

use App\Models\System;

class SystemService
{
    /**
     * Periksa apakah aplikasi sudah terinstal.
     *
     * @return bool
     */
    public static function isInstalled(): bool
    {
        $system = System::first();

        // 1. Periksa apakah data sistem ada dan sudah terinstal
        if ($system && $system->is_installed) {
            return true;
        }

        // 2. Kembalikan false jika aplikasi belum terinstal
        return false;
    }
}
