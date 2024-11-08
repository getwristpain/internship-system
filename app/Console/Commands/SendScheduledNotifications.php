<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Status;
use App\Models\Notification;
use Illuminate\Console\Command;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notifications at their scheduled time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $status = Status::where('slug', 'notify-status-scheduled')->first();

        if (!$status) {
            $this->error('Scheduled status not found. Please check the status slug.');
            return Command::FAILURE;
        }

        $now = Carbon::now();
        $notifications = Notification::where('status_id', $status->id)
            ->where('scheduled_at', '<=', $now)
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('No notifications are scheduled to be sent at this time.');
            return Command::SUCCESS;
        }

        foreach ($notifications as $notification) {
            $notification->sendScheduledNotification();
        }

        $this->info('Scheduled notifications have been sent.');
        return Command::SUCCESS;
    }
}
