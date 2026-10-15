<?php

declare(strict_types=1);

/**
 * Route Definitions — Ardena AI Scratch 3 MVC
 * 
 * Mendaftarkan rute web dan API ke Ardena\Core\Router.
 */

use Ardena\Core\Router;
use Ardena\Controllers\WebController;
use Ardena\Controllers\ChatController;
use Ardena\Controllers\VisionController;
use Ardena\Controllers\TranscribeController;
use Ardena\Controllers\ConversationController;
use Ardena\Controllers\HealthController;
use Ardena\Controllers\AdminController;

// 1. Web Views (Antarmuka Pengguna)
Router::get('/', [WebController::class, 'index']);
Router::get('/chat', [WebController::class, 'chat']);
Router::get('/chat.html', [WebController::class, 'chat']);
Router::get('/riwayat', [WebController::class, 'riwayat']);
Router::get('/riwayat.html', [WebController::class, 'riwayat']);
Router::get('/login', [WebController::class, 'login']);
Router::get('/login.html', [WebController::class, 'login']);
Router::get('/daftar', [WebController::class, 'daftar']);
Router::get('/daftar.html', [WebController::class, 'daftar']);
Router::get('/register', [WebController::class, 'daftar']);
Router::get('/pengaturan', [WebController::class, 'pengaturan']);
Router::get('/pengaturan.html', [WebController::class, 'pengaturan']);
Router::get('/settings', [WebController::class, 'pengaturan']);
Router::get('/admin', [WebController::class, 'admin']);
Router::get('/admin.html', [WebController::class, 'admin']);
Router::get('/guru', [WebController::class, 'admin']);

// Static Asset Helper — Melayani skrip modul Firebase Auth
Router::get('/js/firebase-init.js', function () {
    $file = dirname(__DIR__) . '/public/js/firebase-init.js';
    if (file_exists($file)) {
        header('Content-Type: application/javascript; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');
        readfile($file);
        exit;
    }
    http_response_code(404);
    echo "File not found";
    exit;
});

// 2. API Endpoints (Mendukung pola URL bersih dan ekstensi .php untuk kompatibilitas penuh)
Router::post('/api/chat', [ChatController::class, 'chat']);
Router::post('/api/chat.php', [ChatController::class, 'chat']);

Router::post('/api/vision', [VisionController::class, 'analyze']);
Router::post('/api/vision.php', [VisionController::class, 'analyze']);

Router::post('/api/transcribe', [TranscribeController::class, 'transcribe']);
Router::post('/api/transcribe.php', [TranscribeController::class, 'transcribe']);

Router::get('/api/conversations', [ConversationController::class, 'index']);
Router::get('/api/conversations.php', [ConversationController::class, 'index']);

Router::get('/api/conversation', [ConversationController::class, 'show']);
Router::get('/api/conversation.php', [ConversationController::class, 'show']);
Router::get('/api/conversation/{id}', [ConversationController::class, 'show']);

Router::post('/api/conversation-save', [ConversationController::class, 'save']);
Router::post('/api/conversation-save.php', [ConversationController::class, 'save']);

Router::delete('/api/conversation-delete', [ConversationController::class, 'delete']);
Router::delete('/api/conversation-delete.php', [ConversationController::class, 'delete']);
Router::delete('/api/conversation/{id}', [ConversationController::class, 'delete']);
Router::post('/api/conversation-delete.php', [ConversationController::class, 'delete']);

Router::get('/api/health', [HealthController::class, 'check']);
Router::get('/api/health.php', [HealthController::class, 'check']);

// 3. Admin & Classroom API Endpoints (Fase 19: Guru Pembimbing SD)
Router::get('/api/admin/stats', [AdminController::class, 'stats']);
Router::get('/api/admin/stats.php', [AdminController::class, 'stats']);
Router::get('/api/admin/students', [AdminController::class, 'students']);
Router::get('/api/admin/students.php', [AdminController::class, 'students']);
Router::post('/api/admin/reset-password', [AdminController::class, 'resetPassword']);
Router::post('/api/admin/reset-password.php', [AdminController::class, 'resetPassword']);
Router::post('/api/admin/clear-lab-sessions', [AdminController::class, 'clearLabSessions']);
Router::post('/api/admin/clear-lab-sessions.php', [AdminController::class, 'clearLabSessions']);
