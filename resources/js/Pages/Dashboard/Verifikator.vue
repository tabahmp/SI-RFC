<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

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
              <Link href="/dashboard/verifikator?view=antrean"
                class="text-xs font-bold text-[#0757A8] px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 flex items-center gap-1.5 transition-colors">
                <Icon icon="lucide:list-filter" class="w-3.5 h-3.5" />
                <span>Lihat Seluruh Antrean</span>
              </Link>
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
                  <SlaTimer v-if="rfc.target_sla" :target-sla="rfc.target_sla" class="text-right" />
                  <Link :href="`/rfc/${rfc.kode_pelacakan}/workspace`"
                    class="px-3.5 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs whitespace-nowrap">
                    Telaah
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <!-- SLA Alert Widget -->
          <div class="bg-[#053775] rounded-[20px] p-6 text-white shadow-sm flex flex-col justify-between gap-6">
            <div class="space-y-5">
              <!-- Top bar: Icon + SLA ALERT Pill -->
              <div class="flex items-center justify-between gap-3">
                <div
                  class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors"
                  :class="isTerdekatOverdue ? 'bg-red-500/25' : 'bg-white/10'"
                >
                  <Icon
                    :icon="isTerdekatOverdue ? 'lucide:alarm-clock-off' : 'lucide:alarm-clock'"
                    class="w-5 h-5"
                    :class="isTerdekatOverdue ? 'text-red-300' : 'text-white'"
                  />
                </div>
                <span
                  class="px-4 py-1.5 rounded-full text-xs font-extrabold tracking-wide uppercase transition-colors"
                  :class="isTerdekatOverdue ? 'bg-red-500 text-white' : 'bg-[#FACC15] text-[#053775]'"
                >
                  {{ isTerdekatOverdue ? 'SLA TERLAMPAUI' : 'SLA ALERT' }}
                </span>
              </div>

              <!-- Title & subtitle -->
              <div class="space-y-2.5">
                <h3 class="text-2xl font-extrabold text-white tracking-tight leading-snug">
                  {{ slaKritis.length }} tiket mendekati batas
                </h3>
                <p class="text-sm text-blue-100/80 leading-relaxed">
                  Prioritaskan tiket P1 agar kepatuhan target tanggap tetap terjaga.
                </p>
              </div>
            </div>

            <!-- Inner Terdekat Box -->
            <Link
              v-if="slaKritis.length > 0"
              :href="`/rfc/${slaKritis[0].kode_pelacakan}/workspace`"
              class="block p-4 rounded-2xl transition-colors space-y-2.5"
              :class="isTerdekatOverdue
                ? 'bg-red-500/15 hover:bg-red-500/25 border border-red-400/40'
                : 'bg-white/10 hover:bg-white/15'"
            >
              <div
                class="text-xs font-medium"
                :class="isTerdekatOverdue ? 'text-red-200' : 'text-blue-100/75'"
              >
                {{ isTerdekatOverdue ? 'Terdekat · Melewati Batas' : 'Terdekat' }}
              </div>
              <div class="text-base font-extrabold text-white tracking-wide">
                {{ slaKritis[0].kode_pelacakan }}
              </div>
              <div class="w-full h-1.5 rounded-full bg-white/20 overflow-hidden">
                <div
                  class="h-full rounded-full transition-all duration-700"
                  :class="isTerdekatOverdue ? 'bg-red-500' : 'bg-[#FACC15]'"
                  :style="{ width: `${terdekatSlaProgress}%` }"
                ></div>
              </div>
              <div
                class="text-xs tabular-nums"
                :class="isTerdekatOverdue ? 'text-red-200 font-semibold' : 'text-blue-100/80 font-medium'"
              >
                {{ terdekatSlaCountdown }}
              </div>
            </Link>

            <div
              v-else
              class="p-4 rounded-2xl bg-white/10 space-y-1.5"
            >
              <div class="text-xs font-medium text-blue-100/75">Status SLA</div>
              <div class="flex items-center gap-2 text-sm font-bold text-white">
                <Icon icon="lucide:check-circle-2" class="w-4 h-4 text-[#FACC15]" />
                <span>Semua tiket dalam batas SLA</span>
              </div>
              <div class="w-full h-1.5 rounded-full bg-white/20 overflow-hidden">
                <div class="h-full rounded-full bg-[#FACC15] w-0"></div>
              </div>
            </div>
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
                  <th class="py-3 px-4 text-center">TINDAKAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="rfc in filteredAntrean" :key="rfc.id" class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-[#0757A8]">{{ rfc.kode_pelacakan }}</div>
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
                    <SlaTimer v-if="rfc.target_sla" :target-sla="rfc.target_sla" />
                    <span v-else class="text-slate-400 text-xs">—</span>
                  </td>
                  <td class="py-3.5 px-4 text-center">
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
                  <th class="py-3 px-4 text-center">TINDAKAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="rfc in slaKritis" :key="rfc.id" class="bg-red-50/50 hover:bg-red-50">
                  <td class="py-3.5 px-4 font-bold text-red-700">{{ rfc.kode_pelacakan }}</td>
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
                    <SlaTimer :target-sla="rfc.target_sla" class="font-bold text-red-600" />
                  </td>
                  <td class="py-3.5 px-4 text-center">
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
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
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

// Live clock for SLA Alert countdown & progress bar
const nowTick = ref(Date.now())
let slaInterval = null

onMounted(() => {
  syncTab()
  slaInterval = setInterval(() => {
    nowTick.value = Date.now()
  }, 1000)
})

onUnmounted(() => {
  if (slaInterval) clearInterval(slaInterval)
})

watch(() => page.url, syncTab)

const SLA_WINDOW_HOURS = {
  kritis: 4,
  tinggi: 24,
  sedang: 72,
  rendah: 120,
}

const isTerdekatOverdue = computed(() => {
  const terdekat = props.slaKritis[0]
  if (!terdekat?.target_sla) return false
  return new Date(terdekat.target_sla).getTime() <= nowTick.value
})

const terdekatSlaCountdown = computed(() => {
  const terdekat = props.slaKritis[0]
  if (!terdekat?.target_sla) return 'Tidak ada SLA'
  const diffMs = new Date(terdekat.target_sla).getTime() - nowTick.value
  const pad = n => String(n).padStart(2, '0')
  const formatHms = (ms) => {
    const totalSec = Math.floor(Math.abs(ms) / 1000)
    const hrs = Math.floor(totalSec / 3600)
    const mins = Math.floor((totalSec % 3600) / 60)
    const secs = totalSec % 60
    return `${pad(hrs)}:${pad(mins)}:${pad(secs)}`
  }
  if (diffMs <= 0) return `Terlampaui ${formatHms(diffMs)}`
  return `${formatHms(diffMs)} tersisa`
})

const terdekatSlaProgress = computed(() => {
  const terdekat = props.slaKritis[0]
  if (!terdekat?.target_sla) return 0
  const targetMs = new Date(terdekat.target_sla).getTime()
  const remainingMs = targetMs - nowTick.value
  if (remainingMs <= 0) return 100
  const totalWindowMs = (SLA_WINDOW_HOURS[terdekat.urgensi] ?? 24) * 3_600_000
  const elapsedPct = ((totalWindowMs - remainingMs) / totalWindowMs) * 100
  return Math.min(100, Math.max(8, Math.round(elapsedPct)))
})

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
