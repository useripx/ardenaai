<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ardena\Middleware\AuthMiddleware;
use Ardena\Repositories\FirestoreRepository;
use Ardena\Support\ApiResponse;

if (($_SERVER['REQUEST_METHOD'] ?? 'POST') !== 'POST') {
    ApiResponse::error('METHOD_NOT_ALLOWED', 'Endpoint ini hanya menerima permintaan POST', 405);
}

// 1. Verifikasi Autentikasi Pengguna
$user = AuthMiddleware::authenticate();

// 2. Baca payload
$raw = file_get_contents('php://input');
$body = json_decode((string)$raw, true);

if (!is_array($body) || empty($body['conversation_id'])) {
    ApiResponse::error('INVALID_INPUT', 'ID percakapan wajib disertakan.', 400);
}

$conversationId = (string)$body['conversation_id'];
$title = trim((string)($body['title'] ?? 'Obrolan Scratch'));
$messages = is_array($body['messages'] ?? null) ? $body['messages'] : [];

// Ambil cuplikan preview dari pesan pertama atau terakhir
$preview = '';
if (!empty($messages)) {
    $lastMsg = end($messages);
    $preview = substr((string)($lastMsg['content'] ?? ($lastMsg['answer'] ?? '')), 0, 80) . '...';
}

$repo = new FirestoreRepository();
$saved = $repo->saveConversation($user['uid'], $conversationId, [
    'title'    => $title,
    'preview'  => $preview,
    'messages' => $messages,
]);

if (!$saved) {
    ApiResponse::error('INTERNAL_SERVER_ERROR', 'Gagal menyimpan percakapan ke penyimpanan.', 500);
}

ApiResponse::success([
    'conversation_id' => $conversationId,
    'saved_at'        => date('c'),
], 'Percakapan berhasil disimpan! 💾');
