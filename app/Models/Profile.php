<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravolt\Avatar\Avatar;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'id_number',
        'position',
        'class',
        'school_year',
        'address',
        'phone',
        'birth_place',
        'birth_date',
        'gender',
        'blood_type',
        'parent_name',
        'parent_address',
        'parent_phone',
    ];

    /**
     * Get the avatar URL with default handling.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                $avatar = new Avatar();

                return $value
                    ? $value
                    : $avatar->create($this->user->name)->toBase64();
            }
        );
    }

    /**
     * Summary of user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
