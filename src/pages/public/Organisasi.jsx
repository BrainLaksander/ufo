import React, { useState, useMemo } from 'react';
import { useNavigate } from 'react-router-dom';
import Card from '../../components/ui/Card';
import Badge from '../../components/ui/Badge';
import SearchInput from '../../components/ui/SearchInput';
import FilterChips from '../../components/ui/FilterChips';

/**
 * Organisasi Page - List semua UFO/organisasi
 *
 * Features:
 * - List card organisasi dengan emoji/icon
 * - Search nama organisasi
 * - Filter kategori
 * - Button "Lihat Detail" dan "Daftar"
 * - Responsive grid layout
 */
export default function Organisasi() {
  const navigate = useNavigate();

  // Dummy data organisasi
  const [organisasiList] = useState([
    {
      id: 1,
      nama: 'HIMAKOM',
      icon: '💻',
      tagline: 'Himpunan Mahasiswa Teknik Informatika',
      kategori: 'Akademik',
      members: 245,
      deskripsi:
        'Organisasi untuk mahasiswa Teknik Informatika yang fokus pada pengembangan skill programming dan networking.',
      visiMisi: {
        visi: 'Menjadi wadah bagi mahasiswa Teknik Informatika untuk mengembangkan potensi akademik dan profesional',
        misi: [
          'Mengadakan workshop dan seminar teknologi',
          'Memfasilitasi kompetisi programming',
          'Membangun networking dengan industri IT',
        ],
      },
      budaya: 'Kolaboratif, Inovatif, Profesional',
      programs: [
        {
          judul: 'Weekly Coding Challenge',
          deskripsi: 'Kompetisi programming mingguan',
        },
        {
          judul: 'Tech Talk Series',
          deskripsi: 'Pembicaraan dengan praktisi teknologi',
        },
        { judul: 'Hackathon', deskripsi: 'Kompetisi hackathon tahunan' },
      ],
      events: [
        { judul: 'UNKLAB Programming Competition 2024', tanggal: '2024-04-15' },
        { judul: 'Tech Expo & Career Fair', tanggal: '2024-05-20' },
      ],
      struktur: [
        { posisi: 'Ketua Umum', nama: 'Aldi Pratama' },
        { posisi: 'Wakil Ketua', nama: 'Siti Nurhaliza' },
        { posisi: 'Sekretaris', nama: 'Budi Santoso' },
        { posisi: 'Bendahara', nama: 'Cindy Wijaya' },
      ],
      contact: '0821-XXXX-XXXX',
      email: 'himakom@unklab.ac.id',
      registrationOpen: true,
      banner: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    },
    {
      id: 2,
      nama: 'BEM UNKLAB',
      icon: '🎯',
      tagline: 'Badan Eksekutif Mahasiswa',
      kategori: 'Akademik',
      members: 180,
      deskripsi:
        'Badan pemerintahan mahasiswa yang mengurus kepentingan mahasiswa UNKLAB secara umum.',
      visiMisi: {
        visi: 'Menjadi wakil mahasiswa yang responsif terhadap kebutuhan kampus',
        misi: [
          'Mengadvokasi hak-hak mahasiswa',
          'Mengorganisir kegiatan kemahasiswaan',
          'Membangun kerjasama antar organisasi',
        ],
      },
      budaya: 'Transparan, Responsif, Kolaboratif',
      programs: [
        {
          judul: 'Student Forum',
          deskripsi: 'Forum terbuka untuk masukan mahasiswa',
        },
        { judul: 'Kampanye Sosial', deskripsi: 'Program pelayanan sosial' },
        {
          judul: 'Gathering Mahasiswa',
          deskripsi: 'Acara silaturahim mahasiswa',
        },
      ],
      events: [
        { judul: 'Freshmen Gathering 2024', tanggal: '2024-03-25' },
        { judul: 'Pemilu Mahasiswa 2024', tanggal: '2024-04-01' },
      ],
      struktur: [
        { posisi: 'Presiden', nama: 'Ricky Pratama' },
        { posisi: 'Wakil Presiden', nama: 'Diana Kusuma' },
        { posisi: 'Sekretaris Jenderal', nama: 'Muhammad Hari' },
        { posisi: 'Bendahara Umum', nama: 'Rini Widiyawati' },
      ],
      contact: '0821-YYYY-YYYY',
      email: 'bem@unklab.ac.id',
      registrationOpen: true,
      banner: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
    },
    {
      id: 3,
      nama: 'Pramuka UNKLAB',
      icon: '⛺',
      tagline: 'Gerakan Pramuka Universitas UNKLAB',
      kategori: 'Kerohanian',
      members: 150,
      deskripsi:
        'Organisasi kepramukaan yang mendidik karakter dan kepemimpinan melalui kegiatan outdoor.',
      visiMisi: {
        visi: 'Membentuk pemimpin muda yang berkarakter dan tangguh',
        misi: [
          'Mendidik melalui kegiatan outdoor',
          'Mengembangkan kepemimpinan',
          'Membangun disiplin dan tanggung jawab',
        ],
      },
      budaya: 'Disiplin, Tanggung jawab, Kebersamaan',
      programs: [
        { judul: 'Latihan Rutin', deskripsi: 'Latihan kepramukaan mingguan' },
        {
          judul: 'Kemping Tahunan',
          deskripsi: 'Kegiatan kemping besar-besaran',
        },
        {
          judul: 'Survival Training',
          deskripsi: 'Pelatihan ketahanan di alam',
        },
      ],
      events: [
        { judul: 'Jambore Nasional Pramuka', tanggal: '2024-08-10' },
        { judul: 'Latihan Dasar Pramuka Pemula', tanggal: '2024-03-20' },
      ],
      struktur: [
        { posisi: 'Ketua Kwartir', nama: 'Iwan Setiawan' },
        { posisi: 'Wakil Ketua', nama: 'Eka Putri' },
        { posisi: 'Sekretaris', nama: 'Ahmad Rizki' },
        { posisi: 'Bendahara', nama: 'Lia Margareta' },
      ],
      contact: '0821-ZZZZ-ZZZZ',
      email: 'pramuka@unklab.ac.id',
      registrationOpen: true,
      banner: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    },
    {
      id: 4,
      nama: 'UNISOC',
      icon: '🎭',
      tagline: 'Unit Seni & Olahraga Mahasiswa',
      kategori: 'Seni & Olahraga',
      members: 320,
      deskripsi:
        'Organisasi yang mengembangkan minat dan bakat mahasiswa di bidang seni dan olahraga.',
      visiMisi: {
        visi: 'Mengembangkan potensi seni dan olahraga mahasiswa UNKLAB',
        misi: [
          'Menyelenggarakan berbagai cabang olahraga dan seni',
          'Mengembangkan prestasi di tingkat regional',
          'Membangun semangat olahraga dan seni',
        ],
      },
      budaya: 'Semangat, Prestasi, Kebersamaan',
      programs: [
        {
          judul: 'Olahraga',
          deskripsi: 'Berbagai cabang olahraga (Futsal, Volley, Badminton)',
        },
        { judul: 'Seni', deskripsi: 'Tari tradisional dan modern, musik' },
        {
          judul: 'Turnamen Olahraga',
          deskripsi: 'Kompetisi olahraga antar fakultas',
        },
      ],
      events: [
        { judul: 'UNKLAB Games 2024', tanggal: '2024-05-01' },
        { judul: 'Pentas Seni Mahasiswa', tanggal: '2024-06-15' },
      ],
      struktur: [
        { posisi: 'Ketua', nama: 'Doni Hermawan' },
        { posisi: 'Wakil Ketua', nama: 'Yuni Hartono' },
        { posisi: 'Koordinator Olahraga', nama: 'Taufiq Maulana' },
        { posisi: 'Koordinator Seni', nama: 'Ayu Lestari' },
      ],
      contact: '0821-AAAA-AAAA',
      email: 'unisoc@unklab.ac.id',
      registrationOpen: false,
      banner: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
    },
    {
      id: 5,
      nama: 'IPNU-IPPNU',
      icon: '☪️',
      tagline: 'Ikatan Pelajar Nahdlatul Ulama',
      kategori: 'Kerohanian',
      members: 200,
      deskripsi:
        'Organisasi kemahasiswaan berbasis nilai-nilai Nahdlatul Ulama yang fokus pada dakwah dan pengabdian.',
      visiMisi: {
        visi: 'Menjadi organisasi yang mengintegrasikan nilai islam dalam kehidupan kampus',
        misi: [
          'Menyelenggarakan kegiatan dakwah yang konstruktif',
          'Mengembangkan karakter islami mahasiswa',
          'Melayani masyarakat dengan nilai-nilai islam',
        ],
      },
      budaya: 'Islami, Persatuan, Amanah',
      programs: [
        { judul: 'Kajian Rutin', deskripsi: 'Pengajian mingguan dan bulanan' },
        {
          judul: 'Pemberdayaan Masyarakat',
          deskripsi: 'Program sosial kemasyarakatan',
        },
        {
          judul: 'Pesantren Kilat',
          deskripsi: 'Pelatihan intensif nilai-nilai islam',
        },
      ],
      events: [
        { judul: 'Pesantren Ramadhan 2024', tanggal: '2024-03-01' },
        { judul: 'Peringatan Tahun Baru Islam', tanggal: '2024-07-07' },
      ],
      struktur: [
        { posisi: 'Ketua Umum', nama: 'Muhammad Nur' },
        { posisi: 'Wakil Ketua', nama: 'Fatimah Az-Zahra' },
        { posisi: 'Sekretaris', nama: 'Arif Rahman' },
        { posisi: 'Bendahara', nama: 'Nurul Hidayah' },
      ],
      contact: '0821-BBBB-BBBB',
      email: 'ipnuippnu@unklab.ac.id',
      registrationOpen: true,
      banner: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    },
    {
      id: 6,
      nama: 'HIMA SISTEM INFORMASI',
      icon: '📊',
      tagline: 'Himpunan Mahasiswa Sistem Informasi',
      kategori: 'Akademik',
      members: 180,
      deskripsi:
        'Organisasi akademik mahasiswa Sistem Informasi yang fokus pada pengembangan profesional di bidang SI.',
      visiMisi: {
        visi: 'Menjadi himpunan yang mendukung pengembangan profesional mahasiswa SI',
        misi: [
          'Menyelenggarakan seminar IT dan bisnis',
          'Memfasilitasi magang dan karir',
          'Mengembangkan profesionalisme mahasiswa SI',
        ],
      },
      budaya: 'Profesional, Inovatif, Supportif',
      programs: [
        {
          judul: 'Career Development',
          deskripsi: 'Program pengembangan karir',
        },
        { judul: 'Business Case Study', deskripsi: 'Studi kasus bisnis IT' },
        {
          judul: 'Networking with IT Companies',
          deskripsi: 'Networking dengan perusahaan IT',
        },
      ],
      events: [
        { judul: 'Business Intelligence Seminar', tanggal: '2024-04-10' },
        { judul: 'IT Career Expo 2024', tanggal: '2024-05-15' },
      ],
      struktur: [
        { posisi: 'Ketua Umum', nama: 'Bambang Wijaya' },
        { posisi: 'Wakil Ketua', nama: 'Sinta Wijaya' },
        { posisi: 'Sekretaris', nama: 'Randi Hermawan' },
        { posisi: 'Bendahara', nama: 'Lina Melisa' },
      ],
      contact: '0821-CCCC-CCCC',
      email: 'himasi@unklab.ac.id',
      registrationOpen: true,
      banner: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    },
  ]);

  // State
  const [searchQuery, setSearchQuery] = useState('');
  const [activeFilter, setActiveFilter] = useState('all');

  // Filter options
  const filterOptions = [
    { id: 'all', label: 'Semua' },
    { id: 'akademik', label: 'Akademik' },
    { id: 'seni & olahraga', label: 'Seni & Olahraga' },
    { id: 'kerohanian', label: 'Kerohanian' },
  ];

  // Filter dan search organisasi
  const filteredOrganisasi = useMemo(() => {
    let filtered = organisasiList;

    // Filter by kategori
    if (activeFilter !== 'all') {
      filtered = filtered.filter(
        (org) => org.kategori.toLowerCase() === activeFilter.toLowerCase()
      );
    }

    // Filter by search
    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      filtered = filtered.filter(
        (org) =>
          org.nama.toLowerCase().includes(q) ||
          org.tagline.toLowerCase().includes(q)
      );
    }

    return filtered;
  }, [activeFilter, searchQuery]);

  return (
    <div className="pt-20 pb-12">
      <div className="max-w-6xl mx-auto px-4">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-purple-700 mb-2">
            🛸 Unit & Organisasi Mahasiswa
          </h1>
          <p className="text-gray-600 text-lg">
            Jelajahi semua organisasi mahasiswa UNKLAB dan temukan yang sesuai
            dengan minatmu
          </p>
        </div>

        {/* Search & Filter */}
        <div className="space-y-4 mb-8">
          <SearchInput
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            placeholder="Cari organisasi..."
          />

          <div>
            <p className="text-sm font-semibold text-gray-700 mb-3">
              Kategori:
            </p>
            <FilterChips
              items={filterOptions}
              selected={activeFilter}
              onSelect={setActiveFilter}
            />
          </div>
        </div>

        {/* Organization Cards Grid */}
        {filteredOrganisasi.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredOrganisasi.map((org) => (
              <Card key={org.id} hover className="flex flex-col">
                <div className="flex-1 space-y-4">
                  {/* Icon & Title */}
                  <div>
                    <div className="text-5xl mb-3">{org.icon}</div>
                    <h3 className="text-xl font-bold text-gray-900">
                      {org.nama}
                    </h3>
                    <p className="text-sm text-gray-600 mt-1">{org.tagline}</p>
                  </div>

                  {/* Info */}
                  <div className="space-y-2 text-sm text-gray-700">
                    <div className="flex items-center gap-2">
                      <span className="text-lg">👥</span>
                      <span>{org.members} anggota aktif</span>
                    </div>
                    <div>
                      <Badge variant="purple" size="sm">
                        {org.kategori}
                      </Badge>
                    </div>
                  </div>

                  {/* Deskripsi */}
                  <p className="text-sm text-gray-600 line-clamp-2">
                    {org.deskripsi}
                  </p>
                </div>

                {/* Action Buttons */}
                <div className="space-y-2 mt-4 pt-4 border-t border-gray-200">
                  <button
                    onClick={() => navigate(`/organisasi/${org.id}`)}
                    className="w-full px-4 py-2 bg-purple-700 text-white rounded-xl hover:bg-purple-800 transition-colors font-semibold text-sm"
                  >
                    Lihat Detail
                  </button>
                  <button
                    disabled={!org.registrationOpen}
                    className={`w-full px-4 py-2 rounded-xl font-semibold text-sm transition-colors ${
                      org.registrationOpen
                        ? 'bg-yellow-400 text-gray-900 hover:bg-yellow-500'
                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    }`}
                  >
                    {org.registrationOpen ? '📝 Daftar' : 'Pendaftaran Tutup'}
                  </button>
                </div>
              </Card>
            ))}
          </div>
        ) : (
          <div className="text-center py-12">
            <div className="text-4xl mb-3">🔍</div>
            <p className="text-gray-600 text-lg">Organisasi tidak ditemukan</p>
          </div>
        )}
      </div>
    </div>
  );
}
