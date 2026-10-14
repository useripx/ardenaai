# AGENTS.md — Panduan Standar & Aturan Kerja AI Agent / Chat Baru

> **PENTING UNTUK SETIAP AI AGENT / CHAT BARU:**  
> Dokumen ini adalah acuan instruksi kerja wajib (*mandatory instructions*) untuk proyek **Ardena AI Scratch 3**. Sebelum melakukan tindakan apa pun, Anda **WAJIB membaca dan mematuhi seluruh aturan emas di bawah ini secara ketat tanpa terkecuali**.

---

## 1. Identitas & Ruang Lingkup Proyek

- **Nama Proyek:** Ardena AI Scratch 3
- **Tujuan:** Asisten AI ramah anak SD (usia 7–12 tahun) untuk pembelajaran koding visual Scratch 3.
- **Peran Anda:** Backend Developer (Pair Programming dengan Pengguna: **Yogi**).
- **Teknologi Backend:** **PHP 8.4 Native Modern** (Object-Oriented, Separation of Concerns, tanpa framework berat).
- **Repositori Resmi:** `https://github.com/useripx/ardenaai.git`
- **Acuan Master Dokumen:** [Docs/Implementation_plan.md](file:///c:/laragon/www/ardenaai/Docs/Implementation_plan.md)

---

## 2. Enam Aturan Emas (Golden Rules) yang Tidak Boleh Dilanggar

### 🛑 Aturan 1: ZERO-CODE Sebelum Persetujuan Rencana (Approval Gate)
- **JANGAN PERNAH langsung menyentuh, membuat, atau mengubah kode program sebelum membuat rencana kerja tertulis dan disetujui oleh Yogi.**
- Setiap kali Yogi memberikan tugas atau permintaan baru:
  1. Pelajari konteks dan periksa dokumen terkait.
  2. Susun dokumen rencana implementasi (*Implementation Plan*) atau artifact rencana secara terperinci.
  3. Minta persetujuan eksplisit dari Yogi (tunggu hingga pengguna menyetujui / klik *Proceed*).
  4. Baru mulai bekerja setelah persetujuan diberikan.

### 🛑 Aturan 2: Arsitektur All-in-One Groq (Opsi A Dikunci)
- Seluruh modul AI murni menggunakan **Groq Cloud API** tunggal (`GROQ_API_KEY`).
- **DILARANG** menambahkan OpenRouter atau provider eksternal lain kecuali diminta secara eksplisit oleh Yogi.
- Kunci API disediakan secara lokal di file `Docs/api` dan dimuat ke `backend/.env`.
- **DILARANG KERAS MENGEKSPOS KUNCI API KE CHAT, CODEBASE PUBLIK, ATAU GIT.**
- Pastikan file `Docs/api` dan `backend/.env` selalu berstatus terabaikan oleh `.gitignore`.

### 🛑 Aturan 3: Mekanisme Multi-Model & Fallback 4 Tingkat
- **Chat Teks & Logika Scratch:**
  - Model Utama: `openai/gpt-oss-120b` (Priority 1).
  - Jika terjadi status HTTP `429` (*Rate Limit / Quota Exceeded*), sistem secara otomatis beralih berurutan:
    * Priority 2: `qwen/qwen3.8-27b`
    * Priority 3: `openai/gpt-oss-20b`
    * Priority 4: `allam-2-7b`
- **Analisis Foto Blok Scratch (Vision/OCR):**
  - Menggunakan model multimodal vision Groq: **`qwen/qwen3.8-27b`**.
  - Telah teruji mengenali warna blok Scratch dalam bahasa Indonesia secara instan (< 1 detik).
- **Speech-to-Text (STT):**
  - Menggunakan model Groq: **`whisper-large-v3`** (prioritas Bahasa Indonesia `language=id`).

### 🛑 Aturan 4: Perlindungan Integritas Template Frontend (`Material/`)
- Folder [Material/](file:///c:/laragon/www/ardenaai/Material) berisi aset asli dari tim Frontend.
- **DILARANG MENGUBAH, MENIMPA, ATAU MENGHAPUS BERKAS DI DALAM FOLDER `Material/`.**
- Seluruh halaman web terintegrasi berada di `backend/public/`:
  - `backend/public/chat.html` (Workstation interaktif Tanya Ardena).
  - `backend/public/riwayat.html` (Daftar riwayat obrolan siswa).
  - `backend/public/index.html` (Entry point aplikasi).

### 🛑 Aturan 5: Protokol Dokumentasi Wajib & Changelog Bertingkat
Setiap selesai melakukan kelompok perubahan kode atau konfigurasi, AI Agent **wajib** memperbarui rangkaian dokumentasi berikut:
1. **Changelog Bertingkat:** Wajib membuat berkas baru dengan pola:
   `changelog/dd/bb/yyyy/jam/menit.md` (menggunakan zona waktu WIB proyek).  
   *Contoh:* `changelog/14/10/2026/23/20.md`.
2. **`Docs/Task_Status.md`:** Perbarui persentase progres dan status checklist tugas yang telah selesai.
3. **`Docs/Decision_Log.md`:** Catat setiap keputusan arsitektur, parameter baru, atau pertanyaan yang dijawab pengguna.
4. **`Docs/Test_Report.md`:** Perbarui bukti pengujian jika ada penambahan atau perubahan fitur.

### 🛑 Aturan 6: Wajib Menjalankan Pengujian Otomatis
- Jangan pernah mengklaim suatu pekerjaan selesai sebelum benar-benar menjalankan verifikasi pengujian.
- Jalankan suite pengujian komprehensif:
  ```bash
  php backend/tests/test_full_suite.php
  ```
- Pastikan seluruh tes berstatus **PASS (100%)** tanpa ada kegagalan.

---

## 3. Struktur Direktori Inti yang Wajib Dipahami

```text
ardenaai/
├── assets/logo.png                  # Logo resmi proyek
├── backend/
│   ├── public/api/                  # Endpoint API resmi (health, chat, transcribe, vision, conversations)
│   ├── public/                      # Web UI terintegrasi (chat.html, riwayat.html)
│   ├── src/Config/AppConfig.php     # Parser .env mandiri
│   ├── src/Middleware/              # AuthMiddleware (JWT RS256) & CorsMiddleware
│   ├── src/Services/                # GroqService, WhisperService, VisionService
│   ├── src/Validators/              # ScratchBlockValidator & AIResponseValidator
│   ├── src/Repositories/          # FirestoreRepository
│   ├── src/Support/                 # ApiResponse, Logger, HttpClient
│   ├── storage/                     # Logs & Temporary files
│   ├── tests/test_full_suite.php    # Test suite utama
│   └── .env                         # Konfigurasi aktif (TIDAK DITRACK GIT)
├── Docs/                            # Seluruh dokumentasi formal proyek
│   └── aturan/                      # Folder aturan kerja standar AI Agent
│       ├── AGENTS.md                # Dokumen ini
│       └── GEMINI.md
├── Material/                        # Template UI tim frontend asli (JANGAN DIUBAH)
├── changelog/                       # Catatan riwayat bertimestamp
├── AGENTS.md                        # Pointer indeks aturan kerja AI
└── README.md                        # Dokumentasi utama proyek
```

---

## 4. Alur Kerja Standar Agen saat Menangani Tugas Baru

```mermaid
graph TD
    A["Menerima Request dari Yogi"] --> B["Analisis Kebutuhan & Periksa Kode Eksisting"]
    B --> C["Susun Implementation Plan (Dokumen / Artifact)"]
    C --> D["Minta Persetujuan Eksplisit Yogi (Approval Gate)"]
    D -->|Belum Disetujui| C
    D -->|Disetujui / Proceed| E["Mulai Eksekusi Kode Bertahap"]
    E --> F["Jalankan php backend/tests/test_full_suite.php"]
    F -->|Ada Gagal| E
    F -->|Semua Lulus (100% PASS)| G["Buat changelog/dd/bb/yyyy/jam/menit.md"]
    G --> H["Perbarui Task_Status.md & Decision_Log.md"]
    H --> I["Lapor ke Yogi dengan Bukti Pengujian"]
```

Jika Anda mematuhi seluruh pedoman di atas, proyek akan selalu terjaga kerapiannya, aman, andal, dan bekerja secara harmonis sesuai harapan Yogi.
