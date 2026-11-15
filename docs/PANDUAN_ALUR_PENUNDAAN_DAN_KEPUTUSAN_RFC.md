# Panduan Alur Penundaan & Keputusan Ulang RFC
**Sistem Informasi Pengelolaan Permohonan Perubahan TI (SI-RFC)**  
*Dinas Komunikasi dan Informatika Provinsi Jawa Barat*  
*Standar SMKI ISO/IEC 27001 & ITIL Change Management*

---

## 1. Latar Belakang & Prinsip Tata Kelola
Sesuai standar **ISO/IEC 27001 (A.12.1.2 - Pengelolaan Perubahan)**, setiap perubahan pada aset dan layanan TI pemerintah harus terdokumentasi, dianalisis risikonya, dan disahkan oleh pejabat berwenang.

Dalam praktiknya, permohonan RFC (Request for Change) tidak selalu dapat langsung disetujui atau ditolak secara mutlak pada evaluasi awal. Kondisi operasional tertentu, seperti:
- Kebutuhan alokasi anggaran belanja tambahan,
- Kesiapan sumber daya teknis pelaksana,
- Periode _freeze window_ sistem kedinasan, atau
- Menunggu telaah dampak infrastruktur lebih lanjut,

memerlukan mekanisme **Penundaan Sementara (Postponed/Pending Review)** yang terjadwal.

---

## 2. Alur Transisi Status Penundaan

```
[Permohonan Diajukan]
         │
         ▼
[Telaah Verifikator]
         │
         ▼
[Menunggu Persetujuan]
         │
         ├───────────────────────┬───────────────────────┐
         ▼                       ▼                       ▼
    [Disetujui]             [Ditunda]           [Ditolak Pimpinan]
         │                       │
         ▼                       ▼
  [Penugasan Teknisi]    [Jadwal Tinjau Ulang]
                                 │
         ┌───────────────────────┴───────────────────────┐
         ▼                                               ▼
   (Tinjau Ulang / Jatuh Tempo)                   (Tunda Ulang Tanggal Baru)
         │
         ├───────────────────────┬───────────────────────┐
         ▼                       ▼                       ▼
    [Disetujui]             [Ditunda]           [Ditolak Pimpinan]
```

---

## 3. Hak Akses & Fitur Pimpinan

1. **Antrean RFC Ditunda**:
   - Pimpinan dapat mengakses daftar tiket yang ditunda melalui menu navigasi view `RFC Ditunda` (`/dashboard/pimpinan?view=ditunda`).
   - Setiap tiket dilengkapi informasi jadwal evaluasi ulang, catatan penundaan sebelumnya, dan indikator status jatuh tempo (**Siap Diputuskan** / **Menunggu Jadwal**).

2. **Pengambilan Keputusan Langsung**:
   - Pimpinan tidak perlu menunggu tiket dikembalikan ke draf atau diajukan ulang oleh pemohon.
   - Pimpinan dapat langsung menekan tombol **"Beri Keputusan"** baik di Dashboard Pimpinan maupun di dalam **Workspace RFC**.

3. **Pilihan Tindak Lanjut**:
   - **Disetujui**: Menetapkan teknisi pelaksana Diskominfo dan meneruskan tiket ke tahap pengerjaan teknis.
   - **Ditunda Kembali**: Memperbarui tanggal jadwal tinjau ulang berikutnya disertai catatan arahan.
   - **Ditolak**: Menghentikan permohonan dengan alasan penolakan resmi.

---

## 4. Audit Trail & Matriks Otorisasi
Setiap perubahan status dan keputusan penundaan terekam otomatis pada:
- **Tabel `persetujuan_rfc`**: Menyimpan timestamp keputusan, identitas pimpinan, catatan arahan, jadwal tinjau ulang, dan tanda tangan digital.
- **Tabel `riwayat_rfc`**: Audit log berurutan yang dapat diinspeksi oleh auditor eksternal/internal.
- **Dokumen Elektronik FR-015**: Lembar formulir perubahan TI resmi Provinsi Jawa Barat.
