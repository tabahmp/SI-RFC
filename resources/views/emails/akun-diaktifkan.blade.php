@extends('emails.layout')

@section('konten')
<p>Yth. <strong>{{ $nama }}</strong>,</p>
<p>Selamat! Akun SI-RFC Anda telah <strong>diaktifkan</strong> oleh Administrator Diskominfo Jawa Barat. Anda sekarang dapat masuk ke sistem dan mulai mengajukan Request for Change.</p>

<p><a href="{{ $urlLogin }}" class="btn">Masuk ke SI-RFC</a></p>

<p>Jika ada pertanyaan, silakan hubungi Bidang APTIKA Diskominfo Prov. Jawa Barat.</p>
@endsection
