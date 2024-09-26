<?php

namespace App\Utils;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AccessKeyGen
{
    /**
     * Generate a random access key with a specified length.
     *
     * @param int $length
     * @return string
     */
    public static function generate(int $length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Hash an access key using Laravel's Hash facade.
     *
     * @param string $key
     * @return string
     */
    public static function hashKey(string $key): string
    {
        return Hash::make($key);
    }

    /**
     * Encrypt an access key for secure storage.
     *
     * @param string $key
     * @return string
     */
    public static function encryptKey(string $key): string
    {
        return Crypt::encryptString($key);
    }

    /**
     * Decrypt an access key.
     *
     * @param string $encryptedKey
     * @return string
     */
    public static function decryptKey(string $encryptedKey): string
    {
        return Crypt::decryptString($encryptedKey);
    }

    /**
     * Verify if the given access key matches the hashed key.
     *
     * @param string $key
     * @param string $hashedKey
     * @return bool
     */
    public static function verifyKey(string $key, string $hashedKey): bool
    {
        return Hash::check($key, $hashedKey);
    }
}
