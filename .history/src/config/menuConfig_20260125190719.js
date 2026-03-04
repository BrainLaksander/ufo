/**
 * Menu Configuration File
 * 
 * File ini menyimpan konfigurasi menu untuk StudentBurgerMenu
 * Memudahkan untuk menambah/mengubah menu items tanpa edit JSX
 * 
 * Usage:
 * import { STUDENT_MENU_ITEMS } from './menuConfig';
 */

export const STUDENT_MENU_ITEMS = [
    {
        id: "organisasi",
        label: "Organisasi",
        icon: "👥",
        path: "/organisasi",
        description: "Jelajahi semua organisasi mahasiswa",
    },
    {
        id: "event",
        label: "Event",
        icon: "📅",
        path: "/event",
        description: "Lihat event dan aktivitas organisasi",
    },
    {
        id: "lostandfound",
        label: "Lost & Found",
        icon: "🔍",
        path: "/lostandfound",
        description: "Cari atau laporkan barang hilang/ditemukan",
    },
    {
        id: "pengumuman",
        label: "Pengumuman",
        icon: "📢",
        path: "/pengumuman",
        description: "Pengumuman resmi dari organisasi & kemahasiswaan",
    },
    {
        id: "tentang",
        label: "Tentang UFO",
        icon: "ℹ️",
        path: "/tentang-ufo",
        description: "Informasi tentang UFO dan versi aplikasi",
    },
];

/**
 * Aplikasi Info
 * Ditampilkan di footer StudentBurgerMenu
 */
export const APP_INFO = {
    name: "UNKLAB Forum Organization",
    shortName: "UFO",
    version: "1.0",
    year: "2024",
    status: "Beta Version",
    icon: "🛸",
};

/**
 * Style Configuration
 * Warna dan styling constants
 */
export const STYLE_CONFIG = {
    colors: {
        primary: "#663399", // Ungu gelap
        accent: "#ffcc00", // Kuning
        accentDark: "#ffb800", // Kuning gelap
        textPrimary: "#111",
        textSecondary: "#666",
        bgLight: "#f7f7fb",
        white: "#ffffff",
        overlay: "rgba(0, 0, 0, 0.45)",
    },
    sizing: {
        sidebarWidth: "280px",
        headerHeight: "56px",
        iconSize: "20px",
        closeButtonSize: "32px",
    },
    timing: {
        slideTransition: "0.28s cubic-bezier(0.4, 0, 0.2, 1)",
        fadeTransition: "0.3s ease-in-out",
        hoverTransition: "0.2s ease",
    },
    zIndex: {
        overlay: 95,
        sidebar: 96,
        header: 40,
    },
};

/**
 * Layout Configuration
 */
export const LAYOUT_CONFIG = {
    mahasiswa: {
        headerGradient: "from-purple-700 to-purple-800",
        headerTextColor: "text-white",
        headerRole: "mahasiswa",
        sidebarBg: "linear-gradient(135deg, #ffcc00 0%, #ffb800 100%)",
    },
    pengurus: {
        headerGradient: "from-yellow-400 to-yellow-500",
        headerTextColor: "text-white",
        headerRole: "pengurus",
        sidebarBg: "linear-gradient(135deg, #ffcc00 0%, #ffb800 100%)",
    },
    admin: {
        headerGradient: "from-blue-500 to-blue-600",
        headerTextColor: "text-white",
        headerRole: "admin",
        sidebarBg: "linear-gradient(135deg, #4A90E2 0%, #2E5C8A 100%)",
    },
    kemahasiswaan: {
        headerGradient: "from-green-500 to-green-600",
        headerTextColor: "text-white",
        headerRole: "kemahasiswaan",
        sidebarBg: "linear-gradient(135deg, #50C878 0%, #2D9966 100%)",
    },
};
