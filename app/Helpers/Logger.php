<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

/**
 * Helper class for logging messages with various statuses.
 */
class Logger
{
    /**
     * Logs a message with a specific status and optional throwable details.
     *
     * @param string $level The log level ['emergency'|'alert'|'critical'|'error'|'warning'|'notice'|'info'|'debug'].
     * @param string $message The message to log.
     * @param \Throwable|null $exception Optional exception for detailed error logging.
     * @return string
     */
    public static function handle(string $level, string $message, ?\Throwable $exception = null): string
    {
        $level = self::sanitizeLevel($level);
        $logMessage = self::formatLogMessage($level, $message);
        $logContext = $exception ? self::buildExceptionContext($exception) : [];

        Log::log($level, $logMessage, $logContext);

        return __($message);
    }

    /**
     * Ensures the level is valid and defaults to "info" if not.
     *
     * @param string $level
     * @return string
     */
    private static function sanitizeLevel(string $level): string
    {
        $validLevel = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];
        return in_array(strtolower($level), $validLevel) ? strtolower($level) : 'info';
    }

    /**
     * Formats the log message with the given status and message.
     *
     * @param string $level
     * @param string $message
     * @return string
     */
    private static function formatLogMessage(string $level, string $message): string
    {
        return sprintf("[%s] %s", strtoupper($level), __($message, locale: 'en'));
    }

    /**
     * Builds a context array from the given exception.
     *
     * @param \Throwable $exception
     * @return array
     */
    private static function buildExceptionContext(\Throwable $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'stack' => $exception->getTraceAsString(),
        ];
    }
}
