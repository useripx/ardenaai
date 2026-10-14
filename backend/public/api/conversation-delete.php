<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ardena\Middleware\AuthMiddleware;
use Ardena\Repositories\FirestoreRepository;
use Ardena\Support\ApiResponse;

// Izinkan DELETE atau POST dengan _method=DELETE
$method = $_SERVER['REQUEST_METHOD'] ?? 'DELETE';
if ($method !== 'DELETE' && $method !== 'POST') {
    ApiResponse::error('METHOD_NOT_ALLOWED', 'Endpoint ini hanya menerima permintaan DELETE', 405);
}

// 1. Verifikasi Autentikasi Pengguna
$user = AuthMiddleware::authenticate();

// 2. Baca ID percakapan
$conversationId = (string)($_GET['id'] ?? '');
if (empty($conversationId)) {
    $raw = file_get_contents('php://input');
    $body = json_decode((string)$raw, true);
    $conversationId = (string)($body['id'] ?? ($body['conversation_id'] ?? ''));
}

if (empty($conversationId)) {
    ApiResponse::error('INVALID_INPUT', 'Parameter ID percakapan tidak boleh kosong!', 400);
}

$repo = new FirestoreRepository();
$deleted = $repo->deleteConversation($user['uid'], $conversationId);

if (!$deleted) {
    ApiResponse::error('NOT_FOUND', 'Percakapan tidak ditemukan atau gagal dihapus.', 404);
}

ApiResponse::success([
    'deleted_id' => $conversationId
], 'Percakapan berhasil dihapus! 🗑️');
