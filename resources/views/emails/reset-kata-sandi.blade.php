@extends('emails.layout')

@section('konten')
<p>Kepada <strong>{{ $nama ?: 'Pengguna' }}</strong>,</p>
<p>Kami menerima permintaan untuk mengatur ulang kata sandi akun SI-RFC Anda. Klik tombol di bawah untuk melanjutkan.</p>

<p><a href="{{ $urlReset }}" class="btn">Atur Ulang Kata Sandi</a></p>

<p style="font-size:12px; color:#64748B;">Tautan ini hanya berlaku selama <strong>60 menit</strong>. Jika Anda tidak meminta reset kata sandi, abaikan email ini. Akun Anda tetap aman.</p>

<p style="font-size:12px; color:#64748B;">Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
<span style="color:#0757A8; word-break:break-all;">{{ $urlReset }}</span></p>
@endsection
