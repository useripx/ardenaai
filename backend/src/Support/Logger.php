<?php

declare(strict_types=1);

namespace Ardena\Support;

/**
 * Logger
 *
 * Pencatatan log aman (Safe Logging) tanpa membocorkan API key, token,
 * atau data percakapan sensitif anak.
 */
class Logger
{
    private static string $logDir = '';

    public static function init(?string $dir = null): void
    {
        self::$logDir = $dir ?? dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    private static function log(string $level, string $message, array $context = []): void
    {
        if (empty(self::$logDir)) {
            self::init();
        }

        // Sanitasi konteks agar tidak mencatat API key atau token rahasia
        $sanitizedContext = self::sanitize($context);
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($sanitizedContext) ? ' | ' . json_encode($sanitizedContext, JSON_UNESCAPED_SLASHES) : '';

        $entry = "[$timestamp] [$level] $message$contextStr" . PHP_EOL;
        $file = self::$logDir . DIRECTORY_SEPARATOR . 'app-' . date('Y-m-d') . '.log';

        @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Sensor kata sandi, token, dan API key secara otomatis
     */
    private static function sanitize(array $data): array
    {
        $sensitiveKeys = ['key', 'api_key', 'token', 'authorization', 'password', 'secret', 'groq_api_key'];

        foreach ($data as $key => $value) {
            $lowerKey = strtolower((string)$key);
            foreach ($sensitiveKeys as $sensitive) {
                if (str_contains($lowerKey, $sensitive)) {
                    $data[$key] = '***REDACTED***';
                    continue 2;
                }
            }

            if (is_array($value)) {
                $data[$key] = self::sanitize($value);
            }
        }

        return $data;
    }
}
