import React, { useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import Card from '../../components/ui/Card';
import Badge from '../../components/ui/Badge';
import Dialog from '../../components/ui/Dialog';

/**
 * Organisasi Detail Page - Detail view organisasi mirip OrganisasiDetail
 *
 * Features:
 * - Banner gradient
 * - Logo/icon besar
 * - Info organisasi (visi misi, budaya, program)
 * - Event organisasi
 * - Struktur organisasi (read-only)
 * - Action buttons: Hubungi, Lihat Event, Daftar
 */

// Dummy data - sama dengan di Organisasi.jsx
const ORGANISASI_DATA = [
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
      {
        judul: 'UNKLAB Programming Competition 2024',
        tanggal: '2024-04-15',
        deskripsi: 'Kompetisi programming tahunan dengan hadiah menarik',
      },
      {
        judul: 'Tech Expo & Career Fair',
        tanggal: '2024-05-20',
        deskripsi: 'Pameran teknologi dan expo karir',
      },
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
      {
        judul: 'Freshmen Gathering 2024',
        tanggal: '2024-03-25',
        deskripsi: 'Acara pertemuan mahasiswa baru',
      },
      {
        judul: 'Pemilu Mahasiswa 2024',
        tanggal: '2024-04-01',
        deskripsi: 'Pemilihan umum raya mahasiswa',
      },
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
      { judul: 'Kemping Tahunan', deskripsi: 'Kegiatan kemping besar-besaran' },
      { judul: 'Survival Training', deskripsi: 'Pelatihan ketahanan di alam' },
    ],
    events: [
      {
        judul: 'Jambore Nasional Pramuka',
        tanggal: '2024-08-10',
        deskripsi: 'Jambore pramuka nasional',
      },
      {
        judul: 'Latihan Dasar Pramuka Pemula',
        tanggal: '2024-03-20',
        deskripsi: 'Pelatihan dasar untuk pramuka pemula',
      },
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
      {
        judul: 'UNKLAB Games 2024',
        tanggal: '2024-05-01',
        deskripsi: 'Kompetisi olahraga kampus tahunan',
      },
      {
        judul: 'Pentas Seni Mahasiswa',
        tanggal: '2024-06-15',
        deskripsi: 'Pertunjukan seni mahasiswa',
      },
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
      {
        judul: 'Pesantren Ramadhan 2024',
        tanggal: '2024-03-01',
        deskripsi: 'Pesantren kilat bulan ramadhan',
      },
      {
        judul: 'Peringatan Tahun Baru Islam',
        tanggal: '2024-07-07',
        deskripsi: 'Peringatan tahun baru islam',
      },
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
      { judul: 'Career Development', deskripsi: 'Program pengembangan karir' },
      { judul: 'Business Case Study', deskripsi: 'Studi kasus bisnis IT' },
      {
        judul: 'Networking with IT Companies',
        deskripsi: 'Networking dengan perusahaan IT',
      },
    ],
    events: [
      {
        judul: 'Business Intelligence Seminar',
        tanggal: '2024-04-10',
        deskripsi: 'Seminar business intelligence',
      },
      {
        judul: 'IT Career Expo 2024',
        tanggal: '2024-05-15',
        deskripsi: 'Pameran karir IT',
      },
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
];

export default function OrganisasiDetail() {
  const { id } = useParams();
  const navigate = useNavigate();

  // Find organisasi
  const organisasi = ORGANISASI_DATA.find((org) => org.id === parseInt(id));

  // Dialog state
  const [contactDialogOpen, setContactDialogOpen] = useState(false);
  const [registrationDialogOpen, setRegistrationDialogOpen] = useState(false);

  if (!organisasi) {
    return (
      <div className="pt-20 pb-12 text-center">
        <h1 className="text-2xl font-bold text-gray-900 mb-4">
          Organisasi tidak ditemukan
        </h1>
        <button
          onClick={() => navigate('/organisasi')}
          className="px-6 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800"
        >
          Kembali ke daftar organisasi
        </button>
      </div>
    );
  }

  return (
    <div className="pt-20 pb-12">
      {/* Banner */}
      <div
        className="h-40 sm:h-56 mb-8"
        style={{
          background: organisasi.banner,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        }}
      />

      <div className="max-w-5xl mx-auto px-4">
        {/* Hero Section dengan Logo */}
        <div className="flex flex-col sm:flex-row gap-6 mb-12 items-start">
          {/* Logo */}
          <div className="text-6xl sm:text-8xl flex-shrink-0">
            {organisasi.icon}
          </div>

          {/* Info */}
          <div className="flex-1">
            <h1 className="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
              {organisasi.nama}
            </h1>
            <p className="text-lg text-gray-600 mb-4">{organisasi.tagline}</p>

            <div className="flex flex-wrap gap-3 mb-6">
              <Badge variant="purple" size="md">
                {organisasi.kategori}
              </Badge>
              <div className="flex items-center gap-2 text-sm text-gray-700">
                <span className="text-lg">👥</span>
                <span>{organisasi.members} anggota aktif</span>
              </div>
            </div>

            <p className="text-gray-700 mb-6">{organisasi.deskripsi}</p>

            {/* Action Buttons */}
            <div className="flex flex-wrap gap-3">
              <button
                onClick={() => setContactDialogOpen(true)}
                className="px-6 py-2 bg-purple-700 text-white rounded-full hover:bg-purple-800 transition-colors font-semibold"
              >
                📞 Hubungi
              </button>
              <button
                onClick={() =>
                  window.scrollTo({
                    top:
                      document.querySelector('[data-events]')?.offsetTop || 0,
                    behavior: 'smooth',
                  })
                }
                className="px-6 py-2 bg-gray-100 text-gray-900 rounded-full hover:bg-gray-200 transition-colors font-semibold"
              >
                📅 Lihat Event
              </button>
              {organisasi.registrationOpen && (
                <button
                  onClick={() => setRegistrationDialogOpen(true)}
                  className="px-6 py-2 bg-yellow-400 text-gray-900 rounded-full hover:bg-yellow-500 transition-colors font-semibold"
                >
                  📝 Daftar
                </button>
              )}
            </div>
          </div>
        </div>

        {/* Visi & Misi */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
          <Card>
            <h2 className="text-2xl font-bold text-gray-900 mb-4">🎯 Visi</h2>
            <p className="text-gray-700 leading-relaxed">
              {organisasi.visiMisi.visi}
            </p>
          </Card>

          <Card>
            <h2 className="text-2xl font-bold text-gray-900 mb-4">🚀 Misi</h2>
            <ul className="space-y-2">
              {organisasi.visiMisi.misi.map((m, idx) => (
                <li key={idx} className="flex gap-2 text-gray-700">
                  <span className="text-purple-700 flex-shrink-0">✓</span>
                  <span>{m}</span>
                </li>
              ))}
            </ul>
          </Card>
        </div>

        {/* Budaya & Nilai */}
        <Card className="mb-12">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">
            💜 Budaya & Nilai
          </h2>
          <p className="text-lg text-gray-700">{organisasi.budaya}</p>
        </Card>

        {/* Program Unggulan */}
        <Card className="mb-12">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">
            ⭐ Program Unggulan
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {organisasi.programs.map((prog, idx) => (
              <div key={idx} className="border-l-4 border-purple-700 pl-4 py-2">
                <h3 className="font-semibold text-gray-900">{prog.judul}</h3>
                <p className="text-sm text-gray-600">{prog.deskripsi}</p>
              </div>
            ))}
          </div>
        </Card>

        {/* Event Organisasi */}
        <div data-events className="mb-12">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">
            📅 Event Organisasi
          </h2>
          <div className="space-y-4">
            {organisasi.events.length > 0 ? (
              organisasi.events.map((event, idx) => (
                <Card key={idx}>
                  <div className="flex items-start justify-between gap-4">
                    <div className="flex-1">
                      <h3 className="font-bold text-lg text-gray-900">
                        {event.judul}
                      </h3>
                      <p className="text-gray-600 mt-1">{event.deskripsi}</p>
                      <p className="text-sm text-gray-500 mt-2">
                        📅{' '}
                        {new Date(event.tanggal).toLocaleDateString('id-ID', {
                          weekday: 'long',
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric',
                        })}
                      </p>
                    </div>
                    <button className="px-4 py-2 bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-500 transition-colors font-semibold whitespace-nowrap">
                      Ikuti
                    </button>
                  </div>
                </Card>
              ))
            ) : (
              <p className="text-gray-600">
                Belum ada event yang dijadwalkan saat ini
              </p>
            )}
          </div>
        </div>

        {/* Struktur Organisasi */}
        <Card>
          <h2 className="text-2xl font-bold text-gray-900 mb-6">
            👥 Struktur Organisasi
          </h2>
          <div className="space-y-4">
            {organisasi.struktur.map((member, idx) => (
              <div
                key={idx}
                className="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
              >
                <div>
                  <p className="font-semibold text-gray-900">{member.posisi}</p>
                  <p className="text-sm text-gray-600">{member.nama}</p>
                </div>
                <div className="text-2xl">👤</div>
              </div>
            ))}
          </div>
        </Card>

        {/* Back Button */}
        <div className="mt-12 text-center">
          <button
            onClick={() => navigate('/organisasi')}
            className="px-6 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold"
          >
            ← Kembali
          </button>
        </div>
      </div>

      {/* Contact Dialog */}
      <Dialog
        open={contactDialogOpen}
        onClose={() => setContactDialogOpen(false)}
        title="Hubungi Organisasi"
        size="md"
      >
        <div className="space-y-4">
          <div>
            <p className="text-sm font-semibold text-gray-600 mb-1">
              Nama Organisasi
            </p>
            <p className="text-lg font-bold text-gray-900">{organisasi.nama}</p>
          </div>

          <div>
            <p className="text-sm font-semibold text-gray-600 mb-1">
              📞 Nomor Whatsapp
            </p>
            <a
              href={`https://wa.me/62${organisasi.contact.substring(1)}`}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-semibold text-sm"
            >
              💬 Chat di Whatsapp
            </a>
          </div>

          <div>
            <p className="text-sm font-semibold text-gray-600 mb-1">📧 Email</p>
            <a
              href={`mailto:${organisasi.email}`}
              className="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold text-sm"
            >
              ✉️ Kirim Email
            </a>
          </div>

          <div className="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400 mt-6">
            <p className="text-sm text-gray-700">
              <strong>💡 Tips:</strong> Hubungi organisasi melalui Whatsapp atau
              email untuk informasi lebih lanjut tentang pendaftaran dan
              kegiatan organisasi.
            </p>
          </div>
        </div>
      </Dialog>

      {/* Registration Dialog */}
      <Dialog
        open={registrationDialogOpen}
        onClose={() => setRegistrationDialogOpen(false)}
        title="Pendaftaran Anggota"
        size="md"
      >
        <div className="space-y-4">
          <p className="text-gray-700">
            Tertarik bergabung dengan <strong>{organisasi.nama}</strong>?
          </p>

          <p className="text-sm text-gray-600">
            Hubungi organisasi melalui Whatsapp atau email untuk mendaftar dan
            mendapatkan informasi tentang proses pendaftaran.
          </p>

          <div className="space-y-3 mt-6 pt-6 border-t border-gray-200">
            <a
              href={`https://wa.me/62${organisasi.contact.substring(1)}`}
              target="_blank"
              rel="noopener noreferrer"
              className="block w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-semibold text-center"
            >
              💬 Hubungi via Whatsapp
            </a>
            <a
              href={`mailto:${organisasi.email}`}
              className="block w-full px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold text-center"
            >
              ✉️ Hubungi via Email
            </a>
            <button
              onClick={() => setRegistrationDialogOpen(false)}
              className="block w-full px-4 py-3 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold"
            >
              Tutup
            </button>
          </div>
        </div>
      </Dialog>
    </div>
  );
}
