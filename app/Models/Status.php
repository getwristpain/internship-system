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
        'code',
        'type',
        'name',
        'description',
    ];

    /**
     * Boot method for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Event saving to generate code before saving the model
        static::saving(function (Status $status) {
            if (empty($status->code)) {
                $formattedSlug = strtolower(str_replace(' ', '-', $status->type . '-' . $status->name));
                $status->code = Str::slug($formattedSlug);
            }
        });
    }
}
