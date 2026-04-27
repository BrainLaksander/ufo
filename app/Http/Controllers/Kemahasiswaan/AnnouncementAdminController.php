<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use App\Services\ReferenceValueService;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class AnnouncementAdminController extends Controller
{
    use KemahasiswaanControllerTrait;

    public function __construct(ReferenceValueService $referenceService)
    {
        $this->referenceService = $referenceService;
    }

    public function pengumumanIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();

        $pengumuman = $this->getPengumuman();
        $pendingEmailReviewStatuses = $this->pendingEmailReviewStatuses();

        $reviewQueue = array_values(array_filter(
            $pengumuman,
            fn (array $item) => in_array($item['email_review_code'], $pendingEmailReviewStatuses, true)
        ));

        return view('portal.kemahasiswaan.pengumuman', [
            'workflowPengumuman' => $pengumuman,
            'emailReviewQueue' => $reviewQueue,
            'ukmAccounts' => $this->getAkunUKM(),
            'ui' => $this->buildPengumumanUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function storePengumuman(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:140',
            'kategori' => 'required|string|max:60',
            'target' => 'required|string|max:100',
            'konten' => 'required|string|max:10000',
            'publish_at' => 'nullable|date|after_or_equal:now',
            'ukm_account_id' => 'nullable|integer|exists:kemahasiswaan_ukm_accounts,id',
            'submit_action' => 'required|in:draft,publish_now',
        ]);

        $publishAt = !empty($validated['publish_at'])
            ? Carbon::parse($validated['publish_at'])
            : null;

        $submitAction = (string) ($validated['submit_action'] ?? 'draft');
        $publishStatus = 'draft';
        $emailReviewStatus = $this->defaultPendingEmailReviewStatus();
        $reviewedBy = null;
        $reviewedAt = null;

        if ($submitAction === 'publish_now') {
            $publishStatus = ($publishAt && $publishAt->isFuture()) ? 'scheduled' : 'published';
            $emailReviewStatus = 'approved';
            $reviewedBy = $this->resolveSessionUserId($request);
            $reviewedAt = now();
        }

        $rawContent = trim((string) ($validated['konten'] ?? ''));
        $normalizedContent = preg_replace('/\s+/u', ' ', strip_tags($rawContent)) ?? $rawContent;
        $summary = Str::limit(trim($normalizedContent), 240, '...');

        $accountId = !empty($validated['ukm_account_id'])
            ? (int) $validated['ukm_account_id']
            : (int) (DB::table('kemahasiswaan_ukm_accounts')
                ->where('status', $this->defaultAccountStatus())
                ->orderBy('id')
                ->value('id') ?? 0);

        $announcementId = DB::table('kemahasiswaan_announcements')->insertGetId([
            'ukm_account_id' => $accountId > 0 ? $accountId : null,
            'title' => $validated['judul'],
            'category' => $validated['kategori'],
            'target_audience' => $validated['target'],
            'summary' => $summary,
            'content' => $rawContent,
            'publish_at' => $publishAt,
            'publish_status' => $publishStatus,
            'submit_action' => $submitAction,
            'email_review_status' => $emailReviewStatus,
            'email_review_note' => null,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => $reviewedAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $announcementId)->first();
        $account = $announcement ? $this->findAkunUKM((int) $announcement->ukm_account_id) : null;

        $emailDispatchError = null;
        if ($publishStatus === 'published') {
            $emailDispatchError = $this->dispatchAnnouncementEmailById($announcementId);
        }

        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Membuat Pengumuman',
            'description' => $submitAction === 'publish_now'
                ? 'Pengumuman "' . $validated['judul'] . '" dipublikasikan dari modal Kemahasiswaan.'
                : 'Draft pengumuman "' . $validated['judul'] . '" disimpan dari modal Kemahasiswaan.',
        ]);

        $message = 'Pengumuman berhasil disimpan.';
        if ($publishStatus === 'published') {
            $message .= $emailDispatchError === null
                ? ' Distribusi email berhasil dikirim.'
                : ' Distribusi email belum terkirim: ' . $emailDispatchError;
        }

        return back()->with('success', $message);
    }

    public function updatePengumuman(Request $request, int $id): RedirectResponse
    {
        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $id)->first();
        if (!$announcement) {
            return back()->with('error', 'Data pengumuman tidak ditemukan.');
        }

        if ((string) ($announcement->publish_status ?? '') === 'published') {
            return back()->with('error', 'Pengumuman yang sudah terpublikasi tidak dapat diubah jadwalnya.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:140',
            'kategori' => 'required|string|max:60',
            'target' => 'required|string|max:100',
            'konten' => 'required|string|max:10000',
            'publish_at' => 'nullable|date|after_or_equal:now',
            'submit_action' => 'required|in:draft,publish_now',
        ]);

        $publishAt = !empty($validated['publish_at'])
            ? Carbon::parse($validated['publish_at'])
            : null;

        $submitAction = (string) ($validated['submit_action'] ?? 'draft');
        $publishStatus = 'draft';
        $emailReviewStatus = $this->defaultPendingEmailReviewStatus();
        $reviewedBy = null;
        $reviewedAt = null;

        if ($submitAction === 'publish_now') {
            $publishStatus = ($publishAt && $publishAt->isFuture()) ? 'scheduled' : 'published';
            $emailReviewStatus = 'approved';
            $reviewedBy = $this->resolveSessionUserId($request);
            $reviewedAt = now();
        }

        $rawContent = trim((string) ($validated['konten'] ?? ''));
        $normalizedContent = preg_replace('/\s+/u', ' ', strip_tags($rawContent)) ?? $rawContent;
        $summary = Str::limit(trim($normalizedContent), 240, '...');

        DB::table('kemahasiswaan_announcements')
            ->where('id', $id)
            ->update([
                'title' => $validated['judul'],
                'category' => $validated['kategori'],
                'target_audience' => $validated['target'],
                'summary' => $summary,
                'content' => $rawContent,
                'publish_at' => $publishAt,
                'publish_status' => $publishStatus,
                'submit_action' => $submitAction,
                'email_review_status' => $emailReviewStatus,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => $reviewedAt,
                'updated_at' => now(),
            ]);

        $emailDispatchError = null;
        if ($publishStatus === 'published') {
            $emailDispatchError = $this->dispatchAnnouncementEmailById($id);
        }

        $account = $this->findAkunUKM((int) ($announcement->ukm_account_id ?? 0));
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Memperbarui Pengumuman',
            'description' => 'Pengumuman "' . $validated['judul'] . '" diperbarui oleh Departemen Kemahasiswaan.',
        ]);

        $message = 'Pengumuman berhasil diperbarui.';
        if ($publishStatus === 'published') {
            $message .= $emailDispatchError === null
                ? ' Distribusi email berhasil dikirim.'
                : ' Distribusi email belum terkirim: ' . $emailDispatchError;
        }

        return back()->with('success', $message);
    }

    public function destroyPengumuman(int $id): RedirectResponse
    {
        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $id)->first();
        if (!$announcement) {
            return back()->with('error', 'Data pengumuman tidak ditemukan.');
        }

        $status = (string) ($announcement->publish_status ?? '');
        if ($status === 'published') {
            return back()->with('error', 'Pengumuman terpublikasi tidak dapat dihapus.');
        }

        if (!in_array($status, ['draft', 'scheduled'], true)) {
            return back()->with('error', 'Status pengumuman ini tidak dapat dihapus.');
        }

        DB::table('kemahasiswaan_announcements')->where('id', $id)->delete();

        $account = $this->findAkunUKM((int) ($announcement->ukm_account_id ?? 0));
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Menghapus Pengumuman',
            'description' => 'Pengumuman "' . (string) ($announcement->title ?? 'Tanpa Judul') . '" dihapus oleh Departemen Kemahasiswaan.',
        ]);

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function reviewIzinPengumumanEmail(Request $request, int $id): RedirectResponse
    {
        $decisionMap = $this->reviewAnnouncementDecisionMap();

        $validated = $request->validate([
            'decision' => 'required|in:' . implode(',', array_keys($decisionMap)),
            'catatan' => 'nullable|string|max:220',
        ]);

        $decisionConfig = $decisionMap[$validated['decision']] ?? [];
        if (($decisionConfig['requires_note'] ?? false) && empty(trim((string) ($validated['catatan'] ?? '')))) {
            return back()->with('error', 'Catatan wajib diisi jika review email ditolak atau revisi.');
        }

        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $id)->first();
        if (!$announcement) {
            return back()->with('error', 'Data pengumuman tidak ditemukan.');
        }

        $reviewStatus = (string) ($decisionConfig['review_status'] ?? $this->defaultPendingEmailReviewStatus());

        $publishStatus = $announcement->publish_status;
        if ($validated['decision'] === 'setujui') {
            if (!empty($announcement->publish_at)) {
                $publishStatus = (string) ($decisionConfig['publish_status_scheduled'] ?? 'scheduled');
            } else {
                $publishStatus = (string) ($decisionConfig['publish_status'] ?? 'published');
            }
        } else {
            $publishStatus = (string) ($decisionConfig['publish_status'] ?? 'draft');
        }

        DB::table('kemahasiswaan_announcements')
            ->where('id', $id)
            ->update([
                'publish_status' => $publishStatus,
                'email_review_status' => $reviewStatus,
                'email_review_note' => trim((string) ($validated['catatan'] ?? '')) ?: null,
                'reviewed_by' => $this->resolveSessionUserId($request),
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

        $emailDispatchError = null;
        if ($publishStatus === 'published' && $reviewStatus === 'approved') {
            $emailDispatchError = $this->dispatchAnnouncementEmailById($id);
        }

        $account = $this->findAkunUKM((int) $announcement->ukm_account_id);
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Review Izin Pengumuman ke Email',
            'description' => 'Review email pengumuman "' . $announcement->title . '" disimpan oleh Departemen Kemahasiswaan.',
        ]);

        $message = 'Review izin pengumuman ke email berhasil disimpan.';
        if ($publishStatus === 'published' && $reviewStatus === 'approved') {
            $message .= $emailDispatchError === null
                ? ' Distribusi email berhasil dikirim.'
                : ' Distribusi email belum terkirim: ' . $emailDispatchError;
        }

        return back()->with('success', $message);
    }

    // ============ Private Helpers ============

    private function ensureDefaultBemUkmAccount(): void
    {
        if (!Schema::hasTable('organizations') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $organizationRow = DB::table('organizations')
            ->select(['id', 'name', 'shortname'])
            ->whereRaw('LOWER(name) = ?', ['bem unklab'])
            ->orWhereRaw('LOWER(shortname) = ?', ['bem'])
            ->first();

        if (!$organizationRow) {
            $organizationId = DB::table('organizations')->insertGetId([
                'name' => 'BEM UNKLAB',
                'shortname' => 'bem',
                'description' => 'Badan Eksekutif Mahasiswa UNKLAB',
                'status' => $this->organizationActiveStatus(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $organizationId = (int) $organizationRow->id;
        }

        $query = DB::table('kemahasiswaan_ukm_accounts')
            ->select(['id', 'organization_id', 'name', 'status'])
            ->whereRaw('LOWER(email) = ?', ['bem@unklab.ac.id']);

        if (Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash')) {
            $query->addSelect('password_hash');
        }

        $existingBemAccount = $query->first();

        if (!$existingBemAccount) {
            $insertPayload = [
                'organization_id' => $organizationId,
                'user_id' => null,
                'name' => 'Pengurus BEM UNKLAB',
                'email' => 'bem@unklab.ac.id',
                'status' => $this->defaultAccountStatus(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('kemahasiswaan_ukm_accounts')->insert($insertPayload);

            return;
        }

        $updates = [];

        if (empty($existingBemAccount->organization_id)) {
            $updates['organization_id'] = $organizationId;
        }

        if (empty($existingBemAccount->name)) {
            $updates['name'] = 'Pengurus BEM UNKLAB';
        }

        if (($existingBemAccount->status ?? '') !== $this->defaultAccountStatus()) {
            $updates['status'] = $this->defaultAccountStatus();
        }

        if (!empty($updates)) {
            $updates['updated_at'] = now();

            DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $existingBemAccount->id)
                ->update($updates);
        }
    }

    private function getPengumuman(): array
    {
        $publishLabels = [
            'draft' => 'Draft',
            'scheduled' => 'Dijadwalkan',
            'published' => 'Dipublikasikan',
            'archived' => 'Arsip',
        ];

        $reviewLabels = [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'revision' => 'Perlu Revisi',
        ];

        $rows = DB::table('kemahasiswaan_announcements as ann')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
            ->select([
                'ann.id',
                'ann.title',
                'ann.category',
                'ann.target_audience',
                'ann.summary',
                'ann.content',
                'ann.publish_status',
                'ann.publish_at',
                'ann.email_review_status',
                'ann.email_review_note',
                'ann.created_at',
                'ann.updated_at',
                'akun.name as account_name',
                'akun.organization_id',
            ])
            ->orderByDesc('ann.id')
            ->get();

        return $rows->map(function ($row) use ($publishLabels, $reviewLabels) {
            $publishCode = (string) ($row->publish_status ?? 'draft');
            $reviewCode = (string) ($row->email_review_status ?? 'pending');

            return [
                'id' => (int) $row->id,
                'judul' => $row->title,
                'kategori' => $row->category,
                'target' => (string) ($row->target_audience ?? ''),
                'konten' => (string) ($row->content ?? ''),
                'summary' => $row->summary,
                'ringkasan' => $row->summary,
                'organisasi' => (string) ($row->account_name ?? '-'),
                'ukm_account_name' => (string) ($row->account_name ?? '-'),
                'publish_code' => $publishCode,
                'publish_label' => $publishLabels[$publishCode] ?? $publishCode,
                'status_code' => $publishCode,
                'status' => $publishLabels[$publishCode] ?? $publishCode,
                'email_review_code' => $reviewCode,
                'email_review_label' => $reviewLabels[$reviewCode] ?? $reviewCode,
                'email_review_status' => $reviewLabels[$reviewCode] ?? $reviewCode,
                'email_review_note' => $row->email_review_note,
                'publish_at' => $row->publish_at,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
                'account_name' => $row->account_name,
                'organization_id' => $row->organization_id,
            ];
        })->all();
    }

    private function dispatchAnnouncementEmailById(int $announcementId): ?string
    {
        if ($announcementId <= 0 || !Schema::hasTable('kemahasiswaan_announcements')) {
            return 'Data pengumuman tidak valid.';
        }

        $row = DB::table('kemahasiswaan_announcements as ann')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
            ->select([
                'ann.id',
                'ann.title',
                'ann.category',
                'ann.target_audience',
                'ann.summary',
                'ann.content',
                'ann.publish_status',
                'ann.publish_at',
                'ann.ukm_account_id',
                'akun.name as account_name',
                'akun.email as account_email',
            ])
            ->where('ann.id', $announcementId)
            ->first();

        if (!$row) {
            return 'Data pengumuman tidak ditemukan.';
        }

        if ((string) ($row->publish_status ?? '') !== 'published') {
            return null;
        }

        $recipients = $this->resolveAnnouncementRecipients($row);
        if (empty($recipients)) {
            return 'Target email belum dikonfigurasi. Isi mailing list di pengaturan env.';
        }

        $subject = '[UFO] ' . trim((string) ($row->title ?? 'Pengumuman Kampus'));
        $summary = trim((string) ($row->summary ?? ''));
        $content = trim((string) ($row->content ?? ''));
        $category = trim((string) ($row->category ?? 'Umum'));
        $targetAudience = trim((string) ($row->target_audience ?? 'Umum'));
        $publishAt = !empty($row->publish_at)
            ? Carbon::parse((string) $row->publish_at)->format('d M Y H:i')
            : now()->format('d M Y H:i');

        if ($summary === '' && $content !== '') {
            $summary = Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($content)) ?? $content), 240, '...');
        }

        $safeSummary = e($summary);
        $safeContentHtml = $content !== '' ? nl2br(e($content)) : $safeSummary;
        $safeCategory = e($category);
        $safeTarget = e($targetAudience);
        $safePublishAt = e($publishAt);

        $html = "
            <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #222;'>
                <h2 style='margin-bottom: 8px;'>" . e((string) ($row->title ?? 'Pengumuman')) . "</h2>
                <p style='margin: 0 0 14px 0; color: #555;'>Kategori: {$safeCategory} | Target: {$safeTarget} | Publish: {$safePublishAt}</p>
                <div style='padding: 12px; background: #f8f9fb; border: 1px solid #e6e8ef; border-radius: 8px; margin-bottom: 14px;'>{$safeSummary}</div>
                <div>{$safeContentHtml}</div>
                <hr style='margin: 18px 0; border: none; border-top: 1px solid #e6e8ef;'>
                <small style='color: #6b7280;'>Email ini dikirim otomatis oleh sistem UFO Kemahasiswaan.</small>
            </div>
        ";

        try {
            $firstRecipient = array_shift($recipients);
            $bccRecipients = array_values(array_filter($recipients));

            Mail::html($html, function (Message $message) use ($subject, $firstRecipient, $bccRecipients) {
                $message->to($firstRecipient)->subject($subject);
                if (!empty($bccRecipients)) {
                    $message->bcc($bccRecipients);
                }
            });

            return null;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return array<int, string>
     */
    private function resolveAnnouncementRecipients(object $announcement): array
    {
        $target = Str::lower(trim((string) ($announcement->target_audience ?? '')));
        $configuredTargets = config('mail.announcement_targets', []);

        $emails = [];

        if (str_contains($target, '@')) {
            $emails = $this->parseEmailList($target);
        } elseif ($target === 'semua mahasiswa') {
            $emails = $this->parseEmailList((string) ($configuredTargets['all_students'] ?? ''));
        } elseif ($target === 'mahasiswa tertentu') {
            $emails = $this->parseEmailList((string) ($configuredTargets['selected_students'] ?? ''));
        } elseif ($target === 'semua organisasi') {
            if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
                $emails = DB::table('kemahasiswaan_ukm_accounts')
                    ->where('status', $this->defaultAccountStatus())
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->filter(fn ($email) => filter_var((string) $email, FILTER_VALIDATE_EMAIL) !== false)
                    ->map(fn ($email) => Str::lower(trim((string) $email)))
                    ->unique()
                    ->values()
                    ->all();
            }
        } elseif ($target === 'organisasi tertentu') {
            $emails = $this->parseEmailList((string) ($announcement->account_email ?? ''));
        }

        if (empty($emails)) {
            $emails = $this->parseEmailList((string) ($configuredTargets['default'] ?? ''));
        }

        return array_values(array_unique(array_filter($emails)));
    }

    /**
     * @return array<int, string>
     */
    private function parseEmailList(string $raw): array
    {
        $items = preg_split('/[,;\n\r]+/', $raw) ?: [];

        return array_values(array_filter(array_map(function ($item) {
            $email = Str::lower(trim((string) $item));
            return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
        }, $items)));
    }

    private function getAkunUKM(): array
    {
        $statusLabels = [
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
        ];

        $rows = DB::table('kemahasiswaan_ukm_accounts as akun')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select([
                'akun.id',
                'akun.organization_id',
                'akun.name',
                'akun.email',
                'akun.status',
                'akun.created_at',
                'org.name as organization_name',
            ])
            ->orderByDesc('akun.id')
            ->get();

        return $rows->map(function ($row) use ($statusLabels) {
            $statusCode = Str::lower((string) $row->status);

            return [
                'id' => (int) $row->id,
                'name' => $row->name,
                'email' => $row->email,
                'organization_id' => (int) ($row->organization_id ?? 0),
                'organization_name' => $row->organization_name,
                'status' => $statusCode,
                'status_label' => $statusLabels[$statusCode] ?? 'Unknown',
                'created_at' => $row->created_at,
            ];
        })->all();
    }

    private function findAkunUKM(int $id): ?array
    {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return null;
        }

        $row = DB::table('kemahasiswaan_ukm_accounts as akun')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select([
                'akun.id',
                'akun.organization_id',
                'akun.name',
                'akun.email',
                'org.name as organization_name',
            ])
            ->where('akun.id', $id)
            ->first();

        if (!$row) {
            return null;
        }

        return [
            'id' => (int) $row->id,
            'name' => $row->name,
            'email' => $row->email,
            'organization_id' => (int) ($row->organization_id ?? 0),
            'organization_name' => $row->organization_name,
        ];
    }

    private function appendActivityLog(array $payload): void
    {
        if (!Schema::hasTable('kemahasiswaan_activity_logs')) {
            return;
        }

        $insertPayload = [
            'ukm_account_id' => $payload['ukm_account_id'] ?? null,
            'organization_id' => $payload['organization_id'] ?? null,
            'action' => $payload['action'] ?? '',
            'description' => $payload['description'] ?? '',
            'created_at' => now(),
        ];

        DB::table('kemahasiswaan_activity_logs')->insert($insertPayload);
    }

    private function resolveSessionUserId(Request $request): ?int
    {
        $userId = $request->session()->get('user.id');

        return $userId !== null ? (int) $userId : null;
    }

    private function pendingEmailReviewStatuses(): array
    {
        $codes = $this->referenceService->getStatusOptions('pending_email_review_status');

        return !empty($codes) ? $codes : ['pending', 'revision'];
    }

    private function defaultPendingEmailReviewStatus(): string
    {
        $codes = $this->pendingEmailReviewStatuses();

        return $codes[0] ?? 'pending';
    }

    private function reviewAnnouncementDecisionMap(): array
    {
        return [
            'setujui' => [
                'review_status' => 'approved',
                'publish_status' => 'published',
                'publish_status_scheduled' => 'scheduled',
                'requires_note' => false,
                'ui_label' => 'Setujui',
            ],
            'tolak' => [
                'review_status' => 'rejected',
                'publish_status' => 'draft',
                'requires_note' => true,
                'ui_label' => 'Tolak',
            ],
            'revisi' => [
                'review_status' => 'revision',
                'publish_status' => 'draft',
                'requires_note' => true,
                'ui_label' => 'Minta Revisi',
            ],
        ];
    }

    private function uiText(string $code): string
    {
        return $this->referenceService->getLabel('ui_text', $code);
    }

    private function uiTextMap(array $keyCodeMap): array
    {
        $labels = [];

        foreach ($keyCodeMap as $key => $code) {
            $labels[$key] = $this->uiText((string) $code);
        }

        return $labels;
    }

    private function buildPengumumanUiText(): array
    {
        return $this->uiTextMap([
            'total_label' => 'kmh_announcement_total_label',
            'published_label' => 'kmh_announcement_published_label',
            'scheduled_label' => 'kmh_announcement_scheduled_label',
            'draft_label' => 'kmh_announcement_draft_label',
            'search_placeholder' => 'kmh_announcement_search_placeholder',
            'search_aria' => 'kmh_announcement_search_aria',
            'all_statuses' => 'kmh_common_all_statuses',
            'create_new_button' => 'kmh_announcement_create_new_button',
            'create_new_title' => 'kmh_announcement_create_new_title',
            'create_new_subtitle' => 'kmh_announcement_create_new_subtitle',
            'modal_title' => 'kmh_announcement_modal_title',
            'modal_subtitle' => 'kmh_announcement_modal_subtitle',
            'account_missing_warning' => 'kmh_announcement_account_missing_warning',
            'field_title' => 'kmh_announcement_field_title',
            'field_category' => 'kmh_announcement_field_category',
            'field_category_placeholder' => 'kmh_announcement_field_category_placeholder',
            'field_target' => 'kmh_announcement_field_target',
            'field_target_placeholder' => 'kmh_announcement_field_target_placeholder',
            'field_content' => 'kmh_announcement_field_content',
            'field_content_placeholder' => 'kmh_announcement_field_content_placeholder',
            'field_publish_date' => 'kmh_announcement_field_publish_datetime',
            'field_publish_placeholder' => 'kmh_announcement_field_publish_datetime_placeholder',
            'field_account' => 'kmh_announcement_field_account',
            'field_account_placeholder' => 'kmh_announcement_field_account_placeholder',
            'field_summary' => 'kmh_announcement_field_summary',
            'save_button' => 'kmh_common_save_button',
            'cancel_button' => 'kmh_common_cancel_button',
            'save_draft_button' => 'kmh_announcement_save_draft_button',
            'publish_now_button' => 'kmh_announcement_publish_now_button',
            'distribution_info_title' => 'kmh_announcement_distribution_info_title',
            'distribution_info_body' => 'kmh_announcement_distribution_info_body',
            'review_email_title' => 'kmh_announcement_review_email_title',
            'review_email_count_suffix' => 'kmh_announcement_review_email_count_suffix',
            'list_title' => 'kmh_announcement_list_title',
            'list_count_suffix' => 'kmh_announcement_list_count_suffix',
            'review_queue_empty' => 'kmh_announcement_review_queue_empty',
            'list_empty' => 'kmh_announcement_list_empty',
            'list_filter_empty' => 'kmh_announcement_list_filter_empty',
            'note_placeholder' => 'kmh_common_review_note_placeholder',
        ]);
    }
}
