<template>
  <div :class="['rounded-xl px-4 py-2 text-center', color === 'white' ? 'bg-white/20' : color === 'green' ? 'bg-emerald-500/30' : 'bg-yellow-400/30']">
    <div class="text-2xl font-extrabold text-white">{{ value }}</div>
    <div class="text-xs text-blue-100 font-medium">{{ label }}</div>
  </div>
</template>

<script setup>
defineProps({ label: String, value: Number, color: String })
</script>

