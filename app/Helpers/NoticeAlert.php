<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class NoticeAlert
{
    protected ?User $user = null;
    protected string $userRole = '';
    protected string $userStatus = '';
    protected array $userProfile = [];
    protected array $alerts = [];

    /**
     * Constructor to initialize user role, profile, and user instance.
     */
    public function __construct(?User $user = null)
    {
        $this->user = $user;
        $this->userProfile = $user->profile ? $user->profile->toArray() : [];
        $this->userRole = $user->roles->first()->name ?? 'guest';
        $this->userStatus = $user->status->slug ?? 'user-status-pending';

        // Check for alerts upon initialization
        $this->checkForAlerts();
    }

    /**
     * Check for user profile completion, role-specific alerts, and email verification.
     */
    protected function checkForAlerts(): void
    {
        $this->checkUserProfileCompletion();
    }

    /**
     * Check if the user profile is incomplete and add an alert if needed.
     */
    protected function checkUserProfileCompletion(): void
    {
        $baseRequiredFields = ['address', 'phone', 'gender'];

        if (in_array($this->userRole, ['student', 'teacher'])) {
            foreach ($baseRequiredFields as $field) {
                if (empty($this->userProfile[$field] ?? null)) {
                    $this->addAlert('info', 'Profil Anda belum lengkap. Silakan perbarui informasi profil Anda.', 'Periksa Profil', 'profile');
                    return;
                }
            }
        }
    }

    /**
     * Helper method to add an alert message.
     */
    protected function addAlert(string $type = 'info', string $message = '', string $label = '', string $action = ''): void
    {

        $this->alerts[] = [
            'type' => $type,
            'message' => $message,
            'label' => $label,
            'action' => Route::has($action) ? route($action) : '',
        ];
    }

    /**
     * Remove duplicate alerts.
     *
     * @param array $alerts
     * @return array
     */
    public static function removeDuplicateAlerts(array $alerts): array
    {
        // Use array_unique with SORT_REGULAR to remove duplicate alerts
        return array_values(array_unique($alerts, SORT_REGULAR));
    }

    /**
     * Get the alerts and remove duplicates.
     *
     * @return array
     */
    public function getAlerts(): array
    {
        // Return unique alerts only
        return self::removeDuplicateAlerts($this->alerts);
    }
}
