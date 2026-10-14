# Implementation Plan — Backend Ardena AI Scratch 3 (Revisi Final 1.3)

**Peran:** Backend Developer  
**Klien / Pengguna:** Yogi (Ardena AI Team)  
**Status Dokumen:** Menunggu Persetujuan Final (Approval Gate) — *Belum menyentuh kode aplikasi*  
**Tanggal Penetapan:** 14 Oktober 2026  
**Repository GitHub Resmi:** [https://github.com/useripx/ardenaai.git](https://github.com/useripx/ardenaai.git)  
**Keputusan Arsitektur AI:** **Opsi A — All-in-One Groq** (1 Provider Tunggal untuk Chat, Whisper, & Vision)  
**Penyedia API Key Lokal:** Telah disediakan oleh Yogi di file `Docs/api`  
**Referensi Utama:** [Docs/Implementation_plan.md](file:///c:/laragon/www/ardenaai/Docs/Implementation_plan.md), Folder [Material/](file:///c:/laragon/www/ardenaai/Material)

---

## 1. Komitmen Kerja & Prinsip Eksekusi

Sesuai instruksi ketat dari Yogi:
1. **Zero-Code Sebelum Persetujuan Rencana:** Tidak ada file kode PHP atau skrip backend yang dibuat sebelum dokumen rencana implementasi ini disetujui secara tuntas.
2. **Kepatuhan Terhadap Template Frontend:** Seluruh rancangan backend disesuaikan 100% untuk melayani aset antarmuka yang telah disiapkan tim Frontend di folder [Material/](file:///c:/laragon/www/ardenaai/Material).
3. **Penyederhanaan Ekosistem AI:** Mengunci keputusan **Opsi A (All-in-One Groq)**. Backend hanya membutuhkan **1 API Key Groq** untuk seluruh pemrosesan kecerdasan buatan (Chat, Whisper, Vision).
4. **Proteksi Kunci API & `.gitignore`:**
   - File kunci `Docs/api` yang disediakan Yogi di lingkungan lokal **wajib dimasukkan ke dalam `.gitignore`**.
   - Kunci dari `Docs/api` akan dimuat ke file `.env` lokal (`GROQ_API_KEY`).
   - Baik `Docs/api` maupun `.env` **tidak akan pernah di-commit atau di-push ke GitHub** (`https://github.com/useripx/ardenaai.git`) untuk menjaga keamanan akun dan menghindari pemblokiran otomatis oleh Git Secret Scanner.
5. **Kepatuhan Dokumentasi:** Setiap fase perubahan wajib memelihara `Docs/Audit_Report.md`, `Docs/Task_Status.md`, `Docs/Decision_Log.md`, `Docs/Test_Report.md`, serta changelog bertimestamp dengan format `changelog/dd/bb/yyyy/jam/menit.md`.

---

## 2. Arsitektur AI Engine: All-in-One Groq (Opsi A)

Dengan disetujuinya **Opsi A**, seluruh fitur kecerdasan buatan berjalan di atas infrastruktur Groq LPU (Language Processing Unit) dengan latensi ultra-cepat (< 1 detik):

| Modul Fitur | Engine / Model AI | Input Format | Output Format | Fungsi Utama di Aplikasi |
|---|---|---|---|---|
| **Percakapan Teks & Logika Scratch** | Groq API (`openai/gpt-oss-120b`) + 4-Level Fallback | JSON teks pesan & riwayat obrolan | JSON terstruktur (Penjelasan ramah anak + blok Scratch) | Menjawab pertanyaan coding, memandu algoritma anak SD, dan menyusun blok puzzle. |
| **Input Suara (Speech-to-Text)** | Groq API (`whisper-large-v3`) | File audio multipart (`.webm`, `.wav`, `.mp3`) maks 10MB | JSON hasil transkripsi teks | Mentranskripsi suara rekaman mikrofon siswa secara instan ke teks pertanyaan. |
| **Analisis Foto Blok (Vision / OCR)** | Groq API (`llama-3.2-11b-vision-preview` / `90b`) | File gambar multipart / base64 (`.png`, `.jpg`, `.webp`) maks 5MB | JSON terstruktur hasil analisis blok Scratch | Membaca tangkapan layar atau foto blok Scratch karya siswa, mendeteksi bug, dan memberi saran perbaikan. |

### 2.1 Mekanisme Auto-Fallback 4 Tingkat (`chat.php`)
Ketika endpoint `/api/chat.php` menerima respons HTTP `429` (*Rate Limit / Quota Exceeded*) dari Groq, backend secara otomatis beralih ke model berikutnya tanpa memutus sesi siswa:
1. **Priority 1 (Utama):** `openai/gpt-oss-120b` (penalaran logika Scratch utama).
2. **Priority 2 (Cadangan 1):** `qwen/qwen3.8-27b` (alternatif penalaran cepat).
3. **Priority 3 (Cadangan 2):** `openai/gpt-oss-20b` (alternatif ringan).
4. **Priority 4 (Cadangan 3):** `allam-2-7b` (alternatif percakapan umum).

---

## 3. Pemetaan Lengkap Template Frontend (`Material/`)

Backend dirancang presisi untuk melayani 5 halaman utama yang ada di folder [Material/](file:///c:/laragon/www/ardenaai/Material):

```mermaid
graph TD
    subgraph Frontend_Templates [Folder Material]
        M_Login["Material/login_ardena_scratch_3"]
        M_Register["Material/daftar_akun_ardena_scratch_3"]
        M_Chat["Material/stage_tanya_ardena_asisten_ai_scratch_3"]
        M_History["Material/riwayat_obrolan_ardena_scratch_3"]
        M_Settings["Material/pengaturan_akun_ardena_scratch_3"]
        M_Design["Material/ardena_scratch_assistant/DESIGN.md"]
    end

    subgraph Backend_Endpoints [Backend PHP Native]
        E_Auth["AuthMiddleware (Firebase ID Token)"]
        E_Health["GET /api/health.php"]
        E_Chat["POST /api/chat.php"]
        E_STT["POST /api/transcribe.php"]
        E_Vision["POST /api/vision.php"]
        E_Conversations["GET /api/conversations.php"]
        E_Conversation["GET /api/conversation.php?id="]
        E_Save["POST /api/conversation-save.php"]
        E_Delete["DELETE /api/conversation-delete.php?id="]
    end

    subgraph Cloud_Services [Layanan Cloud]
        Firebase_Auth["Firebase Authentication"]
        Cloud_Firestore["Cloud Firestore Database"]
        Groq_LPU["Groq Cloud API (GROQ_API_KEY)"]
    end

    M_Login -->|Verifikasi Sesi| E_Auth --> Firebase_Auth
    M_Register -->|Registrasi Siswa| Firebase_Auth
    M_Register -->|Inisialisasi Profil| Cloud_Firestore

    M_Chat -->|Tanya Teks + Fallback 4x| E_Chat --> Groq_LPU
    M_Chat -->|Rekam Suara Mic| E_STT --> Groq_LPU
    M_Chat -->|Upload Foto Blok| E_Vision --> Groq_LPU
    M_Chat -->|Simpan Pesan| E_Save --> Cloud_Firestore

    M_History -->|Ambil Daftar Sesi| E_Conversations --> Cloud_Firestore
    M_History -->|Buka Sesi Lama| E_Conversation --> Cloud_Firestore
    M_History -->|Hapus Sesi| E_Delete --> Cloud_Firestore

    M_Settings -->|Update Profil & Preferensi| Cloud_Firestore
```

---

## 4. Standar Kontrak API Backend

### 4.1 Respons Standar JSON
Semua endpoint mengembalikan struktur seragam:
```json
{
  "success": true,
  "data": { ... },
  "message": "Operasi berhasil",
  "meta": {
    "model_used": "openai/gpt-oss-120b",
    "timestamp": "2026-10-14T22:50:00+07:00"
  }
}
```

### 4.2 Respons Output Percakapan AI (`/api/chat.php` & `/api/vision.php`)
```json
{
  "answer": "Keren sekali pertanyaannya! 🌟 Untuk membuat Kucing melompat saat tombol Spasi ditekan, kita ubah posisi Y kucing ya!",
  "steps": [
    "Tarik blok kuning 'ketika tombol [spasi] ditekan' dari kategori Kejadian.",
    "Pasang blok biru 'ubah y sebesar ( 50 )' dari kategori Gerakan.",
    "Pasang blok kontrol 'tunggu ( 0.2 ) detik'.",
    "Pasang blok biru 'ubah y sebesar ( -50 )' agar kucing turun kembali ke lantai."
  ],
  "blocks": [
    {
      "id": "blk_1",
      "category": "events",
      "opcode": "event_whenkeypressed",
      "fields": { "KEY_OPTION": "space" },
      "inputs": {},
      "label": "ketika tombol [ spasi ] ditekan"
    },
    {
      "id": "blk_2",
      "category": "motion",
      "opcode": "motion_changeyby",
      "fields": {},
      "inputs": { "DY": 50 },
      "label": "ubah y sebesar ( 50 )"
    }
  ],
  "follow_up_question": "Mau coba kita tambahkan efek suara 'meong' saat kucingnya mendarat?"
}
```

---

## 5. Struktur Direktori Proyek Backend

```text
c:\laragon\www\ardenaai\
├── backend/
│   ├── public/
│   │   ├── index.php                 # Health / Fallback router
│   │   └── api/
│   │       ├── health.php            # GET  - Cek konektivitas server & Groq
│   │       ├── chat.php              # POST - Chat AI + auto-fallback 4 tingkat
│   │       ├── transcribe.php        # POST - Speech-to-Text (Groq Whisper)
│   │       ├── vision.php            # POST - Analisis foto blok (Groq Vision)
│   │       ├── conversations.php     # GET  - Daftar riwayat obrolan user
│   │       ├── conversation.php      # GET  - Ambil 1 sesi obrolan
│   │       ├── conversation-save.php # POST - Simpan / update sesi obrolan
│   │       └── conversation-delete.php # DELETE - Hapus sesi obrolan
│   ├── src/
│   │   ├── Config/
│   │   │   ├── AppConfig.php         # Parsing .env & pengaturan runtime
│   │   │   └── FirebaseConfig.php    # Konfigurasi kredensial Firebase
│   │   ├── Middleware/
│   │   │   ├── CorsMiddleware.php    # Header CORS & keamanan HTTP
│   │   │   ├── AuthMiddleware.php    # Verifikasi Bearer Firebase ID Token
│   │   │   └── RateLimiter.php       # Proteksi request spam/abuse
│   │   ├── Services/
│   │   │   ├── GroqService.php       # Core Groq Chat + Fallback 4 Tingkat
│   │   │   ├── WhisperService.php    # Groq Whisper STT Handler
│   │   │   ├── VisionService.php     # Groq Llama-Vision Handler
│   │   │   └── ConversationService.php # Layanan bisnis sesi percakapan
│   │   ├── Repositories/
│   │   │   └── FirestoreRepository.php # Komunikasi data Cloud Firestore REST API
│   │   ├── Validators/
│   │   │   ├── InputValidator.php    # Validasi request payload dari browser
│   │   │   ├── AIResponseValidator.php # Validasi ketat format JSON respons AI
│   │   │   └── ScratchBlockValidator.php # Validasi warna & kategori blok Scratch 3
│   │   └── Support/
│   │       ├── ApiResponse.php       # Pembungkus standar output JSON
│   │       ├── Logger.php            # Safe logging (tanpa bocor API key / PII)
│   │       └── HttpClient.php        # Wrapper cURL stabil dengan timeout & retry
│   ├── tests/                        # Skrip pengujian otomatis (Unit & Integrasi)
│   ├── .env.example                  # Template variabel lingkungan aman
│   ├── composer.json                 # Manajemen dependensi backend
│   └── README.md                     # Panduan operasional & instalasi lokal
├── Docs/
│   ├── Implementation_plan.md       # Master acuan versi tetap 1.0
│   ├── Audit_Report.md              # Laporan temuan audit Fase 0
│   ├── API_Contract.md              # Kontrak API resmi request & response
│   ├── Decision_Log.md              # Rekam jejak keputusan (Opsi A terpilih)
│   ├── Task_Status.md               # Tracking progres setiap tugas backend
│   ├── Test_Report.md               # Bukti dan hasil uji nyata
│   └── api                          # File lokal API key (DIABAIKAN OLEH .GITIGNORE)
├── Material/                        # Template UI tim frontend (asli dipertahankan)
├── .gitignore                       # Mengabaikan .env, Docs/api, dan temporary files
└── changelog/                       # Catatan riwayat perubahan (dd/bb/yyyy/jam/menit.md)
```

---

## 6. Rencana Tahapan Eksekusi (Fase 0 hingga Fase 11)

| Fase | Nama Fase | Lingkup Kerja Utama | Status |
|---|---|---|---|
| **Fase 0** | **Audit & Penyelarasan Git** | Inisialisasi git lokal (`git init`), buat `.gitignore` protektif (`Docs/api`, `.env`), pasang origin `https://github.com/useripx/ardenaai.git`, susun `Docs/Audit_Report.md` dan `Docs/Task_Status.md`. | **Siap Dijalankan** |
| **Fase 1** | **Kontrak API & Decision Log** | Catat keputusan Opsi A (All-in-One Groq) di `Docs/Decision_Log.md`, finalisasi `Docs/API_Contract.md`, verifikasi format blok Scratch 3. | Menunggu Fase 0 |
| **Fase 2** | **Fondasi Backend PHP Native** | Buat struktur folder `backend/`, integrasikan kunci dari `Docs/api` ke `.env`, buat `ApiResponse.php`, `Logger.php`, `HttpClient.php`, dan uji endpoint `GET /api/health.php`. | Menunggu Fase 1 |
| **Fase 3** | **Autentikasi & Otorisasi Firebase** | Buat `AuthMiddleware.php`, validasi Firebase ID Token, verifikasi kepemilikan data pengguna. | Menunggu Fase 2 |
| **Fase 4** | **Engine Chat Groq & 4-Level Fallback** | Buat `GroqService.php`, terapkan fallback 4 tingkat saat HTTP 429, validasi keluaran JSON, uji endpoint `POST /api/chat.php`. | Menunggu Fase 3 |
| **Fase 5** | **Validasi Scratch Blocks** | Buat `ScratchBlockValidator.php` untuk memastikan blok aman dan sesuai kategori Scratch 3. | Menunggu Fase 4 |
| **Fase 6** | **Penyimpanan Riwayat Firestore** | Buat `FirestoreRepository.php`, bangun endpoint `/api/conversations.php`, `/api/conversation.php`, `/api/conversation-save.php`, `/api/conversation-delete.php`. | Menunggu Fase 5 |
| **Fase 7** | **Analisis Gambar via Groq Vision** | Buat `VisionService.php` (Groq Llama-Vision), upload gambar multipart, uji endpoint `POST /api/vision.php`. | Menunggu Fase 6 |
| **Fase 8** | **Speech-to-Text via Groq Whisper** | Buat `WhisperService.php` (Groq Whisper), upload audio multipart, uji endpoint `POST /api/transcribe.php`. | Menunggu Fase 7 |
| **Fase 9** | **Integrasi Template Frontend (`Material/`)** | Sambungkan form, audio mic, upload foto, dan chat stream di folder `Material/` ke backend API. | Menunggu Fase 8 |
| **Fase 10** | **Pengujian Menyeluruh & Keamanan** | Pengujian integrasi end-to-end, verifikasi keamanan secret, uji simulasi error, susun `Docs/Test_Report.md`. | Menunggu Fase 9 |
| **Fase 11** | **Dokumentasi & Push GitHub** | Buat `backend/README.md`, verifikasi commit bersih (pastikan tidak ada API key di git), dan lakukan push ke `https://github.com/useripx/ardenaai.git`. | Menunggu Fase 10 |

---

## 7. Variabel Lingkungan & Penanganan Keamanan (`.gitignore`)

### Konfigurasi `.gitignore`
Untuk melindungi API key yang telah diletakkan di `Docs/api`:
```gitignore
# Environment & Secrets
.env
Docs/api
Docs/api*
*.key
*.pem
serviceAccountKey.json

# Temporary Uploads
backend/storage/temp/*
!backend/storage/temp/.gitkeep

# Composer Dependencies
backend/vendor/
```

### Konfigurasi `backend/.env.example`
```ini
# Environment Ardena AI Scratch 3
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/ardenaai

# AI Engine (All-in-One Groq Provider)
GROQ_API_KEY=gsk_your_groq_api_key_here
GROQ_MODEL_CHAT_PRIMARY=openai/gpt-oss-120b
GROQ_MODEL_CHAT_FALLBACK_1=qwen/qwen3.8-27b
GROQ_MODEL_CHAT_FALLBACK_2=openai/gpt-oss-20b
GROQ_MODEL_CHAT_FALLBACK_3=allam-2-7b
GROQ_MODEL_WHISPER=whisper-large-v3
GROQ_MODEL_VISION=llama-3.2-11b-vision-preview

# Firebase Project Configuration
FIREBASE_PROJECT_ID=your-firebase-project-id
FIREBASE_CLIENT_EMAIL=your-service-account@project.iam.gserviceaccount.com
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n"

# Client CORS
CORS_ALLOWED_ORIGINS=http://localhost,http://127.0.0.1,http://localhost:3000
```

---

## 8. Persetujuan Final untuk Memulai Fase 0 (Git & Audit)

Dokumen ini sekarang telah **100% lengkap, aman, dan siap**:
1. **GitHub Remote:** `https://github.com/useripx/ardenaai.git`
2. **Provider AI:** **All-in-One Groq** (Kunci API diambil secara aman dari `Docs/api` lokal).
3. **Keamanan Secret:** `Docs/api` dan `.env` otomatis diproteksi oleh `.gitignore`.
4. **Template Frontend:** Terpetakan penuh ke 5 antarmuka di folder `Material/`.

Apakah Anda menyetujui dokumen **Implementation Plan Revisi Final 1.3** ini agar kita dapat segera mengeksekusi **Fase 0 (Inisialisasi Git, Setup Proteksi `.gitignore`, dan Penyusunan Dokumen Audit)**?
