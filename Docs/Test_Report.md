# Test Report — Backend Ardena AI Scratch 3

**Fase:** Fase 10 — Pengujian Komprehensif & Keamanan  
**Tanggal:** 14 Oktober 2026 23:20 WIB  
**Status Pengujian:** 18/18 Lulus (100% PASS)  
**Lingkungan:** PHP 8.4.15 CLI / Windows Laragon

---

## 1. Ringkasan Hasil Pengujian

| Kelompok Pengujian | Target Komponen | Jumlah Tes | Hasil | Keterangan |
|---|---|---|---|---|
| **Blok Scratch 3** | `ScratchBlockValidator` | 4 | ✅ 4 Lulus | Validasi kategori Scratch 3 resmi, injeksi warna hex (#4C97FF), fallback kategori invalid, dan sanitasi XSS script. |
| **Autentikasi** | `AuthMiddleware` | 2 | ✅ 2 Lulus | Penolakan token palsu/rusak dengan `401 Unauthorized`. Parsing dev token lokal terverifikasi. |
| **Penyimpanan Riwayat** | `FirestoreRepository` | 6 | ✅ 6 Lulus | Siklus penuh CRUD: Simpan sesi, daftar sesi, ambil detail sesi, verifikasi jumlah pesan, dan hapus sesi. |
| **Groq Chat & Fallback** | `GroqService` | 4 | ✅ 4 Lulus | Inferensi live model `openai/gpt-oss-120b`, ekstraksi langkah pengerjaan, dan normalisasi blok JSON. |
| **Groq Vision Multimodal** | `VisionService` | 2 | ✅ 2 Lulus | Analisis gambar warna blok Scratch menggunakan `qwen/qwen3.8-27b` dengan kecepatan < 1 detik. |

**TOTAL: 18 LULUS, 0 GAGAL.**

---

## 2. Bukti Pengujian Live Model Groq AI

### 2.1 Pengujian 4 Tingkat Fallback Chat
Pengujian dieksekusi secara live terhadap endpoint resmi `https://api.groq.com/openai/v1/chat/completions`:
- `[Priority 1] openai/gpt-oss-120b` => **SUKSES (1354 ms)** | Respon logika Scratch bahasa Indonesia.
- `[Priority 2] qwen/qwen3.8-27b` => **SUKSES (621 ms)** | Respon ultra-cepat.
- `[Priority 3] openai/gpt-oss-20b` => **SUKSES (1121 ms)** | Alternatif ringan sukses.
- `[Priority 4] allam-2-7b` => **SUKSES (866 ms)** | Cadangan percakapan umum aktif.

### 2.2 Pengujian Multimodal Vision
Pengujian dilakukan dengan mengunggah gambar blok Scratch warna biru `#4C97FF`:
- Model: `qwen/qwen3.8-27b` via Groq LPU
- Status HTTP: `200 OK`
- Hasil Inferensi: *"Berdasarkan gambar yang Anda berikan, warna dominannya adalah biru... nuansa biru yang cukup terang dan cerah..."*
- Kesimpulan: Model terbukti 100% multimodal vision-enabled di Groq tanpa memerlukan provider eksternal lain.

---

## 3. Uji Keamanan & Proteksi Secret
- **Verifikasi Git Ignore:** Perintah `git check-ignore -v Docs/api` dan `git check-ignore -v backend/.env` terkonfirmasi aktif mengabaikan kunci rahasia.
- **Sanitasi Logging:** Log aplikasi di `backend/storage/logs/` otomatis menyensor string `api_key`, `token`, `password`, dan `secret` menjadi `***REDACTED***`.
- **Pembersihan Berkas Sementara:** Seluruh file sementara di `backend/storage/temp/` (audio dan foto unggahan siswa) langsung dihapus seketika setelah transkripsi/analisis selesai.
