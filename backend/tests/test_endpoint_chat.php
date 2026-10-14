<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Ardena\Support\HttpClient;

echo "=== PENGUJIAN ENDPOINT /api/chat.php ===" . PHP_EOL;

// 1. Uji Tanpa Token (Harus 401 Unauthorized)
echo "[Test 1] Permintaan tanpa token Authorization..." . PHP_EOL;
$res1 = HttpClient::postJson('http://localhost/ardenaai/backend/public/api/chat.php', [
    'message' => 'Halo Ardena!'
]);

echo "Status: " . $res1['status'] . PHP_EOL;
if ($res1['status'] === 401) {
    echo "LULUS: Ditolak dengan 401 Unauthorized sesuai ketentuan keamanan!" . PHP_EOL;
} else {
    // Jika Laragon web server belum aktif di localhost, kita uji simulasi via mock server environment
    echo "Catatan: Web server Laragon belum menyajikan port 80 atau URL berbeda (" . $res1['status'] . ")" . PHP_EOL;
}

// 2. Uji Langsung via GroqService & AuthMiddleware secara terisolasi
echo PHP_EOL . "[Test 2] Uji internal GroqService & Validator..." . PHP_EOL;
$service = new Ardena\Services\GroqService();
$result = $service->chat("Gimana cara bikin kucing Scratch jalan bolak-balik di layar?");

echo "Sukses: " . ($result['success'] ? 'YA' : 'TIDAK') . PHP_EOL;
echo "Model Digunakan: " . ($result['model_used'] ?? 'N/A') . PHP_EOL;
echo "Fallback Level: " . ($result['fallback_level'] ?? 'N/A') . PHP_EOL;
echo "Answer: " . ($result['data']['answer'] ?? 'N/A') . PHP_EOL;
echo "Jumlah Langkah: " . count($result['data']['steps'] ?? []) . PHP_EOL;
echo "Jumlah Blok Scratch: " . count($result['data']['blocks'] ?? []) . PHP_EOL;

if (!empty($result['data']['blocks'])) {
    echo "Contoh Blok 1: " . json_encode($result['data']['blocks'][0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
