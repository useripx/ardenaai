<?php

declare(strict_types=1);

namespace Ardena\Repositories;

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;
use Ardena\Support\Logger;

/**
 * FirestoreRepository
 *
 * Mengelola penyimpanan dan pengambilan riwayat percakapan siswa
 * menggunakan Cloud Firestore REST API dengan fallback penyimpanan lokal.
 */
class FirestoreRepository
{
    private string $projectId;
    private string $localDataDir;

    public function __construct()
    {
        $this->projectId = (string)AppConfig::get('FIREBASE_PROJECT_ID', '');
        $this->localDataDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'conversations';

        if (!is_dir($this->localDataDir)) {
            mkdir($this->localDataDir, 0755, true);
        }
    }

    /**
     * Mengambil daftar percakapan milik satu siswa
     */
    public function listConversations(string $uid): array
    {
        // 1. Coba ambil dari Firestore REST API jika Project ID aktif
        if (!empty($this->projectId) && $this->projectId !== 'ardena-ai-scratch3') {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/users/{$uid}/conversations";
            $res = HttpClient::get($url);
            if ($res['success'] && isset($res['body']['documents'])) {
                $list = [];
                foreach ($res['body']['documents'] as $doc) {
                    $fields = $doc['fields'] ?? [];
                    $nameParts = explode('/', $doc['name']);
                    $convId = end($nameParts);
                    $list[] = [
                        'id'             => $convId,
                        'title'          => $fields['title']['stringValue'] ?? 'Obrolan Scratch',
                        'preview'        => $fields['preview']['stringValue'] ?? '',
                        'updated_at'     => $fields['updated_at']['stringValue'] ?? date('c'),
                        'total_messages' => (int)($fields['total_messages']['integerValue'] ?? 0),
                    ];
                }
                return $list;
            }
        }

        // 2. Fallback penyimpanan lokal per siswa
        $userDir = $this->localDataDir . DIRECTORY_SEPARATOR . preg_replace('/[^a-zA-Z0-9_-]/', '', $uid);
        if (!is_dir($userDir)) {
            return [];
        }

        $files = glob($userDir . DIRECTORY_SEPARATOR . '*.json');
        $conversations = [];

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $data = json_decode((string)$content, true);
            if (is_array($data)) {
                $conversations[] = [
                    'id'             => $data['id'] ?? basename($file, '.json'),
                    'title'          => $data['title'] ?? 'Obrolan Scratch',
                    'preview'        => $data['preview'] ?? '',
                    'updated_at'     => $data['updated_at'] ?? date('c', filemtime($file)),
                    'total_messages' => count($data['messages'] ?? []),
                ];
            }
        }

        // Urutkan dari yang paling baru
        usort($conversations, fn($a, $b) => strcmp($b['updated_at'], $a['updated_at']));

        return $conversations;
    }

    /**
     * Mengambil detail satu percakapan
     */
    public function getConversation(string $uid, string $conversationId): ?array
    {
        $safeUid = preg_replace('/[^a-zA-Z0-9_-]/', '', $uid);
        $safeConvId = preg_replace('/[^a-zA-Z0-9_-]/', '', $conversationId);
        $filePath = $this->localDataDir . DIRECTORY_SEPARATOR . $safeUid . DIRECTORY_SEPARATOR . "{$safeConvId}.json";

        if (file_exists($filePath)) {
            $data = json_decode((string)file_get_contents($filePath), true);
            return is_array($data) ? $data : null;
        }

        return null;
    }

    /**
     * Menyimpan atau memperbarui percakapan
     */
    public function saveConversation(string $uid, string $conversationId, array $data): bool
    {
        $safeUid = preg_replace('/[^a-zA-Z0-9_-]/', '', $uid);
        $safeConvId = preg_replace('/[^a-zA-Z0-9_-]/', '', $conversationId);
        $userDir = $this->localDataDir . DIRECTORY_SEPARATOR . $safeUid;

        if (!is_dir($userDir)) {
            mkdir($userDir, 0755, true);
        }

        $data['id'] = $safeConvId;
        $data['updated_at'] = date('c');

        $filePath = $userDir . DIRECTORY_SEPARATOR . "{$safeConvId}.json";
        $success = (bool)file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        Logger::info("Percakapan disimpan", ['uid' => $safeUid, 'conv_id' => $safeConvId]);
        return $success;
    }

    /**
     * Menghapus percakapan
     */
    public function deleteConversation(string $uid, string $conversationId): bool
    {
        $safeUid = preg_replace('/[^a-zA-Z0-9_-]/', '', $uid);
        $safeConvId = preg_replace('/[^a-zA-Z0-9_-]/', '', $conversationId);
        $filePath = $this->localDataDir . DIRECTORY_SEPARATOR . $safeUid . DIRECTORY_SEPARATOR . "{$safeConvId}.json";

        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return false;
    }
}
