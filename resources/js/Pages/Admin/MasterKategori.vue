<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Master Data</div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Master Kategori RFC</h1>
          <p class="text-xs text-[#64748B] mt-0.5">Kelola klasifikasi domain perubahan TI (aplikasi, infrastruktur, jaringan, keamanan informasi, dll).</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- Stat badge info -->
          <div class="hidden sm:flex items-center gap-3 px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            <span class="text-slate-500">Total: <strong class="text-slate-800">{{ statistik?.total ?? kategori?.total ?? 0 }}</strong></span>
            <span class="text-slate-300">|</span>
            <span class="text-green-600 font-semibold">Aktif: {{ statistik?.aktif ?? '-' }}</span>
            <span class="text-slate-300">|</span>
            <span class="text-slate-500">Nonaktif: {{ statistik?.nonaktif ?? '-' }}</span>
          </div>

          <!-- Tombol Tambah Kategori -->
          <button
            @click="bukaModalTambah"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0757A8] text-white text-xs font-bold rounded-xl hover:bg-[#063D78] transition-all shadow-sm active:scale-95"
          >
            <Icon icon="lucide:plus" class="w-4 h-4" />
            Tambah Kategori
          </button>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap gap-3 items-end shadow-xs">
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Status</label>
          <select v-model="filter.status" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>
        <div class="flex-1 min-w-[220px]">
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Cari Kategori</label>
          <div class="relative">
            <Icon icon="lucide:search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" />
            <input
              v-model="filter.search"
              @keyup.enter="terapkanFilter"
              type="text"
              placeholder="Cari nama kategori atau kata kunci deskripsi..."
              class="w-full pl-8 pr-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20"
            />
          </div>
        </div>
        <button @click="terapkanFilter" class="px-4 py-2 bg-[#0757A8] text-white text-xs font-bold rounded-lg hover:bg-[#063D78] transition-colors">
          Terapkan
        </button>
        <button @click="resetFilter" class="px-4 py-2 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
          Reset
        </button>
      </div>

      <!-- Tabel Kategori -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold w-56">Nama Kategori</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Deskripsi Domain Perubahan</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold w-32">Status</th>
                <th class="text-center px-5 py-3 text-slate-500 font-semibold w-40">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="!kategori?.data?.length">
                <td colspan="4" class="text-center py-12 text-slate-400">
                  <Icon icon="lucide:layers" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                  <p>Tidak ada kategori RFC yang ditemukan</p>
                </td>
              </tr>
              <tr v-for="item in kategori?.data" :key="item.id" class="hover:bg-slate-50 transition-colors">
                <!-- Nama Kategori -->
                <td class="px-5 py-3">
                  <div class="font-bold text-slate-800 text-[12px] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0757A8]"></span>
                    <span>{{ item.nama_kategori }}</span>
                  </div>
                </td>

                <!-- Deskripsi -->
                <td class="px-5 py-3 text-slate-600">
                  <div class="line-clamp-2 text-[11px] leading-relaxed">
                    {{ item.deskripsi || '-' }}
                  </div>
                </td>

                <!-- Status -->
                <td class="px-5 py-3">
                  <span
                    :class="[
                      'px-2.5 py-1 rounded-lg text-[10px] font-bold inline-flex items-center gap-1',
                      item.aktif ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-600 border border-slate-200'
                    ]"
                  >
                    <span
                      class="w-1.5 h-1.5 rounded-full"
                      :class="item.aktif ? 'bg-green-500' : 'bg-slate-400'"
                    ></span>
                    {{ item.aktif ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>

                <!-- Aksi -->
                <td class="px-5 py-3">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Edit -->
                    <button
                      @click="bukaModalEdit(item)"
                      title="Edit Kategori"
                      class="p-1.5 text-slate-600 hover:text-[#0757A8] hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200"
                    >
                      <Icon icon="lucide:pencil" class="w-3.5 h-3.5" />
                    </button>

                    <!-- Toggle Status (Aktif / Nonaktif) -->
                    <button
                      @click="bukaKonfirmasi(item.aktif ? 'nonaktif' : 'aktif', item)"
                      :title="item.aktif ? 'Nonaktifkan Kategori' : 'Aktifkan Kategori'"
                      :class="[
                        'px-2.5 py-1 text-[10px] font-bold rounded-lg transition-colors flex items-center gap-1 border',
                        item.aktif
                          ? 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-slate-200'
                          : 'bg-green-600 text-white hover:bg-green-700 border-green-600'
                      ]"
                    >
                      <Icon :icon="item.aktif ? 'lucide:ban' : 'lucide:check'" class="w-3 h-3" />
                      {{ item.aktif ? 'Nonaktif' : 'Aktifkan' }}
                    </button>

                    <!-- Hapus -->
                    <button
                      @click="bukaKonfirmasi('hapus', item)"
                      title="Hapus Kategori"
                      class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200"
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
        <div v-if="kategori?.last_page > 1" class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-xs">
          <span class="text-slate-500">
            Menampilkan {{ kategori.from }}–{{ kategori.to }} dari {{ kategori.total }} kategori
          </span>
          <div class="flex gap-1">
            <a
              v-for="link in kategori.links"
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

    <!-- Modal Form Tambah / Edit Kategori -->
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
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10 animate-in zoom-in-95 duration-200 my-8">
            <!-- Header Modal -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center shrink-0">
                  <Icon :icon="isEditing ? 'lucide:file-edit' : 'lucide:folder-plus'" class="w-5 h-5" />
                </div>
                <div>
                  <h3 class="text-base font-bold text-[#0F172A]">
                    {{ isEditing ? 'Edit Kategori RFC' : 'Tambah Kategori RFC Baru' }}
                  </h3>
                  <p class="text-xs text-[#64748B]">
                    {{ isEditing ? 'Perbarui informasi dan deskripsi domain kategori perubahan TI' : 'Daftarkan nama kategori perubahan sistem yang baru' }}
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
            <form @submit.prevent="simpanFormKategori" class="space-y-4">
              <!-- Nama Kategori -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Nama Kategori RFC <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="formKategori.nama_kategori"
                  type="text"
                  required
                  placeholder="mis. Jaringan & Komunikasi Data"
                  class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                  :class="formErrors.nama_kategori ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                />
                <p v-if="formErrors.nama_kategori" class="mt-1 text-[11px] text-red-600">{{ formErrors.nama_kategori[0] }}</p>
              </div>

              <!-- Deskripsi -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Deskripsi Kategori
                </label>
                <textarea
                  v-model="formKategori.deskripsi"
                  rows="3"
                  placeholder="Penjelasan cakupan domain perubahan teknologi ini..."
                  class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors resize-none"
                  :class="formErrors.deskripsi ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                ></textarea>
                <p v-if="formErrors.deskripsi" class="mt-1 text-[11px] text-red-600">{{ formErrors.deskripsi[0] }}</p>
              </div>

              <!-- Status Aktif (ketika edit) -->
              <div>
                <label class="flex items-center gap-2 cursor-pointer select-none">
                  <input
                    v-model="formKategori.aktif"
                    type="checkbox"
                    class="w-4 h-4 text-[#0757A8] rounded border-slate-300 focus:ring-[#0757A8]"
                  />
                  <span class="text-xs font-semibold text-slate-700">Kategori Aktif</span>
                  <span class="text-[11px] text-slate-400">(Dapat dipilih saat mengajukan permohonan RFC)</span>
                </label>
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
                  <span>{{ formSaving ? 'Menyimpan...' : (isEditing ? 'Perbarui Kategori' : 'Simpan Kategori') }}</span>
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
  kategori: { type: Object, default: () => ({ data: [], last_page: 1 }) },
  filterAktif: { type: Object, default: () => ({}) },
  statistik: { type: Object, default: () => ({ total: 0, aktif: 0, nonaktif: 0 }) },
})

const filter = ref({
  status: props.filterAktif?.status ?? '',
  search: props.filterAktif?.search ?? '',
})

// ── Filter ───────────────────────────────────────────────────────────────────
function terapkanFilter() {
  router.get('/admin/kategori', filter.value, { preserveState: true, replace: true })
}
function resetFilter() {
  filter.value = { status: '', search: '' }
  router.get('/admin/kategori', {}, { preserveState: true, replace: true })
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

const formKategori = ref({
  id: null,
  nama_kategori: '',
  deskripsi: '',
  aktif: true,
})

function bukaModalTambah() {
  isEditing.value = false
  formErrors.value = {}
  formErrorGeneral.value = ''
  formKategori.value = {
    id: null,
    nama_kategori: '',
    deskripsi: '',
    aktif: true,
  }
  showModalForm.value = true
}

function bukaModalEdit(item) {
  isEditing.value = true
  formErrors.value = {}
  formErrorGeneral.value = ''
  formKategori.value = {
    id: item.id,
    nama_kategori: item.nama_kategori || '',
    deskripsi: item.deskripsi || '',
    aktif: Boolean(item.aktif),
  }
  showModalForm.value = true
}

function tutupModalForm() {
  showModalForm.value = false
  formErrors.value = {}
  formErrorGeneral.value = ''
}

async function simpanFormKategori() {
  if (formSaving.value) return
  formSaving.value = true
  formErrors.value = {}
  formErrorGeneral.value = ''

  try {
    if (isEditing.value) {
      const res = await axios.patch(`/admin/kategori/${formKategori.value.id}`, formKategori.value)
      tutupModalForm()
      tampilToast(res.data?.message || 'Data kategori RFC berhasil diperbarui.')
    } else {
      const res = await axios.post('/admin/kategori', formKategori.value)
      tutupModalForm()
      tampilToast(res.data?.message || 'Kategori RFC baru berhasil ditambahkan.')
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
      judul: 'Aktifkan Kategori RFC',
      pesan: `Aktifkan kategori "${item.nama_kategori}"? Kategori ini akan kembali muncul pada pilihan permohonan RFC.`,
      label: 'Ya, Aktifkan',
    },
    nonaktif: {
      tipe: 'warning',
      judul: 'Nonaktifkan Kategori RFC',
      pesan: `Nonaktifkan kategori "${item.nama_kategori}"? Kategori ini tidak akan dapat dipilih pada formulir RFC baru.`,
      label: 'Ya, Nonaktifkan',
    },
    hapus: {
      tipe: 'danger',
      judul: 'Hapus Kategori RFC',
      pesan: `Apakah Anda yakin ingin menghapus kategori "${item.nama_kategori}"? Tindakan ini tidak dapat dibatalkan.`,
      label: 'Ya, Hapus Kategori',
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
      const res = await axios.delete(`/admin/kategori/${item.id}`)
      tampilToast(res.data?.message || 'Kategori RFC berhasil dihapus.')
    } else {
      const res = await axios.patch(`/admin/kategori/${item.id}/toggle`)
      tampilToast(res.data?.message || 'Status kategori berhasil diubah.')
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
