import React, { useState } from "react";
import { Link } from "react-router-dom";

/**
 * OrganizationCard Component
 * 
 * Card untuk menampilkan informasi organisasi
 * Dengan logo, nama, tagline, dan tombol detail
 * 
 * Props:
 * - id (number): ID organisasi
 * - logo (string): Emoji atau icon untuk logo
 * - name (string): Nama organisasi
 * - tagline (string): Tagline/deskripsi singkat
 * - link (string): Link ke detail organisasi
 */
export default function OrganizationCard({ id, logo = "👥", name, tagline, link }) {
    const [isFavorite, setIsFavorite] = useState(false);

    const handleFavoriteClick = (e) => {
        e.preventDefault();
        setIsFavorite(!isFavorite);
    };

    return (
        <div className="mahasiswa-org-card">
            {/* Card Header: Logo + Favorite Button */}
            <div className="org-card-header">
                <div className="org-card-logo">{logo}</div>
                <button
                    className="org-card-favorite"
                    onClick={handleFavoriteClick}
                    style={{ color: isFavorite ? "#cc0000" : "inherit" }}
                    aria-label={isFavorite ? "Hapus dari favorit" : "Tambah ke favorit"}
                >
                    {isFavorite ? "❤️" : "♡"}
                </button>
            </div>

            {/* Card Body: Name + Tagline */}
            <div className="org-card-body">
                <h3 className="org-card-name">{name}</h3>
                <p className="org-card-tagline">{tagline}</p>
            </div>

            {/* Card Button */}
            <Link to={link} className="org-card-btn">
                Lihat Detail
            </Link>
        </div>
    );
}
