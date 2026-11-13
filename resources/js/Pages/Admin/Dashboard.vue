<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- Header -->
      <div>
        <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Administrator Sistem</div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Dasbor Admin</h1>
        <p class="text-xs text-[#64748B] mt-0.5">Kelola akun, master data, dan pantau seluruh aktivitas RFC.</p>
      </div>

      <!-- 4 Stat Card -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500">Total RFC</span>
            <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
              <Icon icon="lucide:file-text" class="w-4 h-4 text-blue-600" />
            </div>
          </div>
          <div class="text-3xl font-black text-slate-800">{{ totalRfc }}</div>
          <div class="text-[11px] text-slate-500 mt-1">{{ rfcBulanIni }} bulan ini</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500">Menunggu Verifikasi</span>
            <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
              <Icon icon="lucide:clock" class="w-4 h-4 text-amber-600" />
            </div>
          </div>
          <div class="text-3xl font-black text-slate-800">{{ statistikRfc['diajukan'] ?? 0 }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Perlu telaah verifikator</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500">RFC Selesai</span>
            <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center">
              <Icon icon="lucide:check-circle" class="w-4 h-4 text-green-600" />
            </div>
          </div>
          <div class="text-3xl font-black text-slate-800">{{ statistikRfc['selesai'] ?? 0 }}</div>
          <div class="text-[11px] text-slate-500 mt-1">BAST diterbitkan</div>
        </div>

        <div
          :class="['bg-white rounded-2xl p-5 border shadow-xs', akunTertunda > 0 ? 'border-red-300 bg-red-50/40' : 'border-slate-200']"
        >
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold" :class="akunTertunda > 0 ? 'text-red-600' : 'text-slate-500'">Akun Tertunda</span>
            <div :class="['w-8 h-8 rounded-xl flex items-center justify-center', akunTertunda > 0 ? 'bg-red-100' : 'bg-slate-100']">
              <Icon icon="lucide:user-x" :class="['w-4 h-4', akunTertunda > 0 ? 'text-red-600' : 'text-slate-500']" />
            </div>
          </div>
          <div :class="['text-3xl font-black', akunTertunda > 0 ? 'text-red-700' : 'text-slate-800']">{{ akunTertunda }}</div>
          <div class="text-[11px] mt-1">
            <Link href="/admin/akun" :class="akunTertunda > 0 ? 'text-red-600 font-semibold hover:underline' : 'text-slate-500'">
              {{ akunTertunda > 0 ? 'Aktivasi sekarang' : 'Semua aktif' }}
            </Link>
          </div>
        </div>
      </div>

      <!-- Distribusi Status RFC (bar chart CSS) -->
      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <h2 class="text-sm font-bold text-slate-800 mb-5 flex items-center gap-2">
          <Icon icon="lucide:bar-chart-2" class="w-4 h-4 text-[#0757A8]" />
          Distribusi Status RFC
        </h2>
        <div class="space-y-2.5">
          <div v-for="item in distribusiStatus" :key="item.status" class="flex items-center gap-3">
            <div class="w-36 text-xs text-slate-600 font-medium flex-shrink-0">{{ item.label }}</div>
            <div class="flex-1 h-6 bg-slate-100 rounded-lg overflow-hidden">
              <div
                :style="{ width: item.persen + '%' }"
                :class="['h-full rounded-lg transition-all duration-500', item.warna]"
              ></div>
            </div>
            <div class="w-8 text-xs font-bold text-slate-700 text-right">{{ item.jumlah }}</div>
          </div>
        </div>
      </div>

      <!-- RFC Terbaru -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
            <Icon icon="lucide:activity" class="w-4 h-4 text-[#0757A8]" />
            RFC Terbaru
          </h2>
          <Link href="/admin/laporan" class="text-xs font-semibold text-[#0757A8] hover:underline flex items-center gap-1">
            <span>Lihat semua</span>
            <Icon icon="lucide:chevron-right" class="w-3.5 h-3.5" />
          </Link>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Kode RFC</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Pemohon</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">OPD</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Kategori</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Status</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Tanggal</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="rfcTerbaru.length === 0">
                <td colspan="6" class="text-center py-8 text-slate-400">Belum ada data RFC</td>
              </tr>
              <tr v-for="rfc in rfcTerbaru" :key="rfc.id" class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3">
                  <Link :href="`/rfc/${rfc.kode_pelacakan}`" class="font-mono font-bold text-[#0757A8] hover:underline">
                    {{ rfc.kode_pelacakan }}
                  </Link>
                </td>
                <td class="px-5 py-3 text-slate-700">{{ rfc.nama_pemohon }}</td>
                <td class="px-5 py-3 text-slate-500 max-w-[180px] truncate">{{ rfc.opd }}</td>
                <td class="px-5 py-3 text-slate-500">{{ rfc.kategori }}</td>
                <td class="px-5 py-3">
                  <StatusBadge :status="rfc.status_terkini" :label="rfc.status_label" />
                </td>
                <td class="px-5 py-3 text-slate-400">{{ rfc.created_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link }     from '@inertiajs/vue3'
import AppLayout    from '@/Layouts/AppLayout.vue'
import StatusBadge  from '@/Components/StatusBadge.vue'

const props = defineProps({
  statistikRfc:      { type: Object, default: () => ({}) },
  statistikPengguna: { type: Object, default: () => ({}) },
  rfcTerbaru:        { type: Array,  default: () => [] },
  totalRfc:          { type: Number, default: 0 },
  rfcBulanIni:       { type: Number, default: 0 },
  akunTertunda:      { type: Number, default: 0 },
  totalPengguna:     { type: Number, default: 0 },
})

const statusConfig = [
  { status: 'diajukan',             label: 'Diajukan',              warna: 'bg-blue-500' },
  { status: 'menunggu_persetujuan', label: 'Menunggu Persetujuan',  warna: 'bg-purple-500' },
  { status: 'disetujui',            label: 'Disetujui',             warna: 'bg-green-500' },
  { status: 'sedang_dikerjakan',    label: 'Sedang Dikerjakan',     warna: 'bg-cyan-500' },
  { status: 'selesai',              label: 'Selesai',               warna: 'bg-emerald-500' },
  { status: 'perlu_revisi',         label: 'Perlu Revisi',          warna: 'bg-orange-500' },
  { status: 'ditolak_verifikator',  label: 'Ditolak Verifikator',   warna: 'bg-red-400' },
  { status: 'ditolak_pimpinan',     label: 'Ditolak Pimpinan',      warna: 'bg-red-600' },
  { status: 'ditunda',              label: 'Ditunda',               warna: 'bg-yellow-500' },
  { status: 'draf',                 label: 'Draf',                  warna: 'bg-slate-400' },
]

const distribusiStatus = computed(() => {
  const max = Math.max(...Object.values(props.statistikRfc), 1)
  return statusConfig
    .filter(s => (props.statistikRfc[s.status] ?? 0) > 0)
    .map(s => ({
      ...s,
      jumlah: props.statistikRfc[s.status] ?? 0,
      persen: Math.round(((props.statistikRfc[s.status] ?? 0) / max) * 100),
    }))
    .sort((a, b) => b.jumlah - a.jumlah)
})
</script>
