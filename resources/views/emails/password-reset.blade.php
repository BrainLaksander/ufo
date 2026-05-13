<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - UFO</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 520px; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e1b4b 0%, #5e3191 50%, #a476d1 100%); padding: 32px 28px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: 2px;">UFO</h1>
                            <p style="margin: 6px 0 0; color: rgba(255,255,255,0.8); font-size: 13px;">UNKLAB Forum Organization</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 32px 28px;">
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; margin: 0 auto 16px; background: #f0e7ff; border-radius: 50%; text-align: center; line-height: 64px;">
                                    <img src="https://img.icons8.com/ios-filled/50/5e3191/lock-2.png" width="32" height="32" alt="Lock" style="vertical-align: middle;">
                                </div>
                                <h2 style="margin: 0 0 8px; color: #1e1b4b; font-size: 20px; font-weight: 700;">Reset Password</h2>
                                <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                    Kami menerima permintaan untuk mereset password akun organisasi <strong style="color: #5e3191;">{{ $orgName }}</strong>.
                                </p>
                            </div>

                            <p style="color: #374151; font-size: 14px; line-height: 1.6; margin: 0 0 24px;">
                                Klik tombol di bawah ini untuk membuat password baru. Link ini akan kedaluwarsa dalam <strong>60 menit</strong>.
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 8px 0 24px;">
                                        <a href="{{ $resetUrl }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #5e3191 0%, #7c3aed 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 15px; font-weight: 700; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(94,49,145,0.3);">
                                            Reset Password Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px;">
                                <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                                    <strong>Perhatian:</strong> Jika Anda <strong>tidak merasa</strong> meminta reset password, abaikan email ini. Password Anda tidak akan berubah.
                                </p>
                            </div>

                            <p style="color: #9ca3af; font-size: 12px; line-height: 1.5; margin: 0;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:
                            </p>
                            <p style="color: #6366f1; font-size: 12px; word-break: break-all; margin: 6px 0 0;">
                                {{ $resetUrl }}
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f9fafb; padding: 20px 28px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                © {{ date('Y') }} UFO - UNKLAB Forum Organization. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
