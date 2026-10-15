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

echo PHP_EOL . "==========================================================" . PHP_EOL;
echo "  HASIL MVC SUITE: $passed LULUS, $failed GAGAL" . PHP_EOL;
echo "==========================================================" . PHP_EOL;

if ($failed > 0) {
    exit(1);
}
