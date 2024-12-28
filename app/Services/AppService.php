<?php

namespace App\Services;

use App\Models\Setting;

class AppService
{
    public static function isNotInstalled(): bool
    {
        $setting = Setting::first();
        if ($setting->is_installed) {
            return true;
        }

        return false;
    }
}
