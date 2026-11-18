<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Master Data</div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Master Organisasi Perangkat Daerah</h1>
          <p class="text-xs text-[#64748B] mt-0.5">Kelola daftar OPD Pemerintah Provinsi Jawa Barat, status keaktifan, dan kontak kedinasan.</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- Tombol Tambah OPD -->
          <button
            @click="bukaModalTambah"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0757A8] text-white text-xs font-bold rounded-xl hover:bg-[#063D78] transition-all shadow-sm"
          >
            <Icon icon="lucide:plus" class="w-4 h-4" />
            Tambah OPD
          </button>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-xl border border-[#DFE7F1] p-4 flex flex-wrap gap-3 items-end shadow-xs">
        <div>
          <label class="block text-xs font-bold text-[#0F172A] mb-1">Status</label>
          <select v-model="filter.status" class="h-9 text-xs border border-[#DFE7F1] rounded-lg px-3 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>
        <div class="flex-1 min-w-[220px]">
          <label class="block text-xs font-bold text-[#0F172A] mb-1">Cari OPD</label>
          <div class="relative">
            <Icon icon="lucide:search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" />
            <input
              v-model="filter.search"
              @keyup.enter="terapkanFilter"
              type="text"
              placeholder="Cari kode OPD, nama dinas, alamat, atau telepon..."
              class="w-full h-9 pl-8 pr-3 text-xs border border-[#DFE7F1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] placeholder:text-[#94A3B8]"
            />
          </div>
        </div>
        <button @click="terapkanFilter" class="h-9 px-4 bg-[#0757A8] text-white text-xs font-bold rounded-lg hover:bg-[#063D78] transition-colors">
          Terapkan
        </button>
        <button @click="resetFilter" class="h-9 px-4 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
          Reset
        </button>
      </div>

      <!-- Tabel OPD -->
      <div class="bg-white rounded-xl border border-[#DFE7F1] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-[#F8FAFC] border-b border-slate-200 text-[10px] font-bold text-[#64748B] uppercase tracking-wider">
                <th class="py-3 px-5 w-40">KODE OPD</th>
                <th class="py-3 px-5">NAMA PERANGKAT DAERAH & ALAMAT</th>
                <th class="py-3 px-5 w-48">TELEPON / KONTAK</th>
                <th class="py-3 px-5 w-32">STATUS</th>
                <th class="py-3 px-5 text-center w-40">AKSI</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
              <tr v-if="!opd?.data?.length">
                <td colspan="5" class="py-10 text-center text-slate-400">
                  <Icon icon="lucide:search-x" class="w-6 h-6 mx-auto mb-2 text-slate-300" />
                  <p>Tidak ada data Organisasi Perangkat Daerah yang ditemukan.</p>
                </td>
              </tr>
              <tr v-for="item in opd?.data" :key="item.id" class="hover:bg-slate-50 transition-colors">
                <!-- Kode OPD -->
                <td class="py-3.5 px-5 font-semibold text-slate-800">
                  {{ item.kode_opd }}
                </td>

                <!-- Nama OPD & Alamat -->
                <td class="py-3.5 px-5">
                  <div class="font-semibold text-slate-800">{{ item.nama_opd }}</div>
                  <div v-if="item.alamat" class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">
                    {{ item.alamat }}
                  </div>
                </td>

                <!-- Telepon -->
                <td class="py-3.5 px-5 text-slate-500">
                  {{ item.telepon || '-' }}
                </td>

                <!-- Status -->
                <td class="py-3.5 px-5">
                  <span
                    :class="[
                      'px-2.5 py-1 rounded-full text-[10px] font-bold',
                      item.aktif ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600'
                    ]"
                  >
                    {{ item.aktif ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>

                <!-- Aksi -->
                <td class="py-3.5 px-5">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Edit -->
                    <button
                      @click="bukaModalEdit(item)"
                      title="Edit OPD"
                      class="p-1.5 text-slate-500 hover:text-[#0757A8] hover:bg-slate-100 rounded-lg transition-colors"
                    >
                      <Icon icon="lucide:pencil" class="w-3.5 h-3.5" />
                    </button>

                    <!-- Toggle Status (Aktif / Nonaktif) -->
                    <button
                      @click="bukaKonfirmasi(item.aktif ? 'nonaktif' : 'aktif', item)"
                      :title="item.aktif ? 'Nonaktifkan OPD' : 'Aktifkan OPD'"
                      :class="[
                        'px-2.5 py-1 text-[10px] font-bold rounded-lg transition-colors',
                        item.aktif
                          ? 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                          : 'bg-green-50 text-green-700 hover:bg-green-100'
                      ]"
                    >
                      {{ item.aktif ? 'Nonaktif' : 'Aktifkan' }}
                    </button>

                    <!-- Hapus -->
                    <button
                      @click="bukaKonfirmasi('hapus', item)"
                      title="Hapus OPD"
                      class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                    >
                      <Icon icon="lucide:trash-2" class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="opd?.last_page > 1" class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-xs">
          <span class="text-slate-500">
            Menampilkan {{ opd.from }}–{{ opd.to }} dari {{ opd.total }} OPD
          </span>
          <div class="flex gap-1">
            <a
              v-for="link in opd.links"
              :key="link.label"
              :href="link.url"
              v-html="link.label"
              :class="[
                'px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors',
                link.active ? 'bg-[#0757A8] text-white border-[#0757A8]' : 'border-slate-200 text-slate-600 hover:bg-slate-50',
                !link.url ? 'opacity-40 pointer-events-none' : ''
              ]"
            ></a>
          </div>
        </div>
      </div>

    </div>

    <!-- Modal Form Tambah / Edit OPD -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showModalForm"
          class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
          role="dialog"
          aria-modal="true"
        >
          <!-- Backdrop -->
          <div
            class="fixed inset-0 bg-black/50 backdrop-blur-sm"
            @click="tutupModalForm"
          ></div>

          <!-- Modal Box -->
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 z-10 animate-in zoom-in-95 duration-200 my-8">
            <!-- Header Modal -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center shrink-0">
                  <Icon :icon="isEditing ? 'lucide:building' : 'lucide:building-2'" class="w-5 h-5" />
                </div>
                <div>
                  <h3 class="text-base font-bold text-[#0F172A]">
                    {{ isEditing ? 'Edit Data OPD' : 'Tambah OPD Baru' }}
                  </h3>
                  <p class="text-xs text-[#64748B]">
                    {{ isEditing ? 'Perbarui informasi Organisasi Perangkat Daerah' : 'Daftarkan instansi/perangkat daerah baru ke dalam sistem' }}
                  </p>
                </div>
              </div>
              <button
                @click="tutupModalForm"
                class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
              >
                <Icon icon="lucide:x" class="w-4 h-4" />
              </button>
            </div>

            <!-- Error Banner -->
            <div v-if="formErrorGeneral" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-start gap-2">
              <Icon icon="lucide:alert-circle" class="w-4 h-4 shrink-0 mt-0.5 text-red-500" />
              <span>{{ formErrorGeneral }}</span>
            </div>

            <!-- Form Body -->
            <form @submit.prevent="simpanFormOpd" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Kode OPD -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Kode OPD <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="formOpd.kode_opd"
                    type="text"
                    required
                    placeholder="mis. DISKOMINFO"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 uppercase font-mono focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                    :class="formErrors.kode_opd ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                  />
                  <p v-if="formErrors.kode_opd" class="mt-1 text-[11px] text-red-600">{{ formErrors.kode_opd[0] }}</p>
                </div>

                <!-- Telepon -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Nomor Telepon
                  </label>
                  <input
                    v-model="formOpd.telepon"
                    type="text"
                    placeholder="mis. (022) 2502898"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                    :class="formErrors.telepon ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                  />
                  <p v-if="formErrors.telepon" class="mt-1 text-[11px] text-red-600">{{ formErrors.telepon[0] }}</p>
                </div>

                <!-- Nama OPD -->
                <div class="md:col-span-2">
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Nama Perangkat Daerah <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="formOpd.nama_opd"
                    type="text"
                    required
                    placeholder="mis. Dinas Komunikasi dan Informatika"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                    :class="formErrors.nama_opd ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                  />
                  <p v-if="formErrors.nama_opd" class="mt-1 text-[11px] text-red-600">{{ formErrors.nama_opd[0] }}</p>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Alamat Kantor
                  </label>
                  <textarea
                    v-model="formOpd.alamat"
                    rows="3"
                    placeholder="mis. Jl. Tamansari No. 55, Bandung, Jawa Barat"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors resize-none"
                    :class="formErrors.alamat ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                  ></textarea>
                  <p v-if="formErrors.alamat" class="mt-1 text-[11px] text-red-600">{{ formErrors.alamat[0] }}</p>
                </div>

                <!-- Status Aktif (ketika edit) -->
                <div class="md:col-span-2">
                  <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input
                      v-model="formOpd.aktif"
                      type="checkbox"
                      class="w-4 h-4 text-[#0757A8] rounded border-slate-300 focus:ring-[#0757A8]"
                    />
                    <span class="text-xs font-semibold text-slate-700">OPD Aktif</span>
                    <span class="text-[11px] text-slate-400">(Dapat dipilih pemohon saat pendaftaran & pengajuan RFC)</span>
                  </label>
                </div>

              </div>

              <!-- Footer Modal -->
              <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                <button
                  type="button"
                  @click="tutupModalForm"
                  class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  :disabled="formSaving"
                  class="px-6 py-2.5 bg-[#0757A8] text-white text-xs font-bold rounded-xl hover:bg-[#063D78] transition-colors disabled:opacity-50 flex items-center gap-2 shadow-sm"
                >
                  <Icon v-if="formSaving" icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
                  <span>{{ formSaving ? 'Menyimpan...' : (isEditing ? 'Perbarui OPD' : 'Simpan OPD') }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Modal Konfirmasi Tindakan -->
    <KonfirmasiModal
      :show="modal.show"
      :judul="modal.judul"
      :pesan="modal.pesan"
      :label-konfirmasi="modal.labelKonfirmasi"
      :tipe-konfirmasi="modal.tipe"
      @konfirmasi="prosesAksi"
      @batal="modal.show = false"
    />

    <!-- Toast Feedback -->
    <Teleport to="body">
      <Transition name="toast-fade">
        <div
          v-if="toast.show"
          class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 px-4 py-3 bg-[#0F172A] text-white text-xs font-medium rounded-xl shadow-xl border border-slate-700/50 animate-in slide-in-from-bottom-2 duration-200"
        >
          <Icon icon="lucide:check-circle-2" class="w-4 h-4 text-green-400 shrink-0" />
          <span>{{ toast.pesan }}</span>
        </div>
      </Transition>
    </Teleport>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import KonfirmasiModal from '@/Components/KonfirmasiModal.vue'
import axios from 'axios'

const props = defineProps({
  opd: { type: Object, default: () => ({ data: [], last_page: 1 }) },
  filterAktif: { type: Object, default: () => ({}) },
  statistik: { type: Object, default: () => ({ total: 0, aktif: 0, nonaktif: 0 }) },
})

const filter = ref({
  status: props.filterAktif?.status ?? '',
  search: props.filterAktif?.search ?? '',
})

// ── Filter ───────────────────────────────────────────────────────────────────
function terapkanFilter() {
  router.get('/admin/opd', filter.value, { preserveState: true, replace: true })
}
function resetFilter() {
  filter.value = { status: '', search: '' }
  router.get('/admin/opd', {}, { preserveState: true, replace: true })
}

// ── Toast ────────────────────────────────────────────────────────────────────
const toast = ref({ show: false, pesan: '' })
let toastTimer = null
function tampilToast(pesan) {
  clearTimeout(toastTimer)
  toast.value = { show: true, pesan }
  toastTimer = setTimeout(() => {
    toast.value.show = false
  }, 3500)
}

// ── Modal Form Tambah / Edit ─────────────────────────────────────────────────
const showModalForm = ref(false)
const isEditing = ref(false)
const formSaving = ref(false)
const formErrors = ref({})
const formErrorGeneral = ref('')

const formOpd = ref({
  id: null,
  kode_opd: '',
  nama_opd: '',
  telepon: '',
  alamat: '',
  aktif: true,
})

function bukaModalTambah() {
  isEditing.value = false
  formErrors.value = {}
  formErrorGeneral.value = ''
  formOpd.value = {
    id: null,
    kode_opd: '',
    nama_opd: '',
    telepon: '',
    alamat: '',
    aktif: true,
  }
  showModalForm.value = true
}

function bukaModalEdit(item) {
  isEditing.value = true
  formErrors.value = {}
  formErrorGeneral.value = ''
  formOpd.value = {
    id: item.id,
    kode_opd: item.kode_opd || '',
    nama_opd: item.nama_opd || '',
    telepon: item.telepon || '',
    alamat: item.alamat || '',
    aktif: Boolean(item.aktif),
  }
  showModalForm.value = true
}

function tutupModalForm() {
  showModalForm.value = false
  formErrors.value = {}
  formErrorGeneral.value = ''
}

async function simpanFormOpd() {
  if (formSaving.value) return
  formSaving.value = true
  formErrors.value = {}
  formErrorGeneral.value = ''

  try {
    if (isEditing.value) {
      const res = await axios.patch(`/admin/opd/${formOpd.value.id}`, formOpd.value)
      tutupModalForm()
      tampilToast(res.data?.message || 'Data OPD berhasil diperbarui.')
    } else {
      const res = await axios.post('/admin/opd', formOpd.value)
      tutupModalForm()
      tampilToast(res.data?.message || 'OPD baru berhasil ditambahkan.')
    }
    router.reload({ preserveScroll: true })
  } catch (error) {
    if (error.response?.status === 422) {
      formErrors.value = error.response.data?.errors || {}
      formErrorGeneral.value = error.response.data?.message || 'Terdapat kesalahan pada input formulir.'
    } else {
      formErrorGeneral.value = error.response?.data?.message || 'Terjadi kesalahan sistem. Silakan coba lagi.'
    }
  } finally {
    formSaving.value = false
  }
}

// ── Modal Konfirmasi Tindakan ────────────────────────────────────────────────
const modal = ref({ show: false, tipe: 'danger', judul: '', pesan: '', labelKonfirmasi: '', aksi: '', item: null })

function bukaKonfirmasi(aksi, item) {
  const cfg = {
    aktif: {
      tipe: 'success',
      judul: 'Aktifkan OPD',
      pesan: `Aktifkan status ${item.nama_opd} (${item.kode_opd})? Instansi ini akan kembali muncul pada pilihan formulir.`,
      label: 'Ya, Aktifkan',
    },
    nonaktif: {
      tipe: 'warning',
      judul: 'Nonaktifkan OPD',
      pesan: `Nonaktifkan status ${item.nama_opd} (${item.kode_opd})? Instansi ini tidak akan dapat dipilih pada pendaftaran atau RFC baru.`,
      label: 'Ya, Nonaktifkan',
    },
    hapus: {
      tipe: 'danger',
      judul: 'Hapus Organisasi Perangkat Daerah',
      pesan: `Apakah Anda yakin ingin menghapus OPD ${item.nama_opd} (${item.kode_opd})? Tindakan ini tidak dapat dibatalkan.`,
      label: 'Ya, Hapus OPD',
    },
  }[aksi]

  modal.value = {
    show: true,
    aksi,
    item,
    tipe: cfg.tipe,
    judul: cfg.judul,
    pesan: cfg.pesan,
    labelKonfirmasi: cfg.label,
  }
}

const loadingAksi = ref(false)
async function prosesAksi() {
  if (loadingAksi.value) return
  loadingAksi.value = true
  const { aksi, item } = modal.value
  modal.value.show = false

  try {
    if (aksi === 'hapus') {
      const res = await axios.delete(`/admin/opd/${item.id}`)
      tampilToast(res.data?.message || 'OPD berhasil dihapus.')
    } else {
      const res = await axios.patch(`/admin/opd/${item.id}/toggle`)
      tampilToast(res.data?.message || 'Status OPD berhasil diubah.')
    }
    router.reload({ preserveScroll: true })
  } catch (e) {
    alert(e.response?.data?.message || 'Terjadi kesalahan saat memproses aksi.')
  } finally {
    loadingAksi.value = false
  }
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to    { opacity: 0; }

.toast-fade-enter-active,
.toast-fade-leave-active { transition: all 0.25s ease; }
.toast-fade-enter-from,
.toast-fade-leave-to    { opacity: 0; transform: translateY(10px); }
</style>
