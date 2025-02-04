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
     * @param string $locale (e.g. 'en', 'eu', 'id').
     * @param \Throwable|null $exception Optional exception for detailed error logging.
     */
    public static function handle(string $level, string $message, string $context = '', string $locale = '', ?\Throwable $exception = null): string
    {
        $level = self::sanitizeLevel($level);
        $logMessage = self::formatLogMessage($level, $message, $context, $locale);
        $logContext = $exception ? self::buildExceptionContext($exception) : [];

        Log::log($level, $logMessage, $logContext);

        return self::getMessage($message, $context, $locale) ?? '';
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
    private static function formatLogMessage(string $level, string $message, string $context = '', string $locale = ''): string
    {
        return sprintf("[%s] %s", strtoupper($level), __('system.' . $message, ['context' => self::translate($context, 'system.context', locale: $locale ?: 'en')], locale: $locale ?: 'en'));
    }

    /**
     * Get the translated log message.
     *
     * @param string $message
     * @param string $context
     * @param string $locale
     *
     * @return string
     */
    private static function getMessage(string $message, string $context = '', string $locale = ''): string
    {
        $translatedContext = self::translate($context, 'system.context', locale: $locale);
        return self::translate($message, 'system', ['context' => $translatedContext], $locale);
    }

    /**
     * Text.
     *
     * @param string $text
     * @param string $resource
     * @param string $locale
     *
     * @return string
     */
    private static function translate(string $text, string $resource, array $replace = [], string $locale = ''): string
    {
        $resourceText = $resource . '.' . $text;
        $translatedText = __($resourceText, replace: $replace, locale: $locale ?: app()->getLocale());

        if ($resourceText != $translatedText) {
            return $translatedText;
        }

        return __($text, replace: $replace, locale: $locale ?: app()->getLocale());
    }

    /**
     * Builds a context array from the given exception.
     *
     * @param string $locale (e.g. 'en', 'eu', 'id').
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
