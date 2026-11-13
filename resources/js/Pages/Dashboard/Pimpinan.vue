<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- HEADER -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Ringkasan eksekutif</div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Papan Keputusan</h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">Keputusan terarah berdasarkan rekomendasi verifikator.</p>
        </div>
        <span class="px-3 py-1.5 rounded-lg bg-blue-50 text-[#0757A8] text-xs font-bold border border-blue-200 flex items-center gap-1.5">
          <Icon icon="lucide:scale" class="w-4 h-4" />
          <span>Pimpinan APTIKA</span>
        </span>
      </div>

      <!-- Flash message -->
      <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
        <div class="flex items-center gap-2">
          <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
          <span>{{ successMsg }}</span>
        </div>
        <button @click="successMsg = null" class="text-emerald-700 hover:text-emerald-900 font-bold ml-2">✕</button>
      </div>

      <!-- 4 Kartu Metrik -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-amber-600 uppercase tracking-wide">Menunggu Keputusan</div>
          <div class="text-3xl font-black text-amber-600 mt-2">{{ countMenunggu }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Memerlukan keputusan pimpinan</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Disetujui</div>
          <div class="text-3xl font-black text-emerald-600 mt-2">{{ statistik?.disetujui ?? 0 }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Bulan ini</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Ditolak / Ditunda</div>
          <div class="text-3xl font-black text-red-600 mt-2">{{ (statistik?.ditolak ?? 0) + (statistik?.ditunda ?? 0) }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Dampak risiko tinggi</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-[#0757A8] uppercase tracking-wide">Total Selesai</div>
          <div class="text-3xl font-black text-[#0757A8] mt-2">{{ statistik?.total_selesai ?? 0 }}</div>
          <div class="text-[11px] text-slate-500 mt-1">RFC berhasil diimplementasi</div>
        </div>
      </div>

      <!-- Form Keputusan -->
      <div v-if="activeTiket" class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2 py-0.5 rounded text-xs font-extrabold text-white" :class="getBadgeByUrgensi(activeTiket.urgensi)">
                {{ activeTiket.urgensi_label }}
              </span>
              <span class="font-mono text-xs font-bold text-[#0757A8]">{{ activeTiket.kode_pelacakan }}</span>
            </div>
            <h2 class="text-lg font-extrabold text-[#0F172A]">{{ activeTiket.deskripsi || 'Permohonan RFC' }}</h2>
            <div class="text-xs text-slate-500 mt-0.5">{{ activeTiket.opd }} · {{ activeTiket.nama_pemohon }}</div>
          </div>
          <div class="flex items-center gap-2">
            <!-- Pilih tiket -->
            <select v-if="menungguList.length > 1" v-model="selectedId"
              class="text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700">
              <option v-for="t in menungguList" :key="t.id" :value="t.id">
                {{ t.kode_pelacakan }}
              </option>
            </select>
            <Link :href="`/rfc/${activeTiket.kode_pelacakan}`"
              class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors">
              <Icon icon="lucide:file-text" class="w-4 h-4 text-[#0757A8]" />
              <span>Detail FR-015</span>
            </Link>
          </div>
        </div>

        <!-- Rekomendasi verifikator (jika ada) -->
        <div v-if="revisiTerbaru" class="p-4 rounded-xl bg-purple-50 border border-purple-200/80 space-y-1">
          <div class="text-xs font-bold text-purple-800 flex items-center gap-1.5">
            <Icon icon="lucide:message-circle" class="w-4 h-4 text-purple-600" />
            <span>Catatan Verifikator</span>
          </div>
          <p class="text-xs text-purple-900/90 leading-relaxed">{{ revisiTerbaru }}</p>
        </div>

        <!-- Form keputusan -->
        <form @submit.prevent="submitKeputusan" class="space-y-4">

          <!-- Pilihan keputusan -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-2">Keputusan <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-3 gap-3">
              <label v-for="opt in keputusanOptions" :key="opt.value"
                :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all',
                  keputusan === opt.value ? opt.activeClass : 'border-slate-200 hover:bg-slate-50']">
                <input type="radio" :value="opt.value" v-model="keputusan" class="sr-only" />
                <Icon :icon="opt.icon" class="w-6 h-6 mb-1" :class="opt.iconClass" />
                <span class="font-bold text-xs">{{ opt.label }}</span>
                <span class="text-[10px] text-slate-500">{{ opt.desc }}</span>
              </label>
            </div>
          </div>

          <!-- Pilih teknisi (jika disetujui) -->
          <div v-if="keputusan === 'disetujui'" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1">Teknisi Pelaksana <span class="text-red-500">*</span></label>
              <select v-model="form.teknisi_id" required
                class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-800 focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none">
                <option value="" disabled>Pilih teknisi...</option>
                <option v-for="t in teknisiList" :key="t.id" :value="t.id">{{ t.nama }}</option>
              </select>
              <p class="text-[10px] text-slate-400 mt-1">Jika teknisi tidak ada, tambahkan via Admin.</p>
            </div>
          </div>

          <!-- Jadwal tinjau (jika ditunda) -->
          <div v-if="keputusan === 'ditunda'">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Jadwal Tinjau Ulang <span class="text-red-500">*</span></label>
            <input v-model="form.jadwal_tinjau" type="date" required
              class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none" />
          </div>

          <!-- Catatan (wajib untuk tolak/tunda) -->
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">
              Catatan Pimpinan
              <span v-if="keputusan !== 'disetujui'" class="text-red-500">*</span>
            </label>
            <textarea v-model="form.catatan_keputusan" :required="keputusan !== 'disetujui'" rows="3"
              :placeholder="keputusan === 'disetujui' ? 'Arahan atau instruksi khusus (opsional)...' : 'Alasan keputusan (wajib)...'"
              class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none" />
          </div>

          <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" :disabled="isSubmitting"
              class="px-6 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold shadow-xs flex items-center gap-1.5 disabled:opacity-50">
              <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
              <Icon v-else icon="lucide:send" class="w-4 h-4" />
              <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Keputusan' }}</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Empty state -->
      <div v-else-if="menungguList.length === 0" class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center space-y-3">
        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
          <Icon icon="lucide:check-circle" class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-slate-800 text-base">Tidak ada permohonan menunggu keputusan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">Semua permohonan telah ditindaklanjuti.</p>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'

// ── Props (match controller: dashboardPimpinan) ───────────────────────────────
const props = defineProps({
  menungguKeputusan: { type: Array,  default: () => [] },   // RFC status 'menunggu_persetujuan'
  countMenunggu:     { type: Number, default: 0 },
  statistik:         { type: Object, default: null },
  initialView:       { type: String, default: 'ringkasan' },
})

// ── State ─────────────────────────────────────────────────────────────────────
const isSubmitting = ref(false)
const successMsg   = ref(null)
const keputusan    = ref('disetujui')

const form = ref({
  catatan_keputusan: '',
  teknisi_id: '',
  jadwal_tinjau: '',
})

const menungguList   = ref([...props.menungguKeputusan])
const selectedId     = ref(menungguList.value[0]?.id ?? null)

watch(() => props.menungguKeputusan, v => { menungguList.value = [...v] })
watch(menungguList, list => { if (list.length > 0 && !selectedId.value) selectedId.value = list[0].id }, { immediate: true })

const activeTiket = computed(() => menungguList.value.find(t => t.id === selectedId.value) ?? menungguList.value[0] ?? null)

// Ambil catatan verifikator terbaru dari histories (jika ada di prop rfc)
const revisiTerbaru = computed(() => null) // akan ditampilkan dari workspace

// Teknisi list dari halaman (akan di-fetch via API)
const teknisiList = ref([])

// Fetch daftar teknisi
async function loadTeknisi() {
  try {
    const res = await axios.get('/api/teknisi-list')
    teknisiList.value = res.data
  } catch {}
}
loadTeknisi()

// ── Submit ────────────────────────────────────────────────────────────────────
async function submitKeputusan() {
  if (!activeTiket.value) return
  isSubmitting.value = true

  const payload = {
    keputusan: keputusan.value,                // 'disetujui' | 'ditolak' | 'ditunda'
    catatan_keputusan: form.value.catatan_keputusan,
    teknisi_id: keputusan.value === 'disetujui' ? form.value.teknisi_id : null,
    jadwal_tinjau_ulang: keputusan.value === 'ditunda' ? form.value.jadwal_tinjau : null,
  }

  try {
    await axios.post(`/rfc/${activeTiket.value.id}/keputusan`, payload)
    successMsg.value = `Keputusan berhasil disimpan untuk ${activeTiket.value.kode_pelacakan}.`
    // Hapus dari list lokal
    menungguList.value = menungguList.value.filter(t => t.id !== activeTiket.value.id)
    selectedId.value = menungguList.value[0]?.id ?? null
    form.value = { catatan_keputusan: '', teknisi_id: '', jadwal_tinjau: '' }
    keputusan.value = 'disetujui'
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan keputusan.')
  } finally {
    isSubmitting.value = false
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function getBadgeByUrgensi(urgensi) {
  return { kritis: 'bg-red-600', tinggi: 'bg-amber-500', sedang: 'bg-blue-600', rendah: 'bg-emerald-600' }[urgensi] ?? 'bg-slate-500'
}

const keputusanOptions = [
  { value: 'disetujui', label: 'Setujui', desc: 'Lanjut ke teknisi', icon: 'lucide:check-circle-2', iconClass: 'text-emerald-600', activeClass: 'border-emerald-500 bg-emerald-50 text-emerald-800' },
  { value: 'ditunda',   label: 'Tunda',   desc: 'Jadwal nanti',       icon: 'lucide:clock',           iconClass: 'text-amber-600',   activeClass: 'border-amber-500 bg-amber-50 text-amber-800' },
  { value: 'ditolak',   label: 'Tolak',   desc: 'RFC dibatalkan',     icon: 'lucide:x-circle',        iconClass: 'text-red-600',     activeClass: 'border-red-500 bg-red-50 text-red-800' },
]
</script>
