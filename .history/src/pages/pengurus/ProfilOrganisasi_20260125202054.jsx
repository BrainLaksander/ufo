import React, { useState } from "react";

export default function ProfilOrganisasi() {
    const [isFavorite, setIsFavorite] = useState(false);

    // Dummy state
    const org = {
        id: 1,
        nama: "Himpunan Mahasiswa Contoh",
        emoji: "🏛️",
        ketua: "Budi Santoso",
        kontak: "hmc@example.test",
        deskripsi: "Organisasi mahasiswa yang aktif dalam kegiatan kampus dan pengembangan keterampilan anggota.",
        kategori: "Himpunan",
        jumlahAnggota: 245,
        eventPerTahun: 20,
        registrationOpen: true,
        divisions: [
            { id: 1, nama: "Internal", deskripsi: "Mengelola operasional organisasi" },
            { id: 2, nama: "Event", deskripsi: "Merencanakan dan melaksanakan acara" },
            { id: 3, nama: "Akademik", deskripsi: "Program pengembangan skill anggota" },
            { id: 4, nama: "Humas", deskripsi: "Komunikasi internal dan eksternal" },
        ],
        guidebookUrl: "#",
    };

    return (
        <div className="w-full bg-gradient-to-b from-blue-50 to-white min-h-screen pb-12">
            {/* Back Button */}
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <a
                    href="/organisasi"
                    className="inline-flex items-center gap-2 text-gray-600 hover:text-purple-600 transition-colors duration-200 mb-6"
                >
                    <span className="text-xl">←</span>
                    <span className="font-medium">Kembali ke Daftar</span>
                </a>
            </div>

            {/* Main Container */}
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {/* Header Card */}
                <div className="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mb-8">
                    {/* Logo Section */}
                    <div className="flex flex-col sm:flex-row sm:items-start sm:gap-8 mb-8">
                        <div className="flex-shrink-0">
                            <div className="w-20 h-20 sm:w-24 sm:h-24 mx-auto sm:mx-0 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl flex items-center justify-center shadow-lg">
                                <span className="text-5xl sm:text-6xl">{org.emoji}</span>
                            </div>
                        </div>

                        {/* Title & Info */}
                        <div className="flex-1 mt-6 sm:mt-0">
                            <h1 className="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
                                {org.nama}
                            </h1>
                            <p className="text-gray-600 mb-4">{org.deskripsi}</p>
                            
                            {/* Category Badge */}
                            <div className="flex flex-wrap gap-3 mb-4">
                                <span className="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                                    {org.kategori}
                                </span>
                            </div>

                            {/* Stats */}
                            <div className="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p className="text-gray-600">Anggota</p>
                                    <p className="text-2xl font-bold text-purple-600">{org.jumlahAnggota}</p>
                                </div>
                                <div>
                                    <p className="text-gray-600">Event/Tahun</p>
                                    <p className="text-2xl font-bold text-purple-600">{org.eventPerTahun}</p>
                                </div>
                            </div>
                        </div>

                        {/* Favorite Button */}
                        <button
                            onClick={() => setIsFavorite(!isFavorite)}
                            className="flex-shrink-0 w-12 h-12 rounded-full bg-gray-100 hover:bg-purple-100 transition-colors duration-200 flex items-center justify-center text-2xl focus:outline-none focus:ring-2 focus:ring-purple-400"
                            aria-label={isFavorite ? "Remove from favorites" : "Add to favorites"}
                        >
                            {isFavorite ? "❤️" : "🤍"}
                        </button>
                    </div>
                </div>

                {/* Registration Status Card */}
                {org.registrationOpen && (
                    <div className="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 sm:p-8 mb-8">
                        <div className="flex items-start gap-4">
                            <div className="flex-shrink-0">
                                <div className="flex items-center justify-center h-12 w-12 rounded-full bg-green-500">
                                    <span className="text-xl">✓</span>
                                </div>
                            </div>
                            <div className="flex-1">
                                <h3 className="text-lg sm:text-xl font-bold text-gray-900 mb-2">
                                    Pendaftaran Dibuka
                                </h3>
                                <p className="text-gray-700 mb-4">
                                    Anda dapat mendaftar sebagai anggota organisasi ini sekarang.
                                </p>
                                <button className="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                    Daftar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                )}

                {/* Contact & Leadership */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    {/* Ketua */}
                    <div className="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 className="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">
                            Ketua Organisasi
                        </h3>
                        <p className="text-xl font-bold text-gray-900">{org.ketua}</p>
                    </div>

                    {/* Kontak */}
                    <div className="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 className="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">
                            Email Kontak
                        </h3>
                        <a
                            href={`mailto:${org.kontak}`}
                            className="text-xl font-bold text-purple-600 hover:text-purple-700 break-all"
                        >
                            {org.kontak}
                        </a>
                    </div>
                </div>

                {/* Divisions Section */}
                <div className="mb-8">
                    <h2 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">
                        Divisi Organisasi
                    </h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {org.divisions.map((division) => (
                            <div
                                key={division.id}
                                className="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg hover:border-purple-200 transition-all duration-200 group cursor-pointer"
                            >
                                <h3 className="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                    {division.nama}
                                </h3>
                                <p className="text-gray-600 text-sm">
                                    {division.deskripsi}
                                </p>
                                <div className="mt-4 pt-4 border-t border-gray-100">
                                    <button className="text-purple-600 font-semibold text-sm hover:text-purple-700 transition-colors">
                                        Pelajari Lebih Lanjut →
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Guidebook Section */}
                <div className="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl shadow-lg p-8 sm:p-10 text-white">
                    <div className="max-w-2xl">
                        <h2 className="text-2xl sm:text-3xl font-bold mb-3">
                            📖 Panduan Organisasi
                        </h2>
                        <p className="text-purple-100 mb-6">
                            Baca panduan lengkap tentang visi, misi, struktur organisasi, dan berbagai informasi penting lainnya.
                        </p>
                        <a
                            href={org.guidebookUrl}
                            download
                            className="inline-block px-6 py-3 bg-white text-purple-700 font-semibold rounded-lg hover:bg-purple-50 transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-purple-600"
                        >
                            Download Panduan (PDF)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    );
}
