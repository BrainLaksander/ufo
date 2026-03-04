import React from "react";

/**
 * Lost & Found Page
 * 
 * Halaman untuk mencari dan melaporkan barang hilang/ditemukan
 * Menampilkan list item dengan status Lost/Found
 */
export default function LostAndFound() {
    const [items] = React.useState([
        {
            id: 1,
            name: "Dompet Hitam",
            status: "lost",
            date: "2024-01-20",
            location: "Aula Utama",
            description: "Dompet kulit hitam dengan inisial AB",
            contact: "Aldi Budiman",
        },
        {
            id: 2,
            name: "Kunci Gantungan Biru",
            status: "found",
            date: "2024-01-19",
            location: "Perpustakaan",
            description: "Gantungan kunci biru dengan logo kampus",
            contact: "Humas UFO",
        },
    ]);

    return (
        <div className="pt-20 pb-8">
            <div className="max-w-4xl mx-auto px-4">
                <h1 className="text-3xl font-bold text-purple-700 mb-6">
                    🔍 Lost & Found
                </h1>
                <p className="text-gray-600 mb-8">
                    Cari barang hilang Anda atau laporkan barang yang ditemukan
                </p>

                <div className="grid gap-4">
                    {items.map((item) => (
                        <div
                            key={item.id}
                            className={`p-4 rounded-lg border-2 ${
                                item.status === "lost"
                                    ? "border-red-300 bg-red-50"
                                    : "border-green-300 bg-green-50"
                            }`}
                        >
                            <div className="flex justify-between items-start">
                                <div>
                                    <h3 className="text-lg font-semibold">
                                        {item.name}
                                    </h3>
                                    <p className="text-sm text-gray-600">
                                        {item.description}
                                    </p>
                                    <p className="text-xs text-gray-500 mt-2">
                                        📍 {item.location} • {item.date}
                                    </p>
                                </div>
                                <span
                                    className={`px-3 py-1 rounded-full text-sm font-semibold ${
                                        item.status === "lost"
                                            ? "bg-red-200 text-red-800"
                                            : "bg-green-200 text-green-800"
                                    }`}
                                >
                                    {item.status === "lost" ? "HILANG" : "DITEMUKAN"}
                                </span>
                            </div>
                            <button className="mt-3 px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                                Hubungi {item.contact}
                            </button>
                        </div>
                    ))}
                </div>

                <button className="mt-8 px-6 py-3 bg-yellow-400 text-purple-700 font-semibold rounded-lg hover:bg-yellow-500 transition">
                    + Laporkan Barang Hilang/Ditemukan
                </button>
            </div>
        </div>
    );
}
