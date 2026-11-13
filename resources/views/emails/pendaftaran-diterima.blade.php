@extends('emails.layout')

@section('konten')
<p>Yth. <strong>{{ $nama }}</strong>,</p>
<p>Pendaftaran akun SI-RFC Anda telah <strong>berhasil diterima</strong> dan saat ini sedang menunggu verifikasi oleh Administrator Diskominfo Jawa Barat.</p>

<div class="info-box">
  <table>
    <tr><td>Email</td><td>{{ $email }}</td></tr>
    <tr><td>OPD</td><td>{{ $opd }}</td></tr>
    <tr><td>Status</td><td>Menunggu Aktivasi</td></tr>
  </table>
</div>

<p>Anda akan menerima email konfirmasi setelah akun Anda diaktifkan oleh administrator. Proses ini biasanya memerlukan 1–2 hari kerja.</p>
<p>Terima kasih telah mendaftar di SI-RFC Diskominfo Jawa Barat.</p>
@endsection
