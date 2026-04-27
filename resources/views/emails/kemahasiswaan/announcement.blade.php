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
<body style="margin:0;padding:0;background:#f4f0ff;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:680px;margin:0 auto;padding:32px 16px;">
        @include('emails.partials.header', ['title' => $title, 'category' => $category, 'target' => $targetLabel, 'publishAt' => $publishAt, 'senderName' => $senderName])

        <div style="background:#ffffff;border:2px solid #ede9fe;border-radius:14px;padding:20px;box-shadow:0 10px 30px rgba(91,33,182,.08);">
            @if($summary !== '')
                <div style="background:#f8f4ff;border:1px solid #ede9fe;border-radius:14px;padding:16px 18px;margin-bottom:20px;">
                    <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#5b21b6;margin-bottom:8px;">Ringkasan</div>
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
                    Balas ke: <a href="mailto:{{ $senderEmail }}" style="color:#7c3aed;text-decoration:none;font-weight:600;">{{ $senderEmail }}</a>
                </div>
                @if($footerAddress !== '')
                    <div style="font-size:13px;line-height:1.7;color:#6b7280;">{{ $footerAddress }}</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
