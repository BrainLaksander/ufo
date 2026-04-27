<?php

namespace App\Services\Kemahasiswaan;

use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\Factory as ViewFactory;
use Throwable;

class AnnouncementEmailService
{
    public function dispatchAnnouncementEmailById(int $announcementId): ?string
    {
        if ($announcementId <= 0 || !Schema::hasTable('kemahasiswaan_announcements')) {
            return 'Data pengumuman tidak valid.';
        }

        $row = $this->findAnnouncementRow($announcementId);
        if (!$row) {
            return 'Data pengumuman tidak ditemukan.';
        }

        if ((string) ($row->publish_status ?? '') !== 'published') {
            return null;
        }

        $recipients = $this->resolveAnnouncementRecipients($row);
        if (empty($recipients)) {
            $this->updateDeliveryState($announcementId, 'failed', null, 'Target email belum dikonfigurasi.');

            return 'Target email belum dikonfigurasi. Isi target manual atau default mahasiswa.';
        }

        $subject = '[UFO] ' . trim((string) ($row->title ?? 'Pengumuman Kampus'));
        $viewData = $this->buildEmailViewData($row);

        try {
            $firstRecipient = array_shift($recipients);
            $bccRecipients = array_values(array_filter($recipients));

            Mail::send('emails.kemahasiswaan.announcement', $viewData, function (Message $message) use ($subject, $firstRecipient, $bccRecipients) {
                $message->to($firstRecipient)->subject($subject);

                if (!empty($bccRecipients)) {
                    $message->bcc($bccRecipients);
                }
            });

            $this->updateDeliveryState($announcementId, 'sent', now(), null);

            return null;
        } catch (Throwable $exception) {
            $this->updateDeliveryState($announcementId, 'failed', null, $exception->getMessage());

            return $exception->getMessage();
        }
    }

    /**
     * @return array{processed:int, sent:int, failed:int}
     */
    public function processScheduledAnnouncements(): array
    {
        if (!Schema::hasTable('kemahasiswaan_announcements')) {
            return ['processed' => 0, 'sent' => 0, 'failed' => 0];
        }

        $rows = DB::table('kemahasiswaan_announcements')
            ->where('publish_status', 'scheduled')
            ->whereNotNull('publish_at')
            ->whereRaw('publish_at <= NOW()')
            ->orderBy('publish_at')
            ->orderBy('id')
            ->get();

        // Debug: write discovered rows count to debug log
        try {
            $debugPath = base_path('storage/logs/scheduler-debug.log');
            file_put_contents($debugPath, '[' . now()->toDateTimeString() . '] found_rows=' . count($rows) . PHP_EOL, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            // ignore logging failure
        }

        $processed = 0;
        $sent = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $processed++;

            DB::table('kemahasiswaan_announcements')
                ->where('id', $row->id)
                ->update([
                    'publish_status' => 'published',
                    'email_delivery_status' => 'sending',
                    'email_delivery_error' => null,
                    'updated_at' => now(),
                ]);

            $error = $this->dispatchAnnouncementEmailById((int) $row->id);
            if ($error === null) {
                $sent++;
                continue;
            }

            $failed++;
        }

        return [
            'processed' => $processed,
            'sent' => $sent,
            'failed' => $failed,
        ];
    }

    private function findAnnouncementRow(int $announcementId): ?object
    {
        return DB::table('kemahasiswaan_announcements as ann')
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
                'ann.email_delivery_status',
                'ann.email_dispatched_at',
                'ann.email_delivery_error',
                'ann.ukm_account_id',
                'akun.name as account_name',
                'akun.email as account_email',
            ])
            ->where('ann.id', $announcementId)
            ->first();
    }

    /**
     * @return array<int, string>
     */
    private function resolveAnnouncementRecipients(object $announcement): array
    {
        $mode = Str::lower(trim((string) ($announcement->recipient_mode ?? '')));
        $configuredDefaultRecipient = (string) config('mail.announcement_default_student_recipient', 'student252@student.unklab.ac.id');
        $defaultStudentRecipient = $this->parseEmailList($configuredDefaultRecipient);
        $manualRecipients = $this->parseEmailList((string) ($announcement->recipient_emails ?? ''));

        if ($mode === 'manual') {
            if (!empty($manualRecipients)) {
                return $manualRecipients;
            }

            return $this->resolveLegacyRecipients($announcement);
        }

        if ($mode === 'all_students') {
            return !empty($defaultStudentRecipient)
                ? $defaultStudentRecipient
                : ['student252@student.unklab.ac.id'];
        }

        return $this->resolveLegacyRecipients($announcement);
    }

    /**
     * @return array<int, string>
     */
    private function resolveLegacyRecipients(object $announcement): array
    {
        $target = Str::lower(trim((string) ($announcement->target_audience ?? '')));
        $configuredTargets = config('mail.announcement_targets', []);
        $emails = [];

        if (str_contains($target, '@')) {
            $emails = $this->parseEmailList($target);
        } elseif ($target === 'semua mahasiswa') {
            $defaultStudentRecipient = (string) config('mail.announcement_default_student_recipient', 'student252@student.unklab.ac.id');
            $emails = $this->parseEmailList($defaultStudentRecipient);
        } elseif ($target === 'mahasiswa tertentu') {
            $emails = $this->parseEmailList((string) ($configuredTargets['selected_students'] ?? ''));
        } elseif ($target === 'semua organisasi') {
            if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
                $emails = DB::table('kemahasiswaan_ukm_accounts')
                    ->where('status', 'active')
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

    /**
     * @return array<string, mixed>
     */
    private function buildEmailViewData(object $announcement): array
    {
        $category = trim((string) ($announcement->category ?? 'Umum'));
        $targetMode = Str::lower(trim((string) ($announcement->recipient_mode ?? '')));
        $targetLabel = $targetMode === 'manual'
            ? 'Manual'
            : ($targetMode === 'all_students' ? 'Semua Mahasiswa' : trim((string) ($announcement->target_audience ?? 'Semua Mahasiswa')));
        $summary = trim((string) ($announcement->summary ?? ''));
        $content = trim((string) ($announcement->content ?? ''));

        if ($summary === '' && $content !== '') {
            $normalizedContent = preg_replace('/\s+/u', ' ', strip_tags($content)) ?? $content;
            $summary = Str::limit(trim($normalizedContent), 240, '...');
        }

        $publishAt = !empty($announcement->publish_at)
            ? Carbon::parse((string) $announcement->publish_at)->format('d M Y H:i')
            : now()->format('d M Y H:i');

        return [
            'announcement' => $announcement,
            'subject' => '[UFO] ' . trim((string) ($announcement->title ?? 'Pengumuman Kampus')),
            'summary' => $summary,
            'content_html' => $content !== '' ? nl2br(e($content)) : e($summary),
            'category' => $category,
            'target_label' => $targetLabel,
            'publish_at' => $publishAt,
            'sender_name' => (string) config('mail.from.name', 'UFO'),
            'sender_email' => (string) config('mail.from.address', 'noreply@example.com'),
            'footer_address' => (string) config('mail.announcement_footer_address', ''),
        ];
    }

    private function updateDeliveryState(int $announcementId, string $status, ?Carbon $dispatchedAt, ?string $error): void
    {
        if (!Schema::hasTable('kemahasiswaan_announcements')) {
            return;
        }

        DB::table('kemahasiswaan_announcements')
            ->where('id', $announcementId)
            ->update([
                'email_delivery_status' => $status,
                'email_dispatched_at' => $dispatchedAt,
                'email_delivery_error' => $error,
                'updated_at' => now(),
            ]);
    }
}
