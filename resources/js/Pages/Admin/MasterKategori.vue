<template>
  <AppLayout>
    <div class="max-w-[900px] mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Master Data</div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Master Kategori RFC</h1>
          <p class="text-xs text-[#64748B] mt-0.5">Kelola kategori domain teknis perubahan TI.</p>
        </div>
        <button
          @click="showForm = !showForm"
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0757A8] text-white text-xs font-bold rounded-xl hover:bg-[#063D78] transition-colors shadow-sm"
        >
          <Icon icon="lucide:plus" class="w-4 h-4" />
          Tambah Kategori
        </button>
      </div>

      <!-- Form Tambah -->
      <div v-if="showForm" class="bg-white rounded-2xl border border-[#0757A8]/20 p-6 shadow-xs">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Tambah Kategori Baru</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
            <input v-model="form.nama_kategori" type="text" placeholder="mis. Jaringan, Server, Aplikasi..." class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi</label>
            <textarea v-model="form.deskripsi" rows="2" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 resize-none" placeholder="Deskripsi singkat kategori..."></textarea>
          </div>
        </div>
        <div class="flex gap-3 mt-4">
          <button @click="simpanKategori" :disabled="saving" class="px-5 py-2 bg-[#0757A8] text-white text-xs font-bold rounded-lg hover:bg-[#063D78] transition-colors disabled:opacity-50">
            {{ saving ? 'Menyimpan...' : 'Simpan' }}
          </button>
          <button @click="showForm = false; resetForm()" class="px-5 py-2 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
        </div>
        <p v-if="errorMsg" class="mt-2 text-xs text-red-600">{{ errorMsg }}</p>
      </div>

      <!-- Tabel -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-xs">
          <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
              <th class="text-left px-5 py-3 text-slate-500 font-semibold">Nama Kategori</th>
              <th class="text-left px-5 py-3 text-slate-500 font-semibold">Deskripsi</th>
              <th class="text-left px-5 py-3 text-slate-500 font-semibold">Status</th>
              <th class="text-right px-5 py-3 text-slate-500 font-semibold">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="item in kategori?.data" :key="item.id" class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3 font-semibold text-slate-800">{{ item.nama_kategori }}</td>
              <td class="px-5 py-3 text-slate-500 max-w-xs">{{ item.deskripsi ?? '-' }}</td>
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
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

const props = defineProps({
  kategori: { type: Object, default: () => ({ data: [] }) },
})

const showForm = ref(false)
const saving   = ref(false)
const errorMsg = ref(null)
const form = ref({ nama_kategori: '', deskripsi: '' })
function resetForm() { form.value = { nama_kategori: '', deskripsi: '' } }

async function simpanKategori() {
  if (!form.value.nama_kategori) { errorMsg.value = 'Nama kategori wajib diisi.'; return }
  saving.value = true; errorMsg.value = null
  try {
    await axios.post('/admin/kategori', form.value)
    showForm.value = false; resetForm()
    router.reload({ preserveScroll: true })
  } catch (e) {
    errorMsg.value = e.response?.data?.message ?? 'Gagal menyimpan.'
  } finally { saving.value = false }
}

async function toggleAktif(item) {
  try {
    await axios.patch(`/admin/kategori/${item.id}`, { aktif: !item.aktif, nama_kategori: item.nama_kategori })
    router.reload({ preserveScroll: true })
  } catch (e) { alert('Gagal mengubah status.') }
}
</script>
