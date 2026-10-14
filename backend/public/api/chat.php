<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ardena\Middleware\AuthMiddleware;
use Ardena\Services\GroqService;
use Ardena\Support\ApiResponse;

// Hanya menerima metode POST
if (($_SERVER['REQUEST_METHOD'] ?? 'POST') !== 'POST') {
    ApiResponse::error('METHOD_NOT_ALLOWED', 'Endpoint ini hanya menerima permintaan POST', 405);
}

// 1. Verifikasi Autentikasi Pengguna
$user = AuthMiddleware::authenticate();

// 2. Baca payload request
$rawInput = file_get_contents('php://input');
$body = json_decode((string)$rawInput, true);

if (!is_array($body) || empty(trim((string)($body['message'] ?? '')))) {
    ApiResponse::error(
        'INVALID_INPUT',
        'Oops! Pertanyaan belajarmu belum ditulis nih. Tuliskan apa yang ingin kamu tanyakan pada Ardena ya! 📝',
        400
    );
}

$userMessage = trim((string)$body['message']);
$history = is_array($body['history'] ?? null) ? $body['history'] : [];
$conversationId = (string)($body['conversation_id'] ?? ('conv_' . time() . '_' . substr(md5(uniqid()), 0, 6)));

// 3. Eksekusi Groq Service dengan Auto-Fallback 4 Tingkat
$groqService = new GroqService();
$result = $groqService->chat($userMessage, $history);

if (!$result['success']) {
    ApiResponse::error(
        'AI_SERVICE_UNAVAILABLE',
        $result['error'] ?? 'Layanan AI Ardena sedang sibuk saat ini. Coba klik kirim lagi ya! 🤖',
        503,
        $result['errors'] ?? null
    );
}

// 4. Kembalikan respons terstruktur sukses
ApiResponse::success(
    array_merge($result['data'], [
        'conversation_id' => $conversationId,
        'user_id'         => $user['uid'],
    ]),
    'Ardena berhasil menjawab pertanyaanmu! 🚀',
    200,
    [
        'model_used'     => $result['model_used'],
        'fallback_level' => $result['fallback_level'],
        'duration_ms'    => $result['duration_ms'] ?? 0,
    ]
);
