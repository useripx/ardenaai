<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ardena\Config\AppConfig;
use Ardena\Support\ApiResponse;
use Ardena\Support\HttpClient;

// Hanya izinkan method GET
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    ApiResponse::error('METHOD_NOT_ALLOWED', 'Endpoint ini hanya menerima permintaan GET', 405);
}

$groqKey = (string)AppConfig::get('GROQ_API_KEY', '');
$groqStatus = 'not_configured';
$groqModelsCount = 0;

if (!empty($groqKey) && !str_starts_with($groqKey, 'gsk_your_')) {
    // Uji koneksi ringan ke Groq Models API
    $response = HttpClient::get('https://api.groq.com/openai/v1/models', [
        'Authorization' => "Bearer $groqKey"
    ], 5);

    if ($response['success'] && isset($response['body']['data'])) {
        $groqStatus = 'connected';
        $groqModelsCount = count($response['body']['data']);
    } else {
        $groqStatus = 'connection_error';
    }
}

ApiResponse::success([
    'status'           => 'healthy',
    'app_name'         => AppConfig::get('APP_NAME', 'Ardena AI Scratch 3'),
    'environment'      => AppConfig::get('APP_ENV', 'local'),
    'php_version'      => PHP_VERSION,
    'groq_connection'  => $groqStatus,
    'groq_models_available' => $groqModelsCount,
], 'Backend Ardena AI siap melayani!');
