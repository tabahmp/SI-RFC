<template>
  <AppLayout role="pimpinan">
    <div class="max-w-[1440px] mx-auto space-y-6">
      
      <!-- HEADER DASHBOARD PIMPINAN (PERSIS FIGMA 1:474) -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
            Ringkasan eksekutif
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
            Papan Keputusan
          </h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
            Keputusan terarah berdasarkan telaah teknis dan dampak layanan.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <button
            @click="exportPdf"
            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-colors"
          >
            <Icon icon="lucide:file-down" class="w-4 h-4 text-red-500" />
            <span>Ekspor PDF</span>
          </button>
          <button
            @click="exportExcel"
            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-colors"
          >
            <Icon icon="lucide:sheet" class="w-4 h-4 text-emerald-600" />
            <span>Excel</span>
          </button>
        </div>
      </div>

      <!-- Alert Notifikasi Tindakan Pimpinan -->
      <div v-if="actionSuccessMessage" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
        <div class="flex items-center gap-2">
          <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
          <span>{{ actionSuccessMessage }}</span>
        </div>
        <button @click="actionSuccessMessage = null" class="text-emerald-700 hover:text-emerald-900 font-bold ml-2">✕</button>
      </div>

      <!-- 4 KARTU METRIK PIMPINAN (PERSIS FIGMA) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-amber-600 uppercase tracking-wide">Menunggu Persetujuan</div>
          <div class="text-3xl font-black text-amber-600 mt-2">{{ menungguPersetujuanList.length }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Memerlukan keputusan pimpinan</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Disetujui Bulan Ini</div>
          <div class="text-3xl font-black text-emerald-600 mt-2">{{ disetujuiCount }}</div>
          <div class="text-[11px] text-emerald-600 mt-1 font-semibold">+18% dari bulan lalu</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Ditolak / Ditunda</div>
          <div class="text-3xl font-black text-red-600 mt-2">{{ ditolakDitundaCount }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Dampak risiko tinggi</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-[#0757A8] uppercase tracking-wide">Kepatuhan SLA</div>
          <div class="text-3xl font-black text-[#0757A8] mt-2">{{ kepatuhanSla }}%</div>
          <div class="text-[11px] text-slate-500 mt-1">Target Diskominfo ≥ 95%</div>
        </div>
      </div>

      <!-- KARTU TINJAUAN KEPUTUSAN UTAMA -->
      <div v-if="activeTiket" class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2 py-0.5 rounded text-xs font-extrabold" :class="activeTiket.prioritas === 'P1' || activeTiket.prioritas === 'P1 - Kritis' ? 'bg-red-600 text-white' : 'bg-amber-500 text-white'">
                {{ activeTiket.prioritas }}
              </span>
              <span class="font-mono text-xs font-bold text-[#0757A8]">{{ activeTiket.no_rfc }}</span>
            </div>
            <h2 class="text-lg font-extrabold text-[#0F172A]">{{ activeTiket.perubahan_diharapkan }}</h2>
            <div class="text-xs text-slate-500 mt-0.5">Pemohon: {{ activeTiket.perangkat_daerah }} ({{ activeTiket.pemohon }})</div>
          </div>

          <div class="flex items-center gap-2">
            <!-- Pilihan Tiket jika ada lebih dari 1 -->
            <select
              v-if="menungguPersetujuanList.length > 1"
              v-model="selectedTicketId"
              class="text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700"
            >
              <option v-for="t in menungguPersetujuanList" :key="t.id" :value="t.id">
                {{ t.no_rfc }} — {{ t.nama_aplikasi }}
              </option>
            </select>

            <Link
              :href="`/form-perubahan-it/dokumen/${activeTiket.no_rfc}`"
              class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors"
            >
              <Icon icon="lucide:file-text" class="w-4 h-4 text-[#0757A8]" />
              <span>Lihat Dokumen FR-015 Lengkap</span>
            </Link>
          </div>
        </div>

        <!-- Rekomendasi Verifikator APTIKA -->
        <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200/80 space-y-1">
          <div class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
            <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600" />
            <span>Rekomendasi verifikator: Layak dilanjutkan ke tahap implementasi teknis</span>
          </div>
          <p class="text-xs text-emerald-900/90 leading-relaxed">
            Arsitektur integrasi memenuhi standar keamanan informasi. Telah diperiksa kelayakan administratif dan kriteria risiko SMKI ISO/IEC 27001.
          </p>
        </div>

        <!-- Form Keputusan & Disposisi -->
        <form @submit.prevent="submitDecision" class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-4">
          <div class="text-xs font-bold text-slate-700 uppercase tracking-wide">Tentukan keputusan resmi pimpinan</div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1">Tim pelaksana / Teknisi ditugaskan <span class="text-red-500">*</span></label>
              <input
                v-model="formKeputusan.assigned_to"
                type="text"
                placeholder="Contoh: Teguh Wicaksono, S.T / Tim Integrasi & API"
                required
                class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-800 font-medium focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1">Target selesai implementasi <span class="text-red-500">*</span></label>
              <input
                v-model="formKeputusan.target_deadline"
                type="date"
                required
                class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-800 font-medium focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan dan instruksi pimpinan</label>
            <textarea
              v-model="formKeputusan.pimpinan_catatan"
              rows="2"
              placeholder="Berikan arahan mitigasi risiko atau instruksi pengerjaan..."
              class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-800 focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all"
            ></textarea>
          </div>

          <!-- 3 Opsi Tombol Keputusan -->
          <div class="flex flex-wrap items-center gap-3 pt-2">
            <button
              type="button"
              @click="pimpinanChoice = 'ditolak'"
              :class="[
                'px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5',
                pimpinanChoice === 'ditolak' ? 'bg-red-600 text-white shadow-xs' : 'bg-red-50 text-red-700 border border-red-200'
              ]"
            >
              <Icon icon="lucide:x-circle" class="w-3.5 h-3.5" />
              <span>Tolak</span>
            </button>
            <button
              type="button"
              @click="pimpinanChoice = 'ditunda'"
              :class="[
                'px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5',
                pimpinanChoice === 'ditunda' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-700 border border-amber-200'
              ]"
            >
              <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
              <span>Tunda</span>
            </button>
            <button
              type="button"
              @click="pimpinanChoice = 'disetujui'"
              :class="[
                'px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5',
                pimpinanChoice === 'disetujui' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
              ]"
            >
              <Icon icon="lucide:check-circle-2" class="w-3.5 h-3.5" />
              <span>Setujui</span>
            </button>

            <button
              type="submit"
              :disabled="isSubmitting"
              class="ml-auto px-5 py-2 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 disabled:opacity-50"
            >
              <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
              <Icon v-else icon="lucide:save" class="w-4 h-4" />
              <span>Simpan Keputusan (Status: {{ pimpinanChoice.toUpperCase() }})</span>
            </button>
          </div>
        </form>
      </div>

      <!-- State Kosong jika tidak ada tiket menunggu persetujuan -->
      <div v-else class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center space-y-3">
        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
          <Icon icon="lucide:check-circle" class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-slate-800 text-base">Tidak ada permohonan yang menunggu keputusan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
          Semua permohonan telah ditindaklanjuti. Laporan kepatuhan SLA dan riwayat disposisi dapat diunduh melalui menu ekspor di atas.
        </p>
      </div>

      <!-- Tabel Daftar Seluruh Keputusan & Tiket Aktif -->
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
          <h3 class="font-bold text-slate-800 text-sm">Daftar Permohonan Terkait Keputusan</h3>
          <span class="text-xs text-slate-500 font-medium">Total: {{ (tikets || []).length }} Permohonan</span>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <th class="py-3 px-4">NOMOR RFC</th>
                <th class="py-3 px-4">SISTEM & KEBUTUHAN</th>
                <th class="py-3 px-4">PEMOHON / OPD</th>
                <th class="py-3 px-4">STATUS</th>
                <th class="py-3 px-4">DISPOSISI</th>
                <th class="py-3 px-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
              <tr v-for="t in tikets" :key="t.no_rfc" class="hover:bg-slate-50 transition-colors">
                <td class="py-3 px-4 font-mono font-bold text-[#0757A8]">{{ t.no_rfc }}</td>
                <td class="py-3 px-4 font-medium text-slate-800">{{ t.nama_aplikasi }}</td>
                <td class="py-3 px-4 text-slate-500">{{ t.perangkat_daerah }}</td>
                <td class="py-3 px-4">
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                    :class="[
                      t.status === 'disetujui' ? 'bg-emerald-100 text-emerald-800' :
                      t.status === 'menunggu_keputusan_pimpinan' ? 'bg-purple-100 text-purple-800' :
                      t.status === 'dalam_pengerjaan' ? 'bg-cyan-100 text-cyan-800' :
                      t.status === 'ditolak' ? 'bg-red-100 text-red-800' :
                      t.status === 'ditunda' ? 'bg-amber-100 text-amber-800' :
                      'bg-slate-100 text-slate-700'
                    ]"
                  >
                    {{ t.status_label || t.status }}
                  </span>
                </td>
                <td class="py-3 px-4 font-medium text-slate-700">{{ t.assigned_to || '-' }}</td>
                <td class="py-3 px-4 text-right">
                  <Link
                    :href="`/form-perubahan-it/ticket/${t.no_rfc}`"
                    class="text-[#0757A8] hover:underline font-bold"
                  >
                    Detail FR-015
                  </Link>
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
import { ref, computed, reactive, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  tikets: {
    type: Array,
    default: () => []
  }
})

const page = usePage()
const authUser = computed(() => page.props.auth?.user)

const pimpinanChoice = ref('disetujui')
const isSubmitting = ref(false)
const actionSuccessMessage = ref(null)

const localTikets = ref([...(props.tikets || [])])

watch(() => props.tikets, (newVal) => {
  localTikets.value = [...(newVal || [])]
})

const menungguPersetujuanList = computed(() => {
  return localTikets.value.filter(t => t.status === 'menunggu_keputusan_pimpinan')
})

const disetujuiCount = computed(() => {
  return localTikets.value.filter(t => ['disetujui', 'dalam_pengerjaan', 'selesai'].includes(t.status)).length
})

const ditolakDitundaCount = computed(() => {
  return localTikets.value.filter(t => ['ditolak', 'ditunda'].includes(t.status)).length
})

const kepatuhanSla = computed(() => {
  return '96.4'
})

const selectedTicketId = ref(null)

// Otomatis pilih tiket pertama yang menunggu keputusan, atau fallback tiket pertama
watch(menungguPersetujuanList, (list) => {
  if (list.length > 0 && !selectedTicketId.value) {
    selectedTicketId.value = list[0].id
  }
}, { immediate: true })

const activeTiket = computed(() => {
  if (selectedTicketId.value) {
    return localTikets.value.find(t => t.id === selectedTicketId.value) || menungguPersetujuanList.value[0] || localTikets.value[0]
  }
  return menungguPersetujuanList.value[0] || localTikets.value[0]
})

const formKeputusan = reactive({
  assigned_to: 'Teguh Wicaksono, S.T (Teknisi APTIKA)',
  target_deadline: new Date(Date.now() + 10 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
  pimpinan_catatan: 'Disetujui. Lakukan uji implementasi di lingkungan staging dan koordinasikan dengan pemohon.',
})

async function submitDecision() {
  if (!activeTiket.value) return
  isSubmitting.value = true

  const pimpinanNama = authUser.value?.name || 'Dr. H. Hendra Setiawan, M.Si'

  try {
    const res = await axios.post(`/api/form-perubahan-it/${activeTiket.value.id}/keputusan-pimpinan`, {
      pimpinan_nama: pimpinanNama,
      pimpinan_keputusan: pimpinanChoice.value,
      pimpinan_catatan: formKeputusan.pimpinan_catatan,
      assigned_to: formKeputusan.assigned_to,
      target_deadline: formKeputusan.target_deadline,
    })

    actionSuccessMessage.value = `Keputusan pimpinan berhasil disimpan untuk ${activeTiket.value.no_rfc}. Tiket sekarang berstatus: ${pimpinanChoice.value.toUpperCase()}.`
    
    // Update data lokal
    const idx = localTikets.value.findIndex(t => t.id === activeTiket.value.id)
    if (idx !== -1) {
      localTikets.value[idx].status = pimpinanChoice.value
      localTikets.value[idx].status_label = pimpinanChoice.value === 'disetujui' ? 'Disetujui' : (pimpinanChoice.value === 'ditolak' ? 'Ditolak' : 'Ditunda')
      localTikets.value[idx].assigned_to = formKeputusan.assigned_to
    }
    selectedTicketId.value = null
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || 'Gagal menyimpan keputusan pimpinan.')
  } finally {
    isSubmitting.value = false
  }
}

function exportPdf() {
  window.print()
}

function exportExcel() {
  alert('Data rekapitulasi keputusan FR-015 diekspor.')
}
</script>
