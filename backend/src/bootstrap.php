<?php

declare(strict_types=1);

/**
 * Bootstrap File — Ardena AI Scratch 3 Backend
 *
 * Mengatur PSR-4 Autoloading mandiri, inisialisasi konfigurasi .env,
 * dan penanganan CORS untuk semua endpoint API.
 */

// 1. PSR-4 Autoloader untuk namespace Ardena\
spl_autoload_register(function (string $class): void {
    $prefix = 'Ardena\\';
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use Ardena\Config\AppConfig;
use Ardena\Support\Logger;
use Ardena\Middleware\CorsMiddleware;

// 2. Muat variabel lingkungan
AppConfig::load();

// 3. Inisialisasi Logger
Logger::init();

// 4. Tangani CORS Headers
CorsMiddleware::handle();
