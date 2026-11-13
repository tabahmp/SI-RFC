<template>
  <AppLayout>
    <div class="max-w-[1200px] mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Master Data</div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Master OPD</h1>
          <p class="text-xs text-[#64748B] mt-0.5">Kelola daftar Organisasi Perangkat Daerah yang terdaftar di sistem.</p>
        </div>
        <button
          @click="showForm = !showForm"
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0757A8] text-white text-xs font-bold rounded-xl hover:bg-[#063D78] transition-colors shadow-sm"
        >
          <Icon icon="lucide:plus" class="w-4 h-4" />
          Tambah OPD
        </button>
      </div>

      <!-- Form Tambah -->
      <div v-if="showForm" class="bg-white rounded-2xl border border-[#0757A8]/20 p-6 shadow-xs">
        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
          <Icon icon="lucide:building-2" class="w-4 h-4 text-[#0757A8]" />
          Tambah OPD Baru
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Kode OPD <span class="text-red-500">*</span></label>
            <input v-model="form.kode_opd" type="text" placeholder="mis. DISKOMINFO" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 uppercase" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama OPD <span class="text-red-500">*</span></label>
            <input v-model="form.nama_opd" type="text" placeholder="Nama lengkap OPD" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Telepon</label>
            <input v-model="form.telepon" type="text" placeholder="022-xxxxxxx" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat</label>
            <input v-model="form.alamat" type="text" placeholder="Alamat kantor" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20" />
          </div>
        </div>
        <div class="flex gap-3 mt-4">
          <button @click="simpanOpd" :disabled="saving" class="px-5 py-2 bg-[#0757A8] text-white text-xs font-bold rounded-lg hover:bg-[#063D78] transition-colors disabled:opacity-50">
            {{ saving ? 'Menyimpan...' : 'Simpan OPD' }}
          </button>
          <button @click="showForm = false; resetForm()" class="px-5 py-2 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
        </div>
        <p v-if="errorMsg" class="mt-2 text-xs text-red-600">{{ errorMsg }}</p>
      </div>

      <!-- Tabel OPD -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Kode</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Nama OPD</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Telepon</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Status</th>
                <th class="text-right px-5 py-3 text-slate-500 font-semibold">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="item in opd?.data" :key="item.id" class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3 font-mono font-bold text-slate-700">{{ item.kode_opd }}</td>
                <td class="px-5 py-3 text-slate-700">{{ item.nama_opd }}</td>
                <td class="px-5 py-3 text-slate-500">{{ item.telepon ?? '-' }}</td>
                <td class="px-5 py-3">
                  <span :class="['px-2.5 py-1 rounded-lg text-[10px] font-bold', item.aktif ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-500']">
                    {{ item.aktif ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-5 py-3">
                  <div class="flex justify-end gap-2">
                    <button @click="toggleAktif(item)" :class="['px-3 py-1.5 text-[10px] font-bold rounded-lg transition-colors', item.aktif ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-green-50 text-green-700 hover:bg-green-100']">
                      {{ item.aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                  </div>
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
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

const props = defineProps({
  opd: { type: Object, default: () => ({ data: [] }) },
})

const showForm = ref(false)
const saving   = ref(false)
const errorMsg = ref(null)

const form = ref({ kode_opd: '', nama_opd: '', telepon: '', alamat: '' })
function resetForm() { form.value = { kode_opd: '', nama_opd: '', telepon: '', alamat: '' } }

async function simpanOpd() {
  if (!form.value.kode_opd || !form.value.nama_opd) { errorMsg.value = 'Kode dan nama OPD wajib diisi.'; return }
  saving.value = true; errorMsg.value = null
  try {
    await axios.post('/admin/opd', form.value)
    showForm.value = false; resetForm()
    router.reload({ preserveScroll: true })
  } catch (e) {
    errorMsg.value = e.response?.data?.message ?? 'Gagal menyimpan.'
  } finally { saving.value = false }
}

async function toggleAktif(item) {
  try {
    await axios.patch(`/admin/opd/${item.id}/toggle`)
    router.reload({ preserveScroll: true })
  } catch (e) { alert('Gagal mengubah status.') }
}
</script>
