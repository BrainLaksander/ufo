import React from "react";

/**
 * Header Component
 * 
 * Header umum untuk semua layout (Mahasiswa, Pengurus, Admin, Kemahasiswaan)
 * Menampilkan:
 * - Hamburger button untuk trigger burger menu
 * - Brand/judul aplikasi
 * - Notification atau info lainnya
 * 
 * Props:
 * - role: string - peran user (mahasiswa, pengurus, admin, kemahasiswaan)
 * - onMenuClick: function - callback untuk trigger pembukaan menu
 */
export default function Header({ role, onMenuClick }) {
    // Determine header styling berdasarkan role
    const getHeaderClass = () => {
        const baseClass = "fixed top-0 left-0 right-0 z-40 shadow";
        
        switch (role) {
            case "pengurus":
                return `${baseClass} bg-gradient-to-r from-yellow-400 to-yellow-500`;
            case "admin":
                return `${baseClass} bg-gradient-to-r from-blue-500 to-blue-600`;
            case "kemahasiswaan":
                return `${baseClass} bg-gradient-to-r from-green-500 to-green-600`;
            case "mahasiswa":
            default:
                return `${baseClass} bg-gradient-to-r from-purple-700 to-purple-800 text-white`;
        }
    };

    const getTextClass = () => {
        return role === "mahasiswa" || role === "admin" || role === "kemahasiswaan" || role === "pengurus"
            ? "text-white"
            : "text-gray-800";
    };

    return (
        <header className={getHeaderClass()}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
                {/* Left side - Hamburger button and brand */}
                <div className="flex items-center gap-3">
                    <button
                        onClick={onMenuClick}
                        aria-label="Buka menu"
                        className={`p-2 rounded-md transition-colors ${
                            role === "mahasiswa" || role === "admin" || role === "kemahasiswaan"
                                ? "hover:bg-white hover:bg-opacity-20 text-white"
                                : "hover:bg-gray-100 text-gray-800"
                        }`}
                    >
                        {/* Hamburger icon */}
                        <svg
                            className="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>

                    {/* Brand/Judul */}
                    <div className="flex items-center gap-2">
                        <span className="text-xl">🛸</span>
                        <h1 className={`text-lg font-semibold ${getTextClass()}`}>
                            {role === "pengurus"
                                ? "Panel Pengurus"
                                : role === "admin"
                                ? "Admin Panel"
                                : role === "kemahasiswaan"
                                ? "Kemahasiswaan"
                                : "UFO Portal"}
                        </h1>
                    </div>
                </div>

                {/* Right side - Info atau notifikasi */}
                <div className="flex items-center gap-4">
                    <span className={`text-sm ${
                        role === "mahasiswa" || role === "admin" || role === "kemahasiswaan"
                            ? "text-white"
                            : "text-gray-600"
                    }`}>
                        Selamat datang
                    </span>
                </div>
            </div>
        </header>
    );
}

