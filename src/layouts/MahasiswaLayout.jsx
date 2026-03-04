<<<<<<< HEAD
import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from '../components/layout/Header';
import Sidebar from '../components/Sidebar';
import './MahasiswaLayout.css';

export default function MahasiswaLayout() {
  return (
    <div className="layout mahasiswa-layout">
      <Header role="mahasiswa" />
      <div className="layout-body">
        <aside className="layout-sidebar">
          <Sidebar role="mahasiswa" />
        </aside>
        <main className="layout-content">
          <Outlet />
        </main>
      </div>
    </div>
  );
=======
import React, { useState } from "react";
import { Outlet } from "react-router-dom";
import Header from "../components/layout/Header";
import StudentBurgerMenu from "../components/layout/StudentBurgerMenu";

/**
 * MahasiswaLayout Component
 *
 * Layout utama untuk halaman publik (mahasiswa / portal).
 * Menyediakan:
 * - Header dengan hamburger button
 * - Sidebar burger menu dengan design kuning/ungu
 * - Main content area
 */
export default function MahasiswaLayout() {
    // State untuk kontrol pembukaan/penutupan burger menu
    const [menuOpen, setMenuOpen] = useState(false);
    const [activeItem, setActiveItem] = useState(null);

    // Trigger pembukaan menu dari header hamburger button
    const handleMenuOpen = () => {
        setMenuOpen(true);
    };

    // Trigger penutupan menu
    const handleMenuClose = () => {
        setMenuOpen(false);
    };

    return (
        <div className="mahasiswa-layout">
            {/* Header dengan hamburger button */}
            <Header role="mahasiswa" onMenuClick={handleMenuOpen} />

            {/* Burger menu untuk mahasiswa */}
            <StudentBurgerMenu
                open={menuOpen}
                onClose={handleMenuClose}
                activeItem={activeItem}
            />

            {/* Main content area */}
            <main className="mahasiswa-layout-content">
                <Outlet />
            </main>
        </div>
    );
>>>>>>> b969c6653b3ab658934d1967de204238ba09c868
}
