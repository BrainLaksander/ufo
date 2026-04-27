from pathlib import Path
import re
import subprocess

source = subprocess.check_output([
    'git', 'show', 'HEAD:app/Http/Controllers/Kemahasiswaan/AnnouncementAdminController.php'
], text=True)
text = source

pengumuman_index = '''    public function pengumumanIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();

        $pengumuman = $this->getPengumuman();
        $pendingEmailReviewStatuses = $this->pendingEmailReviewStatuses();

        $reviewQueue = array_values(array_filter(
            $pengumuman,
            fn (array $item) => in_array($item['email_review_code'], $pendingEmailReviewStatuses, true)
        ));

        $scheduledEmailQueue = array_values(array_filter(
            $pengumuman,
            fn (array $item) => ($item['status_code'] ?? '') === 'scheduled' && in_array((string) ($item['email_delivery_status'] ?? 'queued'), ['queued', 'failed'], true)
        ));

        $sentEmailQueue = array_values(array_filter(
            $pengumuman,
            fn (array $item) => (string) ($item['email_delivery_status'] ?? '') === 'sent'
        ));

        return view('pages.kemahasiswaan.pengumuman', [
            'workflowPengumuman' => $pengumuman,
            'emailReviewQueue' => $reviewQueue,
            'scheduledEmailQueue' => $scheduledEmailQueue,
            'sentEmailQueue' => $sentEmailQueue,
            'ukmAccounts' => $this->getAkunUKM(),
            'ui' => $this->buildPengumumanUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function storePengumuman(Request $request): RedirectResponse'''

store_method = '''    public function storePengumuman(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:140',
            'kategori' => 'required|string|max:60',
            'target' => 'required|in:all_students,manual',
            'manual_recipients' => 'nullable|string|max:4000',
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
        $recipientMode = (string) ($validated['target'] ?? 'all_students');
        $manualRecipients = trim((string) ($validated['manual_recipients'] ?? ''));
        $targetAudience = $recipientMode === 'manual' ? 'Manual' : 'Semua Mahasiswa';
        $emailDeliveryStatus = 'pending';

        if ($submitAction === 'publish_now') {
            $publishStatus = ($publishAt && $publishAt->isFuture()) ? 'scheduled' : 'published';
            $emailReviewStatus = 'approved';
            $reviewedBy = $this->resolveSessionUserId($request);
            $reviewedAt = now();
            $emailDeliveryStatus = $publishStatus === 'scheduled' ? 'queued' : 'sending';
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
            'target_audience' => $targetAudience,
            'recipient_mode' => $recipientMode,
            'recipient_emails' => $recipientMode === 'manual' ? $manualRecipients : null,
            'summary' => $summary,
            'content' => $rawContent,
            'publish_at' => $publishAt,
            'publish_status' => $publishStatus,
            'submit_action' => $submitAction,
            'email_review_status' => $emailReviewStatus,
            'email_delivery_status' => $emailDeliveryStatus,
            'email_delivery_error' => null,
            'email_dispatched_at' => null,
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
            $emailDispatchError = app(AnnouncementEmailService::class)->dispatchAnnouncementEmailById($announcementId);
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

    public function updatePengumuman(Request $request, int $id): RedirectResponse'''

update_method = '''    public function updatePengumuman(Request $request, int $id): RedirectResponse
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
            'target' => 'required|in:all_students,manual',
            'manual_recipients' => 'nullable|string|max:4000',
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
        $recipientMode = (string) ($validated['target'] ?? 'all_students');
        $manualRecipients = trim((string) ($validated['manual_recipients'] ?? ''));
        $targetAudience = $recipientMode === 'manual' ? 'Manual' : 'Semua Mahasiswa';
        $emailDeliveryStatus = 'pending';

        if ($submitAction === 'publish_now') {
            $publishStatus = ($publishAt && $publishAt->isFuture()) ? 'scheduled' : 'published';
            $emailReviewStatus = 'approved';
            $reviewedBy = $this->resolveSessionUserId($request);
            $reviewedAt = now();
            $emailDeliveryStatus = $publishStatus === 'scheduled' ? 'queued' : 'sending';
        }

        $rawContent = trim((string) ($validated['konten'] ?? ''));
        $normalizedContent = preg_replace('/\s+/u', ' ', strip_tags($rawContent)) ?? $rawContent;
        $summary = Str::limit(trim($normalizedContent), 240, '...');

        DB::table('kemahasiswaan_announcements')
            ->where('id', $id)
            ->update([
                'title' => $validated['judul'],
                'category' => $validated['kategori'],
                'target_audience' => $targetAudience,
                'recipient_mode' => $recipientMode,
                'recipient_emails' => $recipientMode === 'manual' ? $manualRecipients : null,
                'summary' => $summary,
                'content' => $rawContent,
                'publish_at' => $publishAt,
                'publish_status' => $publishStatus,
                'submit_action' => $submitAction,
                'email_review_status' => $emailReviewStatus,
                'email_delivery_status' => $emailDeliveryStatus,
                'email_delivery_error' => null,
                'email_dispatched_at' => null,
                'email_review_note' => null,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => $reviewedAt,
                'updated_at' => now(),
            ]);

        $emailDispatchError = null;
        if ($publishStatus === 'published') {
            $emailDispatchError = app(AnnouncementEmailService::class)->dispatchAnnouncementEmailById($id);
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

    public function destroyPengumuman(int $id): RedirectResponse'''

destroy_review = '''    public function destroyPengumuman(int $id): RedirectResponse
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
            $emailDispatchError = app(AnnouncementEmailService::class)->dispatchAnnouncementEmailById($id);
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
'''

get_pengumuman = '''    private function getPengumuman(): array
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
            'rejected' => 'Ditolak',
        ];

        $rows = DB::table('kemahasiswaan_announcements as ann')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
            ->select([
                'ann.id',
                'ann.title',
                'ann.category',
                'ann.target_audience',
                'ann.recipient_mode',
                'ann.recipient_emails',
                'ann.summary',
                'ann.content',
                'ann.publish_status',
                'ann.publish_at',
                'ann.email_review_status',
                'ann.email_review_note',
                'ann.email_delivery_status',
                'ann.email_dispatched_at',
                'ann.email_delivery_error',
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
            $deliveryStatus = (string) ($row->email_delivery_status ?? 'pending');
            $recipientMode = (string) ($row->recipient_mode ?? '');

            return [
                'id' => (int) $row->id,
                'judul' => (string) ($row->title ?? ''),
                'kategori' => (string) ($row->category ?? ''),
                'target' => $recipientMode === 'manual' ? 'Manual' : ($recipientMode === 'all_students' ? 'Semua Mahasiswa' : (string) ($row->target_audience ?? '')),
                'target_mode' => $recipientMode,
                'recipient_mode' => $recipientMode,
                'recipient_emails' => (string) ($row->recipient_emails ?? ''),
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
                'email_delivery_status' => $deliveryStatus,
                'email_delivery_label' => $this->emailDeliveryLabel($deliveryStatus),
                'email_dispatched_at' => $row->email_dispatched_at,
                'email_delivery_error' => $row->email_delivery_error,
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
        return app(AnnouncementEmailService::class)->dispatchAnnouncementEmailById($announcementId);
    }

    /**
     * @return array<int, string>
     */
    private function resolveAnnouncementRecipients(object $announcement): array
    {
        return [];
    }

    /**
     * @return array<int, string>
     */
    private function parseEmailList(string $raw): array
    {
        return [];
    }

    private function emailDeliveryLabel(string $status): string
    {
        return match ($status) {
            'queued' => 'Menunggu Kirim',
            'sending' => 'Sedang Dikirim',
            'sent' => 'Terkirim',
            'failed' => 'Gagal',
            default => 'Belum Diproses',
        };
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
            'field_manual_recipients' => 'kmh_announcement_field_manual_recipients',
            'field_manual_recipients_placeholder' => 'kmh_announcement_field_manual_recipients_placeholder',
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
            'queue_title' => 'kmh_announcement_queue_title',
            'queue_count_suffix' => 'kmh_announcement_queue_count_suffix',
            'queue_empty' => 'kmh_announcement_queue_empty',
            'sent_title' => 'kmh_announcement_sent_title',
            'sent_count_suffix' => 'kmh_announcement_sent_count_suffix',
            'sent_empty' => 'kmh_announcement_sent_empty',
            'default_student_recipient_label' => 'kmh_announcement_default_student_recipient_label',
            'list_title' => 'kmh_announcement_list_title',
            'list_count_suffix' => 'kmh_announcement_list_count_suffix',
            'review_queue_empty' => 'kmh_announcement_review_queue_empty',
            'list_empty' => 'kmh_announcement_list_empty',
            'list_filter_empty' => 'kmh_announcement_list_filter_empty',
            'note_placeholder' => 'kmh_common_review_note_placeholder',
        ]);
    }
'''

patterns = [
    (r"    public function pengumumanIndex\(\): View\n    \{.*?    public function storePengumuman\(Request \$request\): RedirectResponse", pengumuman_index),
    (r"    public function storePengumuman\(Request \$request\): RedirectResponse\n    \{.*?    public function updatePengumuman\(Request \$request, int \$id\): RedirectResponse", store_method),
    (r"    public function updatePengumuman\(Request \$request, int \$id\): RedirectResponse\n    \{.*?    public function destroyPengumuman\(int \$id\): RedirectResponse", update_method),
    (r"    public function destroyPengumuman\(int \$id\): RedirectResponse\n    \{.*?    // ============ Private Helpers ============", destroy_review),
    (r"    private function getPengumuman\(\): array\n    \{.*?    private function buildPengumumanUiText\(\): array", get_pengumuman),
]

for pattern, replacement in patterns:
    text, count = re.subn(pattern, lambda match: replacement, text, flags=re.S)
    if count != 1:
        raise SystemExit(f'Pattern replacement failed for {pattern}: {count}')

Path('app/Http/Controllers/Kemahasiswaan/AnnouncementAdminController.php').write_text(text)
print('controller rewritten')
