<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the "name" attribute and apply the "studly" method from the Str class.
     *
     * @param  mixed  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return $this->attributes['name'] = Str::studly($value);
    }

    /**
     * Get the "slug" attribute value.
     *
     * @param  mixed  $value
     * @return string
     */
    public function getSlugAttribute($value)
    {
        return $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Define a one-to-many relationship with the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
