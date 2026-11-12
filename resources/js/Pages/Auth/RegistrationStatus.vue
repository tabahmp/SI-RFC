<template>
  <!-- Layar Penuh 100vh tanpa scrollbar (overflow-hidden) persis layout halaman register -->
  <div class="h-screen w-screen overflow-hidden bg-[#F3F7FB] font-sans flex flex-col">
    
    <!-- WRAPPER KARTU STATUS — ukuran dan padding disesuaikan dengan kartu Register -->
    <div class="flex-1 flex items-center justify-center p-4 sm:p-6 overflow-hidden">
      <div class="w-full max-w-[820px] bg-white rounded-2xl sm:rounded-[28px] border border-[#E2E8F0] shadow-[0_20px_60px_0_rgba(15,23,42,0.08)] relative flex flex-col overflow-hidden p-6 sm:p-9 space-y-4 my-auto"
           style="max-height: calc(100vh - 2rem);">
        
        <!-- Top Strip Aksen Jawa Barat (Khas Diskominfo Jabar) -->
        <div class="absolute top-0 left-0 right-0 h-[4px] flex z-10">
          <div class="w-1/2 bg-[#0757A8]"></div>
          <div class="w-1/4 bg-[#F4CE18]"></div>
          <div class="w-1/4 bg-[#16834A]"></div>
        </div>

      <!-- BAGIAN 1: Logo & Header Judul -->
      <div class="space-y-3">
        <!-- Logo Aplikasi: Logo RFC & Diskominfo murni tanpa kotak border -->
        <div class="flex items-center gap-3.5">
          <img
            src="/images/assets/logo_rfc_asli.png"
            alt="Logo Request for Change"
            class="h-9 sm:h-10 w-auto object-contain"
          />
          <div class="h-6 w-[1px] bg-slate-200"></div>
          <img
            src="/images/assets/logo_diskominfo_jabar.png"
            alt="Logo Diskominfo Jabar"
            class="h-7 sm:h-8 w-auto object-contain"
          />
        </div>

        <div>
          <!-- Tagline Registrasi Pemohon -->
          <div class="text-[10.5px] font-bold text-[#0757A8] tracking-wider uppercase">
            REGISTRASI PEMOHON ORGANISASI PERANGKAT DAERAH
          </div>
          <!-- Judul Utama -->
          <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight mt-0.5 leading-snug">
            Pendaftaran berhasil diterima
          </h1>
          <!-- Deskripsi -->
          <p class="text-[11.5px] sm:text-[13px] text-[#64748B] mt-0.5 leading-relaxed">
            Akun Anda menunggu verifikasi administrator. Akses pengajuan RFC tersedia setelah identitas kedinasan disetujui.
          </p>
        </div>
      </div>

      <!-- BAGIAN 2: Alert Status Menunggu Verifikasi Akun -->
      <div class="my-2.5 p-3 rounded-xl bg-[#FFFBEB] border border-[#FEF3C7] flex items-start gap-2.5">
        <div class="w-6 h-6 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 text-amber-800 mt-0.5">
          <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
        </div>
        <div class="space-y-0.5">
          <div class="text-[10.5px] font-bold text-[#92400E] tracking-wider uppercase">
            MENUNGGU VERIFIKASI AKUN
          </div>
          <div class="text-[11.5px] text-[#92400E]/90 leading-relaxed">
            Status akun berbeda dari status permohonan RFC. Verifikasi dilakukan untuk memastikan keabsahan aparatur sipil negara dan instansi terkait.
          </div>
        </div>
      </div>

      <!-- BAGIAN 3: Ringkasan Pendaftaran -->
      <div class="p-3 sm:p-3.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
          <div>
            <div class="text-[10.5px] font-medium text-[#64748B]">Nama pemohon</div>
            <div class="text-[13px] font-semibold text-[#334155] mt-0.5 truncate">
              {{ pemohonName }}
            </div>
          </div>
          <div>
            <div class="text-[10.5px] font-medium text-[#64748B]">Email dinas</div>
            <div class="text-[13px] font-semibold text-[#334155] mt-0.5 truncate">
              {{ pemohonEmail }}
            </div>
          </div>
        </div>
        <div class="pt-1.5 border-t border-[#E2E8F0]/70 flex items-center gap-2 text-[11px] text-[#64748B]">
          <Icon icon="lucide:building-2" class="w-3.5 h-3.5 text-[#0757A8] flex-shrink-0" />
          <span class="truncate">Organisasi Perangkat Daerah: <strong class="text-[#334155] font-semibold">{{ pemohonOpd }}</strong></span>
        </div>
      </div>

      <!-- BAGIAN 4: Alur Proses Verifikasi (Stepper 3 Tahap) -->
      <div class="my-1 py-1 space-y-2.5">
        <!-- Tahap 1: Selesai -->
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-[#047857] flex items-center justify-center text-white flex-shrink-0 mt-0.5">
            <Icon icon="lucide:check" class="w-3.5 h-3.5 stroke-[3]" />
          </div>
          <div class="leading-tight">
            <div class="text-xs sm:text-[13px] font-bold text-[#047857]">
              Formulir registrasi dikirim
            </div>
            <div class="text-[11px] sm:text-xs text-[#64748B] mt-0.5">
              Identitas dan kontak pemohon berhasil diterima oleh sistem.
            </div>
          </div>
        </div>

        <!-- Tahap 2: Sedang Diproses -->
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-[#0757A8] flex items-center justify-center text-white font-bold text-xs flex-shrink-0 mt-0.5">
            2
          </div>
          <div class="leading-tight">
            <div class="text-xs sm:text-[13px] font-bold text-[#1E293B]">
              Pemeriksaan administrator
            </div>
            <div class="text-[11px] sm:text-xs text-[#64748B] mt-0.5">
              Kesesuaian identitas dan Organisasi Perangkat Daerah sedang diperiksa oleh administrator.
            </div>
          </div>
        </div>

        <!-- Tahap 3: Menunggu -->
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs flex-shrink-0 mt-0.5">
            3
          </div>
          <div class="leading-tight">
            <div class="text-xs sm:text-[13px] font-bold text-[#64748B]">
              Akun aktif & siap mengajukan
            </div>
            <div class="text-[11px] sm:text-xs text-[#94A3B8] mt-0.5">
              Pemberitahuan hasil verifikasi dan aktivasi akun dikirim ke email dinas.
            </div>
          </div>
        </div>
      </div>

      <!-- BAGIAN 5: Tombol Kembali ke Halaman Masuk (Presisi Sesuai Figma) -->
      <div class="pt-2">
        <Link
          href="/login"
          class="w-full h-11 flex items-center justify-center bg-[#0757A8] hover:bg-[#063D78] text-white rounded-lg font-semibold text-xs sm:text-sm shadow-sm transition-all text-center tracking-wide"
        >
          Kembali ke halaman masuk
        </Link>
      </div>

      <!-- KETERANGAN KOREKSI DATA DI DALAM KARTU/FOOTER KARTU -->
      <div class="text-center text-[11px] text-[#64748B] pt-2 border-t border-[#E2E8F0]/70 leading-relaxed">
        Butuh koreksi data? Hubungi administrator melalui kanal kedinasan instansi Anda.
      </div>

    </div>
  </div>
</div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  nama: {
    type: String,
    default: 'Raka Pratama'
  },
  email: {
    type: String,
    default: 'raka@instansi.go.id'
  },
  opd: {
    type: String,
    default: 'Dinas Komunikasi dan Informatika Provinsi Jawa Barat'
  }
})

// Gunakan URL query params jika dioper lewat URL, atau default ke props
const searchParams = typeof window !== 'undefined' ? new URLSearchParams(window.location.search) : null

const pemohonName = computed(() => {
  return searchParams?.get('nama') || props.nama
})

const pemohonEmail = computed(() => {
  return searchParams?.get('email') || props.email
})

const pemohonOpd = computed(() => {
  return searchParams?.get('opd') || props.opd
})
</script>

