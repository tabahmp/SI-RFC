<template>
  <AppLayout role="pemohon">
    <div class="max-w-[1440px] mx-auto space-y-6">
      
      <!-- ======================================================================= -->
      <!-- TAMPILAN 0: DRAF PERMOHONAN (PERSIS FIGMA FRAME 20 · Draf permohonan)   -->
      <!-- ======================================================================= -->
      <div v-if="activeView === 'draf'" class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[13px] font-normal text-[#64748B] mb-1.5 flex items-center gap-1.5">
              <span>Pemohon</span>
              <span>/</span>
              <span class="text-[#0757A8] font-semibold">Draf</span>
            </div>
            <h1 class="text-2xl sm:text-[32px] font-bold text-[#0F172A] tracking-tight leading-tight">
              Draf permohonan
            </h1>
            <p class="text-xs sm:text-[14px] text-[#64748B] mt-1">
              Lanjutkan pengisian FR-015. Draf belum dikirim ke verifikator.
            </p>
          </div>

          <Link
            href="/form-perubahan-it/baru"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs sm:text-[13px] shadow-sm transition-all"
          >
            <Icon icon="lucide:plus" class="w-4 h-4" />
            <span>+ Buat permohonan</span>
          </Link>
        </div>

        <!-- Info Banner: Tersimpan sebagai draf -->
        <div class="flex items-start gap-3 p-4 rounded-xl bg-[#FFFBEB] border border-[#FDE68A]">
          <div class="w-5 h-5 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
            <Icon icon="lucide:info" class="w-3.5 h-3.5 text-amber-700" />
          </div>
          <p class="text-[12px] sm:text-[13px] text-[#92400E] leading-relaxed">
            <strong>Tersimpan sebagai draf</strong> · Nomor RFC diterbitkan setelah seluruh isian wajib dan tanda tangan pemohon dikirim.
          </p>
        </div>

        <!-- Draf Cards -->
        <div class="space-y-4">

          <!-- Draf Card 1: Satu Data Jabar -->
          <div class="bg-white rounded-2xl border border-[#DFE7F1] shadow-xs p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <!-- Badge DRAF -->
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#FEF3C7] text-[#92400E] text-[10px] font-black uppercase tracking-wider mb-3">
                  DRAF
                </span>
                <!-- Nama Sistem -->
                <h3 class="text-[18px] sm:text-[20px] font-bold text-[#0F172A] leading-snug">
                  Satu Data Jabar
                </h3>
                <!-- Deskripsi -->
                <p class="text-[13px] text-[#64748B] mt-0.5">
                  Peningkatan sinkronisasi dataset
                </p>
                <!-- Progress Steps -->
                <div class="flex items-center gap-1.5 mt-3 text-[12px] text-[#64748B]">
                  <Icon icon="lucide:layers" class="w-3.5 h-3.5 text-[#0757A8]" />
                  <span class="font-semibold text-[#0F172A]">2 dari 4 langkah</span>
                  <span class="text-[#CBD5E1]">•</span>
                  <span>Rincian kebutuhan</span>
                </div>
                <!-- Waktu -->
                <div class="flex items-center gap-1.5 mt-2 text-[11px] text-[#94A3B8]">
                  <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
                  <span>Terakhir disimpan 18 Jun 2025, 09.12</span>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0">
                <Link
                  href="/form-perubahan-it/baru?draf=draf-001"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-[12px] font-bold shadow-xs transition-all"
                >
                  <Icon icon="lucide:pencil" class="w-3.5 h-3.5" />
                  <span>Lanjutkan pengisian</span>
                </Link>
                <button
                  type="button"
                  @click="confirmHapusDraf('draf-001')"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-50 text-[12px] font-bold transition-all"
                >
                  <Icon icon="lucide:trash-2" class="w-3.5 h-3.5" />
                  <span>Hapus draf</span>
                </button>
              </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-[#64748B]">Progres pengisian</span>
                <span class="text-[11px] font-bold text-[#0757A8]">50%</span>
              </div>
              <div class="h-1.5 w-full bg-[#E2E8F0] rounded-full overflow-hidden">
                <div class="h-full w-1/2 bg-[#0757A8] rounded-full"></div>
              </div>
              <!-- 4 Titik Langkah -->
              <div class="flex items-center justify-between mt-2">
                <span v-for="(step, i) in ['Identitas sistem', 'Rincian kebutuhan', 'Analisis risiko', 'Tanda tangan']" :key="i"
                  class="text-[10px]"
                  :class="i < 2 ? 'text-[#0757A8] font-bold' : 'text-[#CBD5E1]'"
                >
                  {{ step }}
                </span>
              </div>
            </div>
          </div>

          <!-- Draf Card 2: Portal Layanan Publik -->
          <div class="bg-white rounded-2xl border border-[#DFE7F1] shadow-xs p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#FEF3C7] text-[#92400E] text-[10px] font-black uppercase tracking-wider mb-3">
                  DRAF
                </span>
                <h3 class="text-[18px] sm:text-[20px] font-bold text-[#0F172A] leading-snug">
                  Portal Layanan Publik
                </h3>
                <p class="text-[13px] text-[#64748B] mt-0.5">
                  Penambahan pelacakan permohonan
                </p>
                <div class="flex items-center gap-1.5 mt-3 text-[12px] text-[#64748B]">
                  <Icon icon="lucide:layers" class="w-3.5 h-3.5 text-[#0757A8]" />
                  <span class="font-semibold text-[#0F172A]">1 dari 4 langkah</span>
                  <span class="text-[#CBD5E1]">•</span>
                  <span>Identitas sistem</span>
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] text-[#94A3B8]">
                  <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
                  <span>Terakhir disimpan 17 Jun 2025, 16.20</span>
                </div>
              </div>
              <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0">
                <Link
                  href="/form-perubahan-it/baru?draf=draf-002"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-[12px] font-bold shadow-xs transition-all"
                >
                  <Icon icon="lucide:pencil" class="w-3.5 h-3.5" />
                  <span>Lanjutkan pengisian</span>
                </Link>
                <button
                  type="button"
                  @click="confirmHapusDraf('draf-002')"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-50 text-[12px] font-bold transition-all"
                >
                  <Icon icon="lucide:trash-2" class="w-3.5 h-3.5" />
                  <span>Hapus draf</span>
                </button>
              </div>
            </div>
            <div class="mt-4">
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-[#64748B]">Progres pengisian</span>
                <span class="text-[11px] font-bold text-[#0757A8]">25%</span>
              </div>
              <div class="h-1.5 w-full bg-[#E2E8F0] rounded-full overflow-hidden">
                <div class="h-full w-1/4 bg-[#0757A8] rounded-full"></div>
              </div>
              <div class="flex items-center justify-between mt-2">
                <span v-for="(step, i) in ['Identitas sistem', 'Rincian kebutuhan', 'Analisis risiko', 'Tanda tangan']" :key="i"
                  class="text-[10px]"
                  :class="i < 1 ? 'text-[#0757A8] font-bold' : 'text-[#CBD5E1]'"
                >
                  {{ step }}
                </span>
              </div>
            </div>
          </div>

          <!-- Draf Card 3: Dashboard Statistik -->
          <div class="bg-white rounded-2xl border border-[#DFE7F1] shadow-xs p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#FEF3C7] text-[#92400E] text-[10px] font-black uppercase tracking-wider mb-3">
                  DRAF
                </span>
                <h3 class="text-[18px] sm:text-[20px] font-bold text-[#0F172A] leading-snug">
                  Dashboard Statistik
                </h3>
                <p class="text-[13px] text-[#64748B] mt-0.5">
                  Perbaikan ringkasan indikator
                </p>
                <div class="flex items-center gap-1.5 mt-3 text-[12px] text-[#64748B]">
                  <Icon icon="lucide:layers" class="w-3.5 h-3.5 text-[#0757A8]" />
                  <span class="font-semibold text-[#0F172A]">3 dari 4 langkah</span>
                  <span class="text-[#CBD5E1]">•</span>
                  <span>Analisis risiko</span>
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] text-[#94A3B8]">
                  <Icon icon="lucide:clock" class="w-3.5 h-3.5" />
                  <span>Terakhir disimpan 16 Jun 2025, 10.05</span>
                </div>
              </div>
              <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0">
                <Link
                  href="/form-perubahan-it/baru?draf=draf-003"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-[12px] font-bold shadow-xs transition-all"
                >
                  <Icon icon="lucide:pencil" class="w-3.5 h-3.5" />
                  <span>Lanjutkan pengisian</span>
                </Link>
                <button
                  type="button"
                  @click="confirmHapusDraf('draf-003')"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-50 text-[12px] font-bold transition-all"
                >
                  <Icon icon="lucide:trash-2" class="w-3.5 h-3.5" />
                  <span>Hapus draf</span>
                </button>
              </div>
            </div>
            <div class="mt-4">
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-[#64748B]">Progres pengisian</span>
                <span class="text-[11px] font-bold text-[#0757A8]">75%</span>
              </div>
              <div class="h-1.5 w-full bg-[#E2E8F0] rounded-full overflow-hidden">
                <div class="h-full w-3/4 bg-[#0757A8] rounded-full"></div>
              </div>
              <div class="flex items-center justify-between mt-2">
                <span v-for="(step, i) in ['Identitas sistem', 'Rincian kebutuhan', 'Analisis risiko', 'Tanda tangan']" :key="i"
                  class="text-[10px]"
                  :class="i < 3 ? 'text-[#0757A8] font-bold' : 'text-[#CBD5E1]'"
                >
                  {{ step }}
                </span>
              </div>
            </div>
          </div>

        </div>

        <!-- Modal Konfirmasi Hapus Draf -->
        <div
          v-if="showHapusDrafModal"
          class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
          @click.self="showHapusDrafModal = false"
        >
          <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 space-y-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <Icon icon="lucide:trash-2" class="w-5 h-5 text-red-600" />
              </div>
              <div>
                <h3 class="font-bold text-[#0F172A] text-base">Hapus draf?</h3>
                <p class="text-[12px] text-[#64748B] mt-0.5">Draf yang dihapus tidak dapat dikembalikan.</p>
              </div>
            </div>
            <div class="flex gap-3 pt-2">
              <button
                @click="showHapusDrafModal = false"
                class="flex-1 py-2 rounded-xl border border-slate-200 text-slate-700 text-sm font-bold hover:bg-slate-50 transition-all"
              >
                Batal
              </button>
              <button
                @click="hapusDraf"
                class="flex-1 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-all"
              >
                Ya, hapus
              </button>
            </div>
          </div>
        </div>

      </div>

      <!-- ======================================================================= -->
      <!-- TAMPILAN 1: SEMUA PERMOHONAN (PERSIS FIGMA FRAME 19 · Semua permohonan) -->
      <!-- ======================================================================= -->
      <div v-else-if="activeView === 'permohonan'" class="space-y-6">
        
        <!-- Breadcrumb & Header Sesuai Frame 19 -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[13px] font-normal text-[#64748B] mb-1.5 flex items-center gap-1.5">
              <span>Pemohon</span>
              <span>/</span>
              <span class="text-[#0757A8] font-semibold">Permohonan saya</span>
            </div>
            <h1 class="text-2xl sm:text-[32px] font-bold text-[#0F172A] tracking-tight leading-tight">
              Semua permohonan
            </h1>
            <p class="text-xs sm:text-[14px] text-[#64748B] mt-1">
              Cari, saring, dan pantau perjalanan perubahan layanan TI Anda.
            </p>
          </div>

          <Link
            href="/form-perubahan-it/baru"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs sm:text-[13px] shadow-sm transition-all"
          >
            <Icon icon="lucide:plus" class="w-4 h-4" />
            <span>+ Ajukan RFC baru</span>
          </Link>
        </div>

        <!-- 4 Kapsul Tab Status (Semua · X, Menunggu verifikasi · Y, Perlu revisi · Z, Selesai · W) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
          <!-- Semua -->
          <button
            type="button"
            @click="activeStatusFilter = 'semua'"
            :class="[
              'h-[42px] px-3.5 rounded-xl flex items-center justify-center text-[13px] transition-all',
              activeStatusFilter === 'semua'
                ? 'bg-[#EAF2FD] text-[#0757A8] font-bold shadow-xs'
                : 'bg-white text-[#64748B] border border-[#DFE7F1] hover:bg-slate-50 font-normal'
            ]"
          >
            <span>Semua · {{ totalCount }}</span>
          </button>

          <!-- Menunggu verifikasi -->
          <button
            type="button"
            @click="activeStatusFilter = 'menunggu_verifikasi'"
            :class="[
              'h-[42px] px-3.5 rounded-xl flex items-center justify-center text-[13px] transition-all',
              activeStatusFilter === 'menunggu_verifikasi'
                ? 'bg-[#EAF2FD] text-[#0757A8] font-bold shadow-xs'
                : 'bg-white text-[#64748B] border border-[#DFE7F1] hover:bg-slate-50 font-normal'
            ]"
          >
            <span>Menunggu verifikasi · {{ menungguCount }}</span>
          </button>

          <!-- Perlu revisi -->
          <button
            type="button"
            @click="activeStatusFilter = 'perlu_revisi'"
            :class="[
              'h-[42px] px-3.5 rounded-xl flex items-center justify-center text-[13px] transition-all',
              activeStatusFilter === 'perlu_revisi'
                ? 'bg-[#EAF2FD] text-[#0757A8] font-bold shadow-xs'
                : 'bg-white text-[#64748B] border border-[#DFE7F1] hover:bg-slate-50 font-normal'
            ]"
          >
            <span>Perlu revisi · {{ revisiCount }}</span>
          </button>

          <!-- Selesai -->
          <button
            type="button"
            @click="activeStatusFilter = 'selesai'"
            :class="[
              'h-[42px] px-3.5 rounded-xl flex items-center justify-center text-[13px] transition-all',
              activeStatusFilter === 'selesai'
                ? 'bg-[#EAF2FD] text-[#0757A8] font-bold shadow-xs'
                : 'bg-white text-[#64748B] border border-[#DFE7F1] hover:bg-slate-50 font-normal'
            ]"
          >
            <span>Selesai · {{ selesaiCount }}</span>
          </button>
        </div>

        <!-- Box Filter Permohonan (Pencarian, Status, Prioritas, Terapkan) -->
        <div class="bg-white rounded-xl border border-[#DFE7F1] p-4 sm:p-5 shadow-xs">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">
            <!-- Pencarian -->
            <div class="md:col-span-5 space-y-1.5">
              <label class="block text-[13px] font-bold text-[#0F172A]">Pencarian</label>
              <div class="relative">
                <input
                  v-model="searchKeyword"
                  type="text"
                  placeholder="Cari nomor RFC atau nama sistem"
                  class="w-full h-[38px] px-3 text-[13px] text-[#0F172A] bg-white border border-[#DFE7F1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] placeholder:text-[#64748B] transition-all"
                />
              </div>
            </div>

            <!-- Status Dropdown -->
            <div class="md:col-span-3 space-y-1.5">
              <label class="block text-[13px] font-bold text-[#0F172A]">Status</label>
              <select
                v-model="selectedStatus"
                class="w-full h-[38px] px-3 text-[13px] text-[#64748B] bg-white border border-[#DFE7F1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all"
              >
                <option value="">Semua status</option>
                <option value="Menunggu verifikasi">Menunggu verifikasi</option>
                <option value="Dalam pengerjaan">Dalam pengerjaan</option>
                <option value="Perlu revisi">Perlu revisi</option>
                <option value="Selesai">Selesai</option>
              </select>
            </div>

            <!-- Prioritas Dropdown -->
            <div class="md:col-span-2 space-y-1.5">
              <label class="block text-[13px] font-bold text-[#0F172A]">Prioritas</label>
              <select
                v-model="selectedPrioritas"
                class="w-full h-[38px] px-3 text-[13px] text-[#64748B] bg-white border border-[#DFE7F1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] transition-all"
              >
                <option value="">Semua prioritas</option>
                <option value="P1">P1 - Kritis</option>
                <option value="P2">P2 - Tinggi</option>
                <option value="P3">P3 - Sedang</option>
                <option value="P4">P4 - Rendah</option>
              </select>
            </div>

            <!-- Tombol Terapkan -->
            <div class="md:col-span-2">
              <button
                type="button"
                @click="applyFilters"
                class="w-full h-[38px] flex items-center justify-center bg-[#0757A8] hover:bg-[#063D78] text-white rounded-xl text-[13px] font-bold shadow-xs transition-all"
              >
                <span>Terapkan</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Tabel Permohonan (Frame 19) -->
        <div class="bg-white rounded-xl border border-[#DFE7F1] shadow-xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-white border-b border-[#DFE7F1] text-[10px] font-bold text-[#64748B] uppercase tracking-wider">
                  <th class="py-3 px-6 w-[20%]">NOMOR RFC / TANGGAL</th>
                  <th class="py-3 px-6 w-[35%]">SISTEM & PERUBAHAN</th>
                  <th class="py-3 px-6 w-[12%]">PRIORITAS</th>
                  <th class="py-3 px-6 w-[18%]">STATUS</th>
                  <th class="py-3 px-6 w-[15%] text-left">TINDAKAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr
                  v-for="(t, index) in filteredTikets"
                  :key="t.no_rfc"
                  :class="[
                    'transition-colors',
                    index % 2 === 1 ? 'bg-[#F7F9FC]' : 'bg-white'
                  ]"
                >
                  <!-- NOMOR RFC / TANGGAL -->
                  <td class="py-4 px-6 align-middle">
                    <div class="font-bold text-[#0757A8] text-[12px] leading-tight">{{ t.no_rfc }}</div>
                    <div class="text-[11px] text-[#64748B] mt-1">{{ t.tanggal }}</div>
                  </td>

                  <!-- SISTEM & PERUBAHAN -->
                  <td class="py-4 px-6 align-middle">
                    <div class="font-bold text-[#0F172A] text-[14px] leading-snug">{{ t.sistem }}</div>
                    <div class="text-[12px] text-[#64748B] mt-0.5 line-clamp-1">{{ t.perubahan }}</div>
                  </td>

                  <!-- PRIORITAS -->
                  <td class="py-4 px-6 align-middle">
                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-[5px] text-[11px] font-bold bg-[#EFF6FF] text-[#0757A8]">
                      {{ t.prioritas }}
                    </span>
                  </td>

                  <!-- STATUS -->
                  <td class="py-4 px-6 align-middle">
                    <span
                      :class="[
                        'inline-flex items-center justify-center px-3 py-1 rounded-[5px] text-[11px] font-bold',
                        t.status === 'Perlu revisi' ? 'bg-[#FFFBEB] text-[#92400E]' :
                        t.status === 'Selesai' ? 'bg-[#ECFDF5] text-[#047857]' :
                        'bg-[#EFF6FF] text-[#0757A8]'
                      ]"
                    >
                      {{ t.status }}
                    </span>
                  </td>

                  <!-- TINDAKAN -->
                  <td class="py-4 px-6 align-middle text-left">
                    <Link
                      :href="t.tindakanUrl"
                      class="text-[13px] font-bold text-[#0757A8] hover:underline transition-colors"
                    >
                      {{ t.tindakanLabel }}
                    </Link>
                  </td>
                </tr>

                <!-- State Kosong jika filter tidak ada data -->
                <tr v-if="filteredTikets.length === 0">
                  <td colspan="5" class="py-12 text-center text-slate-500 text-xs">
                    Tidak ada permohonan yang sesuai dengan filter atau kata kunci pencarian.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination Footer Figma 19 -->
          <div class="p-4 px-6 border-t border-[#DFE7F1] bg-white flex flex-wrap items-center justify-between gap-3 text-[12px]">
            <div class="text-[#64748B]">
              Menampilkan 1–{{ filteredTikets.length }} dari {{ totalCount }} permohonan
            </div>

            <div class="flex items-center gap-4 text-[#0757A8] font-bold select-none">
              <button class="hover:underline">Sebelumnya</button>
              <div class="flex items-center gap-3">
                <span class="cursor-pointer hover:underline text-[#0757A8]">1</span>
              </div>
              <button class="hover:underline">Berikutnya</button>
            </div>
          </div>
        </div>

      </div>

      <!-- ======================================================================= -->
      <!-- TAMPILAN 2: RINGKASAN DASHBOARD (Frame 01 · Dashboard Pemohon)          -->
      <!-- ======================================================================= -->
      <div v-else class="space-y-6">
        
        <!-- HEADER DASHBOARD PEMOHON -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
              Portal Layanan SI-RFC
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
              Dashboard Pemohon
            </h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
              Kelola pengajuan perubahan layanan TI, pantau posisi penelaahan, dan pantau SLA secara langsung.
            </p>
          </div>

          <div class="flex items-center gap-2.5">
            <button
              type="button"
              @click="activeView = 'permohonan'"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm shadow-xs transition-all"
            >
              <Icon icon="lucide:list" class="w-4 h-4 text-[#0757A8]" />
              <span>Lihat Permohonan Saya</span>
            </button>
            
            <Link
              href="/form-perubahan-it/baru"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white font-bold text-xs sm:text-sm shadow-sm transition-all"
            >
              <Icon icon="lucide:plus" class="w-4 h-4" />
              <span>+ Ajukan RFC Baru</span>
            </Link>
          </div>
        </div>

        <!-- 4 KARTU METRIK STATISTIK PEMOHON (PERSIS FIGMA) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Total Permohonan -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Permohonan</span>
              <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center font-bold">
                <Icon icon="lucide:file-text" class="w-4 h-4" />
              </div>
            </div>
            <div class="text-3xl font-black text-[#0F172A]">{{ totalCount }}</div>
            <div class="text-[11px] text-emerald-600 mt-1 font-semibold flex items-center gap-1">
              <Icon icon="lucide:trending-up" class="w-3.5 h-3.5" />
              <span>Aktif dalam proses</span>
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
            <div class="text-3xl font-black text-amber-600">{{ menungguCount }}</div>
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
            <div class="text-3xl font-black text-red-600">{{ revisiCount }}</div>
            <div class="text-[11px] text-red-600 mt-1 font-medium">Membutuhkan revisi/respons</div>
          </div>

          <!-- Selesai BAST -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Selesai BAST</span>
              <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <Icon icon="lucide:check-circle-2" class="w-4 h-4" />
              </div>
            </div>
            <div class="text-3xl font-black text-emerald-600">{{ selesaiCount }}</div>
            <div class="text-[11px] text-slate-500 mt-1 font-medium">Tingkat penyelesaian {{ persentaseSelesai }}%</div>
          </div>
        </div>

        <!-- TABEL PERMOHONAN TERBARU PEMOHON -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div>
              <h2 class="text-lg font-bold text-[#0F172A]">Permohonan terbaru</h2>
              <p class="text-xs text-[#64748B] mt-0.5">Pelacakan status dan posisi pemeriksa secara langsung</p>
            </div>
            <button
              @click="activeView = 'permohonan'"
              class="text-xs font-bold text-[#0757A8] hover:underline flex items-center gap-1"
            >
              <span>Lihat semua {{ totalCount }} permohonan</span>
              <Icon icon="lucide:arrow-right" class="w-4 h-4" />
            </button>
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
                <tr v-for="t in normalizedTikets.slice(0, 5)" :key="t.no_rfc" class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#0757A8] text-xs">{{ t.no_rfc }}</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5">{{ t.tanggal }}</div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="font-bold text-[#334155] text-sm">{{ t.sistem }}</div>
                    <div class="text-[11px] text-[#64748B] mt-0.5 line-clamp-1">{{ t.perubahan }}</div>
                  </td>
                  <td class="py-4 px-6">
                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-[#EFF6FF] text-[#0757A8]">
                      {{ t.prioritas }}
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <span
                      :class="[
                        'inline-flex items-center justify-center px-3 py-1 rounded-full text-[11px] font-bold',
                        t.raw_status === 'perlu_revisi' ? 'bg-[#FFFBEB] text-[#92400E]' :
                        t.raw_status === 'selesai' ? 'bg-[#ECFDF5] text-[#047857]' :
                        'bg-[#EFF6FF] text-[#0757A8]'
                      ]"
                    >
                      {{ t.status }}
                    </span>
                  </td>
                  <td class="py-4 px-6 font-bold text-[#475569]">
                    {{ t.posisi }}
                  </td>
                  <td class="py-4 px-6 font-mono text-[11px]" :class="t.prioritas === 'P1' ? 'text-[#B91C1C] font-bold' : 'text-[#64748B]'">
                    {{ t.sla }}
                  </td>
                  <td class="py-4 px-6 text-right">
                    <Link
                      :href="t.tindakanUrl"
                      class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#0757A8] hover:bg-blue-50 transition-colors"
                      title="Buka detail"
                    >
                      <Icon icon="lucide:chevron-right" class="w-5 h-5" />
                    </Link>
                  </td>
                </tr>
                <tr v-if="normalizedTikets.length === 0">
                  <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                    Belum ada permohonan yang diajukan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  tikets: {
    type: Array,
    default: () => []
  },
  initialView: {
    type: String,
    default: 'ringkasan'
  }
})

const page = usePage()
const activeView = ref(props.initialView || 'ringkasan')
const activeStatusFilter = ref('semua')
const searchKeyword = ref('')
const selectedStatus = ref('')
const selectedPrioritas = ref('')

// Data master permohonan dinamis dari props (database) dengan fallback
const normalizedTikets = computed(() => {
  if (props.tikets && props.tikets.length > 0) {
    return props.tikets.map(t => {
      const prioritasShort = t.prioritas
        ? t.prioritas.split(' ')[0]
        : 'P3'

      let posisi = 'Verifikator'
      let tindakanLabel = 'Buka detail'
      let tindakanUrl = `/form-perubahan-it/ticket/${t.no_rfc}`

      if (t.status === 'selesai') {
        posisi = 'Selesai BAST'
        tindakanLabel = 'Unduh BAST'
        tindakanUrl = `/form-perubahan-it/dokumen/${t.no_rfc}`
      } else if (t.status === 'perlu_revisi') {
        posisi = 'Pemohon'
        tindakanLabel = 'Perbaiki RFC'
      } else if (t.status === 'dalam_pengerjaan') {
        posisi = 'Tim Integrasi'
      } else if (t.status === 'menunggu_keputusan_pimpinan') {
        posisi = 'Pimpinan'
      }

      return {
        id: t.id,
        no_rfc: t.no_rfc,
        tanggal: t.tanggal_permohonan || t.created_at,
        sistem: t.nama_aplikasi || 'Sistem Informasi Layanan TI',
        perubahan: t.perubahan_diharapkan || '-',
        prioritas: prioritasShort,
        raw_prioritas: t.prioritas,
        status: t.status_label || t.status,
        raw_status: t.status,
        posisi: posisi,
        sla: t.status === 'selesai' ? 'Tepat SLA' : (prioritasShort === 'P1' ? 'SLA: ≤ 4 jam' : 'SLA Terjaga'),
        tindakanLabel: tindakanLabel,
        tindakanUrl: tindakanUrl,
      }
    })
  }

  // Fallback demo sample jika database baru kosong
  return [
    {
      no_rfc: 'RFC-202506-0142',
      tanggal: '18 Jun 2025',
      sistem: 'Satu Data Jabar',
      perubahan: 'Optimasi sinkronisasi data lintas OPD',
      prioritas: 'P1',
      raw_prioritas: 'P1 - Kritis',
      status: 'Menunggu verifikasi',
      raw_status: 'menunggu_verifikasi',
      posisi: 'Verifikator',
      sla: 'SLA: 01:42:18',
      tindakanLabel: 'Buka detail',
      tindakanUrl: '/form-perubahan-it/ticket/RFC-202506-0142'
    },
    {
      no_rfc: 'RFC-202506-0138',
      tanggal: '17 Jun 2025',
      sistem: 'Sistem Perizinan Terpadu',
      perubahan: 'Integrasi API tanda tangan elektronik',
      prioritas: 'P2',
      raw_prioritas: 'P2 - Tinggi',
      status: 'Dalam pengerjaan',
      raw_status: 'dalam_pengerjaan',
      posisi: 'Tim Integrasi',
      sla: 'SLA: 18:24:00',
      tindakanLabel: 'Buka detail',
      tindakanUrl: '/form-perubahan-it/ticket/RFC-202506-0138'
    },
    {
      no_rfc: 'RFC-202506-0129',
      tanggal: '15 Jun 2025',
      sistem: 'Portal Kepegawaian',
      perubahan: 'Penambahan modul riwayat pelatihan',
      prioritas: 'P3',
      raw_prioritas: 'P3 - Sedang',
      status: 'Perlu revisi',
      raw_status: 'perlu_revisi',
      posisi: 'Pemohon',
      sla: 'SLA: 2 hari',
      tindakanLabel: 'Perbaiki RFC',
      tindakanUrl: '/form-perubahan-it/ticket/RFC-202506-0129'
    },
    {
      no_rfc: 'RFC-202506-0117',
      tanggal: '12 Jun 2025',
      sistem: 'Dashboard Pimpinan',
      perubahan: 'Penyempurnaan visualisasi indikator IKU',
      prioritas: 'P4',
      raw_prioritas: 'P4 - Rendah',
      status: 'Selesai',
      raw_status: 'selesai',
      posisi: 'Selesai BAST',
      sla: 'Tepat SLA',
      tindakanLabel: 'Unduh BAST',
      tindakanUrl: '/form-perubahan-it/dokumen/RFC-202506-0117'
    }
  ]
})

// Perhitungan metrik dinamis
const totalCount = computed(() => normalizedTikets.value.length)
const menungguCount = computed(() => normalizedTikets.value.filter(t => t.raw_status === 'menunggu_verifikasi').length)
const revisiCount = computed(() => normalizedTikets.value.filter(t => t.raw_status === 'perlu_revisi').length)
const selesaiCount = computed(() => normalizedTikets.value.filter(t => t.raw_status === 'selesai').length)
const persentaseSelesai = computed(() => {
  if (totalCount.value === 0) return 0
  return Math.round((selesaiCount.value / totalCount.value) * 100)
})

// Filter data interaktif
const filteredTikets = computed(() => {
  return normalizedTikets.value.filter(t => {
    // Filter kapsul status
    if (activeStatusFilter.value === 'menunggu_verifikasi' && t.raw_status !== 'menunggu_verifikasi') return false
    if (activeStatusFilter.value === 'perlu_revisi' && t.raw_status !== 'perlu_revisi') return false
    if (activeStatusFilter.value === 'selesai' && t.raw_status !== 'selesai') return false

    // Filter status dropdown
    if (selectedStatus.value) {
      if (selectedStatus.value === 'Menunggu verifikasi' && t.raw_status !== 'menunggu_verifikasi') return false
      if (selectedStatus.value === 'Dalam pengerjaan' && t.raw_status !== 'dalam_pengerjaan') return false
      if (selectedStatus.value === 'Perlu revisi' && t.raw_status !== 'perlu_revisi') return false
      if (selectedStatus.value === 'Selesai' && t.raw_status !== 'selesai') return false
    }

    // Filter prioritas dropdown
    if (selectedPrioritas.value && t.prioritas !== selectedPrioritas.value) return false

    // Filter search keyword
    if (searchKeyword.value.trim()) {
      const q = searchKeyword.value.toLowerCase()
      const matchRfc = t.no_rfc.toLowerCase().includes(q)
      const matchSistem = t.sistem.toLowerCase().includes(q)
      const matchDesc = t.perubahan.toLowerCase().includes(q)
      if (!matchRfc && !matchSistem && !matchDesc) return false
    }

    return true
  })
})

// State modal hapus draf
const showHapusDrafModal = ref(false)
const targetHapusDrafId = ref(null)

function confirmHapusDraf(drafId) {
  targetHapusDrafId.value = drafId
  showHapusDrafModal.value = true
}

function hapusDraf() {
  // TODO: Panggil API delete draf
  showHapusDrafModal.value = false
  targetHapusDrafId.value = null
}

function applyFilters() {
  // Trigger reaktif otomatis berjalan melalui computed
}

function syncViewFromUrl() {
  if (typeof window === 'undefined') return
  const params = new URLSearchParams(window.location.search)
  const viewParam = params.get('view')
  if (viewParam === 'permohonan' || viewParam === 'permohonan_saya') {
    activeView.value = 'permohonan'
  } else if (viewParam === 'draf') {
    activeView.value = 'draf'
  } else if (viewParam === 'ringkasan') {
    activeView.value = 'ringkasan'
  }
}

watch(() => page.url, () => {
  syncViewFromUrl()
})

onMounted(() => {
  syncViewFromUrl()
})
</script>
