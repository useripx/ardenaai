<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;

$key = AppConfig::get('GROQ_API_KEY');

// Buat gambar 100x100 PNG menggunakan GD library
$im = imagecreatetruecolor(100, 100);
$blue = imagecolorallocate($im, 76, 151, 255); // Scratch motion blue
imagefill($im, 0, 0, $blue);

ob_start();
imagepng($im);
$imgData = ob_get_clean();
imagedestroy($im);

$base64 = 'data:image/png;base64,' . base64_encode($imgData);

echo "Menguji qwen/qwen3.8-27b dengan gambar 100x100 warna biru..." . PHP_EOL;

$payload = [
    'model' => 'qwen/qwen3.8-27b',
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                [
                    'type' => 'text',
                    'text' => 'Apa warna dominan pada gambar ini dalam bahasa Indonesia?'
                ],
                [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => $base64
                    ]
                ]
            ]
        ]
    ],
    'max_tokens' => 50
];

$response = HttpClient::postJson('https://api.groq.com/openai/v1/chat/completions', $payload, [
    'Authorization' => "Bearer $key"
]);

echo "Status: " . $response['status'] . PHP_EOL;
if ($response['success']) {
    echo "Jawaban Vision Qwen: " . ($response['body']['choices'][0]['message']['content'] ?? '') . PHP_EOL;
} else {
    echo "Error: " . json_encode($response['body']) . PHP_EOL;
}
