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
            <h2 class="text-sm font-bold text-slate-800 mb-5">Keamanan akun</h2>
            <p class="text-xs text-slate-500 mb-5">Masukkan kata sandi saat ini sebelum membuat kata sandi baru.</p>

            <div v-if="sukses" class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs flex items-center gap-2">
              <Icon icon="lucide:check-circle" class="w-4 h-4" />
              <span>{{ sukses }}</span>
            </div>

            <form @submit.prevent="ubahKataSandi" class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Kata sandi saat ini <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input v-model="form.password_lama" :type="show.lama ? 'text' : 'password'"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                  <button type="button" @click="show.lama = !show.lama"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-[#0757A8] font-semibold">
                    {{ show.lama ? 'Sembunyikan' : 'Tampilkan' }}
                  </button>
                </div>
                <p v-if="errors.password_lama" class="mt-1 text-xs text-red-500">{{ errors.password_lama }}</p>
                <button type="button" class="mt-1 text-xs text-[#0757A8] font-semibold hover:underline">
                  Lupa kata sandi saat ini?
                </button>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Kata sandi baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input v-model="form.password_baru" :type="show.baru ? 'text' : 'password'"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                  <button type="button" @click="show.baru = !show.baru"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-[#0757A8] font-semibold">
                    {{ show.baru ? 'Sembunyikan' : 'Tampilkan' }}
                  </button>
                </div>
                <p v-if="errors.password_baru" class="mt-1 text-xs text-red-500">{{ errors.password_baru }}</p>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Konfirmasi kata sandi baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input v-model="form.password_konfirmasi" :type="show.konfirmasi ? 'text' : 'password'"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                  <button type="button" @click="show.konfirmasi = !show.konfirmasi"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-[#0757A8] font-semibold">
                    {{ show.konfirmasi ? 'Sembunyikan' : 'Tampilkan' }}
                  </button>
                </div>
                <p v-if="errors.password_konfirmasi" class="mt-1 text-xs text-red-500">{{ errors.password_konfirmasi }}</p>
              </div>

              <p class="text-xs text-slate-400">Minimal 8 karakter. Gunakan kata sandi yang berbeda dari sebelumnya.</p>

              <div class="flex items-center gap-3 pt-1">
                <button type="submit" :disabled="loading"
                  class="px-5 py-2 rounded-lg bg-[#0757A8] text-white text-xs font-bold hover:bg-[#063D78] transition disabled:opacity-50 flex items-center gap-2">
                  <Icon v-if="loading" icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
                  <Icon v-else icon="lucide:key-round" class="w-3.5 h-3.5" />
                  {{ loading ? 'Menyimpan...' : 'Simpan kata sandi' }}
                </button>
                <Link href="/akun/profil"
                  class="px-5 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                  Batalkan
                </Link>
              </div>
            </form>

            <p class="text-[11px] text-slate-400 mt-5">
              Jika Anda tidak merasa meminta perubahan kata sandi, hubungi administrator melalui Pusat Bantuan.
            </p>
          </div>
        </div>

        <!-- Info keamanan -->
        <div>
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Lindungi akses akun Anda</h2>
            <div class="p-3 rounded-xl bg-green-50 border border-green-200 mb-4">
              <div class="text-xs font-bold text-green-700">Akun terverifikasi</div>
              <div class="text-[11px] text-green-600 mt-0.5">Peran aktif: {{ labelPeran }}</div>
            </div>
            <ul class="space-y-2 text-xs text-slate-600">
              <li class="flex items-start gap-2"><span class="text-slate-400 mt-0.5">1.</span> Jangan bagikan kata sandi kepada siapa pun.</li>
              <li class="flex items-start gap-2"><span class="text-slate-400 mt-0.5">2.</span> Hindari penggunaan ulang kata sandi.</li>
              <li class="flex items-start gap-2"><span class="text-slate-400 mt-0.5">3.</span> Keluar dari perangkat yang digunakan bersama.</li>
            </ul>
            <p class="text-[11px] text-slate-400 mt-4">Perubahan kata sandi tidak mengubah data profil, peran, atau riwayat permohonan.</p>
          </div>
        </div>

      </div>

      <div class="text-[11px] text-slate-400 text-center pb-2">
        SI-RFC v2.0 &middot; FR-015 &middot; Pengaturan Akun Kedinasan
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

async function ubahKataSandi() {
  Object.keys(errors).forEach(k => { errors[k] = '' })
  if (form.password_baru !== form.password_konfirmasi) {
    errors.password_konfirmasi = 'Konfirmasi kata sandi tidak cocok.'
    return
  }
  if (form.password_baru.length < 8) {
    errors.password_baru = 'Kata sandi minimal 8 karakter.'
    return
  }
  loading.value = true
  try {
    await axios.patch('/akun/kata-sandi', {
      password_lama:        form.password_lama,
      password:             form.password_baru,
      password_confirmation:form.password_konfirmasi,
    })
    sukses.value = 'Kata sandi berhasil diperbarui.'
    form.password_lama = ''
    form.password_baru = ''
    form.password_konfirmasi = ''
  } catch (e) {
    if (e.response?.data?.errors) Object.assign(errors, e.response.data.errors)
    else errors.password_lama = e.response?.data?.message ?? 'Gagal mengubah kata sandi.'
  } finally {
    loading.value = false
  }
}
</script>
