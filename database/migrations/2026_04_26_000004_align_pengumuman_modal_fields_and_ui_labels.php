<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kemahasiswaan_announcements') && !Schema::hasColumn('kemahasiswaan_announcements', 'submit_action')) {
            Schema::table('kemahasiswaan_announcements', function (Blueprint $table) {
                $table->enum('submit_action', ['draft', 'publish_now'])
                    ->default('draft')
                    ->after('publish_status');
            });
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        $domain = 'ui_text';

        $records = [
            'kmh_announcement_modal_title' => 'Buat Pengumuman Baru',
            'kmh_announcement_modal_subtitle' => 'Lengkapi detail pengumuman dan pilih aksi publikasi.',
            'kmh_announcement_field_category_placeholder' => 'Pilih Kategori',
            'kmh_announcement_field_target_placeholder' => 'Pilih target distribusi',
            'kmh_announcement_field_content' => 'Konten Pengumuman',
            'kmh_announcement_field_content_placeholder' => 'Tulis konten pengumuman di sini...',
            'kmh_announcement_field_publish_datetime' => 'Jadwal Publish (Opsional)',
            'kmh_announcement_field_publish_datetime_placeholder' => 'dd/mm/yyyy, --:--',
            'kmh_announcement_save_draft_button' => 'Simpan sebagai Draft',
            'kmh_announcement_publish_now_button' => 'Publikasikan Sekarang',
            'kmh_common_cancel_button' => 'Batal',
            'kmh_announcement_distribution_info_body' => 'Pengumuman yang dipublikasikan akan didistribusikan otomatis ke email resmi target yang dipilih. Pastikan konten sudah benar sebelum dipublikasikan.',
        ];

        $sortBase = (int) DB::table('workflow_reference_values')
            ->where('domain', $domain)
            ->max('sort_order');

        $counter = 1;
        foreach ($records as $code => $label) {
            DB::table('workflow_reference_values')->updateOrInsert(
                ['domain' => $domain, 'code' => $code],
                [
                    'label' => $label,
                    'payload' => null,
                    'sort_order' => $sortBase + $counter,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $counter++;
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kemahasiswaan_announcements') && Schema::hasColumn('kemahasiswaan_announcements', 'submit_action')) {
            Schema::table('kemahasiswaan_announcements', function (Blueprint $table) {
                $table->dropColumn('submit_action');
            });
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        DB::table('workflow_reference_values')
            ->where('domain', 'ui_text')
            ->whereIn('code', [
                'kmh_announcement_modal_title',
                'kmh_announcement_modal_subtitle',
                'kmh_announcement_field_category_placeholder',
                'kmh_announcement_field_target_placeholder',
                'kmh_announcement_field_content',
                'kmh_announcement_field_content_placeholder',
                'kmh_announcement_field_publish_datetime',
                'kmh_announcement_field_publish_datetime_placeholder',
                'kmh_announcement_save_draft_button',
                'kmh_announcement_publish_now_button',
                'kmh_common_cancel_button',
            ])
            ->delete();
    }
};
