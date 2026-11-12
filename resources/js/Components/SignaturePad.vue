<template>
  <div class="relative">
    <div
      class="border-2 border-dashed border-slate-300 rounded-lg bg-white overflow-hidden relative"
      style="height: 160px;"
    >
      <canvas
        ref="canvasRef"
        class="w-full h-full cursor-crosshair block"
        @mousedown="startDraw"
        @mousemove="draw"
        @mouseup="stopDraw"
        @mouseleave="stopDraw"
        @touchstart.prevent="startDrawTouch"
        @touchmove.prevent="drawTouch"
        @touchend="stopDraw"
      ></canvas>
      <div
        v-if="isEmpty"
        class="absolute inset-0 flex items-center justify-center pointer-events-none"
      >
        <span class="text-slate-400 text-sm select-none">Tanda tangan di sini...</span>
      </div>
    </div>
    <div class="flex items-center justify-between mt-1">
      <span class="text-xs text-slate-400 italic">Gunakan mouse atau sentuh layar untuk menandatangani</span>
      <button
        type="button"
        @click="clear"
        class="text-xs text-red-600 hover:text-red-700 font-medium transition-colors"
      >
        ✕ Hapus Tanda Tangan
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['update:modelValue'])

const canvasRef = ref(null)
const isDrawing = ref(false)
const isEmpty = ref(true)
let ctx = null

onMounted(() => {
  const canvas = canvasRef.value
  canvas.width = canvas.offsetWidth
  canvas.height = canvas.offsetHeight
  ctx = canvas.getContext('2d')
  ctx.strokeStyle = '#0f172a'
  ctx.lineWidth = 2
  ctx.lineCap = 'round'
  ctx.lineJoin = 'round'

  // If initial value provided, draw it
  if (props.modelValue) {
    loadImageFromDataURL(props.modelValue)
  }
})

watch(
  () => props.modelValue,
  (val) => {
    if (!val) {
      clearCanvas()
    } else if (val && isEmpty.value) {
      loadImageFromDataURL(val)
    }
  }
)

function loadImageFromDataURL(dataURL) {
  const img = new Image()
  img.onload = () => {
    ctx.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height)
    ctx.drawImage(img, 0, 0, canvasRef.value.width, canvasRef.value.height)
    isEmpty.value = false
  }
  img.src = dataURL
}

function getPos(e, canvas) {
  const rect = canvas.getBoundingClientRect()
  return {
    x: (e.clientX - rect.left) * (canvas.width / rect.width),
    y: (e.clientY - rect.top) * (canvas.height / rect.height),
  }
}

function startDraw(e) {
  isDrawing.value = true
  const pos = getPos(e, canvasRef.value)
  ctx.beginPath()
  ctx.moveTo(pos.x, pos.y)
}

function draw(e) {
  if (!isDrawing.value) return
  const pos = getPos(e, canvasRef.value)
  ctx.lineTo(pos.x, pos.y)
  ctx.stroke()
  isEmpty.value = false
}

function stopDraw() {
  if (!isDrawing.value) return
  isDrawing.value = false
  emit('update:modelValue', canvasRef.value.toDataURL('image/png'))
}

function startDrawTouch(e) {
  startDraw(e.touches[0])
}

function drawTouch(e) {
  draw(e.touches[0])
}

function clearCanvas() {
  if (!ctx || !canvasRef.value) return
  ctx.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height)
  isEmpty.value = true
}

function clear() {
  clearCanvas()
  emit('update:modelValue', null)
}
</script>
