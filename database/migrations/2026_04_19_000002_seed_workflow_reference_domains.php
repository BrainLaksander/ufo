<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        $now = now();

        foreach ($this->referenceData() as $domain => $entries) {
            $sortOrder = 0;

            foreach ($entries as $code => $entry) {
                $label = trim((string) ($entry['label'] ?? ''));
                $payload = $entry['payload'] ?? null;
                $isActive = array_key_exists('is_active', $entry) ? (bool) $entry['is_active'] : true;
                $rowSortOrder = array_key_exists('sort_order', $entry) ? (int) $entry['sort_order'] : $sortOrder;

                $encodedPayload = null;
                if (is_array($payload)) {
                    $encodedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }

                DB::table('workflow_reference_values')->updateOrInsert(
                    [
                        'domain' => $domain,
                        'code' => (string) $code,
                    ],
                    [
                        'label' => $label !== '' ? $label : null,
                        'payload' => $encodedPayload,
                        'sort_order' => $rowSortOrder,
                        'is_active' => $isActive,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );

                $sortOrder++;
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        foreach ($this->referenceData() as $domain => $entries) {
            DB::table('workflow_reference_values')
                ->where('domain', $domain)
                ->whereIn('code', array_map('strval', array_keys($entries)))
                ->delete();
        }
    }

    private function referenceData(): array
    {
        return [
            'flash_message' => [
                'org_context_missing' => [
                    'label' => 'Akun pengurus belum terhubung ke organisasi pada database.',
                ],
                'profile_updated' => [
                    'label' => 'Profil organisasi berhasil diperbarui.',
                ],
                'pengurus_data_incomplete' => [
                    'label' => 'Data pengurus tidak lengkap. Pastikan akun terhubung dengan organisasi.',
                ],
                'event_requires_approved_submission' => [
                    'label' => 'Event hanya dapat dibuat setelah ada pengajuan kegiatan berstatus disetujui.',
                ],
                'event_created' => [
                    'label' => 'Event berhasil dibuat.',
                ],
                'proposal_created' => [
                    'label' => 'Pengajuan kegiatan berhasil dibuat.',
                ],
                'report_event_invalid' => [
                    'label' => 'Event yang dipilih tidak valid untuk organisasi ini.',
                ],
                'report_draft_created' => [
                    'label' => 'Draft laporan kegiatan berhasil dibuat.',
                ],
                'submission_not_found' => [
                    'label' => 'Data pengajuan tidak ditemukan.',
                ],
                'submission_not_owned' => [
                    'label' => 'Pengajuan ini bukan milik organisasi Anda.',
                ],
                'submission_status_invalid_for_submit' => [
                    'label' => 'Status pengajuan tidak dapat dikirim.',
                ],
                'submission_submitted' => [
                    'label' => 'Pengajuan berhasil dikirim untuk direview.',
                ],
                'report_not_found' => [
                    'label' => 'Data laporan tidak ditemukan.',
                ],
                'report_not_owned' => [
                    'label' => 'Laporan ini bukan milik organisasi Anda.',
                ],
                'report_status_invalid_for_submit' => [
                    'label' => 'Status laporan tidak dapat dikirim.',
                ],
                'report_submitted' => [
                    'label' => 'Laporan berhasil dikirim untuk direview.',
                ],
                'review_note_required_submission' => [
                    'label' => 'Catatan wajib diisi untuk keputusan tolak atau revisi pengajuan.',
                ],
                'submission_still_draft' => [
                    'label' => 'Pengajuan masih draft dan belum bisa direview.',
                ],
                'submission_review_config_missing' => [
                    'label' => 'Konfigurasi review pengajuan belum lengkap.',
                ],
                'submission_review_saved' => [
                    'label' => 'Hasil review pengajuan berhasil disimpan.',
                ],
                'review_note_required_report' => [
                    'label' => 'Catatan wajib diisi untuk keputusan tolak atau revisi laporan.',
                ],
                'report_still_draft' => [
                    'label' => 'Laporan masih draft dan belum bisa direview.',
                ],
                'report_review_config_missing' => [
                    'label' => 'Konfigurasi review laporan belum lengkap.',
                ],
                'report_review_saved' => [
                    'label' => 'Hasil review laporan berhasil disimpan.',
                ],
                'schedule_created' => [
                    'label' => 'Jadwal kegiatan berhasil disimpan.',
                ],
            ],
            'ui_text' => [
                'activity_default_description' => [
                    'label' => 'Aktivitas baru tercatat.',
                ],
                'task_deadline_none' => [
                    'label' => 'Tanpa tenggat waktu',
                ],
                'task_deadline_overdue_prefix' => [
                    'label' => 'Lewat tenggat ',
                ],
            ],
            'submission_status_map' => [
                'draft' => ['label' => 'Draft'],
                'submitted' => ['label' => 'Diajukan'],
                'reviewing' => ['label' => 'Sedang Direview'],
                'approved' => ['label' => 'Disetujui'],
                'rejected' => ['label' => 'Ditolak'],
                'revised' => ['label' => 'Revisi'],
            ],
            'report_status_map' => [
                'draft' => ['label' => 'Draft'],
                'submitted' => ['label' => 'Diajukan'],
                'reviewing' => ['label' => 'Sedang Direview'],
                'approved' => ['label' => 'Disetujui'],
                'rejected' => ['label' => 'Ditolak'],
                'revision_needed' => ['label' => 'Revisi'],
            ],
            'pending_submission_status' => [
                'submitted' => ['label' => 'Diajukan'],
                'reviewing' => ['label' => 'Sedang Direview'],
                'revised' => ['label' => 'Revisi'],
            ],
            'pending_report_status' => [
                'submitted' => ['label' => 'Diajukan'],
                'reviewing' => ['label' => 'Sedang Direview'],
                'revision_needed' => ['label' => 'Revisi'],
            ],
            'pending_email_review_status' => [
                'pending' => ['label' => 'Menunggu Review'],
                'revision' => ['label' => 'Perlu Revisi'],
            ],
            'review_submission_decision_map' => [
                'disetujui' => [
                    'label' => 'Setujui',
                    'payload' => [
                        'value' => 'approved',
                        'approved' => true,
                        'requires_note' => false,
                    ],
                ],
                'ditolak' => [
                    'label' => 'Tolak',
                    'payload' => [
                        'value' => 'rejected',
                        'approved' => false,
                        'requires_note' => true,
                    ],
                ],
                'revisi' => [
                    'label' => 'Revisi',
                    'payload' => [
                        'value' => 'revised',
                        'approved' => false,
                        'requires_note' => true,
                    ],
                ],
            ],
            'review_report_decision_map' => [
                'disetujui' => [
                    'label' => 'Setujui',
                    'payload' => [
                        'value' => 'approved',
                        'approved' => true,
                        'requires_note' => false,
                    ],
                ],
                'ditolak' => [
                    'label' => 'Tolak',
                    'payload' => [
                        'value' => 'rejected',
                        'approved' => false,
                        'requires_note' => true,
                    ],
                ],
                'revisi' => [
                    'label' => 'Revisi',
                    'payload' => [
                        'value' => 'revision_needed',
                        'approved' => false,
                        'requires_note' => true,
                    ],
                ],
            ],
            'review_announcement_decision_map' => [
                'setujui' => [
                    'label' => 'Setujui',
                    'payload' => [
                        'review_status' => 'approved',
                        'publish_status' => 'published',
                        'publish_status_scheduled' => 'scheduled',
                        'requires_note' => false,
                    ],
                ],
                'tolak' => [
                    'label' => 'Tolak',
                    'payload' => [
                        'review_status' => 'rejected',
                        'publish_status' => 'draft',
                        'requires_note' => true,
                    ],
                ],
                'revisi' => [
                    'label' => 'Revisi',
                    'payload' => [
                        'review_status' => 'revision',
                        'publish_status' => 'draft',
                        'requires_note' => true,
                    ],
                ],
            ],
            'event_status_map' => [
                'draft' => ['label' => 'Draft', 'payload' => ['value' => 'draft']],
                'future' => ['label' => 'Akan Datang', 'payload' => ['value' => 'pending']],
                'ongoing' => ['label' => 'Berlangsung', 'payload' => ['value' => 'approved']],
                'completed' => ['label' => 'Selesai', 'payload' => ['value' => 'approved']],
                'cancelled' => ['label' => 'Dibatalkan', 'payload' => ['value' => 'rejected']],
                'default' => ['label' => 'Aktif', 'payload' => ['value' => 'approved']],
            ],
            'event_dashboard_status_map' => [
                'draft' => ['label' => 'Draft', 'payload' => ['tone' => 'pending']],
                'approved' => ['label' => 'Aktif', 'payload' => ['tone' => 'active']],
                'ongoing' => ['label' => 'Aktif', 'payload' => ['tone' => 'active']],
                'completed' => ['label' => 'Selesai', 'payload' => ['tone' => 'completed']],
                'cancelled' => ['label' => 'Batal', 'payload' => ['tone' => 'pending']],
                'default' => ['label' => 'Aktif', 'payload' => ['tone' => 'active']],
            ],
            'ongoing_event_status' => [
                'approved' => ['label' => 'Disetujui'],
                'ongoing' => ['label' => 'Berlangsung'],
            ],
            'announcement_status_map' => [
                'published' => ['label' => 'Aktif', 'payload' => ['value' => 'approved']],
                'scheduled' => ['label' => 'Terjadwal', 'payload' => ['value' => 'pending']],
                'archived' => ['label' => 'Arsip', 'payload' => ['value' => 'draft']],
                'default' => ['label' => 'Draft', 'payload' => ['value' => 'draft']],
            ],
            'announcement_publish_status_map' => [
                'draft' => ['label' => 'Draft'],
                'scheduled' => ['label' => 'Terjadwal'],
                'published' => ['label' => 'Terpublikasi'],
                'archived' => ['label' => 'Arsip'],
            ],
            'announcement_email_review_status_map' => [
                'pending' => ['label' => 'Menunggu Review'],
                'approved' => ['label' => 'Disetujui'],
                'rejected' => ['label' => 'Ditolak'],
                'revision' => ['label' => 'Perlu Revisi'],
            ],
            'lostfound_moderation_map' => [
                'active' => ['label' => 'approved', 'payload' => ['value' => 'belum_ditemukan']],
                'claimed' => ['label' => 'approved', 'payload' => ['value' => 'sudah_ditemukan']],
                'closed' => ['label' => 'rejected', 'payload' => ['value' => 'sudah_dikembalikan']],
                'default' => ['label' => 'pending', 'payload' => ['value' => 'belum_ditemukan']],
            ],
            'lostfound_review_status' => [
                'pending' => ['label' => 'Menunggu Konfirmasi BEM', 'payload' => ['pill' => 'pending']],
                'approved' => ['label' => 'Disetujui & Dipublikasikan', 'payload' => ['pill' => 'approved']],
                'rejected' => ['label' => 'Ditolak', 'payload' => ['pill' => 'rejected']],
            ],
            'notification_action_rule' => [
                'default' => [
                    'label' => 'Default',
                    'payload' => [
                        'icon' => 'bi-info-circle',
                        'tone' => 'primary',
                        'keywords' => [],
                    ],
                ],
                'submission' => [
                    'label' => 'Submission',
                    'payload' => [
                        'icon' => 'bi-file-earmark-text',
                        'tone' => 'warning',
                        'keywords' => ['submission', 'proposal', 'pengajuan'],
                    ],
                ],
                'report' => [
                    'label' => 'Report',
                    'payload' => [
                        'icon' => 'bi-clipboard-data',
                        'tone' => 'warning',
                        'keywords' => ['report', 'laporan'],
                    ],
                ],
                'event' => [
                    'label' => 'Event',
                    'payload' => [
                        'icon' => 'bi-calendar-event',
                        'tone' => 'primary',
                        'keywords' => ['event', 'jadwal', 'schedule'],
                    ],
                ],
                'announcement' => [
                    'label' => 'Announcement',
                    'payload' => [
                        'icon' => 'bi-megaphone',
                        'tone' => 'primary',
                        'keywords' => ['announcement', 'pengumuman'],
                    ],
                ],
                'account' => [
                    'label' => 'Account',
                    'payload' => [
                        'icon' => 'bi-person-gear',
                        'tone' => 'primary',
                        'keywords' => ['akun', 'account', 'password'],
                    ],
                ],
            ],
            'event_status_category_map' => [
                'draft' => ['label' => 'org'],
                'approved' => ['label' => 'org'],
                'ongoing' => ['label' => 'org'],
                'completed' => ['label' => 'campus'],
                'cancelled' => ['label' => 'restricted'],
                'default' => ['label' => 'org'],
            ],
            'schedule_status_category_map' => [
                'planned' => ['label' => 'acad'],
                'ongoing' => ['label' => 'org'],
                'completed' => ['label' => 'campus'],
                'cancelled' => ['label' => 'restricted'],
                'default' => ['label' => 'acad'],
            ],
            'dashboard_summary_label' => [
                'academic' => ['label' => 'Total Kegiatan Akademik'],
                'organization' => ['label' => 'Total Kegiatan Organisasi'],
                'month' => ['label' => 'Bulan Ini'],
                'total' => ['label' => 'Total Semua Event'],
            ],
            'dashboard_legend' => [
                'acad' => ['label' => 'Kegiatan Akademik', 'payload' => ['emoji' => '🎓']],
                'org' => ['label' => 'Kegiatan Organisasi', 'payload' => ['emoji' => '📣']],
                'restricted' => ['label' => 'Masa Tidak Boleh Berorganisasi', 'payload' => ['emoji' => '⛔']],
                'holiday' => ['label' => 'Libur Akademik', 'payload' => ['emoji' => '🏖']],
                'campus' => ['label' => 'Event Kampus Besar', 'payload' => ['emoji' => '🎉']],
            ],
            'dashboard_setting' => [
                'default_category' => ['label' => 'org'],
                'default_event' => ['label' => 'Default Event', 'payload' => ['emoji' => '📌']],
            ],
            'ukm_account_status_map' => [
                'active' => ['label' => 'Aktif', 'payload' => ['value' => 'active']],
                'inactive' => ['label' => 'Nonaktif', 'payload' => ['value' => 'inactive']],
            ],
            'kmh_notification_type_map' => [
                'semua' => ['label' => 'Semua Notifikasi', 'payload' => ['value' => 'semua']],
                'pengajuan' => ['label' => 'Pengajuan Kegiatan', 'payload' => ['value' => 'pengajuan']],
                'laporan' => ['label' => 'Laporan Masuk', 'payload' => ['value' => 'laporan']],
                'pengumuman' => ['label' => 'Review Pengumuman', 'payload' => ['value' => 'pengumuman']],
                'akun' => ['label' => 'Aktivitas Akun UKM', 'payload' => ['value' => 'akun']],
                'jadwal' => ['label' => 'Jadwal Kegiatan', 'payload' => ['value' => 'jadwal']],
            ],
            'organization_active_status' => [
                'active' => ['label' => 'Aktif', 'payload' => ['value' => 'active']],
            ],
        ];
    }
};
