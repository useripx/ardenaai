<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Ardena\Middleware\AuthMiddleware;
use Ardena\Repositories\FirestoreRepository;
use Ardena\Services\GroqService;
use Ardena\Services\VisionService;
use Ardena\Services\WhisperService;
use Ardena\Validators\ScratchBlockValidator;

echo "==========================================================" . PHP_EOL;
echo "  ARDENA AI SCRATCH 3 — COMPREHENSIVE BACKEND TEST SUITE" . PHP_EOL;
echo "==========================================================" . PHP_EOL;

$passed = 0;
$failed = 0;

function assertTest(string $name, bool $condition, string $detail = '') {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $name" . PHP_EOL;
        $passed++;
    } else {
        echo " [FAIL] $name - $detail" . PHP_EOL;
        $failed++;
    }
}

// 1. Test ScratchBlockValidator
echo PHP_EOL . "--- [Test 1] ScratchBlockValidator ---" . PHP_EOL;
$rawBlocks = [
    [
        'id'       => 'b1',
        'category' => 'motion',
        'opcode'   => 'motion_movesteps',
        'label'    => 'gerak ( 10 ) langkah'
    ],
    [
        'id'       => 'b2',
        'category' => 'invalid_cat',
        'opcode'   => 'test_block',
        'label'    => 'blok percobaan <script>alert(1)</script>'
    ]
];
$sanitized = ScratchBlockValidator::validateBlocks($rawBlocks);
assertTest("Block count matches valid blocks", count($sanitized) === 2);
assertTest("Category fallback to motion for invalid", $sanitized[1]['category'] === 'motion');
assertTest("Color hex injected correctly", $sanitized[0]['color'] === '#4C97FF');
assertTest("XSS script tags escaped in label", !str_contains($sanitized[1]['label'], '<script>'));

// 2. Test AuthMiddleware token verification
echo PHP_EOL . "--- [Test 2] AuthMiddleware ---" . PHP_EOL;
$invalidTokenResult = AuthMiddleware::verifyToken('token_sembarangan');
assertTest("Invalid token returns null", $invalidTokenResult === null);

$devTokenResult = AuthMiddleware::verifyToken('dev_siswa_12345');
assertTest("Local dev token parsed correctly", is_array($devTokenResult) && $devTokenResult['uid'] === '12345');

// 3. Test FirestoreRepository CRUD
echo PHP_EOL . "--- [Test 3] FirestoreRepository CRUD ---" . PHP_EOL;
$repo = new FirestoreRepository();
$testUid = 'test_student_unit';
$convId = 'conv_unit_' . time();

// Save
$saved = $repo->saveConversation($testUid, $convId, [
    'title'    => 'Game Labirin Kucing',
    'preview'  => 'Bagaimana cara bikin dinding labirin?',
    'messages' => [
        ['sender' => 'user', 'content' => 'Gimana cara bikin dinding labirin?'],
        ['sender' => 'assistant', 'content' => 'Gunakan sensor warna sentuh!']
    ]
]);
assertTest("Save conversation succeeds", $saved === true);

// List
$list = $repo->listConversations($testUid);
assertTest("List conversations contains saved session", count($list) >= 1 && $list[0]['id'] === $convId);

// Get single
$detail = $repo->getConversation($testUid, $convId);
assertTest("Get single conversation returns correct title", ($detail['title'] ?? '') === 'Game Labirin Kucing');
assertTest("Get single conversation returns 2 messages", count($detail['messages'] ?? []) === 2);

// Delete
$deleted = $repo->deleteConversation($testUid, $convId);
assertTest("Delete conversation succeeds", $deleted === true);

$detailAfterDelete = $repo->getConversation($testUid, $convId);
assertTest("Conversation is gone after delete", $detailAfterDelete === null);

// 4. Test Live Groq Chat
echo PHP_EOL . "--- [Test 4] Live Groq Chat with 4-Level Fallback ---" . PHP_EOL;
$groqService = new GroqService();
$chatResult = $groqService->chat("Bagaimana cara membuat skor bertambah saat koin diambil?");
assertTest("Live Groq Chat succeeds", $chatResult['success'] === true);
assertTest("Groq answer contains text", !empty($chatResult['data']['answer'] ?? ''));
assertTest("Groq steps is an array", is_array($chatResult['data']['steps'] ?? null));
assertTest("Groq blocks are validated", is_array($chatResult['data']['blocks'] ?? null));

// 5. Test Live Groq Vision (qwen3.8-27b)
echo PHP_EOL . "--- [Test 5] Live Groq Vision (qwen/qwen3.8-27b) ---" . PHP_EOL;
$tempImg = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'unit_test_img.png';
if (!is_dir(dirname($tempImg))) {
    mkdir(dirname($tempImg), 0755, true);
}
// Generate a 100x100 yellow image (Scratch Events yellow #FFBF00)
$im = imagecreatetruecolor(100, 100);
$yellow = imagecolorallocate($im, 255, 191, 0);
imagefill($im, 0, 0, $yellow);
imagepng($im, $tempImg);
imagedestroy($im);

$visionService = new VisionService();
$visionResult = $visionService->analyzeImage($tempImg, 'Apa warna blok pada gambar ini?');
@unlink($tempImg);

assertTest("Live Groq Vision succeeds", $visionResult['success'] === true);
assertTest("Analysis text is present", !empty($visionResult['data']['analysis'] ?? ''));

// 6. Test Live Groq Whisper (whisper-large-v3)
echo PHP_EOL . "--- [Test 6] Live Groq Whisper (whisper-large-v3) ---" . PHP_EOL;
$sampleRate = 16000;
$numSamples = $sampleRate;
$freq = 440.0;
$data = '';
for ($i = 0; $i < $numSamples; $i++) {
    $t = (float)$i / $sampleRate;
    $val = (int)(sin(2 * M_PI * $freq * $t) * 16000);
    $data .= pack('v', $val);
}
$header = 'RIFF' . pack('V', 36 + strlen($data)) . 'WAVEfmt ' . pack('V', 16) . pack('v', 1) . pack('v', 1) . pack('V', $sampleRate) . pack('V', $sampleRate * 2) . pack('v', 2) . pack('v', 16) . 'data' . pack('V', strlen($data));
$tempAudio = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'unit_test_audio.wav';
file_put_contents($tempAudio, $header . $data);

$whisperService = new WhisperService();
$whisperResult = $whisperService->transcribe($tempAudio);
@unlink($tempAudio);

assertTest("Live Groq Whisper succeeds", $whisperResult['success'] === true);
assertTest("Whisper transcription text is present", !empty($whisperResult['text'] ?? ''));
assertTest("Whisper model is whisper-large-v3", ($whisperResult['model_used'] ?? '') === 'whisper-large-v3');

echo PHP_EOL . "==========================================================" . PHP_EOL;
echo "  HASIL: $passed LULUS, $failed GAGAL" . PHP_EOL;
echo "==========================================================" . PHP_EOL;
