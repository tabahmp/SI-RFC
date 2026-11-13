<template>
  <Link
    :href="href"
    :class="[
      'flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all',
      aktif ? 'bg-[#F4CE18] text-[#073B70] shadow-sm' : 'text-[#E4F0FC] hover:bg-white/10 font-medium'
    ]"
  >
    <div class="flex items-center gap-2.5">
      <Icon
        :icon="icon"
        :class="aktif ? 'text-[#073B70]' : 'text-[#A9C9E8]'"
        class="w-4 h-4 flex-shrink-0"
      />
      <span>{{ label }}</span>
    </div>
    <span
      v-if="badge > 0"
      :class="[
        'text-[10px] px-2 py-0.5 rounded-md font-extrabold flex-shrink-0',
        badgeWarna === 'red'
          ? 'bg-red-500 text-white'
          : aktif
            ? 'bg-[#285981] text-[#E4F0FC]'
            : 'bg-[#1B4A7A] text-[#E4F0FC]'
      ]"
    >{{ badge }}</span>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href:       { type: String,  required: true },
  aktif:      { type: Boolean, default: false },
  icon:       { type: String,  required: true },
  label:      { type: String,  required: true },
  badge:      { type: Number,  default: 0 },
  badgeWarna: { type: String,  default: 'blue' },
})
</script>