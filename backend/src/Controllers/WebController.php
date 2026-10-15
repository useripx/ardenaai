<?php

declare(strict_types=1);

namespace Ardena\Controllers;

/**
 * WebController — Mengelola Tampilan Antarmuka Pengguna
 * 
 * Melayani request browser untuk halaman Tanya Ardena dan Riwayat Obrolan.
 */
class WebController extends BaseController
{
    /**
     * Halaman Beranda (Workstation Utama Tanya Ardena)
     */
    public function index(): void
    {
        $this->view('chat', [
            'pageTitle' => 'Tanya Ardena — Asisten AI Scratch 3',
            'appName'   => 'Ardena AI Scratch 3'
        ]);
    }

    /**
     * Halaman Chat (Alias ke Workstation Tanya Ardena)
     */
    public function chat(): void
    {
        $this->index();
    }

    /**
     * Halaman Riwayat Obrolan Siswa
     */
    public function riwayat(): void
    {
        $this->view('riwayat', [
            'pageTitle' => 'Riwayat Obrolan — Ardena AI Scratch 3',
            'appName'   => 'Ardena AI Scratch 3'
        ]);
    }

    /**
     * Halaman Masuk Siswa
     */
    public function login(): void
    {
        $this->view('login', [
            'pageTitle' => 'Masuk Siswa — Ardena AI Scratch 3',
            'appName'   => 'Ardena AI Scratch 3'
        ]);
    }

    /**
     * Halaman Pendaftaran Siswa Baru
     */
    public function daftar(): void
    {
        $this->view('daftar', [
            'pageTitle' => 'Daftar Akun Siswa — Ardena AI Scratch 3',
            'appName'   => 'Ardena AI Scratch 3'
        ]);
    }
}
