import React from "react";

/**
 * HeaderBar Component
 *
 * Header bar untuk halaman publik/mahasiswa
 * Dengan burger menu button, logo, dan notification
 *
 * Props:
 * - onMenuClick (function): Callback saat hamburger diklik
 * - notificationCount (number): Jumlah notifikasi
 */
export default function HeaderBar({ onMenuClick, notificationCount = 0 }) {
    return (
        <header className="mahasiswa-header">
            <div className="mahasiswa-header-container">
                {/* Left: Burger Menu + Brand */}
                <div className="mahasiswa-header-left">
                    <button
                        className="mahasiswa-header-burger"
                        onClick={onMenuClick}
                        aria-label="Buka menu"
                    >
                        ☰
                    </button>
                    <div className="mahasiswa-header-brand">
                        <span className="mahasiswa-brand-icon">🛸</span>
                        <div>
                            <div className="mahasiswa-brand-title">UFO</div>
                            <div className="mahasiswa-brand-subtitle">
                                UNKLAB Forum Organization
                            </div>
                        </div>
                    </div>
                </div>

                {/* Right: Notification */}
                <div className="mahasiswa-header-right">
                    <button
                        className="mahasiswa-notification-btn"
                        aria-label="Notifikasi"
                    >
                        🔔
                        {notificationCount > 0 && (
                            <span className="notification-badge">
                                {notificationCount}
                            </span>
                        )}
                    </button>
                </div>
            </div>
        </header>
    );
}
