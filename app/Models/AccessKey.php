<?php

namespace App\Models;

use App\Helpers\AccessKeyGen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessKey extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'hashed_key', 'user_id', 'expires_at'];
    protected $hidden = ['key', 'user_id'];

    /**
     * Generate and store a new access key.
     *
     * @param int $length
     * @param int|null $userId
     * @param int $expiryDays
     * @return self
     */
    public static function createNewKey(int $length = 32, ?string $userId = null, int $expiryDays = 7): self
    {
        do {
            // Generate a new plain access key
            $plainKey = AccessKeyGen::generate($length);
        } while (self::where('hashed_key', AccessKeyGen::hashKey($plainKey))->exists());

        // Validate userId if provided
        if ($userId && !User::find($userId)) {
            throw new \InvalidArgumentException('Invalid user ID provided.');
        }

        // Create and store the access key with the encrypted and hashed version
        return self::create([
            'key' => AccessKeyGen::encryptKey($plainKey),
            'hashed_key' => AccessKeyGen::hashKey($plainKey),
            'user_id' => $userId,
            'expires_at' => now()->addDays($expiryDays),
        ]);
    }

    /**
     * Decrypt the stored access key.
     *
     * @return string
     */
    public function getDecryptedKey(): string
    {
        // Decrypt and return the plain access key
        return AccessKeyGen::decryptKey($this->key);
    }

    /**
     * Define the relationship to the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
