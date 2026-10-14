<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;

$key = AppConfig::get('GROQ_API_KEY');

// 1x1 transparent PNG base64
$dummyBase64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=";

$candidateVisionModels = [
    'llama-3.2-11b-vision-preview',
    'meta-llama/llama-3.2-11b-vision-preview',
    'qwen/qwen3.8-27b'
];

echo "=== PENGUJIAN INPUT VISION PADA GROQ ===" . PHP_EOL;

foreach ($candidateVisionModels as $model) {
    $payload = [
        'model' => $model,
        'messages' => [
            [
                'role' => 'user',
                'content' => [
                    ['type' => 'text', 'text' => 'Deskripsikan gambar ini.'],
                    ['type' => 'image_url', 'image_url' => ['url' => $dummyBase64]]
                ]
            ]
        ],
        'max_tokens' => 20
    ];

    $response = HttpClient::postJson('https://api.groq.com/openai/v1/chat/completions', $payload, [
        'Authorization' => "Bearer $key"
    ]);

    echo "Model: $model => Status: " . $response['status'] . PHP_EOL;
    if ($response['success']) {
        echo "  Sukses! Respons: " . ($response['body']['choices'][0]['message']['content'] ?? '') . PHP_EOL;
    } else {
        echo "  Gagal: " . json_encode($response['body']['error']['message'] ?? $response['body']) . PHP_EOL;
    }
}
