@extends('emails.layout')

@section('konten')
<p>Yth. <strong>{{ $namaPenerima }}</strong>,</p>
<p>Terdapat RFC baru yang masuk dan memerlukan telaah teknis Anda.</p>

<div class="info-box">
  <table>
    <tr><td>Kode RFC</td><td><strong>{{ $rfc->kode_pelacakan }}</strong></td></tr>
    <tr><td>Pemohon</td><td>{{ $rfc->nama_pemohon }}</td></tr>
    <tr><td>OPD</td><td>{{ $rfc->opd?->nama_opd ?? '-' }}</td></tr>
    <tr><td>Kategori</td><td>{{ $rfc->kategori?->nama_kategori ?? '-' }}</td></tr>
    <tr><td>Urgensi</td><td>{{ ucfirst($rfc->urgensi) }}</td></tr>
    <tr><td>Tanggal Ajuan</td><td>{{ $rfc->created_at->format('d/m/Y H:i') }}</td></tr>
  </table>
</div>

<p>Silakan buka workspace untuk melakukan telaah.</p>
<p><a href="{{ $urlWorkspace }}" class="btn">Buka Workspace RFC</a></p>
@endsection
