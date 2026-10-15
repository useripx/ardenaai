<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta content="web_standard" name="shell-type"/>
  <title><?= htmlspecialchars($pageTitle ?? 'Daftar Akun Siswa — Ardena AI Scratch 3') ?></title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&amp;display=swap" rel="stylesheet"/>

  <style>
    @layer base {
      html, body { margin:0; padding:0; }
      body { overscroll-behavior: none; }
      main > :first-child { margin-top: 0 !important; }
      main > :last-child { margin-bottom: 0 !important; }
    }
    ::-webkit-scrollbar { display: none; }
  </style>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-secondary": "#ffffff",
            "on-secondary-container": "#fffbff",
            "scratch-events": "#FFBF00",
            "on-primary-fixed": "#001b3d",
            "on-tertiary-container": "#3e2c00",
            "secondary-container": "#8854ed",
            "on-tertiary-fixed": "#261a00",
            "inverse-primary": "#a9c7ff",
            "on-surface-variant": "#414753",
            "inverse-surface": "#233148",
            "on-primary-fixed-variant": "#00468b",
            "bubble-user": "#4C97FF",
            "surface-container-high": "#dfe8ff",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f0f3ff",
            "surface-bright": "#f9f9ff",
            "surface-variant": "#d6e3ff",
            "bubble-ai": "#FFFFFF",
            "secondary-fixed-dim": "#d2bcff",
            "tertiary-fixed": "#ffdfa0",
            "ardena-navy": "#0A2540",
            "outline": "#717784",
            "on-primary-container": "#002f61",
            "on-secondary-fixed-variant": "#5815bc",
            "scratch-sound": "#CF63CF",
            "surface-container-highest": "#d6e3ff",
            "on-tertiary": "#ffffff",
            "error": "#ba1a1a",
            "on-error-container": "#93000a",
            "surface-dim": "#ccdaf9",
            "surface-tint": "#005db6",
            "outline-variant": "#c1c6d5",
            "primary-fixed": "#d6e3ff",
            "scratch-control": "#FFAB19",
            "surface-container": "#e7eeff",
            "tertiary": "#795900",
            "on-secondary-fixed": "#25005a",
            "on-primary": "#ffffff",
            "background": "#f9f9ff",
            "tertiary-container": "#c08f00",
            "scratch-looks": "#9966FF",
            "error-container": "#ffdad6",
            "primary-fixed-dim": "#a9c7ff",
            "secondary-fixed": "#eaddff",
            "scratch-motion": "#4C97FF",
            "on-error": "#ffffff",
            "primary-container": "#4c97ff",
            "stage-bg": "#F9F9F9",
            "scratch-operators": "#59C059",
            "inverse-on-surface": "#ecf0ff",
            "secondary": "#6e36d2",
            "on-surface": "#0d1c32",
            "surface": "#f9f9ff",
            "scratch-variables": "#FF8C1A",
            "tertiary-fixed-dim": "#fbbc00",
            "scratch-sensing": "#5CB1D6",
            "on-background": "#0d1c32",
            "primary": "#005db6",
            "on-tertiary-fixed-variant": "#5c4300",
            "canvas-panel": "#E9F1FC"
          },
          borderRadius: {
            DEFAULT: "0.25rem",
            lg: "0.5rem",
            xl: "0.75rem",
            full: "9999px"
          },
          spacing: {
            gutter: "1rem",
            "margin-mobile": "1rem",
            "space-lg": "1.5rem",
            margin: "1.5rem",
            "space-sm": "0.5rem",
            "space-md": "1rem",
            "space-xs": "0.25rem",
            "gutter-mobile": "0.75rem",
            "space-xl": "2.25rem"
          },
          fontFamily: {
            "headline-lg": ["Nunito Sans"],
            "label-lg": ["Nunito Sans"],
            "body-sm": ["Nunito Sans"],
            "headline-sm": ["Nunito Sans"],
            "label-sm": ["Nunito Sans"],
            "body-lg": ["Nunito Sans"],
            display: ["Nunito Sans"],
            "label-md": ["Nunito Sans"],
            "headline-md": ["Nunito Sans"],
            "body-md": ["Nunito Sans"]
          }
        }
      }
    };
  </script>
</head>

<body class="bg-background font-body-md text-body-md text-on-surface antialiased">
  <!-- Header -->
  <header class="fixed top-0 w-full z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-20 w-full px-margin flex items-center justify-between">
      <a href="/" class="flex items-center gap-space-md hover:opacity-90 transition-opacity">
        <div class="w-10 h-10 rounded-xl bg-scratch-motion flex items-center justify-center text-white shadow-sm font-black text-xl">
          🐱
        </div>
        <div class="flex flex-col">
          <span class="font-headline-sm text-headline-sm text-ardena-navy leading-tight">Ardena AI</span>
          <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">Teman Belajar Scratch 3</span>
        </div>
      </a>

      <nav class="hidden sm:flex items-center gap-space-lg">
        <a class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors py-space-xs px-space-sm" href="/login">Masuk Siswa</a>
        <a class="transition-colors py-space-xs px-space-sm bg-surface-container text-primary font-bold rounded-xl" href="/daftar">Daftar Akun</a>
        <a class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors py-space-xs px-space-sm" href="/">Laboratorium Koding</a>
      </nav>

      <div class="flex items-center gap-space-md">
        <a href="/login" class="px-4 py-2 bg-surface-container hover:bg-surface-container-high text-primary font-bold rounded-xl text-sm transition-colors">
          Sudah Punya Akun? Masuk
        </a>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="w-full pt-20 bg-canvas-panel relative min-h-screen bg-[radial-gradient(#4C97FF_1.5px,transparent_1.5px)] [background-size:24px_24px]">
    <div class="flex flex-col w-full">
      <div class="relative w-full max-w-5xl mx-auto px-margin-mobile md:px-margin py-space-md md:py-space-xl">
        
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
          
          <!-- LEFT PANEL: Kid-friendly Mascot & Benefits (5 cols) -->
          <div class="lg:col-span-5 flex flex-col gap-space-md order-2 lg:order-1">
            
            <div class="bg-surface-container-lowest rounded-3xl p-space-lg shadow-xl relative overflow-hidden">
              <div class="flex items-center gap-space-md mb-space-sm">
                <div class="w-14 h-14 rounded-2xl bg-primary-fixed flex items-center justify-center text-3xl shadow-inner">
                  🤖
                </div>
                <div>
                  <h3 class="font-headline-sm text-headline-sm text-ardena-navy font-black">Robot Ardena</h3>
                  <p class="font-label-sm text-label-sm text-on-surface-variant font-bold">Asisten Belajar Scratch Ramah Anak</p>
                </div>
              </div>

              <div class="bg-surface-container-low rounded-2xl p-space-md text-ardena-navy font-bold leading-relaxed mb-space-md">
                "Yuk buat akun belajarmu sekarang! Kita akan belajar membuat animasi kucing Scratch menari, game petualangan, hingga tebak blok bersama AI!"
              </div>

              <div class="flex flex-col gap-2.5">
                <div class="flex items-center gap-2 text-sm text-on-surface">
                  <span class="material-symbols-outlined text-scratch-operators text-[20px]">check_circle</span>
                  <span>100% Gratis &amp; Tanpa Iklan Mengganggu</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-on-surface">
                  <span class="material-symbols-outlined text-scratch-operators text-[20px]">check_circle</span>
                  <span>Simpan Proyek &amp; Riwayat Obrolan Mandiri</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-on-surface">
                  <span class="material-symbols-outlined text-scratch-operators text-[20px]">check_circle</span>
                  <span>Didukung Login Cepat Akun Google Sekolah</span>
                </div>
              </div>

              <div class="mt-space-md pt-space-sm border-t border-surface-container flex items-center gap-2 text-xs text-on-surface-variant">
                <span class="material-symbols-outlined text-scratch-operators text-[18px]">verified_user</span>
                <span>Data aman dan dilindungi standar Firebase Secure Authentication.</span>
              </div>
            </div>

            <!-- Guru / Bantuan Card -->
            <div class="bg-surface-container-lowest rounded-2xl p-space-md shadow-md flex items-center justify-between gap-space-md">
              <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-full bg-scratch-control/15 text-scratch-control flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[22px]">contact_support</span>
                </div>
                <div>
                  <p class="font-label-md text-label-md text-ardena-navy font-bold">Butuh Bantuan Guru?</p>
                  <p class="font-body-sm text-body-sm text-on-surface-variant text-xs">Panduan pendaftaran siswa &amp; integrasi kelas</p>
                </div>
              </div>
              <a class="px-space-sm py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors font-label-sm text-label-sm text-primary font-bold" href="/login">
                Buka
              </a>
            </div>

          </div>

          <!-- RIGHT PANEL: Sign-Up Form (7 cols) -->
          <div class="lg:col-span-7 flex flex-col order-1 lg:order-2">
            <div class="bg-surface-container-lowest rounded-[28px] p-space-lg sm:p-space-xl shadow-xl flex flex-col justify-between">
              
              <div class="mb-space-lg">
                <div class="inline-flex items-center gap-space-xs px-space-sm py-1 rounded-full bg-scratch-events/15 text-on-tertiary-container font-label-sm text-label-sm font-extrabold mb-space-xs">
                  <span class="material-symbols-outlined text-[16px] text-scratch-events">star</span>
                  <span>Pendaftaran Gratis &amp; Cepat</span>
                </div>
                <h1 class="font-display text-[28px] md:text-[32px] font-extrabold text-ardena-navy tracking-tight">
                  Mulai Petualangan Kodingmu! 🐱✨
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-space-xs">
                  Daftar akun gratis dan belajar membuat game seru bersama Ardena AI.
                </p>
              </div>

              <!-- Alert Feedback -->
              <div class="hidden mb-space-md p-space-sm rounded-2xl flex items-center gap-space-sm text-on-surface transition-all" id="register-status-feedback">
                <span class="material-symbols-outlined text-[24px]" id="register-status-icon">info</span>
                <span class="font-body-sm text-body-sm font-bold" id="register-status-message">Menyiapkan akun belajarmu...</span>
              </div>

              <!-- Registration Form -->
              <form class="flex flex-col gap-space-md" id="registerForm">
                
                <!-- 1. Nama Panggilan Siswa -->
                <div class="flex flex-col gap-1.5">
                  <label class="flex items-center justify-between font-label-md text-label-md text-ardena-navy font-bold" for="studentName">
                    <span class="flex items-center gap-space-xs">
                      <span class="material-symbols-outlined text-scratch-motion text-[20px]">badge</span>
                      <span>Nama Panggilan Siswa <span class="text-error">*</span></span>
                    </span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-normal">Nama sapaan AI</span>
                  </label>
                  <div class="relative flex items-center">
                    <div class="absolute left-4 pointer-events-none text-on-surface-variant flex items-center">
                      <span class="material-symbols-outlined text-[22px]">face</span>
                    </div>
                    <input class="w-full h-14 pl-12 pr-4 bg-surface-container-low focus:bg-surface-container-lowest rounded-2xl font-body-md text-body-md text-on-surface placeholder:text-outline outline-none transition-all shadow-inner focus:ring-4 focus:ring-scratch-motion/30" id="studentName" placeholder="Contoh: Budi Pratama atau Alya" required="" type="text"/>
                  </div>
                </div>

                <!-- 2. Pilihan Jenjang / Kelas -->
                <div class="flex flex-col gap-2">
                  <label class="flex items-center gap-space-xs font-label-md text-label-md text-ardena-navy font-bold">
                    <span class="material-symbols-outlined text-scratch-control text-[20px]">school</span>
                    <span>Pilih Kelas / Jenjang Belajar <span class="text-error">*</span></span>
                  </label>
                  <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm" id="gradeSelectorGroup">
                    <button class="grade-pill flex flex-col items-start p-space-sm rounded-2xl bg-surface-container hover:bg-surface-container-high transition-all text-left shadow-sm active:translate-y-0.5 cursor-pointer" data-grade="1-3" type="button">
                      <div class="w-8 h-8 rounded-xl bg-scratch-events/20 text-on-tertiary-container flex items-center justify-center font-bold mb-1">🌱</div>
                      <span class="font-label-md text-label-md text-ardena-navy font-bold">Kelas 1-3 SD</span>
                      <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Visual blok sederhana</span>
                    </button>
                    <button class="grade-pill active-grade flex flex-col items-start p-space-sm rounded-2xl bg-bubble-user text-on-primary transition-all text-left shadow-[0_4px_0_#00468b] translate-y-0.5 cursor-pointer" data-grade="4-6" type="button">
                      <div class="w-8 h-8 rounded-xl bg-surface-container-lowest/20 text-on-primary flex items-center justify-center font-bold mb-1">🚀</div>
                      <span class="font-label-md text-label-md text-on-primary font-bold">Kelas 4-6 SD</span>
                      <span class="font-body-sm text-body-sm text-on-primary/80 text-xs">Variabel &amp; Logika</span>
                    </button>
                    <button class="grade-pill flex flex-col items-start p-space-sm rounded-2xl bg-surface-container hover:bg-surface-container-high transition-all text-left shadow-sm active:translate-y-0.5 cursor-pointer" data-grade="pemula" type="button">
                      <div class="w-8 h-8 rounded-xl bg-scratch-looks/20 text-scratch-looks flex items-center justify-center font-bold mb-1">🧩</div>
                      <span class="font-label-md text-label-md text-ardena-navy font-bold">Pemula Scratch</span>
                      <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Baru mulai coding</span>
                    </button>
                  </div>
                  <input id="selectedGrade" type="hidden" value="4-6"/>
                </div>

                <!-- 3. Email Siswa atau Orang Tua -->
                <div class="flex flex-col gap-1.5">
                  <label class="flex items-center justify-between font-label-md text-label-md text-ardena-navy font-bold" for="studentEmail">
                    <span class="flex items-center gap-space-xs">
                      <span class="material-symbols-outlined text-scratch-sensing text-[20px]">mail</span>
                      <span>Email Siswa atau Orang Tua <span class="text-error">*</span></span>
                    </span>
                  </label>
                  <div class="relative flex items-center">
                    <div class="absolute left-4 pointer-events-none text-on-surface-variant flex items-center">
                      <span class="material-symbols-outlined text-[22px]">alternate_email</span>
                    </div>
                    <input class="w-full h-14 pl-12 pr-4 bg-surface-container-low focus:bg-surface-container-lowest rounded-2xl font-body-md text-body-md text-on-surface placeholder:text-outline outline-none transition-all shadow-inner focus:ring-4 focus:ring-scratch-motion/30" id="studentEmail" placeholder="nama.kamu@orangtua.com" required="" type="email"/>
                  </div>
                </div>

                <!-- 4 & 5. Kata Sandi & Konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                  <div class="flex flex-col gap-1.5">
                    <label class="flex items-center gap-space-xs font-label-md text-label-md text-ardena-navy font-bold" for="password">
                      <span class="material-symbols-outlined text-scratch-sound text-[20px]">lock</span>
                      <span>Kata Sandi Baru <span class="text-error">*</span></span>
                    </label>
                    <div class="relative flex items-center">
                      <input class="w-full h-14 pl-4 pr-12 bg-surface-container-low focus:bg-surface-container-lowest rounded-2xl font-body-md text-body-md text-on-surface placeholder:text-outline outline-none transition-all shadow-inner focus:ring-4 focus:ring-scratch-motion/30" id="password" minlength="6" placeholder="Minimal 6 karakter" required="" type="password"/>
                      <button class="absolute right-3 w-8 h-8 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors" id="toggle-pwd-btn" type="button">
                        <span class="material-symbols-outlined text-[20px]" id="pwdToggleIcon">visibility</span>
                      </button>
                    </div>
                  </div>

                  <div class="flex flex-col gap-1.5">
                    <label class="flex items-center justify-between font-label-md text-label-md text-ardena-navy font-bold" for="confirmPassword">
                      <span class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-scratch-operators text-[20px]">check_circle</span>
                        <span>Konfirmasi Sandi <span class="text-error">*</span></span>
                      </span>
                      <span class="font-label-sm text-label-sm hidden font-bold" id="matchStatusText">Cocok!</span>
                    </label>
                    <div class="relative flex items-center">
                      <input class="w-full h-14 pl-4 pr-12 bg-surface-container-low focus:bg-surface-container-lowest rounded-2xl font-body-md text-body-md text-on-surface placeholder:text-outline outline-none transition-all shadow-inner focus:ring-4 focus:ring-scratch-motion/30" id="confirmPassword" minlength="6" placeholder="Ulangi sandi kamu" required="" type="password"/>
                      <div class="absolute right-3 w-8 h-8 rounded-xl flex items-center justify-center opacity-0 transition-opacity" id="checkIndicator">
                        <span class="material-symbols-outlined text-scratch-operators text-[22px] font-bold">verified</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- 6. Kid-Friendly Checkbox Consent -->
                <div class="bg-surface-container-low/70 rounded-2xl p-space-sm flex items-start gap-space-sm mt-1">
                  <div class="flex items-center h-6 pt-0.5">
                    <input class="w-5 h-5 rounded-md accent-primary text-on-primary cursor-pointer focus:ring-0" id="childConsent" required="" type="checkbox"/>
                  </div>
                  <label class="font-body-sm text-body-sm text-on-surface cursor-pointer select-none" for="childConsent">
                    <span>Saya dan orang tua/wali menyetujui Ketentuan Penggunaan &amp; Kebijakan Privasi Ramah Anak Ardena AI.</span>
                    <span class="block text-on-surface-variant text-xs mt-0.5">Kami tidak pernah menyebarkan data pribadi anak kepada pihak ketiga mana pun.</span>
                  </label>
                </div>

                <!-- 7. Main Action Button -->
                <div class="pt-space-xs">
                  <button class="w-full h-16 rounded-2xl bg-scratch-motion hover:bg-primary text-on-primary font-headline-sm text-headline-sm font-black flex items-center justify-center gap-space-sm shadow-[0_5px_0_#00468b] active:translate-y-1 active:shadow-[0_1px_0_#00468b] transition-all cursor-pointer group" id="submitButton" type="submit">
                    <span id="submitButtonText">Buat Akun Scratcher Cilik</span>
                    <span class="text-2xl group-hover:scale-125 transition-transform">🚀</span>
                  </button>
                </div>

                <!-- Direct link to Login -->
                <div class="flex items-center justify-center gap-space-xs pt-space-xs text-center">
                  <span class="font-body-md text-body-md text-on-surface-variant">Sudah punya akun?</span>
                  <a class="font-label-lg text-label-lg text-primary hover:text-ardena-navy underline font-bold transition-colors" href="/login">
                    Masuk di sini 🐱
                  </a>
                </div>

              </form>

            </div>
          </div>

        </div>

      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="w-full bg-surface-container-lowest shadow-[0_-1px_6px_rgba(0,0,0,0.03)] py-space-lg">
    <div class="w-full px-margin flex flex-col md:flex-row items-center justify-between gap-space-md">
      <div class="flex items-center gap-space-sm">
        <span class="material-symbols-outlined text-scratch-operators text-[22px]">verified_user</span>
        <span class="font-label-md text-label-md text-on-surface font-bold">Aman &amp; Terbimbing</span>
        <span class="text-outline-variant">•</span>
        <span class="font-body-sm text-body-sm text-on-surface-variant">Firebase Auth Active</span>
        <span class="text-outline-variant">•</span>
        <span class="font-body-sm text-body-sm text-on-surface-variant">Scratch 3 Companion</span>
      </div>
      <div class="flex items-center gap-space-lg">
        <span class="font-label-sm text-label-sm text-on-surface-variant">© 2026 Ardena Technologies. Semua hak cipta dilindungi.</span>
      </div>
    </div>
  </footer>

  <!-- Firebase Authentication Scripts -->
  <script type="module">
    import { registerWithEmail } from "/js/firebase-init.js";

    // Grade Pill Selector
    const allPills = document.querySelectorAll('.grade-pill');
    const selectedGradeInput = document.getElementById('selectedGrade');

    allPills.forEach(pill => {
      pill.addEventListener('click', () => {
        allPills.forEach(p => {
          p.classList.remove('active-grade', 'bg-bubble-user', 'text-on-primary', 'shadow-[0_4px_0_#00468b]', 'translate-y-0.5');
          p.classList.add('bg-surface-container', 'hover:bg-surface-container-high', 'shadow-sm');
          
          const label = p.querySelector('span:first-of-type');
          if (label) {
            label.classList.remove('text-on-primary');
            label.classList.add('text-ardena-navy');
          }
          const sub = p.querySelector('span:last-of-type');
          if (sub) {
            sub.classList.remove('text-on-primary/80');
            sub.classList.add('text-on-surface-variant');
          }
        });

        pill.classList.remove('bg-surface-container', 'hover:bg-surface-container-high', 'shadow-sm');
        pill.classList.add('active-grade', 'bg-bubble-user', 'text-on-primary', 'shadow-[0_4px_0_#00468b]', 'translate-y-0.5');
        
        const activeLabel = pill.querySelector('span:first-of-type');
        if (activeLabel) {
          activeLabel.classList.remove('text-ardena-navy');
          activeLabel.classList.add('text-on-primary');
        }
        const activeSub = pill.querySelector('span:last-of-type');
        if (activeSub) {
          activeSub.classList.remove('text-on-surface-variant');
          activeSub.classList.add('text-on-primary/80');
        }

        selectedGradeInput.value = pill.getAttribute('data-grade');
      });
    });

    // Password Toggle
    const toggleBtn = document.getElementById('toggle-pwd-btn');
    const pwdInput = document.getElementById('password');
    const pwdIcon = document.getElementById('pwdToggleIcon');

    toggleBtn.addEventListener('click', () => {
      if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        pwdIcon.textContent = 'visibility_off';
      } else {
        pwdInput.type = 'password';
        pwdIcon.textContent = 'visibility';
      }
    });

    // Validate Password Matching
    const confirmInput = document.getElementById('confirmPassword');
    const checkIndicator = document.getElementById('checkIndicator');
    const statusText = document.getElementById('matchStatusText');

    function checkMatch() {
      const pwd = pwdInput.value;
      const conf = confirmInput.value;

      if (conf.length > 0 && pwd === conf) {
        checkIndicator.classList.remove('opacity-0');
        checkIndicator.classList.add('opacity-100');
        statusText.classList.remove('hidden', 'text-error');
        statusText.classList.add('text-scratch-operators');
        statusText.textContent = 'Sandi Cocok! 👍';
      } else if (conf.length > 0 && pwd !== conf) {
        checkIndicator.classList.add('opacity-0');
        statusText.classList.remove('hidden', 'text-scratch-operators');
        statusText.classList.add('text-error');
        statusText.textContent = 'Belum sama';
      } else {
        checkIndicator.classList.add('opacity-0');
        statusText.classList.add('hidden');
      }
    }

    pwdInput.addEventListener('input', checkMatch);
    confirmInput.addEventListener('input', checkMatch);

    // Feedback Alert Box
    const feedbackBox = document.getElementById('register-status-feedback');
    const feedbackMsg = document.getElementById('register-status-message');
    const feedbackIcon = document.getElementById('register-status-icon');

    function showStatus(message, type = 'info') {
      feedbackBox.classList.remove('hidden', 'bg-surface-container-high', 'bg-red-50', 'bg-green-50', 'text-on-surface', 'text-red-700', 'text-green-700');
      
      if (type === 'error') {
        feedbackBox.classList.add('bg-red-50', 'text-red-700');
        feedbackIcon.textContent = 'error';
        feedbackIcon.className = 'material-symbols-outlined text-[24px] text-red-600';
      } else if (type === 'success') {
        feedbackBox.classList.add('bg-green-50', 'text-green-700');
        feedbackIcon.textContent = 'check_circle';
        feedbackIcon.className = 'material-symbols-outlined text-[24px] text-green-600';
      } else {
        feedbackBox.classList.add('bg-surface-container-high', 'text-on-surface');
        feedbackIcon.textContent = 'progress_activity';
        feedbackIcon.className = 'material-symbols-outlined text-[24px] animate-spin text-primary';
      }

      feedbackMsg.textContent = message;
    }

    // Handle Form Submit
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitButton');
    const submitText = document.getElementById('submitButtonText');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = document.getElementById('studentName').value.trim();
      const grade = selectedGradeInput.value;
      const email = document.getElementById('studentEmail').value.trim();
      const password = pwdInput.value;
      const confirmPwd = confirmInput.value;

      if (password !== confirmPwd) {
        showStatus('Kata sandi dan konfirmasi sandi belum sama ya!', 'error');
        return;
      }

      submitBtn.disabled = true;
      submitText.textContent = 'Menyiapkan Akun Kamu...';
      showStatus('Mendaftarkan akun ke sistem...', 'loading');

      const result = await registerWithEmail(name, grade, email, password);

      if (result.success) {
        showStatus(`Hore! Akun berhasil dibuat. Selamat datang di Ardena AI, ${name}! 🐱🎉`, 'success');
        setTimeout(() => {
          window.location.href = '/';
        }, 1200);
      } else {
        submitBtn.disabled = false;
        submitText.textContent = 'Buat Akun Scratcher Cilik';
        showStatus(result.message, 'error');
      }
    });
  </script>
</body>
</html>
