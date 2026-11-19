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

function applyBrushStyle(context) {
  if (!context) return
  context.strokeStyle = '#0f172a'
  context.lineWidth = 2.8
  context.lineCap = 'round'
  context.lineJoin = 'round'
}

onMounted(() => {
  const canvas = canvasRef.value
  canvas.width = canvas.offsetWidth
  canvas.height = canvas.offsetHeight
  ctx = canvas.getContext('2d')
  applyBrushStyle(ctx)

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
    const canvas = canvasRef.value
    if (!canvas || !ctx) return
    ctx.clearRect(0, 0, canvas.width, canvas.height)

    // Gambar secara proporsional & presisi di tengah canvas (tanpa distorsi stretch)
    const pad = 16
    const maxW = canvas.width - pad * 2
    const maxH = canvas.height - pad * 2
    const scale = Math.min(1, maxW / img.width, maxH / img.height)
    const drawW = img.width * scale
    const drawH = img.height * scale
    const drawX = (canvas.width - drawW) / 2
    const drawY = (canvas.height - drawH) / 2

    ctx.drawImage(img, drawX, drawY, drawW, drawH)
    applyBrushStyle(ctx)
    isEmpty.value = false
  }
  img.src = dataURL
}

/**
 * Potong otomatis ruang kosong transparan di sekeliling goresan tanda tangan
 * agar hasil tanda tangan tampil besar, penuh, dan presisi di tengah dokumen.
 */
function getCroppedDataURL(canvas) {
  const w = canvas.width
  const h = canvas.height
  const context = canvas.getContext('2d')
  const imgData = context.getImageData(0, 0, w, h).data

  let minX = w
  let minY = h
  let maxX = 0
  let maxY = 0
  let found = false

  for (let y = 0; y < h; y++) {
    for (let x = 0; x < w; x++) {
      const alpha = imgData[(y * w + x) * 4 + 3]
      if (alpha > 15) {
        found = true
        if (x < minX) minX = x
        if (x > maxX) maxX = x
        if (y < minY) minY = y
        if (y > maxY) maxY = y
      }
    }
  }

  if (!found) {
    return canvas.toDataURL('image/png')
  }

  const pad = 10
  const cropX = Math.max(0, minX - pad)
  const cropY = Math.max(0, minY - pad)
  const cropW = Math.min(w - cropX, maxX - minX + 1 + pad * 2)
  const cropH = Math.min(h - cropY, maxY - minY + 1 + pad * 2)

  const trimmed = document.createElement('canvas')
  trimmed.width = cropW
  trimmed.height = cropH
  const trimmedCtx = trimmed.getContext('2d')
  trimmedCtx.drawImage(canvas, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH)

  return trimmed.toDataURL('image/png')
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
  applyBrushStyle(ctx)
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
  emit('update:modelValue', getCroppedDataURL(canvasRef.value))
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
