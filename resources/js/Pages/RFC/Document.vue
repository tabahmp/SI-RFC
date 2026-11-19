<template>
  <AppLayout>
    <div class="space-y-6 max-w-[960px] mx-auto pb-16 px-2 sm:px-4">

      <!-- TOOLBAR ATAS: Navigasi & Aksi Unduh PDF -->
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

          <!-- Tombol Cetak Browser -->
          <button
            type="button"
            @click="cetakDokumen"
            class="px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs cursor-pointer"
            title="Cetak dokumen sesuai template resmi FR-015 (Ctrl+P)"
          >
            <Icon icon="lucide:printer" class="w-4 h-4 text-slate-600" />
            <span>Cetak Dokumen</span>
          </button>

          <!-- Tombol Unduh PDF FR-015 Resmi -->
          <a
            :href="`/rfc/${rfcData.kode_pelacakan}/dokumen?download=1`"
            download
            class="px-4 py-2 rounded-xl bg-[#0757A8] hover:bg-[#063D78] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer no-underline"
            title="Unduh file PDF resmi FR-015 hasil konversi template Microsoft Word"
          >
            <Icon icon="lucide:download" class="w-4 h-4" />
            <span>Unduh PDF FR-015 (A4)</span>
          </a>
        </div>
      </div>

      <!-- KERTAS PRATINJAU DOKUMEN BERUKURAN STANDAR A4 PERSIS SESUAI TEMPLATE FR-015 -->
      <div id="printable-fr015" class="w-full flex flex-col items-center py-2">
        <div
          class="fr015-sheet bg-white border border-slate-300 shadow-xl text-black"
          style="width: 210mm; min-height: 297mm; padding: 14mm 10mm 14mm 10mm; box-sizing: border-box; background: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 10.5pt; line-height: 1.35; color: #000000;"
        >
          <!-- TABEL UTAMA DOKUMEN FR-015 DENGAN THEAD KOP BERULANG DI SETIAP HALAMAN CETAK -->
          <table
            class="fr015-main-table"
            cellpadding="0"
            cellspacing="0"
            style="width: 190mm; border-collapse: collapse; table-layout: fixed; font-size: 10.5pt; margin: 0 auto;"
          >
            <colgroup>
              <col style="width: 62.5mm;" />
              <col style="width: 32.5mm;" />
              <col style="width: 95mm;" />
            </colgroup>

            <!-- THEAD: Otomatis berulang di halaman 1, halaman 2, dst. saat dicetak -->
            <thead class="fr015-repeating-header">
              <!-- Spacer margin atas khusus saat print (agar tiap halaman punya jarak atas 12mm) -->
              <tr class="print-only-spacer-row">
                <td colspan="3" class="no-border-cell print-top-spacer" style="border: none !important; padding: 0; height: 0;"></td>
              </tr>

              <!-- Baris Kop Surat (5 Kolom x 3 Baris Persis Template Word) -->
              <tr class="kop-container-row">
                <td colspan="3" class="no-border-cell" style="border: none !important; padding: 0 0 5.5mm 0; background: #ffffff;">
                  <table
                    class="fr015-kop-table"
                    cellpadding="0"
                    cellspacing="0"
                    style="width: 190mm; border-collapse: collapse; table-layout: fixed; margin: 0;"
                  >
                    <colgroup>
                      <col style="width: 22.3mm;" />
                      <col style="width: 107.5mm;" />
                      <col style="width: 21.3mm;" />
                      <col style="width: 4.2mm;" />
                      <col style="width: 34.7mm;" />
                    </colgroup>
                    <tbody>
                      <tr>
                        <!-- Kolom 1: Logo Pemprov Jabar (Rowspan 3) -->
                        <td
                          rowspan="3"
                          style="border: 1px solid #000000; padding: 4px 2px; text-align: center; vertical-align: middle; background: #ffffff;"
                        >
                          <img
                            src="/images/assets/logo_fr015_jabar.png"
                            alt="Logo Pemprov Jabar"
                            style="max-height: 60px; max-width: 58px; width: auto; height: auto; display: block; margin: 0 auto; object-fit: contain;"
                          />
                        </td>

                        <!-- Kolom 2: Judul Formulir (Rowspan 3) -->
                        <td
                          rowspan="3"
                          style="border: 1px solid #000000; padding: 6px 8px; text-align: center; vertical-align: middle; font-weight: bold; font-size: 13.5pt; font-family: Arial, Helvetica, sans-serif; background: #ffffff; color: #000000;"
                        >
                          FORMULIR PERMINTAAN PERUBAHAN TI
                        </td>

                        <!-- Kolom 3-5 Baris 1: No. Dokumen -->
                        <td style="border: 1px solid #000000; padding: 3px 5px; font-size: 8pt; font-weight: normal; vertical-align: middle; background: #ffffff;">
                          No.<br />Dokumen
                        </td>
                        <td style="border: 1px solid #000000; padding: 3px 2px; font-size: 8pt; font-weight: normal; text-align: center; vertical-align: middle; background: #ffffff;">
                          :
                        </td>
                        <td style="border: 1px solid #000000; padding: 3px 5px; font-size: 8pt; font-weight: normal; vertical-align: middle; background: #ffffff;">
                          FR-015/KOM.03.05/<br />SANDIKAMI
                        </td>
                      </tr>
                      <tr>
                        <!-- Kolom 3-5 Baris 2: No. Revisi -->
                        <td style="border: 1px solid #000000; padding: 3px 5px; font-size: 8pt; font-weight: normal; vertical-align: middle; background: #ffffff;">
                          No. Revisi
                        </td>
                        <td style="border: 1px solid #000000; padding: 3px 2px; font-size: 8pt; font-weight: normal; text-align: center; vertical-align: middle; background: #ffffff;">
                          :
                        </td>
                        <td style="border: 1px solid #000000; padding: 3px 5px; font-size: 8pt; font-weight: normal; vertical-align: middle; background: #ffffff;">
                          1.1
                        </td>
                      </tr>
                      <tr>
                        <!-- Kolom 3-5 Baris 3: Tanggal Berlaku -->
                        <td style="border: 1px solid #000000; padding: 3px 5px; font-size: 8pt; font-weight: normal; vertical-align: middle; background: #ffffff;">
                          Tanggal<br />Berlaku
                        </td>
                        <td style="border: 1px solid #000000; padding: 3px 2px; font-size: 8pt; font-weight: normal; text-align: center; vertical-align: middle; background: #ffffff;">
                          :
                        </td>
                        <td style="border: 1px solid #000000; padding: 3px 5px; font-size: 8pt; font-weight: normal; vertical-align: middle; background: #ffffff;">
                          28 April 2022
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </thead>

            <!-- TFOOT: Menjaga jarak margin bawah di setiap halaman saat dicetak -->
            <tfoot class="fr015-repeating-footer">
              <tr class="print-only-spacer-row">
                <td colspan="3" class="no-border-cell print-bottom-spacer" style="border: none !important; padding: 0; height: 0;"></td>
              </tr>
            </tfoot>

            <tbody>
              <!-- Row 0: No. RFC (Tanpa border atas, kiri, kanan — persis template) -->
              <tr class="no-outer-border-row">
                <td
                  colspan="3"
                  class="no-top-left-right-border"
                  style="border-top: none !important; border-left: none !important; border-right: none !important; border-bottom: 1px solid #000000; padding: 2px 2px 5px 2px; font-weight: bold; background: #ffffff; font-size: 11pt;"
                >
                  No. RFC : {{ rfcData.kode_pelacakan || '' }}
                </td>
              </tr>

              <!-- Row 1: Header Seksi I: Informasi Pemohon (Navy #1F3864, Teks Putih, Rata Kiri) -->
              <tr class="navy-section-row">
                <td
                  colspan="3"
                  class="navy-section-cell"
                  style="border: 1px solid #000000; padding: 4.5px 8px; font-weight: bold; font-size: 11pt; background-color: #1F3864 !important; color: #FFFFFF !important; text-align: left; vertical-align: middle;"
                >
                  Informasi Pemohon
                </td>
              </tr>

              <!-- Row 2: Pemohon -->
              <tr>
                <td style="border: 1px solid #000000; padding: 5px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Pemohon
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; background: #ffffff;">
                  <div>{{ rfcData.nama_pemohon || '-' }}</div>
                  <div v-if="rfcData.nip" style="font-size: 9.5pt;">
                    NIP. {{ rfcData.nip }}
                  </div>
                </td>
              </tr>

              <!-- Row 3: Unit Kerja -->
              <tr>
                <td style="border: 1px solid #000000; padding: 5px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Unit Kerja
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; background: #ffffff;">
                  {{ rfcData.unit_kerja || '-' }}
                </td>
              </tr>

              <!-- Row 4: Perangkat Daerah -->
              <tr>
                <td style="border: 1px solid #000000; padding: 5px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Perangkat Daerah
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; background: #ffffff;">
                  {{ rfcData.opd || '-' }}
                </td>
              </tr>

              <!-- Row 5: Nomor Surat / Contact Person -->
              <tr>
                <td style="border: 1px solid #000000; padding: 5px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Nomor Surat/<i>Contact Person</i>
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; background: #ffffff;">
                  <span v-if="rfcData.nomor_surat">No: {{ rfcData.nomor_surat }} • </span>
                  <span>{{ rfcData.nomor_kontak || '-' }}</span>
                  <span v-if="rfcData.email_dinas"> ({{ rfcData.email_dinas }})</span>
                </td>
              </tr>

              <!-- Row 6: Header Seksi II: Informasi Data (Navy #1F3864, Teks Putih, Rata Kiri) -->
              <tr class="navy-section-row">
                <td
                  colspan="3"
                  class="navy-section-cell"
                  style="border: 1px solid #000000; padding: 4.5px 8px; font-weight: bold; font-size: 11pt; background-color: #1F3864 !important; color: #FFFFFF !important; text-align: left; vertical-align: middle;"
                >
                  Informasi Data
                </td>
              </tr>

              <!-- Row 7: Jenis Perubahan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 5px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Jenis Perubahan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; background: #ffffff;">
                  <span>{{ hasJenis('permintaan_baru') ? '☑' : '☐' }} Permintaan Baru</span>
                  <span style="margin-left: 14px;">{{ hasJenis('peningkatan_sistem') ? '☑' : '☐' }} Peningkatan Sistem</span>
                  <span style="margin-left: 14px;">{{ hasJenis('perbaikan_sistem') ? '☑' : '☐' }} Perbaikan Sistem</span>
                </td>
              </tr>

              <!-- Row 8: Perubahan yang diharapkan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Perubahan yang diharapkan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: top; background: #ffffff;">
                  <div v-if="rfcData.nama_aplikasi" style="font-weight: bold; margin-bottom: 2px;">
                    Sistem/Aplikasi: {{ rfcData.nama_aplikasi }}
                  </div>
                  <div>{{ rfcData.deskripsi || rfcData.deskripsi_perubahan || '-' }}</div>
                </td>
              </tr>

              <!-- Row 9: Latar belakang perubahan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Latar belakang perubahan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: top; background: #ffffff; white-space: pre-wrap;">
                  {{ rfcData.latar_belakang || '-' }}
                </td>
              </tr>

              <!-- Row 10: Rincian atas perubahan yang diajukan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Rincian atas perubahan<br />yang diajukan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: top; background: #ffffff; white-space: pre-wrap;">
                  {{ rfcData.rincian_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row 11: Risiko terkait bila perubahan tidak dilakukan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Risiko terkait bila perubahan<br />tidak dilakukan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: top; background: #ffffff;">
                  <div style="font-weight: bold; margin-bottom: 4px;">Kriteria:</div>
                  <table cellpadding="0" cellspacing="0" class="no-border-table" style="width: 100%; border: none !important; margin-bottom: 6px;">
                    <tbody>
                      <tr>
                        <td class="no-border-cell" style="border: none !important; padding: 1px 0; width: 36%;">
                          {{ isKriteria('malapetaka') ? '☑' : '☐' }} Malapetaka
                        </td>
                        <td class="no-border-cell" style="border: none !important; padding: 1px 0; width: 36%;">
                          {{ isKriteria('sangat_berat') ? '☑' : '☐' }} Sangat Berat
                        </td>
                        <td class="no-border-cell" style="border: none !important; padding: 1px 0; width: 28%;">
                          {{ isKriteria('berat') ? '☑' : '☐' }} Berat
                        </td>
                      </tr>
                      <tr>
                        <td class="no-border-cell" style="border: none !important; padding: 1px 0;">
                          {{ isKriteria('agak_berat') ? '☑' : '☐' }} Agak Berat
                        </td>
                        <td class="no-border-cell" style="border: none !important; padding: 1px 0;" colspan="2">
                          {{ isKriteria('tidak_berat') ? '☑' : '☐' }} Tidak Berat
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <div style="font-weight: bold; margin-bottom: 2px;">Keterangan:</div>
                  <div>{{ rfcData.risiko_tidak_dilakukan || rfcData.keterangan_risiko || '-' }}</div>
                  <div v-if="rfcData.rencana_pemulihan" style="margin-top: 3px;">
                    <strong>Rencana Pemulihan:</strong> {{ rfcData.rencana_pemulihan }}
                  </div>
                </td>
              </tr>

              <!-- Row 12: Solusi yang diharapkan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Solusi yang diharapkan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: top; background: #ffffff;">
                  {{ rfcData.solusi_diharapkan || '-' }}
                </td>
              </tr>

              <!-- Row 13: Risiko Perubahan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Risiko Perubahan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: top; background: #ffffff;">
                  {{ rfcData.risiko_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row 14: Alternatif Perubahan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Alternatif Perubahan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: top; background: #ffffff;">
                  {{ rfcData.alternatif_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row 15: Biaya Perubahan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Biaya Perubahan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; background: #ffffff;">
                  {{ rfcData.biaya_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row 16: Waktu Perubahan -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Waktu Perubahan
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; background: #ffffff;">
                  {{ rfcData.waktu_perubahan || '-' }}
                </td>
              </tr>

              <!-- Row 17: Lampiran -->
              <tr>
                <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold; vertical-align: middle; background: #ffffff;">
                  Lampiran
                </td>
                <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; background: #ffffff;">
                  <div v-if="rfcData.lampiran_urls && rfcData.lampiran_urls.length > 0">
                    <div v-for="(fileUrl, fIdx) in rfcData.lampiran_urls" :key="fIdx">
                      <a :href="fileUrl" target="_blank" style="color: #000000; text-decoration: none;">
                        Dokumen Lampiran {{ fIdx + 1 }}
                      </a>
                    </div>
                  </div>
                  <div v-else>
                    -
                  </div>
                </td>
              </tr>

              <!-- Row 18: Pemohon (Kiri 95mm) & Koordinator Agen (Kanan 95mm) — Rata Tengah Persis Template -->
              <tr style="page-break-inside: avoid; break-inside: avoid;">
                <!-- KIRI: Dibuat Oleh Pemohon -->
                <td colspan="2" style="width: 95mm; border: 1px solid #000000; padding: 8px 10px; text-align: center; vertical-align: top; background: #ffffff; font-size: 9.5pt;">
                  <div>Tanggal: {{ rfcData.tanggal_permohonan || '' }}</div>
                  <div>Dibuat Oleh:</div>
                  <div>Pemohon,</div>

                  <div style="height: 78px; display: flex; align-items: center; justify-content: center; margin: 4px auto;">
                    <img
                      v-if="rfcData.tanda_tangan_url"
                      :src="rfcData.tanda_tangan_url"
                      alt="Tanda Tangan Pemohon"
                      style="height: 74px; max-height: 74px; max-width: 220px; width: auto; object-fit: contain; display: block; margin: 0 auto;"
                    />
                  </div>

                  <div>( {{ rfcData.nama_pemohon || '……………………………' }} )</div>
                  <div v-if="rfcData.nip" style="font-size: 8.5pt;">NIP. {{ rfcData.nip }}</div>
                </td>

                <!-- KANAN: Diterima Oleh Koordinator Agen -->
                <td style="width: 95mm; border: 1px solid #000000; padding: 8px 10px; text-align: center; vertical-align: top; background: #ffffff; font-size: 9.5pt;">
                  <div>
                    Tanggal: {{ rfcData.persetujuan ? (rfcData.persetujuan.diputuskan_pada_tanggal || rfcData.persetujuan.diputuskan_pada || '') : '' }}
                  </div>
                  <div>Diterima Oleh:</div>
                  <div>Koordinator Agen,</div>

                  <div style="height: 78px; display: flex; align-items: center; justify-content: center; margin: 4px auto;">
                    <img
                      v-if="rfcData.persetujuan && rfcData.persetujuan.tanda_tangan_pimpinan_url"
                      :src="rfcData.persetujuan.tanda_tangan_pimpinan_url"
                      alt="Tanda Tangan Koordinator Agen"
                      style="height: 74px; max-height: 74px; max-width: 220px; width: auto; object-fit: contain; display: block; margin: 0 auto;"
                    />
                  </div>

                  <div>( {{ rfcData.persetujuan ? rfcData.persetujuan.pimpinan : '……………………………' }} )</div>
                  <div v-if="rfcData.persetujuan && rfcData.persetujuan.pimpinan_nip" style="font-size: 8.5pt;">
                    NIP. {{ rfcData.persetujuan.pimpinan_nip }}
                  </div>
                </td>
              </tr>

              <!-- Row 19: Penanggung Jawab Perangkat (Kiri 95mm) & Agen (Kanan 95mm) — Rata Tengah Persis Template -->
              <tr style="page-break-inside: avoid; break-inside: avoid;">
                <!-- KIRI: Disetujui Oleh Penanggung Jawab Perangkat -->
                <td colspan="2" style="width: 95mm; border: 1px solid #000000; padding: 8px 10px; text-align: center; vertical-align: top; background: #ffffff; font-size: 9.5pt;">
                  <div>Tanggal: {{ rfcData.tanggal_permohonan || '' }}</div>
                  <div>Disetujui Oleh:</div>
                  <div>Penanggung Jawab Perangkat,</div>

                  <div style="height: 78px; display: flex; align-items: center; justify-content: center; margin: 4px auto;">
                    <img
                      v-if="rfcData.tanda_tangan_penanggung_jawab_url"
                      :src="rfcData.tanda_tangan_penanggung_jawab_url"
                      alt="Tanda Tangan Penanggung Jawab"
                      style="height: 74px; max-height: 74px; max-width: 220px; width: auto; object-fit: contain; display: block; margin: 0 auto;"
                    />
                  </div>

                  <div>( {{ rfcData.nama_penanggung_jawab || '……………………………' }} )</div>
                  <div v-if="rfcData.nip_penanggung_jawab" style="font-size: 8.5pt;">
                    NIP. {{ rfcData.nip_penanggung_jawab }}
                  </div>
                </td>

                <!-- KANAN: Dilaksanakan Oleh Agen -->
                <td style="width: 95mm; border: 1px solid #000000; padding: 8px 10px; text-align: center; vertical-align: top; background: #ffffff; font-size: 9.5pt;">
                  <div>
                    Tanggal: {{ rfcData.bast ? (rfcData.bast.dibuat_pada_tanggal || rfcData.bast.dibuat_pada || '') : '' }}
                  </div>
                  <div>Dilaksanakan Oleh:</div>
                  <div>Agen,</div>

                  <div style="height: 78px; display: flex; align-items: center; justify-content: center; margin: 4px auto;">
                    <img
                      v-if="rfcData.bast && rfcData.bast.tanda_tangan_teknisi_url"
                      :src="rfcData.bast.tanda_tangan_teknisi_url"
                      alt="Tanda Tangan Agen"
                      style="height: 74px; max-height: 74px; max-width: 220px; width: auto; object-fit: contain; display: block; margin: 0 auto;"
                    />
                  </div>

                  <div>( {{ rfcData.bast ? rfcData.bast.nama_teknisi : '……………………………' }} )</div>
                  <div v-if="rfcData.bast && rfcData.bast.nip_teknisi && rfcData.bast.nip_teknisi !== '-'" style="font-size: 8.5pt;">
                    NIP. {{ rfcData.bast.nip_teknisi }}
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

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

function cetakDokumen() {
  window.print()
}
</script>

<style>
@media print {
  @page {
    size: A4 portrait;
    margin: 0mm !important;
  }

  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  body * {
    visibility: hidden !important;
  }

  html,
  body {
    background: #ffffff !important;
    color: #000000 !important;
    margin: 0 !important;
    padding: 0 !important;
    width: 210mm !important;
    height: auto !important;
    min-height: 0 !important;
    overflow: visible !important;
    font-family: Arial, Helvetica, sans-serif !important;
  }

  .no-print {
    display: none !important;
  }

  #printable-fr015,
  #printable-fr015 * {
    visibility: visible !important;
  }

  #printable-fr015 {
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    width: 210mm !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
  }

  .fr015-sheet {
    width: 210mm !important;
    max-width: 210mm !important;
    min-height: 0 !important;
    margin: 0 auto !important;
    padding: 0 10mm !important;
    box-sizing: border-box !important;
    border: none !important;
    box-shadow: none !important;
    background: #ffffff !important;
  }

  .fr015-repeating-header {
    display: table-header-group !important;
  }

  .fr015-repeating-footer {
    display: table-footer-group !important;
  }

  .print-top-spacer {
    height: 12mm !important;
  }

  .print-bottom-spacer {
    height: 12mm !important;
  }

  .fr015-kop-table {
    width: 190mm !important;
    margin: 0 !important;
  }

  .fr015-main-table {
    width: 190mm !important;
    margin: 0 auto !important;
  }

  .navy-section-cell {
    background-color: #1F3864 !important;
    color: #FFFFFF !important;
  }

  .no-top-left-right-border {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid #000000 !important;
  }

  .no-border-table,
  .no-border-cell {
    border: none !important;
  }

  tr {
    page-break-inside: avoid !important;
    break-inside: avoid !important;
  }
}
</style>
