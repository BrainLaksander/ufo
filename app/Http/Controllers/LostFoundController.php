<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LostFoundController extends Controller
{
    /**
     * Display the Lost & Found page
     */
    public function index(): View
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Dompet Kulit Hitam',
                'status' => 'approved',
                'itemStatus' => 'hilang',
                'priority' => true,
                'date' => '2024-03-15',
                'location' => 'Aula Utama',
                'description' => 'Dompet kulit hitam dengan inisial AB, berisi kartu pelajar, KTP, dan uang. Ditemukan di area depan aula sebelah tempat duduk besi.',
                'contact' => 'aldi.pratama@unklab.ac.id',
                'phone' => '0821-1234-5678',
                'category' => 'Dompet'
            ],
            [
                'id' => 2,
                'name' => 'Gantungan Kunci Biru',
                'status' => 'approved',
                'itemStatus' => 'ditemukan',
                'priority' => false,
                'date' => '2024-03-10',
                'location' => 'Perpustakaan, Rak Buku Zona C',
                'description' => 'Gantungan kunci biru dengan logo kampus UNKLAB, isi 3 kunci (warna emas). Sangat mudah dikenali.',
                'contact' => 'perpus@unklab.ac.id',
                'phone' => '0821-9876-5432',
                'category' => 'Kunci'
            ],
            [
                'id' => 3,
                'name' => 'Buku Catatan MATH101',
                'status' => 'approved',
                'itemStatus' => 'hilang',
                'priority' => false,
                'date' => '2024-03-18',
                'location' => 'Ruang Kelas Lt. 2',
                'description' => 'Buku catatan merah dengan nama "Sinta" di halaman depan. Isi catatan kuliah Matematika Dasar dari bulan Februari-Maret.',
                'contact' => 'sinta.wijaya@unklab.ac.id',
                'phone' => '0821-5555-5555',
                'category' => 'Buku'
            ],
            [
                'id' => 4,
                'name' => 'Laptop Asus ROG',
                'status' => 'approved',
                'itemStatus' => 'hilang',
                'priority' => true,
                'date' => '2024-03-19',
                'location' => 'Ruang Laboratorium Komputer Lt. 4',
                'description' => 'Laptop Asus ROG warna hitam dengan sticker logo. Casing dalam keadaan bagus, layar masih normal. Memiliki nilai tinggi.',
                'contact' => 'ricky.pram@unklab.ac.id',
                'phone' => '0821-2222-2222',
                'category' => 'Elektronik'
            ],
            [
                'id' => 5,
                'name' => 'Kartu Pelajar Plastik',
                'status' => 'approved',
                'itemStatus' => 'ditemukan',
                'priority' => false,
                'date' => '2024-03-12',
                'location' => 'Kantin Area Meja Makan',
                'description' => 'Kartu pelajar UNKLAB dengan foto nama. Ditemukan dalam kondisi baik di salah satu meja kantin.',
                'contact' => 'kemahasiswaan@unklab.ac.id',
                'phone' => '0821-3333-3333',
                'category' => 'Kartu Identitas'
            ],
            [
                'id' => 6,
                'name' => 'Headphone Wireless JBL',
                'status' => 'approved',
                'itemStatus' => 'ditemukan',
                'priority' => false,
                'date' => '2024-03-08',
                'location' => 'Mushola Lt. 1',
                'description' => 'Headphone nirkabel warna hitam dengan warna merah di bagian telinga. Masih dalam kondisi hidup dengan baterai 60%.',
                'contact' => 'doni.herm@unklab.ac.id',
                'phone' => '0821-4444-4444',
                'category' => 'Elektronik'
            ]
        ];

        return view('mahasiswa.lost-found', [
            'items' => $items
        ]);
    }

    /**
     * Get item detail (API)
     */
    public function detail($id)
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Dompet Kulit Hitam',
                'status' => 'approved',
                'itemStatus' => 'hilang',
                'priority' => true,
                'date' => '2024-03-15',
                'location' => 'Aula Utama',
                'description' => 'Dompet kulit hitam dengan inisial AB, berisi kartu pelajar, KTP, dan uang. Ditemukan di area depan aula sebelah tempat duduk besi.',
                'contact' => 'aldi.pratama@unklab.ac.id',
                'phone' => '0821-1234-5678',
                'category' => 'Dompet'
            ],
            [
                'id' => 2,
                'name' => 'Gantungan Kunci Biru',
                'status' => 'approved',
                'itemStatus' => 'ditemukan',
                'priority' => false,
                'date' => '2024-03-10',
                'location' => 'Perpustakaan, Rak Buku Zona C',
                'description' => 'Gantungan kunci biru dengan logo kampus UNKLAB, isi 3 kunci (warna emas). Sangat mudah dikenali.',
                'contact' => 'perpus@unklab.ac.id',
                'phone' => '0821-9876-5432',
                'category' => 'Kunci'
            ],
            [
                'id' => 3,
                'name' => 'Buku Catatan MATH101',
                'status' => 'approved',
                'itemStatus' => 'hilang',
                'priority' => false,
                'date' => '2024-03-18',
                'location' => 'Ruang Kelas Lt. 2',
                'description' => 'Buku catatan merah dengan nama "Sinta" di halaman depan. Isi catatan kuliah Matematika Dasar dari bulan Februari-Maret.',
                'contact' => 'sinta.wijaya@unklab.ac.id',
                'phone' => '0821-5555-5555',
                'category' => 'Buku'
            ],
            [
                'id' => 4,
                'name' => 'Laptop Asus ROG',
                'status' => 'approved',
                'itemStatus' => 'hilang',
                'priority' => true,
                'date' => '2024-03-19',
                'location' => 'Ruang Laboratorium Komputer Lt. 4',
                'description' => 'Laptop Asus ROG warna hitam dengan sticker logo. Casing dalam keadaan bagus, layar masih normal. Memiliki nilai tinggi.',
                'contact' => 'ricky.pram@unklab.ac.id',
                'phone' => '0821-2222-2222',
                'category' => 'Elektronik'
            ],
            [
                'id' => 5,
                'name' => 'Kartu Pelajar Plastik',
                'status' => 'approved',
                'itemStatus' => 'ditemukan',
                'priority' => false,
                'date' => '2024-03-12',
                'location' => 'Kantin Area Meja Makan',
                'description' => 'Kartu pelajar UNKLAB dengan foto nama. Ditemukan dalam kondisi baik di salah satu meja kantin.',
                'contact' => 'kemahasiswaan@unklab.ac.id',
                'phone' => '0821-3333-3333',
                'category' => 'Kartu Identitas'
            ],
            [
                'id' => 6,
                'name' => 'Headphone Wireless JBL',
                'status' => 'approved',
                'itemStatus' => 'ditemukan',
                'priority' => false,
                'date' => '2024-03-08',
                'location' => 'Mushola Lt. 1',
                'description' => 'Headphone nirkabel warna hitam dengan warna merah di bagian telinga. Masih dalam kondisi hidup dengan baterai 60%.',
                'contact' => 'doni.herm@unklab.ac.id',
                'phone' => '0821-4444-4444',
                'category' => 'Elektronik'
            ]
        ];

        $item = collect($items)->firstWhere('id', (int) $id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json($item);
    }
}
