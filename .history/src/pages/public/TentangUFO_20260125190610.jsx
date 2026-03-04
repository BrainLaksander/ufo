import React from "react";

/**
 * Tentang UFO (About UFO) Page
 *
 * Halaman informasi tentang sistem UNKLAB Forum Organization
 * Menampilkan: deskripsi, visi misi, versi aplikasi
 */
export default function TentangUFO() {
    return (
        <div className="pt-20 pb-8">
            <div className="max-w-4xl mx-auto px-4">
                <div className="text-center mb-10">
                    <span className="text-6xl mb-4 inline-block">🛸</span>
                    <h1 className="text-4xl font-bold text-purple-700 mb-2">
                        Tentang UFO
                    </h1>
                    <p className="text-lg text-gray-600">
                        UNKLAB Forum Organization
                    </p>
                </div>

                <div className="space-y-8">
                    {/* Deskripsi */}
                    <section className="bg-white p-6 rounded-lg shadow">
                        <h2 className="text-2xl font-bold text-purple-700 mb-4">
                            Apa itu UFO?
                        </h2>
                        <p className="text-gray-700 leading-relaxed">
                            UNKLAB Forum Organization (UFO) adalah sistem
                            terintegrasi untuk forum dan manajemen organisasi
                            mahasiswa UNKLAB. UFO menyediakan platform bagi
                            mahasiswa untuk menemukan, bergabung, dan
                            berpartisipasi aktif dalam berbagai organisasi
                            kemahasiswaan serta mendapatkan informasi terkini
                            tentang event, pengumuman, dan aktivitas organisasi.
                        </p>
                    </section>

                    {/* Fitur Utama */}
                    <section className="bg-white p-6 rounded-lg shadow">
                        <h2 className="text-2xl font-bold text-purple-700 mb-4">
                            ✨ Fitur Utama
                        </h2>
                        <div className="grid md:grid-cols-2 gap-4">
                            <div className="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                                <h3 className="font-semibold text-purple-700 mb-2">
                                    👥 Direktori Organisasi
                                </h3>
                                <p className="text-sm text-gray-600">
                                    Temukan semua organisasi mahasiswa dengan
                                    deskripsi, kontak, dan info acara terbaru
                                </p>
                            </div>
                            <div className="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                                <h3 className="font-semibold text-purple-700 mb-2">
                                    📅 Event & Aktivitas
                                </h3>
                                <p className="text-sm text-gray-600">
                                    Lihat semua event organisasi yang akan
                                    datang dengan detail lengkap
                                </p>
                            </div>
                            <div className="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                                <h3 className="font-semibold text-purple-700 mb-2">
                                    📢 Pengumuman Resmi
                                </h3>
                                <p className="text-sm text-gray-600">
                                    Dapatkan informasi terbaru dari
                                    kemahasiswaan dan organisasi
                                </p>
                            </div>
                            <div className="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                                <h3 className="font-semibold text-purple-700 mb-2">
                                    🔍 Lost & Found
                                </h3>
                                <p className="text-sm text-gray-600">
                                    Laporkan barang hilang atau temukan barang
                                    yang ditemukan
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* Visi & Misi */}
                    <section className="bg-white p-6 rounded-lg shadow">
                        <h2 className="text-2xl font-bold text-purple-700 mb-4">
                            🎯 Visi & Misi
                        </h2>
                        <div className="space-y-4">
                            <div>
                                <h3 className="font-semibold text-lg text-purple-700 mb-2">
                                    Visi
                                </h3>
                                <p className="text-gray-700">
                                    Menjadi platform forum dan manajemen
                                    organisasi yang modern, terpercaya, dan
                                    memudahkan kolaborasi mahasiswa di UNKLAB.
                                </p>
                            </div>
                            <div>
                                <h3 className="font-semibold text-lg text-purple-700 mb-2">
                                    Misi
                                </h3>
                                <ul className="text-gray-700 space-y-2">
                                    <li>
                                        ✓ Menyediakan informasi lengkap tentang
                                        organisasi mahasiswa
                                    </li>
                                    <li>
                                        ✓ Memfasilitasi interaksi dan kolaborasi
                                        antar organisasi
                                    </li>
                                    <li>
                                        ✓ Meningkatkan transparansi dan
                                        akuntabilitas organisasi
                                    </li>
                                    <li>
                                        ✓ Mendukung pengembangan keterampilan
                                        mahasiswa melalui keterlibatan
                                        organisasi
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    {/* Versi & Info */}
                    <section className="bg-purple-100 p-6 rounded-lg shadow border-2 border-purple-300 text-center">
                        <h2 className="text-2xl font-bold text-purple-700 mb-4">
                            ℹ️ Informasi Aplikasi
                        </h2>
                        <div className="space-y-2 text-gray-700">
                            <p>
                                <strong>Nama Aplikasi:</strong> UNKLAB Forum
                                Organization (UFO)
                            </p>
                            <p>
                                <strong>Versi:</strong> 1.0
                            </p>
                            <p>
                                <strong>Tahun Rilis:</strong> 2024
                            </p>
                            <p>
                                <strong>Status:</strong> Beta Version
                            </p>
                            <p className="text-sm text-gray-600 mt-4">
                                Dikembangkan untuk mendukung pengelolaan
                                organisasi mahasiswa di UNKLAB
                            </p>
                        </div>
                    </section>

                    {/* Contact */}
                    <section className="bg-white p-6 rounded-lg shadow">
                        <h2 className="text-2xl font-bold text-purple-700 mb-4">
                            📧 Hubungi Kami
                        </h2>
                        <p className="text-gray-700 mb-4">
                            Untuk pertanyaan, masukan, atau laporan bug:
                        </p>
                        <div className="space-y-2 text-gray-700">
                            <p>
                                <strong>Email:</strong> ufo@unklab.ac.id
                            </p>
                            <p>
                                <strong>Tim Pengembang:</strong> Kemahasiswaan
                                UNKLAB
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    );
}
