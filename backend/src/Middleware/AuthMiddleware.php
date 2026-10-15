<?php

declare(strict_types=1);

namespace Ardena\Middleware;

use Ardena\Config\AppConfig;
use Ardena\Support\ApiResponse;
use Ardena\Support\HttpClient;
use Ardena\Support\Logger;

/**
 * AuthMiddleware
 *
 * Memvalidasi Firebase ID Token pada setiap request privat.
 * Mengekstrak data identitas resmi siswa (uid, email, name) dari token.
 */
class AuthMiddleware
{
    private static array $cachedPublicKeys = [];
    private static int $keysExpiry = 0;

    /**
     * Memvalidasi request masuk dan mengembalikan data pengguna terotentikasi
     *
     * @return array [ 'uid' => string, 'email' => string, 'name' => string ]
     */
    public static function authenticate(): array
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (empty($authHeader)) {
            // Periksa juga pada getallheaders jika tersedia
            if (function_exists('getallheaders')) {
                $headers = getallheaders();
                $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
            }
        }

        if (empty($authHeader) || !preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
            ApiResponse::error(
                'UNAUTHORIZED',
                'Kamu belum masuk atau sesi belajarmu sudah berakhir. Silakan masuk terlebih dahulu ya! 🔑',
                401
            );
        }

        $token = $matches[1];

        // Verifikasi token
        $user = self::verifyToken($token);

        if (!$user) {
            ApiResponse::error(
                'UNAUTHORIZED',
                'Kredensial atau token belajarmu tidak valid. Silakan masuk kembali! 🔑',
                401
            );
        }

        return $user;
    }

    /**
     * Verifikasi ID Token Firebase
     */
    public static function verifyToken(string $token): ?array
    {
        // Parsing JWT 3 bagian: header.payload.signature
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            // Dukungan mode simulasi pengujian lokal
            if (AppConfig::get('APP_ENV') === 'local') {
                if (str_starts_with($token, 'dev_siswa_')) {
                    $uid = substr($token, 10);
                    return [
                        'uid'   => $uid,
                        'email' => "{$uid}@ardena.test",
                        'name'  => "Siswa {$uid}",
                        'role'  => 'student',
                    ];
                }
            }
            return null;
        }

        [$headerB64, $payloadB64, $signatureB64] = $parts;

        $header = json_decode(self::base64UrlDecode($headerB64), true);
        $payload = json_decode(self::base64UrlDecode($payloadB64), true);

        if (!$header || !$payload) {
            return null;
        }

        // 1. Validasi Waktu Kedaluwarsa (exp)
        $now = time();
        if (isset($payload['exp']) && $payload['exp'] < $now) {
            Logger::warning("Firebase Token kedaluwarsa untuk sub: " . ($payload['sub'] ?? 'unknown'));
            return null;
        }

        // 2. Validasi Waktu Mulai (auth_time / iat)
        if (isset($payload['iat']) && $payload['iat'] > ($now + 300)) {
            return null;
        }

        // 3. Validasi Issuer & Audience jika PROJECT_ID diset
        $projectId = AppConfig::get('FIREBASE_PROJECT_ID');
        if (!empty($projectId) && $projectId !== 'ardena-ai-scratch3') {
            $expectedIssuer = "https://securetoken.google.com/$projectId";
            if (($payload['iss'] ?? '') !== $expectedIssuer || ($payload['aud'] ?? '') !== $projectId) {
                Logger::warning("Firebase Token issuer/audience tidak cocok", [
                    'expected' => $projectId,
                    'actual_aud' => $payload['aud'] ?? null
                ]);
                return null;
            }
        }

        // 4. Verifikasi Tanda Tangan RS256 Google Public Keys
        $kid = $header['kid'] ?? null;
        if ($kid) {
            $publicKey = self::getGooglePublicKey($kid);
            if ($publicKey) {
                $dataToVerify = "$headerB64.$payloadB64";
                $signature = self::base64UrlDecode($signatureB64);
                $verified = openssl_verify($dataToVerify, $signature, $publicKey, OPENSSL_ALGO_SHA256);

                if ($verified !== 1) {
                    Logger::warning("Verifikasi signature token Firebase gagal");
                    return null;
                }
            }
        }

        return [
            'uid'   => $payload['user_id'] ?? $payload['sub'] ?? 'guest',
            'email' => (string)($payload['email'] ?? ''),
            'name'  => $payload['name'] ?? $payload['display_name'] ?? 'Scratcher Cilik',
            'role'  => 'student',
        ];
    }

    /**
     * Mengambil Google Public Certificates untuk RS256
     */
    private static function getGooglePublicKey(string $kid): ?string
    {
        if (empty(self::$cachedPublicKeys) || time() > self::$keysExpiry) {
            $url = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';
            $res = HttpClient::get($url, [], 10);
            if ($res['success'] && is_array($res['body'])) {
                self::$cachedPublicKeys = $res['body'];
                self::$keysExpiry = time() + 3600; // cache 1 jam
            }
        }

        return self::$cachedPublicKeys[$kid] ?? null;
    }

    private static function base64UrlDecode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/')) ?: '';
    }
}
