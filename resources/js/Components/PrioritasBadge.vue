<template>
  <span :class="cls">{{ prioritas }}</span>
</template>

<script setup>
const props = defineProps({ prioritas: String })

const cls = {
  'P1 - Kritis': 'badge-p1',
  'P2 - Tinggi': 'badge-p2',
  'P3 - Sedang': 'badge-p3',
  'P4 - Rendah': 'badge-p4',
}[props.prioritas] || 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600'
</script>

