<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto px-4 py-8">

      <!-- Tombol Kembali -->
      <Link
        href="/dashboard"
        class="inline-flex items-center gap-2 text-xs font-semibold text-[#0757A8] hover:underline mb-4"
      >
        <Icon icon="lucide:arrow-left" class="w-4 h-4" />
        <span>Kembali ke Dashboard</span>
      </Link>

      <!-- Breadcrumb -->
      <nav class="text-xs text-slate-500 mb-5 flex items-center gap-1.5">
        <Link href="/dashboard" class="hover:text-[#0757a8]">Dashboard</Link>
        <span>›</span>
        <span class="text-slate-700 font-mono font-medium">{{ rfc.kode_pelacakan }}</span>
      </nav>

      <!-- 1. Header RFC Card -->
      <div class="card p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="font-mono text-2xl font-black text-[#0757a8] mb-1">{{ rfc.kode_pelacakan }}</div>
            <div class="font-bold text-slate-900 text-lg">{{ rfc.nama_pemohon }}</div>
            <div class="text-slate-600 text-sm mt-0.5">{{ rfc.opd }}</div>
            <div class="text-slate-400 text-xs mt-1">Diajukan: {{ rfc.tanggal_permohonan || '-' }}</div>
          </div>

          <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
            <div class="flex gap-2 flex-wrap">
              <span :class="['px-2.5 py-1 rounded-full text-[10px] font-bold', rfc.warna_badge]">{{ rfc.status_label }}</span>
              <span :class="['px-2 py-0.5 rounded-md text-[10px] font-bold', rfc.urgensi_warna]">{{ rfc.urgensi_label }}</span>
            </div>

            <div class="flex items-center gap-2 pt-2 sm:pt-0">
              <Link
                :href="`/rfc/${rfc.kode_pelacakan}/dokumen`"
                class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-[#0757a8] text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs"
              >
                <Icon icon="lucide:file-text" class="w-4 h-4" />
                <span>Dokumen FR-015</span>
              </Link>

              <Link
                :href="`/rfc/${rfc.kode_pelacakan}/workspace`"
                class="px-4 py-2 rounded-xl bg-[#0757a8] hover:bg-[#063d78] text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs"
              >
                <Icon icon="lucide:external-link" class="w-4 h-4" />
                <span>Buka Workspace</span>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Stepper: 5 Tahapan Alur Kerja RFC -->
      <div class="card p-6 mb-6">
        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center justify-between">
          <span>Progres Alur Permohonan (FR-015 ITIL / SMKI ISO 27001)</span>
          <span class="text-[#0757a8] font-bold">Tahap {{ activeStep + 1 }} dari {{ steps.length }}</span>
        </div>

        <div class="flex items-center justify-between gap-2 overflow-x-auto pb-2">
          <div
            v-for="(step, i) in steps"
            :key="i"
            class="flex items-center flex-1 min-w-[130px]"
          >
            <div class="flex flex-col items-center mx-auto">
              <div
                :class="[
                  'w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all shadow-xs',
                  i < activeStep ? 'bg-emerald-500 border-emerald-500 text-white' :
                  i === activeStep ? 'bg-[#0757a8] border-[#0757a8] text-white ring-4 ring-[#0757a8]/20' :
                  'bg-white border-slate-300 text-slate-400'
                ]"
              >
                <Icon v-if="i < activeStep" icon="lucide:check" class="w-4 h-4 stroke-[3]" />
                <span v-else>{{ i + 1 }}</span>
              </div>
              <span
                class="text-[11px] text-center mt-2 leading-tight max-w-[100px]"
                :class="i === activeStep ? 'text-[#0757a8] font-bold' : i < activeStep ? 'text-emerald-700 font-semibold' : 'text-slate-400'"
              >
                {{ step }}
              </span>
            </div>

            <!-- Garis Penghubung Antar Langkah -->
            <div
              v-if="i < steps.length - 1"
              :class="['flex-1 h-0.5 mb-6 mx-2', i < activeStep ? 'bg-emerald-400' : 'bg-slate-200']"
            ></div>
          </div>
        </div>
      </div>

      <!-- 3. Panel Notifikasi Revisi (Jika status perlu_revisi) -->
      <div v-if="rfc.status_terkini === 'perlu_revisi'" class="bg-orange-50 border border-orange-200 rounded-xl p-5 mb-6">
        <div class="flex items-start gap-3 mb-4">
          <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 text-white">
            <Icon icon="lucide:alert-triangle" class="w-4 h-4" />
          </div>
          <div>
            <h3 class="font-bold text-orange-900">Formulir Perlu Revisi</h3>
            <p class="text-orange-800 text-sm mt-1">{{ revisiCatatan }}</p>
          </div>
        </div>
        <Link :href="`/rfc/${rfc.id}/edit`" class="btn-warning text-sm inline-flex items-center gap-1.5 shadow-sm">
          <Icon icon="lucide:edit-3" class="w-4 h-4" />
          <span>Buka Form Edit untuk Perbaiki</span>
        </Link>
      </div>

      <!-- 4. Detail Formulir FR-015 -->
      <div class="card p-6 mb-6">
        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-100">
          <div>
            <h2 class="font-bold text-slate-800 text-base">Detail Formulir FR-015</h2>
            <p class="text-xs text-slate-400 mt-0.5">Rincian isian data permohonan perubahan TI</p>
          </div>
          <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
            ✓ Data Terisi Otomatis
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-xs sm:text-sm">
          <!-- Pemohon -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Nama Pemohon</div>
            <div class="text-slate-900 font-semibold">{{ rfc.nama_pemohon || '-' }}</div>
          </div>

          <!-- Unit Kerja -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Unit Kerja</div>
            <div class="text-slate-900 font-semibold">{{ rfc.unit_kerja || '-' }}</div>
          </div>

          <!-- Perangkat Daerah -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Perangkat Daerah / OPD</div>
            <div class="text-slate-900 font-semibold">{{ rfc.opd || '-' }}</div>
          </div>

          <!-- NIP -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">NIP Pemohon</div>
            <div class="text-slate-900 font-semibold">{{ rfc.nip || '-' }}</div>
          </div>

          <!-- Nomor Kontak -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Nomor WhatsApp / Kontak</div>
            <div class="text-slate-900 font-medium">{{ rfc.nomor_kontak || '-' }}</div>
          </div>

          <!-- Email Dinas -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Email Kedinasan</div>
            <div class="text-slate-900 font-medium">{{ rfc.email_dinas || '-' }}</div>
          </div>

          <!-- Tanggal Permohonan -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Tanggal Permohonan</div>
            <div class="text-slate-900 font-medium">{{ rfc.tanggal_permohonan || '-' }}</div>
          </div>

          <!-- Kategori -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Kategori Perubahan</div>
            <div class="text-slate-900 font-semibold text-[#0757a8]">{{ rfc.kategori || '-' }}</div>
          </div>

          <!-- Jenis Perubahan -->
          <div class="md:col-span-2">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Jenis Perubahan</div>
            <div class="text-slate-900 font-medium">{{ formatJenisPerubahan(rfc.jenis_perubahan) }}</div>
          </div>

          <!-- Nama Aplikasi -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Nama Aplikasi / Sistem</div>
            <div class="text-slate-900 font-bold text-[#0757a8]">{{ rfc.nama_aplikasi || '-' }}</div>
          </div>

          <!-- Alamat Aplikasi -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Alamat URL Aplikasi</div>
            <div class="text-slate-900 font-medium truncate">{{ rfc.alamat_aplikasi || '-' }}</div>
          </div>

          <!-- Deskripsi Perubahan -->
          <div class="md:col-span-2">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Perubahan yang Diharapkan</div>
            <div class="text-slate-800 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100">
              {{ rfc.deskripsi || rfc.deskripsi_perubahan || '-' }}
            </div>
          </div>

          <!-- Latar Belakang -->
          <div class="md:col-span-2">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Latar Belakang Perubahan</div>
            <div class="text-slate-800 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100 whitespace-pre-wrap">
              {{ rfc.latar_belakang || '-' }}
            </div>
          </div>

          <!-- Rincian Perubahan -->
          <div class="md:col-span-2">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Rincian Perubahan yang Diajukan</div>
            <div class="text-slate-800 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100 whitespace-pre-wrap">
              {{ rfc.rincian_perubahan || '-' }}
            </div>
          </div>

          <!-- Kriteria Risiko -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Kriteria Risiko</div>
            <div class="text-slate-900 font-semibold capitalize">{{ (rfc.kriteria_risiko || '-').replace(/_/g, ' ') }}</div>
          </div>

          <!-- Biaya Perubahan -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Estimasi Biaya</div>
            <div class="text-slate-900 font-medium">{{ rfc.biaya_perubahan || '-' }}</div>
          </div>

          <!-- Waktu Perubahan -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Target Waktu Pengerjaan</div>
            <div class="text-slate-900 font-medium">{{ rfc.waktu_perubahan || '-' }}</div>
          </div>

          <!-- Risiko Jika Tidak Dilakukan -->
          <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Risiko Jika Tidak Dilakukan</div>
            <div class="text-slate-800">{{ rfc.risiko_tidak_dilakukan || '-' }}</div>
          </div>
        </div>
      </div>

      <!-- 5. Matriks Otorisasi FR-015: 4 Pihak Sesuai Workflow -->
      <div class="card p-6 mb-6">
        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-100">
          <div>
            <h2 class="font-bold text-slate-800 text-base">Matriks Otorisasi FR-015</h2>
            <p class="text-xs text-slate-400 mt-0.5">Otorisasi empat pihak terisi bertahap sesuai trigger alur kerja</p>
          </div>
          <span class="text-[11px] text-slate-500 font-mono">FR-015 4-Party Auth</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- 1. Pemohon (Terisi langsung sejak pengajuan) -->
          <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/60 flex flex-col justify-between min-h-[200px]">
            <div>
              <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.tanggal_permohonan || '-' }}</div>
              <div class="text-xs font-bold text-slate-800">1. Dibuat Oleh:</div>
              <div class="text-xs text-[#0757a8] font-semibold">Pemohon OPD</div>
            </div>

            <!-- TTD Pemohon -->
            <div class="my-3 flex items-center justify-center min-h-[64px] bg-white rounded border border-dashed border-slate-200 p-1">
              <img
                v-if="rfc.tanda_tangan_url"
                :src="rfc.tanda_tangan_url"
                alt="TTD Pemohon"
                class="max-h-14 max-w-[140px] object-contain"
              />
              <span v-else class="text-emerald-600 text-xs font-semibold flex items-center gap-1">
                <Icon icon="lucide:check-circle" class="w-4 h-4" />
                <span>Telah Diajukan</span>
              </span>
            </div>

            <div class="border-t border-slate-200 pt-2 text-center">
              <div class="text-xs font-bold text-slate-800 truncate">{{ rfc.nama_pemohon }}</div>
              <div class="text-[10px] text-slate-500 truncate">{{ rfc.unit_kerja || rfc.opd }}</div>
            </div>
          </div>

          <!-- 2. Penanggung Jawab OPD (Kepala Bidang OPD) -->
          <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/60 flex flex-col justify-between min-h-[200px]">
            <div>
              <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.tanggal_permohonan || '-' }}</div>
              <div class="text-xs font-bold text-slate-800">2. Disetujui Oleh:</div>
              <div class="text-xs text-emerald-700 font-semibold">Penanggung Jawab OPD</div>
            </div>

            <!-- TTD Penanggung Jawab OPD -->
            <div class="my-3 flex items-center justify-center min-h-[64px] bg-white rounded border border-dashed border-slate-200 p-1">
              <img
                v-if="rfc.tanda_tangan_penanggung_jawab_url"
                :src="rfc.tanda_tangan_penanggung_jawab_url"
                alt="TTD Kabid OPD"
                class="max-h-14 max-w-[140px] object-contain"
              />
              <span v-else class="text-emerald-600 text-xs font-semibold flex items-center gap-1">
                <Icon icon="lucide:check-circle" class="w-4 h-4" />
                <span>Disetujui Kedinasan</span>
              </span>
            </div>

            <div class="border-t border-slate-200 pt-2 text-center">
              <div class="text-xs font-bold text-slate-800 truncate">{{ rfc.nama_penanggung_jawab || 'Kepala Bidang OPD' }}</div>
              <div class="text-[10px] text-slate-500 truncate">{{ rfc.nip_penanggung_jawab ? 'NIP. ' + rfc.nip_penanggung_jawab : 'Kabid Kedinasan' }}</div>
            </div>
          </div>

          <!-- 3. Pimpinan Diskominfo (Muncul setelah ada keputusan) -->
          <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/60 flex flex-col justify-between min-h-[200px]">
            <div>
              <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.persetujuan?.diputuskan_pada_tanggal || rfc.persetujuan?.diputuskan_pada || 'Belum' }}</div>
              <div class="text-xs font-bold text-slate-800">3. Diterima Oleh:</div>
              <div class="text-xs text-[#0757a8] font-semibold">Pimpinan Diskominfo</div>
            </div>

            <!-- Status TTD Pimpinan -->
            <div class="my-3 flex items-center justify-center min-h-[64px] bg-white rounded border border-dashed border-slate-200 p-1">
              <img
                v-if="rfc.persetujuan?.tanda_tangan_pimpinan_url"
                :src="rfc.persetujuan.tanda_tangan_pimpinan_url"
                alt="TTD Pimpinan"
                class="max-h-14 max-w-[140px] object-contain"
              />
              <div v-else-if="rfc.persetujuan" class="text-center">
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#0757a8] bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                  <Icon icon="lucide:check-circle" class="w-3.5 h-3.5" />
                  <span>Disahkan Pimpinan</span>
                </span>
              </div>
              <div v-else class="text-slate-400 text-xs italic flex items-center gap-1">
                <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
                <span>Menunggu keputusan pimpinan</span>
              </div>
            </div>

            <div class="border-t border-slate-200 pt-2 text-center">
              <div class="text-xs font-bold text-slate-800 truncate">{{ rfc.persetujuan?.pimpinan || '( Menunggu Pimpinan )' }}</div>
              <div class="text-[10px] text-slate-500 truncate">{{ rfc.persetujuan?.pimpinan_nip ? 'NIP. ' + rfc.persetujuan.pimpinan_nip : 'Otorisasi Diskominfo' }}</div>
            </div>
          </div>

          <!-- 4. Teknisi Pelaksana (Muncul setelah selesai BAST) -->
          <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/60 flex flex-col justify-between min-h-[200px]">
            <div>
              <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.bast?.dibuat_pada_tanggal || rfc.bast?.dibuat_pada || rfc.eksekusi?.selesai_pada || 'Belum' }}</div>
              <div class="text-xs font-bold text-slate-800">4. Dilaksanakan Oleh:</div>
              <div class="text-xs text-cyan-700 font-semibold">Teknisi Pelaksana</div>
            </div>

            <!-- Status TTD Teknisi -->
            <div class="my-3 flex items-center justify-center min-h-[64px] bg-white rounded border border-dashed border-slate-200 p-1">
              <img
                v-if="rfc.bast?.tanda_tangan_teknisi_url"
                :src="rfc.bast.tanda_tangan_teknisi_url"
                alt="TTD Teknisi"
                class="max-h-14 max-w-[140px] object-contain"
              />
              <div v-else-if="rfc.bast" class="text-center">
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded border border-cyan-200">
                  <Icon icon="lucide:check-circle" class="w-3.5 h-3.5" />
                  <span>BAST Terbit</span>
                </span>
              </div>
              <div v-else-if="rfc.eksekusi" class="text-center">
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded border border-cyan-200">
                  <Icon icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
                  <span>Sedang Dikerjakan</span>
                </span>
              </div>
              <div v-else class="text-slate-400 text-xs italic flex items-center gap-1">
                <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
                <span>Menunggu pengerjaan teknisi</span>
              </div>
            </div>

            <div class="border-t border-slate-200 pt-2 text-center">
              <div class="text-xs font-bold text-slate-800 truncate">{{ rfc.bast?.nama_teknisi || rfc.eksekusi?.teknisi || '( Menunggu Teknisi )' }}</div>
              <div class="text-[10px] text-slate-500 truncate">{{ rfc.bast?.nip_teknisi ? 'NIP. ' + rfc.bast.nip_teknisi : 'Pelaksana Teknis Diskominfo' }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- 6. BAST Card (tampil jika bast sudah ada / status selesai) -->
      <div v-if="rfc.bast || rfc.status_terkini === 'selesai'" class="mb-6">
        <div v-if="rfc.bast" class="rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-300 shadow-sm overflow-hidden">
          <!-- Header BAST -->
          <div class="bg-emerald-600 px-6 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
              <Icon icon="lucide:file-check-2" class="w-5 h-5 text-white" />
            </div>
            <div>
              <div class="text-white font-bold text-sm">Berita Acara Serah Terima (BAST)</div>
              <div class="text-emerald-100 text-xs mt-0.5">Dokumen resmi penutupan perubahan TI — dapat diunduh semua pihak</div>
            </div>
            <span class="ml-auto inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/20 text-white text-[10px] font-bold">
              <Icon icon="lucide:check-circle" class="w-3.5 h-3.5" />
              RFC SELESAI
            </span>
          </div>

          <!-- Body BAST -->
          <div class="px-6 py-5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
              <div>
                <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide mb-0.5">Nomor BAST</div>
                <div class="text-sm font-bold text-slate-800">{{ rfc.bast.nomor_dokumen }}</div>
              </div>
              <div>
                <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide mb-0.5">Tanggal Terbit</div>
                <div class="text-sm font-semibold text-slate-700">{{ rfc.bast.dibuat_pada }}</div>
              </div>
              <div>
                <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide mb-0.5">Teknisi Pelaksana</div>
                <div class="text-sm font-semibold text-slate-700">{{ rfc.bast.nama_teknisi || '-' }}</div>
              </div>
              <div>
                <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide mb-0.5">NIP Teknisi</div>
                <div class="text-xs font-mono text-slate-600">{{ rfc.bast.nip_teknisi || '-' }}</div>
              </div>
            </div>

            <div v-if="rfc.bast.catatan" class="bg-white rounded-xl border border-emerald-200 px-4 py-3 mb-4 text-sm text-slate-700 leading-relaxed">
              <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide mb-1">Catatan Serah Terima</div>
              {{ rfc.bast.catatan }}
            </div>

            <!-- Tombol Unduh BAST - Prominent -->
            <a
              v-if="rfc.bast.path_file_url"
              :href="rfc.bast.path_file_url"
              target="_blank"
              download
              class="flex items-center justify-center gap-2.5 w-full px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold text-sm transition-all shadow-md"
            >
              <Icon icon="lucide:download" class="w-5 h-5" />
              Unduh Dokumen BAST (PDF)
            </a>
            <div v-else class="text-center text-xs text-slate-400 py-2">
              File BAST belum dapat diakses. Hubungi administrator.
            </div>
          </div>
        </div>

        <!-- Fallback: status selesai tapi belum ada BAST object -->
        <div v-else class="rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 flex items-center gap-3 text-sm text-emerald-800">
          <Icon icon="lucide:check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0" />
          <span>RFC ini telah ditandai <strong>Selesai</strong>. Dokumen BAST sedang diproses.</span>
        </div>
      </div>

      <!-- 7. Riwayat Aktivitas (Audit Trail) -->
      <div class="card p-6">
        <h2 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Riwayat Aktivitas</h2>
        <div class="space-y-4">
          <div v-for="h in histories" :key="h.id" class="flex gap-3">
            <div :class="['w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold', roleColor(h.peran_aktor)]">
              {{ (h.nama_aktor || '?').charAt(0).toUpperCase() }}
            </div>
            <div class="flex-1">
              <div class="text-sm font-semibold text-slate-800">{{ h.nama_aktor }} <span class="font-normal text-slate-500 text-xs">({{ h.peran_aktor }})</span></div>
              <div class="text-sm text-slate-700">{{ h.keterangan }}</div>
              <div v-if="h.catatan" class="text-xs text-slate-500 mt-0.5 italic">{{ h.catatan }}</div>
              <div class="text-xs text-slate-400 mt-0.5">{{ h.created_at }}</div>
            </div>
          </div>
          <div v-if="!histories || histories.length === 0" class="text-center text-slate-400 text-sm py-4">
            Belum ada riwayat aktivitas.
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
  rfc: { type: Object, required: true },
  histories: { type: Array, default: () => [] },
})

onMounted(() => {
  if (props.rfc?.kode_pelacakan) {
    router.replace(`/rfc/${props.rfc.kode_pelacakan}/workspace`)
  }
})

const steps = ['Pemohon', 'Verifikasi', 'Pimpinan', 'Implementasi', 'BAST & Selesai']

const activeStep = computed(() => {
  const s = props.rfc.status_terkini
  if (['draf'].includes(s)) return 0
  if (['diajukan', 'perlu_revisi', 'ditolak_verifikator'].includes(s)) return 1
  if (['menunggu_persetujuan', 'ditolak_pimpinan', 'ditunda'].includes(s)) return 2
  if (['disetujui', 'sedang_dikerjakan'].includes(s)) return 3
  if (['selesai'].includes(s)) return 4
  return 1
})

const revisiCatatan = computed(() => {
  if (props.rfc.revisi && props.rfc.revisi.length > 0) {
    return props.rfc.revisi[0].catatan_revisi || 'Tidak ada catatan revisi.'
  }
  return 'Tidak ada catatan revisi.'
})
const revisiVerifikator = computed(() => {
  if (props.rfc.revisi && props.rfc.revisi.length > 0) {
    return props.rfc.revisi[0].verifikator
  }
  return null
})
const revisiTanggal = computed(() => {
  if (props.rfc.revisi && props.rfc.revisi.length > 0) {
    return props.rfc.revisi[0].dibuat_pada
  }
  return null
})

// Persetujuan info
const persetujuanPimpinan = computed(() => props.rfc.persetujuan?.pimpinan ?? null)
const persetujuanTanggal = computed(() => props.rfc.persetujuan?.diputuskan_pada ?? null)

// Eksekusi info
const eksekusiTeknisi = computed(() => props.rfc.eksekusi?.teknisi ?? null)
const eksekusiTanggal = computed(() => props.rfc.eksekusi?.dimulai_pada ?? null)

const JENIS_LABEL_MAP = {
  permintaan_baru: 'Permintaan Baru',
  peningkatan_sistem: 'Peningkatan Sistem',
  perbaikan_sistem: 'Perbaikan Sistem',
}

function formatJenisPerubahan(jenis) {
  if (!jenis) return '-'
  let list = jenis
  if (!Array.isArray(list)) {
    try {
      const parsed = JSON.parse(list)
      list = Array.isArray(parsed) ? parsed : [parsed]
    } catch {
      list = [list]
    }
  }
  const formatted = list
    .filter(Boolean)
    .map(v => {
      const key = String(v).trim()
      return JENIS_LABEL_MAP[key] || key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
    })
  return formatted.length ? formatted.join(', ') : '-'
}

function roleColor(role) {
  return {
    pemohon: 'bg-[#0757a8]',
    verifikator: 'bg-purple-600',
    pimpinan: 'bg-[#047857]',
    teknisi: 'bg-cyan-600',
    admin: 'bg-slate-600',
  }[role] || 'bg-slate-400'
}
</script>

<style scoped>
.card {
  background: white;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}
.btn-warning {
  background: #f59e0b;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.75rem;
  font-weight: 700;
  transition: all 0.2s;
}
.btn-warning:hover {
  background: #d97706;
}
</style>
