import React from 'react';
export default function Pengumuman() {
  return (
    <div className="p-6">
      <h1 className="text-xl font-semibold">Pengumuman</h1>
      <p>Halaman pengumuman placeholder.</p>
    </div>
  );
}
import React, { useState, useMemo } from 'react';
import Card from '../../components/ui/Card';
import Badge from '../../components/ui/Badge';
import Dialog from '../../components/ui/Dialog';
import Tabs from '../../components/ui/Tabs';
import SearchInput from '../../components/ui/SearchInput';

/**
 * Pengumuman Page - Mahasiswa View
 *
 * Features:
 * - Tabs: Semua, Akademik, Organisasi, Event
 * - Search & filter by kategori
 * - List card dengan badge status (Baru, Penting)
 * - Detail modal untuk lihat konten lengkap
 * - Dummy data untuk development
 */
export default function Pengumuman() {
  // Dummy data pengumuman
  const [pengumumanList] = useState([
    {
      id: 1,
      judul: 'Jadwal UTS Semester Genap 2024',
      ringkasan:
        'Jadwal pelaksanaan UTS untuk semua program studi sudah tersedia. Silakan cek portal akademik...',
      konten: `Berikut adalah jadwal pelaksanaan Ujian Tengah Semester (UTS) Genap 2024 untuk semua program studi:

PROGRAM STUDI TEKNIK INFORMATIKA
- Tanggal: 18 Maret - 29 Maret 2024
- Waktu: 08:00 - 17:00 WIB (sesuai jadwal individual)
- Lokasi: Ruang Laboratorium Lt. 2-4

PROGRAM STUDI SISTEM INFORMASI
- Tanggal: 18 Maret - 29 Maret 2024
- Lokasi: Ruang Kelas Lt. 1-3

Peraturan:
1. Mahasiswa wajib hadir 15 menit sebelum ujian dimulai
2. Membawa kartu pelajar dan KTP
3. Tidak diperkenankan membawa barang elektronik kecuali kalkulator

Untuk informasi lebih lanjut, hubungi Bagian Akademik di ruang 101.`,
      kategori: 'Akademik',
      tanggal: '2024-03-10',
      author: 'Bagian Akademik & Kemahasiswaan',
      status: 'baru', // 'baru' | 'biasa'
      penting: true,
      lampiran: 'jadwal-uts-2024.pdf',
      link: null,
    },
    {
      id: 2,
      judul: 'Pendaftaran PILDIK 2024 Dibuka',
      ringkasan:
        'Pendaftaran Pemilihan Duta Internal Kampus tahun 2024 telah dibuka. Pendaftaran dibuka untuk...',
      konten: `Assalamualaikum Warahmatullahi Wabarakatuh,

Dengan antusias kami kabarkan bahwa Pendaftaran Pemilihan Duta Internal Kampus (PILDIK) 2024 sudah DIBUKA!

SYARAT & KETENTUAN:
1. Mahasiswa aktif UNKLAB
2. IPK minimal 3.0
3. Belum pernah menjadi Duta Kampus
4. Bersedia mengikuti seluruh rangkaian acara hingga selesai

PENDAFTARAN:
- Tempat: Kantor BEM Lantai 1
- Waktu: 12 Maret - 20 Maret 2024
- Biaya: Rp 100.000

DOKUMEN YANG DIPERLUKAN:
- Kartu Pelajar & KTP
- Transkrip nilai (fotokopi)
- Surat rekomendasi dari akademik / organisasi
- Foto 4x6 (3 lembar)

Mari bergabung dan tunjukkan bakat Anda! Duta Internal Kampus menjadi wakil mahasiswa dalam berbagai acara penting.

Untuk info lebih lanjut hubungi:
BEM UNKLAB - Whatsapp: 0821-XXXX-XXXX`,
      kategori: 'Organisasi',
      tanggal: '2024-03-08',
      author: 'BEM UNKLAB',
      status: 'baru',
      penting: false,
      lampiran: null,
      link: 'https://bit.ly/pildik2024',
    },
    {
      id: 3,
      judul: 'Launching UNKLAB Annual Awards 2024',
      ringkasan:
        'Acara launching dan pengumuman kategori untuk UNKLAB Annual Awards tahun ini akan segera...',
      konten: `UNKLAB Annual Awards merupakan penghargaan tahunan untuk memberikan apresiasi kepada mahasiswa, organisasi, dan karyawan yang berprestasi.

KATEGORI PENGHARGAAN:
1. Best Student Leader
2. Best Organization
3. Best Academic Achievement
4. Best Community Service
5. Best Innovation/Kreativitas

PENDAFTARAN: 
- Mulai: 25 Maret 2024
- Tutup: 15 April 2024
- Undian hadiah utama: Rp 50 juta

ACARA LAUNCHING:
- Hari: Sabtu, 23 Maret 2024
- Waktu: 13:00 - 17:00 WIB
- Tempat: Aula Utama UNKLAB
- Gratis untuk semua mahasiswa

Jangan lewatkan kesempatan emas ini untuk mendapatkan penghargaan atas prestasi Anda!`,
      kategori: 'Event',
      tanggal: '2024-03-05',
      author: 'Rektorat UNKLAB',
      status: 'biasa',
      penting: true,
      lampiran: null,
      link: null,
    },
    {
      id: 4,
      judul: 'Workshop: Digital Marketing 101',
      ringkasan:
        'Workshop gratis tentang dasar-dasar digital marketing akan diadakan oleh HIMAKOM. Terbuka untuk...',
      konten: `HIMAKOM dengan bangga mempersembahkan Workshop Digital Marketing 101 yang akan membahas strategi marketing di era digital.

PEMBICARA: Budi Santoso (Digital Marketing Manager at Tech Company)

MATERI:
- Pengenalan Digital Marketing
- Social Media Strategy
- Content Creation Tips
- Analitik & Metrik Penting
- Q&A Session

DETAIL ACARA:
- Hari: Rabu, 20 Maret 2024
- Waktu: 15:00 - 17:30 WIB
- Tempat: Ruang Seminar A Lt. 3
- Kuota: 50 peserta (first come first served)
- Biaya: GRATIS

PENDAFTARAN:
Link Google Form: [akan dikirim via Instagram HIMAKOM]

Benefit Peserta:
✓ Sertifikat kehadiran
✓ Goodie bag
✓ Networking dengan praktisi

Jangan sia-siakan kesempatan untuk belajar dari ahli!`,
      kategori: 'Event',
      tanggal: '2024-03-01',
      author: 'HIMAKOM (Himpunan Mahasiswa Teknik Informatika)',
      status: 'biasa',
      penting: false,
      lampiran: null,
      link: null,
    },
    {
      id: 5,
      judul: 'Pengumuman Hasil Beasiswa Prestasi',
      ringkasan:
        'Pengumuman penerimaan beasiswa prestasi tahun akademik 2024/2025 telah diumumkan di portal...',
      konten: `Assalamualaikum Wr. Wb.,

Dengan hormat kami sampaikan bahwa pengumuman hasil seleksi Beasiswa Prestasi Tahun Akademik 2024/2025 telah keluar.

CARA MENGECEK HASIL:
1. Login ke portal akademik dengan NIM dan password Anda
2. Klik menu "Beasiswa"
3. Pilih "Cek Status Seleksi"

JADWAL PENTING:
- Pengumuman Hasil: 10 Maret 2024
- Penetapan Penerima: 15 Maret 2024
- Pencairan Beasiswa: Mulai 20 Maret 2024

PERLU DIKETAHUI:
- Jangan spam atau tanya-tanya ke staff, cek portal dulu
- Jika ada kendala akses, lapor ke Bagian Akademik
- Bagi yang tidak lolos, tetap semangat coba lagi semester depan

Terima kasih atas partisipasi Anda.`,
      kategori: 'Akademik',
      tanggal: '2024-02-28',
      author: 'Bagian Akademik & Kemahasiswaan',
      status: 'biasa',
      penting: true,
      lampiran: 'daftar-penerima-beasiswa.pdf',
      link: 'https://portal.unklab.ac.id/beasiswa',
    },
    {
      id: 6,
      judul: 'Gathering Pengurus 2024',
      ringkasan:
        'Gathering bersama seluruh pengurus organisasi untuk membahas program kerja tahun ini...',
      konten: `Halo semua pengurus!

Kami mengundang seluruh pengurus organisasi untuk hadir di Gathering Pengurus 2024 yang akan membahas:

AGENDA:
1. Sambutan dari Kemahasiswaan
2. Sharing Program Kerja Tahunan
3. Diskusi Kolaborasi Antar Organisasi
4. Team Building
5. Makan bersama

DETAIL:
- Hari: Jumat, 22 Maret 2024
- Waktu: 17:00 - 22:00 WIB
- Tempat: Aula Olahraga UNKLAB
- Peserta: Ketua & Perwakilan setiap organisasi
- Biaya: Rp 50.000/orang

PENDAFTARAN:
- Link: [akan dikirim via email pengurus]
- Deadline: 18 Maret 2024

Confetti, hiburan, dan kejutan menanti! Jangan sampai organisasi Anda ketinggalan.`,
      kategori: 'Organisasi',
      tanggal: '2024-02-25',
      author: 'Kemahasiswaan UNKLAB',
      status: 'biasa',
      penting: false,
      lampiran: null,
      link: null,
    },
  ]);

  // State
  const [searchQuery, setSearchQuery] = useState('');
  const [activeTab, setActiveTab] = useState('semua');
  const [selectedPengumuman, setSelectedPengumuman] = useState(null);
  const [dialogOpen, setDialogOpen] = useState(false);

  // Tabs data
  const tabs = [
    { id: 'semua', label: 'Semua' },
    { id: 'akademik', label: 'Akademik' },
    { id: 'organisasi', label: 'Organisasi' },
    { id: 'event', label: 'Event' },
  ];

  // Filter pengumuman berdasarkan tab dan search
  const filteredPengumuman = useMemo(() => {
    let filtered = pengumumanList;

    // Filter by tab
    if (activeTab !== 'semua') {
      filtered = filtered.filter(
        (p) => p.kategori.toLowerCase() === activeTab.toLowerCase()
      );
    }

    // Filter by search
    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      filtered = filtered.filter(
        (p) =>
          p.judul.toLowerCase().includes(q) ||
          p.ringkasan.toLowerCase().includes(q)
      );
    }

    return filtered;
  }, [activeTab, searchQuery]);

  // Handler membuka detail
  const openDetail = (pengumuman) => {
    setSelectedPengumuman(pengumuman);
    setDialogOpen(true);
  };

  return (
    <div className="pt-20 pb-12">
      <div className="max-w-5xl mx-auto px-4">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-purple-700 mb-2">
            📢 Pengumuman
          </h1>
          <p className="text-gray-600 text-lg">
            Informasi terbaru dari kampus dan organisasi mahasiswa
          </p>
        </div>

        {/* Search */}
        <div className="mb-8">
          <SearchInput
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            placeholder="Cari pengumuman..."
          />
        </div>

        {/* Tabs */}
        <div className="mb-8">
          <Tabs tabs={tabs} activeTab={activeTab} onTabChange={setActiveTab} />
        </div>

        {/* Pengumuman List */}
        <div className="space-y-4">
          {filteredPengumuman.length > 0 ? (
            filteredPengumuman.map((pengumuman) => (
              <Card
                key={pengumuman.id}
                hover
                onClick={() => openDetail(pengumuman)}
                className="cursor-pointer"
              >
                <div className="space-y-3">
                  {/* Header dengan badges */}
                  <div className="flex items-start justify-between gap-4">
                    <div className="flex-1">
                      <h3 className="text-xl font-bold text-gray-900 mb-2">
                        {pengumuman.judul}
                      </h3>
                      <p className="text-gray-600 line-clamp-2">
                        {pengumuman.ringkasan}
                      </p>
                    </div>
                    <div className="flex flex-col gap-2 items-end">
                      {pengumuman.status === 'baru' && (
                        <Badge variant="warning" size="sm">
                          🆕 Baru
                        </Badge>
                      )}
                      {pengumuman.penting && (
                        <Badge variant="danger" size="sm">
                          ⭐ Penting
                        </Badge>
                      )}
                    </div>
                  </div>

                  {/* Footer info */}
                  <div className="flex items-center justify-between text-sm text-gray-500 pt-2 border-t border-gray-200">
                    <div className="flex items-center gap-4">
                      <span className="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                        {pengumuman.kategori}
                      </span>
                      <span>{pengumuman.author}</span>
                    </div>
                    <span>{formatDate(pengumuman.tanggal)}</span>
                  </div>
                </div>
              </Card>
            ))
          ) : (
            <div className="text-center py-12">
              <div className="text-4xl mb-3">🤔</div>
              <p className="text-gray-600 text-lg">
                Tidak ada pengumuman yang ditemukan
              </p>
            </div>
          )}
        </div>
      </div>

      {/* Detail Modal */}
      <Dialog
        open={dialogOpen}
        onClose={() => setDialogOpen(false)}
        title={selectedPengumuman?.judul}
        size="lg"
      >
        {selectedPengumuman && (
          <div className="space-y-6">
            {/* Meta Info */}
            <div className="flex flex-wrap items-center gap-4 pb-4 border-b border-gray-200">
              <Badge variant="purple" size="md">
                {selectedPengumuman.kategori}
              </Badge>
              {selectedPengumuman.status === 'baru' && (
                <Badge variant="warning" size="md">
                  🆕 Baru
                </Badge>
              )}
              {selectedPengumuman.penting && (
                <Badge variant="danger" size="md">
                  ⭐ Penting
                </Badge>
              )}
            </div>

            {/* Author & Date */}
            <div className="text-sm text-gray-600 space-y-1">
              <p>
                <strong>Sumber:</strong> {selectedPengumuman.author}
              </p>
              <p>
                <strong>Tanggal:</strong>{' '}
                {formatDateLong(selectedPengumuman.tanggal)}
              </p>
            </div>

            {/* Content */}
            <div className="prose prose-sm max-w-none">
              <div
                className="text-gray-700 whitespace-pre-line leading-relaxed"
                style={{ fontSize: '15px' }}
              >
                {selectedPengumuman.konten}
              </div>
            </div>

            {/* Attachments & Links */}
            <div className="space-y-3 pt-4 border-t border-gray-200">
              {selectedPengumuman.lampiran && (
                <div>
                  <p className="text-sm font-semibold text-gray-700 mb-2">
                    📎 Lampiran:
                  </p>
                  <button className="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-sm font-semibold">
                    <svg
                      className="w-4 h-4"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                    </svg>
                    {selectedPengumuman.lampiran}
                  </button>
                </div>
              )}

              {selectedPengumuman.link && (
                <div>
                  <p className="text-sm font-semibold text-gray-700 mb-2">
                    🔗 Link Terkait:
                  </p>
                  <a
                    href={selectedPengumuman.link}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-semibold"
                  >
                    <svg
                      className="w-4 h-4"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path d="M11 3a1 1 0 100 2h3.586L9.293 9.293a1 1 0 000 1.414 1 1 0 001.414 0L16 6.414V10a1 1 0 102 0V4a1 1 0 00-1-1h-6z" />
                      <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                    </svg>
                    Buka Link
                  </a>
                </div>
              )}
            </div>

            {/* Footer action */}
            <div className="pt-4 border-t border-gray-200">
              <button
                onClick={() => setDialogOpen(false)}
                className="w-full px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors font-semibold"
              >
                Tutup
              </button>
            </div>
          </div>
        )}
      </Dialog>
    </div>
  );
}

// Utility functions
function formatDate(dateString) {
  const date = new Date(dateString);
  const today = new Date();
  const yesterday = new Date(today);
  yesterday.setDate(yesterday.getDate() - 1);

  if (date.toDateString() === today.toDateString()) return 'Hari ini';
  if (date.toDateString() === yesterday.toDateString()) return 'Kemarin';

  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

function formatDateLong(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}
