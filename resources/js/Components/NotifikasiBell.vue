<template>
  <div class="relative" ref="rootRef">
    <!-- Tombol Bell -->
    <button
      type="button"
      @click="toggleDropdown"
      :class="buttonClass || 'relative p-2 text-white hover:bg-white/15 rounded-xl transition-colors'"
      aria-label="Pusat Notifikasi"
    >
      <Icon icon="lucide:bell" class="w-5 h-5 text-current" />
      <span
        v-if="unreadCount > 0"
        class="absolute top-1 right-1 min-w-[17px] h-[17px] px-1 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center ring-2 ring-white shadow-xs leading-none"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <Transition name="dropdown-fade">
      <div
        v-if="showDropdown"
        class="absolute right-0 mt-2.5 w-80 sm:w-96 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl border border-slate-200 z-[100] overflow-hidden text-slate-800 text-left"
      >
        <!-- Header dropdown -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50/80">
          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-800 tracking-tight">Pusat Notifikasi</span>
            <span
              v-if="unreadCount > 0"
              class="px-2 py-0.5 rounded-full text-[10px] font-black bg-[#0757A8] text-white"
            >
              {{ unreadCount }} Baru
            </span>
          </div>
          <button
            v-if="unreadCount > 0"
            type="button"
            @click="tandaiBacaSemua"
            class="text-[11px] font-bold text-[#0757A8] hover:underline"
          >
            Tandai semua dibaca
          </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex flex-col items-center justify-center py-10 text-slate-400 gap-2">
          <Icon icon="lucide:loader-2" class="w-6 h-6 animate-spin text-[#0757A8]" />
          <span class="text-xs font-medium">Memuat notifikasi...</span>
        </div>

        <!-- Kosong -->
        <div v-else-if="daftarNotifikasi.length === 0" class="py-12 text-center text-slate-400">
          <Icon icon="lucide:bell-off" class="w-9 h-9 text-slate-300 mx-auto mb-2" />
          <p class="text-xs font-semibold text-slate-600">Tidak ada notifikasi baru</p>
          <p class="text-[11px] text-slate-400 mt-0.5">Semua permohonan terkini Anda sudah ditinjau.</p>
        </div>

        <!-- Daftar notifikasi -->
        <ul v-else class="max-h-80 overflow-y-auto divide-y divide-slate-100 scrollbar-thin scrollbar-thumb-slate-200">
          <li
            v-for="notif in daftarNotifikasi"
            :key="notif.id"
            @click="klikNotifikasi(notif)"
            :class="[
              'px-4 py-3 cursor-pointer hover:bg-blue-50/60 transition-colors',
              !notif.sudah_dibaca ? 'bg-blue-50/40' : 'bg-white'
            ]"
          >
            <div class="flex items-start gap-3">
              <div
                :class="[
                  'w-2 h-2 rounded-full mt-1.5 flex-shrink-0 transition-colors',
                  !notif.sudah_dibaca ? 'bg-[#0757A8] ring-4 ring-blue-100' : 'bg-slate-300'
                ]"
              ></div>
              <div class="flex-1 min-w-0">
                <p
                  :class="[
                    'text-xs leading-snug mb-0.5',
                    !notif.sudah_dibaca ? 'font-bold text-slate-900' : 'font-semibold text-slate-700'
                  ]"
                >
                  {{ notif.judul }}
                </p>
                <p class="text-[11px] text-slate-600 leading-snug line-clamp-2">{{ notif.pesan }}</p>
                <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1 font-medium">
                  <Icon icon="lucide:clock" class="w-3 h-3 text-slate-400" />
                  <span>{{ notif.dibuat_pada }}</span>
                </p>
              </div>
              <Icon icon="lucide:chevron-right" class="w-3.5 h-3.5 text-slate-300 flex-shrink-0 self-center" />
            </div>
          </li>
        </ul>

        <!-- Footer -->
        <div class="border-t border-slate-100 px-4 py-2.5 text-center bg-slate-50/60">
          <span class="text-[10px] text-slate-500 font-medium">{{ daftarNotifikasi.length }} notifikasi tercatat</span>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import { Icon } from '@iconify/vue'

const props = defineProps({
  jumlahBelumDibaca: { type: Number, default: 0 },
  buttonClass:       { type: String, default: '' },
})

const unreadCount        = ref(props.jumlahBelumDibaca)
const showDropdown       = ref(false)
const loading            = ref(false)
const daftarNotifikasi   = ref([])
const rootRef            = ref(null)
let intervalId           = null

watch(() => props.jumlahBelumDibaca, (newVal) => {
  unreadCount.value = newVal
})

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
  if (showDropdown.value) muatNotifikasi()
}

async function muatNotifikasi() {
  loading.value = true
  try {
    const res = await axios.get('/api/notifikasi')
    daftarNotifikasi.value = res.data.notifikasi ?? []
    if (typeof res.data.belum_dibaca === 'number') {
      unreadCount.value = res.data.belum_dibaca
    }
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
      if (unreadCount.value > 0) unreadCount.value--
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
    unreadCount.value = 0
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
