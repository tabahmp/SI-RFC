<template>
  <AppLayout>
    <div class="space-y-6">

      <!-- BANNER PILIH ROLE / SIMULASI PERAN (INTERAKTIF & CEPAT) -->
      <div class="bg-white rounded-2xl p-2.5 sm:p-3 border border-slate-200/90 shadow-xs flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 pl-2">
          <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
          <span class="text-xs font-bold text-slate-700">Simulasi Peran Aktif:</span>
          <span class="text-xs font-extrabold uppercase px-2.5 py-0.5 rounded-md bg-[#073B70] text-white">
            {{ currentRole }}
          </span>
        </div>

        <div class="flex items-center gap-1.5 flex-wrap">
          <button
            @click="setRole('pemohon')"
            :class="[
              'px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5',
              currentRole === 'pemohon'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            ]"
          >
            <Icon icon="lucide:user" class="w-3.5 h-3.5" />
            <span>01 · Pemohon</span>
          </button>

          <button
            @click="setRole('verifikator')"
            :class="[
              'px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5',
              currentRole === 'verifikator'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            ]"
          >
            <Icon icon="lucide:shield-check" class="w-3.5 h-3.5" />
            <span>02 · Verifikator</span>
          </button>

          <button
            @click="setRole('pimpinan')"
            :class="[
              'px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5',
              currentRole === 'pimpinan'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            ]"
          >
            <Icon icon="lucide:scale" class="w-3.5 h-3.5" />
            <span>03 · Pimpinan</span>
          </button>

          <button
            @click="setRole('teknisi')"
            :class="[
              'px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5',
              currentRole === 'teknisi'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            ]"
          >
            <Icon icon="lucide:code-2" class="w-3.5 h-3.5" />
            <span>04 · Teknisi</span>
          </button>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAMPILAN 1: DASHBOARD PEMOHON (Frame 1:89 · 01 · Dashboard Pemohon) -->
      <!-- =================================================================== -->
      <div v-if="currentRole === 'pemohon'" class="space-y-6">
        <!-- Header Pemohon -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
              Selamat datang kembali,
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
              Raka Pratama
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
              Pantau seluruh perubahan layanan TI Anda dalam satu tempat.
            </p>
          </div>

          <Link
            href="/form-perubahan-it/baru"
            class="inline-flex items-center gap-2 bg-[#0757A8] hover:bg-[#063D78] text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-sm transition-all"
          >
            <Icon icon="lucide:plus" class="w-4 h-4" />
            <span>Ajukan RFC Baru</span>
          </Link>
        </div>

        <!-- 4 Kartu Metrik Pemohon (Figma) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Total Pengajuan -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Pengajuan</span>
              <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center font-bold">
                <Icon icon="lucide:file-text" class="w-4 h-4" />
              </div>
            </div>
            <div class="text-3xl font-black text-[#0F172A]">24</div>
            <div class="text-[11px] text-emerald-600 mt-1 font-semibold flex items-center gap-1">
              <Icon icon="lucide:trending-up" class="w-3.5 h-3.5" />
              <span>+2 dari bulan lalu</span>
            </div>
          </div>

          <!-- Menunggu Verifikasi -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Menunggu Verifikasi</span>
              <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <Icon icon="lucide:clock" class="w-4 h-4" />
              </div>
            </div>
            <div class="text-3xl font-black text-amber-600">5</div>
            <div class="text-[11px] text-slate-500 mt-1 font-medium">Dalam antrean telaah</div>
          </div>

          <!-- Perlu Tindakan -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Perlu Tindakan</span>
              <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                <Icon icon="lucide:alert-circle" class="w-4 h-4" />
              </div>
            </div>
            <div class="text-3xl font-black text-red-600">2</div>
            <div class="text-[11px] text-red-600 mt-1 font-medium">Membutuhkan respons Anda</div>
          </div>

          <!-- Selesai -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Selesai</span>
              <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <Icon icon="lucide:check-circle-2" class="w-4 h-4" />
              </div>
            </div>
            <div class="text-3xl font-black text-emerald-600">17</div>
            <div class="text-[11px] text-slate-500 mt-1 font-medium">Tingkat penyelesaian 71%</div>
          </div>
        </div>

        <!-- Tabel Permohonan Terbaru Pemohon (Persis Figma Frame 1:89) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div>
              <h2 class="text-lg font-bold text-[#0F172A]">Permohonan terbaru</h2>
              <p class="text-xs text-[#64748B] mt-0.5">Pelacakan status dan posisi pemeriksa secara langsung</p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-[#F8FAFC] border-b border-slate-200/80 text-[11px] font-bold text-[#64748B] uppercase tracking-wider">
                  <th class="py-3 px-6">NOMOR TIKET</th>
                  <th class="py-3 px-6">SISTEM & KEBUTUHAN</th>
                  <th class="py-3 px-6">PRIORITAS</th>
                  <th class="py-3 px-6">STATUS</th>
                  <th class="py-3 px-6">POSISI</th>
                  <th class="py-3 px-6">SLA</th>
                  <th class="py-3 px-6 text-right">AKSI</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#F1F5F9] text-xs text-slate-700">
                <!-- Baris 1: RFC-202506-0142 -->
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#0757A8] text-xs">RFC-202506-0142</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5">18 Jun 2025</div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#334155] text-sm">Satu Data Jabar</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5 line-clamp-1">Optimasi sinkronisasi data lintas OPD</div>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center w-10 h-6 rounded-md bg-[#FEE2E2] text-[#B91C1C] text-[11px] font-bold">
                      P1
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[11px] font-bold">
                      Menunggu Verifikasi
                    </span>
                  </td>
                  <td class="py-4 px-6 font-bold text-[#475569]">
                    Verifikator
                  </td>
                  <td class="py-4 px-6 font-mono text-[11px] text-[#B91C1C] font-bold">
                    SLA: 01:42:18
                  </td>
                  <td class="py-4 px-6 text-right">
                    <Link
                      href="/form-perubahan-it/ticket/RFC-202506-0142"
                      class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#0757A8] hover:bg-blue-50 transition-colors"
                      title="Buka detail"
                    >
                      <Icon icon="lucide:chevron-right" class="w-5 h-5" />
                    </Link>
                  </td>
                </tr>

                <!-- Baris 2: RFC-202506-0138 -->
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#0757A8] text-xs">RFC-202506-0138</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5">17 Jun 2025</div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#334155] text-sm">Sistem Perizinan Terpadu</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5 line-clamp-1">Integrasi API tanda tangan elektronik</div>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center w-10 h-6 rounded-md bg-[#FFEDD5] text-[#C2410C] text-[11px] font-bold">
                      P2
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-[#CFFAFE] text-[#0E7490] text-[11px] font-bold">
                      Pengerjaan
                    </span>
                  </td>
                  <td class="py-4 px-6 font-bold text-[#475569]">
                    Tim Integrasi
                  </td>
                  <td class="py-4 px-6 font-mono text-[11px] text-[#64748B]">
                    SLA: 18:24:00
                  </td>
                  <td class="py-4 px-6 text-right">
                    <Link
                      href="/form-perubahan-it/ticket/RFC-202506-0138"
                      class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#0757A8] hover:bg-blue-50 transition-colors"
                      title="Buka detail"
                    >
                      <Icon icon="lucide:chevron-right" class="w-5 h-5" />
                    </Link>
                  </td>
                </tr>

                <!-- Baris 3: RFC-202506-0129 -->
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#0757A8] text-xs">RFC-202506-0129</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5">15 Jun 2025</div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#334155] text-sm">Portal Kepegawaian</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5 line-clamp-1">Penambahan modul riwayat pelatihan</div>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center w-10 h-6 rounded-md bg-[#FEF3C7] text-[#92400E] text-[11px] font-bold">
                      P3
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[10px] font-bold">
                      Perlu Revisi
                    </span>
                  </td>
                  <td class="py-4 px-6 font-bold text-[#475569]">
                    Pemohon
                  </td>
                  <td class="py-4 px-6 font-mono text-[11px] text-[#64748B]">
                    SLA: 2 hari
                  </td>
                  <td class="py-4 px-6 text-right">
                    <Link
                      href="/form-perubahan-it/ticket/RFC-202506-0129"
                      class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#0757A8] hover:bg-blue-50 transition-colors"
                      title="Buka detail"
                    >
                      <Icon icon="lucide:chevron-right" class="w-5 h-5" />
                    </Link>
                  </td>
                </tr>

                <!-- Baris 4: RFC-202506-0117 -->
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#0757A8] text-xs">RFC-202506-0117</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5">12 Jun 2025</div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#334155] text-sm">Dashboard Pimpinan</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5 line-clamp-1">Penyempurnaan visualisasi indikator IKU</div>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center w-10 h-6 rounded-md bg-[#D1FAE5] text-[#047857] text-[11px] font-bold">
                      P4
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-[#D1FAE5] text-[#047857] text-[11px] font-bold">
                      Ditutup
                    </span>
                  </td>
                  <td class="py-4 px-6 font-bold text-[#475569]">
                    Ditutup
                  </td>
                  <td class="py-4 px-6 font-mono text-[11px] text-[#64748B]">
                    SLA: Tepat SLA
                  </td>
                  <td class="py-4 px-6 text-right">
                    <Link
                      href="/form-perubahan-it/ticket/RFC-202506-0117"
                      class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#0757A8] hover:bg-blue-50 transition-colors"
                      title="Buka detail"
                    >
                      <Icon icon="lucide:chevron-right" class="w-5 h-5" />
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Footer Tabel Persis Figma -->
          <div class="p-4 px-6 border-t border-[#F1F5F9] bg-white flex items-center justify-between text-xs text-[#64748B]">
            <span>Menampilkan 4 dari 24 permohonan</span>
            <button class="font-bold text-[#0757A8] hover:underline flex items-center gap-1">
              <span>Lihat semua permohonan</span>
              <Icon icon="lucide:chevron-right" class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAMPILAN 2: DASHBOARD VERIFIKATOR (Frame 1:297 · 02 · Dashboard Verifikator) -->
      <!-- =================================================================== -->
      <div v-else-if="currentRole === 'verifikator'" class="space-y-6">
        <!-- Header Verifikator -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
              Ruang kerja teknis
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
              Antrean Verifikasi
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
              Tiket otomatis diurutkan berdasarkan prioritas dan sisa waktu SLA.
            </p>
          </div>

          <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200 flex items-center gap-1.5">
              <Icon icon="lucide:shield-check" class="w-4 h-4" />
              <span>Verifikator APTIKA</span>
            </span>
          </div>
        </div>

        <!-- 4 Kartu Metrik Prioritas SLA Verifikator (Figma) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- P1 • Kritis -->
          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-red-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-red-600">P1 • Kritis</span>
              <span class="text-xs font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded">≤ 4 jam</span>
            </div>
            <div class="text-3xl font-black text-red-600 mt-2">2</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 4 jam</div>
          </div>

          <!-- P2 • Tinggi -->
          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-amber-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-amber-600">P2 • Tinggi</span>
              <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded">≤ 24 jam</span>
            </div>
            <div class="text-3xl font-black text-amber-600 mt-2">7</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 24 jam</div>
          </div>

          <!-- P3 • Sedang -->
          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-blue-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-blue-600">P3 • Sedang</span>
              <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">≤ 3 hari</span>
            </div>
            <div class="text-3xl font-black text-blue-600 mt-2">12</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 3 hari</div>
          </div>

          <!-- P4 • Rendah -->
          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-emerald-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-emerald-600">P4 • Rendah</span>
              <span class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">≤ 5 hari</span>
            </div>
            <div class="text-3xl font-black text-emerald-600 mt-2">8</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 5 hari</div>
          </div>
        </div>

        <!-- Konten 2 Kolom: Smart Priority Queue & SLA Alert (Figma) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Smart Priority Queue (2 Kolom Lebar) -->
          <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-[#0F172A]">Smart priority queue</h2>
                <p class="text-xs text-slate-500">29 tiket perlu ditelaah</p>
              </div>
              <button class="text-xs font-bold text-[#0757A8] px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 flex items-center gap-1.5 transition-colors">
                <Icon icon="lucide:arrow-up-down" class="w-3.5 h-3.5" />
                <span>Urutkan</span>
              </button>
            </div>

            <!-- List Tiket Queue -->
            <div class="space-y-3">
              <!-- Item 01 (P1 - Kritis) -->
              <div class="p-4 rounded-xl border border-red-200 bg-red-50/30 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                  <div class="font-mono font-bold text-red-600 text-sm">01</div>
                  <span class="px-2 py-0.5 rounded text-xs font-extrabold bg-red-600 text-white">P1</span>
                  <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Optimasi sinkronisasi data lintas OPD</h3>
                    <div class="text-xs text-slate-500 mt-0.5">RFC-202506-0142 • Dinas Komunikasi dan Informatika</div>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <div class="text-right">
                    <div class="text-xs font-mono font-bold text-red-600">01:42:18</div>
                    <div class="text-[10px] text-slate-400">Sisa SLA</div>
                  </div>
                  <Link
                    href="/form-perubahan-it/pengerjaan/RFC-202506-0142"
                    class="px-3.5 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs"
                  >
                    Telaah
                  </Link>
                </div>
              </div>

              <!-- Item 02 (P2 - Tinggi) -->
              <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/30 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                  <div class="font-mono font-bold text-amber-600 text-sm">02</div>
                  <span class="px-2 py-0.5 rounded text-xs font-extrabold bg-amber-500 text-white">P2</span>
                  <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Integrasi API tanda tangan elektronik</h3>
                    <div class="text-xs text-slate-500 mt-0.5">RFC-202506-0138 • DPMPTSP</div>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <div class="text-right">
                    <div class="text-xs font-mono font-bold text-amber-600">18:24:00</div>
                    <div class="text-[10px] text-slate-400">Sisa SLA</div>
                  </div>
                  <Link
                    href="/form-perubahan-it/pengerjaan/RFC-202506-0138"
                    class="px-3.5 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs"
                  >
                    Telaah
                  </Link>
                </div>
              </div>

              <!-- Item 03 (P3 - Sedang) -->
              <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                  <div class="font-mono font-bold text-slate-400 text-sm">03</div>
                  <span class="px-2 py-0.5 rounded text-xs font-extrabold bg-blue-600 text-white">P3</span>
                  <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Penambahan modul riwayat pelatihan</h3>
                    <div class="text-xs text-slate-500 mt-0.5">RFC-202506-0129 • BKD</div>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <div class="text-right">
                    <div class="text-xs font-mono font-bold text-slate-600">2 hari</div>
                    <div class="text-[10px] text-slate-400">Sisa SLA</div>
                  </div>
                  <Link
                    href="/form-perubahan-it/pengerjaan/RFC-202506-0129"
                    class="px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all"
                  >
                    Telaah
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <!-- SLA ALERT Widget (1 Kolom Kanan) -->
          <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-4 flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-2 text-red-600 font-extrabold text-xs tracking-wider uppercase mb-1">
                <Icon icon="lucide:alert-triangle" class="w-4 h-4" />
                <span>SLA ALERT</span>
              </div>
              <h3 class="text-base font-extrabold text-[#0F172A]">2 tiket mendekati batas</h3>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Prioritaskan tiket P1 agar kepatuhan target tanggap tetap terjaga sesuai standar ISO 20000 & SMKI.
              </p>

              <!-- Box Terdekat -->
              <div class="mt-4 p-4 rounded-xl bg-red-50 border border-red-200 space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="text-slate-500 font-medium">Terdekat</span>
                  <span class="font-mono font-bold text-red-700">RFC-202506-0142</span>
                </div>
                <div class="text-xl font-mono font-black text-red-600">01:42:18 tersisa</div>
                <div class="text-[11px] text-red-700">Satu Data Jabar — Optimasi sinkronisasi lintas OPD</div>
              </div>
            </div>

            <Link
              href="/form-perubahan-it/pengerjaan/RFC-202506-0142"
              class="w-full h-11 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all"
            >
              <span>Verifikasi Tiket Kritis Sekarang</span>
              <Icon icon="lucide:arrow-right" class="w-4 h-4" />
            </Link>
          </div>

        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAMPILAN 3: DASHBOARD PIMPINAN (Frame 1:474 · 03 · Dashboard Pimpinan) -->
      <!-- =================================================================== -->
      <div v-else-if="currentRole === 'pimpinan'" class="space-y-6">
        <!-- Header Pimpinan -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
              Ringkasan eksekutif
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
              Papan Keputusan
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
              Keputusan terarah berdasarkan telaah teknis dan dampak layanan.
            </p>
          </div>

          <div class="flex items-center gap-2">
            <Link
              href="/form-perubahan-it/dokumen/RFC-202506-0138"
              class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-colors"
            >
              <Icon icon="lucide:file-down" class="w-4 h-4 text-red-500" />
              <span>Ekspor PDF</span>
            </Link>
            <Link
              href="/form-perubahan-it/dokumen/RFC-202506-0138"
              class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-colors"
            >
              <Icon icon="lucide:sheet" class="w-4 h-4 text-emerald-600" />
              <span>Excel</span>
            </Link>
          </div>
        </div>

        <!-- 4 Kartu Metrik Pimpinan (Figma) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-amber-600 uppercase tracking-wide">Menunggu Persetujuan</div>
            <div class="text-3xl font-black text-amber-600 mt-2">8</div>
            <div class="text-[11px] text-slate-500 mt-1">Memerlukan keputusan pimpinan</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Disetujui Bulan Ini</div>
            <div class="text-3xl font-black text-emerald-600 mt-2">32</div>
            <div class="text-[11px] text-emerald-600 mt-1 font-semibold">+18% dari bulan lalu</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Total Ditolak</div>
            <div class="text-3xl font-black text-red-600 mt-2">4</div>
            <div class="text-[11px] text-slate-500 mt-1">Dengan alasan kebijakan</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-[#0757A8] uppercase tracking-wide">Rata-rata Keputusan</div>
            <div class="text-3xl font-black text-[#0757A8] mt-2">1j 24m</div>
            <div class="text-[11px] text-emerald-600 mt-1 font-semibold">Lebih cepat 14 menit</div>
          </div>
        </div>

        <!-- Papan Keputusan Eksekutif (Card Focus RFC-202506-0138 Persis Figma) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
          <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="space-y-1">
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded text-xs font-extrabold bg-amber-500 text-white">P2 • TINGGI</span>
                <span class="font-mono text-xs font-bold text-slate-500">RFC-202506-0138</span>
              </div>
              <h2 class="text-lg font-extrabold text-[#0F172A]">Integrasi API tanda tangan elektronik</h2>
              <p class="text-xs text-slate-500">
                Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu • Sistem Perizinan Terpadu
              </p>
            </div>
          </div>

          <!-- Grid Dampak, Urgensi, SLA -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
              <div class="text-[11px] text-slate-500 font-medium">Dampak</div>
              <div class="text-sm font-bold text-[#0F172A] mt-0.5">Tinggi — layanan publik</div>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
              <div class="text-[11px] text-slate-500 font-medium">Urgensi</div>
              <div class="text-sm font-bold text-[#0F172A] mt-0.5">Sedang</div>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
              <div class="text-[11px] text-slate-500 font-medium">SLA</div>
              <div class="text-sm font-bold text-amber-600 mt-0.5">18 jam tersisa</div>
            </div>
          </div>

          <!-- Rekomendasi Verifikator -->
          <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200 space-y-1">
            <div class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
              <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600" />
              <span>Rekomendasi verifikator: Layak dilanjutkan</span>
            </div>
            <p class="text-xs text-emerald-900/90 leading-relaxed">
              Arsitektur integrasi memenuhi standar keamanan. Tim meminta implementasi dilakukan di staging dan dilengkapi skenario rollback.
            </p>
          </div>

          <!-- Form Keputusan & Disposisi -->
          <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-4">
            <div class="text-xs font-bold text-slate-700 uppercase tracking-wide">Tentukan keputusan</div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tim pelaksana</label>
                <input
                  type="text"
                  value="Tim Integrasi & API"
                  readonly
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-medium"
                />
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Target selesai</label>
                <input
                  type="text"
                  value="27 Juni 2025"
                  readonly
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-medium"
                />
              </div>
            </div>

            <!-- 3 Opsi Tombol Keputusan -->
            <div class="flex items-center gap-3 pt-2">
              <button
                @click="pimpinanChoice = 'tolak'"
                :class="[
                  'px-4 py-2 rounded-lg text-xs font-bold transition-all',
                  pimpinanChoice === 'tolak' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 border border-red-200'
                ]"
              >
                Tolak
              </button>
              <button
                @click="pimpinanChoice = 'tunda'"
                :class="[
                  'px-4 py-2 rounded-lg text-xs font-bold transition-all',
                  pimpinanChoice === 'tunda' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 border border-amber-200'
                ]"
              >
                Tunda
              </button>
              <button
                @click="pimpinanChoice = 'setujui'"
                :class="[
                  'px-5 py-2 rounded-lg text-xs font-bold transition-all',
                  pimpinanChoice === 'setujui' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                ]"
              >
                Setujui
              </button>

              <button
                @click="confirmKeputusan"
                class="ml-auto px-5 py-2 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs"
              >
                Konfirmasi keputusan
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAMPILAN 4: DASHBOARD TEKNISI (Frame 1:658 · 04 · Dashboard Teknisi) -->
      <!-- =================================================================== -->
      <div v-else-if="currentRole === 'teknisi'" class="space-y-6">
        <!-- Header Teknisi -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
              Ruang kerja implementasi
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
              Tugas Aktif Saya
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
              Dokumentasikan implementasi dan pastikan setiap perubahan dapat diaudit.
            </p>
          </div>

          <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-lg bg-cyan-50 text-cyan-800 text-xs font-bold border border-cyan-200 flex items-center gap-1.5">
              <Icon icon="lucide:terminal" class="w-4 h-4" />
              <span>Tim Pengembang & Integrasi</span>
            </span>
          </div>
        </div>

        <!-- 4 Kartu Metrik Teknisi (Figma) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tugas Aktif</div>
            <div class="text-3xl font-black text-[#0F172A] mt-2">6</div>
            <div class="text-[11px] text-amber-600 mt-1 font-semibold">2 prioritas tinggi</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-blue-600 uppercase tracking-wide">Dalam Pengerjaan</div>
            <div class="text-3xl font-black text-blue-600 mt-2">3</div>
            <div class="text-[11px] text-slate-500 mt-1">Dikerjakan minggu ini</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Selesai Bulan Ini</div>
            <div class="text-3xl font-black text-emerald-600 mt-2">14</div>
            <div class="text-[11px] text-slate-500 mt-1">Dengan dokumen BAST</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-purple-600 uppercase tracking-wide">BAST Siap Validasi</div>
            <div class="text-3xl font-black text-purple-600 mt-2">1</div>
            <div class="text-[11px] text-slate-500 mt-1">Menunggu tanda tangan</div>
          </div>
        </div>

        <!-- Lembar Tugas Aktif Teknisi (RFC-202506-0138 Persis Figma) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
          <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-xs font-extrabold bg-amber-500 text-white">P2</span>
                <span class="font-mono text-xs font-bold text-[#0757A8]">RFC-202506-0138</span>
              </div>
              <h2 class="text-lg font-extrabold text-[#0F172A]">Integrasi API tanda tangan elektronik</h2>
              <div class="text-xs text-slate-500 mt-0.5">Target selesai: 27 Juni 2025</div>
            </div>

            <Link
              href="/form-perubahan-it/pengerjaan/RFC-202506-0138?tab=implementasi"
              class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors"
            >
              Mulai Pengerjaan
            </Link>
          </div>

          <!-- Metadata Teknis Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
              <div class="text-[11px] text-slate-500 font-medium">Lingkungan</div>
              <div class="text-sm font-bold text-[#0F172A] mt-0.5">Staging</div>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
              <div class="text-[11px] text-slate-500 font-medium">Tim</div>
              <div class="text-sm font-bold text-[#0F172A] mt-0.5">Integrasi & API</div>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
              <div class="text-[11px] text-slate-500 font-medium">Reviewer</div>
              <div class="text-sm font-bold text-[#0F172A] mt-0.5">Nadia A.</div>
            </div>
          </div>

          <!-- Box Catatan Implementasi & Catatan Teknis -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/80 space-y-1">
              <div class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                <Icon icon="lucide:info" class="w-4 h-4 text-amber-700" />
                <span>Catatan implementasi</span>
              </div>
              <p class="text-xs text-amber-900/90 leading-relaxed">
                Gunakan endpoint sandbox BSrE, validasi webhook, dan siapkan rollback ke versi 2.4.1.
              </p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
              <div class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                <Icon icon="lucide:file-check" class="w-4 h-4 text-emerald-600" />
                <span>Catatan teknis</span>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed">
                Tiket ditutup setelah dokumen BAST sah dan ditandatangani para pihak terkait.
              </p>
            </div>
          </div>

          <!-- Form Input Bukti Teknis -->
          <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tautan changelog / commit</label>
              <input
                type="text"
                placeholder="https://gitlab.jabarprov.go.id/.../commit/..."
                class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-mono focus:outline-none focus:ring-1 focus:ring-[#0757A8]"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Ringkasan implementasi</label>
              <textarea
                rows="2"
                placeholder="Jelaskan perubahan yang telah diterapkan..."
                class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#0757A8]"
              ></textarea>
            </div>

            <div class="flex items-center gap-3 pt-1">
              <button class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold flex items-center gap-1.5 transition-colors">
                <Icon icon="lucide:upload" class="w-4 h-4 text-blue-600" />
                <span>Unggah hasil uji</span>
              </button>
              
              <Link
                href="/form-perubahan-it/pengerjaan/RFC-202506-0138"
                class="ml-auto px-5 py-2 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5"
              >
                <span>Simpan & lanjutkan uji</span>
                <Icon icon="lucide:arrow-right" class="w-4 h-4" />
              </Link>
            </div>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const currentRole = ref('pemohon')
const filterStatus = ref('Semua')
const pimpinanChoice = ref('setujui')

function setRole(role) {
  currentRole.value = role
  if (typeof window !== 'undefined') {
    localStorage.setItem('si_rfc_role', role)
    const url = new URL(window.location.href)
    url.searchParams.set('role', role)
    window.history.replaceState({}, '', url.toString())
  }
}

function confirmKeputusan() {
  router.visit(`/form-perubahan-it/pengerjaan/RFC-202506-0138?tab=pimpinan`)
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search)
    const roleParam = params.get('role')
    const savedRole = localStorage.getItem('si_rfc_role')
    if (roleParam && ['pemohon', 'verifikator', 'pimpinan', 'teknisi'].includes(roleParam)) {
      currentRole.value = roleParam
    } else if (savedRole && ['pemohon', 'verifikator', 'pimpinan', 'teknisi'].includes(savedRole)) {
      currentRole.value = savedRole
    }

    window.addEventListener('role-changed', (e) => {
      if (e.detail) currentRole.value = e.detail
    })
  }
})

// Data Tiket Pemohon Sesuai Figma Frame 1:89
const pemohonTikets = [
  {
    no_rfc: 'RFC-202506-0142',
    tanggal: '18 Jun 2025',
    sistem: 'Satu Data Jabar',
    kebutuhan: 'Optimasi sinkronisasi data lintas OPD',
    prioritas: 'P1',
    status: 'Menunggu Verifikasi',
    posisi: 'Verifikator',
    sla: '01:42:18'
  },
  {
    no_rfc: 'RFC-202506-0138',
    tanggal: '17 Jun 2025',
    sistem: 'Sistem Perizinan Terpadu',
    kebutuhan: 'Integrasi API tanda tangan elektronik',
    prioritas: 'P2',
    status: 'Pengerjaan',
    posisi: 'Tim Integrasi',
    sla: '18:24:00'
  },
  {
    no_rfc: 'RFC-202506-0129',
    tanggal: '15 Jun 2025',
    sistem: 'Portal Kepegawaian',
    kebutuhan: 'Penambahan modul riwayat pelatihan',
    prioritas: 'P3',
    status: 'Perlu Revisi',
    posisi: 'Pemohon',
    sla: '2 hari'
  },
  {
    no_rfc: 'RFC-202506-0117',
    tanggal: '12 Jun 2025',
    sistem: 'Dashboard Pimpinan',
    kebutuhan: 'Penyempurnaan visualisasi indikator IKU',
    prioritas: 'P4',
    status: 'Ditutup',
    posisi: 'Selesai BAST',
    sla: 'Tepat SLA'
  }
]

const filteredPemohonTikets = computed(() => {
  if (filterStatus.value === 'Semua') return pemohonTikets
  return pemohonTikets.filter(t => t.status === filterStatus.value)
})

function getPrioritasBadge(p) {
  if (p === 'P1') return 'bg-red-100 text-red-700'
  if (p === 'P2') return 'bg-amber-100 text-amber-700'
  if (p === 'P3') return 'bg-blue-100 text-blue-700'
  return 'bg-emerald-100 text-emerald-700'
}

function getStatusBadge(s) {
  if (s === 'Menunggu Verifikasi') return 'bg-amber-50 text-amber-800 border border-amber-200'
  if (s === 'Pengerjaan') return 'bg-blue-50 text-blue-800 border border-blue-200'
  if (s === 'Perlu Revisi') return 'bg-red-50 text-red-800 border border-red-200'
  return 'bg-emerald-50 text-emerald-800 border border-emerald-200'
}

function getStatusDot(s) {
  if (s === 'Menunggu Verifikasi') return 'bg-amber-500'
  if (s === 'Pengerjaan') return 'bg-blue-500'
  if (s === 'Perlu Revisi') return 'bg-red-500'
  return 'bg-emerald-500'
}
</script>
