<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Panel Tentor & Lab Komputer — Ardena AI</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet"/>
  
  <style>
    @layer base {
      html, body { margin: 0; padding: 0; }
      body { overscroll-behavior: none; }
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #c1c6d5; border-radius: 9999px; }
    .fade-in { animation: fadeIn 0.25s ease-in-out; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .status-pulse {
      animation: pulseGlow 2s infinite;
    }
    @keyframes pulseGlow {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(76, 151, 255, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(76, 151, 255, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(76, 151, 255, 0); }
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
            sans: ["Nunito Sans", "system-ui", "sans-serif"]
          }
        }
      }
    };
  </script>
</head>

<body class="bg-[#F4F7FC] text-[#0d1c32] font-sans antialiased min-h-screen flex flex-col">

  <!-- Header Panel Guru -->
  <header class="bg-white border-b border-[#E1E8F5] sticky top-0 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      
      <!-- Logo & Judul -->
      <div class="flex items-center space-x-3">
        <a id="brandLogoLink" href="/" class="flex items-center space-x-2">
          <div class="w-10 h-10 rounded-xl bg-scratch-motion flex items-center justify-center text-white shadow-md">
            <span class="material-symbols-outlined text-2xl">school</span>
          </div>
          <div>
            <div class="flex items-center space-x-2">
              <span class="font-extrabold text-lg text-primary leading-tight">Ardena AI</span>
              <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-scratch-looks/15 text-scratch-looks uppercase tracking-wider">Guru & Lab SD</span>
            </div>
            <p class="text-[11px] text-[#717784] font-medium leading-tight">Panel Pengelolaan Kelas & Reset Sandi Lab Komputer</p>
          </div>
        </a>
      </div>

      <!-- Navigasi Cepat & Akun Guru -->
      <div class="flex items-center space-x-3">
        <a id="linkKeChat" href="/chat" class="hidden sm:inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl border border-scratch-motion text-scratch-motion hover:bg-scratch-motion hover:text-white transition-all text-xs font-bold shadow-sm">
          <span class="material-symbols-outlined text-base">forum</span>
          <span>Buka Tanya Ardena</span>
        </a>

        <div class="flex items-center space-x-2 pl-3 border-l border-slate-200">
          <div class="w-9 h-9 rounded-full bg-scratch-motion/15 text-primary flex items-center justify-center font-bold text-sm shadow-inner">
            👩‍🏫
          </div>
          <div class="hidden md:block text-left">
            <div id="teacherNameDisplay" class="text-xs font-bold text-[#0d1c32]">Ibu Sarah, S.Pd</div>
            <div class="text-[10px] text-[#717784] font-semibold">Guru Pembimbing SD</div>
          </div>
        </div>

        <button id="btnSwitchDevRole" onclick="toggleTeacherSession()" title="Ganti Mode Guru / Siswa Lokal" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[#414753] text-[11px] font-bold border border-slate-300">
          🔑 Mode Guru
        </button>
      </div>

    </div>
  </header>

  <!-- Konten Utama Dasbor Guru -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Kartu Informasi Kelas & Lab Komputer -->
    <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
      
      <!-- Kartu 1: Kode Kelas -->
      <div class="bg-white rounded-2xl p-5 border border-[#E1E8F5] shadow-sm flex items-start space-x-4">
        <div class="w-12 h-12 rounded-xl bg-scratch-motion/15 text-scratch-motion flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">class</span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-bold text-[#717784] uppercase tracking-wider">Ruang Kelas SD</p>
          <h2 id="classroomCodeDisplay" class="text-lg font-black text-primary truncate">SCRATCH-4B-2026</h2>
          <p id="classroomLabDisplay" class="text-xs text-[#717784] mt-0.5">Lab Komputer SD-04</p>
        </div>
      </div>

      <!-- Kartu 2: Sandi Default Siswa -->
      <div class="bg-white rounded-2xl p-5 border border-[#E1E8F5] shadow-sm flex items-start space-x-4">
        <div class="w-12 h-12 rounded-xl bg-scratch-events/20 text-scratch-control flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">key</span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-bold text-[#717784] uppercase tracking-wider">Sandi Default Reset</p>
          <div class="flex items-center space-x-1.5 mt-0.5">
            <code id="defaultPasswordDisplay" class="px-2 py-0.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 font-mono text-sm font-bold">scratch123</code>
          </div>
          <p class="text-[11px] text-slate-500 mt-1">Sandi seragam saat siswa lupa kata sandi</p>
        </div>
      </div>

      <!-- Kartu 3: Status Lab Aktif -->
      <div class="bg-white rounded-2xl p-5 border border-[#E1E8F5] shadow-sm flex items-start space-x-4">
        <div class="w-12 h-12 rounded-xl bg-scratch-operators/20 text-scratch-operators flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">computer</span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-bold text-[#717784] uppercase tracking-wider">Siswa Aktif di Lab</p>
          <div class="flex items-baseline space-x-2 mt-0.5">
            <span id="activeStudentsCount" class="text-2xl font-black text-scratch-operators">3</span>
            <span id="totalStudentsCount" class="text-xs text-[#717784] font-semibold">dari 5 siswa</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-0.5">Sedang membuka modul Scratch</p>
        </div>
      </div>

      <!-- Kartu 4: Tindakan Cepat Lab -->
      <div class="bg-white rounded-2xl p-5 border border-[#E1E8F5] shadow-sm flex flex-col justify-between">
        <div>
          <p class="text-xs font-bold text-[#717784] uppercase tracking-wider">Tindakan Cepat Lab</p>
          <p class="text-xs text-slate-500 mt-1">Putus sesi bila komputer lab selesai jam pelajaran</p>
        </div>
        <button onclick="confirmClearAllSessions()" class="mt-3 w-full inline-flex items-center justify-center space-x-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-bold transition-all shadow-sm">
          <span class="material-symbols-outlined text-base">logout</span>
          <span>Keluarkan Semua Sesi Lab</span>
        </button>
      </div>

    </section>

    <!-- Kontrol Pencarian & Filter Siswa -->
    <section class="bg-white rounded-2xl p-4 border border-[#E1E8F5] shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
      
      <!-- Input Cari Siswa -->
      <div class="relative w-full sm:w-80">
        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-lg">search</span>
        <input 
          id="searchStudentInput" 
          type="text" 
          placeholder="Cari nama atau email siswa..." 
          oninput="filterStudentsTable()"
          class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-scratch-motion/50 focus:border-scratch-motion bg-slate-50 focus:bg-white transition-all font-medium"
        />
      </div>

      <!-- Filter Status & Refresh -->
      <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
        <select id="statusFilterSelect" onchange="filterStudentsTable()" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-[#414753] bg-slate-50 focus:outline-none">
          <option value="all">Semua Status (Online & Offline)</option>
          <option value="online">Hanya yang Sedang Online 🟢</option>
          <option value="offline">Hanya yang Sedang Offline ⚪</option>
        </select>

        <button onclick="fetchClassroomData()" title="Segarkan Data Siswa" class="p-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-600 transition-all flex items-center justify-center">
          <span class="material-symbols-outlined text-lg">refresh</span>
        </button>
      </div>

    </section>

    <!-- Tabel Daftar Siswa Kelas Koding Scratch -->
    <section class="bg-white rounded-2xl border border-[#E1E8F5] shadow-sm overflow-hidden">
      
      <div class="px-6 py-4 border-b border-[#E1E8F5] flex items-center justify-between">
        <div>
          <h3 class="font-extrabold text-base text-[#0d1c32]">Daftar Akun Siswa & Komputer Lab</h3>
          <p class="text-xs text-[#717784]">Guru dapat langsung mereset sandi siswa yang lupa tanpa menunggu verifikasi email</p>
        </div>
        <span id="badgeTableCount" class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">5 Siswa</span>
      </div>

      <!-- Wadah Tabel Responsif -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
              <th class="py-3 px-6">Siswa & Akun</th>
              <th class="py-3 px-6">Tingkat / Fase</th>
              <th class="py-3 px-6">Proyek Scratch Terakhir</th>
              <th class="py-3 px-6">Status Komputer Lab</th>
              <th class="py-3 px-6 text-right">Aksi Pembimbing</th>
            </tr>
          </thead>
          <tbody id="studentsTableBody" class="divide-y divide-slate-100 text-xs font-medium text-[#0d1c32]">
            <!-- Skeleton Loader / Render JavaScript -->
            <tr>
              <td colspan="5" class="py-8 text-center text-slate-400">
                <span class="material-symbols-outlined text-3xl animate-spin">progress_activity</span>
                <p class="mt-2 text-xs">Memuat data lab komputer kelas...</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </section>

  </main>

  <!-- Modal Konfirmasi One-Click Reset Sandi -->
  <div id="resetModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 fade-in">
      
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">lock_reset</span>
        </div>
        <div>
          <h3 class="font-extrabold text-base text-slate-900">One-Click Classroom Reset</h3>
          <p class="text-xs text-slate-500">Reset sandi instan untuk siswa di lab komputer</p>
        </div>
      </div>

      <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 mb-4 space-y-2">
        <div class="flex justify-between items-center text-xs">
          <span class="text-slate-500">Nama Siswa:</span>
          <span id="modalStudentName" class="font-bold text-slate-800">-</span>
        </div>
        <div class="flex justify-between items-center text-xs">
          <span class="text-slate-500">Email Akun:</span>
          <span id="modalStudentEmail" class="font-bold text-slate-800">-</span>
        </div>
        <div class="flex justify-between items-center text-xs">
          <span class="text-slate-500">Sandi Baru Otomatis:</span>
          <span id="modalNewPassword" class="font-mono font-bold text-scratch-motion bg-blue-50 px-2 py-0.5 rounded border border-blue-200">scratch123</span>
        </div>
      </div>

      <p class="text-xs text-slate-600 mb-5 leading-relaxed">
        Siswa bersangkutan dapat langsung masuk kembali ke Ardena AI menggunakan kata sandi <strong class="font-mono text-primary">scratch123</strong>. Sesi komputer aktif sebelumnya akan diputus secara aman.
      </p>

      <div class="flex items-center justify-end space-x-2">
        <button onclick="closeResetModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
          Batal
        </button>
        <button id="btnExecuteReset" onclick="executePasswordReset()" class="px-4 py-2 rounded-xl bg-scratch-motion hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-md flex items-center space-x-1.5">
          <span class="material-symbols-outlined text-base">check_circle</span>
          <span>Reset Sandi Sekarang</span>
        </button>
      </div>

    </div>
  </div>

  <!-- Toast Notifikasi Ringan -->
  <div id="toastNotification" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none">
    <div id="toastCard" class="px-4 py-3 rounded-xl shadow-xl flex items-center space-x-2 text-xs font-bold text-white bg-slate-900 border border-slate-800">
      <span id="toastIcon" class="material-symbols-outlined text-base">info</span>
      <span id="toastMessage">Pesan pemberitahuan</span>
    </div>
  </div>

  <!-- Script Logika Panel Guru -->
  <script>
    // Dynamic Base Path Resolver
    const BASE_PATH = window.location.pathname.includes('/ardenaai') ? '/ardenaai' : '';
    function getApiUrl(endpoint) {
      const clean = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
      return BASE_PATH + (clean.startsWith('api/') ? '/' + clean : '/api/' + clean);
    }
    function getPageUrl(path) {
      return BASE_PATH + (path.startsWith('/') ? path : '/' + path);
    }

    // Inisialisasi Tautan Navigasi Dinamis
    document.getElementById('brandLogoLink').href = getPageUrl('/admin');
    document.getElementById('linkKeChat').href = getPageUrl('/chat');

    // Token Guru Pembimbing (Default dev_guru_sarah untuk lab komputer SD)
    function getTeacherAuthToken() {
      return localStorage.getItem('ardena_admin_token') || 'dev_guru_sarah';
    }

    let classroomStudents = [];
    let targetResetStudent = null;

    // Memuat Data Statistik dan Siswa Kelas
    async function fetchClassroomData() {
      try {
        const token = getTeacherAuthToken();
        
        // 1. Ambil Statistik Kelas
        const statsRes = await fetch(getApiUrl('/admin/stats'), {
          headers: { 'Authorization': 'Bearer ' + token }
        });
        const statsData = await statsRes.json();
        
        if (statsData.success && statsData.data) {
          const s = statsData.data;
          document.getElementById('classroomCodeDisplay').textContent = s.classroom_code || 'SCRATCH-4B-2026';
          document.getElementById('classroomLabDisplay').textContent = s.lab_room || 'Lab Komputer SD-04';
          document.getElementById('defaultPasswordDisplay').textContent = s.default_password || 'scratch123';
          document.getElementById('modalNewPassword').textContent = s.default_password || 'scratch123';
          document.getElementById('activeStudentsCount').textContent = s.active_in_lab ?? 0;
          document.getElementById('totalStudentsCount').textContent = 'dari ' + (s.total_students ?? 0) + ' siswa';
          if (s.teacher_name) {
            document.getElementById('teacherNameDisplay').textContent = s.teacher_name;
          }
        }

        // 2. Ambil Daftar Siswa
        const stdRes = await fetch(getApiUrl('/admin/students'), {
          headers: { 'Authorization': 'Bearer ' + token }
        });
        const stdData = await stdRes.json();

        if (stdData.success && stdData.data && stdData.data.students) {
          classroomStudents = stdData.data.students;
          renderStudentsTable(classroomStudents);
        } else if (stdRes.status === 403) {
          showToast('Akses Ditolak: Hanya Guru Pembimbing yang diizinkan.', 'error');
        }
      } catch (err) {
        console.error('Gagal mengambil data lab guru:', err);
        showToast('Gagal memuat data kelas dari server.', 'error');
      }
    }

    // Render Tabel Siswa
    function renderStudentsTable(students) {
      const tbody = document.getElementById('studentsTableBody');
      const badgeCount = document.getElementById('badgeTableCount');
      if (badgeCount) badgeCount.textContent = students.length + ' Siswa';

      if (!students || students.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="5" class="py-8 text-center text-slate-400">
              <span class="material-symbols-outlined text-3xl">sentiment_dissatisfied</span>
              <p class="mt-2 text-xs">Tidak ada siswa yang sesuai kriteria pencarian.</p>
            </td>
          </tr>
        `;
        return;
      }

      tbody.innerHTML = students.map(std => {
        const isOnline = (std.status === 'online');
        const statusBadge = isOnline 
          ? `<span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-[11px] font-bold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span>Online di Lab</span>`
          : `<span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 text-[11px] font-semibold">Offline</span>`;

        return `
          <tr class="hover:bg-blue-50/40 transition-colors">
            <!-- Siswa & Akun -->
            <td class="py-3.5 px-6">
              <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-scratch-motion/10 text-xl flex items-center justify-center flex-shrink-0 shadow-inner">
                  ${std.avatar || '🐱'}
                </div>
                <div>
                  <div class="font-bold text-slate-800 text-xs">${escapeHtml(std.name)}</div>
                  <div class="text-[11px] text-slate-400 font-mono">${escapeHtml(std.email)}</div>
                </div>
              </div>
            </td>

            <!-- Tingkat Kelas -->
            <td class="py-3.5 px-6 text-xs text-slate-600">
              <span class="px-2 py-0.5 rounded-lg bg-slate-100 font-semibold text-[11px]">${escapeHtml(std.grade || 'Kelas 4 SD')}</span>
            </td>

            <!-- Proyek Terakhir -->
            <td class="py-3.5 px-6 text-xs">
              <div class="flex items-center space-x-1.5 text-primary font-bold">
                <span class="material-symbols-outlined text-sm text-scratch-motion">extension</span>
                <span class="truncate max-w-[200px]" title="${escapeHtml(std.active_project || 'Belum ada proyek')}">
                  ${escapeHtml(std.active_project || 'Belum ada proyek')}
                </span>
              </div>
            </td>

            <!-- Status Komputer Lab -->
            <td class="py-3.5 px-6">
              ${statusBadge}
            </td>

            <!-- Aksi Pembimbing -->
            <td class="py-3.5 px-6 text-right">
              <div class="flex items-center justify-end space-x-2">
                <button 
                  onclick="openResetModal('${std.uid}', '${escapeHtml(std.name)}', '${escapeHtml(std.email)}')" 
                  title="One-Click Classroom Reset Sandi" 
                  class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-amber-500/15 hover:bg-amber-500 text-amber-900 hover:text-white font-bold text-xs transition-all border border-amber-300 shadow-sm"
                >
                  <span class="material-symbols-outlined text-sm">lock_reset</span>
                  <span>Reset Sandi</span>
                </button>

                <button 
                  onclick="clearStudentSession('${std.uid}', '${escapeHtml(std.name)}')" 
                  title="Keluarkan Sesi Komputer Siswa" 
                  class="p-1.5 rounded-xl border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-400 transition-all"
                >
                  <span class="material-symbols-outlined text-base">logout</span>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    // Filter & Cari Siswa
    function filterStudentsTable() {
      const query = document.getElementById('searchStudentInput').value.toLowerCase().trim();
      const statusFilter = document.getElementById('statusFilterSelect').value;

      const filtered = classroomStudents.filter(std => {
        const matchesQuery = !query || 
          (std.name && std.name.toLowerCase().includes(query)) || 
          (std.email && std.email.toLowerCase().includes(query));

        const matchesStatus = (statusFilter === 'all') || 
          (statusFilter === 'online' && std.status === 'online') || 
          (statusFilter === 'offline' && std.status !== 'online');

        return matchesQuery && matchesStatus;
      });

      renderStudentsTable(filtered);
    }

    // Buka Modal Reset Sandi
    function openResetModal(uid, name, email) {
      targetResetStudent = { uid, name, email };
      document.getElementById('modalStudentName').textContent = name;
      document.getElementById('modalStudentEmail').textContent = email;
      const modal = document.getElementById('resetModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeResetModal() {
      const modal = document.getElementById('resetModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      targetResetStudent = null;
    }

    // Eksekusi Reset Sandi Instan (One-Click Reset)
    async function executePasswordReset() {
      if (!targetResetStudent) return;
      const btn = document.getElementById('btnExecuteReset');
      btn.disabled = true;
      btn.innerHTML = `<span class="material-symbols-outlined text-base animate-spin">progress_activity</span><span>Mereset...</span>`;

      try {
        const token = getTeacherAuthToken();
        const res = await fetch(getApiUrl('/admin/reset-password'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
          },
          body: JSON.stringify({
            student_uid: targetResetStudent.uid,
            student_email: targetResetStudent.email
          })
        });

        const data = await res.json();
        if (data.success) {
          showToast(`Sandi ${targetResetStudent.name} berhasil direset ke 'scratch123'! 🔑`, 'success');
          closeResetModal();
          fetchClassroomData();
        } else {
          showToast(data.message || 'Gagal mereset kata sandi siswa.', 'error');
        }
      } catch (err) {
        console.error('Error saat reset sandi:', err);
        showToast('Terjadi kesalahan jaringan saat mereset kata sandi.', 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-outlined text-base">check_circle</span><span>Reset Sandi Sekarang</span>`;
      }
    }

    // Putus Sesi Komputer Tunggal
    async function clearStudentSession(uid, name) {
      if (!confirm(`Keluarkan sesi komputer lab untuk ${name}?`)) return;

      try {
        const token = getTeacherAuthToken();
        const res = await fetch(getApiUrl('/admin/clear-lab-sessions'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
          },
          body: JSON.stringify({ student_uid: uid })
        });
        const data = await res.json();
        if (data.success) {
          showToast(`Sesi lab untuk ${name} berhasil dikeluarkan. 🚪`, 'success');
          fetchClassroomData();
        } else {
          showToast(data.message || 'Gagal mengeluarkan sesi.', 'error');
        }
      } catch (err) {
        showToast('Terjadi kendala jaringan.', 'error');
      }
    }

    // Putus Seluruh Sesi Lab Komputer
    async function confirmClearAllSessions() {
      if (!confirm("Peringatan: Seluruh komputer lab siswa yang masih online akan dikeluarkan. Lanjutkan?")) return;

      try {
        const token = getTeacherAuthToken();
        const res = await fetch(getApiUrl('/admin/clear-lab-sessions'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
          },
          body: JSON.stringify({})
        });
        const data = await res.json();
        if (data.success) {
          showToast(data.message || 'Seluruh sesi komputer lab berhasil dibersihkan! 🚪', 'success');
          fetchClassroomData();
        } else {
          showToast(data.message || 'Gagal membersihkan sesi lab.', 'error');
        }
      } catch (err) {
        showToast('Terjadi kendala jaringan.', 'error');
      }
    }

    // Toast Helper
    function showToast(message, type = 'info') {
      const toast = document.getElementById('toastNotification');
      const card = document.getElementById('toastCard');
      const icon = document.getElementById('toastIcon');
      const msg = document.getElementById('toastMessage');

      msg.textContent = message;
      if (type === 'success') {
        card.className = 'px-4 py-3 rounded-xl shadow-xl flex items-center space-x-2 text-xs font-bold text-white bg-emerald-600 border border-emerald-500';
        icon.textContent = 'check_circle';
      } else if (type === 'error') {
        card.className = 'px-4 py-3 rounded-xl shadow-xl flex items-center space-x-2 text-xs font-bold text-white bg-rose-600 border border-rose-500';
        icon.textContent = 'error';
      } else {
        card.className = 'px-4 py-3 rounded-xl shadow-xl flex items-center space-x-2 text-xs font-bold text-white bg-slate-900 border border-slate-800';
        icon.textContent = 'info';
      }

      toast.classList.remove('translate-y-24', 'opacity-0');
      toast.classList.add('translate-y-0', 'opacity-100');

      setTimeout(() => {
        toast.classList.add('translate-y-24', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
      }, 3500);
    }

    // Helper Escape HTML
    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    // Switcher Dev Role (Kemudahan Testing Guru vs Siswa)
    function toggleTeacherSession() {
      const current = localStorage.getItem('ardena_admin_token');
      if (current === 'dev_siswa_andi') {
        localStorage.setItem('ardena_admin_token', 'dev_guru_sarah');
        showToast('Beralih ke Sesi Guru (Ibu Sarah, S.Pd)', 'success');
      } else {
        localStorage.setItem('ardena_admin_token', 'dev_guru_sarah');
        showToast('Sesi Guru Aktif (Ibu Sarah, S.Pd)', 'info');
      }
      fetchClassroomData();
    }

    // Mulai saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
      // Pastikan token guru tersimpan jika belum ada
      if (!localStorage.getItem('ardena_admin_token')) {
        localStorage.setItem('ardena_admin_token', 'dev_guru_sarah');
      }
      fetchClassroomData();
    });
  </script>

</body>
</html>
