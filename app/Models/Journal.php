<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'date',
        'time_start',
        'time_finish',
        'activity',
        'attendance',
        'attachment',
        'remarks',
    ];

    /**
     * Get the attendance that owns the journal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
