<?php

namespace App\Http\Controllers\Pengurus;

use App\Models\Core\Organization;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Shared trait for Pengurus controllers with utility and helper methods.
 * Consolidates 40+ private helper methods into reusable functions.
 */
trait PengurusControllerTrait
{
    protected array $referenceCache = [];

    // ============ Context Resolution ============
    protected function resolvePengurusContext($request): array
    {
        $user = $request->user();
        $session = $request->session()->get('user');

        if (!$user && !is_array($session)) {
            return [
                'organization_id' => null,
                'member_id' => null,
                'organization_name' => null,
                'organization_level' => null,
            ];
        }

        $organizationId = null;
        $memberId = null;
        $organizationName = is_array($session) ? (string) ($session['organization_name'] ?? '') : '';
        $organizationLevel = '';

        if (is_array($session) && !empty($session['organization_id'])) {
            $organizationId = (int) $session['organization_id'];
        }

        if (is_array($session) && !empty($session['ukm_account_id'])) {
            $memberId = (int) $session['ukm_account_id'];
        }

        if (Schema::hasTable('organizations')) {
            $org = DB::table('organizations')
                ->select(['id', 'name', 'level'])
                ->when($organizationId, fn ($query) => $query->where('id', $organizationId), function ($query) use ($user) {
                    if ($user) {
                        $query->where(function ($sub) use ($user) {
                            $sub->where('user_id', $user->id)
                                ->orWhere('admin_id', $user->id);
                        });
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                })
                ->first();

            if ($org) {
                $organizationId = (int) $org->id;
                $organizationName = (string) ($org->name ?? '');
                $organizationLevel = (string) ($org->level ?? '');
            }
        }

        if ($organizationId && $user && Schema::hasTable('members')) {
            $member = DB::table('members')
                ->select(['id'])
                ->where('user_id', $user->id)
                ->where('organization_id', $organizationId)
                ->first();

            if ($member) {
                $memberId = (int) $member->id;
            }
        }

        return [
            'organization_id' => $organizationId,
            'member_id' => $memberId,
            'organization_name' => $organizationName,
            'organization_level' => $organizationLevel,
        ];
    }

    // ============ Status Mapping ============
    protected function mapEventStatusToPortal(string $status, ?Carbon $startDate, ?Carbon $endDate): array
    {
        $normalized = Str::lower($status);

        return match ($normalized) {
            'cancelled' => $this->refStatusPair('event_status_map', 'cancelled'),
            'completed' => $this->refStatusPair('event_status_map', 'completed'),
            'draft' => $this->refStatusPair('event_status_map', 'draft'),
            default => $this->determineEventStatusFromDates($startDate, $endDate),
        };
    }

    protected function mapAnnouncementStatus(string $status): array
    {
        return match (Str::lower($status)) {
            'published' => $this->refStatusPair('announcement_status_map', 'published'),
            'scheduled' => $this->refStatusPair('announcement_status_map', 'scheduled'),
            'archived' => $this->refStatusPair('announcement_status_map', 'archived'),
            default => $this->refStatusPair('announcement_status_map', 'default'),
        };
    }

    protected function mapReportStatus(string $status): string
    {
        return match (Str::lower($status)) {
            'draft' => 'Draf',
            'submitted' => 'Diajukan',
            'under_review' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => Str::title(str_replace('_', ' ', (string) $status)),
        };
    }

    private function determineEventStatusFromDates(?Carbon $startDate, ?Carbon $endDate): array
    {
        if ($startDate && $startDate->isFuture()) {
            return $this->refStatusPair('event_status_map', 'future');
        }

        if ($startDate && $endDate && $startDate->lte(now()) && $endDate->gte(now())) {
            return $this->refStatusPair('event_status_map', 'ongoing');
        }

        return $this->refStatusPair('event_status_map', 'default');
    }

    // ============ Reference & Labels ============
    protected function refLabel(string $domain, string $code): string
    {
        $map = $this->getReferenceMap($domain);
        return (string) data_get($map, $code . '.label', '');
    }

    protected function refStatusPair(string $domain, string $code): array
    {
        $map = $this->getReferenceMap($domain);
        $entry = data_get($map, $code, null);

        if (is_array($entry)) {
            return [
                (string) ($entry['label'] ?? ''),
                (string) ($entry['payload']['pill'] ?? ''),
            ];
        }

        return ['', ''];
    }

    protected function getReferenceMap(string $domain): array
    {
        if (array_key_exists($domain, $this->referenceCache)) {
            return $this->referenceCache[$domain];
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            $this->referenceCache[$domain] = [];
            return [];
        }

        $map = DB::table('workflow_reference_values')
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
                return [(string) $row->code => ['label' => $row->label, 'payload' => $payload]];
            })
            ->all();

        $this->referenceCache[$domain] = $map;
        return $map;
    }

    protected function getReferencePayload(string $domain, string $code): array
    {
        $map = $this->getReferenceMap($domain);
        return data_get($map, $code . '.payload', []);
    }

    // ============ Date/Time Utilities ============
    protected function resolveMonthNumber(string $month): ?int
    {
        $value = Str::lower(trim($month));
        $value = str_replace('.', '', $value);

        return match ($value) {
            'jan', 'januari', 'january' => 1,
            'feb', 'februari', 'february' => 2,
            'mar', 'maret', 'march' => 3,
            'apr', 'april' => 4,
            'mei', 'may' => 5,
            'jun', 'juni', 'june' => 6,
            'jul', 'juli', 'july' => 7,
            'agu', 'agt', 'agustus', 'aug', 'august' => 8,
            'sep', 'sept', 'september' => 9,
            'oct', 'okt', 'oktober', 'october' => 10,
            'nov', 'november' => 11,
            'des', 'dec', 'desember', 'december' => 12,
            default => null,
        };
    }

    protected function safeCreateDate(int $year, int $month, int $day): ?Carbon
    {
        try {
            return Carbon::createFromDate($year, $month, $day)->startOfDay();
        } catch (Throwable) {
            return null;
        }
    }

    protected function formatEventTimeRange(?Carbon $startDate, ?Carbon $endDate): string
    {
        if (!$startDate && !$endDate) {
            return '';
        }

        if ($startDate && $endDate) {
            return $startDate->format('H:i') . ' - ' . $endDate->format('H:i');
        }

        return ($startDate ?: $endDate)->format('H:i');
    }

    protected function normalizeDateField($primaryDate, $fallbackDate): string
    {
        try {
            $date = $primaryDate
                ? Carbon::parse((string) $primaryDate)
                : ($fallbackDate ? Carbon::parse((string) $fallbackDate) : null);
            return $date ? $date->translatedFormat('d M Y') : '-';
        } catch (Throwable) {
            return '-';
        }
    }

    // ============ Media & URLs ============
    protected function resolveOrganizationMediaUrl(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '';
        }

        if (Str::startsWith($trimmed, ['http://', 'https://', '//'])) {
            return $trimmed;
        }

        if (File::exists(public_path($trimmed))) {
            return asset(ltrim($trimmed, '/'));
        }

        if (Storage::disk('public')->exists($trimmed)) {
            return Storage::url($trimmed);
        }

        return asset(ltrim($trimmed, '/'));
    }

    protected function formatWhatsappLink(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?: '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return 'https://wa.me/' . $digits;
    }

    protected function storeOrganizationMedia(UploadedFile $file, int $organizationId, string $type): string
    {
        $safeType = in_array($type, ['logo', 'banner', 'event'], true) ? $type : 'media';
        $relativeDirectory = 'uploads/organizations/' . $organizationId;
        $absoluteDirectory = public_path($relativeDirectory);

        $extension = strtolower((string) ($file->getClientOriginalExtension() ?: 'jpg'));
        $fileName = $safeType . '_' . now()->format('YmdHis') . '_' . Str::random(10) . '.' . $extension;

        try {
            File::ensureDirectoryExists($absoluteDirectory);
            $file->move($absoluteDirectory, $fileName);
            return $relativeDirectory . '/' . $fileName;
        } catch (Throwable) {
            Storage::disk('public')->putFileAs($relativeDirectory, $file, $fileName);
        }

        return $relativeDirectory . '/' . $fileName;
    }

    // ============ Profile Parsing ============
    protected function decodeProfileList(?object $organization, string $column, array $keys): array
    {
        if (!$organization || !Schema::hasColumn('organizations', $column)) {
            return [];
        }

        $raw = trim((string) ($organization->{$column} ?? ''));
        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [];
        }

        return collect($decoded)
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) use ($keys) {
                $item = [];
                foreach ($keys as $key) {
                    $item[$key] = trim((string) ($row[$key] ?? ''));
                }
                return $item;
            })
            ->filter(function ($row) {
                foreach ($row as $value) {
                    if ($value !== '') {
                        return true;
                    }
                }
                return false;
            })
            ->values()
            ->all();
    }

    protected function parseProfileRows(string $text, array $keys, int $maxItems = 20): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($text)) ?: [];

        return collect($lines)
            ->map(fn ($line) => trim((string) $line))
            ->filter(fn ($line) => $line !== '')
            ->take($maxItems)
            ->map(function ($line) use ($keys) {
                $parts = array_map('trim', explode('|', $line));
                $item = [];

                foreach ($keys as $index => $key) {
                    $item[$key] = (string) ($parts[$index] ?? '');
                }

                return $item;
            })
            ->filter(function ($row) {
                foreach ($row as $value) {
                    if ($value !== '') {
                        return true;
                    }
                }
                return false;
            })
            ->values()
            ->all();
    }

    protected function formatProfileRows(array $rows, array $keys): string
    {
        return collect($rows)
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) use ($keys) {
                $values = [];
                foreach ($keys as $key) {
                    $values[] = trim((string) ($row[$key] ?? ''));
                }
                return implode('|', $values);
            })
            ->filter(fn ($line) => trim($line, '| ') !== '')
            ->values()
            ->implode("\n");
    }

    protected function missionToValues(?string $mission): array
    {
        $text = trim((string) $mission);
        if ($text === '') {
            return [];
        }

        $parts = preg_split('/\r\n|\r|\n|\./', $text) ?: [];
        return collect($parts)
            ->map(fn ($part) => trim((string) $part))
            ->filter()
            ->take(6)
            ->map(function ($part) {
                return ['name' => Str::limit($part, 48, ''), 'desc' => $part];
            })
            ->values()
            ->all();
    }

    // ============ Organization Access Control ============
    protected function canAccessLostAndFound(?string $organizationLevel, ?string $organizationName = null): bool
    {
        return Organization::isUniversityBem($organizationName, $organizationLevel);
    }

    protected function inferOrganizationCategory(?string $name, ?string $shortname): string
    {
        $text = Str::lower(trim((string) $name . ' ' . (string) $shortname));

        if ($text === '') {
            return 'Umum';
        }

        if (Str::contains($text, ['bem'])) {
            return 'BEM';
        }

        if (Str::contains($text, ['choir', 'paduan suara', 'vocal', 'echo'])) {
            return 'Paduan Suara';
        }

        if (Str::contains($text, ['creative', 'cinema', 'sinema', 'media', 'event', 'organizer', 'computer', 'science', 'cssa', 'uvics'])) {
            return 'Kreatif & Teknologi';
        }

        if (Str::contains($text, ['ikatan', 'daerah', 'papua', 'minahasa', 'maluku', 'ikmapap', 'ikmamalut'])) {
            return 'Kedaerahan';
        }

        if (Str::contains($text, ['ministry', 'rohis', 'kerohanian', 'pilgrims', 'penginjilan', 'mission'])) {
            return 'Kerohanian';
        }

        return 'Umum';
    }

    protected function resolveOrganizationLevel(?int $organizationId): string
    {
        if (!$organizationId || !Schema::hasTable('organizations')) {
            return '';
        }

        return (string) (DB::table('organizations')
            ->where('id', $organizationId)
            ->value('level') ?? '');
    }

    // ============ Category Inference ============
    protected function inferScheduleCategoryFromTitle(string $title, string $defaultCategory = 'campus'): string
    {
        $value = Str::lower(trim($title));

        if (Str::contains($value, ['ujian', 'seminar', 'akademik', 'kuliah', 'skripsi', 'wisuda'])) {
            return 'acad';
        }

        if (Str::contains($value, ['libur', 'cuti bersama', 'holiday', 'natal', 'idul', 'nyepi'])) {
            return 'holiday';
        }

        if (Str::contains($value, ['masa tenang', 'pembatasan', 'lockdown'])) {
            return 'restricted';
        }

        if (Str::contains($value, ['ukm', 'bem', 'organisasi', 'hima'])) {
            return 'org';
        }

        return in_array($defaultCategory, ['acad', 'org', 'restricted', 'holiday', 'campus'], true)
            ? $defaultCategory
            : 'campus';
    }

    protected function isLikelyCalendarNoiseLine(string $line): bool
    {
        $value = Str::lower(trim($line));

        if ($value === '' || preg_match('/^\s+$/', $value)) {
            return true;
        }

        if (preg_match('/^(sen|sel|rab|kam|jum|sab|min|sun|mon|tue|wed|thu|fri|sat)(\s+(sen|sel|rab|kam|jum|sab|min|sun|mon|tue|wed|thu|fri|sat))*$/iu', $value) === 1) {
            return true;
        }

        if (preg_match('/^(mulai|sampai|keterangan|kalender|semester|minggu|bulan|tahun|hari)(\s+\w+)*$/iu', $value) === 1) {
            return true;
        }

        return preg_match('/^\d{1,2}(\s+\d{1,2}){3,}$/u', $value) === 1;
    }

    protected function lineContainsAnyDate(string $line): bool
    {
        return preg_match('/\b\d{1,2}\s*[\/-]\s*\d{1,2}(?:\s*[\/-]\s*20\d{2})?\b/u', $line) === 1
            || preg_match('/\b\d{1,2}\s+([\p{L}.]+)\s*(20\d{2})?\b/u', $line) === 1;
    }

    protected function extractMonthYearContext(string $line, int $fallbackYear): ?array
    {
        $monthPattern = 'januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember|january|february|march|april|may|june|july|august|september|october|november|december';
        if (preg_match('/^(' . $monthPattern . ')(?:\s+(20\d{2}))?$/iu', trim($line), $m) !== 1) {
            return null;
        }

        $month = $this->resolveMonthNumber((string) $m[1]);
        if ($month === null) {
            return null;
        }

        $year = isset($m[2]) && trim((string) $m[2]) !== '' ? (int) $m[2] : $fallbackYear;

        return ['month' => $month, 'year' => $year];
    }
}
