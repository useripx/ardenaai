<?php

declare(strict_types=1);

namespace Ardena\Config;

/**
 * AppConfig
 *
 * Mengelola pembacaan variabel lingkungan (.env) dan konfigurasi aplikasi.
 */
class AppConfig
{
    private static array $env = [];
    private static bool $loaded = false;

    /**
     * Memuat file .env ke dalam memori
     */
    public static function load(?string $envPath = null): void
    {
        if (self::$loaded) {
            return;
        }

        $envFile = $envPath ?? dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env';

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                // Lewati komentar
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }

                if (str_contains($line, '=')) {
                    [$key, $value] = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);

                    // Hapus tanda kutip jika ada
                    if (
                        (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                        (str_starts_with($value, "'") && str_ends_with($value, "'"))
                    ) {
                        $value = substr($value, 1, -1);
                    }

                    self::$env[$key] = $value;
                    if (!array_key_exists($key, $_ENV)) {
                        $_ENV[$key] = $value;
                    }
                }
            }
        }

        self::$loaded = true;
    }

    /**
     * Mengambil nilai konfigurasi
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();
        return self::$env[$key] ?? $_ENV[$key] ?? getenv($key) ?: $default;
    }

    /**
     * Mengambil boolean
     */
    public static function getBool(string $key, bool $default = false): bool
    {
        $val = self::get($key, $default);
        if (is_bool($val)) {
            return $val;
        }
        return in_array(strtolower((string)$val), ['true', '1', 'yes', 'on'], true);
    }

    /**
     * Mengambil integer
     */
    public static function getInt(string $key, int $default = 0): int
    {
        $val = self::get($key, $default);
        return (int)$val;
    }
}
