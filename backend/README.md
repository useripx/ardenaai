# Backend Ardena AI Scratch 3

Backend layanan untuk **Ardena AI Scratch 3** — Asisten kecerdasan buatan ramah anak untuk pembelajaran pemrograman visual Scratch 3, dibangun dengan **PHP Native Modern (PHP 8.4)** dan terhubung ke **Groq Cloud API (All-in-One AI Engine)**.

---

## 🌟 Fitur Utama

1. **Percakapan Cerdas Scratch 3 (`/api/chat.php`):**
   - Didukung model utama `openai/gpt-oss-120b` via Groq LPU.
   - **Mekanisme Auto-Fallback 4 Tingkat:** Beralih mulus saat HTTP 429 (`gpt-oss-120b` -> `qwen3.8-27b` -> `gpt-oss-20b` -> `allam-2-7b`).
   - Format respons JSON tervalidasi dengan puzzle blok Scratch 3 berwarna.
2. **Input Suara Siswa (`/api/transcribe.php`):**
   - Transkripsi suara rekaman mikrofon menjadi teks menggunakan `whisper-large-v3` via Groq Audio API.
   - Pembersihan berkas audio sementara otomatis setelah pemrosesan demi privasi anak.
3. **Pemeriksaan Foto Blok Kodingan (`/api/vision.php`):**
   - Menganalisis tangkapan layar atau foto blok Scratch menggunakan multimodal vision Groq `qwen/qwen3.8-27b`.
   - Mendeteksi kategori blok dan memberikan rekomendasi perbaikan kodingan.
4. **Riwayat Percakapan Siswa (`/api/conversations.php`):**
   - Mengelola riwayat obrolan per siswa berbasis ID pengguna Firebase (`uid`).
5. **Keamanan & Kepatuhan Privasi:**
   - Tidak ada kunci API di sisi frontend.
   - Safe Logging: Tidak pernah mencatat kunci API, token, atau kata sandi ke dalam log file.

---

## 🚀 Persyaratan Sistem

- PHP 8.2 atau lebih baru (Rekomendasi: **PHP 8.4**)
- Ekstensi PHP: `curl`, `json`, `openssl`, `mbstring`, `fileinfo`, `gd`
- Web Server: Laragon (Apache / Nginx) atau PHP Built-in Server

---

## ⚙️ Panduan Instalasi Lokal

1. **Salin File Konfigurasi:**
   ```bash
   cp backend/.env.example backend/.env
   ```
2. **Konfigurasikan API Key:**
   Buka file `backend/.env` dan pastikan kunci Groq Anda terisi:
   ```ini
   GROQ_API_KEY=gsk_your_groq_api_key_here
   ```
3. **Jalankan Pengujian Otomatis:**
   ```bash
   # Uji unit & router MVC
   php backend/tests/test_mvc_suite.php

   # Uji integrasi penuh & AI
   php backend/tests/test_full_suite.php
   ```
4. **Menjalankan Server Lokal:**
   - **Melalui Laragon:**
     Langsung buka:
     👉 `http://ardenaai.test/` (Halaman utama Tanya Ardena)  
     👉 `http://ardenaai.test/riwayat` (Halaman riwayat obrolan)
   - **Melalui PHP Built-in Server:**
     ```bash
     php -S localhost:8000 index.php
     ```
     Lalu buka browser di `http://localhost:8000/`.

---

## 📡 Daftar Endpoint API

| Method | Endpoint | Fungsi |
|---|---|---|
| `GET` | `/api/health.php` | Pemeriksaan status backend dan konektivitas Groq |
| `POST` | `/api/chat.php` | Percakapan teks AI dengan auto-fallback 4 tingkat |
| `POST` | `/api/transcribe.php` | Mengubah rekaman audio menjadi teks (Whisper) |
| `POST` | `/api/vision.php` | Analisis foto blok kodingan Scratch (Vision) |
| `GET` | `/api/conversations.php` | Mengambil daftar riwayat sesi obrolan |
| `GET` | `/api/conversation.php?id=...` | Mengambil detail 1 sesi obrolan |
| `POST` | `/api/conversation-save.php` | Menyimpan sesi obrolan |
| `DELETE` | `/api/conversation-delete.php?id=...`| Menghapus sesi obrolan |
