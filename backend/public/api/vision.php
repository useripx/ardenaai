<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ardena\Config\AppConfig;
use Ardena\Middleware\AuthMiddleware;
use Ardena\Services\VisionService;
use Ardena\Support\ApiResponse;

if (($_SERVER['REQUEST_METHOD'] ?? 'POST') !== 'POST') {
    ApiResponse::error('METHOD_NOT_ALLOWED', 'Endpoint ini hanya menerima permintaan POST', 405);
}

// 1. Verifikasi Autentikasi Pengguna
$user = AuthMiddleware::authenticate();

// 2. Validasi File Unggahan
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    ApiResponse::error(
        'INVALID_INPUT',
        'Foto blok kodingan belum dipilih atau terjadi kendala saat mengunggah. Coba pilih foto lagi ya! 📷',
        400
    );
}

$uploadedFile = $_FILES['image'];
$maxSizeMb = AppConfig::getInt('MAX_UPLOAD_SIZE_IMAGE_MB', 5);
$maxSizeBytes = $maxSizeMb * 1024 * 1024;

if ($uploadedFile['size'] > $maxSizeBytes) {
    ApiResponse::error(
        'FILE_TOO_LARGE',
        "Ukuran foto melebihi batas maksimal ({$maxSizeMb} MB). Coba kompres atau ambil foto dengan resolusi lebih kecil ya!",
        413
    );
}

// 3. Simpan sementara di direktori temp
$tempDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'temp';
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0755, true);
}

$ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION) ?: 'jpg';
$tempPath = $tempDir . DIRECTORY_SEPARATOR . 'img_' . uniqid() . '.' . $ext;

if (!move_uploaded_file($uploadedFile['tmp_name'], $tempPath)) {
    ApiResponse::error('INTERNAL_SERVER_ERROR', 'Gagal memproses berkas gambar di server.', 500);
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
    ApiResponse::error(
        'VISION_ANALYSIS_FAILED',
        $result['error'] ?? 'Gagal menganalisis foto blok kodingan.',
        500
    );
}

ApiResponse::success(
    $result['data'],
    'Foto kodinganmu berhasil diperiksa oleh Ardena! 🔍',
    200,
    [
        'duration_ms' => $result['duration_ms'] ?? 0,
        'model_used'  => AppConfig::get('GROQ_MODEL_VISION', 'qwen/qwen3.8-27b'),
    ]
);
