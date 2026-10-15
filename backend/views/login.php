<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta content="web_standard" name="shell-type"/>
  <title><?= htmlspecialchars($pageTitle ?? 'Masuk Siswa — Ardena AI Scratch 3') ?></title>
  
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
  <!-- Top Navigation Header -->
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
        <a class="transition-colors py-space-xs px-space-sm bg-surface-container text-primary font-bold rounded-xl" href="/login">Masuk Siswa</a>
        <a class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors py-space-xs px-space-sm" href="/daftar">Daftar Akun</a>
        <a class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors py-space-xs px-space-sm" href="/">Laboratorium Koding</a>
      </nav>

      <div class="flex items-center gap-space-md">
        <div class="hidden md:flex items-center gap-space-xs px-space-sm py-1 bg-surface-container rounded-full text-on-surface-variant">
          <span class="font-label-sm text-label-sm font-bold uppercase text-primary">ID</span>
          <span class="font-label-sm text-label-sm text-outline">•</span>
          <span class="font-label-sm text-label-sm text-outline hover:text-on-surface transition-colors cursor-pointer">EN</span>
        </div>
        <a href="/" class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-on-primary hover:scale-105 transition-transform" title="Masuk ke Lab Koding">
          <span class="material-symbols-outlined text-[18px]">home</span>
        </a>
      </div>
    </div>
  </header>

  <!-- Main Content Area -->
  <main class="w-full pt-20 bg-canvas-panel relative min-h-screen bg-[radial-gradient(#4C97FF_1.5px,transparent_1.5px)] [background-size:24px_24px]">
    <div class="flex flex-col w-full">
      <div class="relative w-full max-w-5xl mx-auto px-margin-mobile md:px-margin py-space-md md:py-space-xl flex flex-col items-center">
        
        <!-- Floating Scratch Sprites Decor -->
        <div class="hidden lg:flex items-center gap-space-xs absolute -top-2 left-6 bg-scratch-motion/15 text-scratch-motion px-4 py-1.5 rounded-full shadow-sm backdrop-blur-md">
          <span class="material-symbols-outlined text-[18px]">play_circle</span>
          <span class="font-label-sm text-label-sm font-extrabold uppercase tracking-wide">when green flag clicked</span>
        </div>
        <div class="hidden lg:flex items-center gap-space-xs absolute top-12 right-8 bg-scratch-looks/15 text-secondary px-4 py-1.5 rounded-full shadow-sm backdrop-blur-md">
          <span class="material-symbols-outlined text-[18px]">forum</span>
          <span class="font-label-sm text-label-sm font-extrabold tracking-wide">say "Ayo Koding!"</span>
        </div>

        <!-- Main Grid: Companion Mascot Panel + Main Login Stage -->
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">
          
          <!-- Left: Kid Friendly Companion Panel -->
          <div class="lg:col-span-5 flex flex-col items-center lg:items-start text-center lg:text-left gap-space-md order-2 lg:order-1">
            <div class="w-full bg-surface-container-lowest rounded-3xl p-space-lg shadow-[0_12px_30px_rgba(76,151,255,0.12)] relative">
              <!-- Top puzzle notch accent -->
              <div class="absolute -top-3 left-10 w-16 h-3 bg-bubble-user rounded-t-lg"></div>
              
              <div class="flex items-center gap-space-md mb-space-sm">
                <div class="relative">
                  <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center shadow-inner overflow-hidden text-3xl">
                    🤖
                  </div>
                  <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-scratch-operators opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-scratch-operators"></span>
                  </span>
                </div>
                <div class="flex flex-col text-left">
                  <div class="flex items-center gap-1.5">
                    <span class="font-headline-sm text-headline-sm text-ardena-navy font-black">Robot Ardena</span>
                    <span class="material-symbols-outlined text-scratch-events text-[20px]" style="font-variation-settings: 'FILL' 1;">stars</span>
                  </div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">Sahabat AI Belajar Scratch</span>
                </div>
              </div>

              <!-- Speech bubble -->
              <div class="relative bg-surface-container-low rounded-2xl p-space-md text-on-surface">
                <div class="absolute -top-2 left-7 w-4 h-4 bg-surface-container-low rotate-45"></div>
                <p class="font-body-md text-body-md text-ardena-navy font-bold leading-relaxed">
                  "Halo Teman! 🐱 Siap melanjutkan proyek game atau animasimu hari ini? Masuk akunmu sekarang ya!"
                </p>
              </div>

              <!-- Kid-safe guidance tip -->
              <div class="mt-space-md p-space-sm bg-tertiary-fixed/30 rounded-xl flex items-start gap-space-sm text-left">
                <div class="w-8 h-8 rounded-lg bg-scratch-events flex items-center justify-center shrink-0 shadow-[0_2px_0_#c08f00]">
                  <span class="material-symbols-outlined text-on-tertiary-fixed text-[20px]">lightbulb</span>
                </div>
                <div class="flex flex-col">
                  <span class="font-label-sm text-label-sm text-tertiary font-extrabold uppercase">Tips Pendamping:</span>
                  <span class="font-body-sm text-body-sm text-on-surface font-semibold">Minta bantuan Ayah, Ibu, atau Guru di sekolah jika kamu lupa kata sandimu ya!</span>
                </div>
              </div>

              <!-- Mini Block Showcase Preview -->
              <div class="mt-space-md pt-space-sm flex flex-wrap gap-2 justify-center lg:justify-start">
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-scratch-motion text-on-primary font-label-sm text-label-sm rounded-lg shadow-[0_3px_0_#005db6]">
                  <span class="material-symbols-outlined text-[15px]">directions_run</span> Gerak 10 Langkah
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-scratch-sound text-on-primary font-label-sm text-label-sm rounded-lg shadow-[0_3px_0_#9966FF]">
                  <span class="material-symbols-outlined text-[15px]">volume_up</span> Meong Bunyi
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-scratch-control text-on-primary font-label-sm text-label-sm rounded-lg shadow-[0_3px_0_#c08f00]">
                  <span class="material-symbols-outlined text-[15px]">repeat</span> Ulangi Selamanya
                </span>
              </div>
            </div>

            <!-- Safe Learning Badge -->
            <div class="w-full flex items-center justify-center lg:justify-start gap-space-sm px-2 text-on-surface-variant">
              <span class="material-symbols-outlined text-scratch-operators text-[22px]">health_and_safety</span>
              <span class="font-label-sm text-label-sm font-bold">100% Aman untuk Siswa SD • Firebase Authentication Secure</span>
            </div>
          </div>

          <!-- Right: Main Stage Login Card -->
          <div class="lg:col-span-7 w-full order-1 lg:order-2">
            <div class="bg-surface-container-lowest rounded-3xl p-space-md md:p-space-xl shadow-[0_16px_40px_rgba(10,37,64,0.08)] relative overflow-hidden">
              
              <!-- Top Scratch Palette Bar -->
              <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-scratch-motion via-scratch-events to-scratch-operators"></div>

              <!-- Card Header -->
              <div class="flex flex-col gap-1 mb-space-lg">
                <div class="flex items-center justify-between">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-scratch-events/20 text-tertiary-container rounded-full font-label-sm text-label-sm font-black">
                    <span class="material-symbols-outlined text-[16px] text-scratch-events" style="font-variation-settings: 'FILL' 1;">flag</span>
                    MODUL MASUK SISWA
                  </span>
                  <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">Versi 3.0</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-ardena-navy mt-space-xs tracking-tight">
                  Selamat Datang Kembali, Scratcher Cilik! 🚀
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Masuk dengan email belajar atau akun Google sekolah untuk melanjutkan kodingmu.
                </p>
              </div>

              <!-- Dynamic Alert / Feedback State -->
              <div class="hidden mb-space-md p-space-sm rounded-2xl flex items-center gap-space-sm text-on-surface transition-all" id="status-feedback">
                <span class="material-symbols-outlined text-[24px]" id="status-icon">info</span>
                <span class="font-body-sm text-body-sm font-bold" id="status-message">Memeriksa akun belajarmu...</span>
              </div>

              <!-- Login Form -->
              <form class="flex flex-col gap-space-md" id="kid-login-form">
                
                <!-- Email Input Field -->
                <div class="flex flex-col gap-1.5">
                  <label class="font-label-md text-label-md text-ardena-navy font-extrabold flex items-center justify-between" for="student-email">
                    <span class="flex items-center gap-1.5">
                      <span class="w-6 h-6 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[16px]">mail</span>
                      </span>
                      Email Siswa / Akun Belajar
                    </span>
                    <span class="font-label-sm text-label-sm text-primary font-bold">Wajib diisi</span>
                  </label>
                  <div class="relative flex items-center">
                    <input class="w-full h-14 pl-12 pr-4 bg-surface-container-low focus:bg-surface-container-lowest text-on-surface rounded-2xl font-body-lg text-body-lg placeholder:text-outline/70 focus:outline-none focus:ring-4 focus:ring-scratch-motion/30 transition-all shadow-inner" id="student-email" placeholder="contoh: budi@sekolah.id" required="" type="email"/>
                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[22px] pointer-events-none">alternate_email</span>
                  </div>
                  <span class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 pl-1">
                    <span class="material-symbols-outlined text-[16px] text-scratch-operators">check_circle</span>
                    Gunakan email sekolah atau email yang didaftarkan orang tua.
                  </span>
                </div>

                <!-- Password Input Field -->
                <div class="flex flex-col gap-1.5">
                  <div class="flex items-center justify-between">
                    <label class="font-label-md text-label-md text-ardena-navy font-extrabold flex items-center gap-1.5" for="student-password">
                      <span class="w-6 h-6 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[16px]">lock</span>
                      </span>
                      Kata Sandi
                    </label>
                    <a class="font-label-sm text-label-sm text-primary hover:text-on-primary-container font-extrabold flex items-center gap-1 hover:underline transition-colors" href="javascript:void(0)" onclick="alert('Minta bantuan Guru atau Orang Tuamu untuk mengatur ulang kata sandi ya!')">
                      <span class="material-symbols-outlined text-[16px]">key</span>
                      Lupa Sandi?
                    </a>
                  </div>
                  <div class="relative flex items-center">
                    <input class="w-full h-14 pl-12 pr-14 bg-surface-container-low focus:bg-surface-container-lowest text-on-surface rounded-2xl font-body-lg text-body-lg placeholder:text-outline focus:outline-none focus:ring-4 focus:ring-scratch-motion/30 transition-all tracking-wider shadow-inner" id="student-password" placeholder="••••••••" required="" type="password"/>
                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[22px] pointer-events-none">password</span>
                    
                    <button aria-label="Tampilkan / Sembunyikan Sandi" class="absolute right-2 w-10 h-10 rounded-xl bg-surface-container-high hover:bg-surface-variant flex items-center justify-center text-on-surface-variant hover:text-primary transition-all active:scale-95" id="toggle-pwd-btn" type="button">
                      <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                    </button>
                  </div>
                  <span class="font-body-sm text-body-sm text-on-surface-variant pl-1">
                    Rahasia! Jangan beritahu teman yang lain ya 🤫
                  </span>
                </div>

                <!-- Submit Button -->
                <button id="submit-login-btn" class="group relative w-full h-14 mt-space-xs bg-scratch-events hover:bg-tertiary-fixed text-on-tertiary-fixed rounded-2xl font-headline-sm text-headline-sm font-black flex items-center justify-center gap-space-sm shadow-[0_5px_0_#c08f00] hover:shadow-[0_3px_0_#c08f00] hover:translate-y-0.5 active:translate-y-1 active:shadow-none transition-all cursor-pointer" type="submit">
                  <span class="w-8 h-8 rounded-full bg-scratch-operators flex items-center justify-center text-on-primary shadow-sm group-hover:rotate-12 transition-transform">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">flag</span>
                  </span>
                  <span id="submit-login-text">Masuk ke Ruang Koding</span>
                  <span class="material-symbols-outlined text-[22px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>

                <!-- Divider -->
                <div class="relative flex items-center justify-center my-space-xs">
                  <div class="w-full h-0.5 bg-surface-container"></div>
                  <span class="absolute bg-surface-container-lowest px-4 font-label-sm text-label-sm text-on-surface-variant font-bold uppercase tracking-wider">
                    atau
                  </span>
                </div>

                <!-- Google SSO Button -->
                <button id="google-login-btn" class="w-full h-14 bg-surface-container-low hover:bg-surface-container text-ardena-navy rounded-2xl font-label-lg text-label-lg font-bold flex items-center justify-center gap-space-sm shadow-[0_4px_0_#c1c6d5] active:translate-y-1 active:shadow-none transition-all cursor-pointer" type="button">
                  <svg class="w-5 h-5 shrink-0" viewbox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"></path>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"></path>
                  </svg>
                  <span id="google-login-text">Masuk Cepat dengan Akun Sekolah Google</span>
                </button>
              </form>

              <!-- Register Referral Card -->
              <div class="mt-space-lg pt-space-md bg-surface-container-low rounded-2xl p-space-md text-center flex flex-col sm:flex-row items-center justify-between gap-space-sm">
                <div class="flex items-center gap-space-xs text-left">
                  <span class="w-8 h-8 rounded-full bg-scratch-looks/20 text-secondary flex items-center justify-center font-bold">
                    ✨
                  </span>
                  <span class="font-body-md text-body-md text-ardena-navy font-bold">Belum punya akun belajar?</span>
                </div>
                <a class="px-space-md py-2 bg-bubble-user text-on-primary rounded-xl font-label-md text-label-md font-extrabold shadow-[0_3px_0_#005db6] hover:brightness-110 active:translate-y-0.5 active:shadow-none transition-all inline-flex items-center gap-1" href="/daftar">
                  <span>Daftar Baru (Gratis)</span>
                  <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
              </div>

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
        <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="javascript:void(0)" onclick="alert('Ardena AI menerapkan kebijakan perlindungan privasi ketat untuk data anak sesuai standar COPPA.')">Privasi Anak &amp; Ketentuan</a>
      </div>
    </div>
  </footer>

  <!-- Firebase Authentication Scripts -->
  <script type="module">
    import { loginWithEmail, loginWithGoogle, getCurrentUser } from "/js/firebase-init.js";

    // Auto-redirect jika sudah masuk
    const existingUser = getCurrentUser();
    if (existingUser) {
      console.log("Siswa sudah masuk sebelumnya:", existingUser.name);
    }

    // Toggle Password Visibility
    const toggleBtn = document.getElementById('toggle-pwd-btn');
    const pwdInput = document.getElementById('student-password');
    const eyeIcon = document.getElementById('eye-icon');

    toggleBtn.addEventListener('click', () => {
      if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        eyeIcon.textContent = 'visibility_off';
      } else {
        pwdInput.type = 'password';
        eyeIcon.textContent = 'visibility';
      }
    });

    // Feedback helper
    const feedbackBox = document.getElementById('status-feedback');
    const feedbackMsg = document.getElementById('status-message');
    const feedbackIcon = document.getElementById('status-icon');

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

    // Handle Form Submit: Email & Password
    const loginForm = document.getElementById('kid-login-form');
    const submitBtn = document.getElementById('submit-login-btn');
    const submitText = document.getElementById('submit-login-text');

    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = document.getElementById('student-email').value.trim();
      const password = document.getElementById('student-password').value;

      submitBtn.disabled = true;
      submitText.textContent = 'Memeriksa Akun...';
      showStatus('Menghubungkan ke ruang belajar...', 'loading');

      const result = await loginWithEmail(email, password);

      if (result.success) {
        showStatus(`Hore! Berhasil masuk. Selamat datang kembali, ${result.user.name}! 🚀`, 'success');
        setTimeout(() => {
          window.location.href = '/';
        }, 1000);
      } else {
        submitBtn.disabled = false;
        submitText.textContent = 'Masuk ke Ruang Koding';
        showStatus(result.message, 'error');
      }
    });

    // Handle Google SSO Login
    const googleBtn = document.getElementById('google-login-btn');
    const googleText = document.getElementById('google-login-text');

    googleBtn.addEventListener('click', async () => {
      googleBtn.disabled = true;
      googleText.textContent = 'Membuka Google Sign-In...';
      showStatus('Membuka jendela masuk Google... Mohon selesaikan di jendela popup ya! 🔑', 'loading');

      const result = await loginWithGoogle();

      if (result.success) {
        showStatus(`Hore! Berhasil masuk dengan Google. Halo, ${result.user.name}! 🚀`, 'success');
        setTimeout(() => {
          window.location.href = '/';
        }, 1000);
      } else {
        googleBtn.disabled = false;
        googleText.textContent = 'Masuk Cepat dengan Akun Sekolah Google';
        showStatus(result.message, 'error');
      }
    });
  </script>
</body>
</html>
