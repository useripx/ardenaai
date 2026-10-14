# AGENTS.md — Panduan Standar & Aturan Kerja AI Agent

> **PANDUAN RESMI TELAH DITATA RAPI DI:**  
> 📖 **[Docs/aturan/AGENTS.md](file:///c:/laragon/www/ardenaai/Docs/aturan/AGENTS.md)**

Seluruh AI Agent atau Chat Baru yang beroperasi pada repositori **Ardena AI Scratch 3** wajib membaca dan mematuhi dokumen acuan master di atas.

### Enam Aturan Inti:
1. **ZERO-CODE Sebelum Persetujuan:** Wajib menyusun Implementation Plan dan memperoleh persetujuan eksplisit dari Yogi sebelum menyentuh kode.
2. **All-in-One Groq Architecture:** Hanya menggunakan 1 `GROQ_API_KEY` (Teks `gpt-oss-120b` dengan fallback 4 tingkat, Whisper STT `whisper-large-v3`, Vision `qwen/qwen3.8-27b`). Dilarang menambah provider eksternal lain.
3. **Kerahasiaan Kunci API:** `Docs/api` dan `.env` wajib selalu berstatus diabaikan oleh `.gitignore`. Jangan pernah membocorkan kunci ke chat atau repositori Git.
4. **Proteksi Folder Material:** Folder `Material/` adalah aset desain asli dari tim Frontend yang dilarang keras diubah atau dihapus. Antarmuka terintegrasi berada di `backend/public/`.
5. **Protokol Dokumentasi Wajib:** Selalu perbarui `Docs/Task_Status.md`, `Docs/Decision_Log.md`, dan buat changelog bertimestamp: `changelog/dd/bb/yyyy/jam/menit.md`.
6. **Verifikasi Pengujian:** Selalu jalankan `php backend/tests/test_full_suite.php` dan pastikan seluruh pengujian 100% PASS sebelum melaporkan pekerjaan selesai.
