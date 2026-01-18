import React from "react";

export default function ProfilOrganisasi() {
    // dummy state
    const org = {
        nama: "Himpunan Mahasiswa Contoh",
        ketua: "Budi",
        kontak: "hmc@example.test",
        deskripsi: "Organisasi mahasiswa yang aktif dalam kegiatan kampus.",
    };

    return (
        <div>
            <h2 className="text-2xl font-semibold">Profil Organisasi</h2>
            <div className="mt-4 bg-white p-4 rounded shadow">
                <p className="font-semibold">
                    Nama: <span className="font-normal">{org.nama}</span>
                </p>
                <p className="mt-2">Ketua: {org.ketua}</p>
                <p className="mt-2">Kontak: {org.kontak}</p>
                <p className="mt-3 text-gray-700">{org.deskripsi}</p>
            </div>
        </div>
    );
}
