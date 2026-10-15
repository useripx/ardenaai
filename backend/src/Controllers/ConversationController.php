<?php

declare(strict_types=1);

namespace Ardena\Controllers;

use Ardena\Repositories\FirestoreRepository;

/**
 * ConversationController — Mengelola CRUD Riwayat Sesi Percakapan Siswa
 * 
 * Terhubung ke FirestoreRepository dengan isolasi per identitas uid siswa.
 */
class ConversationController extends BaseController
{
    private FirestoreRepository $repo;

    public function __construct()
    {
        $this->repo = new FirestoreRepository();
    }

    /**
     * Mengambil daftar seluruh percakapan milik siswa yang login
     */
    public function index(): void
    {
        $user = $this->getAuthUser();
        $conversations = $this->repo->listConversations($user['uid']);

        $this->json([
            'conversations' => $conversations,
            'total'         => count($conversations),
        ], 'Riwayat percakapan berhasil diambil!');
    }

    /**
     * Memuat detail 1 percakapan berdasarkan ID
     */
    public function show(?string $id = null): void
    {
        $user = $this->getAuthUser();
        $convId = $id ?? $this->getQuery('id');

        if (empty($convId)) {
            $this->error('INVALID_INPUT', 'Parameter ID percakapan tidak boleh kosong!', 400);
            return;
        }

        $conversation = $this->repo->getConversation($user['uid'], $convId);

        if (!$conversation) {
            $this->error('NOT_FOUND', 'Percakapan tidak ditemukan atau kamu tidak memiliki akses.', 404);
            return;
        }

        $this->json($conversation, 'Percakapan berhasil dimuat!');
    }

    /**
     * Menyimpan sesi percakapan baru atau pembaruan pesan
     */
    public function save(): void
    {
        $user = $this->getAuthUser();
        $body = $this->getJsonPayload();

        if (empty($body['conversation_id'])) {
            $this->error('INVALID_INPUT', 'ID percakapan wajib disertakan.', 400);
            return;
        }

        $conversationId = (string)$body['conversation_id'];
        $title = trim((string)($body['title'] ?? 'Obrolan Scratch'));
        $messages = is_array($body['messages'] ?? null) ? $body['messages'] : [];

        // Cuplikan preview
        $preview = '';
        if (!empty($messages)) {
            $lastMsg = end($messages);
            $preview = substr((string)($lastMsg['content'] ?? ($lastMsg['answer'] ?? '')), 0, 80) . '...';
        }

        $saved = $this->repo->saveConversation($user['uid'], $conversationId, [
            'title'    => $title,
            'preview'  => $preview,
            'messages' => $messages,
        ]);

        if (!$saved) {
            $this->error('INTERNAL_SERVER_ERROR', 'Gagal menyimpan percakapan ke penyimpanan.', 500);
            return;
        }

        $this->json([
            'conversation_id' => $conversationId,
            'saved_at'        => date('c'),
        ], 'Percakapan berhasil disimpan! 💾');
    }

    /**
     * Menghapus sesi percakapan
     */
    public function delete(?string $id = null): void
    {
        $user = $this->getAuthUser();
        $convId = $id ?? $this->getQuery('id');

        if (empty($convId)) {
            $body = $this->getJsonPayload();
            $convId = (string)($body['id'] ?? ($body['conversation_id'] ?? ''));
        }

        if (empty($convId)) {
            $this->error('INVALID_INPUT', 'Parameter ID percakapan tidak boleh kosong!', 400);
            return;
        }

        $deleted = $this->repo->deleteConversation($user['uid'], $convId);

        if (!$deleted) {
            $this->error('NOT_FOUND', 'Percakapan tidak ditemukan atau gagal dihapus.', 404);
            return;
        }

        $this->json([
            'deleted_id' => $convId
        ], 'Percakapan berhasil dihapus! 🗑️');
    }
}
