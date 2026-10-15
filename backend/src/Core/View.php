<?php

declare(strict_types=1);

namespace Ardena\Core;

/**
 * View Engine — Ardena AI Scratch 3
 * 
 * Engine perender tampilan dinamis untuk menyajikan antarmuka ke browser siswa.
 */
class View
{
    private static string $viewsPath = '';

    public static function setViewsPath(string $path): void
    {
        self::$viewsPath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
    }

    public static function getViewsPath(): string
    {
        if (empty(self::$viewsPath)) {
            self::$viewsPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
        }
        return self::$viewsPath;
    }

    /**
     * Render berkas view PHP/HTML dengan data konteks.
     */
    public static function render(string $view, array $data = []): void
    {
        $base = self::getViewsPath();
        $file = $base . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($file)) {
            // Coba ekstensi .html jika .php tidak ditemukan
            $htmlFile = $base . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.html';
            if (file_exists($htmlFile)) {
                $file = $htmlFile;
            } else {
                http_response_code(500);
                echo "View [{$view}] tidak ditemukan di {$base}";
                return;
            }
        }

        // Ekstrak variabel agar dapat langsung diakses di dalam view
        extract($data, EXTR_SKIP);

        require $file;
    }
}
