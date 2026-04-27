<?php

namespace App\Http\Controllers\Mahasiswa;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

trait MahasiswaControllerTrait
{
    // ============ Date/Time Helpers ============

    protected function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse((string) $value);
        } catch (Throwable) {
            return null;
        }
    }

    protected function formatDate(mixed $value): string
    {
        return $this->parseDate($value)?->format('d M Y') ?? $this->uiText('mahasiswa_placeholder_dash');
    }

    protected function formatTimeRange(?Carbon $startDate, ?Carbon $endDate): string
    {
        if (!$startDate && !$endDate) {
            return $this->uiText('mahasiswa_placeholder_dash');
        }

        if ($startDate && $endDate) {
            return $startDate->format('H:i') . ' - ' . $endDate->format('H:i');
        }

        return ($startDate ?: $endDate)->format('H:i');
    }

    // ============ Text Processing ============

    protected function splitListText(?string $text): array
    {
        if (!$text) {
            return [];
        }

        $rows = preg_split('/\r\n|\r|\n/', $text) ?: [];

        return collect($rows)
            ->map(fn ($row) => trim(preg_replace('/^\d+[\.)]\s*/', '', (string) $row)))
            ->filter()
            ->values()
            ->all();
    }

    protected function extractLostFoundMeta(string $description): array
    {
        if ($description === '') {
            return [];
        }

        $meta = [];
        $lines = preg_split('/\r\n|\r|\n/', $description) ?: [];

        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '' || !str_contains($line, ':')) {
                continue;
            }

            [$rawKey, $rawValue] = explode(':', $line, 2);
            $key = trim((string) $rawKey);
            $value = trim((string) $rawValue);

            if ($key !== '' && $value !== '') {
                $meta[$key] = $value;
            }
        }

        return $meta;
    }

    // ============ URL Normalization ============

    protected function normalizeInstagramUrl(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return 'https://instagram.com/' . ltrim($value, '@');
    }

    protected function normalizeWhatsappUrl(?string $value): ?string
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return null;
        }

        if (Str::startsWith($raw, ['http://', 'https://'])) {
            return $raw;
        }

        $number = preg_replace('/[^0-9]/', '', $raw);

        if ($number === '') {
            return null;
        }

        if (Str::startsWith($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return 'https://wa.me/' . $number;
    }

    protected function resolveMediaUrl(mixed $value): ?string
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return null;
        }

        if (Str::startsWith($raw, ['data:image/', 'http://', 'https://'])) {
            return $raw;
        }

        if (Str::startsWith($raw, '/storage/')) {
            return $raw;
        }

        if (Str::startsWith($raw, 'storage/')) {
            return '/' . ltrim($raw, '/');
        }

        $publicRelativePath = ltrim($raw, '/');

        if (Str::startsWith($publicRelativePath, 'public/')) {
            $storageRelativePath = Str::after($publicRelativePath, 'public/');

            if ($storageRelativePath !== '' && Storage::disk('public')->exists($storageRelativePath)) {
                return Storage::url($storageRelativePath);
            }
        }

        if (Storage::disk('public')->exists($publicRelativePath)) {
            return Storage::url($publicRelativePath);
        }

        if (file_exists(public_path($publicRelativePath))) {
            return '/' . $publicRelativePath;
        }

        if (!Str::contains($raw, ['/', '.'])) {
            return null;
        }

        return null;
    }

    // ============ Generator/Formatter Helpers ============

    protected function acronym(?string $shortname, ?string $name): string
    {
        $shortname = trim((string) $shortname);

        if ($shortname !== '') {
            return Str::upper($shortname);
        }

        $words = preg_split('/\s+/', trim((string) $name)) ?: [];
        $acronym = collect($words)
            ->filter()
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');

        $acronym = Str::upper(Str::limit($acronym, 4, ''));

        return $acronym !== '' ? $acronym : $this->uiText('mahasiswa_org_acronym_default');
    }

    protected function placeholderImage(string $label): string
    {
        $text = trim($label) !== '' ? Str::limit(trim($label), 30, '') : 'UFO';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675" viewBox="0 0 1200 675"><rect width="1200" height="675" fill="#E6EEF4"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#44576D" font-size="44" font-family="Arial, sans-serif">' . e($text) . '</text></svg>';

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    // ============ Inference/Mapping Helpers ============

    protected function inferOrganizationCategory(?string $name, ?string $shortname): string
    {
        $text = Str::lower(trim((string) $name . ' ' . (string) $shortname));

        if ($text === '') {
            return $this->uiText('mahasiswa_org_category_default');
        }

        if (Str::contains($text, ['bem'])) {
            return $this->uiText('mahasiswa_org_category_bem');
        }

        if (Str::contains($text, ['choir', 'paduan suara', 'vocal', 'echo'])) {
            return $this->uiText('mahasiswa_org_category_choir');
        }

        if (Str::contains($text, ['creative', 'cinema', 'sinema', 'media', 'event', 'organizer', 'computer', 'science', 'cssa', 'uvics'])) {
            return $this->uiText('mahasiswa_org_category_creative');
        }

        if (Str::contains($text, ['ikatan', 'daerah', 'papua', 'minahasa', 'maluku', 'ikmapap', 'ikmamalut'])) {
            return $this->uiText('mahasiswa_org_category_regional');
        }

        if (Str::contains($text, ['ministry', 'rohis', 'kerohanian', 'pilgrims', 'penginjilan', 'mission'])) {
            return $this->uiText('mahasiswa_org_category_ministry');
        }

        return $this->uiText('mahasiswa_org_category_default');
    }

    protected function inferLostFoundCategory(string $name): string
    {
        $text = Str::lower($name);

        if (Str::contains($text, ['dompet', 'wallet', 'uang'])) {
            return $this->uiText('mahasiswa_lf_category_wallet');
        }

        if (Str::contains($text, ['kunci', 'key'])) {
            return $this->uiText('mahasiswa_lf_category_key');
        }

        if (Str::contains($text, ['ktm', 'id', 'kartu', 'card'])) {
            return $this->uiText('mahasiswa_lf_category_card');
        }

        if (Str::contains($text, ['laptop', 'hp', 'handphone', 'headset', 'earphone'])) {
            return $this->uiText('mahasiswa_lf_category_electronic');
        }

        return $this->uiText('mahasiswa_lf_category_other');
    }

    protected function mapLostFoundStatus(string $status): string
    {
        return match (Str::lower($status)) {
            'active', 'pending', 'approved' => $this->uiText('mahasiswa_lf_status_active'),
            'claimed', 'closed', 'resolved', 'found' => $this->uiText('mahasiswa_lf_status_completed'),
            default => $this->uiText('mahasiswa_lf_status_pending'),
        };
    }

    protected function mapEventStatusLabel(string $status): string
    {
        $normalized = Str::lower($status);
        $references = $this->loadReferenceDomain('event_status_map');

        $label = (string) data_get($references, $normalized . '.label', '');

        if ($label !== '') {
            return $label;
        }

        return Str::title(str_replace('_', ' ', $status));
    }

    protected function inferNotificationIcon(string $action): string
    {
        $action = Str::lower($action);

        if (Str::contains($action, ['event', 'jadwal'])) {
            return 'calendar-event';
        }

        if (Str::contains($action, ['lost', 'found', 'barang'])) {
            return 'search';
        }

        if (Str::contains($action, ['pengumuman', 'announcement'])) {
            return 'megaphone';
        }

        return 'info-circle';
    }

    protected function isEventRegistrationOpen(string $status): bool
    {
        return in_array(Str::lower(trim($status)), [
            'approved',
            'ongoing',
            'open',
            'scheduled',
            'published',
            'active',
        ], true);
    }

    protected function withAllCategory(array $categories): array
    {
        $allLabel = trim($this->uiText('mahasiswa_category_all_label'));

        return array_values(array_merge(
            [$allLabel],
            collect($categories)->filter(fn ($item) => trim((string) $item) !== '')->unique()->values()->all()
        ));
    }

    // ============ UI Text Loading ============

    protected function loadReferenceDomain(string $domain): array
    {
        if (!isset($this->referenceCache)) {
            $this->referenceCache = [];
        }

        if (array_key_exists($domain, $this->referenceCache)) {
            return $this->referenceCache[$domain];
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('workflow_reference_values')) {
            $this->referenceCache[$domain] = [];
            return [];
        }

        $map = \Illuminate\Support\Facades\DB::table('workflow_reference_values')
            ->select(['code', 'label', 'payload'])
            ->where('domain', $domain)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(function ($row) {
                $payload = [];

                if (is_string($row->payload) && trim($row->payload) !== '') {
                    $decoded = json_decode($row->payload, true);
                    $payload = is_array($decoded) ? $decoded : [];
                }

                return [
                    (string) $row->code => [
                        'label' => $row->label,
                        'payload' => $payload,
                    ],
                ];
            })
            ->all();

        $this->referenceCache[$domain] = $map;

        return $map;
    }

    protected function uiText(string $code): string
    {
        $map = $this->loadReferenceDomain('ui_text');
        $label = trim((string) data_get($map, $code . '.label', ''));

        return $label;
    }
}
