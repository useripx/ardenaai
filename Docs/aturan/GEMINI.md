# GEMINI.md — Aturan Kerja AI Agent Ardena AI Scratch 3

> **Dokumen ini mengindeks panduan kerja resmi di [Docs/aturan/AGENTS.md](file:///c:/laragon/www/ardenaai/Docs/aturan/AGENTS.md).**

Setiap sesi baru atau AI Agent yang beroperasi pada repositori ini wajib mematuhi aturan kerja:

1. **ZERO-CODE Sebelum Persetujuan:** Wajib membuat dan menyajikan Implementation Plan terlebih dahulu kepada Yogi, lalu menunggu persetujuan eksplisit sebelum menyentuh kode.
2. **All-in-One Groq Provider:** Hanya menggunakan 1 `GROQ_API_KEY` (Teks `gpt-oss-120b` dengan 4x fallback, Whisper STT `whisper-large-v3`, Vision `qwen/qwen3.8-27b`). Dilarang menambah provider eksternal lain.
3. **Kerahasiaan Kunci API:** `Docs/api` dan `.env` wajib selalu berstatus diabaikan oleh `.gitignore`. Jangan pernah membocorkan kunci ke chat atau repositori Git.
4. **Proteksi Folder Material:** Folder `Material/` adalah aset desain asli dari tim Frontend yang dilarang keras diubah atau dihapus. Antarmuka terintegrasi berada di `backend/public/`.
5. **Protokol Dokumentasi Wajib:** Selalu perbarui `Docs/Task_Status.md`, `Docs/Decision_Log.md`, dan buat changelog bertimestamp: `changelog/dd/bb/yyyy/jam/menit.md`.
6. **Verifikasi Pengujian:** Selalu jalankan `php backend/tests/test_full_suite.php` dan `php backend/tests/test_mvc_suite.php` dan pastikan seluruh pengujian 100% PASS sebelum melaporkan pekerjaan selesai.
7. **Protokol Branch Git:** Seluruh update wajib masuk ke branch `develop` terlebih dahulu dan di-push ke `origin develop`. Dilarang commit/push langsung ke `main` (merge ke `main` dilakukan secara manual oleh Yogi).
8. **Pencadangan Riwayat Chat:** Wajib proaktif bertanya di setiap akhir respon untuk mencadangkan riwayat chat ke `Docs/chat/riwayat<ddmmyyyy>.md` (contoh acuan: `Docs/chat/riwayat16102026.md`).

Rincian lengkap dapat dibaca pada [Docs/aturan/AGENTS.md](file:///c:/laragon/www/ardenaai/Docs/aturan/AGENTS.md) dan [Docs/Implementation_plan.md](file:///c:/laragon/www/ardenaai/Docs/Implementation_plan.md).
