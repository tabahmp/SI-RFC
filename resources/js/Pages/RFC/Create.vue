<template>
  <AppLayout title="Formulir RFC Baru">
    <div class="min-h-screen bg-slate-50 pb-16">

      <!-- Page Header -->
      <div class="bg-white border-b border-slate-200 px-4 py-3">
        <div class="max-w-5xl mx-auto">
          <!-- Breadcrumb -->
          <nav class="flex items-center gap-1 text-xs text-slate-500 mb-2">
            <Link href="/" class="hover:text-[#0757a8] transition-colors">Beranda</Link>
            <span>›</span>
            <Link href="/form-perubahan-it" class="hover:text-[#0757a8] transition-colors">Daftar Tiket</Link>
            <span>›</span>
            <span class="text-slate-700 font-medium">Formulir Permohonan Baru</span>
          </nav>

          <!-- Badge -->
          <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="inline-flex items-center gap-1.5 bg-[#0757a8] text-white text-xs font-semibold px-3 py-1 rounded-full tracking-wide">
              FORMULIR PERUBAHAN TI · FR-015 · SMKI ISO/IEC 27001
            </span>
          </div>

          <h1 class="text-xl font-bold text-slate-900">Formulir Permohonan Request for Change (RFC)</h1>
          <p class="text-sm text-slate-500 mt-0.5">Diskominfo Prov. Jawa Barat · Bidang APTIKA</p>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="max-w-5xl mx-auto px-4 mt-6 space-y-6">

        <!-- SECTION 1: Informasi Pemohon -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#0757a8] px-6 py-4 bg-blue-50/50">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#0757a8] text-white text-xs flex items-center justify-center font-bold">1</span>
              Informasi Pemohon
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Data diri pemohon perubahan sistem</p>
          </div>
          <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Nama Lengkap Pemohon <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.nama_pemohon"
                type="text"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="Masukkan nama lengkap"
                required
              />
              <p v-if="errors.nama_pemohon" class="mt-1 text-xs text-red-500">{{ errors.nama_pemohon }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                NIP <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.nip"
                type="text"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="Nomor Induk Pegawai"
                required
              />
              <p v-if="errors.nip" class="mt-1 text-xs text-red-500">{{ errors.nip }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Unit Kerja / Bidang <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.unit_kerja"
                type="text"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="Nama unit kerja atau bidang"
                required
              />
              <p v-if="errors.unit_kerja" class="mt-1 text-xs text-red-500">{{ errors.unit_kerja }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Perangkat Daerah / OPD <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.opd"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition bg-white"
                required
              >
                <option value="" disabled>-- Pilih OPD --</option>
                <option v-for="opd in opdList" :key="opd.value ?? opd" :value="opd.value ?? opd">
                  {{ opd.label ?? opd }}
                </option>
              </select>
              <p v-if="errors.opd" class="mt-1 text-xs text-red-500">{{ errors.opd }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Nomor Surat Kedinasan <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.nomor_surat"
                type="text"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="Contoh: 005/SURAT/OPD/2026"
                required
              />
              <p v-if="errors.nomor_surat" class="mt-1 text-xs text-red-500">{{ errors.nomor_surat }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Contact Person <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.contact_person"
                type="text"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="No. HP / WhatsApp yang bisa dihubungi"
                required
              />
              <p v-if="errors.contact_person" class="mt-1 text-xs text-red-500">{{ errors.contact_person }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Email Dinas Resmi <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.email"
                type="email"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="email@jabarprov.go.id"
                required
              />
              <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Tanggal Permohonan <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.tanggal_permohonan"
                type="date"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                required
              />
              <p v-if="errors.tanggal_permohonan" class="mt-1 text-xs text-red-500">{{ errors.tanggal_permohonan }}</p>
            </div>

          </div>
        </div>

        <!-- SECTION 2: Jenis Perubahan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="border-l-4 border-[#16834a] px-6 py-4 bg-green-50/50">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#16834a] text-white text-xs flex items-center justify-center font-bold">2</span>
              Jenis Perubahan
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Pilih satu atau lebih jenis perubahan yang dimohonkan</p>
          </div>
          <div class="px-6 py-5">
            <div class="flex flex-col sm:flex-row gap-4">
              <label
                v-for="jenis in jenisOptions"
                :key="jenis.value"
                class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border-2 transition-all"
                :class="form.jenis_perubahan.includes(jenis.value)
                  ? 'border-[#0757a8] bg-blue-50'
                  : 'border-slate-200 hover:border-slate-300'"
              >
                <input
                  type="checkbox"
                  :value="jenis.value"
                  v-model="form.jenis_perubahan"
                  class="w-4 h-4 text-[#0757a8] rounded focus:ring-[#0757a8]"
                />
                <div>
                  <span class="text-sm font-medium text-slate-700">{{ jenis.label }}</span>
                  <p class="text-xs text-slate-400">{{ jenis.desc }}</p>
                </div>
              </label>
            </div>
            <p v-if="errors.jenis_perubahan" class="mt-2 text-xs text-red-500">{{ errors.jenis_perubahan }}</p>
          </div>
        </div>

        <!-- SECTION 3: Data Perubahan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-slate-600 text-white text-xs flex items-center justify-center font-bold">3</span>
              Data Perubahan
            </h2>
          </div>
          <div class="px-6 py-5 space-y-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Perubahan yang Diharapkan <span class="text-red-500">*</span>
              </label>
              <textarea
                v-model="form.perubahan_diharapkan"
                rows="3"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Deskripsikan perubahan yang diinginkan secara spesifik..."
                required
              ></textarea>
              <p v-if="errors.perubahan_diharapkan" class="mt-1 text-xs text-red-500">{{ errors.perubahan_diharapkan }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Latar Belakang Perubahan <span class="text-red-500">*</span>
              </label>
              <textarea
                v-model="form.latar_belakang"
                rows="3"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Jelaskan alasan dan kondisi yang melatarbelakangi permohonan ini..."
                required
              ></textarea>
              <p v-if="errors.latar_belakang" class="mt-1 text-xs text-red-500">{{ errors.latar_belakang }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Rincian Perubahan yang Diajukan <span class="text-red-500">*</span>
              </label>
              <textarea
                v-model="form.rincian_perubahan"
                rows="4"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Jabarkan secara teknis rincian perubahan yang diminta..."
                required
              ></textarea>
              <p v-if="errors.rincian_perubahan" class="mt-1 text-xs text-red-500">{{ errors.rincian_perubahan }}</p>
            </div>

          </div>
        </div>

        <!-- SECTION 4: Analisis Risiko SMKI -->
        <div class="bg-amber-50 rounded-xl shadow-sm border border-amber-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-amber-200">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center font-bold">4</span>
              Analisis Risiko SMKI
            </h2>
            <p class="text-xs text-amber-700 mt-0.5">Sesuai standar ISO/IEC 27001 — Sistem Manajemen Keamanan Informasi</p>
          </div>
          <div class="px-6 py-5 space-y-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Risiko Bila Tidak Dilakukan</label>
              <textarea
                v-model="form.risiko_tidak_dilakukan"
                rows="2"
                class="w-full rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Apa dampak negatif jika perubahan ini tidak dilakukan?"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Kriteria Risiko</label>
              <div class="flex flex-wrap gap-2">
                <label
                  v-for="risiko in risikoOptions"
                  :key="risiko.value"
                  class="flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-full border-2 text-xs font-medium transition-all"
                  :class="form.kriteria_risiko === risiko.value
                    ? 'border-amber-500 bg-amber-100 text-amber-800'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-amber-300'"
                >
                  <input
                    type="radio"
                    :value="risiko.value"
                    v-model="form.kriteria_risiko"
                    class="sr-only"
                  />
                  {{ risiko.label }}
                </label>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan Risiko</label>
              <textarea
                v-model="form.keterangan_risiko"
                rows="2"
                class="w-full rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Keterangan tambahan mengenai risiko..."
              ></textarea>
            </div>

          </div>
        </div>

        <!-- SECTION 5: Teknis & Solusi -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-slate-600 text-white text-xs flex items-center justify-center font-bold">5</span>
              Teknis &amp; Solusi
            </h2>
          </div>
          <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-1">Solusi yang Diharapkan</label>
              <textarea
                v-model="form.solusi_diharapkan"
                rows="3"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Deskripsikan solusi teknis yang diharapkan..."
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Risiko Perubahan</label>
              <textarea
                v-model="form.risiko_perubahan"
                rows="3"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Risiko yang mungkin muncul akibat perubahan..."
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Alternatif Perubahan</label>
              <textarea
                v-model="form.alternatif_perubahan"
                rows="3"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition resize-none"
                style="min-height:80px"
                placeholder="Jika ada solusi alternatif yang dipertimbangkan..."
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Biaya Perubahan</label>
              <input
                v-model="form.biaya_perubahan"
                type="text"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="Rp 0 (fasilitasi internal)"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Perubahan</label>
              <input
                v-model="form.waktu_perubahan"
                type="text"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="contoh: 7 Hari Kerja"
              />
            </div>

          </div>
        </div>

        <!-- SECTION 6: Identitas Aplikasi -->
        <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-slate-500 text-white text-xs flex items-center justify-center font-bold">6</span>
              Identitas Aplikasi / Sistem
            </h2>
          </div>
          <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aplikasi / Sistem</label>
              <input
                v-model="form.nama_aplikasi"
                type="text"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="Nama sistem atau aplikasi yang terdampak"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Aplikasi</label>
              <textarea
                v-model="form.deskripsi_aplikasi"
                rows="2"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition resize-none"
                placeholder="Deskripsi singkat aplikasi..."
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">URL Aplikasi</label>
              <input
                v-model="form.url_aplikasi"
                type="url"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="https://aplikasi.jabarprov.go.id"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">URL Repository</label>
              <input
                v-model="form.url_repository"
                type="url"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0757a8] focus:border-transparent transition"
                placeholder="https://github.com/..."
              />
            </div>

          </div>
        </div>

        <!-- SECTION 7: Lampiran & Tanda Tangan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800 flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-[#0757a8] text-white text-xs flex items-center justify-center font-bold">7</span>
              Lampiran &amp; Tanda Tangan
            </h2>
          </div>
          <div class="px-6 py-5 space-y-6">

            <!-- File Upload -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Dokumen Pendukung
                <span class="text-xs font-normal text-slate-400 ml-1">(Maks 5 file · PDF/DOCX/ZIP/PNG/JPG · Max 10MB per file)</span>
              </label>
              <div
                class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-[#0757a8] transition-colors cursor-pointer"
                @click="triggerFileInput"
                @dragover.prevent
                @drop.prevent="handleFileDrop"
              >
                <input
                  ref="fileInputRef"
                  type="file"
                  multiple
                  accept=".pdf,.docx,.zip,.png,.jpg,.jpeg"
                  class="hidden"
                  @change="handleFileChange"
                />
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="text-sm text-slate-500">Klik atau seret file ke sini</p>
              </div>

              <!-- File List -->
              <div v-if="selectedFiles.length > 0" class="mt-3 space-y-2">
                <div
                  v-for="(file, idx) in selectedFiles"
                  :key="idx"
                  class="flex items-center justify-between bg-slate-50 rounded-lg px-3 py-2 border border-slate-200"
                >
                  <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-4 h-4 text-[#0757a8] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm text-slate-700 truncate">{{ file.name }}</span>
                    <span class="text-xs text-slate-400 flex-shrink-0">{{ formatFileSize(file.size) }}</span>
                  </div>
                  <button type="button" @click="removeFile(idx)" class="text-red-400 hover:text-red-600 ml-2 flex-shrink-0 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <p v-if="errors.lampiran" class="mt-1 text-xs text-red-500">{{ errors.lampiran }}</p>
            </div>

            <!-- Signature -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Tanda Tangan Digital <span class="text-red-500">*</span>
              </label>
              <SignaturePad v-model="form.tanda_tangan" />
              <p class="mt-1.5 text-xs text-slate-500 text-center">
                <strong>Dibuat Oleh: Pemohon</strong> — {{ form.nama_pemohon || '(nama pemohon)' }}
              </p>
              <p v-if="errors.tanda_tangan" class="mt-1 text-xs text-red-500">{{ errors.tanda_tangan }}</p>
            </div>

          </div>
        </div>

        <!-- Submit -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 pb-8">
          <Link href="/form-perubahan-it" class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors text-center">
            Batal
          </Link>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="inline-flex items-center justify-center gap-2 px-8 py-2.5 rounded-lg bg-[#0757a8] hover:bg-[#065096] text-white text-sm font-semibold transition-colors disabled:opacity-60 disabled:cursor-not-allowed shadow-sm"
          >
            <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <span v-if="!isSubmitting">📤</span>
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
              <svg class="w-8 h-8 text-[#16834a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-1">Permohonan Berhasil Dikirim!</h3>
            <p class="text-slate-500 text-sm mb-4">RFC Anda telah berhasil diajukan dan sedang menunggu verifikasi.</p>
            <div class="bg-slate-50 rounded-xl p-4 mb-6">
              <p class="text-xs text-slate-500 mb-1">Nomor RFC Anda</p>
              <p class="text-2xl font-bold font-mono text-[#0757a8] tracking-widest">{{ successModal.noRfc }}</p>
            </div>
            <div class="flex flex-col gap-2">
              <Link
                :href="`/form-perubahan-it/ticket/${successModal.noRfc}`"
                class="w-full px-4 py-2.5 rounded-lg bg-[#0757a8] text-white text-sm font-semibold hover:bg-[#065096] transition-colors"
              >
                Lihat Status Tiket
              </Link>
              <button
                type="button"
                @click="resetForm"
                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors"
              >
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
import AppLayout from '@/Layouts/AppLayout.vue'
import SignaturePad from '@/Components/SignaturePad.vue'
import { ref, reactive, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  opdList: {
    type: Array,
    default: () => [],
  },
})

// --- Options ---
const jenisOptions = [
  { value: 'permintaan_baru', label: 'Permintaan Baru', desc: 'Fitur atau sistem baru' },
  { value: 'peningkatan_sistem', label: 'Peningkatan Sistem', desc: 'Upgrade & enhancement' },
  { value: 'perbaikan_sistem', label: 'Perbaikan Sistem', desc: 'Bug fix & pemulihan' },
]

const risikoOptions = [
  { value: 'malapetaka', label: 'Malapetaka' },
  { value: 'sangat_berat', label: 'Sangat Berat' },
  { value: 'berat', label: 'Berat' },
  { value: 'agak_berat', label: 'Agak Berat' },
  { value: 'tidak_berat', label: 'Tidak Berat' },
]

// --- Form State ---
const today = new Date().toISOString().split('T')[0]

const form = reactive({
  nama_pemohon: '',
  nip: '',
  unit_kerja: '',
  opd: '',
  nomor_surat: '',
  contact_person: '',
  email: '',
  tanggal_permohonan: today,
  jenis_perubahan: [],
  perubahan_diharapkan: '',
  latar_belakang: '',
  rincian_perubahan: '',
  risiko_tidak_dilakukan: '',
  kriteria_risiko: '',
  keterangan_risiko: '',
  solusi_diharapkan: '',
  risiko_perubahan: '',
  alternatif_perubahan: '',
  biaya_perubahan: '',
  waktu_perubahan: '',
  nama_aplikasi: '',
  deskripsi_aplikasi: '',
  url_aplikasi: '',
  url_repository: '',
  tanda_tangan: null,
})

const errors = reactive({})
const isSubmitting = ref(false)
const selectedFiles = ref([])
const fileInputRef = ref(null)
const successModal = reactive({ show: false, noRfc: '' })

// --- File Handling ---
function triggerFileInput() {
  fileInputRef.value?.click()
}

function handleFileChange(e) {
  addFiles(Array.from(e.target.files))
  e.target.value = ''
}

function handleFileDrop(e) {
  addFiles(Array.from(e.dataTransfer.files))
}

function addFiles(newFiles) {
  const allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'image/png', 'image/jpeg']
  for (const file of newFiles) {
    if (selectedFiles.value.length >= 5) {
      errors.lampiran = 'Maksimal 5 file yang dapat diunggah.'
      break
    }
    if (!allowedTypes.includes(file.type)) {
      errors.lampiran = `Format file ${file.name} tidak didukung.`
      continue
    }
    if (file.size > 10 * 1024 * 1024) {
      errors.lampiran = `File ${file.name} melebihi batas 10MB.`
      continue
    }
    errors.lampiran = ''
    selectedFiles.value.push(file)
  }
}

function removeFile(idx) {
  selectedFiles.value.splice(idx, 1)
}

function formatFileSize(bytes) {
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
}

// --- Validation ---
function validate() {
  let valid = true
  Object.keys(errors).forEach(k => { errors[k] = '' })

  if (!form.nama_pemohon.trim()) { errors.nama_pemohon = 'Nama pemohon wajib diisi.'; valid = false }
  if (!form.nip.trim()) { errors.nip = 'NIP wajib diisi.'; valid = false }
  if (!form.unit_kerja.trim()) { errors.unit_kerja = 'Unit kerja wajib diisi.'; valid = false }
  if (!form.opd) { errors.opd = 'OPD wajib dipilih.'; valid = false }
  if (!form.nomor_surat.trim()) { errors.nomor_surat = 'Nomor surat wajib diisi.'; valid = false }
  if (!form.contact_person.trim()) { errors.contact_person = 'Contact person wajib diisi.'; valid = false }
  if (!form.email.trim()) {
    errors.email = 'Email wajib diisi.'; valid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Format email tidak valid.'; valid = false
  }
  if (!form.tanggal_permohonan) { errors.tanggal_permohonan = 'Tanggal wajib diisi.'; valid = false }
  if (form.jenis_perubahan.length === 0) { errors.jenis_perubahan = 'Pilih minimal satu jenis perubahan.'; valid = false }
  if (!form.perubahan_diharapkan.trim()) { errors.perubahan_diharapkan = 'Field ini wajib diisi.'; valid = false }
  if (!form.latar_belakang.trim()) { errors.latar_belakang = 'Field ini wajib diisi.'; valid = false }
  if (!form.rincian_perubahan.trim()) { errors.rincian_perubahan = 'Field ini wajib diisi.'; valid = false }
  if (!form.tanda_tangan) { errors.tanda_tangan = 'Tanda tangan digital wajib dibuat.'; valid = false }

  return valid
}

// --- Submit ---
async function handleSubmit() {
  if (!validate()) {
    // scroll to first error
    const firstError = document.querySelector('.text-red-500')
    firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' })
    return
  }

  isSubmitting.value = true

  const data = new FormData()
  Object.entries(form).forEach(([key, val]) => {
    if (val !== null && val !== undefined) {
      if (Array.isArray(val)) {
        val.forEach(v => data.append(`${key}[]`, v))
      } else {
        data.append(key, val)
      }
    }
  })
  selectedFiles.value.forEach((file) => data.append('lampiran[]', file))

  try {
    const res = await axios.post('/api/form-perubahan-it', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    successModal.noRfc = res.data?.no_rfc ?? res.data?.data?.no_rfc ?? 'RFC-' + Date.now()
    successModal.show = true
  } catch (err) {
    if (err.response?.data?.errors) {
      Object.assign(errors, err.response.data.errors)
    } else {
      errors._general = err.response?.data?.message ?? 'Terjadi kesalahan. Silakan coba lagi.'
    }
  } finally {
    isSubmitting.value = false
  }
}

function resetForm() {
  Object.assign(form, {
    nama_pemohon: '', nip: '', unit_kerja: '', opd: '', nomor_surat: '',
    contact_person: '', email: '', tanggal_permohonan: today, jenis_perubahan: [],
    perubahan_diharapkan: '', latar_belakang: '', rincian_perubahan: '',
    risiko_tidak_dilakukan: '', kriteria_risiko: '', keterangan_risiko: '',
    solusi_diharapkan: '', risiko_perubahan: '', alternatif_perubahan: '',
    biaya_perubahan: '', waktu_perubahan: '', nama_aplikasi: '', deskripsi_aplikasi: '',
    url_aplikasi: '', url_repository: '', tanda_tangan: null,
  })
  selectedFiles.value = []
  Object.keys(errors).forEach(k => { errors[k] = '' })
  successModal.show = false
  successModal.noRfc = ''
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
