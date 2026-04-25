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

        $items = [
            'organisasi_detail' => [
                'back_to_list' => 'Kembali ke Daftar Organisasi',
            ],
            'pengumuman_detail' => [
                'back_to_list' => 'Kembali ke Daftar Pengumuman',
            ],
            'event_detail' => [
                'back_to_list' => 'Kembali ke Daftar Event',
                'organizer_prefix' => 'oleh',
                'date_label' => 'Tanggal',
                'time_label' => 'Waktu',
                'location_label' => 'Lokasi',
                'registration_status_label' => 'Status Pendaftaran',
                'description_title' => 'Deskripsi Event',
                'benefits_title' => 'Benefit dan Fasilitas',
                'speakers_title' => 'Pembicara atau Performer',
                'schedule_title' => 'Rundown Acara',
            ],
            'organisasi_daftar' => [
                'back_label' => 'Kembali',
                'title_prefix' => 'Pendaftaran',
                'status_title' => 'Status Pendaftaran',
                'status_open' => 'Sedang Dibuka',
                'status_closed' => 'Belum Dibuka',
                'period_label' => 'Periode pendaftaran:',
                'open_form_button' => 'Buka Form Pendaftaran',
                'open_on_label' => 'Pendaftaran akan dibuka pada:',
                'open_date_fallback' => 'Informasi menyusul',
                'divisions_title' => 'Divisi yang Tersedia',
                'divisions_empty' => 'Informasi divisi belum tersedia.',
                'guide_title' => 'Buku Panduan Organisasi',
                'guide_description' => 'Unduh buku panduan untuk mengetahui visi misi, jobdesk divisi, dan alur kerja organisasi.',
                'guide_download_button' => 'Unduh Buku Panduan',
            ],
            'organisasi_event_detail' => [
                'back_label' => 'Kembali',
                'about_title' => 'Tentang Event',
                'activities_title' => 'Rangkaian Kegiatan',
                'highlight_title' => 'Highlight dan Kesan Event',
                'gallery_title' => 'Dokumentasi Event',
                'gallery_alt_prefix' => 'Dokumentasi',
                'back_to_org_button' => 'Kembali ke Halaman Organisasi',
            ],
        ];

        foreach ($items as $code => $newLabels) {
            $row = DB::table('workflow_reference_values')
                ->select(['id', 'payload'])
                ->where('domain', 'mahasiswa_public_ui')
                ->where('code', $code)
                ->first();

            if (!$row) {
                continue;
            }

            $payload = [];
            if (is_string($row->payload) && trim($row->payload) !== '') {
                $decoded = json_decode($row->payload, true);
                $payload = is_array($decoded) ? $decoded : [];
            }

            foreach ($newLabels as $key => $value) {
                if (!array_key_exists($key, $payload) || $payload[$key] === null || $payload[$key] === '') {
                    $payload[$key] = $value;
                }
            }

            DB::table('workflow_reference_values')
                ->where('id', $row->id)
                ->update([
                    'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => $now,
                ]);
        }
    }

    public function down(): void
    {
        // Intentionally no-op: this migration only extends payload keys.
    }
};
