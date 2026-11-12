<template>
  <DashboardPemohon v-if="currentRole === 'pemohon'" :tikets="tikets" />
  <DashboardVerifikator v-else-if="currentRole === 'verifikator'" :tikets="tikets" :users-pemohon="usersPemohon" />
  <DashboardPimpinan v-else-if="currentRole === 'pimpinan'" :tikets="tikets" />
  <DashboardTeknisi v-else-if="currentRole === 'teknisi'" :tikets="tikets" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import DashboardPemohon from '@/Pages/Dashboard/Pemohon.vue'
import DashboardVerifikator from '@/Pages/Dashboard/Verifikator.vue'
import DashboardPimpinan from '@/Pages/Dashboard/Pimpinan.vue'
import DashboardTeknisi from '@/Pages/Dashboard/Teknisi.vue'

const props = defineProps({
  tikets: {
    type: Array,
    default: () => []
  },
  usersPemohon: {
    type: Array,
    default: () => []
  }
})

const currentRole = ref('pemohon')

onMounted(() => {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search)
    const roleParam = params.get('role')
    if (roleParam && ['pemohon', 'verifikator', 'pimpinan', 'teknisi'].includes(roleParam)) {
      currentRole.value = roleParam
    } else {
      const saved = localStorage.getItem('si_rfc_role')
      if (saved && ['pemohon', 'verifikator', 'pimpinan', 'teknisi'].includes(saved)) {
        currentRole.value = saved
      }
    }
  }
})
</script>
