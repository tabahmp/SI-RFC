<template>
  <div class="min-h-screen w-full bg-[#F3F7FB] flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-[#DAE3EF] overflow-hidden">
      <!-- Aksen -->
      <div class="h-1 flex">
        <div class="w-1/2 bg-[#0757A8]"></div>
        <div class="w-1/4 bg-[#F4CE18]"></div>
        <div class="w-1/4 bg-[#16834A]"></div>
      </div>

      <div class="p-8">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-6">
          <Icon icon="lucide:key-round" class="w-8 h-8 text-[#0757A8]" />
          <div>
            <div class="font-extrabold text-[#073B70] text-sm">SI-RFC</div>
            <div class="text-[10px] text-slate-400">Reset Kata Sandi</div>
          </div>
        </div>

        <h2 class="text-xl font-extrabold text-[#0B1021] mb-1">Buat Kata Sandi Baru</h2>
        <p class="text-xs text-[#71839B] mb-6">Masukkan kata sandi baru untuk akun <strong>{{ email }}</strong></p>

        <!-- Alert error -->
        <div v-if="Object.keys(errors).length" class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs">
          <div v-for="(msg, field) in errors" :key="field" class="flex items-center gap-1.5">
            <Icon icon="lucide:alert-circle" class="w-3.5 h-3.5" />
            {{ msg }}
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Password baru -->
          <div>
            <label class="block text-xs font-bold text-[#2D405E] mb-1.5">Kata Sandi Baru</label>
            <div class="relative">
              <Icon icon="lucide:lock" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#718AAB]" />
              <input
                v-model="form.password"
                :type="showPwd ? 'text' : 'password'"
                placeholder="Minimal 8 karakter"
                required
                minlength="8"
                class="w-full pl-10 pr-10 py-2.5 text-sm border border-[#DAE3EF] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all"
              />
              <button type="button" @click="showPwd = !showPwd" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#718AAB] hover:text-slate-700">
                <Icon :icon="showPwd ? 'lucide:eye-off' : 'lucide:eye'" class="w-4 h-4" />
              </button>
            </div>
            <!-- Indikator kekuatan -->
            <div class="flex gap-1 mt-1.5">
              <div v-for="i in 3" :key="i" :class="['h-1 flex-1 rounded-full transition-colors', kekuatanLevel >= i ? kekuatanWarna : 'bg-slate-200']"></div>
            </div>
            <div class="text-[10px] mt-0.5" :class="kekuatanWarnaTeks">{{ kekuatanLabel }}</div>
          </div>

          <!-- Konfirmasi -->
          <div>
            <label class="block text-xs font-bold text-[#2D405E] mb-1.5">Konfirmasi Kata Sandi</label>
            <div class="relative">
              <Icon icon="lucide:lock-keyhole" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#718AAB]" />
              <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Ketik ulang kata sandi"
                required
                class="w-full pl-10 py-2.5 text-sm border border-[#DAE3EF] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all"
                :class="form.password_confirmation && !cocok ? 'border-red-400' : ''"
              />
            </div>
            <p v-if="form.password_confirmation && !cocok" class="text-[10px] text-red-500 mt-0.5">Kata sandi tidak cocok.</p>
          </div>

          <button
            type="submit"
            :disabled="loading || !cocok"
            class="w-full h-11 flex items-center justify-center gap-2 bg-[#0757A8] hover:bg-[#063D78] text-white rounded-xl font-semibold text-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <Icon v-if="loading" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
            {{ loading ? 'Menyimpan...' : 'Simpan Kata Sandi Baru' }}
          </button>
        </form>

        <div class="mt-5 text-center">
          <Link href="/login" class="text-xs text-[#0757A8] font-semibold hover:underline flex items-center justify-center gap-1">
            <Icon icon="lucide:arrow-left" class="w-3.5 h-3.5" />
            Kembali ke halaman masuk
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router }  from '@inertiajs/vue3'

const props = defineProps({
  token:  { type: String, required: true },
  email:  { type: String, default: '' },
  errors: { type: Object, default: () => ({}) },
})

const showPwd = ref(false)
const loading = ref(false)
const form    = ref({ password: '', password_confirmation: '' })

const cocok = computed(() =>
  !form.value.password_confirmation || form.value.password === form.value.password_confirmation
)

const kekuatanLevel = computed(() => {
  const p = form.value.password
  if (!p) return 0
  let s = 0
  if (p.length >= 8) s++
  if (/[A-Z]/.test(p) || /\d/.test(p)) s++
  if (/[^a-zA-Z0-9]/.test(p)) s++
  return s
})

const kekuatanWarna    = computed(() => ['bg-red-500','bg-amber-500','bg-green-500'][kekuatanLevel.value - 1] ?? 'bg-slate-200')
const kekuatanWarnaTeks= computed(() => ['text-red-600','text-amber-600','text-green-600'][kekuatanLevel.value - 1] ?? 'text-slate-400')
const kekuatanLabel    = computed(() => ['Lemah','Sedang','Kuat'][kekuatanLevel.value - 1] ?? '')

function submit() {
  if (!cocok.value) return
  loading.value = true
  router.post('/reset-kata-sandi', {
    token:                 props.token,
    email:                 props.email,
    password:              form.value.password,
    password_confirmation: form.value.password_confirmation,
  }, {
    onFinish: () => { loading.value = false },
  })
}
</script>
