<template>
  <AppLayout>
    <div class="space-y-6 max-w-[960px] mx-auto pb-16 px-2 sm:px-4">

      <!-- TOOLBAR ATAS: Navigasi & Aksi Cetak (Sembunyi saat Print) -->
      <div class="no-print pt-4 flex flex-wrap items-center justify-between gap-4">
        <div>
          <!-- Breadcrumb -->
          <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1">
            <Link href="/dashboard" class="hover:text-[#0757A8]">Dashboard</Link>
            <span>›</span>
            <Link :href="`/rfc/${rfcData.kode_pelacakan}/workspace`" class="hover:text-[#0757A8] font-mono">
              {{ rfcData.kode_pelacakan }}
            </Link>
            <span>›</span>
            <span class="text-slate-700 font-semibold">Dokumen FR-015</span>
          </div>

          <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Dokumen Resmi FR-015 (Standar A4)
          </h1>
          <p class="text-xs text-slate-500 mt-0.5">
            Sesuai format resmi template Microsoft Word Diskominfo Prov. Jawa Barat
          </p>
        </div>

        <div class="flex items-center gap-2.5">
          <!-- Tombol Kembali ke Workspace -->
          <Link
            :href="`/rfc/${rfcData.kode_pelacakan}/workspace`"
            class="px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs"
          >
            <Icon icon="lucide:arrow-left" class="w-4 h-4" />
            <span>Kembali ke Workspace</span>
          </Link>

          <!-- Tombol Unduh PDF / Cetak Dokumen -->
          <button
            type="button"
            @click="unduhPdf"
            class="px-4 py-2 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer"
          >
            <Icon icon="lucide:printer" class="w-4 h-4" />
            <span>Unduh PDF FR-015 (A4)</span>
          </button>
        </div>
      </div>

      <!-- KERTAS PRATINJAU DOKUMEN BERUKURAN STANDAR A4 -->
      <div class="w-full flex justify-center py-2">
        <div
          id="printable-fr015"
          class="bg-white border border-slate-300 shadow-xl text-black a4-sheet"
          style="width: 190mm; min-height: 275mm; box-sizing: border-box; background: #ffffff; font-family: Arial, sans-serif; font-size: 8.5pt; line-height: 1.25; color: #000000;"
        >
          <!-- =======================================================
               KOP / HEADER BOX STANDAR FR-015 (23mm | 107mm | 60mm = 190mm)
               ======================================================= -->
          <table
            class="fr015-kop-table"
            cellpadding="0"
            cellspacing="0"
            style="width: 190mm; border-collapse: collapse; border: 1.5px solid #000; table-layout: fixed; margin: 0;"
          >
            <colgroup>
              <col style="width: 23mm;" />
              <col style="width: 107mm;" />
              <col style="width: 60mm;" />
            </colgroup>
            <tbody>
              <tr>
                <!-- Kolom 1: Logo Pemprov Jabar -->
                <td
                  style="width: 23mm; border: 1px solid #000; padding: 4px 2px; text-align: center; vertical-align: middle; background: #ffffff;"
                >
                  <img
                    src="/images/assets/logo_fr015_jabar.png"
                    alt="Logo Pemprov Jabar"
                    style="max-height: 48px; max-width: 54px; width: auto; height: auto; display: block; margin: 0 auto; object-fit: contain;"
                    @error="$event.target.src = '/images/assets/logo_diskominfo_jabar.png'"
                  />
                </td>

                <!-- Kolom 2: Judul Formulir -->
                <td
                  style="width: 107mm; border: 1px solid #000; padding: 6px 8px; text-align: center; vertical-align: middle; font-weight: bold; font-size: 11pt; letter-spacing: 0.5px; background: #ffffff;"
                >
                  FORMULIR PERMINTAAN PERUBAHAN TI
                </td>

                <!-- Kolom 3: Metadata Dokumen ISO SMKI -->
                <td
                  style="width: 60mm; border: 1px solid #000; padding: 4px 6px; vertical-align: middle; font-size: 7.5pt; line-height: 1.3; background: #ffffff;"
                >
                  <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; border: none; margin: 0;">
                    <tbody>
                      <tr>
                        <td style="border: none; padding: 1px 0; font-weight: bold; width: 25mm; white-space: nowrap;">No. Dokumen</td>
                        <td style="border: none; padding: 1px 2px; width: 3mm; text-align: center;">:</td>
                        <td style="border: none; padding: 1px 0; width: 32mm; font-family: monospace; font-size: 7pt; white-space: nowrap;">FR-015/KOM.03.05/<br>SANDIKAMI</td>
                      </tr>
                      <tr>
                        <td style="border: none; padding: 1px 0; font-weight: bold;">No. Revisi</td>
                        <td style="border: none; padding: 1px 2px; text-align: center;">:</td>
                        <td style="border: none; padding: 1px 0;">1.1</td>
                      </tr>
                      <tr>
                        <td style="border: none; padding: 1px 0; font-weight: bold; white-space: nowrap;">Tanggal Berlaku</td>
                        <td style="border: none; padding: 1px 2px; text-align: center;">:</td>
                        <td style="border: none; padding: 1px 0; white-space: nowrap;">28 April 2022</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- =======================================================
               TABEL UTAMA FORMULIR FR-015 (63mm Label | 127mm Data = 190mm)
               ======================================================= -->
          <table
            class="fr015-main-table"
            cellpadding="0"
            cellspacing="0"
            style="width: 190mm; border-collapse: collapse; border: 1.5px solid #000; border-top: none; table-layout: fixed; font-size: 8.5pt; margin: 0;"
          >
            <colgroup>
              <col style="width: 63mm;" />
              <col style="width: 127mm;" />
            </colgroup>
            <tbody>
              <!-- Row No. RFC -->
              <tr>
                <td colspan="2" style="border: 1px solid #000; padding: 4px 8px; font-weight: bold; background: #ffffff; font-size: 9pt;">
                  No. RFC : <span style="font-family: monospace; font-size: 9.5pt; font-weight: bold; margin-left: 4px;">{{ rfcData.kode_pelacakan || '-' }}</span>
                </td>
              </tr>

              <!-- Header Seksi I: Informasi Pemohon -->
              <tr class="section-header">
                <td colspan="2" style="border: 1px solid #000; padding: 3px 8px; font-weight: bold; font-size: 8.5pt; background: #E5E7EB; text-transform: uppercase; letter-spacing: 0.5px;">
                  Informasi Pemohon
                </td>
              </tr>

              <!-- Row Pemohon -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Pemohon
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff;">
                  <span style="font-weight: 600;">{{ rfcData.nama_pemohon || '-' }}</span>
                  <span v-if="rfcData.nip" style="display: block; font-size: 8pt; color: #4B5563; margin-top: 1px;">
                    NIP. {{ rfcData.nip }}
                  </span>
                </td>
              </tr>

              <!-- Row Unit Kerja -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Unit Kerja
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff;">
                  {{ rfcData.unit_kerja || '-' }}
                </td>
              </tr>

              <!-- Row Perangkat Daerah -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Perangkat Daerah
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff;">
                  {{ rfcData.opd || '-' }}
                </td>
              </tr>

              <!-- Row Nomor Surat / Contact Person -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Nomor Surat/Contact Person
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff;">
                  <span v-if="rfcData.nomor_surat">No: {{ rfcData.nomor_surat }} • </span>
                  <span>{{ rfcData.nomor_kontak || '-' }}</span>
                  <span v-if="rfcData.email_dinas" style="color: #4B5563;"> ({{ rfcData.email_dinas }})</span>
                </td>
              </tr>

              <!-- Header Seksi II: Informasi Data -->
              <tr class="section-header">
                <td colspan="2" style="border: 1px solid #000; padding: 3px 8px; font-weight: bold; font-size: 8.5pt; background: #E5E7EB; text-transform: uppercase; letter-spacing: 0.5px;">
                  Informasi Data
                </td>
              </tr>

              <!-- Row Jenis Perubahan (Checkbox Sesuai Template Word) -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 4px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Jenis Perubahan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 4px 8px; vertical-align: top; background: #ffffff;">
                  <div style="display: flex; flex-wrap: wrap; gap: 4px 18px; align-items: center;">
                    <span style="display: inline-flex; align-items: center; gap: 4px;">
                      <span style="font-family: monospace; font-size: 10pt; font-weight: bold;">{{ hasJenis('permintaan_baru') ? '☑' : '☐' }}</span>
                      <span>Permintaan Baru</span>
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 4px;">
                      <span style="font-family: monospace; font-size: 10pt; font-weight: bold;">{{ hasJenis('peningkatan_sistem') ? '☑' : '☐' }}</span>
                      <span>Peningkatan Sistem</span>
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 4px;">
                      <span style="font-family: monospace; font-size: 10pt; font-weight: bold;">{{ hasJenis('perbaikan_sistem') ? '☑' : '☐' }}</span>
                      <span>Perbaikan Sistem</span>
                    </span>
                  </div>
                </td>
              </tr>

              <!-- Row Perubahan yang diharapkan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 4px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Perubahan yang diharapkan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 4px 8px; vertical-align: top; background: #ffffff; line-height: 1.3;">
                  <div v-if="rfcData.nama_aplikasi" style="font-weight: bold; color: #1E3A8A; margin-bottom: 2px;">
                    Sistem/Aplikasi: {{ rfcData.nama_aplikasi }}
                  </div>
                  <div>{{ rfcData.deskripsi || rfcData.deskripsi_perubahan || '-' }}</div>
                </td>
              </tr>

              <!-- Row Latar belakang perubahan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 4px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Latar belakang perubahan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 4px 8px; vertical-align: top; background: #ffffff; line-height: 1.3; white-space: pre-wrap;">
                  {{ rfcData.latar_belakang || '-' }}
                </td>
              </tr>

              <!-- Row Rincian atas perubahan yang diajukan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 4px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Rincian atas perubahan yang diajukan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 4px 8px; vertical-align: top; background: #ffffff; line-height: 1.3; white-space: pre-wrap;">
                  {{ rfcData.rincian_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row Risiko terkait bila perubahan tidak dilakukan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 4px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Risiko terkait bila perubahan tidak dilakukan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 4px 8px; vertical-align: top; background: #ffffff; line-height: 1.3;">
                  <div style="font-weight: bold; margin-bottom: 2px;">Kriteria:</div>
                  <div style="display: flex; flex-wrap: wrap; gap: 3px 12px; margin-bottom: 4px; font-size: 8pt;">
                    <span style="display: inline-flex; align-items: center; gap: 3px;">
                      <span style="font-family: monospace; font-size: 9.5pt; font-weight: bold;">{{ isKriteria('malapetaka') ? '☑' : '☐' }}</span>
                      <span>Malapetaka</span>
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 3px;">
                      <span style="font-family: monospace; font-size: 9.5pt; font-weight: bold;">{{ isKriteria('sangat_berat') ? '☑' : '☐' }}</span>
                      <span>Sangat Berat</span>
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 3px;">
                      <span style="font-family: monospace; font-size: 9.5pt; font-weight: bold;">{{ isKriteria('berat') ? '☑' : '☐' }}</span>
                      <span>Berat</span>
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 3px;">
                      <span style="font-family: monospace; font-size: 9.5pt; font-weight: bold;">{{ isKriteria('agak_berat') ? '☑' : '☐' }}</span>
                      <span>Agak Berat</span>
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 3px;">
                      <span style="font-family: monospace; font-size: 9.5pt; font-weight: bold;">{{ isKriteria('tidak_berat') ? '☑' : '☐' }}</span>
                      <span>Tidak Berat</span>
                    </span>
                  </div>

                  <div style="font-weight: bold; margin-bottom: 1px;">Keterangan:</div>
                  <div>{{ rfcData.risiko_tidak_dilakukan || rfcData.keterangan_risiko || '-' }}</div>
                  <div v-if="rfcData.rencana_pemulihan" style="font-size: 8pt; color: #4B5563; margin-top: 2px;">
                    <strong>Rencana Pemulihan:</strong> {{ rfcData.rencana_pemulihan }}
                  </div>
                </td>
              </tr>

              <!-- Row Solusi yang diharapkan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 4px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Solusi yang diharapkan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 4px 8px; vertical-align: top; background: #ffffff; line-height: 1.3;">
                  {{ rfcData.solusi_diharapkan || '-' }}
                </td>
              </tr>

              <!-- Row Risiko Perubahan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Risiko Perubahan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff; line-height: 1.3;">
                  {{ rfcData.risiko_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row Alternatif Perubahan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Alternatif Perubahan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff; line-height: 1.3;">
                  {{ rfcData.alternatif_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row Biaya Perubahan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Biaya Perubahan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff;">
                  {{ rfcData.biaya_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row Waktu Perubahan -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Waktu Perubahan
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff;">
                  {{ rfcData.waktu_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row Lampiran -->
              <tr>
                <td style="width: 63mm; border: 1px solid #000; padding: 3.5px 8px; font-weight: bold; vertical-align: top; background: #ffffff;">
                  Lampiran
                </td>
                <td style="width: 127mm; border: 1px solid #000; padding: 3.5px 8px; vertical-align: top; background: #ffffff;">
                  <div v-if="rfcData.lampiran_urls && rfcData.lampiran_urls.length > 0">
                    <div v-for="(fileUrl, fIdx) in rfcData.lampiran_urls" :key="fIdx" style="font-size: 8pt; margin-bottom: 2px;">
                      • <a :href="fileUrl" target="_blank" style="color: #1D4ED8; text-decoration: underline;">Dokumen Lampiran {{ fIdx + 1 }}</a>
                    </div>
                  </div>
                  <div v-else style="color: #6B7280; font-style: italic; font-size: 8pt;">
                    Terlampir pada berkas digital sistem SI-RFC
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- =======================================================
               BAGIAN TANDA TANGAN 2x2 SESUAI TEMPLATE ASLI WORD (95mm | 95mm = 190mm)
               Hanya Pemohon dan Kabid OPD Pemohon yang bertanda tangan
               Koordinator Agen & Agen masih menunggu proses
               ======================================================= -->
          <table
            class="fr015-ttd-table"
            cellpadding="0"
            cellspacing="0"
            style="width: 190mm; border-collapse: collapse; border: 1.5px solid #000; border-top: none; table-layout: fixed; font-size: 8pt; page-break-inside: avoid; margin: 0;"
          >
            <colgroup>
              <col style="width: 95mm;" />
              <col style="width: 95mm;" />
            </colgroup>
            <tbody>
              <!-- BARIS ATAS TTD: Pemohon (Kiri) & Koordinator Agen (Kanan) -->
              <tr>
                <!-- KIRI ATAS: Pemohon (DIBUAT OLEH - SUDAH TTD) -->
                <td style="width: 95mm; border: 1px solid #000; padding: 5px 8px; vertical-align: top; background: #ffffff;">
                  <div style="font-size: 7.5pt; color: #374151; margin-bottom: 1px;">
                    Tanggal: {{ rfcData.tanggal_permohonan || '-' }}
                  </div>
                  <div style="font-weight: bold; font-size: 8.5pt;">Dibuat Oleh:</div>
                  <div style="font-size: 8.5pt; margin-bottom: 2px;">Pemohon,</div>

                  <!-- Tanda Tangan Digital Pemohon -->
                  <div style="height: 44px; display: flex; align-items: center; justify-content: center; margin: 3px 0;">
                    <img
                      v-if="rfcData.tanda_tangan_url"
                      :src="rfcData.tanda_tangan_url"
                      alt="Tanda Tangan Pemohon"
                      style="max-height: 42px; max-width: 150px; object-fit: contain;"
                    />
                    <div v-else style="font-size: 7.5pt; color: #9CA3AF; font-style: italic;">
                      (Tanda Tangan Digital Pemohon)
                    </div>
                  </div>

                  <div style="margin-top: 3px; padding-top: 2px; border-top: 1px solid #000; text-align: center;">
                    <div style="font-weight: bold; font-size: 8.5pt;">( {{ rfcData.nama_pemohon || '.........................' }} )</div>
                    <div v-if="rfcData.nip" style="font-size: 7.5pt; color: #4B5563;">NIP. {{ rfcData.nip }}</div>
                  </div>
                </td>

                <!-- KANAN ATAS: Koordinator Agen (DITERIMA OLEH - dinamis dari pimpinan) -->
                <td style="width: 95mm; border: 1px solid #000; padding: 5px 8px; vertical-align: top; background: #ffffff;">
                  <div style="font-size: 7.5pt; color: #374151; margin-bottom: 1px;">
                    Tanggal: {{ rfcData.persetujuan ? (rfcData.persetujuan.diputuskan_pada_tanggal || rfcData.persetujuan.diputuskan_pada || '........................................') : '........................................' }}
                  </div>
                  <div style="font-weight: bold; font-size: 8.5pt;">Diterima Oleh:</div>
                  <div style="font-size: 8.5pt; margin-bottom: 2px;">Koordinator Agen,</div>

                  <!-- TTD Pimpinan: tampil jika sudah ada persetujuan -->
                  <div style="height: 44px; display: flex; align-items: center; justify-content: center; margin: 3px 0;">
                    <img
                      v-if="rfcData.persetujuan && rfcData.persetujuan.tanda_tangan_pimpinan_url"
                      :src="rfcData.persetujuan.tanda_tangan_pimpinan_url"
                      alt="Tanda Tangan Pimpinan"
                      style="max-height: 42px; max-width: 150px; object-fit: contain;"
                    />
                    <div v-else-if="rfcData.persetujuan" style="text-align: center;">
                      <div style="font-size: 7pt; font-weight: bold; color: #047857; letter-spacing: 0.5px;">✓ DISETUJUI</div>
                      <div style="font-size: 6.5pt; color: #15803D;">{{ rfcData.persetujuan.diputuskan_pada }}</div>
                    </div>
                    <span v-else style="color: #9CA3AF; font-size: 7.5pt; font-style: italic;">
                      (Menunggu verifikasi agen Diskominfo)
                    </span>
                  </div>

                  <div style="margin-top: 3px; padding-top: 2px; border-top: 1px solid #000; text-align: center;">
                    <div style="font-weight: bold; font-size: 8.5pt;">( {{ rfcData.persetujuan ? rfcData.persetujuan.pimpinan : '………………………………………' }} )</div>
                    <div v-if="rfcData.persetujuan && rfcData.persetujuan.pimpinan_nip" style="font-size: 7.5pt; color: #4B5563;">NIP. {{ rfcData.persetujuan.pimpinan_nip }}</div>
                    <div v-else style="font-size: 7.5pt; color: #6B7280;">Koordinator Agen Diskominfo</div>
                  </div>
                </td>
              </tr>

              <!-- BARIS BAWAH TTD: Penanggung Jawab OPD (Kiri) & Pelaksana Agen (Kanan) -->
              <tr>
                <!-- KIRI BAWAH: Penanggung Jawab Perangkat (KABID OPD PEMOHON - dinamis dari input pemohon) -->
                <td style="width: 95mm; border: 1px solid #000; padding: 5px 8px; vertical-align: top; background: #ffffff;">
                  <div style="font-size: 7.5pt; color: #374151; margin-bottom: 1px;">
                    Tanggal: {{ rfcData.tanggal_permohonan || '-' }}
                  </div>
                  <div style="font-weight: bold; font-size: 8.5pt;">Disetujui Oleh:</div>
                  <div style="font-size: 8.5pt; font-weight: bold;">Penanggung Jawab Perangkat Daerah,</div>
                  <div style="font-size: 7.5pt; color: #4B5563; margin-bottom: 1px;">
                    Kepala Bidang {{ rfcData.unit_kerja || 'OPD Pemohon' }}
                  </div>

                  <!-- TTD Kabid OPD: gunakan tanda tangan dari input Create.vue -->
                  <div style="height: 44px; display: flex; align-items: center; justify-content: center; margin: 3px 0;">
                    <img
                      v-if="rfcData.tanda_tangan_penanggung_jawab_url"
                      :src="rfcData.tanda_tangan_penanggung_jawab_url"
                      alt="Tanda Tangan Kepala Bidang OPD"
                      style="max-height: 42px; max-width: 150px; object-fit: contain;"
                    />
                    <div v-else style="text-align: center; background: #F0FDF4; border: 1px dashed #86EFAC; border-radius: 4px; padding: 4px 8px; width: 100%;">
                      <div style="font-size: 7pt; font-weight: bold; color: #166534; letter-spacing: 0.5px;">
                        ✓ TERVERIFIKASI KEDINASAN OPD
                      </div>
                      <div style="font-size: 6.5pt; color: #15803D; font-family: monospace;">
                        Disetujui Kepala Bidang Kedinasan
                      </div>
                    </div>
                  </div>

                  <div style="margin-top: 3px; padding-top: 2px; border-top: 1px solid #000; text-align: center;">
                    <div style="font-weight: bold; font-size: 8.5pt;">( {{ rfcData.nama_penanggung_jawab || namaKabidOpd }} )</div>
                    <div style="font-size: 7.5pt; color: #4B5563;">NIP. {{ rfcData.nip_penanggung_jawab || nipKabidOpd }}</div>
                  </div>
                </td>

                <!-- KANAN BAWAH: Agen Pelaksana (DILAKSANAKAN OLEH - dinamis dari teknisi/BAST) -->
                <td style="width: 95mm; border: 1px solid #000; padding: 5px 8px; vertical-align: top; background: #ffffff;">
                  <div style="font-size: 7.5pt; color: #374151; margin-bottom: 1px;">
                    Tanggal: {{ rfcData.bast ? (rfcData.bast.dibuat_pada_tanggal || rfcData.bast.dibuat_pada || '........................................') : '........................................' }}
                  </div>
                  <div style="font-weight: bold; font-size: 8.5pt;">Dilaksanakan Oleh:</div>
                  <div style="font-size: 8.5pt; margin-bottom: 2px;">Agen,</div>

                  <!-- TTD Teknisi: tampil jika BAST sudah ada -->
                  <div style="height: 44px; display: flex; align-items: center; justify-content: center; margin: 3px 0;">
                    <img
                      v-if="rfcData.bast && rfcData.bast.tanda_tangan_teknisi_url"
                      :src="rfcData.bast.tanda_tangan_teknisi_url"
                      alt="Tanda Tangan Teknisi"
                      style="max-height: 42px; max-width: 150px; object-fit: contain;"
                    />
                    <div v-else-if="rfcData.bast" style="text-align: center;">
                      <div style="font-size: 7pt; font-weight: bold; color: #0e7490; letter-spacing: 0.5px;">✓ SELESAI DIKERJAKAN</div>
                      <div style="font-size: 6.5pt; color: #0e7490;">BAST: {{ rfcData.bast.nomor_dokumen }}</div>
                    </div>
                    <span v-else style="color: #9CA3AF; font-size: 7.5pt; font-style: italic;">
                      (Menunggu pelaksanaan teknis agen)
                    </span>
                  </div>

                  <div style="margin-top: 3px; padding-top: 2px; border-top: 1px solid #000; text-align: center;">
                    <div style="font-weight: bold; font-size: 8.5pt;">( {{ rfcData.bast ? rfcData.bast.nama_teknisi : '………………………………………' }} )</div>
                    <div v-if="rfcData.bast && rfcData.bast.nip_teknisi !== '-'" style="font-size: 7.5pt; color: #4B5563;">NIP. {{ rfcData.bast.nip_teknisi }}</div>
                    <div v-else style="font-size: 7.5pt; color: #6B7280;">Pelaksana Agen Diskominfo</div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Catatan Kaki Dokumen Asli -->
          <div style="margin-top: 6px; font-size: 7pt; color: #6B7280; display: flex; justify-content: space-between;">
            <span>FR-015 • Formulir Permintaan Perubahan TI</span>
            <span>Dokumen Resmi Sistem Informasi SI-RFC Prov. Jawa Barat</span>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
  rfc: { type: Object, default: () => null },
  doc: { type: Object, default: () => null },
})

const rfcData = computed(() => {
  return props.rfc ?? props.doc ?? {}
})

function hasJenis(val) {
  const jenis = rfcData.value.jenis_perubahan
  if (!jenis) return false
  if (Array.isArray(jenis)) return jenis.includes(val)
  if (typeof jenis === 'string') {
    try {
      const parsed = JSON.parse(jenis)
      if (Array.isArray(parsed)) return parsed.includes(val)
    } catch {
      return jenis.includes(val)
    }
  }
  return false
}

function isKriteria(val) {
  const k = (rfcData.value.kriteria_risiko || '').toLowerCase().replace(/\s+/g, '_')
  return k === val
}

const namaKabidOpd = computed(() => {
  // Prioritas: gunakan data aktual dari form pemohon
  if (rfcData.value.nama_penanggung_jawab) return rfcData.value.nama_penanggung_jawab
  // Fallback berdasarkan OPD
  const opd = (rfcData.value.opd || '').toLowerCase()
  if (opd.includes('komunikasi') || opd.includes('diskominfo')) return 'Drs. H. Iwan G., M.Si'
  if (opd.includes('bappeda')) return 'Dr. Ir. H. Ahmad Sudrajat, M.T.'
  if (opd.includes('dpmptsp') || opd.includes('perizinan')) return 'Dra. Hj. Ratna Sari, M.Si'
  if (opd.includes('bkd') || opd.includes('kepegawaian')) return 'H. Dedi Mulyadi, S.IP, M.Si'
  if (opd.includes('pemberdayaan') || opd.includes('perempuan') || opd.includes('anak')) return 'Dra. Hj. Nunung Nurjanah, M.Si'
  return 'Drs. H. Penanggung Jawab OPD, M.Si'
})

const nipKabidOpd = computed(() => {
  if (rfcData.value.nip_penanggung_jawab) return rfcData.value.nip_penanggung_jawab
  return '19760812 200103 1 004'
})

// Fungsi Cetak / Unduh PDF FR-015 dengan dokumen murni isolasi A4
function unduhPdf() {
  const printEl = document.getElementById('printable-fr015')
  if (!printEl) {
    window.print()
    return
  }

  let iframe = document.getElementById('fr015-print-frame')
  if (iframe) iframe.remove()

  iframe = document.createElement('iframe')
  iframe.id = 'fr015-print-frame'
  iframe.style.position = 'fixed'
  iframe.style.right = '0'
  iframe.style.bottom = '0'
  iframe.style.width = '0'
  iframe.style.height = '0'
  iframe.style.border = '0'
  document.body.appendChild(iframe)

  const doc = iframe.contentWindow.document
  doc.open()
  doc.write(`
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <title>FR-015 - ${rfcData.value.kode_pelacakan || 'Permohonan TI'}</title>
      <style>
        @page {
          size: A4 portrait;
          margin: 8mm 10mm 8mm 10mm;
        }
        *, *::before, *::after {
          box-sizing: border-box !important;
          -webkit-print-color-adjust: exact !important;
          print-color-adjust: exact !important;
        }
        html, body {
          width: 100% !important;
          margin: 0 !important;
          padding: 0 !important;
          background: #ffffff !important;
          color: #000000 !important;
          font-family: Arial, "Helvetica Neue", Helvetica, sans-serif !important;
          font-size: 8.5pt !important;
          line-height: 1.25 !important;
        }
        #printable-fr015 {
          width: 190mm !important;
          max-width: 190mm !important;
          margin: 0 auto !important;
          padding: 0 !important;
          background: #ffffff !important;
          border: none !important;
          box-shadow: none !important;
        }
        table {
          width: 190mm !important;
          border-collapse: collapse !important;
          table-layout: fixed !important;
          margin: 0 !important;
          padding: 0 !important;
        }
        th, td {
          border: 1px solid #000000 !important;
          word-wrap: break-word !important;
          overflow-wrap: break-word !important;
          word-break: break-word !important;
          vertical-align: top;
        }
        .section-header td {
          background-color: #E5E7EB !important;
          font-weight: bold !important;
          text-transform: uppercase !important;
          font-size: 8.5pt !important;
          padding: 3px 8px !important;
        }
        img {
          image-rendering: -webkit-optimize-contrast;
        }
        tr {
          page-break-inside: avoid;
        }
      </style>
    </head>
    <body>
      <div id="printable-fr015">
        ${printEl.innerHTML}
      </div>
    </body>
    </html>
  `)
  doc.close()

  setTimeout(() => {
    iframe.contentWindow.focus()
    iframe.contentWindow.print()
  }, 350)
}
</script>

<style>
/* Cetak langsung jika user tekan Ctrl + P pada halaman browser */
@media print {
  /* Sembunyikan semua elemen kecuali printable document */
  body * {
    visibility: hidden !important;
  }

  html,
  body {
    background: #ffffff !important;
    color: #000000 !important;
    margin: 0 !important;
    padding: 0 !important;
    height: auto !important;
    min-height: 0 !important;
    overflow: visible !important;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif !important;
  }

  #app,
  .min-h-screen,
  main,
  div {
    min-height: 0 !important;
    height: auto !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
    overflow: visible !important;
    display: block !important;
  }

  /* Tampilkan hanya printable-fr015 dan posisikan di ujung atas kertas (0, 0) */
  #printable-fr015,
  #printable-fr015 * {
    visibility: visible !important;
  }

  #printable-fr015 {
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    display: block !important;
    width: 190mm !important;
    max-width: 190mm !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
    box-shadow: none !important;
    border: none !important;
  }

  @page {
    size: A4 portrait;
    margin: 8mm 10mm 8mm 10mm;
  }

  table {
    border-collapse: collapse !important;
    width: 190mm !important;
    table-layout: fixed !important;
  }

  table, th, td {
    border: 1px solid #000000 !important;
  }

  .section-header td {
    background-color: #E5E7EB !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  tr {
    page-break-inside: avoid !important;
  }
}
</style>
