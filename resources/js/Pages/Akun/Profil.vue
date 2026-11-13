<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto space-y-6">

      <!-- Breadcrumb -->
      <div>
        <nav class="text-xs text-slate-500 mb-1 flex items-center gap-1">
          <span>Akun</span>
          <span>/</span>
          <span class="text-slate-700 font-medium">Profil kedinasan</span>
        </nav>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan akun</h1>
        <p class="text-sm text-slate-500 mt-0.5">Kelola identitas pemohon, keamanan, dan preferensi pemberitahuan.</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom kiri: Form profil -->
        <div class="lg:col-span-2 space-y-5">

          <!-- Informasi profil -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h2 class="text-sm font-bold text-slate-800 mb-5">Informasi profil</h2>

            <div v-if="sukses" class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs flex items-center gap-2">
              <Icon icon="lucide:check-circle" class="w-4 h-4" />
              <span>{{ sukses }}</span>
            </div>

            <div v-if="errors._general" class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2">
              <Icon icon="lucide:alert-circle" class="w-4 h-4" />
              <span>{{ errors._general }}</span>
            </div>

            <form @submit.prevent="simpanProfil" class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">Nama lengkap</label>
                  <input v-model="form.nama_lengkap" type="text"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                  <p v-if="errors.nama_lengkap" class="mt-1 text-xs text-red-500">{{ errors.nama_lengkap }}</p>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">NIP / NIK</label>
                  <input v-model="form.nip" type="text" placeholder="Tercatat pada profil kedinasan"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">Unit kerja</label>
                  <input v-model="form.unit_kerja" type="text" placeholder="Bidang Layanan TI"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">Perangkat daerah</label>
                  <div class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-600">
                    <Icon icon="lucide:building-2" class="w-4 h-4 text-slate-400 flex-shrink-0" />
                    <span class="truncate">{{ authUser?.opd || 'Belum terdaftar' }}</span>
                    <span class="ml-auto text-[10px] text-slate-400 bg-slate-200 px-1.5 py-0.5 rounded flex-shrink-0">Auto</span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">Email dinas</label>
                  <input v-model="form.email" type="email"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                  <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email }}</p>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / kontak PIC</label>
                  <input v-model="form.nomor_kontak" type="text" placeholder="Nomor kontak kedinasan terdaftar"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] transition" />
                </div>
              </div>

              <p class="text-xs text-slate-400 italic">Perubahan identitas kedinasan dapat memerlukan pemeriksaan ulang administrator.</p>

              <div class="flex items-center gap-3 pt-1">
                <button type="submit" :disabled="loading"
                  class="px-5 py-2 rounded-lg bg-[#0757A8] text-white text-xs font-bold hover:bg-[#063D78] transition disabled:opacity-50 flex items-center gap-2">
                  <Icon v-if="loading" icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
                  <Icon v-else icon="lucide:save" class="w-3.5 h-3.5" />
                  {{ loading ? 'Menyimpan...' : 'Simpan perubahan' }}
                </button>
                <button type="button" @click="resetForm"
                  class="px-5 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                  Batalkan
                </button>
              </div>
            </form>
          </div>

          <!-- Preferensi pemberitahuan -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Preferensi pemberitahuan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" v-model="notifRevisi" class="w-4 h-4 rounded text-[#0757A8] focus:ring-[#0757A8]" />
                <span class="text-xs text-slate-700">Email dinas untuk permintaan revisi</span>
              </label>
              <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" v-model="notifStatus" class="w-4 h-4 rounded text-[#0757A8] focus:ring-[#0757A8]" />
                <span class="text-xs text-slate-700">Pemberitahuan perubahan status tiket</span>
              </label>
            </div>
            <p class="text-[11px] text-slate-400 mt-3">Pemberitahuan penting terkait revisi dan pengesahan tetap ditampilkan di pusat notifikasi.</p>
          </div>
        </div>

        <!-- Kolom kanan: Status & Keamanan -->
        <div class="space-y-5">

          <!-- Status akun -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Keamanan &amp; akses</h2>
            <div class="p-3 rounded-xl bg-green-50 border border-green-200 mb-4">
              <div class="text-xs font-bold text-green-700">Akun terverifikasi</div>
              <div class="text-[11px] text-green-600 mt-0.5">Peran aktif: {{ labelPeran }}</div>
            </div>

            <div class="border-t border-slate-100 pt-4">
              <div class="text-xs font-semibold text-slate-700 mb-1">Kata sandi</div>
              <p class="text-xs text-slate-500 mb-3">Gunakan kata sandi unik dan jangan bagikan akses akun kedinasan.</p>
              <Link href="/akun/keamanan"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                <Icon icon="lucide:key-round" class="w-3.5 h-3.5" />
                Ubah kata sandi
              </Link>
            </div>

            <div class="border-t border-slate-100 pt-4 mt-4">
              <div class="text-xs font-semibold text-slate-700 mb-2">Akses berbasis peran</div>
              <p class="text-xs text-slate-500">Perubahan peran dilakukan oleh administrator. Pengajuan FR-015 tetap mengikuti kewenangan masing-masing pihak.</p>
            </div>
          </div>

        </div>
      </div>

      <!-- Footer halaman -->
      <div class="text-[11px] text-slate-400 text-center pb-2">
        SI-RFC v2.0 &middot; FR-015 &middot; Pengaturan Akun Kedinasan
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
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

const form = reactive({
  nama_lengkap: '',
  nip:          '',
  unit_kerja:   '',
  email:        '',
  nomor_kontak: '',
})

const errors       = reactive({})
const sukses       = ref(null)
const loading      = ref(false)
const notifRevisi  = ref(true)
const notifStatus  = ref(true)

function isiDariAuth() {
  const u = authUser.value
  if (!u) return
  form.nama_lengkap = u.nama_lengkap ?? ''
  form.email        = u.email ?? ''
  form.nip          = u.nip ?? ''
  form.nomor_kontak = u.nomor_kontak ?? ''
}

function resetForm() { isiDariAuth() }

onMounted(isiDariAuth)

async function simpanProfil() {
  loading.value = true
  sukses.value  = null
  Object.keys(errors).forEach(k => { errors[k] = '' })
  try {
    await axios.patch('/akun/profil', form)
    sukses.value = 'Profil berhasil diperbarui.'
  } catch (e) {
    if (e.response?.data?.errors) Object.assign(errors, e.response.data.errors)
    else errors._general = e.response?.data?.message ?? 'Gagal menyimpan.'
  } finally {
    loading.value = false
  }
}
</script>
