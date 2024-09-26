<?php

namespace App\Models;

use App\Utils\AccessKeyGen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessKey extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'hashed_key', 'user_id', 'expires_at'];
    protected $hidden = ['key', 'user_id']; // Kunci tetap tersembunyi

    /**
     * Generate and store a new access key.
     *
     * @param int $length
     * @param int|null $userId
     * @param int $expiryDays
     * @return AccessKey
     */
    public static function createNewKey(int $length = 32, ?string $userId = null, int $expiryDays = 7): self
    {
        do {
            // Generate a new access key
            $plainKey = AccessKeyGen::generate($length);
        } while (self::where('key', $plainKey)->exists());

        // Create and store the access key with the hashed version
        return self::create([
            'key' => AccessKeyGen::encryptKey($plainKey),
            'hashed_key' => AccessKeyGen::hashKey($plainKey),
            'user_id' => $userId,
            'expires_at' => now()->addDays($expiryDays),
        ]);
    }

    /**
     * Verify if the provided access key matches the stored hashed key.
     *
     * @param string $key
     * @return bool
     */
    public function verifyKey(string $key): bool
    {
        return AccessKeyGen::verifyKey($key, $this->hashed_key);
    }

    /**
     * Decrypt the stored access key.
     *
     * @return string
     */
    public function getDecryptedKey(): string
    {
        return AccessKeyGen::decryptKey($this->key);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
