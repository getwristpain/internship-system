<?php

namespace App\Utils;

use Illuminate\Support\Facades\Route;

class Alert
{
    protected $user;
    protected string $userRole;
    protected array $userProfile;
    protected array $alerts = [];

    /**
     * Constructor to initialize user role, profile, and user instance.
     */
    public function __construct($user, string $userRole, array $userProfile)
    {
        $this->user = $user;
        $this->userRole = $userRole;
        $this->userProfile = $userProfile;

        // Check for alerts upon initialization
        $this->checkForAlerts();
    }

    /**
     * Check for user profile completion, role-specific alerts, and email verification.
     */
    protected function checkForAlerts(): void
    {
        $this->checkUserRole();
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
                    $this->addAlert('info', 'Profil Anda belum lengkap. Silakan perbarui informasi profil Anda.', 'profil', 'Periksa Profil');
                    return;
                }
            }
        }
    }

    /**
     * Add an alert if the user is logged in as a guest.
     */
    protected function checkUserRole(): void
    {
        if ($this->userRole === 'guest') {
            $this->addAlert('warning', 'Anda saat ini masuk sebagai tamu. Silakan daftar untuk mengakses lebih banyak fitur.', 'daftar', 'Daftar');
        }
    }

    /**
     * Helper method to add an alert message.
     */
    protected function addAlert(string $type, string $message, string $route = '', string $label = ''): void
    {
        $this->alerts[] = [
            'type' => $type,
            'message' => $message,
            'route' => Route::has($route) ? $route : '',
            'label' => $label,
        ];
    }

    /**
     * Get the alerts.
     *
     * @return array
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }
}
