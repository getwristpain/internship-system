<?php

namespace App\Services;

use App\Helpers\Logger;
use App\Models\System;
use Illuminate\Support\Facades\Validator;

class SystemService
{
    /**
     * Retrieve system data or create a new instance if none exists.
     *
     * @return System
     */
    public static function firstData(): System
    {
        try {
            return System::firstOrNew();
        } catch (\Throwable $th) {
            Logger::handle('error', 'Failed to retrieve system data.', $th);
            throw $th;
        }
    }

    /**
     * Validate and update system data.
     *
     * @param array $systemData
     * @return bool
     */
    public static function setData(array $systemData): bool
    {
        if (!self::validateSystemData($systemData)) {
            return false;
        }

        try {
            $system = self::firstData();
            $system->fill($systemData)->save();
            return true;
        } catch (\Throwable $th) {
            Logger::handle('error', 'Failed to update system data.', $th);
            throw $th;
        }
    }

    /**
     * Mark system as installed or not installed.
     *
     * @param bool $is_installed
     * @return bool
     */
    public static function markAsInstalled(bool $is_installed = false): bool
    {
        try {
            $system = self::firstData();
            $system->is_installed = $is_installed;
            return $system->save();
        } catch (\Throwable $th) {
            Logger::handle('error', 'Failed while tagging installation.', $th);
            throw $th;
        }
    }

    /**
     * Check if the application is installed.
     *
     * @return bool
     */
    public static function isInstalled(): bool
    {
        return (bool) self::firstData()->is_installed;
    }

    /**
     * Validate system data against predefined rules.
     *
     * @param array $systemData
     * @return bool
     */
    protected static function validateSystemData(array $systemData): bool
    {
        $rules = [
            'app_name' => 'required|string|min:5|max:255',
            'app_logo' => 'nullable|string|min:5|max:255',
            'is_installed' => 'boolean',
        ];

        $validator = Validator::make($systemData, $rules);

        if ($validator->fails()) {
            Logger::handle('error', 'Validation failed for system data.', new \Exception(json_encode($validator->errors()->getMessages())));
            return false;
        }

        return true;
    }
}
