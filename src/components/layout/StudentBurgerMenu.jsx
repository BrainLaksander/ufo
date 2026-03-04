import React from 'react';
import { Link } from 'react-router-dom';

/**
 * StudentBurgerMenu Component
 *
 * Sidebar/burger menu untuk mahasiswa dengan desain kuning (#FFD000) dan ungu gelap.
 * Menampilkan menu: Organisasi, Event, Lost & Found, Pengumuman, Tentang UFO
 *
 * Props:
 * - open: boolean - kontrol state terbuka/tertutup
 * - onClose: function - callback saat menu ditutup
 * - activeItem: string - item menu yang aktif (untuk highlight)
 */
export default function StudentBurgerMenu({
  open,
  onClose,
  activeItem = null,
}) {
  // Handle overlay click
  const handleOverlayClick = (e) => {
    if (e.target === e.currentTarget) {
      onClose();
    }
  };

  return (
    <>
      {/* Overlay - gelap (dimmed) di belakang sidebar */}
      {open && (
        <div
          className="student-menu-overlay"
          onClick={handleOverlayClick}
          role="presentation"
        />
      )}

      {/* Sidebar/Burger Menu - slide dari kiri */}
      <nav
        className={`student-burger-menu ${
          open ? 'student-burger-menu--open' : ''
        }`}
        role="navigation"
        aria-label="Menu UFO Mahasiswa"
      >
        {/* Header dengan icon, title, dan close button */}
        <div className="student-burger-header">
          <div className="student-burger-header-content">
            <span className="student-burger-icon">🛸</span>
            <span className="student-burger-title">Menu UFO</span>
          </div>
          <button
            className="student-burger-close"
            onClick={onClose}
            aria-label="Tutup menu"
          >
            ✕
          </button>
        </div>

        {/* Menu items */}
        <div className="student-burger-nav">
          <ul className="student-burger-list">
            {/* Menu: Organisasi */}
            <li className="student-burger-item">
              <Link
                to="/organisasi"
                onClick={onClose}
                className={`student-burger-link ${
                  activeItem === 'organisasi'
                    ? 'student-burger-link--active'
                    : ''
                }`}
              >
                <span className="student-burger-icon-item">👥</span>
                <span>Organisasi</span>
              </Link>
            </li>

            {/* Menu: Event */}
            <li className="student-burger-item">
              <Link
                to="/event"
                onClick={onClose}
                className={`student-burger-link ${
                  activeItem === 'event' ? 'student-burger-link--active' : ''
                }`}
              >
                <span className="student-burger-icon-item">📅</span>
                <span>Event</span>
              </Link>
            </li>

            {/* Menu: Lost & Found */}
            <li className="student-burger-item">
              <Link
                to="/lost-found"
                onClick={onClose}
                className={`student-burger-link ${
                  activeItem === 'lost-found'
                    ? 'student-burger-link--active'
                    : ''
                }`}
              >
                <span className="student-burger-icon-item">🔍</span>
                <span>Lost & Found</span>
              </Link>
            </li>

            {/* Menu: Pengumuman */}
            <li className="student-burger-item">
              <Link
                to="/pengumuman"
                onClick={onClose}
                className={`student-burger-link ${
                  activeItem === 'pengumuman'
                    ? 'student-burger-link--active'
                    : ''
                }`}
              >
                <span className="student-burger-icon-item">📢</span>
                <span>Pengumuman</span>
              </Link>
            </li>

            {/* Menu: Portal Internal (Admin) */}
            <li className="student-burger-item">
              <Link
                to="/portal/login"
                onClick={onClose}
                className={`student-burger-link ${
                  activeItem === 'portal' ? 'student-burger-link--active' : ''
                }`}
              >
                <span className="student-burger-icon-item">🔐</span>
                <span>Portal Internal</span>
              </Link>
            </li>

            {/* Menu: Tentang UFO */}
            <li className="student-burger-item">
              <Link
                to="/tentang-ufo"
                onClick={onClose}
                className={`student-burger-link ${
                  activeItem === 'tentang' ? 'student-burger-link--active' : ''
                }`}
              >
                <span className="student-burger-icon-item">ℹ️</span>
                <span>Tentang UFO</span>
              </Link>
            </li>
          </ul>
        </div>

        {/* Footer - Versi dan nama aplikasi */}
        <div className="student-burger-footer">
          <div className="student-burger-footer-title">
            UNKLAB Forum Organization
          </div>
          <div className="student-burger-footer-version">Versi 1.0</div>
        </div>
      </nav>
    </>
  );
}
