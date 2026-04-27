<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    use PengurusControllerTrait;

    public function index(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);

        $allAnnouncements = $this->loadAnnouncementCards($organizationId);
        $activeAnnouncements = array_values(array_filter($allAnnouncements, static function (array $item): bool {
            return in_array($item['raw_status'], ['published', 'scheduled'], true);
        }));

        $eventOptions = $this->loadAnnouncementEventOptions($organizationId);

        return view('pages.pengurus.announcements', [
            'activeAnnouncements' => $activeAnnouncements,
            'allAnnouncements' => $allAnnouncements,
            'eventOptions' => $eventOptions,
        ]);
    }

    public function form(Request $request): View
    {
        return view('pages.pengurus.announcements.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);

        if ($organizationId <= 0) {
            return back()->with('error', 'Konteks organisasi tidak ditemukan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'description' => 'required|string|max:3000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if (!Schema::hasTable('kemahasiswaan_announcements')) {
            return back()->with('error', 'Tabel pengumuman belum tersedia.');
        }

        $publishAt = Carbon::parse((string) $validated['start_date'])->startOfDay();
        $publishStatus = $publishAt->isFuture() ? 'scheduled' : 'published';
        $rawContent = trim((string) ($validated['description'] ?? ''));
        $normalizedContent = preg_replace('/\s+/u', ' ', strip_tags($rawContent)) ?? $rawContent;
        $summary = Str::limit(trim($normalizedContent), 240, '...');
        $ukmAccountId = $this->resolveOrganizationUkmAccountId($request, $organizationId);

        if ($ukmAccountId === null) {
            return back()->with('error', 'Akun UKM organisasi tidak ditemukan. Hubungi admin kemahasiswaan.');
        }

        DB::table('kemahasiswaan_announcements')->insert([
            'ukm_account_id' => $ukmAccountId,
            'title' => trim((string) ($validated['title'] ?? '')),
            'category' => 'Organisasi',
            'target_audience' => 'Mahasiswa',
            'summary' => $summary,
            'content' => $rawContent,
            'publish_at' => $publishAt,
            'publish_status' => $publishStatus,
            'submit_action' => 'publish_now',
            'email_review_status' => 'approved',
            'email_review_note' => null,
            'reviewed_by' => null,
            'reviewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pengumuman berhasil disimpan.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);

        if ($organizationId <= 0) {
            return back()->with('error', 'Konteks organisasi tidak ditemukan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'description' => 'required|string|max:3000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if (!Schema::hasTable('kemahasiswaan_announcements') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return back()->with('error', 'Data pengumuman belum tersedia.');
        }

        $belongsToOrganization = DB::table('kemahasiswaan_announcements as ann')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
            ->where('ann.id', $id)
            ->where('akun.organization_id', $organizationId)
            ->exists();

        if (!$belongsToOrganization) {
            return back()->with('error', 'Pengumuman tidak ditemukan atau bukan milik organisasi Anda.');
        }

        $publishAt = Carbon::parse((string) $validated['start_date'])->startOfDay();
        $publishStatus = $publishAt->isFuture() ? 'scheduled' : 'published';
        $rawContent = trim((string) ($validated['description'] ?? ''));
        $normalizedContent = preg_replace('/\s+/u', ' ', strip_tags($rawContent)) ?? $rawContent;
        $summary = Str::limit(trim($normalizedContent), 240, '...');

        DB::table('kemahasiswaan_announcements')
            ->where('id', $id)
            ->update([
                'title' => trim((string) ($validated['title'] ?? '')),
                'summary' => $summary,
                'content' => $rawContent,
                'publish_at' => $publishAt,
                'publish_status' => $publishStatus,
                'submit_action' => 'publish_now',
                'email_review_status' => 'approved',
                'email_review_note' => null,
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Pengumuman berhasil diperbarui.');
    }

    private function resolveOrganizationUkmAccountId(Request $request, int $organizationId): ?int
    {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return null;
        }

        $sessionUser = $request->session()->get('user');
        $sessionAccountId = is_array($sessionUser) ? (int) ($sessionUser['ukm_account_id'] ?? 0) : 0;

        if ($sessionAccountId > 0) {
            $isSameOrganization = DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $sessionAccountId)
                ->where('organization_id', $organizationId)
                ->exists();

            if ($isSameOrganization) {
                return $sessionAccountId;
            }
        }

        $fallbackAccountId = (int) (DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $organizationId)
            ->orderByRaw("CASE status WHEN 'active' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->value('id') ?? 0);

        return $fallbackAccountId > 0 ? $fallbackAccountId : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadAnnouncementCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('kemahasiswaan_announcements') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return [];
        }

        $rows = DB::table('kemahasiswaan_announcements as ann')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
            ->where('akun.organization_id', $organizationId)
            ->select(['ann.id', 'ann.title', 'ann.summary', 'ann.content', 'ann.publish_at', 'ann.publish_status'])
            ->orderByDesc('ann.publish_at')
            ->orderByDesc('ann.id')
            ->get();

        return $rows->map(function ($row) {
            $publishAt = !empty($row->publish_at) ? Carbon::parse((string) $row->publish_at) : null;
            $rawStatus = Str::lower((string) ($row->publish_status ?? 'draft'));
            $content = trim((string) ($row->content ?? ''));
            $summary = trim((string) ($row->summary ?? ''));

            if ($summary === '' && $content !== '') {
                $summary = Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($content)) ?? $content), 160, '...');
            }

            $dateLabel = $publishAt ? $publishAt->translatedFormat('d M Y') : '-';

            return [
                'id' => (int) $row->id,
                'title' => (string) $row->title,
                'description' => $summary,
                'full_content' => $content !== '' ? $content : $summary,
                'start_date' => $dateLabel,
                'raw_start_date' => $publishAt?->toDateString() ?? '',
                'end_date' => $dateLabel,
                'raw_end_date' => $publishAt?->toDateString() ?? '',
                'status' => $this->announcementStatusLabel($rawStatus),
                'raw_status' => $rawStatus,
                'pill' => $this->announcementStatusPill($rawStatus),
            ];
        })->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadAnnouncementEventOptions(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('events')) {
            return [];
        }

        return DB::table('events')
            ->where('organization_id', $organizationId)
            ->orderByDesc('start_date')
            ->limit(20)
            ->get(['id', 'name'])
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => (string) $row->name,
            ])
            ->all();
    }

    private function announcementStatusLabel(string $status): string
    {
        return match ($status) {
            'published' => 'Aktif',
            'scheduled' => 'Terjadwal',
            'archived' => 'Arsip',
            'draft' => 'Draft',
            default => Str::title(str_replace('_', ' ', $status)),
        };
    }

    private function announcementStatusPill(string $status): string
    {
        return match ($status) {
            'published' => 'approved',
            'scheduled' => 'pending',
            'archived' => 'draft',
            default => 'draft',
        };
    }
}
