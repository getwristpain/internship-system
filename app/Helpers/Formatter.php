<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Formatter
{
    private const UNITS = [
        1_000_000_000_000 => 't',  // Triliun
        1_000_000_000 => 'm',     // Miliar
        1_000_000 => 'jt',        // Juta
        1_000 => 'rb',            // Ribu
    ];

    /**
     * Generate a reactive unique id.
     *
     * @param string $value
     * @return string
     */
    public static function uniqid(string $value): string
    {
        return now()->timestamp . '-' . Str::random(8) . "-$value";
    }

    /**
     * Abbreviate a string by taking the first character of each word
     * that starts with an uppercase letter. Ensures a minimum length of 3 characters
     * if the input contains fewer than 3 words.
     *
     * @param string $value
     * @return string
     */
    public static function abbrev(string $value): string
    {
        $words = explode(' ', trim($value));
        $abbrev = collect($words)
            ->filter(fn($word) => ctype_upper($word[0] ?? ''))
            ->map(fn($word) => $word[0])
            ->implode('');

        if (count($words) >= 3) {
            return strtoupper($abbrev);
        }

        $firstWord = preg_replace('/\s+/', '', $value);
        while (strlen($abbrev) < 4 && strlen($abbrev) < strlen($firstWord)) {
            $abbrev .= $firstWord[strlen($abbrev)] ?? '';
        }

        return strtoupper($abbrev);
    }


    /**
     * Format a number into a human-readable relative format.
     * Converts large numbers into units like "rb", "jt", "m", or "t".
     *
     * @param int|null $num
     * @return string
     */
    public static function formatNum(?int $num): string
    {
        if ($num === null) return '0';

        foreach (self::UNITS as $value => $suffix) {
            if ($num >= $value) {
                return sprintf('%s %s', number_format($num / $value, 1), $suffix);
            }
        }

        return (string)$num;
    }

    /**
     * Format a phone number into the format (XXX) XXX-XXXX.
     *
     * @param string|null $number
     * @return string|null
     */
    public static function formatPhone(?string $number): ?string
    {
        if (empty($number) || strlen($cleanedNumber = preg_replace('/\D/', '', $number)) < 5) {
            return null;
        }

        return sprintf('(%s) %s', substr($cleanedNumber, 0, 3), substr($cleanedNumber, 3));
    }

    /**
     * Generate a code from multiple segments and convert it to uppercase with hyphens.
     *
     * @param mixed ...$segments
     * @return string
     */
    public static function genCode(...$segments): string
    {
        return strtoupper(Str::slug(implode('-', array_map('strval', $segments)), '-'));
    }
}
