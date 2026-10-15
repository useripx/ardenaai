<?php

declare(strict_types=1);

namespace Ardena\Controllers;

use Ardena\Core\View;
use Ardena\Middleware\AuthMiddleware;
use Ardena\Support\ApiResponse;

/**
 * BaseController — Induk Controller Ardena AI Scratch 3
 * 
 * Menyediakan utilitas umum untuk response JSON, perenderan View,
 * pembacaan payload request, dan verifikasi autentikasi siswa.
 */
abstract class BaseController
{
    /**
     * Mengembalikan respons JSON sukses seragam.
     */
    protected function json(array $data, string $message = 'Sukses', int $statusCode = 200, array $meta = []): void
    {
        ApiResponse::success($data, $message, $statusCode, $meta);
    }

    /**
     * Mengembalikan respons JSON error seragam.
     */
    protected function error(string $code, string $message, int $statusCode = 400, mixed $details = null): void
    {
        ApiResponse::error($code, $message, $statusCode, $details);
    }

    /**
     * Merender template View dinamis.
     */
    protected function view(string $viewName, array $data = []): void
    {
        View::render($viewName, $data);
    }

    /**
     * Mengambil dan mem-parsing body JSON dari request php://input.
     */
    protected function getJsonPayload(): array
    {
        $raw = file_get_contents('php://input');
        if (empty($raw)) {
            return [];
        }
        $decoded = json_decode((string)$raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Melakukan autentikasi Bearer Token siswa. Jika gagal, otomatis menghentikan request dengan 401.
     */
    protected function getAuthUser(): array
    {
        return AuthMiddleware::authenticate();
    }

    /**
     * Mengambil parameter query string $_GET dengan nilai default.
     */
    protected function getQuery(string $key, ?string $default = null): ?string
    {
        return isset($_GET[$key]) ? trim((string)$_GET[$key]) : $default;
    }
}
