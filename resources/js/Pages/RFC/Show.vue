<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto px-4 py-10">
      <!-- Breadcrumb -->
      <nav class="text-xs text-slate-500 mb-6 flex items-center gap-1">
        <Link href="/form-perubahan-it" class="hover:text-[#0757a8]">Daftar Tiket</Link>
        <span>›</span>
        <span class="text-slate-700 font-medium">{{ tiket.no_rfc }}</span>
      </nav>

      <!-- Header Tiket -->
      <div class="card p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="font-mono text-2xl font-black text-[#0757a8] mb-1">{{ tiket.no_rfc }}</div>
            <div class="font-semibold text-slate-800 text-lg">{{ tiket.pemohon }}</div>
            <div class="text-slate-500 text-sm">{{ tiket.perangkat_daerah }}</div>
            <div class="text-slate-400 text-xs mt-1">Diajukan: {{ tiket.tanggal_permohonan }}</div>
          </div>
          <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
            <div class="flex gap-2 flex-wrap">
              <StatusBadge :status="tiket.status" :label="tiket.status_label" />
              <PrioritasBadge :prioritas="tiket.prioritas" />
            </div>

            <div class="flex items-center gap-2 pt-2 sm:pt-0">
              <Link
                :href="`/form-perubahan-it/dokumen/${tiket.no_rfc}`"
                class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-[#0757a8] text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs"
              >
                <Icon icon="lucide:file-text" class="w-4 h-4" />
                <span>Dokumen FR-015</span>
              </Link>

              <Link
                :href="`/form-perubahan-it/pengerjaan/${tiket.no_rfc}`"
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
      <div v-if="tiket.status === 'perlu_revisi'" class="bg-orange-50 border border-orange-200 rounded-xl p-5 mb-6">
        <div class="flex items-start gap-3 mb-4">
          <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          </div>
          <div>
            <h3 class="font-bold text-orange-800">Formulir Perlu Revisi</h3>
            <p class="text-orange-700 text-sm mt-1">{{ tiket.catatan_revisi }}</p>
          </div>
        </div>
        <button @click="showRevisiForm = !showRevisiForm" class="btn-warning text-sm">
          {{ showRevisiForm ? 'Tutup Form Revisi' : '✏️ Buka Form Revisi' }}
        </button>
        <div v-if="showRevisiForm" class="mt-4 space-y-3">
          <div>
            <label class="input-label">Perubahan yang Diharapkan (revisi)</label>
            <textarea v-model="revisiForm.perubahan_diharapkan" class="input-field" rows="3"></textarea>
          </div>
          <div>
            <label class="input-label">Latar Belakang (revisi)</label>
            <textarea v-model="revisiForm.latar_belakang" class="input-field" rows="3"></textarea>
          </div>
          <div>
            <label class="input-label">Rincian Perubahan (revisi)</label>
            <textarea v-model="revisiForm.rincian_perubahan" class="input-field" rows="3"></textarea>
          </div>
          <div>
            <label class="input-label">Tanda Tangan Pemohon</label>
            <SignaturePad v-model="revisiForm.tanda_tangan_file" />
          </div>
          <button @click="submitRevisi" :disabled="revisiLoading" class="btn-primary">
            <span v-if="revisiLoading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            {{ revisiLoading ? 'Mengirim...' : '📤 Kirim Revisi' }}
          </button>
          <div v-if="revisiSuccess" class="text-emerald-600 text-sm font-medium">✅ {{ revisiSuccess }}</div>
        </div>
      </div>

      <!-- Detail FR-015 -->
      <div class="card p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Detail Formulir FR-015</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
          <FieldRow label="Pemohon" :value="tiket.pemohon" />
          <FieldRow label="Unit Kerja" :value="tiket.unit_kerja" />
          <FieldRow label="Perangkat Daerah" :value="tiket.perangkat_daerah" />
          <FieldRow label="Nomor Kontak" :value="tiket.nomor_kontak" />
          <FieldRow label="Email Dinas" :value="tiket.email_dinas" />
          <FieldRow label="Tanggal Permohonan" :value="tiket.tanggal_permohonan" />
          <div class="md:col-span-2">
            <FieldRow label="Jenis Perubahan" :value="(tiket.jenis_perubahan || []).join(', ')" />
          </div>
          <div class="md:col-span-2"><FieldRow label="Perubahan yang Diharapkan" :value="tiket.perubahan_diharapkan" multiline /></div>
          <div class="md:col-span-2"><FieldRow label="Latar Belakang" :value="tiket.latar_belakang" multiline /></div>
          <div class="md:col-span-2"><FieldRow label="Rincian Perubahan" :value="tiket.rincian_perubahan" multiline /></div>
          <FieldRow label="Kriteria Risiko" :value="(tiket.kriteria_risiko || []).join(', ')" />
          <FieldRow label="Biaya Perubahan" :value="tiket.biaya_perubahan" />
          <FieldRow label="Waktu Perubahan" :value="tiket.waktu_perubahan" />
          <FieldRow label="Nama Aplikasi" :value="tiket.nama_aplikasi" />
        </div>
      </div>

      <!-- Matriks Otorisasi FR-015 -->
      <div class="card p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Matriks Otorisasi FR-015</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <OtorisasiKotak
            label="Dibuat Oleh" role="Pemohon"
            :nama="tiket.pemohon" :tanggal="tiket.tanggal_permohonan" :ttdUrl="tiket.tanda_tangan_url" />
          <OtorisasiKotak
            label="Diterima Oleh" role="Koordinator Agen"
            :nama="tiket.verifikator_nama" :tanggal="tiket.verifikator_tanggal" :ttdUrl="tiket.verifikator_ttd_url" />
          <OtorisasiKotak
            label="Disetujui Oleh" role="Penanggung Jawab Perangkat"
            :nama="tiket.pimpinan_nama" :tanggal="tiket.pimpinan_tanggal" :ttdUrl="tiket.pimpinan_ttd_url" />
          <OtorisasiKotak
            label="Dilaksanakan Oleh" role="Agen"
            :nama="tiket.agen_nama" :tanggal="tiket.tanggal_bast" :ttdUrl="tiket.agen_ttd_url" />
        </div>
      </div>

      <!-- Audit Trail -->
      <div class="card p-6">
        <h2 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Riwayat Aktivitas</h2>
        <div class="space-y-4">
          <div v-for="h in histories" :key="h.id" class="flex gap-3">
            <div :class="['w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold', roleColor(h.actor_role)]">
              {{ h.actor_name.charAt(0).toUpperCase() }}
            </div>
            <div class="flex-1">
              <div class="text-sm font-semibold text-slate-800">{{ h.actor_name }} <span class="font-normal text-slate-500 text-xs">({{ h.actor_role_label }})</span></div>
              <div class="text-sm text-slate-700">{{ h.keterangan }}</div>
              <div v-if="h.catatan" class="text-xs text-slate-500 mt-0.5 italic">{{ h.catatan }}</div>
              <div class="text-xs text-slate-400 mt-0.5">{{ h.created_at }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import SignaturePad from '@/Components/SignaturePad.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import PrioritasBadge from '@/Components/PrioritasBadge.vue'

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
          <span v-if="index < activeStep">✓</span>
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
      <div v-else class="text-slate-400 italic text-xs">—</div>
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

const props = defineProps({ tiket: Object, histories: Array })

const steps = ['Pemohon', 'Verifikasi', 'Pimpinan', 'Implementasi', 'BAST & Selesai']
const activeStep = computed(() => {
  const s = props.tiket.status
  if (['menunggu_verifikasi', 'perlu_revisi'].includes(s)) return 0
  if (s === 'menunggu_keputusan_pimpinan') return 1
  if (['disetujui', 'dalam_pengerjaan', 'menunggu_bast'].includes(s)) return 2
  if (s === 'selesai') return 4
  return 0
})

const showRevisiForm = ref(false)
const revisiLoading = ref(false)
const revisiSuccess = ref(null)
const revisiForm = reactive({
  perubahan_diharapkan: props.tiket.perubahan_diharapkan,
  latar_belakang: props.tiket.latar_belakang,
  rincian_perubahan: props.tiket.rincian_perubahan,
  tanda_tangan_file: null,
})

async function submitRevisi() {
  revisiLoading.value = true
  try {
    await axios.post(`/api/form-perubahan-it/${props.tiket.id}/revisi`, revisiForm)
    revisiSuccess.value = 'Revisi berhasil dikirim!'
    showRevisiForm.value = false
    setTimeout(() => router.reload(), 1500)
  } catch (e) {
    alert('Gagal mengirim revisi.')
  } finally {
    revisiLoading.value = false
  }
}

function roleColor(role) {
  return {
    pemohon: 'bg-[#0757a8]',
    verifikator: 'bg-purple-600',
    approver: 'bg-[#047857]',
    teknisi: 'bg-cyan-600',
    agen: 'bg-emerald-600',
    admin: 'bg-slate-600',
  }[role] || 'bg-slate-400'
}
</script>

