<?php

declare(strict_types=1);

namespace Ardena\Controllers;

use Ardena\Config\AppConfig;
use Ardena\Services\VisionService;

/**
 * VisionController — Mengelola Analisis Foto Blok Scratch
 * 
 * Mengirim gambar ke Groq Vision (qwen/qwen3.8-27b) untuk mengenali blok Scratch & deteksi warna.
 */
class VisionController extends BaseController
{
    public function analyze(): void
    {
        // 1. Verifikasi Autentikasi Pengguna
        $user = $this->getAuthUser();

        // 2. Validasi File Unggahan
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $this->error(
                'INVALID_INPUT',
                'Foto blok kodingan belum dipilih atau terjadi kendala saat mengunggah. Coba pilih foto lagi ya! 📷',
                400
            );
            return;
        }

        $uploadedFile = $_FILES['image'];
        $maxSizeMb = AppConfig::getInt('MAX_UPLOAD_SIZE_IMAGE_MB', 5);
        $maxSizeBytes = $maxSizeMb * 1024 * 1024;

        if ($uploadedFile['size'] > $maxSizeBytes) {
            $this->error(
                'FILE_TOO_LARGE',
                "Ukuran foto melebihi batas maksimal ({$maxSizeMb} MB). Coba kompres atau ambil foto dengan resolusi lebih kecil ya!",
                413
            );
            return;
        }

        // 3. Simpan sementara di direktori temp
        $tempDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $tempPath = $tempDir . DIRECTORY_SEPARATOR . 'img_' . uniqid() . '.' . $ext;

        if (!move_uploaded_file($uploadedFile['tmp_name'], $tempPath)) {
            $this->error('INTERNAL_SERVER_ERROR', 'Gagal memproses berkas gambar di server.', 500);
            return;
        }

        $prompt = (string)($_POST['prompt'] ?? '');

        // 4. Analisis melalui VisionService
        $visionService = new VisionService();
        $result = $visionService->analyzeImage($tempPath, $prompt);

        // 5. Hapus file sementara seketika setelah selesai diproses (kepatuhan privasi)
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }

        if (!$result['success']) {
            $this->error(
                'VISION_ANALYSIS_FAILED',
                $result['error'] ?? 'Gagal menganalisis foto blok kodingan.',
                500
            );
            return;
        }

        $this->json(
            $result['data'],
            'Foto kodinganmu berhasil diperiksa oleh Ardena! 🔍',
            200,
            [
                'duration_ms' => $result['duration_ms'] ?? 0,
                'model_used'  => AppConfig::get('GROQ_MODEL_VISION', 'qwen/qwen3.8-27b'),
            ]
        );
    }
}
