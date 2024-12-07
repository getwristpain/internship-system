<?php

namespace App\Models;

use App\Services\EventStatusService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Program extends Model
{
    protected $fillable = [
        'title',
        'year',
        'date_start',
        'date_finish',
        'status_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($program) {
            if (empty($program->status_id)) {
                $status = EventStatusService::getStatus('pending');
                $program->status_id = $status ? $status->id : null;
            }
        });
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function internships(): HasMany
    {
        return $this->hasMany(internship::class);
    }
}
