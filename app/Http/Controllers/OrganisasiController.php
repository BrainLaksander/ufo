<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class OrganisasiController extends Controller
{
    /**
     * Display organisasi listing
     */
    public function index(): View
    {
        $organisasi = [
            [
                'id' => 1,
                'nama' => 'HIMAKOM',
                'emoji' => '💻',
                'tagline' => 'Himpunan Mahasiswa Program Studi Teknik Informatika',
                'deskripsi' => 'Organisasi mahasiswa Informatika yang fokus pada pengembangan teknologi dan inovasi digital.',
                'kategori' => 'Akademik',
                'members' => 150,
                'visiMisi' => [
                    'visi' => 'Menjadi organisasi yang mengembangkan potensi mahasiswa Informatika di bidang teknologi dan inovasi.',
                    'misi' => [
                        'Mengadakan pelatihan dan workshop teknologi',
                        'Memfasilitasi diskusi dan sharing ilmu',
                        'Membangun portofolio mahasiswa'
                    ]
                ],
                'budaya' => 'Kolaboratif, Inovatif, Profesional',
                'programs' => [
                    ['nama' => 'Workshop Coding', 'deskripsi' => 'Pelatihan coding untuk berbagai bahasa pemrograman'],
                    ['nama' => 'Hackathon', 'deskripsi' => 'Kompetisi inovasi teknologi'],
                    ['nama' => 'Tech Talk', 'deskripsi' => 'Sharing ilmu dengan praktisi industri']
                ],
                'events' => [
                    ['nama' => 'Hackathon 2025', 'date' => '2025-02-15', 'deskripsi' => 'Kompetisi coding tahunan'],
                    ['nama' => 'Tech Workshop', 'date' => '2025-01-20', 'deskripsi' => 'Workshop React dan Laravel']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua Umum', 'nama' => 'Ahmad Pratama'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Siti Nurhaliza'],
                    ['posisi' => 'Sekretaris', 'nama' => 'Budi Santoso'],
                    ['posisi' => 'Bendahara', 'nama' => 'Rini Suryani']
                ],
                'contact' => 'himakom@unklab.ac.id',
                'phone' => '0821-1111-1111',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
            ],
            [
                'id' => 2,
                'nama' => 'BEM UNKLAB',
                'emoji' => '🎯',
                'tagline' => 'Badan Eksekutif Mahasiswa UNKLAB',
                'deskripsi' => 'Organisasi pusat mahasiswa UNKLAB yang mengurus kesejahteraan dan kegiatan bersama semua mahasiswa.',
                'kategori' => 'Akademik',
                'members' => 300,
                'visiMisi' => [
                    'visi' => 'BEM yang transparan, akuntabel, dan responsif terhadap kebutuhan mahasiswa.',
                    'misi' => [
                        'Melayani kebutuhan mahasiswa',
                        'Mengorganisir kegiatan kampus',
                        'Membangun solidaritas mahasiswa'
                    ]
                ],
                'budaya' => 'Transparan, Akuntabel, Ikhlas',
                'programs' => [
                    ['nama' => 'Bantuan Mahasiswa Berprestasi', 'deskripsi' => 'Program beasiswa untuk mahasiswa berprestasi'],
                    ['nama' => 'Konseling Mahasiswa', 'deskripsi' => 'Layanan konseling untuk kesejahteraan mahasiswa']
                ],
                'events' => [
                    ['nama' => 'Konser Musik BEM 2025', 'date' => '2025-01-25', 'deskripsi' => 'Konser musik dengan artis terkenal'],
                    ['nama' => 'Gathering Mahasiswa', 'date' => '2025-02-10', 'deskripsi' => 'Acara kebersamaan semua mahasiswa']
                ],
                'struktur' => [
                    ['posisi' => 'Presiden', 'nama' => 'Andi Wijaya'],
                    ['posisi' => 'Wakil Presiden', 'nama' => 'Eka Putri'],
                    ['posisi' => 'Sekretaris Jenderal', 'nama' => 'Hendra Gunawan'],
                    ['posisi' => 'Bendahara Umum', 'nama' => 'Lita Handoko']
                ],
                'contact' => 'bem@unklab.ac.id',
                'phone' => '0821-2222-2222',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'
            ],
            [
                'id' => 3,
                'nama' => 'Sinematografi UNKLAB',
                'emoji' => '🎬',
                'tagline' => 'Klub Sinematografi dan Produksi Konten UNKLAB',
                'deskripsi' => 'Organisasi yang fokus pada seni sinematografi, fotografi, dan produksi konten kreatif.',
                'kategori' => 'Seni & Olahraga',
                'members' => 80,
                'visiMisi' => [
                    'visi' => 'Mengembangkan talenta sinematografi dan produksi konten berkualitas.',
                    'misi' => [
                        'Memberikan pelatihan sinematografi',
                        'Memproduksi konten berkualitas',
                        'Memamerkan karya siswa'
                    ]
                ],
                'budaya' => 'Kreatif, Inovatif, Kolaboratif',
                'programs' => [
                    ['nama' => 'Workshop Cinematography', 'deskripsi' => 'Pelatihan teknik sinematografi profesional'],
                    ['nama' => 'Film Production', 'deskripsi' => 'Produksi film pendek berkualitas']
                ],
                'events' => [
                    ['nama' => 'UNKLAB Film Festival', 'date' => '2025-03-15', 'deskripsi' => 'Festival film mahasiswa UNKLAB'],
                    ['nama' => 'Fotografi Eksibisi', 'date' => '2025-02-28', 'deskripsi' => 'Pameran fotografi terbaik']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua', 'nama' => 'Rizki Pratama'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Diana Saputri'],
                    ['posisi' => 'Sekretaris', 'nama' => 'Fani Kusuma']
                ],
                'contact' => 'sinematografi@unklab.ac.id',
                'phone' => '0821-3333-3333',
                'registrationOpen' => false,
                'banner_gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
            ],
            [
                'id' => 4,
                'nama' => 'FUTSAL UNKLAB',
                'emoji' => '⚽',
                'tagline' => 'Klub Olahraga Futsal UNKLAB',
                'deskripsi' => 'Organisasi olahraga yang mengembangkan kemampuan futsal dan membangun persahabatan antar mahasiswa.',
                'kategori' => 'Seni & Olahraga',
                'members' => 120,
                'visiMisi' => [
                    'visi' => 'Menjadi klub futsal yang kompetitif dan membangun solidaritas mahasiswa.',
                    'misi' => [
                        'Mengembangkan kemampuan futsal',
                        'Mengikuti turnamen tingkat universitas',
                        'Membina karakter melalui olahraga'
                    ]
                ],
                'budaya' => 'Sportif, Disiplin, Solidaritas',
                'programs' => [
                    ['nama' => 'Latihan Rutin', 'deskripsi' => 'Latihan futsal setiap minggu'],
                    ['nama' => 'Turnamen Internal', 'deskripsi' => 'Kompetisi antar tim internal']
                ],
                'events' => [
                    ['nama' => 'Turnamen Futsal UNKLAB 2025', 'date' => '2025-03-01', 'deskripsi' => 'Turnamen futsal tahunan'],
                    ['nama' => 'Pertandingan Persahabatan', 'date' => '2025-02-22', 'deskripsi' => 'Match dengan universitas lain']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua', 'nama' => 'Irfan Habibie'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Galih Pratama'],
                    ['posisi' => 'Pelatih', 'nama' => 'Bambang Suryanto']
                ],
                'contact' => 'futsal@unklab.ac.id',
                'phone' => '0821-4444-4444',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'
            ],
            [
                'id' => 5,
                'nama' => 'ROHIS UNKLAB',
                'emoji' => '☪️',
                'tagline' => 'Rohani Islam Mahasiswa UNKLAB',
                'deskripsi' => 'Organisasi kerohanian Islam yang mengembangkan nilai-nilai spiritual dan moral mahasiswa.',
                'kategori' => 'Kerohanian',
                'members' => 200,
                'visiMisi' => [
                    'visi' => 'Menjadi organisasi yang mengembangkan keimanan dan akhlak mulia mahasiswa.',
                    'misi' => [
                        'Mengadakan kajian keislaman',
                        'Membantu mahasiswa memahami ajaran Islam',
                        'Berbagi ilmu dengan komunitas'
                    ]
                ],
                'budaya' => 'Suportif, Islami, Inklusif',
                'programs' => [
                    ['nama' => 'Kajian Rutin', 'deskripsi' => 'Kajian agama Islam setiap minggu'],
                    ['nama' => 'Program Zakat', 'deskripsi' => 'Program bantuan untuk yang membutuhkan']
                ],
                'events' => [
                    ['nama' => 'Isra Mi\'raj 2025', 'date' => '2025-02-01', 'deskripsi' => 'Perayaan Isra dan Mi\'raj'],
                    ['nama' => 'Kajian Ramadan', 'date' => '2025-03-15', 'deskripsi' => 'Program khusus bulan Ramadan']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua', 'nama' => 'Muhammad Arief'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Aisha Putri'],
                    ['posisi' => 'Sekretaris', 'nama' => 'Ibrahim Hasan']
                ],
                'contact' => 'rohis@unklab.ac.id',
                'phone' => '0821-5555-5555',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
            ],
            [
                'id' => 6,
                'nama' => 'English Club UNKLAB',
                'emoji' => '🌍',
                'tagline' => 'Klub Bahasa Inggris UNKLAB',
                'deskripsi' => 'Organisasi yang mengembangkan kemampuan bahasa Inggris dan membangun jejaring internasional.',
                'kategori' => 'Akademik',
                'members' => 100,
                'visiMisi' => [
                    'visi' => 'Menjadi komunitas yang meningkatkan kemampuan bahasa Inggris mahasiswa.',
                    'misi' => [
                        'Menyelenggarakan workshop bahasa Inggris',
                        'Memfasilitasi diskusi dalam bahasa Inggris',
                        'Membangun koneksi dengan komunitas internasional'
                    ]
                ],
                'budaya' => 'Global, Inklusif, Supportif',
                'programs' => [
                    ['nama' => 'English Conversation', 'deskripsi' => 'Latihan percakapan bahasa Inggris'],
                    ['nama' => 'TOEFL Preparation', 'deskripsi' => 'Persiapan tes TOEFL']
                ],
                'events' => [
                    ['nama' => 'English Speech Contest', 'date' => '2025-04-10', 'deskripsi' => 'Lomba pidato bahasa Inggris'],
                    ['nama' => 'Movie Night', 'date' => '2025-02-14', 'deskripsi' => 'Nonton film berbahasa Inggris']
                ],
                'struktur' => [
                    ['posisi' => 'Coordinator', 'nama' => 'Sarah Johnson'],
                    ['posisi' => 'Vice Coordinator', 'nama' => 'Budi Santoso']
                ],
                'contact' => 'englishclub@unklab.ac.id',
                'phone' => '0821-6666-6666',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%)'
            ]
        ];

        return view('mahasiswa.organisasi', [
            'organisasi' => $organisasi
        ]);
    }

    /**
     * Show organisasi detail
     */
    public function show($id): View
    {
        $organisasi = [
            [
                'id' => 1,
                'nama' => 'HIMAKOM',
                'emoji' => '💻',
                'tagline' => 'Himpunan Mahasiswa Program Studi Teknik Informatika',
                'deskripsi' => 'Organisasi mahasiswa Informatika yang fokus pada pengembangan teknologi dan inovasi digital.',
                'kategori' => 'Akademik',
                'members' => 150,
                'visiMisi' => [
                    'visi' => 'Menjadi organisasi yang mengembangkan potensi mahasiswa Informatika di bidang teknologi dan inovasi.',
                    'misi' => [
                        'Mengadakan pelatihan dan workshop teknologi',
                        'Memfasilitasi diskusi dan sharing ilmu',
                        'Membangun portofolio mahasiswa'
                    ]
                ],
                'budaya' => 'Kolaboratif, Inovatif, Profesional',
                'programs' => [
                    ['nama' => 'Workshop Coding', 'deskripsi' => 'Pelatihan coding untuk berbagai bahasa pemrograman'],
                    ['nama' => 'Hackathon', 'deskripsi' => 'Kompetisi inovasi teknologi'],
                    ['nama' => 'Tech Talk', 'deskripsi' => 'Sharing ilmu dengan praktisi industri']
                ],
                'events' => [
                    ['nama' => 'Hackathon 2025', 'date' => '2025-02-15', 'deskripsi' => 'Kompetisi coding tahunan'],
                    ['nama' => 'Tech Workshop', 'date' => '2025-01-20', 'deskripsi' => 'Workshop React dan Laravel']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua Umum', 'nama' => 'Ahmad Pratama'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Siti Nurhaliza'],
                    ['posisi' => 'Sekretaris', 'nama' => 'Budi Santoso'],
                    ['posisi' => 'Bendahara', 'nama' => 'Rini Suryani']
                ],
                'contact' => 'himakom@unklab.ac.id',
                'phone' => '0821-1111-1111',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
            ],
            [
                'id' => 2,
                'nama' => 'BEM UNKLAB',
                'emoji' => '🎯',
                'tagline' => 'Badan Eksekutif Mahasiswa UNKLAB',
                'deskripsi' => 'Organisasi pusat mahasiswa UNKLAB yang mengurus kesejahteraan dan kegiatan bersama semua mahasiswa.',
                'kategori' => 'Akademik',
                'members' => 300,
                'visiMisi' => [
                    'visi' => 'BEM yang transparan, akuntabel, dan responsif terhadap kebutuhan mahasiswa.',
                    'misi' => [
                        'Melayani kebutuhan mahasiswa',
                        'Mengorganisir kegiatan kampus',
                        'Membangun solidaritas mahasiswa'
                    ]
                ],
                'budaya' => 'Transparan, Akuntabel, Ikhlas',
                'programs' => [
                    ['nama' => 'Bantuan Mahasiswa Berprestasi', 'deskripsi' => 'Program beasiswa untuk mahasiswa berprestasi'],
                    ['nama' => 'Konseling Mahasiswa', 'deskripsi' => 'Layanan konseling untuk kesejahteraan mahasiswa']
                ],
                'events' => [
                    ['nama' => 'Konser Musik BEM 2025', 'date' => '2025-01-25', 'deskripsi' => 'Konser musik dengan artis terkenal'],
                    ['nama' => 'Gathering Mahasiswa', 'date' => '2025-02-10', 'deskripsi' => 'Acara kebersamaan semua mahasiswa']
                ],
                'struktur' => [
                    ['posisi' => 'Presiden', 'nama' => 'Andi Wijaya'],
                    ['posisi' => 'Wakil Presiden', 'nama' => 'Eka Putri'],
                    ['posisi' => 'Sekretaris Jenderal', 'nama' => 'Hendra Gunawan'],
                    ['posisi' => 'Bendahara Umum', 'nama' => 'Lita Handoko']
                ],
                'contact' => 'bem@unklab.ac.id',
                'phone' => '0821-2222-2222',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'
            ],
            [
                'id' => 3,
                'nama' => 'Sinematografi UNKLAB',
                'emoji' => '🎬',
                'tagline' => 'Klub Sinematografi dan Produksi Konten UNKLAB',
                'deskripsi' => 'Organisasi yang fokus pada seni sinematografi, fotografi, dan produksi konten kreatif.',
                'kategori' => 'Seni & Olahraga',
                'members' => 80,
                'visiMisi' => [
                    'visi' => 'Mengembangkan talenta sinematografi dan produksi konten berkualitas.',
                    'misi' => [
                        'Memberikan pelatihan sinematografi',
                        'Memproduksi konten berkualitas',
                        'Memamerkan karya siswa'
                    ]
                ],
                'budaya' => 'Kreatif, Inovatif, Kolaboratif',
                'programs' => [
                    ['nama' => 'Workshop Cinematography', 'deskripsi' => 'Pelatihan teknik sinematografi profesional'],
                    ['nama' => 'Film Production', 'deskripsi' => 'Produksi film pendek berkualitas']
                ],
                'events' => [
                    ['nama' => 'UNKLAB Film Festival', 'date' => '2025-03-15', 'deskripsi' => 'Festival film mahasiswa UNKLAB'],
                    ['nama' => 'Fotografi Eksibisi', 'date' => '2025-02-28', 'deskripsi' => 'Pameran fotografi terbaik']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua', 'nama' => 'Rizki Pratama'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Diana Saputri'],
                    ['posisi' => 'Sekretaris', 'nama' => 'Fani Kusuma']
                ],
                'contact' => 'sinematografi@unklab.ac.id',
                'phone' => '0821-3333-3333',
                'registrationOpen' => false,
                'banner_gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
            ],
            [
                'id' => 4,
                'nama' => 'FUTSAL UNKLAB',
                'emoji' => '⚽',
                'tagline' => 'Klub Olahraga Futsal UNKLAB',
                'deskripsi' => 'Organisasi olahraga yang mengembangkan kemampuan futsal dan membangun persahabatan antar mahasiswa.',
                'kategori' => 'Seni & Olahraga',
                'members' => 120,
                'visiMisi' => [
                    'visi' => 'Menjadi klub futsal yang kompetitif dan membangun solidaritas mahasiswa.',
                    'misi' => [
                        'Mengembangkan kemampuan futsal',
                        'Mengikuti turnamen tingkat universitas',
                        'Membina karakter melalui olahraga'
                    ]
                ],
                'budaya' => 'Sportif, Disiplin, Solidaritas',
                'programs' => [
                    ['nama' => 'Latihan Rutin', 'deskripsi' => 'Latihan futsal setiap minggu'],
                    ['nama' => 'Turnamen Internal', 'deskripsi' => 'Kompetisi antar tim internal']
                ],
                'events' => [
                    ['nama' => 'Turnamen Futsal UNKLAB 2025', 'date' => '2025-03-01', 'deskripsi' => 'Turnamen futsal tahunan'],
                    ['nama' => 'Pertandingan Persahabatan', 'date' => '2025-02-22', 'deskripsi' => 'Match dengan universitas lain']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua', 'nama' => 'Irfan Habibie'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Galih Pratama'],
                    ['posisi' => 'Pelatih', 'nama' => 'Bambang Suryanto']
                ],
                'contact' => 'futsal@unklab.ac.id',
                'phone' => '0821-4444-4444',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'
            ],
            [
                'id' => 5,
                'nama' => 'ROHIS UNKLAB',
                'emoji' => '☪️',
                'tagline' => 'Rohani Islam Mahasiswa UNKLAB',
                'deskripsi' => 'Organisasi kerohanian Islam yang mengembangkan nilai-nilai spiritual dan moral mahasiswa.',
                'kategori' => 'Kerohanian',
                'members' => 200,
                'visiMisi' => [
                    'visi' => 'Menjadi organisasi yang mengembangkan keimanan dan akhlak mulia mahasiswa.',
                    'misi' => [
                        'Mengadakan kajian keislaman',
                        'Membantu mahasiswa memahami ajaran Islam',
                        'Berbagi ilmu dengan komunitas'
                    ]
                ],
                'budaya' => 'Suportif, Islami, Inklusif',
                'programs' => [
                    ['nama' => 'Kajian Rutin', 'deskripsi' => 'Kajian agama Islam setiap minggu'],
                    ['nama' => 'Program Zakat', 'deskripsi' => 'Program bantuan untuk yang membutuhkan']
                ],
                'events' => [
                    ['nama' => 'Isra Mi\'raj 2025', 'date' => '2025-02-01', 'deskripsi' => 'Perayaan Isra dan Mi\'raj'],
                    ['nama' => 'Kajian Ramadan', 'date' => '2025-03-15', 'deskripsi' => 'Program khusus bulan Ramadan']
                ],
                'struktur' => [
                    ['posisi' => 'Ketua', 'nama' => 'Muhammad Arief'],
                    ['posisi' => 'Wakil Ketua', 'nama' => 'Aisha Putri'],
                    ['posisi' => 'Sekretaris', 'nama' => 'Ibrahim Hasan']
                ],
                'contact' => 'rohis@unklab.ac.id',
                'phone' => '0821-5555-5555',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
            ],
            [
                'id' => 6,
                'nama' => 'English Club UNKLAB',
                'emoji' => '🌍',
                'tagline' => 'Klub Bahasa Inggris UNKLAB',
                'deskripsi' => 'Organisasi yang mengembangkan kemampuan bahasa Inggris dan membangun jejaring internasional.',
                'kategori' => 'Akademik',
                'members' => 100,
                'visiMisi' => [
                    'visi' => 'Menjadi komunitas yang meningkatkan kemampuan bahasa Inggris mahasiswa.',
                    'misi' => [
                        'Menyelenggarakan workshop bahasa Inggris',
                        'Memfasilitasi diskusi dalam bahasa Inggris',
                        'Membangun koneksi dengan komunitas internasional'
                    ]
                ],
                'budaya' => 'Global, Inklusif, Supportif',
                'programs' => [
                    ['nama' => 'English Conversation', 'deskripsi' => 'Latihan percakapan bahasa Inggris'],
                    ['nama' => 'TOEFL Preparation', 'deskripsi' => 'Persiapan tes TOEFL']
                ],
                'events' => [
                    ['nama' => 'English Speech Contest', 'date' => '2025-04-10', 'deskripsi' => 'Lomba pidato bahasa Inggris'],
                    ['nama' => 'Movie Night', 'date' => '2025-02-14', 'deskripsi' => 'Nonton film berbahasa Inggris']
                ],
                'struktur' => [
                    ['posisi' => 'Coordinator', 'nama' => 'Sarah Johnson'],
                    ['posisi' => 'Vice Coordinator', 'nama' => 'Budi Santoso']
                ],
                'contact' => 'englishclub@unklab.ac.id',
                'phone' => '0821-6666-6666',
                'registrationOpen' => true,
                'banner_gradient' => 'linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%)'
            ]
        ];

        $org = collect($organisasi)->firstWhere('id', (int) $id);

        if (!$org) {
            abort(404);
        }

        return view('mahasiswa.organisasi-detail', [
            'org' => $org
        ]);
    }
}
