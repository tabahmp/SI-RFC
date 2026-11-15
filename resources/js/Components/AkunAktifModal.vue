<template>
  <Teleport to="body">
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
        @click.self="tutup"
      >
        <!-- WRAPPER KARTU STATUS: Desain Presisi Identik RegistrationStatus.vue -->
        <div
          class="w-full max-w-[820px] bg-white rounded-2xl sm:rounded-[28px] border border-[#E2E8F0] shadow-[0_20px_60px_0_rgba(15,23,42,0.25)] relative flex flex-col overflow-hidden p-6 sm:p-9 space-y-4 my-auto animate-in fade-in zoom-in-95 duration-200"
          style="max-height: calc(100vh - 2rem);"
        >
          <!-- Top Strip Aksen Jawa Barat (Khas Diskominfo Jabar) -->
          <div class="absolute top-0 left-0 right-0 h-[4px] flex z-10">
            <div class="w-1/2 bg-[#0757A8]"></div>
            <div class="w-1/4 bg-[#F4CE18]"></div>
            <div class="w-1/4 bg-[#16834A]"></div>
          </div>

          <!-- Tombol Tutup (X) -->
          <button
            type="button"
            @click="tutup"
            class="absolute top-4 right-4 sm:top-5 sm:right-6 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors z-20"
            title="Tutup dialog"
          >
            <Icon icon="lucide:x" class="w-4 h-4" />
          </button>

          <!-- BAGIAN 1: Logo & Header Judul -->
          <div class="space-y-3">
            <div class="flex items-center gap-3.5">
              <img
                src="/images/assets/logo_rfc_asli.png"
                alt="Logo Request for Change"
                class="h-9 sm:h-10 w-auto object-contain"
              />
              <div class="h-6 w-[1px] bg-slate-200"></div>
              <img
                src="/images/assets/logo_diskominfo_jabar.png"
                alt="Logo Diskominfo Jabar"
                class="h-7 sm:h-8 w-auto object-contain"
              />
            </div>

            <div>
              <div class="text-[10.5px] font-bold text-[#0757A8] tracking-wider uppercase">
                REGISTRASI PEMOHON ORGANISASI PERANGKAT DAERAH
              </div>
              <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight mt-0.5 leading-snug">
                Akun Anda Telah Aktif & Siap Mengajukan
              </h1>
              <p class="text-[11.5px] sm:text-[13px] text-[#64748B] mt-0.5 leading-relaxed">
                Akun kedinasan Anda telah diverifikasi oleh administrator. Akses pengajuan RFC dan layanan perubahan TI Jawa Barat telah siap digunakan.
              </p>
            </div>
          </div>

          <!-- BAGIAN 2: Alert Status Berhasil Diaktivasi (Emerald/Hijau Khas Diskominfo) -->
          <div class="my-2.5 p-3 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] flex items-start gap-2.5">
            <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-700 mt-0.5">
              <Icon icon="lucide:check-circle-2" class="w-4 h-4" />
            </div>
            <div class="space-y-0.5">
              <div class="text-[10.5px] font-bold text-[#065F46] tracking-wider uppercase">
                AKUN RESMI DIAKTIVASI OLEH ADMINISTRATOR
              </div>
              <div class="text-[11.5px] text-[#065F46]/90 leading-relaxed">
                Status akun Anda saat ini <strong>Aktif</strong>. Anda sekarang memiliki hak akses penuh untuk menyusun formulir permohonan RFC baru dan memantau perkembangannya secara langsung.
              </div>
            </div>
          </div>

          <!-- BAGIAN 3: Ringkasan Akun Pemohon -->
          <div class="p-3 sm:p-3.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-2">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
              <div>
                <div class="text-[10.5px] font-medium text-[#64748B]">Nama pemohon</div>
                <div class="text-[13px] font-semibold text-[#334155] mt-0.5 truncate">
                  {{ dataAkun?.nama || '-' }}
                </div>
              </div>
              <div>
                <div class="text-[10.5px] font-medium text-[#64748B]">Email dinas</div>
                <div class="text-[13px] font-semibold text-[#334155] mt-0.5 truncate">
                  {{ dataAkun?.email || '-' }}
                </div>
              </div>
            </div>
            <div class="pt-2 border-t border-[#E2E8F0]/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-[#64748B]">
              <div class="flex items-center gap-1.5 truncate">
                <Icon icon="lucide:building-2" class="w-3.5 h-3.5 text-[#0757A8] flex-shrink-0" />
                <span class="truncate">Organisasi Perangkat Daerah: <strong class="text-[#334155] font-semibold">{{ dataAkun?.opd || '-' }}</strong></span>
              </div>
              <div v-if="dataAkun?.unit_kerja" class="flex items-center gap-1.5 truncate">
                <Icon icon="lucide:layers" class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" />
                <span class="truncate">Unit Kerja: <strong class="text-[#334155] font-semibold">{{ dataAkun.unit_kerja }}</strong></span>
              </div>
            </div>
          </div>

          <!-- BAGIAN 4: Alur Proses Verifikasi (Stepper 3 Tahap: SEMUA SELESAI / HIJAU) -->
          <div class="my-1 py-1 space-y-2.5">
            <!-- Tahap 1: Selesai -->
            <div class="flex items-start gap-3">
              <div class="w-6 h-6 rounded-full bg-[#047857] flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                <Icon icon="lucide:check" class="w-3.5 h-3.5 stroke-[3]" />
              </div>
              <div class="leading-tight">
                <div class="text-xs sm:text-[13px] font-bold text-[#047857]">
                  Formulir registrasi dikirim
                </div>
                <div class="text-[11px] sm:text-xs text-[#64748B] mt-0.5">
                  Identitas dan kontak pemohon berhasil diterima oleh sistem.
                </div>
              </div>
            </div>

            <!-- Tahap 2: Selesai / Disetujui -->
            <div class="flex items-start gap-3">
              <div class="w-6 h-6 rounded-full bg-[#047857] flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                <Icon icon="lucide:check" class="w-3.5 h-3.5 stroke-[3]" />
              </div>
              <div class="leading-tight">
                <div class="text-xs sm:text-[13px] font-bold text-[#047857]">
                  Pemeriksaan administrator: Disetujui
                </div>
                <div class="text-[11px] sm:text-xs text-[#64748B] mt-0.5">
                  Kesesuaian identitas dan Organisasi Perangkat Daerah telah diverifikasi dan disetujui.
                </div>
              </div>
            </div>

            <!-- Tahap 3: Aktif & Siap Mengajukan -->
            <div class="flex items-start gap-3">
              <div class="w-6 h-6 rounded-full bg-[#047857] flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                <Icon icon="lucide:check" class="w-3.5 h-3.5 stroke-[3]" />
              </div>
              <div class="leading-tight">
                <div class="text-xs sm:text-[13px] font-bold text-[#047857]">
                  Akun aktif & siap mengajukan
                </div>
                <div class="text-[11px] sm:text-xs text-[#64748B] mt-0.5">
                  Akun telah resmi aktif. Pemohon siap membuat dan mengajukan formulir RFC ke Diskominfo Jawa Barat.
                </div>
              </div>
            </div>
          </div>

          <!-- BAGIAN 5: Tombol Aksi (Mulai Buat RFC & Lanjut ke Dashboard) -->
          <div class="pt-2 flex flex-col sm:flex-row gap-2.5">
            <Link
              href="/rfc/baru"
              @click="tutup"
              class="w-full sm:flex-1 h-11 flex items-center justify-center gap-2 bg-[#0757A8] hover:bg-[#063D78] text-white rounded-lg font-semibold text-xs sm:text-sm shadow-sm transition-all text-center tracking-wide"
            >
              <span>Mulai ajukan RFC sekarang</span>
              <Icon icon="lucide:arrow-right" class="w-4 h-4" />
            </Link>
            <button
              type="button"
              @click="tutup"
              class="w-full sm:w-auto px-5 h-11 flex items-center justify-center gap-2 border border-[#CBD5E1] bg-white hover:bg-slate-50 text-[#334155] rounded-lg font-semibold text-xs sm:text-sm shadow-2xs transition-all"
            >
              <Icon icon="lucide:layout-dashboard" class="w-4 h-4 text-slate-500" />
              <span>Lanjut ke dashboard</span>
            </button>
          </div>

          <!-- FOOTER KARTU -->
          <div class="text-center text-[11px] text-[#64748B] pt-2 border-t border-[#E2E8F0]/70 leading-relaxed">
            Butuh penyesuaian data akun? Hubungi administrator melalui kanal kedinasan Diskominfo Provinsi Jawa Barat.
          </div>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import axios from 'axios'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  dataAkun: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits(['update:modelValue', 'close'])

const show = ref(props.modelValue)

// Watch internal show with props
import { watch } from 'vue'
watch(() => props.modelValue, (val) => {
  show.value = val
})

const tutup = () => {
  show.value = false
  emit('update:modelValue', false)
  emit('close')

  // Kirim sinyal ke server agar flag modal_aktivasi_dilihat_pada terisi
  try {
    axios.post('/pemohon/tandai-modal-aktivasi-dilihat').catch(() => {})
  } catch (e) {
    // Abaikan jika offline
  }
}
</script>
