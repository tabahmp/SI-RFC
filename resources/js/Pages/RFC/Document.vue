<template>
  <AppLayout>
    <div class="space-y-6 max-w-[1000px] mx-auto pb-12">

      <!-- Tombol Kembali ke Permohonan Saya -->
      <Link
        href="/dashboard/pemohon?view=permohonan"
        class="inline-flex items-center gap-2 text-xs font-semibold text-[#0757A8] hover:underline"
      >
        <Icon icon="lucide:arrow-left" class="w-4 h-4" />
        <span>Kembali ke Permohonan Saya</span>
      </Link>
      
      <!-- HEADER ACTIONS & BREADCRUMB (Persis Figma Frame 1:5010) -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-xs font-bold text-[#0757A8] mb-1">
            <Link href="/form-perubahan-it" class="hover:underline">Dashboard</Link>
            <span>/</span>
            <Link :href="`/form-perubahan-it/ticket/${doc.no_rfc}`" class="hover:underline">Tiket</Link>
            <span>/</span>
            <span class="text-slate-600 font-mono">{{ doc.no_rfc }}</span>
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
            FR-015 · Formulir Perubahan TI
          </h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
            Rekap lengkap permohonan, hasil perubahan, dan otorisasi empat pihak.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <Link
            :href="`/form-perubahan-it/ticket/${doc.no_rfc}`"
            class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all flex items-center gap-2"
          >
            <Icon icon="lucide:arrow-left" class="w-4 h-4" />
            <span>Kembali ke Detail</span>
          </Link>

          <button
            @click="printDocument"
            class="px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-2"
          >
            <Icon icon="lucide:printer" class="w-4 h-4" />
            <span>Unduh PDF FR-015</span>
          </button>
        </div>
      </div>

      <!-- LEMBAR DOKUMEN RESMI CETAK FR-015 (Background Putih Kertas Resmi) -->
      <div id="printable-fr015" class="bg-white rounded-2xl border border-slate-300 shadow-lg p-8 sm:p-12 space-y-8 font-sans text-slate-800">
        
        <!-- KOP SURAT PEMERINTAH PROVINSI JAWA BARAT -->
        <div class="border-b-2 border-slate-800 pb-5">
          <div class="flex items-center justify-between gap-4">
            <img src="/images/assets/logo_diskominfo_jabar.png" alt="Diskominfo Jabar" class="h-12 w-auto object-contain" />
            
            <div class="text-center flex-1">
              <div class="text-xs font-bold tracking-widest uppercase text-slate-700">
                PEMERINTAH DAERAH PROVINSI JAWA BARAT
              </div>
              <div class="text-sm font-black tracking-wider uppercase text-slate-900 mt-0.5">
                DINAS KOMUNIKASI DAN INFORMATIKA
              </div>
              <div class="text-[10px] text-slate-500 mt-1">
                Jl. Tamansari No. 55 Bandung 40132 • Telp: (022) 2502898 • Email: diskominfo@jabarprov.go.id
              </div>
            </div>

            <img src="/images/assets/logo_rfc_asli.png" alt="Logo RFC" class="h-12 w-auto object-contain" />
          </div>

          <div class="mt-4 pt-3 border-t border-slate-300 text-center">
            <h2 class="text-base sm:text-lg font-black tracking-wider text-slate-900 uppercase">
              FORMULIR PERMOHONAN PERUBAHAN TEKNOLOGI INFORMASI (FR-015)
            </h2>
            <div class="text-xs text-slate-600 font-medium mt-1 flex items-center justify-center gap-4 flex-wrap">
              <span>No. RFC: <strong class="font-mono text-slate-900">{{ doc.no_rfc }}</strong></span>
              <span>•</span>
              <span>Status: <strong class="text-emerald-700 uppercase">{{ doc.status_label }}</strong></span>
              <span>•</span>
              <span>Tanggal: <strong>{{ doc.tanggal }}</strong></span>
            </div>
          </div>
        </div>

        <!-- SEKSI 1: INFORMASI PEMOHON -->
        <div class="space-y-3">
          <div class="bg-slate-100 px-3.5 py-1.5 rounded-md text-xs font-bold text-slate-800 uppercase tracking-wider border-l-4 border-[#0757A8]">
            I. INFORMASI PEMOHON
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
              <div class="text-[11px] font-semibold text-slate-500 uppercase">Nama Pemohon / NIP</div>
              <div class="font-bold text-slate-800 text-sm mt-0.5">{{ doc.pemohon }}</div>
              <div class="text-[11px] text-slate-500">NIP tercatat pada profil kedinasan</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-slate-500 uppercase">Unit Kerja / Perangkat Daerah</div>
              <div class="font-bold text-slate-800 text-sm mt-0.5">{{ doc.perangkat_daerah }}</div>
              <div class="text-[11px] text-slate-500">{{ doc.unit_kerja }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-slate-500 uppercase">Nomor Surat / Kontak PIC</div>
              <div class="font-bold text-slate-800 mt-0.5">{{ doc.nomor_kontak }}</div>
            </div>
            <div>
              <div class="text-[11px] font-semibold text-slate-500 uppercase">Email Kedinasan</div>
              <div class="font-bold text-slate-800 mt-0.5">{{ doc.email_dinas }}</div>
            </div>
          </div>
        </div>

        <!-- SEKSI 2: INFORMASI DATA PERUBAHAN -->
        <div class="space-y-3">
          <div class="bg-slate-100 px-3.5 py-1.5 rounded-md text-xs font-bold text-slate-800 uppercase tracking-wider border-l-4 border-[#0757A8]">
            II. INFORMASI DATA PERUBAHAN SISTEM
          </div>

          <div class="space-y-3 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <div class="text-[11px] font-semibold text-slate-500 uppercase">Nama Aplikasi / Sistem</div>
                <div class="font-bold text-slate-800 text-sm mt-0.5">{{ doc.nama_aplikasi }}</div>
              </div>
              <div>
                <div class="text-[11px] font-semibold text-slate-500 uppercase">Jenis Perubahan & Prioritas</div>
                <div class="font-bold text-slate-800 text-sm mt-0.5">
                  {{ doc.jenis_perubahan }} • Prioritas: {{ doc.prioritas }}
                </div>
              </div>
            </div>

            <div>
              <div class="text-[11px] font-semibold text-slate-500 uppercase">Perubahan yang Diharapkan</div>
              <div class="mt-1 p-3 bg-slate-50 rounded-lg text-slate-800 font-medium leading-relaxed">
                {{ doc.perubahan_diharapkan }}
              </div>
            </div>

            <div>
              <div class="text-[11px] font-semibold text-slate-500 uppercase">Latar Belakang Permohonan</div>
              <div class="mt-1 p-3 bg-slate-50 rounded-lg text-slate-700 leading-relaxed">
                {{ doc.latar_belakang }}
              </div>
            </div>

            <div>
              <div class="text-[11px] font-semibold text-slate-500 uppercase">Rincian Perubahan</div>
              <div class="mt-1 p-3 bg-slate-50 rounded-lg text-slate-700 leading-relaxed whitespace-pre-line font-mono text-[11px]">
                {{ doc.rincian_perubahan }}
              </div>
            </div>
          </div>
        </div>

        <!-- SEKSI 3: ANALISIS RISIKO & MITIGASI (SMKI ISO 27001) -->
        <div class="space-y-3">
          <div class="bg-slate-100 px-3.5 py-1.5 rounded-md text-xs font-bold text-slate-800 uppercase tracking-wider border-l-4 border-[#0757A8]">
            III. ANALISIS RISIKO & DAMPAK OPERASIONAL
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-3 bg-slate-50 rounded-lg">
              <div class="font-bold text-slate-700 mb-1">Risiko Jika Tidak Dilakukan</div>
              <p class="text-slate-600 leading-relaxed">{{ doc.risiko_tidak_dilakukan }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-lg">
              <div class="font-bold text-slate-700 mb-1">Rencana Mitigasi & Rollback</div>
              <p class="text-slate-600 leading-relaxed">{{ doc.solusi_diharapkan }}</p>
            </div>
          </div>
        </div>

        <!-- SEKSI 4: MATRIKS PENGESAHAN OTORISASI 4 PIHAK (PERSIS FIGMA) -->
        <div class="space-y-3 pt-2">
          <div class="bg-slate-100 px-3.5 py-1.5 rounded-md text-xs font-bold text-slate-800 uppercase tracking-wider border-l-4 border-[#0757A8]">
            IV. MATRIKS PENGESAHAN OTORISASI EMPAT PIHAK
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-center">
            <!-- 1. Pemohon -->
            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50/60 flex flex-col justify-between h-44">
              <div>
                <div class="text-[10px] font-bold text-slate-500 uppercase">1. PEMOHON OPD</div>
                <div class="text-xs font-bold text-slate-800 mt-1 truncate">{{ doc.pemohon.split('(')[0] }}</div>
              </div>
              <div class="my-auto py-2">
                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                  <Icon icon="lucide:check-circle" class="w-3.5 h-3.5 text-emerald-600" />
                  <span>✓ Tanda tangan sah</span>
                </div>
              </div>
              <div class="text-[10px] text-slate-500 border-t border-slate-200 pt-1">
                Tanggal: {{ doc.tanggal }}
              </div>
            </div>

            <!-- 2. Verifikator -->
            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50/60 flex flex-col justify-between h-44">
              <div>
                <div class="text-[10px] font-bold text-slate-500 uppercase">2. VERIFIKATOR</div>
                <div class="text-xs font-bold text-slate-800 mt-1 truncate">Hendro Susilo, M.T.</div>
              </div>
              <div class="my-auto py-2">
                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                  <Icon icon="lucide:check-circle" class="w-3.5 h-3.5 text-emerald-600" />
                  <span>✓ Telah diverifikasi</span>
                </div>
              </div>
              <div class="text-[10px] text-slate-500 border-t border-slate-200 pt-1">
                APTIKA Diskominfo
              </div>
            </div>

            <!-- 3. Pimpinan -->
            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50/60 flex flex-col justify-between h-44">
              <div>
                <div class="text-[10px] font-bold text-slate-500 uppercase">3. PIMPINAN (KABID)</div>
                <div class="text-xs font-bold text-slate-800 mt-1 truncate">Drs. H. Iwan G., M.Si</div>
              </div>
              <div class="my-auto py-2">
                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                  <Icon icon="lucide:check-circle" class="w-3.5 h-3.5 text-emerald-600" />
                  <span>✓ Disetujui</span>
                </div>
              </div>
              <div class="text-[10px] text-slate-500 border-t border-slate-200 pt-1">
                Otorisasi Resmi
              </div>
            </div>

            <!-- 4. Teknisi -->
            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50/60 flex flex-col justify-between h-44">
              <div>
                <div class="text-[10px] font-bold text-slate-500 uppercase">4. TEKNISI PELAKSANA</div>
                <div class="text-xs font-bold text-slate-800 mt-1 truncate">Tim Integrasi & API</div>
              </div>
              <div class="my-auto py-2">
                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                  <Icon icon="lucide:check-circle" class="w-3.5 h-3.5 text-emerald-600" />
                  <span>✓ Selesai & BAST</span>
                </div>
              </div>
              <div class="text-[10px] text-slate-500 border-t border-slate-200 pt-1">
                Implementasi Selesai
              </div>
            </div>
          </div>
        </div>

        <!-- FOOTER DOKUMEN RESMI (Persis Figma Frame 1:5010) -->
        <div class="pt-6 border-t border-slate-300 text-[11px] text-slate-500 flex flex-wrap items-center justify-between gap-3">
          <div>
            <strong>Dokumen hasil rekap SI-RFC</strong> • Lampiran dan jejak otorisasi tersimpan pada tiket terkait.
          </div>
          <div>
            FR-015 • Formulir Perubahan TI • Diskominfo Provinsi Jawa Barat
          </div>
        </div>

      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  doc: {
    type: Object,
    default: () => ({
      no_rfc: 'RFC-202506-0117',
      status_label: 'Selesai',
      tanggal: '12 Juni 2025',
      pemohon: 'Raka Pratama, S.Kom',
      perangkat_daerah: 'Badan Perencanaan Pembangunan Daerah Provinsi Jawa Barat',
      unit_kerja: 'Bidang Perencanaan dan Evaluasi',
      nomor_kontak: 'Surat permohonan pada lampiran • raka@instansi.go.id',
      email_dinas: 'raka@instansi.go.id',
      nama_aplikasi: 'Dashboard Pimpinan',
      jenis_perubahan: 'Peningkatan Sistem',
      prioritas: 'P4 - Rendah',
      perubahan_diharapkan: 'Penyempurnaan visualisasi indikator IKU untuk pemantauan pimpinan.',
      latar_belakang: 'Kebutuhan pimpinan daerah untuk pemantauan real-time capaian indikator kinerja utama lintas sektor.',
      rincian_perubahan: '1. Modifikasi komponen chart agregat\n2. Optimasi API query rekapitulasi capaian triwulanan\n3. Penambahan filter dinamis berdasarkan perangkat daerah',
      risiko_tidak_dilakukan: 'Penyajian data visual indikator IKU tidak mutakhir.',
      solusi_diharapkan: 'Penerapan caching data 15 menit dan mekanisme fallback database replica.'
    })
  }
})

function printDocument() {
  window.print()
}
</script>

<style>
@media print {
  header, aside, footer, .no-print {
    display: none !important;
  }
  body {
    background: #fff !important;
  }
  #printable-fr015 {
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
  }
}
</style>

