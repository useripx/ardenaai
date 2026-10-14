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

// 2. Ambil riwayat percakapan milik pengguna terkait
$repo = new FirestoreRepository();
$conversations = $repo->listConversations($user['uid']);

ApiResponse::success([
    'conversations' => $conversations,
    'total'         => count($conversations),
], 'Riwayat percakapan berhasil diambil!');
