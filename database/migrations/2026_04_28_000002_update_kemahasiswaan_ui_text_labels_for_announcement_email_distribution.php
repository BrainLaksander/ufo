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

        $domain = 'ui_text';
        $records = [
            'kmh_announcement_modal_title' => 'Buat Pengumuman Baru',
            'kmh_announcement_modal_subtitle' => 'Lengkapi detail pengumuman dan pilih aksi publikasi.',
            'kmh_announcement_field_target' => 'Target Distribusi Email',
            'kmh_announcement_field_target_placeholder' => 'Pilih target pengiriman',
            'kmh_announcement_field_manual_recipients' => 'Email Manual',
            'kmh_announcement_field_manual_recipients_placeholder' => 'Pisahkan email dengan koma atau baris baru',
            'kmh_announcement_field_content' => 'Konten Pengumuman',
            'kmh_announcement_field_content_placeholder' => 'Tulis konten pengumuman di sini...',
            'kmh_announcement_field_publish_datetime' => 'Jadwal Publish (Opsional)',
            'kmh_announcement_field_publish_datetime_placeholder' => 'dd/mm/yyyy, --:--',
            'kmh_announcement_save_draft_button' => 'Simpan sebagai Draft',
            'kmh_announcement_publish_now_button' => 'Publikasikan Sekarang',
            'kmh_common_cancel_button' => 'Batal',
            'kmh_announcement_distribution_info_body' => 'Pengumuman untuk semua mahasiswa akan dikirim ke student252@student.unklab.ac.id. Target manual harus diisi alamat email secara langsung. Jika jadwal publish diisi, pengiriman diproses otomatis oleh cron setiap menit.',
            'kmh_announcement_queue_title' => 'Antrian Email Terjadwal',
            'kmh_announcement_queue_count_suffix' => 'item menunggu kirim',
            'kmh_announcement_queue_empty' => 'Tidak ada antrian email terjadwal.',
            'kmh_announcement_sent_title' => 'Email Terkirim',
            'kmh_announcement_sent_count_suffix' => 'item sudah terkirim',
            'kmh_announcement_sent_empty' => 'Belum ada email yang terkirim.',
            'kmh_announcement_default_student_recipient_label' => 'Default semua mahasiswa: student252@student.unklab.ac.id',
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
                'kmh_announcement_modal_title',
                'kmh_announcement_modal_subtitle',
                'kmh_announcement_field_target',
                'kmh_announcement_field_target_placeholder',
                'kmh_announcement_field_manual_recipients',
                'kmh_announcement_field_manual_recipients_placeholder',
                'kmh_announcement_field_content',
                'kmh_announcement_field_content_placeholder',
                'kmh_announcement_field_publish_datetime',
                'kmh_announcement_field_publish_datetime_placeholder',
                'kmh_announcement_save_draft_button',
                'kmh_announcement_publish_now_button',
                'kmh_common_cancel_button',
                'kmh_announcement_distribution_info_body',
                'kmh_announcement_queue_title',
                'kmh_announcement_queue_count_suffix',
                'kmh_announcement_queue_empty',
                'kmh_announcement_sent_title',
                'kmh_announcement_sent_count_suffix',
                'kmh_announcement_sent_empty',
                'kmh_announcement_default_student_recipient_label',
            ])
            ->delete();
    }
};
