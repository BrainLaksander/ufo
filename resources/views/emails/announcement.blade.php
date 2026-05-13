<!DOCTYPE html>
<html>
<head>
    <title>{{ $announcement->title }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px;">
        <h2 style="color: #3730a3; border-bottom: 2px solid #e0e7ff; padding-bottom: 10px;">{{ $announcement->title }}</h2>
        
        <p style="font-size: 14px; color: #6b7280;">Kategori: <strong>{{ $announcement->category }}</strong></p>
        
        <div style="margin-top: 20px; white-space: pre-wrap;">{{ $announcement->content }}</div>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af;">
            <p>Email ini dikirim oleh Sistem Kemahasiswaan UFO UNKLAB. Harap tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
