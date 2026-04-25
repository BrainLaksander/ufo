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
            ->whereIn('domain', ['mahasiswa_home', 'mahasiswa_about'])
            ->delete();
    }

    private function referenceData(): array
    {
        return [
            'mahasiswa_home' => [
                'hero' => [
                    'label' => 'Hero Beranda Mahasiswa',
                    'payload' => [
                        'title' => 'Selamat Datang, Mahasiswa UNKLAB',
                        'subtitle' => 'Portal UFO membantu Anda menemukan organisasi, event, pengumuman, dan layanan Lost & Found dalam satu tempat.',
                        'actions' => [
                            [
                                'label' => 'Daftar Organisasi',
                                'url' => '/organisasi',
                                'icon' => 'bi bi-people-fill',
                                'style' => 'figma-btn-secondary',
                            ],
                            [
                                'label' => 'Lihat Pengumuman',
                                'url' => '/pengumuman',
                                'icon' => 'bi bi-megaphone-fill',
                                'style' => 'figma-btn-primary',
                            ],
                        ],
                    ],
                ],
                'quick_links' => [
                    'label' => 'Akses Cepat',
                    'payload' => [
                        'items' => [
                            ['label' => 'Organisasi', 'url' => '/organisasi'],
                            ['label' => 'Event Kampus', 'url' => '/event'],
                            ['label' => 'Pengumuman', 'url' => '/pengumuman'],
                            ['label' => 'Lost & Found', 'url' => '/lost-found'],
                            ['label' => 'Tentang UFO', 'url' => '/tentang'],
                            ['label' => 'Portal Internal', 'url' => '/login'],
                        ],
                    ],
                ],
                'services' => [
                    'label' => 'Layanan Utama',
                    'payload' => [
                        'items' => [
                            [
                                'title' => 'Organisasi',
                                'description' => 'Cari UKM atau komunitas kampus berdasarkan minat dan kategori.',
                                'icon' => 'bi bi-people',
                            ],
                            [
                                'title' => 'Informasi',
                                'description' => 'Akses pengumuman terbaru terkait akademik, event, dan kegiatan mahasiswa.',
                                'icon' => 'bi bi-megaphone',
                            ],
                            [
                                'title' => 'Lost & Found',
                                'description' => 'Bantu melaporkan dan menemukan barang hilang di area kampus.',
                                'icon' => 'bi bi-search',
                            ],
                        ],
                    ],
                ],
            ],
            'mahasiswa_about' => [
                'hero' => [
                    'label' => 'Hero Tentang UFO',
                    'payload' => [
                        'title' => 'Tentang UFO',
                        'subtitle' => 'Unklab Forum Organization adalah platform digital untuk mahasiswa Universitas Klabat.',
                    ],
                ],
                'intro' => [
                    'label' => 'Apa Itu UFO',
                    'payload' => [
                        'title' => 'Apa itu UFO?',
                        'icon' => 'bi bi-people',
                        'paragraphs' => [
                            'UFO dirancang untuk mempermudah akses informasi organisasi kampus, agenda event, pengumuman penting, serta layanan Lost and Found. Platform ini berfokus pada pengalaman mahasiswa yang cepat, jelas, dan mudah digunakan pada desktop maupun perangkat mobile.',
                            'Dengan UFO, mahasiswa dapat dengan mudah menjelajahi berbagai organisasi yang ada di kampus, mendaftar ke acara-acara menarik, melihat pengumuman terkini, dan melaporkan atau mencari barang yang hilang.',
                        ],
                    ],
                ],
                'vision' => [
                    'label' => 'Visi',
                    'payload' => [
                        'title' => 'Visi',
                        'icon' => 'bi bi-bullseye',
                        'description' => 'Menjadi platform digital terpadu yang memfasilitasi kehidupan kampus yang lebih terorganisir, informatif, dan kolaboratif untuk seluruh civitas akademika Universitas Klabat.',
                    ],
                ],
                'mission' => [
                    'label' => 'Misi',
                    'payload' => [
                        'title' => 'Misi',
                        'icon' => 'bi bi-award',
                        'items' => [
                            'Menyediakan informasi organisasi dan acara kampus yang akurat dan terkini.',
                            'Memfasilitasi komunikasi antara mahasiswa dan organisasi.',
                            'Membantu mahasiswa dalam menemukan barang hilang dengan cepat.',
                        ],
                    ],
                ],
                'features' => [
                    'label' => 'Fitur Utama UFO',
                    'payload' => [
                        'title' => 'Fitur Utama UFO',
                        'items' => [
                            [
                                'title' => 'Organisasi',
                                'description' => 'Jelajahi berbagai organisasi kampus dan temukan komunitas yang sesuai dengan minatmu.',
                                'icon' => 'bi bi-people',
                                'tone_class' => 'figma-about-feature-org',
                            ],
                            [
                                'title' => 'Event',
                                'description' => 'Lihat dan daftar ke berbagai acara menarik yang diselenggarakan di kampus.',
                                'icon' => 'bi bi-calendar-event',
                                'tone_class' => 'figma-about-feature-event',
                            ],
                            [
                                'title' => 'Lost & Found',
                                'description' => 'Laporkan barang hilang atau temukan pemilik barang yang kamu temukan.',
                                'icon' => 'bi bi-geo-alt',
                                'tone_class' => 'figma-about-feature-lf',
                            ],
                            [
                                'title' => 'Pengumuman',
                                'description' => 'Dapatkan informasi dan pengumuman penting dari kampus secara real-time.',
                                'icon' => 'bi bi-award',
                                'tone_class' => 'figma-about-feature-ann',
                            ],
                        ],
                    ],
                ],
                'contact' => [
                    'label' => 'Hubungi Kami',
                    'payload' => [
                        'title' => 'Hubungi Kami',
                        'items' => [
                            [
                                'title' => 'Email',
                                'value' => 'ufo@unklab.ac.id',
                                'icon' => 'bi bi-envelope',
                            ],
                            [
                                'title' => 'Telepon',
                                'value' => '+62 431 891 035',
                                'icon' => 'bi bi-telephone',
                            ],
                            [
                                'title' => 'Alamat',
                                'value' => 'Universitas Klabat, Airmadidi, Sulawesi Utara',
                                'icon' => 'bi bi-geo-alt',
                            ],
                        ],
                    ],
                ],
                'bot' => [
                    'label' => 'UFO Bot',
                    'payload' => [
                        'title' => 'Kenalan dengan UFO-Bot!',
                        'description' => 'Butuh bantuan? Klik tombol UFO-Bot di pojok kanan bawah untuk mendapatkan bantuan instan. UFO-Bot siap membantu menjawab pertanyaan seputar penggunaan platform UFO.',
                        'icon' => 'bi bi-send-fill',
                    ],
                ],
            ],
        ];
    }
};
