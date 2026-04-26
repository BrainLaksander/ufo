<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organisasiList = [
            [
                'id' => 1,
                'nama' => "Event Organizer Club UNKLAB (EOC)",
                'singkatan' => "EOC",
                'deskripsi' => "Organisasi event organizer kampus yang bertanggung jawab dalam penyelenggaraan acara-acara di lingkungan Universitas Klabat. EOC menjadi wadah pengembangan potensi mahasiswa di bidang manajemen acara, kepemimpinan, komunikasi, dan kerja sama tim, dengan berlandaskan nilai-nilai Kristiani.",
                'visi' => "Menjadi komunitas event organizer kampus yang kreatif, profesional, berdampak positif, dan berlandaskan nilai-nilai Kristiani, serta menjadi wadah pengembangan potensi mahasiswa Universitas Klabat melalui penyelenggaraan acara yang menginspirasi, membangun, dan memuliakan Tuhan.",
                'misi' => [
                    "Menciptakan event yang kreatif and inovatif sesuai kebutuhan dan minat civitas akademika UNKLAB.",
                    "Menjalankan acara secara profesional dengan perencanaan matang, koordinasi yang solid, dan eksekusi yang rapi.",
                    "Menjadikan setiap kegiatan sebagai sarana kesaksian dan kemuliaan bagi Tuhan.",
                    "Memberikan manfaat nyata bagi mahasiswa, kampus, dan masyarakat melalui kegiatan yang edukatif dan menghibur.",
                    "Mengembangkan keterampilan anggota dalam manajemen acara, kepemimpinan, komunikasi, dan kerja sama tim.",
                    "Menjadi representasi positif kampus sambil memegang teguh nilai rohani.",
                ],
                'sosmed' => [
                    'instagram' => "@officialeocunklab",
                    'tiktok' => "@eoc.unklab",
                ],
                'logo' => "https://drive.google.com/open?id=133msLad7zUfxB2u-8PcZruvtFuypG-iY",
            ],
            [
                'id' => 2,
                'nama' => "The Pilgrims Group",
                'singkatan' => "Pilgrims",
                'deskripsi' => "Kelompok penginjilan mahasiswa Universitas Klabat yang berfokus pada pemberitaan Injil, pembinaan rohani, dan pelayanan kepada masyarakat. Pilgrims aktif melaksanakan pelayanan jemaat, program ibadah, dan kegiatan sosial berlandaskan nilai Kristiani.",
                'visi' => "Menjadi kelompok yang bersemangat dan berdedikasi untuk memberitakan Injil, membawa terang Kristus ke setiap sudut kehidupan, serta membimbing jiwa-jiwa untuk mengalami kasih, anugerah, dan keselamatan yang sejati dalam Yesus Kristus.",
                'misi' => [
                    "Mewujudkan Amanat Agung: memberitakan Injil kepada semua orang secara aktif dan kreatif.",
                    "Membentuk Murid Kristus: memberikan pengajaran Firman Tuhan yang mendalam.",
                    "Melayani dengan Kasih: menjangkau masyarakat yang membutuhkan.",
                    "Membangun Persatuan Tubuh Kristus: mengembangkan persekutuan yang kuat.",
                    "Hidup dalam Kekudusan: menjaga integritas dan kekudusan hidup.",
                ],
                'sosmed' => [
                    'instagram' => "@the_pilgrimsgroupnew",
                ],
                'logo' => "https://drive.google.com/open?id=1TU2xKg4pNdjIfSOpueuV11Otsn6tMhjQ",
            ],
            [
                'id' => 3,
                'nama' => "UNKLAB Virtue in Computer Science",
                'singkatan' => "UVICS",
                'deskripsi' => "Organisasi mahasiswa Fakultas Ilmu Komputer UNKLAB yang berfokus pada pengembangan hard skill dan soft skill di bidang teknologi informasi, serta mendorong mahasiswa untuk berprestasi dalam berbagai kompetisi. UVICS menjadi wadah belajar bersama, membangun portofolio, dan melatih kolaborasi antar mahasiswa.",
                'visi' => "Menjadi wadah untuk mahasiswa yang ingin bertumbuh dan berprestasi dalam kompetisi; membawa nama baik Universitas Klabat; serta tempat mengasah hard skill dan soft skill untuk meningkatkan pengalaman dan portofolio.",
                'misi' => [
                    "Mengumpulkan mahasiswa dari berbagai tingkat dan jurusan untuk sama-sama bertumbuh dan berprestasi.",
                    "Memberikan sistem dan kesempatan yang sama untuk melatih kerja sama, komunikasi, dan leadership.",
                    "Menyediakan media belajar bersama untuk meningkatkan hard skill dan soft skill anggota.",
                ],
                'sosmed' => [
                    'instagram' => "@uvics_id",
                ],
                'logo' => "https://drive.google.com/open?id=1TVah7t4zi-VGRo9pV5ORmdMsVJBiAER9",
            ],
            [
                'id' => 4,
                'nama' => "The UNKLAB Echo Choir",
                'singkatan' => "TUEC",
                'deskripsi' => "Paduan suara resmi Universitas Klabat yang menjadi saluran bakat mahasiswa di bidang musik dan vokal. TUEC aktif melayani melalui pujian di gereja-gereja, konser rohani, dan tur pelayanan ke berbagai daerah, dengan semboyan 'To Serve God With Our Hearts and Minds'.",
                'visi' => "Menjadi saluran bakat/talenta bagi mahasiswa UNKLAB di bidang musik dan lagu; menjadi duta untuk mengharumkan nama Civitas Akademika UNKLAB; wadah sosialisasi mempererat persaudaraan; serta menjadi alat Tuhan dalam pelayanan penginjilan.",
                'misi' => [
                    "Melayani lewat pujian sebagai bentuk ibadah.",
                    "Disiplin dalam latihan dan tampil dengan kualitas terbaik.",
                    "Membangun kekeluargaan yang saling mendukung dan kompak.",
                    "Menjaga sikap, moral, dan akademik sebagai teladan.",
                    "Menjaga keseimbangan antara latihan, pelayanan, dan tanggung jawab kuliah.",
                ],
                'sosmed' => [
                    'instagram' => "@echo_choir",
                ],
                'logo' => "https://drive.google.com/open?id=10zP1j7jG0lzry0NF6xhY9uypbx28ZD14",
            ],
            [
                'id' => 5,
                'nama' => "BEM Fakultas Keperawatan",
                'singkatan' => "BEM FKEP",
                'deskripsi' => "Badan Eksekutif Mahasiswa Fakultas Keperawatan Universitas Klabat yang menjadi wadah aspirasi, kreativitas, dan pengembangan mahasiswa keperawatan. BEM FKEP mengintegrasikan nilai Kristiani dalam kepemimpinan dan pelayanan untuk membentuk perawat masa depan yang berkarakter.",
                'visi' => "Menjadi organisasi mahasiswa Fakultas Keperawatan yang inovatif, berdaya cipta, dan berkarakter empati, guna mewujudkan komunitas akademik yang peduli, tangguh, serta berdampak positif bagi kampus, profesi keperawatan, dan masyarakat luas.",
                'misi' => [
                    "Aspiratif: menjadi wadah penyampaian aspirasi mahasiswa keperawatan secara terbuka dan solutif.",
                    "Kreatif: menghadirkan program kerja yang variatif dan inovatif.",
                    "Tangguh: mengintegrasikan nilai Kristiani dalam pelayanan dan kepemimpinan.",
                    "Inovatif: menyempurnakan program BEM dengan ide-ide baru yang relevan.",
                    "Futuristik: mendorong mahasiswa aktif dalam kegiatan sosial, penelitian, dan pengabdian masyarakat.",
                ],
                'sosmed' => [
                    'instagram' => "@bemfkepunklab",
                ],
                'logo' => "https://drive.google.com/open?id=1Oyf9k7kJdk0w2RTXpvtaHrNo_Y0ipHkt",
            ],
            [
                'id' => 6,
                'nama' => "Ikatan Mahasiswa Papua",
                'singkatan' => "IKMAPAP",
                'deskripsi' => "Organisasi ikatan daerah yang menghimpun mahasiswa Papua di Universitas Klabat. IKMAPAP menjunjung tinggi rasa kebersamaan dan kekeluargaan, menciptakan komunitas yang saling mendukung di perantauan melalui kegiatan kerohanian, sosial, pendidikan, seni budaya, dan olahraga.",
                'visi' => "Membantu mahasiswa bertumbuh dalam aspek sosial, akademik, dan spiritual. Mahasiswa Papua menjunjung tinggi rasa kebersamaan dan ikatan kekeluargaan, menciptakan komunitas yang saling mendukung di perantauan.",
                'misi' => [],
                'sosmed' => [
                    'instagram' => "@ikmapaunklab",
                ],
                'logo' => "https://drive.google.com/open?id=1Zks_AVu3BdE-465FGLjskcJFsR_ZpuJ2",
            ],
            [
                'id' => 7,
                'nama' => "Teachers of Tomorrow Choir",
                'singkatan' => "TOT Choir",
                'deskripsi' => "Paduan suara mahasiswa yang berada di bawah naungan Fakultas Pendidikan Universitas Klabat. TOT Choir menjadi wadah pelayanan melalui musik vokal, mempererat kekeluargaan antar anggota, serta mengembangkan kemampuan bernyanyi mahasiswa dalam bidang pelayanan rohani dan pertunjukan.",
                'visi' => "Menjaga, membina, dan mempererat hubungan kekeluargaan serta kerja sama antar anggota aktif TOT Choir; menumbuhkan rasa cinta terhadap TOT Choir sebagai wadah pelayanan dan pengembangan bakat; mengembangkan potensi dalam musik dan kerohanian; mengoptimalkan kemampuan anggota sebagai sarana pelayanan.",
                'misi' => [
                    "Menyelenggarakan kegiatan yang membangun kebersamaan, disiplin, dan rasa memiliki.",
                    "Mengembangkan kemampuan vokal, musik, dan pelayanan melalui latihan rutin.",
                    "Membangun komunikasi, solidaritas, dan kerja sama antar anggota.",
                    "Mengadakan kegiatan pelayanan melalui musik yang berdampak bagi gereja dan masyarakat.",
                ],
                'sosmed' => [
                    'instagram' => "@tot_choirunklab",
                ],
                'logo' => "https://drive.google.com/open?id=1VrNrRGnbNCGfmHDDPlAbyAViEF1YYj7m",
            ],
            [
                'id' => 8,
                'nama' => "BEM Fakultas Pertanian",
                'singkatan' => "BEM Faperta",
                'deskripsi' => "Badan Eksekutif Mahasiswa Fakultas Pertanian (Agroteknologi) Universitas Klabat yang menjadi wadah kegiatan kemahasiswaan di bidang pertanian. BEM Faperta mendukung pengembangan akademik, penelitian, pengabdian masyarakat, dan kewirausahaan mahasiswa agroteknologi.",
                'visi' => "Menghasilkan lulusan yang berdaya saing nasional maupun internasional di bidang agronomi, yang berlandaskan pada pengembangan seimbang secara intelektual, spiritual, fisik, dan sosial.",
                'misi' => [
                    "Pendidikan Berkualitas: menyelenggarakan pendidikan agroteknologi untuk menghasilkan SDM yang kompetitif, komunikatif, dan berkarakter.",
                    "Penelitian & Pengembangan: melaksanakan penelitian pertanian untuk mengembangkan ilmu pengetahuan dan teknologi.",
                    "Pengabdian Masyarakat: menerapkan hasil-hasil penelitian pertanian kepada masyarakat.",
                ],
                'sosmed' => [
                    'instagram' => "@fapertaunklabofficial",
                ],
                'logo' => "https://drive.google.com/file/d/1W9Orc6N8986Dg6JvaJMOBe4ox9JGvucm/view?usp=drivesdk",
            ],
            [
                'id' => 9,
                'nama' => "Kelompok Studi Pasar Modal",
                'singkatan' => "KSPM",
                'deskripsi' => "Organisasi mahasiswa yang berfokus pada edukasi literasi keuangan, investasi, dan pasar modal di Universitas Klabat. KSPM menjadi wadah bagi mahasiswa untuk mempelajari dunia investasi secara ilmiah dan bertanggung jawab, dengan mengintegrasikan nilai-nilai Kristiani dalam pengelolaan keuangan.",
                'visi' => "Membentuk mahasiswa Universitas Klabat untuk memiliki pemahaman dasar finansial literasi yang kuat, mampu mengelola keuangan secara bijaksana, serta memahami prinsip investasi dan mengintegrasikan nilai-nilai Kristiani.",
                'misi' => [
                    "Meningkatkan pengembangan kompetensi minat dan bakat di bidang Pasar Modal untuk memaksimalkan niat investasi mahasiswa.",
                    "Melaksanakan kegiatan dengan efektif dan efisien sebagai instrumen menggali potensi keanggotaan.",
                    "Memberikan ruang kebebasan untuk berinovasi dan berpendapat mengenai investasi.",
                ],
                'sosmed' => [
                    'instagram' => "@kspm.unklab",
                ],
                'logo' => null,
            ],
            [
                'id' => 10,
                'nama' => "Generasi Baru Indonesia UNKLAB",
                'singkatan' => "GenBI UNKLAB",
                'deskripsi' => "Komunitas penerima beasiswa Bank Indonesia di Universitas Klabat yang berperan sebagai Frontliner Bank Indonesia. GenBI aktif dalam kegiatan pemberdayaan masyarakat, sosialisasi kebijakan Bank Indonesia, kegiatan sosial, dan pengembangan kapasitas diri mahasiswa sebagai Agent of Change dan Future Leader.",
                'visi' => "Menjadikan kaum muda Indonesia sebagai generasi yang kompeten dalam berbagai bidang keilmuan serta dapat membawa perubahan positif dan menjadi inspirasi bagi bangsa dan negara.",
                'misi' => [
                    "INITIATE: menggagas berbagai kegiatan pemberdayaan masyarakat.",
                    "ACT: berperan sebagai garda terdepan dalam aksi nyata bagi pembangunan.",
                    "SHARE: berkontribusi langsung dalam upaya pemberdayaan masyarakat.",
                ],
                'sosmed' => [
                    'instagram' => "@genbiunklab",
                ],
                'logo' => "https://drive.google.com/file/d/1bCWOLu6B5Y-toYfs6OWTtw54VgFJuRqd/view?usp=drivesdk",
            ],
            [
                'id' => 11,
                'nama' => "Computer Science Student Association",
                'singkatan' => "CSSA",
                'deskripsi' => "Asosiasi mahasiswa Program Studi Ilmu Komputer Universitas Klabat yang menjadi jembatan antara mahasiswa dan dosen, serta membangun lingkungan perkuliahan yang kondusif dan berkesan. CSSA aktif dalam kegiatan akademik, kerohanian, multimedia, dan olahraga.",
                'visi' => "Membangun lingkungan perkuliahan yang membuat mahasiswa mendapatkan kesan baik; membantu mahasiswa lebih dekat dengan dosen; menjadi penengah antara dosen dan mahasiswa.",
                'misi' => [
                    "Membangun acara yang berkualitas sehingga mahasiswa mendapatkan kesan yang baik.",
                ],
                'sosmed' => [
                    'instagram' => "@cssaunklab",
                ],
                'logo' => null,
            ],
            [
                'id' => 12,
                'nama' => "BEM Fakultas Filsafat",
                'singkatan' => "BEM Filsafat",
                'deskripsi' => "Badan Eksekutif Mahasiswa Fakultas Filsafat Universitas Klabat yang berfokus pada pembinaan kerohanian dan pengembangan mahasiswa calon pelayan injil. BEM Filsafat aktif dalam kegiatan doa, ibadah, dan pengembangan karakter rohani mahasiswa fakultas.",
                'visi' => "Mempersiapkan pelayan-pelayan injil dengan karakter seperti Yesus Kristus untuk melayani Allah dan manusia.",
                'misi' => [
                    "Mempersiapkan dan melatih pelayan injil melalui pengembangan kualitas pribadi, pengetahuan, komitmen, dan kecakapan.",
                    "Bergerak untuk mendukung dan membangun kerohanian mahasiswa Fakultas Filsafat.",
                ],
                'sosmed' => [
                    'instagram' => "@fakultasfilsafatofficial",
                ],
                'logo' => null,
            ],
            [
                'id' => 13,
                'nama' => "Tou Minahasa Student Club",
                'singkatan' => "Tou Minahasa",
                'deskripsi' => "Organisasi ikatan daerah yang menghimpun mahasiswa berdarah Minahasa di Universitas Klabat. Tou Minahasa menjunjung semangat kekeluargaan 'Torang Samua Basudara', aktif melestarikan budaya Minahasa melalui seni tari adat, serta menjalankan program pelayanan dan pengabdian kepada masyarakat.",
                'visi' => "Menjadi wadah organisasi yang solid, berkarakter, dan berlandaskan iman, guna mempererat persaudaraan serta melestarikan budaya dan nilai-nilai luhur Minahasa di lingkungan Universitas Klabat.",
                'misi' => [
                    "Mempererat Persaudaraan: menyatukan ke-5 suku besar tanah Minahasa dengan semboyan 'Torang Samua Basudara'.",
                    "Menjunjung Tinggi Iman dan Moral: membentuk anggota yang bertakwa, beretika, dan menjadi teladan.",
                    "Pengembangan Diri dan Prestasi: mendorong anggota aktif dan berprestasi.",
                    "Pelestarian Budaya: melestarikan seni, budaya, dan kearifan lokal Minahasa.",
                    "Pelayanan dan Pengabdian: melaksanakan bakti sosial dan kerjasama bagi kampus dan masyarakat.",
                ],
                'sosmed' => [
                    'instagram' => "@touminahasasc",
                ],
                'logo' => "https://drive.google.com/drive/folders/1G0NL4iXDtqVBmufJzrI6eCp_jsmqO1gD",
            ],
            [
                'id' => 14,
                'nama' => "Ikatan Mahasiswa Maluku Utara",
                'singkatan' => "IKMAMALUT",
                'deskripsi' => "Organisasi ikatan daerah yang menghimpun mahasiswa asal Maluku Utara di Universitas Klabat. IKMAMALUT menjadi rumah bagi mahasiswa Maluku Utara di tanah rantau, melestarikan identitas budaya daerah, mempererat persaudaraan, dan mendorong anggota untuk berprestasi serta lulus tepat waktu.",
                'visi' => "Menjadi rumah bagi mahasiswa Maluku Utara yang berlandaskan nilai ketuhanan, kekeluargaan, dan pelestarian identitas daerah.",
                'misi' => [
                    "Religiusitas: menjadikan nilai-nilai ketuhanan sebagai landasan utama setiap kegiatan.",
                    "Solidaritas: mempererat ikatan persaudaraan dan rasa nyaman bagi seluruh anggota.",
                    "Pelestarian Budaya: menjaga identitas asal melalui penggunaan logat dan tradisi Maluku Utara.",
                    "Pengembangan Anggota: mewadahi aspirasi dan pengembangan potensi mahasiswa di tanah rantau.",
                ],
                'sosmed' => [
                    'instagram' => "@ikmamalut_unklab",
                ],
                'logo' => "https://drive.google.com/drive/folders/125u2Z6lhDrN1iK_bD5MlOpEk8hW2SVso",
            ],
        ];

        foreach ($organisasiList as $org) {
            DB::table('organizations')->updateOrInsert(
                ['shortname' => $org['singkatan']],
                [
                    'name' => $org['nama'],
                    'description' => $org['deskripsi'],
                    'vision' => $org['visi'],
                    'mission' => !empty($org['misi']) ? implode("\n", array_map(fn($m) => "- " . $m, $org['misi'])) : null,
                    'instagram' => $org['sosmed']['instagram'] ?? null,
                    'logo' => $org['logo'],
                    'profile_status' => 'complete',
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
