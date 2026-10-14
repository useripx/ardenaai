<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ardena\Middleware\AuthMiddleware;
use Ardena\Repositories\FirestoreRepository;
use Ardena\Support\ApiResponse;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    ApiResponse::error('METHOD_NOT_ALLOWED', 'Endpoint ini hanya menerima permintaan GET', 405);
}

// 1. Verifikasi Autentikasi Pengguna
$user = AuthMiddleware::authenticate();

// 2. Baca parameter ID
$conversationId = (string)($_GET['id'] ?? '');

if (empty($conversationId)) {
    ApiResponse::error('INVALID_INPUT', 'Parameter ID percakapan tidak boleh kosong!', 400);
}

// 3. Ambil percakapan dengan validasi kepemilikan data
$repo = new FirestoreRepository();
$conversation = $repo->getConversation($user['uid'], $conversationId);

if (!$conversation) {
    ApiResponse::error('NOT_FOUND', 'Percakapan tidak ditemukan atau kamu tidak memiliki akses.', 404);
}

ApiResponse::success($conversation, 'Percakapan berhasil dimuat!');
