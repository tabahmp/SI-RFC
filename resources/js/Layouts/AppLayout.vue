<template>
  <div class="min-h-screen bg-[#F3F7FB] text-slate-800 flex flex-col font-sans">
    <!-- 1. TOP NAVBAR (PERSIS FIGMA) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
      <!-- Top Strip Aksen Jawa Barat (Khas Jabar di Figma) -->
      <div class="h-[3px] w-full flex">
        <div class="w-1/2 bg-[#0757a8]"></div>
        <div class="w-1/4 bg-[#f4ce18]"></div>
        <div class="w-1/4 bg-[#16834a]"></div>
      </div>

      <div class="max-w-[1600px] mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-3">
          <Link href="/dashboard" class="flex items-center gap-3">
            <img src="/images/assets/logo_rfc_asli.png" alt="Logo RFC" class="h-9 w-auto object-contain" />
            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
            <img src="/images/assets/logo_diskominfo_jabar.png" alt="Diskominfo Jabar" class="h-7 w-auto object-contain hidden sm:block" />
            <div class="sm:hidden">
              <div class="font-extrabold text-[#073b70] text-sm tracking-tight leading-none">SI-RFC</div>
              <div class="text-[9px] text-slate-400 font-semibold tracking-wider uppercase mt-0.5">Diskominfo Jabar</div>
            </div>
          </Link>
        </div>

        <!-- Global Search Bar (Figma shortcut ⌘ K) -->
        <div class="hidden md:flex items-center flex-1 max-w-md mx-6">
          <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
              <Icon icon="lucide:search" class="w-4 h-4 text-slate-400" />
            </span>
            <input
              type="text"
              placeholder="Cari tiket..."
              class="w-full pl-9 pr-14 py-2 text-xs bg-slate-100/80 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] focus:bg-white transition-all placeholder:text-slate-400"
            />
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
              <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 bg-white border border-slate-200 rounded shadow-xs">⌘ K</kbd>
            </div>
          </div>
        </div>

        <!-- User Profile & Action -->
        <div class="flex items-center gap-4">
          <!-- Notification Bell -->
          <button class="relative p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
            <Icon icon="lucide:bell" class="w-5 h-5 text-slate-600" />
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#ef4444] rounded-full ring-2 ring-white"></span>
          </button>

          <div class="h-6 w-px bg-slate-200"></div>

          <!-- Profile Badge with Dropdown for Role Switching -->
          <div class="relative">
            <button
              @click="showRoleDropdown = !showRoleDropdown"
              class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-100 transition-colors text-left"
            >
              <div class="w-8 h-8 rounded-full bg-[#0757a8] text-white flex items-center justify-center font-bold text-xs ring-2 ring-blue-100">
                {{ activeUserInitials }}
              </div>
              <div class="hidden sm:block text-left">
                <div class="font-bold text-xs text-slate-800 leading-tight">{{ activeUserName }}</div>
                <div class="text-[10px] text-slate-500 font-medium capitalize flex items-center gap-1">
                  <span>{{ activeRoleLabel }}</span>
                  <Icon icon="lucide:chevron-down" class="w-3 h-3 text-slate-400" />
                </div>
              </div>
            </button>

            <!-- Role Dropdown Menu (Untuk ganti peran) -->
            <div
              v-if="showRoleDropdown"
              class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50 animate-in fade-in zoom-in-95 duration-100"
            >
              <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                Ganti Peran Dashboard
              </div>
              <Link
                href="/dashboard/pemohon"
                @click="showRoleDropdown = false"
                class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-[#0757A8] transition-colors"
              >
                <div class="flex items-center gap-2">
                  <Icon icon="lucide:user" class="w-4 h-4 text-blue-600" />
                  <span>Pemohon</span>
                </div>
                <Icon v-if="activeRole === 'pemohon'" icon="lucide:check" class="w-3.5 h-3.5 text-blue-600" />
              </Link>
              <Link
                href="/dashboard/verifikator"
                @click="showRoleDropdown = false"
                class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-[#0757A8] transition-colors"
              >
                <div class="flex items-center gap-2">
                  <Icon icon="lucide:shield-check" class="w-4 h-4 text-purple-600" />
                  <span>Verifikator</span>
                </div>
                <Icon v-if="activeRole === 'verifikator'" icon="lucide:check" class="w-3.5 h-3.5 text-purple-600" />
              </Link>
              <Link
                href="/dashboard/pimpinan"
                @click="showRoleDropdown = false"
                class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-[#0757A8] transition-colors"
              >
                <div class="flex items-center gap-2">
                  <Icon icon="lucide:scale" class="w-4 h-4 text-amber-600" />
                  <span>Pimpinan</span>
                </div>
                <Icon v-if="activeRole === 'pimpinan'" icon="lucide:check" class="w-3.5 h-3.5 text-amber-600" />
              </Link>
              <Link
                href="/dashboard/teknisi"
                @click="showRoleDropdown = false"
                class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-[#0757A8] transition-colors"
              >
                <div class="flex items-center gap-2">
                  <Icon icon="lucide:code-2" class="w-4 h-4 text-cyan-600" />
                  <span>Teknisi</span>
                </div>
                <Icon v-if="activeRole === 'teknisi'" icon="lucide:check" class="w-3.5 h-3.5 text-cyan-600" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- 2. MAIN LAYOUT: SIDEBAR + CONTENT -->
    <div class="flex-1 flex max-w-[1600px] w-full mx-auto">
      <!-- SIDEBAR PERSIS FIGMA (Warna #073B70) - HANYA BERISI MENU UTAMA -->
      <aside class="w-64 bg-[#073B70] text-white p-5 flex flex-col justify-between hidden lg:flex flex-shrink-0">
        <div class="space-y-6">
          
          <!-- SECTION: MENU UTAMA (HANYA MENU UTAMA SESUAI PERAN AKTIF) -->
          <div>
            <div class="text-[10px] font-extrabold tracking-wider text-[#a9c9e8] uppercase mb-2 px-3">
              MENU UTAMA
            </div>
            
            <!-- Menu Khusus Pemohon -->
            <nav v-if="activeRole === 'pemohon'" class="space-y-1">
              <Link
                href="/dashboard/pemohon?view=ringkasan"
                :class="[
                  'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
                  currentRouteView === 'ringkasan' || !currentRouteView
                    ? 'bg-[#F4CE18] text-[#073B70] shadow-sm'
                    : 'text-[#E4F0FC] hover:bg-white/10 font-medium'
                ]"
              >
                <Icon icon="lucide:layout-grid" :class="(currentRouteView === 'ringkasan' || !currentRouteView) ? 'text-[#073B70]' : 'text-[#A9C9E8]'" class="w-4 h-4" />
                <span>Ringkasan</span>
              </Link>
              <Link
                href="/dashboard/pemohon?view=permohonan"
                :class="[
                  'flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
                  currentRouteView === 'permohonan'
                    ? 'bg-[#F4CE18] text-[#073B70] shadow-sm'
                    : 'text-[#E4F0FC] hover:bg-white/10 font-medium'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:file-text" :class="currentRouteView === 'permohonan' ? 'text-[#073B70]' : 'text-[#A9C9E8]'" class="w-4 h-4" />
                  <span>Permohonan Saya</span>
                </div>
                <span :class="currentRouteView === 'permohonan' ? 'bg-[#285981] text-[#E4F0FC]' : 'bg-[#1B4A7A] text-[#E4F0FC]'" class="text-[10px] px-2 py-0.5 rounded-md font-bold">24</span>
              </Link>
              <Link
                href="/dashboard/pemohon?view=draf"
                :class="[
                  'flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
                  currentRouteView === 'draf'
                    ? 'bg-[#F4CE18] text-[#073B70] shadow-sm'
                    : 'text-[#E4F0FC] hover:bg-white/10 font-medium'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:file-edit" :class="currentRouteView === 'draf' ? 'text-[#073B70]' : 'text-[#A9C9E8]'" class="w-4 h-4" />
                  <span>Draf (Ajukan Baru)</span>
                </div>
                <span :class="currentRouteView === 'draf' ? 'bg-[#285981] text-[#E4F0FC]' : 'bg-[#1B4A7A] text-[#E4F0FC]'" class="text-[10px] px-2 py-0.5 rounded-md font-bold">3</span>
              </Link>
            </nav>

            <!-- Menu Khusus Verifikator -->
            <nav v-else-if="activeRole === 'verifikator'" class="space-y-1">
              <Link
                href="/dashboard/verifikator?view=ringkasan"
                :class="[
                  'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
                  currentRouteView === 'ringkasan' || !currentRouteView ? 'bg-[#F4CE18] text-[#073B70] shadow-sm' : 'text-[#E4F0FC] hover:bg-white/10'
                ]"
              >
                <Icon icon="lucide:layout-grid" :class="(currentRouteView === 'ringkasan' || !currentRouteView) ? 'text-[#073B70]' : 'text-[#A9C9E8]'" class="w-4 h-4" />
                <span>Ringkasan</span>
              </Link>
              <Link
                href="/dashboard/verifikator?view=antrean"
                :class="[
                  'flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
                  currentRouteView === 'antrean' ? 'bg-[#F4CE18] text-[#073B70] shadow-sm' : 'text-[#E4F0FC] hover:bg-white/10'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:shield-check" :class="currentRouteView === 'antrean' ? 'text-[#073B70]' : 'text-[#A9C9E8]'" class="w-4 h-4" />
                  <span>Antrean Telaah</span>
                </div>
                <span :class="currentRouteView === 'antrean' ? 'bg-[#285981] text-[#E4F0FC]' : 'bg-[#1B4A7A] text-[#E4F0FC]'" class="text-[10px] px-2 py-0.5 rounded-md font-bold">29</span>
              </Link>
              <Link
                href="/dashboard/verifikator?view=sla"
                :class="[
                  'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
                  currentRouteView === 'sla' ? 'bg-[#F4CE18] text-[#073B70] shadow-sm' : 'text-[#E4F0FC] hover:bg-white/10'
                ]"
              >
                <Icon icon="lucide:clock" :class="currentRouteView === 'sla' ? 'text-[#073B70]' : 'text-[#A9C9E8]'" class="w-4 h-4" />
                <span>Pemantauan SLA</span>
              </Link>
              <!-- Menu Baru: Verifikasi Registrasi Pemohon -->
              <Link
                href="/dashboard/verifikator?view=verifikasi_pemohon"
                :class="[
                  'flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
                  currentRouteView === 'verifikasi_pemohon' ? 'bg-[#F4CE18] text-[#073B70] shadow-sm' : 'text-[#E4F0FC] hover:bg-white/10'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:user-check" :class="currentRouteView === 'verifikasi_pemohon' ? 'text-[#073B70]' : 'text-[#A9C9E8]'" class="w-4 h-4" />
                  <span>Verifikasi Pemohon</span>
                </div>
                <span class="bg-amber-400 text-[#073B70] text-[10px] px-2 py-0.5 rounded-md font-extrabold">Baru</span>
              </Link>
            </nav>

            <!-- Menu Khusus Pimpinan -->
            <nav v-else-if="activeRole === 'pimpinan'" class="space-y-1">
              <Link
                href="/dashboard/pimpinan"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-[#F4CE18] text-[#073B70] shadow-sm"
              >
                <Icon icon="lucide:layout-grid" class="w-4 h-4 text-[#073B70]" />
                <span>Ringkasan</span>
              </Link>
              <Link
                href="/form-perubahan-it/pengerjaan/RFC-202506-0138?tab=pimpinan"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:scale" class="w-4 h-4 text-[#A9C9E8]" />
                  <span>Menunggu Keputusan</span>
                </div>
                <span class="bg-[#1B4A7A] text-[#E4F0FC] text-[10px] px-2 py-0.5 rounded-md font-bold">8</span>
              </Link>
              <Link
                href="/form-perubahan-it/dokumen/RFC-202506-0117"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <Icon icon="lucide:file-bar-chart" class="w-4 h-4 text-[#A9C9E8]" />
                <span>Laporan FR-015</span>
              </Link>
            </nav>

            <!-- Menu Khusus Teknisi -->
            <nav v-else-if="activeRole === 'teknisi'" class="space-y-1">
              <Link
                href="/dashboard/teknisi"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-[#F4CE18] text-[#073B70] shadow-sm"
              >
                <Icon icon="lucide:layout-grid" class="w-4 h-4 text-[#073B70]" />
                <span>Ringkasan</span>
              </Link>
              <Link
                href="/form-perubahan-it/pengerjaan/RFC-202506-0138?tab=implementasi"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:code-2" class="w-4 h-4 text-[#A9C9E8]" />
                  <span>Tugas Saya</span>
                </div>
                <span class="bg-[#1B4A7A] text-[#E4F0FC] text-[10px] px-2 py-0.5 rounded-md font-bold">6</span>
              </Link>
              <Link
                href="/form-perubahan-it/dokumen/RFC-202506-0117"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <Icon icon="lucide:archive" class="w-4 h-4 text-[#A9C9E8]" />
                <span>Arsip BAST</span>
              </Link>
            </nav>
          </div>

          <!-- SECTION: MENU BAWAH (PERSIS FIGMA) -->
          <div class="pt-4 border-t border-blue-400/20 space-y-1">
            <a href="#" class="flex items-center gap-2.5 px-3 py-1.5 text-xs text-blue-200 hover:text-white transition-colors">
              <Icon icon="lucide:help-circle" class="w-4 h-4" />
              <span>Pusat Bantuan</span>
            </a>
            <a href="#" class="flex items-center gap-2.5 px-3 py-1.5 text-xs text-blue-200 hover:text-white transition-colors">
              <Icon icon="lucide:settings" class="w-4 h-4" />
              <span>Pengaturan</span>
            </a>
            <Link href="/logout" method="post" as="button" class="w-full flex items-center gap-2.5 px-3 py-1.5 text-xs text-red-300 hover:text-red-200 transition-colors text-left">
              <Icon icon="lucide:log-out" class="w-4 h-4" />
              <span>Keluar</span>
            </Link>
          </div>
        </div>

        <!-- KEAMANAN & AUDIT TRAIL BADGE (PERSIS FIGMA) -->
        <div class="bg-[#0757a8] rounded-xl p-3.5 border border-blue-400/20">
          <div class="flex items-center gap-2 text-white font-bold text-xs mb-1">
            <Icon icon="lucide:shield-check" class="w-4 h-4 text-emerald-400" />
            <span>Sistem terlindungi</span>
          </div>
          <p class="text-[11px] text-blue-100 leading-tight">
            Audit trail aktif • ISO 27001 ready
          </p>
        </div>
      </aside>

      <!-- CONTENT AREA -->
      <main class="flex-1 overflow-x-hidden p-4 sm:p-6 lg:p-8">
        <slot :role="activeRole" />
      </main>
    </div>

    <!-- 3. FOOTER PERSIS FIGMA -->
    <footer class="bg-white border-t border-slate-200 py-3.5 px-6 text-xs text-slate-500">
      <div class="max-w-[1600px] mx-auto flex flex-wrap items-center justify-between gap-4">
        <div>
          <strong>SI-RFC v1.0</strong> • Sistem Manajemen Perubahan Layanan TI
        </div>
        <div class="flex gap-4 text-slate-400">
          <span>Diskominfo • ITIL / ISO 20000 / ISO 27001</span>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  role: {
    type: String,
    default: null
  }
})

const showRoleDropdown = ref(false)
const localRole = ref('pemohon')
const currentRouteView = ref('ringkasan')

const page = usePage()
const authUser = computed(() => page.props.auth?.user)

const activeRole = computed(() => {
  if (authUser.value?.role) return authUser.value.role
  if (props.role) return props.role
  return localRole.value
})

const activeRoleLabel = computed(() => {
  switch (activeRole.value) {
    case 'verifikator': return 'Verifikator'
    case 'pimpinan': return 'Pimpinan'
    case 'teknisi': return 'Teknisi'
    default: return 'Pemohon'
  }
})

const activeUserName = computed(() => {
  if (authUser.value?.name) return authUser.value.name
  switch (activeRole.value) {
    case 'verifikator': return 'Raka Pratama, S.STP'
    case 'pimpinan': return 'Dr. H. Hendra Setiawan, M.Si'
    case 'teknisi': return 'Teguh Wicaksono, S.T'
    default: return 'Ahmad Fauzi, S.Kom'
  }
})

const activeUserInitials = computed(() => {
  const parts = activeUserName.value.trim().split(/\s+/)
  if (parts.length >= 2) return `${parts[0][0]}${parts[1][0]}`.toUpperCase()
  return activeUserName.value.substring(0, 2).toUpperCase()
})

function updateFromUrl() {
  if (typeof window === 'undefined') return
  const path = window.location.pathname
  const params = new URLSearchParams(window.location.search)

  if (path.includes('/permohonan-saya')) {
    localRole.value = 'pemohon'
    currentRouteView.value = 'permohonan'
  } else if (path.includes('/form-perubahan-it/ticket/') || path.includes('/form-perubahan-it/dokumen/') || path.includes('/form-perubahan-it/pengerjaan/')) {
    // Ketika buka detail RFC/BAST/Workspace — pertahankan sidebar "Permohonan Saya" aktif (kuning)
    if (!localRole.value || localRole.value === 'pemohon') {
      localRole.value = 'pemohon'
      currentRouteView.value = 'permohonan'
    }
  } else if (path.includes('/dashboard/verifikator')) {
    localRole.value = 'verifikator'
  } else if (path.includes('/dashboard/pimpinan')) {
    localRole.value = 'pimpinan'
  } else if (path.includes('/dashboard/teknisi')) {
    localRole.value = 'teknisi'
  } else if (path.includes('/dashboard/pemohon')) {
    localRole.value = 'pemohon'
  } else if (params.get('role')) {
    localRole.value = params.get('role')
  }

  if (params.get('view')) {
    currentRouteView.value = params.get('view')
  } else if (path.includes('/dashboard/pemohon') && !params.get('view')) {
    currentRouteView.value = 'ringkasan'
  } else if (path.includes('/dashboard/verifikator') && !params.get('view')) {
    currentRouteView.value = 'ringkasan'
  }
}

watch(() => page.url, () => {
  updateFromUrl()
})

onMounted(() => {
  updateFromUrl()
})
</script>
