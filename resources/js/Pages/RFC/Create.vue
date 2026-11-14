<template>
  <AppLayout>
    <div class="min-h-screen bg-slate-50 pb-16">

      <!-- Page Header -->
      <div class="bg-white border-b border-slate-200 px-4 py-3">
        <div class="max-w-5xl mx-auto">
          <nav class="flex items-center gap-1 text-xs text-slate-500 mb-2">
            <Link href="/dashboard" class="hover:text-[#0757a8]">Beranda</Link>
            <span>�</span>
            <Link href="/dashboard/pemohon" class="hover:text-[#0757a8]">Dashboard</Link>
            <span>�</span>
            <span class="text-slate-700 font-medium">Formulir Permohonan Baru</span>
          </nav>
          <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="inline-flex items-center gap-1.5 bg-[#0757a8] text-white text-xs font-semibold px-3 py-1 rounded-full tracking-wide">
              FORMULIR PERUBAHAN TI � FR-015 � SMKI ISO/IEC 27001
            </span>
          </div>
          <h1 class="text-xl font-bold text-slate-900">
            {{ mode === 'edit' ? 'Edit Permohonan RFC' : 'Formulir Permohonan Request for Change (RFC)' }}
          </h1>
          <p class="text-sm text-slate-500 mt-0.5">Diskominfo Prov. Jawa Barat � Bidang APTIKA</p>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="max-w-5xl mx-auto px-4 mt-6 space-y-6">

        <!-- Error umum -->
        <div v-if="errors._general" class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
          <Icon icon="lucide:alert-circle" class="w-4 h-4 flex-shrink-0" />
          <span>{{ errors._general }}</span>
        </div>

        <!-- -------------------------------------------------------
             SEKSI 1 � INFORMASI PEMOHON (BIRU)
        ------------------------------------------------------- -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#0757A8] px-6 py-4 bg-blue-50/60">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#0757A8] text-white text-xs flex items-center justify-center font-bold flex-shrink-0">1</span>
              Informasi Pemohon
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Data diri pemohon perubahan sistem</p>
          </div>
          <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap Pemohon <span class="text-red-500">*</span></label>
              <input v-model="form.nama_pemohon" type="text" placeholder="Masukkan nama lengkap" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
              <p v-if="errors.nama_pemohon" class="mt-1 text-xs text-red-500">{{ errors.nama_pemohon }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">NIP <span class="text-red-500">*</span></label>
              <input v-model="form.nip" type="text" placeholder="Nomor Induk Pegawai" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
              <p v-if="errors.nip" class="mt-1 text-xs text-red-500">{{ errors.nip }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Unit Kerja / Bidang <span class="text-red-500">*</span></label>
              <input v-model="form.unit_kerja" type="text" placeholder="Nama unit kerja atau bidang" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
              <p v-if="errors.unit_kerja" class="mt-1 text-xs text-red-500">{{ errors.unit_kerja }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Perangkat Daerah / OPD <span class="text-red-500">*</span></label>
              <!-- Jika user sudah terdaftar di OPD, tampilkan readonly -->
              <div v-if="opdUser" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700">
                <Icon icon="lucide:building-2" class="w-4 h-4 text-slate-400 flex-shrink-0" />
                <span>{{ opdUser.nama_opd }}</span>
                <span class="ml-auto text-[10px] font-semibold text-slate-400 bg-slate-200 px-1.5 py-0.5 rounded">Auto</span>
              </div>
              <!-- Jika belum terdaftar OPD, tampilkan dropdown -->
              <select v-else v-model="form.opd_id" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] bg-white transition">
                <option value="" disabled>-- Pilih OPD --</option>
                <option v-for="opd in opdList" :key="opd.id" :value="opd.id">{{ opd.nama_opd }}</option>
              </select>
              <p v-if="errors.opd_id" class="mt-1 text-xs text-red-500">{{ errors.opd_id }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Surat Kedinasan</label>
              <input v-model="form.nomor_surat" type="text" placeholder="Contoh: 005/SURAT/OPD/2026"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Contact Person <span class="text-red-500">*</span></label>
              <input v-model="form.nomor_kontak" type="text" placeholder="No. HP / WhatsApp" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
              <p v-if="errors.nomor_kontak" class="mt-1 text-xs text-red-500">{{ errors.nomor_kontak }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Email Dinas Resmi <span class="text-red-500">*</span></label>
              <input v-model="form.email_dinas" type="email" placeholder="email@jabarprov.go.id" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
              <p v-if="errors.email_dinas" class="mt-1 text-xs text-red-500">{{ errors.email_dinas }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Permohonan <span class="text-red-500">*</span></label>
              <input v-model="form.tanggal_permohonan" type="date" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
              <p v-if="errors.tanggal_permohonan" class="mt-1 text-xs text-red-500">{{ errors.tanggal_permohonan }}</p>
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             SEKSI 2 � KATEGORI & JENIS PERUBAHAN (HIJAU)
        ------------------------------------------------------- -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#16834A] px-6 py-4 bg-green-50/60">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#16834A] text-white text-xs flex items-center justify-center font-bold flex-shrink-0">2</span>
              Kategori &amp; Jenis Perubahan
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Tentukan domain teknis dan tipe perubahan yang dimohonkan</p>
          </div>
          <div class="px-6 py-5 space-y-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Kategori RFC <span class="text-red-500">*</span></label>
              <select v-model="form.kategori_id" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#16834a]/30 focus:border-[#16834a] bg-white transition">
                <option value="" disabled>-- Pilih Kategori --</option>
                <option v-for="k in kategoriList" :key="k.id" :value="k.id">{{ k.nama_kategori }}</option>
              </select>
              <p v-if="errors.kategori_id" class="mt-1 text-xs text-red-500">{{ errors.kategori_id }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Perubahan <span class="text-red-500">*</span></label>
              <div class="flex flex-col sm:flex-row gap-3">
                <label v-for="jenis in jenisOptions" :key="jenis.value"
                  class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border-2 transition-all flex-1"
                  :class="form.jenis_perubahan.includes(jenis.value) ? 'border-[#16834a] bg-green-50' : 'border-slate-200 hover:border-slate-300'">
                  <input type="checkbox" :value="jenis.value" v-model="form.jenis_perubahan"
                    class="w-4 h-4 text-[#16834a] rounded focus:ring-[#16834a]" />
                  <div>
                    <span class="text-sm font-medium text-slate-700">{{ jenis.label }}</span>
                    <p class="text-xs text-slate-400">{{ jenis.desc }}</p>
                  </div>
                </label>
              </div>
              <p v-if="errors.jenis_perubahan" class="mt-2 text-xs text-red-500">{{ errors.jenis_perubahan }}</p>
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             SEKSI 3 � DATA PERUBAHAN (KUNING)
        ------------------------------------------------------- -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#F9C031] px-6 py-4 bg-yellow-50/60">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#F9C031] text-[#073B70] text-xs flex items-center justify-center font-bold flex-shrink-0">3</span>
              Data Perubahan
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Deskripsikan perubahan yang dibutuhkan secara rinci</p>
          </div>
          <div class="px-6 py-5 space-y-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Perubahan yang Diharapkan <span class="text-red-500">*</span></label>
              <textarea v-model="form.deskripsi_perubahan" rows="3" required placeholder="Deskripsikan perubahan yang diinginkan secara spesifik..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:80px"></textarea>
              <p v-if="errors.deskripsi_perubahan" class="mt-1 text-xs text-red-500">{{ errors.deskripsi_perubahan }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Latar Belakang Perubahan <span class="text-red-500">*</span></label>
              <textarea v-model="form.latar_belakang" rows="3" required placeholder="Jelaskan alasan dan kondisi yang melatarbelakangi permohonan ini..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:80px"></textarea>
              <p v-if="errors.latar_belakang" class="mt-1 text-xs text-red-500">{{ errors.latar_belakang }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Rincian Perubahan yang Diajukan <span class="text-red-500">*</span></label>
              <textarea v-model="form.rincian_perubahan" rows="4" required placeholder="Jabarkan secara teknis rincian perubahan yang diminta..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:80px"></textarea>
              <p v-if="errors.rincian_perubahan" class="mt-1 text-xs text-red-500">{{ errors.rincian_perubahan }}</p>
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             SEKSI 4 � ANALISIS RISIKO SMKI (KUNING � header saja)
        ------------------------------------------------------- -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#0757A8] px-6 py-4 bg-blue-50/60">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#0757A8] text-white text-xs flex items-center justify-center font-bold flex-shrink-0">4</span>
              Analisis Risiko SMKI
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Sesuai standar ISO/IEC 27001 � Sistem Manajemen Keamanan Informasi</p>
          </div>
          <!-- Body putih biasa (bukan amber) -->
          <div class="px-6 py-5 space-y-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Urgensi Perubahan <span class="text-red-500">*</span></label>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <label v-for="u in urgensiOptions" :key="u.value"
                  class="flex flex-col items-center gap-1 cursor-pointer p-3 rounded-lg border-2 text-center transition-all"
                  :class="form.urgensi === u.value ? `border-${u.border} bg-${u.bg}` : 'border-slate-200 hover:border-slate-300'">
                  <input type="radio" :value="u.value" v-model="form.urgensi" class="sr-only" />
                  <span :class="`text-xs font-bold text-${u.text}`">{{ u.label }}</span>
                  <span class="text-[10px] text-slate-500">SLA {{ u.sla }}</span>
                </label>
              </div>
              <p v-if="errors.urgensi" class="mt-1 text-xs text-red-500">{{ errors.urgensi }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Risiko Bila Tidak Dilakukan</label>
              <textarea v-model="form.risiko_tidak_dilakukan" rows="2" placeholder="Apa dampak negatif jika perubahan ini tidak dilakukan?"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:80px"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Kriteria Risiko</label>
              <div class="flex flex-wrap gap-2">
                <label v-for="risiko in risikoOptions" :key="risiko.value"
                  class="flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-full border-2 text-xs font-medium transition-all"
                  :class="form.kriteria_risiko === risiko.value ? 'border-[#F9C031] bg-yellow-50 text-yellow-800' : 'border-slate-200 bg-white text-slate-600 hover:border-yellow-300'">
                  <input type="radio" :value="risiko.value" v-model="form.kriteria_risiko" class="sr-only" />
                  {{ risiko.label }}
                </label>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Rencana Pemulihan (Rollback Plan)</label>
              <textarea v-model="form.rencana_pemulihan" rows="2" placeholder="Langkah pemulihan jika perubahan harus dibatalkan..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:70px"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan Risiko</label>
              <textarea v-model="form.keterangan_risiko" rows="2" placeholder="Keterangan tambahan mengenai risiko..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:70px"></textarea>
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             SEKSI 5 � TEKNIS & SOLUSI (BIRU)
        ------------------------------------------------------- -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#16834A] px-6 py-4 bg-green-50/60">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#16834A] text-white text-xs flex items-center justify-center font-bold flex-shrink-0">5</span>
              Teknis &amp; Solusi
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Detail teknis, solusi, dan pertimbangan implementasi</p>
          </div>
          <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-1">Solusi yang Diharapkan</label>
              <textarea v-model="form.solusi_diharapkan" rows="3" placeholder="Deskripsikan solusi teknis yang diharapkan..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:80px"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Risiko Perubahan</label>
              <textarea v-model="form.risiko_perubahan" rows="3" placeholder="Risiko yang mungkin muncul akibat perubahan..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:80px"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Alternatif Perubahan</label>
              <textarea v-model="form.alternatif_perubahan" rows="3" placeholder="Jika ada solusi alternatif yang dipertimbangkan..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition resize-none" style="min-height:80px"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Estimasi Biaya</label>
              <input v-model="form.biaya_perubahan" type="text" placeholder="Rp 0 (fasilitasi internal)"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Estimasi Waktu</label>
              <input v-model="form.waktu_perubahan" type="text" placeholder="Contoh: 7 Hari Kerja"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8]/30 focus:border-[#0757a8] transition" />
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             SEKSI 6 � IDENTITAS APLIKASI / SISTEM (HIJAU)
        ------------------------------------------------------- -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#F9C031] px-6 py-4 bg-yellow-50/60">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#F9C031] text-[#073B70] text-xs flex items-center justify-center font-bold flex-shrink-0">6</span>
              Identitas Aplikasi / Sistem
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Informasi teknis sistem atau aplikasi yang terdampak</p>
          </div>
          <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aplikasi / Sistem</label>
              <input v-model="form.nama_aplikasi" type="text" placeholder="Nama sistem atau aplikasi yang terdampak"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#16834a]/30 focus:border-[#16834a] transition" />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Aplikasi</label>
              <textarea v-model="form.deskripsi_aplikasi" rows="2" placeholder="Deskripsi singkat aplikasi..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#16834a]/30 focus:border-[#16834a] transition resize-none"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">URL Aplikasi</label>
              <input v-model="form.alamat_aplikasi" type="url" placeholder="https://aplikasi.jabarprov.go.id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#16834a]/30 focus:border-[#16834a] transition" />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">URL Repository</label>
              <input v-model="form.alamat_repository" type="url" placeholder="https://github.com/..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#16834a]/30 focus:border-[#16834a] transition" />
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             SEKSI 7 � LAMPIRAN & TANDA TANGAN (KUNING)
        ------------------------------------------------------- -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#0757A8] px-6 py-4 bg-blue-50/60">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#0757A8] text-white text-xs flex items-center justify-center font-bold flex-shrink-0">7</span>
              Lampiran &amp; Tanda Tangan
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Dokumen pendukung dan tanda tangan digital pemohon</p>
          </div>
          <div class="px-6 py-5 space-y-6">

            <!-- File Upload -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Dokumen Pendukung
                <span class="text-xs font-normal text-slate-400 ml-1">(Maks 5 file — PDF/DOCX/ZIP/PNG/JPG — Max 10MB per file)</span>
              </label>

              <!-- File existing dari draft (edit mode) -->
              <div v-if="existingFiles.length > 0" class="mb-3 space-y-2">
                <p class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                  <Icon icon="lucide:paperclip" class="w-3.5 h-3.5" />
                  File yang sudah diunggah sebelumnya:
                </p>
                <div v-for="(file, idx) in existingFiles" :key="file.path"
                  class="flex items-center justify-between bg-blue-50 rounded-lg px-3 py-2 border border-blue-100">
                  <div class="flex items-center gap-2 min-w-0">
                    <Icon icon="lucide:file-check" class="w-4 h-4 text-[#0757a8] flex-shrink-0" />
                    <a :href="file.url" target="_blank"
                      class="text-sm text-[#0757a8] hover:underline truncate">{{ file.nama }}</a>
                    <span class="text-[10px] text-slate-400 flex-shrink-0 bg-blue-100 px-1.5 py-0.5 rounded">Tersimpan</span>
                  </div>
                  <button type="button" @click="removeExistingFile(idx)"
                    class="text-red-400 hover:text-red-600 ml-2 flex-shrink-0" title="Hapus file ini">
                    <Icon icon="lucide:x" class="w-4 h-4" />
                  </button>
                </div>
                <p class="text-xs text-slate-400">
                  Tambahkan file baru di bawah. Total maks 5 file (sudah ada {{ existingFiles.length }}).
                </p>
              </div>

              <!-- Upload file baru -->
              <div v-if="(existingFiles.length + selectedFiles.length) < 5"
                class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-[#0757a8] transition-colors cursor-pointer"
                @click="triggerFileInput"
                @dragover.prevent
                @drop.prevent="handleFileDrop"
              >
                <input ref="fileInputRef" type="file" multiple accept=".pdf,.docx,.zip,.png,.jpg,.jpeg" class="hidden" @change="handleFileChange" />
                <Icon icon="lucide:upload-cloud" class="w-8 h-8 text-slate-300 mx-auto mb-2" />
                <p class="text-sm text-slate-500">Klik atau seret file baru ke sini</p>
                <p class="text-xs text-slate-400 mt-1">PDF, DOCX, ZIP, PNG, JPG</p>
              </div>
              <p v-else class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 flex items-center gap-1.5">
                <Icon icon="lucide:info" class="w-3.5 h-3.5 flex-shrink-0" />
                Batas maksimal 5 file sudah tercapai.
              </p>

              <!-- File baru yang akan diunggah -->
              <div v-if="selectedFiles.length > 0" class="mt-3 space-y-2">
                <p class="text-xs font-medium text-slate-500">File baru yang akan diunggah:</p>
                <div v-for="(file, idx) in selectedFiles" :key="idx"
                  class="flex items-center justify-between bg-slate-50 rounded-lg px-3 py-2 border border-slate-200">
                  <div class="flex items-center gap-2 min-w-0">
                    <Icon icon="lucide:file" class="w-4 h-4 text-[#0757a8] flex-shrink-0" />
                    <span class="text-sm text-slate-700 truncate">{{ file.name }}</span>
                    <span class="text-xs text-slate-400 flex-shrink-0">{{ formatFileSize(file.size) }}</span>
                  </div>
                  <button type="button" @click="removeFile(idx)" class="text-red-400 hover:text-red-600 ml-2">
                    <Icon icon="lucide:x" class="w-4 h-4" />
                  </button>
                </div>
              </div>
              <p v-if="errors.lampiran" class="mt-1 text-xs text-red-500">{{ errors.lampiran }}</p>
            </div>

            <!-- Tanda Tangan -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Tanda Tangan Digital <span class="text-red-500">*</span>
              </label>

              <!-- Info tanda tangan existing (saat loading) -->
              <div v-if="mode === 'edit' && props.rfc?.tanda_tangan && !form.tanda_tangan"
                class="mb-2 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex items-center gap-2">
                <Icon icon="lucide:loader-2" class="w-4 h-4 animate-spin flex-shrink-0" />
                <span>Memuat tanda tangan yang tersimpan...</span>
              </div>

              <SignaturePad v-model="form.tanda_tangan" />

              <div class="mt-1.5 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                  <strong>Dibuat Oleh: Pemohon</strong> — {{ form.nama_pemohon || '(nama pemohon)' }}
                </p>
                <p v-if="mode === 'edit' && props.rfc?.tanda_tangan && form.tanda_tangan"
                  class="text-xs text-emerald-600 flex items-center gap-1">
                  <Icon icon="lucide:check-circle" class="w-3.5 h-3.5" />
                  Tanda tangan tersimpan
                </p>
              </div>
              <p v-if="errors.tanda_tangan" class="mt-1 text-xs text-red-500">{{ errors.tanda_tangan }}</p>
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             TOMBOL AKSI
        ------------------------------------------------------- -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 pb-8">
          <button type="button" @click="simpanDraf" :disabled="isSavingDraf"
            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors disabled:opacity-50">
            <Icon v-if="isSavingDraf" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
            <Icon v-else icon="lucide:save" class="w-4 h-4" />
            {{ isSavingDraf ? 'Menyimpan...' : 'Simpan sebagai Draf' }}
          </button>

          <button type="submit" :disabled="isSubmitting"
            class="inline-flex items-center justify-center gap-2 px-8 py-2.5 rounded-lg bg-[#0757a8] hover:bg-[#065096] text-white text-sm font-semibold transition-colors disabled:opacity-60 disabled:cursor-not-allowed shadow-sm">
            <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
            <Icon v-else icon="lucide:send" class="w-4 h-4" />
            {{ isSubmitting ? 'Mengirim...' : 'Kirim Permohonan RFC' }}
          </button>
        </div>

      </form>

      <!-- Success Modal -->
      <Transition name="modal">
        <div v-if="successModal.show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="successModal.show = false"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
              <Icon icon="lucide:check-circle-2" class="w-8 h-8 text-[#16834a]" />
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-1">Permohonan Berhasil Dikirim!</h3>
            <p class="text-slate-500 text-sm mb-4">RFC Anda telah diajukan dan sedang menunggu verifikasi.</p>
            <div class="bg-slate-50 rounded-xl p-4 mb-6">
              <p class="text-xs text-slate-500 mb-1">Nomor RFC Anda</p>
              <p class="text-2xl font-bold font-mono text-[#0757a8] tracking-widest">{{ successModal.kodePelacakan }}</p>
            </div>
            <div class="flex flex-col gap-2">
              <Link :href="`/rfc/${successModal.kodePelacakan}`"
                class="w-full px-4 py-2.5 rounded-lg bg-[#0757a8] text-white text-sm font-semibold hover:bg-[#065096] transition-colors">
                Lihat Status RFC
              </Link>
              <button type="button" @click="resetForm"
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">
                Ajukan RFC Baru
              </button>
            </div>
          </div>
        </div>
      </Transition>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Link, usePage }   from '@inertiajs/vue3'
import AppLayout           from '@/Layouts/AppLayout.vue'
import SignaturePad        from '@/Components/SignaturePad.vue'
import { Icon }            from '@iconify/vue'
import axios               from 'axios'

const props = defineProps({
  kategoriList: { type: Array, default: () => [] },
  opdList:      { type: Array, default: () => [] },
  opdUser:      { type: Object, default: null },
  userProfile:  { type: Object, default: null },
  mode:         { type: String, default: 'create' },
  rfc:          { type: Object, default: null },
})

const page = usePage()
const today = new Date().toISOString().split('T')[0]

// -- Options -------------------------------------------------------------------
const jenisOptions = [
  { value: 'permintaan_baru',    label: 'Permintaan Baru',    desc: 'Fitur atau sistem baru' },
  { value: 'peningkatan_sistem', label: 'Peningkatan Sistem', desc: 'Upgrade & enhancement' },
  { value: 'perbaikan_sistem',   label: 'Perbaikan Sistem',   desc: 'Bug fix & pemulihan' },
]

const risikoOptions = [
  { value: 'malapetaka',  label: 'Malapetaka' },
  { value: 'sangat_berat',label: 'Sangat Berat' },
  { value: 'berat',       label: 'Berat' },
  { value: 'agak_berat',  label: 'Agak Berat' },
  { value: 'tidak_berat', label: 'Tidak Berat' },
]

const urgensiOptions = [
  { value: 'rendah',  label: 'Rendah',  sla: '5 hari', border: 'green-400',  bg: 'green-50',  text: 'green-700' },
  { value: 'sedang',  label: 'Sedang',  sla: '3 hari', border: 'yellow-400', bg: 'yellow-50', text: 'yellow-700' },
  { value: 'tinggi',  label: 'Tinggi',  sla: '24 jam', border: 'orange-400', bg: 'orange-50', text: 'orange-700' },
  { value: 'kritis',  label: 'Kritis',  sla: '4 jam',  border: 'red-500',    bg: 'red-50',    text: 'red-700' },
]

// -- Form State ----------------------------------------------------------------
const form = reactive({
  // Seksi 1
  nama_pemohon:      '',
  nip:               '',
  unit_kerja:        '',
  opd_id:            null,
  nomor_surat:       '',
  nomor_kontak:      '',
  email_dinas:       '',
  tanggal_permohonan: today,
  // Seksi 2
  kategori_id:       null,
  jenis_perubahan:   [],
  // Seksi 3
  deskripsi_perubahan: '',
  latar_belakang:    '',
  rincian_perubahan: '',
  // Seksi 4
  urgensi:           'sedang',
  risiko_tidak_dilakukan: '',
  kriteria_risiko:   '',
  keterangan_risiko: '',
  rencana_pemulihan: '',
  // Seksi 5
  solusi_diharapkan: '',
  risiko_perubahan:  '',
  alternatif_perubahan: '',
  biaya_perubahan:   '',
  waktu_perubahan:   '',
  // Seksi 6
  nama_aplikasi:     '',
  deskripsi_aplikasi:'',
  alamat_aplikasi:   '',
  alamat_repository: '',
  // Seksi 7
  tanda_tangan:      null,
})

const errors       = reactive({})
const isSubmitting = ref(false)
const isSavingDraf = ref(false)
const selectedFiles= ref([])
const existingFiles= ref([])   // file lampiran yang sudah tersimpan di draft
const fileInputRef = ref(null)
const successModal = reactive({ show: false, kodePelacakan: '' })

// -- Auto-isi dari profil user yang login --------------------------------------
onMounted(() => {
  if (props.mode === 'edit' && props.rfc) {
    // Pre-fill SEMUA field form dari data RFC yang tersimpan di database
    const rfcData = props.rfc

    // Seksi 1
    if (rfcData.nama_pemohon)       form.nama_pemohon       = rfcData.nama_pemohon
    if (rfcData.nip)                form.nip                = rfcData.nip
    if (rfcData.unit_kerja)         form.unit_kerja         = rfcData.unit_kerja
    if (rfcData.opd_id)             form.opd_id             = rfcData.opd_id
    if (rfcData.nomor_surat)        form.nomor_surat        = rfcData.nomor_surat
    if (rfcData.nomor_kontak)       form.nomor_kontak       = rfcData.nomor_kontak
    if (rfcData.email_dinas)        form.email_dinas        = rfcData.email_dinas
    if (rfcData.tanggal_permohonan) form.tanggal_permohonan = rfcData.tanggal_permohonan

    // Seksi 2
    if (rfcData.kategori_id)        form.kategori_id        = rfcData.kategori_id
    if (rfcData.jenis_perubahan) {
      if (Array.isArray(rfcData.jenis_perubahan)) {
        form.jenis_perubahan = rfcData.jenis_perubahan
      } else {
        try { form.jenis_perubahan = JSON.parse(rfcData.jenis_perubahan) } catch { form.jenis_perubahan = [] }
      }
    }

    // Seksi 3
    if (rfcData.deskripsi_perubahan) form.deskripsi_perubahan = rfcData.deskripsi_perubahan
    if (rfcData.latar_belakang)      form.latar_belakang      = rfcData.latar_belakang
    if (rfcData.rincian_perubahan)   form.rincian_perubahan   = rfcData.rincian_perubahan

    // Seksi 4
    if (rfcData.urgensi)                 form.urgensi                 = rfcData.urgensi
    if (rfcData.risiko_tidak_dilakukan)  form.risiko_tidak_dilakukan  = rfcData.risiko_tidak_dilakukan
    if (rfcData.kriteria_risiko)         form.kriteria_risiko         = rfcData.kriteria_risiko
    if (rfcData.keterangan_risiko)       form.keterangan_risiko       = rfcData.keterangan_risiko
    if (rfcData.rencana_pemulihan)       form.rencana_pemulihan       = rfcData.rencana_pemulihan

    // Seksi 5
    if (rfcData.solusi_diharapkan)       form.solusi_diharapkan       = rfcData.solusi_diharapkan
    if (rfcData.risiko_perubahan)        form.risiko_perubahan        = rfcData.risiko_perubahan
    if (rfcData.alternatif_perubahan)    form.alternatif_perubahan    = rfcData.alternatif_perubahan
    if (rfcData.biaya_perubahan)         form.biaya_perubahan         = rfcData.biaya_perubahan
    if (rfcData.waktu_perubahan)         form.waktu_perubahan         = rfcData.waktu_perubahan

    // Seksi 6
    if (rfcData.nama_aplikasi)           form.nama_aplikasi           = rfcData.nama_aplikasi
    if (rfcData.deskripsi_aplikasi)      form.deskripsi_aplikasi      = rfcData.deskripsi_aplikasi
    if (rfcData.alamat_aplikasi)         form.alamat_aplikasi         = rfcData.alamat_aplikasi
    if (rfcData.alamat_repository)       form.alamat_repository       = rfcData.alamat_repository

    // Seksi 7 — tanda tangan existing (URL dari storage)
    if (rfcData.tanda_tangan)            form.tanda_tangan            = rfcData.tanda_tangan

    // Override opd_id dari opdUser jika ada
    if (props.opdUser) form.opd_id = props.opdUser.id

    // Lampiran existing — tampilkan file yang sudah diupload sebelumnya
    if (rfcData.lampiran_existing && rfcData.lampiran_existing.length > 0) {
      existingFiles.value = rfcData.lampiran_existing
    }

    // Load tanda tangan: jika URL /storage/..., fetch sebagai base64 untuk ditampilkan di canvas
    if (rfcData.tanda_tangan && rfcData.tanda_tangan.startsWith('/')) {
      fetch(rfcData.tanda_tangan)
        .then(r => r.blob())
        .then(blob => {
          const reader = new FileReader()
          reader.onloadend = () => {
            form.tanda_tangan = reader.result  // base64 data URL
          }
          reader.readAsDataURL(blob)
        })
        .catch(() => {
          // Gagal load — tanda tangan tidak ditampilkan, user harus tanda tangan ulang
          form.tanda_tangan = null
        })
    }

  } else {
    // Mode create: auto-isi dari profil user yang login
    if (props.userProfile) {
      form.nama_pemohon = props.userProfile.nama_lengkap ?? ''
      form.nip          = props.userProfile.nip ?? ''
      form.nomor_kontak = props.userProfile.nomor_kontak ?? ''
      form.email_dinas  = props.userProfile.email ?? ''
      form.opd_id       = props.userProfile.opd_id ?? null
    }
    if (props.opdUser) {
      form.opd_id = props.opdUser.id
    }
  }
})

// -- File Handling -------------------------------------------------------------
function triggerFileInput() { fileInputRef.value?.click() }

function handleFileChange(e) {
  addFiles(Array.from(e.target.files))
  e.target.value = ''
}

function handleFileDrop(e) {
  addFiles(Array.from(e.dataTransfer.files))
}

function addFiles(newFiles) {
  const allowedTypes = ['application/pdf','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/zip','image/png','image/jpeg']
  for (const file of newFiles) {
    if (selectedFiles.value.length >= 5) { errors.lampiran = 'Maksimal 5 file.'; break }
    if (!allowedTypes.includes(file.type)) { errors.lampiran = `Format ${file.name} tidak didukung.`; continue }
    if (file.size > 10 * 1024 * 1024) { errors.lampiran = `${file.name} melebihi 10MB.`; continue }
    errors.lampiran = ''
    selectedFiles.value.push(file)
  }
}

function removeFile(idx) { selectedFiles.value.splice(idx, 1) }

function removeExistingFile(idx) {
  existingFiles.value.splice(idx, 1)
}

function formatFileSize(bytes) {
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
}

// -- Build FormData ------------------------------------------------------------
function buildFormData(simpanDraf = false) {
  const data = new FormData()
  data.append('simpan_draf', simpanDraf ? '1' : '0')

  Object.entries(form).forEach(([key, val]) => {
    if (val === null || val === undefined) return
    if (Array.isArray(val)) {
      val.forEach(v => data.append(`${key}[]`, v))
    } else {
      data.append(key, val)
    }
  })

  // File baru
  selectedFiles.value.forEach(file => data.append('lampiran[]', file))

  // Kirim path lampiran existing yang masih dipertahankan (mode edit)
  existingFiles.value.forEach(f => data.append('lampiran_existing[]', f.path))

  return data
}

// -- Simpan Draf ---------------------------------------------------------------
async function simpanDraf() {
  isSavingDraf.value = true
  try {
    let res
    if (props.mode === 'edit' && props.rfc) {
      // Mode edit: PUT ke /rfc/{id}
      const data = new FormData()
      data.append('simpan_draf', '1')
      data.append('_method', 'PUT')
      Object.entries(form).forEach(([key, val]) => {
        if (val === null || val === undefined) return
        if (Array.isArray(val)) {
          val.forEach(v => data.append(`${key}[]`, v))
        } else {
          data.append(key, val)
        }
      })
      res = await axios.post(`/rfc/${props.rfc.id}`, data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
    } else {
      // Mode create: POST ke /rfc
      res = await axios.post('/rfc', buildFormData(true), {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
    }
    // Redirect ke dashboard dengan view draf
    window.location.href = '/dashboard/pemohon?view=draf'
  } catch (err) {
    if (err.response?.data?.errors) Object.assign(errors, err.response.data.errors)
    else errors._general = err.response?.data?.message ?? 'Gagal menyimpan draf.'
  } finally {
    isSavingDraf.value = false
  }
}

// -- Validasi & Submit ---------------------------------------------------------
function validate() {
  let valid = true
  Object.keys(errors).forEach(k => { errors[k] = '' })

  if (!form.nama_pemohon?.trim())       { errors.nama_pemohon = 'Wajib diisi.'; valid = false }
  if (!form.nip?.trim())                { errors.nip = 'Wajib diisi.'; valid = false }
  if (!form.unit_kerja?.trim())         { errors.unit_kerja = 'Wajib diisi.'; valid = false }
  if (!form.opd_id)                     { errors.opd_id = 'OPD wajib dipilih.'; valid = false }
  if (!form.nomor_kontak?.trim())       { errors.nomor_kontak = 'Wajib diisi.'; valid = false }
  if (!form.email_dinas?.trim())        { errors.email_dinas = 'Wajib diisi.'; valid = false }
  if (!form.tanggal_permohonan)         { errors.tanggal_permohonan = 'Wajib diisi.'; valid = false }
  if (!form.kategori_id)                { errors.kategori_id = 'Kategori wajib dipilih.'; valid = false }
  if (!form.jenis_perubahan.length)     { errors.jenis_perubahan = 'Pilih minimal satu.'; valid = false }
  if (!form.deskripsi_perubahan?.trim()){ errors.deskripsi_perubahan = 'Wajib diisi.'; valid = false }
  if (!form.latar_belakang?.trim())     { errors.latar_belakang = 'Wajib diisi.'; valid = false }
  if (!form.rincian_perubahan?.trim())  { errors.rincian_perubahan = 'Wajib diisi.'; valid = false }
  if (!form.tanda_tangan)               { errors.tanda_tangan = 'Tanda tangan digital wajib dibuat.'; valid = false }

  return valid
}

async function handleSubmit() {
  if (!validate()) {
    document.querySelector('.text-red-500')?.scrollIntoView({ behavior: 'smooth', block: 'center' })
    return
  }
  isSubmitting.value = true
  try {
    let res
    if (props.mode === 'edit' && props.rfc) {
      // Mode edit: PUT ke /rfc/{id}
      const data = new FormData()
      data.append('simpan_draf', '0')
      data.append('_method', 'PUT')
      Object.entries(form).forEach(([key, val]) => {
        if (val === null || val === undefined) return
        if (Array.isArray(val)) {
          val.forEach(v => data.append(`${key}[]`, v))
        } else {
          data.append(key, val)
        }
      })
      selectedFiles.value.forEach(file => data.append('lampiran[]', file))
      res = await axios.post(`/rfc/${props.rfc.id}`, data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      // Redirect ke detail RFC
      window.location.href = `/rfc/${props.rfc.kode_pelacakan || res.data?.kode_pelacakan}`
    } else {
      // Mode create: POST ke /rfc
      res = await axios.post('/rfc', buildFormData(false), {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      successModal.kodePelacakan = res.data?.kode_pelacakan ?? res.data?.no_rfc ?? 'RFC-' + Date.now()
      successModal.show = true
    }
  } catch (err) {
    if (err.response?.data?.errors) Object.assign(errors, err.response.data.errors)
    else errors._general = err.response?.data?.message ?? 'Terjadi kesalahan. Silakan coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}

function resetForm() {
  Object.keys(form).forEach(k => {
    if (Array.isArray(form[k])) form[k] = []
    else if (k === 'urgensi') form[k] = 'sedang'
    else if (k === 'tanggal_permohonan') form[k] = today
    else form[k] = k === 'opd_id' || k === 'kategori_id' ? null : ''
  })
  selectedFiles.value = []
  Object.keys(errors).forEach(k => { errors[k] = '' })
  successModal.show = false
  window.scrollTo({ top: 0, behavior: 'smooth' })
  // Re-isi dari profil
  if (props.userProfile) {
    form.nama_pemohon = props.userProfile.nama_lengkap ?? ''
    form.nip          = props.userProfile.nip ?? ''
    form.nomor_kontak = props.userProfile.nomor_kontak ?? ''
    form.email_dinas  = props.userProfile.email ?? ''
    form.opd_id       = props.userProfile.opd_id ?? null
  }
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>