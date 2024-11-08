<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use App\Models\Notification;

class NotifyService
{
    /**
     * Kirim notifikasi selamat datang ke user yang baru dibuat.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public static function sendWelcomeNotification($user)
    {
        // Menentukan status default untuk notifikasi
        $status = self::getStatus('notify-status-read');

        // Membuat notifikasi baru
        Notification::create([
            'user_id' => $user->id,
            'status_id' => $status ? $status->id : null,
            'title' => 'Selamat Datang di Sistem Informasi Manajemen PKL!',
            'content' => 'Selamat bergabung! Anda kini terdaftar di Sistem Informasi Manajemen PKL. Sistem ini dirancang untuk mempermudah Anda dalam mengelola dan memonitor kegiatan Praktik Kerja Lapangan. Jika membutuhkan bantuan, kami siap membantu!',
            'action_required' => false,
            'action_label' => null,
            'action_url' => null,
            'scheduled_at' => Carbon::now(),
            'expired_at' => null,
        ]);
    }

    /**
     * Mendapatkan notifikasi pengguna berdasarkan ID pengguna.
     *
     * @param  string|null  $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getNotifications(?string $userId = null)
    {
        $user = User::with(['notifications'])->find($userId);
        $user->orderBy('updated_at', 'desc');

        return $user->notifications;
    }

    /**
     * Send a notification to a user with either a delivered or scheduled status.
     *
     * @param User  $user         The user to whom the notification is sent.
     * @param array $notification The notification data to be stored.
     * @param bool  $isScheduled  Indicates if the notification is scheduled.
     * @param \Carbon\Carbon|null $scheduledAt The scheduled time for the notification (required if $isScheduled is true).
     *
     * @return \Illuminate\Database\Eloquent\Model The created notification instance.
     *
     * @throws \InvalidArgumentException If $isScheduled is true but no $scheduledAt time is provided.
     */
    public static function sendNotification(User $user, array $notification, bool $isScheduled = false, \Carbon\Carbon $scheduledAt = null)
    {
        $statusKey = $isScheduled ? 'notify-status-scheduled' : 'notify-status-delivered';
        $statusId = self::getStatus($statusKey)->id;

        if ($isScheduled && is_null($scheduledAt)) {
            throw new \InvalidArgumentException('Scheduled notifications require a $scheduledAt time.');
        }

        $notificationData = array_merge($notification, [
            'status_id' => $statusId,
            'scheduled_at' => $isScheduled ? $scheduledAt : null,
        ]);

        return $user->notifications()->create($notificationData);
    }

    /**
     * Sets the status of a notification to "read".
     *
     * This method updates the status of a given notification by setting its
     * `status_id` to the ID of the "notify-status-read" status. If the status
     * or notification is not found, no changes will be made.
     *
     * @param  int  $notifyId  The ID of the notification to update.
     * @return void
     */
    public static function setReadNotification(int $notifyId)
    {
        $status = self::getStatus('notify-status-read');

        if (!$status) {
            return;
        }

        $notification = Notification::find($notifyId);

        if (!$notification || $notification->status->slug === 'notify-status-read' || $notification->status->slug === 'notify-status-expired') {
            return;
        }

        $notification->update([
            'status_id' => $status->id,
        ]);
    }

    /**
     * Memeriksa apakah ada notifikasi yang belum dibaca oleh pengguna.
     *
     * @param  string  $userId
     * @return bool
     */
    public static function hasUnreadNotifications(string $userId)
    {
        $deliveredStatus = self::getStatus('notify-status-delivered');

        $user = User::with(['notifications'])->find($userId);
        $undeliveredNotifications = $user->notifications()->where('status_id', $deliveredStatus ? $deliveredStatus->id : null)->count();

        return $undeliveredNotifications > 0;
    }

    private static function getStatus(string $slug)
    {
        return Status::where('slug', $slug)->first();
    }
}
