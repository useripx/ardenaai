# API Contract — Backend Ardena AI Scratch 3

**Versi:** 1.0  
**Tanggal:** 14 Oktober 2026  
**Status:** Ditetapkan (Acuan Pengembangan Fase 2 s.d. 9)  
**Base URL:** `http://localhost/ardenaai/backend/public/api`  
**Protokol:** HTTP/1.1 atau HTTPS  
**Format Konten:** `application/json` (Kecuali endpoint multipart audio & gambar)

---

## 1. Standar Header & Autentikasi

### 1.1 Header Request
- `Content-Type: application/json` (untuk request JSON)
- `Content-Type: multipart/form-data` (untuk upload audio di `transcribe.php` dan gambar di `vision.php`)
- `Authorization: Bearer <firebase_id_token>` (wajib untuk seluruh endpoint privat)

### 1.2 Format Respons Standar
Seluruh endpoint mengembalikan JSON dengan struktur konsisten:

#### Sukses (`200 OK` / `201 Created`):
```json
{
  "success": true,
  "data": {},
  "message": "Deskripsi singkat hasil operasi",
  "meta": {
    "timestamp": "2026-10-14T23:00:00+07:00",
    "model_used": "openai/gpt-oss-120b"
  }
}
```

#### Gagal (`400`, `401`, `403`, `404`, `429`, `500`):
```json
{
  "success": false,
  "error": {
    "code": "KODE_ERROR_STANDAR",
    "message": "Pesan ramah anak yang dapat dipahami siswa",
    "details": null
  }
}
```

---

## 2. Rincian Kontrak Endpoint

### 2.1 Pemeriksaan Kesehatan Sistem
- **Method:** `GET`
- **Path:** `/health.php`
- **Akses:** Publik (tanpa token)
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "status": "healthy",
    "php_version": "8.4.15",
    "environment": "local",
    "groq_connection": "ready"
  },
  "message": "Backend Ardena AI siap melayani!"
}
```

---

### 2.2 Percakapan AI & Logika Scratch (Chat)
- **Method:** `POST`
- **Path:** `/chat.php`
- **Akses:** Privat (membutuhkan Bearer Firebase ID Token)
- **Request Body:**
```json
{
  "message": "Bagaimana cara membuat kucing melompat saat tombol spasi ditekan?",
  "conversation_id": "opsional_string_id_percakapan",
  "history": [
    {
      "role": "user",
      "content": "Halo Ardena!"
    },
    {
      "role": "assistant",
      "content": "Halo juga sahabat cilik! Mau bikin game apa hari ini?"
    }
  ]
}
```
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "answer": "Pertanyaan hebat! Untuk membuat Kucing melompat, kita pakai blok 'ketika tombol [spasi] ditekan' lalu ubah koordinat Y kucing ke atas dan turun lagi.",
    "steps": [
      "Ambil blok kuning 'ketika tombol [spasi] ditekan' dari kategori Kejadian.",
      "Sambungkan dengan blok biru 'ubah y sebesar ( 50 )' dari kategori Gerakan.",
      "Tunggu sebentar dengan blok 'tunggu ( 0.2 ) detik'.",
      "Pasang blok biru 'ubah y sebesar ( -50 )' agar kucing mendarat kembali ke lantai."
    ],
    "blocks": [
      {
        "id": "blk_events_space",
        "category": "events",
        "opcode": "event_whenkeypressed",
        "fields": { "KEY_OPTION": "space" },
        "inputs": {},
        "label": "ketika tombol [ spasi ] ditekan"
      },
      {
        "id": "blk_motion_up",
        "category": "motion",
        "opcode": "motion_changeyby",
        "fields": {},
        "inputs": { "DY": 50 },
        "label": "ubah y sebesar ( 50 )"
      },
      {
        "id": "blk_motion_down",
        "category": "motion",
        "opcode": "motion_changeyby",
        "fields": {},
        "inputs": { "DY": -50 },
        "label": "ubah y sebesar ( -50 )"
      }
    ],
    "follow_up_question": "Mau coba kita tambahkan suara meong saat kucing mendarat?"
  },
  "meta": {
    "model_used": "openai/gpt-oss-120b",
    "fallback_level": 0
  }
}
```

---

### 2.3 Input Suara Siswa (Speech-to-Text)
- **Method:** `POST`
- **Path:** `/transcribe.php`
- **Akses:** Privat (membutuhkan Bearer Firebase ID Token)
- **Content-Type:** `multipart/form-data`
- **Form Data:**
  - `audio`: Berkas audio (`.webm`, `.wav`, `.mp3`, `.m4a`), maksimal 10 MB.
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "text": "Bagaimana cara membuat skor bertambah saat koin tersentuh?",
    "duration_seconds": 3.4
  },
  "message": "Suara berhasil diubah menjadi teks!"
}
```

---

### 2.4 Analisis Foto Blok Scratch (Vision / OCR)
- **Method:** `POST`
- **Path:** `/vision.php`
- **Akses:** Privat (membutuhkan Bearer Firebase ID Token)
- **Content-Type:** `multipart/form-data`
- **Form Data:**
  - `image`: Berkas gambar (`.png`, `.jpg`, `.jpeg`, `.webp`), maksimal 5 MB.
  - `prompt`: (Opsional) Pertanyaan tambahan dari siswa, contoh: *"Tolong periksa kenapa kodinganku tidak jalan?"*
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "detected_blocks_summary": "Ditemukan 3 blok: Kejadian bendera hijau, Selamanya, dan Gerak 10 langkah.",
    "analysis": "Kodinganmu sudah rapi! Namun kucing belum memantul saat mentok di pinggir layar.",
    "recommendation": "Tambahkan blok biru 'jika di pinggir, pantulkan' di dalam blok selamanya.",
    "suggested_blocks": [
      {
        "id": "blk_bounce",
        "category": "motion",
        "opcode": "motion_ifonedgebounce",
        "label": "jika di pinggir, pantulkan"
      }
    ]
  },
  "message": "Foto blok kodinganmu berhasil diperiksa!"
}
```

---

### 2.5 Daftar Riwayat Percakapan
- **Method:** `GET`
- **Path:** `/conversations.php`
- **Akses:** Privat (membutuhkan Bearer Firebase ID Token)
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "conversations": [
      {
        "id": "conv_98231",
        "title": "Bikin Kucing Lompat",
        "preview": "Untuk membuat Kucing melompat, kita pakai...",
        "updated_at": "2026-10-14T21:30:00+07:00",
        "total_messages": 6
      }
    ]
  }
}
```

---

### 2.6 Detail Satu Percakapan
- **Method:** `GET`
- **Path:** `/conversation.php?id={conversation_id}`
- **Akses:** Privat (membutuhkan Bearer Firebase ID Token)
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "id": "conv_98231",
    "title": "Bikin Kucing Lompat",
    "messages": [
      {
        "id": "msg_1",
        "sender": "user",
        "content": "Ardena, gimana caranya biar Kucing Scratch bisa lompat?",
        "timestamp": "2026-10-14T21:28:00+07:00"
      },
      {
        "id": "msg_2",
        "sender": "assistant",
        "content": "Wah, pertanyaan yang keren sekali! 🌟...",
        "blocks": [ ... ],
        "timestamp": "2026-10-14T21:28:02+07:00"
      }
    ]
  }
}
```

---

### 2.7 Simpan Percakapan
- **Method:** `POST`
- **Path:** `/conversation-save.php`
- **Akses:** Privat (membutuhkan Bearer Firebase ID Token)
- **Request Body:**
```json
{
  "conversation_id": "conv_98231",
  "title": "Bikin Kucing Lompat",
  "messages": [ ... ]
}
```
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "conversation_id": "conv_98231",
    "saved_at": "2026-10-14T23:05:00+07:00"
  },
  "message": "Percakapan berhasil disimpan!"
}
```

---

### 2.8 Hapus Percakapan
- **Method:** `DELETE`
- **Path:** `/conversation-delete.php?id={conversation_id}`
- **Akses:** Privat (membutuhkan Bearer Firebase ID Token)
- **Respons `200 OK`:**
```json
{
  "success": true,
  "data": {
    "deleted_id": "conv_98231"
  },
  "message": "Percakapan berhasil dihapus."
}
```

---

## 3. Daftar Kode Error Resmi

| HTTP Status | Error Code | Makna | Pesan Ramah Pengguna |
|---|---|---|---|
| `400` | `INVALID_INPUT` | Input tidak valid / data hilang | "Oops! Pesan atau file yang kamu kirim belum lengkap nih. Coba periksa lagi ya! 🧐" |
| `401` | `UNAUTHORIZED` | Token tidak ada atau kedaluwarsa | "Sesi belajarmu sudah selesai. Masuk kembali untuk melanjutkan ya! 🔑" |
| `403` | `FORBIDDEN` | Mencoba akses data milik user lain | "Kamu tidak memiliki akses untuk membuka riwayat percakapan ini. 🚫" |
| `413` | `FILE_TOO_LARGE` | Ukuran gambar / audio melebihi batas | "Waduh, ukuran berkasmu terlalu besar. Maksimal 10MB untuk audio dan 5MB untuk foto ya! 📁" |
| `415` | `UNSUPPORTED_MEDIA` | Format file tidak didukung | "Format berkas tidak dikenali. Gunakan foto JPG/PNG atau rekaman audio WEBM/WAV ya! 🎨" |
| `429` | `RATE_LIMIT_EXCEEDED` | Kuota atau batas permintaan terlampaui | "Halo! Ardena sedang banyak yang tanya nih. Tunggu beberapa detik lalu kirim lagi ya! 🌟" |
| `500` | `INTERNAL_SERVER_ERROR` | Kesalahan server internal | "Ardena sedang beristirahat sejenak. Jangan sedih, coba klik tombol kirim lagi ya! 🤖" |
| `503` | `AI_SERVICE_UNAVAILABLE` | Seluruh model Groq gagal | "Layanan AI sedang sibuk. Sahabat Ardena akan segera kembali! ⏳" |
