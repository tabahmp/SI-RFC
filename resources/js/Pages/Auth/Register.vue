<template>
  <!-- Layar Penuh 100vh tanpa scrollbar — layout dinamis mengikuti tinggi layar -->
  <div class="h-screen w-screen overflow-hidden bg-[#F3F7FB] font-sans flex flex-col">

    <!-- KARTU FORMULIR REGISTRASI — full height, scroll internal di dalam kartu -->
    <div class="flex-1 flex items-center justify-center p-4 sm:p-6 overflow-hidden">
      <div class="w-full max-w-[820px] bg-white rounded-2xl sm:rounded-[28px] border border-[#E2E8F0] shadow-[0_20px_60px_0_rgba(15,23,42,0.08)] relative flex flex-col overflow-hidden"
           style="max-height: calc(100vh - 2rem);">

        <!-- Top Strip Aksen Jawa Barat -->
        <div class="absolute top-0 left-0 right-0 h-[4px] flex z-10">
          <div class="w-1/2 bg-[#0757A8]"></div>
          <div class="w-1/4 bg-[#F4CE18]"></div>
          <div class="w-1/4 bg-[#16834A]"></div>
        </div>

        <!-- KONTEN KARTU (flex-col, tidak overflow ke luar) -->
        <div class="flex flex-col flex-1 overflow-hidden pt-[4px]">

          <!-- Header (tidak scroll) -->
          <div class="flex-shrink-0 px-6 sm:px-8 pt-5 pb-3">
            <!-- Tombol Kembali -->
            <div class="mb-3">
              <Link
                href="/login"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#64748B] hover:text-[#0757A8] transition-colors"
              >
                <Icon icon="lucide:arrow-left" class="w-3.5 h-3.5" />
                <span>Kembali ke halaman masuk</span>
              </Link>
            </div>

            <!-- Logo RFC langsung — tanpa wrapper kotak apapun -->
            <div class="flex items-center gap-3.5">
              <img
                src="/images/assets/logo_rfc_asli.png"
                alt="Logo RFC"
                class="h-11 w-auto object-contain flex-shrink-0"
                style="background:none; border:none; box-shadow:none; border-radius:0;"
              />
              <div>
                <div class="text-[10px] font-bold text-[#0757A8] uppercase tracking-widest leading-none">
                  Registrasi Pemohon Organisasi Perangkat Daerah
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight mt-0.5 leading-tight">
                  Buat akun instansi
                </h1>
                <p class="text-[11px] text-[#64748B] mt-0.5 leading-tight">
                  Akun baru berstatus menunggu verifikasi untuk memastikan legalitas kedinasan.
                </p>
              </div>
            </div>

            <!-- Alert -->
            <div v-if="successMessage" class="mt-3 p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
              <div class="flex items-center gap-2">
                <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                <span>{{ successMessage }}</span>
              </div>
              <Link href="/login" class="underline font-bold text-emerald-900 ml-3">Masuk sekarang →</Link>
            </div>
            <div v-if="errorMessage" class="mt-3 p-2.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs font-semibold flex items-center justify-between">
              <div class="flex items-center gap-2">
                <Icon icon="lucide:alert-circle" class="w-4 h-4 text-red-600 flex-shrink-0" />
                <span>{{ errorMessage }}</span>
              </div>
              <button @click="errorMessage = null" class="font-bold text-slate-400 hover:text-slate-600 ml-2">✕</button>
            </div>
          </div>

          <!-- FORMULIR — area tengah, tidak scroll -->
          <form @submit.prevent="submitRegister" class="flex-1 px-6 sm:px-8 flex flex-col justify-center gap-2.5 py-2">

            <!-- Baris 1: Nama Lengkap & NIP/NIK -->
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">Nama lengkap</label>
                <input v-model="form.nama_lengkap" type="text" placeholder="Nama sesuai identitas" required
                  class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]" />
              </div>
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">NIP / NIK</label>
                <input v-model="form.nip" type="text" placeholder="Masukkan NIP atau NIK" required
                  class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]" />
              </div>
            </div>

            <!-- Baris 2: Nomor WA & Organisasi Perangkat Daerah -->
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">Nomor WhatsApp</label>
                <input v-model="form.nomor_kontak" type="text" placeholder="08xxxxxxxxxx" required
                  class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]" />
              </div>
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">Asal Organisasi Perangkat Daerah</label>
                <select v-model="form.opd_id" required
                  class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all">
                  <option value="" disabled selected>Pilih Organisasi Perangkat Daerah</option>
                  <option v-for="opd in opdList" :key="opd.id" :value="opd.id">{{ opd.nama_opd }}</option>
                </select>
              </div>
            </div>

            <!-- Baris 3: Email Dinas -->
            <div>
              <label class="block text-[11px] font-bold text-[#475569] mb-1">Email dinas</label>
              <input v-model="form.email" type="email" placeholder="nama@instansi.go.id" required
                class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]" />
            </div>

            <!-- Baris 4: Kata Sandi & Konfirmasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">Kata sandi</label>
                <div class="relative">
                  <input
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="Minimal 8 karakter unik"
                    required
                    class="w-full h-9 pl-3 pr-8 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]"
                  />
                  <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#94A3B8] hover:text-[#475569]"
                  >
                    <Icon :icon="showPassword ? 'lucide:eye-off' : 'lucide:eye'" class="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">Konfirmasi kata sandi</label>
                <div class="relative">
                  <input
                    v-model="form.password_confirmation"
                    :type="showPasswordConfirm ? 'text' : 'password'"
                    placeholder="Ulangi kata sandi"
                    required
                    class="w-full h-9 pl-3 pr-8 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]"
                  />
                  <button
                    type="button"
                    @click="showPasswordConfirm = !showPasswordConfirm"
                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#94A3B8] hover:text-[#475569]"
                  >
                    <Icon :icon="showPasswordConfirm ? 'lucide:eye-off' : 'lucide:eye'" class="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Indikator Keamanan Password (Live Strength & Checklist Syarat) -->
            <div v-if="form.password" class="p-2.5 rounded-lg bg-[#F8FAFC] border border-[#E2E8F0] space-y-2">
              <div class="flex items-center justify-between text-[10.5px]">
                <span class="font-bold text-[#475569]">Kekuatan kata sandi:</span>
                <span :class="passwordStrength.colorClass" class="font-extrabold uppercase tracking-wide">
                  {{ passwordStrength.label }}
                </span>
              </div>

              <!-- Bar Progres Kekuatan -->
              <div class="w-full h-1.5 bg-[#E2E8F0] rounded-full overflow-hidden flex gap-1">
                <div
                  v-for="i in 4"
                  :key="i"
                  class="flex-1 h-full rounded-full transition-all duration-300"
                  :class="i <= passwordStrength.score ? passwordStrength.barClass : 'bg-[#E2E8F0]'"
                ></div>
              </div>

              <!-- Checklist 4 Kriteria Keamanan -->
              <div class="grid grid-cols-2 gap-x-2 gap-y-1 pt-1 text-[10px]">
                <div class="flex items-center gap-1.5" :class="passwordRules.hasMinLength ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                  <Icon :icon="passwordRules.hasMinLength ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3 h-3 flex-shrink-0" />
                  <span>Minimal 8 karakter</span>
                </div>
                <div class="flex items-center gap-1.5" :class="passwordRules.hasUpper ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                  <Icon :icon="passwordRules.hasUpper ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3 h-3 flex-shrink-0" />
                  <span>Huruf kapital (A-Z)</span>
                </div>
                <div class="flex items-center gap-1.5" :class="passwordRules.hasNumber ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                  <Icon :icon="passwordRules.hasNumber ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3 h-3 flex-shrink-0" />
                  <span>Angka (0-9)</span>
                </div>
                <div class="flex items-center gap-1.5" :class="passwordRules.hasSymbol ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                  <Icon :icon="passwordRules.hasSymbol ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3 h-3 flex-shrink-0" />
                  <span>Simbol unik (@$!%*?&#)</span>
                </div>
              </div>
            </div>

            <!-- Checkbox Pernyataan -->
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
              <input v-model="form.pernyataan" type="checkbox" required
                class="w-4 h-4 rounded border-[#CBD5E1] text-[#0757A8] focus:ring-[#0757A8] flex-shrink-0" />
              <span class="text-[11px] text-[#64748B] leading-tight">
                Saya menyatakan informasi instansi yang diberikan benar dan dapat diverifikasi.
              </span>
            </label>

            <!-- Tombol Submit -->
            <button
              type="submit"
              :disabled="loading"
              class="w-full h-10 flex items-center justify-center gap-2 bg-[#0757A8] hover:bg-[#063D78] text-white rounded-lg font-bold text-sm shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Icon v-if="loading" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
              <Icon v-else icon="lucide:user-plus" class="w-4 h-4" />
              <span>Daftarkan akun</span>
            </button>

          </form>

          <!-- Footer (tidak scroll) -->
          <div class="flex-shrink-0 px-6 sm:px-8 pb-5 pt-3 border-t border-[#E2E8F0] space-y-2">
            <div class="flex items-center justify-center gap-1.5 text-xs text-[#64748B]">
              <span>Sudah memiliki akun?</span>
              <Link href="/login" class="text-[#0757A8] font-bold hover:underline">Kembali ke halaman masuk</Link>
            </div>
            <div class="rounded-lg bg-[#F8FAFC] py-2 px-3 flex items-center justify-center gap-2 text-[11px] text-[#64748B] border border-[#E2E8F0]/70">
              <Icon icon="lucide:shield-check" class="w-4 h-4 text-[#059669] flex-shrink-0" />
              <span>Data akun dilindungi dan hanya digunakan untuk proses layanan perubahan TI.</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const loading = ref(false)
const successMessage = ref(null)
const errorMessage = ref(null)
const showPassword = ref(false)
const showPasswordConfirm = ref(false)

// Evaluasi aturan keamanan password (Kapital, Simbol, Angka, Minimal 8 Karakter)
const passwordRules = computed(() => {
  const pwd = form.password || ''
  return {
    hasMinLength: pwd.length >= 8,
    hasUpper: /[A-Z]/.test(pwd),
    hasNumber: /[0-9]/.test(pwd),
    hasSymbol: /[^A-Za-z0-9]/.test(pwd),
  }
})

const passwordStrength = computed(() => {
  const rules = passwordRules.value
  let score = 0
  if (rules.hasMinLength) score++
  if (rules.hasUpper) score++
  if (rules.hasNumber) score++
  if (rules.hasSymbol) score++

  if (score <= 1) {
    return { score, label: 'Sangat Lemah', colorClass: 'text-rose-500', barClass: 'bg-rose-500' }
  } else if (score === 2) {
    return { score, label: 'Lemah', colorClass: 'text-amber-500', barClass: 'bg-amber-500' }
  } else if (score === 3) {
    return { score, label: 'Sedang', colorClass: 'text-sky-600', barClass: 'bg-sky-600' }
  } else {
    return { score, label: 'Kuat & Aman', colorClass: 'text-emerald-600', barClass: 'bg-emerald-600' }
  }
})

// OPD dari database via props
const props = defineProps({
  opdList: { type: Array, default: () => [] },
  errors:  { type: Object, default: () => ({}) },
})

const form = reactive({
  nama_lengkap: '',
  nip: '',
  nomor_kontak: '',
  opd_id: '',           // FK ke tabel opd
  email: '',
  password: '',
  password_confirmation: '',
  pernyataan: false,
})

function submitRegister() {
  errorMessage.value = null
  successMessage.value = null

  // Validasi Kriteria Keamanan Password (Minimal 8 karakter, Kapital, Angka, dan Simbol)
  const rules = passwordRules.value
  if (!rules.hasMinLength || !rules.hasUpper || !rules.hasNumber || !rules.hasSymbol) {
    errorMessage.value = 'Kata sandi harus minimal 8 karakter dan mengandung huruf kapital, angka, serta simbol unik.'
    return
  }

  if (form.password !== form.password_confirmation) {
    errorMessage.value = 'Kata sandi dan konfirmasi kata sandi tidak cocok.'
    return
  }

  if (!form.pernyataan) {
    errorMessage.value = 'Anda harus menyetujui pernyataan kebenaran data.'
    return
  }

  loading.value = true
  router.post('/register', {
    nama_lengkap:          form.nama_lengkap,
    nip:                   form.nip,
    nomor_kontak:          form.nomor_kontak,
    opd_id:                form.opd_id,
    email:                 form.email,
    password:              form.password,
    password_confirmation: form.password_confirmation,
    pernyataan:            form.pernyataan,
  }, {
    onError: (errors) => {
      loading.value = false
      if (errors.email) {
        errorMessage.value = errors.email
      } else {
        errorMessage.value = Object.values(errors)[0] || 'Terjadi kesalahan saat pendaftaran akun.'
      }
    },
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
