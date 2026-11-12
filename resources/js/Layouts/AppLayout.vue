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
          <Link href="/form-perubahan-it" class="flex items-center gap-3">
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

          <!-- Profile Badge -->
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-[#0757a8] text-white flex items-center justify-center font-bold text-xs ring-2 ring-blue-100">
              RP
            </div>
            <div class="hidden sm:block text-left">
              <div class="font-bold text-xs text-slate-800 leading-tight">Raka Pratama</div>
              <div class="text-[10px] text-slate-500 font-medium capitalize">{{ activeRoleLabel }}</div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- 2. MAIN LAYOUT: SIDEBAR + CONTENT -->
    <div class="flex-1 flex max-w-[1600px] w-full mx-auto">
      <!-- SIDEBAR PERSIS FIGMA (Warna #073B70) -->
      <aside class="w-64 bg-[#073B70] text-white p-5 flex flex-col justify-between hidden lg:flex flex-shrink-0">
        <div class="space-y-6">
          
          <!-- SECTION 1: MENU UTAMA SESUAI PERAN FIGMA -->
          <div>
            <div class="text-[10px] font-extrabold tracking-wider text-[#a9c9e8] uppercase mb-2 px-3">
              MENU UTAMA
            </div>
            
            <!-- Menu Khusus Pemohon -->
            <nav v-if="activeRole === 'pemohon'" class="space-y-1">
              <Link
                href="/form-perubahan-it?role=pemohon"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-white text-[#073B70] shadow-sm"
              >
                <Icon icon="lucide:layout-grid" class="w-4 h-4 text-[#073B70]" />
                <span>Ringkasan</span>
              </Link>
              <Link
                href="/form-perubahan-it?role=pemohon"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:file-text" class="w-4 h-4 text-[#A9C9E8]" />
                  <span>Permohonan Saya</span>
                </div>
                <span class="bg-[#1B4A7A] text-[#E4F0FC] text-[10px] px-2 py-0.5 rounded-md font-bold">24</span>
              </Link>
              <Link
                href="/form-perubahan-it/baru"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:file-edit" class="w-4 h-4 text-[#A9C9E8]" />
                  <span>Draf (Ajukan Baru)</span>
                </div>
                <span class="bg-[#1B4A7A] text-[#E4F0FC] text-[10px] px-2 py-0.5 rounded-md font-bold">3</span>
              </Link>
            </nav>

            <!-- Menu Khusus Verifikator -->
            <nav v-else-if="activeRole === 'verifikator'" class="space-y-1">
              <Link
                href="/form-perubahan-it?role=verifikator"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-white text-[#073B70] shadow-sm"
              >
                <Icon icon="lucide:layout-grid" class="w-4 h-4 text-[#073B70]" />
                <span>Ringkasan</span>
              </Link>
              <Link
                href="/form-perubahan-it/pengerjaan/RFC-202506-0142?tab=verifikasi"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:shield-check" class="w-4 h-4 text-[#A9C9E8]" />
                  <span>Antrean Telaah</span>
                </div>
                <span class="bg-[#1B4A7A] text-[#E4F0FC] text-[10px] px-2 py-0.5 rounded-md font-bold">29</span>
              </Link>
              <Link
                href="/form-perubahan-it?role=verifikator"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-medium text-[#E4F0FC] hover:bg-white/10 transition-all"
              >
                <Icon icon="lucide:clock" class="w-4 h-4 text-[#A9C9E8]" />
                <span>Pemantauan SLA</span>
              </Link>
            </nav>

            <!-- Menu Khusus Pimpinan -->
            <nav v-else-if="activeRole === 'pimpinan'" class="space-y-1">
              <Link
                href="/form-perubahan-it?role=pimpinan"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-white text-[#073B70] shadow-sm"
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
                href="/form-perubahan-it?role=teknisi"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-white text-[#073B70] shadow-sm"
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

          <!-- SECTION 2: SIMULASI PERAN KEDINASAN (PERSIS FIGMA) -->
          <div>
            <div class="text-[10px] font-extrabold tracking-wider text-[#a9c9e8] uppercase mb-2 px-3">
              SIMULASI PERAN
            </div>
            <nav class="space-y-1">
              <!-- Pemohon (Frame 1:89) -->
              <button
                @click="switchRole('pemohon')"
                :class="[
                  'w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-all',
                  activeRole === 'pemohon' ? 'bg-[#16834a] text-white shadow-xs' : 'text-blue-100 hover:bg-white/10'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:user" class="w-4 h-4" />
                  <span>Pemohon</span>
                </div>
                <Icon v-if="activeRole === 'pemohon'" icon="lucide:check" class="w-3.5 h-3.5" />
              </button>

              <!-- Verifikator (Frame 1:297) -->
              <button
                @click="switchRole('verifikator')"
                :class="[
                  'w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-all',
                  activeRole === 'verifikator' ? 'bg-[#16834a] text-white shadow-xs' : 'text-blue-100 hover:bg-white/10'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:shield-check" class="w-4 h-4 text-purple-300" />
                  <span>Verifikator</span>
                </div>
                <Icon v-if="activeRole === 'verifikator'" icon="lucide:check" class="w-3.5 h-3.5" />
              </button>

              <!-- Pimpinan (Frame 1:474) -->
              <button
                @click="switchRole('pimpinan')"
                :class="[
                  'w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-all',
                  activeRole === 'pimpinan' ? 'bg-[#16834a] text-white shadow-xs' : 'text-blue-100 hover:bg-white/10'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:scale" class="w-4 h-4 text-blue-300" />
                  <span>Pimpinan</span>
                </div>
                <Icon v-if="activeRole === 'pimpinan'" icon="lucide:check" class="w-3.5 h-3.5" />
              </button>

              <!-- Teknisi (Frame 1:658) -->
              <button
                @click="switchRole('teknisi')"
                :class="[
                  'w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-all',
                  activeRole === 'teknisi' ? 'bg-[#16834a] text-white shadow-xs' : 'text-blue-100 hover:bg-white/10'
                ]"
              >
                <div class="flex items-center gap-2.5">
                  <Icon icon="lucide:code-2" class="w-4 h-4 text-cyan-300" />
                  <span>Teknisi</span>
                </div>
                <Icon v-if="activeRole === 'teknisi'" icon="lucide:check" class="w-3.5 h-3.5" />
              </button>
            </nav>
          </div>

          <!-- SECTION 3: MENU BAWAH (PERSIS FIGMA) -->
          <div class="pt-2 border-t border-blue-400/20 space-y-1">
            <a href="#" class="flex items-center gap-2.5 px-3 py-1.5 text-xs text-blue-200 hover:text-white transition-colors">
              <Icon icon="lucide:help-circle" class="w-4 h-4" />
              <span>Pusat Bantuan</span>
            </a>
            <a href="#" class="flex items-center gap-2.5 px-3 py-1.5 text-xs text-blue-200 hover:text-white transition-colors">
              <Icon icon="lucide:settings" class="w-4 h-4" />
              <span>Pengaturan</span>
            </a>
            <Link href="/login" class="flex items-center gap-2.5 px-3 py-1.5 text-xs text-red-300 hover:text-red-200 transition-colors">
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
        <slot :role="activeRole" :setRole="switchRole" />
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
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const activeRole = ref('pemohon')

const activeRoleLabel = computed(() => {
  switch (activeRole.value) {
    case 'verifikator': return 'Verifikator'
    case 'pimpinan': return 'Pimpinan'
    case 'teknisi': return 'Teknisi'
    default: return 'Pemohon'
  }
})

function switchRole(role) {
  activeRole.value = role
  if (typeof window !== 'undefined') {
    localStorage.setItem('si_rfc_role', role)
    // Update URL query parameter tanpa reload jika di halaman /form-perubahan-it
    if (window.location.pathname.includes('/form-perubahan-it')) {
      const url = new URL(window.location.href)
      url.searchParams.set('role', role)
      window.history.replaceState({}, '', url.toString())
      window.dispatchEvent(new CustomEvent('role-changed', { detail: role }))
    }
  }
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search)
    const roleParam = params.get('role')
    const savedRole = localStorage.getItem('si_rfc_role')
    if (roleParam && ['pemohon', 'verifikator', 'pimpinan', 'teknisi'].includes(roleParam)) {
      activeRole.value = roleParam
    } else if (savedRole && ['pemohon', 'verifikator', 'pimpinan', 'teknisi'].includes(savedRole)) {
      activeRole.value = savedRole
    }
  }
})
</script>
