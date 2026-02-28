import React, { useState } from 'react';

export default function ProfilOrganisasi() {
  const [isFavorite, setIsFavorite] = useState(false);

  // Dummy state
  const org = {
    id: 1,
    nama: 'Himpunan Mahasiswa Contoh',
    emoji: '🏛️',
    ketua: 'Budi Santoso',
    kontak: 'hmc@example.test',
    deskripsi:
      'Organisasi mahasiswa yang aktif dalam kegiatan kampus dan pengembangan keterampilan anggota.',
    kategori: 'Himpunan',
    jumlahAnggota: 245,
    eventPerTahun: 20,
    registrationOpen: true,
    divisions: [
      {
        id: 1,
        nama: 'Internal',
        deskripsi: 'Mengelola operasional organisasi',
      },
      {
        id: 2,
        nama: 'Event',
        deskripsi: 'Merencanakan dan melaksanakan acara',
      },
      {
        id: 3,
        nama: 'Akademik',
        deskripsi: 'Program pengembangan skill anggota',
      },
      {
        id: 4,
        nama: 'Humas',
        deskripsi: 'Komunikasi internal dan eksternal',
      },
    ],
    guidebookUrl: '#',
  };

  return (
    <div className="w-full bg-white min-h-screen">
      {/* Back Button & Header */}
      <div className="max-w-7xl mx-auto px-6 py-10">
        <a
          href="/organisasi"
          className="inline-flex items-center gap-2 text-gray-600 hover:text-[#663399] transition-colors duration-200 mb-6"
        >
          <span className="text-xl">←</span>
          <span className="font-medium">Kembali ke Daftar</span>
        </a>
      </div>

      {/* Main Content Container */}
      <div className="max-w-7xl mx-auto px-6 pb-12 space-y-6">
        {/* Header Card */}
        <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-all duration-200">
          {/* Logo & Title Section */}
          <div className="flex flex-col sm:flex-row sm:items-start sm:gap-8 mb-8">
            {/* Logo */}
            <div className="flex-shrink-0">
              <div className="w-24 h-24 rounded-2xl bg-[#663399] flex items-center justify-center shadow-sm">
                <span className="text-6xl">{org.emoji}</span>
              </div>
            </div>

            {/* Title & Info */}
            <div className="flex-1 mt-6 sm:mt-0">
              <h1 className="text-3xl font-bold text-gray-900 mb-2">
                {org.nama}
              </h1>
              <p className="text-gray-600 text-sm mb-4">{org.deskripsi}</p>

              {/* Category Badge */}
              <div className="flex flex-wrap gap-3 mb-6">
                <span className="inline-flex items-center px-3 py-1 bg-[#663399]/10 text-[#663399] rounded-full text-xs font-medium">
                  {org.kategori}
                </span>
              </div>

              {/* Stats */}
              <div className="grid grid-cols-2 gap-8">
                <div>
                  <p className="text-gray-500 text-xs font-medium mb-1">
                    Anggota
                  </p>
                  <p className="text-2xl font-bold text-[#663399]">
                    {org.jumlahAnggota}
                  </p>
                </div>
                <div>
                  <p className="text-gray-500 text-xs font-medium mb-1">
                    Event/Tahun
                  </p>
                  <p className="text-2xl font-bold text-[#663399]">
                    {org.eventPerTahun}
                  </p>
                </div>
              </div>
            </div>

            {/* Favorite Button */}
            <button
              onClick={() => setIsFavorite(!isFavorite)}
              className="flex-shrink-0 w-12 h-12 rounded-full bg-gray-100 hover:bg-gray-200 transition-all duration-200 flex items-center justify-center text-2xl focus:outline-none focus:ring-2 focus:ring-[#663399]/30"
              aria-label={
                isFavorite ? 'Remove from favorites' : 'Add to favorites'
              }
            >
              {isFavorite ? '❤️' : '🤍'}
            </button>
          </div>
        </div>

        {/* Registration Status Card */}
        {org.registrationOpen && (
          <div className="bg-green-50 border border-green-200 rounded-2xl p-8">
            <div className="flex items-start gap-4">
              <div className="flex-shrink-0">
                <div className="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-700 text-lg font-bold">
                  ✓
                </div>
              </div>
              <div className="flex-1">
                <h3 className="text-lg font-semibold text-gray-900 mb-1">
                  Pendaftaran Dibuka
                </h3>
                <p className="text-gray-600 text-sm mb-4">
                  Anda dapat mendaftar sebagai anggota organisasi ini sekarang
                  juga.
                </p>
                <button className="px-5 py-2.5 bg-[#663399] text-white font-medium text-sm rounded-xl hover:bg-[#5b2d86] transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#663399]/30">
                  Daftar Sekarang
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Contact & Leadership Info */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
          {/* Ketua Card */}
          <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <h3 className="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
              Ketua Organisasi
            </h3>
            <p className="text-lg font-semibold text-gray-900">{org.ketua}</p>
          </div>

          {/* Contact Card */}
          <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
            <h3 className="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
              Email Kontak
            </h3>
            <a
              href={`mailto:${org.kontak}`}
              className="text-lg font-semibold text-[#663399] hover:text-[#5b2d86] break-all transition-colors"
            >
              {org.kontak}
            </a>
          </div>
        </div>

        {/* Divisions Section */}
        <div className="border-t border-gray-200 pt-6">
          <h2 className="text-lg font-semibold text-gray-900 mb-6">
            Divisi Organisasi
          </h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {org.divisions.map((division) => (
              <div
                key={division.id}
                className="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-[#663399]/20 transition-all duration-200 group cursor-pointer"
              >
                <div className="flex items-start gap-3 mb-3">
                  <div className="flex-shrink-0 w-10 h-10 rounded-lg bg-[#663399]/10 flex items-center justify-center text-[#663399] font-bold">
                    ▶
                  </div>
                  <h3 className="text-lg font-semibold text-gray-900 group-hover:text-[#663399] transition-colors">
                    {division.nama}
                  </h3>
                </div>
                <p className="text-gray-600 text-sm mb-4">
                  {division.deskripsi}
                </p>
                <button className="text-[#663399] font-medium text-sm hover:text-[#5b2d86] transition-colors">
                  Pelajari Lebih Lanjut →
                </button>
              </div>
            ))}
          </div>
        </div>

        {/* Guidebook Section */}
        <div className="bg-[#663399] rounded-2xl shadow-sm p-10 text-white">
          <div className="max-w-2xl">
            <h2 className="text-2xl font-bold mb-3">📖 Panduan Organisasi</h2>
            <p className="text-[#e5d9f2] text-sm mb-6">
              Baca panduan lengkap tentang visi, misi, struktur organisasi, dan
              berbagai informasi penting lainnya.
            </p>
            <a
              href={org.guidebookUrl}
              download
              className="inline-block px-5 py-2.5 bg-white text-[#663399] font-medium text-sm rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#663399]"
            >
              Download Panduan (PDF)
            </a>
          </div>
        </div>
      </div>
    </div>
  );
}
