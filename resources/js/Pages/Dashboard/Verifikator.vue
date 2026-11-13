<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- SUB-NAVIGASI -->
      <div class="flex flex-wrap items-center justify-between border-b border-slate-200 pb-3 gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <button @click="activeTab = 'ringkasan'" :class="tabClass('ringkasan')">
            <Icon icon="lucide:layout-grid" class="w-4 h-4" />
            <span>Ringkasan & Prioritas</span>
          </button>
          <button @click="activeTab = 'antrean'" :class="tabClass('antrean')">
            <Icon icon="lucide:shield-check" class="w-4 h-4" />
            <span>Antrean Telaah</span>
            <span :class="activeTab === 'antrean' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'"
              class="px-2 py-0.5 rounded-md text-[10px] font-extrabold">{{ countAntrean }}</span>
          </button>
          <button @click="activeTab = 'sla'" :class="tabClass('sla')">
            <Icon icon="lucide:clock" class="w-4 h-4" />
            <span>Pemantauan SLA</span>
          </button>
        </div>
        <span class="px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200 flex items-center gap-1.5">
          <Icon icon="lucide:shield-check" class="w-4 h-4" />
          <span>Verifikator APTIKA</span>
        </span>
      </div>

      <!-- VIEW: RINGKASAN -->
      <div v-if="activeTab === 'ringkasan'" class="space-y-6">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Ruang kerja teknis</div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Antrean Verifikasi</h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">Tiket otomatis diurutkan berdasarkan kriteria risiko dan waktu pengajuan.</p>
        </div>

        <!-- Kartu Prioritas (dari data nyata) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div v-for="p in prioritasCards" :key="p.label"
            class="bg-white rounded-2xl p-5 border-l-4 border border-slate-200/90 shadow-xs"
            :class="p.borderClass">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold" :class="p.textClass">{{ p.label }}</span>
              <span class="text-xs font-bold px-2 py-0.5 rounded" :class="p.badgeClass">{{ p.sla }}</span>
            </div>
            <div class="text-3xl font-black mt-2" :class="p.textClass">{{ p.count }}</div>
            <div class="text-[11px] text-slate-500 mt-1">{{ p.desc }}</div>
          </div>
        </div>

        <!-- Smart Priority Queue -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-[#0F172A]">Smart priority queue</h2>
                <p class="text-xs text-slate-500">{{ countAntrean }} tiket perlu ditelaah</p>
              </div>
              <button @click="activeTab = 'antrean'"
                class="text-xs font-bold text-[#0757A8] px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 flex items-center gap-1.5 transition-colors">
                <Icon icon="lucide:list-filter" class="w-3.5 h-3.5" />
                <span>Lihat Seluruh Antrean</span>
              </button>
            </div>

            <div v-if="antrean.length === 0" class="py-8 text-center text-slate-400">
              <Icon icon="lucide:check-circle-2" class="w-8 h-8 mx-auto mb-2 text-emerald-400" />
              <p class="text-sm font-semibold">Tidak ada tiket menunggu verifikasi</p>
            </div>

            <div v-else class="space-y-3">
              <div v-for="rfc in antrean.slice(0, 5)" :key="rfc.id"
                class="p-4 rounded-xl border flex items-center justify-between gap-4"
                :class="getBorderByUrgensi(rfc.urgensi)">
                <div class="flex items-center gap-3.5 min-w-0">
                  <span class="px-2 py-0.5 rounded text-xs font-extrabold text-white" :class="getBadgeByUrgensi(rfc.urgensi)">
                    {{ rfc.urgensi_label }}
                  </span>
                  <div class="min-w-0">
                    <h3 class="text-sm font-bold text-[#0F172A] truncate">{{ rfc.deskripsi || 'Permohonan RFC' }}</h3>
                    <div class="text-xs text-slate-500 mt-0.5">{{ rfc.kode_pelacakan }} · {{ rfc.opd }}</div>
                  </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                  <SlaTimer v-if="rfc.target_sla" :deadline="rfc.target_sla" class="text-right" />
                  <Link :href="`/rfc/${rfc.kode_pelacakan}/workspace`"
                    class="px-3.5 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs whitespace-nowrap">
                    Telaah
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <!-- SLA Alert Widget -->
          <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-4">
            <div>
              <div class="flex items-center gap-2 text-red-600 font-extrabold text-xs tracking-wider uppercase mb-1">
                <Icon icon="lucide:alert-triangle" class="w-4 h-4" />
                <span>SLA ALERT</span>
              </div>
              <h3 class="text-base font-extrabold text-[#0F172A]">{{ slaKritis.length }} tiket mendekati batas</h3>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">Prioritaskan tiket mendesak agar kepatuhan target tanggap terjaga.</p>

              <div v-if="slaKritis.length > 0" class="mt-4 p-4 rounded-xl bg-red-50 border border-red-200 space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="text-slate-500 font-medium">Terdekat</span>
                  <span class="font-mono font-bold text-red-700">{{ slaKritis[0].kode_pelacakan }}</span>
                </div>
                <SlaTimer :deadline="slaKritis[0].target_sla" class="text-xl font-mono font-black text-red-600" />
                <div class="text-[11px] text-red-700">{{ slaKritis[0].opd }} — {{ slaKritis[0].deskripsi }}</div>
              </div>
              <div v-else class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 font-semibold text-center">
                Semua tiket dalam batas SLA ✓
              </div>
            </div>

            <Link v-if="slaKritis.length > 0" :href="`/rfc/${slaKritis[0].kode_pelacakan}/workspace`"
              class="w-full h-11 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all">
              <span>Verifikasi Tiket Kritis Sekarang</span>
              <Icon icon="lucide:arrow-right" class="w-4 h-4" />
            </Link>
          </div>
        </div>
      </div>

      <!-- VIEW: ANTREAN TELAAH -->
      <div v-else-if="activeTab === 'antrean'" class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Verifikator / Antrean telaah</div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Antrean telaah</h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">{{ countAntrean }} tiket menunggu pemeriksaan kelengkapan dan kelayakan.</p>
          </div>
        </div>

        <!-- Filter bar -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input v-model="filterSearch" type="text" placeholder="Cari kode RFC atau pemohon..."
              class="col-span-2 h-9 px-3 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8]" />
            <select v-model="filterUrgensi" class="h-9 px-3 text-xs border border-slate-200 rounded-lg focus:outline-none bg-white">
              <option value="">Semua urgensi</option>
              <option value="kritis">Kritis</option>
              <option value="tinggi">Tinggi</option>
              <option value="sedang">Sedang</option>
              <option value="rendah">Rendah</option>
            </select>
            <button @click="filterSearch=''; filterUrgensi=''"
              class="h-9 flex items-center justify-center gap-1.5 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold hover:bg-slate-50">
              <Icon icon="lucide:x" class="w-3.5 h-3.5" />Reset
            </button>
          </div>
        </div>

        <!-- Tabel Antrean -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4">TIKET / PEMOHON</th>
                  <th class="py-3 px-4">KEBUTUHAN PERUBAHAN</th>
                  <th class="py-3 px-4">URGENSI</th>
                  <th class="py-3 px-4">SLA</th>
                  <th class="py-3 px-4 text-right">TINDAKAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="rfc in filteredAntrean" :key="rfc.id" class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-[#0757A8] font-mono">{{ rfc.kode_pelacakan }}</div>
                    <div class="text-[11px] text-slate-500">{{ rfc.opd }} · {{ rfc.created_at_human }}</div>
                  </td>
                  <td class="py-3.5 px-4 max-w-xs">
                    <div class="font-semibold text-slate-800 truncate">{{ rfc.nama_pemohon }}</div>
                    <div class="text-[11px] text-slate-500 truncate">{{ rfc.deskripsi || '-' }}</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold" :class="getBadgeByUrgensi(rfc.urgensi) + ' text-white'">
                      {{ rfc.urgensi_label }}
                    </span>
                  </td>
                  <td class="py-3.5 px-4">
                    <SlaTimer v-if="rfc.target_sla" :deadline="rfc.target_sla" />
                    <span v-else class="text-slate-400 text-xs">—</span>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <Link :href="`/rfc/${rfc.kode_pelacakan}/workspace`"
                      class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs">
                      <span>Mulai telaah</span>
                      <Icon icon="lucide:arrow-right" class="w-3.5 h-3.5" />
                    </Link>
                  </td>
                </tr>
                <tr v-if="filteredAntrean.length === 0">
                  <td colspan="5" class="py-10 text-center text-slate-400">
                    <Icon icon="lucide:search-x" class="w-6 h-6 mx-auto mb-2 text-slate-300" />
                    <p>{{ antrean.length === 0 ? 'Tidak ada tiket menunggu verifikasi.' : 'Tidak ada hasil sesuai filter.' }}</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- VIEW: PEMANTAUAN SLA -->
      <div v-else-if="activeTab === 'sla'" class="space-y-6">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Verifikator / Kinerja layanan</div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Pemantauan SLA</h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">Pantau batas waktu tanggap verifikasi berdasarkan urgensi.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Antrean verifikasi</div>
            <div class="text-3xl font-black text-[#0F172A] mt-1.5">{{ countAntrean }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Semua urgensi</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Mendekati batas</div>
            <div class="text-3xl font-black text-red-600 mt-1.5">{{ slaKritis.length }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Segera lakukan telaah</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Urgensi Kritis</div>
            <div class="text-3xl font-black text-red-600 mt-1.5">{{ antrean.filter(r => r.urgensi === 'kritis').length }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Target tanggap 2–4 jam</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-[#0757A8] uppercase tracking-wide">Statistik Bulan Ini</div>
            <div class="text-3xl font-black text-[#0757A8] mt-1.5">{{ statistikBulanIni?.total_selesai ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Tiket terverifikasi</div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4">NOMOR RFC</th>
                  <th class="py-3 px-4">PERUBAHAN / OPD</th>
                  <th class="py-3 px-4">URGENSI</th>
                  <th class="py-3 px-4">SISA TANGGAP</th>
                  <th class="py-3 px-4 text-right">TINDAKAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="rfc in slaKritis" :key="rfc.id" class="bg-red-50/50 hover:bg-red-50">
                  <td class="py-3.5 px-4 font-mono font-bold text-red-700">{{ rfc.kode_pelacakan }}</td>
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-slate-800">{{ rfc.deskripsi }}</div>
                    <div class="text-[11px] text-slate-500">{{ rfc.opd }}</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold text-white" :class="getBadgeByUrgensi(rfc.urgensi)">
                      {{ rfc.urgensi_label }}
                    </span>
                  </td>
                  <td class="py-3.5 px-4">
                    <SlaTimer :deadline="rfc.target_sla" class="font-mono font-bold text-red-600" />
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <Link :href="`/rfc/${rfc.kode_pelacakan}/workspace`"
                      class="px-3 py-1.5 rounded-lg bg-[#0757A8] text-white font-bold text-xs hover:bg-[#063D78]">Verifikasi</Link>
                  </td>
                </tr>
                <tr v-if="slaKritis.length === 0">
                  <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                    <Icon icon="lucide:check-circle-2" class="w-6 h-6 mx-auto mb-1 text-emerald-400" />
                    Tidak ada tiket mendekati batas SLA.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SlaTimer from '@/Components/SlaTimer.vue'
import { Icon } from '@iconify/vue'

// ── Props (match controller: dashboardVerifikator) ────────────────────────────
const props = defineProps({
  antrean:          { type: Array,  default: () => [] },   // RFC status 'diajukan'
  filters:          { type: Object, default: () => ({}) },
  kategoriList:     { type: Array,  default: () => [] },
  opdList:          { type: Array,  default: () => [] },
  countAntrean:     { type: Number, default: 0 },
  slaKritis:        { type: Array,  default: () => [] },
  statistikBulanIni:{ type: Object, default: null },
  initialView:      { type: String, default: 'ringkasan' },
})

// ── State ─────────────────────────────────────────────────────────────────────
const page       = usePage()
const activeTab  = ref(props.initialView || 'ringkasan')
const filterSearch  = ref(props.filters?.search || '')
const filterUrgensi = ref(props.filters?.urgensi || '')

// Sync tab from URL
function syncTab() {
  if (typeof window === 'undefined') return
  const v = new URLSearchParams(window.location.search).get('view')
  if (v && ['ringkasan', 'antrean', 'sla'].includes(v)) activeTab.value = v
}
onMounted(syncTab)
watch(() => page.url, syncTab)

// ── Filtered list ─────────────────────────────────────────────────────────────
const filteredAntrean = computed(() => {
  let list = props.antrean
  if (filterSearch.value) {
    const q = filterSearch.value.toLowerCase()
    list = list.filter(r =>
      r.kode_pelacakan?.toLowerCase().includes(q) ||
      r.nama_pemohon?.toLowerCase().includes(q) ||
      r.deskripsi?.toLowerCase().includes(q)
    )
  }
  if (filterUrgensi.value) {
    list = list.filter(r => r.urgensi === filterUrgensi.value)
  }
  return list
})

// ── Prioritas cards ───────────────────────────────────────────────────────────
const prioritasCards = computed(() => [
  {
    label: 'Kritis', sla: '≤ 4 jam', count: props.antrean.filter(r => r.urgensi === 'kritis').length,
    borderClass: 'border-l-red-500', textClass: 'text-red-600', badgeClass: 'bg-red-100 text-red-700',
    desc: 'Target respons ≤ 4 jam',
  },
  {
    label: 'Tinggi', sla: '≤ 24 jam', count: props.antrean.filter(r => r.urgensi === 'tinggi').length,
    borderClass: 'border-l-amber-500', textClass: 'text-amber-600', badgeClass: 'bg-amber-100 text-amber-700',
    desc: 'Target respons ≤ 24 jam',
  },
  {
    label: 'Sedang', sla: '≤ 3 hari', count: props.antrean.filter(r => r.urgensi === 'sedang').length,
    borderClass: 'border-l-blue-500', textClass: 'text-blue-600', badgeClass: 'bg-blue-100 text-blue-700',
    desc: 'Target respons ≤ 3 hari kerja',
  },
  {
    label: 'Rendah', sla: '≤ 5 hari', count: props.antrean.filter(r => r.urgensi === 'rendah').length,
    borderClass: 'border-l-emerald-500', textClass: 'text-emerald-600', badgeClass: 'bg-emerald-100 text-emerald-700',
    desc: 'Target respons ≤ 5 hari kerja',
  },
])

// ── Helpers ───────────────────────────────────────────────────────────────────
function tabClass(tab) {
  return [
    'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2',
    activeTab.value === tab ? 'bg-[#0757A8] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200',
  ]
}

function getBadgeByUrgensi(urgensi) {
  return { kritis: 'bg-red-600', tinggi: 'bg-amber-500', sedang: 'bg-blue-600', rendah: 'bg-emerald-600' }[urgensi] ?? 'bg-slate-500'
}

function getBorderByUrgensi(urgensi) {
  return {
    kritis: 'border-red-200 bg-red-50/30',
    tinggi: 'border-amber-200 bg-amber-50/30',
    sedang: 'border-blue-200 bg-blue-50/20',
    rendah: 'border-slate-200 bg-slate-50/50',
  }[urgensi] ?? 'border-slate-200 bg-slate-50/50'
}
</script>
