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
                DB::table('workflow_reference_values')->updateOrInsert(
                    [
                        'domain' => $domain,
                        'code' => (string) $code,
                    ],
                    [
                        'label' => $entry['label'] ?? null,
                        'payload' => json_encode($entry['payload'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'sort_order' => $entry['sort_order'] ?? $sortOrder,
                        'is_active' => array_key_exists('is_active', $entry) ? (bool) $entry['is_active'] : true,
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

        DB::table('workflow_reference_values')
            ->whereIn('domain', ['mahasiswa_public_ui', 'mahasiswa_org_registration'])
            ->delete();
    }

    private function referenceData(): array
    {
        return [
            'mahasiswa_public_ui' => [
                'organisasi' => [
                    'label' => 'Halaman Organisasi',
                    'payload' => [
                        'search_placeholder' => 'Cari organisasi...',
                        'empty_message' => 'Organisasi tidak ditemukan. Ubah kata kunci atau kategori.',
                        'count_suffix' => 'organisasi ditemukan',
                    ],
                ],
                'event' => [
                    'label' => 'Halaman Event',
                    'payload' => [
                        'title' => 'Event Kampus',
                        'subtitle' => 'Temukan dan ikuti berbagai event menarik di UNKLAB',
                        'search_placeholder' => 'Cari event...',
                        'count_suffix' => 'event ditemukan',
                        'empty_title' => 'Tidak ada event ditemukan',
                        'empty_message' => 'Coba ubah kata kunci pencarian atau filter kategori.',
                        'show_all_label' => 'Lihat Semua Event',
                        'detail_button_label' => 'Lihat Detail',
                    ],
                ],
                'pengumuman' => [
                    'label' => 'Halaman Pengumuman',
                    'payload' => [
                        'title' => 'Pengumuman',
                        'subtitle' => 'Informasi terbaru seputar kampus dan organisasi',
                        'search_placeholder' => 'Cari pengumuman...',
                        'priority_title' => 'Pengumuman Penting',
                        'all_title' => 'Semua Pengumuman',
                        'count_suffix' => 'pengumuman ditemukan',
                        'empty_message' => 'Pengumuman tidak ditemukan untuk filter saat ini.',
                    ],
                ],
                'lost_found' => [
                    'label' => 'Halaman Lost and Found',
                    'payload' => [
                        'urgent_title' => 'Barang Penting yang Hilang',
                        'urgent_subtitle' => 'Segera hubungi jika menemukan.',
                        'main_title' => 'Lost and Found',
                        'main_subtitle' => 'Laporkan barang hilang atau temuan',
                        'report_button' => 'Laporkan',
                        'search_placeholder' => 'Cari barang...',
                        'count_suffix' => 'barang ditemukan',
                        'empty_message' => 'Tidak ada data barang yang sesuai.',
                        'tab_lost' => 'Barang Hilang',
                        'tab_found' => 'Barang Ditemukan',
                        'reporter_prefix' => 'Pelapor',
                        'contact_button' => 'Hubungi Pelapor',
                        'modal_title' => 'Laporkan Barang',
                        'modal_submit' => 'Kirim Laporan',
                    ],
                ],
                'event_detail' => [
                    'label' => 'Halaman Detail Event',
                    'payload' => [
                        'register_button' => 'Daftar Sekarang',
                    ],
                ],
                'pengumuman_detail' => [
                    'label' => 'Halaman Detail Pengumuman',
                    'payload' => [
                        'share_button' => 'Bagikan',
                        'download_button' => 'Download PDF',
                        'contact_prompt' => 'Ada pertanyaan tentang pengumuman ini?',
                        'contact_button_prefix' => 'Hubungi',
                    ],
                ],
                'organisasi_detail' => [
                    'label' => 'Halaman Detail Organisasi',
                    'payload' => [
                        'contact_button' => 'Hubungi Organisasi',
                        'org_events_button' => 'Lihat Event Organisasi',
                        'register_button' => 'Daftar Organisasi',
                    ],
                ],
                'organisasi_daftar' => [
                    'label' => 'Halaman Daftar Organisasi',
                    'payload' => [
                        'subtitle' => 'Informasi pendaftaran anggota organisasi',
                    ],
                ],
                'organisasi_event_detail' => [
                    'label' => 'Halaman Detail Event Organisasi',
                    'payload' => [
                        'badge' => 'Event Organisasi',
                    ],
                ],
            ],
            'mahasiswa_org_registration' => [
                'default' => [
                    'label' => 'Konfigurasi default pendaftaran organisasi',
                    'payload' => [
                        'open' => false,
                        'period' => null,
                        'open_date' => null,
                        'form_link' => null,
                        'guidebook_url' => null,
                        'divisions' => [],
                    ],
                ],
            ],
        ];
    }
};
