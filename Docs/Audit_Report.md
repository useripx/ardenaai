# Audit Report — Ardena AI Scratch 3

**Fase:** Fase 0 — Audit dan Pemahaman Proyek  
**Tanggal:** 14 Oktober 2026  
**Auditor / Backend Developer:** Antigravity AI  
**Status Audit:** Selesai (Lolos Verifikasi)

---

## 1. Kondisi Aktual Lingkungan Pengembangan

| Parameter | Hasil Audit Aktual | Evaluasi & Status |
|---|---|---|
| **Sistem Operasi** | Windows (Lingkungan Laragon) | Siap & Kompatibel |
| **PHP Runtime** | PHP 8.4.15 (cli) (built Nov 18 2025) | Sangat baik; mendukung fitur PHP 8.4 modern (typed properties, readonly, cURL, OpenSSL, match expression, JSON validation). |
| **Composer** | Composer version 2.9.2 (2025-11-19) | Siap digunakan untuk manajemen paket dependensi backend jika diperlukan. |
| **Git Repositori** | Diinisialisasi (`master`), Remote: `https://github.com/useripx/ardenaai.git` | Terhubung. `.gitignore` telah dipasang untuk melindungi secret. |
| **Proteksi Secret** | `Docs/api` terkonfirmasi diabaikan oleh Git (`.gitignore:6:Docs/api*`) | **Lulus Audit Keamanan**: Tidak ada risiko kebocoran kunci API ke repositori publik. |

---

## 2. Inventarisasi Aset Folder Material (Template Frontend)

Tim Frontend telah menyediakan template HTML lengkap dan spesifikasi desain di folder `Material/`:

1. **`Material/login_ardena_scratch_3`**
   - Berkas: `code.html` (23.7 KB), `screen.png`.
   - Fungsi: Halaman autentikasi siswa (email & password).
   - Kebutuhan Backend: Integrasi Firebase Auth Client -> Verifikasi Firebase ID Token pada backend.

2. **`Material/daftar_akun_ardena_scratch_3`**
   - Berkas: `code.html` (31.7 KB), `screen.png`.
   - Fungsi: Registrasi akun siswa (nama lengkap, email, password, kelas SD 1-6).
   - Kebutuhan Backend: Penyimpanan profil siswa awal ke Cloud Firestore (`/users/{uid}`).

3. **`Material/stage_tanya_ardena_asisten_ai_scratch_3`**
   - Berkas: `code.html` (36.8 KB), `screen.png`.
   - Fungsi: Workstation percakapan interaktif Ardena AI Scratch 3.
   - Fitur Input:
     - Teks & Quick Chips (pertanyaan cepat blok koding).
     - Input Suara (rekaman audio mikrofon).
     - Upload Gambar (foto tangkapan layar blok Scratch).
   - Fitur Output:
     - Bubble percakapan ramah anak SD.
     - Puzzle visual Scratch Blocks (warna kategori Scratch 3: Motion, Looks, Sound, Events, Control, Variables).
   - Kebutuhan Backend:
     - `POST /api/chat.php` (Groq Chat + Auto-Fallback 4 Tingkat).
     - `POST /api/transcribe.php` (Groq Whisper-large-v3).
     - `POST /api/vision.php` (Groq Llama-3.2-Vision).

4. **`Material/riwayat_obrolan_ardena_scratch_3`**
   - Berkas: `code.html` (37.1 KB), `screen.png`.
   - Fungsi: Daftar riwayat obrolan terdahulu siswa.
   - Kebutuhan Backend: `GET /api/conversations.php`, `GET /api/conversation.php?id=...`, `DELETE /api/conversation-delete.php?id=...`.

5. **`Material/pengaturan_akun_ardena_scratch_3`**
   - Berkas: `code.html` (37.2 KB), `screen.png`.
   - Fungsi: Preferensi akun, avatar siswa, setelan ukuran teks, dan volume suara.
   - Kebutuhan Backend: Penyimpanan profil dan preferensi di Firestore.

6. **`Material/ardena_scratch_assistant/DESIGN.md`**
   - Dokumen token desain: Palet warna resmi Scratch 3 (Motion `#4C97FF`, Looks `#9966FF`, Sound `#CF63CF`, Events `#FFBF00`, Control `#FFAB19`, Operators `#59C059`, Sensing `#5CB1D6`, Variables `#FF8C1A`), tipografi Nunito Sans, dan elevasi bevel drop-offset.

---

## 3. Identifikasi Risiko & Mitigasi

| Risiko | Dampak | Mitigasi yang Ditetapkan |
|---|---|---|
| **Kebocoran API Key ke Git** | Akun terblokir atau kuota disalahgunakan pihak ketiga | `.gitignore` langsung dikonfigurasi aktif sebelum commit perdana. File `Docs/api` dan `.env` terisolasi 100% secara lokal. |
| **Groq Rate Limit (HTTP 429)** | Siswa mengalami kegagalan respons di tengah belajar | Mekanisme Auto-Fallback 4 Tingkat di backend (`gpt-oss-120b` -> `qwen3.8-27b` -> `gpt-oss-20b` -> `allam-2-7b`). |
| **Output Blok Rusak / Tidak Valid** | Antarmuka Scratch Blocks gagal me-render puzzle | Validator `ScratchBlockValidator.php` menyaring dan memvalidasi JSON AI sebelum dikirim ke frontend. |
| **Akses Lintas Pengguna** | Siswa melihat riwayat obrolan siswa lain | Verifikasi kepemilikan data (`uid` dari Firebase ID Token yang tervalidasi secara kriptografis). |

---

## 4. Kesimpulan Audit
Lingkungan pengembangan dan aset frontend telah memenuhi seluruh syarat untuk memulai Fase 1 (Konfirmasi Kontrak API) dan Fase 2 (Fondasi Backend PHP Native).
