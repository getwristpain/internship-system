<?php

namespace App\Models;

use Laravolt\Avatar\Avatar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'id_number',
        'position',
        'group',
        'school_year',
        'address',
        'phone',
        'gender',
        'parent_name',
        'parent_address',
        'parent_phone',
    ];

    protected $hidden = [
        'user_id',
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
     * User Profile with one-to-one Relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
