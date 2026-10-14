<?php

declare(strict_types=1);

namespace Ardena\Middleware;

use Ardena\Config\AppConfig;

/**
 * CorsMiddleware
 *
 * Mengatur header Cross-Origin Resource Sharing (CORS) dan respons pre-flight OPTIONS
 * untuk memfasilitasi komunikasi frontend prototype (folder Material).
 */
class CorsMiddleware
{
    public static function handle(): void
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        $allowedOriginsStr = (string)AppConfig::get('CORS_ALLOWED_ORIGINS', '*');
        $allowedOrigins = array_map('trim', explode(',', $allowedOriginsStr));

        if (in_array('*', $allowedOrigins, true) || in_array($origin, $allowedOrigins, true)) {
            header("Access-Control-Allow-Origin: $origin");
        } else {
            header("Access-Control-Allow-Origin: " . ($allowedOrigins[0] ?? '*'));
        }

        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');

        // Jika request adalah pre-flight OPTIONS, kembalikan respons 204 No Content seketika
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
