<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto px-4 py-6 sm:py-10">

      <!-- Kembali -->
      <Link href="/dashboard"
        class="inline-flex items-center gap-2 text-xs font-semibold text-[#0757A8] hover:underline mb-5">
        <Icon icon="lucide:arrow-left" class="w-4 h-4" />
        <span>Kembali ke Dashboard</span>
      </Link>

      <!-- Alert -->
      <Transition name="flash-fade">
        <div v-if="alertMsg" :class="['mb-6 p-4 rounded-xl text-sm font-medium flex items-center justify-between',
          alertType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200']">
          <div class="flex items-center gap-2">
            <Icon :icon="alertType === 'success' ? 'lucide:check-circle' : 'lucide:alert-circle'" class="w-4 h-4 flex-shrink-0" />
            <span>{{ alertMsg }}</span>
          </div>
          <button @click="alertMsg = null" class="text-current opacity-60 hover:opacity-100 ml-4">✕</button>
        </div>
      </Transition>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- SIDEBAR RINGKASAN RFC (4 Kolom) -->
        <div class="lg:col-span-4 space-y-4">
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sticky top-6">

            <!-- Kode & Status -->
            <div class="flex items-center justify-between gap-2 mb-3">
              <span class="font-mono text-xl font-black text-[#0757a8]">{{ rfc.kode_pelacakan }}</span>
              <span :class="['px-2.5 py-1 rounded-full text-[10px] font-bold', rfc.warna_badge]">{{ rfc.status_label }}</span>
            </div>

            <div class="flex items-center gap-2 mb-4">
              <span class="px-2 py-0.5 rounded text-xs font-bold text-white" :class="getBadgeByUrgensi(rfc.urgensi)">
                {{ rfc.urgensi_label }}
              </span>
            </div>

            <!-- Info -->
            <div class="space-y-3 text-xs border-t border-slate-100 pt-4">
              <div>
                <span class="text-slate-400 block mb-0.5">Pemohon:</span>
                <span class="font-semibold text-slate-800">{{ rfc.nama_pemohon }}</span>
              </div>
              <div>
                <span class="text-slate-400 block mb-0.5">OPD:</span>
                <span class="font-medium text-slate-700">{{ rfc.opd }}</span>
              </div>
              <div v-if="rfc.nomor_kontak">
                <span class="text-slate-400 block mb-0.5">Kontak:</span>
                <span class="text-slate-700">{{ rfc.nomor_kontak }}</span>
              </div>
              <div v-if="rfc.jenis_perubahan && rfc.jenis_perubahan.length">
                <span class="text-slate-400 block mb-0.5">Jenis Perubahan:</span>
                <div class="flex flex-wrap gap-1 mt-1">
                  <span v-for="j in rfc.jenis_perubahan" :key="j"
                    class="bg-blue-50 text-[#0757a8] px-2 py-0.5 rounded text-[10px] font-bold">{{ j }}</span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
              <Link :href="`/rfc/${rfc.kode_pelacakan}`"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition-colors">
                <Icon icon="lucide:file-text" class="w-4 h-4 text-[#0757A8]" />
                <span>Buka Detail FR-015</span>
              </Link>
              <Link :href="`/rfc/${rfc.kode_pelacakan}/dokumen`"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-[#0757a8] font-bold text-xs transition-colors">
                <Icon icon="lucide:printer" class="w-4 h-4" />
                <span>Dokumen Cetak FR-015</span>
              </Link>
            </div>
          </div>
        </div>

        <!-- AREA KERJA WORKSPACE (8 Kolom) -->
        <div class="lg:col-span-8 space-y-5">

          <!-- Deskripsi perubahan -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h3 class="text-sm font-bold text-slate-800 mb-3">Isi Permohonan FR-015</h3>
            <div class="space-y-3 text-xs text-slate-700">
              <div v-if="rfc.deskripsi">
                <span class="font-bold text-slate-500 uppercase text-[10px]">Deskripsi Perubahan:</span>
                <p class="mt-1 leading-relaxed">{{ rfc.deskripsi }}</p>
              </div>
              <div v-if="rfc.latar_belakang">
                <span class="font-bold text-slate-500 uppercase text-[10px]">Latar Belakang:</span>
                <p class="mt-1 leading-relaxed">{{ rfc.latar_belakang }}</p>
              </div>
              <div v-if="rfc.rincian_perubahan">
                <span class="font-bold text-slate-500 uppercase text-[10px]">Rincian Perubahan:</span>
                <p class="mt-1 leading-relaxed">{{ rfc.rincian_perubahan }}</p>
              </div>
              <div v-if="rfc.solusi_diharapkan">
                <span class="font-bold text-slate-500 uppercase text-[10px]">Solusi Diharapkan:</span>
                <p class="mt-1 leading-relaxed">{{ rfc.solusi_diharapkan }}</p>
              </div>
            </div>
          </div>

          <!-- NAVIGASI TAB WORKFLOW -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-2 flex flex-wrap gap-1.5">
            <button v-for="tab in visibleTabs" :key="tab.id" @click="activeTab = tab.id"
              :class="['px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2',
                activeTab === tab.id ? 'bg-[#0757a8] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100']">
              <Icon :icon="tab.icon" class="w-4 h-4" />
              <span>{{ tab.name }}</span>
              <span v-if="tab.active" class="w-2 h-2 rounded-full" :class="tab.dotColor"></span>
            </button>
          </div>

          <!-- === TAB: INFORMASI === -->
          <div v-if="activeTab === 'info'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3 text-xs">
            <h2 class="text-base font-bold text-slate-800 pb-2 border-b border-slate-100">Riwayat Aktivitas</h2>
            <div v-if="!histories || histories.length === 0" class="py-6 text-center text-slate-400">
              <Icon icon="lucide:clock" class="w-6 h-6 mx-auto mb-2 text-slate-300" />
              <p>Belum ada riwayat aktivitas.</p>
            </div>
            <div v-else class="space-y-3">
              <div v-for="h in histories" :key="h.id" class="flex gap-3">
                <div :class="['w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold', roleColor(h.peran_aktor)]">
                  {{ (h.nama_aktor || '?').charAt(0).toUpperCase() }}
                </div>
                <div class="flex-1">
                  <div class="text-sm font-semibold text-slate-800">{{ h.nama_aktor }}
                    <span class="font-normal text-slate-500 text-xs">({{ h.peran_aktor }})</span>
                  </div>
                  <div class="text-xs text-slate-700">{{ h.keterangan }}</div>
                  <div v-if="h.catatan" class="text-xs text-slate-500 italic mt-0.5">{{ h.catatan }}</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">{{ h.created_at }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- === TAB: VERIFIKASI (Verifikator) === -->
          <div v-if="activeTab === 'verifikasi'" class="bg-white rounded-2xl border-t-4 border-purple-600 border border-slate-200 shadow-xs p-5">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">Verifikasi Kelayakan</h2>
                <p class="text-slate-500 text-xs mt-0.5">Periksa kelengkapan & berikan rekomendasi ke pimpinan</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-purple-100 text-purple-800">Koordinator Agen</span>
            </div>

            <!-- Info isian pemohon -->
            <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-200 space-y-2 text-xs">
              <div><span class="font-bold text-slate-600">Kriteria Risiko:</span>
                <span class="ml-1 text-amber-700 font-semibold">{{ rfc.kriteria_risiko || 'Tidak diisi' }}</span>
              </div>
              <div v-if="rfc.keterangan_risiko">
                <span class="font-bold text-slate-600">Keterangan Risiko:</span>
                <p class="mt-1 text-slate-600 leading-relaxed">{{ rfc.keterangan_risiko }}</p>
              </div>
            </div>

            <!-- Sudah diverifikasi sebelumnya? -->
            <div v-if="rfc.revisi && rfc.revisi.length > 0 && rfc.status_terkini !== 'diajukan'"
              class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
              <div class="font-bold flex items-center gap-1.5 mb-1">
                <Icon icon="lucide:check-circle" class="w-4 h-4" />
                Sudah diverifikasi sebelumnya
              </div>
              <p>{{ rfc.revisi[0]?.catatan_revisi }}</p>
            </div>

            <form v-if="izin.bisa_telaah" @submit.prevent="submitVerifikasi" class="space-y-4">
              <!-- Dampak & Urgensi -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Penilaian Dampak <span class="text-red-500">*</span></label>
                  <select v-model="formVerif.dampak" required
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none">
                    <option value="" disabled>Pilih dampak...</option>
                    <option value="tinggi">Tinggi - Memengaruhi pelayanan publik provinsi</option>
                    <option value="sedang">Sedang - Memengaruhi beberapa OPD</option>
                    <option value="rendah">Rendah - Sistem internal unit kerja tunggal</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Penilaian Urgensi <span class="text-red-500">*</span></label>
                  <select v-model="formVerif.urgensi_penilaian" required
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none">
                    <option value="" disabled>Pilih urgensi...</option>
                    <option value="kritis">Kritis - Downtime / celah keamanan</option>
                    <option value="tinggi">Tinggi - Dibutuhkan siklus operasional berikutnya</option>
                    <option value="sedang">Sedang - Perbaikan fungsional</option>
                    <option value="rendah">Rendah - Enhancement minor</option>
                  </select>
                </div>
              </div>

              <!-- Catatan -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Verifikasi <span class="text-red-500">*</span></label>
                <textarea v-model="formVerif.catatan_verifikasi" rows="3" required
                  placeholder="Hasil telaah kelengkapan berkas dan catatan kelayakan..."
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none" />
              </div>

              <!-- Rekomendasi -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Rekomendasi <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                  <label v-for="opt in rekomendasiOptions" :key="opt.value"
                    :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all',
                      formVerif.rekomendasi === opt.value ? opt.activeClass : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" :value="opt.value" v-model="formVerif.rekomendasi" class="sr-only" />
                    <Icon :icon="opt.icon" class="w-6 h-6 mb-1" :class="opt.iconClass" />
                    <span class="font-bold text-xs">{{ opt.label }}</span>
                    <span class="text-[10px] text-slate-500">{{ opt.desc }}</span>
                  </label>
                </div>
              </div>

              <!-- Catatan revisi jika perlu revisi -->
              <div v-if="formVerif.rekomendasi === 'perlu_revisi'">
                <label class="block text-xs font-bold text-amber-800 mb-1">Instruksi Perbaikan <span class="text-red-500">*</span></label>
                <textarea v-model="formVerif.catatan_revisi_spesifik" rows="3" required
                  placeholder="Sebutkan field yang harus diperbaiki pemohon..."
                  class="w-full text-xs px-3 py-2 bg-white border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-400/30 outline-none" />
              </div>

              <div class="flex justify-end pt-2">
                <button type="submit" :disabled="isSubmitting"
                  class="px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs disabled:opacity-50">
                  <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                  <Icon v-else icon="lucide:send" class="w-4 h-4" />
                  <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Keputusan Verifikasi' }}</span>
                </button>
              </div>
            </form>

            <!-- Readonly jika bukan verifikator atau status sudah lewat -->
            <div v-else class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-center">
              <Icon icon="lucide:lock" class="w-5 h-5 mx-auto mb-1 text-slate-400" />
              <p>{{ rfc.status_terkini === 'diajukan' ? 'Hanya verifikator yang dapat mengisi tahap ini.' : 'Tahap verifikasi sudah selesai.' }}</p>
            </div>
          </div>

          <!-- === TAB: KEPUTUSAN PIMPINAN === -->
          <div v-if="activeTab === 'pimpinan'" class="bg-white rounded-2xl border-t-4 border-[#0757a8] border border-slate-200 shadow-xs p-5">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">Keputusan Pimpinan</h2>
                <p class="text-slate-500 text-xs mt-0.5">Persetujuan strategis & penugasan teknisi</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-blue-100 text-[#0757a8]">Penanggung Jawab</span>
            </div>

            <!-- Rekomendasi verifikator -->
            <div v-if="rfc.revisi && rfc.revisi.length > 0" class="mb-4 p-4 rounded-xl bg-purple-50 border border-purple-200 text-xs space-y-1">
              <div class="font-bold text-purple-800 flex items-center gap-1.5">
                <Icon icon="lucide:message-circle" class="w-4 h-4" />
                Rekomendasi Verifikator
              </div>
              <p class="text-purple-900/90 leading-relaxed">{{ rfc.revisi[0]?.catatan_revisi }}</p>
            </div>

            <form v-if="izin.bisa_keputusan" @submit.prevent="submitKeputusan" class="space-y-4">
              <!-- Pilihan -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Keputusan <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                  <label v-for="opt in keputusanOptions" :key="opt.value"
                    :class="['border-2 rounded-xl p-3 flex flex-col items-center cursor-pointer text-center transition-all',
                      formPimpinan.keputusan === opt.value ? opt.activeClass : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" :value="opt.value" v-model="formPimpinan.keputusan" class="sr-only" />
                    <Icon :icon="opt.icon" class="w-6 h-6 mb-1" :class="opt.iconClass" />
                    <span class="font-bold text-xs">{{ opt.label }}</span>
                    <span class="text-[10px] text-slate-500">{{ opt.desc }}</span>
                  </label>
                </div>
              </div>

              <!-- Teknisi (jika disetujui) -->
              <div v-if="formPimpinan.keputusan === 'disetujui'" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Teknisi Pelaksana <span class="text-red-500">*</span></label>
                  <select v-model="formPimpinan.teknisi_id" required
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0757A8]/20 outline-none">
                    <option value="" disabled>Pilih teknisi...</option>
                    <option v-for="t in teknisiList" :key="t.id" :value="t.id">{{ t.nama }}</option>
                  </select>
                </div>
              </div>

              <!-- Jadwal tinjau (jika ditunda) -->
              <div v-if="formPimpinan.keputusan === 'ditunda'">
                <label class="block text-xs font-bold text-slate-700 mb-1">Jadwal Tinjau Ulang <span class="text-red-500">*</span></label>
                <input v-model="formPimpinan.jadwal_tinjau_ulang" type="date" required
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 outline-none" />
              </div>

              <!-- Catatan -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                  Catatan Pimpinan
                  <span v-if="formPimpinan.keputusan !== 'disetujui'" class="text-red-500">*</span>
                </label>
                <textarea v-model="formPimpinan.catatan_keputusan" rows="3"
                  :required="formPimpinan.keputusan !== 'disetujui'"
                  placeholder="Arahan, instruksi, atau alasan keputusan..."
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0757A8]/20 outline-none" />
              </div>

              <div class="flex justify-end pt-2">
                <button type="submit" :disabled="isSubmitting"
                  class="px-5 py-2.5 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs disabled:opacity-50">
                  <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                  <span>{{ isSubmitting ? 'Menyimpan...' : 'Sahkan Keputusan' }}</span>
                </button>
              </div>
            </form>

            <div v-else class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-center">
              <Icon icon="lucide:lock" class="w-5 h-5 mx-auto mb-1 text-slate-400" />
              <p>{{ rfc.status_terkini === 'menunggu_persetujuan' ? 'Hanya pimpinan yang dapat mengisi tahap ini.' : 'Tahap keputusan sudah selesai.' }}</p>
              <!-- Tampilkan hasil keputusan jika sudah ada -->
              <div v-if="rfc.persetujuan" class="mt-3 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-left">
                <div class="font-bold text-emerald-800">
                  Keputusan: {{ rfc.persetujuan.keputusan }}
                </div>
                <p v-if="rfc.persetujuan.catatan_keputusan" class="text-emerald-700 mt-1">{{ rfc.persetujuan.catatan_keputusan }}</p>
              </div>
            </div>
          </div>

          <!-- === TAB: EKSEKUSI TEKNISI === -->
          <div v-if="activeTab === 'eksekusi'" class="bg-white rounded-2xl border-t-4 border-cyan-600 border border-slate-200 shadow-xs p-5">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">Mulai Pengerjaan</h2>
                <p class="text-slate-500 text-xs mt-0.5">Konfirmasi mulai implementasi RFC ini</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-cyan-100 text-cyan-800">Teknisi</span>
            </div>

            <form v-if="izin.bisa_eksekusi" @submit.prevent="submitEksekusi" class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Awal</label>
                <textarea v-model="formEksekusi.catatan_eksekusi" rows="3"
                  placeholder="Rencana implementasi atau catatan teknis awal..."
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-400/30 outline-none" />
              </div>
              <div class="flex justify-end">
                <button type="submit" :disabled="isSubmitting"
                  class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-xs disabled:opacity-50">
                  <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                  <Icon v-else icon="lucide:play" class="w-4 h-4" />
                  <span>{{ isSubmitting ? 'Memproses...' : 'Mulai Pengerjaan' }}</span>
                </button>
              </div>
            </form>

            <div v-else class="p-4 rounded-xl bg-slate-50 border text-xs text-slate-600 text-center">
              <Icon icon="lucide:lock" class="w-5 h-5 mx-auto mb-1 text-slate-400" />
              <p>{{ rfc.status_terkini === 'disetujui' ? 'Hanya teknisi yang ditugaskan yang dapat memulai.' : 'Tahap ini sudah dimulai.' }}</p>
            </div>
          </div>

          <!-- === TAB: BAST === -->
          <div v-if="activeTab === 'bast'" class="bg-white rounded-2xl border-t-4 border-emerald-600 border border-slate-200 shadow-xs p-5">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">BAST & Penutupan Tiket</h2>
                <p class="text-slate-500 text-xs mt-0.5">Unggah BAST resmi untuk menutup RFC menjadi Selesai</p>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800">Agen</span>
            </div>

            <form v-if="izin.bisa_bast" @submit.prevent="submitBast" class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Nomor BAST <span class="text-red-500">*</span></label>
                  <input v-model="formBast.nomor_dokumen" type="text" required
                    :placeholder="`001/BAST-RFC/APTIKA/${new Date().getFullYear()}`"
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400/30 outline-none" />
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">File BAST (PDF) <span class="text-red-500">*</span></label>
                <input type="file" @change="e => fileBast = e.target.files[0]" accept=".pdf"
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg" />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Serah Terima</label>
                <textarea v-model="formBast.catatan" rows="3"
                  placeholder="Pernyataan hasil pekerjaan telah diserahkan dan diterima..."
                  class="w-full text-xs px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 outline-none" />
              </div>

              <div class="flex justify-end">
                <button type="submit" :disabled="isSubmitting"
                  class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-2 shadow-xs disabled:opacity-50">
                  <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
                  <Icon v-else icon="lucide:check-circle" class="w-4 h-4" />
                  <span>{{ isSubmitting ? 'Memproses...' : 'Terbitkan BAST & Tutup RFC' }}</span>
                </button>
              </div>
            </form>

            <div v-else class="p-4 rounded-xl bg-slate-50 border text-xs text-slate-600 text-center">
              <Icon icon="lucide:lock" class="w-5 h-5 mx-auto mb-1 text-slate-400" />
              <p v-if="rfc.status_terkini === 'selesai'" class="text-emerald-700 font-semibold">RFC ini telah selesai. BAST sudah diterbitkan.</p>
              <p v-else>Tahap BAST hanya tersedia saat RFC sedang dikerjakan.</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'
import SlaTimer from '@/Components/SlaTimer.vue'

// ── Props (match controller: workspace) ──────────────────────────────────────
const props = defineProps({
  rfc:      { type: Object, required: true },
  histories:{ type: Array,  default: () => [] },
  izin:     { type: Object, default: () => ({
    bisa_telaah: false, bisa_keputusan: false, bisa_eksekusi: false, bisa_bast: false,
  })},
})

// ── State ─────────────────────────────────────────────────────────────────────
const isSubmitting = ref(false)
const alertMsg     = ref(null)
const alertType    = ref('success')
const fileBast     = ref(null)
const teknisiList  = ref([])

// Tentukan tab aktif awal berdasarkan status
function tabFromStatus(status) {
  if (status === 'diajukan') return 'verifikasi'
  if (status === 'menunggu_persetujuan') return 'pimpinan'
  if (status === 'disetujui') return 'eksekusi'
  if (status === 'sedang_dikerjakan') return 'bast'
  return 'info'
}

const urlTab = typeof window !== 'undefined' ? new URLSearchParams(window.location.search).get('tab') : null
const activeTab = ref(urlTab || tabFromStatus(props.rfc.status_terkini))

// Form states
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
})

const formEksekusi = reactive({ catatan_eksekusi: '' })
const formBast = reactive({ nomor_dokumen: '', catatan: '' })

// ── Teknisi list ──────────────────────────────────────────────────────────────
onMounted(async () => {
  try {
    const res = await axios.get('/api/teknisi-list')
    teknisiList.value = res.data
  } catch {}
})

// ── Tab visibility ────────────────────────────────────────────────────────────
const visibleTabs = computed(() => {
  const all = [
    { id: 'info', name: 'Riwayat', icon: 'lucide:history', active: false, dotColor: '' },
    { id: 'verifikasi', name: '2. Verifikasi', icon: 'lucide:shield-check',
      active: props.rfc.status_terkini === 'diajukan', dotColor: 'bg-purple-500' },
    { id: 'pimpinan', name: '3. Keputusan', icon: 'lucide:scale',
      active: props.rfc.status_terkini === 'menunggu_persetujuan', dotColor: 'bg-blue-500' },
    { id: 'eksekusi', name: '4. Eksekusi', icon: 'lucide:code-2',
      active: props.rfc.status_terkini === 'disetujui', dotColor: 'bg-cyan-500' },
    { id: 'bast', name: '5. BAST', icon: 'lucide:file-check',
      active: props.rfc.status_terkini === 'sedang_dikerjakan', dotColor: 'bg-emerald-500' },
  ]
  return all
})

// ── Submit handlers ───────────────────────────────────────────────────────────
async function submitVerifikasi() {
  isSubmitting.value = true
  try {
    await axios.post(`/rfc/${props.rfc.id}/telaah`, {
      rekomendasi:    formVerif.rekomendasi,          // 'lanjut_pimpinan' | 'perlu_revisi' | 'tolak'
      catatan_revisi: formVerif.rekomendasi === 'perlu_revisi'
        ? formVerif.catatan_revisi_spesifik
        : formVerif.catatan_verifikasi,
    })
    showAlert('Keputusan verifikasi berhasil disimpan.', 'success')
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
    })
    showAlert('Keputusan pimpinan berhasil disimpan.', 'success')
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
    setTimeout(() => router.reload(), 1200)
  } catch (err) {
    showAlert(err.response?.data?.message || 'Gagal memulai eksekusi.', 'error')
  } finally {
    isSubmitting.value = false
  }
}

async function submitBast() {
  if (!fileBast.value) {
    showAlert('File BAST harus diunggah.', 'error')
    return
  }
  isSubmitting.value = true
  try {
    const fd = new FormData()
    fd.append('nomor_dokumen', formBast.nomor_dokumen)
    fd.append('catatan', formBast.catatan || '')
    fd.append('file_bast', fileBast.value)

    await axios.post(`/rfc/${props.rfc.id}/bast`, fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    showAlert('BAST berhasil diterbitkan. RFC ditutup menjadi SELESAI.', 'success')
    setTimeout(() => router.reload(), 1500)
  } catch (err) {
    showAlert(err.response?.data?.message || 'Gagal menerbitkan BAST.', 'error')
  } finally {
    isSubmitting.value = false
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
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
  { value: 'lanjut_pimpinan', label: 'Lanjut Pimpinan', desc: 'Berkas layak', icon: 'lucide:arrow-right-circle', iconClass: 'text-emerald-600', activeClass: 'border-emerald-500 bg-emerald-50 text-emerald-800' },
  { value: 'perlu_revisi',   label: 'Minta Revisi',   desc: 'Kembalikan ke pemohon', icon: 'lucide:edit-3', iconClass: 'text-amber-600', activeClass: 'border-amber-500 bg-amber-50 text-amber-800' },
  { value: 'tolak',          label: 'Tolak',           desc: 'Tidak layak', icon: 'lucide:x-circle', iconClass: 'text-red-600', activeClass: 'border-red-500 bg-red-50 text-red-800' },
]

const keputusanOptions = [
  { value: 'disetujui', label: 'Setujui', desc: 'Lanjut ke teknisi', icon: 'lucide:check-circle-2', iconClass: 'text-emerald-600', activeClass: 'border-emerald-500 bg-emerald-50 text-emerald-800' },
  { value: 'ditunda',   label: 'Tunda',   desc: 'Jadwal nanti',       icon: 'lucide:clock',           iconClass: 'text-amber-600',   activeClass: 'border-amber-500 bg-amber-50 text-amber-800' },
  { value: 'ditolak',   label: 'Tolak',   desc: 'RFC dibatalkan',     icon: 'lucide:x-circle',        iconClass: 'text-red-600',     activeClass: 'border-red-500 bg-red-50 text-red-800' },
]
</script>

<style scoped>
.flash-fade-enter-active, .flash-fade-leave-active { transition: all 0.3s ease; }
.flash-fade-enter-from, .flash-fade-leave-to { opacity: 0; transform: translateY(-8px); }
.card { background: white; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
</style>
