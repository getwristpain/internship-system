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
        'slug',
        'name',
    ];

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
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
