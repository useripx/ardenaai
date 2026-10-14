<?php

declare(strict_types=1);

namespace Ardena\Services;

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;
use Ardena\Support\Logger;
use Ardena\Validators\ScratchBlockValidator;

/**
 * VisionService
 *
 * Menganalisis foto atau tangkapan layar blok Scratch 3 yang diunggah siswa
 * menggunakan model Vision Groq (qwen/qwen3.8-27b).
 */
class VisionService
{
    private string $apiKey;
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = (string)AppConfig::get('GROQ_API_KEY', '');
        $this->baseUrl = (string)AppConfig::get('GROQ_BASE_URL', 'https://api.groq.com/openai/v1');
        $this->model = (string)AppConfig::get('GROQ_MODEL_VISION', 'qwen/qwen3.8-27b');
    }

    /**
     * Menganalisis gambar blok Scratch dari file path
     *
     * @param string $imagePath Lokasi file gambar di server
     * @param string $userPrompt Pertanyaan atau catatan tambahan siswa
     * @return array
     */
    public function analyzeImage(string $imagePath, string $userPrompt = ''): array
    {
        if (!file_exists($imagePath)) {
            return [
                'success' => false,
                'error'   => 'Berkas gambar tidak ditemukan di server.',
            ];
        }

        // 1. Validasi MIME Type & Dimensi Gambar
        $mime = mime_content_type($imagePath) ?: 'image/jpeg';
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $allowedMimes, true)) {
            return [
                'success' => false,
                'error'   => 'Format gambar tidak didukung. Harap unggah foto berekstensi PNG, JPG, atau WEBP ya! 📷',
            ];
        }

        $imageSize = @getimagesize($imagePath);
        if (!$imageSize || $imageSize[0] < 32 || $imageSize[1] < 32) {
            return [
                'success' => false,
                'error'   => 'Resolusi gambar terlalu kecil. Minimal ukuran gambar adalah 32x32 piksel ya! 🔍',
            ];
        }

        // 2. Encode gambar ke format data URI Base64
        $imageData = file_get_contents($imagePath);
        $base64Image = 'data:' . $mime . ';base64,' . base64_encode((string)$imageData);

        $promptText = !empty($userPrompt) ? $userPrompt : 'Tolong periksa foto blok kodingan Scratch 3 saya ini.';

        $systemInstruction = <<<PROMPT
Kamu adalah Ardena, asisten AI ramah untuk anak SD yang memeriksa foto/tangkapan layar blok Scratch 3.
Tugasmu:
1. Identifikasi warna dan teks blok Scratch 3 pada gambar (Biru = Gerakan, Ungu = Tampilan, Kuning = Kejadian, Oranye = Kontrol, Hijau = Operator, dsb).
2. Jelaskan apa yang dilakukan blok-blok tersebut dengan bahasa ramah anak.
3. Jika ada kekurangan atau kesalahan logika (bug), berikan saran perbaikan yang menyemangati.

FORMAT OUTPUT WAJIB:
Jawab HANYA dalam format JSON valid berikut:
{
  "detected_blocks_summary": "Ringkasan blok yang terdeteksi pada foto",
  "analysis": "Penjelasan cara kerja susunan blok tersebut dalam bahasa Indonesia yang ceria",
  "recommendation": "Saran perbaikan atau penambahan blok jika ada",
  "suggested_blocks": [
    {
      "id": "blk_rec_1",
      "category": "motion",
      "opcode": "motion_ifonedgebounce",
      "label": "jika di pinggir, pantulkan"
    }
  ]
}
PROMPT;

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemInstruction
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $promptText
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $base64Image
                            ]
                        ]
                    ]
                ]
            ],
            'temperature' => 0.4,
            'max_tokens'  => 800,
        ];

        Logger::info("Mengirim gambar ke Groq Vision model: {$this->model}");
        $startTime = microtime(true);

        $response = HttpClient::postJson("{$this->baseUrl}/chat/completions", $payload, [
            'Authorization' => "Bearer {$this->apiKey}"
        ], 30);

        $durationMs = round((microtime(true) - $startTime) * 1000);

        if (!$response['success'] || !isset($response['body']['choices'][0]['message']['content'])) {
            Logger::error("Gagal menganalisis gambar pada Groq Vision", [
                'status' => $response['status'],
                'body'   => $response['body']
            ]);
            return [
                'success' => false,
                'error'   => 'Maaf, Ardena belum berhasil membaca foto kodinganmu. Coba foto lebih dekat dan terang ya! 📷',
            ];
        }

        $rawContent = trim($response['body']['choices'][0]['message']['content']);
        Logger::info("Berhasil menerima respons Groq Vision ($durationMs ms)");

        // Parse JSON dari output
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $rawContent, $matches)) {
            $jsonStr = $matches[1];
        } elseif (preg_match('/\{[\s\S]*\}/', $rawContent, $matches)) {
            $jsonStr = $matches[0];
        } else {
            $jsonStr = $rawContent;
        }

        $decoded = json_decode($jsonStr, true);

        if (!is_array($decoded)) {
            return [
                'success' => true,
                'data' => [
                    'detected_blocks_summary' => 'Foto kodingan Scratch 3 berhasil dibaca.',
                    'analysis'                => $rawContent,
                    'recommendation'          => 'Terus berlatih ya, kodinganmu sudah hebat! 🌟',
                    'suggested_blocks'        => [],
                ],
                'duration_ms' => $durationMs,
            ];
        }

        $suggestedBlocks = is_array($decoded['suggested_blocks'] ?? null)
            ? ScratchBlockValidator::validateBlocks($decoded['suggested_blocks'])
            : [];

        return [
            'success' => true,
            'data' => [
                'detected_blocks_summary' => (string)($decoded['detected_blocks_summary'] ?? 'Blok Scratch terdeteksi.'),
                'analysis'                => (string)($decoded['analysis'] ?? ''),
                'recommendation'          => (string)($decoded['recommendation'] ?? ''),
                'suggested_blocks'        => $suggestedBlocks,
            ],
            'duration_ms' => $durationMs,
        ];
    }
}
