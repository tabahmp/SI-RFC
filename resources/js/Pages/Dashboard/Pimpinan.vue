<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- ========================================================
           HEADER & NAVIGASI TAB VIEW (RINGKASAN VS KEPUTUSAN)
      ======================================================== -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
            {{ currentView === 'ringkasan' ? 'Dashboard Pimpinan' : (currentView === 'ditunda' ? 'Permohonan Ditunda' : 'Otorisasi Kedinasan') }}
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
            {{ currentView === 'ringkasan' ? 'Ringkasan Eksekutif Tata Kelola RFC' : (currentView === 'ditunda' ? 'Daftar Permohonan Ditunda' : 'Daftar Menunggu Keputusan') }}
          </h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
            {{ currentView === 'ringkasan'
              ? 'Ikhtisar statistik, riwayat keputusan, dan distribusi implementasi perubahan TI Provinsi Jawa Barat.'
              : (currentView === 'ditunda'
                ? 'Daftar permohonan perubahan TI yang ditunda dan siap ditinjau ulang untuk disahkan kembali.'
                : 'Daftar permohonan perubahan TI yang telah diverifikasi dan siap disahkan pimpinan.')
            }}
          </p>
        </div>

        <!-- Tombol Switch View Langsung di Halaman (Dihilangkan pada halaman Manajemen Keputusan) -->
        <div v-if="currentView !== 'keputusan'" class="flex items-center gap-2 p-1 bg-slate-100 rounded-2xl border border-slate-200">
          <Link
            href="/dashboard/pimpinan?view=ringkasan"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
              currentView === 'ringkasan'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'
            ]"
          >
            <Icon icon="lucide:layout-grid" class="w-4 h-4" />
            <span>Ringkasan</span>
          </Link>

          <Link
            href="/dashboard/pimpinan?view=keputusan"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
              currentView === 'keputusan'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'
            ]"
          >
            <Icon icon="lucide:scale" class="w-4 h-4" />
            <span>Menunggu Keputusan</span>
            <span
              v-if="countMenunggu > 0"
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-black',
                currentView === 'keputusan' ? 'bg-amber-400 text-amber-950' : 'bg-amber-100 text-amber-800'
              ]"
            >
              {{ countMenunggu }}
            </span>
          </Link>

          <Link
            href="/dashboard/pimpinan?view=ditunda"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
              currentView === 'ditunda'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'
            ]"
          >
            <Icon icon="lucide:clock" class="w-4 h-4" />
            <span>RFC Ditunda</span>
            <span
              v-if="countDitunda > 0"
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-black',
                currentView === 'ditunda' ? 'bg-amber-400 text-amber-950' : 'bg-amber-100 text-amber-800'
              ]"
            >
              {{ countDitunda }}
            </span>
          </Link>
        </div>
      </div>

      <!-- Flash Message Berhasil -->
      <div v-if="successMsg" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2">
          <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
          <span>{{ successMsg }}</span>
        </div>
        <button @click="successMsg = null" class="text-emerald-700 hover:text-emerald-900 font-bold ml-2">✕</button>
      </div>

      <!-- ========================================================
           VIEW 1: TAMPILAN "RINGKASAN" (EXECUTIVE OVERVIEW)
      ======================================================== -->
      <div v-if="currentView === 'ringkasan'" class="space-y-6">

        <!-- 4 KARTU METRIK UTAMA MONITORING (KHUSUS RINGKASAN) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Menunggu Keputusan -->
          <Link
            href="/dashboard/pimpinan?view=keputusan"
            class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-amber-300 hover:shadow-md transition-all group cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-amber-600 uppercase tracking-wide">Menunggu Keputusan</span>
              <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <Icon icon="lucide:clock" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-amber-600 mt-2">{{ countMenunggu }}</div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
              <span>Memerlukan keputusan pimpinan</span>
              <Icon icon="lucide:chevron-right" class="w-3 h-3 text-slate-400 group-hover:translate-x-0.5 transition-transform" />
            </div>
          </Link>

          <!-- Disetujui Bulan Ini -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Disetujui</span>
              <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <Icon icon="lucide:check-circle" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-emerald-600 mt-2">{{ statistik?.disetujui ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Bulan berjalan</div>
          </div>

          <!-- RFC Ditunda -->
          <Link
            href="/dashboard/pimpinan?view=ditunda"
            class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-amber-300 hover:shadow-md transition-all group cursor-pointer block"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-amber-600 uppercase tracking-wide">RFC Ditunda</span>
              <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <Icon icon="lucide:clock" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-amber-600 mt-2">{{ countDitunda }}</div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
              <span>Siap ditinjau & diputuskan ulang</span>
              <Icon icon="lucide:chevron-right" class="w-3 h-3 text-slate-400 group-hover:translate-x-0.5 transition-transform" />
            </div>
          </Link>

          <!-- Total Selesai -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-[#0757A8] uppercase tracking-wide">Total Selesai</span>
              <span class="w-8 h-8 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center">
                <Icon icon="lucide:award" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-[#0757A8] mt-2">{{ statistik?.selesai ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-1">RFC selesai diimplementasikan</div>
          </div>
        </div>

        <!-- Banner Call-to-Action jika ada tiket menunggu -->
        <div v-if="countMenunggu > 0" class="p-5 rounded-2xl bg-gradient-to-r from-blue-900 via-[#0757A8] to-blue-800 text-white shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[10px] font-bold tracking-wide uppercase">
              <Icon icon="lucide:bell" class="w-3 h-3 text-amber-300" />
              <span>Perlu Tindakan Pimpinan</span>
            </div>
            <h3 class="text-base sm:text-lg font-bold">
              Terdapat {{ countMenunggu }} permohonan perubahan TI menunggu keputusan Anda
            </h3>
            <p class="text-xs text-blue-100 max-w-2xl">
              Permohonan telah melalui telaah kelengkapan dan penilaian risiko oleh verifikator Diskominfo Jabar.
            </p>
          </div>
          <Link
            href="/dashboard/pimpinan?view=keputusan"
            class="px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-900 font-extrabold text-xs flex items-center justify-center gap-2 shadow-sm transition-all whitespace-nowrap"
          >
            <span>Tinjau & Beri Keputusan</span>
            <Icon icon="lucide:arrow-right" class="w-4 h-4" />
          </Link>
        </div>

        <!-- 2 Kolom: Riwayat Keputusan Terakhir & Distribusi Kategori -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- Kolom Kiri: Riwayat Keputusan Pimpinan (Lebar 2/3) -->
          <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">Riwayat Keputusan Terakhir</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar permohonan yang telah disahkan keputusannya oleh pimpinan.</p>
              </div>
              <span class="text-xs font-bold text-[#0757A8] bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                {{ riwayatList.length }} Tercatat
              </span>
            </div>

            <!-- Tabel Riwayat Bersih -->
            <div v-if="riwayatList.length > 0" class="overflow-x-auto">
              <table class="w-full text-left text-xs">
                <thead>
                  <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[10.5px]">
                    <th class="py-2.5 px-3">Kode RFC</th>
                    <th class="py-2.5 px-3">Perangkat Daerah</th>
                    <th class="py-2.5 px-3 text-center">Keputusan</th>
                    <th class="py-2.5 px-3">Tanggal Sah</th>
                    <th class="py-2.5 px-3 text-right">Dokumen</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="r in riwayatList" :key="r.id" class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-3 font-mono font-bold text-[#0757A8]">
                      {{ r.kode_pelacakan || '-' }}
                    </td>
                    <td class="py-3 px-3">
                      <div class="font-semibold text-slate-800 truncate max-w-[200px]" :title="r.opd">{{ r.opd || '-' }}</div>
                      <div class="text-[11px] text-slate-500 truncate max-w-[200px]">{{ r.pemohon }}</div>
                    </td>
                    <td class="py-3 px-3 text-center">
                      <span
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold capitalize inline-block"
                        :class="[
                          r.keputusan === 'disetujui' ? 'bg-emerald-100 text-emerald-800' :
                          r.keputusan === 'ditunda' ? 'bg-amber-100 text-amber-800' :
                          'bg-rose-100 text-rose-800'
                        ]"
                      >
                        {{ r.keputusan }}
                      </span>
                    </td>
                    <td class="py-3 px-3 text-slate-600 whitespace-nowrap">
                      {{ r.diputuskan_pada || '-' }}
                    </td>
                    <td class="py-3 px-3 text-right">
                      <Link
                        v-if="r.kode_pelacakan"
                        :href="`/rfc/${r.kode_pelacakan}/dokumen`"
                        class="inline-flex items-center gap-1 text-[#0757A8] hover:underline font-bold"
                      >
                        <Icon icon="lucide:file-text" class="w-3.5 h-3.5" />
                        <span>FR-015</span>
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-else class="text-center py-8 text-slate-400 text-xs">
              Belum ada riwayat keputusan permohonan RFC.
            </div>
          </div>

          <!-- Kolom Kanan: Distribusi Kategori & Tata Kelola (Lebar 1/3) -->
          <div class="space-y-6">
            <!-- Distribusi Kategori -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
              <h2 class="text-base font-bold text-slate-800 pb-2 border-b border-slate-100">
                Distribusi Kategori TI
              </h2>

              <div v-if="kategoriList.length > 0" class="space-y-3">
                <div v-for="kat in kategoriList" :key="kat.id" class="space-y-1">
                  <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                    <span class="truncate">{{ kat.nama_kategori }}</span>
                    <span class="font-mono text-slate-500 font-normal">{{ kat.permohonan_count }} RFC</span>
                  </div>
                  <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div
                      class="h-full bg-[#0757A8] rounded-full transition-all"
                      :style="{ width: `${Math.min(100, (kat.permohonan_count / Math.max(1, totalKategoriRfc)) * 100)}%` }"
                    ></div>
                  </div>
                </div>
              </div>
              <div v-else class="text-center py-4 text-xs text-slate-400">
                Data kategori belum tersedia.
              </div>
            </div>

            <!-- Standar Kepatuhan SMKI & ITIL -->
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 space-y-3">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                  <Icon icon="lucide:shield-check" class="w-4 h-4" />
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-800">SMKI ISO/IEC 27001 & ITIL</div>
                  <div class="text-[10px] text-slate-500">Tata Kelola Perubahan Kedinasan</div>
                </div>
              </div>
              <p class="text-[11.5px] text-slate-600 leading-relaxed">
                Setiap persetujuan pimpinan menghasilkan stempel otorisasi digital dan jejak audit (audit trail) yang tidak dapat dimanipulasi pada dokumen resmi FR-015.
              </p>
            </div>
          </div>

        </div>

      </div>

      <!-- ========================================================
           VIEW 2: TAMPILAN "MENUNGGU KEPUTUSAN" (FILTERISASI & TABEL RAPI)
      ======================================================== -->
      <div v-else-if="currentView === 'keputusan'" class="space-y-6">

        <!-- CARD FILTERISASI (PENGGANTI KARTU MONITORING DI MENU KEPUTUSAN) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5">
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Info Filter -->
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center flex-shrink-0">
                <Icon icon="lucide:sliders-horizontal" class="w-5 h-5" />
              </div>
              <div>
                <h3 class="text-sm font-extrabold text-slate-800">Filterisasi Antrean RFC</h3>
                <p class="text-xs text-slate-500">Saring permohonan berdasarkan urgensi, kategori, SLA, atau pencarian</p>
              </div>
            </div>

            <!-- Kontrol Filter Form -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 flex-1 lg:max-w-3xl">
              <!-- Filter Urgensi -->
              <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Urgensi</label>
                <select
                  v-model="filterUrgensi"
                  class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all font-medium text-slate-700"
                >
                  <option value="semua">Semua Urgensi</option>
                  <option value="darurat">Darurat</option>
                  <option value="tinggi">Tinggi</option>
                  <option value="sedang">Sedang</option>
                  <option value="rendah">Rendah</option>
                </select>
              </div>

              <!-- Filter Kategori TI -->
              <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Kategori TI</label>
                <select
                  v-model="filterKategori"
                  class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all font-medium text-slate-700"
                >
                  <option value="semua">Semua Kategori</option>
                  <option v-for="kat in kategoriList" :key="kat.id" :value="kat.nama_kategori">
                    {{ kat.nama_kategori }}
                  </option>
                </select>
              </div>

              <!-- Filter Status SLA -->
              <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Status SLA</label>
                <select
                  v-model="filterSla"
                  class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all font-medium text-slate-700"
                >
                  <option value="semua">Semua Status SLA</option>
                  <option value="kritis">Kritis / Terlewat</option>
                  <option value="aman">Aman</option>
                </select>
              </div>

              <!-- Pencarian Text -->
              <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Pencarian</label>
                <div class="relative">
                  <Icon icon="lucide:search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                  <input
                    v-model="search"
                    type="text"
                    placeholder="Kode / Pemohon / Rincian..."
                    class="w-full text-xs pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all font-medium text-slate-700"
                  />
                </div>
              </div>
            </div>

            <!-- Tombol Reset Filter jika aktif -->
            <div v-if="isFiltered" class="flex items-end">
              <button
                type="button"
                @click="resetFilters"
                class="px-3.5 py-2 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-colors flex items-center gap-1.5 border border-rose-200 whitespace-nowrap"
              >
                <Icon icon="lucide:rotate-ccw" class="w-3.5 h-3.5" />
                <span>Reset</span>
              </button>
            </div>
          </div>
        </div>

        <!-- TABEL ANTREAN KEPUTUSAN BERSIH & RAPI -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">

          <!-- Header Toolbar Tabel -->
          <div class="py-4 px-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <h2 class="text-base font-extrabold text-slate-900">Antrean Menunggu Keputusan</h2>
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                {{ filteredMenunggu.length }} Permohonan
              </span>
            </div>
          </div>

          <!-- TABEL RAPI, SIMETRIS & LEGA -->
          <div v-if="filteredMenunggu.length > 0" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider">
                  <th class="py-3.5 px-4 w-12 text-center">No</th>
                  <th class="py-3.5 px-4 w-44">Kode RFC</th>
                  <th class="py-3.5 px-4 min-w-[180px]">Pemohon</th>
                  <th class="py-3.5 px-4 min-w-[280px]">Rincian Perubahan</th>
                  <th class="py-3.5 px-4 w-32 text-center">Urgensi</th>
                  <th class="py-3.5 px-4 w-36 text-center">SLA</th>
                  <th class="py-3.5 px-4 w-44 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs">
                <tr
                  v-for="(t, index) in filteredMenunggu"
                  :key="t.id"
                  class="hover:bg-blue-50/30 transition-colors"
                >
                  <!-- No -->
                  <td class="py-4 px-4 text-center text-slate-400 font-mono font-medium align-middle">
                    {{ index + 1 }}
                  </td>

                  <!-- Kode RFC (Hanya Kode Pelacakan, Tanpa Tanggal) -->
                  <td class="py-4 px-4 align-middle whitespace-nowrap">
                    <span class="font-mono text-xs font-bold text-[#0757A8] tracking-tight">
                      {{ t.kode_pelacakan }}
                    </span>
                  </td>

                  <!-- Pemohon (Hanya Nama Pemohon, Tanpa Perangkat Daerah) -->
                  <td class="py-4 px-4 align-middle">
                    <span class="font-semibold text-slate-800 text-xs">
                      {{ t.nama_pemohon || '-' }}
                    </span>
                  </td>

                  <!-- Rincian Perubahan (Hanya Rincian Saja, Tanpa Badge di Bawahnya) -->
                  <td class="py-4 px-4 align-middle">
                    <div class="text-xs text-slate-700 leading-relaxed font-normal" :title="t.deskripsi">
                      {{ t.deskripsi || '-' }}
                    </div>
                  </td>

                  <!-- Urgensi (Atribut Terpisah) -->
                  <td class="py-4 px-4 align-middle text-center whitespace-nowrap">
                    <span
                      class="px-3 py-1 rounded-full text-[10px] font-extrabold text-white uppercase inline-block shadow-xs"
                      :class="getBadgeByUrgensi(t.urgensi)"
                    >
                      {{ t.urgensi_label || t.urgensi }}
                    </span>
                  </td>

                  <!-- SLA (Atribut Terpisah) -->
                  <td class="py-4 px-4 align-middle text-center whitespace-nowrap">
                    <div v-if="t.sisa_sla" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                      :class="t.sla_terlewat ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200'"
                    >
                      <Icon icon="lucide:clock" class="w-3.5 h-3.5" :class="t.sla_terlewat ? 'text-rose-500' : 'text-slate-500'" />
                      <span>{{ t.sisa_sla }}</span>
                    </div>
                    <span v-else class="text-slate-400 text-xs">-</span>
                  </td>

                  <!-- Tombol Aksi -->
                  <td class="py-4 px-4 align-middle text-right whitespace-nowrap">
                    <div class="flex items-center justify-end">
                      <Link
                        :href="`/rfc/${t.kode_pelacakan}/workspace`"
                        class="px-3.5 py-1.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-sm transition-all duration-200"
                        title="Buka Workspace untuk memberikan keputusan"
                      >
                        <Icon icon="lucide:check-circle-2" class="w-3.5 h-3.5" />
                        <span>Beri Keputusan</span>
                        <Icon icon="lucide:arrow-right" class="w-3.5 h-3.5" />
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-else class="p-16 text-center space-y-3">
            <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
              <Icon icon="lucide:check-circle" class="w-7 h-7" />
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg">Tidak ada permohonan menunggu keputusan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
              {{ isFiltered ? 'Tidak ada permohonan yang sesuai dengan filter yang dipilih.' : 'Semua permohonan RFC yang diajukan telah selesai ditindaklanjuti oleh pimpinan.' }}
            </p>
            <button v-if="isFiltered" type="button" @click="resetFilters" class="text-xs font-bold text-[#0757A8] hover:underline pt-2">
              Bersihkan Semua Filter
            </button>
          </div>

        </div>

      </div>

      <!-- ========================================================
           VIEW 3: TAMPILAN "DITUNDA" (DAFTAR RFC DITUNDA)
      ======================================================== -->
      <div v-else-if="currentView === 'ditunda'" class="space-y-6">

        <!-- CARD FILTERISASI / INFO STATUS TUNDA -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                <Icon icon="lucide:clock" class="w-5 h-5" />
              </div>
              <div>
                <h3 class="text-sm font-extrabold text-slate-800">Antrean RFC Ditunda</h3>
                <p class="text-xs text-slate-500">Permohonan yang ditunda sementara dan dapat diproses kembali kapan saja oleh pimpinan</p>
              </div>
            </div>

            <!-- Pencarian Text -->
            <div class="w-full sm:w-72">
              <div class="relative">
                <Icon icon="lucide:search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                <input
                  v-model="searchDitunda"
                  type="text"
                  placeholder="Cari RFC ditunda..."
                  class="w-full text-xs pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all font-medium text-slate-700"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- TABEL ANTREAN DITUNDA -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
          <div class="py-4 px-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <h2 class="text-base font-extrabold text-slate-900">Daftar RFC Dalam Penundaan</h2>
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                {{ filteredDitunda.length }} Permohonan
              </span>
            </div>
            <div class="text-xs text-slate-500 hidden sm:block">
              Pimpinan dapat langsung mengesahkan keputusan baru (<strong>Setujui</strong> / <strong>Tolak</strong> / <strong>Atur Jadwal Ulang</strong>).
            </div>
          </div>

          <div v-if="filteredDitunda.length > 0" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider">
                  <th class="py-3.5 px-4 w-12 text-center">No</th>
                  <th class="py-3.5 px-4 w-44">Kode RFC</th>
                  <th class="py-3.5 px-4 min-w-[180px]">Pemohon</th>
                  <th class="py-3.5 px-4 min-w-[280px]">Rincian & Catatan Tunda</th>
                  <th class="py-3.5 px-4 w-44 text-center">Jadwal Tinjau Ulang</th>
                  <th class="py-3.5 px-4 w-44 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs">
                <tr
                  v-for="(t, index) in filteredDitunda"
                  :key="t.id"
                  class="hover:bg-amber-50/30 transition-colors"
                >
                  <td class="py-4 px-4 text-center text-slate-400 font-mono font-medium align-middle">
                    {{ index + 1 }}
                  </td>

                  <td class="py-4 px-4 align-middle whitespace-nowrap">
                    <span class="font-mono text-xs font-bold text-[#0757A8] tracking-tight">
                      {{ t.kode_pelacakan }}
                    </span>
                  </td>

                  <td class="py-4 px-4 align-middle">
                    <div class="font-semibold text-slate-800 text-xs">
                      {{ t.nama_pemohon || '-' }}
                    </div>
                    <div class="text-[11px] text-slate-500 truncate max-w-[220px]">
                      {{ t.opd || '-' }}
                    </div>
                  </td>

                  <td class="py-4 px-4 align-middle">
                    <div class="text-xs text-slate-800 font-medium leading-relaxed" :title="t.deskripsi">
                      {{ t.deskripsi || '-' }}
                    </div>
                    <div v-if="t.catatan_tunda" class="mt-1.5 text-[11px] text-amber-800 bg-amber-50 border border-amber-200/80 rounded-lg px-2.5 py-1 inline-flex items-center gap-1.5 max-w-full">
                      <Icon icon="lucide:info" class="w-3.5 h-3.5 text-amber-600 flex-shrink-0" />
                      <span class="truncate"><strong>Alasan Tunda:</strong> {{ t.catatan_tunda }}</span>
                    </div>
                  </td>

                  <td class="py-4 px-4 align-middle text-center whitespace-nowrap">
                    <div class="flex flex-col items-center gap-1">
                      <span
                        v-if="t.sudah_jatuh_tempo"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 border border-rose-200 inline-flex items-center gap-1"
                      >
                        <Icon icon="lucide:alert-circle" class="w-3 h-3 text-rose-600" />
                        <span>Siap Diputuskan</span>
                      </span>
                      <span
                        v-else
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 inline-flex items-center gap-1"
                      >
                        <Icon icon="lucide:clock" class="w-3 h-3 text-amber-600" />
                        <span>Menunggu Jadwal</span>
                      </span>
                      <span class="text-xs font-semibold text-slate-700">
                        {{ t.jadwal_tinjau_ulang || 'Tidak ditentukan' }}
                      </span>
                    </div>
                  </td>

                  <td class="py-4 px-4 align-middle text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-1.5">
                      <button
                        type="button"
                        @click="bukaModalKeputusan(t)"
                        class="px-3.5 py-1.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all"
                        title="Proses penundaan dan buat keputusan baru"
                      >
                        <Icon icon="lucide:scale" class="w-3.5 h-3.5" />
                        <span>Beri Keputusan</span>
                      </button>

                      <Link
                        :href="`/rfc/${t.kode_pelacakan}/dokumen`"
                        class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-[#0757A8] transition-colors"
                        title="Lihat Dokumen Resmi FR-015"
                      >
                        <Icon icon="lucide:file-text" class="w-3.5 h-3.5" />
                      </Link>

                      <Link
                        :href="`/rfc/${t.kode_pelacakan}/workspace`"
                        class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-[#0757A8] transition-colors"
                        title="Buka Workspace RFC"
                      >
                        <Icon icon="lucide:external-link" class="w-3.5 h-3.5" />
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="p-16 text-center space-y-3">
            <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
              <Icon icon="lucide:clock" class="w-7 h-7" />
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg">Tidak ada permohonan yang ditunda</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
              {{ searchDitunda ? 'Tidak ada permohonan ditunda yang cocok dengan pencarian Anda.' : 'Semua permohonan perubahan TI aktif telah diproses atau menunggu keputusan awal.' }}
            </p>
          </div>
        </div>

      </div>

      <!-- ========================================================
           MODAL KEPUTUSAN PIMPINAN (TERFOKUS, BERSIH & RAPI)
      ======================================================== -->
      <div
        v-if="modal.show && modal.tiket"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
      >
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden my-6 animate-in fade-in zoom-in-95 duration-200">

          <!-- Top Strip Jawa Barat -->
          <div class="h-1.5 flex">
            <div class="w-1/2 bg-[#0757A8]"></div>
            <div class="w-1/4 bg-[#F4CE18]"></div>
            <div class="w-1/4 bg-[#16834A]"></div>
          </div>

          <!-- Header Modal -->
          <div class="p-6 border-b border-slate-100 flex items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1.5">
                <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold text-white uppercase" :class="getBadgeByUrgensi(modal.tiket.urgensi)">
                  {{ modal.tiket.urgensi_label || modal.tiket.urgensi }}
                </span>
                <span class="font-mono text-xs font-bold text-[#0757A8]">{{ modal.tiket.kode_pelacakan }}</span>
              </div>
              <h3 class="text-lg font-extrabold text-slate-900 leading-snug">
                Persetujuan RFC: {{ modal.tiket.deskripsi || 'Permohonan Perubahan TI' }}
              </h3>
              <p class="text-xs text-slate-500 mt-1">
                {{ modal.tiket.opd }} · Pemohon: <strong class="text-slate-800">{{ modal.tiket.nama_pemohon }}</strong>
              </p>
            </div>
            <button
              type="button"
              @click="tutupModal"
              class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center flex-shrink-0 transition-colors"
            >
              ✕
            </button>
          </div>

          <!-- Form Keputusan Modal -->
          <form @submit.prevent="submitKeputusanModal" class="p-6 space-y-5 max-h-[calc(85vh-160px)] overflow-y-auto">

            <!-- Ringkasan Telaah Verifikator -->
            <div v-if="modal.tiket.catatan_verifikator" class="p-3.5 rounded-xl bg-purple-50 border border-purple-200 text-xs space-y-1">
              <div class="font-bold text-purple-900 flex items-center gap-1.5">
                <Icon icon="lucide:message-circle" class="w-3.5 h-3.5 text-purple-600" />
                <span>Rekomendasi Telaah Verifikator:</span>
              </div>
              <p class="text-purple-950/90 leading-relaxed">{{ modal.tiket.catatan_verifikator }}</p>
            </div>

            <!-- Catatan Penundaan Sebelumnya (Jika Sedang Ditunda) -->
            <div v-if="modal.tiket.status_terkini === 'ditunda' || modal.tiket.catatan_tunda" class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs space-y-1.5">
              <div class="font-bold text-amber-900 flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                  <Icon icon="lucide:clock" class="w-3.5 h-3.5 text-amber-600" />
                  <span>Jadwal Tinjau Ulang: <strong>{{ modal.tiket.jadwal_tinjau_ulang || 'Belum ditentukan' }}</strong></span>
                </div>
                <span v-if="modal.tiket.sudah_jatuh_tempo" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700">
                  Siap Diputuskan
                </span>
              </div>
              <p v-if="modal.tiket.catatan_tunda" class="text-amber-950/90 leading-relaxed">
                <span class="font-semibold text-amber-900">Alasan Tunda:</span> {{ modal.tiket.catatan_tunda }}
              </p>
            </div>

            <!-- Pilihan Keputusan -->
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-2">Keputusan Pimpinan <span class="text-red-500">*</span></label>
              <div class="grid grid-cols-3 gap-3">
                <label
                  v-for="opt in keputusanOptions"
                  :key="opt.value"
                  :class="[
                    'border-2 rounded-2xl p-3 flex flex-col items-center cursor-pointer text-center transition-all',
                    form.keputusan === opt.value ? opt.activeClass : 'border-slate-200 hover:bg-slate-50'
                  ]"
                >
                  <input type="radio" :value="opt.value" v-model="form.keputusan" class="sr-only" />
                  <Icon :icon="opt.icon" class="w-5 h-5 mb-1" :class="opt.iconClass" />
                  <span class="font-bold text-xs">{{ opt.label }}</span>
                  <span class="text-[10px] text-slate-500">{{ opt.desc }}</span>
                </label>
              </div>
            </div>

            <!-- Pilih Teknisi (Jika Disetujui) -->
            <div v-if="form.keputusan === 'disetujui'">
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                Teknisi Pelaksana Diskominfo <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.teknisi_id"
                required
                class="w-full text-xs px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none"
              >
                <option value="" disabled>Pilih teknisi yang ditugaskan...</option>
                <option v-for="t in teknisiList" :key="t.id" :value="t.id">{{ t.nama }}</option>
              </select>
            </div>

            <!-- Jadwal Tinjau (Jika Ditunda) -->
            <div v-if="form.keputusan === 'ditunda'">
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                Jadwal Tinjau Ulang <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.jadwal_tinjau"
                type="date"
                required
                class="w-full text-xs px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none"
              />
            </div>

            <!-- Catatan Pimpinan -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                Catatan / Arahan Pimpinan
                <span v-if="form.keputusan !== 'disetujui'" class="text-red-500">*</span>
              </label>
              <textarea
                v-model="form.catatan_keputusan"
                :required="form.keputusan !== 'disetujui'"
                rows="2"
                :placeholder="form.keputusan === 'disetujui' ? 'Arahan khusus pelaksanaan teknis (opsional)...' : 'Alasan penolakan atau jadwal penundaan (wajib)...'"
                class="w-full text-xs px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none"
              />
            </div>

            <!-- Tanda Tangan Digital Pimpinan -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                Tanda Tangan Digital Pimpinan <span class="text-red-500">*</span>
              </label>
              <SignaturePad v-model="form.tanda_tangan" />
              <p class="text-[10px] text-slate-400 mt-1">
                Tanda tangan digital Anda akan otomatis disematkan ke dokumen resmi FR-015 dan matriks otorisasi.
              </p>
            </div>

            <!-- Footer Tombol Aksi Modal -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
              <button
                type="button"
                @click="tutupModal"
                class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold transition-colors"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="isSubmitting"
                class="px-6 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold shadow-sm flex items-center gap-2 transition-all disabled:opacity-50"
              >
                <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                <Icon v-else icon="lucide:check-circle" class="w-4 h-4" />
                <span>{{ isSubmitting ? 'Menyimpan...' : 'Sahkan Keputusan' }}</span>
              </button>
            </div>
          </form>

        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, reactive, watch, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'
import SignaturePad from '@/Components/SignaturePad.vue'

// ── Props ─────────────────────────────────────────────────────────────────────
const props = defineProps({
  menungguKeputusan:  { type: Array,  default: () => [] },
  countMenunggu:      { type: Number, default: 0 },
  tiketDitunda:       { type: Array,  default: () => [] },
  countDitunda:       { type: Number, default: 0 },
  riwayatKeputusan:   { type: Array,  default: () => [] },
  distribusiKategori: { type: Array,  default: () => [] },
  statistik:          { type: Object, default: null },
  initialView:        { type: String, default: 'ringkasan' },
})

// ── View Detection (Ringkasan vs Menunggu Keputusan vs Ditunda) ───────────────
const page = usePage()

const currentView = computed(() => {
  const url = page.url || ''
  if (url.includes('view=ditunda')) return 'ditunda'
  if (url.includes('view=keputusan')) return 'keputusan'
  if (url.includes('view=ringkasan')) return 'ringkasan'
  return props.initialView || 'ringkasan'
})

// ── State ─────────────────────────────────────────────────────────────────────
const isSubmitting   = ref(false)
const successMsg     = ref(null)
const search         = ref('')
const searchDitunda  = ref('')
const filterUrgensi  = ref('semua')
const filterKategori = ref('semua')
const filterSla      = ref('semua')

const isFiltered = computed(() => {
  return filterUrgensi.value !== 'semua' ||
         filterKategori.value !== 'semua' ||
         filterSla.value !== 'semua' ||
         (search.value && search.value.trim() !== '')
})

const resetFilters = () => {
  filterUrgensi.value  = 'semua'
  filterKategori.value = 'semua'
  filterSla.value      = 'semua'
  search.value         = ''
}

const menungguList = ref([...props.menungguKeputusan])
const ditundaList  = ref([...(props.tiketDitunda || [])])
const riwayatList  = ref([...props.riwayatKeputusan])
const kategoriList = ref([...props.distribusiKategori])

watch(() => props.menungguKeputusan,  v => { menungguList.value = [...(v || [])] })
watch(() => props.tiketDitunda,       v => { ditundaList.value  = [...(v || [])] })
watch(() => props.riwayatKeputusan,   v => { riwayatList.value  = [...(v || [])] })
watch(() => props.distribusiKategori, v => { kategoriList.value = [...(v || [])] })

const filteredDitunda = computed(() => {
  return ditundaList.value.filter(t => {
    if (searchDitunda.value && searchDitunda.value.trim()) {
      const q = searchDitunda.value.toLowerCase()
      const match = (t.kode_pelacakan && t.kode_pelacakan.toLowerCase().includes(q)) ||
                    (t.nama_pemohon && t.nama_pemohon.toLowerCase().includes(q)) ||
                    (t.deskripsi && t.deskripsi.toLowerCase().includes(q)) ||
                    (t.opd && t.opd.toLowerCase().includes(q)) ||
                    (t.catatan_tunda && t.catatan_tunda.toLowerCase().includes(q))
      if (!match) return false
    }
    return true
  })
})

const totalKategoriRfc = computed(() => {
  return kategoriList.value.reduce((acc, k) => acc + (k.permohonan_count || 0), 0)
})

// Filtered List Menunggu Keputusan berdasarkan Filterisasi & Pencarian
const filteredMenunggu = computed(() => {
  return menungguList.value.filter(t => {
    // 1. Filter Urgensi
    if (filterUrgensi.value !== 'semua' && (t.urgensi || '').toLowerCase() !== filterUrgensi.value.toLowerCase()) {
      return false
    }

    // 2. Filter Kategori
    if (filterKategori.value !== 'semua') {
      const katNama = t.kategori || ''
      if (katNama.toLowerCase() !== filterKategori.value.toLowerCase()) {
        return false
      }
    }

    // 3. Filter Status SLA
    if (filterSla.value === 'kritis') {
      const isCritical = t.sla_terlewat || (t.sisa_sla && (t.sisa_sla.includes('jam') || t.sisa_sla.includes('menit') || t.sisa_sla.includes('detik')))
      if (!isCritical) return false
    } else if (filterSla.value === 'aman') {
      if (t.sla_terlewat) return false
    }

    // 4. Filter Kata Kunci Pencarian
    if (search.value && search.value.trim()) {
      const q = search.value.toLowerCase()
      const match = (t.kode_pelacakan && t.kode_pelacakan.toLowerCase().includes(q)) ||
                    (t.nama_pemohon && t.nama_pemohon.toLowerCase().includes(q)) ||
                    (t.deskripsi && t.deskripsi.toLowerCase().includes(q)) ||
                    (t.opd && t.opd.toLowerCase().includes(q))
      if (!match) return false
    }

    return true
  })
})

// ── Modal State & Form ────────────────────────────────────────────────────────
const modal = reactive({
  show: false,
  tiket: null,
})

const form = reactive({
  keputusan: 'disetujui',
  catatan_keputusan: '',
  teknisi_id: '',
  jadwal_tinjau: '',
  tanda_tangan: null,
})

// Teknisi list
const teknisiList = ref([])
async function loadTeknisi() {
  try {
    const res = await axios.get('/api/teknisi-list')
    teknisiList.value = res.data
  } catch {}
}
loadTeknisi()

// ── Auto-Save Draf per Tiket ──────────────────────────────────────────────────
function saveDraft(tiketId) {
  if (!tiketId) return
  try {
    const data = {
      keputusan: form.keputusan,
      catatan_keputusan: form.catatan_keputusan,
      teknisi_id: form.teknisi_id,
      jadwal_tinjau: form.jadwal_tinjau,
      tanda_tangan: form.tanda_tangan,
    }
    localStorage.setItem(`si_rfc_pimpinan_draft_${tiketId}`, JSON.stringify(data))
  } catch {}
}

function loadDraft(tiketId) {
  if (!tiketId) return
  try {
    const raw = localStorage.getItem(`si_rfc_pimpinan_draft_${tiketId}`)
    if (raw) {
      const parsed = JSON.parse(raw)
      form.keputusan = parsed.keputusan || 'disetujui'
      form.catatan_keputusan = parsed.catatan_keputusan || ''
      form.teknisi_id = parsed.teknisi_id || ''
      form.jadwal_tinjau = parsed.jadwal_tinjau || ''
      form.tanda_tangan = parsed.tanda_tangan || null
    } else {
      form.keputusan = 'disetujui'
      form.catatan_keputusan = ''
      form.teknisi_id = ''
      form.jadwal_tinjau = ''
      form.tanda_tangan = null
    }
  } catch {}
}

function clearDraft(tiketId) {
  if (!tiketId) return
  try { localStorage.removeItem(`si_rfc_pimpinan_draft_${tiketId}`) } catch {}
}

// Watch form saat modal aktif untuk auto-save
watch(form, () => {
  if (modal.show && modal.tiket) {
    saveDraft(modal.tiket.id)
  }
}, { deep: true })

// ── Handler Modal ─────────────────────────────────────────────────────────────
function bukaModalKeputusan(tiket) {
  modal.tiket = tiket
  loadDraft(tiket.id)
  modal.show = true
}

function tutupModal() {
  if (modal.tiket) {
    saveDraft(modal.tiket.id)
  }
  modal.show = false
  modal.tiket = null
}

async function submitKeputusanModal() {
  if (!modal.tiket) return
  isSubmitting.value = true

  const payload = {
    keputusan: form.keputusan,
    catatan_keputusan: form.catatan_keputusan,
    teknisi_id: form.keputusan === 'disetujui' ? form.teknisi_id : null,
    jadwal_tinjau_ulang: form.keputusan === 'ditunda' ? form.jadwal_tinjau : null,
    tanda_tangan: form.tanda_tangan,
  }

  try {
    await axios.post(`/rfc/${modal.tiket.id}/keputusan`, payload)
    successMsg.value = `Keputusan berhasil disahkan untuk ${modal.tiket.kode_pelacakan}. Dokumen FR-015 otomatis diperbarui.`
    clearDraft(modal.tiket.id)
    
    // Pindahkan dari menungguList ke riwayatList
    const t = modal.tiket
    riwayatList.value.unshift({
      id: Date.now(),
      rfc_id: t.id,
      kode_pelacakan: t.kode_pelacakan,
      opd: t.opd,
      pemohon: t.nama_pemohon,
      keputusan: form.keputusan,
      catatan_keputusan: form.catatan_keputusan,
      diputuskan_pada: 'Baru saja',
    })

    menungguList.value = menungguList.value.filter(item => item.id !== t.id)
    ditundaList.value  = ditundaList.value.filter(item => item.id !== t.id)
    tutupModal()
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan keputusan.')
  } finally {
    isSubmitting.value = false
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function getBadgeByUrgensi(urgensi) {
  return {
    kritis: 'bg-red-600',
    tinggi: 'bg-amber-500',
    sedang: 'bg-blue-600',
    rendah: 'bg-emerald-600'
  }[urgensi] ?? 'bg-slate-500'
}

const keputusanOptions = [
  { value: 'disetujui', label: 'Setujui', desc: 'Lanjut ke teknisi', icon: 'lucide:check-circle-2', iconClass: 'text-emerald-600', activeClass: 'border-emerald-500 bg-emerald-50 text-emerald-800' },
  { value: 'ditunda',   label: 'Tunda',   desc: 'Jadwal nanti',       icon: 'lucide:clock',           iconClass: 'text-amber-600',   activeClass: 'border-amber-500 bg-amber-50 text-amber-800' },
  { value: 'ditolak',   label: 'Tolak',   desc: 'RFC dibatalkan',     icon: 'lucide:x-circle',        iconClass: 'text-red-600',     activeClass: 'border-red-500 bg-red-50 text-red-800' },
]
</script>
