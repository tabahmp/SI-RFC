<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto px-4 py-10">

      <!-- Tombol Kembali -->
      <Link
        href="/dashboard"
        class="inline-flex items-center gap-2 text-xs font-semibold text-[#0757A8] hover:underline mb-5"
      >
        <Icon icon="lucide:arrow-left" class="w-4 h-4" />
        <span>Kembali ke Dashboard</span>
      </Link>

      <!-- Breadcrumb -->
      <nav class="text-xs text-slate-500 mb-6 flex items-center gap-1">
        <Link href="/dashboard" class="hover:text-[#0757a8]">Dashboard</Link>
        <span>›</span>
        <span class="text-slate-700 font-medium">{{ rfc.kode_pelacakan }}</span>
      </nav>

      <!-- Header RFC -->
      <div class="card p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="font-mono text-2xl font-black text-[#0757a8] mb-1">{{ rfc.kode_pelacakan }}</div>
            <div class="font-semibold text-slate-800 text-lg">{{ rfc.nama_pemohon }}</div>
            <div class="text-slate-500 text-sm">{{ rfc.opd }}</div>
            <div class="text-slate-400 text-xs mt-1">Diajukan: {{ rfc.tanggal_permohonan }}</div>
          </div>
          <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
            <div class="flex gap-2 flex-wrap">
              <span :class="['px-2.5 py-1 rounded-full text-[10px] font-bold', rfc.warna_badge]">{{ rfc.status_label }}</span>
              <span :class="['px-2 py-0.5 rounded-md text-[10px] font-bold', rfc.urgensi_warna]">{{ rfc.urgensi_label }}</span>
            </div>

            <div class="flex items-center gap-2 pt-2 sm:pt-0">
              <Link
                :href="`/rfc/${rfc.kode_pelacakan}/dokumen`"
                class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-[#0757a8] text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs"
              >
                <Icon icon="lucide:file-text" class="w-4 h-4" />
                <span>Dokumen FR-015</span>
              </Link>

              <Link
                :href="`/rfc/${rfc.kode_pelacakan}/workspace`"
                class="px-4 py-2 rounded-xl bg-[#0757a8] hover:bg-[#063d78] text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs"
              >
                <Icon icon="lucide:external-link" class="w-4 h-4" />
                <span>Buka Workspace</span>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Stepper -->
      <div class="card p-6 mb-6">
        <div class="flex items-center justify-between gap-2">
          <StepperItem v-for="(step, i) in steps" :key="i" :step="step" :index="i" :activeStep="activeStep" :isLast="i === steps.length - 1" />
        </div>
      </div>

      <!-- Panel Revisi -->
      <div v-if="rfc.status_terkini === 'perlu_revisi'" class="bg-orange-50 border border-orange-200 rounded-xl p-5 mb-6">
        <div class="flex items-start gap-3 mb-4">
          <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          </div>
          <div>
            <h3 class="font-bold text-orange-800">Formulir Perlu Revisi</h3>
            <p class="text-orange-700 text-sm mt-1">{{ revisiCatatan }}</p>
          </div>
        </div>
        <Link :href="`/rfc/${rfc.id}/edit`" class="btn-warning text-sm inline-flex items-center gap-1.5">
          <Icon icon="lucide:edit-3" class="w-4 h-4" />
          <span>Buka Form Edit untuk Perbaiki</span>
        </Link>
      </div>

      <!-- Detail FR-015 -->
      <div class="card p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Detail Formulir FR-015</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
          <FieldRow label="Pemohon" :value="rfc.nama_pemohon" />
          <FieldRow label="Unit Kerja" :value="rfc.unit_kerja" />
          <FieldRow label="Perangkat Daerah" :value="rfc.opd" />
          <FieldRow label="Nomor Kontak" :value="rfc.nomor_kontak" />
          <FieldRow label="Email Dinas" :value="rfc.email_dinas" />
          <FieldRow label="Tanggal Permohonan" :value="rfc.tanggal_permohonan" />
          <FieldRow label="NIP" :value="rfc.nip" />
          <FieldRow label="Kategori" :value="rfc.kategori" />
          <div class="md:col-span-2">
            <FieldRow label="Jenis Perubahan" :value="formatJenisPerubahan(rfc.jenis_perubahan)" />
          </div>
          <div class="md:col-span-2"><FieldRow label="Deskripsi Perubahan" :value="rfc.deskripsi" multiline /></div>
          <div class="md:col-span-2"><FieldRow label="Latar Belakang" :value="rfc.latar_belakang" multiline /></div>
          <div class="md:col-span-2"><FieldRow label="Rincian Perubahan" :value="rfc.rincian_perubahan" multiline /></div>
          <FieldRow label="Kriteria Risiko" :value="rfc.kriteria_risiko" />
          <FieldRow label="Biaya Perubahan" :value="rfc.biaya_perubahan" />
          <FieldRow label="Waktu Perubahan" :value="rfc.waktu_perubahan" />
          <FieldRow label="Nama Aplikasi" :value="rfc.nama_aplikasi" />
          <FieldRow label="URL Aplikasi" :value="rfc.alamat_aplikasi" />
        </div>
      </div>

      <!-- Matriks Otorisasi FR-015 -->
      <div class="card p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Matriks Otorisasi FR-015</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <OtorisasiKotak
            label="Dibuat Oleh" role="Pemohon"
            :nama="rfc.nama_pemohon" :tanggal="rfc.tanggal_permohonan" :ttdUrl="rfc.tanda_tangan_url" />
          <OtorisasiKotak
            label="Diterima Oleh" role="Verifikator"
            :nama="revisiVerifikator" :tanggal="revisiTanggal" :ttdUrl="null" />
          <OtorisasiKotak
            label="Disetujui Oleh" role="Pimpinan"
            :nama="persetujuanPimpinan" :tanggal="persetujuanTanggal" :ttdUrl="null" />
          <OtorisasiKotak
            label="Dilaksanakan Oleh" role="Teknisi"
            :nama="eksekusiTeknisi" :tanggal="eksekusiTanggal" :ttdUrl="null" />
        </div>
      </div>

      <!-- Audit Trail -->
      <div class="card p-6">
        <h2 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Riwayat Aktivitas</h2>
        <div class="space-y-4">
          <div v-for="h in histories" :key="h.id" class="flex gap-3">
            <div :class="['w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold', roleColor(h.peran_aktor)]">
              {{ (h.nama_aktor || '?').charAt(0).toUpperCase() }}
            </div>
            <div class="flex-1">
              <div class="text-sm font-semibold text-slate-800">{{ h.nama_aktor }} <span class="font-normal text-slate-500 text-xs">({{ h.peran_aktor }})</span></div>
              <div class="text-sm text-slate-700">{{ h.keterangan }}</div>
              <div v-if="h.catatan" class="text-xs text-slate-500 mt-0.5 italic">{{ h.catatan }}</div>
              <div class="text-xs text-slate-400 mt-0.5">{{ h.created_at }}</div>
            </div>
          </div>
          <div v-if="!histories || histories.length === 0" class="text-center text-slate-400 text-sm py-4">
            Belum ada riwayat aktivitas.
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'

// Inline sub-components
const StepperItem = {
  props: ['step', 'index', 'activeStep', 'isLast'],
  template: `
    <div class="flex items-center flex-1">
      <div class="flex flex-col items-center">
        <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors',
          index < activeStep ? 'bg-emerald-500 border-emerald-500 text-white' :
          index === activeStep ? 'bg-[#0757a8] border-[#0757a8] text-white' :
          'bg-white border-slate-300 text-slate-400']">
          <span v-if="index < activeStep">&#10003;</span>
          <span v-else>{{ index + 1 }}</span>
        </div>
        <span class="text-xs text-center mt-1 leading-tight w-16" :class="index === activeStep ? 'text-[#0757a8] font-semibold' : 'text-slate-500'">{{ step }}</span>
      </div>
      <div v-if="!isLast" :class="['flex-1 h-0.5 mb-5 mx-1', index < activeStep ? 'bg-emerald-400' : 'bg-slate-200']"></div>
    </div>
  `
}

const FieldRow = {
  props: ['label', 'value', 'multiline'],
  template: `
    <div>
      <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-0.5">{{ label }}</div>
      <div v-if="value" :class="['text-slate-800', multiline ? 'whitespace-pre-wrap' : '']">{{ value }}</div>
      <div v-else class="text-slate-400 italic text-xs">&mdash;</div>
    </div>
  `
}

const OtorisasiKotak = {
  props: ['label', 'role', 'nama', 'tanggal', 'ttdUrl'],
  template: `
    <div class="border border-slate-200 rounded-xl p-4 min-h-32">
      <div class="text-xs text-slate-500 mb-1">Tanggal: {{ tanggal || '...' }}</div>
      <div class="text-sm font-bold text-slate-700">{{ label }}:</div>
      <div class="text-xs text-slate-500 mb-3">{{ role }},</div>
      <div class="min-h-16 flex items-center justify-center">
        <img v-if="ttdUrl" :src="ttdUrl" alt="TTD" class="max-h-16 object-contain" />
        <span v-else class="text-slate-300 text-xs italic">Menunggu tanda tangan...</span>
      </div>
      <div class="border-t border-slate-200 mt-2 pt-2 text-sm font-medium text-slate-700">{{ nama || '( ................ )' }}</div>
    </div>
  `
}

const props = defineProps({
  rfc: { type: Object, required: true },
  histories: { type: Array, default: () => [] },
})

const steps = ['Pemohon', 'Verifikasi', 'Pimpinan', 'Implementasi', 'BAST & Selesai']

const activeStep = computed(() => {
  const s = props.rfc.status_terkini
  if (['draf', 'diajukan', 'perlu_revisi'].includes(s)) return 0
  if (s === 'menunggu_persetujuan') return 1
  if (['disetujui', 'sedang_dikerjakan'].includes(s)) return 2
  if (s === 'selesai') return 4
  return 0
})

// Revisi info
const revisiCatatan = computed(() => {
  if (props.rfc.revisi && props.rfc.revisi.length > 0) {
    return props.rfc.revisi[0].catatan_revisi || 'Tidak ada catatan revisi.'
  }
  return 'Tidak ada catatan revisi.'
})
const revisiVerifikator = computed(() => {
  if (props.rfc.revisi && props.rfc.revisi.length > 0) {
    return props.rfc.revisi[0].verifikator
  }
  return null
})
const revisiTanggal = computed(() => {
  if (props.rfc.revisi && props.rfc.revisi.length > 0) {
    return props.rfc.revisi[0].dibuat_pada
  }
  return null
})

// Persetujuan info
const persetujuanPimpinan = computed(() => props.rfc.persetujuan?.pimpinan ?? null)
const persetujuanTanggal = computed(() => props.rfc.persetujuan?.diputuskan_pada ?? null)

// Eksekusi info
const eksekusiTeknisi = computed(() => props.rfc.eksekusi?.teknisi ?? null)
const eksekusiTanggal = computed(() => props.rfc.eksekusi?.dimulai_pada ?? null)

function formatJenisPerubahan(jenis) {
  if (!jenis) return '-'
  if (Array.isArray(jenis)) return jenis.join(', ')
  try {
    const parsed = JSON.parse(jenis)
    return Array.isArray(parsed) ? parsed.join(', ') : parsed
  } catch {
    return String(jenis)
  }
}

function roleColor(role) {
  return {
    pemohon: 'bg-[#0757a8]',
    verifikator: 'bg-purple-600',
    pimpinan: 'bg-[#047857]',
    teknisi: 'bg-cyan-600',
    admin: 'bg-slate-600',
  }[role] || 'bg-slate-400'
}
</script>

<style scoped>
.card {
  background: white;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}
.btn-warning {
  background: #f59e0b;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.75rem;
  font-weight: 700;
  transition: all 0.2s;
}
.btn-warning:hover {
  background: #d97706;
}
</style>
