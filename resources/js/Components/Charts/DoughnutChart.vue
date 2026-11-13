<template>
  <div class="chart-container">
    <Doughnut :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  ArcElement,
  Title,
  Tooltip,
  Legend
)

const props = defineProps({
  labels: {
    type: Array,
    required: true
  },
  data: {
    type: Array,
    required: true
  },
  backgroundColor: {
    type: Array,
    default: () => [
      'rgba(59, 130, 246, 0.8)',   // blue
      'rgba(16, 185, 129, 0.8)',   // green
      'rgba(251, 146, 60, 0.8)',   // orange
      'rgba(239, 68, 68, 0.8)',    // red
      'rgba(168, 85, 247, 0.8)',   // purple
      'rgba(236, 72, 153, 0.8)',   // pink
    ]
  },
  title: {
    type: String,
    default: ''
  },
  cutout: {
    type: String,
    default: '60%'
  },
  height: {
    type: Number,
    default: 300
  }
})

const chartData = {
  labels: props.labels,
  datasets: [{
    data: props.data,
    backgroundColor: props.backgroundColor,
    borderWidth: 2,
    borderColor: '#fff'
  }]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: props.cutout,
  plugins: {
    legend: {
      display: true,
      position: 'bottom',
    },
    title: {
      display: !!props.title,
      text: props.title,
      font: {
        size: 16,
        weight: 'bold'
      }
    },
    tooltip: {
      callbacks: {
        label: function(context) {
          const label = context.label || '';
          const value = context.parsed || 0;
          const total = context.dataset.data.reduce((a, b) => a + b, 0);
          const percentage = ((value / total) * 100).toFixed(1);
          return `${label}: ${value} (${percentage}%)`;
        }
      }
    }
  }
}
</script>

<style scoped>
.chart-container {
  position: relative;
  width: 100%;
}
</style>
