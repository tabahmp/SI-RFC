<template>
  <AppLayout role="teknisi">
    <div class="max-w-[1440px] mx-auto space-y-6">
      
      <!-- HEADER DASHBOARD TEKNISI (PERSIS FIGMA 1:658) -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
            Ruang kerja implementasi
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
            Tugas Aktif Saya
          </h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
            Dokumentasikan implementasi dan pastikan setiap perubahan dapat diaudit.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <span class="px-3 py-1.5 rounded-lg bg-cyan-50 text-cyan-800 text-xs font-bold border border-cyan-200 flex items-center gap-1.5">
            <Icon icon="lucide:terminal" class="w-4 h-4" />
            <span>Tim Pengembang & Integrasi</span>
          </span>
        </div>
      </div>

      <!-- Alert Notifikasi Tindakan Teknisi -->
      <div v-if="actionMessage" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
        <div class="flex items-center gap-2">
          <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
          <span>{{ actionMessage }}</span>
        </div>
        <button @click="actionMessage = null" class="text-emerald-700 hover:text-emerald-900 font-bold ml-2">✕</button>
      </div>

      <!-- 4 KARTU METRIK TEKNISI (PERSIS FIGMA) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tugas Aktif</div>
          <div class="text-3xl font-black text-[#0F172A] mt-2">{{ tugasAktifList.length }}</div>
          <div class="text-[11px] text-amber-600 mt-1 font-semibold">{{ tugasPrioritasTinggiCount }} prioritas tinggi</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-blue-600 uppercase tracking-wide">Dalam Pengerjaan</div>
          <div class="text-3xl font-black text-blue-600 mt-2">{{ dalamPengerjaanCount }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Dikerjakan minggu ini</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Selesai</div>
          <div class="text-3xl font-black text-emerald-600 mt-2">{{ selesaiCount }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Dengan dokumen BAST sah</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
          <div class="text-xs font-bold text-purple-600 uppercase tracking-wide">BAST Siap Validasi</div>
          <div class="text-3xl font-black text-purple-600 mt-2">{{ menungguBastCount }}</div>
          <div class="text-[11px] text-slate-500 mt-1">Menunggu penandatanganan</div>
        </div>
      </div>

      <!-- LEMBAR TUGAS AKTIF TEKNISI (PERSIS FIGMA) -->
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
            <div class="text-xs text-slate-500 mt-0.5">Sistem: {{ activeTiket.nama_aplikasi }} • Target selesai: {{ activeTiket.target_deadline || 'Sesuai SLA' }}</div>
          </div>

          <div class="flex items-center gap-2">
            <!-- Pilihan Tiket jika ada lebih dari 1 tugas -->
            <select
              v-if="tugasAktifList.length > 1"
              v-model="selectedTicketId"
              class="text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700"
            >
              <option v-for="t in tugasAktifList" :key="t.id" :value="t.id">
                {{ t.no_rfc }} — {{ t.nama_aplikasi }}
              </option>
            </select>

            <button
              v-if="activeTiket.status !== 'dalam_pengerjaan'"
              @click="mulaiPengerjaan"
              :disabled="isSubmitting"
              class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5 disabled:opacity-50"
            >
              <Icon icon="lucide:play" class="w-3.5 h-3.5" />
              <span>Mulai Pengerjaan</span>
            </button>
            <span v-else class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200 flex items-center gap-1.5">
              <Icon icon="lucide:loader" class="w-3.5 h-3.5 animate-spin" />
              <span>Sedang Dikerjakan</span>
            </span>
          </div>
        </div>

        <!-- Metadata Teknis Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
            <div class="text-[11px] text-slate-500 font-medium">Lingkungan Target</div>
            <div class="text-sm font-bold text-[#0F172A] mt-0.5">Staging & Produksi</div>
          </div>
          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
            <div class="text-[11px] text-slate-500 font-medium">Tim Pelaksana</div>
            <div class="text-sm font-bold text-[#0F172A] mt-0.5">{{ activeTiket.assigned_to || 'Tim Integrasi TI' }}</div>
          </div>
          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
            <div class="text-[11px] text-slate-500 font-medium">Status Pekerjaan</div>
            <div class="text-sm font-bold text-[#0757A8] mt-0.5 capitalize">{{ activeTiket.status_label || activeTiket.status }}</div>
          </div>
        </div>

        <!-- Box Catatan Implementasi & Catatan Teknis -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/80 space-y-1">
            <div class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
              <Icon icon="lucide:info" class="w-4 h-4 text-amber-700" />
              <span>Arahan pimpinan & mitigasi risiko</span>
            </div>
            <p class="text-xs text-amber-900/90 leading-relaxed">
              Lakukan pengujian koneksi dan skenario rollback sebelum deployment ke server produksi.
            </p>
          </div>

          <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
            <div class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
              <Icon icon="lucide:file-check" class="w-4 h-4 text-emerald-600" />
              <span>Catatan teknis penyelesaian</span>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
              Tiket ditutup setelah dokumen BAST (Berita Acara Serah Terima) sah dan ditandatangani para pihak terkait.
            </p>
          </div>
        </div>

        <!-- Form Input Bukti Teknis & Penyelesaian -->
        <form @submit.prevent="submitImplementation" class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Tautan changelog / commit / pull request</label>
            <input
              v-model="formTeknis.tautan_perubahan"
              type="url"
              placeholder="https://gitlab.jabarprov.go.id/.../commit/..."
              class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-mono focus:outline-none focus:ring-1 focus:ring-[#0757A8]"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Ringkasan implementasi & hasil uji <span class="text-red-500">*</span></label>
            <textarea
              v-model="formTeknis.telaah_teknis"
              rows="3"
              required
              placeholder="Jelaskan perubahan yang telah diterapkan, modul yang diuji, dan hasil verifikasi stabilitas sistem..."
              class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#0757A8]"
            ></textarea>
          </div>

          <div class="flex flex-wrap items-center gap-3 pt-1">
            <button
              type="button"
              @click="simpanProgress"
              :disabled="isSubmitting"
              class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold flex items-center gap-1.5 transition-colors disabled:opacity-50"
            >
              <Icon icon="lucide:save" class="w-4 h-4 text-blue-600" />
              <span>Simpan Catatan Progress</span>
            </button>
            
            <button
              type="submit"
              :disabled="isSubmitting"
              class="ml-auto px-5 py-2 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 disabled:opacity-50"
            >
              <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
              <Icon v-else icon="lucide:check-circle" class="w-4 h-4" />
              <span>Selesaikan Implementasi & Ajukan BAST</span>
            </button>
          </div>
        </form>
      </div>

      <!-- State Kosong jika tidak ada tugas aktif -->
      <div v-else class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center space-y-3">
        <div class="w-12 h-12 rounded-full bg-blue-50 text-[#0757A8] flex items-center justify-center mx-auto">
          <Icon icon="lucide:check-circle" class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-slate-800 text-base">Tidak ada tugas aktif yang sedang berjalan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
          Semua permohonan yang disetujui telah selesai diimplementasikan atau menunggu persetujuan pimpinan.
        </p>
      </div>

      <!-- Tabel Seluruh Tugas Teknisi -->
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
          <h3 class="font-bold text-slate-800 text-sm">Daftar Seluruh Penugasan Perubahan TI</h3>
          <span class="text-xs text-slate-500 font-medium">Total: {{ (tikets || []).length }} Tiket</span>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <th class="py-3 px-4">NOMOR RFC</th>
                <th class="py-3 px-4">SISTEM & KEBUTUHAN</th>
                <th class="py-3 px-4">PRIORITAS</th>
                <th class="py-3 px-4">STATUS</th>
                <th class="py-3 px-4">TARGET SELESAI</th>
                <th class="py-3 px-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
              <tr v-for="t in tikets" :key="t.no_rfc" class="hover:bg-slate-50 transition-colors">
                <td class="py-3 px-4 font-mono font-bold text-[#0757A8]">{{ t.no_rfc }}</td>
                <td class="py-3 px-4 font-medium text-slate-800">{{ t.nama_aplikasi }}</td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#0757A8]">
                    {{ t.prioritas }}
                  </span>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                    :class="[
                      t.status === 'selesai' ? 'bg-emerald-100 text-emerald-800' :
                      t.status === 'dalam_pengerjaan' ? 'bg-cyan-100 text-cyan-800' :
                      t.status === 'menunggu_bast' ? 'bg-indigo-100 text-indigo-800' :
                      t.status === 'disetujui' ? 'bg-purple-100 text-purple-800' :
                      'bg-slate-100 text-slate-700'
                    ]"
                  >
                    {{ t.status_label || t.status }}
                  </span>
                </td>
                <td class="py-3 px-4 font-medium text-slate-600">{{ t.target_deadline || '-' }}</td>
                <td class="py-3 px-4 text-right">
                  <Link
                    :href="`/form-perubahan-it/pengerjaan/${t.no_rfc}?tab=implementasi`"
                    class="text-[#0757A8] hover:underline font-bold"
                  >
                    Buka Workspace
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
import { Link, usePage } from '@inertiajs/vue3'
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

const isSubmitting = ref(false)
const actionMessage = ref(null)

const localTikets = ref([...(props.tikets || [])])

watch(() => props.tikets, (newVal) => {
  localTikets.value = [...(newVal || [])]
})

const tugasAktifList = computed(() => {
  return localTikets.value.filter(t => ['disetujui', 'dalam_pengerjaan', 'menunggu_bast'].includes(t.status))
})

const dalamPengerjaanCount = computed(() => {
  return localTikets.value.filter(t => t.status === 'dalam_pengerjaan').length
})

const selesaiCount = computed(() => {
  return localTikets.value.filter(t => t.status === 'selesai').length
})

const menungguBastCount = computed(() => {
  return localTikets.value.filter(t => t.status === 'menunggu_bast').length
})

const tugasPrioritasTinggiCount = computed(() => {
  return tugasAktifList.value.filter(t => t.prioritas?.includes('P1') || t.prioritas?.includes('P2')).length
})

const selectedTicketId = ref(null)

watch(tugasAktifList, (list) => {
  if (list.length > 0 && !selectedTicketId.value) {
    selectedTicketId.value = list[0].id
  }
}, { immediate: true })

const activeTiket = computed(() => {
  if (selectedTicketId.value) {
    return localTikets.value.find(t => t.id === selectedTicketId.value) || tugasAktifList.value[0] || localTikets.value[0]
  }
  return tugasAktifList.value[0] || localTikets.value[0]
})

const formTeknis = reactive({
  tautan_perubahan: 'https://gitlab.jabarprov.go.id/dpmptsp/perizinan-core/commit/7a8f9c1',
  telaah_teknis: 'Konfigurasi endpoint sandbox BSrE telah disiapkan dan webhook staging aktif. Pengujian beban pada 100 concurrent requests menghasilkan latency rata-rata 180ms.',
})

async function mulaiPengerjaan() {
  if (!activeTiket.value) return
  isSubmitting.value = true
  const teknisiNama = authUser.value?.name || 'Teguh Wicaksono, S.T'

  try {
    const res = await axios.post(`/api/form-perubahan-it/${activeTiket.value.id}/telaah-teknis`, {
      teknisi_nama: teknisiNama,
      telaah_teknis: formTeknis.telaah_teknis || 'Pengerjaan dimulai',
      catatan_implementasi: 'Pengerjaan modul di lingkungan staging',
      tautan_perubahan: formTeknis.tautan_perubahan || null,
      tgl_mulai_pengerjaan: new Date().toISOString().split('T')[0],
      progress_status: 'dalam_pengerjaan',
    })

    actionMessage.value = `Pengerjaan tiket ${activeTiket.value.no_rfc} berhasil dimulai.`
    const idx = localTikets.value.findIndex(t => t.id === activeTiket.value.id)
    if (idx !== -1) {
      localTikets.value[idx].status = 'dalam_pengerjaan'
      localTikets.value[idx].status_label = 'Dalam Pengerjaan'
    }
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || 'Gagal memulai pengerjaan tiket.')
  } finally {
    isSubmitting.value = false
  }
}

async function simpanProgress() {
  if (!activeTiket.value) return
  isSubmitting.value = true
  const teknisiNama = authUser.value?.name || 'Teguh Wicaksono, S.T'

  try {
    const res = await axios.post(`/api/form-perubahan-it/${activeTiket.value.id}/telaah-teknis`, {
      teknisi_nama: teknisiNama,
      telaah_teknis: formTeknis.telaah_teknis,
      tautan_perubahan: formTeknis.tautan_perubahan || null,
      progress_status: activeTiket.value.status === 'disetujui' ? 'dalam_pengerjaan' : activeTiket.value.status,
    })

    actionMessage.value = `Catatan telaah teknis untuk ${activeTiket.value.no_rfc} berhasil disimpan.`
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || 'Gagal menyimpan catatan progres.')
  } finally {
    isSubmitting.value = false
  }
}

async function submitImplementation() {
  if (!activeTiket.value) return
  isSubmitting.value = true
  const teknisiNama = authUser.value?.name || 'Teguh Wicaksono, S.T'

  try {
    const res = await axios.post(`/api/form-perubahan-it/${activeTiket.value.id}/telaah-teknis`, {
      teknisi_nama: teknisiNama,
      telaah_teknis: formTeknis.telaah_teknis,
      tautan_perubahan: formTeknis.tautan_perubahan || null,
      tgl_selesai_pengerjaan: new Date().toISOString().split('T')[0],
      progress_status: 'menunggu_bast',
    })

    actionMessage.value = `Implementasi ${activeTiket.value.no_rfc} dinyatakan selesai. Tiket dialihkan ke tahap penerbitan BAST.`
    const idx = localTikets.value.findIndex(t => t.id === activeTiket.value.id)
    if (idx !== -1) {
      localTikets.value[idx].status = 'menunggu_bast'
      localTikets.value[idx].status_label = 'Menunggu BAST'
    }
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || 'Gagal menyelesaikan implementasi.')
  } finally {
    isSubmitting.value = false
  }
}
</script>
