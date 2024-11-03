<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'slug',
        'description',
    ];

    /**
     * Boot method for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Event saving to generate slug before saving the model
        static::saving(function (Status $status) {
            if (empty($status->slug)) {
                $formattedSlug = strtolower(str_replace(' ', '-', $status->type . '-' . $status->name));
                $status->slug = Str::slug($formattedSlug);
            }
        });
    }
}
