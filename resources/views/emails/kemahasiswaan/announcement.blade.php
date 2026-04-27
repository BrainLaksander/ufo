@php
    $title = (string) ($announcement->title ?? ($subject ?? 'Pengumuman'));
    $summary = (string) ($summary ?? '');
    $contentHtml = (string) ($content_html ?? e($summary));
    $category = (string) ($category ?? 'Umum');
    $targetLabel = (string) ($target_label ?? 'Semua Mahasiswa');
    $publishAt = (string) ($publish_at ?? now()->format('d M Y H:i'));
    $senderName = (string) ($sender_name ?? 'UFO');
    $senderEmail = (string) ($sender_email ?? 'noreply@example.com');
    $footerAddress = trim((string) ($footer_address ?? ''));
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:680px;margin:0 auto;padding:32px 16px;">
        <div style="background:linear-gradient(135deg,#5c3d91 0%,#7152a6 100%);color:#fff;border-radius:18px 18px 0 0;padding:28px 30px;">
            <div style="font-size:13px;letter-spacing:.08em;text-transform:uppercase;opacity:.9;">{{ $senderName }}</div>
            <h1 style="margin:10px 0 0;font-size:28px;line-height:1.2;">{{ $title }}</h1>
            <p style="margin:12px 0 0;font-size:14px;opacity:.95;">Kategori: {{ $category }} | Target: {{ $targetLabel }} | Publish: {{ $publishAt }}</p>
        </div>

        <div style="background:#ffffff;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 18px 18px;padding:28px 30px;box-shadow:0 10px 30px rgba(17,24,39,.06);">
            @if($summary !== '')
                <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;padding:16px 18px;margin-bottom:20px;">
                    <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#6b7280;margin-bottom:8px;">Ringkasan</div>
                    <div style="font-size:15px;line-height:1.7;color:#111827;">{{ $summary }}</div>
                </div>
            @endif

            <div style="font-size:15px;line-height:1.8;color:#111827;">
                {!! $contentHtml !!}
            </div>

            <div style="margin-top:28px;padding-top:22px;border-top:1px solid #e5e7eb;">
                <div style="font-size:13px;line-height:1.7;color:#6b7280;">
                    Email ini dikirim otomatis oleh sistem UFO Kemahasiswaan.
                </div>
                <div style="font-size:13px;line-height:1.7;color:#6b7280;">
                    Balas ke: <a href="mailto:{{ $senderEmail }}" style="color:#5c3d91;text-decoration:none;">{{ $senderEmail }}</a>
                </div>
                @if($footerAddress !== '')
                    <div style="font-size:13px;line-height:1.7;color:#6b7280;">{{ $footerAddress }}</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
