<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- ========================================================
           HEADER & NAVIGASI TAB VIEW (RINGKASAN VS TUGAS VS BAST)
      ======================================================== -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">
            {{ currentView === 'ringkasan' ? 'Dashboard Teknisi' : currentView === 'tugas' ? 'Eksekusi Teknis TI' : 'Arsip Dokumen Kedinasan' }}
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
            {{ currentView === 'ringkasan'
              ? 'Ringkasan Eksekutif Implementasi Teknis'
              : currentView === 'tugas'
                ? 'Tugas Implementasi Saya'
                : 'Arsip Berita Acara Serah Terima (BAST)'
            }}
          </h1>
          <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
            {{ currentView === 'ringkasan'
              ? 'Ikhtisar tugas aktif, riwayat implementasi selesai, dan distribusi kategori perubahan TI Provinsi Jawa Barat.'
              : currentView === 'tugas'
                ? 'Daftar permohonan perubahan TI yang telah disahkan pimpinan dan siap Anda laksanakan di ruang kerja.'
                : 'Daftar seluruh berkas BAST yang telah diunggah dan disahkan untuk bukti serah terima pekerjaan.'
            }}
          </p>
        </div>

        <!-- Tombol Switch View Langsung di Halaman -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200 overflow-x-auto">
          <!-- Ringkasan -->
          <Link
            href="/dashboard/teknisi?view=ringkasan"
            :class="[
              'px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap',
              currentView === 'ringkasan'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'
            ]"
          >
            <Icon icon="lucide:layout-grid" class="w-4 h-4" />
            <span>Ringkasan</span>
          </Link>

          <!-- Tugas Saya -->
          <Link
            href="/dashboard/teknisi?view=tugas"
            :class="[
              'px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap',
              currentView === 'tugas'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'
            ]"
          >
            <Icon icon="lucide:code-2" class="w-4 h-4" />
            <span>Tugas Saya</span>
            <span
              v-if="localAntrian.length > 0"
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-black',
                currentView === 'tugas' ? 'bg-amber-400 text-amber-950' : 'bg-amber-100 text-amber-800'
              ]"
            >
              {{ localAntrian.length }}
            </span>
          </Link>

          <!-- Arsip BAST -->
          <Link
            href="/dashboard/teknisi?view=bast"
            :class="[
              'px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap',
              currentView === 'bast'
                ? 'bg-[#0757A8] text-white shadow-xs'
                : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'
            ]"
          >
            <Icon icon="lucide:archive" class="w-4 h-4" />
            <span>Arsip BAST</span>
            <span
              v-if="hasilSelesai.length > 0"
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-black',
                currentView === 'bast' ? 'bg-emerald-400 text-emerald-950' : 'bg-emerald-100 text-emerald-800'
              ]"
            >
              {{ hasilSelesai.length }}
            </span>
          </Link>
        </div>
      </div>

      <!-- Flash Message Berhasil -->
      <div v-if="actionMsg" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2">
          <Icon icon="lucide:check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
          <span>{{ actionMsg }}</span>
        </div>
        <button @click="actionMsg = null" class="text-emerald-700 hover:text-emerald-900 font-bold ml-2">✕</button>
      </div>

      <!-- ========================================================
           VIEW 1: TAMPILAN "RINGKASAN" (KONSEP SEPERTI RINGKASAN PIMPINAN)
      ======================================================== -->
      <div v-if="currentView === 'ringkasan'" class="space-y-6">

        <!-- 4 KARTU METRIK UTAMA MONITORING (KHUSUS RINGKASAN) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Tugas Siap Dikerjakan -->
          <Link
            href="/dashboard/teknisi?view=tugas"
            class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-amber-300 hover:shadow-md transition-all group cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-amber-600 uppercase tracking-wide">Siap Dikerjakan</span>
              <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <Icon icon="lucide:clock" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-amber-600 mt-2">
              {{ localAntrian.filter(r => r.status_terkini === 'disetujui').length }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
              <span>Disetujui pimpinan APTIKA</span>
              <Icon icon="lucide:chevron-right" class="w-3 h-3 text-slate-400 group-hover:translate-x-0.5 transition-transform" />
            </div>
          </Link>

          <!-- Sedang Dikerjakan -->
          <Link
            href="/dashboard/teknisi?view=tugas"
            class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-blue-300 hover:shadow-md transition-all group cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">Sedang Dikerjakan</span>
              <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <Icon icon="lucide:loader" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-blue-600 mt-2">
              {{ localAntrian.filter(r => r.status_terkini === 'sedang_dikerjakan').length }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
              <span>Proses eksekusi aktif</span>
              <Icon icon="lucide:chevron-right" class="w-3 h-3 text-slate-400 group-hover:translate-x-0.5 transition-transform" />
            </div>
          </Link>

          <!-- Selesai Diimplementasikan -->
          <Link
            href="/dashboard/teknisi?view=bast"
            class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-emerald-300 hover:shadow-md transition-all group cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Total Selesai</span>
              <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <Icon icon="lucide:check-circle" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-emerald-600 mt-2">
              {{ hasilSelesai.length }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
              <span>Implementasi tuntas</span>
              <Icon icon="lucide:chevron-right" class="w-3 h-3 text-slate-400 group-hover:translate-x-0.5 transition-transform" />
            </div>
          </Link>

          <!-- Berita Acara (BAST) -->
          <Link
            href="/dashboard/teknisi?view=bast"
            class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-purple-300 hover:shadow-md transition-all group cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-purple-600 uppercase tracking-wide">Arsip BAST</span>
              <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <Icon icon="lucide:file-check" class="w-4 h-4" />
              </span>
            </div>
            <div class="text-3xl font-black text-purple-600 mt-2">
              {{ countBast }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
              <span>Bukti serah terima pekerjaan</span>
              <Icon icon="lucide:chevron-right" class="w-3 h-3 text-slate-400 group-hover:translate-x-0.5 transition-transform" />
            </div>
          </Link>
        </div>

        <!-- Banner Call-to-Action jika ada tugas aktif -->
        <div v-if="localAntrian.length > 0" class="p-5 rounded-2xl bg-gradient-to-r from-blue-900 via-[#0757A8] to-blue-800 text-white shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[10px] font-bold tracking-wide uppercase">
              <Icon icon="lucide:bell" class="w-3 h-3 text-amber-300" />
              <span>Perlu Tindakan Teknisi</span>
            </div>
            <h3 class="text-base sm:text-lg font-bold">
              Terdapat {{ localAntrian.length }} tugas implementasi perubahan TI menunggu pengerjaan Anda
            </h3>
            <p class="text-xs text-blue-100 max-w-2xl">
              Permohonan telah disahkan pimpinan dan siap untuk dieksekusi serta diunggah dokumen BAST-nya.
            </p>
          </div>
          <Link
            href="/dashboard/teknisi?view=tugas"
            class="px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-900 font-extrabold text-xs flex items-center justify-center gap-2 shadow-sm transition-all whitespace-nowrap"
          >
            <span>Buka Tugas Saya</span>
            <Icon icon="lucide:arrow-right" class="w-4 h-4" />
          </Link>
        </div>

        <!-- 2 Kolom: Riwayat Implementasi Selesai & Distribusi Kategori -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- 2 Kolom: Riwayat Implementasi Selesai & Distribusi Kategori -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- Kolom Kiri: Riwayat Implementasi Selesai (Lebar 2/3) -->
          <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">Riwayat Implementasi Selesai</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar permohonan yang telah selesai dikerjakan dan dituntaskan BAST-nya.</p>
              </div>
              <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                {{ hasilSelesai.length }} Selesai
              </span>
            </div>

            <!-- Tabel Riwayat Bersih -->
            <div v-if="hasilSelesai.length > 0" class="overflow-x-auto">
              <table class="w-full text-left text-xs border-collapse">
                <thead>
                  <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[10.5px]">
                    <th class="py-2.5 px-3">Kode RFC</th>
                    <th class="py-2.5 px-3">Perangkat Daerah</th>
                    <th class="py-2.5 px-3">Uraian Pekerjaan</th>
                    <th class="py-2.5 px-3">Tanggal Selesai</th>
                    <th class="py-2.5 px-3 text-right">Dokumen</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="r in hasilSelesai" :key="r.id" class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-3 font-mono font-bold text-[#0757A8] whitespace-nowrap">
                      {{ r.kode_pelacakan || '-' }}
                    </td>
                    <td class="py-3 px-3">
                      <div class="font-semibold text-slate-800 truncate max-w-[180px]" :title="r.opd">{{ r.opd || '-' }}</div>
                      <div class="text-[11px] text-slate-500 truncate max-w-[180px]">{{ r.nama_pemohon }}</div>
                    </td>
                    <td class="py-3 px-3 max-w-[220px]">
                      <div class="text-slate-700 line-clamp-2" :title="r.uraian_pekerjaan || r.deskripsi">
                        {{ r.uraian_pekerjaan || r.deskripsi || '-' }}
                      </div>
                    </td>
                    <td class="py-3 px-3 text-slate-600 whitespace-nowrap">
                      {{ r.selesai_pada || r.created_at || '-' }}
                    </td>
                    <td class="py-3 px-3 text-right whitespace-nowrap">
                      <div class="flex items-center justify-end gap-2">
                        <!-- FR-015 -->
                        <Link
                          v-if="r.kode_pelacakan"
                          :href="`/rfc/${r.kode_pelacakan}/dokumen`"
                          class="inline-flex items-center gap-1 text-[#0757A8] hover:underline font-bold"
                          title="Lihat FR-015"
                        >
                          <Icon icon="lucide:file-text" class="w-3.5 h-3.5" />
                          <span>FR-015</span>
                        </Link>

                        <!-- BAST Download jika ada -->
                        <a
                          v-if="r.path_file_bast"
                          :href="r.path_file_bast"
                          target="_blank"
                          class="inline-flex items-center gap-1 text-emerald-700 hover:underline font-bold"
                          title="Unduh BAST Resmi"
                        >
                          <Icon icon="lucide:download" class="w-3.5 h-3.5" />
                          <span>BAST</span>
                        </a>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-else class="text-center py-8 text-slate-400 text-xs">
              Belum ada riwayat implementasi RFC yang selesai.
            </div>
          </div>

          <!-- Kolom Kanan: Distribusi Kategori & Tata Kelola (Lebar 1/3) -->
          <div class="space-y-6">
            <!-- Distribusi Kategori TI -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
              <h2 class="text-base font-bold text-slate-800 pb-2 border-b border-slate-100">
                Distribusi Kategori Implementasi
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

            <!-- Standar Kepatuhan Teknis SMKI & BAST -->
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 space-y-3">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                  <Icon icon="lucide:shield-check" class="w-4 h-4" />
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-800">SMKI ISO/IEC 27001 & ITIL</div>
                  <div class="text-[10px] text-slate-500">Tata Kelola Implementasi Teknis</div>
                </div>
              </div>
              <p class="text-[11.5px] text-slate-600 leading-relaxed">
                Setiap pengerjaan perubahan wajib melewati pengujian kelayakan (*testing*), dokumentasi *rollback plan*, dan penerbitan Berita Acara Serah Terima (BAST) bertanda tangan digital.
              </p>
            </div>
          </div>

        </div>

      </div>

      <!-- ========================================================
           VIEW 2: TAMPILAN "TUGAS SAYA" (FILTERISASI & TABEL RAPI)
      ======================================================== -->
      <div v-else-if="currentView === 'tugas'" class="space-y-6">

        <!-- CARD FILTERISASI TUGAS TEKNISI -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5">
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Info Filter -->
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center flex-shrink-0">
                <Icon icon="lucide:sliders-horizontal" class="w-5 h-5" />
              </div>
              <div>
                <h3 class="text-sm font-extrabold text-slate-800">Filterisasi Tugas Implementasi</h3>
                <p class="text-xs text-slate-500">Saring tugas berdasarkan status, urgensi, atau pencarian</p>
              </div>
            </div>

            <!-- Kontrol Filter Form -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 flex-1 lg:max-w-3xl">
              <!-- Filter Status Pengerjaan -->
              <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Status Pengerjaan</label>
                <select
                  v-model="filterStatus"
                  class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all font-medium text-slate-700"
                >
                  <option value="semua">Semua Status</option>
                  <option value="disetujui">Siap Dikerjakan</option>
                  <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                </select>
              </div>

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

        <!-- TABEL TUGAS IMPLEMENTASI BERSIH & RAPI -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">

          <!-- Header Toolbar Tabel -->
          <div class="py-4 px-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <h2 class="text-base font-extrabold text-slate-900">Daftar Tugas Siap Dikerjakan</h2>
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                {{ filteredAntrian.length }} Tugas
              </span>
            </div>
            <div class="text-xs text-slate-500 hidden sm:block">
              Klik <strong>"Buka Workspace"</strong> untuk menginput hasil pengerjaan dan unggah BAST.
            </div>
          </div>

          <!-- TABEL RAPI, SIMETRIS & LEGA -->
          <div v-if="filteredAntrian.length > 0" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider">
                  <th class="py-3.5 px-4 w-12 text-center">No</th>
                  <th class="py-3.5 px-4 w-40">Kode RFC</th>
                  <th class="py-3.5 px-4 min-w-[160px]">Pemohon</th>
                  <th class="py-3.5 px-4 min-w-[260px]">Rincian Perubahan</th>
                  <th class="py-3.5 px-4 w-28 text-center">Urgensi</th>
                  <th class="py-3.5 px-4 w-36 text-center">Status</th>
                  <th class="py-3.5 px-4 w-52 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs">
                <tr
                  v-for="(t, index) in filteredAntrian"
                  :key="t.id"
                  class="hover:bg-blue-50/30 transition-colors"
                >
                  <!-- No -->
                  <td class="py-4 px-4 text-center text-slate-400 font-mono font-medium align-middle">
                    {{ index + 1 }}
                  </td>

                  <!-- Kode RFC -->
                  <td class="py-4 px-4 align-middle whitespace-nowrap">
                    <span class="font-mono text-xs font-bold text-[#0757A8] tracking-tight">
                      {{ t.kode_pelacakan }}
                    </span>
                  </td>

                  <!-- Pemohon -->
                  <td class="py-4 px-4 align-middle">
                    <div class="font-semibold text-slate-800 text-xs">
                      {{ t.nama_pemohon || '-' }}
                    </div>
                    <div class="text-[11px] text-slate-500 truncate max-w-[160px]" :title="t.opd">
                      {{ t.opd }}
                    </div>
                  </td>

                  <!-- Rincian Perubahan -->
                  <td class="py-4 px-4 align-middle">
                    <div class="text-xs text-slate-700 leading-relaxed font-normal" :title="t.deskripsi">
                      {{ t.deskripsi || '-' }}
                    </div>
                  </td>

                  <!-- Urgensi -->
                  <td class="py-4 px-4 align-middle text-center whitespace-nowrap">
                    <span
                      class="px-3 py-1 rounded-full text-[10px] font-extrabold text-white uppercase inline-block shadow-xs"
                      :class="getBadgeByUrgensi(t.urgensi)"
                    >
                      {{ t.urgensi_label || t.urgensi }}
                    </span>
                  </td>

                  <!-- Status Pengerjaan -->
                  <td class="py-4 px-4 align-middle text-center whitespace-nowrap">
                    <span
                      class="px-2.5 py-1 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5"
                      :class="statusClass(t.status_terkini)"
                    >
                      <Icon
                        :icon="t.status_terkini === 'sedang_dikerjakan' ? 'lucide:loader' : 'lucide:clock'"
                        class="w-3.5 h-3.5"
                        :class="{ 'animate-spin': t.status_terkini === 'sedang_dikerjakan' }"
                      />
                      <span>{{ t.status_terkini === 'sedang_dikerjakan' ? 'Sedang Dikerjakan' : 'Siap Dikerjakan' }}</span>
                    </span>
                  </td>

                  <!-- Tombol Aksi -->
                  <td class="py-4 px-4 align-middle text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-1.5">
                      <!-- Tombol Mulai Pengerjaan jika disetujui -->
                      <button
                        v-if="t.status_terkini === 'disetujui'"
                        type="button"
                        @click="mulaiEksekusi(t)"
                        :disabled="isSubmitting"
                        class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1 shadow-xs transition-all disabled:opacity-50"
                        title="Mulai pengerjaan tugas"
                      >
                        <Icon icon="lucide:play" class="w-3.5 h-3.5" />
                        <span>Mulai</span>
                      </button>

                      <!-- Tombol Workspace (Utama) -->
                      <Link
                        :href="`/rfc/${t.kode_pelacakan}/workspace`"
                        class="px-3 py-1.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold flex items-center gap-1 shadow-xs transition-all"
                        title="Buka Workspace untuk eksekusi & BAST"
                      >
                        <Icon icon="lucide:terminal" class="w-3.5 h-3.5" />
                        <span>Workspace</span>
                      </Link>

                      <!-- Tombol FR-015 -->
                      <Link
                        :href="`/rfc/${t.kode_pelacakan}/dokumen`"
                        class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-[#0757A8] transition-colors"
                        title="Lihat Dokumen Resmi FR-015"
                      >
                        <Icon icon="lucide:file-text" class="w-3.5 h-3.5" />
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
            <h3 class="font-extrabold text-slate-900 text-lg">Tidak ada tugas aktif</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
              {{ isFiltered ? 'Tidak ada tugas yang sesuai dengan filter yang dipilih.' : 'Semua permohonan RFC telah selesai dikerjakan atau belum ada permohonan yang disetujui pimpinan.' }}
            </p>
            <button v-if="isFiltered" type="button" @click="resetFilters" class="text-xs font-bold text-[#0757A8] hover:underline pt-2">
              Bersihkan Semua Filter
            </button>
          </div>

        </div>

      </div>

      <!-- ========================================================
           VIEW 3: TAMPILAN "ARSIP BAST" (TABEL ARSIP DOKUMEN BAST)
      ======================================================== -->
      <div v-else-if="currentView === 'bast'" class="space-y-6">

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">

          <!-- Header Toolbar Tabel BAST -->
          <div class="py-4 px-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <h2 class="text-base font-extrabold text-slate-900">Arsip Berita Acara Serah Terima (BAST)</h2>
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                {{ hasilSelesai.length }} Berkas
              </span>
            </div>
            <div class="text-xs text-slate-500 hidden sm:block">
              Berkas serah terima resmi bertanda tangan digital antara teknisi pelaksana dan pemohon.
            </div>
          </div>

          <div v-if="hasilSelesai.length > 0" class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider">
                  <th class="py-3.5 px-4 w-12 text-center">No</th>
                  <th class="py-3.5 px-4 w-40">Kode RFC</th>
                  <th class="py-3.5 px-4 min-w-[200px]">Perangkat Daerah & Pemohon</th>
                  <th class="py-3.5 px-4 min-w-[240px]">Nomor & Tanggal BAST</th>
                  <th class="py-3.5 px-4 min-w-[240px]">Hasil Pekerjaan</th>
                  <th class="py-3.5 px-4 w-44 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(r, idx) in hasilSelesai" :key="r.id" class="hover:bg-slate-50/70 transition-colors">
                  <!-- No -->
                  <td class="py-4 px-4 text-center text-slate-400 font-mono font-medium align-middle">
                    {{ idx + 1 }}
                  </td>

                  <!-- Kode RFC -->
                  <td class="py-4 px-4 align-middle whitespace-nowrap">
                    <span class="font-mono text-xs font-bold text-[#0757A8] tracking-tight">
                      {{ r.kode_pelacakan }}
                    </span>
                  </td>

                  <!-- Perangkat Daerah & Pemohon -->
                  <td class="py-4 px-4 align-middle">
                    <div class="font-bold text-slate-800 text-xs truncate max-w-[200px]" :title="r.opd">
                      {{ r.opd }}
                    </div>
                    <div class="text-[11px] text-slate-500 mt-0.5 truncate max-w-[200px]">
                      {{ r.nama_pemohon }}
                    </div>
                  </td>

                  <!-- Nomor Dokumen BAST & Tanggal -->
                  <td class="py-4 px-4 align-middle">
                    <div class="font-mono text-xs font-bold text-purple-800">
                      {{ r.nomor_dokumen_bast || 'BAST Telah Terbit' }}
                    </div>
                    <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                      <Icon icon="lucide:calendar" class="w-3.5 h-3.5 text-slate-400" />
                      <span>{{ r.tanggal_bast || r.selesai_pada || '-' }}</span>
                    </div>
                  </td>

                  <!-- Hasil Pekerjaan -->
                  <td class="py-4 px-4 align-middle">
                    <div class="text-slate-700 line-clamp-2" :title="r.uraian_pekerjaan || r.deskripsi">
                      {{ r.uraian_pekerjaan || r.deskripsi || '-' }}
                    </div>
                    <div class="text-[10px] text-emerald-700 font-semibold mt-1">
                      Pelaksana: {{ r.teknisi || 'Teknisi APTIKA' }}
                    </div>
                  </td>

                  <!-- Aksi -->
                  <td class="py-4 px-4 align-middle text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-2">
                      <!-- Unduh BAST -->
                      <a
                        v-if="r.path_file_bast"
                        :href="r.path_file_bast"
                        target="_blank"
                        class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold flex items-center gap-1 shadow-xs transition-all"
                        title="Unduh Berkas BAST PDF"
                      >
                        <Icon icon="lucide:download" class="w-3.5 h-3.5" />
                        <span>Unduh BAST</span>
                      </a>

                      <!-- Lihat FR-015 -->
                      <Link
                        :href="`/rfc/${r.kode_pelacakan}/dokumen`"
                        class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-[#0757A8] transition-colors"
                        title="Lihat FR-015"
                      >
                        <Icon icon="lucide:file-text" class="w-3.5 h-3.5" />
                      </Link>

                      <!-- Workspace RFC -->
                      <Link
                        :href="`/rfc/${r.kode_pelacakan}/workspace`"
                        class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-[#0757A8] transition-colors"
                        title="Buka Workspace RFC"
                      >
                        <Icon icon="lucide:terminal" class="w-3.5 h-3.5" />
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="p-16 text-center space-y-3">
            <div class="w-14 h-14 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mx-auto">
              <Icon icon="lucide:archive" class="w-7 h-7" />
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg">Belum ada arsip BAST</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
              Dokumen BAST akan terdaftar di sini secara otomatis setelah Anda menyelesaikan pekerjaan dan mengunggah berkas BAST di ruang kerja.
            </p>
          </div>

        </div>

      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'

// ── Props (match controller: dashboardTeknisi) ────────────────────────────────
const props = defineProps({
  antrian:            { type: Array,  default: () => [] },
  hasilSelesai:       { type: Array,  default: () => [] },
  distribusiKategori: { type: Array,  default: () => [] },
  statistik:          { type: Object, default: null },
  initialView:        { type: String, default: 'ringkasan' },
})

// ── View Detection (Ringkasan vs Tugas vs BAST) ────────────────────────────────
const page = usePage()

const currentView = computed(() => {
  const url = page.url || ''
  if (url.includes('view=tugas')) return 'tugas'
  if (url.includes('view=bast')) return 'bast'
  if (url.includes('view=ringkasan')) return 'ringkasan'
  return props.initialView || 'ringkasan'
})

// ── State ─────────────────────────────────────────────────────────────────────
const isSubmitting   = ref(false)
const actionMsg      = ref(null)
const search         = ref('')
const filterStatus   = ref('semua')
const filterUrgensi  = ref('semua')
const filterKategori = ref('semua')

const localAntrian = ref([...props.antrian])
const kategoriList = ref([...props.distribusiKategori])

watch(() => props.antrian, v => { localAntrian.value = [...(v || [])] })
watch(() => props.distribusiKategori, v => { kategoriList.value = [...(v || [])] })

const countBast = computed(() => {
  return props.hasilSelesai.filter(r => r.nomor_dokumen_bast || r.path_file_bast).length
})

const totalKategoriRfc = computed(() => {
  return kategoriList.value.reduce((acc, k) => acc + (k.permohonan_count || 0), 0)
})

// ── Filterisasi Tugas Saya ────────────────────────────────────────────────────
const isFiltered = computed(() => {
  return filterStatus.value !== 'semua' ||
         filterUrgensi.value !== 'semua' ||
         filterKategori.value !== 'semua' ||
         (search.value && search.value.trim() !== '')
})

function resetFilters() {
  filterStatus.value   = 'semua'
  filterUrgensi.value  = 'semua'
  filterKategori.value = 'semua'
  search.value         = ''
}

const filteredAntrian = computed(() => {
  return localAntrian.value.filter(t => {
    // Filter status
    if (filterStatus.value !== 'semua' && t.status_terkini !== filterStatus.value) {
      return false
    }

    // Filter urgensi
    if (filterUrgensi.value !== 'semua' && (t.urgensi || '').toLowerCase() !== filterUrgensi.value.toLowerCase()) {
      return false
    }

    // Filter kategori
    if (filterKategori.value !== 'semua') {
      const kat = t.kategori || ''
      if (kat.toLowerCase() !== filterKategori.value.toLowerCase()) {
        return false
      }
    }

    // Search query
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

// ── Actions ───────────────────────────────────────────────────────────────────
async function mulaiEksekusi(rfc) {
  isSubmitting.value = true
  try {
    await axios.post(`/rfc/${rfc.id}/mulai-eksekusi`, { catatan_eksekusi: 'Pengerjaan dimulai oleh teknisi' })
    actionMsg.value = `Pengerjaan untuk ${rfc.kode_pelacakan} berhasil dimulai.`
    // Update status lokal seketika
    const idx = localAntrian.value.findIndex(r => r.id === rfc.id)
    if (idx !== -1) {
      localAntrian.value[idx] = {
        ...localAntrian.value[idx],
        status_terkini: 'sedang_dikerjakan',
        status_label: 'Sedang Dikerjakan'
      }
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal memulai pengerjaan.')
  } finally {
    isSubmitting.value = false
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function getBadgeByUrgensi(urgensi) {
  return {
    darurat: 'bg-red-700',
    tinggi:  'bg-amber-500',
    sedang:  'bg-blue-600',
    rendah:  'bg-emerald-600'
  }[urgensi] ?? 'bg-slate-500'
}

function statusClass(status) {
  return {
    disetujui:         'bg-purple-100 text-purple-800',
    sedang_dikerjakan: 'bg-cyan-100 text-cyan-800',
    selesai:           'bg-emerald-100 text-emerald-800',
  }[status] ?? 'bg-slate-100 text-slate-700'
}
</script>
