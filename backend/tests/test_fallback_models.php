<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;

$key = AppConfig::get('GROQ_API_KEY');
$models = [
    'Priority 1' => 'openai/gpt-oss-120b',
    'Priority 2' => 'qwen/qwen3.8-27b',
    'Priority 3' => 'openai/gpt-oss-20b',
    'Priority 4' => 'allam-2-7b',
];

echo "=== PENGUJIAN 4 MODEL FALLBACK GROQ ===" . PHP_EOL;

foreach ($models as $priority => $modelId) {
    $payload = [
        'model' => $modelId,
        'messages' => [
            ['role' => 'user', 'content' => 'Sebutkan 1 nama blok gerak di Scratch 3 dalam 3 kata!']
        ],
        'max_tokens' => 30,
        'temperature' => 0.5
    ];

    $startTime = microtime(true);
    $response = HttpClient::postJson('https://api.groq.com/openai/v1/chat/completions', $payload, [
        'Authorization' => "Bearer $key"
    ]);
    $duration = round((microtime(true) - $startTime) * 1000);

    if ($response['success']) {
        $answer = trim($response['body']['choices'][0]['message']['content'] ?? '');
        echo "[$priority] $modelId => SUKSES ($duration ms) | Respons: $answer" . PHP_EOL;
    } else {
        echo "[$priority] $modelId => GAGAL (" . $response['status'] . ") | " . json_encode($response['body']) . PHP_EOL;
    }
}
