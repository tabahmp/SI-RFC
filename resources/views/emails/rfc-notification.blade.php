<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Notifikasi SI-RFC' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #0757a8 0%, #0a4d8c 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">{{ config('app.name') }}</h1>
        <p style="margin: 5px 0 0; opacity: 0.9;">Sistem Informasi Request for Change</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <h2 style="color: #0757a8; margin-top: 0;">{{ $title }}</h2>
        
        <p>Halo <strong>{{ $recipientName }}</strong>,</p>
        
        <p>{{ $message }}</p>
        
        @if(isset($rfcData))
        <div style="background: #f8f9fa; border-left: 4px solid #0757a8; padding: 15px; margin: 20px 0;">
            <strong style="color: #0757a8;">Detail RFC:</strong>
            <p style="margin: 5px 0;"><strong>Kode:</strong> {{ $rfcData['kode'] }}</p>
            <p style="margin: 5px 0;"><strong>Status:</strong> {{ $rfcData['status'] }}</p>
            <p style="margin: 5px 0;"><strong>Urgensi:</strong> {{ $rfcData['urgensi'] }}</p>
            @if(isset($rfcData['target_sla']))
            <p style="margin: 5px 0;"><strong>Target SLA:</strong> {{ $rfcData['target_sla'] }}</p>
            @endif
        </div>
        @endif
        
        @if(isset($actionUrl))
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $actionUrl }}" style="background: #0757a8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600;">
                {{ $actionText ?? 'Lihat Detail RFC' }}
            </a>
        </div>
        @endif
        
        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            Email ini dikirim secara otomatis oleh sistem {{ config('app.name') }}. Jangan balas email ini.
        </p>
    </div>
    
    <div style="text-align: center; padding: 20px; font-size: 12px; color: #999;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
