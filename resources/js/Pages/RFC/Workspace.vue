<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Tombol Kembali -->
      <div class="mb-4">
        <button
          type="button"
          @click="kembaliKeDashboard"
          class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0757A8] hover:underline"
        >
          <Icon icon="lucide:arrow-left" class="w-4 h-4" />
          <span>Kembali ke Dashboard</span>
        </button>
      </div>

      <!-- Breadcrumb -->
      <nav class="text-xs text-slate-500 mb-6 flex items-center gap-1">
        <Link href="/dashboard" class="hover:text-[#0757a8]">Dashboard</Link>
        <span>/</span>
        <Link :href="`/form-perubahan-it/ticket/${tiket.no_rfc}`" class="hover:text-[#0757a8] font-mono">{{ tiket.no_rfc }}</Link>
        <span>/</span>
        <span class="text-slate-700 font-medium">Workspace Petugas</span>
      </nav>

      <!-- Alert Notifikasi -->
      <div v-if="alertMessage" :class="['mb-6 p-4 rounded-xl text-sm font-medium flex items-center justify-between', alertType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200']">
        <div class="flex items-center gap-2">
          <Icon :icon="alertType === 'success' ? 'lucide:check-circle' : 'lucide:alert-circle'" class="w-4 h-4 flex-shrink-0" />
          <span>{{ alertMessage }}</span>
        </div>
        <button @click="alertMessage = null" class="text-slate-400 hover:text-slate-600 font-bold ml-4">✕</button>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- SIDEBAR RINGKASAN TIKET (4 Kolom) -->
        <div class="lg:col-span-4 space-y-6">
          <div class="card p-6 sticky top-6">
            <div class="flex items-center justify-between gap-2 mb-3">
              <span class="font-mono text-xl font-black text-[#0757a8]">{{ tiket.no_rfc }}</span>
              <StatusBadge :status="tiket.status" :label="tiket.status_label" />
            </div>

            <div class="mb-4">
              <PrioritasBadge :prioritas="tiket.prioritas" />
              <div v-if="tiket.target_sla" class="text-xs text-amber-700 font-medium mt-1 flex items-center gap-1">
                <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
                <span>Target SLA: {{ tiket.target_sla }}</span>
              </div>
            </div>

            <div class="border-t border-slate-100 pt-4 space-y-3 text-xs">
              <div>
                <span class="text-slate-400 block mb-0.5">Pemohon:</span>
                <span class="font-semibold text-slate-800">{{ tiket.pemohon }}</span>
              </div>
              <div>
                <span class="text-slate-400 block mb-0.5">Perangkat Daerah:</span>
                <span class="font-medium text-slate-700">{{ tiket.perangkat_daerah }}</span>
              </div>
              <div>
                <span class="text-slate-400 block mb-0.5">Kontak / Email:</span>
                <span class="text-slate-700">{{ tiket.nomor_kontak }} · {{ tiket.email_dinas }}</span>
              </div>
              <div>
                <span class="text-slate-400 block mb-0.5">Jenis Perubahan:</span>
                <div class="flex flex-wrap gap-1 mt-1">
                  <span v-for="j in tiket.jenis_perubahan" :key="j" class="bg-blue-50 text-[#0757a8] px-2 py-0.5 rounded text-[10px] font-bold">
                    {{ j }}
                  </span>
                </div>
              </div>
              <div v-if="tiket.assigned_to">
                <span class="text-slate-400 block mb-0.5">Disposisi Teknisi:</span>
                <span class="font-bold text-[#047857]">{{ tiket.assigned_to }}</span>
              </div>
              <div v-if="tiket.target_deadline">
                <span class="text-slate-400 block mb-0.5">Target Penyelesaian:</span>
                <span class="font-bold text-red-600">{{ tiket.target_deadline }}</span>
              </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
              <Link :href="`/form-perubahan-it/ticket/${tiket.no_rfc}`" class="btn-secondary w-full justify-center text-xs flex items-center gap-1.5">
                <Icon icon="lucide:file-text" class="w-4 h-4 text-[#0757A8]" />
                <span>Buka Detail Lengkap FR-015</span>
              </Link>
              <Link :href="`/form-perubahan-it/dokumen/${tiket.no_rfc}`" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-[#0757a8] font-bold text-xs transition-colors">
                <Icon icon="lucide:printer" class="w-4 h-4" />
                <span>Dokumen Cetak FR-015</span>
              </Link>
            </div>
          </div>
        </div>

        <!-- AREA KERJA WORKSPACE TAB (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">
          <!-- NAVIGASI TAB WORKFLOW -->
          <div class="card p-2 flex flex-wrap gap-2">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="['px-4 py-2.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2', activeTab === tab.id ? 'bg-[#0757a8] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100']"
            >
              <Icon :icon="tab.iconName" class="w-4 h-4" />
              <span>{{ tab.name }}</span>
              <span v-if="tab.badge" :class="['w-2 h-2 rounded-full', tab.badgeColor]"></span>
            </button>
          </div>

          <!-- TAB 1: VERIFIKATOR (KOORDINATOR AGEN) -->
          <div v-if="activeTab === 'verifikasi'" class="card p-6 border-t-4 border-purple-600">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-lg font-bold text-slate-800">Tahap 2: Verifikasi Kelayakan Teknis & SLA</h2>
                <p class="text-slate-500 text-xs mt-0.5">Pemeriksaan administratif, penetapan skala prioritas, & rekomendasi ke pimpinan</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-purple-100 text-purple-800">Kotak Otorisasi 2</span>
            </div>

            <!-- Ringkasan Kebutuhan -->
            <div class="bg-slate-50 rounded-xl p-4 mb-6 border border-slate-200 text-xs space-y-2">
              <div><span class="font-bold text-slate-700">Perubahan yang diharapkan:</span> <p class="text-slate-600 mt-0.5">{{ tiket.perubahan_diharapkan }}</p></div>
              <div><span class="font-bold text-slate-700">Latar belakang:</span> <p class="text-slate-600 mt-0.5">{{ tiket.latar_belakang }}</p></div>
              <div><span class="font-bold text-slate-700">Kriteria risiko SMKI:</span> <span class="text-amber-700 font-semibold">{{ (tiket.kriteria_risiko || []).join(', ') || 'Belum diisi' }}</span></div>
            </div>

            <form @submit.prevent="submitVerifikasi" class="space-y-4">
              <div>
                <label class="input-label">Nama Koordinator Agen / Verifikator <span class="text-red-500">*</span></label>
                <input v-model="formVerifikasi.verifikator_nama" type="text" class="input-field" placeholder="Nama Verifikator" required />
              </div>

              <div>
                <label class="input-label">Penetapan Skala Prioritas Resmi (ITIL/SLA) <span class="text-red-500">*</span></label>
                <select v-model="formVerifikasi.prioritas" class="input-field" required>
                  <option value="P1 - Kritis">P1 - Kritis (SLA ≤ 2-4 Jam) - Emergency / System Down</option>
                  <option value="P2 - Tinggi">P2 - Tinggi (SLA ≤ 24 Jam) - Dampak Luas</option>
                  <option value="P3 - Sedang">P3 - Sedang (SLA ≤ 2-3 Hari Kerja) - Fungsional Standar</option>
                  <option value="P4 - Rendah">P4 - Rendah (SLA ≤ 5 Hari Kerja) - Minor / Teks / Tampilan</option>
                </select>
              </div>

              <div>
                <label class="input-label">Catatan Telaah & Rekomendasi</label>
                <textarea v-model="formVerifikasi.verifikator_catatan" class="input-field" rows="3" placeholder="Hasil telaah awal dan catatan kelayakan berkas..."></textarea>
              </div>

              <div>
                <label class="input-label">Keputusan Verifikasi <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <label :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all', formVerifikasi.verifikator_rekomendasi === 'lanjut_pimpinan' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" value="lanjut_pimpinan" v-model="formVerifikasi.verifikator_rekomendasi" class="sr-only" />
                    <Icon icon="lucide:arrow-right-circle" class="w-6 h-6 text-emerald-600 mb-1" />
                    <span class="font-bold text-xs">Lanjut Pimpinan</span>
                    <span class="text-[10px] text-slate-500">Berkas lengkap & layak</span>
                  </label>
                  <label :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all', formVerifikasi.verifikator_rekomendasi === 'perlu_revisi' ? 'border-amber-500 bg-amber-50 text-amber-800' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" value="perlu_revisi" v-model="formVerifikasi.verifikator_rekomendasi" class="sr-only" />
                    <Icon icon="lucide:edit-3" class="w-6 h-6 text-amber-600 mb-1" />
                    <span class="font-bold text-xs">Minta Revisi</span>
                    <span class="text-[10px] text-slate-500">Kembalikan ke pemohon</span>
                  </label>
                  <label :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all', formVerifikasi.verifikator_rekomendasi === 'tolak' ? 'border-red-500 bg-red-50 text-red-800' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" value="tolak" v-model="formVerifikasi.verifikator_rekomendasi" class="sr-only" />
                    <Icon icon="lucide:x-circle" class="w-6 h-6 text-red-600 mb-1" />
                    <span class="font-bold text-xs">Tolak Tiket</span>
                    <span class="text-[10px] text-slate-500">Tidak layak dikerjakan</span>
                  </label>
                </div>
              </div>

              <div v-if="formVerifikasi.verifikator_rekomendasi === 'perlu_revisi'">
                <label class="input-label text-amber-800 font-bold">Catatan Instruksi Perbaikan untuk Pemohon <span class="text-red-500">*</span></label>
                <textarea v-model="formVerifikasi.catatan_revisi" class="input-field border-amber-300" rows="3" placeholder="Sebutkan berkas atau field yang harus diperbaiki oleh pemohon..." required></textarea>
              </div>

              <div>
                <label class="input-label">Tanda Tangan Digital Koordinator Agen</label>
                <SignaturePad v-model="formVerifikasi.tanda_tangan_file" />
              </div>

              <div class="flex justify-end pt-3">
                <button type="submit" :disabled="loading" class="btn-primary">
                  {{ loading ? 'Menyimpan...' : 'Simpan Keputusan Verifikasi' }}
                </button>
              </div>
            </form>
          </div>

          <!-- TAB 2: KEPUTUSAN PIMPINAN -->
          <div v-if="activeTab === 'pimpinan'" class="card p-6 border-t-4 border-[#0757a8]">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-lg font-bold text-slate-800">Tahap 3: Keputusan Pimpinan (Kabid APTIKA)</h2>
                <p class="text-slate-500 text-xs mt-0.5">Persetujuan strategis, penugasan teknisi pelaksana, & batas target deadline</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-blue-100 text-[#0757a8]">Kotak Otorisasi 3</span>
            </div>

            <form @submit.prevent="submitPimpinan" class="space-y-4">
              <div>
                <label class="input-label">Nama Pejabat Penanggung Jawab / Kabid <span class="text-red-500">*</span></label>
                <input v-model="formPimpinan.pimpinan_nama" type="text" class="input-field" placeholder="Nama Lengkap Pejabat" required />
              </div>

              <div>
                <label class="input-label">Keputusan Persetujuan <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <label :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all', formPimpinan.pimpinan_keputusan === 'disetujui' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" value="disetujui" v-model="formPimpinan.pimpinan_keputusan" class="sr-only" />
                    <Icon icon="lucide:check-circle-2" class="w-6 h-6 text-emerald-600 mb-1" />
                    <span class="font-bold text-xs">Disetujui</span>
                    <span class="text-[10px] text-slate-500">Lanjut disposisi teknisi</span>
                  </label>
                  <label :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all', formPimpinan.pimpinan_keputusan === 'ditunda' ? 'border-amber-500 bg-amber-50 text-amber-800' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" value="ditunda" v-model="formPimpinan.pimpinan_keputusan" class="sr-only" />
                    <Icon icon="lucide:clock" class="w-6 h-6 text-amber-600 mb-1" />
                    <span class="font-bold text-xs">Ditunda</span>
                    <span class="text-[10px] text-slate-500">Jadwalkan periode nanti</span>
                  </label>
                  <label :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all', formPimpinan.pimpinan_keputusan === 'ditolak' ? 'border-red-500 bg-red-50 text-red-800' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" value="ditolak" v-model="formPimpinan.pimpinan_keputusan" class="sr-only" />
                    <Icon icon="lucide:x-circle" class="w-6 h-6 text-red-600 mb-1" />
                    <span class="font-bold text-xs">Ditolak</span>
                    <span class="text-[10px] text-slate-500">Permohonan dibatalkan</span>
                  </label>
                </div>
              </div>

              <div v-if="formPimpinan.pimpinan_keputusan === 'disetujui'" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="input-label">Disposisi Tim / Teknisi Pelaksana <span class="text-red-500">*</span></label>
                  <input v-model="formPimpinan.assigned_to" type="text" class="input-field" placeholder="Contoh: Tim Software APTIKA / Rian Hidayat" required />
                </div>
                <div>
                  <label class="input-label">Target Batas Waktu Penyelesaian <span class="text-red-500">*</span></label>
                  <input v-model="formPimpinan.target_deadline" type="date" class="input-field" required />
                </div>
              </div>

              <div>
                <label class="input-label">Catatan Arahan Pimpinan</label>
                <textarea v-model="formPimpinan.pimpinan_catatan" class="input-field" rows="3" placeholder="Arahan kebijakan atau catatan instruksi khusus..."></textarea>
              </div>

              <div>
                <label class="input-label">Tanda Tangan Digital Pimpinan</label>
                <SignaturePad v-model="formPimpinan.tanda_tangan_file" />
              </div>

              <div class="flex justify-end pt-3">
                <button type="submit" :disabled="loading" class="btn-primary">
                  {{ loading ? 'Menyimpan...' : 'Sahkan Keputusan Pimpinan' }}
                </button>
              </div>
            </form>
          </div>

          <!-- TAB 3: TELAAH TEKNIS & IMPLEMENTASI -->
          <div v-if="activeTab === 'implementasi'" class="card p-6 border-t-4 border-cyan-600">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-lg font-bold text-slate-800">Tahap 4: Telaah Teknis & Eksekusi Pengerjaan</h2>
                <p class="text-slate-500 text-xs mt-0.5">Analisis teknis, rekaman changelog koding, tautan commit repository, & uji fungsi</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-cyan-100 text-cyan-800">Tim Teknisi</span>
            </div>

            <form @submit.prevent="submitTeknis" class="space-y-4">
              <div>
                <label class="input-label">Nama PIC Teknisi Pelaksana <span class="text-red-500">*</span></label>
                <input v-model="formTeknis.teknisi_nama" type="text" class="input-field" placeholder="Nama Software Engineer / DBA / NetOps" required />
              </div>

              <div>
                <label class="input-label">Lembar Telaah Teknis Sistem <span class="text-red-500">*</span></label>
                <textarea v-model="formTeknis.telaah_teknis" class="input-field" rows="4" placeholder="Uraian arsitektur modul yang diubah, dependensi basis data, skema perubahan, dan rencana uji fungsi..." required></textarea>
              </div>

              <div>
                <label class="input-label">Catatan Changelog / Rincian Implementasi</label>
                <textarea v-model="formTeknis.catatan_implementasi" class="input-field" rows="3" placeholder="Rincian fitur yang ditambahkan, bugfix, konfigurasi server yang dieksekusi..."></textarea>
              </div>

              <div>
                <label class="input-label">Tautan Git Repository / Commit Kedinasan</label>
                <input v-model="formTeknis.tautan_perubahan" type="url" class="input-field" placeholder="https://gitlab.jabarprov.go.id/aptika/.../commit/..." />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="input-label">Tanggal Mulai Pengerjaan</label>
                  <input v-model="formTeknis.tgl_mulai_pengerjaan" type="date" class="input-field" />
                </div>
                <div>
                  <label class="input-label">Tanggal Selesai Pengerjaan</label>
                  <input v-model="formTeknis.tgl_selesai_pengerjaan" type="date" class="input-field" />
                </div>
              </div>

              <div>
                <label class="input-label">Pembaruan Status Progres <span class="text-red-500">*</span></label>
                <select v-model="formTeknis.progress_status" class="input-field" required>
                  <option value="dalam_pengerjaan">Dalam Pengerjaan (Koding / Konfigurasi Sedang Berlangsung)</option>
                  <option value="menunggu_bast">Pengerjaan Selesai & Uji Fungsi Sukses (Lanjut Penerbitan BAST)</option>
                </select>
              </div>

              <div class="flex justify-end pt-3">
                <button type="submit" :disabled="loading" class="btn-primary">
                  {{ loading ? 'Menyimpan...' : 'Simpan Log Pengerjaan Teknis' }}
                </button>
              </div>
            </form>
          </div>

          <!-- TAB 4: BAST & PENYELESAIAN -->
          <div v-if="activeTab === 'bast'" class="card p-6 border-t-4 border-emerald-600">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-lg font-bold text-slate-800">Tahap 5: Berita Acara Serah Terima (BAST) & Penutupan Tiket</h2>
                <p class="text-slate-500 text-xs mt-0.5">Penerbitan nomor BAST sah, berkas serah terima, dan tanda tangan Agen Pelaksana</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800">Kotak Otorisasi 4</span>
            </div>

            <form @submit.prevent="submitBast" class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="input-label">Nama Agen Pelaksana / Pejabat Penyerah <span class="text-red-500">*</span></label>
                  <input v-model="formBast.agen_nama" type="text" class="input-field" placeholder="Nama Agen Pelaksana" required />
                </div>
                <div>
                  <label class="input-label">Nomor Registrasi BAST Resmi <span class="text-red-500">*</span></label>
                  <input v-model="formBast.nomor_bast" type="text" class="input-field" placeholder="Contoh: 005/BAST-RFC/APTIKA/2026" required />
                </div>
              </div>

              <div>
                <label class="input-label">Tanggal Penyerahan BAST <span class="text-red-500">*</span></label>
                <input v-model="formBast.tanggal_bast" type="date" class="input-field" required />
              </div>

              <div>
                <label class="input-label">Unggah Dokumen PDF BAST Resmi (Scan Sah)</label>
                <input type="file" @change="e => fileBast = e.target.files[0]" accept=".pdf" class="input-field" />
              </div>

              <div>
                <label class="input-label">Catatan Serah Terima Hasil Layanan</label>
                <textarea v-model="formBast.catatan_bast" class="input-field" rows="3" placeholder="Pernyataan hasil pekerjaan telah diserahkan dan diterima oleh pihak pemohon OPD..."></textarea>
              </div>

              <div>
                <label class="input-label">Tanda Tangan Digital Agen Pelaksana</label>
                <SignaturePad v-model="formBast.tanda_tangan_file" />
              </div>

              <div class="flex justify-end pt-3">
                <button type="submit" :disabled="loading" class="btn-success flex items-center gap-2">
                  <Icon icon="lucide:check-circle" class="w-4 h-4" />
                  <span>{{ loading ? 'Memproses...' : 'Terbitkan BAST & Tutup Tiket RFC Menjadi SELESAI' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import PrioritasBadge from '@/Components/PrioritasBadge.vue'
import SignaturePad from '@/Components/SignaturePad.vue'

const props = defineProps({
  tiket: Object,
  histories: Array,
  opdList: Array,
})

const loading = ref(false)
const alertMessage = ref(null)
const alertType = ref('success')
const fileBast = ref(null)

// Penentuan tab aktif bawaan sesuai URL query param atau status tiket saat ini
const urlParams = typeof window !== 'undefined' ? new URLSearchParams(window.location.search) : null
const requestedTab = urlParams?.get('tab')

const activeTab = ref(
  (requestedTab && ['verifikasi', 'pimpinan', 'implementasi', 'bast'].includes(requestedTab)) ? requestedTab :
  props.tiket.status === 'menunggu_verifikasi' ? 'verifikasi' :
  props.tiket.status === 'menunggu_keputusan_pimpinan' ? 'pimpinan' :
  ['disetujui', 'dalam_pengerjaan'].includes(props.tiket.status) ? 'implementasi' :
  props.tiket.status === 'menunggu_bast' ? 'bast' : 'verifikasi'
)

const tabs = computed(() => [
  { id: 'verifikasi', name: '2. Verifikasi', iconName: 'lucide:shield-check', badge: props.tiket.status === 'menunggu_verifikasi', badgeColor: 'bg-purple-500' },
  { id: 'pimpinan', name: '3. Keputusan Pimpinan', iconName: 'lucide:scale', badge: props.tiket.status === 'menunggu_keputusan_pimpinan', badgeColor: 'bg-blue-500' },
  { id: 'implementasi', name: '4. Telaah & Koding', iconName: 'lucide:code-2', badge: ['disetujui', 'dalam_pengerjaan'].includes(props.tiket.status), badgeColor: 'bg-cyan-500' },
  { id: 'bast', name: '5. BAST & Selesai', iconName: 'lucide:file-check', badge: props.tiket.status === 'menunggu_bast', badgeColor: 'bg-emerald-500' },
])

function kembaliKeDashboard() {
  if (window.history.length > 1) {
    window.history.back()
  } else {
    router.visit('/dashboard')
  }
}

// Form Data per Tahap
const formVerifikasi = reactive({
  verifikator_nama: props.tiket.verifikator_nama || '',
  prioritas: props.tiket.prioritas || 'P3 - Sedang',
  verifikator_catatan: props.tiket.verifikator_catatan || '',
  verifikator_rekomendasi: 'lanjut_pimpinan',
  catatan_revisi: '',
  tanda_tangan_file: null,
})

const formPimpinan = reactive({
  pimpinan_nama: props.tiket.pimpinan_nama || '',
  pimpinan_keputusan: 'disetujui',
  assigned_to: props.tiket.assigned_to || '',
  target_deadline: '',
  pimpinan_catatan: props.tiket.pimpinan_catatan || '',
  tanda_tangan_file: null,
})

const formTeknis = reactive({
  teknisi_nama: props.tiket.teknisi_nama || '',
  telaah_teknis: props.tiket.telaah_teknis || '',
  catatan_implementasi: props.tiket.catatan_implementasi || '',
  tautan_perubahan: props.tiket.tautan_perubahan || '',
  tgl_mulai_pengerjaan: '',
  tgl_selesai_pengerjaan: '',
  progress_status: 'dalam_pengerjaan',
})

const today = new Date().toISOString().split('T')[0]
const formBast = reactive({
  agen_nama: props.tiket.agen_nama || '',
  nomor_bast: props.tiket.nomor_bast || `001/BAST-RFC/APTIKA/${new Date().getFullYear()}`,
  tanggal_bast: today,
  catatan_bast: props.tiket.catatan_bast || '',
  tanda_tangan_file: null,
})

// Submissions
async function submitVerifikasi() {
  loading.value = true
  try {
    await axios.post(`/api/form-perubahan-it/${props.tiket.id}/verifikasi`, formVerifikasi)
    alertMessage.value = 'Data verifikasi dan penetapan skala SLA berhasil disimpan.'
    alertType.value = 'success'
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    alertMessage.value = err.response?.data?.message || 'Gagal menyimpan data verifikasi.'
    alertType.value = 'error'
  } finally {
    loading.value = false
  }
}

async function submitPimpinan() {
  loading.value = true
  try {
    await axios.post(`/api/form-perubahan-it/${props.tiket.id}/keputusan-pimpinan`, formPimpinan)
    alertMessage.value = 'Pengesahan keputusan pimpinan berhasil disimpan.'
    alertType.value = 'success'
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    alertMessage.value = err.response?.data?.message || 'Gagal menyimpan keputusan pimpinan.'
    alertType.value = 'error'
  } finally {
    loading.value = false
  }
}

async function submitTeknis() {
  loading.value = true
  try {
    await axios.post(`/api/form-perubahan-it/${props.tiket.id}/telaah-teknis`, formTeknis)
    alertMessage.value = 'Catatan telaah teknis dan changelog berhasil diperbarui.'
    alertType.value = 'success'
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    alertMessage.value = err.response?.data?.message || 'Gagal menyimpan log teknis.'
    alertType.value = 'error'
  } finally {
    loading.value = false
  }
}

async function submitBast() {
  loading.value = true
  try {
    const fd = new FormData()
    Object.entries(formBast).forEach(([k, v]) => {
      if (v) fd.append(k, v)
    })
    if (fileBast.value) {
      fd.append('file_bast', fileBast.value)
    }

    await axios.post(`/api/form-perubahan-it/${props.tiket.id}/bast`, fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    alertMessage.value = 'BAST resmi berhasil diterbitkan. Tiket ditutup menjadi SELESAI.'
    alertType.value = 'success'
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    alertMessage.value = err.response?.data?.message || 'Gagal menerbitkan BAST.'
    alertType.value = 'error'
  } finally {
    loading.value = false
  }
}
</script>

