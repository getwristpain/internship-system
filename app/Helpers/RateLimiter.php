<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter as RL;

/**
 * Helper class for managing rate limiting to prevent brute-force attacks.
 */
class RateLimiter
{
    /**
     * Ensures the action is not rate-limited and handles throttling logic.
     *
     * @param string $throttleKey The key used to identify the throttle instance.
     * @param int $maxAttempts The maximum number of attempts allowed.
     * @param string $action A description of the action being throttled.
     * @return void
     */
    public static function ensureNotRateLimited(string $throttleKey, int $maxAttempts, string $action): void
    {
        try {
            if (RL::tooManyAttempts(self::getThrottleKey($throttleKey), $maxAttempts)) {
                $minutes = ceil(RL::availableIn(self::getThrottleKey($throttleKey)) / 60);
                Session::flash('message.warning', __('action.throttle', ['action' => $action, 'minutes' => $minutes]));
            }

            RL::hit(self::getThrottleKey($throttleKey), 60);
        } catch (\Throwable $th) {
            Exception::handle('An error occurred while attempting to rate limit the action: "' . $action . '".', $th);
        }
    }

    /**
     * Retrieves the throttle key for the current operation.
     *
     * @param string $throttleKey The base throttle key.
     * @return string
     */
    protected static function getThrottleKey(string $throttleKey): string
    {
        if (!$throttleKey) {
            Exception::handle('Throttle key not set. Please ensure the throttle key is properly initialized before attempting to use it.');
            return '';
        }

        return $throttleKey . request()->ip();
    }
}
