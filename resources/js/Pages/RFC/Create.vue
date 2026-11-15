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
            {{ isPerluRevisi ? `Perbaikan Permohonan (${rfc?.kode_pelacakan || ''})` : (mode === 'edit' ? 'Edit Draf Permohonan RFC' : 'Formulir Permohonan Request for Change (RFC)') }}
          </h1>
          <p class="text-sm text-slate-500 mt-0.5">Diskominfo Prov. Jawa Barat � Bidang APTIKA</p>
        </div>
      </div>

      <!-- Banner Catatan Revisi dari Verifikator (Mencolok, Jelas & Informatif) -->
      <div v-if="isPerluRevisi" class="max-w-5xl mx-auto px-4 mt-6">
        <div class="rounded-2xl bg-gradient-to-br from-amber-50 via-white to-amber-50/70 border-2 border-amber-300 shadow-md overflow-hidden">
          
          <!-- Header Bar -->
          <div class="px-5 py-3.5 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <span class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-xs flex items-center justify-center flex-shrink-0 shadow-inner">
                <Icon icon="lucide:alert-triangle" class="w-5 h-5 text-amber-100" />
              </span>
              <div>
                <div class="flex items-center gap-2">
                  <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-white text-amber-900 tracking-wider">
                    Perlu Revisi
                  </span>
                  <span class="font-mono text-xs font-bold text-amber-100">{{ rfc?.kode_pelacakan || '-' }}</span>
                </div>
                <h3 class="text-sm font-bold text-white mt-0.5">Formulir Dikembalikan oleh Verifikator</h3>
              </div>
            </div>

            <!-- Info Pengirim Revisi -->
            <div class="flex items-center gap-2 self-start sm:self-auto">
              <div class="text-right text-[11px] leading-tight">
                <div class="font-bold text-white flex items-center gap-1 sm:justify-end">
                  <Icon icon="lucide:user-check" class="w-3.5 h-3.5 text-amber-200" />
                  <span>{{ namaVerifikator }}</span>
                </div>
                <div v-if="tanggalRevisi" class="text-amber-200 text-[10px] mt-0.5">
                  {{ tanggalRevisi }}
                </div>
              </div>
            </div>
          </div>

          <!-- Body Kartu: Pesan Inti Verifikator -->
          <div class="p-5 sm:p-6 space-y-4">
            
            <div>
              <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-black uppercase tracking-wider text-amber-950 flex items-center gap-1.5">
                  <Icon icon="lucide:message-square-quote" class="w-4 h-4 text-amber-600" />
                  <span>Catatan & Instruksi Perbaikan Verifikator:</span>
                </label>
                <span class="text-[11px] font-semibold text-amber-700">Wajib Diperbaiki</span>
              </div>

              <!-- Kotak Pesan Revisi yang Jelas & Kontras -->
              <div class="p-4 sm:p-5 rounded-xl bg-white border-2 border-amber-300 shadow-xs relative">
                <div class="absolute -top-3 left-4 px-2 py-0.5 bg-amber-100 border border-amber-300 rounded text-[10px] font-bold text-amber-900">
                  Pesan Verifikator
                </div>
                <p v-if="pesanVerifikator" class="text-sm text-slate-800 leading-relaxed font-semibold whitespace-pre-line">
                  {{ pesanVerifikator }}
                </p>
                <p v-else class="text-sm text-slate-500 italic">
                  (Tidak ada catatan teks tertulis. Silakan tinjau kelengkapan isian formulir, dokumen lampiran, atau kriteria risiko Anda.)
                </p>
              </div>
            </div>

            <!-- Petunjuk Pemohon & Tombol Riwayat Revisi -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
              <div class="flex items-start gap-2 text-xs text-amber-900">
                <Icon icon="lucide:info" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" />
                <span class="leading-relaxed">
                  Perbaiki poin yang diminta pada formulir di bawah ini, lalu klik tombol <strong>"Kirim Perbaikan RFC"</strong>.
                </span>
              </div>

              <!-- Tombol toggle riwayat jika ada lebih dari 1 catatan revisi -->
              <button
                v-if="daftarRiwayatRevisi.length > 1"
                type="button"
                @click="showRiwayat = !showRiwayat"
                class="text-xs font-bold text-amber-800 hover:text-amber-950 flex items-center gap-1 self-start sm:self-auto py-1 px-2.5 rounded-lg bg-amber-100/70 hover:bg-amber-100 border border-amber-200 transition-colors whitespace-nowrap"
              >
                <Icon :icon="showRiwayat ? 'lucide:chevron-up' : 'lucide:chevron-down'" class="w-3.5 h-3.5" />
                <span>{{ showRiwayat ? 'Tutup Riwayat Revisi' : `Lihat Riwayat Revisi (${daftarRiwayatRevisi.length})` }}</span>
              </button>
            </div>

            <!-- List Riwayat Revisi Sebelumnya (Jika Dibuka) -->
            <div v-if="showRiwayat && daftarRiwayatRevisi.length > 1" class="mt-3 pt-3 border-t border-amber-200 space-y-2.5 animate-in fade-in duration-150">
              <div class="text-[11px] font-bold text-amber-950 uppercase tracking-wider">Riwayat Catatan Sebelumnya:</div>
              <div
                v-for="(r, idx) in daftarRiwayatRevisi.slice(1)"
                :key="r.id || idx"
                class="p-3 rounded-lg bg-amber-50/70 border border-amber-200/80 text-xs text-slate-700 space-y-1"
              >
                <div class="flex items-center justify-between text-[11px] text-amber-800 font-semibold">
                  <span>{{ r.verifikator }}</span>
                  <span class="text-slate-500">{{ r.tanggal }}</span>
                </div>
                <p class="text-slate-800 font-medium whitespace-pre-line">{{ r.catatan }}</p>
              </div>
            </div>

          </div>
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
                <span class="text-xs font-normal text-slate-400 ml-1">(Maks 5 file — PDF/DOCX/ZIP/PNG/JPG — Max 10MB)</span>
              </label>

              <!-- File existing dari draft (edit mode) -->
              <div v-if="existingFiles.length > 0" class="mb-3">
                <p class="text-xs font-semibold text-slate-500 mb-2 flex items-center gap-1.5">
                  <Icon icon="lucide:paperclip" class="w-3.5 h-3.5" />
                  File sebelumnya ({{ existingFiles.length }}):
                </p>
                <div class="space-y-2">
                  <div v-for="(file, idx) in existingFiles" :key="file.path"
                    class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2.5">
                    <div class="flex items-center gap-2 min-w-0">
                      <Icon icon="lucide:file-check-2" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                      <div class="min-w-0">
                        <a :href="file.url" target="_blank"
                          class="text-sm font-medium text-emerald-800 hover:text-emerald-600 hover:underline truncate block">
                          {{ file.nama }}
                        </a>
                        <span class="text-[10px] text-emerald-600">Sudah tersimpan · klik untuk buka</span>
                      </div>
                    </div>
                    <button type="button" @click="removeExistingFile(idx)"
                      title="Hapus file ini"
                      class="ml-3 flex-shrink-0 p-1 rounded-md text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                      <Icon icon="lucide:trash-2" class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
              </div>

              <!-- Drop zone -->
              <div v-if="(existingFiles.length + selectedFiles.length) < 5">
                <div
                  class="relative border-2 border-dashed rounded-xl transition-all cursor-pointer"
                  :class="isDragOver
                    ? 'border-[#0757a8] bg-blue-50 scale-[1.01]'
                    : 'border-slate-300 hover:border-[#0757a8] hover:bg-slate-50/50'"
                  @click="triggerFileInput"
                  @dragover.prevent="isDragOver = true"
                  @dragleave="isDragOver = false"
                  @drop.prevent="e => { isDragOver = false; handleFileDrop(e) }"
                >
                  <input ref="fileInputRef" type="file" multiple
                    accept=".pdf,.docx,.doc,.xlsx,.xls,.zip,.png,.jpg,.jpeg"
                    class="hidden" @change="handleFileChange" />
                  <div class="p-6 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-100 flex items-center justify-center">
                      <Icon icon="lucide:upload-cloud" class="w-6 h-6 text-slate-400" />
                    </div>
                    <p class="text-sm font-medium text-slate-600">Klik atau seret file ke sini</p>
                    <p class="text-xs text-slate-400 mt-1">PDF, DOCX, XLSX, ZIP, PNG, JPG · maks 10MB/file</p>
                    <p class="text-xs text-blue-500 mt-1">
                      Sisa slot: {{ 5 - existingFiles.length - selectedFiles.length }} file
                    </p>
                  </div>
                </div>
              </div>
              <div v-else class="border-2 border-dashed border-amber-300 rounded-xl p-4 text-center bg-amber-50">
                <Icon icon="lucide:alert-circle" class="w-5 h-5 text-amber-500 mx-auto mb-1" />
                <p class="text-xs text-amber-700 font-medium">Batas 5 file sudah tercapai</p>
              </div>

              <!-- File baru yang dipilih + progress -->
              <div v-if="selectedFiles.length > 0" class="mt-3 space-y-2">
                <p class="text-xs font-semibold text-slate-500 mb-1">File baru:</p>
                <div v-for="(item, idx) in selectedFiles" :key="idx"
                  class="rounded-lg border border-slate-200 overflow-hidden">
                  <!-- Header file -->
                  <div class="flex items-center justify-between bg-white px-3 py-2.5">
                    <div class="flex items-center gap-2 min-w-0">
                      <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                        :class="getFileIconClass(item.file.name)">
                        <Icon :icon="getFileIcon(item.file.name)" class="w-4 h-4" />
                      </div>
                      <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ item.file.name }}</p>
                        <p class="text-[11px] text-slate-400">{{ formatFileSize(item.file.size) }}</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-2 ml-2 flex-shrink-0">
                      <span v-if="item.status === 'ready'" class="text-[11px] text-slate-400">Siap diunggah</span>
                      <span v-else-if="item.status === 'uploading'" class="text-[11px] text-blue-600 flex items-center gap-1">
                        <Icon icon="lucide:loader-2" class="w-3 h-3 animate-spin" />
                        {{ item.progress }}%
                      </span>
                      <span v-else-if="item.status === 'done'" class="text-[11px] text-emerald-600 flex items-center gap-1">
                        <Icon icon="lucide:check-circle" class="w-3 h-3" />
                        Selesai
                      </span>
                      <button type="button" @click="removeFile(idx)"
                        class="p-1 rounded-md text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <Icon icon="lucide:x" class="w-3.5 h-3.5" />
                      </button>
                    </div>
                  </div>
                  <!-- Progress bar -->
                  <div class="h-1 bg-slate-100">
                    <div class="h-full transition-all duration-300 rounded-full"
                      :class="item.status === 'done' ? 'bg-emerald-400' : 'bg-[#0757a8]'"
                      :style="`width: ${item.progress}%`"></div>
                  </div>
                </div>
              </div>

              <p v-if="errors.lampiran" class="mt-1 text-xs text-red-500 flex items-center gap-1">
                <Icon icon="lucide:alert-circle" class="w-3.5 h-3.5" />
                {{ errors.lampiran }}
              </p>
            </div>

            <!-- Tanda Tangan -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Tanda Tangan Digital <span class="text-red-500">*</span>
              </label>

              <!-- Loading indicator tanda tangan -->
              <div v-if="ttdLoading"
                class="mb-2 flex items-center gap-2 px-3 py-2.5 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
                <Icon icon="lucide:loader-2" class="w-4 h-4 animate-spin flex-shrink-0" />
                <span>Memuat tanda tangan yang tersimpan...</span>
              </div>

              <!-- Badge tanda tangan berhasil dimuat -->
              <div v-if="!ttdLoading && mode === 'edit' && form.tanda_tangan"
                class="mb-2 flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-700">
                <Icon icon="lucide:check-circle-2" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                <span>Tanda tangan sebelumnya berhasil dimuat. Gambar ulang jika ingin mengubah.</span>
              </div>

              <!-- Badge tanda tangan gagal dimuat -->
              <div v-if="ttdFailed"
                class="mb-2 flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
                <Icon icon="lucide:alert-triangle" class="w-4 h-4 text-amber-600 flex-shrink-0" />
                <span>Tanda tangan lama tidak dapat dimuat. Silakan buat tanda tangan baru.</span>
              </div>

              <SignaturePad v-model="form.tanda_tangan" />

              <p class="mt-1.5 text-xs text-slate-500">
                <strong>Dibuat Oleh: Pemohon</strong> — {{ form.nama_pemohon || '(nama pemohon)' }}
              </p>
              <p v-if="errors.tanda_tangan" class="mt-1 text-xs text-red-500 flex items-center gap-1">
                <Icon icon="lucide:alert-circle" class="w-3.5 h-3.5" />
                {{ errors.tanda_tangan }}
              </p>
            </div>

            <!-- Penanggung Jawab OPD (Kepala Bidang) -->
            <div class="border-t border-slate-200 pt-6 space-y-4">
              <div>
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2 mb-1">
                  <Icon icon="lucide:user-check" class="w-4 h-4 text-[#0757A8]" />
                  Penanggung Jawab Perangkat Daerah
                </h3>
                <p class="text-xs text-slate-500">Kepala Bidang OPD yang menyetujui pengajuan RFC ini.</p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nama Kabid -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nama Kepala Bidang <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.nama_penanggung_jawab"
                    type="text"
                    placeholder="Nama lengkap Kepala Bidang OPD"
                    class="w-full text-sm px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all"
                  />
                  <p v-if="errors.nama_penanggung_jawab" class="mt-1 text-xs text-red-500 flex items-center gap-1">
                    <Icon icon="lucide:alert-circle" class="w-3.5 h-3.5" />
                    {{ errors.nama_penanggung_jawab }}
                  </p>
                </div>

                <!-- NIP Kabid -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">
                    NIP Kepala Bidang <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.nip_penanggung_jawab"
                    type="text"
                    placeholder="Nomor Induk Pegawai Kepala Bidang"
                    class="w-full text-sm px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#0757A8]/20 focus:border-[#0757A8] outline-none transition-all"
                  />
                  <p v-if="errors.nip_penanggung_jawab" class="mt-1 text-xs text-red-500 flex items-center gap-1">
                    <Icon icon="lucide:alert-circle" class="w-3.5 h-3.5" />
                    {{ errors.nip_penanggung_jawab }}
                  </p>
                </div>
              </div>

              <!-- TTD Kabid OPD -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Tanda Tangan Kepala Bidang OPD <span class="text-red-500">*</span>
                </label>

                <!-- Badge TTD Kabid loaded -->
                <div v-if="!ttdPjLoading && mode === 'edit' && form.tanda_tangan_penanggung_jawab"
                  class="mb-2 flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-700">
                  <Icon icon="lucide:check-circle-2" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                  <span>Tanda tangan Kepala Bidang sebelumnya dimuat. Gambar ulang untuk mengubah.</span>
                </div>

                <SignaturePad v-model="form.tanda_tangan_penanggung_jawab" />

                <p class="mt-1.5 text-xs text-slate-500">
                  <strong>Disetujui Oleh: Kepala Bidang</strong> — {{ form.nama_penanggung_jawab || '(nama kepala bidang)' }}
                </p>
                <p v-if="errors.tanda_tangan_penanggung_jawab" class="mt-1 text-xs text-red-500 flex items-center gap-1">
                  <Icon icon="lucide:alert-circle" class="w-3.5 h-3.5" />
                  {{ errors.tanda_tangan_penanggung_jawab }}
                </p>
              </div>
            </div>

          </div>
        </div>

        <!-- -------------------------------------------------------
             TOMBOL AKSI
        ------------------------------------------------------- -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 pb-8">
          <!-- Tombol Simpan sebagai Draf: dinonaktifkan jika status Perlu Revisi -->
          <button
            type="button"
            @click="simpanDraf"
            :disabled="isSavingDraf || isPerluRevisi"
            :title="isPerluRevisi ? 'Permohonan berstatus Perlu Revisi tidak dapat disimpan ke draf karena sudah pernah diajukan sebelumnya' : 'Simpan progres pengisian ke draf'"
            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg border text-sm font-medium transition-colors"
            :class="isPerluRevisi
              ? 'border-slate-200 bg-slate-100 text-slate-400 opacity-60 cursor-not-allowed select-none'
              : 'border-slate-300 bg-white text-slate-600 hover:bg-slate-50 cursor-pointer disabled:opacity-50'"
          >
            <Icon v-if="isSavingDraf" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
            <Icon v-else icon="lucide:save" class="w-4 h-4" />
            {{ isSavingDraf ? 'Menyimpan...' : 'Simpan sebagai Draf' }}
          </button>

          <!-- Tombol Submit / Kirim Permohonan -->
          <button
            type="submit"
            :disabled="isSubmitting"
            class="inline-flex items-center justify-center gap-2 px-8 py-2.5 rounded-lg bg-[#0757a8] hover:bg-[#065096] text-white text-sm font-semibold transition-colors disabled:opacity-60 disabled:cursor-not-allowed shadow-sm cursor-pointer"
          >
            <Icon v-if="isSubmitting" icon="lucide:loader-2" class="w-4 h-4 animate-spin" />
            <Icon v-else icon="lucide:send" class="w-4 h-4" />
            {{ isSubmitting ? 'Mengirim...' : (isPerluRevisi ? 'Kirim Perbaikan RFC' : 'Kirim Permohonan RFC') }}
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
              <Link :href="`/rfc/${successModal.kodePelacakan}/workspace`"
                class="w-full px-4 py-2.5 rounded-lg bg-[#0757a8] text-white text-sm font-semibold hover:bg-[#065096] transition-colors">
                Buka Workspace RFC
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
import { ref, reactive, computed, onMounted } from 'vue'
import { Link, usePage }   from '@inertiajs/vue3'
import AppLayout           from '@/Layouts/AppLayout.vue'
import SignaturePad        from '@/Components/SignaturePad.vue'
import { Icon }            from '@iconify/vue'
import axios               from 'axios'

const props = defineProps({
  kategoriList:  { type: Array, default: () => [] },
  opdList:       { type: Array, default: () => [] },
  opdUser:       { type: Object, default: null },
  userProfile:   { type: Object, default: null },
  mode:          { type: String, default: 'create' },
  rfc:           { type: Object, default: null },
  catatanRevisi: { type: Object, default: null },
  riwayatRevisi: { type: Array,  default: () => [] },
})

const isPerluRevisi = computed(() => {
  return props.rfc?.status_terkini === 'perlu_revisi'
})

const pesanVerifikator = computed(() => {
  return props.catatanRevisi?.catatan 
    || props.rfc?.catatan_verifikator 
    || props.rfc?.catatanRevisi?.catatan 
    || null
})

const namaVerifikator = computed(() => {
  return props.catatanRevisi?.verifikator 
    || props.rfc?.nama_verifikator 
    || props.rfc?.catatanRevisi?.verifikator 
    || 'Verifikator Diskominfo Jabar'
})

const tanggalRevisi = computed(() => {
  return props.catatanRevisi?.tanggal 
    || props.rfc?.tanggal_revisi 
    || props.rfc?.catatanRevisi?.tanggal 
    || null
})

const daftarRiwayatRevisi = computed(() => {
  return props.riwayatRevisi?.length ? props.riwayatRevisi : (props.rfc?.riwayatRevisi || [])
})

const showRiwayat = ref(false)

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
  // Seksi 7 — Penanggung Jawab OPD (Kepala Bidang)
  nama_penanggung_jawab:          '',
  nip_penanggung_jawab:           '',
  tanda_tangan_penanggung_jawab:  null,
})

const errors       = reactive({})
const isSubmitting = ref(false)
const isSavingDraf = ref(false)
const selectedFiles= ref([])    // [{file, status:'ready'|'uploading'|'done', progress:0}]
const existingFiles= ref([])    // [{url, nama, path}] - file existing dari draft
const isDragOver   = ref(false)
const ttdLoading   = ref(false)
const ttdFailed    = ref(false)
const ttdPjLoading = ref(false)
const fileInputRef = ref(null)
const successModal = reactive({ show: false, kodePelacakan: '' })

// -- Auto-isi dari profil user yang login --------------------------------------
onMounted(() => {
  if (props.mode === 'edit' && props.rfc) {
    // Pre-fill SEMUA field form dari data RFC yang tersimpan di database
    const rfcData = props.rfc

    console.log('[RFC Edit] props.rfc =', rfcData)

    // Seksi 1 — semua field di-assign langsung (nullable pakai ?? '')
    form.nama_pemohon       = rfcData.nama_pemohon       ?? ''
    form.nip                = rfcData.nip                ?? ''
    form.unit_kerja         = rfcData.unit_kerja         ?? ''
    form.opd_id             = rfcData.opd_id             ?? null
    form.nomor_surat        = rfcData.nomor_surat        ?? ''
    form.nomor_kontak       = rfcData.nomor_kontak       ?? ''
    form.email_dinas        = rfcData.email_dinas        ?? ''
    form.tanggal_permohonan = rfcData.tanggal_permohonan ?? today

    // Seksi 2
    form.kategori_id = rfcData.kategori_id ?? null
    if (rfcData.jenis_perubahan) {
      if (Array.isArray(rfcData.jenis_perubahan)) {
        form.jenis_perubahan = rfcData.jenis_perubahan
      } else {
        try { form.jenis_perubahan = JSON.parse(rfcData.jenis_perubahan) } catch { form.jenis_perubahan = [] }
      }
    }

    // Seksi 3
    form.deskripsi_perubahan = rfcData.deskripsi_perubahan ?? ''
    form.latar_belakang      = rfcData.latar_belakang      ?? ''
    form.rincian_perubahan   = rfcData.rincian_perubahan   ?? ''

    // Seksi 4
    form.urgensi                = rfcData.urgensi                ?? 'sedang'
    form.risiko_tidak_dilakukan = rfcData.risiko_tidak_dilakukan ?? ''
    form.kriteria_risiko        = rfcData.kriteria_risiko        ?? ''
    form.keterangan_risiko      = rfcData.keterangan_risiko      ?? ''
    form.rencana_pemulihan      = rfcData.rencana_pemulihan      ?? ''

    // Seksi 5
    form.solusi_diharapkan    = rfcData.solusi_diharapkan    ?? ''
    form.risiko_perubahan     = rfcData.risiko_perubahan     ?? ''
    form.alternatif_perubahan = rfcData.alternatif_perubahan ?? ''
    form.biaya_perubahan      = rfcData.biaya_perubahan      ?? ''
    form.waktu_perubahan      = rfcData.waktu_perubahan      ?? ''

    // Seksi 6
    form.nama_aplikasi      = rfcData.nama_aplikasi      ?? ''
    form.deskripsi_aplikasi = rfcData.deskripsi_aplikasi ?? ''
    form.alamat_aplikasi    = rfcData.alamat_aplikasi    ?? ''
    form.alamat_repository  = rfcData.alamat_repository  ?? ''

    // Seksi 7 — tanda tangan existing
    form.tanda_tangan = rfcData.tanda_tangan ?? null

    // Seksi 7 — Penanggung Jawab OPD
    form.nama_penanggung_jawab          = rfcData.nama_penanggung_jawab ?? ''
    form.nip_penanggung_jawab           = rfcData.nip_penanggung_jawab  ?? ''
    form.tanda_tangan_penanggung_jawab  = rfcData.tanda_tangan_penanggung_jawab ?? null

    // Override opd_id dari opdUser jika ada
    if (props.opdUser) form.opd_id = props.opdUser.id

    // Lampiran existing
    if (rfcData.lampiran_existing && rfcData.lampiran_existing.length > 0) {
      existingFiles.value = rfcData.lampiran_existing
    }

    // Load tanda tangan: jika URL /storage/..., fetch sebagai base64 untuk ditampilkan di canvas
    if (rfcData.tanda_tangan && rfcData.tanda_tangan.startsWith('/')) {
      ttdLoading.value = true
      fetch(rfcData.tanda_tangan)
        .then(r => {
          if (!r.ok) throw new Error('not found')
          return r.blob()
        })
        .then(blob => {
          const reader = new FileReader()
          reader.onloadend = () => {
            form.tanda_tangan = reader.result
            ttdLoading.value = false
          }
          reader.readAsDataURL(blob)
        })
        .catch(() => {
          ttdLoading.value = false
          ttdFailed.value = true
          form.tanda_tangan = null
        })
    }

    // Load tanda tangan Penanggung Jawab OPD jika ada URL
    if (rfcData.tanda_tangan_penanggung_jawab && rfcData.tanda_tangan_penanggung_jawab.startsWith('/')) {
      ttdPjLoading.value = true
      fetch(rfcData.tanda_tangan_penanggung_jawab)
        .then(r => {
          if (!r.ok) throw new Error('not found')
          return r.blob()
        })
        .then(blob => {
          const reader = new FileReader()
          reader.onloadend = () => {
            form.tanda_tangan_penanggung_jawab = reader.result
            ttdPjLoading.value = false
          }
          reader.readAsDataURL(blob)
        })
        .catch(() => {
          ttdPjLoading.value = false
          form.tanda_tangan_penanggung_jawab = null
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

function triggerFileInput() { fileInputRef.value?.click() }

function handleFileChange(e) {
  addFiles(Array.from(e.target.files))
  e.target.value = ''
}

function handleFileDrop(e) {
  addFiles(Array.from(e.dataTransfer.files))
}

function addFiles(newFiles) {
  const allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/zip',
    'image/png',
    'image/jpeg',
  ]
  for (const file of newFiles) {
    const total = existingFiles.value.length + selectedFiles.value.length
    if (total >= 5) { errors.lampiran = 'Maksimal 5 file.'; break }
    if (!allowedTypes.includes(file.type)) {
      errors.lampiran = `Format "${file.name}" tidak didukung.`; continue
    }
    if (file.size > 10 * 1024 * 1024) {
      errors.lampiran = `"${file.name}" melebihi 10MB.`; continue
    }
    errors.lampiran = ''
    // Simulasi progress saat file dipilih
    const item = { file, status: 'ready', progress: 0 }
    selectedFiles.value.push(item)
    // Animasi progress bar "ready" seolah sedang divalidasi
    setTimeout(() => { item.status = 'uploading'; item.progress = 30 }, 100)
    setTimeout(() => { item.progress = 70 }, 300)
    setTimeout(() => { item.progress = 100; item.status = 'done' }, 600)
  }
}

function removeFile(idx) { selectedFiles.value.splice(idx, 1) }

function removeExistingFile(idx) { existingFiles.value.splice(idx, 1) }

function formatFileSize(bytes) {
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
}

function getFileIcon(name) {
  const ext = name.split('.').pop()?.toLowerCase()
  if (['pdf'].includes(ext)) return 'lucide:file-text'
  if (['doc','docx'].includes(ext)) return 'lucide:file-type-2'
  if (['xls','xlsx'].includes(ext)) return 'lucide:sheet'
  if (['png','jpg','jpeg'].includes(ext)) return 'lucide:image'
  if (['zip','rar'].includes(ext)) return 'lucide:archive'
  return 'lucide:file'
}

function getFileIconClass(name) {
  const ext = name.split('.').pop()?.toLowerCase()
  if (['pdf'].includes(ext)) return 'bg-red-100 text-red-600'
  if (['doc','docx'].includes(ext)) return 'bg-blue-100 text-blue-600'
  if (['xls','xlsx'].includes(ext)) return 'bg-emerald-100 text-emerald-600'
  if (['png','jpg','jpeg'].includes(ext)) return 'bg-purple-100 text-purple-600'
  if (['zip','rar'].includes(ext)) return 'bg-amber-100 text-amber-600'
  return 'bg-slate-100 text-slate-600'
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

  // File baru (ambil .file dari objek {file, status, progress})
  selectedFiles.value.forEach(item => {
    const f = item.file ?? item
    data.append('lampiran[]', f)
  })

  // Kirim path lampiran existing yang masih dipertahankan (mode edit)
  existingFiles.value.forEach(f => data.append('lampiran_existing[]', f.path))

  return data
}

// -- Simpan Draf ---------------------------------------------------------------
async function simpanDraf() {
  if (isPerluRevisi.value) return
  isSavingDraf.value = true
  try {
    let res
    if (props.mode === 'edit' && props.rfc) {
      // Mode edit: POST dengan _method=PUT ke /rfc/{id}
      const data = buildFormData(true)   // pakai buildFormData agar file & existing ikut
      data.append('_method', 'PUT')
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
  if (!form.tanda_tangan)               { errors.tanda_tangan = 'Tanda tangan digital pemohon wajib dibuat.'; valid = false }
  if (!form.nama_penanggung_jawab?.trim()) { errors.nama_penanggung_jawab = 'Nama Kepala Bidang OPD wajib diisi.'; valid = false }
  if (!form.nip_penanggung_jawab?.trim())  { errors.nip_penanggung_jawab = 'NIP Kepala Bidang OPD wajib diisi.'; valid = false }
  if (!form.tanda_tangan_penanggung_jawab) { errors.tanda_tangan_penanggung_jawab = 'Tanda tangan Kepala Bidang OPD wajib dibuat.'; valid = false }

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
      // Mode edit: POST dengan _method=PUT ke /rfc/{id}
      const data = buildFormData(false)  // pakai buildFormData agar file & existing ikut
      data.append('_method', 'PUT')
      res = await axios.post(`/rfc/${props.rfc.id}`, data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      // Redirect ke workspace RFC
      window.location.href = `/rfc/${props.rfc.kode_pelacakan || res.data?.kode_pelacakan}/workspace`
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