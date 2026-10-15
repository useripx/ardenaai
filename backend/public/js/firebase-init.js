/**
 * Ardena AI Scratch 3 — Firebase Authentication Client
 * 
 * Mengelola inisialisasi Firebase Web SDK v10 (ESM),
 * autentikasi Google SSO, Email & Kata Sandi siswa,
 * serta penyimpanan sesi dan ID Token ke localStorage.
 */

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { 
  getAuth, 
  signInWithEmailAndPassword, 
  createUserWithEmailAndPassword, 
  signInWithPopup, 
  GoogleAuthProvider,
  updateProfile,
  signOut,
  onAuthStateChanged
} from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

// Konfigurasi Resmi Firebase Web Project: ardenaai
export const firebaseConfig = {
  apiKey: "AIzaSyDL-tuFQzokDEwdyQHvWfmtBy6HYuzWr0I",
  authDomain: "ardenaai.firebaseapp.com",
  projectId: "ardenaai",
  storageBucket: "ardenaai.firebasestorage.app",
  messagingSenderId: "176561479882",
  appId: "1:176561479882:web:c0990929e540170ebf5433",
  measurementId: "G-PN54MWHPT5"
};

// Inisialisasi Firebase App
export const app = initializeApp(firebaseConfig);
export const auth = getAuth(app);
export const googleProvider = new GoogleAuthProvider();

// Parameter khusus Google Auth (selalu minta pilih akun)
googleProvider.setCustomParameters({
  prompt: 'select_account'
});

const TOKEN_KEY = 'ardena_auth_token';
const USER_KEY  = 'ardena_auth_user';

/**
 * Menyimpan data sesi ke localStorage
 */
export function saveSession(user, token, extra = {}) {
  const userData = {
    uid: user.uid,
    name: user.displayName || extra.name || 'Scratcher Cilik',
    email: user.email || '',
    photoURL: user.photoURL || '',
    grade: extra.grade || localStorage.getItem('ardena_student_grade') || '4-6',
    loginMethod: extra.loginMethod || 'email'
  };

  localStorage.setItem(TOKEN_KEY, token);
  localStorage.setItem(USER_KEY, JSON.stringify(userData));
  if (extra.grade) {
    localStorage.setItem('ardena_student_grade', extra.grade);
  }

  return userData;
}

/**
 * Mendapatkan token autentikasi aktif
 */
export function getAuthToken() {
  return localStorage.getItem(TOKEN_KEY) || null;
}

/**
 * Mendapatkan data profil siswa aktif
 */
export function getCurrentUser() {
  const raw = localStorage.getItem(USER_KEY);
  if (!raw) return null;
  try {
    return JSON.parse(raw);
  } catch (e) {
    return null;
  }
}

/**
 * Keluar / Logout
 */
export async function logoutUser() {
  try {
    await signOut(auth);
  } catch (e) {
    console.warn("Firebase signOut notice:", e);
  }
  localStorage.removeItem(TOKEN_KEY);
  localStorage.removeItem(USER_KEY);
  window.location.href = '/login';
}

/**
 * Masuk dengan Google Sign-In (Popup)
 */
export async function loginWithGoogle() {
  try {
    const result = await signInWithPopup(auth, googleProvider);
    const token = await result.user.getIdToken();
    const userData = saveSession(result.user, token, { loginMethod: 'google' });
    return { success: true, user: userData, token };
  } catch (error) {
    console.error("Google Sign-In Error:", error);
    return { 
      success: false, 
      code: error.code || 'AUTH_ERROR',
      message: mapFirebaseErrorMessage(error.code, error.message)
    };
  }
}

/**
 * Masuk dengan Email dan Kata Sandi
 */
export async function loginWithEmail(email, password) {
  try {
    const result = await signInWithEmailAndPassword(auth, email, password);
    const token = await result.user.getIdToken();
    const userData = saveSession(result.user, token, { loginMethod: 'email' });
    return { success: true, user: userData, token };
  } catch (error) {
    console.error("Email Login Error:", error);
    return { 
      success: false, 
      code: error.code || 'AUTH_ERROR',
      message: mapFirebaseErrorMessage(error.code, error.message)
    };
  }
}

/**
 * Pendaftaran Siswa Baru (Email, Kata Sandi, Nama, dan Kelas)
 */
export async function registerWithEmail(name, grade, email, password) {
  try {
    const result = await createUserWithEmailAndPassword(auth, email, password);
    
    // Simpan nama tampilan siswa di profil Firebase
    if (name) {
      await updateProfile(result.user, { displayName: name });
    }

    const token = await result.user.getIdToken();
    const userData = saveSession(result.user, token, { name, grade, loginMethod: 'email' });

    return { success: true, user: userData, token };
  } catch (error) {
    console.error("Email Register Error:", error);
    return { 
      success: false, 
      code: error.code || 'AUTH_ERROR',
      message: mapFirebaseErrorMessage(error.code, error.message)
    };
  }
}

/**
 * Terjemahan pesan error Firebase ke bahasa anak yang ramah & edukatif
 */
export function mapFirebaseErrorMessage(code, rawMsg = '') {
  switch (code) {
    case 'auth/invalid-email':
      return 'Format email belum tepat. Contoh: budi@sekolah.id 📧';
    case 'auth/user-not-found':
    case 'auth/wrong-password':
    case 'auth/invalid-credential':
      return 'Email atau kata sandi tidak cocok. Silakan periksa kembali ya! 🔑';
    case 'auth/email-already-in-use':
      return 'Email ini sudah pernah terdaftar! Silakan langsung masuk di menu Masuk ya 🐱';
    case 'auth/weak-password':
      return 'Kata sandi minimal 6 karakter ya, agar akun belajarmu tetap aman 🔒';
    case 'auth/popup-closed-by-user':
      return 'Jendela masuk Google ditutup sebelum selesai. Silakan klik tombol Google lagi ya!';
    case 'auth/unauthorized-domain':
      return 'Domain belum terdaftar di Firebase Console. Pastikan ardenaai.test sudah ditambahkan!';
    case 'auth/network-request-failed':
      return 'Koneksi internet sedang lambat atau terputus. Cek internetmu lalu coba lagi ya! 🌐';
    default:
      return rawMsg || 'Terjadi kendala saat menghubungkan akun. Minta bantuan Guru atau Orang Tuamu ya! 💡';
  }
}

/**
 * Listener Perubahan Sesi Otomatis
 */
onAuthStateChanged(auth, async (user) => {
  if (user) {
    try {
      const token = await user.getIdToken();
      saveSession(user, token);
    } catch (e) {
      console.warn("Gagal memperbarui token Firebase:", e);
    }
  }
});
