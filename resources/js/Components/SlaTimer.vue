<template>
  <span :class="['inline-flex items-center gap-1 text-xs font-semibold', warnaKelas]">
    <Icon :icon="ikon" class="w-3.5 h-3.5" />
    <span>{{ label }}</span>
  </span>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  targetSla: { type: String, default: null }  // ISO8601 string
})

const sisaMs  = ref(0)
let interval  = null

function hitung() {
  if (!props.targetSla) { sisaMs.value = -1; return }
  const target = new Date(props.targetSla).getTime()
  sisaMs.value = target - Date.now()
}

onMounted(() => {
  hitung()
  interval = setInterval(hitung, 60_000)
})
onUnmounted(() => clearInterval(interval))

const label = computed(() => {
  if (!props.targetSla) return 'Tidak ada SLA'
  if (sisaMs.value < 0) return 'SLA Terlampaui'
  const totalMenit = Math.floor(sisaMs.value / 60_000)
  const jam  = Math.floor(totalMenit / 60)
  const mnt  = totalMenit % 60
  if (jam > 48) return `${Math.floor(jam / 24)} hari tersisa`
  if (jam > 0)  return `${jam} jam ${mnt} mnt tersisa`
  return `${mnt} menit tersisa`
})

const warnaKelas = computed(() => {
  if (!props.targetSla || sisaMs.value < 0) return 'text-red-700'
  const jam = sisaMs.value / 3_600_000
  if (jam < 2)  return 'text-red-700'
  if (jam < 24) return 'text-amber-700'
  return 'text-green-700'
})

const ikon = computed(() => {
  if (sisaMs.value < 0) return 'lucide:alarm-clock-off'
  const jam = sisaMs.value / 3_600_000
  if (jam < 2) return 'lucide:alarm-clock'
  return 'lucide:clock'
})
</script>
