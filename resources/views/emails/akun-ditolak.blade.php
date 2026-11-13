@extends('emails.layout')

@section('konten')
<p>Yth. <strong>{{ $nama }}</strong>,</p>
<p>Mohon maaf, pendaftaran akun SI-RFC Anda <strong>tidak dapat disetujui</strong> pada saat ini.</p>

@if($catatan)
<div class="info-box">
  <p style="margin:0; font-size:13px;"><strong>Alasan:</strong> {{ $catatan }}</p>
</div>
@endif

<p>Jika Anda merasa ada kekeliruan, silakan hubungi langsung Bidang APTIKA Diskominfo Prov. Jawa Barat untuk klarifikasi lebih lanjut.</p>
@endsection
