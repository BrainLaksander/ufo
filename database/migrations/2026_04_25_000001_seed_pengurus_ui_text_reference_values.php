<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        $domain = 'ui_text';

        $records = [
            'pengurus_profile_status_label' => 'Status Profil',
            'pengurus_event_name_placeholder' => 'Nama event {organization}',
            'pengurus_event_location_placeholder' => 'Lokasi kegiatan {organization}',
            'pengurus_event_description_placeholder' => 'Jelaskan tujuan, agenda, dan manfaat event {organization}.',
            'pengurus_proposal_title_placeholder' => 'Proposal kegiatan {organization}',
            'pengurus_proposal_description_placeholder' => 'Jelaskan detail kegiatan yang akan diajukan untuk {organization}.',
            'pengurus_report_title_placeholder' => 'Laporan kegiatan {organization}',
            'pengurus_report_content_placeholder' => 'Ceritakan hasil kegiatan, evaluasi, dan dampak program {organization}.',
            'pengurus_announcement_title_placeholder' => 'Pengumuman {organization}',
            'pengurus_announcement_description_placeholder' => 'Jelaskan isi pengumuman untuk {organization}.',
            'pengurus_event_news_title_placeholder' => 'Berita event {organization}',
            'pengurus_event_news_description_placeholder' => 'Ceritakan bagaimana event {organization} berlangsung dan highlight-nya.',
            'pengurus_event_news_highlight_placeholder' => 'Sorot momen utama, capaian, atau hasil paling penting dari kegiatan.',
            'pengurus_support_info_title' => 'Informasi Tambahan',
            'pengurus_support_info_text_1' => 'Silakan hubungi kontak organisasi yang tersedia untuk koordinasi lanjutan.',
            'pengurus_support_info_text_2' => 'Pastikan waktu komunikasi disesuaikan dengan jam operasional pihak terkait.',
            'pengurus_support_info_text_3' => 'Untuk kebutuhan mendesak, gunakan kanal kontak utama organisasi.',
        ];

        $sortBase = (int) DB::table('workflow_reference_values')
            ->where('domain', $domain)
            ->max('sort_order');

        $i = 1;
        foreach ($records as $code => $label) {
            DB::table('workflow_reference_values')->updateOrInsert(
                ['domain' => $domain, 'code' => $code],
                [
                    'label' => $label,
                    'payload' => null,
                    'sort_order' => $sortBase + $i,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            $i++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        DB::table('workflow_reference_values')
            ->where('domain', 'ui_text')
            ->whereIn('code', [
                'pengurus_profile_status_label',
                'pengurus_event_name_placeholder',
                'pengurus_event_location_placeholder',
                'pengurus_event_description_placeholder',
                'pengurus_proposal_title_placeholder',
                'pengurus_proposal_description_placeholder',
                'pengurus_report_title_placeholder',
                'pengurus_report_content_placeholder',
                'pengurus_announcement_title_placeholder',
                'pengurus_announcement_description_placeholder',
                'pengurus_event_news_title_placeholder',
                'pengurus_event_news_description_placeholder',
                'pengurus_event_news_highlight_placeholder',
                'pengurus_support_info_title',
                'pengurus_support_info_icon_1',
                'pengurus_support_info_text_1',
                'pengurus_support_info_icon_2',
                'pengurus_support_info_text_2',
                'pengurus_support_info_icon_3',
                'pengurus_support_info_text_3',
            ])
            ->delete();
    }
};
