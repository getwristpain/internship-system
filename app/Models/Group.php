<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'level',
        'department_id',
    ];

    /**
     * Ensure the group's code is always uppercase.
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value)
        );
    }

    /**
     * Group and User with many-to-many Relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * School classes and Department with one-to-many Relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
