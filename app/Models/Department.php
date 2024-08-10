<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Set the department's name and code.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $this->attributes['code'] = strtoupper(implode('', array_map(function ($word) {
                    return $word[0];
                }, explode(' ', $value))));
                return $value;
            }
        );
    }

    /**
     * Deparment and user with many-to-many relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class);
    }

    /**
     * Deparment and Group with one-to-many relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
