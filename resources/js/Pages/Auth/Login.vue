<template>
  <!-- Layar Penuh 100vh dinamis dan responsif, terpusat rapi -->
  <div class="min-h-screen w-full bg-[#F3F7FB] flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans">
    
    <!-- Kartu Login: Proporsional, Lebih Luas tapi Presisi di Tengah -->
    <div class="w-full max-w-[1360px] h-[92vh] max-h-[860px] min-h-[580px] bg-white rounded-2xl lg:rounded-[32px] shadow-[0_20px_50px_rgba(7,59,112,0.08)] overflow-hidden grid grid-cols-1 lg:grid-cols-12 border border-[#DAE3EF]/80">
      
      <!-- ======================================================================= -->
      <!-- PANEL KIRI (HERO BRANDING JABAR) - 7 Kolom                              -->
      <!-- ======================================================================= -->
      <div class="hidden lg:flex lg:col-span-7 bg-gradient-to-br from-[#073B70] via-[#0757A8] to-[#16834A] p-10 xl:p-14 text-white flex-col justify-between relative overflow-hidden h-full">
        
        <!-- Lingkaran Dekoratif Asli Figma SVG -->
        <div class="absolute -top-[120px] -right-[60px] pointer-events-none select-none opacity-30">
          <svg width="460" height="460" viewBox="0 0 430 430" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="215" cy="215" r="175" stroke="#ffffff" stroke-width="70" />
          </svg>
        </div>

        <!-- 1. Logo RFC & Diskominfo Jabar (Bersih tanpa kotak putih) -->
        <div class="relative z-10 flex items-center gap-6 w-fit">
          <img src="/images/assets/logo_rfc_asli.png" alt="Logo RFC" class="h-14 xl:h-16 w-auto object-contain drop-shadow" />
          <div class="h-9 w-[1.5px] bg-white/35"></div>
          <img src="/images/assets/logo_diskominfo_jabar.png" alt="Logo Diskominfo Jabar" class="h-11 xl:h-12 w-auto object-contain drop-shadow" />
        </div>

        <!-- 2. Konten Utama Tengah (Label, Headline, Deskripsi) -->
        <div class="relative z-10 my-auto py-6">
          <div class="inline-flex items-center h-7 px-3.5 rounded-full bg-[#F4CE18] text-[#073B70] text-[10px] xl:text-[11px] font-black tracking-[1.8px] uppercase mb-5 shadow-xs">
            IT SERVICE MANAGEMENT
          </div>

          <h1 class="text-3xl xl:text-[44px] font-extrabold text-white leading-[1.2] tracking-tight mb-4">
            Perubahan layanan TI<br />
            yang lebih tertib, aman,<br />
            dan terukur.
          </h1>

          <p class="text-sm xl:text-[15px] text-[#E0EAF5] leading-[26px] max-w-[500px]">
            Kelola seluruh siklus Request for Change berdasarkan standar  Information Technology Infrastructure Library, mulai dari pengajuan hingga dokumentasi BAST (Berita Acara Serah Terima).
          </p>
        </div>

        <!-- 3. Tiga Kartu Manfaat Bawah -->
        <div class="relative z-10 grid grid-cols-3 gap-3.5 pt-2">
          <div class="bg-[#106144]/60 backdrop-blur-md rounded-xl p-3.5 border border-white/15 flex flex-col justify-between">
            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white mb-2">
              <Icon icon="lucide:check" class="w-4 h-4 text-emerald-300" />
            </div>
            <div class="text-xs font-bold text-white leading-tight">Audit trail lengkap</div>
          </div>

          <div class="bg-[#106144]/60 backdrop-blur-md rounded-xl p-3.5 border border-white/15 flex flex-col justify-between">
            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white mb-2">
              <Icon icon="lucide:check" class="w-4 h-4 text-emerald-300" />
            </div>
            <div class="text-xs font-bold text-white leading-tight">SLA terpantau</div>
          </div>

          <div class="bg-[#106144]/60 backdrop-blur-md rounded-xl p-3.5 border border-white/15 flex flex-col justify-between">
            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white mb-2">
              <Icon icon="lucide:check" class="w-4 h-4 text-emerald-300" />
            </div>
            <div class="text-xs font-bold text-white leading-tight">Akses berbasis peran</div>
          </div>
        </div>

        <!-- Garis Aksen Jawa Barat (Khas Diskominfo Jabar) -->
        <div class="absolute bottom-0 left-0 right-0 h-[4px] flex">
          <div class="w-1/2 bg-[#0757A8]"></div>
          <div class="w-1/4 bg-[#F4CE18]"></div>
          <div class="w-1/4 bg-[#16834A]"></div>
        </div>
      </div>

      <!-- ======================================================================= -->
      <!-- PANEL KANAN (FORMULIR MASUK + CAPTCHA) - 5 Kolom                        -->
      <!-- ======================================================================= -->
      <div class="col-span-1 lg:col-span-5 px-8 sm:px-12 xl:px-14 py-8 xl:py-10 flex flex-col justify-between bg-white h-full overflow-y-auto">
        <div class="my-auto w-full max-w-[460px] mx-auto">
          
          <!-- Headline Masuk -->
          <div class="mb-6">
            <div class="text-xs font-bold text-[#0757A8] uppercase tracking-[1.2px] mb-1.5">
              Portal Layanan SI-RFC
            </div>
            <h2 class="text-2xl xl:text-[30px] font-extrabold text-[#0B1021] leading-tight tracking-tight">
              Selamat datang kembali
            </h2>
            <p class="text-xs xl:text-[13px] text-[#71839B] mt-1.5 leading-relaxed">
              Masuk menggunakan akun instansi yang sudah diverifikasi.
            </p>
          </div>

          <!-- Alert Error -->
          <div v-if="errorMessage" class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center gap-2">
              <Icon icon="lucide:alert-circle" class="w-4 h-4 flex-shrink-0" />
              <span>{{ errorMessage }}</span>
            </div>
            <button @click="errorMessage = null" class="text-slate-400 hover:text-slate-600 font-bold ml-2">✕</button>
          </div>

          <!-- FORMULIR INPUT -->
          <form @submit.prevent="submitLogin" class="space-y-4">
            
            <!-- Email Dinas -->
            <div>
              <label class="block text-xs font-bold text-[#2D405E] mb-1.5">Email dinas</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <Icon icon="lucide:mail" class="w-4 h-4 text-[#718AAB]" />
                </span>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="nama@instansi.go.id"
                  required
                  class="w-full pl-10 pr-3.5 py-3 text-sm text-[#344965] bg-white border border-[#DAE3EF] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#71839B]"
                />
              </div>
            </div>

            <!-- Kata Sandi -->
            <div>
              <label class="block text-xs font-bold text-[#2D405E] mb-1.5">Kata sandi</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <Icon icon="lucide:lock" class="w-4 h-4 text-[#718AAB]" />
                </span>
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Masukkan kata sandi"
                  required
                  class="w-full pl-10 pr-10 py-3 text-sm text-[#344965] bg-white border border-[#DAE3EF] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#71839B]"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#718AAB] hover:text-[#344965] transition-colors"
                >
                  <Icon :icon="showPassword ? 'lucide:eye-off' : 'lucide:eye'" class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Ingat Saya & Lupa Kata Sandi -->
            <div class="flex items-center justify-between text-xs pt-0.5">
              <label class="flex items-center gap-2 cursor-pointer text-[#70829D] font-medium select-none">
                <input
                  v-model="form.remember"
                  type="checkbox"
                  class="w-4 h-4 rounded border-[#8C98A6] text-[#0757A8] focus:ring-[#0757A8]"
                />
                <span>Ingat saya</span>
              </label>

              <Link href="/forgot-password" class="text-[#0757A8] hover:text-[#063D78] font-semibold transition-colors">
                Lupa kata sandi?
              </Link>
            </div>

            <!-- VERIFIKASI KEAMANAN CAPTCHA (ICONIFY) -->
            <div class="pt-1">
              <div class="flex items-center justify-between mb-1.5">
                <label class="text-xs font-bold text-[#2D405E] flex items-center gap-1.5">
                  <Icon icon="lucide:shield-alert" class="w-4 h-4 text-[#0757A8]" />
                  <span>Verifikasi Captcha</span>
                </label>
                <button
                  type="button"
                  @click="generateCaptcha"
                  class="text-xs text-[#0757A8] hover:underline font-semibold flex items-center gap-1"
                >
                  <Icon icon="lucide:rotate-cw" class="w-3.5 h-3.5 text-[#0757A8]" />
                  <span>Acak Ulang</span>
                </button>
              </div>

              <div class="flex items-center gap-2.5">
                <!-- Tampilan Box Captcha Text -->
                <div class="bg-[#F3F7FB] border border-[#DAE3EF] rounded-xl px-4 py-2.5 font-mono text-base font-extrabold tracking-[0.25em] text-[#073B70] select-none relative overflow-hidden shadow-xs flex items-center justify-center min-w-[120px]">
                  <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#0757A8 1px, transparent 1px); background-size: 5px 5px;"></div>
                  <span class="relative z-10 font-black italic tracking-widest">{{ captchaCode }}</span>
                </div>

                <!-- Input Kode Captcha -->
                <input
                  v-model="form.captchaInput"
                  type="text"
                  placeholder="Ketik kode"
                  maxlength="5"
                  required
                  class="flex-1 px-4 py-2.5 text-sm text-[#344965] bg-white border border-[#DAE3EF] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all uppercase font-mono tracking-widest placeholder:normal-case placeholder:font-sans placeholder:tracking-normal placeholder:text-[#71839B]"
                />
              </div>
            </div>

            <!-- Tombol Masuk ke SI-RFC -->
            <div class="pt-2">
              <button
                type="submit"
                :disabled="loading"
                class="w-full h-12 flex items-center justify-center gap-2 bg-[#0757A8] hover:bg-[#063D78] text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span>Masuk ke SI-RFC</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Footer Bawah: Belum Memiliki Akun? -->
        <div class="pt-4 border-t border-[#DFE7F0] flex items-center justify-center gap-1.5 text-xs text-[#70829D]">
          <span>Belum memiliki akun?</span>
          <Link href="/register" class="text-[#0757A8] font-semibold hover:underline flex items-center gap-0.5">
            <span>Daftar sebagai pemohon</span>
            <Icon icon="lucide:chevron-right" class="w-3.5 h-3.5" />
          </Link>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const showPassword = ref(false)
const loading = ref(false)
const errorMessage = ref(null)
const captchaCode = ref('')

const form = reactive({
  email: '',
  password: '',
  remember: false,
  captchaInput: '',
})

// Fungsi pembuat kode Captcha 5 karakter acak unik
function generateCaptcha() {
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'
  let res = ''
  for (let i = 0; i < 5; i++) {
    res += chars.charAt(Math.floor(Math.random() * chars.length))
  }
  captchaCode.value = res
  form.captchaInput = ''
}

onMounted(() => {
  generateCaptcha()
})

function submitLogin() {
  errorMessage.value = null

  // Validasi Captcha
  if (form.captchaInput.trim().toUpperCase() !== captchaCode.value) {
    errorMessage.value = 'Kode Captcha tidak sesuai. Silakan coba kembali.'
    generateCaptcha()
    return
  }

  loading.value = true
  setTimeout(() => {
    loading.value = false
    router.visit('/form-perubahan-it')
  }, 400)
}
</script>
