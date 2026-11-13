<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- ----------------------------------------------------------------------
           VIEW: DRAF
      ---------------------------------------------------------------------- -->
      <div v-if="activeView === 'draf'" class="space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[13px] text-[#64748B] mb-1 flex items-center gap-1.5">
              <span>Pemohon</span><span>/</span>
              <span class="text-[#0757A8] font-semibold">Draf</span>
            </div>
            <h1 class="text-2xl sm:text-[32px] font-bold text-[#0F172A] tracking-tight">Draf permohonan</h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-1">Lanjutkan pengisian FR-015. Draf belum dikirim ke verifikator.</p>
          </div>
          <Link href="/rfc/baru" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs shadow-sm transition-all">
            <Icon icon="lucide:plus" class="w-4 h-4" />
            <span>Buat permohonan baru</span>
          </Link>
        </div>

        <!-- Info Banner -->
        <div class="flex items-start gap-3 p-4 rounded-xl bg-[#FFFBEB] border border-[#FDE68A]">
          <Icon icon="lucide:info" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" />
          <p class="text-xs text-[#92400E] leading-relaxed">
            <strong>Tersimpan sebagai draf</strong> � Nomor RFC diterbitkan setelah seluruh isian wajib dan tanda tangan pemohon dikirim ke verifikator.
          </p>
        </div>

        <!-- Draf dari database -->
        <div v-if="drafList.length > 0" class="space-y-4">
          <div v-for="d in drafList" :key="d.id" class="bg-white rounded-2xl border border-[#DFE7F1] shadow-xs p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#FEF3C7] text-[#92400E] text-[10px] font-black uppercase tracking-wider mb-3">
                  DRAF
                </span>
                <h3 class="text-lg font-bold text-[#0F172A] leading-snug">
                  {{ d.nama_aplikasi || d.deskripsi_singkat || 'Permohonan belum diberi judul' }}
                </h3>
                <p class="text-xs text-[#64748B] mt-0.5 line-clamp-2">{{ d.deskripsi_perubahan || '-' }}</p>
                <div class="flex items-center gap-1.5 mt-3 text-xs text-[#64748B]">
                  <Icon icon="lucide:layers" class="w-3.5 h-3.5 text-[#0757A8]" />
                  <span class="font-semibold text-[#0F172A]">{{ d.langkah_terisi }} dari 7 seksi</span>
                  <span class="text-[#CBD5E1]">�</span>
                  <span>{{ d.seksi_terakhir }}</span>
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] text-[#94A3B8]">
                  <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
                  <span>Terakhir disimpan {{ d.updated_at }}</span>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0">
                <Link
                  :href="`/rfc/${d.id}/edit`"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold shadow-xs transition-all"
                >
                  <Icon icon="lucide:pencil" class="w-3.5 h-3.5" />
                  <span>Lanjutkan pengisian</span>
                </Link>
                <button
                  type="button"
                  @click="bukaModalAjukan(d)"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-all"
                >
                  <Icon icon="lucide:send" class="w-3.5 h-3.5" />
                  <span>Ajukan sekarang</span>
                </button>
                <button
                  type="button"
                  @click="confirmHapusDraf(d)"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-50 text-xs font-bold transition-all"
                >
                  <Icon icon="lucide:trash-2" class="w-3.5 h-3.5" />
                  <span>Hapus</span>
                </button>
              </div>
            </div>

            <!-- Progress bar -->
            <div class="mt-4">
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-[#64748B]">Progres pengisian</span>
                <span class="text-[11px] font-bold text-[#0757A8]">{{ d.persen }}%</span>
              </div>
              <div class="h-1.5 w-full bg-[#E2E8F0] rounded-full overflow-hidden">
                <div class="h-full bg-[#0757A8] rounded-full transition-all duration-500" :style="`width:${d.persen}%`"></div>
              </div>
              <div class="flex items-center justify-between mt-2">
                <span v-for="(step, i) in seksiLabels" :key="i"
                  class="text-[9px] sm:text-[10px]"
                  :class="i < d.langkah_terisi ? 'text-[#0757A8] font-bold' : 'text-[#CBD5E1]'"
                >{{ step }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Kosong -->
        <div v-else class="bg-white rounded-2xl border border-[#DFE7F1] shadow-xs p-12 text-center">
          <Icon icon="lucide:file-plus" class="w-10 h-10 text-slate-300 mx-auto mb-3" />
          <p class="text-sm font-semibold text-slate-500 mb-1">Belum ada draf tersimpan</p>
          <p class="text-xs text-slate-400 mb-4">Mulai pengisian formulir FR-015 dan simpan sebagai draf kapan saja.</p>
          <Link href="/rfc/baru" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0757A8] text-white text-xs font-bold hover:bg-[#063D78] transition-all">
            <Icon icon="lucide:plus" class="w-4 h-4" />
            <span>Buat permohonan baru</span>
          </Link>
        </div>
      </div>

      <!-- ----------------------------------------------------------------------
           VIEW: PERMOHONAN SAYA
      ---------------------------------------------------------------------- -->
      <div v-else-if="activeView === 'permohonan'" class="space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[13px] text-[#64748B] mb-1 flex items-center gap-1.5">
              <span>Pemohon</span><span>/</span>
              <span class="text-[#0757A8] font-semibold">Permohonan saya</span>
            </div>
            <h1 class="text-2xl sm:text-[32px] font-bold text-[#0F172A] tracking-tight">Semua permohonan</h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-1">Cari, saring, dan pantau perjalanan perubahan layanan TI Anda.</p>
          </div>
          <Link href="/rfc/baru" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs shadow-sm transition-all">
            <Icon icon="lucide:plus" class="w-4 h-4" />
            <span>Ajukan RFC baru</span>
          </Link>
        </div>

        <!-- Kapsul filter status -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
          <button v-for="tab in tabStatus" :key="tab.value" type="button"
            @click="activeStatusFilter = tab.value"
            :class="['h-[42px] px-3.5 rounded-xl flex items-center justify-center text-[13px] transition-all',
              activeStatusFilter === tab.value
                ? 'bg-[#EAF2FD] text-[#0757A8] font-bold shadow-xs'
                : 'bg-white text-[#64748B] border border-[#DFE7F1] hover:bg-slate-50']"
          >{{ tab.label }} � {{ tab.count }}</button>
        </div>

        <!-- Filter bar -->
        <div class="bg-white rounded-xl border border-[#DFE7F1] p-4 shadow-xs">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-5">
              <label class="block text-xs font-bold text-[#0F172A] mb-1">Pencarian</label>
              <input v-model="searchKeyword" type="text" placeholder="Cari kode RFC atau nama sistem"
                class="w-full h-9 px-3 text-xs border border-[#DFE7F1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] placeholder:text-[#94A3B8]" />
            </div>
            <div class="md:col-span-3">
              <label class="block text-xs font-bold text-[#0F172A] mb-1">Status</label>
              <select v-model="selectedStatus" class="w-full h-9 px-3 text-xs border border-[#DFE7F1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
                <option value="">Semua status</option>
                <option value="diajukan">Menunggu Verifikasi</option>
                <option value="perlu_revisi">Perlu Revisi</option>
                <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                <option value="selesai">Selesai</option>
              </select>
            </div>
            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-[#0F172A] mb-1">Urgensi</label>
              <select v-model="selectedUrgensi" class="w-full h-9 px-3 text-xs border border-[#DFE7F1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
                <option value="">Semua</option>
                <option value="kritis">Kritis</option>
                <option value="tinggi">Tinggi</option>
                <option value="sedang">Sedang</option>
                <option value="rendah">Rendah</option>
              </select>
            </div>
            <div class="md:col-span-2">
              <button type="button" @click="resetFilter"
                class="w-full h-9 flex items-center justify-center gap-1.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50 transition-all">
                <Icon icon="lucide:x" class="w-3.5 h-3.5" />
                Reset
              </button>
            </div>
          </div>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-xl border border-[#DFE7F1] shadow-xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-[#F8FAFC] border-b border-slate-200 text-[10px] font-bold text-[#64748B] uppercase tracking-wider">
                  <th class="py-3 px-5">KODE RFC / TANGGAL</th>
                  <th class="py-3 px-5">DESKRIPSI PERUBAHAN</th>
                  <th class="py-3 px-5">URGENSI</th>
                  <th class="py-3 px-5">STATUS</th>
                  <th class="py-3 px-5 text-right">AKSI</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs">
                <tr v-for="(t, idx) in filteredTikets" :key="t.id ?? idx" class="hover:bg-slate-50 transition-colors">
                  <td class="py-3.5 px-5">
                    <div class="font-bold text-[#0757A8]">{{ t.kode_pelacakan }}</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5">{{ t.created_at_human }}</div>
                  </td>
                  <td class="py-3.5 px-5 max-w-xs">
                    <div class="font-semibold text-slate-800 truncate">{{ t.nama_aplikasi || 'Permohonan RFC' }}</div>
                    <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ t.deskripsi }}</div>
                  </td>
                  <td class="py-3.5 px-5">
                    <span :class="['px-2 py-0.5 rounded-md text-[10px] font-bold', urgensiKelas(t.urgensi)]">{{ t.urgensi_label }}</span>
                  </td>
                  <td class="py-3.5 px-5">
                    <span :class="['px-2.5 py-1 rounded-full text-[10px] font-bold', statusKelas(t.status_terkini)]">{{ t.status_label }}</span>
                  </td>
                  <td class="py-3.5 px-5 text-right">
                    <Link v-if="t.status_terkini === 'perlu_revisi'" :href="`/rfc/${t.id}/edit`"
                      class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:underline">
                      <span>Perbaiki</span>
                      <Icon icon="lucide:chevron-right" class="w-3.5 h-3.5" />
                    </Link>
                    <Link v-else :href="`/rfc/${t.kode_pelacakan}`"
                      class="inline-flex items-center gap-1 text-xs font-bold text-[#0757A8] hover:underline">
                      <span>Detail</span>
                      <Icon icon="lucide:chevron-right" class="w-3.5 h-3.5" />
                    </Link>
                  </td>
                </tr>
                <tr v-if="filteredTikets.length === 0">
                  <td colspan="5" class="py-10 text-center text-slate-400">
                    <Icon icon="lucide:search-x" class="w-6 h-6 mx-auto mb-2 text-slate-300" />
                    <p>Tidak ada permohonan sesuai filter.</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-500">
            Menampilkan {{ filteredTikets.length }} dari {{ rfcSaya.length }} permohonan
          </div>
        </div>
      </div>

      <!-- ----------------------------------------------------------------------
           VIEW: RINGKASAN (DEFAULT)
      ---------------------------------------------------------------------- -->
      <div v-else class="space-y-6">

        <!-- Header ringkasan -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Portal Layanan SI-RFC</div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Dashboard Pemohon</h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">Kelola pengajuan perubahan layanan TI dan pantau posisi penelaahan secara langsung.</p>
          </div>
          <div class="flex items-center gap-2.5">
            <button type="button" @click="activeView = 'permohonan'"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
              <Icon icon="lucide:list" class="w-4 h-4 text-[#0757A8]" />
              <span>Lihat Permohonan Saya</span>
            </button>
            <Link href="/rfc/baru"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs shadow-sm transition-all">
              <Icon icon="lucide:plus" class="w-4 h-4" />
              <span>Ajukan RFC Baru</span>
            </Link>
          </div>
        </div>

        <!-- 4 Stat Card -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Permohonan</span>
              <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <Icon icon="lucide:file-text" class="w-4 h-4 text-[#0757A8]" />
              </div>
            </div>
            <div class="text-3xl font-black text-[#0F172A]">{{ totalCount }}</div>
            <div class="text-[11px] text-slate-500 mt-1">{{ countDraf }} draf tersimpan</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Menunggu Verifikasi</span>
              <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <Icon icon="lucide:clock" class="w-4 h-4 text-amber-600" />
              </div>
            </div>
            <div class="text-3xl font-black text-amber-600">{{ menungguCount }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Dalam antrean telaah</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Perlu Tindakan</span>
              <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                <Icon icon="lucide:alert-circle" class="w-4 h-4 text-red-600" />
              </div>
            </div>
            <div class="text-3xl font-black text-red-600">{{ revisiCount }}</div>
            <div class="text-[11px] text-red-600 mt-1">Membutuhkan revisi</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Selesai BAST</span>
              <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                <Icon icon="lucide:check-circle-2" class="w-4 h-4 text-emerald-600" />
              </div>
            </div>
            <div class="text-3xl font-black text-emerald-600">{{ selesaiCount }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Tingkat selesai {{ persentaseSelesai }}%</div>
          </div>
        </div>

        <!-- Tabel Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
          <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div>
              <h2 class="text-base font-bold text-[#0F172A]">Permohonan terbaru</h2>
              <p class="text-xs text-[#64748B] mt-0.5">Pelacakan status secara langsung</p>
            </div>
            <button type="button" @click="activeView = 'permohonan'"
              class="text-xs font-bold text-[#0757A8] hover:underline flex items-center gap-1">
              <span>Lihat semua {{ totalCount }} permohonan</span>
              <Icon icon="lucide:arrow-right" class="w-4 h-4" />
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-[#F8FAFC] border-b border-slate-200 text-[10px] font-bold text-[#64748B] uppercase tracking-wider">
                  <th class="py-3 px-5">KODE RFC</th>
                  <th class="py-3 px-5">DESKRIPSI</th>
                  <th class="py-3 px-5">URGENSI</th>
                  <th class="py-3 px-5">STATUS</th>
                  <th class="py-3 px-5 text-right">AKSI</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs">
                <tr v-for="t in rfcSaya.slice(0, 5)" :key="t.id" class="hover:bg-slate-50 transition-colors">
                  <td class="py-3.5 px-5">
                    <div class="font-bold text-[#0757A8]">{{ t.kode_pelacakan }}</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">{{ t.created_at_human }}</div>
                  </td>
                  <td class="py-3.5 px-5 max-w-xs">
                    <div class="font-semibold text-slate-800 truncate">{{ t.nama_aplikasi || 'Permohonan RFC' }}</div>
                    <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ t.deskripsi }}</div>
                  </td>
                  <td class="py-3.5 px-5">
                    <span :class="['px-2 py-0.5 rounded-md text-[10px] font-bold', urgensiKelas(t.urgensi)]">{{ t.urgensi_label }}</span>
                  </td>
                  <td class="py-3.5 px-5">
                    <span :class="['px-2.5 py-1 rounded-full text-[10px] font-bold', statusKelas(t.status_terkini)]">{{ t.status_label }}</span>
                  </td>
                  <td class="py-3.5 px-5 text-right">
                    <Link :href="`/rfc/${t.kode_pelacakan}`"
                      class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-slate-400 hover:text-[#0757A8] hover:bg-blue-50 transition-colors">
                      <Icon icon="lucide:chevron-right" class="w-4 h-4" />
                    </Link>
                  </td>
                </tr>
                <tr v-if="rfcSaya.length === 0">
                  <td colspan="5" class="py-10 text-center text-slate-400">
                    <Icon icon="lucide:file-plus" class="w-7 h-7 mx-auto mb-2 text-slate-300" />
                    <p class="mb-3">Belum ada permohonan RFC yang diajukan.</p>
                    <Link href="/rfc/baru" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0757A8] text-white text-xs font-bold hover:bg-[#063D78] transition-all">
                      <Icon icon="lucide:plus" class="w-3.5 h-3.5" />
                      <span>Ajukan RFC Pertama Anda</span>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal Konfirmasi Hapus Draf -->
    <KonfirmasiModal
      :show="showHapusDrafModal"
      judul="Hapus draf?"
      pesan="Draf yang dihapus tidak dapat dikembalikan. Lanjutkan?"
      label-konfirmasi="Ya, Hapus"
      tipe-konfirmasi="danger"
      @konfirmasi="hapusDraf"
      @batal="showHapusDrafModal = false"
    />

    <!-- Modal Konfirmasi Ajukan -->
    <KonfirmasiModal
      :show="showModalAjukan"
      judul="Ajukan permohonan ini?"
      pesan="Permohonan akan dikirim ke verifikator dan tidak dapat diedit kembali setelah diajukan."
      label-konfirmasi="Ya, Ajukan"
      tipe-konfirmasi="success"
      @konfirmasi="ajukanDraf"
      @batal="showModalAjukan = false"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import AppLayout      from '@/Layouts/AppLayout.vue'
import KonfirmasiModal from '@/Components/KonfirmasiModal.vue'
import axios          from 'axios'

const props = defineProps({
  rfc:         { type: Array,  default: () => [] },   // semua RFC milik user
  countDraf:   { type: Number, default: 0 },
  countAktif:  { type: Number, default: 0 },
  countSelesai:{ type: Number, default: 0 },
  initialView: { type: String, default: 'ringkasan' },
})

const page        = usePage()
const activeView  = ref(props.initialView || 'ringkasan')

// -- Data dari backend ---------------------------------------------------------
const rfcSaya = computed(() => props.rfc ?? [])

const drafList = computed(() =>
  rfcSaya.value
    .filter(r => r.status_terkini === 'draf')
    .map(r => ({
      ...r,
      langkah_terisi: hitungLangkah(r),
      seksi_terakhir: labelSeksiTerakhir(r),
      persen:         Math.round((hitungLangkah(r) / 7) * 100),
    }))
)

const seksiLabels = ['Pemohon', 'Jenis', 'Data', 'Risiko', 'Teknis', 'Aplikasi', 'Lampiran']

function hitungLangkah(r) {
  let n = 0
  if (r.unit_kerja || r.nomor_kontak) n++          // Seksi 1
  if (r.jenis_perubahan?.length)      n++          // Seksi 2
  if (r.deskripsi_perubahan)          n++          // Seksi 3
  if (r.risiko_tidak_dilakukan || r.urgensi !== 'sedang') n++ // Seksi 4
  if (r.solusi_diharapkan || r.rencana_pemulihan) n++         // Seksi 5
  if (r.nama_aplikasi)                n++          // Seksi 6
  if (r.path_lampiran?.length || r.tanda_tangan_pemohon) n++  // Seksi 7
  return Math.max(n, 1)
}

function labelSeksiTerakhir(r) {
  const labels = ['Informasi Pemohon','Jenis Perubahan','Data Perubahan','Analisis Risiko','Teknis & Solusi','Identitas Aplikasi','Lampiran & TTD']
  return labels[Math.min(hitungLangkah(r), 7) - 1] ?? 'Belum ada isian'
}

// -- Statistik -----------------------------------------------------------------
const totalCount  = computed(() => rfcSaya.value.filter(r => r.status_terkini !== 'draf').length)
const menungguCount = computed(() => rfcSaya.value.filter(r => r.status_terkini === 'diajukan').length)
const revisiCount = computed(() => rfcSaya.value.filter(r => r.status_terkini === 'perlu_revisi').length)
const selesaiCount= computed(() => rfcSaya.value.filter(r => r.status_terkini === 'selesai').length)
const persentaseSelesai = computed(() => totalCount.value > 0 ? Math.round((selesaiCount.value / totalCount.value) * 100) : 0)

// -- Filter permohonan ---------------------------------------------------------
const activeStatusFilter = ref('semua')
const searchKeyword      = ref('')
const selectedStatus     = ref('')
const selectedUrgensi    = ref('')

const tabStatus = computed(() => [
  { value: 'semua',         label: 'Semua',              count: totalCount.value },
  { value: 'diajukan',      label: 'Menunggu Verifikasi', count: menungguCount.value },
  { value: 'perlu_revisi',  label: 'Perlu Revisi',        count: revisiCount.value },
  { value: 'selesai',       label: 'Selesai',             count: selesaiCount.value },
])

const rfcTanpaDraf = computed(() => rfcSaya.value.filter(r => r.status_terkini !== 'draf'))

const filteredTikets = computed(() => {
  return rfcTanpaDraf.value.filter(t => {
    if (activeStatusFilter.value !== 'semua' && t.status_terkini !== activeStatusFilter.value) return false
    if (selectedStatus.value && t.status_terkini !== selectedStatus.value) return false
    if (selectedUrgensi.value && t.urgensi !== selectedUrgensi.value) return false
    if (searchKeyword.value.trim()) {
      const q = searchKeyword.value.toLowerCase()
      if (!t.kode_pelacakan?.toLowerCase().includes(q) &&
          !t.deskripsi?.toLowerCase().includes(q) &&
          !t.nama_aplikasi?.toLowerCase().includes(q)) return false
    }
    return true
  })
})

function resetFilter() {
  activeStatusFilter.value = 'semua'
  searchKeyword.value = ''
  selectedStatus.value = ''
  selectedUrgensi.value = ''
}

// -- Style helpers -------------------------------------------------------------
function urgensiKelas(u) {
  return { rendah: 'bg-green-50 text-green-700', sedang: 'bg-yellow-50 text-yellow-700', tinggi: 'bg-orange-50 text-orange-700', kritis: 'bg-red-50 text-red-700' }[u] ?? 'bg-slate-100 text-slate-600'
}
function statusKelas(s) {
  return {
    draf:                 'bg-slate-100 text-slate-600',
    diajukan:             'bg-blue-50 text-blue-700',
    perlu_revisi:         'bg-amber-50 text-amber-700',
    ditolak_verifikator:  'bg-red-50 text-red-700',
    menunggu_persetujuan: 'bg-purple-50 text-purple-700',
    ditolak_pimpinan:     'bg-red-50 text-red-700',
    ditunda:              'bg-yellow-50 text-yellow-700',
    disetujui:            'bg-green-50 text-green-700',
    sedang_dikerjakan:    'bg-cyan-50 text-cyan-700',
    selesai:              'bg-emerald-50 text-emerald-700',
  }[s] ?? 'bg-slate-100 text-slate-600'
}

// -- Modal hapus draf ----------------------------------------------------------
const showHapusDrafModal = ref(false)
const targetDraf         = ref(null)

function confirmHapusDraf(d) {
  targetDraf.value = d
  showHapusDrafModal.value = true
}

async function hapusDraf() {
  if (!targetDraf.value) return
  showHapusDrafModal.value = false
  try {
    await axios.delete(`/rfc/${targetDraf.value.id}/draf`)
    router.reload({ preserveScroll: true })
  } catch (e) {
    alert('Gagal menghapus draf.')
  }
}

// -- Modal ajukan draf ---------------------------------------------------------
const showModalAjukan = ref(false)
const targetAjukan    = ref(null)

function bukaModalAjukan(d) {
  targetAjukan.value = d
  showModalAjukan.value = true
}

async function ajukanDraf() {
  if (!targetAjukan.value) return
  showModalAjukan.value = false
  try {
    await axios.patch(`/rfc/${targetAjukan.value.id}/ajukan`)
    router.reload({ preserveScroll: true })
  } catch (e) {
    alert(e.response?.data?.message ?? 'Gagal mengajukan draf.')
  }
}

// -- Sync view dari URL --------------------------------------------------------
function syncView() {
  if (typeof window === 'undefined') return
  const v = new URLSearchParams(window.location.search).get('view')
  if (v === 'permohonan') activeView.value = 'permohonan'
  else if (v === 'draf')  activeView.value = 'draf'
  else if (v === 'ringkasan') activeView.value = 'ringkasan'
}

watch(() => page.url, syncView)
onMounted(syncView)
</script>