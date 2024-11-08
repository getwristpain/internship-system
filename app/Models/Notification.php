<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'status_id',
        'title',
        'content',
        'action_required',
        'action_label',
        'action_url',
        'scheduled_at',
        'expired_at',
    ];

    public function sendScheduledNotification()
    {
        if ($this->status === 'scheduled' && $this->scheduled_at <= Carbon::now()) {
            $status = Status::where('slug', 'notify-status-delivered')->first();
            $this->update(['status_id' => $status->id]);
        }
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
