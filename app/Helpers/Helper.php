<?php

namespace App\Helpers;

use App\Helpers\Logger;

abstract class Helper
{
    /**
     * Log message dengan level tertentu.
     */
    protected static function log(string $level, string $message, ?\Throwable $exception = null): void
    {
        Logger::handle($level, $message, $exception);
    }

    /**
     * Cek apakah string kosong atau null.
     */
    protected static function isEmpty(?string $value): bool
    {
        return trim($value) === '' || $value === null;
    }

    /**
     * Bersihkan string dari karakter berbahaya.
     */
    protected static function sanitize(string $text): string
    {
        return htmlspecialchars(strip_tags(trim($text)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Konversi JSON ke array dengan error handling.
     */
    protected static function jsonToArray(string $json): array
    {
        $data = json_decode($json, true);
        return json_last_error() === JSON_ERROR_NONE ? $data : [];
    }

    /**
     * Format angka dengan pemisah ribuan.
     */
    protected static function formatNumber(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Cek apakah nilai adalah angka.
     */
    protected static function isNumeric($value): bool
    {
        return is_numeric($value);
    }
}
