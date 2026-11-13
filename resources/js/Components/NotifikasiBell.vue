<template>
  <div class="relative" ref="rootRef">
    <!-- Tombol Bell -->
    <button
      type="button"
      @click="toggleDropdown"
      class="relative p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors"
      aria-label="Notifikasi"
    >
      <Icon icon="lucide:bell" class="w-5 h-5 text-slate-600" />
      <span
        v-if="jumlahBelumDibaca > 0"
        class="absolute top-1 right-1 min-w-[16px] h-4 px-0.5 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center ring-2 ring-white"
      >
        {{ jumlahBelumDibaca > 9 ? '9+' : jumlahBelumDibaca }}
      </span>
    </button>

    <!-- Dropdown -->
    <Transition name="dropdown-fade">
      <div
        v-if="showDropdown"
        class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden"
      >
        <!-- Header dropdown -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
          <span class="text-xs font-bold text-slate-800">Notifikasi</span>
          <button
            v-if="jumlahBelumDibaca > 0"
            type="button"
            @click="tandaiBacaSemua"
            class="text-[10px] font-semibold text-[#0757A8] hover:underline"
          >
            Tandai semua dibaca
          </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-8">
          <Icon icon="lucide:loader-2" class="w-5 h-5 text-slate-400 animate-spin" />
        </div>

        <!-- Kosong -->
        <div v-else-if="daftarNotifikasi.length === 0" class="py-10 text-center">
          <Icon icon="lucide:bell-off" class="w-8 h-8 text-slate-300 mx-auto mb-2" />
          <p class="text-xs text-slate-400">Belum ada notifikasi</p>
        </div>

        <!-- Daftar notifikasi -->
        <ul v-else class="max-h-80 overflow-y-auto divide-y divide-slate-100">
          <li
            v-for="notif in daftarNotifikasi"
            :key="notif.id"
            @click="klikNotifikasi(notif)"
            :class="[
              'px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors',
              !notif.sudah_dibaca ? 'bg-blue-50/40' : ''
            ]"
          >
            <div class="flex items-start gap-2.5">
              <div
                :class="[
                  'w-2 h-2 rounded-full mt-1.5 flex-shrink-0',
                  !notif.sudah_dibaca ? 'bg-[#0757A8]' : 'bg-transparent'
                ]"
              ></div>
              <div class="flex-1 min-w-0">
                <p
                  :class="[
                    'text-xs leading-snug mb-0.5',
                    !notif.sudah_dibaca ? 'font-bold text-slate-800' : 'font-semibold text-slate-600'
                  ]"
                >
                  {{ notif.judul }}
                </p>
                <p class="text-[11px] text-slate-500 leading-snug line-clamp-2">{{ notif.pesan }}</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ notif.dibuat_pada }}</p>
              </div>
            </div>
          </li>
        </ul>

        <!-- Footer -->
        <div class="border-t border-slate-100 px-4 py-2.5 text-center">
          <span class="text-[10px] text-slate-400">{{ daftarNotifikasi.length }} notifikasi terbaru</span>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  jumlahBelumDibaca: { type: Number, default: 0 }
})

const showDropdown       = ref(false)
const loading            = ref(false)
const daftarNotifikasi   = ref([])
const rootRef            = ref(null)
let intervalId           = null

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
  if (showDropdown.value) muatNotifikasi()
}

async function muatNotifikasi() {
  loading.value = true
  try {
    const res = await axios.get('/api/notifikasi')
    daftarNotifikasi.value = res.data.notifikasi ?? []
  } catch (e) {
    // silent fail
  } finally {
    loading.value = false
  }
}

async function klikNotifikasi(notif) {
  if (!notif.sudah_dibaca) {
    try {
      await axios.patch(`/api/notifikasi/${notif.id}/baca`)
      notif.sudah_dibaca = true
    } catch (e) { /* silent */ }
  }
  showDropdown.value = false
  if (notif.url_tujuan) {
    router.visit(notif.url_tujuan)
  }
}

async function tandaiBacaSemua() {
  try {
    await axios.patch('/api/notifikasi/baca-semua')
    daftarNotifikasi.value.forEach(n => n.sudah_dibaca = true)
    router.reload({ only: ['notifikasi_belum_dibaca', 'sidebar_counts'] })
  } catch (e) { /* silent */ }
}

function klikLuar(e) {
  if (rootRef.value && !rootRef.value.contains(e.target)) {
    showDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', klikLuar)
  // Poll tiap 30 detik
  intervalId = setInterval(() => {
    if (!showDropdown.value) return
    muatNotifikasi()
  }, 30000)
})

onUnmounted(() => {
  document.removeEventListener('click', klikLuar)
  if (intervalId) clearInterval(intervalId)
})
</script>

<style scoped>
.dropdown-fade-enter-active,
.dropdown-fade-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.dropdown-fade-enter-from,
.dropdown-fade-leave-to     { opacity: 0; transform: translateY(-6px) scale(0.98); }
</style>
