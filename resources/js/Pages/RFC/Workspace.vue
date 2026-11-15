<template>
  <AppLayout>
    <div class="max-w-[1600px] mx-auto px-2 sm:px-4 py-3 sm:py-5">

      <!-- Guard: rfc null (seharusnya tidak terjadi karena controller sudah redirect) -->
      <div v-if="!rfc" class="text-center py-20 bg-white rounded-3xl border border-slate-200">
        <Icon icon="lucide:alert-circle" class="w-12 h-12 text-slate-300 mx-auto mb-4" />
        <p class="text-slate-500 font-medium mb-4">Data RFC tidak ditemukan.</p>
        <Link href="/dashboard" class="px-5 py-2.5 rounded-xl bg-[#0757A8] text-white text-xs font-bold">
          Kembali ke Dashboard
        </Link>
      </div>

      <template v-else>

        <!-- TOP BAR: BREADCRUMB & TOOLBAR AKSI -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
          <div class="flex items-center gap-2 text-xs text-slate-500">
            <Link href="/dashboard" class="hover:text-[#0757A8] flex items-center gap-1 font-semibold">
              <Icon icon="lucide:arrow-left" class="w-4 h-4" />
              <span>Dashboard</span>
            </Link>
            <span>/</span>
            <span class="font-mono font-bold text-[#0757A8]">{{ rfc.kode_pelacakan }}</span>
            <span>/</span>
            <span class="text-slate-700 font-medium">Ruang Kerja & Lembar FR-015</span>
          </div>

          <!-- Tombol Aksi Cepat Cetak -->
          <div class="flex items-center gap-2">
            <Link
              :href="`/rfc/${rfc.kode_pelacakan}/dokumen`"
              class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-[#0757A8] text-xs font-bold transition-all shadow-xs flex items-center gap-1.5"
              title="Buka Lembar Cetak Dokumen FR-015 Standar A4"
            >
              <Icon icon="lucide:printer" class="w-4 h-4" />
              <span>Cetak Dokumen FR-015 (A4)</span>
            </Link>
          </div>
        </div>

        <!-- ALERT NOTIFIKASI -->
        <Transition name="flash-fade">
          <div
            v-if="alertMsg"
            :class="[
              'mb-5 p-4 rounded-2xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs',
              alertType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200'
            ]"
          >
            <div class="flex items-center gap-2">
              <Icon :icon="alertType === 'success' ? 'lucide:check-circle' : 'lucide:alert-circle'" class="w-4 h-4 flex-shrink-0" />
              <span>{{ alertMsg }}</span>
            </div>
            <button @click="alertMsg = null" class="text-current opacity-60 hover:opacity-100 ml-4 font-bold">✕</button>
          </div>
        </Transition>

        <!-- ========================================================
             LAYOUT SPLIT-SCREEN (SIDE-BY-SIDE):
             KIRI: DETAIL LEMBAR FR-015 LENGKAP
             KANAN: RUANG KERJA EKSEKUSI & WORKFLOW
        ======================================================== -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

          <!-- ====================================================
               KOLOM 1 (KIRI - 6/12): DOKUMEN DETAIL LENGKAP FR-015
          ==================================================== -->
          <div class="xl:col-span-6 space-y-6 xl:max-h-[calc(100vh-6rem)] xl:overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-slate-200">

            <!-- KARTU 1: DETAIL FORMULIR FR-015 -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-5">

              <!-- BAR ATAS: NO. RFC & NAMA PEMOHON -->
              <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. RFC:</span>
                  <span class="font-mono font-black text-[#0757A8] text-sm bg-blue-50 px-2.5 py-1 rounded-xl border border-blue-200 shadow-2xs">
                    {{ rfc.kode_pelacakan }}
                  </span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama:</span>
                  <span class="font-bold text-slate-900 text-sm">
                    {{ rfc.nama_pemohon }}
                  </span>
                  <span :class="['ml-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wide uppercase', rfc.warna_badge]">
                    {{ rfc.status_label }}
                  </span>
                </div>
              </div>

              <!-- HEADER KARTU: DETAIL FORMULIR FR-015 (PERSIS SEPERTI GAMBAR) -->
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <h2 class="font-bold text-slate-800 text-base sm:text-lg">Detail Formulir FR-015</h2>
                  <p class="text-xs text-slate-400 mt-0.5">Rincian isian data permohonan perubahan TI</p>
                </div>
                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200 flex items-center gap-1 shadow-2xs">
                  ✓ Data Terisi Otomatis
                </span>
              </div>

              <!-- GRID ISIAN DATA FORMULIR FR-015 (PERSIS SEPERTI GAMBAR) -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-xs sm:text-sm">
                <!-- 1. Nama Pemohon -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">NAMA PEMOHON</div>
                  <div class="text-slate-900 font-semibold">{{ rfc.nama_pemohon || '-' }}</div>
                </div>

                <!-- 2. Unit Kerja -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">UNIT KERJA</div>
                  <div class="text-slate-900 font-semibold">{{ rfc.unit_kerja || '-' }}</div>
                </div>

                <!-- 3. Perangkat Daerah / OPD -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">PERANGKAT DAERAH / OPD</div>
                  <div class="text-slate-900 font-semibold">{{ rfc.opd || '-' }}</div>
                </div>

                <!-- 4. NIP Pemohon -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">NIP PEMOHON</div>
                  <div class="text-slate-900 font-semibold font-mono">{{ rfc.nip || '-' }}</div>
                </div>

                <!-- 5. Nomor Whatsapp / Kontak -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">NOMOR WHATSAPP / KONTAK</div>
                  <div class="text-slate-900 font-medium">{{ rfc.nomor_kontak || '-' }}</div>
                </div>

                <!-- 6. Email Kedinasan -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">EMAIL KEDINASAN</div>
                  <div class="text-slate-900 font-medium">{{ rfc.email_dinas || '-' }}</div>
                </div>

                <!-- 7. Tanggal Permohonan -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">TANGGAL PERMOHONAN</div>
                  <div class="text-slate-900 font-medium">{{ rfc.tanggal_permohonan || '-' }}</div>
                </div>

                <!-- 8. Kategori Perubahan -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">KATEGORI PERUBAHAN</div>
                  <div class="text-slate-900 font-semibold text-[#0757a8]">{{ rfc.kategori || '-' }}</div>
                </div>

                <!-- 9. Jenis Perubahan -->
                <div class="md:col-span-2">
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">JENIS PERUBAHAN</div>
                  <div class="text-slate-900 font-medium">{{ formatJenisPerubahan(rfc.jenis_perubahan) }}</div>
                </div>

                <!-- 10. Nama Aplikasi / Sistem -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">NAMA APLIKASI / SISTEM</div>
                  <div class="text-slate-900 font-bold text-[#0757a8]">{{ rfc.nama_aplikasi || '-' }}</div>
                </div>

                <!-- 11. Alamat URL Aplikasi -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">ALAMAT URL APLIKASI</div>
                  <div class="text-slate-900 font-medium font-mono truncate">
                    <a v-if="rfc.alamat_aplikasi" :href="rfc.alamat_aplikasi" target="_blank" class="text-[#0757a8] hover:underline">{{ rfc.alamat_aplikasi }}</a>
                    <span v-else>-</span>
                  </div>
                </div>

                <!-- 12. Perubahan yang Diharapkan (Box) -->
                <div class="md:col-span-2">
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">PERUBAHAN YANG DIHARAPKAN</div>
                  <div class="text-slate-800 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    {{ rfc.solusi_diharapkan || rfc.deskripsi || rfc.deskripsi_perubahan || '-' }}
                  </div>
                </div>

                <!-- 13. Latar Belakang Perubahan (Box) -->
                <div class="md:col-span-2">
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">LATAR BELAKANG PERUBAHAN</div>
                  <div class="text-slate-800 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-100 whitespace-pre-wrap">
                    {{ rfc.latar_belakang || '-' }}
                  </div>
                </div>

                <!-- 14. Rincian Perubahan yang Diajukan (Box) -->
                <div class="md:col-span-2">
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">RINCIAN PERUBAHAN YANG DIAJUKAN</div>
                  <div class="text-slate-800 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-100 whitespace-pre-wrap font-mono text-xs">
                    {{ rfc.rincian_perubahan || '-' }}
                  </div>
                </div>

                <!-- 15. Kriteria Risiko -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">KRITERIA RISIKO</div>
                  <div class="text-slate-900 font-semibold capitalize">{{ (rfc.kriteria_risiko || '-').replace(/_/g, ' ') }}</div>
                </div>

                <!-- 16. Estimasi Biaya -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">ESTIMASI BIAYA</div>
                  <div class="text-slate-900 font-medium">{{ rfc.biaya_perubahan || '-' }}</div>
                </div>

                <!-- 17. Target Waktu Pengerjaan -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">TARGET WAKTU PENGERJAAN</div>
                  <div class="text-slate-900 font-medium">{{ rfc.waktu_perubahan ? `${rfc.waktu_perubahan} Hari Kerja` : '-' }}</div>
                </div>

                <!-- 18. Risiko Jika Tidak Dilakukan -->
                <div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">RISIKO JIKA TIDAK DILAKUKAN</div>
                  <div class="text-slate-800 font-medium">{{ rfc.risiko_tidak_dilakukan || '-' }}</div>
                </div>
              </div>

            </div>

            <!-- KARTU 2: MATRIKS OTORISASI FR-015 (4 PIHAK SESUAI WORKFLOW) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <h2 class="font-bold text-slate-800 text-base">Matriks Otorisasi FR-015</h2>
                  <p class="text-xs text-slate-400 mt-0.5">Otorisasi empat pihak terisi bertahap sesuai trigger alur kerja</p>
                </div>
                <span class="text-[11px] text-slate-500 font-mono">FR-015 4-Party Auth</span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- 1. Pemohon (Terisi langsung sejak pengajuan) -->
                <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/70 flex flex-col justify-between min-h-[190px] shadow-2xs">
                  <div>
                    <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.tanggal_permohonan || '-' }}</div>
                    <div class="text-xs font-bold text-slate-800">1. Dibuat Oleh:</div>
                    <div class="text-xs text-[#0757A8] font-bold">Pemohon OPD</div>
                  </div>

                  <!-- TTD Pemohon -->
                  <div class="my-3 flex items-center justify-center min-h-[60px] bg-white rounded-xl border border-dashed border-slate-200 p-1.5 shadow-2xs">
                    <img
                      v-if="rfc.tanda_tangan_url"
                      :src="rfc.tanda_tangan_url"
                      alt="TTD Pemohon"
                      class="max-h-12 max-w-[130px] object-contain"
                    />
                    <span v-else class="text-emerald-600 text-xs font-bold flex items-center gap-1.5">
                      <Icon icon="lucide:check-circle" class="w-4 h-4" />
                      <span>Telah Diajukan</span>
                    </span>
                  </div>

                  <div class="border-t border-slate-200/80 pt-2 text-center">
                    <div class="text-xs font-bold text-slate-800 truncate" :title="rfc.nama_pemohon">{{ rfc.nama_pemohon }}</div>
                    <div class="text-[10px] text-slate-500 truncate" :title="rfc.unit_kerja || rfc.opd">{{ rfc.unit_kerja || rfc.opd }}</div>
                  </div>
                </div>

                <!-- 2. Penanggung Jawab OPD -->
                <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/70 flex flex-col justify-between min-h-[190px] shadow-2xs">
                  <div>
                    <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.tanggal_permohonan || '-' }}</div>
                    <div class="text-xs font-bold text-slate-800">2. Disetujui Oleh:</div>
                    <div class="text-xs text-emerald-700 font-bold">Penanggung Jawab OPD</div>
                  </div>

                  <!-- TTD Penanggung Jawab OPD -->
                  <div class="my-3 flex items-center justify-center min-h-[60px] bg-white rounded-xl border border-dashed border-slate-200 p-1.5 shadow-2xs">
                    <img
                      v-if="rfc.tanda_tangan_penanggung_jawab_url"
                      :src="rfc.tanda_tangan_penanggung_jawab_url"
                      alt="TTD Kabid OPD"
                      class="max-h-12 max-w-[130px] object-contain"
                    />
                    <span v-else class="text-emerald-600 text-xs font-bold flex items-center gap-1.5">
                      <Icon icon="lucide:check-circle" class="w-4 h-4" />
                      <span>Disetujui Kedinasan</span>
                    </span>
                  </div>

                  <div class="border-t border-slate-200/80 pt-2 text-center">
                    <div class="text-xs font-bold text-slate-800 truncate" :title="rfc.nama_penanggung_jawab || 'Kepala Bidang OPD'">{{ rfc.nama_penanggung_jawab || 'Kepala Bidang OPD' }}</div>
                    <div class="text-[10px] text-slate-500 truncate">{{ rfc.nip_penanggung_jawab ? 'NIP. ' + rfc.nip_penanggung_jawab : 'Kabid Kedinasan' }}</div>
                  </div>
                </div>

                <!-- 3. Pimpinan Diskominfo -->
                <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/70 flex flex-col justify-between min-h-[190px] shadow-2xs">
                  <div>
                    <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.persetujuan?.diputuskan_pada_tanggal || rfc.persetujuan?.diputuskan_pada || 'Belum' }}</div>
                    <div class="text-xs font-bold text-slate-800">3. Diterima Oleh:</div>
                    <div class="text-xs text-[#0757A8] font-bold">Pimpinan Diskominfo</div>
                  </div>

                  <!-- Status TTD Pimpinan -->
                  <div class="my-3 flex items-center justify-center min-h-[60px] bg-white rounded-xl border border-dashed border-slate-200 p-1.5 shadow-2xs">
                    <img
                      v-if="rfc.persetujuan?.tanda_tangan_pimpinan_url"
                      :src="rfc.persetujuan.tanda_tangan_pimpinan_url"
                      alt="TTD Pimpinan"
                      class="max-h-12 max-w-[130px] object-contain"
                    />
                    <div v-else-if="rfc.persetujuan" class="text-center">
                      <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#0757A8] bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                        <Icon icon="lucide:check-circle" class="w-3.5 h-3.5" />
                        <span>Disahkan Pimpinan</span>
                      </span>
                    </div>
                    <div v-else class="text-slate-400 text-xs italic flex items-center gap-1.5">
                      <Icon icon="lucide:clock" class="w-4 h-4" />
                      <span>Menunggu keputusan pimpinan</span>
                    </div>
                  </div>

                  <div class="border-t border-slate-200/80 pt-2 text-center">
                    <div class="text-xs font-bold text-slate-800 truncate" :title="rfc.persetujuan?.pimpinan || '( Menunggu Pimpinan )'">{{ rfc.persetujuan?.pimpinan || '( Menunggu Pimpinan )' }}</div>
                    <div class="text-[10px] text-slate-500 truncate">{{ rfc.persetujuan?.pimpinan_nip ? 'NIP. ' + rfc.persetujuan.pimpinan_nip : 'Otorisasi Diskominfo' }}</div>
                  </div>
                </div>

                <!-- 4. Teknisi Pelaksana -->
                <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/70 flex flex-col justify-between min-h-[190px] shadow-2xs">
                  <div>
                    <div class="text-[10px] text-slate-500 font-mono mb-1">Tanggal: {{ rfc.bast?.dibuat_pada_tanggal || rfc.bast?.dibuat_pada || rfc.eksekusi?.selesai_pada || 'Belum' }}</div>
                    <div class="text-xs font-bold text-slate-800">4. Dilaksanakan Oleh:</div>
                    <div class="text-xs text-cyan-700 font-bold">Teknisi Pelaksana</div>
                  </div>

                  <!-- Status TTD Teknisi -->
                  <div class="my-3 flex items-center justify-center min-h-[60px] bg-white rounded-xl border border-dashed border-slate-200 p-1.5 shadow-2xs">
                    <img
                      v-if="rfc.bast?.tanda_tangan_teknisi_url"
                      :src="rfc.bast.tanda_tangan_teknisi_url"
                      alt="TTD Teknisi"
                      class="max-h-12 max-w-[130px] object-contain"
                    />
                    <div v-else-if="rfc.bast" class="text-center">
                      <span class="inline-flex items-center gap-1 text-[11px] font-bold text-cyan-700 bg-cyan-50 px-2.5 py-1 rounded-lg border border-cyan-200">
                        <Icon icon="lucide:check-circle" class="w-3.5 h-3.5" />
                        <span>BAST Terbit</span>
                      </span>
                    </div>
                    <div v-else-if="rfc.eksekusi" class="text-center">
                      <span class="inline-flex items-center gap-1 text-[11px] font-bold text-cyan-700 bg-cyan-50 px-2.5 py-1 rounded-lg border border-cyan-200">
                        <Icon icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
                        <span>Sedang Dikerjakan</span>
                      </span>
                    </div>
                    <div v-else class="text-slate-400 text-xs italic flex items-center gap-1.5">
                      <Icon icon="lucide:clock" class="w-4 h-4" />
                      <span>Menunggu pengerjaan teknisi</span>
                    </div>
                  </div>

                  <div class="border-t border-slate-200/80 pt-2 text-center">
                    <div class="text-xs font-bold text-slate-800 truncate" :title="rfc.bast?.nama_teknisi || rfc.eksekusi?.teknisi || '( Menunggu Teknisi )'">{{ rfc.bast?.nama_teknisi || rfc.eksekusi?.teknisi || '( Menunggu Teknisi )' }}</div>
                    <div class="text-[10px] text-slate-500 truncate">{{ rfc.bast?.nip_teknisi ? 'NIP. ' + rfc.bast.nip_teknisi : 'Pelaksana Teknis Diskominfo' }}</div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- ====================================================
               KOLOM 2 (KANAN - 6/12): RUANG KERJA EKSEKUSI & WORKFLOW
          ==================================================== -->
          <div class="xl:col-span-6 space-y-5 xl:max-h-[calc(100vh-6rem)] xl:overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-slate-200">

            <!-- STEPPER 5 TAHAP ALUR PERMOHONAN -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs">
              <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center justify-between">
                <span>Alur Kerja Implementasi (ITIL / ISO 27001)</span>
                <span class="text-[#0757A8] font-bold">Tahap {{ activeStepIndex + 1 }} dari 5</span>
              </div>

              <!-- Bar Step Bulat -->
              <div class="grid grid-cols-5 gap-1 pt-1">
                <div v-for="(s, idx) in workflowSteps" :key="s.name" class="flex flex-col items-center text-center">
                  <div
                    :class="[
                      'w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black text-xs transition-all shadow-xs mb-1.5',
                      idx < activeStepIndex ? 'bg-emerald-500 text-white' :
                      idx === activeStepIndex ? 'bg-[#0757A8] text-white ring-4 ring-[#0757A8]/20' :
                      'bg-slate-100 text-slate-400 border border-slate-200'
                    ]"
                  >
                    <Icon v-if="idx < activeStepIndex" icon="lucide:check" class="w-4 h-4 stroke-[3]" />
                    <span v-else>{{ idx + 1 }}</span>
                  </div>
                  <span
                    class="text-[10px] leading-tight font-medium"
                    :class="idx === activeStepIndex ? 'font-bold text-[#0757A8]' : idx < activeStepIndex ? 'text-emerald-700' : 'text-slate-400'"
                  >
                    {{ s.name }}
                  </span>
                </div>
              </div>
            </div>

            <!-- BILAH TAB NAVIGASI RUANG KERJA -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-1.5 flex flex-wrap gap-1">
              <button
                v-for="tab in visibleTabs"
                :key="tab.id"
                type="button"
                @click="activeTab = tab.id"
                :class="[
                  'px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
                  activeTab === tab.id ? 'bg-[#0757A8] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'
                ]"
              >
                <Icon :icon="tab.icon" class="w-4 h-4" />
                <span>{{ tab.name }}</span>
                <span v-if="tab.active" class="w-2 h-2 rounded-full" :class="tab.dotColor"></span>
              </button>
            </div>

            <!-- ==============================================
                 KONTEN TAB 1: RIWAYAT AKTIVITAS & JEJAK AUDIT
            ============================================== -->
            <div v-if="activeTab === 'info'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                  <Icon icon="lucide:history" class="w-4 h-4 text-[#0757A8]" />
                  <span>Jejak Audit & Riwayat Aktivitas</span>
                </h3>
                <span class="text-[11px] text-slate-400">{{ histories.length }} Catatan</span>
              </div>

              <div v-if="!histories || histories.length === 0" class="py-8 text-center text-slate-400 text-xs">
                <Icon icon="lucide:clock" class="w-7 h-7 mx-auto mb-2 text-slate-300" />
                <p>Belum ada riwayat aktivitas yang tercatat.</p>
              </div>

              <div v-else class="space-y-3.5">
                <div v-for="h in histories" :key="h.id" class="flex items-start gap-3 text-xs">
                  <div :class="['w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-black shadow-xs', roleColor(h.peran_aktor)]">
                    {{ (h.nama_aktor || '?').charAt(0).toUpperCase() }}
                  </div>
                  <div class="flex-1 bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                    <div class="flex items-center justify-between">
                      <div class="font-bold text-slate-800">
                        {{ h.nama_aktor }}
                        <span class="font-normal text-slate-500 text-[11px]">({{ h.peran_aktor }})</span>
                      </div>
                      <span class="text-[10px] text-slate-400">{{ h.created_at }}</span>
                    </div>
                    <div class="text-slate-700 mt-1 font-medium">{{ h.keterangan }}</div>
                    <div v-if="h.catatan" class="text-slate-600 bg-white p-2 rounded-lg mt-1.5 border border-slate-100 italic text-[11px]">
                      "{{ h.catatan }}"
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ==============================================
                 KONTEN TAB 2: VERIFIKASI (VERIFIKATOR)
            ============================================== -->
            <div v-if="activeTab === 'verifikasi'" class="bg-white rounded-2xl border-t-4 border-purple-600 border border-slate-200 shadow-xs p-5 space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <h3 class="text-sm font-bold text-slate-800">Verifikasi Kelayakan Perubahan</h3>
                  <p class="text-slate-500 text-xs mt-0.5">Tinjau kesesuaian dokumen dan kirim rekomendasi ke pimpinan</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded bg-purple-100 text-purple-800">Verifikator</span>
              </div>

              <form v-if="izin.bisa_telaah" @submit.prevent="submitVerifikasi" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Penilaian Dampak <span class="text-red-500">*</span></label>
                    <select v-model="formVerif.dampak" required
                      class="w-full text-xs px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none">
                      <option value="" disabled>Pilih dampak...</option>
                      <option value="tinggi">Tinggi - Memengaruhi layanan publik provinsi</option>
                      <option value="sedang">Sedang - Memengaruhi beberapa unit kerja</option>
                      <option value="rendah">Rendah - Lingkup internal terbatas</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Penilaian Urgensi <span class="text-red-500">*</span></label>
                    <select v-model="formVerif.urgensi_penilaian" required
                      class="w-full text-xs px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none">
                      <option value="" disabled>Pilih urgensi...</option>
                      <option value="kritis">Kritis - Risiko henti layanan / keamanan</option>
                      <option value="tinggi">Tinggi - Kebutuhan operasional mendesak</option>
                      <option value="sedang">Sedang - Perbaikan terencana</option>
                      <option value="rendah">Rendah - Peningkatan minor</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Telaah <span class="text-red-500">*</span></label>
                  <textarea v-model="formVerif.catatan_verifikasi" rows="3" required
                    placeholder="Uraian hasil verifikasi kelayakan teknis..."
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none" />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-2">Rekomendasi <span class="text-red-500">*</span></label>
                  <div class="grid grid-cols-3 gap-2.5">
                    <label v-for="opt in rekomendasiOptions" :key="opt.value"
                      :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all',
                        formVerif.rekomendasi === opt.value ? opt.activeClass : 'border-slate-200 hover:bg-slate-50']">
                      <input type="radio" :value="opt.value" v-model="formVerif.rekomendasi" class="sr-only" />
                      <Icon :icon="opt.icon" class="w-5 h-5 mb-1" :class="opt.iconClass" />
                      <span class="font-bold text-xs">{{ opt.label }}</span>
                    </label>
                  </div>
                </div>

                <div class="flex justify-end pt-2">
                  <button type="submit" :disabled="isSubmitting"
                    class="px-5 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5 disabled:opacity-50">
                    <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                    <span>{{ isSubmitting ? 'Menyimpan...' : 'Kirim Telaah Verifikator' }}</span>
                  </button>
                </div>
              </form>

              <div v-else class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-center">
                <Icon icon="lucide:lock" class="w-6 h-6 mx-auto mb-1.5 text-slate-400" />
                <p v-if="rfc.status_terkini === 'diajukan'">Hanya akun dengan peran Verifikator yang dapat memproses telaah pada tahap ini.</p>
                <p v-else class="text-purple-800 font-semibold">Tahap telaah verifikasi kelayakan telah selesai diproses.</p>
              </div>
            </div>

            <!-- ==============================================
                 KONTEN TAB 3: KEPUTUSAN (PIMPINAN)
            ============================================== -->
            <div v-if="activeTab === 'pimpinan'" class="bg-white rounded-2xl border-t-4 border-blue-600 border border-slate-200 shadow-xs p-5 space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <h3 class="text-sm font-bold text-slate-800">Otorisasi & Keputusan Pimpinan</h3>
                  <p class="text-slate-500 text-xs mt-0.5">Pengesahan resmi perubahan layanan TI</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded bg-blue-100 text-blue-800">Pimpinan</span>
              </div>

              <form v-if="izin.bisa_keputusan" @submit.prevent="submitKeputusan" class="space-y-4">
                <!-- Status Info Jika Sedang Ditunda -->
                <div v-if="rfc.status_terkini === 'ditunda'" class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start gap-2.5">
                  <Icon icon="lucide:clock" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" />
                  <div>
                    <div class="font-bold">Permohonan Ini Sedang Ditunda</div>
                    <div class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                      Jadwal evaluasi tinjau ulang: <strong>{{ rfc.persetujuan?.diputuskan_pada_tanggal || rfc.persetujuan?.diputuskan_pada || 'Belum ditentukan' }}</strong>.
                      Sebagai Pimpinan, Anda dapat langsung memproses keputusan baru sekarang (Setujui atau Tolak) atau memperbarui jadwal penundaan.
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-2">Keputusan <span class="text-red-500">*</span></label>
                  <div class="grid grid-cols-3 gap-2.5">
                    <label v-for="opt in keputusanOptions" :key="opt.value"
                      :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all',
                        formPimpinan.keputusan === opt.value ? opt.activeClass : 'border-slate-200 hover:bg-slate-50']">
                      <input type="radio" :value="opt.value" v-model="formPimpinan.keputusan" class="sr-only" />
                      <Icon :icon="opt.icon" class="w-5 h-5 mb-1" :class="opt.iconClass" />
                      <span class="font-bold text-xs">{{ opt.label }}</span>
                    </label>
                  </div>
                </div>

                <!-- Pilihan Teknisi jika Disetujui -->
                <div v-if="formPimpinan.keputusan === 'disetujui'">
                  <label class="block text-xs font-bold text-slate-700 mb-1">Tugaskan Teknisi Pelaksana <span class="text-red-500">*</span></label>
                  <select v-model="formPimpinan.teknisi_id" required
                    class="w-full text-xs px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 outline-none">
                    <option value="" disabled>Pilih teknisi pelaksana...</option>
                    <option v-for="t in teknisiList" :key="t.id" :value="t.id">{{ t.nama }}</option>
                  </select>
                </div>

                <!-- Jadwal Tinjau Ulang jika Ditunda -->
                <div v-if="formPimpinan.keputusan === 'ditunda'">
                  <label class="block text-xs font-bold text-slate-700 mb-1">Jadwal Tinjau Ulang <span class="text-red-500">*</span></label>
                  <input
                    type="date"
                    v-model="formPimpinan.jadwal_tinjau_ulang"
                    required
                    :min="new Date().toISOString().split('T')[0]"
                    class="w-full text-xs px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 outline-none"
                  />
                  <p class="text-[11px] text-amber-700 mt-1">Tentukan tanggal evaluasi ulang untuk permohonan ini.</p>
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Arahan Pimpinan</label>
                  <textarea v-model="formPimpinan.catatan_keputusan" rows="3"
                    placeholder="Instruksi khusus pelaksanaan atau alasan keputusan..."
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 outline-none" />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Tanda Tangan Digital Pimpinan</label>
                  <SignaturePad v-model="formPimpinan.tanda_tangan" />
                </div>

                <div class="flex justify-end pt-2">
                  <button type="submit" :disabled="isSubmitting"
                    class="px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5 disabled:opacity-50">
                    <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                    <span>{{ isSubmitting ? 'Menyimpan...' : 'Sahkan Keputusan' }}</span>
                  </button>
                </div>
              </form>

              <div v-else class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-center">
                <Icon icon="lucide:lock" class="w-6 h-6 mx-auto mb-1.5 text-slate-400" />
                <p v-if="rfc.status_terkini === 'menunggu_persetujuan'">Hanya akun dengan peran Pimpinan yang dapat memberikan keputusan pada tahap ini.</p>
                <p v-else class="text-emerald-800 font-semibold">Keputusan otorisasi pimpinan telah selesai disahkan.</p>
              </div>
            </div>

            <!-- ==============================================
                 KONTEN TAB 4: EKSEKUSI TEKNISI
            ============================================== -->
            <div v-if="activeTab === 'eksekusi'" class="bg-white rounded-2xl border-t-4 border-cyan-600 border border-slate-200 shadow-xs p-5 space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <h3 class="text-sm font-bold text-slate-800">Ruang Implementasi Teknisi</h3>
                  <p class="text-slate-500 text-xs mt-0.5">Konfirmasi pengerjaan dan mulai eksekusi perubahan layanan TI</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded bg-cyan-100 text-cyan-800">Teknisi</span>
              </div>

              <!-- Form Mulai Pengerjaan (jika status disetujui & teknisi berwenang) -->
              <form v-if="izin.bisa_eksekusi" @submit.prevent="submitEksekusi" class="space-y-4">
                <div class="p-4 rounded-xl bg-cyan-50 border border-cyan-200 text-xs text-cyan-900 leading-relaxed">
                  Permohonan RFC telah disetujui pimpinan dan ditugaskan kepada Anda. Silakan pelajari rincian teknis pada lembar FR-015 di sebelah kiri, lalu klik <strong>"Mulai Pengerjaan"</strong> untuk mengaktifkan status implementasi.
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Teknis Awal</label>
                  <textarea v-model="formEksekusi.catatan_eksekusi" rows="3"
                    placeholder="Rencana teknis, jadwal eksekusi konfigurasi, atau catatan awal..."
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-cyan-500/20 outline-none" />
                </div>

                <div class="flex justify-end pt-1">
                  <button type="submit" :disabled="isSubmitting"
                    class="px-5 py-2.5 rounded-xl bg-cyan-700 hover:bg-cyan-800 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5 disabled:opacity-50">
                    <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                    <Icon v-else icon="lucide:play" class="w-4 h-4" />
                    <span>{{ isSubmitting ? 'Memproses...' : 'Mulai Pengerjaan' }}</span>
                  </button>
                </div>
              </form>

              <!-- Informasi jika pengerjaan sedang berlangsung atau sudah selesai -->
              <div v-else class="space-y-3">
                <div v-if="rfc.status_terkini === 'sedang_dikerjakan'" class="p-4 rounded-xl bg-cyan-50 border border-cyan-200 text-xs space-y-2">
                  <div class="flex items-center gap-2 font-bold text-cyan-900">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 animate-pulse"></span>
                    <span>Pengerjaan Sedang Berlangsung</span>
                  </div>
                  <p class="text-cyan-800 leading-relaxed">
                    RFC saat ini sedang dalam proses implementasi dan pengujian sistem. Setelah pekerjaan tuntas, silakan buka tab <strong>"5. BAST & Penutupan"</strong> untuk menerbitkan Berita Acara Serah Terima.
                  </p>
                  <button
                    type="button"
                    @click="activeTab = 'bast'"
                    class="mt-2 px-4 py-2 rounded-xl bg-cyan-700 text-white font-bold text-xs hover:bg-cyan-800 transition"
                  >
                    Buka Tab BAST &rarr;
                  </button>
                </div>

                <div v-else class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-center">
                  <Icon icon="lucide:lock" class="w-6 h-6 mx-auto mb-1.5 text-slate-400" />
                  <p v-if="rfc.status_terkini === 'disetujui'">Menunggu teknisi pelaksana yang ditugaskan untuk memulai pengerjaan.</p>
                  <p v-else>Tahap pengerjaan teknisi telah dilewati / selesai.</p>
                </div>
              </div>
            </div>

            <!-- ==============================================
                 KONTEN TAB 5: BAST (BERITA ACARA SERAH TERIMA)
            ============================================== -->
            <div v-if="activeTab === 'bast'" class="bg-white rounded-2xl border-t-4 border-emerald-600 border border-slate-200 shadow-xs p-5 space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <h3 class="text-sm font-bold text-slate-800">Berita Acara Serah Terima (BAST)</h3>
                  <p class="text-slate-500 text-xs mt-0.5">Unggah berkas BAST resmi bertanda tangan untuk menutup tiket menjadi Selesai</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800">BAST</span>
              </div>

              <!-- Form Upload BAST (Jika teknisi berwenang pada status sedang_dikerjakan) -->
              <form v-if="izin.bisa_bast" @submit.prevent="submitBast" class="space-y-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Dokumen BAST <span class="text-red-500">*</span></label>
                  <input
                    v-model="formBast.nomor_dokumen"
                    type="text"
                    required
                    placeholder="Contoh: 000.1.2/BAST-012/DISKOMINFO/2026"
                    class="w-full text-xs px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Hasil Pengerjaan</label>
                  <textarea
                    v-model="formBast.catatan"
                    rows="3"
                    placeholder="Deskripsikan hasil uji implementasi, verifikasi fungsi sistem..."
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none"
                  />
                </div>

                <!-- Unggah File BAST PDF -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Berkas BAST Bertanda Tangan (PDF) <span class="text-red-500">*</span></label>
                  <input
                    type="file"
                    accept=".pdf"
                    required
                    @change="e => fileBast = e.target.files[0]"
                    class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer"
                  />
                  <p class="text-[11px] text-slate-400 mt-1">Format PDF maks. 10MB. Berkas harus telah ditandatangani oleh pemohon dan teknisi.</p>
                </div>

                <!-- Tanda Tangan Digital Teknisi Pelaksana -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">
                    Tanda Tangan Digital Teknisi Pelaksana <span class="text-red-500">*</span>
                  </label>
                  <p class="text-[11px] text-slate-500 mb-2">
                    Bubuhkan tanda tangan digital Anda untuk pengesahan Berita Acara Serah Terima (BAST) dan pembuktian penyelesaian perubahan TI.
                  </p>
                  <SignaturePad v-model="formBast.tanda_tangan" />
                </div>

                <div class="flex justify-end pt-2">
                  <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5 disabled:opacity-50"
                  >
                    <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                    <Icon v-else icon="lucide:check-circle" class="w-4 h-4" />
                    <span>{{ isSubmitting ? 'Menyimpan BAST...' : 'Terbitkan BAST & Tutup Tiket Selesai' }}</span>
                  </button>
                </div>
              </form>

              <!-- Tampilan Berkas BAST jika sudah ada / selesai -->
              <div v-else-if="rfc.bast" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs space-y-3">
                <div class="flex items-center gap-2 font-bold text-emerald-900 text-sm">
                  <Icon icon="lucide:file-check" class="w-5 h-5 text-emerald-600" />
                  <span>Dokumen BAST Telah Diterbitkan</span>
                </div>

                <div class="bg-white p-3.5 rounded-xl border border-emerald-100 space-y-1.5">
                  <div class="text-slate-500 text-[11px]">Nomor BAST:</div>
                  <div class="font-bold text-slate-900 font-mono text-sm">{{ rfc.bast.nomor_dokumen }}</div>
                  <div v-if="rfc.bast.catatan" class="text-slate-600 pt-1 text-xs italic">
                    "{{ rfc.bast.catatan }}"
                  </div>
                </div>

                <a
                  v-if="rfc.bast.path_file_url"
                  :href="rfc.bast.path_file_url"
                  target="_blank"
                  download
                  class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs"
                >
                  <Icon icon="lucide:download" class="w-4 h-4" />
                  <span>Unduh Dokumen BAST (PDF)</span>
                </a>
              </div>

              <div v-else class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-center">
                <Icon icon="lucide:lock" class="w-6 h-6 mx-auto mb-1.5 text-slate-400" />
                <p>Tahap BAST baru dapat dibuka setelah pengerjaan teknisi dimulai (*status: sedang dikerjakan*).</p>
              </div>
            </div>

          </div>

        </div>

      </template>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, reactive, onMounted, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'
import SignaturePad from '@/Components/SignaturePad.vue'

// ── Props ─────────────────────────────────────────────────────────────────────
const props = defineProps({
  rfc:      { type: Object, default: null },
  histories:{ type: Array,  default: () => [] },
  izin:     { type: Object, default: () => ({
    bisa_telaah: false, bisa_keputusan: false, bisa_eksekusi: false, bisa_bast: false,
  })},
})

// ── State ─────────────────────────────────────────────────────────────────────
const isSubmitting     = ref(false)
const alertMsg         = ref(null)
const alertType        = ref('success')
const fileBast         = ref(null)
const teknisiList      = ref([])
const hasRestoredDraft = ref(false)

// ── Tahapan Workflow ──────────────────────────────────────────────────────────
const workflowSteps = [
  { name: '1. Diajukan' },
  { name: '2. Verifikasi' },
  { name: '3. Keputusan' },
  { name: '4. Eksekusi' },
  { name: '5. Selesai' },
]

const activeStepIndex = computed(() => {
  const s = props.rfc?.status_terkini
  if (s === 'diajukan' || s === 'perlu_revisi') return 1
  if (s === 'menunggu_persetujuan' || s === 'ditunda') return 2
  if (s === 'disetujui' || s === 'sedang_dikerjakan') return 3
  if (s === 'selesai') return 4
  return 0
})

// Tentukan tab aktif awal berdasarkan hak akses & status
function tabFromStatus(status) {
  if (props.izin.bisa_telaah || status === 'diajukan') return 'verifikasi'
  if (props.izin.bisa_keputusan || status === 'menunggu_persetujuan' || status === 'ditunda') return 'pimpinan'
  if (props.izin.bisa_eksekusi || status === 'disetujui') return 'eksekusi'
  if (props.izin.bisa_bast || status === 'sedang_dikerjakan') return 'bast'
  return 'info'
}

const urlTab = typeof window !== 'undefined' ? new URLSearchParams(window.location.search).get('tab') : null
const activeTab = ref(urlTab || tabFromStatus(props.rfc?.status_terkini ?? 'info'))

// ── Form States ───────────────────────────────────────────────────────────────
const formVerif = reactive({
  rekomendasi: 'lanjut_pimpinan',
  catatan_verifikasi: '',
  dampak: '',
  urgensi_penilaian: '',
  catatan_revisi_spesifik: '',
})

const formPimpinan = reactive({
  keputusan: 'disetujui',
  catatan_keputusan: '',
  teknisi_id: '',
  jadwal_tinjau_ulang: '',
  tanda_tangan: null,
})

const formEksekusi = reactive({ catatan_eksekusi: '' })
const formBast = reactive({ nomor_dokumen: '', catatan: '', tanda_tangan: null })

// ── Auto-save Draf ke LocalStorage ────────────────────────────────────────────
const workspaceStorageKey = computed(() => props.rfc ? `si_rfc_workspace_draft_${props.rfc.id}` : null)

function saveWorkspaceDraft() {
  if (!workspaceStorageKey.value) return
  try {
    const data = {
      formVerif: { ...formVerif },
      formPimpinan: { ...formPimpinan },
      formEksekusi: { ...formEksekusi },
      formBast: {
        nomor_dokumen: formBast.nomor_dokumen,
        catatan: formBast.catatan,
        tanda_tangan: formBast.tanda_tangan,
      },
    }
    localStorage.setItem(workspaceStorageKey.value, JSON.stringify(data))
  } catch {}
}

function loadWorkspaceDraft() {
  if (!workspaceStorageKey.value) return
  try {
    const raw = localStorage.getItem(workspaceStorageKey.value)
    if (raw) {
      const parsed = JSON.parse(raw)
      let restored = false
      if (parsed.formVerif && props.izin.bisa_telaah) {
        Object.assign(formVerif, parsed.formVerif)
        restored = true
      }
      if (parsed.formPimpinan && props.izin.bisa_keputusan) {
        Object.assign(formPimpinan, parsed.formPimpinan)
        restored = true
      }
      if (parsed.formEksekusi && props.izin.bisa_eksekusi) {
        Object.assign(formEksekusi, parsed.formEksekusi)
        restored = true
      }
      if (parsed.formBast && props.izin.bisa_bast) {
        formBast.nomor_dokumen = parsed.formBast.nomor_dokumen || ''
        formBast.catatan = parsed.formBast.catatan || ''
        formBast.tanda_tangan = parsed.formBast.tanda_tangan || null
        restored = true
      }
      hasRestoredDraft.value = restored
    }
  } catch {}
}

function clearWorkspaceDraft(section = null) {
  if (!workspaceStorageKey.value) return
  try {
    if (!section) {
      localStorage.removeItem(workspaceStorageKey.value)
      hasRestoredDraft.value = false
    } else {
      const raw = localStorage.getItem(workspaceStorageKey.value)
      if (raw) {
        const parsed = JSON.parse(raw)
        delete parsed[section]
        localStorage.setItem(workspaceStorageKey.value, JSON.stringify(parsed))
      }
    }
  } catch {}
}

watch([formVerif, formPimpinan, formEksekusi, formBast], () => {
  saveWorkspaceDraft()
}, { deep: true })

onMounted(async () => {
  if (!formVerif.urgensi_penilaian && props.rfc?.urgensi) {
    formVerif.urgensi_penilaian = props.rfc.urgensi
  }
  loadWorkspaceDraft()

  try {
    const res = await axios.get('/api/teknisi-list')
    teknisiList.value = res.data
  } catch {}
})

// ── Tab Visibility ────────────────────────────────────────────────────────────
const visibleTabs = computed(() => {
  return [
    { id: 'info', name: 'Riwayat Audit', icon: 'lucide:history', active: false, dotColor: '' },
    { id: 'verifikasi', name: 'Telaah Verifikasi', icon: 'lucide:shield-check',
      active: props.rfc?.status_terkini === 'diajukan', dotColor: 'bg-purple-500' },
    { id: 'pimpinan', name: 'Keputusan Pimpinan', icon: 'lucide:scale',
      active: props.rfc?.status_terkini === 'menunggu_persetujuan' || props.rfc?.status_terkini === 'ditunda', dotColor: 'bg-blue-500' },
    { id: 'eksekusi', name: 'Eksekusi Teknisi', icon: 'lucide:code-2',
      active: props.rfc?.status_terkini === 'disetujui', dotColor: 'bg-cyan-500' },
    { id: 'bast', name: 'BAST & Penutupan', icon: 'lucide:file-check',
      active: props.rfc?.status_terkini === 'sedang_dikerjakan', dotColor: 'bg-emerald-500' },
  ]
})

// ── Submissions ───────────────────────────────────────────────────────────────
async function submitVerifikasi() {
  isSubmitting.value = true
  try {
    await axios.post(`/rfc/${props.rfc.id}/telaah`, {
      rekomendasi:    formVerif.rekomendasi,
      catatan_revisi: formVerif.catatan_verifikasi,
    })
    showAlert('Keputusan verifikasi berhasil disimpan.', 'success')
    clearWorkspaceDraft('formVerif')
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    showAlert(err.response?.data?.message || 'Gagal menyimpan verifikasi.', 'error')
  } finally {
    isSubmitting.value = false
  }
}

async function submitKeputusan() {
  isSubmitting.value = true
  try {
    await axios.post(`/rfc/${props.rfc.id}/keputusan`, {
      keputusan:          formPimpinan.keputusan,
      catatan_keputusan:  formPimpinan.catatan_keputusan,
      teknisi_id:         formPimpinan.keputusan === 'disetujui' ? formPimpinan.teknisi_id : null,
      jadwal_tinjau_ulang: formPimpinan.keputusan === 'ditunda' ? formPimpinan.jadwal_tinjau_ulang : null,
      tanda_tangan:        formPimpinan.tanda_tangan,
    })
    showAlert('Keputusan pimpinan berhasil disimpan.', 'success')
    clearWorkspaceDraft('formPimpinan')
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    showAlert(err.response?.data?.message || 'Gagal menyimpan keputusan.', 'error')
  } finally {
    isSubmitting.value = false
  }
}

async function submitEksekusi() {
  isSubmitting.value = true
  try {
    await axios.post(`/rfc/${props.rfc.id}/mulai-eksekusi`, {
      catatan_eksekusi: formEksekusi.catatan_eksekusi,
    })
    showAlert('Pengerjaan RFC berhasil dimulai.', 'success')
    clearWorkspaceDraft('formEksekusi')
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    showAlert(err.response?.data?.message || 'Gagal memulai eksekusi.', 'error')
  } finally {
    isSubmitting.value = false
  }
}

async function submitBast() {
  if (!fileBast.value) {
    showAlert('File dokumen BAST (PDF) harus dipilih.', 'error')
    return
  }
  if (!formBast.tanda_tangan) {
    showAlert('Tanda tangan digital teknisi pelaksana wajib dibubuhkan.', 'error')
    return
  }
  isSubmitting.value = true
  try {
    const fd = new FormData()
    fd.append('nomor_dokumen', formBast.nomor_dokumen)
    fd.append('catatan', formBast.catatan || '')
    fd.append('file_bast', fileBast.value)
    if (formBast.tanda_tangan) fd.append('tanda_tangan', formBast.tanda_tangan)

    await axios.post(`/rfc/${props.rfc.id}/bast`, fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    showAlert('BAST berhasil diterbitkan. RFC resmi ditutup menjadi SELESAI.', 'success')
    clearWorkspaceDraft('formBast')
    setTimeout(() => router.reload(), 1500)
  } catch (err) {
    showAlert(err.response?.data?.message || 'Gagal menerbitkan BAST.', 'error')
  } finally {
    isSubmitting.value = false
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function formatJenisPerubahan(jenis) {
  if (!jenis) return '-'
  if (Array.isArray(jenis)) return jenis.join(', ')
  try {
    const parsed = JSON.parse(jenis)
    return Array.isArray(parsed) ? parsed.join(', ') : parsed
  } catch {
    return String(jenis)
  }
}

function showAlert(msg, type = 'success') {
  alertMsg.value = msg
  alertType.value = type
  if (type === 'success') setTimeout(() => { alertMsg.value = null }, 5000)
}

function getBadgeByUrgensi(urgensi) {
  return { kritis: 'bg-red-600', tinggi: 'bg-amber-500', sedang: 'bg-blue-600', rendah: 'bg-emerald-600' }[urgensi] ?? 'bg-slate-500'
}

function roleColor(role) {
  return { pemohon: 'bg-[#0757a8]', verifikator: 'bg-purple-600', pimpinan: 'bg-[#047857]', teknisi: 'bg-cyan-600', admin: 'bg-slate-600' }[role] ?? 'bg-slate-400'
}

const rekomendasiOptions = [
  { value: 'lanjut_pimpinan', label: 'Lanjut Pimpinan', icon: 'lucide:arrow-right-circle', iconClass: 'text-emerald-600', activeClass: 'border-emerald-500 bg-emerald-50 text-emerald-800' },
  { value: 'perlu_revisi',   label: 'Minta Revisi',   icon: 'lucide:edit-3',             iconClass: 'text-amber-600',   activeClass: 'border-amber-500 bg-amber-50 text-amber-800' },
  { value: 'tolak',          label: 'Tolak Permohonan', icon: 'lucide:x-circle',         iconClass: 'text-red-600',     activeClass: 'border-red-500 bg-red-50 text-red-800' },
]

const keputusanOptions = [
  { value: 'disetujui', label: 'Setujui', icon: 'lucide:check-circle-2', iconClass: 'text-emerald-600', activeClass: 'border-emerald-500 bg-emerald-50 text-emerald-800' },
  { value: 'ditunda',   label: 'Tunda',   icon: 'lucide:clock',          iconClass: 'text-amber-600',   activeClass: 'border-amber-500 bg-amber-50 text-amber-800' },
  { value: 'ditolak',   label: 'Tolak',   icon: 'lucide:x-circle',        iconClass: 'text-red-600',     activeClass: 'border-red-500 bg-red-50 text-red-800' },
]
</script>

<style scoped>
.flash-fade-enter-active, .flash-fade-leave-active { transition: all 0.3s ease; }
.flash-fade-enter-from, .flash-fade-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
