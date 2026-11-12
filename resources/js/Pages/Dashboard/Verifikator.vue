<template>
  <AppLayout role="verifikator">
    <div class="max-w-[1440px] mx-auto space-y-6">
      
      <!-- SUB-NAVIGASI DASHBOARD VERIFIKATOR -->
      <div class="flex flex-wrap items-center justify-between border-b border-slate-200 pb-3 gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <button
            @click="activeTab = 'ringkasan'"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2',
              activeTab === 'ringkasan' ? 'bg-[#0757A8] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
            ]"
          >
            <Icon icon="lucide:layout-grid" class="w-4 h-4" />
            <span>Ringkasan & Prioritas</span>
          </button>
          <button
            @click="activeTab = 'antrean'"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2',
              activeTab === 'antrean' ? 'bg-[#0757A8] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
            ]"
          >
            <Icon icon="lucide:shield-check" class="w-4 h-4" />
            <span>Antrean Telaah</span>
            <span :class="activeTab === 'antrean' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'" class="px-2 py-0.5 rounded-md text-[10px] font-extrabold">29</span>
          </button>
          <button
            @click="activeTab = 'sla'"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2',
              activeTab === 'sla' ? 'bg-[#0757A8] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
            ]"
          >
            <Icon icon="lucide:clock" class="w-4 h-4" />
            <span>Pemantauan SLA</span>
          </button>
          <button
            @click="activeTab = 'verifikasi_pemohon'"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2',
              activeTab === 'verifikasi_pemohon' ? 'bg-[#0757A8] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
            ]"
          >
            <Icon icon="lucide:user-check" class="w-4 h-4" />
            <span>Verifikasi Akun Pemohon</span>
            <span v-if="pendingPemohonCount > 0" class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-400 text-[#073B70]">
              {{ pendingPemohonCount }}
            </span>
          </button>
        </div>

        <span class="px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200 flex items-center gap-1.5">
          <Icon icon="lucide:shield-check" class="w-4 h-4" />
          <span>Verifikator APTIKA</span>
        </span>
      </div>

      <!-- 1. SUB-VIEW: RINGKASAN & PRIORITAS (PERSIS FIGMA 1:297) -->
      <div v-if="activeTab === 'ringkasan'" class="space-y-6">
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

        <!-- 4 Kartu Metrik Prioritas SLA Verifikator -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-red-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-red-600">P1 • Kritis</span>
              <span class="text-xs font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded">≤ 4 jam</span>
            </div>
            <div class="text-3xl font-black text-red-600 mt-2">2</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 4 jam</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-amber-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-amber-600">P2 • Tinggi</span>
              <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded">≤ 24 jam</span>
            </div>
            <div class="text-3xl font-black text-amber-600 mt-2">7</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 24 jam</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-blue-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-blue-600">P3 • Sedang</span>
              <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">≤ 3 hari</span>
            </div>
            <div class="text-3xl font-black text-blue-600 mt-2">12</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 3 hari</div>
          </div>

          <div class="bg-white rounded-2xl p-5 border-l-4 border-l-emerald-500 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-bold text-emerald-600">P4 • Rendah</span>
              <span class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">≤ 5 hari</span>
            </div>
            <div class="text-3xl font-black text-emerald-600 mt-2">8</div>
            <div class="text-[11px] text-slate-500 mt-1">Target respons ≤ 5 hari</div>
          </div>
        </div>

        <!-- Konten 2 Kolom: Smart Priority Queue & SLA Alert -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-[#0F172A]">Smart priority queue</h2>
                <p class="text-xs text-slate-500">29 tiket perlu ditelaah</p>
              </div>
              <button
                @click="activeTab = 'antrean'"
                class="text-xs font-bold text-[#0757A8] px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 flex items-center gap-1.5 transition-colors"
              >
                <Icon icon="lucide:list-filter" class="w-3.5 h-3.5" />
                <span>Lihat Seluruh Antrean (29)</span>
              </button>
            </div>

            <!-- List Tiket Queue -->
            <div class="space-y-3">
              <div class="p-4 rounded-xl border border-red-200 bg-red-50/30 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                  <div class="font-mono font-bold text-red-600 text-sm">01</div>
                  <span class="px-2 py-0.5 rounded text-xs font-extrabold bg-red-600 text-white">P1</span>
                  <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Optimasi sinkronisasi data lintas OPD</h3>
                    <div class="text-xs text-slate-500 mt-0.5">RFC-202506-0142 • Diskominfo</div>
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
                Prioritaskan tiket P1 agar kepatuhan target tanggap tetap terjaga.
              </p>

              <div class="mt-4 p-4 rounded-xl bg-red-50 border border-red-200 space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="text-slate-500 font-medium">Terdekat</span>
                  <span class="font-mono font-bold text-red-700">RFC-202506-0142</span>
                </div>
                <div class="text-xl font-mono font-black text-red-600">01:42:18 tersisa</div>
                <div class="text-[11px] text-red-700">Satu Data Jabar — Optimasi sinkronisasi data lintas OPD</div>
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

      <!-- 2. SUB-VIEW: ANTREAN TELAAH (PERSIS FIGMA 13:235) -->
      <div v-else-if="activeTab === 'antrean'" class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
              Verifikator / Antrean telaah
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
              Antrean telaah
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
              Tinjau kelengkapan dan risiko perubahan berdasarkan prioritas serta sisa waktu SLA.
            </p>
          </div>

          <div class="flex items-center gap-2">
            <button class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-colors">
              <Icon icon="lucide:download" class="w-4 h-4 text-[#0757A8]" />
              <span>Ekspor antrean</span>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Perlu ditelaah</div>
            <div class="text-3xl font-black text-[#0F172A] mt-1.5">29</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Seluruh tiket dalam antrean</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-red-600 uppercase tracking-wide">P1 · Kritis</div>
            <div class="text-3xl font-black text-red-600 mt-1.5">2</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Respons paling lambat 4 jam</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-amber-600 uppercase tracking-wide">Mendekati batas SLA</div>
            <div class="text-3xl font-black text-amber-600 mt-1.5">2</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Dahulukan penelaahan tiket</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">P4 · Rendah</div>
            <div class="text-3xl font-black text-emerald-600 mt-1.5">8</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Respons paling lambat 5 hari kerja</div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4">TIKET / PEMOHON</th>
                  <th class="py-3 px-4">KEBUTUHAN PERUBAHAN</th>
                  <th class="py-3 px-4">PRIORITAS</th>
                  <th class="py-3 px-4">SISA SLA</th>
                  <th class="py-3 px-4 text-right">TINDAKAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-[#0757A8] font-mono">RFC-202506-0142</div>
                    <div class="text-[11px] text-slate-500">Diskominfo · 18 Jun 2025</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="font-semibold text-slate-800">Satu Data Jabar</div>
                    <div class="text-[11px] text-slate-500">Optimasi sinkronisasi data lintas OPD</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700">P1 · Kritis</span>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="font-mono font-bold text-red-600">01:42:18</span>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <Link
                      href="/form-perubahan-it/pengerjaan/RFC-202506-0142"
                      class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs"
                    >
                      <span>Mulai telaah</span>
                      <Icon icon="lucide:arrow-right" class="w-3.5 h-3.5" />
                    </Link>
                  </td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-[#0757A8] font-mono">RFC-202506-0141</div>
                    <div class="text-[11px] text-slate-500">Diskominfo · 18 Jun 2025</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="font-semibold text-slate-800">Layanan pusat data</div>
                    <div class="text-[11px] text-slate-500">Pemulihan replikasi basis data</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700">P1 · Kritis</span>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="font-mono font-bold text-red-600">03:10:00</span>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <Link
                      href="/form-perubahan-it/pengerjaan/RFC-202506-0142"
                      class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs"
                    >
                      <span>Mulai telaah</span>
                      <Icon icon="lucide:arrow-right" class="w-3.5 h-3.5" />
                    </Link>
                  </td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-[#0757A8] font-mono">RFC-202506-0140</div>
                    <div class="text-[11px] text-slate-500">DPMPTSP · 17 Jun 2025</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="font-semibold text-slate-800">Portal pelayanan publik</div>
                    <div class="text-[11px] text-slate-500">Penyesuaian alur validasi berkas</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-700">P2 · Tinggi</span>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="font-mono font-bold text-amber-600">20:15:00</span>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <Link
                      href="/form-perubahan-it/pengerjaan/RFC-202506-0138"
                      class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs"
                    >
                      <span>Mulai telaah</span>
                      <Icon icon="lucide:arrow-right" class="w-3.5 h-3.5" />
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- 3. SUB-VIEW: PEMANTAUAN SLA (PERSIS FIGMA 1:5319) -->
      <div v-else-if="activeTab === 'sla'" class="space-y-6">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
            Verifikator / Kinerja layanan
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
            Pemantauan SLA
          </h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
            Pantau batas waktu tanggap verifikasi berdasarkan prioritas resmi P1–P4.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Antrean verifikasi</div>
            <div class="text-3xl font-black text-[#0F172A] mt-1.5">29</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Semua prioritas</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-amber-600 uppercase tracking-wide">Mendekati batas</div>
            <div class="text-3xl font-black text-amber-600 mt-1.5">2</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Segera lakukan telaah</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Melewati SLA</div>
            <div class="text-3xl font-black text-red-600 mt-1.5">1</div>
            <div class="text-[11px] text-red-600 font-bold mt-0.5">Memerlukan eskalasi</div>
          </div>
          <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
            <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Prioritas P1</div>
            <div class="text-3xl font-black text-red-600 mt-1.5">2</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Target tanggap 2–4 jam</div>
          </div>
        </div>

        <!-- Tabel SLA -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4">NOMOR RFC</th>
                  <th class="py-3 px-4">PERUBAHAN / OPD</th>
                  <th class="py-3 px-4">PRIORITAS</th>
                  <th class="py-3 px-4">SISA TANGGAP</th>
                  <th class="py-3 px-4 text-right">TINDAKAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr class="bg-red-50/50 hover:bg-red-50">
                  <td class="py-3.5 px-4 font-mono font-bold text-red-700">RFC-202506-0140</td>
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-slate-800">Pemulihan akses layanan publik</div>
                    <div class="text-[11px] text-slate-500">Diskominfo</div>
                  </td>
                  <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded text-[11px] font-bold bg-red-600 text-white">P1 • Kritis</span></td>
                  <td class="py-3.5 px-4 font-mono font-extrabold text-red-600">Lewat 00:18:00</td>
                  <td class="py-3.5 px-4 text-right">
                    <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white font-bold text-xs hover:bg-red-700">Eskalasi</button>
                  </td>
                </tr>
                <tr class="hover:bg-slate-50">
                  <td class="py-3.5 px-4 font-mono font-bold text-[#0757A8]">RFC-202506-0142</td>
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-slate-800">Optimasi sinkronisasi data lintas OPD</div>
                    <div class="text-[11px] text-slate-500">Diskominfo</div>
                  </td>
                  <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700">P1 • Kritis</span></td>
                  <td class="py-3.5 px-4 font-mono font-bold text-red-600">01:42:18</td>
                  <td class="py-3.5 px-4 text-right">
                    <Link href="/form-perubahan-it/pengerjaan/RFC-202506-0142" class="px-3 py-1.5 rounded-lg bg-[#0757A8] text-white font-bold text-xs hover:bg-[#063D78]">Verifikasi</Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- 4. SUB-VIEW: VERIFIKASI AKUN PEMOHON (FITUR BARU) -->
      <div v-else-if="activeTab === 'verifikasi_pemohon'" class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
              Verifikator / Manajemen Akun Pemohon
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
              Verifikasi Akun Pemohon OPD
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
              Periksa keabsahan identitas aparatur dan instansi pemohon sebelum memberikan akses pengajuan RFC.
            </p>
          </div>
          
          <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-800 text-xs font-bold border border-amber-200">
              {{ pendingPemohonCount }} Akun Menunggu Verifikasi
            </span>
          </div>
        </div>

        <!-- Alert Berhasil -->
        <div v-if="userActionMessage" class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600" />
            <span>{{ userActionMessage }}</span>
          </div>
          <button @click="userActionMessage = null" class="text-emerald-500 hover:text-emerald-700 font-bold">✕</button>
        </div>

        <!-- Tabel Pemohon yang Mendaftar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                  <th class="py-3.5 px-4">NAMA PEMOHON / NIP</th>
                  <th class="py-3.5 px-4">INSTANSI / OPD</th>
                  <th class="py-3.5 px-4">KONTAK & EMAIL</th>
                  <th class="py-3.5 px-4">TGL DAFTAR</th>
                  <th class="py-3.5 px-4">STATUS REGISTRASI</th>
                  <th class="py-3.5 px-4 text-right">AKSI VERIFIKASI</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="user in pemohonList" :key="user.id" class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-slate-800 text-sm">{{ user.name }}</div>
                    <div class="text-[11px] text-slate-500 font-mono">NIP: {{ user.nip || '-' }}</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="font-medium text-slate-700 max-w-xs">{{ user.asal_opd || 'Dinas Komunikasi dan Informatika Provinsi Jawa Barat' }}</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="font-medium text-[#0757A8]">{{ user.email }}</div>
                    <div class="text-[11px] text-slate-500">{{ user.nomor_kontak || '-' }}</div>
                  </td>
                  <td class="py-3.5 px-4 text-slate-500">
                    {{ user.created_at || 'Hari ini' }}
                  </td>
                  <td class="py-3.5 px-4">
                    <span
                      :class="[
                        'px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide inline-flex items-center gap-1.5',
                        user.status_registrasi === 'terverifikasi' ? 'bg-emerald-100 text-emerald-800' :
                        user.status_registrasi === 'ditolak' ? 'bg-red-100 text-red-800' :
                        'bg-amber-100 text-amber-800'
                      ]"
                    >
                      <span class="w-1.5 h-1.5 rounded-full" :class="user.status_registrasi === 'terverifikasi' ? 'bg-emerald-600' : user.status_registrasi === 'ditolak' ? 'bg-red-600' : 'bg-amber-500'"></span>
                      <span>{{ user.status_registrasi === 'terverifikasi' ? 'Terverifikasi' : user.status_registrasi === 'ditolak' ? 'Ditolak' : 'Menunggu Verifikasi' }}</span>
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <div v-if="user.status_registrasi === 'menunggu_verifikasi'" class="inline-flex items-center gap-1.5">
                      <button
                        @click="openVerifyModal(user, 'terverifikasi')"
                        class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1 transition-all shadow-xs"
                      >
                        <Icon icon="lucide:check" class="w-3.5 h-3.5" />
                        <span>Setujui</span>
                      </button>
                      <button
                        @click="openVerifyModal(user, 'ditolak')"
                        class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs flex items-center gap-1 border border-rose-200 transition-all"
                      >
                        <Icon icon="lucide:x" class="w-3.5 h-3.5" />
                        <span>Tolak</span>
                      </button>
                    </div>
                    <div v-else class="text-xs text-slate-400 italic">
                      {{ user.diverifikasi_oleh ? `Oleh: ${user.diverifikasi_oleh}` : 'Sudah diproses' }}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- MODAL VERIFIKASI AKUN PEMOHON -->
      <div v-if="selectedUserForVerification" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 p-6 space-y-4">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
              <Icon :icon="verifyStatusAction === 'terverifikasi' ? 'lucide:user-check' : 'lucide:user-x'" :class="verifyStatusAction === 'terverifikasi' ? 'text-emerald-600' : 'text-red-600'" class="w-5 h-5" />
              <span>{{ verifyStatusAction === 'terverifikasi' ? 'Setujui Registrasi Pemohon' : 'Tolak Registrasi Pemohon' }}</span>
            </h3>
            <button @click="selectedUserForVerification = null" class="text-slate-400 hover:text-slate-600">✕</button>
          </div>

          <div class="p-3 bg-slate-50 rounded-xl space-y-1 text-xs">
            <div class="font-bold text-slate-800">{{ selectedUserForVerification.name }}</div>
            <div class="text-slate-500 font-mono">NIP: {{ selectedUserForVerification.nip }}</div>
            <div class="text-slate-600">{{ selectedUserForVerification.email }} • {{ selectedUserForVerification.asal_opd }}</div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              Catatan Verifikator <span v-if="verifyStatusAction === 'ditolak'" class="text-red-500">*</span>
            </label>
            <textarea
              v-model="verificationNotes"
              rows="3"
              :placeholder="verifyStatusAction === 'terverifikasi' ? 'Catatan persetujuan (opsional)...' : 'Alasan penolakan registrasi akun (wajib)...'"
              class="w-full p-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8]"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button
              @click="selectedUserForVerification = null"
              class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50"
            >
              Batal
            </button>
            <button
              @click="submitUserVerification"
              :disabled="isVerifyingUser || (verifyStatusAction === 'ditolak' && !verificationNotes.trim())"
              :class="verifyStatusAction === 'terverifikasi' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'"
              class="px-4 py-2 rounded-xl text-white text-xs font-bold flex items-center gap-1.5 shadow-sm disabled:opacity-50"
            >
              <Icon v-if="isVerifyingUser" icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
              <span>{{ verifyStatusAction === 'terverifikasi' ? 'Konfirmasi Setujui' : 'Konfirmasi Tolak' }}</span>
            </button>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  tikets: {
    type: Array,
    default: () => []
  },
  usersPemohon: {
    type: Array,
    default: () => []
  }
})

const page = usePage()
const activeTab = ref('ringkasan')
const pemohonList = ref([...(props.usersPemohon || [])])
const selectedUserForVerification = ref(null)
const verifyStatusAction = ref('terverifikasi')
const verificationNotes = ref('')
const isVerifyingUser = ref(false)
const userActionMessage = ref(null)

const pendingPemohonCount = computed(() => {
  return pemohonList.value.filter(u => u.status_registrasi === 'menunggu_verifikasi').length
})

function syncTabFromUrl() {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search)
    const tabParam = params.get('view') || params.get('tab')
    if (tabParam && ['ringkasan', 'antrean', 'sla', 'verifikasi_pemohon'].includes(tabParam)) {
      activeTab.value = tabParam
    }
  }
}

watch(() => page.url, () => {
  syncTabFromUrl()
})

onMounted(() => {
  syncTabFromUrl()
})

function openVerifyModal(user, status) {
  selectedUserForVerification.value = user
  verifyStatusAction.value = status
  verificationNotes.value = ''
}

async function submitUserVerification() {
  if (!selectedUserForVerification.value) return
  isVerifyingUser.value = true
  const user = selectedUserForVerification.value
  const targetStatus = verifyStatusAction.value

  try {
    const res = await axios.post(`/api/users/${user.id}/verifikasi-registrasi`, {
      status: targetStatus,
      catatan: verificationNotes.value,
      diverifikasi_oleh: 'Raka Pratama'
    })

    const idx = pemohonList.value.findIndex(u => u.id === user.id)
    if (idx !== -1) {
      pemohonList.value[idx].status_registrasi = targetStatus
      pemohonList.value[idx].diverifikasi_oleh = 'Raka Pratama'
      pemohonList.value[idx].catatan_verifikasi = verificationNotes.value
    }
    userActionMessage.value = `Akun pemohon ${user.name} berhasil di-${targetStatus === 'terverifikasi' ? 'setujui' : 'tolak'}.`
    selectedUserForVerification.value = null
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || 'Gagal memverifikasi akun pemohon.')
  } finally {
    isVerifyingUser.value = false
  }
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search)
    const tabParam = params.get('view') || params.get('tab')
    if (tabParam && ['ringkasan', 'antrean', 'sla', 'verifikasi_pemohon'].includes(tabParam)) {
      activeTab.value = tabParam
    }
  }
})
</script>

