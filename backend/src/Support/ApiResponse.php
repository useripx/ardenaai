<?php

declare(strict_types=1);

namespace Ardena\Support;

/**
 * ApiResponse
 *
 * Mengatur format keluaran JSON standar backend Ardena AI Scratch 3
 * sesuai dengan Docs/API_Contract.md.
 */
class ApiResponse
{
    /**
     * Mengirimkan respons sukses
     */
    public static function success(
        mixed $data = [],
        string $message = 'Operasi berhasil',
        int $statusCode = 200,
        array $meta = []
    ): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];

        if (!empty($meta)) {
            $response['meta'] = array_merge([
                'timestamp' => date('c'),
            ], $meta);
        }

        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Mengirimkan respons error
     */
    public static function error(
        string $code,
        string $message,
        int $statusCode = 400,
        mixed $details = null
    ): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');

        $response = [
            'success' => false,
            'error'   => [
                'code'    => $code,
                'message' => $message,
                'details' => $details,
            ],
            'meta'    => [
                'timestamp' => date('c'),
            ]
        ];

        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
