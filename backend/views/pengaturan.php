<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta content="web_dashboard" name="shell-type"/>
  <title>Pengaturan & Profil Akun — Ardena AI Scratch 3</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet"/>
  
  <style>
    @layer base {
      html, body { margin: 0; padding: 0; }
      body { overscroll-behavior: none; }
    }
    ::-webkit-scrollbar { display: none; }
    .fade-in { animation: fadeIn 0.25s ease-in-out; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
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
            "primary-container": "#4c97ff",
            "on-primary": "#ffffff",
            "on-primary-container": "#002f61",
            "scratch-motion": "#4C97FF",
            "scratch-looks": "#9966FF",
            "scratch-sound": "#CF63CF",
            "scratch-events": "#FFBF00",
            "scratch-control": "#FFAB19",
            "scratch-operators": "#59C059",
            "scratch-sensing": "#5CB1D6",
            "canvas-panel": "#E9F1FC",
            "stage-bg": "#F9F9F9",
            "on-surface": "#0d1c32",
            "on-surface-variant": "#414753",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f0f3ff",
            "surface-container": "#e7eeff",
            "surface-container-high": "#dfe8ff",
            "surface-container-highest": "#d6e3ff",
            "error": "#ba1a1a",
            "error-container": "#ffdad6",
            "on-error": "#ffffff",
            "on-error-container": "#93000a",
            "outline": "#717784",
            "outline-variant": "#c1c6d5"
          },
          fontFamily: {
            sans: ["Nunito Sans", "sans-serif"]
          }
        }
      }
    };
  </script>
</head>
<body class="bg-canvas-panel font-sans text-on-surface min-h-screen selection:bg-primary-container selection:text-on-primary-container">
  
  <!-- Sidebar Navigasi Dekstop -->
  <aside class="hidden lg:flex fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex-col justify-between p-4">
    <div class="flex flex-col gap-6">
      <!-- App Brand -->
      <div class="flex items-center gap-3 p-1">
        <div class="w-10 h-10 rounded-xl bg-scratch-motion flex items-center justify-center text-white shadow-sm">
          <span class="material-symbols-outlined text-[28px]">extension</span>
        </div>
        <div class="flex flex-col">
          <div class="flex items-center gap-1.5">
            <span class="font-extrabold text-xl text-on-surface">Ardena AI</span>
            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-scratch-operators/15 text-scratch-operators text-[11px] font-bold">
              <span class="w-1.5 h-1.5 rounded-full bg-scratch-operators animate-pulse"></span>
              Online
            </span>
          </div>
          <span class="text-xs font-semibold text-on-surface-variant">Sahabat Scratch 3</span>
        </div>
      </div>

      <!-- Navigation Links -->
      <nav class="flex flex-col gap-1.5">
        <a class="min-h-[48px] flex items-center justify-between px-4 py-2.5 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-all group font-bold" href="/">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-scratch-motion group-hover:scale-110 transition-transform">chat</span>
            <span>Tanya Ardena</span>
          </div>
          <span class="material-symbols-outlined text-outline-variant text-[18px]">chevron_right</span>
        </a>

        <a class="min-h-[48px] flex items-center justify-between px-4 py-2.5 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-all group font-bold" href="/riwayat">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-scratch-events group-hover:scale-110 transition-transform">history</span>
            <span>Riwayat Obrolan</span>
          </div>
          <span class="material-symbols-outlined text-outline-variant text-[18px]">chevron_right</span>
        </a>

        <a class="min-h-[48px] flex items-center justify-between px-4 py-2.5 rounded-xl bg-primary-container text-on-primary font-bold shadow-[0_3px_0_#00468b] transition-all group" href="/pengaturan">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-on-primary group-hover:scale-110 transition-transform">settings</span>
            <span>Pengaturan & Profil</span>
          </div>
          <span class="material-symbols-outlined text-on-primary text-[18px]">check</span>
        </a>
      </nav>
    </div>

    <!-- User Profile Card Sidebar -->
    <div class="flex flex-col gap-2 pt-3 bg-surface-container-low rounded-xl p-3 shadow-[0_2px_0_#c1c6d5]">
      <div class="flex items-center gap-3">
        <div id="sidebarAvatarSlot" class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
          🐱
        </div>
        <div class="flex flex-col flex-1 min-w-0">
          <span id="sidebarUserName" class="font-bold text-sm text-on-surface truncate">Siswa Cilik</span>
          <span id="sidebarUserSub" class="text-xs text-on-surface-variant truncate">Siswa Kelas 4 SD</span>
        </div>
      </div>
      <button onclick="handleLogout()" class="min-h-[40px] flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-surface-container-lowest text-error hover:bg-error-container hover:text-on-error-container font-bold text-xs shadow-sm transition-all" type="button">
        <span class="material-symbols-outlined text-[16px]">logout</span>
        <span>Keluar Akun</span>
      </button>
    </div>
  </aside>

  <!-- Konten Utama (Berada di samping Sidebar jika layar besar) -->
  <div class="lg:pl-72">
    <!-- Header Atas (Top Header) -->
    <header class="fixed top-0 left-0 lg:left-72 right-0 h-16 bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-4 sm:px-6">
      <div class="flex items-center gap-2 text-on-surface-variant">
        <a href="/" class="lg:hidden w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary font-bold">
          <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <span class="text-xs sm:text-sm font-semibold">Ruang Belajar</span>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-xs sm:text-sm text-primary font-bold">Pengaturan & Profil</span>
      </div>

      <div class="flex items-center gap-2">
        <a href="/" class="px-3 py-1.5 rounded-xl bg-scratch-motion text-white font-bold text-xs shadow-sm flex items-center gap-1 hover:brightness-105">
          <span class="material-symbols-outlined text-[16px]">chat</span>
          <span class="hidden sm:inline">Tanya Ardena</span>
        </a>
        <a href="/riwayat" class="px-3 py-1.5 rounded-xl bg-surface-container text-on-surface font-bold text-xs shadow-sm flex items-center gap-1 hover:bg-surface-container-high">
          <span class="material-symbols-outlined text-[16px]">history</span>
          <span class="hidden sm:inline">Riwayat</span>
        </a>
      </div>
    </header>

    <!-- Halaman Utama Pengaturan -->
    <main class="relative pt-20 pb-16 px-4 sm:px-6 max-w-5xl mx-auto flex flex-col gap-6">
      
      <!-- Banner Sambutan Interaktif -->
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary via-scratch-looks to-primary-container p-6 sm:p-8 text-on-primary shadow-lg">
        <div class="absolute -right-8 -bottom-10 w-44 h-44 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex flex-col max-w-2xl">
            <div class="flex items-center gap-2 mb-1">
              <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-bold text-on-primary">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                Akun Siswa Aktif
              </span>
              <span class="px-2.5 py-1 rounded-full bg-scratch-events text-on-primary-container text-xs font-bold shadow-sm">
                Level 4: Penjelajah Scratch
              </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-on-primary tracking-tight">Pengaturan & Profil Akun ⚙️</h1>
            <p class="text-sm text-primary-fixed mt-1">
              Kelola avatar belajarmu, kata sandi, pengaturan suara Ardena AI, dan preferensi sesi belajarmu.
            </p>
          </div>
          <div class="flex items-center gap-3 bg-white/15 backdrop-blur-md p-3 rounded-2xl">
            <div id="bannerAvatarSlot" class="w-12 h-12 rounded-full bg-scratch-events flex items-center justify-center text-on-primary-container text-2xl shadow-sm">
              🐱
            </div>
            <div class="flex flex-col">
              <span id="bannerUserName" class="font-bold text-sm text-on-primary">Siswa Cilik</span>
              <span id="bannerUserEmail" class="text-xs text-primary-fixed">siswa@ardena.test</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Anchor Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a class="px-4 py-2 rounded-xl bg-scratch-motion text-on-primary font-bold text-xs shadow-sm hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center gap-1.5 whitespace-nowrap" href="#profil">
          <span class="material-symbols-outlined text-[18px]">face</span>
          <span>Profil Siswa</span>
        </a>
        <a class="px-4 py-2 rounded-xl bg-scratch-events text-on-primary-container font-bold text-xs shadow-sm hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center gap-1.5 whitespace-nowrap" href="#keamanan">
          <span class="material-symbols-outlined text-[18px]">lock</span>
          <span>Keamanan Sandi</span>
        </a>
        <a class="px-4 py-2 rounded-xl bg-scratch-sound text-white font-bold text-xs shadow-sm hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center gap-1.5 whitespace-nowrap" href="#suara">
          <span class="material-symbols-outlined text-[18px]">volume_up</span>
          <span>Suara & Aksesibilitas</span>
        </a>
        <a class="px-4 py-2 rounded-xl bg-scratch-operators text-on-primary font-bold text-xs shadow-sm hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center gap-1.5 whitespace-nowrap" href="#pendamping">
          <span class="material-symbols-outlined text-[18px]">school</span>
          <span>Sekolah & Wali</span>
        </a>
        <a class="px-4 py-2 rounded-xl bg-error text-on-error font-bold text-xs shadow-sm hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center gap-1.5 whitespace-nowrap" href="#sesi">
          <span class="material-symbols-outlined text-[18px]">logout</span>
          <span>Keluar Sesi</span>
        </a>
      </div>

      <!-- Bento Grid Sections -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- BAGIAN 1: PROFIL SISWA (7 Kolom) -->
        <section class="lg:col-span-7 flex flex-col gap-4 bg-surface-container-lowest rounded-2xl p-6 shadow-sm" id="profil">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-scratch-motion flex items-center justify-center text-white shadow-sm">
                <span class="material-symbols-outlined text-[22px]">face</span>
              </div>
              <div>
                <h3 class="font-extrabold text-lg text-on-surface">1. Profil Siswa Cilik</h3>
                <p class="text-xs text-on-surface-variant font-medium">Pilih maskot dan perkenalkan gaya belajarmu!</p>
              </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-primary-fixed text-primary text-xs font-bold">
              Fase B (SD)
            </span>
          </div>

          <!-- Pilihan Avatar Interaktif -->
          <div class="flex flex-col gap-2 pt-1">
            <label class="text-sm font-bold text-on-surface">Pilih Avatar Favorit Kamu</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="avatarGrid">
              <!-- Item 1: Cat -->
              <button onclick="selectAvatar('🐱', 'Kucing Scratch', this)" class="avatar-option group relative flex flex-col items-center p-3 rounded-xl bg-primary-fixed/40 ring-2 ring-primary transition-all shadow-sm" type="button" data-avatar="🐱">
                <span class="check-icon absolute top-2 right-2 text-primary material-symbols-outlined text-[18px]">check_circle</span>
                <div class="w-12 h-12 rounded-full bg-scratch-events/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                  🐱
                </div>
                <span class="text-xs font-bold text-on-surface mt-2">Kucing Scratch</span>
                <span class="status-label text-[10px] text-primary font-bold">Terpilih</span>
              </button>

              <!-- Item 2: Robot -->
              <button onclick="selectAvatar('🤖', 'Robot Ardena', this)" class="avatar-option group relative flex flex-col items-center p-3 rounded-xl bg-surface-container-low hover:bg-surface-container transition-all shadow-sm" type="button" data-avatar="🤖">
                <span class="check-icon hidden absolute top-2 right-2 text-primary material-symbols-outlined text-[18px]">check_circle</span>
                <div class="w-12 h-12 rounded-full bg-scratch-looks/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                  🤖
                </div>
                <span class="text-xs font-bold text-on-surface mt-2">Robot Ardena</span>
                <span class="status-label text-[10px] text-on-surface-variant">Pilih</span>
              </button>

              <!-- Item 3: Star -->
              <button onclick="selectAvatar('⭐', 'Bintang Ajaib', this)" class="avatar-option group relative flex flex-col items-center p-3 rounded-xl bg-surface-container-low hover:bg-surface-container transition-all shadow-sm" type="button" data-avatar="⭐">
                <span class="check-icon hidden absolute top-2 right-2 text-primary material-symbols-outlined text-[18px]">check_circle</span>
                <div class="w-12 h-12 rounded-full bg-scratch-control/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                  ⭐
                </div>
                <span class="text-xs font-bold text-on-surface mt-2">Bintang Ajaib</span>
                <span class="status-label text-[10px] text-on-surface-variant">Pilih</span>
              </button>

              <!-- Item 4: Astronaut -->
              <button onclick="selectAvatar('🧑‍🚀', 'Astronot Cilik', this)" class="avatar-option group relative flex flex-col items-center p-3 rounded-xl bg-surface-container-low hover:bg-surface-container transition-all shadow-sm" type="button" data-avatar="🧑‍🚀">
                <span class="check-icon hidden absolute top-2 right-2 text-primary material-symbols-outlined text-[18px]">check_circle</span>
                <div class="w-12 h-12 rounded-full bg-scratch-sensing/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                  🧑‍🚀
                </div>
                <span class="text-xs font-bold text-on-surface mt-2">Astronot Cilik</span>
                <span class="status-label text-[10px] text-on-surface-variant">Pilih</span>
              </button>
            </div>
          </div>

          <!-- Formulir Profil Siswa -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-on-surface flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px] text-scratch-motion">badge</span>
                Nama Lengkap / Panggilan
              </label>
              <input id="profileNameInput" class="h-11 px-3 rounded-xl bg-surface-container-low text-on-surface font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-inner" type="text" value="Budi Pratama"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-on-surface flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px] text-scratch-events">school</span>
                Jenjang Kelas
              </label>
              <select id="profileGradeInput" class="h-11 px-3 rounded-xl bg-surface-container-low text-on-surface font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-inner">
                <option value="Kelas 4 SD (Fase B)" selected>Kelas 4 SD (Fase B)</option>
                <option value="Kelas 3 SD (Fase B)">Kelas 3 SD (Fase B)</option>
                <option value="Kelas 5 SD (Fase C)">Kelas 5 SD (Fase C)</option>
                <option value="Kelas 6 SD (Fase C)">Kelas 6 SD (Fase C)</option>
                <option value="SMP (Fase D)">SMP (Fase D)</option>
              </select>
            </div>
          </div>

          <div class="flex flex-col gap-1">
            <label class="text-xs font-bold text-on-surface flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px] text-scratch-looks">auto_stories</span>
              Bio Siswa (Proyek apa yang suka kamu buat?)
            </label>
            <textarea id="profileBioInput" class="p-3 rounded-xl bg-surface-container-low text-on-surface font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-inner resize-none" rows="3">Suka bikin game petualangan roket dan kucing tangkap apel!</textarea>
          </div>

          <div class="flex items-center justify-between pt-2">
            <span id="profileSaveNotice" class="text-xs font-bold text-scratch-operators hidden">
              ✓ Perubahan profil berhasil disimpan!
            </span>
            <button onclick="saveProfileChanges()" class="ml-auto px-5 py-2.5 rounded-xl bg-scratch-motion text-white font-bold text-sm shadow-[0_3px_0_#2b73db] hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center gap-2" type="button">
              <span class="material-symbols-outlined text-[18px]">save</span>
              <span>Simpan Profil</span>
            </button>
          </div>
        </section>

        <!-- BAGIAN 2: KEAMANAN & KATA SANDI (5 Kolom) -->
        <section class="lg:col-span-5 flex flex-col gap-4 bg-surface-container-lowest rounded-2xl p-6 shadow-sm" id="keamanan">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-scratch-events flex items-center justify-center text-on-primary-container shadow-sm">
                <span class="material-symbols-outlined text-[22px]">key</span>
              </div>
              <div>
                <h3 class="font-extrabold text-lg text-on-surface">2. Keamanan & Sandi 🔑</h3>
                <p class="text-xs text-on-surface-variant font-medium">Jaga akun Scratch tetap aman</p>
              </div>
            </div>
          </div>

          <form class="flex flex-col gap-3" onsubmit="handlePasswordUpdate(event)">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-on-surface">Kata Sandi Baru</label>
              <div class="relative flex items-center">
                <input class="h-11 w-full px-3 pr-10 rounded-xl bg-surface-container-low text-on-surface font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-scratch-events shadow-inner" id="newPassInput" placeholder="Minimal 8 karakter..." type="password" required/>
                <button class="absolute right-2 text-on-surface-variant hover:text-on-surface" onclick="togglePassVisibility('newPassInput')" type="button">
                  <span class="material-symbols-outlined text-[18px]">visibility</span>
                </button>
              </div>
            </div>

            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-on-surface">Ulangi Kata Sandi Baru</label>
              <div class="relative flex items-center">
                <input class="h-11 w-full px-3 pr-10 rounded-xl bg-surface-container-low text-on-surface font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-scratch-events shadow-inner" id="confirmPassInput" placeholder="Ketik ulang sandi baru..." type="password" required/>
                <button class="absolute right-2 text-on-surface-variant hover:text-on-surface" onclick="togglePassVisibility('confirmPassInput')" type="button">
                  <span class="material-symbols-outlined text-[18px]">visibility</span>
                </button>
              </div>
            </div>

            <!-- Strength Indicator -->
            <div class="p-3 bg-surface-container-low rounded-xl flex flex-col gap-1.5">
              <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant">Kekuatan Sandi:</span>
                <span class="text-[11px] text-scratch-operators font-bold">Kuat & Terlindungi 🛡️</span>
              </div>
              <div class="w-full h-1.5 rounded-full bg-slate-200 overflow-hidden">
                <div class="w-4/5 h-full rounded-full bg-scratch-operators"></div>
              </div>
            </div>

            <button class="min-h-[44px] w-full rounded-xl bg-scratch-events text-on-primary-container font-bold text-sm shadow-[0_3px_0_#d9a300] hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center justify-center gap-1.5" type="submit">
              <span class="material-symbols-outlined text-[18px]">verified_user</span>
              <span>Perbarui Kata Sandi</span>
            </button>

            <div class="text-center pt-1">
              <button onclick="handleResetPasswordEmail()" class="text-xs font-bold text-primary hover:underline inline-flex items-center gap-1" type="button">
                <span class="material-symbols-outlined text-[14px]">send</span>
                <span>Kirim tautan reset sandi ke Email</span>
              </button>
            </div>
          </form>
        </section>

        <!-- BAGIAN 3: SUARA & AKSESIBILITAS (7 Kolom) -->
        <section class="lg:col-span-7 flex flex-col gap-4 bg-surface-container-lowest rounded-2xl p-6 shadow-sm" id="suara">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-scratch-sound flex items-center justify-center text-white shadow-sm">
                <span class="material-symbols-outlined text-[22px]">volume_up</span>
              </div>
              <div>
                <h3 class="font-extrabold text-lg text-on-surface">3. Suara & Aksesibilitas Belajar 🔊</h3>
                <p class="text-xs text-on-surface-variant font-medium">Atur suara pembaca AI dan kenyamanan membaca</p>
              </div>
            </div>
          </div>

          <!-- Toggle Suara Ardena AI -->
          <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-scratch-sound/20 flex items-center justify-center text-scratch-sound">
                <span class="material-symbols-outlined text-[20px]">record_voice_over</span>
              </div>
              <div class="flex flex-col">
                <span class="text-sm font-bold text-on-surface">Suara Asisten Ardena AI</span>
                <span class="text-xs text-on-surface-variant">Membacakan jawaban obrolan secara otomatis</span>
              </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input id="ttsToggle" checked class="sr-only peer" type="checkbox" onchange="savePreferences()"/>
              <div class="w-12 h-7 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-scratch-sound"></div>
            </label>
          </div>

          <!-- Kecepatan Suara -->
          <div class="flex flex-col gap-2 p-3 rounded-xl bg-surface-container-low">
            <span class="text-xs font-bold text-on-surface flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px] text-scratch-sound">speed</span>
              Kecepatan Bicara Ardena
            </span>
            <div class="grid grid-cols-3 gap-2">
              <button onclick="setSpeed(0.8, this)" class="speed-btn py-2 px-3 rounded-xl bg-surface-container text-on-surface font-bold text-xs hover:bg-surface-container-high transition-all" type="button">
                🐢 0.8x Pelan
              </button>
              <button onclick="setSpeed(1.0, this)" class="speed-btn py-2 px-3 rounded-xl bg-scratch-sound text-white font-bold text-xs shadow-sm transition-all" type="button">
                🐱 1.0x Normal
              </button>
              <button onclick="setSpeed(1.2, this)" class="speed-btn py-2 px-3 rounded-xl bg-surface-container text-on-surface font-bold text-xs hover:bg-surface-container-high transition-all" type="button">
                🚀 1.2x Cepat
              </button>
            </div>
          </div>

          <!-- Ukuran Teks Huruf -->
          <div class="flex flex-col gap-2 p-3 rounded-xl bg-surface-container-low">
            <span class="text-xs font-bold text-on-surface flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px] text-scratch-motion">format_size</span>
              Ukuran Teks Obrolan
            </span>
            <div class="grid grid-cols-3 gap-2">
              <button onclick="setTextSize('normal', this)" class="text-size-btn py-2 px-3 rounded-xl bg-surface-container text-on-surface font-bold text-xs transition-all" type="button">
                Sedang (A)
              </button>
              <button onclick="setTextSize('large', this)" class="text-size-btn py-2 px-3 rounded-xl bg-primary text-white font-bold text-xs shadow-sm transition-all" type="button">
                Besar (A+)
              </button>
              <button onclick="setTextSize('xlarge', this)" class="text-size-btn py-2 px-3 rounded-xl bg-surface-container text-on-surface font-bold text-xs transition-all" type="button">
                Ekstra (A++)
              </button>
            </div>
          </div>
        </section>

        <!-- BAGIAN 4: SEKOLAH & PENDAMPING (5 Kolom) -->
        <section class="lg:col-span-5 flex flex-col gap-4 bg-surface-container-lowest rounded-2xl p-6 shadow-sm" id="pendamping">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-scratch-operators flex items-center justify-center text-white shadow-sm">
                <span class="material-symbols-outlined text-[22px]">school</span>
              </div>
              <div>
                <h3 class="font-extrabold text-lg text-on-surface">4. Sekolah & Pendamping 🏫</h3>
                <p class="text-xs text-on-surface-variant font-medium">Integrasi kelas dan pengawasan belajar</p>
              </div>
            </div>
          </div>

          <div class="flex flex-col gap-2.5">
            <div class="p-3 rounded-xl bg-surface-container-low flex flex-col gap-1">
              <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant">Email Terdaftar Siswa</span>
                <span class="inline-flex items-center gap-1 text-[11px] text-scratch-operators font-bold">
                  <span class="material-symbols-outlined text-[14px]">check_circle</span> Terverifikasi
                </span>
              </div>
              <span id="registeredEmailDisplay" class="font-bold text-sm text-on-surface font-mono">siswa@ardena.test</span>
            </div>

            <div class="p-3 rounded-xl bg-surface-container-low flex flex-col gap-1">
              <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant">Email Orang Tua / Wali</span>
                <span class="text-[11px] text-scratch-motion font-bold">Pendamping</span>
              </div>
              <span class="font-bold text-sm text-on-surface font-mono">orangtua.siswa@keluarga.com</span>
              <span class="text-[11px] text-on-surface-variant">Menerima ringkasan hasil belajar Scratch</span>
            </div>

            <div class="p-3 rounded-xl bg-surface-container-low flex flex-col gap-2">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-scratch-operators">apartment</span>
                <span class="text-xs font-bold text-on-surface">SD Negeri Percobaan Ardena 01</span>
              </div>
              <div class="flex flex-col gap-1 pl-6">
                <span class="text-xs text-on-surface-variant">Guru Pembimbing: <strong>Ibu Sarah, S.Pd</strong></span>
                <span class="text-[11px] text-on-surface-variant">Kelas: <strong>Scratch 3 Level Dasar</strong></span>
              </div>
            </div>
          </div>
        </section>

        <!-- BAGIAN 5: PENGELOLAAN SESI & LOGOUT (12 Kolom) -->
        <section class="lg:col-span-12 flex flex-col gap-4 bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-red-100" id="sesi">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-red-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-error flex items-center justify-center text-white shadow-sm">
                <span class="material-symbols-outlined text-[22px]">logout</span>
              </div>
              <div>
                <h3 class="font-extrabold text-lg text-error">5. Pengelolaan Sesi & Keluar Akun 🚪</h3>
                <p class="text-xs text-on-surface-variant font-medium">Amankan komputermu saat selesai jam belajar di lab sekolah</p>
              </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-red-50 text-error text-xs font-bold self-start sm:self-center">
              Sesi Komputer Aktif
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <div class="md:col-span-8 flex flex-col gap-1.5 p-4 bg-surface-container-low rounded-xl">
              <div class="flex items-center gap-2 text-scratch-events">
                <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                <span class="font-bold text-sm text-on-surface">Pesan Ardena Sebelum Keluar:</span>
              </div>
              <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                "Hebat sekali belajarmu hari ini! Seluruh percakapan dan proyek koding Scratch-mu sudah tersimpan otomatis di akunmu. Sampai jumpa di sesi berikutnya! 🚀"
              </p>
            </div>

            <div class="md:col-span-4 flex flex-col gap-2">
              <button onclick="handleLogout()" class="min-h-[48px] w-full px-4 rounded-xl bg-error text-white font-bold text-sm shadow-[0_3px_0_#8b0000] hover:translate-y-0.5 active:translate-y-1 transition-all flex items-center justify-center gap-2" type="button">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                <span>Keluar dari Akun Ini (Logout)</span>
              </button>
            </div>
          </div>
        </section>

      </div>
    </main>
  </div>

  <script>
    // Dynamic Base Path Resolver (Mendukung Virtual Host http://ardenaai.test dan Subfolder http://localhost/ardenaai)
    const BASE_PATH = window.location.pathname.includes('/ardenaai') ? '/ardenaai' : '';
    function getPageUrl(path) {
      return BASE_PATH + (path.startsWith('/') ? path : '/' + path);
    }

    // Inisialisasi Data Siswa dari LocalStorage
    let selectedAvatar = localStorage.getItem('ardena_user_avatar') || '🐱';
    let userSpeechSpeed = parseFloat(localStorage.getItem('ardena_speech_speed')) || 1.0;
    let userTextSize = localStorage.getItem('ardena_text_size') || 'large';

    function initSettingsPage() {
      if (BASE_PATH) {
        document.querySelectorAll('a[href^="/"]').forEach(a => {
          const href = a.getAttribute('href');
          if (href && !href.startsWith(BASE_PATH)) {
            a.setAttribute('href', BASE_PATH + href);
          }
        });
      }

      const rawUser = localStorage.getItem('ardena_auth_user');
      const user = rawUser ? JSON.parse(rawUser) : null;

      const userName = user?.name || localStorage.getItem('ardena_user_name') || 'Budi Pratama';
      const userEmail = user?.email || 'siswa@ardena.test';
      const userGrade = localStorage.getItem('ardena_user_grade') || 'Kelas 4 SD (Fase B)';
      const userBio = localStorage.getItem('ardena_user_bio') || 'Suka bikin game petualangan roket dan kucing tangkap apel!';

      // Set input values
      document.getElementById('profileNameInput').value = userName;
      document.getElementById('profileGradeInput').value = userGrade;
      document.getElementById('profileBioInput').value = userBio;

      // Update Text Displays
      document.getElementById('sidebarUserName').textContent = userName;
      document.getElementById('sidebarUserSub').textContent = userGrade;
      document.getElementById('bannerUserName').textContent = userName;
      document.getElementById('bannerUserEmail').textContent = userEmail;
      document.getElementById('registeredEmailDisplay').textContent = userEmail;

      // Update Avatar
      applyAvatarUI(selectedAvatar);

      // Update Preferences UI
      updateSpeedUI(userSpeechSpeed);
      updateTextSizeUI(userTextSize);
    }

    function selectAvatar(avatar, label, btnElement) {
      selectedAvatar = avatar;
      localStorage.setItem('ardena_user_avatar', avatar);
      applyAvatarUI(avatar);
    }

    function applyAvatarUI(avatar) {
      document.getElementById('sidebarAvatarSlot').textContent = avatar;
      document.getElementById('bannerAvatarSlot').textContent = avatar;

      const buttons = document.querySelectorAll('.avatar-option');
      buttons.forEach(btn => {
        const btnAvatar = btn.getAttribute('data-avatar');
        const checkIcon = btn.querySelector('.check-icon');
        const statusLabel = btn.querySelector('.status-label');

        if (btnAvatar === avatar) {
          btn.classList.add('bg-primary-fixed/40', 'ring-2', 'ring-primary');
          btn.classList.remove('bg-surface-container-low');
          checkIcon?.classList.remove('hidden');
          if (statusLabel) {
            statusLabel.textContent = 'Terpilih';
            statusLabel.className = 'status-label text-[10px] text-primary font-bold';
          }
        } else {
          btn.classList.remove('bg-primary-fixed/40', 'ring-2', 'ring-primary');
          btn.classList.add('bg-surface-container-low');
          checkIcon?.classList.add('hidden');
          if (statusLabel) {
            statusLabel.textContent = 'Pilih';
            statusLabel.className = 'status-label text-[10px] text-on-surface-variant';
          }
        }
      });
    }

    function saveProfileChanges() {
      const name = document.getElementById('profileNameInput').value.trim();
      const grade = document.getElementById('profileGradeInput').value;
      const bio = document.getElementById('profileBioInput').value.trim();

      if (!name) {
        alert('Nama lengkap tidak boleh kosong ya!');
        return;
      }

      localStorage.setItem('ardena_user_name', name);
      localStorage.setItem('ardena_user_grade', grade);
      localStorage.setItem('ardena_user_bio', bio);

      // Perbarui juga data di ardena_auth_user
      const rawUser = localStorage.getItem('ardena_auth_user');
      let user = rawUser ? JSON.parse(rawUser) : {};
      user.name = name;
      localStorage.setItem('ardena_auth_user', JSON.stringify(user));

      // Update tampilan langsung
      document.getElementById('sidebarUserName').textContent = name;
      document.getElementById('sidebarUserSub').textContent = grade;
      document.getElementById('bannerUserName').textContent = name;

      const notice = document.getElementById('profileSaveNotice');
      notice.classList.remove('hidden');
      setTimeout(() => notice.classList.add('hidden'), 3500);
    }

    function setSpeed(speed, btn) {
      userSpeechSpeed = speed;
      localStorage.setItem('ardena_speech_speed', speed.toString());
      updateSpeedUI(speed);
    }

    function updateSpeedUI(speed) {
      const buttons = document.querySelectorAll('.speed-btn');
      buttons.forEach(b => {
        if (b.textContent.includes(speed.toFixed(1))) {
          b.className = 'speed-btn py-2 px-3 rounded-xl bg-scratch-sound text-white font-bold text-xs shadow-sm transition-all';
        } else {
          b.className = 'speed-btn py-2 px-3 rounded-xl bg-surface-container text-on-surface font-bold text-xs hover:bg-surface-container-high transition-all';
        }
      });
    }

    function setTextSize(size, btn) {
      userTextSize = size;
      localStorage.setItem('ardena_text_size', size);
      updateTextSizeUI(size);
    }

    function updateTextSizeUI(size) {
      const buttons = document.querySelectorAll('.text-size-btn');
      buttons.forEach(b => {
        const text = b.textContent.toLowerCase();
        let match = (size === 'normal' && text.includes('sedang')) ||
                    (size === 'large' && text.includes('besar (a+)')) ||
                    (size === 'xlarge' && text.includes('ekstra'));
        if (match) {
          b.className = 'text-size-btn py-2 px-3 rounded-xl bg-primary text-white font-bold text-xs shadow-sm transition-all';
        } else {
          b.className = 'text-size-btn py-2 px-3 rounded-xl bg-surface-container text-on-surface font-bold text-xs transition-all';
        }
      });
    }

    function savePreferences() {
      const tts = document.getElementById('ttsToggle').checked;
      localStorage.setItem('ardena_tts_enabled', tts ? 'true' : 'false');
    }

    function togglePassVisibility(id) {
      const el = document.getElementById(id);
      if (el) el.type = el.type === 'password' ? 'text' : 'password';
    }

    function handlePasswordUpdate(e) {
      e.preventDefault();
      const newPass = document.getElementById('newPassInput').value;
      const confirmPass = document.getElementById('confirmPassInput').value;

      if (newPass.length < 8) {
        alert('Kata sandi baru minimal harus 8 karakter ya!');
        return;
      }
      if (newPass !== confirmPass) {
        alert('Kata sandi baru dan ulangi kata sandi belum sama!');
        return;
      }

      alert('Kata sandimu berhasil diperbarui dengan aman! 🛡️ Silakan gunakan sandi baru untuk masuk berikutnya.');
      document.getElementById('newPassInput').value = '';
      document.getElementById('confirmPassInput').value = '';
    }

    function handleResetPasswordEmail() {
      const email = document.getElementById('registeredEmailDisplay').textContent;
      alert(`Tautan instruksi reset kata sandi telah dikirim ke: ${email} 📩 Periksa kotak masuk email orang tua/wali ya!`);
    }

    function handleLogout() {
      if (confirm('Apakah kamu yakin ingin keluar dari akun belajarmu? 🐱 Pastikan kodinganmu sudah rapi ya!')) {
        localStorage.removeItem('ardena_auth_token');
        localStorage.removeItem('ardena_auth_user');
        window.location.href = getPageUrl('/login');
      }
    }

    // Jalankan inisialisasi
    initSettingsPage();
  </script>
</body>
</html>
