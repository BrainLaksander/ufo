@php
    $logoUrl = config('app.url') . '/logoufo.png';
    $title = $title ?? ($subject ?? 'Pengumuman');
    $senderName = $senderName ?? 'UFO';
@endphp
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;margin:0 0 18px 0;">
    <tr>
        <td style="padding:0 0 12px 0;vertical-align:middle;">
            <table cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;">
                <tr>
                    <td style="vertical-align:middle;padding-right:12px;">
                        <img src="{{ $logoUrl }}" alt="UFO" width="56" style="display:block;border:0;outline:none;text-decoration:none;">
                    </td>
                    <td style="vertical-align:middle;">
                        <div style="font-size:12px;color:#6b7280;letter-spacing:.06em;text-transform:uppercase;">{{ $senderName }}</div>
                        <div style="font-size:20px;font-weight:700;color:#3b1c57;margin-top:6px;">{{ $title }}</div>
                        @php
                            $metaParts = [];
                            if (!empty($category)) $metaParts[] = 'Kategori: ' . $category;
                            if (!empty($target)) $metaParts[] = 'Target: ' . $target;
                            if (!empty($publishAt)) $metaParts[] = 'Publish: ' . $publishAt;
                            $metaLine = implode(' | ', $metaParts);
                        @endphp
                        @if(!empty($metaLine))
                            <div style="font-size:13px;color:#6b7280;margin-top:6px;">{{ $metaLine }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
