<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;

$key = AppConfig::get('GROQ_API_KEY');

// Test chat completion dengan Priority 1 model: openai/gpt-oss-120b
$payload = [
    'model' => 'openai/gpt-oss-120b',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Kamu adalah Ardena, asisten AI ramah untuk anak-anak belajar Scratch 3.'
        ],
        [
            'role' => 'user',
            'content' => 'Halo Ardena! Apa kabar?'
        ]
    ],
    'max_tokens' => 100,
    'temperature' => 0.7
];

$response = HttpClient::postJson('https://api.groq.com/openai/v1/chat/completions', $payload, [
    'Authorization' => "Bearer $key"
]);

echo "Status HTTP: " . $response['status'] . PHP_EOL;
if ($response['success']) {
    echo "Jawaban AI: " . ($response['body']['choices'][0]['message']['content'] ?? 'N/A') . PHP_EOL;
} else {
    echo "Error: " . json_encode($response['body']) . PHP_EOL;
}
