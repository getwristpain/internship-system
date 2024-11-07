<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipReport extends Model
{
    protected $fillable = [
        'user_id',
        'status_id',
        'remarks',
        'file_name',
        'file_path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->status_id)) {
                $status = Status::where('slug', 'acceptance-status-pending')->first();
                $model->status_id = $status ? $status->id : null;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
