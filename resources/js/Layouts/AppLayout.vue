<template>
  <div class="min-h-screen bg-[#F0F4F8] text-slate-800 flex flex-col font-sans p-3 sm:p-4 lg:p-5 selection:bg-emerald-600 selection:text-white">

    <!-- OVERLAY MOBILE DRAWER (< lg) -->
    <Transition name="overlay-fade">
      <div
        v-if="sidebarMobileOpen"
        class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden"
        @click="sidebarMobileOpen = false"
      ></div>
    </Transition>

    <!-- ========================================================
         SIDEBAR: FLOATING PANEL ELEGAN JAWA BARAT
    ======================================================== -->
    <aside
      :class="[
        'bg-gradient-to-b from-[#0A2548] via-[#081F3D] to-[#061830] text-white flex flex-col justify-between z-50 transition-all duration-300 ease-in-out overflow-hidden shadow-xl shadow-slate-900/15 border border-white/[0.08]',
        // Mobile Drawer: sliding drawer dari kiri
        sidebarMobileOpen
          ? 'fixed inset-y-4 left-4 w-72 rounded-[28px] translate-x-0'
          : 'fixed -translate-x-full lg:translate-x-0',
        // Desktop: Fixed melayang di sebelah kiri
        'lg:fixed lg:top-5 lg:bottom-5 lg:left-5 lg:w-[268px] lg:rounded-[28px]'
      ]"
    >
      <!-- ORNAMEN GELOMBANG KANAN ATAS (RAPI, SEJAJAR & HARMONIS) -->
      <svg
        class="absolute top-0 right-0 w-44 h-44 pointer-events-none select-none z-0 opacity-95"
        viewBox="0 0 180 180"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <defs>
          <linearGradient id="sidebarWaveGold" x1="25" y1="0" x2="180" y2="115" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#F59E0B" stop-opacity="0.95" />
            <stop offset="50%" stop-color="#FBBF24" stop-opacity="0.95" />
            <stop offset="100%" stop-color="#FCD34D" stop-opacity="0.9" />
          </linearGradient>
          <linearGradient id="sidebarWaveGreen" x1="60" y1="0" x2="180" y2="82" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#10B981" stop-opacity="0.95" />
            <stop offset="50%" stop-color="#059669" stop-opacity="0.95" />
            <stop offset="100%" stop-color="#047857" stop-opacity="0.9" />
          </linearGradient>
          <linearGradient id="sidebarWaveDarkGreen" x1="95" y1="0" x2="180" y2="52" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#059669" stop-opacity="0.95" />
            <stop offset="100%" stop-color="#064E3B" stop-opacity="0.9" />
          </linearGradient>
        </defs>

        <!-- Pita Kuning / Emas: Melengkung Halus & Presisi -->
        <path d="M25 0 C85 30, 140 75, 180 115 V82 C148 55, 105 22, 60 0 Z" fill="url(#sidebarWaveGold)" />

        <!-- Pita Hijau Segar: Mengalir Sejajar Tepat di Bawah Pita Emas -->
        <path d="M60 0 C105 22, 148 55, 180 82 V52 C158 35, 128 15, 95 0 Z" fill="url(#sidebarWaveGreen)" />

        <!-- Pita Hijau Zamrud (Diskominfo Accent): Aksen Sudut Kanan Atas -->
        <path d="M95 0 C128 15, 158 35, 180 52 V24 C168 15, 150 7, 130 0 Z" fill="url(#sidebarWaveDarkGreen)" />
      </svg>

      <!-- HEADER SIDEBAR: LOGO RESMI DISKOMINFO & RFC KE TENGAH-TENGAH -->
      <div class="pt-6 px-4 pb-4 flex items-center justify-center relative z-10 border-b border-white/[0.08] text-center">
        <Link href="/dashboard" class="flex items-center justify-center gap-3 transition-opacity hover:opacity-90 mx-auto" title="Beranda SI-RFC">
          <img
            src="/images/assets/logo_rfc_asli.png"
            alt="Logo SI-RFC"
            class="h-8 sm:h-9 w-auto object-contain flex-shrink-0 drop-shadow-sm"
          />
          <div class="h-6 w-px bg-white/25 flex-shrink-0"></div>
          <img
            src="/images/assets/logo_diskominfo_jabar.png"
            alt="Diskominfo Jabar"
            class="h-7 sm:h-8 w-auto object-contain flex-shrink-0 drop-shadow-sm"
          />
        </Link>

        <!-- Tombol Tutup Mobile (< lg) di pojok kanan -->
        <button
          type="button"
          @click="sidebarMobileOpen = false"
          class="absolute right-3 top-6 p-1.5 rounded-xl text-white/70 hover:text-white hover:bg-white/10 lg:hidden transition-colors"
          aria-label="Tutup Menu"
        >
          <Icon icon="lucide:x" class="w-5 h-5" />
        </button>
      </div>

      <!-- AREA MENU NAVIGASI (BEBAS SCROLLBAR & BERSIH) -->
      <div class="flex-1 overflow-y-auto px-4 py-2.5 space-y-4 relative z-10 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

        <!-- KELOMPOK 1: NAVIGASI -->
        <div>
          <div class="text-[10px] font-bold tracking-widest text-[#8DB4DE]/75 uppercase px-3 mb-2">
            NAVIGASI
          </div>

          <!-- PIMPINAN -->
          <nav v-if="namaPeran === 'pimpinan'" class="space-y-1">
            <SidebarLink href="/dashboard/pimpinan?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:home" label="Ringkasan" @click="tutupSidebarMobile" />
            <SidebarLink href="/dashboard/pimpinan?view=keputusan" :aktif="isView('keputusan')" icon="lucide:file-edit" label="Manajemen Keputusan" :badge="counts.menunggu_keputusan" @click="tutupSidebarMobile" />
            <SidebarLink href="/analytics" :aktif="isPath('/analytics')" icon="lucide:trending-up" label="Analisis & Laporan" @click="tutupSidebarMobile" />
          </nav>

          <!-- PEMOHON -->
          <nav v-else-if="namaPeran === 'pemohon'" class="space-y-1">
            <SidebarLink href="/dashboard/pemohon?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:home" label="Ringkasan" @click="tutupSidebarMobile" />
            <SidebarLink href="/dashboard/pemohon?view=permohonan" :aktif="isView('permohonan')" icon="lucide:file-text" label="Permohonan Saya" @click="tutupSidebarMobile" />
            <SidebarLink href="/dashboard/pemohon?view=draf" :aktif="isView('draf')" icon="lucide:file-edit" label="Draf RFC" :badge="counts.draf" @click="tutupSidebarMobile" />
            <SidebarLink href="/rfc/baru" :aktif="isPath('/rfc/baru')" icon="lucide:plus-circle" label="Ajukan RFC Baru" @click="tutupSidebarMobile" />
            <SidebarLink href="/analytics" :aktif="isPath('/analytics')" icon="lucide:trending-up" label="Analisis & Laporan" @click="tutupSidebarMobile" />
          </nav>

          <!-- VERIFIKATOR -->
          <nav v-else-if="namaPeran === 'verifikator'" class="space-y-1">
            <SidebarLink href="/dashboard/verifikator?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:home" label="Ringkasan" @click="tutupSidebarMobile" />
            <SidebarLink href="/dashboard/verifikator?view=antrean" :aktif="isView('antrean')" icon="lucide:shield-check" label="Antrean Telaah" :badge="counts.antrean" @click="tutupSidebarMobile" />
            <SidebarLink href="/dashboard/verifikator?view=sla" :aktif="isView('sla')" icon="lucide:clock" label="Pemantauan SLA" @click="tutupSidebarMobile" />
            <SidebarLink href="/analytics" :aktif="isPath('/analytics')" icon="lucide:trending-up" label="Analisis & Laporan" @click="tutupSidebarMobile" />
          </nav>

          <!-- TEKNISI -->
          <nav v-else-if="namaPeran === 'teknisi'" class="space-y-1">
            <SidebarLink href="/dashboard/teknisi?view=ringkasan" :aktif="isView('ringkasan')" icon="lucide:home" label="Ringkasan" @click="tutupSidebarMobile" />
            <SidebarLink href="/dashboard/teknisi?view=tugas" :aktif="isView('tugas')" icon="lucide:code-2" label="Tugas Saya" :badge="counts.tugas_saya" @click="tutupSidebarMobile" />
            <SidebarLink href="/dashboard/teknisi?view=bast" :aktif="isView('bast')" icon="lucide:archive" label="Arsip BAST" @click="tutupSidebarMobile" />
            <SidebarLink href="/analytics" :aktif="isPath('/analytics')" icon="lucide:trending-up" label="Analisis & Laporan" @click="tutupSidebarMobile" />
          </nav>

          <!-- ADMIN -->
          <nav v-else-if="namaPeran === 'admin'" class="space-y-1">
            <SidebarLink href="/admin/dashboard" :aktif="isPath('/admin/dashboard')" icon="lucide:home" label="Ringkasan" @click="tutupSidebarMobile" />
            <SidebarLink href="/admin/akun" :aktif="isPath('/admin/akun')" icon="lucide:users" label="Kelola Akun" :badge="counts.akun_tertunda" badge-warna="red" @click="tutupSidebarMobile" />
            <SidebarLink href="/admin/opd" :aktif="isPath('/admin/opd')" icon="lucide:building-2" label="Master OPD" @click="tutupSidebarMobile" />
            <SidebarLink href="/admin/kategori" :aktif="isPath('/admin/kategori')" icon="lucide:tag" label="Master Kategori RFC" @click="tutupSidebarMobile" />
            <SidebarLink href="/admin/laporan" :aktif="isPath('/admin/laporan')" icon="lucide:bar-chart-2" label="Laporan & Statistik" @click="tutupSidebarMobile" />
            <SidebarLink href="/analytics" :aktif="isPath('/analytics')" icon="lucide:trending-up" label="Analisis & Laporan" @click="tutupSidebarMobile" />
          </nav>

          <!-- Fallback -->
          <div v-else class="px-3 py-2 text-xs text-blue-300">
            Peran: {{ namaPeran }}
          </div>
        </div>

        <!-- KELOMPOK 2: PENGATURAN -->
        <div>
          <div class="text-[10px] font-bold tracking-widest text-[#8DB4DE]/75 uppercase px-3 mb-2.5">
            PENGATURAN
          </div>
          <div class="space-y-1">
            <!-- Pengaturan Akun -->
            <SidebarLink
              href="/akun/profil"
              :aktif="isPath('/akun')"
              icon="lucide:settings"
              label="Pengaturan Akun"
              @click="tutupSidebarMobile"
            />

            <!-- Keluar -->
            <Link
              href="/logout"
              method="post"
              as="button"
              @click="tutupSidebarMobile"
              class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-medium text-rose-300 hover:text-rose-200 hover:bg-rose-500/10 transition-all text-left"
            >
              <Icon icon="lucide:log-out" class="w-5 h-5 text-rose-400" />
              <span>Keluar</span>
            </Link>
          </div>
        </div>

      </div>

      <!-- BAGIAN BAWAH SIDEBAR: ILUSTRASI GEDUNG SATE & ORNAMEN JABAR -->
      <div class="relative w-full overflow-hidden select-none pointer-events-none mt-auto pt-2">
        <!-- Gedung Sate Lebih Besar, Tegas & Proporsional -->
        <div class="relative w-full flex items-end justify-center">
          <img
            src="/images/assets/gedung_sate.png"
            alt="Gedung Sate"
            class="w-full max-h-36 sm:max-h-40 h-auto object-contain object-bottom opacity-90 relative z-0 transform origin-bottom scale-110 drop-shadow-md transition-all duration-300"
            style="mask-image: linear-gradient(to top, rgba(0,0,0,1) 80%, rgba(0,0,0,0.1) 100%); -webkit-mask-image: linear-gradient(to top, rgba(0,0,0,1) 80%, rgba(0,0,0,0.1) 100%);"
          />
        </div>

        <!-- ORNAMEN GELOMBANG KIRI BAWAH (RAPI, HARMONIS & SEJAJAR) -->
        <svg
          class="absolute bottom-0 left-0 w-44 h-44 pointer-events-none select-none z-10 opacity-95"
          viewBox="0 0 180 180"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <defs>
            <linearGradient id="sidebarBottomGold" x1="0" y1="180" x2="140" y2="70" gradientUnits="userSpaceOnUse">
              <stop offset="0%" stop-color="#F59E0B" stop-opacity="0.95" />
              <stop offset="50%" stop-color="#FBBF24" stop-opacity="0.95" />
              <stop offset="100%" stop-color="#FCD34D" stop-opacity="0.9" />
            </linearGradient>
            <linearGradient id="sidebarBottomGreen" x1="0" y1="180" x2="110" y2="95" gradientUnits="userSpaceOnUse">
              <stop offset="0%" stop-color="#10B981" stop-opacity="0.95" />
              <stop offset="50%" stop-color="#059669" stop-opacity="0.95" />
              <stop offset="100%" stop-color="#047857" stop-opacity="0.9" />
            </linearGradient>
            <linearGradient id="sidebarBottomDarkGreen" x1="0" y1="180" x2="80" y2="120" gradientUnits="userSpaceOnUse">
              <stop offset="0%" stop-color="#059669" stop-opacity="0.9" />
              <stop offset="100%" stop-color="#064E3B" stop-opacity="0.85" />
            </linearGradient>
          </defs>

          <!-- Pita Kuning / Emas: Melengkung Halus & Presisi -->
          <path d="M0 72C50 76 100 114 138 180H108C76 126 36 98 0 98Z" fill="url(#sidebarBottomGold)" />

          <!-- Pita Hijau Terang: Mengalir Sejajar Tepat di Bawah Pita Emas -->
          <path d="M0 98C36 98 76 126 108 180H82C56 134 24 116 0 120Z" fill="url(#sidebarBottomGreen)" />

          <!-- Pita Hijau Tua / Zamrud: Aksen Elegan Menyatu ke Sudut Bawah -->
          <path d="M0 120C24 116 56 134 82 180H58C36 145 14 135 0 138Z" fill="url(#sidebarBottomDarkGreen)" />
        </svg>
      </div>
    </aside>

    <!-- ========================================================
         WRAPPER KONTEN UTAMA (BERADA DI SEBELAH KANAN SIDEBAR)
    ======================================================== -->
    <div class="flex-1 flex flex-col min-h-screen lg:pl-[284px] transition-all duration-300 ease-in-out">

      <!-- ========================================================
           STICKY NAVBAR CONTAINER (SOLID BACKGROUND HEADER SUPAYA
           KONTEN SCROLL TIDAK PERNAH MENEMBUS/TERLIHAT DI ATAS NAVBAR)
      ======================================================== -->
      <div class="sticky top-0 z-40 -mt-3 sm:-mt-4 lg:-mt-5 pt-3 sm:pt-4 lg:pt-5 pb-3 bg-[#F0F4F8]">
        <header
          class="bg-gradient-to-r from-[#0E7A42] via-[#0D723D] to-[#0B6434] text-white rounded-2xl sm:rounded-[24px] px-4 sm:px-6 py-3.5 flex items-center justify-between gap-4 shadow-md shadow-emerald-950/10 border border-emerald-400/20 relative flex-shrink-0"
        >
          <!-- WRAPPER CLIP ORNAMEN KANAN ATAS (AGAR GELOMBANG TIDAK KELUAR SUDUT NAVBAR) -->
          <div class="absolute inset-0 overflow-hidden rounded-2xl sm:rounded-[24px] pointer-events-none">
            <!-- AKSEN ORNAMEN GELOMBANG ORGANIK KANAN ATAS NAVBAR (KONTRAS TINGGI) -->
            <svg
              class="absolute top-0 right-0 h-full w-56 sm:w-80 pointer-events-none select-none z-0 opacity-85"
              viewBox="0 0 320 80"
              preserveAspectRatio="none"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <defs>
                <linearGradient id="navbarWaveGradient" x1="0" y1="0" x2="320" y2="80" gradientUnits="userSpaceOnUse">
                  <stop offset="0%" stop-color="#FCD34D" stop-opacity="0.9" />
                  <stop offset="60%" stop-color="#F59E0B" stop-opacity="0.75" />
                  <stop offset="100%" stop-color="#D97706" stop-opacity="0.4" />
                </linearGradient>
                <linearGradient id="navbarWaveSoft" x1="60" y1="0" x2="320" y2="60" gradientUnits="userSpaceOnUse">
                  <stop offset="0%" stop-color="#FEF08A" stop-opacity="0.5" />
                  <stop offset="100%" stop-color="#A7F3D0" stop-opacity="0.2" />
                </linearGradient>
              </defs>
              <path d="M0 0C90 55 190 70 320 30V0H0Z" fill="url(#navbarWaveGradient)" />
              <path d="M70 0C140 45 220 55 320 18V0H70Z" fill="url(#navbarWaveSoft)" />
            </svg>
          </div>

          <!-- KIRI: Hamburger Menu (< lg) + Teks "Sistem Informasi Request for Change" -->
          <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0 relative z-10">
            <!-- Tombol Hamburger Mobile (< lg) -->
            <button
              type="button"
              @click="sidebarMobileOpen = !sidebarMobileOpen"
              class="p-2 rounded-xl text-white/90 hover:text-white hover:bg-white/15 transition-colors flex-shrink-0 lg:hidden"
              :aria-label="sidebarMobileOpen ? 'Tutup menu' : 'Buka menu'"
            >
              <Icon icon="lucide:menu" class="w-5 h-5" />
            </button>

            <!-- Teks Sesuai Permintaan Pengguna: Cukup "Sistem Informasi Request for Change" -->
            <h1 class="font-bold text-sm sm:text-base md:text-lg text-white tracking-tight leading-tight truncate">
              Sistem Informasi Request for Change
            </h1>
          </div>

          <!-- KANAN: Lonceng Notifikasi + Profil Pengguna -->
          <div class="flex items-center gap-2.5 sm:gap-4 flex-shrink-0 relative z-10">
            <!-- Notifikasi Lonceng -->
            <NotifikasiBell
              :jumlah-belum-dibaca="$page.props.notifikasi_belum_dibaca ?? 0"
              button-class="relative p-2 text-white hover:bg-white/15 rounded-xl transition-colors"
            />

            <!-- Pembatas Halus -->
            <div class="h-6 w-px bg-white/20 hidden sm:block"></div>

            <!-- Avatar & Nama Pengguna -->
            <div class="flex items-center gap-2 sm:gap-3">
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white text-[#0E7A42] flex items-center justify-center font-black text-xs ring-2 ring-white/30 shadow-xs flex-shrink-0">
                {{ authUser?.inisial ?? 'U' }}
              </div>
              <div class="hidden sm:block text-left">
                <div class="font-bold text-xs sm:text-sm text-white leading-tight truncate max-w-[150px] md:max-w-[220px]">
                  {{ authUser?.nama_lengkap ?? 'Pengguna' }}
                </div>
                <div class="text-[11px] text-emerald-100/85 font-medium capitalize mt-0.5">
                  {{ labelPeran }}
                </div>
              </div>
            </div>
          </div>
        </header>
      </div>

      <!-- FLASH MESSAGE (JIKA ADA) -->
      <Transition name="flash-fade">
        <div
          v-if="flashMsg"
          :class="[
            'mb-4 p-3.5 rounded-2xl text-sm font-medium flex items-center justify-between shadow-xs',
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

      <!-- ========================================================
           KONTEN HALAMAN (TIDAK MENGUBAH APAPUN DI DALAMNYA)
      ======================================================== -->
      <main class="flex-1 min-w-0">
        <slot />
      </main>

      <!-- ========================================================
           FOOTER HALAMAN BERSIH & PROPORSIONAL
      ======================================================== -->
      <footer class="pt-6 pb-2 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2 mt-auto border-t border-slate-200/60">
        <div>&copy; 2026 Diskominfo Jawa Barat. Semua hak dilindungi.</div>
        <div class="text-slate-400 font-medium">Sistem Informasi Request for Change (SI-RFC) &middot; ITIL &middot; ISO/IEC 27001</div>
      </footer>

    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, provide } from 'vue'
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

// ── Sidebar State (Mobile Drawer) ─────────────────────────────────────────────
const sidebarMobileOpen = ref(false)

// Sediakan state sidebarCollapsed untuk SidebarLink agar kompatibel
provide('sidebarCollapsed', ref(false))

function tutupSidebarMobile() {
  sidebarMobileOpen.value = false
}

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

// ── Route helpers (Reactive terhadap Inertia navigation) ──────────────────────
function isPath(path) {
  const url = page.url || ''
  const pathname = url.split('?')[0]
  return pathname === path || pathname.startsWith(path + '/')
}

function isView(view) {
  const url = page.url || ''
  const queryString = url.includes('?') ? url.split('?')[1] : ''
  const params = new URLSearchParams(queryString)
  const v = params.get('view')
  const pathname = url.split('?')[0]
  if (!v && view === 'ringkasan') {
    return pathname.includes('dashboard') || pathname.startsWith('/admin/')
  }
  return v === view
}
</script>

<style scoped>
/* Overlay fade */
.overlay-fade-enter-active,
.overlay-fade-leave-active { transition: opacity 0.2s ease; }
.overlay-fade-enter-from,
.overlay-fade-leave-to     { opacity: 0; }

/* Flash */
.flash-fade-enter-active, .flash-fade-leave-active { transition: all 0.3s ease; }
.flash-fade-enter-from, .flash-fade-leave-to       { opacity: 0; transform: translateY(-8px); }
</style>
