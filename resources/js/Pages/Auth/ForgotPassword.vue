<template>
  <!-- Layar Penuh 100vh tanpa scrollbar (overflow-hidden) -->
  <div class="h-screen w-screen overflow-hidden bg-[#F3F7FB] flex flex-col items-center justify-center p-3 sm:p-5 font-sans">
    
    <!-- KARTU PEMULIHAN KATA SANDI (Persis Figma Frame 34 · Pemulihan kata sandi) -->
    <div class="w-full max-w-[560px] bg-white rounded-2xl sm:rounded-[20px] border border-[#E2E8F0] shadow-[0_20px_50px_rgba(15,23,42,0.06)] overflow-hidden relative p-6 sm:p-8 flex flex-col justify-between my-auto">
      
      <!-- Top Strip Aksen Jawa Barat (Khas Diskominfo Jabar) -->
      <div class="absolute top-0 left-0 right-0 h-[4px] flex z-10">
        <div class="w-1/2 bg-[#0757A8]"></div>
        <div class="w-1/4 bg-[#F4CE18]"></div>
        <div class="w-1/4 bg-[#16834A]"></div>
      </div>

      <!-- Tombol Kembali -->
      <div>
        <Link
          href="/login"
          class="inline-flex items-center gap-1.5 text-xs font-bold text-[#64748B] hover:text-[#0757A8] transition-colors mb-4"
        >
          <Icon icon="lucide:arrow-left" class="w-4 h-4" />
          <span>Kembali ke halaman masuk</span>
        </Link>

        <!-- Header -->
        <div class="space-y-1">
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase">
            PEMULIHAN AKUN
          </div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">
            Lupa kata sandi?
          </h1>
          <p class="text-xs sm:text-[13px] text-[#64748B] leading-relaxed mt-1">
            Masukkan email dinas yang terdaftar. Tautan untuk mengatur ulang kata sandi akan dikirim ke email Anda.
          </p>
        </div>
      </div>

      <!-- Alert Notifikasi Terkirim -->
      <div v-if="submitted" class="my-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-1">
        <div class="font-bold flex items-center gap-1.5">
          <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600" />
          <span>Tautan pemulihan berhasil dikirim!</span>
        </div>
        <p class="text-emerald-700">
          Silakan periksa kotak masuk atau folder spam email dinas <strong>{{ email }}</strong> untuk mereset kata sandi.
        </p>
      </div>

      <!-- Form Input Email Dinas -->
      <form v-else @submit.prevent="submitRecovery" class="space-y-4 my-4">
        <div>
          <label class="block text-xs font-bold text-[#475569] mb-1.5">
            Email dinas <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
              <Icon icon="lucide:mail" class="w-4 h-4" />
            </span>
            <input
              v-model="email"
              type="email"
              placeholder="nama@instansi.go.id"
              required
              class="w-full h-11 pl-9 pr-3 text-xs sm:text-sm text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all placeholder:text-[#94A3B8]"
            />
          </div>
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full h-11 flex items-center justify-center gap-2 bg-[#0757A8] hover:bg-[#063D78] text-white rounded-lg font-bold text-xs sm:text-sm shadow-sm transition-all disabled:opacity-50"
        >
          <Icon v-if="loading" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
          <Icon v-else icon="lucide:send" class="w-4 h-4" />
          <span>Kirim tautan pemulihan</span>
        </button>
      </form>

      <!-- Kotak Panduan Tautan Reset -->
      <div class="p-3.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1.5">
        <div class="text-xs font-bold text-[#334155] flex items-center gap-1.5">
          <Icon icon="lucide:info" class="w-4 h-4 text-[#0757A8]" />
          <span>Periksa kotak masuk dan folder spam</span>
        </div>
        <p class="text-[11px] text-[#64748B] leading-relaxed">
          Jika email terdaftar, tautan pemulihan akan dikirim. Jangan bagikan tautan kepada pihak lain. Hubungi administrator jika email dinas tidak dapat diakses.
        </p>
      </div>

      <!-- Teks Penutup -->
      <div class="mt-4 pt-3 border-t border-[#F1F5F9] text-center text-xs text-[#64748B]">
        Akses akun kembali setelah kata sandi berhasil diperbarui.
      </div>

    </div>

    <!-- Footer SI-RFC Luar Kartu -->
    <div class="text-center text-[11px] text-[#64748B] mt-4 max-w-[560px]">
      SI-RFC • Portal layanan perubahan TI Pemerintah Provinsi Jawa Barat
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const email = ref('')
const loading = ref(false)
const submitted = ref(false)

function submitRecovery() {
  if (!email.value) return
  loading.value = true
  setTimeout(() => {
    loading.value = false
    submitted.value = true
  }, 600)
}
</script>

