<?php

namespace App\Helpers;

use App\Models\AccessKey;
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
     * Verify if the provided access key matches any stored key,
     * and return the associated AccessKey instance if verification succeeds.
     *
     * @param string $key
     * @return AccessKey|null
     */
    public static function verifyKey(string $key): ?AccessKey
    {
        // Retrieve all access keys that are still valid
        $accessKeys = AccessKey::where('expires_at', '>=', now())->get();

        foreach ($accessKeys as $accessKey) {
            // Compare the plain key from the database with the provided key
            if (Hash::check($key, $accessKey->hashed_key)) {
                return $accessKey; // Return the matching access key
            }
        }

        return null; // Return null if no match is found
    }
}
