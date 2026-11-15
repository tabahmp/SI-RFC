<template>
  <Link
    :href="href"
    :title="isCollapsed ? label : undefined"
    :class="[
      'flex items-center rounded-xl text-[13px] transition-all relative group',
      isCollapsed ? 'justify-center p-3' : 'justify-between px-3.5 py-2.5',
      aktif
        ? 'bg-gradient-to-r from-[#E5A91A] to-[#F2B726] text-[#0A2342] font-bold shadow-sm shadow-black/10'
        : 'text-[#D5E3F5] hover:bg-white/[0.08] hover:text-white font-medium'
    ]"
  >
    <div class="flex items-center gap-3" :class="{ 'justify-center': isCollapsed }">
      <Icon
        :icon="icon"
        :class="aktif ? 'text-[#082247]' : 'text-[#8DB4DE] group-hover:text-white'"
        class="w-5 h-5 flex-shrink-0 transition-colors"
      />
      <span v-if="!isCollapsed" class="whitespace-nowrap">{{ label }}</span>
    </div>

    <!-- Badge saat expanded -->
    <span
      v-if="!isCollapsed && badge > 0"
      :class="[
        'text-[10px] px-2 py-0.5 rounded-full font-black flex-shrink-0',
        badgeWarna === 'red'
          ? 'bg-red-500 text-white'
          : aktif
            ? 'bg-[#082247] text-[#F4B400]'
            : 'bg-[#153B68] text-white'
      ]"
    >{{ badge }}</span>

    <!-- Badge dot saat collapsed -->
    <span
      v-if="isCollapsed && badge > 0"
      class="absolute top-2 right-2 w-2 h-2 rounded-full ring-1 ring-[#082247]"
      :class="badgeWarna === 'red' ? 'bg-red-500' : 'bg-[#F4B400]'"
    ></span>
  </Link>
</template>

<script setup>
import { inject, computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

const props = defineProps({
  href:       { type: String,  required: true },
  aktif:      { type: Boolean, default: false },
  icon:       { type: String,  required: true },
  label:      { type: String,  required: true },
  badge:      { type: Number,  default: 0 },
  badgeWarna: { type: String,  default: 'blue' },
  collapsed:  { type: Boolean, default: undefined },
})

const injectedCollapsed = inject('sidebarCollapsed', ref(false))
const isCollapsed = computed(() => {
  if (props.collapsed !== undefined) return props.collapsed
  return injectedCollapsed.value
})
</script>
