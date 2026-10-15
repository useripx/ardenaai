<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta content="web_standard" name="shell-type"/>
  <title>Tanya Ardena — Asisten AI Belajar Scratch 3 Ramah Anak</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  
  <style>
    @layer base {
      html, body { margin: 0; padding: 0; }
      body { overscroll-behavior: none; }
    }
    ::-webkit-scrollbar { display: none; }
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <script src="https://cdn.tailwindcss.com"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#005db6",
            "primary-fixed": "#d6e3ff",
            "on-primary": "#ffffff",
            "scratch-motion": "#4C97FF",
            "scratch-looks": "#9966FF",
            "scratch-sound": "#CF63CF",
            "scratch-events": "#FFBF00",
            "scratch-control": "#FFAB19",
            "scratch-operators": "#59C059",
            "scratch-sensing": "#5CB1D6",
            "scratch-variables": "#FF8C1A",
            "canvas-panel": "#E9F1FC",
            "stage-bg": "#F9F9F9",
            "on-surface": "#0d1c32",
            "on-surface-variant": "#414753",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f0f3ff",
            "surface-container": "#e7eeff",
            "error": "#ba1a1a",
            "outline": "#717784"
          },
          fontFamily: {
            sans: ["Nunito Sans", "sans-serif"]
          }
        }
      }
    };
  </script>
</head>
<body class="bg-stage-bg font-sans text-on-surface min-h-screen">
  <!-- Top Navigation Header -->
  <header class="fixed top-0 w-full z-50 bg-scratch-motion text-on-primary shadow-[0_4px_12px_rgba(76,151,255,0.25)]">
    <div class="h-20 w-full px-4 sm:px-8 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-surface-container-lowest flex items-center justify-center shadow-sm">
          <span class="material-symbols-outlined text-scratch-motion text-[28px]">extension</span>
        </div>
        <div class="flex flex-col">
          <div class="flex items-center gap-2">
            <span class="text-xl sm:text-2xl text-on-primary font-extrabold tracking-tight">Ardena</span>
            <span class="hidden sm:inline-block text-lg text-primary-fixed opacity-90 font-bold">- Teman Belajar Scratch 3</span>
          </div>
          <div class="flex items-center gap-1">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-scratch-operators text-on-primary text-[11px] font-bold tracking-wide shadow-sm">
              <span class="w-2 h-2 rounded-full bg-surface-container-lowest animate-pulse"></span>
              Online - AI Terhubung!
            </span>
          </div>
        </div>
      </div>

      <nav class="hidden md:flex items-center gap-2 bg-black/10 p-1.5 rounded-xl">
        <a class="px-4 py-1.5 rounded-lg bg-surface-container-lowest text-primary font-bold shadow-sm" href="/">Tanya Ardena</a>
        <a class="px-4 py-1.5 rounded-lg text-white/90 hover:bg-white/10 font-bold transition-all" href="/riwayat">Riwayat Obrolan</a>
        <a class="px-4 py-1.5 rounded-lg text-white/90 hover:bg-white/10 font-bold transition-all" href="/pengaturan">Pengaturan</a>
      </nav>

      <div class="flex items-center gap-2 sm:gap-3">
        <div class="flex items-center bg-white/20 rounded-xl p-1 gap-1">
          <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container-lowest text-primary font-bold shadow-sm active:translate-y-0.5" onclick="adjustFontSize(-1)" title="Kecilkan Teks" type="button">A-</button>
          <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container-lowest text-primary font-bold shadow-sm active:translate-y-0.5" onclick="adjustFontSize(1)" title="Besarkan Teks" type="button">A+</button>
        </div>
        
        <!-- User Auth Badge / Login Action -->
        <div id="authHeaderContainer" class="flex items-center gap-2">
          <a href="/login" id="headerLoginBtn" class="px-3 py-1.5 rounded-xl bg-surface-container-lowest text-primary font-bold text-sm shadow-sm hover:brightness-105 transition-all flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px]">login</span>
            <span>Masuk</span>
          </a>
          <div id="headerUserBadge" class="hidden items-center gap-2 bg-white/20 px-3 py-1 rounded-xl">
            <a href="/pengaturan" class="flex items-center gap-1.5 text-white hover:opacity-90 transition-opacity" title="Buka Pengaturan Akun">
              <span id="headerUserAvatar" class="text-base">🐱</span>
              <span id="headerUserName" class="text-sm font-bold text-white max-w-[120px] truncate">Siswa</span>
            </a>
            <a href="/pengaturan" class="w-7 h-7 rounded-lg bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition-colors" title="Pengaturan">
              <span class="material-symbols-outlined text-[16px]">settings</span>
            </a>
            <button onclick="handleLogout()" class="w-7 h-7 rounded-lg bg-red-500/80 hover:bg-red-600 text-white flex items-center justify-center transition-colors" title="Keluar Akun">
              <span class="material-symbols-outlined text-[16px]">logout</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Canvas / Workspace -->
  <main class="w-full pt-24 pb-32 bg-canvas-panel bg-[radial-gradient(#d6e3ff_1.5px,transparent_1.5px)] [background-size:20px_20px] min-h-[calc(100vh-80px)]">
    <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 flex flex-col gap-6">
      
      <!-- HERO WELCOME & MASCOT BANNER -->
      <section class="relative w-full rounded-2xl bg-surface-container-lowest p-6 shadow-sm overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-primary-fixed/30 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
          <div class="flex-1 flex flex-col gap-2 text-center md:text-left">
            <div class="inline-flex items-center gap-2 mx-auto md:mx-0 px-3 py-1 rounded-full bg-primary-fixed text-primary text-xs font-bold w-fit">
              <span class="material-symbols-outlined text-[16px] text-scratch-events">star</span>
              <span>Ruang Belajar Scratch 3 Ramah Anak</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight" id="mainHeading">
              Halo Programmer Cilik! Mau bikin game apa hari ini? 🚀
            </h1>
            <p class="text-base text-on-surface-variant max-w-2xl">
              Tanya Ardena apa saja tentang Scratch 3—blok gerak, suara seru, skor variabel, atau cukup unggah foto blok kodinganmu untuk dicek bareng!
            </p>
            
            <!-- Quick Action Chips -->
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-2">
              <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-scratch-motion text-on-primary text-xs sm:text-sm font-bold shadow-[0_3px_0_#2b73db] hover:translate-y-[-1px] active:translate-y-[2px] transition-all" onclick="insertQuestion('Gimana cara bikin Kucing Scratch jalan bolak-balik?')" type="button">
                <span class="material-symbols-outlined text-[18px]">pets</span>
                <span>🐱 Bikin Kucing Jalan</span>
              </button>
              <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-scratch-looks text-on-primary text-xs sm:text-sm font-bold shadow-[0_3px_0_#7b4cd9] hover:translate-y-[-1px] active:translate-y-[2px] transition-all" onclick="insertQuestion('Cara ganti Latar Belakang (Backdrop) saat menang game gimana?')" type="button">
                <span class="material-symbols-outlined text-[18px]">palette</span>
                <span>🎨 Ganti Latar (Backdrop)</span>
              </button>
              <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-scratch-sound text-on-primary text-xs sm:text-sm font-bold shadow-[0_3px_0_#a848a8] hover:translate-y-[-1px] active:translate-y-[2px] transition-all" onclick="insertQuestion('Gimana cara nambahin efek suara menang atau meong di Scratch 3?')" type="button">
                <span class="material-symbols-outlined text-[18px]">volume_up</span>
                <span>🔊 Tambah Efek Suara</span>
              </button>
              <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-scratch-operators text-on-primary text-xs sm:text-sm font-bold shadow-[0_3px_0_#3f9c3f] hover:translate-y-[-1px] active:translate-y-[2px] transition-all" onclick="triggerPhotoModal()" type="button">
                <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                <span>📷 Cek Foto Kodinganku</span>
              </button>
              <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-scratch-control text-on-primary text-xs sm:text-sm font-bold shadow-[0_3px_0_#d88c0a] hover:translate-y-[-1px] active:translate-y-[2px] transition-all" onclick="insertQuestion('Bagaimana cara bikin variabel skor nambah 1 tiap koin diambil?')" type="button">
                <span class="material-symbols-outlined text-[18px]">emoji_events</span>
                <span>🏆 Tambah Variabel Skor</span>
              </button>
            </div>
          </div>

          <!-- Mascot Sprite SVG -->
          <div class="flex-shrink-0 flex flex-col items-center gap-2 bg-primary-fixed/20 p-4 rounded-2xl">
            <div class="relative w-28 h-28 flex items-center justify-center">
              <svg class="w-24 h-24 drop-shadow-md" fill="none" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                <circle cx="60" cy="60" fill="#FFAB19" r="54"></circle>
                <circle cx="60" cy="60" fill="#FFBF00" r="48"></circle>
                <path d="M30 40 L42 16 L54 36 Z" fill="#FF8C1A"></path>
                <path d="M90 40 L78 16 L66 36 Z" fill="#FF8C1A"></path>
                <ellipse cx="60" cy="65" fill="#FFAB19" rx="38" ry="32"></ellipse>
                <ellipse cx="60" cy="70" fill="#FFFFFF" rx="26" ry="20"></ellipse>
                <ellipse cx="46" cy="58" fill="#FFFFFF" rx="8" ry="11"></ellipse>
                <circle cx="48" cy="58" fill="#0D1C32" r="5"></circle>
                <ellipse cx="74" cy="58" fill="#FFFFFF" rx="8" ry="11"></ellipse>
                <circle cx="72" cy="58" fill="#0D1C32" r="5"></circle>
                <polygon fill="#FF4D4D" points="60,68 56,73 64,73"></polygon>
                <path d="M60 73 Q55 80 50 76 M60 73 Q65 80 70 76" stroke="#0D1C32" stroke-linecap="round" stroke-width="2.5"></path>
              </svg>
              <span class="absolute -bottom-1 -right-1 px-2 py-0.5 rounded-full bg-scratch-operators text-on-primary text-xs font-bold shadow-sm flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-surface-container-lowest animate-ping"></span>
                Aktif
              </span>
            </div>
            <span class="text-xs font-bold text-primary">Sprite Sahabat Ardena</span>
          </div>
        </div>
      </section>

      <!-- CONVERSATION HEADER / AUTO-SAVE STATUS BAR -->
      <div class="flex items-center justify-between px-2 text-xs">
        <div class="flex items-center gap-2">
          <span id="convTitleDisplay" class="font-bold text-on-surface flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[18px] text-scratch-motion">chat_bubble</span>
            <span id="convTitleText">Sesi Obrolan Baru</span>
          </span>
          <span id="historyBadge" class="hidden px-2 py-0.5 rounded-full bg-blue-100 text-primary font-bold text-[11px]">Riwayat Obrolan Lampau</span>
        </div>
        <div id="saveStatusIndicator" class="flex items-center gap-1.5 font-bold text-slate-500 bg-white/70 px-3 py-1 rounded-full shadow-sm">
          <span id="saveStatusDot" class="w-2 h-2 rounded-full bg-green-500"></span>
          <span id="saveStatusText">Tersimpan otomatis 💾</span>
        </div>
      </div>

      <!-- CHAT CONVERSATION CONTAINER -->
      <div class="w-full flex flex-col gap-6" id="chatHistory">
        <!-- Initial Ardena Welcome Message -->
        <div class="flex items-start justify-start gap-3 sm:gap-4 pr-4 sm:pr-12 fade-in">
          <div class="relative w-12 h-12 rounded-2xl bg-surface-container-lowest shadow-sm flex items-center justify-center p-1 flex-shrink-0">
            <span class="material-symbols-outlined text-scratch-motion text-[32px]">smart_toy</span>
            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-scratch-operators flex items-center justify-center">
              <span class="material-symbols-outlined text-[10px] text-on-primary font-bold">bolt</span>
            </div>
          </div>
          <div class="flex-1 max-w-2xl flex flex-col gap-1">
            <div class="flex items-center gap-2">
              <span class="text-lg font-bold text-on-surface">Ardena AI</span>
              <span class="px-2 py-0.5 rounded-full bg-purple-100 text-scratch-looks text-xs font-bold">Teman Coding</span>
            </div>
            <div class="bg-surface-container-lowest p-5 rounded-2xl rounded-tl-none shadow-[0_4px_16px_rgba(0,93,182,0.08)] flex flex-col gap-3">
              <p class="text-base text-on-surface leading-relaxed">
                Halo! Aku Ardena, teman belajarmu di Scratch 3! 🎉 Kamu bisa tanya cara menggerakkan karakter, bikin skor, atau klik tombol 🎙️ mikrofon untuk bicara langsung!
              </p>
            </div>
          </div>
        </div>

        <!-- Thinking Bubble (Dynamic Loading State) -->
        <div class="hidden flex items-center gap-3 pr-12 fade-in" id="thinkingBubble">
          <div class="w-10 h-10 rounded-2xl bg-surface-container-lowest shadow-sm flex items-center justify-center p-1 flex-shrink-0">
            <span class="material-symbols-outlined text-scratch-motion text-[24px]">smart_toy</span>
          </div>
          <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl rounded-tl-none bg-surface-container-lowest shadow-sm">
            <span class="text-sm font-bold text-primary">Ardena sedang menyusun blok</span>
            <div class="flex items-center gap-1">
              <span class="w-2.5 h-2.5 rounded-full bg-scratch-looks animate-bounce"></span>
              <span class="w-2.5 h-2.5 rounded-full bg-scratch-motion animate-bounce [animation-delay:0.15s]"></span>
              <span class="w-2.5 h-2.5 rounded-full bg-scratch-events animate-bounce [animation-delay:0.3s]"></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Uploaded Image Preview Tray -->
      <div class="hidden w-full bg-surface-container-lowest p-3 rounded-2xl shadow-md flex items-center justify-between gap-4 fade-in" id="imagePreviewTray">
        <div class="flex items-center gap-3">
          <div class="relative w-16 h-16 rounded-xl overflow-hidden bg-surface-container flex-shrink-0">
            <img class="w-full h-full object-cover" id="previewImgSlot" src=""/>
          </div>
          <div class="flex flex-col">
            <span class="font-bold text-sm text-on-surface">Foto Kodingan Siap Diperiksa! 📸</span>
            <span class="text-xs text-scratch-operators font-semibold">Ardena akan membaca susunan blok Scratch di fotomu.</span>
          </div>
        </div>
        <button class="px-3 py-1.5 rounded-xl text-error font-bold text-xs hover:bg-red-50" onclick="removeImagePreview()" type="button">
          Batal
        </button>
      </div>

    </div>
  </main>

  <!-- Hidden Real File Input -->
  <input accept="image/*" class="hidden" id="realFileInput" onchange="handleRealImageChosen(event)" type="file"/>

  <!-- BOTTOM FLOATING ACTION BAR -->
  <div class="fixed bottom-4 left-0 right-0 z-40 max-w-4xl mx-auto px-4">
    <div class="w-full p-2 bg-surface-container-lowest rounded-full shadow-[0_8px_28px_rgba(0,93,182,0.15)] flex items-center gap-2">
      <!-- Voice Input (Groq Whisper) -->
      <button class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-scratch-sound text-on-primary flex items-center justify-center shadow-[0_3px_0_#a848a8] active:translate-y-[2px] transition-all flex-shrink-0" id="voiceBtn" onclick="toggleVoiceRecording()" title="Bicara dengan Suara (Mic)" type="button">
        <span class="material-symbols-outlined text-[24px]" id="voiceIcon">mic</span>
        <span class="hidden absolute inset-0 rounded-full bg-scratch-sound animate-ping opacity-60 pointer-events-none" id="voicePulse"></span>
      </button>

      <!-- Photo Input (Groq Vision) -->
      <button class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-scratch-operators text-on-primary flex items-center justify-center shadow-[0_3px_0_#3f9c3f] active:translate-y-[2px] transition-all flex-shrink-0" onclick="triggerPhotoModal()" title="Upload Foto Blok Scratch" type="button">
        <span class="material-symbols-outlined text-[24px]">photo_camera</span>
      </button>

      <!-- Text Input -->
      <div class="flex-1 px-2">
        <input class="w-full h-11 bg-transparent text-on-surface font-semibold placeholder:text-outline focus:outline-none" id="userChatInput" placeholder="Ketik pertanyaanmu di sini... (Contoh: Cara bikin kucing lompat)" type="text"/>
      </div>

      <!-- Send Button -->
      <button class="h-12 sm:h-14 px-5 sm:px-7 rounded-full bg-scratch-events text-on-tertiary-container font-extrabold flex items-center gap-2 shadow-[0_4px_0_#d9a300] active:translate-y-[2px] transition-all flex-shrink-0" onclick="handleSendMessage()" title="Kirim Pertanyaan" type="button">
        <span>Kirim</span>
        <span class="material-symbols-outlined text-scratch-operators text-[24px]" style="font-variation-settings: 'FILL' 1;">flag</span>
      </button>
    </div>
  </div>

  <script>
    // Konfigurasi Kredensial Siswa & ID Sesi
    const AUTH_TOKEN = localStorage.getItem('ardena_auth_token') || 'dev_siswa_andi';
    const urlParams = new URLSearchParams(window.location.search);
    const queryConvId = urlParams.get('id');
    let CONVERSATION_ID = queryConvId || ('conv_' + Date.now());
    let messageHistory = [];
    let isRecording = false;
    let mediaRecorder = null;
    let audioChunks = [];
    let selectedImageFile = null;

    function getAuthToken() {
      return localStorage.getItem('ardena_auth_token') || AUTH_TOKEN;
    }

    function insertQuestion(text) {
      const input = document.getElementById('userChatInput');
      input.value = text;
      input.focus();
    }

    // 1. CHAT TEXT ENGINE
    async function handleSendMessage() {
      const input = document.getElementById('userChatInput');
      const text = input ? input.value.trim() : '';

      // Jika ada gambar yang sedang dipilih, proses analisis gambar via Vision API
      if (selectedImageFile) {
        uploadImageToVision(selectedImageFile, text);
        return;
      }

      if (!text) return;

      appendUserBubble(text);
      input.value = '';
      showThinking(true);

      try {
        const response = await fetch('/api/chat', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
          },
          body: JSON.stringify({
            message: text,
            conversation_id: CONVERSATION_ID,
            history: messageHistory
          })
        });

        const result = await response.json();
        showThinking(false);

        if (result.success && result.data) {
          appendArdenaBubble(result.data);
          messageHistory.push({ role: 'user', content: text });
          messageHistory.push({ role: 'assistant', content: result.data.answer });
          saveConversationToBackend(CONVERSATION_ID, text, messageHistory);
        } else {
          appendErrorBubble(result.error?.message || 'Maaf, Ardena sedang banyak yang tanya nih. Coba klik kirim lagi ya! 🤖');
        }
      } catch (err) {
        showThinking(false);
        appendErrorBubble('Gagal terhubung ke server backend. Periksa koneksi lokal ya!');
      }
    }

    // 2. SPEECH-TO-TEXT (Groq Whisper)
    async function toggleVoiceRecording() {
      const pulse = document.getElementById('voicePulse');
      const icon = document.getElementById('voiceIcon');
      const input = document.getElementById('userChatInput');

      if (!isRecording) {
        try {
          const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
          mediaRecorder = new MediaRecorder(stream);
          audioChunks = [];

          mediaRecorder.ondataavailable = e => {
            if (e.data.size > 0) audioChunks.push(e.data);
          };

          mediaRecorder.onstop = async () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
            stream.getTracks().forEach(track => track.stop());
            await uploadAudioForTranscription(audioBlob);
          };

          mediaRecorder.start();
          isRecording = true;
          pulse.classList.remove('hidden');
          icon.innerText = 'graphic_eq';
          input.placeholder = "Sedang mendengarkan suaramu... Bicara sekarang ya!";
        } catch (e) {
          alert("Izin mikrofon diperlukan untuk menggunakan fitur suara.");
        }
      } else {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
          mediaRecorder.stop();
        }
        isRecording = false;
        pulse.classList.add('hidden');
        icon.innerText = 'mic';
        input.placeholder = "Ketik pertanyaanmu di sini...";
      }
    }

    async function uploadAudioForTranscription(audioBlob) {
      showThinking(true);
      const formData = new FormData();
      formData.append('audio', audioBlob, 'voice.webm');

      try {
        const res = await fetch('/api/transcribe', {
          method: 'POST',
          headers: {
            'Authorization': 'Bearer ' + getAuthToken()
          },
          body: formData
        });
        const result = await res.json();
        showThinking(false);

        if (result.success && result.data?.text) {
          document.getElementById('userChatInput').value = result.data.text;
          handleSendMessage();
        } else {
          appendErrorBubble(result.error?.message || 'Suaramu belum terdengar jelas. Coba bicara lebih dekat ya!');
        }
      } catch (e) {
        showThinking(false);
        appendErrorBubble('Gagal mengirim rekaman suara.');
      }
    }

    // 3. VISION IMAGE ANALYSIS (Groq Vision)
    function triggerPhotoModal() {
      document.getElementById('realFileInput').click();
    }

    function handleRealImageChosen(event) {
      const file = event.target.files[0];
      if (!file) return;

      selectedImageFile = file;
      const tray = document.getElementById('imagePreviewTray');
      const previewImg = document.getElementById('previewImgSlot');

      const reader = new FileReader();
      reader.onload = e => {
        previewImg.src = e.target.result;
        tray.classList.remove('hidden');
        insertQuestion('Ardena, tolong periksa foto blok kodingan ini apakah sudah benar?');
      };
      reader.readAsDataURL(file);
    }

    function removeImagePreview() {
      selectedImageFile = null;
      document.getElementById('imagePreviewTray').classList.add('hidden');
      document.getElementById('realFileInput').value = '';
    }

    async function uploadImageToVision(file, promptText) {
      removeImagePreview();
      appendUserBubble(promptText || 'Tolong periksa foto kodinganku ini!');
      showThinking(true);

      const formData = new FormData();
      formData.append('image', file);
      formData.append('prompt', promptText);

      try {
        const res = await fetch('/api/vision', {
          method: 'POST',
          headers: {
            'Authorization': 'Bearer ' + getAuthToken()
          },
          body: formData
        });
        const result = await res.json();
        showThinking(false);

        if (result.success && result.data) {
          appendVisionBubble(result.data);
          const userPrompt = promptText || 'Tolong periksa foto kodinganku ini!';
          messageHistory.push({ role: 'user', content: userPrompt });
          messageHistory.push({
            role: 'assistant',
            content: result.data.analysis || result.data.detected_blocks_summary || 'Hasil analisis foto blok Scratch',
            vision_data: result.data
          });
          saveConversationToBackend(CONVERSATION_ID, 'Foto Blok: ' + (result.data.detected_blocks_summary || userPrompt), messageHistory);
        } else {
          appendErrorBubble(result.error?.message || 'Gagal membaca foto blok Scratch.');
        }
      } catch (e) {
        showThinking(false);
        appendErrorBubble('Terjadi kendala saat mengirim gambar ke server.');
      }
    }

    // 4. BUBBLE RENDERERS
    function appendUserBubble(text) {
      const chatHistory = document.getElementById('chatHistory');
      const thinking = document.getElementById('thinkingBubble');

      const bubble = document.createElement('div');
      bubble.className = "flex items-start justify-end gap-3 sm:gap-4 pl-8 fade-in";
      bubble.innerHTML = `
        <div class="flex flex-col items-end gap-1 max-w-xl">
          <span class="text-xs text-on-surface-variant font-bold flex items-center gap-1">
            Kamu (Scratcher Cilik)
            <span class="material-symbols-outlined text-[14px] text-scratch-motion">face</span>
          </span>
          <div class="relative bg-scratch-motion text-on-primary p-4 rounded-2xl rounded-tr-none shadow-[0_4px_0_#2b73db]">
            <p class="text-base font-bold leading-relaxed">${escapeHtml(text)}</p>
          </div>
          <span class="text-[11px] text-outline">Baru saja • Terkirim</span>
        </div>
        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold shadow-sm flex-shrink-0">
          <span class="material-symbols-outlined text-[22px]">sentiment_very_satisfied</span>
        </div>
      `;

      chatHistory.insertBefore(bubble, thinking);
      window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function appendArdenaBubble(data) {
      const chatHistory = document.getElementById('chatHistory');
      const thinking = document.getElementById('thinkingBubble');

      let stepsHtml = '';
      if (data.steps && data.steps.length) {
        stepsHtml = `
          <div class="flex flex-col gap-1.5 bg-surface-container-low p-3 rounded-xl mt-2">
            <span class="text-xs font-bold text-primary">📝 Langkah Pengerjaan:</span>
            <ul class="text-sm text-on-surface list-disc list-inside space-y-1">
              ${data.steps.map(s => `<li>${escapeHtml(s)}</li>`).join('')}
            </ul>
          </div>
        `;
      }

      let blocksHtml = '';
      if (data.blocks && data.blocks.length) {
        blocksHtml = `
          <div class="bg-canvas-panel p-4 rounded-xl flex flex-col gap-2 mt-2">
            <div class="flex items-center justify-between pb-1">
              <span class="text-xs font-bold text-primary flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">extension</span>
                Susunan Blok Scratch 3:
              </span>
              <span class="text-xs text-on-surface-variant font-semibold">${data.blocks.length} Blok Puzzle</span>
            </div>
            ${data.blocks.map(b => `
              <div style="background-color: ${b.color || '#4C97FF'}" class="w-fit flex items-center gap-2 px-4 py-2 rounded-lg text-white font-bold text-sm shadow-[0_3px_0_rgba(0,0,0,0.2)]">
                <span class="material-symbols-outlined text-[18px]">extension</span>
                <span>${escapeHtml(b.label)}</span>
              </div>
            `).join('')}
          </div>
        `;
      }

      const bubble = document.createElement('div');
      bubble.className = "flex items-start justify-start gap-3 sm:gap-4 pr-4 sm:pr-12 fade-in";
      bubble.innerHTML = `
        <div class="relative w-12 h-12 rounded-2xl bg-surface-container-lowest shadow-sm flex items-center justify-center p-1 flex-shrink-0">
          <span class="material-symbols-outlined text-scratch-motion text-[32px]">smart_toy</span>
          <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-scratch-operators flex items-center justify-center">
            <span class="material-symbols-outlined text-[10px] text-on-primary font-bold">bolt</span>
          </div>
        </div>
        <div class="flex-1 max-w-2xl flex flex-col gap-1">
          <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-on-surface">Ardena AI</span>
            <span class="px-2 py-0.5 rounded-full bg-purple-100 text-scratch-looks text-xs font-bold">Teman Coding</span>
          </div>
          <div class="bg-surface-container-lowest p-5 rounded-2xl rounded-tl-none shadow-[0_4px_16px_rgba(0,93,182,0.08)] flex flex-col gap-3">
            <p class="text-base text-on-surface leading-relaxed">${escapeHtml(data.answer)}</p>
            ${blocksHtml}
            ${stepsHtml}
            ${data.follow_up_question ? `<p class="text-xs font-bold text-scratch-operators mt-1">💡 ${escapeHtml(data.follow_up_question)}</p>` : ''}
          </div>
          <span class="text-[11px] text-outline">Baru saja • Dijawab oleh Ardena</span>
        </div>
      `;

      chatHistory.insertBefore(bubble, thinking);
      window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function appendVisionBubble(data) {
      const chatHistory = document.getElementById('chatHistory');
      const thinking = document.getElementById('thinkingBubble');

      let suggestedBlocksHtml = '';
      if (data.suggested_blocks && data.suggested_blocks.length) {
        suggestedBlocksHtml = `
          <div class="bg-canvas-panel p-3 rounded-xl flex flex-col gap-2 mt-2">
            <span class="text-xs font-bold text-primary">Saran Blok Tambahan:</span>
            ${data.suggested_blocks.map(b => `
              <div style="background-color: ${b.color || '#4C97FF'}" class="w-fit flex items-center gap-2 px-3 py-1.5 rounded-lg text-white font-bold text-xs shadow-sm">
                <span class="material-symbols-outlined text-[16px]">extension</span>
                <span>${escapeHtml(b.label)}</span>
              </div>
            `).join('')}
          </div>
        `;
      }

      const bubble = document.createElement('div');
      bubble.className = "flex items-start justify-start gap-3 sm:gap-4 pr-4 sm:pr-12 fade-in";
      bubble.innerHTML = `
        <div class="relative w-12 h-12 rounded-2xl bg-surface-container-lowest shadow-sm flex items-center justify-center p-1 flex-shrink-0">
          <span class="material-symbols-outlined text-scratch-operators text-[32px]">photo_camera</span>
        </div>
        <div class="flex-1 max-w-2xl flex flex-col gap-1">
          <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-on-surface">Pemeriksaan Foto Blok</span>
            <span class="px-2 py-0.5 rounded-full bg-green-100 text-scratch-operators text-xs font-bold">Hasil OCR / Vision</span>
          </div>
          <div class="bg-surface-container-lowest p-5 rounded-2xl rounded-tl-none shadow-[0_4px_16px_rgba(0,93,182,0.08)] flex flex-col gap-3">
            <div class="p-2 rounded-lg bg-primary-fixed/30 text-xs font-bold text-primary">
              ${escapeHtml(data.detected_blocks_summary)}
            </div>
            <p class="text-base text-on-surface leading-relaxed">${escapeHtml(data.analysis)}</p>
            ${data.recommendation ? `<p class="text-sm font-semibold text-scratch-operators">💡 ${escapeHtml(data.recommendation)}</p>` : ''}
            ${suggestedBlocksHtml}
          </div>
        </div>
      `;

      chatHistory.insertBefore(bubble, thinking);
      window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function appendErrorBubble(msg) {
      const chatHistory = document.getElementById('chatHistory');
      const thinking = document.getElementById('thinkingBubble');

      const bubble = document.createElement('div');
      bubble.className = "flex items-start justify-start gap-3 sm:gap-4 pr-4 sm:pr-12 fade-in";
      bubble.innerHTML = `
        <div class="w-10 h-10 rounded-2xl bg-red-100 text-error flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-[24px]">info</span>
        </div>
        <div class="bg-red-50 text-error border border-red-200 p-4 rounded-2xl rounded-tl-none max-w-xl text-sm font-bold shadow-sm">
          ${escapeHtml(msg)}
        </div>
      `;

      chatHistory.insertBefore(bubble, thinking);
      window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function showThinking(show) {
      const thinking = document.getElementById('thinkingBubble');
      if (show) {
        thinking.classList.remove('hidden');
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
      } else {
        thinking.classList.add('hidden');
      }
    }

    function setSaveStatus(status, text) {
      const dot = document.getElementById('saveStatusDot');
      const label = document.getElementById('saveStatusText');
      if (!dot || !label) return;

      if (status === 'saving') {
        dot.className = 'w-2 h-2 rounded-full bg-amber-500 animate-pulse';
        label.textContent = text || 'Menyimpan obrolan... ⏳';
      } else if (status === 'saved') {
        dot.className = 'w-2 h-2 rounded-full bg-green-500';
        label.textContent = text || 'Tersimpan otomatis 💾';
      } else if (status === 'error') {
        dot.className = 'w-2 h-2 rounded-full bg-red-500';
        label.textContent = text || 'Gagal menyimpan ⚠️';
      } else if (status === 'loading') {
        dot.className = 'w-2 h-2 rounded-full bg-blue-500 animate-pulse';
        label.textContent = text || 'Memuat riwayat...';
      }
    }

    async function saveConversationToBackend(convId, title, messages) {
      setSaveStatus('saving');
      try {
        const res = await fetch('/api/conversation-save', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
          },
          body: JSON.stringify({
            conversation_id: convId,
            title: title.substring(0, 40),
            messages: messages
          })
        });

        const json = await res.json();
        if (json.success) {
          setSaveStatus('saved');
          const titleEl = document.getElementById('convTitleText');
          if (titleEl && (titleEl.textContent === 'Sesi Obrolan Baru' || !titleEl.textContent)) {
            titleEl.textContent = title.substring(0, 32);
          }
        } else {
          setSaveStatus('error');
        }
      } catch (e) {
        setSaveStatus('error');
      }
    }

    async function loadHistoricalConversation(convId) {
      const historyBadge = document.getElementById('historyBadge');
      const convTitleText = document.getElementById('convTitleText');
      setSaveStatus('loading', 'Memuat obrolan lampau... ⏳');
      showThinking(true);

      try {
        const res = await fetch(`/api/conversation?id=${encodeURIComponent(convId)}`, {
          headers: {
            'Authorization': 'Bearer ' + getAuthToken()
          }
        });
        const result = await res.json();
        showThinking(false);

        if (result.success && result.data) {
          const conv = result.data;
          CONVERSATION_ID = conv.id || convId;
          if (convTitleText) convTitleText.textContent = conv.title || 'Obrolan Scratch';
          if (historyBadge) historyBadge.classList.remove('hidden');

          // Bersihkan bubble awal default kecuali thinkingBubble
          const chatHistory = document.getElementById('chatHistory');
          const thinking = document.getElementById('thinkingBubble');
          chatHistory.innerHTML = '';
          chatHistory.appendChild(thinking);

          messageHistory = [];
          const messages = conv.messages || [];

          messages.forEach(msg => {
            if (msg.role === 'user') {
              appendUserBubble(msg.content);
              messageHistory.push(msg);
            } else if (msg.role === 'assistant') {
              if (msg.vision_data) {
                appendVisionBubble(msg.vision_data);
              } else {
                appendArdenaBubble({
                  answer: msg.content,
                  block_category: msg.block_category,
                  suggested_block: msg.suggested_block,
                  blocks_to_use: msg.blocks_to_use
                });
              }
              messageHistory.push(msg);
            }
          });

          setSaveStatus('saved', 'Riwayat berhasil dimuat 📖');
        } else {
          setSaveStatus('error', 'Gagal memuat riwayat');
        }
      } catch (e) {
        showThinking(false);
        setSaveStatus('error', 'Gagal terhubung');
      }
    }

    function adjustFontSize(delta) {
      const body = document.body;
      const currentSize = parseFloat(window.getComputedStyle(body).fontSize) || 16;
      body.style.fontSize = Math.max(14, Math.min(22, currentSize + delta)) + 'px';
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.innerText = text || '';
      return div.innerHTML;
    }

    document.getElementById('userChatInput')?.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') handleSendMessage();
    });

    function initUserHeader() {
      const rawUser = localStorage.getItem('ardena_auth_user');
      const loginBtn = document.getElementById('headerLoginBtn');
      const userBadge = document.getElementById('headerUserBadge');
      const userName = document.getElementById('headerUserName');
      const userAvatar = document.getElementById('headerUserAvatar');
      const heading = document.getElementById('mainHeading');
      const savedAvatar = localStorage.getItem('ardena_user_avatar') || '🐱';

      if (userAvatar) userAvatar.textContent = savedAvatar;

      if (rawUser) {
        try {
          const user = JSON.parse(rawUser);
          if (user && user.name) {
            loginBtn?.classList.add('hidden');
            userBadge?.classList.remove('hidden');
            userBadge?.classList.add('flex');
            if (userName) userName.textContent = user.name;
            if (heading) {
              heading.textContent = `Halo ${user.name}! Mau bikin game apa hari ini? 🚀`;
            }
          }
        } catch(e) {}
      }

      // Jika ada query id obrolan, muat obrolan lampau
      if (queryConvId) {
        loadHistoricalConversation(queryConvId);
      }
    }

    function handleLogout() {
      if (confirm('Apakah kamu yakin ingin keluar dari akun belajarmu? 🐱')) {
        localStorage.removeItem('ardena_auth_token');
        localStorage.removeItem('ardena_auth_user');
        window.location.href = '/login';
      }
    }

    initUserHeader();
  </script>
</body>
</html>
