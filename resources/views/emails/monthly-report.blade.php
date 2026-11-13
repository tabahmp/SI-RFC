<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan RFC</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #0757a8 0%, #0a4d8c 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">{{ config('app.name') }}</h1>
        <p style="margin: 5px 0 0; opacity: 0.9;">Laporan Bulanan RFC</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <h2 style="color: #0757a8; margin-top: 0;">Laporan Periode {{ $month }}</h2>
        
        <p>Yth. Administrator SI-RFC,</p>
        
        <p>Terlampir adalah laporan bulanan RFC untuk periode <strong>{{ $startDate }}</strong> sampai <strong>{{ $endDate }}</strong>.</p>
        
        <div style="background: #f8f9fa; border-left: 4px solid #0757a8; padding: 15px; margin: 20px 0;">
            <strong style="color: #0757a8;">File Terlampir:</strong>
            <p style="margin: 5px 0 0;">📎 {{ $filename }}</p>
        </div>
        
        <p>Laporan ini berisi data lengkap semua permohonan RFC yang diajukan dalam periode tersebut, termasuk:</p>
        <ul>
            <li>Detail permohonan RFC</li>
            <li>Status dan progress</li>
            <li>Durasi pemrosesan</li>
            <li>History perubahan</li>
        </ul>
        
        <p>Laporan ini dibuat secara otomatis setiap bulan dan dapat digunakan untuk:</p>
        <ul>
            <li>Evaluasi kinerja sistem</li>
            <li>Analisis trend permohonan</li>
            <li>Pelaporan ke stakeholder</li>
            <li>Arsip dokumentasi</li>
        </ul>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="font-size: 12px; color: #666; margin: 0;">
                Email ini dikirim secara otomatis oleh sistem {{ config('app.name') }}.<br>
                Jangan balas email ini.
            </p>
        </div>
    </div>
    
    <div style="text-align: center; padding: 20px; font-size: 12px; color: #999;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
