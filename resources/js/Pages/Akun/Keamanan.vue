<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto space-y-6">

      <!-- Breadcrumb -->
      <div>
        <nav class="text-xs text-slate-500 mb-1 flex items-center gap-1">
          <span>Akun</span><span>/</span>
          <Link href="/akun/profil" class="hover:text-[#0757a8]">Pengaturan</Link>
          <span>/</span>
          <span class="text-slate-700 font-medium">Keamanan</span>
        </nav>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ubah kata sandi</h1>
        <p class="text-sm text-slate-500 mt-0.5">Perbarui kata sandi untuk menjaga keamanan akses akun kedinasan Anda.</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form ubah kata sandi -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h2 class="text-sm font-bold text-slate-800 mb-1">Keamanan akun</h2>
            <p class="text-xs text-slate-500 mb-5">Masukkan kata sandi saat ini sebelum membuat kata sandi baru.</p>

            <div v-if="sukses" class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-xs">
              <div class="flex items-center gap-2">
                <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                <span class="font-semibold">{{ sukses }}</span>
              </div>
              <button @click="sukses = null" class="text-emerald-700 hover:text-emerald-900 font-bold ml-2">✕</button>
            </div>

            <form @submit.prevent="ubahKataSandi" class="space-y-4">
              <!-- Kata sandi saat ini -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Kata sandi saat ini <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="form.password_lama"
                    :type="show.lama ? 'text' : 'password'"
                    placeholder="Masukkan kata sandi saat ini"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition"
                  />
                  <button
                    type="button"
                    @click="show.lama = !show.lama"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-[#0757A8] font-semibold hover:text-[#063D78]"
                  >
                    {{ show.lama ? 'Sembunyikan' : 'Tampilkan' }}
                  </button>
                </div>
                <p v-if="errors.password_lama" class="mt-1 text-xs text-red-500 font-medium">{{ errors.password_lama }}</p>
                <div class="mt-1">
                  <Link href="/lupa-kata-sandi" class="text-xs text-[#0757A8] font-semibold hover:underline">
                    Lupa kata sandi saat ini?
                  </Link>
                </div>
              </div>

              <!-- Kata sandi baru -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Kata sandi baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="form.password_baru"
                    :type="show.baru ? 'text' : 'password'"
                    placeholder="Minimal 8 karakter unik"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition"
                  />
                  <button
                    type="button"
                    @click="show.baru = !show.baru"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-[#0757A8] font-semibold hover:text-[#063D78]"
                  >
                    {{ show.baru ? 'Sembunyikan' : 'Tampilkan' }}
                  </button>
                </div>
                <p v-if="errors.password_baru" class="mt-1 text-xs text-red-500 font-medium">{{ errors.password_baru }}</p>
              </div>

              <!-- Indikator Keamanan Password (Live Strength & 4 Syarat: Kapital, Angka, Simbol) -->
              <div v-if="form.password_baru" class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="font-bold text-slate-700">Kekuatan kata sandi:</span>
                  <span :class="passwordStrength.colorClass" class="font-black uppercase tracking-wider text-[11px]">
                    {{ passwordStrength.label }}
                  </span>
                </div>

                <!-- Bar Progres Kekuatan -->
                <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden flex gap-1">
                  <div
                    v-for="i in 4"
                    :key="i"
                    class="flex-1 h-full rounded-full transition-all duration-300"
                    :class="i <= passwordStrength.score ? passwordStrength.barClass : 'bg-slate-200'"
                  ></div>
                </div>

                <!-- Checklist 4 Kriteria Keamanan -->
                <div class="grid grid-cols-2 gap-x-2 gap-y-1.5 pt-1 text-[11px]">
                  <div class="flex items-center gap-1.5" :class="passwordRules.hasMinLength ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                    <Icon :icon="passwordRules.hasMinLength ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                    <span>Minimal 8 karakter</span>
                  </div>
                  <div class="flex items-center gap-1.5" :class="passwordRules.hasUpper ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                    <Icon :icon="passwordRules.hasUpper ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                    <span>Huruf kapital (A-Z)</span>
                  </div>
                  <div class="flex items-center gap-1.5" :class="passwordRules.hasNumber ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                    <Icon :icon="passwordRules.hasNumber ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                    <span>Angka (0-9)</span>
                  </div>
                  <div class="flex items-center gap-1.5" :class="passwordRules.hasSymbol ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                    <Icon :icon="passwordRules.hasSymbol ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                    <span>Simbol unik (@$!%*?&#)</span>
                  </div>
                </div>
              </div>

              <!-- Konfirmasi kata sandi baru -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Konfirmasi kata sandi baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="form.password_konfirmasi"
                    :type="show.konfirmasi ? 'text' : 'password'"
                    placeholder="Ulangi kata sandi baru"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition"
                  />
                  <button
                    type="button"
                    @click="show.konfirmasi = !show.konfirmasi"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-[#0757A8] font-semibold hover:text-[#063D78]"
                  >
                    {{ show.konfirmasi ? 'Sembunyikan' : 'Tampilkan' }}
                  </button>
                </div>
                <p v-if="errors.password_konfirmasi" class="mt-1 text-xs text-red-500 font-medium">{{ errors.password_konfirmasi }}</p>
              </div>

              <div class="flex items-center gap-3 pt-2">
                <button
                  type="submit"
                  :disabled="loading"
                  class="px-5 py-2.5 rounded-xl bg-[#0757A8] text-white text-xs font-bold hover:bg-[#063D78] transition shadow-xs disabled:opacity-50 flex items-center gap-2"
                >
                  <Icon v-if="loading" icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
                  <Icon v-else icon="lucide:key-round" class="w-3.5 h-3.5" />
                  <span>{{ loading ? 'Menyimpan...' : 'Simpan kata sandi' }}</span>
                </button>
                <Link
                  href="/akun/profil"
                  class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition"
                >
                  Batalkan
                </Link>
              </div>
            </form>

            <p class="text-[11px] text-slate-400 mt-6 border-t border-slate-100 pt-4">
              Jika Anda tidak merasa meminta perubahan kata sandi, hubungi administrator melalui Pusat Bantuan.
            </p>
          </div>
        </div>

        <!-- Info keamanan -->
        <div>
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Lindungi akses akun Anda</h2>
            <div class="p-3.5 rounded-xl bg-green-50 border border-green-200 mb-4">
              <div class="text-xs font-bold text-green-800">Akun Kedinasan Terverifikasi</div>
              <div class="text-[11px] text-green-700 mt-0.5">Peran aktif: {{ labelPeran }}</div>
            </div>

            <div class="space-y-3 text-xs text-slate-600">
              <div class="flex items-start gap-2.5">
                <Icon icon="lucide:shield-check" class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                <span>Kata sandi wajib memuat kombinasi huruf kapital, angka, dan simbol unik untuk standar SMKI ISO/IEC 27001.</span>
              </div>
              <div class="flex items-start gap-2.5">
                <Icon icon="lucide:lock" class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" />
                <span>Jangan gunakan ulang kata sandi yang pernah dipakai di akun lain.</span>
              </div>
              <div class="flex items-start gap-2.5">
                <Icon icon="lucide:log-out" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" />
                <span>Selalu keluar (*logout*) dari peramban setelah selesai menggunakan komputer bersama.</span>
              </div>
            </div>

            <p class="text-[11px] text-slate-400 mt-5 pt-3 border-t border-slate-100">
              Perubahan kata sandi tidak mengubah data profil, peran, atau riwayat permohonan RFC.
            </p>
          </div>
        </div>

      </div>

      <div class="text-[11px] text-slate-400 text-center pb-2">
        SI-RFC v2.0 &middot; SMKI ISO/IEC 27001 &middot; Pengaturan Keamanan Akun Kedinasan
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'
import axios from 'axios'

const page     = usePage()
const authUser = computed(() => page.props.auth?.user)
const labelPeran = computed(() => ({
  admin: 'Administrator', pemohon: 'Pemohon OPD',
  verifikator: 'Verifikator', pimpinan: 'Pimpinan', teknisi: 'Teknisi',
}[authUser.value?.peran] ?? authUser.value?.peran ?? '-'))

const form = reactive({ password_lama: '', password_baru: '', password_konfirmasi: '' })
const show = reactive({ lama: false, baru: false, konfirmasi: false })
const errors  = reactive({})
const sukses  = ref(null)
const loading = ref(false)

// Evaluasi aturan keamanan password (Kapital, Simbol, Angka, Minimal 8 Karakter)
const passwordRules = computed(() => {
  const pwd = form.password_baru || ''
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

async function ubahKataSandi() {
  Object.keys(errors).forEach(k => { errors[k] = '' })
  sukses.value = null

  if (!form.password_lama) {
    errors.password_lama = 'Kata sandi saat ini wajib diisi.'
    return
  }

  // Validasi Kriteria Keamanan Password (Minimal 8 karakter, Kapital, Angka, dan Simbol)
  const rules = passwordRules.value
  if (!rules.hasMinLength || !rules.hasUpper || !rules.hasNumber || !rules.hasSymbol) {
    errors.password_baru = 'Kata sandi baru harus minimal 8 karakter dan mengandung huruf kapital (A-Z), angka (0-9), serta simbol unik (@$!%*?&#).'
    return
  }

  if (form.password_baru !== form.password_konfirmasi) {
    errors.password_konfirmasi = 'Konfirmasi kata sandi baru tidak cocok.'
    return
  }

  loading.value = true
  try {
    const res = await axios.patch('/akun/kata-sandi', {
      password_lama:        form.password_lama,
      password:             form.password_baru,
      password_confirmation:form.password_konfirmasi,
    })
    sukses.value = res.data?.message || 'Kata sandi berhasil diperbarui.'
    form.password_lama = ''
    form.password_baru = ''
    form.password_konfirmasi = ''
  } catch (e) {
    if (e.response?.data?.errors) {
      const errs = e.response.data.errors
      if (errs.password) errors.password_baru = Array.isArray(errs.password) ? errs.password[0] : errs.password
      if (errs.password_lama) errors.password_lama = Array.isArray(errs.password_lama) ? errs.password_lama[0] : errs.password_lama
      if (errs.password_confirmation) errors.password_konfirmasi = Array.isArray(errs.password_confirmation) ? errs.password_confirmation[0] : errs.password_confirmation
    } else {
      errors.password_lama = e.response?.data?.message ?? 'Gagal mengubah kata sandi. Periksa kembali data Anda.'
    }
  } finally {
    loading.value = false
  }
}
</script>
