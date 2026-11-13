<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Administrator</div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Kelola Akun Pengguna</h1>
          <p class="text-xs text-[#64748B] mt-0.5">Aktivasi, nonaktifkan, atau tolak akun pemohon.</p>
        </div>
        <!-- Badge tertunda -->
        <div v-if="akunTertunda > 0" class="flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 rounded-xl">
          <Icon icon="lucide:alert-triangle" class="w-4 h-4 text-red-500" />
          <span class="text-xs font-bold text-red-700">{{ akunTertunda }} akun menunggu aktivasi</span>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap gap-3 items-end shadow-xs">
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Peran</label>
          <select v-model="filter.peran" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua Peran</option>
            <option value="pemohon">Pemohon</option>
            <option value="verifikator">Verifikator</option>
            <option value="pimpinan">Pimpinan</option>
            <option value="teknisi">Teknisi</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Status</label>
          <select v-model="filter.status" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua Status</option>
            <option value="tertunda">Tertunda</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>
        <div class="flex-1 min-w-[200px]">
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Cari</label>
          <div class="relative">
            <Icon icon="lucide:search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" />
            <input
              v-model="filter.search"
              type="text"
              placeholder="Nama, email, atau NIP..."
              class="w-full pl-8 pr-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20"
            />
          </div>
        </div>
        <button @click="terapkanFilter" class="px-4 py-2 bg-[#0757A8] text-white text-xs font-bold rounded-lg hover:bg-[#063D78] transition-colors">
          Terapkan
        </button>
        <button @click="resetFilter" class="px-4 py-2 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
          Reset
        </button>
      </div>

      <!-- Tabel -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Pengguna</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">NIP</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">OPD</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Peran</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Status</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Terdaftar</th>
                <th class="text-right px-5 py-3 text-slate-500 font-semibold">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="!pengguna?.data?.length">
                <td colspan="7" class="text-center py-12 text-slate-400">
                  <Icon icon="lucide:users" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                  <p>Tidak ada pengguna ditemukan</p>
                </td>
              </tr>
              <tr v-for="user in pengguna?.data" :key="user.id" class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3">
                  <div class="font-semibold text-slate-800">{{ user.nama_lengkap }}</div>
                  <div class="text-slate-400">{{ user.email }}</div>
                </td>
                <td class="px-5 py-3 text-slate-500 font-mono">{{ user.nip }}</td>
                <td class="px-5 py-3 text-slate-500 max-w-[160px]">
                  <span class="line-clamp-2">{{ user.opd }}</span>
                </td>
                <td class="px-5 py-3">
                  <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-semibold capitalize">{{ user.peran }}</span>
                </td>
                <td class="px-5 py-3">
                  <span :class="[
                    'px-2.5 py-1 rounded-lg font-semibold capitalize',
                    user.status_aktivasi === 'aktif'    ? 'bg-green-50 text-green-700' :
                    user.status_aktivasi === 'tertunda' ? 'bg-amber-50 text-amber-700' :
                                                          'bg-red-50 text-red-700'
                  ]">{{ user.status_aktivasi }}</span>
                </td>
                <td class="px-5 py-3 text-slate-400">{{ user.created_at }}</td>
                <td class="px-5 py-3">
                  <div class="flex items-center justify-end gap-2">
                    <!-- Aktifkan -->
                    <button
                      v-if="user.status_aktivasi !== 'aktif'"
                      @click="bukaKonfirmasi('aktivasi', user)"
                      class="px-3 py-1.5 bg-green-600 text-white text-[10px] font-bold rounded-lg hover:bg-green-700 transition-colors flex items-center gap-1"
                    >
                      <Icon icon="lucide:check" class="w-3 h-3" />
                      Aktifkan
                    </button>
                    <!-- Nonaktifkan -->
                    <button
                      v-if="user.status_aktivasi === 'aktif'"
                      @click="bukaKonfirmasi('nonaktif', user)"
                      class="px-3 py-1.5 bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg hover:bg-slate-300 transition-colors flex items-center gap-1"
                    >
                      <Icon icon="lucide:ban" class="w-3 h-3" />
                      Nonaktifkan
                    </button>
                    <!-- Tolak -->
                    <button
                      v-if="user.status_aktivasi === 'tertunda'"
                      @click="bukaKonfirmasi('tolak', user)"
                      class="px-3 py-1.5 bg-red-100 text-red-700 text-[10px] font-bold rounded-lg hover:bg-red-200 transition-colors flex items-center gap-1"
                    >
                      <Icon icon="lucide:x" class="w-3 h-3" />
                      Tolak
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pengguna?.last_page > 1" class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-xs">
          <span class="text-slate-500">
            Menampilkan {{ pengguna.from }}–{{ pengguna.to }} dari {{ pengguna.total }} pengguna
          </span>
          <div class="flex gap-1">
            <a
              v-for="link in pengguna.links"
              :key="link.label"
              :href="link.url"
              v-html="link.label"
              :class="[
                'px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors',
                link.active ? 'bg-[#0757A8] text-white border-[#0757A8]' : 'border-slate-200 text-slate-600 hover:bg-slate-50',
                !link.url ? 'opacity-40 pointer-events-none' : ''
              ]"
            ></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal konfirmasi -->
    <KonfirmasiModal
      :show="modal.show"
      :judul="modal.judul"
      :pesan="modal.pesan"
      :label-konfirmasi="modal.labelKonfirmasi"
      :tipe-konfirmasi="modal.tipe"
      @konfirmasi="prosesAksi"
      @batal="modal.show = false"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router }        from '@inertiajs/vue3'
import AppLayout         from '@/Layouts/AppLayout.vue'
import KonfirmasiModal   from '@/Components/KonfirmasiModal.vue'
import axios             from 'axios'

const props = defineProps({
  pengguna:    { type: Object, default: () => ({ data: [], last_page: 1 }) },
  filterAktif: { type: Object, default: () => ({}) },
})

const filter = ref({
  peran:  props.filterAktif.peran   ?? '',
  status: props.filterAktif.status  ?? '',
  search: props.filterAktif.search  ?? '',
})

const akunTertunda = computed(() =>
  props.pengguna?.data?.filter(u => u.status_aktivasi === 'tertunda').length ?? 0
)

// ── Filter ───────────────────────────────────────────────────────────────────
function terapkanFilter() {
  router.get('/admin/akun', filter.value, { preserveState: true, replace: true })
}
function resetFilter() {
  filter.value = { peran: '', status: '', search: '' }
  router.get('/admin/akun', {}, { preserveState: true, replace: true })
}

// ── Modal ────────────────────────────────────────────────────────────────────
const modal = ref({ show: false, tipe: 'danger', judul: '', pesan: '', labelKonfirmasi: '', aksi: '', user: null })

function bukaKonfirmasi(aksi, user) {
  const cfg = {
    aktivasi: { tipe: 'success', judul: 'Aktifkan Akun',   pesan: `Aktifkan akun ${user.nama_lengkap}? Email konfirmasi akan dikirim.`, label: 'Ya, Aktifkan' },
    nonaktif: { tipe: 'warning', judul: 'Nonaktifkan Akun',pesan: `Nonaktifkan akun ${user.nama_lengkap}? User tidak dapat login.`,      label: 'Ya, Nonaktifkan' },
    tolak:    { tipe: 'danger',  judul: 'Tolak Pendaftaran',pesan: `Tolak pendaftaran ${user.nama_lengkap}? Tindakan ini akan mengirim email pemberitahuan.`, label: 'Ya, Tolak' },
  }[aksi]
  modal.value = { show: true, aksi, user, tipe: cfg.tipe, judul: cfg.judul, pesan: cfg.pesan, labelKonfirmasi: cfg.label }
}

const loading = ref(false)
async function prosesAksi() {
  if (loading.value) return
  loading.value = true
  modal.value.show = false

  const { aksi, user } = modal.value
  const urlMap = {
    aktivasi: `/admin/akun/${user.id}/aktivasi`,
    nonaktif: `/admin/akun/${user.id}/nonaktif`,
    tolak:    `/admin/akun/${user.id}/tolak`,
  }

  try {
    await axios.patch(urlMap[aksi])
    router.reload({ preserveScroll: true })
  } catch (e) {
    alert('Terjadi kesalahan. Silakan coba lagi.')
  } finally {
    loading.value = false
  }
}
</script>
