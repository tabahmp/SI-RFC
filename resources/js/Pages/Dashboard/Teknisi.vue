<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- HEADER -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Ruang kerja implementasi</div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Tugas Aktif Saya</h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">Dokumentasikan implementasi dan pastikan setiap perubahan dapat diaudit.</p>
        </div>
        <span class="px-3 py-1.5 rounded-lg bg-cyan-50 text-cyan-800 text-xs font-bold border border-cyan-200 flex items-center gap-1.5">
          <Icon icon="lucide:terminal" class="w-4 h-4" />
          <span>Teknisi Pelaksana</span>
        </span>
      </div>

      <!-- Flash -->
      <div v-if="actionMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
        <div class="flex items-center gap-2">
          <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
          <span>{{ actionMsg }}</span>
        </div>
        <button @click="actionMsg = null" class="text-emerald-700 font-bold ml-2">✕</button>
      </div>

      <!-- 4 Kartu Metrik -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Antrian Tersedia</div>
          <div class="text-3xl font-black text-[#0F172A] mt-2">{{ antrian.length }}</div>
          <div class="text-[11px] text-amber-600 mt-1 font-semibold">Siap dikerjakan</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-blue-600 uppercase tracking-wide">Sedang Dikerjakan</div>
          <div class="text-3xl font-black text-blue-600 mt-2">{{ antrian.filter(r => r.status_terkini === 'sedang_dikerjakan').length }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Proses aktif</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Selesai</div>
          <div class="text-3xl font-black text-emerald-600 mt-2">{{ hasilSelesai.length }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Dengan dokumen BAST</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-purple-600 uppercase tracking-wide">Butuh BAST</div>
          <div class="text-3xl font-black text-purple-600 mt-2">{{ antrian.filter(r => r.status_terkini === 'disetujui').length }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Sudah disetujui pimpinan</div>
        </div>
      </div>

      <!-- SUB-NAVIGASI -->
      <div class="flex gap-2">
        <button @click="activeTab = 'tugas'" :class="tabClass('tugas')">
          <Icon icon="lucide:code-2" class="w-4 h-4" />
          <span>Tugas Aktif ({{ antrian.length }})</span>
        </button>
        <button @click="activeTab = 'bast'" :class="tabClass('bast')">
          <Icon icon="lucide:archive" class="w-4 h-4" />
          <span>Arsip Selesai</span>
        </button>
      </div>

      <!-- VIEW: TUGAS AKTIF -->
      <div v-if="activeTab === 'tugas'" class="space-y-4">

        <!-- Empty state -->
        <div v-if="antrian.length === 0" class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center space-y-3">
          <div class="w-12 h-12 rounded-full bg-blue-50 text-[#0757A8] flex items-center justify-center mx-auto">
            <Icon icon="lucide:check-circle" class="w-6 h-6" />
          </div>
          <h3 class="font-bold text-slate-800 text-base">Tidak ada tugas aktif</h3>
          <p class="text-xs text-slate-500 max-w-md mx-auto">Semua RFC telah selesai atau belum ada yang disetujui untuk Anda kerjakan.</p>
        </div>

        <!-- Kartu Tugas -->
        <div v-for="rfc in antrian" :key="rfc.id"
          class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-4">
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-xs font-extrabold text-white" :class="getBadgeByUrgensi(rfc.urgensi)">
                  {{ rfc.urgensi_label }}
                </span>
                <span class="font-mono text-xs font-bold text-[#0757A8]">{{ rfc.kode_pelacakan }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="statusClass(rfc.status_terkini)">
                  {{ rfc.status_label }}
                </span>
              </div>
              <h3 class="text-base font-extrabold text-[#0F172A]">{{ rfc.deskripsi || 'Permohonan RFC' }}</h3>
              <p class="text-xs text-slate-500 mt-0.5">{{ rfc.opd }} · {{ rfc.nama_pemohon }}</p>
            </div>
            <Link :href="`/rfc/${rfc.kode_pelacakan}/workspace`"
              class="px-4 py-2 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs">
              <Icon icon="lucide:external-link" class="w-3.5 h-3.5" />
              <span>Buka Workspace</span>
            </Link>
          </div>

          <!-- Aksi cepat -->
          <div class="flex gap-2 flex-wrap pt-2 border-t border-slate-100">
            <button v-if="rfc.status_terkini === 'disetujui'"
              @click="mulaiEksekusi(rfc)"
              :disabled="isSubmitting"
              class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-1.5 disabled:opacity-50">
              <Icon icon="lucide:play" class="w-3.5 h-3.5" />
              <span>Mulai Pengerjaan</span>
            </button>
            <span v-else class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200 flex items-center gap-1.5">
              <Icon icon="lucide:loader" class="w-3.5 h-3.5 animate-spin" />
              <span>Sedang Dikerjakan</span>
            </span>
            <Link :href="`/rfc/${rfc.kode_pelacakan}`"
              class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5">
              <Icon icon="lucide:file-text" class="w-3.5 h-3.5" />
              <span>Detail RFC</span>
            </Link>
          </div>
        </div>
      </div>

      <!-- VIEW: ARSIP SELESAI -->
      <div v-else-if="activeTab === 'bast'" class="space-y-4">
        <div v-if="hasilSelesai.length === 0" class="bg-white rounded-2xl border p-10 text-center text-slate-400 text-xs">
          <Icon icon="lucide:archive" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
          <p>Belum ada RFC yang selesai.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">KODE RFC</th>
                <th class="py-3 px-4">DESKRIPSI</th>
                <th class="py-3 px-4">TEKNISI</th>
                <th class="py-3 px-4">SELESAI</th>
                <th class="py-3 px-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="rfc in hasilSelesai" :key="rfc.id" class="hover:bg-slate-50">
                <td class="py-3 px-4 font-mono font-bold text-[#0757A8]">{{ rfc.kode_pelacakan }}</td>
                <td class="py-3 px-4 max-w-xs">
                  <div class="font-semibold text-slate-800 truncate">{{ rfc.deskripsi }}</div>
                  <div class="text-[11px] text-slate-500">{{ rfc.opd }}</div>
                </td>
                <td class="py-3 px-4 text-slate-600">{{ rfc.teknisi || '-' }}</td>
                <td class="py-3 px-4 text-slate-500">{{ rfc.selesai_pada || '-' }}</td>
                <td class="py-3 px-4 text-right">
                  <Link :href="`/rfc/${rfc.kode_pelacakan}`" class="text-[#0757A8] hover:underline font-bold text-xs">Detail</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'

// ── Props (match controller: dashboardTeknisi) ────────────────────────────────
const props = defineProps({
  antrian:      { type: Array, default: () => [] },   // RFC status 'disetujui' | 'sedang_dikerjakan'
  hasilSelesai: { type: Array, default: () => [] },   // RFC status 'selesai'
  initialView:  { type: String, default: 'ringkasan' },
})

// ── State ─────────────────────────────────────────────────────────────────────
const page       = usePage()
const activeTab  = ref('tugas')
const isSubmitting = ref(false)
const actionMsg  = ref(null)

const localAntrian = ref([...props.antrian])
watch(() => props.antrian, v => { localAntrian.value = [...v] })

// ── Actions ───────────────────────────────────────────────────────────────────
async function mulaiEksekusi(rfc) {
  isSubmitting.value = true
  try {
    await axios.post(`/rfc/${rfc.id}/mulai-eksekusi`, { catatan_eksekusi: 'Pengerjaan dimulai' })
    actionMsg.value = `Pengerjaan ${rfc.kode_pelacakan} berhasil dimulai.`
    // Update status lokal
    const idx = localAntrian.value.findIndex(r => r.id === rfc.id)
    if (idx !== -1) {
      localAntrian.value[idx] = { ...localAntrian.value[idx], status_terkini: 'sedang_dikerjakan', status_label: 'Sedang Dikerjakan' }
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal memulai pengerjaan.')
  } finally {
    isSubmitting.value = false
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function tabClass(tab) {
  return ['px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all',
    activeTab.value === tab ? 'bg-[#0757A8] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200']
}

function getBadgeByUrgensi(urgensi) {
  return { kritis: 'bg-red-600', tinggi: 'bg-amber-500', sedang: 'bg-blue-600', rendah: 'bg-emerald-600' }[urgensi] ?? 'bg-slate-500'
}

function statusClass(status) {
  return {
    disetujui: 'bg-purple-100 text-purple-800',
    sedang_dikerjakan: 'bg-cyan-100 text-cyan-800',
    selesai: 'bg-emerald-100 text-emerald-800',
  }[status] ?? 'bg-slate-100 text-slate-700'
}
</script>
