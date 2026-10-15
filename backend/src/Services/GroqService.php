<?php

declare(strict_types=1);

namespace Ardena\Services;

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;
use Ardena\Support\Logger;
use Ardena\Validators\AIResponseValidator;

/**
 * GroqService
 *
 * Layanan integrasi Groq Cloud API dengan mekanisme Auto-Fallback 4 Tingkat
 * untuk percakapan coding Scratch 3 anak SD.
 */
class GroqService
{
    private string $apiKey;
    private string $baseUrl;
    private array $fallbackModels;

    public function __construct()
    {
        $this->apiKey = (string)AppConfig::get('GROQ_API_KEY', '');
        $this->baseUrl = (string)AppConfig::get('GROQ_BASE_URL', 'https://api.groq.com/openai/v1');

        // Urutan fallback 4 tingkat resmi sesuai Implementation Plan
        $this->fallbackModels = [
            (string)AppConfig::get('GROQ_MODEL_CHAT_PRIMARY', 'openai/gpt-oss-120b'),
            (string)AppConfig::get('GROQ_MODEL_CHAT_FALLBACK_1', 'qwen/qwen3.8-27b'),
            (string)AppConfig::get('GROQ_MODEL_CHAT_FALLBACK_2', 'openai/gpt-oss-20b'),
            (string)AppConfig::get('GROQ_MODEL_CHAT_FALLBACK_3', 'allam-2-7b'),
        ];
    }

    /**
     * Mengirim pesan siswa ke Groq dengan fallback otomatis
     *
     * @param string $userMessage Pesan teks dari siswa
     * @param array $history Riwayat pesan sebelumnya [['role' => 'user'|'assistant', 'content' => '...']]
     * @return array [ 'success' => bool, 'data' => array, 'model_used' => string, 'fallback_level' => int ]
     */
    public function chat(string $userMessage, array $history = []): array
    {
        $systemPrompt = $this->buildSystemPrompt();

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Masukkan riwayat pesan (maksimal 6 pesan terakhir untuk efisiensi konteks)
        $recentHistory = array_slice($history, -6);
        foreach ($recentHistory as $msg) {
            if (isset($msg['role'], $msg['content']) && in_array($msg['role'], ['user', 'assistant'], true)) {
                $messages[] = [
                    'role'    => $msg['role'],
                    'content' => (string)$msg['content'],
                ];
            }
        }

        // Tambahkan pesan siswa saat ini
        $messages[] = [
            'role'    => 'user',
            'content' => $userMessage,
        ];

        $errors = [];

        // Iterasi melalui 4 tingkat fallback jika terjadi HTTP 429 atau error recoverable
        foreach ($this->fallbackModels as $level => $model) {
            $payload = [
                'model'       => $model,
                'messages'    => $messages,
                'temperature' => 0.6,
                'max_tokens'  => 2048,
            ];

            Logger::info("Mencoba kirim chat ke Groq model: $model (Fallback Level $level)");

            $startTime = microtime(true);
            $response = HttpClient::postJson("{$this->baseUrl}/chat/completions", $payload, [
                'Authorization' => "Bearer {$this->apiKey}"
            ], 20);
            $durationMs = round((microtime(true) - $startTime) * 1000);

            if ($response['success'] && isset($response['body']['choices'][0]['message']['content'])) {
                $rawContent = $response['body']['choices'][0]['message']['content'];
                Logger::info("Berhasil menerima respons dari model $model ($durationMs ms)");

                $validatedData = AIResponseValidator::parseAndValidate($rawContent);

                return [
                    'success'        => true,
                    'data'           => $validatedData,
                    'model_used'     => $model,
                    'fallback_level' => $level,
                    'duration_ms'    => $durationMs,
                ];
            }

            $httpStatus = $response['status'];
            $errorMessage = $response['body']['error']['message'] ?? ($response['error'] ?? 'Unknown Error');
            $errors[] = "[$model: HTTP $httpStatus - $errorMessage]";

            Logger::warning("Percobaan gagal pada model $model (HTTP $httpStatus). Mencoba fallback berikutnya...", [
                'status' => $httpStatus,
                'error'  => $errorMessage,
            ]);

            // Jika status bukan 429 atau 503 (misal error format), tetap coba fallback berikutnya untuk keandalan maksimal
        }

        // Jika seluruh 4 model gagal
        Logger::error("Seluruh model Groq fallback gagal", ['errors' => $errors]);

        return [
            'success' => false,
            'error'   => 'Layanan AI Ardena sedang sibuk saat ini. Tunggu beberapa detik lalu coba kirim lagi ya! 🌟',
            'errors'  => $errors,
        ];
    }

    /**
     * Membangun instruksi sistem ramah anak SD
     */
    private function buildSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah Ardena, asisten AI ramah, ceria, dan suportif untuk anak-anak sekolah dasar (usia 7-12 tahun) yang sedang belajar pemrograman visual Scratch 3.

Gaya Komunikasi:
1. Selalu gunakan Bahasa Indonesia yang mudah dipahami, antusias, dan menyemangati (gunakan emotikon ceria seperti 🚀, 🌟, 🐱, 🎮).
2. Jawaban harus langsung pada intinya, tidak berbelit-belit, dan mudah dipraktikkan langsung di aplikasi Scratch 3.
3. Kategori blok Scratch 3 resmi:
   - Gerakan (motion / Biru)
   - Tampilan (looks / Ungu)
   - Suara (sound / Merah Muda/Pink)
   - Kejadian (events / Kuning)
   - Kontrol (control / Oranye)
   - Sensor (sensing / Biru Muda)
   - Operator (operators / Hijau)
   - Variabel (variables / Oranye Tua)

FORMAT OUTPUT WAJIB:
Kamu HARUS selalu menjawab dalam format JSON murni yang valid dengan struktur berikut:
{
  "answer": "Penjelasan singkat, ramah, dan memotivasi tentang logika Scratch yang ditanyakan.",
  "steps": [
    "Langkah 1: Pilih kategori Kejadian (Kuning) dan tarik blok 'ketika tombol [spasi] ditekan'.",
    "Langkah 2: Pilih kategori Gerakan (Biru) dan sambungkan blok 'ubah y sebesar ( 50 )'."
  ],
  "blocks": [
    {
      "id": "blk_1",
      "category": "events",
      "opcode": "event_whenkeypressed",
      "fields": { "KEY_OPTION": "space" },
      "inputs": {},
      "label": "ketika tombol [ spasi ] ditekan"
    },
    {
      "id": "blk_2",
      "category": "motion",
      "opcode": "motion_changeyby",
      "fields": {},
      "inputs": { "DY": 50 },
      "label": "ubah y sebesar ( 50 )"
    }
  ],
  "follow_up_question": "Pertanyaan pemantik santai untuk eksplorasi anak berikutnya, atau null."
}
PROMPT;
    }
}
