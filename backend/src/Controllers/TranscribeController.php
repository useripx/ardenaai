<?php

declare(strict_types=1);

namespace Ardena\Controllers;

use Ardena\Config\AppConfig;
use Ardena\Services\WhisperService;

/**
 * TranscribeController — Mengelola Speech-to-Text Rekaman Suara Siswa
 * 
 * Mengirim berkas audio ke Groq Whisper (whisper-large-v3) dengan prioritas bahasa Indonesia.
 */
class TranscribeController extends BaseController
{
    public function transcribe(): void
    {
        // 1. Verifikasi Autentikasi Pengguna
        $user = $this->getAuthUser();

        // 2. Validasi File Audio Unggahan
        if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
            $this->error(
                'INVALID_INPUT',
                'Rekaman audio belum diterima atau terjadi kesalahan saat merekam. Coba tekan tombol mikrofon lagi ya! 🎙️',
                400
            );
            return;
        }

        $audioFile = $_FILES['audio'];
        $maxSizeMb = AppConfig::getInt('MAX_UPLOAD_SIZE_AUDIO_MB', 10);
        $maxSizeBytes = $maxSizeMb * 1024 * 1024;

        if ($audioFile['size'] > $maxSizeBytes) {
            $this->error(
                'FILE_TOO_LARGE',
                "Ukuran rekaman audio terlalu panjang atau melebihi {$maxSizeMb} MB. Coba rekam pertanyaan yang lebih singkat ya!",
                413
            );
            return;
        }

        // 3. Simpan sementara di direktori temp
        $tempDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $ext = pathinfo($audioFile['name'], PATHINFO_EXTENSION) ?: 'webm';
        $tempPath = $tempDir . DIRECTORY_SEPARATOR . 'aud_' . uniqid() . '.' . $ext;

        if (!move_uploaded_file($audioFile['tmp_name'], $tempPath)) {
            $this->error('INTERNAL_SERVER_ERROR', 'Gagal memproses berkas audio di server.', 500);
            return;
        }

        // 4. Transkripsi melalui WhisperService
        $whisperService = new WhisperService();
        $result = $whisperService->transcribe($tempPath);

        // 5. Hapus berkas audio sementara seketika setelah selesai diproses (kepatuhan privasi)
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }

        if (!$result['success']) {
            $this->error(
                'AUDIO_TRANSCRIPTION_FAILED',
                $result['error'] ?? 'Gagal mengubah rekaman suara menjadi teks.',
                500,
                $result['details'] ?? null
            );
            return;
        }

        $this->json(
            [
                'text' => $result['text'],
            ],
            'Suara berhasil diubah menjadi teks!',
            200,
            [
                'duration_ms' => $result['duration_ms'] ?? 0,
                'model_used'  => $result['model_used'] ?? 'whisper-large-v3',
            ]
        );
    }
}
