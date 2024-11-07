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
        $status = Status::where('slug', 'notify-status-read')->first();

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
        $status = Status::where('slug', 'notify-status-read')->first();

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
        $deliveredStatus = Status::where('slug', 'notify-status-delivered')->first();

        $user = User::with(['notifications'])->find($userId);
        $undeliveredNotifications = $user->notifications()->where('status_id', $deliveredStatus ? $deliveredStatus->id : null)->count();

        return $undeliveredNotifications > 0;
    }
}
