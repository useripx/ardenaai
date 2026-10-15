# Test Report — Backend Ardena AI Scratch 3

**Fase:** Fase 18 — Verifikasi Menyeluruh & Uji Regresi Akhir MVP  
**Terakhir Diperbarui:** 15 Oktober 2026 21:45 WIB  
**Status Pengujian:** 72/72 Lulus (100% PASS, 0 GAGAL)  
**Lingkungan:** PHP 8.4.15 CLI / Windows 11 Laragon / Apache 2.4

---

## 1. Ringkasan Dua Test Suite Resmi

### A. MVC Architecture Test Suite (`test_mvc_suite.php` — 51 Tes)
| No | Kelompok Pengujian | Target Komponen | Jumlah Tes | Hasil | Keterangan |
|---|---|---|---|---|---|
| 1 | **Router & Dispatcher** | `Router::class` | 4 | ✅ 4 Lulus | Normalisasi root `/`, subfolder `/ardenaai`, rute bersih, dan ekstensi legacy `.php`. |
| 2 | **Domain Models** | `Conversation` & `ScratchBlock` | 4 | ✅ 4 Lulus | Validasi entitas, injeksi warna hex resmi Scratch 3, dan perhitungan jumlah pesan. |
| 3 | **View Template Engine** | `View::class` & Templates | 6 | ✅ 6 Lulus | Pengecekan file view `chat.php`, `riwayat.php`, `login.php`, `daftar.php`, `pengaturan.php`. |
| 4 | **CRUD Riwayat Sesi** | `ConversationController` | 4 | ✅ 4 Lulus | Simpan percakapan, tampilkan daftar, ambil percakapan tunggal, dan hapus percakapan. |
| 5 | **Autentikasi Firebase** | Web Client & Views | 10 | ✅ 10 Lulus | Template login/register, Google SSO action, pill selector kelas, dan verifikasi `firebase-init.js`. |
| 6 | **Profil & Riwayat Recovery** | Settings & Chat Header | 10 | ✅ 10 Lulus | Avatar selector, TTS settings, password update form, recovery query `?id=`, dan rute `/pengaturan`. |
| 7 | **Smart JSON Recovery** | `AIResponseValidator` | 5 | ✅ 5 Lulus | Pemulihan output JSON terpotong tanpa kebocoran sintaks teknis dan ekstraksi langkah koding. |
| 8 | **Dynamic Base Path** | Multi-Host Views | 8 | ✅ 8 Lulus | Dukungan mulus dual-host `http://ardenaai.test/` dan `http://localhost/ardenaai/`. |

### B. Comprehensive Backend Test Suite (`test_full_suite.php` — 21 Tes)
| No | Kelompok Pengujian | Target Komponen | Jumlah Tes | Hasil | Keterangan |
|---|---|---|---|---|---|
| 1 | **Blok Scratch 3** | `ScratchBlockValidator` | 4 | ✅ 4 Lulus | Kategori resmi Scratch 3, hex color injection, fallback motion, dan sanitasi XSS script. |
| 2 | **Autentikasi & Token** | `AuthMiddleware` | 2 | ✅ 2 Lulus | Penolakan token palsu (401) dan validasi dev token lokal terverifikasi. |
| 3 | **Penyimpanan Firestore** | `FirestoreRepository` | 6 | ✅ 6 Lulus | Siklus penuh simpan, baca, hitung, dan hapus data per UID siswa. |
| 4 | **Live Groq Chat AI** | `GroqService` | 4 | ✅ 4 Lulus | Inferensi live model `openai/gpt-oss-120b` dengan 4-level fallback aktif. |
| 5 | **Live Groq Vision** | `VisionService` | 2 | ✅ 2 Lulus | Analisis multimodal foto blok Scratch via `qwen/qwen3.8-27b`. |
| 6 | **Live Groq Whisper** | `WhisperService` | 3 | ✅ 3 Lulus | Transkripsi biner audio via `whisper-large-v3` menggunakan cURL multipart murni. |

**TOTAL: 72 LULUS, 0 GAGAL (100% SUKSES)**

---

## 2. Bukti Pengujian Live Seluruh AI Services Groq

1. **Inferensi Chat (`openai/gpt-oss-120b`):**
   - Latensi: ~1.2 detik.
   - Output: Penjelasan koding ramah anak, daftar blok puzzle Scratch 3 tervalidasi, dan langkah pengerjaan terstruktur.
2. **Inferensi Vision Foto Blok (`qwen/qwen3.8-27b`):**
   - Latensi: ~1.1 detik.
   - Output: Deteksi warna blok puzzle Scratch 3 (biru gerak, kuning kejadian, oranye kontrol).
3. **Inferensi Speech-to-Text (`whisper-large-v3`):**
   - Latensi: ~1.3 detik.
   - Output: Transkripsi suara mikrofon siswa Bahasa Indonesia menjadi teks string bersih.

---

## 3. Uji Keamanan, Privasi & Dual-Host
- **Proteksi Secret:** `Docs/api` dan `backend/.env` terabaikan 100% oleh `.gitignore`.
- **Sanitasi Logging:** Log aplikasi otomatis menyensor kata kunci sensitif (`api_key`, `token`, `password`).
- **Pembersihan Berkas Sementara:** Seluruh berkas audio dan foto di `backend/storage/temp/` langsung dihapus otomatis (`@unlink`) setelah inferensi selesai.
- **Dual Host Compatibility:** Teruji sukses di browser Chrome tanpa error 404 pada `http://ardenaai.test/` maupun `http://localhost/ardenaai/`.
