<?php

declare(strict_types=1);

namespace Ardena\Support;

/**
 * HttpClient
 *
 * Wrapper cURL terstandarisasi untuk komunikasi aman dengan API eksternal
 * (Groq Cloud API & Firebase Firestore REST).
 */
class HttpClient
{
    /**
     * Eksekusi HTTP Request
     */
    public static function request(
        string $method,
        string $url,
        array $headers = [],
        mixed $body = null,
        int $timeout = 30,
        bool $isMultipart = false
    ): array {
        $ch = curl_init();

        $formattedHeaders = [];
        foreach ($headers as $k => $v) {
            $formattedHeaders[] = is_numeric($k) ? $v : "$k: $v";
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        if ($body !== null) {
            if ($isMultipart) {
                // Untuk multipart form data, teruskan array langsung ke CURLOPT_POSTFIELDS.
                // cURL PHP akan otomatis menyusun Content-Type: multipart/form-data beserta boundary yang valid.
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            } elseif (is_array($body) && !isset($headers['Content-Type']) && !in_array('Content-Type: multipart/form-data', $formattedHeaders, true)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
        }

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $curlErrno = curl_errno($ch);

        curl_close($ch);

        if ($curlErrno !== 0) {
            Logger::error("HTTP Client cURL Error: [$curlErrno] $curlError", ['url' => $url]);
            return [
                'status'  => 0,
                'body'    => null,
                'raw'     => $curlError,
                'error'   => $curlError,
                'success' => false,
            ];
        }

        $decoded = json_decode((string)$responseBody, true);

        return [
            'status'  => $httpCode,
            'body'    => $decoded ?? $responseBody,
            'raw'     => $responseBody,
            'success' => $httpCode >= 200 && $httpCode < 300,
        ];
    }

    public static function get(string $url, array $headers = [], int $timeout = 30): array
    {
        return self::request('GET', $url, $headers, null, $timeout);
    }

    public static function postJson(string $url, array $data, array $headers = [], int $timeout = 30): array
    {
        $headers['Content-Type'] = 'application/json';
        return self::request('POST', $url, $headers, json_encode($data), $timeout);
    }

    public static function postMultipart(string $url, array $postFields, array $headers = [], int $timeout = 60): array
    {
        // Hapus Content-Type jika sempat di-set manual agar boundary otomatis cURL tidak tertimpa
        unset($headers['Content-Type']);
        return self::request('POST', $url, $headers, $postFields, $timeout, true);
    }
}
