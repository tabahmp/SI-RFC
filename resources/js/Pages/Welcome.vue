<template>
  <AppLayout>
    <div class="relative overflow-hidden bg-slate-900 text-white">
      <!-- Background Glow Desain Figma -->
      <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-tr from-[#0757a8]/40 to-[#16834a]/30 blur-[120px] pointer-events-none"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">
        <!-- Badge Standar Kedinasan -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-blue-200 mb-6">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          Standar ITIL / ITSM • ISO/IEC 20000 • SMKI ISO/IEC 27001
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
          Sistem Informasi Permohonan <br />
          <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-sky-300 to-emerald-400">
            Request for Change (SI-RFC)
          </span>
        </h1>

        <p class="text-slate-300 text-base sm:text-lg max-w-3xl mx-auto mb-10 leading-relaxed font-normal">
          Pengelolaan siklus hidup perubahan layanan teknologi informasi Pemerintah Daerah secara transparan, akuntabel, dan terukur. Implementasi digital terpadu formulir standar kedinasan <strong>FR-015</strong> dari pengajuan hingga Berita Acara Serah Terima (BAST).
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
          <Link href="/form-perubahan-it/baru" class="btn-primary text-base px-6 py-3 shadow-lg shadow-blue-900/50">
            <span>📝</span> Ajukan Permohonan RFC (FR-015)
          </Link>
          <Link href="/form-perubahan-it" class="btn-secondary text-base px-6 py-3 bg-white/10 text-white border-white/20 hover:bg-white/20">
            <span>📊</span> Pantau & Lacak Tiket RFC
          </Link>
        </div>
      </div>
    </div>

    <!-- FITUR 5 TAHAPAN WORKFLOW -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="text-center mb-12">
        <h2 class="text-2xl font-black text-slate-800">Alur Kerja 5 Tahapan Terstruktur (Separation of Duties)</h2>
        <p class="text-slate-500 text-sm mt-1">Setiap proses tercatat dalam rekam jejak audit (*audit trail*) dan disahkan melalui tanda tangan digital pejabat berwenang.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="card p-5 border-t-4 border-[#0757a8]">
          <div class="text-2xl mb-2">1️⃣</div>
          <h3 class="font-bold text-slate-800 text-sm mb-1">Tahap Pemohon</h3>
          <p class="text-slate-500 text-xs leading-relaxed">Pengisian lengkap FR-015, lampiran nota dinas/dokumen, & TTD digital PIC OPD pemohon.</p>
        </div>

        <div class="card p-5 border-t-4 border-purple-600">
          <div class="text-2xl mb-2">2️⃣</div>
          <h3 class="font-bold text-slate-800 text-sm mb-1">Verifikator APTIKA</h3>
          <p class="text-slate-500 text-xs leading-relaxed">Pemeriksaan berkas, penetapan skala prioritas (P1-P4 & SLA tanggap), serta rekomendasi teknis.</p>
        </div>

        <div class="card p-5 border-t-4 border-blue-600">
          <div class="text-2xl mb-2">3️⃣</div>
          <h3 class="font-bold text-slate-800 text-sm mb-1">Keputusan Pimpinan</h3>
          <p class="text-slate-500 text-xs leading-relaxed">Pengesahan oleh Kabid APTIKA: Setuju / Tolak / Tunda disertai disposisi teknisi pelaksana & deadline.</p>
        </div>

        <div class="card p-5 border-t-4 border-cyan-600">
          <div class="text-2xl mb-2">4️⃣</div>
          <h3 class="font-bold text-slate-800 text-sm mb-1">Telaah & Koding</h3>
          <p class="text-slate-500 text-xs leading-relaxed">Analisis teknis mendalam arsitektur sistem, koding, changelog git, hingga pengujian fungsional sukses.</p>
        </div>

        <div class="card p-5 border-t-4 border-emerald-600">
          <div class="text-2xl mb-2">5️⃣</div>
          <h3 class="font-bold text-slate-800 text-sm mb-1">BAST & Selesai</h3>
          <p class="text-slate-500 text-xs leading-relaxed">Penerbitan nomor BAST sah, berkas serah terima, TTD Agen Pelaksana, dan penutupan tiket selesai.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

