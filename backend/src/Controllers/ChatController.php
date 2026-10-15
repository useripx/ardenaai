<?php

declare(strict_types=1);

namespace Ardena\Controllers;

use Ardena\Services\GroqService;

/**
 * ChatController — Mengelola Percakapan Teks & Logika Scratch
 * 
 * Menangani inferensi Groq Cloud dengan mekanisme 4-level fallback otomatis.
 */
class ChatController extends BaseController
{
    public function chat(): void
    {
        // 1. Verifikasi Autentikasi Pengguna
        $user = $this->getAuthUser();

        // 2. Baca payload request
        $body = $this->getJsonPayload();

        if (empty(trim((string)($body['message'] ?? '')))) {
            $this->error(
                'INVALID_INPUT',
                'Oops! Pertanyaan belajarmu belum ditulis nih. Tuliskan apa yang ingin kamu tanyakan pada Ardena ya! 📝',
                400
            );
            return;
        }

        $userMessage = trim((string)$body['message']);
        $history = is_array($body['history'] ?? null) ? $body['history'] : [];
        $conversationId = (string)($body['conversation_id'] ?? ('conv_' . time() . '_' . substr(md5(uniqid()), 0, 6)));

        // 3. Eksekusi Groq Service dengan Auto-Fallback 4 Tingkat
        $groqService = new GroqService();
        $result = $groqService->chat($userMessage, $history);

        if (!$result['success']) {
            $this->error(
                'AI_SERVICE_UNAVAILABLE',
                $result['error'] ?? 'Layanan AI Ardena sedang sibuk saat ini. Coba klik kirim lagi ya! 🤖',
                503,
                $result['errors'] ?? null
            );
            return;
        }

        // 4. Kembalikan respons terstruktur sukses
        $this->json(
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
    }
}
