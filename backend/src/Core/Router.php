<?php

declare(strict_types=1);

namespace Ardena\Core;

use Ardena\Support\ApiResponse;

/**
 * HTTP Router & Request Dispatcher — Ardena AI Scratch 3
 * 
 * Mengatur pemetaan URI dan method HTTP ke Controller Action secara modular.
 */
class Router
{
    private static array $routes = [];

    public static function get(string $path, array|callable $handler): void
    {
        self::addRoute('GET', $path, $handler);
    }

    public static function post(string $path, array|callable $handler): void
    {
        self::addRoute('POST', $path, $handler);
    }

    public static function delete(string $path, array|callable $handler): void
    {
        self::addRoute('DELETE', $path, $handler);
    }

    public static function options(string $path, array|callable $handler): void
    {
        self::addRoute('OPTIONS', $path, $handler);
    }

    public static function any(string $path, array|callable $handler): void
    {
        foreach (['GET', 'POST', 'DELETE', 'OPTIONS', 'PUT', 'PATCH'] as $method) {
            self::addRoute($method, $path, $handler);
        }
    }

    private static function addRoute(string $method, string $path, array|callable $handler): void
    {
        $normalized = '/' . trim($path, '/');
        self::$routes[] = [
            'method'  => strtoupper($method),
            'path'    => $normalized,
            'handler' => $handler
        ];
    }

    /**
     * Mengambil daftar rute terdaftar (untuk inspeksi/pengujian).
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Reset rute (untuk pengujian unit).
     */
    public static function clear(): void
    {
        self::$routes = [];
    }

    /**
     * Normalisasi URI request agar bekerja mulus di Laragon root, subfolder, atau virtual host.
     */
    public static function normalizeUri(?string $uri = null): string
    {
        $rawUri = $uri ?? ($_SERVER['REQUEST_URI'] ?? '/');
        $path = parse_url($rawUri, PHP_URL_PATH) ?? '/';

        // Hapus base prefix umum jika diakses lewat Laragon subfolder
        $prefixesToRemove = [
            '/ardenaai/backend/public',
            '/backend/public',
            '/ardenaai',
            '/index.php'
        ];

        foreach ($prefixesToRemove as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
                break;
            }
        }

        $path = '/' . trim($path, '/');
        return $path === '' ? '/' : $path;
    }

    /**
     * Dispatch request aktif ke Controller yang sesuai.
     */
    public static function dispatch(?string $method = null, ?string $uri = null): void
    {
        $httpMethod = strtoupper($method ?? ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $path = self::normalizeUri($uri);

        // Preflight OPTIONS CORS langsung sukses
        if ($httpMethod === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        foreach (self::$routes as $route) {
            if ($route['method'] !== $httpMethod && $route['method'] !== 'ANY') {
                continue;
            }

            // Ubah pattern route menjadi regex untuk parameter dinamis {param}
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                self::invokeHandler($route['handler'], $params);
                return;
            }
        }

        // Tidak ditemukan rute yang cocok (404)
        if (str_starts_with($path, '/api/')) {
            ApiResponse::error('NOT_FOUND', "Endpoint API '{$path}' tidak ditemukan.", 404);
        } else {
            http_response_code(404);
            echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><title>404 - Halaman Tidak Ditemukan</title></head>";
            echo "<body style='font-family:sans-serif;text-align:center;padding:50px;'>";
            echo "<h1 style='color:#005DB2;'>404 — Sahabat Ardena Tersesat</h1>";
            echo "<p>Halaman <code>" . htmlspecialchars($path, ENT_QUOTES) . "</code> belum ada nih.</p>";
            echo "<a href='/' style='display:inline-block;padding:10px 20px;background:#005DB2;color:#fff;border-radius:10px;text-decoration:none;'>Kembali ke Beranda Ardena</a>";
            echo "</body></html>";
        }
    }

    private static function invokeHandler(array|callable $handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            $controller = new $class();
            call_user_func_array([$controller, $method], $params);
            return;
        }

        throw new \InvalidArgumentException("Format route handler tidak valid.");
    }
}
