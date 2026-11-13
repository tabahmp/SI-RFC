<template>
  <AppLayout title="Analytics & Reports">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-900">Analytics & Reports</h1>
          <p class="mt-1 text-sm text-gray-600">Visualisasi data dan statistik RFC</p>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
              <input
                type="date"
                v-model="filters.start_date"
                @change="fetchAllData"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
              <input
                type="date"
                v-model="filters.end_date"
                @change="fetchAllData"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Periode Trend</label>
              <select
                v-model="filters.trend_months"
                @change="fetchTrendData"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
                <option :value="3">3 Bulan</option>
                <option :value="6">6 Bulan</option>
                <option :value="12">12 Bulan</option>
              </select>
            </div>
            <div class="flex items-end">
              <button
                @click="resetFilters"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition"
              >
                Reset Filter
              </button>
            </div>
          </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
          <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                <Icon icon="mdi:file-document-multiple" class="w-6 h-6 text-blue-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total RFC</p>
                <p class="text-2xl font-bold text-gray-900">{{ summary.total_rfc || 0 }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                <Icon icon="mdi:clock-outline" class="w-6 h-6 text-yellow-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Menunggu Approval</p>
                <p class="text-2xl font-bold text-gray-900">{{ summary.pending_approval || 0 }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-cyan-100 rounded-md p-3">
                <Icon icon="mdi:progress-wrench" class="w-6 h-6 text-cyan-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Sedang Dikerjakan</p>
                <p class="text-2xl font-bold text-gray-900">{{ summary.in_progress || 0 }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                <Icon icon="mdi:alert-circle" class="w-6 h-6 text-red-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Overdue</p>
                <p class="text-2xl font-bold text-gray-900">{{ summary.overdue || 0 }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- RFC by Status -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">RFC berdasarkan Status</h3>
            <div v-if="loading" class="h-64 flex items-center justify-center">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <div v-else style="height: 300px">
              <DoughnutChart
                :labels="statusData.labels"
                :data="statusData.data"
                title=""
              />
            </div>
          </div>

          <!-- RFC by Urgency -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">RFC berdasarkan Urgensi</h3>
            <div v-if="loading" class="h-64 flex items-center justify-center">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <div v-else style="height: 300px">
              <PieChart
                :labels="urgencyData.labels"
                :data="urgencyData.data"
                :backgroundColor="['#10b981', '#f59e0b', '#f97316', '#ef4444']"
                title=""
              />
            </div>
          </div>

          <!-- RFC Trend -->
          <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Trend RFC ({{ filters.trend_months }} Bulan Terakhir)</h3>
            <div v-if="loading" class="h-64 flex items-center justify-center">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <div v-else style="height: 300px">
              <LineChart
                :labels="trendData.labels"
                :datasets="[
                  {
                    label: 'Jumlah RFC',
                    data: trendData.data,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true
                  }
                ]"
                title=""
              />
            </div>
          </div>

          <!-- RFC by Category -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">RFC berdasarkan Kategori</h3>
            <div v-if="loading" class="h-64 flex items-center justify-center">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <div v-else style="height: 300px">
              <BarChart
                :labels="categoryData.labels"
                :datasets="[
                  {
                    label: 'Jumlah RFC',
                    data: categoryData.data,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)'
                  }
                ]"
                title=""
              />
            </div>
          </div>

          <!-- RFC by OPD -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Top 10 OPD</h3>
            <div v-if="loading" class="h-64 flex items-center justify-center">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <div v-else style="height: 300px">
              <BarChart
                :labels="opdData.labels"
                :datasets="[
                  {
                    label: 'Jumlah RFC',
                    data: opdData.data,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)'
                  }
                ]"
                :horizontal="true"
                title=""
              />
            </div>
          </div>

          <!-- Completion & SLA Stats -->
          <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Statistik Completion & SLA</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">Completion Rate</h4>
                <div class="space-y-3">
                  <div>
                    <div class="flex justify-between text-sm mb-1">
                      <span>Selesai</span>
                      <span class="font-semibold">{{ completionData.selesai || 0 }} ({{ completionData.completion_percentage || 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-green-600 h-2 rounded-full" :style="{ width: (completionData.completion_percentage || 0) + '%' }"></div>
                    </div>
                  </div>
                  <div>
                    <div class="flex justify-between text-sm mb-1">
                      <span>Ditolak</span>
                      <span class="font-semibold">{{ completionData.ditolak || 0 }} ({{ completionData.rejection_percentage || 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-red-600 h-2 rounded-full" :style="{ width: (completionData.rejection_percentage || 0) + '%' }"></div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">SLA Compliance</h4>
                <div class="space-y-3">
                  <div>
                    <div class="flex justify-between text-sm mb-1">
                      <span>On Time</span>
                      <span class="font-semibold">{{ slaData.on_time || 0 }} ({{ slaData.compliance_percentage || 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-green-600 h-2 rounded-full" :style="{ width: (slaData.compliance_percentage || 0) + '%' }"></div>
                    </div>
                  </div>
                  <div>
                    <div class="flex justify-between text-sm mb-1">
                      <span>Overdue</span>
                      <span class="font-semibold text-red-600">{{ slaData.overdue || 0 }}</span>
                    </div>
                  </div>
                  <div>
                    <div class="flex justify-between text-sm mb-1">
                      <span>Pending Overdue</span>
                      <span class="font-semibold text-orange-600">{{ slaData.pending_overdue || 0 }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Icon } from '@iconify/vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import LineChart from '@/Components/Charts/LineChart.vue'
import BarChart from '@/Components/Charts/BarChart.vue'
import PieChart from '@/Components/Charts/PieChart.vue'
import DoughnutChart from '@/Components/Charts/DoughnutChart.vue'
import axios from 'axios'

const loading = ref(true)
const filters = ref({
  start_date: null,
  end_date: null,
  trend_months: 6
})

const summary = ref({})
const statusData = ref({ labels: [], data: [] })
const urgencyData = ref({ labels: [], data: [] })
const trendData = ref({ labels: [], data: [] })
const categoryData = ref({ labels: [], data: [] })
const opdData = ref({ labels: [], data: [] })
const completionData = ref({})
const slaData = ref({})

const fetchAllData = async () => {
  loading.value = true
  
  try {
    const params = {
      start_date: filters.value.start_date,
      end_date: filters.value.end_date
    }
    
    const [
      summaryRes,
      statusRes,
      urgencyRes,
      trendRes,
      categoryRes,
      opdRes,
      completionRes,
      slaRes
    ] = await Promise.all([
      axios.get('/api/analytics/dashboard-summary', { params }),
      axios.get('/api/analytics/rfc-by-status', { params }),
      axios.get('/api/analytics/rfc-by-urgency', { params }),
      axios.get('/api/analytics/rfc-trend', { params: { months: filters.value.trend_months } }),
      axios.get('/api/analytics/rfc-by-category', { params }),
      axios.get('/api/analytics/rfc-by-opd', { params: { ...params, limit: 10 } }),
      axios.get('/api/analytics/completion-rate', { params }),
      axios.get('/api/analytics/sla-compliance', { params })
    ])
    
    summary.value = summaryRes.data
    statusData.value = statusRes.data
    urgencyData.value = urgencyRes.data
    trendData.value = trendRes.data
    categoryData.value = categoryRes.data
    opdData.value = opdRes.data
    completionData.value = completionRes.data
    slaData.value = slaRes.data
  } catch (error) {
    console.error('Error fetching analytics:', error)
  } finally {
    loading.value = false
  }
}

const fetchTrendData = async () => {
  try {
    const res = await axios.get('/api/analytics/rfc-trend', {
      params: { months: filters.value.trend_months }
    })
    trendData.value = res.data
  } catch (error) {
    console.error('Error fetching trend data:', error)
  }
}

const resetFilters = () => {
  filters.value = {
    start_date: null,
    end_date: null,
    trend_months: 6
  }
  fetchAllData()
}

onMounted(() => {
  fetchAllData()
})
</script>
