<?php

namespace App\Helpers;

class NumberFormatter
{
    public static function relative(int $num): string
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
}
