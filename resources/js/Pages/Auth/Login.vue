<template>
  <!-- Layar Penuh 100vh tanpa scrollbar dengan background cerah Figma #F3F7FB -->
  <div class="h-screen w-screen overflow-hidden bg-[#F3F7FB] flex items-center justify-center p-3 sm:p-5 lg:p-6 font-sans">
    
    <!-- Kartu Login Dinamis: Pas Mengisi Viewport Tanpa Scrollbar -->
    <div class="w-full max-w-[1240px] h-full max-h-[94vh] bg-white rounded-2xl sm:rounded-[28px] shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 border border-[#DAE3EF]/70">
      
      <!-- ======================================================================= -->
      <!-- PANEL KIRI (HERO BRANDING JABAR) - 7 Kolom                              -->
      <!-- ======================================================================= -->
      <div class="hidden lg:flex lg:col-span-7 bg-gradient-to-br from-[#073B70] via-[#0757A8] to-[#16834A] p-8 xl:p-12 text-white flex-col justify-between relative overflow-hidden h-full">
        
        <!-- Lingkaran Dekoratif Asli Figma SVG -->
        <div class="absolute -top-[140px] -right-[60px] pointer-events-none select-none opacity-40">
          <svg width="430" height="430" viewBox="0 0 430 430" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="215" cy="215" r="175" stroke="#164B81" stroke-width="80" />
          </svg>
        </div>

        <!-- 1. Posisi Logo RFC & Diskominfo Jabar (Dipindahkan ke kiri atas dan diperbesar presisi) -->
        <div class="relative z-10 flex items-center gap-5 bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20 w-fit shadow-sm">
          <img src="/images/assets/logo_rfc_asli.png" alt="Logo RFC" class="h-14 w-auto object-contain drop-shadow-sm" />
          <div class="h-8 w-px bg-white/30"></div>
          <img src="/images/assets/logo_diskominfo_jabar.png" alt="Logo Diskominfo Jabar" class="h-10 w-auto object-contain drop-shadow-sm" />
        </div>

        <!-- 2. Konten Utama Tengah (Label, Headline 44px, Deskripsi) -->
        <div class="relative z-10 my-auto py-4">
          <div class="inline-flex items-center h-6 px-3 rounded-full bg-[#F4CE18] text-[#073B70] text-[9px] font-extrabold tracking-[1.6px] uppercase mb-4 shadow-xs">
            IT SERVICE MANAGEMENT
          </div>

          <h1 class="text-3xl xl:text-[42px] font-extrabold text-white leading-[1.15] tracking-tight mb-3">
            Perubahan layanan TI<br />
            yang lebih tertib, aman,<br />
            dan terukur.
          </h1>

          <p class="text-[13px] xl:text-[14px] text-[#E0EAF5] leading-[22px] max-w-[480px]">
            Kelola seluruh siklus Request for Change berdasarkan standar ITIL, mulai dari pengajuan hingga dokumentasi BAST.
          </p>
        </div>

        <!-- 3. Tiga Kartu Manfaat Bawah (Iconify) -->
        <div class="relative z-10 grid grid-cols-3 gap-2.5 pt-2">
          <div class="bg-[#106144]/70 backdrop-blur-md rounded-xl p-2.5 border border-[#32608E]/50 flex flex-col justify-between">
            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-white mb-1.5">
              <Icon icon="lucide:check" class="w-3.5 h-3.5 text-emerald-300" />
            </div>
            <div class="text-[11px] font-bold text-white leading-tight">Audit trail lengkap</div>
          </div>

          <div class="bg-[#106144]/70 backdrop-blur-md rounded-xl p-2.5 border border-[#32608E]/50 flex flex-col justify-between">
            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-white mb-1.5">
              <Icon icon="lucide:check" class="w-3.5 h-3.5 text-emerald-300" />
            </div>
            <div class="text-[11px] font-bold text-white leading-tight">SLA terpantau</div>
          </div>

          <div class="bg-[#106144]/70 backdrop-blur-md rounded-xl p-2.5 border border-[#32608E]/50 flex flex-col justify-between">
            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-white mb-1.5">
              <Icon icon="lucide:check" class="w-3.5 h-3.5 text-emerald-300" />
            </div>
            <div class="text-[11px] font-bold text-white leading-tight">Akses berbasis peran</div>
          </div>
        </div>

        <!-- Garis Aksen Jawa Barat (Khas Diskominfo Jabar) -->
        <div class="absolute bottom-0 left-0 right-0 h-[3px] flex">
          <div class="w-1/2 bg-[#0757A8]"></div>
          <div class="w-1/4 bg-[#F4CE18]"></div>
          <div class="w-1/4 bg-[#16834A]"></div>
        </div>
      </div>

      <!-- ======================================================================= -->
      <!-- PANEL KANAN (FORMULIR MASUK + CAPTCHA) - 5 Kolom                        -->
      <!-- ======================================================================= -->
      <div class="col-span-1 lg:col-span-5 p-6 sm:p-8 xl:p-12 flex flex-col justify-between bg-white h-full overflow-hidden">
        <div class="my-auto">
          
          <!-- Headline Masuk (Lebih bersih dan luas setelah logo dipindah ke kiri) -->
          <div class="mb-5">
            <div class="text-[11px] font-bold text-[#0757A8] uppercase tracking-[1px] mb-1">
              Portal Layanan SI-RFC
            </div>
            <h2 class="text-2xl sm:text-[28px] font-extrabold text-[#0B1021] leading-tight tracking-tight">
              Selamat datang kembali
            </h2>
            <p class="text-[12px] text-[#71839B] mt-1">
              Masuk menggunakan akun instansi yang sudah diverifikasi.
            </p>
          </div>

          <!-- Alert Error -->
          <div v-if="errorMessage" class="mb-3 p-2.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center gap-1.5">
              <Icon icon="lucide:alert-circle" class="w-4 h-4 flex-shrink-0" />
              <span>{{ errorMessage }}</span>
            </div>
            <button @click="errorMessage = null" class="text-slate-400 hover:text-slate-600 font-bold ml-2">✕</button>
          </div>

          <!-- FORMULIR INPUT -->
          <form @submit.prevent="submitLogin" class="space-y-3.5">
            
            <!-- Email Dinas -->
            <div>
              <label class="block text-[11px] font-bold text-[#2D405E] mb-1">Email dinas</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Icon icon="lucide:mail" class="w-4 h-4 text-[#718AAB]" />
                </span>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="nama@instansi.go.id"
                  required
                  class="w-full pl-9 pr-3 py-2.5 text-xs text-[#344965] bg-white border border-[#DAE3EF] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#71839B]"
                />
              </div>
            </div>

            <!-- Kata Sandi -->
            <div>
              <label class="block text-[11px] font-bold text-[#2D405E] mb-1">Kata sandi</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Icon icon="lucide:lock" class="w-4 h-4 text-[#718AAB]" />
                </span>
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Masukkan kata sandi"
                  required
                  class="w-full pl-9 pr-9 py-2.5 text-xs text-[#344965] bg-white border border-[#DAE3EF] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#71839B]"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#718AAB] hover:text-[#344965] transition-colors"
                >
                  <Icon :icon="showPassword ? 'lucide:eye-off' : 'lucide:eye'" class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Ingat Saya & Lupa Kata Sandi -->
            <div class="flex items-center justify-between text-[11px] pt-0.5">
              <label class="flex items-center gap-1.5 cursor-pointer text-[#70829D] font-normal select-none">
                <input
                  v-model="form.remember"
                  type="checkbox"
                  class="w-3.5 h-3.5 rounded border-[#8C98A6] text-[#0757A8] focus:ring-[#0757A8]"
                />
                <span>Ingat saya</span>
              </label>

              <Link href="/forgot-password" class="text-[#0757A8] hover:text-[#063D78] font-semibold transition-colors">
                Lupa kata sandi?
              </Link>
            </div>

            <!-- VERIFIKASI KEAMANAN CAPTCHA (ICONIFY) -->
            <div class="pt-1.5">
              <div class="flex items-center justify-between mb-1">
                <label class="text-[11px] font-bold text-[#2D405E] flex items-center gap-1.5">
                  <Icon icon="lucide:shield-alert" class="w-3.5 h-3.5 text-[#0757A8]" />
                  <span>Verifikasi Captcha</span>
                </label>
                <button
                  type="button"
                  @click="generateCaptcha"
                  class="text-[10px] text-[#0757A8] hover:underline font-semibold flex items-center gap-1"
                >
                  <Icon icon="lucide:rotate-cw" class="w-3 h-3 text-[#0757A8]" />
                  <span>Acak Ulang</span>
                </button>
              </div>

              <div class="flex items-center gap-2">
                <!-- Tampilan Box Captcha Text -->
                <div class="bg-[#F3F7FB] border border-[#DAE3EF] rounded-lg px-3 py-2 font-mono text-[15px] font-extrabold tracking-[0.25em] text-[#073B70] select-none relative overflow-hidden shadow-xs flex items-center justify-center min-w-[110px]">
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
                  class="flex-1 px-3 py-2 text-xs text-[#344965] bg-white border border-[#DAE3EF] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all uppercase font-mono tracking-widest placeholder:normal-case placeholder:font-sans placeholder:tracking-normal placeholder:text-[#71839B]"
                />
              </div>
            </div>

            <!-- Tombol Masuk ke SI-RFC -->
            <div class="pt-2">
              <button
                type="submit"
                :disabled="loading"
                class="w-full h-11 flex items-center justify-center gap-2 bg-[#0757A8] hover:bg-[#063D78] text-white rounded-lg font-semibold text-xs sm:text-[13px] shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <Icon v-if="loading" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                <span>Masuk ke SI-RFC</span>
                <Icon v-if="!loading" icon="lucide:arrow-right" class="w-4 h-4" />
              </button>
            </div>
          </form>
        </div>

        <!-- Footer Bawah: Belum Memiliki Akun? -->
        <div class="pt-3 border-t border-[#DFE7F0] flex items-center justify-center gap-1.5 text-xs text-[#70829D]">
          <span>Belum memiliki akun?</span>
          <Link href="/register" class="text-[#0757A8] font-semibold hover:underline flex items-center gap-0.5">
            <span>Daftar sebagai pemohon</span>
            <Icon icon="lucide:chevron-right" class="w-3 h-3" />
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
