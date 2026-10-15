<?php

declare(strict_types=1);

namespace Ardena\Controllers;

use Ardena\Config\AppConfig;
use Ardena\Middleware\AuthMiddleware;
use Ardena\Support\Logger;

/**
 * AdminController — Modul Guru Pembimbing & Administrator Lab Komputer SD
 * 
 * Melayani endpoint otorisasi khusus guru untuk mengelola akun siswa,
 * One-Click Classroom Password Reset, dan pemutusan sesi komputer lab.
 */
class AdminController extends BaseController
{
    private string $storageFile;

    public function __construct()
    {
        $storageDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'data';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        $this->storageFile = $storageDir . DIRECTORY_SEPARATOR . 'classroom_students.json';
        $this->initDefaultStudentsIfEmpty();
    }

    /**
     * GET /api/admin/stats
     * Mengambil statistik lab komputer dan kelas Scratch
     */
    public function stats(): void
    {
        $admin = AuthMiddleware::requireAdmin();
        $students = $this->loadStudents();

        $stats = [
            'classroom_code'   => AppConfig::get('CLASSROOM_CODE') ?: 'SCRATCH-4B-2026',
            'teacher_name'      => $admin['name'] ?? 'Ibu Sarah, S.Pd',
            'teacher_email'     => $admin['email'] ?? 'guru.sarah@ardena.test',
            'default_password'  => AppConfig::get('DEFAULT_STUDENT_PASSWORD') ?: 'scratch123',
            'total_students'    => count($students),
            'active_in_lab'     => count(array_filter($students, fn($s) => ($s['status'] ?? '') === 'online')),
            'lab_room'          => 'Lab Komputer SD-04',
        ];

        $this->json($stats, 'Statistik kelas berhasil diambil');
    }

    /**
     * GET /api/admin/students
     * Mengambil daftar seluruh siswa di kelas koding
     */
    public function students(): void
    {
        AuthMiddleware::requireAdmin();
        $students = $this->loadStudents();

        // Urutkan siswa berdasarkan nama
        usort($students, fn($a, $b) => strcmp($a['name'], $b['name']));

        $this->json([
            'students' => $students,
            'total'    => count($students),
        ], 'Daftar siswa berhasil diambil');
    }

    /**
     * POST /api/admin/reset-password
     * One-Click Classroom Reset untuk mereset kata sandi siswa ke default kelas
     */
    public function resetPassword(): void
    {
        $admin = AuthMiddleware::requireAdmin();
        $body = $this->getJsonBody();

        $studentUid = trim((string)($body['student_uid'] ?? ''));
        $studentEmail = trim((string)($body['student_email'] ?? ''));
        $defaultPassword = AppConfig::get('DEFAULT_STUDENT_PASSWORD') ?: 'scratch123';
        $newPassword = trim((string)($body['new_password'] ?? $defaultPassword));

        if (empty($studentUid) && empty($studentEmail)) {
            $this->error('VALIDATION_ERROR', 'ID siswa atau email wajib disertakan untuk melakukan reset kata sandi.', 422);
            return;
        }

        $students = $this->loadStudents();
        $found = false;
        $updatedStudent = null;

        foreach ($students as &$student) {
            if (($studentUid && ($student['uid'] ?? '') === $studentUid) || 
                ($studentEmail && strtolower($student['email'] ?? '') === strtolower($studentEmail))) {
                
                $student['password_reset_at'] = date('c');
                $student['last_reset_by'] = $admin['name'] ?? 'Guru Pembimbing';
                $student['status'] = 'offline'; // Sesi lama terputus untuk keamanan
                $found = true;
                $updatedStudent = $student;
                break;
            }
        }

        if (!$found) {
            $this->error('NOT_FOUND', 'Siswa tidak ditemukan dalam daftar kelas.', 404);
            return;
        }

        $this->saveStudents($students);

        Logger::info("Guru {$admin['name']} mereset kata sandi siswa {$updatedStudent['name']} ({$updatedStudent['email']})");

        $this->json([
            'student_uid'     => $updatedStudent['uid'],
            'student_name'    => $updatedStudent['name'],
            'student_email'   => $updatedStudent['email'],
            'new_password'    => $newPassword,
            'reset_timestamp' => $updatedStudent['password_reset_at'],
        ], "Kata sandi {$updatedStudent['name']} berhasil direset ke '{$newPassword}'! 🔑 Siswa sudah bisa masuk kembali.");
    }

    /**
     * POST /api/admin/clear-lab-sessions
     * Memutus sesi komputer lab siswa yang lupa logout
     */
    public function clearLabSessions(): void
    {
        $admin = AuthMiddleware::requireAdmin();
        $body = $this->getJsonBody();
        $studentUid = trim((string)($body['student_uid'] ?? ''));

        $students = $this->loadStudents();
        $clearedCount = 0;

        foreach ($students as &$student) {
            if (empty($studentUid) || ($student['uid'] ?? '') === $studentUid) {
                if (($student['status'] ?? '') === 'online') {
                    $student['status'] = 'offline';
                    $student['last_active'] = date('c');
                    $clearedCount++;
                }
            }
        }

        $this->saveStudents($students);

        $msg = empty($studentUid)
            ? "Seluruh {$clearedCount} sesi komputer lab siswa berhasil dibersihkan! 🚪"
            : "Sesi komputer lab untuk siswa bersangkutan berhasil dikeluarkan! 🚪";

        Logger::info("Guru {$admin['name']} membersihkan sesi lab ({$clearedCount} akun terdampak)");

        $this->json([
            'cleared_sessions' => $clearedCount,
            'cleared_at'       => date('c'),
        ], $msg);
    }

    // --- Helper Penyimpanan Data Siswa Kelas ---

    private function loadStudents(): array
    {
        if (!file_exists($this->storageFile)) {
            return [];
        }
        $content = file_get_contents($this->storageFile);
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function saveStudents(array $students): void
    {
        file_put_contents(
            $this->storageFile,
            json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    private function initDefaultStudentsIfEmpty(): void
    {
        if (file_exists($this->storageFile)) {
            return;
        }

        // Daftar awal siswa kelas 4B SD untuk simulasi lab sekolah
        $defaultStudents = [
            [
                'uid' => 'std_budi_01',
                'name' => 'Budi Pratama',
                'email' => 'budi.pratama@ardena.test',
                'grade' => 'Kelas 4 SD (Fase B)',
                'avatar' => '🐱',
                'status' => 'online',
                'last_active' => date('c', time() - 180),
                'active_project' => 'Game Labirin Kucing Tangkap Apel',
            ],
            [
                'uid' => 'std_siti_02',
                'name' => 'Siti Aisyah',
                'email' => 'siti.aisyah@ardena.test',
                'grade' => 'Kelas 4 SD (Fase B)',
                'avatar' => '🐶',
                'status' => 'online',
                'last_active' => date('c', time() - 360),
                'active_project' => 'Animasi Roket Luar Angkasa',
            ],
            [
                'uid' => 'std_dimas_03',
                'name' => 'Dimas Anggara',
                'email' => 'dimas.anggara@ardena.test',
                'grade' => 'Kelas 4 SD (Fase B)',
                'avatar' => '🚀',
                'status' => 'offline',
                'last_active' => date('c', time() - 7200),
                'active_project' => 'Game Lompat Rintangan Dinosaurus',
            ],
            [
                'uid' => 'std_annisa_04',
                'name' => 'Annisa Rahma',
                'email' => 'annisa.rahma@ardena.test',
                'grade' => 'Kelas 4 SD (Fase B)',
                'avatar' => '🌟',
                'status' => 'offline',
                'last_active' => date('c', time() - 86400),
                'active_project' => 'Kartu Ucapan Interaktif Scratch',
            ],
            [
                'uid' => 'std_kevin_05',
                'name' => 'Kevin Alfarizi',
                'email' => 'kevin.alfarizi@ardena.test',
                'grade' => 'Kelas 4 SD (Fase B)',
                'avatar' => '🦁',
                'status' => 'online',
                'last_active' => date('c', time() - 60),
                'active_project' => 'Simulasi Mobil Balap Bergerak X/Y',
            ],
        ];

        $this->saveStudents($defaultStudents);
    }
}
