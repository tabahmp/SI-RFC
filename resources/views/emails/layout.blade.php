<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SI-RFC — Diskominfo Jabar</title>
  <style>
    body { margin: 0; padding: 0; background-color: #F3F7FB; font-family: Arial, Helvetica, sans-serif; }
    .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #DAE3EF; }
    .header { background: linear-gradient(135deg, #073B70, #0757A8); padding: 28px 32px; }
    .header h1 { color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.3px; }
    .header p { color: #A9C9E8; margin: 4px 0 0; font-size: 12px; }
    .accent-bar { height: 4px; display: flex; }
    .accent-bar .blue { flex: 2; background: #0757A8; }
    .accent-bar .yellow { flex: 1; background: #F4CE18; }
    .accent-bar .green { flex: 1; background: #16834A; }
    .body { padding: 32px; color: #1E293B; font-size: 14px; line-height: 1.7; }
    .body p { margin: 0 0 14px; }
    .btn { display: inline-block; padding: 12px 28px; background: #0757A8; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 700; font-size: 14px; margin: 8px 0; }
    .info-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 16px; margin: 16px 0; }
    .info-box table { width: 100%; border-collapse: collapse; }
    .info-box td { padding: 5px 8px; font-size: 13px; }
    .info-box td:first-child { color: #64748B; width: 40%; }
    .info-box td:last-child { font-weight: 600; color: #1E293B; }
    .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 32px; font-size: 11px; color: #94A3B8; text-align: center; line-height: 1.6; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="accent-bar"><div class="blue"></div><div class="yellow"></div><div class="green"></div></div>
    <div class="header">
      <h1>SI-RFC</h1>
      <p>Sistem Informasi Request for Change — Diskominfo Prov. Jawa Barat</p>
    </div>
    <div class="body">
      @yield('konten')
    </div>
    <div class="footer">
      Email ini dikirim secara otomatis oleh sistem SI-RFC. Jangan membalas email ini.<br>
      Diskominfo Prov. Jawa Barat &bull; Bidang Aptika &bull; &copy; {{ date('Y') }}
    </div>
  </div>
</body>
</html>
