<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        $domain = 'ui_text';

        $records = [
            'mahasiswa_lost_found_table_missing' => 'Tabel Lost & Found belum tersedia.',
            'mahasiswa_lost_found_schema_unsupported' => 'Struktur tabel Lost & Found belum mendukung input publik.',
            'mahasiswa_lost_found_saved' => 'Laporan Lost & Found berhasil disimpan.',
            'mahasiswa_category_all_label' => 'Semua',
            'mahasiswa_notification_system_category' => 'Sistem',
            'mahasiswa_notification_announcement_default_title' => 'Pengumuman baru',
            'mahasiswa_notification_announcement_default_category' => 'Pengumuman',
            'mahasiswa_notification_event_default_title' => 'Event kampus',
            'mahasiswa_notification_event_default_category' => 'Event',
            'mahasiswa_notification_lostfound_prefix' => 'Update Lost & Found',
            'mahasiswa_notification_lostfound_default_name' => 'Barang baru',
            'mahasiswa_notification_lostfound_category' => 'Lost and Found',
            'mahasiswa_placeholder_dash' => '-',
            'mahasiswa_org_acronym_default' => 'ORG',
            'mahasiswa_org_category_default' => 'Organisasi Umum',
            'mahasiswa_org_category_bem' => 'BEM',
            'mahasiswa_org_category_choir' => 'Choir',
            'mahasiswa_org_category_creative' => 'Creative Club',
            'mahasiswa_org_category_regional' => 'Ikatan Daerah',
            'mahasiswa_org_category_ministry' => 'Ministries',
            'mahasiswa_lf_category_wallet' => 'Dompet',
            'mahasiswa_lf_category_key' => 'Kunci',
            'mahasiswa_lf_category_card' => 'Kartu',
            'mahasiswa_lf_category_electronic' => 'Elektronik',
            'mahasiswa_lf_category_other' => 'Lainnya',
            'mahasiswa_lf_status_active' => 'Belum ditemukan',
            'mahasiswa_lf_status_completed' => 'Selesai',
            'mahasiswa_lf_status_pending' => 'Menunggu verifikasi',
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
        if (!Schema::hasTable('workflow_reference_values')) {
            return;
        }

        DB::table('workflow_reference_values')
            ->where('domain', 'ui_text')
            ->whereIn('code', [
                'mahasiswa_lost_found_table_missing',
                'mahasiswa_lost_found_schema_unsupported',
                'mahasiswa_lost_found_saved',
                'mahasiswa_category_all_label',
                'mahasiswa_notification_system_category',
                'mahasiswa_notification_announcement_default_title',
                'mahasiswa_notification_announcement_default_category',
                'mahasiswa_notification_event_default_title',
                'mahasiswa_notification_event_default_category',
                'mahasiswa_notification_lostfound_prefix',
                'mahasiswa_notification_lostfound_default_name',
                'mahasiswa_notification_lostfound_category',
                'mahasiswa_placeholder_dash',
                'mahasiswa_org_acronym_default',
                'mahasiswa_org_category_default',
                'mahasiswa_org_category_bem',
                'mahasiswa_org_category_choir',
                'mahasiswa_org_category_creative',
                'mahasiswa_org_category_regional',
                'mahasiswa_org_category_ministry',
                'mahasiswa_lf_category_wallet',
                'mahasiswa_lf_category_key',
                'mahasiswa_lf_category_card',
                'mahasiswa_lf_category_electronic',
                'mahasiswa_lf_category_other',
                'mahasiswa_lf_status_active',
                'mahasiswa_lf_status_completed',
                'mahasiswa_lf_status_pending',
            ])
            ->delete();
    }
};
