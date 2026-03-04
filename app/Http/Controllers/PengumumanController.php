<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PengumumanController extends Controller
{
    /**
     * Menampilkan halaman Pengumuman
     */
    public function index(): View
    {
        // Dummy data pengumuman
        $pengumuman = [
            [
                'id' => 1,
                'judul' => 'Pengumuman Libur Semester Ganjil',
                'ringkasan' => 'Libur semester ganjil akan dimulai pada tanggal 15 Desember 2024 hingga 10 Januari 2025.',
                'konten' => 'Pengumuman resmi dari Rektorat:\n\nLibur semester ganjil akan dimulai pada tanggal 15 Desember 2024 hingga 10 Januari 2025. Semua mahasiswa diharapkan sudah menyelesaikan ujian akhir semester sebelum libur dimulai.\n\nKeterangan lebih lanjut dapat menghubungi bagian akademik kampus.',
                'kategori' => 'Akademik',
                'author' => 'Rektorat UNKLAB',
                'date' => '2024-12-01',
                'lampiran' => ['Kalender_Akademik_2024.pdf'],
                'is_new' => true,
                'is_important' => true
            ],
            [
                'id' => 2,
                'judul' => 'Konser Musik BEM UNKLAB 2025',
                'ringkasan' => 'BEM UNKLAB mengadakan konser musik dengan menghadirkan artis terkenal.',
                'konten' => 'BEM UNKLAB dengan bangga mempersembahkan:\n\nKONSER MUSIK BEM UNKLAB 2025\n\nTanggal: 25 Januari 2025\nTempat: Aula Utama Kampus\nWaktu: 19:00 - 23:00 WIB\n\nArtis yang tampil:\n- Artis 1\n- Artis 2\n- Artis 3\n\nTiket tersedia di...',
                'kategori' => 'Event',
                'author' => 'BEM UNKLAB',
                'date' => '2024-11-28',
                'lampiran' => [],
                'is_new' => true,
                'is_important' => false
            ],
            [
                'id' => 3,
                'judul' => 'Lomba Esai Nasional Kategori Mahasiswa',
                'ringkasan' => 'Fakultas Hukum mengundang mahasiswa mengikuti lomba esai nasional dengan hadiah menarik.',
                'konten' => 'Lomba Esai Nasional\n\nFakultas Hukum UNKLAB mengundang seluruh mahasiswa untuk mengikuti lomba esai nasional.\n\nTema: Hak Asasi Manusia di Era Digital\nDeadline Pendaftaran: 31 Desember 2024\nDeadline Pengumpulan Esai: 15 Januari 2025\n\nHadiah Utama: Rp 10.000.000\nHadiah 2: Rp 7.500.000\nHadiah 3: Rp 5.000.000\n\nInfo selengkapnya di: www.unklab.ac.id/lomba',
                'kategori' => 'Organisasi',
                'author' => 'Fakultas Hukum',
                'date' => '2024-11-25',
                'lampiran' => ['Buku_Panduan_Lomba.pdf'],
                'is_new' => false,
                'is_important' => true
            ],
            [
                'id' => 4,
                'judul' => 'Pembukaan Perpustakaan Baru UNKLAB',
                'ringkasan' => 'Perpustakaan baru UNKLAB telah resmi dibuka untuk melayani mahasiswa.',
                'konten' => 'Dengan senang hati mengumumkan bahwa Perpustakaan Baru UNKLAB telah resmi dibuka.\n\nFasilitas yang tersedia:\n- Ruang baca nyaman\n- Koleksi buku terlengkap\n- Akses digital (e-journal, e-book)\n- Area diskusi kelompok\n- WiFi gratis\n\nJam operasional:\nSenin - Jumat: 08:00 - 17:00\nSabtu: 09:00 - 14:00\nMinggu: Tutup\n\nKartu perpustakaan dapat diurus di tempat.',
                'kategori' => 'Akademik',
                'author' => 'Perpustakaan UNKLAB',
                'date' => '2024-11-20',
                'lampiran' => [],
                'is_new' => false,
                'is_important' => false
            ],
            [
                'id' => 5,
                'judul' => 'Kegiatan Pengabdian Masyarakat Mahasiswa',
                'ringkasan' => 'Mahasiswa UNKLAB mengadakan kegiatan pengabdian masyarakat di desa-desa sekitar kampus.',
                'konten' => 'Program Pengabdian Masyarakat\n\nMahasiswa UNKLAB akan melaksanakan program pengabdian masyarakat di beberapa desa selama semester ini.\n\nKegiatan yang akan dilakukan:\n- Penyuluhan kesehatan\n- Program literasi\n- Pelatihan keterampilan\n- Pembersihan lingkungan\n\nPendaftaran masih dibuka untuk mahasiswa yang ingin ikut serta.',
                'kategori' => 'Organisasi',
                'author' => 'LPPM UNKLAB',
                'date' => '2024-11-15',
                'lampiran' => ['Formulir_Pendaftaran.pdf'],
                'is_new' => false,
                'is_important' => false
            ],
            [
                'id' => 6,
                'judul' => 'Seminar Karir: Mempersiapkan Diri untuk Dunia Kerja',
                'ringkasan' => 'Seminar karir menghadirkan pembicara dari berbagai industri untuk membantu mahasiswa mempersiapkan karir.',
                'konten' => 'Seminar Karir 2025\n\nDi era yang kompetitif ini, persiapan karir sejak dini sangat penting. UNKLAB mengundang mahasiswa untuk mengikuti seminar karir.\n\nPembicara:\n- CEO Perusahaan Tech\n- HR Manager Perusahaan Multinasional\n- Entrepreneur Muda\n\nTanggal: 20 Desember 2024\nTempat: Ruang Seminar Lt. 3\nWaktu: 13:00 - 16:00 WIB\nGratis (Kuota Terbatas)\n\nPendaftaran: bit.ly/seminar-karir-2025',
                'kategori' => 'Akademik',
                'author' => 'Pusat Karir UNKLAB',
                'date' => '2024-11-10',
                'lampiran' => [],
                'is_new' => false,
                'is_important' => false
            ]
        ];

        return view('mahasiswa.pengumuman', [
            'pengumuman' => $pengumuman
        ]);
    }

    /**
     * Show detail pengumuman (API)
     */
    public function detail($id)
    {
        $pengumuman = [
            [
                'id' => 1,
                'judul' => 'Pengumuman Libur Semester Ganjil',
                'ringkasan' => 'Libur semester ganjil akan dimulai pada tanggal 15 Desember 2024 hingga 10 Januari 2025.',
                'konten' => 'Pengumuman resmi dari Rektorat:\n\nLibur semester ganjil akan dimulai pada tanggal 15 Desember 2024 hingga 10 Januari 2025. Semua mahasiswa diharapkan sudah menyelesaikan ujian akhir semester sebelum libur dimulai.\n\nKeterangan lebih lanjut dapat menghubungi bagian akademik kampus.',
                'kategori' => 'Akademik',
                'author' => 'Rektorat UNKLAB',
                'date' => '2024-12-01',
                'lampiran' => ['Kalender_Akademik_2024.pdf'],
                'is_new' => true,
                'is_important' => true
            ],
            [
                'id' => 2,
                'judul' => 'Konser Musik BEM UNKLAB 2025',
                'ringkasan' => 'BEM UNKLAB mengadakan konser musik dengan menghadirkan artis terkenal.',
                'konten' => 'BEM UNKLAB dengan bangga mempersembahkan:\n\nKONSER MUSIK BEM UNKLAB 2025\n\nTanggal: 25 Januari 2025\nTempat: Aula Utama Kampus\nWaktu: 19:00 - 23:00 WIB\n\nArtis yang tampil:\n- Artis 1\n- Artis 2\n- Artis 3\n\nTiket tersedia di...',
                'kategori' => 'Event',
                'author' => 'BEM UNKLAB',
                'date' => '2024-11-28',
                'lampiran' => [],
                'is_new' => true,
                'is_important' => false
            ],
            [
                'id' => 3,
                'judul' => 'Lomba Esai Nasional Kategori Mahasiswa',
                'ringkasan' => 'Fakultas Hukum mengundang mahasiswa mengikuti lomba esai nasional dengan hadiah menarik.',
                'konten' => 'Lomba Esai Nasional\n\nFakultas Hukum UNKLAB mengundang seluruh mahasiswa untuk mengikuti lomba esai nasional.\n\nTema: Hak Asasi Manusia di Era Digital\nDeadline Pendaftaran: 31 Desember 2024\nDeadline Pengumpulan Esai: 15 Januari 2025\n\nHadiah Utama: Rp 10.000.000\nHadiah 2: Rp 7.500.000\nHadiah 3: Rp 5.000.000\n\nInfo selengkapnya di: www.unklab.ac.id/lomba',
                'kategori' => 'Organisasi',
                'author' => 'Fakultas Hukum',
                'date' => '2024-11-25',
                'lampiran' => ['Buku_Panduan_Lomba.pdf'],
                'is_new' => false,
                'is_important' => true
            ],
            [
                'id' => 4,
                'judul' => 'Pembukaan Perpustakaan Baru UNKLAB',
                'ringkasan' => 'Perpustakaan baru UNKLAB telah resmi dibuka untuk melayani mahasiswa.',
                'konten' => 'Dengan senang hati mengumumkan bahwa Perpustakaan Baru UNKLAB telah resmi dibuka.\n\nFasilitas yang tersedia:\n- Ruang baca nyaman\n- Koleksi buku terlengkap\n- Akses digital (e-journal, e-book)\n- Area diskusi kelompok\n- WiFi gratis\n\nJam operasional:\nSenin - Jumat: 08:00 - 17:00\nSabtu: 09:00 - 14:00\nMinggu: Tutup\n\nKartu perpustakaan dapat diurus di tempat.',
                'kategori' => 'Akademik',
                'author' => 'Perpustakaan UNKLAB',
                'date' => '2024-11-20',
                'lampiran' => [],
                'is_new' => false,
                'is_important' => false
            ],
            [
                'id' => 5,
                'judul' => 'Kegiatan Pengabdian Masyarakat Mahasiswa',
                'ringkasan' => 'Mahasiswa UNKLAB mengadakan kegiatan pengabdian masyarakat di desa-desa sekitar kampus.',
                'konten' => 'Program Pengabdian Masyarakat\n\nMahasiswa UNKLAB akan melaksanakan program pengabdian masyarakat di beberapa desa selama semester ini.\n\nKegiatan yang akan dilakukan:\n- Penyuluhan kesehatan\n- Program literasi\n- Pelatihan keterampilan\n- Pembersihan lingkungan\n\nPendaftaran masih dibuka untuk mahasiswa yang ingin ikut serta.',
                'kategori' => 'Organisasi',
                'author' => 'LPPM UNKLAB',
                'date' => '2024-11-15',
                'lampiran' => ['Formulir_Pendaftaran.pdf'],
                'is_new' => false,
                'is_important' => false
            ],
            [
                'id' => 6,
                'judul' => 'Seminar Karir: Mempersiapkan Diri untuk Dunia Kerja',
                'ringkasan' => 'Seminar karir menghadirkan pembicara dari berbagai industri untuk membantu mahasiswa mempersiapkan karir.',
                'konten' => 'Seminar Karir 2025\n\nDi era yang kompetitif ini, persiapan karir sejak dini sangat penting. UNKLAB mengundang mahasiswa untuk mengikuti seminar karir.\n\nPembicara:\n- CEO Perusahaan Tech\n- HR Manager Perusahaan Multinasional\n- Entrepreneur Muda\n\nTanggal: 20 Desember 2024\nTempat: Ruang Seminar Lt. 3\nWaktu: 13:00 - 16:00 WIB\nGratis (Kuota Terbatas)\n\nPendaftaran: bit.ly/seminar-karir-2025',
                'kategori' => 'Akademik',
                'author' => 'Pusat Karir UNKLAB',
                'date' => '2024-11-10',
                'lampiran' => [],
                'is_new' => false,
                'is_important' => false
            ]
        ];

        $item = collect($pengumuman)->firstWhere('id', (int) $id);
        
        if (!$item) {
            abort(404);
        }

        return response()->json($item);
    }
}
