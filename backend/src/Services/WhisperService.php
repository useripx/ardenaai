<?php

declare(strict_types=1);

namespace Ardena\Services;

use Ardena\Config\AppConfig;
use Ardena\Support\HttpClient;
use Ardena\Support\Logger;
use CURLFile;

/**
 * WhisperService
 *
 * Mengubah rekaman suara mikrofon siswa menjadi teks (Speech-to-Text)
 * menggunakan Groq Whisper Audio Transcriptions API.
 */
class WhisperService
{
    private string $apiKey;
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = (string)AppConfig::get('GROQ_API_KEY', '');
        $this->baseUrl = (string)AppConfig::get('GROQ_BASE_URL', 'https://api.groq.com/openai/v1');
        $this->model = (string)AppConfig::get('GROQ_MODEL_WHISPER', 'whisper-large-v3');
    }

    /**
     * Mentranskripsi file audio menjadi teks
     *
     * @param string $audioPath Path berkas audio di server
     * @return array [ 'success' => bool, 'text' => string, 'duration_ms' => int ]
     */
    public function transcribe(string $audioPath): array
    {
        if (!file_exists($audioPath)) {
            return [
                'success' => false,
                'error'   => 'Berkas audio tidak ditemukan.',
            ];
        }

        $ext = strtolower(pathinfo($audioPath, PATHINFO_EXTENSION) ?: 'webm');
        $mimeMap = [
            'webm' => 'audio/webm',
            'ogg'  => 'audio/ogg',
            'wav'  => 'audio/wav',
            'mp3'  => 'audio/mpeg',
            'm4a'  => 'audio/m4a',
            'mp4'  => 'audio/mp4',
        ];
        $mimeType = $mimeMap[$ext] ?? (mime_content_type($audioPath) ?: 'audio/webm');
        $fileName = 'voice.' . $ext;

        $postFields = [
            'file'            => new CURLFile($audioPath, $mimeType, $fileName),
            'model'           => $this->model,
            'language'        => 'id', // Bahasa Indonesia
            'response_format' => 'json',
            'temperature'     => '0.2',
        ];

        Logger::info("Mengirim audio ke Groq Whisper model: {$this->model}");
        $startTime = microtime(true);

        $response = HttpClient::postMultipart("{$this->baseUrl}/audio/transcriptions", $postFields, [
            'Authorization' => "Bearer {$this->apiKey}"
        ], 30);

        $durationMs = round((microtime(true) - $startTime) * 1000);

        if ($response['success'] && isset($response['body']['text'])) {
            $transcribedText = trim((string)$response['body']['text']);
            Logger::info("Berhasil transkripsi suara: '{$transcribedText}' ($durationMs ms)");

            return [
                'success'     => true,
                'text'        => $transcribedText,
                'duration_ms' => $durationMs,
                'model_used'  => $this->model,
            ];
        }

        $errorMsg = $response['body']['error']['message'] ?? ($response['error'] ?? 'Transkripsi gagal.');
        Logger::error("Gagal transkripsi suara pada Groq Whisper", [
            'status' => $response['status'],
            'error'  => $errorMsg,
        ]);

        return [
            'success' => false,
            'error'   => 'Suaramu belum terdengar jelas. Coba dekatkan mikrofon dan bicara lagi ya! 🎙️',
            'details' => is_array($errorMsg) ? $errorMsg : ['raw' => $errorMsg],
        ];
    }
}
