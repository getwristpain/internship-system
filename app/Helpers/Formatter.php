<?php

namespace App\Helpers;

class Formatter
{
    public static function relativeNum(int $num): string
    {
        // Jika di atas 10.000, format menjadi relatif
        if ($num >= 1_000_000_000_000) {
            return number_format($num / 1_000_000_000_000, 1) . ' t'; // Triliun
        } elseif ($num >= 1_000_000_000) {
            return number_format($num / 1_000_000_000, 1) . ' m'; // Miliar
        } elseif ($num >= 1_000_000) {
            return number_format($num / 1_000_000, 1) . ' jt'; // Juta
        } elseif ($num >= 10_000) {
            return number_format($num / 1_000, 1) . ' rb'; // Ribu
        }
        return (string) $num; // Jumlah di bawah 10000
    }

    /**
     * Format phone number or fax to (xxx) xxxxxxx.
     *
     * @param string $number
     * @return string|null
     */
    public static function telp(string $number): ?string
    {
        // Remove non-digit characters
        $number = preg_replace('/\D/', '', $number);

        // Check if the number has at least 5 digits
        if (strlen($number) >= 5) {
            // Format number as (xxx) xxx...
            return '(' . substr($number, 0, 3) . ') ' . substr($number, 3);
        }

        // If the number is invalid (not 10 digits), return null or an empty string.
        return null;
    }
}
