# Implementation_plan.md — Backend Ardena AI Scratch 3

**Status:** VERSI TETAP — acuan implementasi backend; eksekusi tetap mengikuti gerbang persetujuan
**Versi:** 1.0\
**Tanggal penetapan dokumen:** 14 Oktober 2026\
**Cakupan:** Backend dan integrasi layanan Ardena AI Scratch 3\
**Batasan:** Dokumen perencanaan. Pembuatan versi tetap ini tidak mengubah atau menjalankan kode proyek.

------------------------------------------------------------------------

## 1. Tujuan

Menyusun rencana implementasi backend untuk menghubungkan prototype UI
Ardena AI Scratch 3 dengan autentikasi, penyimpanan percakapan, serta
layanan AI. Rencana ini menjadi bahan pemeriksaan sebelum implementasi
dimulai.

Fokus produk tetap **Ardena AI Scratch 3**. Ardena Office AI tidak
termasuk ruang lingkup.

## 2. Prinsip dan keputusan arsitektur

| Area | Keputusan |
|---|---|
| Frontend | HTML, CSS, dan Vanilla JavaScript; prototype Stitch menjadi acuan UI |
| Backend | PHP Native |
| Autentikasi | Firebase Authentication dengan email dan kata sandi |
| Penyimpanan | Cloud Firestore untuk data percakapan dan data aplikasi yang diperlukan |
| Teks dan logika utama | Groq API dengan `openai/gpt-oss-120b` sebagai model utama |
| Input suara (Speech-to-Text) | Groq API dengan `whisper-large-v3` |
| Analisis foto blok Scratch (Vision/OCR) | OpenRouter API dengan `qwen/qwen-2.5-vl-72b-instruct:free` |
| Visualisasi blok | Scratch Blocks; integrasi dilanjutkan setelah Proof of Concept (PoC) berhasil |
| Format keluaran AI | JSON terstruktur yang divalidasi backend sebelum dikirim ke frontend |
| Model lokal | GGUF/Llamafile bukan bagian dari arsitektur utama aplikasi |
| Kunci API | Disimpan hanya di sisi server, tidak ditanamkan dalam HTML/JavaScript |
| Alur perubahan | Tidak mengubah atau menjalankan proyek sebelum rencana ini disetujui |

### 2.1 Arsitektur Multi-Model AI Engine

| Modul Input | Provider API | Model AI | Alasan Pemilihan dan Mekanisme |
|---|---|---|---|
| Teks dan logika utama | Groq API | `openai/gpt-oss-120b` (utama) | Digunakan untuk analisis dan penalaran terkait blok Scratch. Direncanakan memakai auto-fallback empat tingkat. Kuota gratis yang disebut dalam rancangan: 1.000 request/hari; batas aktual wajib diverifikasi pada akun/provider sebelum rilis. |
| Input suara (Speech-to-Text) | Groq API | `whisper-large-v3` | Mengubah audio mikrofon, misalnya `.webm` atau `.wav`, menjadi teks. Kuota gratis yang disebut dalam rancangan: 2.000 request/hari; batas aktual wajib diverifikasi pada akun/provider sebelum rilis. |
| Foto blok Scratch (Vision/OCR) | OpenRouter API | `qwen/qwen-2.5-vl-72b-instruct:free` | Menganalisis gambar untuk mengidentifikasi warna, teks, dan urutan blok Scratch tanpa tahap OCR terpisah dalam alur yang direncanakan. Perkiraan kuota sekitar 200 request/hari perlu diverifikasi karena ketersediaan model dan batas provider dapat berubah. |

### 2.2 Mekanisme Auto-Fallback Empat Tingkat

Untuk endpoint percakapan backend PHP (`chat.php`), gunakan urutan prioritas berikut jika model utama gagal karena HTTP `429` (*Too Many Requests / Quota Exceeded*):

1. **Priority 1 — Utama:** `openai/gpt-oss-120b` — model utama untuk penalaran logika.
2. **Priority 2 — Cadangan 1:** `qwen/qwen3.8-27b` — kandidat cadangan untuk percakapan dan logika.
3. **Priority 3 — Cadangan 2:** `openai/gpt-oss-20b` — alternatif yang lebih ringan.
4. **Priority 4 — Cadangan 3:** `allam-2-7b` — alternatif untuk percakapan umum.

Ketentuan implementasi fallback:

- Terapkan fallback untuk kondisi yang memang dapat dicoba ulang, terutama `429` sesuai keputusan rancangan. Jangan otomatis menganggap semua error sebagai masalah kuota.
- Catat model yang dicoba, status HTTP, dan durasi secara aman; jangan mencatat API key atau isi percakapan sensitif ke log.
- Tetapkan timeout dan batas percobaan agar satu pesan tidak memicu rangkaian permintaan tanpa batas.
- Jika seluruh model gagal, kembalikan error terstruktur yang ramah pengguna dan tidak mengarang jawaban.
- Nama model dan ketersediaannya harus diverifikasi terhadap daftar model provider pada saat implementasi. Jangan mengganti nama model secara diam-diam; jika ada nama yang tidak tersedia, hentikan bagian tersebut dan minta persetujuan pengguna untuk memilih pengganti.
- Angka kuota dan klaim gratis di tabel merupakan asumsi rancangan yang perlu diperiksa kembali pada dokumentasi serta akun provider sebelum digunakan dalam estimasi kapasitas.

## 3. Ruang lingkup backend MVP

### 3.1 Termasuk MVP

1.  Endpoint pemeriksaan kesehatan backend.
2.  Validasi token Firebase Authentication untuk endpoint yang
    memerlukan login.
3.  Endpoint percakapan teks melalui Groq API.
4.  Validasi dan normalisasi respons AI dalam format JSON.
5.  Penyimpanan dan pembacaan riwayat percakapan melalui Firestore.
6.  Endpoint unggah/analisis gambar melalui OpenRouter, setelah model
    dan format input disetujui.
7.  Endpoint transkripsi suara menggunakan Whisper, setelah lokasi
    pemrosesan dan runtime disepakati.
8.  Penanganan error, batas ukuran input, timeout, dan logging tanpa
    menyimpan isi percakapan sensitif secara tidak perlu.
9.  Dokumentasi konfigurasi dan petunjuk pengujian.

### 3.2 Tidak termasuk MVP awal

-   Menjalankan keseluruhan program Scratch menggunakan Scratch VM.
-   Model AI GGUF/Llamafile sebagai jalur utama.
-   Pelatihan model AI sendiri.
-   Dashboard analitik kompleks atau gamifikasi tingkat lanjut.
-   Menganggap output AI sebagai blok yang valid tanpa validasi.
-   Menaruh secret API di frontend.
-   Menghapus atau mengganti prototype UI sebelum ada persetujuan.

## 4. Arsitektur dan alur data

### 4.1 Percakapan teks

1.  Siswa mengirim pesan dari UI.
2.  Frontend mengirim permintaan HTTPS ke endpoint PHP.
3.  Backend memvalidasi format input, ukuran pesan, autentikasi, dan
    batas penggunaan.
4.  Backend membangun instruksi sistem dan konteks percakapan yang
    diperlukan.
5.  Backend mengirim permintaan ke Groq API menggunakan secret yang
    tersimpan di server.
6.  Backend memeriksa respons, menangani kegagalan, dan memvalidasi
    struktur JSON.
7.  Backend mengembalikan jawaban, penjelasan bertahap, dan data blok
    terstruktur yang lolos validasi.
8.  Frontend menampilkan jawaban dan meneruskan data blok ke Scratch
    Blocks.
9.  Riwayat percakapan disimpan ke Firestore melalui mekanisme yang
    memiliki otorisasi.

### 4.2 Input suara

1.  Frontend meminta izin mikrofon dan merekam audio.
2.  Frontend mengirim berkas audio ke endpoint PHP melalui HTTPS.
3.  Backend memvalidasi tipe berkas, ukuran, durasi jika dapat
    diperiksa, dan autentikasi.
4.  Audio diproses oleh Whisper sesuai runtime yang disetujui.
5.  Teks hasil transkripsi dikembalikan ke UI atau diteruskan ke alur
    percakapan setelah ada tindakan yang jelas dari siswa.
6.  Audio sementara dihapus setelah pemrosesan, kecuali penyimpanan
    memang diperlukan dan sudah disetujui.

**Keputusan terbuka:** apakah Whisper berjalan di server milik proyek
atau melalui layanan penyedia. Jangan menganggap Whisper sebagai API
tertentu sebelum model dan runtime dipastikan.

### 4.3 Analisis gambar

1.  Siswa memilih atau mengunggah foto blok Scratch.
2.  Frontend menampilkan pratinjau dan mengirim gambar ke endpoint PHP.
3.  Backend memvalidasi MIME type, ukuran, autentikasi, serta batas
    jumlah permintaan.
4.  Backend mengirim gambar dan instruksi analisis ke model vision
    melalui OpenRouter.
5.  Backend memvalidasi hasil analisis dan mengembalikan penjelasan
    terstruktur.
6.  Jika hasil memuat usulan blok, struktur tersebut tetap harus
    melewati validasi sebelum dirender.

Gambar tidak boleh dikirim ke layanan eksternal tanpa pemberitahuan yang
sesuai pada UI dan kebijakan privasi yang relevan.

## 5. Kontrak respons AI

Backend harus meminta model menghasilkan format yang terstruktur. Bentuk
berikut adalah **usulan kontrak**, bukan format final yang sudah
diimplementasikan:

``` json
{
  "answer": "Penjelasan dalam bahasa Indonesia yang ramah anak.",
  "steps": [
    "Pilih kategori Gerakan.",
    "Tambahkan blok yang sesuai."
  ],
  "blocks": [],
  "follow_up_question": null
}
```

Ketentuan:

-   `answer` wajib berupa teks.
-   `steps` berupa daftar teks dan boleh kosong.
-   `blocks` berupa data terstruktur sesuai skema Scratch Blocks yang
    akan ditetapkan.
-   `follow_up_question` berupa teks atau `null`.
-   Backend harus menangani JSON tidak valid, properti yang hilang, tipe
    data salah, dan respons model yang tidak sesuai.
-   Jangan langsung memasukkan teks AI sebagai HTML mentah ke halaman.
-   Jangan merender `blocks` sebelum validasi terhadap daftar tipe blok
    dan input yang diizinkan selesai.

Skema blok final harus ditetapkan bersama PoC Scratch Blocks. Jangan
mengasumsikan AI sudah menghasilkan JSON Blockly/Scratch yang valid
hanya karena formatnya berupa JSON.

## 6. Autentikasi dan otorisasi

-   Firebase Authentication menangani pendaftaran dan login email/kata
    sandi.
-   Frontend mengirim Firebase ID token ke backend menggunakan header
    `Authorization: Bearer <token>`.
-   Backend harus memverifikasi token menggunakan pendekatan server-side
    yang sesuai sebelum mengakses data privat.
-   Backend tidak boleh mempercayai `uid` yang dikirim bebas di body
    request; identitas harus berasal dari token yang telah diverifikasi.
-   Akses riwayat hanya boleh untuk pengguna yang berhak atas data
    tersebut.
-   Jika backend menggunakan Firebase Admin SDK atau kredensial server,
    backend harus menerapkan pemeriksaan otorisasi sendiri karena akses
    server tidak otomatis dibatasi oleh Firestore Security Rules.
-   Kredensial layanan Firebase server tidak boleh berada di repositori
    publik atau frontend.

## 7. Usulan endpoint PHP

Nama endpoint di bawah adalah usulan untuk ditinjau, belum merupakan
kontrak API final.

  ---------------------------------------------------------------------------------------
  Method                  Endpoint                                Tujuan
  ----------------------- --------------------------------------- -----------------------
  `GET`                   `/api/health.php`                       Memeriksa status
                                                                  backend tanpa
                                                                  membocorkan konfigurasi

  `POST`                  `/api/chat.php`                         Mengirim pertanyaan
                                                                  teks ke Groq dan
                                                                  menerima respons
                                                                  tervalidasi

  `POST`                  `/api/vision.php`                       Mengirim gambar untuk
                                                                  analisis melalui
                                                                  OpenRouter

  `POST`                  `/api/transcribe.php`                   Mengubah audio menjadi
                                                                  teks menggunakan
                                                                  Whisper

  `GET`                   `/api/conversations.php`                Mengambil daftar
                                                                  percakapan milik
                                                                  pengguna terautentikasi

  `GET`                   `/api/conversation.php?id=...`          Mengambil satu
                                                                  percakapan setelah
                                                                  pemeriksaan kepemilikan

  `POST`                  `/api/conversation-save.php`            Menyimpan atau
                                                                  memperbarui percakapan
                                                                  sesuai aturan yang
                                                                  ditetapkan

  `DELETE`                `/api/conversation-delete.php?id=...`   Menghapus percakapan
                                                                  jika fitur penghapusan
                                                                  disetujui
  ---------------------------------------------------------------------------------------

Jika deployment atau framework membutuhkan pola URL berbeda, nama
endpoint boleh disesuaikan. Jangan membuat endpoint duplikat tanpa
kebutuhan.

## 8. Struktur folder backend yang diusulkan

``` text
backend/
├── public/
│   └── api/
│       ├── health.php
│       ├── chat.php
│       ├── vision.php
│       ├── transcribe.php
│       ├── conversations.php
│       ├── conversation.php
│       ├── conversation-save.php
│       └── conversation-delete.php
├── src/
│   ├── Config/
│   ├── Middleware/
│   ├── Controllers/
│   ├── Services/
│   │   ├── GroqService.php
│   │   ├── OpenRouterService.php
│   │   ├── WhisperService.php
│   │   └── ConversationService.php
│   ├── Repositories/
│   │   └── FirestoreRepository.php
│   ├── Validators/
│   ├── DTO/
│   └── Support/
├── tests/
├── .env.example
├── .gitignore
└── README.md
```

Struktur ini usulan pemisahan tanggung jawab. Struktur final harus
disesuaikan setelah memeriksa repositori, hosting, versi PHP, dan
dependensi yang sudah ada. File rahasia `.env` tidak boleh di-commit;
`.env.example` hanya berisi nama variabel tanpa nilai rahasia.

## 9. Keamanan, privasi, dan keandalan

-   Gunakan HTTPS untuk semua komunikasi.
-   Simpan kunci Groq dan OpenRouter di environment server.
-   Batasi ukuran pesan, audio, dan gambar.
-   Validasi tipe berkas dari konten dan metadata; jangan hanya
    mempercayai ekstensi.
-   Terapkan timeout, penanganan error, dan pembatasan permintaan untuk
    mencegah penyalahgunaan biaya API.
-   Jangan mencatat API key, ID token, kata sandi, atau isi percakapan
    lengkap ke log aplikasi.
-   Hindari menyimpan audio dan gambar setelah selesai diproses kecuali
    diperlukan.
-   Berikan pesan error yang aman kepada pengguna; detail teknis hanya
    masuk ke log server yang terlindungi.
-   Atur CORS hanya untuk origin frontend yang diperlukan.
-   Siapkan aturan Firestore untuk membatasi akses pengguna, lalu uji
    aturan tersebut.
-   Karena target pengguna termasuk anak, kumpulkan data seminimal
    mungkin dan tinjau kebutuhan persetujuan orang tua/wali serta
    kebijakan privasi sebelum rilis.
-   Jelaskan pada UI jika teks, audio, atau gambar dikirim ke layanan AI
    eksternal.

## 10. Strategi pengujian

### Unit test

-   Validasi payload masuk.
-   Validasi bentuk JSON respons AI.
-   Penanganan respons kosong atau tidak valid.
-   Penanganan timeout dan error penyedia AI.
-   Pemeriksaan pemetaan data percakapan.

### Integration test

-   Endpoint chat dengan respons provider yang berhasil dan gagal.
-   Verifikasi Firebase ID token.
-   Akses Firestore untuk pemilik data yang benar dan penolakan akses
    pengguna lain.
-   Pengiriman gambar dan audio yang valid maupun tidak valid.
-   Pemeriksaan bahwa secret tidak dikirim ke frontend atau respons API.

### End-to-end test

-   Siswa login, mengirim pertanyaan, menerima penjelasan, lalu melihat
    riwayat tersimpan.
-   Siswa mengunggah foto dan menerima hasil analisis.
-   Siswa menggunakan input suara dan memeriksa hasil transkripsi.
-   Pengguna yang belum login ditolak dari endpoint privat.
-   Kegagalan provider ditampilkan sebagai pesan yang mudah dipahami dan
    dapat dicoba ulang.

## 11. Fase Implementasi Backend

Implementasi wajib dilakukan bertahap dengan gerbang persetujuan (*approval gate*). Selesaikan dan dokumentasikan satu fase sebelum melanjutkan ke fase berikutnya. Urutan dapat disesuaikan hanya setelah alasan dan dampaknya dijelaskan kepada Yogi.

### Fase 0 — Audit dan Pemahaman Proyek

**Tujuan:** memahami kondisi proyek yang sebenarnya sebelum menyentuh kode.

Pekerjaan:
- Periksa repositori, branch aktif, struktur folder, file yang sudah ada, dan status perubahan Git.
- Baca `Implementation_plan.md`, dokumentasi proyek, prototype Stitch, serta catatan keputusan terbaru.
- Identifikasi hosting, versi PHP, ekstensi PHP, Composer/dependensi, konfigurasi Firebase, dan metode deployment yang sudah digunakan.
- Bandingkan rencana dengan kondisi aktual; jangan menganggap fitur yang direncanakan sudah tersedia.

Keluaran:
- `Audit_Report.md` berisi kondisi aktual, risiko, asumsi, konflik, dan daftar file terkait.
- Daftar kebutuhan atau akses yang masih belum tersedia.
- Ringkasan pemahaman tugas: tujuan, ruang lingkup, batasan, hasil akhir yang diharapkan, dan hal yang secara eksplisit tidak boleh dilakukan. Jika instruksi pengguna tidak cukup jelas, tanyakan sebelum melanjutkan.

**Gerbang:** berhenti dan minta klarifikasi jika repositori, konfigurasi, provider, kredensial non-rahasia, atau keputusan arsitektur yang penting belum jelas. Jangan meminta pengguna mengirim secret/API key ke chat.

### Fase 1 — Konfirmasi Kebutuhan dan Kontrak

**Tujuan:** mengunci hal-hal yang perlu disepakati sebelum desain teknis dibuat.

Pekerjaan:
- Konfirmasi endpoint, skema request/response, autentikasi, bentuk data percakapan, skema output blok, dan kebijakan retensi data.
- Verifikasi nama serta ketersediaan model/provider dan batasan request yang diperlukan.
- Tentukan batas ukuran teks, gambar, audio, timeout, rate limit, dan perilaku ketika provider gagal.
- Catat keputusan yang telah disetujui dan yang masih terbuka.

Keluaran:
- `API_Contract.md`
- `Decision_Log.md`
- Daftar pertanyaan terbuka beserta dampaknya.

**Gerbang:** jangan menebak jawaban atas keputusan yang mengubah arsitektur, biaya, privasi, atau kompatibilitas. Ajukan pertanyaan singkat yang spesifik kepada Yogi dan tunggu jawaban.

### Fase 2 — Fondasi Backend

**Tujuan:** membangun kerangka backend yang aman dan mudah diuji.

Pekerjaan:
- Siapkan struktur folder dan konfigurasi environment sesuai proyek aktual.
- Buat respons JSON standar, validasi input dasar, penanganan error, dan endpoint health.
- Siapkan konfigurasi logging yang tidak membocorkan secret atau isi percakapan sensitif.
- Dokumentasikan cara menjalankan dan menguji backend.

Keluaran:
- Fondasi backend dan `README.md` untuk pengembangan lokal.
- Pengujian health endpoint dan validasi dasar.

**Gerbang:** minta persetujuan sebelum memasang dependensi baru, mengubah konfigurasi hosting, atau melakukan tindakan yang berdampak pada lingkungan bersama.

### Fase 3 — Autentikasi dan Otorisasi

**Tujuan:** memastikan endpoint privat hanya diakses pengguna yang sah.

Pekerjaan:
- Integrasikan verifikasi Firebase ID token di backend.
- Gunakan identitas dari token terverifikasi; jangan percaya `uid` dari body request.
- Definisikan middleware autentikasi dan aturan kepemilikan data.
- Uji token valid, kedaluwarsa, tidak valid, dan akses lintas pengguna.

Keluaran:
- Middleware autentikasi dan laporan pengujian akses.

**Gerbang:** jangan menonaktifkan pemeriksaan autentikasi untuk sekadar membuat demo bekerja. Jika konfigurasi Firebase atau metode verifikasi belum jelas, berhenti dan bertanya.

### Fase 4 — Integrasi Chat Groq dan Auto-Fallback

**Tujuan:** menghubungkan percakapan UI ke layanan AI sesungguhnya.

Pekerjaan:
- Integrasikan model utama `openai/gpt-oss-120b`.
- Terapkan urutan fallback empat tingkat sesuai Bagian 2.2.
- Tentukan kondisi fallback, timeout, batas percobaan, dan respons jika seluruh model gagal.
- Validasi JSON keluaran dan pisahkan penjelasan untuk siswa dari data blok terstruktur.
- Uji respons berhasil, HTTP 429, timeout, respons tidak valid, serta seluruh model gagal.

Keluaran:
- Endpoint chat yang teruji dan laporan uji fallback.

**Gerbang:** jangan mengganti model, urutan prioritas, atau perilaku fallback tanpa persetujuan. Verifikasi nama model dan dukungan provider sebelum mengaktifkannya.

### Fase 5 — Kontrak Output dan Validasi Scratch Blocks

**Tujuan:** memastikan hasil AI aman dan dapat dirender oleh antarmuka.

Pekerjaan:
- Definisikan skema blok yang sesuai dengan Scratch Blocks yang benar-benar dipakai.
- Buat validator untuk tipe blok, input, field, nesting, dan batas kedalaman/jumlah blok.
- Tolak atau minta perbaikan jika output tidak valid; jangan mengeksekusi konten tak tepercaya.
- Lakukan Proof of Concept dengan contoh tugas Scratch yang sederhana dan bertahap.

Keluaran:
- `Block_Output_Schema.md`, validator, dan contoh uji.

**Gerbang:** jangan menganggap JSON buatan AI otomatis valid sebagai format Scratch/Blockly. Jika versi library atau format target belum diketahui, periksa dan minta konfirmasi.

### Fase 6 — Penyimpanan Riwayat Firestore

**Tujuan:** menyimpan dan mengambil percakapan sesuai kepemilikan pengguna.

Pekerjaan:
- Tentukan struktur koleksi/dokumen, field minimum, timestamp, dan versi skema.
- Implementasikan penyimpanan, daftar riwayat, detail percakapan, dan penghapusan jika disetujui.
- Pastikan akses dibatasi untuk pemilik data dan aturan keamanan diuji.
- Tentukan kebijakan retensi serta penghapusan data.

Keluaran:
- Repository/service Firestore dan pengujian akses data.

**Gerbang:** jangan melakukan migrasi, menghapus data, atau mengubah database produksi tanpa persetujuan eksplisit dan rencana pemulihan.

### Fase 7 — Analisis Gambar melalui OpenRouter

**Tujuan:** menerima foto blok Scratch dan mengembalikan analisis terstruktur.

Pekerjaan:
- Integrasikan `qwen/qwen-2.5-vl-72b-instruct:free` melalui backend.
- Validasi MIME type, ukuran file, dan bentuk payload gambar.
- Uji gambar yang jelas, buram, terpotong, bukan gambar, dan gambar tanpa blok Scratch.
- Jelaskan di UI bahwa gambar dikirim ke layanan eksternal.

Keluaran:
- Endpoint vision dan hasil pengujian dengan gambar dummy/non-sensitif.

**Gerbang:** jangan mengirim data pribadi siswa ke provider saat pengujian. Jika model tidak tersedia atau batas provider berubah, berhenti dan minta persetujuan untuk alternatif.

### Fase 8 — Speech-to-Text melalui Groq

**Tujuan:** mengubah suara siswa menjadi teks.

Pekerjaan:
- Integrasikan `whisper-large-v3` melalui Groq API.
- Pastikan format audio, ukuran maksimum, durasi, dan metode multipart sesuai dukungan provider.
- Uji audio yang valid, audio kosong, format tidak didukung, durasi terlalu panjang, dan error provider.
- Hapus file sementara setelah pemrosesan sesuai kebijakan retensi.

Keluaran:
- Endpoint transkripsi dan hasil pengujian audio dummy.

**Gerbang:** jangan memilih runtime atau mengubah provider tanpa konfirmasi jika jalur yang direncanakan tidak tersedia.

### Fase 9 — Integrasi Frontend Prototype

**Tujuan:** menghubungkan UI Stitch ke backend tanpa merusak desain yang telah disetujui.

Pekerjaan:
- Petakan tombol/form pada prototype ke endpoint yang relevan.
- Ganti simulasi jawaban dengan permintaan API setelah endpoint terkait lolos pengujian.
- Tambahkan status loading, error, retry, dan kondisi kosong.
- Hubungkan hasil blok tervalidasi ke Scratch Blocks.
- Pertahankan perubahan UI seminimal mungkin dan catat setiap penyimpangan.

Keluaran:
- Alur UI-ke-backend yang dapat diuji end-to-end.

**Gerbang:** jangan menimpa atau menghapus prototype asli. Gunakan branch kerja dan minta persetujuan sebelum perubahan besar pada UI atau navigasi.

### Fase 10 — Pengujian Menyeluruh dan Keamanan

**Tujuan:** memastikan alur utama bekerja dan kegagalan ditangani.

Pekerjaan:
- Jalankan unit test, integration test, dan end-to-end test.
- Uji otorisasi, batas request, file tidak valid, timeout, provider gagal, dan akses lintas pengguna.
- Pastikan secret tidak masuk frontend, respons, Git, maupun log.
- Periksa CORS, HTTPS, kebijakan retensi, dan pesan privasi untuk pengguna anak.
- Catat bug, tingkat dampak, dan langkah reproduksi.

Keluaran:
- `Test_Report.md`, daftar bug, dan status kriteria penerimaan.

**Gerbang:** jangan menyatakan siap rilis jika ada masalah keamanan kritis atau pengujian utama yang gagal. Laporkan masalah dan minta keputusan bila perbaikannya mengubah ruang lingkup.

### Fase 11 — Dokumentasi, Deployment, dan Serah Terima

**Tujuan:** menyiapkan backend untuk lingkungan target secara terkendali.

Pekerjaan:
- Dokumentasikan environment variable, konfigurasi provider, endpoint, dan prosedur deployment/rollback.
- Siapkan konfigurasi produksi tanpa memasukkan secret ke repository.
- Lakukan deployment hanya setelah hosting, domain, akses, biaya, dan waktu pelaksanaan dikonfirmasi.
- Verifikasi health endpoint dan alur utama setelah deployment.
- Catat hasil, perubahan konfigurasi, risiko tersisa, dan pekerjaan lanjutan.

Keluaran:
- Dokumentasi deployment, checklist rilis, dan laporan serah terima.

**Gerbang:** deployment produksi, perubahan DNS, perubahan database, atau tindakan berbiaya memerlukan persetujuan eksplisit Yogi.

## 12. Protokol Bertanya dan Klarifikasi untuk AI/Developer

AI atau developer yang membantu implementasi **wajib bertanya tanpa sungkan** jika ada informasi yang belum jelas, konflik antarfile, keputusan yang belum dibuat, akses yang kurang, atau risiko yang dapat mengubah hasil. Bertanya bukan kegagalan; menebak keputusan penting justru tidak diperbolehkan.

### Kapan wajib bertanya

- Nama model, endpoint, versi library, atau layanan tidak cocok dengan dokumentasi.
- Ada perbedaan antara `Implementation_plan.md`, dokumentasi lain, prototype, dan kode aktual.
- Diperlukan kredensial, izin, akun provider, domain, atau konfigurasi yang belum tersedia.
- Pilihan berpotensi menambah biaya, mengirim data ke pihak ketiga, atau memengaruhi privasi anak.
- Perubahan berpotensi merusak UI yang disetujui, data pengguna, kompatibilitas, atau arsitektur.
- Ada beberapa solusi yang sama-sama masuk akal dan pilihan tersebut berdampak pada hasil.
- Tes gagal dan penyebabnya belum diketahui secara meyakinkan.
- Perlu memasang dependensi, menjalankan migrasi, menghapus/menimpa file, mengubah konfigurasi produksi, atau melakukan deployment.

### Cara mengajukan pertanyaan

- Ajukan pertanyaan yang spesifik dan ringkas.
- Jelaskan mengapa keputusan dibutuhkan dan dampaknya.
- Jika memungkinkan, tawarkan 2–3 opsi dengan kelebihan, kekurangan, biaya/risiko, lalu berikan rekomendasi.
- Tanyakan beberapa hal yang saling berkaitan dalam satu pesan jika itu lebih efisien.
- Jangan menanyakan hal yang sudah jelas dan terdokumentasi; periksa dokumentasi dan kode terlebih dahulu.
- Jangan meminta pengguna membagikan API key, kata sandi, token, atau private key ke percakapan. Minta pengguna mengonfigurasikannya secara lokal di environment yang aman.
- Jika keputusan belum mendesak, catat sebagai pertanyaan terbuka dan lanjutkan hanya pekerjaan yang tidak bergantung padanya.
- Jika pekerjaan bergantung pada jawaban, berhenti pada gerbang fase terkait dan tunggu jawaban. Jangan memilih diam-diam.

### Format catatan pertanyaan

Gunakan format berikut dalam `Decision_Log.md` atau laporan fase:

| ID | Pertanyaan | Mengapa diperlukan | Opsi/rekomendasi | Dampak jika ditunda | Status |
|---|---|---|---|---|---|
| Q-001 | Pertanyaan spesifik | Konteks keputusan | Opsi dan rekomendasi | Risiko/pekerjaan yang terblokir | Menunggu jawaban |

### Aturan persetujuan dan tindakan

- Rencana dan kode harus dipisahkan: persetujuan rencana tidak otomatis berarti persetujuan menjalankan semua perintah.
- Sebelum tindakan berisiko, destruktif, berbiaya, atau berdampak pada produksi, jelaskan tindakan dan minta persetujuan eksplisit.
- Jangan pernah mengklaim implementasi, pengujian, atau deployment berhasil jika belum benar-benar dijalankan dan diverifikasi.
- Setelah setiap kelompok perubahan, ikuti Bagian 13: buat changelog bertimestamp, perbarui `Task_Status.md`, dan sinkronkan `Test_Report.md` serta `Decision_Log.md` jika relevan.
- Setelah setiap fase, laporkan file yang berubah, pekerjaan yang selesai, hasil tes, masalah, pertanyaan terbuka, dan rekomendasi fase berikutnya.
- Jika pengguna mengoreksi keputusan, perbarui dokumentasi terlebih dahulu sebelum meneruskan implementasi.

---

## 13. Dokumentasi Pengerjaan, Status Tugas, dan Checklist Perubahan

Dokumentasi merupakan bagian wajib dari setiap pekerjaan, bukan pekerjaan opsional yang dilakukan hanya jika diminta. Tujuannya agar Yogi dan AI/developer berikutnya dapat memahami apa yang dikerjakan, alasan perubahan, status terkini, serta pekerjaan yang belum selesai.

### 13.1 Dokumen yang wajib dipelihara

| Dokumen | Fungsi | Kapan diperbarui |
|---|---|---|
| `Implementation_plan.md` | Rencana, fase, ruang lingkup, dependensi, dan kriteria penerimaan | Sebelum pekerjaan baru dimulai atau ketika rencana disetujui berubah |
| `Decision_Log.md` | Keputusan yang sudah disepakati, pertanyaan terbuka, opsi, dan persetujuan | Saat menemukan ketidakjelasan, mengajukan pertanyaan, atau menerima keputusan |
| `Task_Status.md` | Daftar tugas selesai, sedang dikerjakan, belum dikerjakan, terblokir, dan alasan/statusnya | Pada awal dan akhir setiap sesi kerja serta setelah perubahan signifikan |
| `Audit_Report.md` | Kondisi awal proyek, struktur, risiko, dan temuan audit | Setelah audit atau saat temuan penting berubah |
| `Test_Report.md` | Pengujian yang dijalankan, hasil, kegagalan, dan pengujian yang belum dilakukan | Setelah setiap rangkaian pengujian |
| `README.md` | Cara konfigurasi, menjalankan, menguji, dan memahami backend | Saat prosedur atau konfigurasi proyek berubah |
| `changelog/dd/bb/yyyy/jam/menit.md` | Catatan terperinci untuk setiap perubahan yang benar-benar dilakukan | Otomatis dibuat setelah setiap kelompok perubahan selesai |

Folder changelog menggunakan format nama bertingkat **`changelog/dd/bb/yyyy/jam/menit.md`**. Setiap bagian `dd`, `bb`, `yyyy`, `jam`, dan `menit` diganti dengan angka tanggal, bulan, tahun, jam, dan menit saat dokumentasi dibuat. Contoh struktur untuk 14 Oktober 2026 pukul 21:07 adalah `changelog/14/10/2026/21/07.md`. Jangan menggunakan tanda kurung pada nama folder atau file. Gunakan zona waktu lokal proyek/pengguna secara konsisten dan tulis zona waktu tersebut di isi changelog.

Jika lingkungan kerja tidak mendukung folder bertingkat atau proyek telah memiliki konvensi changelog yang wajib dipertahankan, berhenti dan tanyakan kepada Yogi sebelum mengubah format. Jika dua perubahan terjadi dalam menit yang sama dan path akan bertabrakan, jangan menimpa changelog lama; minta keputusan atau gunakan mekanisme tambahan yang disetujui untuk menjaga setiap catatan tetap unik.

### 13.2 Isi wajib setiap changelog

Setiap file changelog harus memuat:

1. Tanggal dan waktu perubahan beserta zona waktu.
2. Tujuan perubahan dan konteks tugas.
3. Ringkasan perubahan yang benar-benar dilakukan.
4. Daftar file yang dibuat, diubah, dipindahkan, atau dihapus.
5. Alasan perubahan serta kaitannya dengan fase dan tugas pada `Implementation_plan.md`.
6. Checklist perubahan yang direncanakan beserta status tiap butir.
7. Pengujian yang benar-benar dijalankan dan hasilnya.
8. Pengujian yang belum dijalankan, gagal, atau terblokir, berikut alasannya.
9. Daftar pekerjaan yang sudah selesai.
10. Daftar pekerjaan yang belum selesai dan langkah berikutnya.
11. Risiko, asumsi, bug, atau keputusan yang masih menunggu jawaban.
12. Apakah perubahan telah diverifikasi dan apakah persetujuan lanjutan masih dibutuhkan.

Template isi changelog:

```markdown
# Changelog — [judul singkat perubahan]

- Waktu: DD/MM/YYYY HH:MM [zona waktu]
- Fase: [nomor dan nama fase]
- Status: [Selesai / Sebagian / Terblokir]
- Terkait tugas: [ID atau ringkasan tugas]

## Tujuan
[Tujuan perubahan]

## Perubahan yang dilakukan
- [Perubahan nyata yang sudah dilakukan]

## File yang terdampak
- [Dibuat/diubah/dihapus] `path/file`

## Checklist
- [x] [Butir yang benar-benar selesai]
- [ ] [Butir yang belum selesai]
- [!] [Butir terblokir, sertai penjelasan]

## Pengujian
- [Nama pengujian]: [Lulus/Gagal/Belum dijalankan]
- Bukti atau ringkasan hasil: [hasil aktual]

## Pekerjaan yang belum selesai
- [Tugas, alasan, dan dependensi]

## Risiko dan pertanyaan terbuka
- [Risiko atau pertanyaan; tautkan/catat di Decision_Log.md]

## Langkah berikutnya
- [Tindakan berikut yang disarankan]

## Verifikasi dan persetujuan
- Verifikasi dilakukan: [Ya/Tidak/Parsial]
- Perlu persetujuan Yogi: [Ya/Tidak, jelaskan]
```

Checklist changelog harus mencerminkan keadaan nyata. Jangan memberi tanda selesai jika perubahan belum dilakukan atau pengujian belum dijalankan. Gunakan `[ ]` untuk belum selesai dan jelaskan butir terblokir dengan alasan yang eksplisit.

### 13.3 Prosedur dokumentasi otomatis setelah perubahan

Setelah setiap kelompok perubahan kode atau konfigurasi selesai, AI/developer wajib melakukan urutan berikut:

1. Periksa diff/status Git untuk mengidentifikasi perubahan yang benar-benar terjadi.
2. Jalankan pengujian yang relevan dan diizinkan; jangan mengklaim tes lulus jika belum dijalankan.
3. Buat file changelog baru pada struktur `changelog/dd/bb/yyyy/jam/menit.md`; jangan menimpa catatan sebelumnya.
4. Perbarui `Task_Status.md` dengan tugas selesai, parsial, terblokir, dan belum dimulai.
5. Perbarui `Test_Report.md` jika ada pengujian baru atau perubahan status pengujian.
6. Perbarui `Decision_Log.md` jika muncul keputusan atau pertanyaan baru.
7. Periksa konsistensi dokumentasi terhadap kode aktual dan daftar file yang berubah.
8. Laporkan kepada Yogi ringkasan perubahan, file terdampak, hasil tes, changelog yang dibuat, pekerjaan tersisa, dan persetujuan yang masih dibutuhkan.

Jika pekerjaan berhenti di tengah fase, buat catatan status/changelog yang menyebutkan dengan jelas apa yang sudah dilakukan dan apa yang belum. Jangan menunggu sampai seluruh fase selesai untuk mencatat perubahan yang sudah terjadi.

### 13.4 Ketentuan keamanan dan integritas dokumentasi

- Jangan memasukkan API key, token, kata sandi, private key, data pribadi siswa, atau isi rahasia ke changelog, laporan, screenshot, atau Git.
- Jangan mencatat klaim yang tidak didukung bukti. Bedakan `sudah dilakukan`, `direncanakan`, `belum diuji`, dan `terblokir`.
- Jangan menghapus atau menimpa dokumentasi lama untuk menyembunyikan riwayat perubahan.
- Jika waktu sistem atau zona waktu tidak dapat dipastikan, tanyakan atau catat bahwa waktu belum terverifikasi; jangan mengarang timestamp.
- Pembuatan dokumentasi otomatis berarti AI/developer membuat dan memperbarui berkas dokumentasi sebagai bagian dari alur kerja setelah perubahan, bukan menjalankan perubahan kode tanpa persetujuan.
- Jika alat atau lingkungan tidak mampu membuat file/folder changelog, laporkan keterbatasannya dan minta arahan; jangan mengklaim file sudah dibuat.

---

## 14. Kriteria penerimaan backend MVP

-   [ ] Tidak ada kunci API AI di frontend atau repositori publik.
-   [ ] Endpoint privat memverifikasi Firebase ID token.
-   [ ] Chat menggunakan Groq API, bukan respons simulasi lokal.
-   [ ] Respons AI divalidasi sebelum ditampilkan.
-   [ ] Struktur data blok melewati validasi sebelum dirender.
-   [ ] Riwayat percakapan dibatasi berdasarkan identitas pemilik.
-   [ ] Error provider, timeout, dan input tidak valid ditangani dengan
    aman.
-   [ ] Integrasi gambar dan suara hanya diaktifkan setelah
    model/runtime disetujui.
-   [ ] Dokumentasi konfigurasi, pengujian, dan deployment tersedia.
-   [ ] Prototype asli tidak dihapus atau ditimpa sebelum migrasi
    disetujui.

## 15. Keputusan terbuka sebelum implementasi

1.  Nama model teks Groq yang tepat sesuai Markdown rencana utama.
2.  Nama model vision OpenRouter yang tepat sesuai Markdown rencana
    utama.
3.  Model Whisper, runtime, dan lokasi pemrosesan audio.
4.  Hosting backend, versi PHP, serta mekanisme pengelolaan environment.
5.  Cara backend memverifikasi Firebase ID token dan mengakses
    Firestore.
6.  Skema final data percakapan dan skema blok Scratch Blocks.
7.  Kebijakan penyimpanan/penghapusan pesan, audio, dan gambar.
8.  Apakah endpoint hapus percakapan termasuk MVP.
9.  Batas ukuran file, durasi audio, timeout, dan rate limit.

Keputusan yang sudah ada di dokumen utama harus dipertahankan. Item
terbuka di atas tidak boleh diisi dengan tebakan.

## 16. Aturan kerja saat implementasi

-   Dokumen ini harus diperiksa dan disetujui pengguna terlebih dahulu.
-   Sebelum mengubah kode, buat atau perbarui `Implementation_plan.md`
    sesuai pekerjaan yang akan dilakukan.
-   Sebelum menjalankan perintah yang mengubah proyek, menjangkau
    layanan eksternal, memasang dependensi, atau memodifikasi database,
    jelaskan tindakan dan minta persetujuan pengguna.
-   Jangan menjalankan perintah destruktif atau menghapus file tanpa
    persetujuan eksplisit.
-   Jangan mengubah branch `main` atau melakukan merge/push ke `main`;
    pengguna yang menangani promosi ke `main`.
-   Dokumentasikan perubahan, file yang disentuh, hasil pengujian, serta
    pekerjaan yang belum selesai dalam Markdown.
-   Jika implementasi menemukan konflik dengan keputusan terdahulu,
    berhenti dan minta konfirmasi; jangan diam-diam mengubah arsitektur.

------------------------------------------------------------------------

**Status akhir dokumen: VERSI TETAP 1.0 — menjadi acuan kerja backend Ardena AI Scratch 3. Pertanyaan terbuka wajib diklarifikasi pada fase terkait. Persetujuan dokumen ini tidak otomatis mengizinkan tindakan berisiko, destruktif, berbiaya, atau deployment produksi. Belum ada implementasi yang dilakukan melalui pembuatan dokumen ini.**
