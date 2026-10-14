<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;

$key = AppConfig::get('GROQ_API_KEY');
$response = HttpClient::get('https://api.groq.com/openai/v1/models', [
    'Authorization' => "Bearer $key"
]);

if ($response['success']) {
    echo "=== DAFTAR MODEL GROQ AKTIF ===" . PHP_EOL;
    foreach ($response['body']['data'] as $model) {
        echo "- " . $model['id'] . PHP_EOL;
    }
} else {
    echo "Gagal mengambil model: " . json_encode($response['body']) . PHP_EOL;
}
