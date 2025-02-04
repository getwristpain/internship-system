<?php

namespace App\Helpers;

use App\DynamicMethodCaller;
use App\Helpers\Logger;

abstract class Helper
{
    use DynamicMethodCaller;

    /**
     * Log a message with a specific level.
     */
    protected function logger(string $level, string $message, ?\Throwable $exception = null): void
    {
        Logger::handle($level, $message, $exception);
    }

    /**
     * Generate a unique key with timestamp and a random string, base64-encoded with a salt.
     */
    protected function key(string $salt): string
    {
        $timestamp = now()->timestamp;
        $randomStr = $this->str_random(8);
        $key = $timestamp . '-' . base64_encode($randomStr . '-' . $salt);

        return $key;
    }

    /**
     * Check if a string is empty or null.
     */
    protected function isEmpty(?string $value): bool
    {
        return empty($value) || trim($value) === '';
    }

    /**
     * Sanitize a string by removing dangerous characters.
     */
    protected function sanitize(string $text): string
    {
        return htmlspecialchars(strip_tags(trim($text)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Convert a JSON string to an array with error handling.
     */
    protected function jsonToArray(string $json): array
    {
        $data = json_decode($json, true);
        return json_last_error() === JSON_ERROR_NONE ? $data : [];
    }

    /**
     * Format a number with thousands separators.
     */
    protected function formatNumber(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Check if a value is numeric.
     */
    protected function isNumeric($value): bool
    {
        return is_numeric($value);
    }
}
