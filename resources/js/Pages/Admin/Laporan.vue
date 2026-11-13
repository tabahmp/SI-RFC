<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Laporan</div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Laporan & Statistik RFC</h1>
          <p class="text-xs text-[#64748B] mt-0.5">Rekapitulasi seluruh aktivitas Request for Change.</p>
        </div>
        <a :href="urlEkspor" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#16834A] text-white text-xs font-bold rounded-xl hover:bg-[#105f36] transition-colors shadow-sm">
          <Icon icon="lucide:download" class="w-4 h-4" />
          Ekspor CSV
        </a>
      </div>

      <!-- 4 Stat Card -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs text-center">
          <div class="text-3xl font-black text-slate-800">{{ statistik.total }}</div>
          <div class="text-xs text-slate-500 mt-1">Total RFC</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs text-center">
          <div class="text-3xl font-black text-emerald-700">{{ statistik.selesai }}</div>
          <div class="text-xs text-slate-500 mt-1">RFC Selesai</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs text-center">
          <div class="text-3xl font-black text-[#0757A8]">{{ statistik.bulan_ini }}</div>
          <div class="text-xs text-slate-500 mt-1">RFC Bulan Ini</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs text-center">
          <div class="text-3xl font-black text-amber-700">{{ statistik.persen_selesai }}%</div>
          <div class="text-xs text-slate-500 mt-1">Tingkat Penyelesaian</div>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap gap-3 items-end shadow-xs">
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
          <input v-model="filter.tanggal_dari" type="date" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20" />
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
          <input v-model="filter.tanggal_sampai" type="date" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20" />
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">OPD</label>
          <select v-model="filter.opd_id" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua OPD</option>
            <option v-for="o in opdList" :key="o.id" :value="o.id">{{ o.nama_opd }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Status</label>
          <select v-model="filter.status" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua Status</option>
            <option value="draf">Draf</option>
            <option value="diajukan">Diajukan</option>
            <option value="menunggu_persetujuan">Menunggu Persetujuan</option>
            <option value="disetujui">Disetujui</option>
            <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
            <option value="selesai">Selesai</option>
            <option value="perlu_revisi">Perlu Revisi</option>
            <option value="ditolak_verifikator">Ditolak Verifikator</option>
            <option value="ditolak_pimpinan">Ditolak Pimpinan</option>
            <option value="ditunda">Ditunda</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Urgensi</label>
          <select v-model="filter.urgensi" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua</option>
            <option value="rendah">Rendah</option>
            <option value="sedang">Sedang</option>
            <option value="tinggi">Tinggi</option>
            <option value="kritis">Kritis</option>
          </select>
        </div>
        <button @click="terapkanFilter" class="px-4 py-2 bg-[#0757A8] text-white text-xs font-bold rounded-lg hover:bg-[#063D78] transition-colors">
          Terapkan Filter
        </button>
        <button @click="resetFilter" class="px-4 py-2 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
          Reset
        </button>
      </div>

      <!-- Tabel Laporan -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">No</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Kode RFC</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Pemohon</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">OPD</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Kategori</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Urgensi</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Status</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Tgl Ajuan</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Tgl Selesai</th>
                <th class="text-left px-4 py-3 text-slate-500 font-semibold">Durasi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="!rfc?.data?.length">
                <td colspan="10" class="text-center py-12 text-slate-400">
                  <Icon icon="lucide:file-search" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                  <p>Tidak ada data sesuai filter</p>
                </td>
              </tr>
              <tr v-for="(item, idx) in rfc?.data" :key="item.id" class="hover:bg-slate-50 transition-colors">
                <td class="px-4 py-3 text-slate-400">{{ (rfc.from ?? 0) + idx }}</td>
                <td class="px-4 py-3">
                  <Link :href="`/rfc/${item.kode_pelacakan}`" class="font-mono font-bold text-[#0757A8] hover:underline">{{ item.kode_pelacakan }}</Link>
                </td>
                <td class="px-4 py-3 text-slate-700">{{ item.nama_pemohon }}</td>
                <td class="px-4 py-3 text-slate-500 max-w-[140px]"><span class="line-clamp-2">{{ item.opd }}</span></td>
                <td class="px-4 py-3 text-slate-500">{{ item.kategori }}</td>
                <td class="px-4 py-3">
                  <span :class="['px-2 py-0.5 rounded-md text-[10px] font-bold', urgensiKelas(item.urgensi)]">{{ item.urgensi_label }}</span>
                </td>
                <td class="px-4 py-3">
                  <StatusBadge :status="item.status_terkini" :label="item.status_label" />
                </td>
                <td class="px-4 py-3 text-slate-500">{{ item.tanggal_ajuan }}</td>
                <td class="px-4 py-3 text-slate-500">{{ item.tanggal_selesai ?? '-' }}</td>
                <td class="px-4 py-3 text-slate-500">{{ item.durasi_hari != null ? item.durasi_hari + ' hari' : '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div v-if="rfc?.last_page > 1" class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-xs">
          <span class="text-slate-500">{{ rfc.from }}–{{ rfc.to }} dari {{ rfc.total }} RFC</span>
          <div class="flex gap-1">
            <a v-for="link in rfc.links" :key="link.label" :href="link.url" v-html="link.label"
               :class="['px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors', link.active ? 'bg-[#0757A8] text-white border-[#0757A8]' : 'border-slate-200 text-slate-600 hover:bg-slate-50', !link.url ? 'opacity-40 pointer-events-none' : '']">
            </a>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router }  from '@inertiajs/vue3'
import AppLayout         from '@/Layouts/AppLayout.vue'
import StatusBadge       from '@/Components/StatusBadge.vue'

const props = defineProps({
  rfc:          { type: Object, default: () => ({ data: [] }) },
  statistik:    { type: Object, default: () => ({ total: 0, selesai: 0, bulan_ini: 0, persen_selesai: 0 }) },
  filterAktif:  { type: Object, default: () => ({}) },
  opdList:      { type: Array,  default: () => [] },
  kategoriList: { type: Array,  default: () => [] },
})

const filter = ref({
  tanggal_dari:   props.filterAktif.tanggal_dari   ?? '',
  tanggal_sampai: props.filterAktif.tanggal_sampai ?? '',
  opd_id:         props.filterAktif.opd_id         ?? '',
  status:         props.filterAktif.status         ?? '',
  urgensi:        props.filterAktif.urgensi        ?? '',
})

const urlEkspor = computed(() => {
  const params = new URLSearchParams()
  Object.entries(filter.value).forEach(([k, v]) => v && params.append(k, v))
  return '/admin/laporan/ekspor?' + params.toString()
})

function terapkanFilter() {
  router.get('/admin/laporan', filter.value, { preserveState: true, replace: true })
}
function resetFilter() {
  filter.value = { tanggal_dari: '', tanggal_sampai: '', opd_id: '', status: '', urgensi: '' }
  router.get('/admin/laporan', {}, { preserveState: true, replace: true })
}

function urgensiKelas(urgensi) {
  return { rendah: 'bg-green-50 text-green-700', sedang: 'bg-yellow-50 text-yellow-700', tinggi: 'bg-orange-50 text-orange-700', kritis: 'bg-red-50 text-red-700' }[urgensi] ?? 'bg-slate-100 text-slate-600'
}
</script>
