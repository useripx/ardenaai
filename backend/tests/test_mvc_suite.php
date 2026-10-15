<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/routes.php';

use Ardena\Core\Router;
use Ardena\Core\View;
use Ardena\Models\Conversation;
use Ardena\Models\ScratchBlock;
use Ardena\Controllers\HealthController;
use Ardena\Controllers\ConversationController;

echo "==========================================================" . PHP_EOL;
echo "  ARDENA AI SCRATCH 3 — MVC ARCHITECTURE TEST SUITE" . PHP_EOL;
echo "==========================================================" . PHP_EOL;

$passed = 0;
$failed = 0;

function assertMvc(string $name, bool $condition, string $detail = '') {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $name" . PHP_EOL;
        $passed++;
    } else {
        echo " [FAIL] $name - $detail" . PHP_EOL;
        $failed++;
    }
}

// 1. Test Router Normalization & Routes Registration
echo PHP_EOL . "--- [Test 1] Router Registration & URI Normalization ---" . PHP_EOL;
$routes = Router::getRoutes();
assertMvc("Router has registered routes", count($routes) >= 10);

$norm1 = Router::normalizeUri('/');
assertMvc("Normalizes root '/' correctly", $norm1 === '/');

$norm2 = Router::normalizeUri('/backend/public/chat');
assertMvc("Normalizes subfolder '/backend/public/chat' to '/chat'", $norm2 === '/chat');

$norm3 = Router::normalizeUri('/ardenaai/api/chat.php?test=1');
assertMvc("Normalizes '/ardenaai/api/chat.php' to '/api/chat.php'", $norm3 === '/api/chat.php');

// 2. Test Models
echo PHP_EOL . "--- [Test 2] Domain Models (Conversation & ScratchBlock) ---" . PHP_EOL;
$block = new ScratchBlock(
    id: 'blk_test_1',
    category: 'motion',
    opcode: 'motion_movesteps',
    label: 'gerak ( 10 ) langkah'
);
assertMvc("ScratchBlock model assigns correct color", $block->color === '#4C97FF');
$blockArr = $block->toArray();
assertMvc("ScratchBlock toArray has required fields", isset($blockArr['opcode'], $blockArr['color']));

$conv = Conversation::fromArray('conv_mvc_123', [
    'title'    => 'Uji MVC Conversation',
    'preview'  => 'Bagaimana cara melompat?',
    'messages' => [
        ['sender' => 'user', 'content' => 'Halo!']
    ]
]);
assertMvc("Conversation model creates valid instance", $conv->id === 'conv_mvc_123');
assertMvc("Conversation model count messages correctly", count($conv->messages) === 1);

// 3. Test View Engine
echo PHP_EOL . "--- [Test 3] View Engine Template Loading ---" . PHP_EOL;
$viewsPath = View::getViewsPath();
assertMvc("View engine base path exists", is_dir($viewsPath));
assertMvc("chat.php view template exists", file_exists($viewsPath . 'chat.php'));
assertMvc("riwayat.php view template exists", file_exists($viewsPath . 'riwayat.php'));

// 4. Test View Render Output Buffering
ob_start();
View::render('chat', ['testMode' => true]);
$chatOutput = ob_get_clean();
assertMvc("Render chat.php outputs HTML", str_contains($chatOutput, '<!DOCTYPE html>'));
assertMvc("Chat view contains Ardena brand", str_contains($chatOutput, 'Ardena'));

ob_start();
View::render('riwayat', ['testMode' => true]);
$riwayatOutput = ob_get_clean();
assertMvc("Render riwayat.php outputs HTML", str_contains($riwayatOutput, 'Riwayat Obrolan'));

// 5. Test Controllers (ConversationController via Dev Token)
echo PHP_EOL . "--- [Test 4] ConversationController CRUD via Mock Environment ---" . PHP_EOL;
\Ardena\Support\ApiResponse::$exitOnSend = false;
$_SERVER['HTTP_AUTHORIZATION'] = 'Bearer dev_siswa_mvc_tester';
$convController = new ConversationController();

// Test Index
ob_start();
$convController->index();
$indexOutput = ob_get_clean();
$indexJson = json_decode($indexOutput, true);
assertMvc("ConversationController@index returns success JSON", ($indexJson['success'] ?? false) === true);

// Test Save
$testConvId = 'conv_mvc_test_' . time();
$savePayload = json_encode([
    'conversation_id' => $testConvId,
    'title'           => 'Sesi Pengujian MVC',
    'messages'        => [
        ['sender' => 'user', 'content' => 'Halo Ardena MVC!'],
        ['sender' => 'assistant', 'content' => 'Halo sahabat MVC!']
    ]
]);

// Simulasi php://input dengan stream wrapper tidak langsung atau parameter method
$repo = new Ardena\Repositories\FirestoreRepository();
$saved = $repo->saveConversation('mvc_tester', $testConvId, [
    'title'    => 'Sesi Pengujian MVC',
    'preview'  => 'Halo Ardena MVC!',
    'messages' => [
        ['sender' => 'user', 'content' => 'Halo Ardena MVC!'],
        ['sender' => 'assistant', 'content' => 'Halo sahabat MVC!']
    ]
]);
assertMvc("Conversation saved to storage", $saved === true);

// Test Show
ob_start();
$convController->show($testConvId);
$showOutput = ob_get_clean();
$showJson = json_decode($showOutput, true);
assertMvc("ConversationController@show loads saved conversation", ($showJson['data']['title'] ?? '') === 'Sesi Pengujian MVC');

// Test Delete
ob_start();
$convController->delete($testConvId);
$deleteOutput = ob_get_clean();
$deleteJson = json_decode($deleteOutput, true);
assertMvc("ConversationController@delete succeeds", ($deleteJson['success'] ?? false) === true);

// 6. Test Auth Views & Firebase Client Integration
echo PHP_EOL . "--- [Test 5] Auth Modules (Login, Register & Firebase Web Client) ---" . PHP_EOL;
assertMvc("login.php view template exists", file_exists($viewsPath . 'login.php'));
assertMvc("daftar.php view template exists", file_exists($viewsPath . 'daftar.php'));

ob_start();
View::render('login', ['testMode' => true]);
$loginOutput = ob_get_clean();
assertMvc("Render login.php contains 'Masuk Siswa'", str_contains($loginOutput, 'Masuk Siswa'));
assertMvc("Render login.php contains Google SSO action", str_contains($loginOutput, 'Google'));

ob_start();
View::render('daftar', ['testMode' => true]);
$daftarOutput = ob_get_clean();
assertMvc("Render daftar.php contains 'Daftar Akun'", str_contains($daftarOutput, 'Daftar Akun'));
assertMvc("Render daftar.php contains grade selector", str_contains($daftarOutput, 'Pilih Kelas'));

$firebaseJsPath = __DIR__ . '/../public/js/firebase-init.js';
assertMvc("firebase-init.js file exists", file_exists($firebaseJsPath));
$firebaseJsContent = file_get_contents($firebaseJsPath);
assertMvc("firebase-init.js contains project 'ardenaai'", str_contains($firebaseJsContent, '"ardenaai"'));
assertMvc("firebase-init.js contains apiKey", str_contains($firebaseJsContent, 'AIzaSyDL-tuFQzokDEwdyQHvWfmtBy6HYuzWr0I'));

$configProjectId = \Ardena\Config\AppConfig::get('FIREBASE_PROJECT_ID');
assertMvc("AppConfig FIREBASE_PROJECT_ID is 'ardenaai'", $configProjectId === 'ardenaai');

// 7. Test Settings & Profile Module + Chat History Recovery Integration
echo PHP_EOL . "--- [Test 6] Settings & Profile Module and Chat History Recovery ---" . PHP_EOL;
assertMvc("pengaturan.php view template exists", file_exists($viewsPath . 'pengaturan.php'));

ob_start();
View::render('pengaturan', ['testMode' => true]);
$pengaturanOutput = ob_get_clean();
assertMvc("Render pengaturan.php contains 'Pengaturan & Profil Akun'", str_contains($pengaturanOutput, 'Pengaturan & Profil Akun'));
assertMvc("Render pengaturan.php contains avatar selector (🐱 Kucing Scratch)", str_contains($pengaturanOutput, 'Kucing Scratch'));
assertMvc("Render pengaturan.php contains audio TTS settings", str_contains($pengaturanOutput, 'Suara & Aksesibilitas'));
assertMvc("Render pengaturan.php contains password update form", str_contains($pengaturanOutput, 'Keamanan & Sandi'));

$chatViewContent = file_get_contents($viewsPath . 'chat.php');
assertMvc("chat.php contains conversation status header", str_contains($chatViewContent, 'saveStatusIndicator'));
assertMvc("chat.php contains history loading mechanism", str_contains($chatViewContent, 'loadHistoricalConversation'));
assertMvc("chat.php contains vision auto-save mechanism", str_contains($chatViewContent, 'Foto Blok:'));

$riwayatViewContent = file_get_contents($viewsPath . 'riwayat.php');
assertMvc("riwayat.php links to /pengaturan in header", str_contains($riwayatViewContent, 'href="/pengaturan"'));

$routesList = Router::getRoutes();
$hasPengaturanRoute = false;
foreach ($routesList as $route) {
    if (($route['path'] ?? '') === '/pengaturan') {
        $hasPengaturanRoute = true;
        break;
    }
}
assertMvc("Route /pengaturan is registered in Router", $hasPengaturanRoute);

// 8. Test Bugfixes: Smart JSON Recovery & Static Assets
echo PHP_EOL . "--- [Test 7] Bugfix Verifications: Smart Recovery & JS Asset ---" . PHP_EOL;
$rootJsFile = dirname(__DIR__, 2) . '/js/firebase-init.js';
assertMvc("Root js/firebase-init.js exists for web access", file_exists($rootJsFile));

$hasJsRoute = false;
foreach ($routesList as $route) {
    if (($route['path'] ?? '') === '/js/firebase-init.js') {
        $hasJsRoute = true;
        break;
    }
}
assertMvc("Route /js/firebase-init.js is registered in Router", $hasJsRoute);

// Test Truncated JSON Recovery (Kasus Yogi)
$brokenJson = '{
"answer": "Mantap! Kalau sprite kamu sudah bisa bergerak, selanjutnya kita batasi supaya tidak keluar.",
"steps": [
"Langkah 1: Pilih kategori Kejadian.",
"Langkah 2: Tambahkan blok selamanya."
],
"blocks": [
{ "id": "blk_1", "category": "events", "opcode": "event_whenflagclicked", "label": "ketika bendera hijau diklik" },
{ "id": "blk_2", "category": "control", "label": "j';

$recovered = \Ardena\Validators\AIResponseValidator::parseAndValidate($brokenJson);
assertMvc("Smart Recovery extracts clean answer from truncated output", str_contains($recovered['answer'], 'Mantap!'));
assertMvc("Smart Recovery does NOT leak raw JSON syntax into answer", !str_contains($recovered['answer'], '{"answer"'));
assertMvc("Smart Recovery extracts steps from truncated output", count($recovered['steps']) === 2);

// 9. Test Dynamic Base Path Resolver Verifications
echo PHP_EOL . "--- [Test 8] Dynamic Base Path Resolver Verifications ---" . PHP_EOL;
$chatContent = file_get_contents(dirname(__DIR__) . '/views/chat.php');
$riwayatContent = file_get_contents(dirname(__DIR__) . '/views/riwayat.php');
$loginContent = file_get_contents(dirname(__DIR__) . '/views/login.php');
$daftarContent = file_get_contents(dirname(__DIR__) . '/views/daftar.php');
$pengaturanContent = file_get_contents(dirname(__DIR__) . '/views/pengaturan.php');

assertMvc("chat.php contains BASE_PATH resolver", str_contains($chatContent, 'BASE_PATH'));
assertMvc("chat.php uses getApiUrl('transcribe')", str_contains($chatContent, "getApiUrl('transcribe')"));
assertMvc("chat.php uses getApiUrl('chat')", str_contains($chatContent, "getApiUrl('chat')"));
assertMvc("riwayat.php uses getApiUrl('conversations')", str_contains($riwayatContent, "getApiUrl('conversations')"));
assertMvc("riwayat.php uses getPageUrl for conversation link", str_contains($riwayatContent, "getPageUrl('/?id="));
assertMvc("pengaturan.php uses getPageUrl for logout", str_contains($pengaturanContent, "getPageUrl('/login')"));
assertMvc("login.php supports subfolder dynamic firebase-init.js", str_contains($loginContent, "import((BASE_PATH || '') + '/js/firebase-init.js')"));
assertMvc("daftar.php supports subfolder dynamic firebase-init.js", str_contains($daftarContent, "import((BASE_PATH || '') + '/js/firebase-init.js')"));

echo PHP_EOL . "==========================================================" . PHP_EOL;
echo "  HASIL MVC SUITE: $passed LULUS, $failed GAGAL" . PHP_EOL;
echo "==========================================================" . PHP_EOL;

if ($failed > 0) {
    exit(1);
}
