<template>
  <!-- Layar Penuh 100vh tanpa scrollbar — layout dinamis mengikuti tinggi layar -->
  <div class="h-screen w-screen overflow-hidden bg-[#F3F7FB] font-sans flex flex-col">

    <!-- KARTU FORMULIR REGISTRASI — full height, scroll internal di dalam kartu -->
    <div class="flex-1 flex items-center justify-center p-4 sm:p-6 overflow-hidden">
      <div class="w-full max-w-[820px] bg-white rounded-2xl sm:rounded-[28px] border border-[#E2E8F0] shadow-[0_20px_60px_0_rgba(15,23,42,0.08)] relative flex flex-col"
           style="max-height: calc(100vh - 2rem);">

        <!-- Top Strip Aksen Jawa Barat -->
        <div class="absolute top-0 left-0 right-0 h-[3px] flex rounded-t-2xl sm:rounded-t-[28px] overflow-hidden z-10">
          <div class="w-1/2 bg-[#0757A8]"></div>
          <div class="w-1/4 bg-[#F4CE18]"></div>
          <div class="w-1/4 bg-[#16834A]"></div>
        </div>

        <!-- KONTEN KARTU (flex-col, tidak overflow ke luar) -->
        <div class="flex flex-col flex-1 overflow-hidden pt-[3px]">

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
                <select v-model="form.asal_opd" required
                  class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all">
                  <option value="" disabled selected>Pilih Organisasi Perangkat Daerah</option>
                  <option v-for="opd in opdList" :key="opd" :value="opd">{{ opd }}</option>
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
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">Kata sandi</label>
                <input v-model="form.password" type="password" placeholder="Minimal 8 karakter" required minlength="8"
                  class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]" />
              </div>
              <div>
                <label class="block text-[11px] font-bold text-[#475569] mb-1">Konfirmasi kata sandi</label>
                <input v-model="form.password_confirmation" type="password" placeholder="Ulangi kata sandi" required minlength="8"
                  class="w-full h-9 px-3 text-xs text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#CBD5E1]" />
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
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const loading = ref(false)
const successMessage = ref(null)
const errorMessage = ref(null)

const opdList = [
  'Dinas Komunikasi dan Informatika Provinsi Jawa Barat',
  'Badan Perencanaan Pembangunan Daerah Provinsi Jawa Barat',
  'Dinas Pendidikan Provinsi Jawa Barat',
  'Dinas Kesehatan Provinsi Jawa Barat',
  'Dinas Pekerjaan Umum dan Penataan Ruang Provinsi Jawa Barat',
  'Dinas Perhubungan Provinsi Jawa Barat',
  'Dinas Sosial Provinsi Jawa Barat',
  'Badan Kepegawaian Daerah Provinsi Jawa Barat',
  'Badan Pengelolaan Keuangan dan Aset Daerah Provinsi Jawa Barat',
  'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Provinsi Jawa Barat',
  'Dinas Lingkungan Hidup Provinsi Jawa Barat',
  'Dinas Pariwisata dan Kebudayaan Provinsi Jawa Barat',
  'Dinas Ketahanan Pangan dan Peternakan Provinsi Jawa Barat',
  'Dinas Tanaman Pangan dan Hortikultura Provinsi Jawa Barat',
  'Dinas Perindustrian dan Perdagangan Provinsi Jawa Barat',
  'Dinas Koperasi dan Usaha Kecil Provinsi Jawa Barat',
  'Dinas Tenaga Kerja dan Transmigrasi Provinsi Jawa Barat',
  'Dinas Pemberdayaan Perempuan, Perlindungan Anak dan KB Provinsi Jawa Barat',
  'Dinas Perpustakaan dan Kearsipan Daerah Provinsi Jawa Barat',
  'Badan Kesatuan Bangsa dan Politik Provinsi Jawa Barat',
  'Badan Pendapatan Daerah Provinsi Jawa Barat',
  'Inspektorat Daerah Provinsi Jawa Barat',
  'Sekretariat Daerah Provinsi Jawa Barat',
  'Sekretariat Dewan Perwakilan Rakyat Daerah Provinsi Jawa Barat',
  'Lainnya / Instansi Organisasi Perangkat Daerah Pemohon',
]

const form = reactive({
  nama_lengkap: '',
  nip: '',
  nomor_kontak: '',
  asal_opd: '',
  email: '',
  password: '',
  password_confirmation: '',
  pernyataan: false,
})

function submitRegister() {
  errorMessage.value = null
  successMessage.value = null

  if (form.password !== form.password_confirmation) {
    errorMessage.value = 'Kata sandi dan konfirmasi kata sandi tidak cocok.'
    return
  }

  if (!form.pernyataan) {
    errorMessage.value = 'Anda harus menyetujui pernyataan kebenaran data.'
    return
  }

  loading.value = true
  setTimeout(() => {
    loading.value = false
    const params = new URLSearchParams({
      nama: form.nama_lengkap || 'Raka Pratama',
      email: form.email || 'raka@instansi.go.id',
      opd: form.asal_opd || 'Dinas Komunikasi dan Informatika Provinsi Jawa Barat'
    })
    router.visit(`/registration-status?${params.toString()}`)
  }, 400)
}
</script>
