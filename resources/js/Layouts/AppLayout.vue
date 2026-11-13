<template>
  <div class="min-h-screen bg-[#F3F7FB] text-slate-800 flex flex-col font-sans">

    <!-- TOP NAVBAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
      <div class="h-[3px] w-full flex">
        <div class="w-1/2 bg-[#0757a8]"></div>
        <div class="w-1/4 bg-[#F4CE18]"></div>
        <div class="w-1/4 bg-[#16834A]"></div>
      </div>

      <div class="max-w-[1600px] mx-auto px-3 sm:px-6 h-14 sm:h-16 flex items-center justify-between gap-2 sm:gap-4">
        <!-- Kiri: Hamburger + Logo -->
        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
          <!-- Tombol buka/tutup sidebar -->
          <button
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors flex-shrink-0"
            :aria-label="sidebarOpen ? 'Tutup menu' : 'Buka menu'"
          >
            <Icon :icon="sidebarOpen ? 'lucide:panel-left-close' : 'lucide:panel-left-open'" class="w-5 h-5" />
          </button>

          <Link href="/dashboard" class="flex items-center gap-2 sm:gap-3 min-w-0">
            <img src="/images/assets/logo_rfc_asli.png" alt="Logo SI-RFC" class="h-7 sm:h-9 w-auto object-contain flex-shrink-0" />
            <div class="h-5 w-px bg-slate-200 hidden sm:block flex-shrink-0"></div>
            <img src="/images/assets/logo_diskominfo_jabar.png" alt="Diskominfo Jabar" class="h-6 sm:h-7 w-auto object-contain hidden md:block flex-shrink-0" />
          </Link>
        </div>

        <!-- Tengah: Search (tersembunyi di mobile kecil) -->
        <div class="hidden sm:flex items-center flex-1 max-w-md mx-2 sm:mx-4">
          <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Icon icon="lucide:search" class="w-4 h-4 text-slate-400" />
            </span>
            <input
              type="text"
              placeholder="Cari kode RFC, nama pemohon..."
              class="w-full pl-9 pr-3 py-2 text-xs bg-slate-100/80 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757a8]/20 focus:border-[#0757a8] focus:bg-white transition-all placeholder:text-slate-400"
            />
          </div>
        </div>

        <!-- Kanan: Bell + Profil -->
        <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
          <NotifikasiBell :jumlah-belum-dibaca="$page.props.notifikasi_belum_dibaca ?? 0" />
          <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>
          <div class="flex items-center gap-1.5 sm:gap-2.5">
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[#0757a8] text-white flex items-center justify-center font-bold text-xs ring-2 ring-blue-100 flex-shrink-0">
              {{ authUser?.inisial ?? 'U' }}
            </div>
            <div class="hidden sm:block">
              <div class="font-bold text-xs text-slate-800 leading-tight truncate max-w-[120px] lg:max-w-[160px]">
                {{ authUser?.nama_lengkap ?? 'Pengguna' }}
              </div>
              <div class="text-[10px] text-slate-500 font-medium capitalize">{{ labelPeran }}</div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- MAIN LAYOUT -->
    <div class="flex-1 flex max-w-[1600px] w-full mx-auto relative">

      <!-- Overlay saat sidebar terbuka di mobile -->
      <Transition name="overlay-fade">
        <div
          v-if="sidebarOpen"
          class="fixed inset-0 bg-black/40 z-30 lg:hidden"
          @click="sidebarOpen = false"
        ></div>
      </Transition>

      <!-- SIDEBAR -->
      <Transition name="sidebar-slide">
        <aside
          v-show="sidebarOpen"
          class="bg-[#073B70] text-white flex flex-col z-40
                 fixed top-14 bottom-0 left-0 w-64
                 lg:sticky lg:top-0 lg:h-screen lg:flex-shrink-0"
        >
          <!-- Scroll area isi sidebar -->
          <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-5">

            <!-- MENU UTAMA -->
            <div>
              <div class="text-[10px] font-extrabold tracking-wider text-[#a9c9e8] uppercase mb-2 px-3">
                MENU UTAMA
              </div>

              <!-- PEMOHON -->
              <nav v-if="namaPeran === 'pemohon'" class="space-y-0.5">
                <SidebarLink href="/dashboard/pemohon?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:layout-grid" label="Ringkasan" @click="tutupSidebarMobile" />
                <SidebarLink href="/dashboard/pemohon?view=permohonan" :aktif="isView('permohonan')" icon="lucide:file-text" label="Permohonan Saya" @click="tutupSidebarMobile" />
                <SidebarLink href="/dashboard/pemohon?view=draf" :aktif="isView('draf')" icon="lucide:file-edit" label="Draf" :badge="counts.draf" @click="tutupSidebarMobile" />
                <SidebarLink href="/rfc/baru" :aktif="isPath('/rfc/baru')" icon="lucide:plus-circle" label="Ajukan RFC Baru" @click="tutupSidebarMobile" />
              </nav>

              <!-- VERIFIKATOR -->
              <nav v-else-if="namaPeran === 'verifikator'" class="space-y-0.5">
                <SidebarLink href="/dashboard/verifikator?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:layout-grid" label="Ringkasan" @click="tutupSidebarMobile" />
                <SidebarLink href="/dashboard/verifikator?view=antrean" :aktif="isView('antrean')" icon="lucide:shield-check" label="Antrean Telaah" :badge="counts.antrean" @click="tutupSidebarMobile" />
                <SidebarLink href="/dashboard/verifikator?view=sla" :aktif="isView('sla')" icon="lucide:clock" label="Pemantauan SLA" @click="tutupSidebarMobile" />
              </nav>

              <!-- PIMPINAN -->
              <nav v-else-if="namaPeran === 'pimpinan'" class="space-y-0.5">
                <SidebarLink href="/dashboard/pimpinan?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:layout-grid" label="Ringkasan" @click="tutupSidebarMobile" />
                <SidebarLink href="/dashboard/pimpinan?view=keputusan" :aktif="isView('keputusan')" icon="lucide:scale" label="Menunggu Keputusan" :badge="counts.menunggu_keputusan" @click="tutupSidebarMobile" />
                <SidebarLink href="/admin/laporan" :aktif="isPath('/admin/laporan')" icon="lucide:bar-chart-2" label="Laporan" @click="tutupSidebarMobile" />
              </nav>

              <!-- TEKNISI -->
              <nav v-else-if="namaPeran === 'teknisi'" class="space-y-0.5">
                <SidebarLink href="/dashboard/teknisi?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:layout-grid" label="Ringkasan" @click="tutupSidebarMobile" />
                <SidebarLink href="/dashboard/teknisi?view=tugas" :aktif="isView('tugas')" icon="lucide:code-2" label="Tugas Saya" :badge="counts.tugas_saya" @click="tutupSidebarMobile" />
                <SidebarLink href="/dashboard/teknisi?view=bast" :aktif="isView('bast')" icon="lucide:archive" label="Arsip BAST" @click="tutupSidebarMobile" />
              </nav>

              <!-- ADMIN -->
              <nav v-else-if="namaPeran === 'admin'" class="space-y-0.5">
                <SidebarLink href="/admin/dashboard" :aktif="isPath('/admin/dashboard')" icon="lucide:layout-grid" label="Ringkasan" @click="tutupSidebarMobile" />
                <SidebarLink href="/admin/akun" :aktif="isPath('/admin/akun')" icon="lucide:users" label="Kelola Akun" :badge="counts.akun_tertunda" badge-warna="red" @click="tutupSidebarMobile" />
                <SidebarLink href="/admin/opd" :aktif="isPath('/admin/opd')" icon="lucide:building-2" label="Master OPD" @click="tutupSidebarMobile" />
                <SidebarLink href="/admin/kategori" :aktif="isPath('/admin/kategori')" icon="lucide:tag" label="Master Kategori RFC" @click="tutupSidebarMobile" />
                <SidebarLink href="/admin/laporan" :aktif="isPath('/admin/laporan')" icon="lucide:bar-chart-2" label="Laporan & Statistik" @click="tutupSidebarMobile" />
              </nav>

              <!-- Fallback -->
              <div v-else class="px-3 py-2 text-[10px] text-blue-300">
                Peran: {{ namaPeran }}
              </div>
            </div>

            <!-- Badge keamanan -->
            <div class="bg-[#0757a8] rounded-xl p-3 border border-blue-400/20">
              <div class="flex items-center gap-2 text-white font-bold text-xs mb-1">
                <Icon icon="lucide:shield-check" class="w-4 h-4 text-emerald-400" />
                <span>Sistem terlindungi</span>
              </div>
              <p class="text-[11px] text-blue-100 leading-tight">Audit trail aktif &middot; ISO 27001 ready</p>
            </div>

          </div>

          <!-- BAGIAN BAWAH SIDEBAR: Pengaturan + Pusat Bantuan + Keluar -->
          <div class="flex-shrink-0 border-t border-blue-400/20 p-3 space-y-0.5">
            <Link href="/akun/profil" @click="tutupSidebarMobile"
              class="flex items-center gap-2.5 px-3 py-2 text-xs text-blue-200 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
              <Icon icon="lucide:settings" class="w-4 h-4" />
              <span>Pengaturan Akun</span>
            </Link>
            <a href="#"
              class="flex items-center gap-2.5 px-3 py-2 text-xs text-blue-200 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
              <Icon icon="lucide:help-circle" class="w-4 h-4" />
              <span>Pusat Bantuan</span>
            </a>
            <Link
              href="/logout"
              method="post"
              as="button"
              @click="tutupSidebarMobile"
              class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-red-300 hover:text-red-200 hover:bg-white/10 rounded-lg transition-colors text-left"
            >
              <Icon icon="lucide:log-out" class="w-4 h-4" />
              <span>Keluar</span>
            </Link>
          </div>
        </aside>
      </Transition>

      <!-- CONTENT AREA -->
      <main class="flex-1 min-w-0 overflow-x-hidden p-3 sm:p-5 lg:p-8 transition-all duration-300">
        <!-- Flash message -->
        <Transition name="flash-fade">
          <div
            v-if="flashMsg"
            :class="[
              'mb-4 p-3.5 rounded-xl text-sm font-medium flex items-center justify-between',
              flashTipe === 'sukses' ? 'bg-green-50 text-green-800 border border-green-200' :
              flashTipe === 'error'  ? 'bg-red-50 text-red-800 border border-red-200'       :
                                      'bg-blue-50 text-blue-800 border border-blue-200'
            ]"
          >
            <div class="flex items-center gap-2 min-w-0">
              <Icon
                :icon="flashTipe === 'sukses' ? 'lucide:check-circle' : flashTipe === 'error' ? 'lucide:alert-circle' : 'lucide:info'"
                class="w-4 h-4 flex-shrink-0"
              />
              <span class="truncate">{{ flashMsg }}</span>
            </div>
            <button @click="flashMsg = null" class="text-current opacity-60 hover:opacity-100 ml-3 flex-shrink-0">
              <Icon icon="lucide:x" class="w-4 h-4" />
            </button>
          </div>
        </Transition>

        <slot />
      </main>
    </div>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-3 px-4 sm:px-6 text-xs text-slate-500">
      <div class="max-w-[1600px] mx-auto flex flex-wrap items-center justify-between gap-2">
        <div><strong>SI-RFC v2.0</strong> &middot; Sistem Manajemen Perubahan Layanan TI &middot; Diskominfo Jawa Barat</div>
        <div class="text-slate-400">ITIL &middot; ISO/IEC 20000 &middot; ISO/IEC 27001</div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { Link, usePage }   from '@inertiajs/vue3'
import { Icon }            from '@iconify/vue'
import NotifikasiBell      from '@/Components/NotifikasiBell.vue'
import SidebarLink         from '@/Components/SidebarLink.vue'

// ── Page props ────────────────────────────────────────────────────────────────
const page      = usePage()
const authUser  = computed(() => page.props.auth?.user)
const counts    = computed(() => page.props.sidebar_counts ?? {
  draf: 0, antrean: 0, menunggu_keputusan: 0, tugas_saya: 0, akun_tertunda: 0,
})

const namaPeran = computed(() => authUser.value?.peran ?? 'pemohon')

const labelPeran = computed(() => ({
  admin:       'Administrator',
  pemohon:     'Pemohon OPD',
  verifikator: 'Verifikator',
  pimpinan:    'Pimpinan',
  teknisi:     'Teknisi',
}[namaPeran.value] ?? namaPeran.value))

// ── Sidebar state ─────────────────────────────────────────────────────────────
// Desktop: default terbuka (lg+). Mobile: default tertutup
const isDesktop = ref(false)
const sidebarOpen = ref(false)

function checkDesktop() {
  isDesktop.value = window.innerWidth >= 1024
  // Auto-buka di desktop, auto-tutup di mobile
  sidebarOpen.value = window.innerWidth >= 1024
}

function tutupSidebarMobile() {
  if (!isDesktop.value) sidebarOpen.value = false
}

onMounted(() => {
  checkDesktop()
  window.addEventListener('resize', checkDesktop)
})

onUnmounted(() => {
  window.removeEventListener('resize', checkDesktop)
})

// ── Flash message ─────────────────────────────────────────────────────────────
const flashMsg  = ref(null)
const flashTipe = ref('info')

function bacaFlash() {
  const f = page.props.flash ?? {}
  if (f.sukses) { flashMsg.value = f.sukses; flashTipe.value = 'sukses' }
  else if (f.error) { flashMsg.value = f.error; flashTipe.value = 'error' }
  else if (f.info)  { flashMsg.value = f.info;  flashTipe.value = 'info' }
  else { flashMsg.value = null }
}

watch(() => page.props.flash, bacaFlash, { immediate: true, deep: true })
onMounted(bacaFlash)

// ── Route helpers ─────────────────────────────────────────────────────────────
function isPath(path) {
  if (typeof window === 'undefined') return false
  const c = window.location.pathname
  return c === path || c.startsWith(path + '/')
}

function isView(view) {
  if (typeof window === 'undefined') return false
  const params = new URLSearchParams(window.location.search)
  const c      = window.location.pathname
  const v      = params.get('view')
  if (!v && view === 'ringkasan') {
    return c.includes('dashboard') || c.startsWith('/admin/')
  }
  return v === view
}
</script>

<style scoped>
/* Sidebar slide animation */
.sidebar-slide-enter-active,
.sidebar-slide-leave-active { transition: transform 0.25s ease, opacity 0.25s ease; }
.sidebar-slide-enter-from,
.sidebar-slide-leave-to     { transform: translateX(-100%); opacity: 0; }

/* Overlay fade */
.overlay-fade-enter-active,
.overlay-fade-leave-active { transition: opacity 0.2s ease; }
.overlay-fade-enter-from,
.overlay-fade-leave-to     { opacity: 0; }

/* Flash */
.flash-fade-enter-active, .flash-fade-leave-active { transition: all 0.3s ease; }
.flash-fade-enter-from, .flash-fade-leave-to       { opacity: 0; transform: translateY(-8px); }
</style>
