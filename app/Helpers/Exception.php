<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

/**
 * Helper class for handling exceptions and logging error messages.
 */
class Exception
{
    /**
     * Logs an error message with optional throwable details.
     *
     * @param string $message The error message to log.
     * @param \Throwable|null $th Optional throwable instance for detailed error logging.
     * @return string
     */
    public static function handle(string $message, \Throwable $th = null): string
    {
        // Log error with exception details if throwable is provided
        if ($th) {
            Log::error(__($message), [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'stack' => $th->getTraceAsString(),
            ]);
        } else {
            // Log the error message without throwable details
            Log::error(__($message));
        }

        // Return the localized error message
        return __($message);
    }
}
