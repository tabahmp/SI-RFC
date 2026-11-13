<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        :aria-labelledby="`modal-title-${uid}`"
      >
        <!-- Overlay -->
        <div
          class="absolute inset-0 bg-black/50 backdrop-blur-sm"
          @click="$emit('batal')"
        ></div>

        <!-- Panel -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 animate-in zoom-in-95 duration-200">
          <!-- Ikon -->
          <div class="flex justify-center mb-4">
            <div
              :class="[
                'w-14 h-14 rounded-full flex items-center justify-center',
                tipeKonfirmasi === 'danger'  ? 'bg-red-100'    :
                tipeKonfirmasi === 'warning' ? 'bg-amber-100'  : 'bg-green-100'
              ]"
            >
              <Icon
                :icon="
                  tipeKonfirmasi === 'danger'  ? 'lucide:alert-triangle' :
                  tipeKonfirmasi === 'warning' ? 'lucide:alert-circle'   : 'lucide:check-circle'
                "
                :class="[
                  'w-7 h-7',
                  tipeKonfirmasi === 'danger'  ? 'text-red-600'    :
                  tipeKonfirmasi === 'warning' ? 'text-amber-600'  : 'text-green-600'
                ]"
              />
            </div>
          </div>

          <!-- Konten -->
          <div class="text-center mb-6">
            <h3 :id="`modal-title-${uid}`" class="text-base font-bold text-slate-800 mb-1.5">{{ judul }}</h3>
            <p class="text-sm text-slate-500 leading-relaxed">{{ pesan }}</p>
          </div>

          <!-- Tombol -->
          <div class="flex gap-3">
            <button
              type="button"
              @click="$emit('batal')"
              class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors"
            >
              Batal
            </button>
            <button
              type="button"
              @click="$emit('konfirmasi')"
              :class="[
                'flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition-colors',
                tipeKonfirmasi === 'danger'  ? 'bg-red-600 hover:bg-red-700'       :
                tipeKonfirmasi === 'warning' ? 'bg-amber-500 hover:bg-amber-600'   : 'bg-green-600 hover:bg-green-700'
              ]"
            >
              {{ labelKonfirmasi }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { getCurrentInstance } from 'vue'

defineProps({
  show:             { type: Boolean, default: false },
  judul:            { type: String,  default: 'Konfirmasi Tindakan' },
  pesan:            { type: String,  default: 'Apakah Anda yakin ingin melanjutkan?' },
  labelKonfirmasi:  { type: String,  default: 'Ya, Lanjutkan' },
  tipeKonfirmasi:   { type: String,  default: 'danger' },   // danger | warning | success
})

defineEmits(['konfirmasi', 'batal'])

const uid = getCurrentInstance()?.uid ?? Math.random().toString(36).slice(2)
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to    { opacity: 0; }
</style>
